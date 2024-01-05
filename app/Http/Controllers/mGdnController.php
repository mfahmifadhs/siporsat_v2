<?php

namespace App\Http\Controllers;

use App\Model\submissionModel;
use App\Models\BidangPerbaikan;
use App\Models\KategoriBarang;
use App\Models\Usulan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;

class mGdnController extends Controller
{
    public function index() {
        if (Auth::user()->role_id == 4) {
            $usulan = Usulan::where('form_id', 401)
                        ->where('user_id', Auth::user()->id)
                        ->where(DB::raw("DATE_FORMAT(t_usulan.tanggal_usulan, '%Y')"), 2024)
                        ->get();
        } else {
            $usulan = Usulan::where('form_id', 401)
                        ->where(DB::raw("DATE_FORMAT(t_usulan.tanggal_usulan, '%Y')"), 2024)
                        ->get();
        }

        return view('pages.modul.gdn.index', compact('usulan'));
    }

    public function showBidangPerbaikan(Request $request) {
        $bPerbaikan = BidangPerbaikan::where('jenis_bperbaikan', $request->jenis_perbaikan)->get();
        $response = array();
        foreach ($bPerbaikan as $data) {
            $response[] = array(
                "id"     =>  $data->id_bperbaikan,
                "text"   =>  ucwords(strtolower($data->bidang_perbaikan))
            );
        }

        return response()->json($response);
    }
}
