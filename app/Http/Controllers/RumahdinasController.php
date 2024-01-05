<?php

namespace App\Http\Controllers;

use App\Models\RumahDinas;
use App\Models\RumahDinasFoto;
use App\Models\RumahDinasPenghuni;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RumahdinasController extends Controller
{
    public function showRumah()
    {
        $rumah = RumahDinas::get();
        return view('pages.modul.rumah_dinas.show', compact('rumah'));
    }

    public function detailRumah($id)
    {
        $rumah = RumahDinas::where('id_rumah', $id)->first();
        return view('pages.modul.rumah_dinas.detail', compact('rumah'));
    }

    public function createRumah()
    {
        return view('pages.modul.rumah_dinas.create');
    }

    public function editRumah($id)
    {
        $rumah = RumahDinas::where('id_rumah', $id)->first();

        return view('pages.modul.rumah_dinas.edit', compact('rumah'));
    }

    public function storeRumah(Request $request)
    {
        $tambah = new RumahDinas();
        $tambah->golongan       = $request->golongan;
        $tambah->alamat         = $request->alamat;
        $tambah->lokasi_kota    = $request->lokasi_kota;
        $tambah->luas_bangunan  = $request->luas_bangunan;
        $tambah->luas_tanah     = $request->luas_tanah;
        $tambah->kondisi        = $request->kondisi;
        $tambah->save();

        $lastId = RumahDinas::orderBy('id_rumah', 'DESC')->first();

        $foto = $request->file('foto_rumah');

        if ($foto) {
            foreach ($foto as $fileFoto) {
                $path = $fileFoto->store('rumah_dinas', 'public');
                RumahDinasFoto::create([
                    'rumah_id'  => $lastId->id_rumah,
                    'nama_file' => $path,
                ]);
            }
        }

        return redirect()->route('rumah_dinas.show')->with('success', 'Berhasil menambah data');
    }

    public function updateRumah(Request $request, $id)
    {
        RumahDinas::where('id_rumah', $id)->update([
            'golongan'      => $request->golongan,
            'alamat'        => $request->alamat,
            'lokasi_kota'   => $request->lokasi_kota,
            'luas_bangunan' => $request->luas_bangunan,
            'luas_tanah'    => $request->luas_tanah,
            'kondisi'       => $request->kondisi
        ]);

        return redirect()->route('rumah_dinas.edit', $id)->with('success', 'Berhasil mengubah data');
    }

    public function deleteRumah($id)
    {
        RumahDinas::where('id_rumah',$id)->delete();

        return redirect()->route('rumah_dinas.show')->with('success', 'Berhasil menghapus data');
    }

    public function createPenghuni($id)
    {
        $rumah = RumahDinas::where('id_rumah', $id)->first();
        return view('pages.modul.rumah_dinas.penghuni.create', compact('rumah'));
    }

    public function storePenghuni(Request $request, $id)
    {
        $tambah = new RumahDinasPenghuni();
        $tambah->rumah_id        = $id;
        $tambah->pegawai_id      = $request->pegawai_id;
        $tambah->nomor_sip       = $request->nomor_sip;
        $tambah->sertifikat      = $request->sertifikat;
        $tambah->pbb             = $request->pbb;
        $tambah->imb             = $request->imb;
        $tambah->tanggal_masuk   = $request->tanggal_masuk;
        $tambah->tanggal_keluar  = $request->tanggal_keluar;
        $tambah->status_penghuni = $request->status_penghuni;
        $tambah->created_at      = Carbon::now();
        $tambah->save();

        return redirect()->route('rumah_dinas.detail', $id)->with('success', 'Berhasil menambah penghuni baru');
    }

    public function editPenghuni($id)
    {
        $penghuni = RumahDinasPenghuni::where('id_penghuni', $id)->first();
        return view('pages.modul.rumah_dinas.penghuni.edit', compact('penghuni'));
    }

    public function updatePenghuni(Request $request, $id)
    {
        RumahDinasPenghuni::where('id_penghuni', $id)->update([
            'pegawai_id'      => $request->pegawai_id,
            'nomor_sip'       => $request->nomor_sip,
            'sertifikat'      => $request->sertifikat,
            'pbb'             => $request->pbb,
            'imb'             => $request->imb,
            'tanggal_masuk'   => $request->tanggal_masuk,
            'tanggal_keluar'  => $request->tanggal_keluar,
            'status_penghuni' => $request->status_penghuni
        ]);

        return redirect()->route('rumah_dinas.detail', $request->rumah_id)->with('success', 'Berhasil mengubah data');
    }


    public function editFoto($id)
    {
        $rumah = RumahDinas::where('id_rumah', $id)->first();
        $foto  = RumahDinasFoto::where('rumah_id', $id)->get();
        return view('pages.modul.rumah_dinas.foto.edit', compact('rumah','foto'));
    }

    public function updateFoto(Request $request, $id)
    {
        $foto = $request->file('foto_rumah');
        foreach ($foto as $i => $fileFoto) {
            $fotoRumah = RumahDinasFoto::where('id_foto', $request->id_foto[$i])
                ->where('rumah_id', $id)
                ->first();
            if ($fotoRumah) {
                // Hapus foto yang lama dari storage
                Storage::disk('public')->delete($fotoRumah->nama_file);

                // Simpan foto yang baru ke storage
                $path = $fileFoto->store('rumah_dinas', 'public');

                // Update nama file foto di database
                RumahDinasFoto::where('id_foto', $request->id_foto[$i])->update([
                    'nama_file'    => $path
                ]);
            }

        }

        return redirect()->route('rumah_dinas.detail', $id)->with('success', 'Berhasil mengubah data');
    }

    public function deleteFoto($id)
    {
        $foto = RumahDinasFoto::where('id_foto', $id)->first();
        RumahDinasFoto::where('id_foto',$id)->delete();

        return redirect()->route('rumah_dinas.detail', $foto->rumah_id)->with('success', 'Berhasil menghapus data');
    }

    public function exportRumah()
    {
        return Excel::download(new YourDataExport, 'data.xlsx');
    }


}
