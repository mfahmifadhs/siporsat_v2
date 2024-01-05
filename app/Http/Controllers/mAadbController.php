<?php

namespace App\Http\Controllers;

use App\Model\submissionModel;
use App\Models\Aadb;
use App\Models\AadbPengguna;
use App\Models\Barang;
use App\Models\BarangPengguna;
use App\Models\KategoriAadb;
use App\Models\KategoriBarang;
use App\Models\Kondisi;
use App\Models\Pegawai;
use App\Models\Status;
use App\Models\UnitKerja;
use App\Models\Usulan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class mAadbController extends Controller
{
    public function index()
    {
        if (Auth::user()->role_id == 4) {
            $usulan = Usulan::whereIn('form_id', [101, 102, 103, 104])->where('user_id', Auth::user()->id)->get();
        } else {
            $usulan = Usulan::whereIn('form_id', [101, 102, 103, 104])->get();
        }

        return view('pages.modul.aadb.index', compact('usulan'));
    }

    public function selectKategori(Request $request)
    {
        $search = $request->search;

        if($search == ''){
            $aadb = KategoriAadb::orderBy('kategori_aadb','ASC')->get();
        }else{
            $aadb = KategoriAadb::orderBy('kategori_aadb','ASC')->where('kategori_aadb', 'like', '%' .$search . '%')->get();
        }

        $response = array();
        foreach($aadb as $data){
            $response[] = array(
                "id"    =>  $data->kode_kategori,
                "text"  =>  strtoupper($data->kode_kategori.' - '.$data->kategori_aadb)
            );
        }

        return response()->json($response);
    }

    public function selectKendaraan(Request $request)
    {
        $search = $request->search;

        if($search == ''){
            $aadb = Aadb::where('unit_kerja_id', Auth::user()->pegawai->unit_kerja_id);
        }else{
            $aadb = Aadb::where('unit_kerja_id', Auth::user()->pegawai->unit_kerja_id)->where('merk_tipe', 'like', '%' .$search . '%');
        }

        $result = $aadb->where('no_plat','!=','')->whereNotIn('status_id', [993,994])->orderBy('merk_tipe', 'ASC')->get();
        $response = array();
        foreach($result as $data){
            $response[] = array(
                "id"    =>  $data->id_kendaraan,
                "text"  =>  strtoupper($data->no_plat.' - '.$data->merk_tipe)
            );
        }

        return response()->json($response);
    }

    // =================================
    //              AADB
    // =================================
    public function showAadb(Request $request)
    {
        $ukerPick  = [];
        $aadbPick  = [];
        $data = Aadb::orderBy('t_aadb_kendaraan.created_at', 'DESC')
                ->join('t_aadb_kategori', 'kode_kategori', 'kategori_id')
                ->join('t_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->join('t_status', 'id_status', 'status_id')
                ->join('t_kondisi', 'id_kondisi', 'kondisi_id')
                ->select('kategori_aadb','nama_unit_kerja','nama_status','nama_kondisi','t_aadb_kendaraan.*');
        if ($request->unit_kerja || $request->kendaraan) {
            if ($request->unit_kerja) {
                $search   = $data->where('unit_kerja_id', $request->unit_kerja);
                $ukerPick = UnitKerja::where('id_unit_kerja', $request->unit_kerja)->first();
            }
            if ($request->kendaraan) {
                $search   = $data->where('kategori_id', $request->kendaraan);
                $aadbPick = KategoriAadb::where('kode_kategori', $request->kendaraan)->first();
            }
        }


        if (Auth::user()->role_id == 4 && Auth::user()->pegawai->unit_kerja_id != 465930) {
            $res = $data->where('unit_kerja_id', Auth::user()->pegawai->unit_kerja_id)->get();
        } else {
            $res = $data->get();
        }

        $aadb = json_decode($res);
        return view('pages.modul.aadb.kendaraan.show', compact('aadb','ukerPick','aadbPick'));
    }

    public function detailAadb($id)
    {
        $aadb    = Aadb::where('id_kendaraan', $id)->first();
        $pegawai = Pegawai::orderBy('nama_pegawai','ASC')->get();
        $status  = Status::where('kategori_status','user')->get();
        return view('pages.modul.aadb.kendaraan.detail', compact('aadb','pegawai','status'));
    }

    public function createAadb()
    {
        $uker     = UnitKerja::where('unit_utama_id', 46593)->get();
        $kategori = KategoriAadb::get();
        $kondisi  = Kondisi::get();
        $status   = Status::get();
        return view('pages.modul.aadb.kendaraan.create', compact('uker','kategori','kondisi','status'));
    }

    public function storeAadb(Request $request)
    {
        $id = str_pad(Aadb::withTrashed()->count() + 1, 8, 0, STR_PAD_LEFT);
        $nilai = str_replace(".", "", $request->nilai_perolehan);
        $nilai = (int) $nilai;

        $tambah = new Aadb();
        $tambah->id_kendaraan       = $id;
        $tambah->unit_kerja_id      = $request->unit_kerja_id;
        $tambah->kategori_id        = $request->kategori;
        $tambah->jenis_aadb         = $request->jenis_aadb;
        $tambah->kualifikasi        = $request->kualifikasi;
        $tambah->merk_tipe          = $request->merktipe;
        $tambah->tahun              = $request->tahun;
        $tambah->no_plat            = strtoupper($request->no_plat);
        $tambah->no_plat_dinas      = strtoupper($request->no_plat_dinas);
        $tambah->tanggal_stnk       = $request->tanggal_stnk;
        $tambah->tanggal_perolehan  = $request->tanggal_perolehan;
        $tambah->nilai_perolehan    = $nilai;
        $tambah->keterangan         = $request->keterangan;
        $tambah->no_bpkb            = $request->no_bpkb;
        $tambah->no_rangka          = $request->no_rangka;
        $tambah->no_mesin           = $request->no_mesin;
        $tambah->kondisi_id         = $request->kondisi;
        $tambah->status_id          = $request->status;
        $tambah->created_at         = Carbon::now();
        $tambah->save();

        if ($request->foto_kendaraan) {
            $file  = $request->file('foto_kendaraan');
            $filename = $file->getClientOriginalName();
            $foto = $file->storeAs('public/files/foto_kendaraan', $filename);
            Aadb::where('id_kendaraan', $id)->update(['foto_kendaraan' => $filename]);
        }

        return redirect()->route('aadb.kendaraan.detail', $id)->with('success', 'Berhasil Menambah Kendaraan');
    }

    public function editAadb($id)
    {
        $aadb     = Aadb::where('id_kendaraan', $id)->first();
        $uker     = UnitKerja::where('unit_utama_id', 46593)->get();
        $kategori = KategoriAadb::get();
        $kondisi  = Kondisi::get();
        $status   = Status::get();
        return view('pages.modul.aadb.kendaraan.edit', compact('aadb','uker','kategori','kondisi','status'));
    }

    public function updateAadb(Request $request, $id)
    {
        $aadb  = Aadb::where('id_kendaraan', $id)->first();
        $nilai = str_replace(".", "", $request->nilai_perolehan);
        $nilai = (int) $nilai;

        Aadb::where('id_kendaraan', $id)->update([
            'unit_kerja_id'      => $request->unit_kerja_id,
            'kategori_id'        => $request->kategori,
            'jenis_aadb'         => $request->jenis_aadb,
            'kualifikasi'        => $request->kualifikasi,
            'merk_tipe'          => $request->merktipe,
            'tahun'              => $request->tahun,
            'no_plat'            => strtoupper($request->no_plat),
            'no_plat_dinas'      => strtoupper($request->no_plat_dinas),
            'tanggal_stnk'       => $request->tanggal_stnk,
            'tanggal_perolehan'  => $request->tanggal_perolehan,
            'nilai_perolehan'    => $nilai,
            'keterangan'         => $request->keterangan,
            'no_bpkb'            => $request->no_bpkb,
            'no_rangka'          => $request->no_rangka,
            'no_mesin'           => $request->no_mesin,
            'kondisi_id'         => $request->kondisi,
            'status_id'          => $request->status,
            'created_at'         => Carbon::now()
        ]);

        if ($request->foto_kendaraan) {
            $file  = $request->file('foto_kendaraan');
            $filename = $file->getClientOriginalName();
            $foto = $file->storeAs('public/files/foto_kendaraan', $filename);
            $fotoKendaraan = $filename;

            if ($aadb->foto_kendaraan) {
                Storage::delete('public/files/foto_kendaraan/' . $aadb->foto_kendaraan);
            }

            Aadb::where('id_kendaraan', $id)->update(['foto_kendaraan' => $fotoKendaraan]);
        }

        return redirect()->route('aadb.kendaraan.detail', $id)->with('success', 'Berhasil Menyimpan Perubahan');
    }

    public function deleteAadb($id)
    {
        $aadb = Aadb::where('id_kendaraan', $id)->first();
        Storage::delete('public/files/foto_kendaraan/' . $aadb->foto_kendaraan);
        Aadb::where('id_kendaraan', $aadb->id_kendaraan)->delete();

        return redirect()->route('aadb.kendaraan.show')->with('success', 'Berhasil Menghapus Kendaraan');
    }

    public function processPengguna(Request $request, $id)
    {
        if ($id == '*') {
            $id_kendaraan = $request->kendaraan;
            $id_pengguna  = str_pad(AadbPengguna::withTrashed()->count() + 1, 8, 0, STR_PAD_LEFT);
            $tambah = new AadbPengguna();
            $tambah->id_pengguna      = $id_pengguna;
            $tambah->tanggal_pengguna = $request->tanggal;
            $tambah->kendaraan_id     = $request->kendaraan;
            $tambah->pegawai_id       = $request->pegawai;
            $tambah->keterangan       = $request->keterangan;
            $tambah->status_id        = $request->status;
            $tambah->created_at       = Carbon::now();
            $tambah->save();
        } elseif ($request->aksi == 'update') {
            $pengguna     = AadbPengguna::where('id_pengguna', $id)->first();
            $id_kendaraan = $pengguna->kendaraan_id;
            AadbPengguna::where('id_pengguna', $id)->update([
                'tanggal_pengguna'  => $request->tanggal,
                'kendaraan_id'      => $request->kendaraan,
                'pegawai_id'        => $request->pegawai,
                'keterangan'        => $request->keterangan,
                'status_id'         => $request->status
            ]);
        } else {
            $id_kendaraan = $request->kendaraan;
            AadbPengguna::where('id_pengguna', $id)->delete();
        }


        return redirect()->route('aadb.kendaraan.detail', $id_kendaraan)->with('success', 'Berhasil Memproses');
    }


    // =================================
    //          KATEGORI BARANG
    // =================================

    public function showKategori()
    {
        $kategori = KategoriAadb::orderBy('created_at', 'DESC')->get();
        return view('pages.modul.aadb.kategori.show', compact('kategori'));
    }

    public function createKategori()
    {
        return view('pages.modul.aadb.kategori.create');
    }

    public function editKategori($id)
    {
        $kategori = KategoriAadb::where('id_kategori', $id)->first();
        return view('pages.modul.aadb.kategori.edit', compact('kategori', 'id'));
    }

    public function storeKategori(Request $request)
    {
        $tambah = new KategoriAadb();
        $tambah->kode_kategori = $request->kode_kategori;
        $tambah->kategori_aadb = strtoupper($request->kategori_aadb);
        $tambah->created_at    = Carbon::now();
        $tambah->save();

        return redirect()->route('aadb.kategori.show')->with('success', 'Berhasil menambahkan');
    }

    public function updateKategori(Request $request, $id)
    {
        KategoriAadb::where('id_kategori', $id)->update([
            'kode_kategori' => $request->kode_kategori,
            'kategori_aadb' => strtoupper($request->kategori_aadb),
        ]);

        return redirect()->route('aadb.kategori.show')->with('success', 'Berhasil menyimpan perubahan');
    }

    public function deleteKategori($id)
    {
        KategoriAadb::where('id_kategori', $id)->delete();

        return redirect()->route('aadb.kategori.show')->with('success', 'Berhasil menghapus');
    }
}
