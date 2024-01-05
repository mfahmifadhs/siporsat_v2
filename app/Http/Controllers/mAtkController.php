<?php

namespace App\Http\Controllers;

use App\Models\Atk;
use App\Models\AtkKeranjang;
use App\Models\AtkSatuan;
use App\Models\AtkStockop;
use App\Models\AtkStockopDetail;
use App\Models\KategoriAtk;
use App\Models\Usulan;
use App\Models\UsulanAtk;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use DB;

class mAtkController extends Controller
{

    public function index()
    {
        if (Auth::user()->role_id == 4) {
            $usulan = Usulan::where('form_id', 301)
                        ->where('user_id', Auth::user()->id)
                        ->where(DB::raw("DATE_FORMAT(t_usulan.tanggal_usulan, '%Y')"), 2024)
                        ->get();
        } else {
            $usulan = Usulan::where('form_id', 301)
                        ->where(DB::raw("DATE_FORMAT(t_usulan.tanggal_usulan, '%Y')"), 2024)
                        ->get();
        }

        $atk = Atk::orderBy('deskripsi','asc')->get();
        $kategori = KategoriAtk::orderBy('kategori_atk','asc')->get();
        return view('pages.modul.atk.index', compact('usulan','atk','kategori'));
    }

    // select kategori atk
    public function selectKategori(Request $request)
    {
        $search = $request->search;

        if ($search == '') {
            $atk = KategoriAtk::orderBy('kategori_atk', 'ASC')->get();
        } else {
            $atk = KategoriAtk::orderBy('kategori_atk', 'ASC')->where('kategori_atk', 'like', '%' . $search . '%')->get();
        }

        $response = array();
        foreach ($atk as $data) {
            $response[] = array(
                "id"    =>  $data->kode_kategori,
                "text"  =>  strtoupper($data->kode_kategori . ' - ' . $data->kategori_atk)
            );
        }

        return response()->json($response);
    }

    public function selectAtk(Request $request)
    {
        $search = $request->search;

        if ($search == '') {
            $atk = Atk::orderBy('deskripsi', 'ASC')->get();
        } else {
            $atk = Atk::orderBy('deskripsi', 'ASC')->where('deskripsi', 'like', '%' . $search . '%')->get();
        }

        $response = array();
        foreach ($atk as $data) {
            $response[] = array(
                "id"    =>  $data->kode_kategori,
                "text"  =>  strtoupper($data->kategori_id . ' - ' . $data->deskripsi)
            );
        }

        return response()->json($response);
    }


    // atk
    public function showAtk()
    {
        $satuan = AtkSatuan::orderBy('satuan', 'ASC')->get();
        $atk = Atk::join('t_atk_kategori', 'kode_kategori', 'kategori_id')
                ->orderBy('jenis_atk', 'ASC')
                ->orderBy('id_atk', 'desc')->get();
        return view('pages.modul.atk.barang.show', compact('satuan', 'atk'));
    }

    public function detailAtk($id)
    {
        $atk = Atk::where('id_atk', $id)->first();
        return view('pages.modul.atk.barang.detail', compact('atk'));
    }

    public function uploadAtk(Request $request)
    {
        foreach ($request->file('file_atk') as $key => $file) {
            $fileArr = Excel::toArray([], $file);
            foreach ($fileArr as $dataKey => $fileData) {
                $arrItem  = array_slice($fileArr[$dataKey], 3);
                foreach ($arrItem as $row) {
                    $atkCheck = Atk::where('id_atk', $row[0])->withTrashed()->count();

                    if ($atkCheck == 0) {
                        // jika atk belum terdaftar, tambah baru
                        $tambah = new Atk();
                        $tambah->id_atk         = $row[0];
                        $tambah->kategori_id    = $row[1];
                        $tambah->jenis_atk      = $row[2];
                        $tambah->deskripsi      = $row[4];
                        $tambah->keterangan_atk = $row[5];
                        $tambah->status_id      = $row[6];
                        $tambah->save();
                    } else {
                        // jika atk sudah terdaftar, perbaharui
                        Atk::where('id_atk', $row[0])->withTrashed()->update([
                            'kategori_id'    => $row[1],
                            'jenis_atk'      => $row[2],
                            'deskripsi'      => $row[4],
                            'keterangan_atk' => $row[5],
                            'status_id'      => $row[6],
                        ]);
                    }
                }
            }
        }
        return redirect()->route('atk.show')->with('success', 'Berhasil Upload File');
    }

