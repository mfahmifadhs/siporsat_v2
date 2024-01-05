<?php

namespace App\Http\Controllers;

use App\Model\submissionModel;
use App\Models\UnitUtama;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UnitutamaController extends Controller
{
    public function show()
    {
        $unitUtama = UnitUtama::get();
        return view('pages.unit_utama.show', compact('unitUtama'));
    }

    public function create()
    {
        return view('pages.unit_utama.create');
    }

    public function edit ($id)
    {
        $unitUtama = UnitUtama::where('id_unit_utama', $id)->first();
        return view('pages.unit_utama.edit', compact('unitUtama'));
    }

    public function store(Request $request)
    {
        $tambah = new UnitUtama();
        $tambah->kode_unit_utama = $request->kode_unit_utama;
        $tambah->nama_unit_utama = $request->nama_unit_utama;
        $tambah->save();

        return redirect()->route('unit_utama.show')->with('success', 'Berhasil Menambah Baru');
    }

    public function update(Request $request, $id)
    {
        UnitUtama::where('id_unit_utama', $id)->update([
            'kode_unit_utama' => $request->kode_unit_utama,
            'nama_unit_utama' => $request->nama_unit_utama
        ]);

        return redirect()->route('unit_utama.edit', $id)->with('success', 'Berhasil Memperbaharui');
    }

    public function delete($id)
    {
        UnitUtama::where('id_unit_utama',$id)->delete();

        return redirect()->route('unit_utama.show')->with('success', 'Berhasil Menghapus');
    }
}
