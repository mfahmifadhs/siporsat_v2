<?php

namespace App\Http\Controllers;

use App\Model\submissionModel;
use App\Models\MataAnggaran;
use App\Models\MataAnggaran1;
use App\Models\MataAnggaran2;
use App\Models\MataAnggaran3;
use App\Models\MataAnggaran4;
use App\Models\MataAnggaranKategori;
use App\Models\NilaiAnggaran;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MataAnggaranController extends Controller
{
    public function show()
    {
        $mta1 = MataAnggaran1::get();
        return view('pages.anggaran.mata_anggaran.show', compact('mta1'));
    }

    public function create($id)
    {
        return view('pages.anggaran.mata_anggaran.create', compact('id'));
    }

    public function edit($ctg, $id)
    {
        if ($ctg == 1) {

            $mta = MataAnggaran1::where('id_mta_1', $id)
                    ->select('id_mta_1 as id_mta', 'kode_mta_1 as kode_mta', 'nama_mta_1 as nama_mta')
                    ->first();

        } elseif ($ctg == 2) {

            $mta = MataAnggaran2::where('id_mta_2', $id)
                    ->select('id_mta_2 as id_mta', 't_mta_2.kode_mta_1 as id_kode_mta', 'kode_mta_2 as kode_mta','nama_mta_2 as nama_mta',
                             't_mta_1.nama_mta_1 as nama_mta_id', 't_mta_1.id_mta_1 as mta_id','t_mta_1.kode_mta_1 as kode_mta_id')
                    ->join('t_mta_1', 'id_mta_1', 't_mta_2.kode_mta_1')
                    ->first();

        } elseif ($ctg == 3) {

            $mta = MataAnggaran3::where('id_mta_3', $id)
                    ->select('id_mta_3 as id_mta', 't_mta_3.kode_mta_2 as id_kode_mta', 'kode_mta_3 as kode_mta','nama_mta_3 as nama_mta',
                            't_mta_2.nama_mta_2 as nama_mta_id', 't_mta_2.id_mta_2 as mta_id','t_mta_2.kode_mta_2 as kode_mta_id')
                    ->join('t_mta_2', 'id_mta_2', 't_mta_3.kode_mta_2')
                    ->first();

        } elseif ($ctg == 4) {

            $mta = MataAnggaran4::where('id_mta_4', $id)
                    ->select('id_mta_4 as id_mta', 't_mta_4.kode_mta_3 as id_kode_mta', 'kode_mta_4 as kode_mta','nama_mta_4 as nama_mta',
                            't_mta_3.nama_mta_3 as nama_mta_id', 't_mta_3.id_mta_3 as mta_id','t_mta_3.kode_mta_3 as kode_mta_id')
                    ->join('t_mta_3', 'id_mta_3', 't_mta_4.kode_mta_3')
                    ->first();

        } elseif ($ctg == 'ctg') {

            $mta = MataAnggaranKategori::where('id_mta_ctg', $id)
                    ->select('id_mta_ctg as id_mta', 'kode_mta_ctg as kode_mta','nama_mta_ctg as nama_mta')
                    ->first();

        }


        $id = $ctg;
        return view('pages.anggaran.mata_anggaran.edit', compact('id', 'mta'));
    }

    public function store(Request $request, $id)
    {
        if ($id == 1) {
            $tambah = new MataAnggaran1();
            $tambah->kode_mta_1 = strtoupper($request->kode_mta);
            $tambah->nama_mta_1 = strtoupper($request->nama_mta);
            $tambah->created_at = Carbon::now();
            $tambah->save();
        }

        if ($id == 2) {
            $tambah = new MataAnggaran2();
            $tambah->kode_mta_1 = $request->mta_id;
            $tambah->kode_mta_2 = strtoupper($request->kode_mta);
            $tambah->nama_mta_2 = strtoupper($request->nama_mta);
            $tambah->created_at = Carbon::now();
            $tambah->save();
        }

        if ($id == 3) {
            $tambah = new MataAnggaran3();
            $tambah->kode_mta_2 = $request->mta_id;
            $tambah->kode_mta_3 = strtoupper($request->kode_mta);
            $tambah->nama_mta_3 = strtoupper($request->nama_mta);
            $tambah->created_at = Carbon::now();
            $tambah->save();
        }

        if ($id == 4) {
            $tambah = new MataAnggaran4();
            $tambah->kode_mta_3 = $request->mta_id;
            $tambah->kode_mta_4 = strtoupper($request->kode_mta);
            $tambah->nama_mta_4 = strtoupper($request->nama_mta);
            $tambah->created_at = Carbon::now();
            $tambah->save();
        }

        if ($id == 'ctg') {
            $cek    = MataAnggaranKategori::where('kode_mta_ctg', $request->kode_mta)->count();
            if ($cek == 1) {
                return redirect()->route('mata_anggaran.show')->with('failed', 'Kategori Mata Anggaran Sudah Terdaftar');
            }

            $tambah = new MataAnggaranKategori();
            $tambah->kode_mta_ctg = strtoupper($request->kode_mta);
            $tambah->nama_mta_ctg = strtoupper($request->nama_mta);
            $tambah->created_at = Carbon::now();
            $tambah->save();
        }

        if ($id == '*') {
            $tambah = new MataAnggaran();
            $tambah->unit_kerja_id = $request->unit_kerja_id;
            $tambah->kode_mta_4    = $request->mta_id;
            $tambah->kode_mta_ctg  = $request->mta_ctg_id;
            $tambah->kode_mta      = strtoupper($request->kode_mta);
            $tambah->nama_mta      = strtoupper($request->nama_mta);
            $tambah->created_at    = Carbon::now();
            $tambah->save();
        }

        return redirect()->route('mata_anggaran.show')->with('success', 'Berhasil Menambah Mata Anggaran');
    }

    public function update(Request $request, $ctg, $id)
    {
        if ($ctg == 1) {
            MataAnggaran1::where('id_mta_1', $id)->update([
                'kode_mta_1'    => strtoupper($request->kode_mta),
                'nama_mta_1'    => strtoupper($request->nama_mta),
            ]);
        }

        if ($ctg == 2) {
            MataAnggaran2::where('id_mta_2', $id)->update([
                'kode_mta_1'    => strtoupper($request->mta_id),
                'kode_mta_2'    => strtoupper($request->kode_mta),
                'nama_mta_2'    => strtoupper($request->nama_mta),
            ]);
        }

        if ($ctg == 3) {
            MataAnggaran3::where('id_mta_3', $id)->update([
                'kode_mta_2'    => strtoupper($request->mta_id),
                'kode_mta_3'    => strtoupper($request->kode_mta),
                'nama_mta_3'    => strtoupper($request->nama_mta),
            ]);
        }

        if ($ctg == 4) {
            MataAnggaran4::where('id_mta_4', $id)->update([
                'kode_mta_3'    => strtoupper($request->mta_id),
                'kode_mta_4'    => strtoupper($request->kode_mta),
                'nama_mta_4'    => strtoupper($request->nama_mta),
            ]);
        }

        if ($ctg == 'ctg') {
            MataAnggaranKategori::where('id_mta_ctg', $id)->update([
                'kode_mta_ctg'    => strtoupper($request->kode_mta),
                'nama_mta_ctg'    => strtoupper($request->nama_mta),
            ]);
        }

        return redirect()->route('mata_anggaran.show')->with('success', 'Berhasil Menyimpan Perubahan');
    }



    public function selectMataAnggaran(Request $request, $id)
    {
        $search = $request->search;

        if ($id == 2) {
            if($search == ''){
                $mta = MataAnggaran1::select('id_mta_1 as id_mta', 'kode_mta_1 as kode_mta', 'nama_mta_1 as nama_mta')
                    ->orderBy('kode_mta_1','ASC')->get();
            }else{
                $mta = MataAnggaran1::select('id_mta_1 as id_mta', 'kode_mta_1 as kode_mta', 'nama_mta_1 as nama_mta')
                    ->orderBy('kode_mta_1','ASC')->where('nama_mta_1', 'like', '%' .$search . '%')->get();
            }
        }

        if ($id == 3) {
            if($search == ''){
                $mta = MataAnggaran2::select('id_mta_2 as id_mta', 'kode_mta_2 as kode_mta', 'nama_mta_2 as nama_mta')
                    ->orderBy('kode_mta_2','ASC')->get();
            }else{
                $mta = MataAnggaran2::select('id_mta_2 as id_mta', 'kode_mta_2 as kode_mta', 'nama_mta_2 as nama_mta')
                    ->orderBy('kode_mta_2','ASC')->where('nama_mta_2', 'like', '%' .$search . '%')->get();
            }
        }

        if ($id == 4) {
            if($search == ''){
                $mta = MataAnggaran3::select('id_mta_3 as id_mta', 'kode_mta_3 as kode_mta', 'nama_mta_3 as nama_mta')
                    ->orderBy('kode_mta_3','ASC')->get();
            }else{
                $mta = MataAnggaran3::select('id_mta_3 as id_mta', 'kode_mta_3 as kode_mta', 'nama_mta_3 as nama_mta')
                    ->orderBy('kode_mta_3','ASC')->where('nama_mta_3', 'like', '%' .$search . '%')->get();
            }
        }

        if ($id == '*') {
            if($search == ''){
                $mta = MataAnggaran4::select('id_mta_4 as id_mta', 'kode_mta_4 as kode_mta', 'nama_mta_4 as nama_mta')
                    ->orderBy('kode_mta_4','ASC')->get();
            }else{
                $mta = MataAnggaran4::select('id_mta_4 as id_mta', 'kode_mta_4 as kode_mta', 'nama_mta_4 as nama_mta')
                    ->orderBy('kode_mta_4','ASC')->where('nama_mta_4', 'like', '%' .$search . '%')->get();
            }
        }

        $response = array();
        foreach($mta as $data){
            $response[] = array(
                "id"    =>  $data->id_mta,
                "text"  =>  strtoupper($data->kode_mta.'.'.$data->nama_mta)
            );
        }

        return response()->json($response);
    }


    public function selectKategoriAnggaran(Request $request, $id)
    {
        $search = $request->search;
        if($search == ''){
            $mta = MataAnggaranKategori::select('id_mta_ctg as id_mta', 'kode_mta_ctg as kode_mta', 'nama_mta_ctg as nama_mta')
                ->orderBy('kode_mta_ctg','ASC')->get();
        }else{
            $mta = MataAnggaranKategori::select('id_mta_ctg as id_mta', 'kode_mta_ctg as kode_mta', 'nama_mta_ctg as nama_mta')
                ->orderBy('kode_mta_ctg','ASC')->where('nama_mta_ctg', 'like', '%' .$search . '%')->get();
           }

        $response = array();
        foreach($mta as $data){
            $response[] = array(
                "id"    =>  $data->id_mta,
                "text"  =>  strtoupper($data->kode_mta.'.'.$data->nama_mta)
            );
        }

        return response()->json($response);
    }


}