    public function storeAtk(Request $request)
    {
        $tambah = new Atk();
        $tambah->kategori_id    = $request->kategori;
        $tambah->jenis_atk      = $request->jenis;
        $tambah->deskripsi      = $request->deskripsi;
        $tambah->keterangan_atk = $request->keterangan;
        $tambah->save();

        $idAtk = $tambah->id_atk;

        if ($request->foto_atk) {
            $file  = $request->file('foto_atk');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->storeAs('public/files/foto_atk', $filename);
            Atk::where('id_atk', $idAtk)->update(['foto_atk' => $filename]);
        }

        return redirect()->route('atk.show')->with('success', 'Berhasil Menambah ATK');
    }

    public function updateAtk(Request $request, $id)
    {
        dd($request->all());
        Atk::where('id_atk', $id)->update([
            'kategori_id'    => $request->kategori,
            'jenis_atk'      => $request->jenis,
            'deskripsi'      => $request->deskripsi,
            'keterangan_atk' => $request->keterangan,
            'status_id'      => $request->status
        ]);

        $atk = Atk::where('id_atk', $id)->first();
        if ($request->foto_atk) {
            $file  = $request->file('foto_atk');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->storeAs('public/files/foto_atk', $filename);
            $fotoAtk = $filename;

            if ($atk->foto_atk) {
                Storage::delete('public/files/foto_atk/' . $atk->foto_atk);
            }

            Atk::where('id_atk', $id)->update(['foto_atk' => $fotoAtk]);
        }

        return redirect()->route('atk.show')->with('success', 'Berhasil Menyimpan Perubahan');
    }

    public function deleteAtk($id)
    {
        $atk = Atk::where('id_atk', $id)->first();

        if ($atk->foto_atk) {
            Storage::delete('public/files/foto_atk/' . $atk->foto_atk);
        }

        AtkSatuan::where('atk_id', $id)->delete();
        Atk::where('id_atk', $id)->delete();


        return redirect()->route('atk.show')->with('success', 'Berhasil Menghapus ATK');
    }


    // kategoriAtk
    public function showKategori()
    {
        $kategori = KategoriAtk::orderBy('kategori_atk', 'asc')->get();
        return view('pages.modul.atk.kategori.show', compact('kategori'));
    }

    public function storeKategori(Request $request)
    {
        $tambah = new KategoriAtk();
        $tambah->kode_kategori  = $request->kode;
        $tambah->kategori_atk   = $request->kategori;
        $tambah->created_at = Carbon::now();
        $tambah->save();

        return redirect()->route('atk.kategori.show')->with('success', 'Berhasil Menambah Kategori');
    }

    public function updateKategori(Request $request, $id)
    {
        KategoriAtk::where('id_kategori', $id)->update([
            'kode_kategori' => $request->kode,
            'kategori_atk'  => $request->kategori
        ]);

        return redirect()->route('atk.kategori.show')->with('success', 'Berhasil Menyimpan Perubahan');
    }

    public function deleteKategori($id)
    {
        KategoriAtk::where('id_kategori', $id)->delete();
        return redirect()->route('atk.kategori.show')->with('success', 'Berhasil Menghapus Kategori');
    }


    // satuan atk
    public function storeSatuan(Request $request, $id)
    {
        $checkSatuan = AtkSatuan::where('atk_id', $id)->where('jenis_satuan', $request->jenis)->get();

        if ($checkSatuan->count() > 0) {
            foreach ($checkSatuan as $row) {
                AtkSatuan::where('id_satuan', $row->id_satuan)->where('jenis_satuan', $request->jenis)->update([
                    'status_id' => null
                ]);
            }
        }

        $nilai = str_replace(".", "", $request->harga);
        $nilai = (int) $nilai;

        $tambah = new AtkSatuan();
        $tambah->atk_id       = $id;
        $tambah->jenis_satuan = $request->jenis;
        $tambah->satuan       = strtoupper($request->satuan);
        $tambah->harga        = $nilai;
        $tambah->deskripsi    = $request->deskripsi;
        $tambah->status_id    = $request->status;
        $tambah->created_at   = Carbon::now();
        $tambah->save();

        return redirect()->route('atk.detail', $id)->with('success', 'Berhasil Menambah Satuan');
    }

