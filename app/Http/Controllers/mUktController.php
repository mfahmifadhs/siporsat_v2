<?php

namespace App\Http\Controllers;

use App\Model\submissionModel;
use App\Models\KategoriBarang;
use App\Models\Usulan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;

class mUktController extends Controller
{
    public function index() {
        if (Auth::user()->role_id == 4) {
            $usulan = Usulan::where('form_id', 501)
                        ->where('user_id', Auth::user()->id)
                        ->where(DB::raw("DATE_FORMAT(t_usulan.tanggal_usulan, '%Y')"), 2024)
                        ->get();
        } else {
            $usulan = Usulan::where('form_id', 501)
                        ->where(DB::raw("DATE_FORMAT(t_usulan.tanggal_usulan, '%Y')"), 2024)
                        ->get();
        }

        return view('pages.modul.ukt.index', compact('usulan'));
    }
}
