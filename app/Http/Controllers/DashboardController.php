<?php

namespace App\Http\Controllers;

use App\Model\submissionModel;
use App\Models\Status;
use App\Models\Usulan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;

class DashboardController extends Controller
{
    public function index() {
        $tahun = ['2024', '2023'];
        $data  = Usulan::join('t_pegawai','id_pegawai','pegawai_id')
                ->select(DB::raw("DATE_FORMAT(t_usulan.tanggal_usulan, '%Y') as tahun"), 't_usulan.*');

        if (Auth::user()->role_id == 4) {
            $usulan = $data->where('user_id', Auth::user()->id)->get();
        } else {
            $usulan = $data->get();
        }

        $status = Status::whereIn('id_status', [101,102,103,105,106])->get();
        return view('pages.dashboard', compact('status','usulan','tahun'))->with('success', 'Selamat Datang');
    }
}
