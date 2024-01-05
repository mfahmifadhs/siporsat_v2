<?php

namespace App\Http\Controllers;

use App\Models\Atk;
use App\Models\MataAnggaran1;
use App\Models\MataAnggaran4;
use App\Models\Status;
use App\Models\UnitKerja;
use App\Models\Usulan;
use App\Models\UsulanRealisasi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;

class AnggaranController extends Controller
{
    public function showAlokasi()
    {
        $unitKerja = UnitKerja::where('unit_utama_id', 4)->get();
        return view('pages.anggaran.alokasi_anggaran.show', compact('unitKerja'));
    }

    public function detailAlokasi($id)
    {
        $mta1 = MataAnggaran1::get();
        $unitKerja = UnitKerja::where('kode_unit_kerja', $id)->first();
        return view('pages.anggaran.alokasi_anggaran.detail', compact('mta1','unitKerja'));
    }

    public function showRealisasi($form, $ukerId)
    {
        $realisasi = UsulanRealisasi::select('usulan_id', DB::raw('sum(nilai_realisasi) as nilai_realisasi'))
            ->join('t_usulan','id_usulan','usulan_id')
            ->join('t_pegawai','id_pegawai','pegawai_id')
            ->groupBy('usulan_id')
            ->where('unit_kerja_id', $ukerId)
            ->get();

        return view('pages.anggaran.realisasi.show', compact('form','realisasi'));
    }

    public function createRealisasi($form, $id)
    {
        $usulan = Usulan::where('id_usulan', $id)->first();
        $status = Status::where('kategori_status', 'usulan')->get();
        $mta4   = MataAnggaran4::get();
        $atk    = Atk::orderBy('deskripsi','asc')->get();

        if ($form == 'atk') {
            return view('pages.usulan.atk.deliver', compact('form','id','usulan','status','atk'));
        } else {
            return view('pages.usulan.realisasi.create', compact('form','id','mta4','usulan','status'));
        }
    }

    public function storeRealisasi(Request $request, $form, $id)
    {
        $realisasi = $request->mta_kode;
        foreach ($realisasi as $i => $mta_kode) {
            if ($request->nilai_realisasi[$i]) {
                $nilai = str_replace(".", "", $request->nilai_realisasi[$i]);
                $nilai = (int) $nilai;

                $realisasi = new UsulanRealisasi();
                $realisasi->usulan_id = $id;
                $realisasi->mta_kode        = $mta_kode;
                $realisasi->mta_deskripsi   = $request->mta_deskripsi[$i];
                $realisasi->nilai_realisasi = $nilai;
                $realisasi->jenis_realisasi = $request->jenis_realisasi[$i];
                $realisasi->keterangan      = $request->keterangan[$i];
                $realisasi->created_at      = $request->mta_tanggal[$i];
                $realisasi->save();
            }
        }

        return redirect()->route('usulan.verif.create', ['form' => $form, 'id' => $id]);
        // return redirect()->route('usulan.detail', ['form' => $form, 'id' => $id])->with('success', 'Berhasil Memproses Usulan');
    }

    public function editRealisasi ($form, $id)
    {
        $usulan    = Usulan::where('id_usulan', $id)->first();
        $status    = Status::where('kategori_status', 'usulan')->get();
        $mta4      = MataAnggaran4::get();

        return view('pages.usulan.realisasi.edit', compact('form','id','mta4','usulan','status'));
    }

    public function updateRealisasi(Request $request, $form, $id)
    {
        $realisasi = $request->realisasi_id;
        foreach ($realisasi as $i => $id_realisasi) {
            $nilai = str_replace(".", "", $request->nilai_realisasi[$i]);
            $nilai = (int) $nilai;

            if (!$id_realisasi) {
                $detail = new UsulanRealisasi();
                $detail->usulan_id       = $id;
                $detail->mta_kode        = $request->mta_kode[$i];
                $detail->mta_deskripsi   = $request->mta_deskripsi[$i];
                $detail->nilai_realisasi = $nilai;
                $detail->jenis_realisasi = $request->jenis_realisasi[$i];
                $detail->keterangan      = $request->keterangan[$i];
                $detail->created_at      = $request->mta_tanggal[$i];
                $detail->save();
            } else {
                UsulanRealisasi::where('id_realisasi', $id_realisasi)->update([
                    'usulan_id'       => $id,
                    'mta_kode'        => $request->mta_kode[$i],
                    'mta_deskripsi'   => $request->mta_deskripsi[$i],
                    'nilai_realisasi' => $nilai,
                    'jenis_realisasi' => $request->jenis_realisasi[$i],
                    'keterangan'      => $request->keterangan[$i],
                    'created_at'      => $request->mta_tanggal[$i],
                    'deleted_at'      => $request->hapus[$i] ? Carbon::now() : null,
                ]);
            }
        }

        return redirect()->route('usulan.detail', ['form' => $form, 'id' => $id])->with('success', 'Berhasil Menyimpan Perubahan');
    }

    public function deleteRealisasi ($form, $id)
    {
        $realisasi = UsulanRealisasi::where('id_realisasi', $id)->first();
        UsulanRealisasi::where('id_realisasi', $id)->delete();
        return redirect()->route('usulan.detail', ['form' => $form, 'id' => $realisasi->usulan_id])->with('success', 'Berhasil Menghapus Realisasi');
    }
}
