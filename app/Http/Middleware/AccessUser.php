<?php

namespace App\Http\Middleware;

use App\Model\submissionModel;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccessUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $status)
    {
        if(Auth::user() == null){
            return redirect('/')->with('failed','Anda tidak memiliki akses!');
        }

        $role = Auth::user()->role_id;
        $position = Auth::user()->pegawai->jabatan_id;

        if ($status == 'public')
        {
            if ($role == 1 || $role == 2 || $role == 3) {
                return $next($request);
            } else {
                return back()->with('failed','Anda tidak memiliki akses!');
            }
        }

        if ($status == 'admin')
        {
            if ($role == 1 || $role == 2 || $role == 3) {
                return $next($request);
            } else {
                return back()->with('failed','Anda tidak memiliki akses!');
            }
        }

        if ($status == 'private')
        {
            if ($role == 1) {
                return $next($request);
            } else {
                return back()->with('failed','Anda tidak memiliki akses!');
            }
        }

        if ($status == 'verify')
        {
            $submission = submissionModel::where('id_pengajuan', $request->route('id'))->first();
            if ($role == 3 && $submission->status_pengajuan_id == null) {
                return $next($request);
            } else {
                return back()->with('failed','Anda tidak memiliki akses!');
            }
        }

        if ($status == 'process')
        {
            $submission = submissionModel::where('id_pengajuan', $request->route('id'))->first();
            if ($role == 2 && $submission->status_proses_id == 3) {
                return $next($request);
            } else {
                return back()->with('failed','Anda tidak memiliki akses!');
            }
        }

        if ($status == 'user')
        {
            if ($role == 4) {
                return $next($request);
            } else {
                return back()->with('failed','Anda tidak memiliki akses!');
            }
        }

        if($status == 'psedia')
        {
            if ($position == 12) {
                return $next($request);
            } else {
                return back()->with('failed','Anda tidak memiliki akses!');
            }
        }



        return $next($request);
    }
}
