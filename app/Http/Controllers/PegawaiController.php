<?php

namespace App\Http\Controllers;

use App\Model\submissionModel;
use App\Models\Pegawai;
use App\Models\PegawaiJabatan;
use App\Models\Status;
use App\Models\UnitKerja;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PegawaiController extends Controller
{
    public function show()
    {
        $pegawai = Pegawai::get();
        return view('pages.pegawai.show', compact('pegawai'));
    }

    public function selectPegawai($id)
    {
        $result = Pegawai::where('unit_kerja_id', $id)->get();
        return response()->json($result);
    }

    public function create()
    {
        $unitKerja = UnitKerja::orderBy('nama_unit_kerja','ASC')->get();
        $status    = Status::where('kategori_status','pegawai')->get();
        $jabatan   = PegawaiJabatan::get();
        return view('pages.pegawai.create', compact('unitKerja','status','jabatan'));
    }

    public function edit($id)
    {
        $unitKerja = UnitKerja::orderBy('nama_unit_kerja','ASC')->get();
        $pegawai   = Pegawai::where('id_pegawai', $id)->first();
        $jabatan   = PegawaiJabatan::get();
        $status    = Status::where('kategori_status','pegawai')->get();

        return view('pages.pegawai.edit', compact('unitKerja','pegawai','jabatan','status'));
    }

    public function store(Request $request)
    {
        $pegawai   = Pegawai::count();
        $format    = str_pad($pegawai + 1, 3, 0, STR_PAD_LEFT);
        $idPegawai = Carbon::now()->isoFormat('DDMMYY').$format;


        $tambah = new Pegawai();
        $tambah->id_pegawai        = $idPegawai;
        $tambah->unit_kerja_id     = $request->unit_kerja_id;
        $tambah->nip               = $request->nip;
        $tambah->nama_pegawai      = $request->nama_pegawai;
        $tambah->jabatan_id        = $request->jabatan_id;
        $tambah->nama_jabatan      = $request->nama_jabatan;
        $tambah->status_id         = $request->status_id;
        $tambah->save();

        return redirect()->route('pegawai.show')->with('success', 'Berhasil Menambah Baru');
    }

    public function update(Request $request, $id)
    {
        Pegawai::where('id_pegawai', $id)->update([
            'unit_kerja_id' => $request->unit_kerja_id,
            'nip'           => $request->nip,
            'nama_pegawai'  => $request->nama_pegawai,
            'jabatan_id'    => $request->jabatan_id,
            'nama_jabatan'  => $request->nama_jabatan,
            'status_id'     => $request->status_id
        ]);

        if ($request->nip) {
            User::where('pegawai_id', $id)->update([
                'username'   => $request->nip
            ]);
        }

        return redirect()->route('pegawai.edit', $id)->with('success', 'Berhasil Memperbaharui');
    }

    public function delete($id)
    {
        Pegawai::where('id_pegawai',$id)->delete();

        return redirect()->route('pegawai.show')->with('success', 'Berhasil Menghapus');
    }
}