    public function updateSatuan(Request $request, $id)
    {
        $nilai = str_replace(".", "", $request->harga);
        $nilai = (int) $nilai;

        AtkSatuan::where('id_satuan', $id)->update([
            'atk_id'          => $request->atk_id,
            'jenis_satuan'    => $request->jenis,
            'satuan'          => strtoupper($request->satuan),
            'harga'           => $nilai,
            'deskripsi_harga' => $request->range_harga,
            'deskripsi'       => $request->deskripsi,
            'status_id'       => $request->status
        ]);

        if ($request->status == 1) {
            $checkSatuan = AtkSatuan::where('atk_id', $request->atk_id)->where('jenis_satuan', $request->jenis)->get();
            if ($checkSatuan->count() > 0) {
                foreach ($checkSatuan as $row) {
                    AtkSatuan::where('id_satuan', $row->id_satuan)->where('jenis_satuan', $request->jenis)
                    ->where('id_satuan', '!=', $id)->update([
                        'status_id' => null
                    ]);
                }
            }
        }

        return redirect()->route('atk.detail', $request->atk_id)->with('success', 'Berhasil Menyimpan Perubahan');
    }

    public function deleteSatuan($atkId, $id)
    {
        AtkSatuan::where('id_satuan', $id)->delete();

        return redirect()->route('atk.detail', $atkId)->with('success', 'Berhasil Menghapus Satuan');
    }

    public function updateKeranjang(Request $request, $aksi, $id)
    {
        $atk = AtkKeranjang::where('id_keranjang', $id)->first();
        $usulan = UsulanAtk::where('id_permintaan', $id)->first();

        if ($aksi == 'min') {
            $kuantitas = $atk ? $atk->kuantitas - 1 : $usulan->jumlah_permintaan - 1;
        } else {
            $kuantitas = $atk ? $atk->kuantitas + 1 : $usulan->jumlah_permintaan + 1;
        }

        if ($atk) {
            AtkKeranjang::where('id_keranjang', $id)->update([
                'kuantitas' => $kuantitas
            ]);

            $updated = AtkKeranjang::where('id_keranjang', $id)->first();
        } else {
            UsulanAtk::where('id_permintaan', $id)->update([
                'jumlah_permintaan' => $kuantitas
            ]);

            $updated = UsulanAtk::where('id_permintaan', $id)->first();
        }

        return response()->json(['updatedKuantitas' => $updated]);

    }

    public function storeKeranjang(Request $request)
    {
        $atk = AtkKeranjang::where('user_id', Auth::user()->id)->where('atk_id', $request->atk_id)->first();
        $usulanAtk = UsulanAtk::where('atk_id', $request->atk_id)->where('usulan_id', $request->usulan_id)->first();

        if ($atk || $usulanAtk) {
            if (!$request->usulan_id) {
                AtkKeranjang::where('id_keranjang', $atk->id_keranjang)->update([
                    'kuantitas' => $atk->kuantitas + $request->qty
                ]);
            } else {
                UsulanAtk::where('id_permintaan', $usulanAtk->id_permintaan)->update([
                    'jumlah_permintaan' => $usulanAtk->jumlah_permintaan + 1
                ]);
            }
        } else {
            if (!$request->usulan_id) {
                $tambah = new AtkKeranjang();
                $tambah->user_id    = Auth::user()->id;
                $tambah->atk_id     = $request->atk_id;
                $tambah->kuantitas  = $request->qty;
                $tambah->created_at = Carbon::now();
                $tambah->save();
            } else {
                $tambah = new UsulanAtk();
                $tambah->usulan_id  = $request->usulan_id;
                $tambah->atk_id     = $request->atk_id;
                $tambah->jumlah_permintaan = 1;
                $tambah->harga_permintaan  = $request->harga;
                $tambah->created_at = Carbon::now();
                $tambah->save();
            }
        }
        $cartCount  = AtkKeranjang::where('user_id', Auth::user()->id)->count();
        $cartBasket = AtkKeranjang::where('user_id', Auth::user()->id)
                      ->join('t_atk','id_atk','atk_id')->join('t_atk_kategori','kode_kategori','kategori_id')
                      ->orderBy('id_keranjang', 'ASC')
                      ->get();

        return response()->json(['message' => 'Item berhasil ditambahkan ke keranjang', 'cartCount' => $cartCount, 'cartBasket' => $cartBasket]);
    }


    public function removeKeranjang($id)
    {
        $basket = AtkKeranjang::where('id_keranjang', $id)->first();
        $usulan = UsulanAtk::where('id_permintaan', $id)->first();
        if ($basket) {
            AtkKeranjang::where('id_keranjang', $id)->forceDelete();

            $cartCount  = AtkKeranjang::where('user_id', Auth::user()->id)->count();
            $cartBasket = AtkKeranjang::where('user_id', Auth::user()->id)
                        ->join('t_atk','id_atk','atk_id')->join('t_atk_kategori','kode_kategori','kategori_id')
                        ->orderBy('id_keranjang', 'ASC')
                        ->get();

            return response()->json(['message' => 'Item berhasil ditambahkan ke keranjang', 'cartCount' => $cartCount, 'cartBasket' => $cartBasket]);

        } else {
            UsulanAtk::where('id_permintaan', $id)->forceDelete();
            return redirect()->route('usulan.edit', ['form' => 'atk', 'id' => $usulan->usulan_id])->with('success', 'Berhasil Menghapus Item');

        }
    }

