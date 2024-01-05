<?php

namespace App\Http\Controllers;

use App\Model\submissionModel;
use App\Models\Barang;
use App\Models\BarangPengguna;
use App\Models\KategoriBarang;
use App\Models\Pegawai;
use App\Models\Status;
use App\Models\Uni2024tKerja;
use App\Models\Usulan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use DB;

class mOldatController extends Controller
{
    public function index() {
        if (Auth::user()->role_id == 4) {
            $usulan = Usulan::whereIn('form_id', [201, 202])
                        ->where('user_id', Auth::user()->id)
                        ->where(DB::raw("DATE_FORMAT(t_usulan.tanggal_usulan, '%Y')"), 2024)
                        ->get();
        } else {
            $usulan = Usulan::whereIn('form_id', [201, 202])
                        ->where(DB::raw("DATE_FORMAT(t_usulan.tanggal_usulan, '%Y')"), 2024)
                        ->get();
        }

        return view('pages.modul.oldat.index', compact('usulan'));
    }

    public function selectKategori(Request $request) {
        $search = $request->search;

        if($search == ''){
            $oldat = KategoriBarang::orderBy('kategori_barang','ASC')->get();
        }else{
            $oldat = KategoriBarang::orderBy('kategori_barang','ASC')->where('kategori_barang', 'like', '%' .$search . '%')->get();
        }

        $response = array();
        foreach($oldat as $data){
            $response[] = array(
                "id"    =>  $data->kode_kategori,
                "text"  =>  strtoupper($data->kode_kategori.' - '.$data->kategori_barang)
            );
        }

        return response()->json($response);
    }

    public function selectBarang(Request $request) {
        $kategori = KategoriBarang::where('id_kategori', $request->jenis_barang)->first();
        $barang   = Barang::where('kategori_id', $kategori->kode_kategori)
                    ->where('unit_kerja_id', Auth::user()->pegawai->unitKerja->id_unit_kerja)
                    ->get();

        $response = array();
        foreach ($barang as $data) {
            $response[] = array(
                "id"     =>  $data->id_barang,
                "text"   =>  $data->kategori_id.'.'.$data->nup.' - '.$data->merk_tipe
            );
        }
        return response()->json($response);
    }

    // =================================
    //          BARANG
    // =================================

