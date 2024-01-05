<?php

namespace App\Http\Controllers;

use App\Model\submissionModel;
use App\Models\BeritaAcara;
use App\Models\Status;
use App\Models\Usulan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BeritaAcaraController extends Controller
{
    public function index() {


    }

    public function detail($form, $id)
    {
        $bast = BeritaAcara::where('id_bast', $id)->first();
        $status = Status::where('kategori_status', 'usulan')->get();
        return view ('pages.usulan.bast.detail', compact('form','id','bast','status'));
    }
}