    public function selectFirstAtk($id)
    {
        $satuan = AtkSatuan::where('atk_id', $id)->where('status_id', 1)->where('jenis_satuan', 'distribusi')->first();
        $result = $satuan ? $satuan->satuan : $satuan = '-';
        return response()->json(['satuan' => $result]);
    }

    // =================================
    //           STOCKOPNAME
    // =================================

    public function showStockop()
    {
        if (Auth::user()->role_id == 4) {
            $usulan = Usulan::where('form_id', 301)
                        ->where('user_id', Auth::user()->id)
                        ->where(DB::raw("DATE_FORMAT(t_usulan.tanggal_usulan, '%Y')"), 2023)
                        ->get();
        } else {
            $usulan = Usulan::where('form_id', 301)
                        ->where(DB::raw("DATE_FORMAT(t_usulan.tanggal_usulan, '%Y')"), 2023)
                        ->get();
        }

        $atk = Atk::orderBy('deskripsi','asc')->get();
        $kategori = KategoriAtk::orderBy('kategori_atk','asc')->get();
        return view('pages.modul.atk.stockop.show', compact('usulan','atk','kategori'));
    }

    public function historyStockop()
    {
        $atk = Atk::where('status_id', 1)->get();
        $stockop = AtkStockop::where('pegawai_id', Auth::user()->pegawai_id)->get();
        return view('pages.modul.atk.stockop.history', compact('atk','stockop'));
    }

    public function editStockop()
    {

    }

    public function storeStockop(Request $request)
    {
        $id_stockop = AtkStockop::withTrashed()->count() + 1;

        $tambah = new AtkStockop();
        $tambah->id_stockop = $id_stockop;
        $tambah->pegawai_id = Auth::user()->pegawai_id;
        $tambah->tanggal_stockop = $request->tanggal;
        $tambah->keterangan = $request->ket_so;
        $tambah->created_at = Carbon::now();
        $tambah->save();

        $atk = $request->atk_id;
        foreach ($atk as $i => $atk_id) {
            $detail = new AtkStockopDetail();
            $detail->stockop_id = $id_stockop;
            $detail->atk_id     = $atk_id;
            $detail->kuantitas  = $request->qty[$i];
            $detail->keterangan = $request->ket_detail[$i];
            $detail->save();

            AtkKeranjang::where('id_keranjang', $request->keranjang_id[$i])->forceDelete();
        }

        return redirect()->route('atk.stockop.history')->with('success', 'Berhasil Menambah Stock Opname');
    }

    public function updateStockop(Request $request, $aksi, $id)
    {
        $detail = AtkStockopDetail::where('id_detail', $id)->first();

        if ($aksi == 'new') {
            $stockopId = $request->stockop_id;
            $atk  = $request->atk_id;

            foreach ($atk as $i => $atk_id) {
                $baru = new AtkStockopDetail();
                $baru->stockop_id = $stockopId;
                $baru->atk_id     = $atk_id;
                $baru->kuantitas  = $request->qty[$i];
                $baru->keterangan = $request->keterangan[$i];
                $baru->save();
            }

            return back()->with(compact('stockopId'))->with('success', 'Berhasil Menambah Item');
        } else {
            if ($aksi == 'min') {
                $kuantitas = $detail->kuantitas - 1;
            } else if ($aksi == 'add') {
                $kuantitas = $detail->kuantitas + 1;
            } else if ($aksi == 'desc') {
                $kuantitas = $detail->kuantitas;
            }

            AtkStockopDetail::where('id_detail', $id)->update([
                'kuantitas'  => $kuantitas,
                'keterangan' => $request->keterangan ? $request->keterangan : ''
            ]);

            $updated = AtkStockopDetail::where('id_detail', $id)->first();

            return response()->json(['updated' => $updated]);
        }
    }

    public function removeStockop($stockopId, $id)
    {
        AtkStockopDetail::where('id_detail', $id)->forceDelete();
        $detail  = AtkStockopDetail::where('stockop_id', $stockopId)
                    ->join('t_atk','id_atk','atk_id')
                    ->join('t_atk_kategori','kode_kategori','kategori_id')
                    ->orderBy('deskripsi', 'ASC')
                    ->get();


        return back()->with(compact('stockopId'))->with('success', 'Berhasil Menghapus Item');
        // return response()->json(['message' => 'Item berhasil dihapus', 'dataTable' => $detail]);
    }

}