    public function showBarang()
    {
        $ukerPick  = [];
        $oldatPick = [];
        $char = '"';
        $data = Barang::orderBy('t_oldat_barang.created_at', 'DESC')
                ->join('t_oldat_kategori', 'kode_kategori', 'kategori_id')
                ->join('t_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->select('kategori_barang','nama_unit_kerja','t_oldat_barang.*');

        if (Auth::user()->role_id == 4) {
            $res = $data->where('unit_kerja_id', Auth::user()->pegawai->unit_kerja_id)->get();
        } else {
            $res = $data->get();
        }

        $barang = json_decode($res);
        return view('pages.modul.oldat.barang.show', compact('barang','ukerPick','oldatPick'));
    }

    public function filterBarang(Request $request)
    {
        $ukerPick  = [];
        $oldatPick = [];
        $char       = '"';
        $unitKerja  = UnitKerja::orderBy('nama_unit_kerja','ASC');
        $kategori   = KategoriBarang::orderBy('kategori_barang','ASC');
        $data = Barang::orderBy('t_oldat_barang.created_at', 'DESC')
                ->join('t_oldat_kategori', 'kode_kategori', 'kategori_id')
                ->join('t_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->select('kategori_barang','nama_unit_kerja','t_oldat_barang.*');

        if($request->unit_kerja || $request->barang) {
            if ($request->unit_kerja) {
                $search     = $data->where('id_unit_kerja', $request->unit_kerja);
                $uker       = $unitKerja->get();
                $ukerPick   = UnitKerja::where('id_unit_kerja', $request->unit_kerja)->first();
            } else { $uker  = $unitKerja->get(); }

            if ($request->barang) {
                $search      = $data->where('kategori_id', $request->barang);
                $kategori    = $kategori->get();
                $oldatPick   = KategoriBarang::where('kode_kategori', $request->barang)->first();
            } else { $kategori = $kategori->get(); }

        } else {
            $search = $data;
        }

        if (Auth::user()->role_id == 4) {
            $res = $search->where('unit_kerja_id', Auth::user()->pegawai->unit_kerja_id)->get();
        } else {
            $res = $search->get();
        }

        $barang = json_decode($res);
        return view('pages.modul.oldat.barang.show', compact('barang','ukerPick','oldatPick'));

    }

    public function detailBarang($id)
    {
        $barang  = Barang::where('id_barang', $id)->first();
        $pegawai = Pegawai::orderBy('nama_pegawai','ASC')->get();
        $status  = Status::where('kategori_status','user')->get();
        return view('pages.modul.oldat.barang.detail', compact('barang','pegawai','status'));
    }

    public function createBarang()
    {
        $uker     = UnitKerja::where('unit_utama_id', 46593)->get();
        $kategori = KategoriBarang::orderBy('kategori_barang','ASC')->get();
        return view('pages.modul.oldat.barang.create', compact('uker','kategori'));
    }

    public function storeBarang(Request $request)
    {
        $id = str_pad(Barang::withTrashed()->count() + 1, 8, 0, STR_PAD_LEFT);
        $nilai = str_replace(".", "", $request->nilai);
        $nilai = (int) $nilai;

        $tambah = new Barang();
        $tambah->id_barang = $id;
        $tambah->unit_kerja_id   = $request->unit_kerja_id;
        $tambah->kategori_id     = $request->kategori_id;
        $tambah->nup             = $request->nup;
        $tambah->merk_tipe       = $request->merktipe;
        $tambah->spesifikasi     = $request->spesifikasi;
        $tambah->nilai_perolehan = $nilai;
        $tambah->tahun_perolehan = $request->tanggal;
        $tambah->kondisi_id      = $request->kondisi;
        $tambah->status_id       = $request->status;
        $tambah->created_at      = Carbon::now();
        $tambah->save();

        if ($request->foto_barang) {
            $file  = $request->file('foto_barang');
            $filename = $file->getClientOriginalName();
            $foto = $file->storeAs('public/files/foto_barang', $filename);
            Barang::where('id_barang', $id)->update(['foto_barang' => $filename]);
        }

        return redirect()->route('oldat.barang.detail', $id)->with('success', 'Berhasil Menambah Barang');
    }

    public function editBarang($id)
    {
        $barang   = Barang::where('id_barang', $id)->first();
        $merktipe = Barang::where('kategori_id', $barang->kategori_id)->get();
        $uker     = UnitKerja::where('unit_utama_id', 46593)->get();
        $kategori = KategoriBarang::orderBy('kategori_barang','ASC')->get();

        return view('pages.modul.oldat.barang.edit', compact('barang','uker','kategori'));
    }

    public function updateBarang(Request $request, $id)
    {
        $barang = Barang::where('id_barang', $id)->first();
        $nilai  = str_replace(".", "", $request->nilai);
        $nilai  = (int) $nilai;

        Barang::where('id_barang', $id)->update([
            'unit_kerja_id'   => $request->unit_kerja_id,
            'kategori_id'     => $request->kategori_id,
            'nup'             => $request->nup,
            'merk_tipe'       => $request->merktipe,
            'spesifikasi'     => $request->spesifikasi,
            'nilai_perolehan' => $nilai,
            'tahun_perolehan' => $request->tanggal,
            'kondisi_id'      => $request->kondisi,
            'status_id'       => $request->status
        ]);

        if ($request->foto_barang) {
            $file  = $request->file('foto_barang');
            $filename = $file->getClientOriginalName();
            $foto = $file->storeAs('public/files/foto_barang', $filename);
            $fotoBarang = $filename;

            if ($barang->foto_barang) {
                Storage::delete('public/files/foto_barang/' . $barang->foto_barang);
            }

            Barang::where('id_barang', $id)->update(['foto_barang' => $fotoBarang]);
        }

        return redirect()->route('oldat.barang.detail', $id)->with('success', 'Berhasil Menyimpan Perubahan');
    }

    public function deleteBarang($id)
    {
        Barang::where('id_barang', $id)->delete();

        return redirect()->route('oldat.barang.show')->with('success', 'Berhasil Menghapus Barang');
    }

    public function processPengguna(Request $request, $id)
    {
        if ($id == '*') {
            $id_barang   = $request->barang;
            $id_pengguna = str_pad(BarangPengguna::withTrashed()->count() + 1, 8, 0, STR_PAD_LEFT);
            $tambah = new BarangPengguna();
            $tambah->id_pengguna      = $id_pengguna;
            $tambah->tanggal_pengguna = $request->tanggal;
            $tambah->barang_id        = $request->barang;
            $tambah->pegawai_id       = $request->pegawai;
            $tambah->keterangan       = $request->keterangan;
            $tambah->status_id        = $request->status;
            $tambah->created_at       = Carbon::now();
            $tambah->save();
        } elseif ($request->aksi == 'update') {
            $pengguna  = BarangPengguna::where('id_pengguna', $id)->first();
            $id_barang = $pengguna->barang;
            BarangPengguna::where('id_pengguna', $id)->update([
                'tanggal_pengguna'  => $request->tanggal,
                'barang_id'         => $request->barang,
                'pegawai_id'        => $request->pegawai,
                'keterangan'        => $request->keterangan,
                'status_id'         => $request->status
            ]);
        } else {
            $id_barang = $request->barang;
            BarangPengguna::where('id_pengguna', $id)->delete();
        }


        return redirect()->route('oldat.barang.detail', $id_barang)->with('success', 'Berhasil Memproses');
    }

    // =================================
    //          KATEGORI BARANG
    // =================================

    public function showKategori()
    {
        $kategori = KategoriBarang::get();
        return view('pages.modul.oldat.kategori.show', compact('kategori'));
    }

    public function createKategori()
    {
        return view('pages.modul.oldat.kategori.create');
    }

    public function editKategori($id)
    {
        $kategori = KategoriBarang::where('id_kategori', $id)->first();
        return view('pages.modul.oldat.kategori.edit', compact('kategori','id'));
    }

    public function storeKategori(Request $request)
    {
        $tambah = new KategoriBarang();
        $tambah->kode_kategori   = $request->kode_kategori;
        $tambah->kategori_barang = strtoupper($request->kategori_barang);
        $tambah->created_at      = Carbon::now();
        $tambah->save();

        return redirect()->route('oldat.kategori.show')->with('success', 'Berhasil menambahkan');
    }

    public function updateKategori(Request $request, $id)
    {
        KategoriBarang::where('id_kategori', $id)->update([
            'kode_kategori'   => $request->kode_kategori,
            'kategori_barang' => $request->kategori_barang,
        ]);

        return redirect()->route('oldat.kategori.show')->with('success', 'Berhasil menyimpan perubahan');
    }

    public function deleteKategori($id)
    {
        KategoriBarang::where('id_kategori', $id)->delete();

        return redirect()->route('oldat.kategori.show')->with('success', 'Berhasil menghapus');
    }
}
