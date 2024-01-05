<?php

namespace App\Http\Controllers;

use App\Model\submissionModel;
use App\Models\Aadb;
use App\Models\Atk;
use App\Models\AtkKeranjang;
use App\Models\Barang;
use App\Models\BeritaAcara;
use App\Models\BeritaAcaraDetail;
use App\Models\BidangPerbaikan;
use App\Models\FormUsulan;
use App\Models\KategoriBarang;
use App\Models\MataAnggaran1;
use App\Models\Pegawai;
use App\Models\Status;
use App\Models\AtkSatuan;
use App\Models\UnitKerja;
use App\Models\User;
use App\Models\Usulan;
use App\Models\UsulanAadb;
use App\Models\UsulanAtk;
use App\Models\UsulanBbm;
use App\Models\UsulanGdn;
use App\Models\UsulanOldat;
use App\Models\UsulanStnk;
use App\Models\UsulanUkt;
use App\Models\UsulanRealisasi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Google2FA;
use DB;

class UsulanController extends Controller
{
    public function showUsulan(Request $request, $form)
    {
        $bulanPick  = [];
        $tahunPick  = Carbon::now()->format('Y');
        $statusPick = [];
        $formPick   = [];
        $ukerPick   = [];

        $data   = Usulan::select('id_usulan','tanggal_usulan','form_id','nomor_usulan','nama_pegawai','nama_unit_kerja','nama_status',
                    'status_pengajuan_id','status_proses_id','otp_1','keterangan','user_id','t_usulan.pegawai_id')
                    ->join('t_jenis_form','id_jenis_form','form_id')
                    ->join('t_pegawai','id_pegawai','pegawai_id')
                    ->join('t_unit_kerja','id_unit_kerja','unit_kerja_id')
                    ->join('t_status','id_status','status_proses_id')
                    ->orderBy('status_pengajuan_id', 'ASC')
                    ->orderBy('status_proses_id', 'ASC')
                    ->where('t_jenis_form.kategori', $form)
                    ->where(DB::raw("DATE_FORMAT(t_usulan.tanggal_usulan, '%Y')"), $tahunPick);

        if (Auth::user()->role_id == 4) {
            $usulan = $data->where('user_id', Auth::user()->id)->get();
        } else {
            $usulan = $data->get();
        }

        $totalRealisasi = UsulanRealisasi::join('t_usulan','id_usulan','usulan_id')
                            ->join('t_jenis_form','id_jenis_form','form_id')
                            ->where('t_jenis_form.kategori', $form)
                            ->select(DB::RAW('SUM(nilai_realisasi) as total'))
                            ->first();

        return view('pages.usulan.show3', compact('form','usulan','bulanPick','tahunPick','statusPick','ukerPick','formPick','totalRealisasi'));
    }

    public function filterUsulan(Request $request, $form)
    {
        $listForm   = FormUsulan::orderBy('nama_form', 'ASC');
        $listStatus = Status::whereIn('id_status',[101,102,103,105,106,100])->orderBy('id_status','ASC');
        $data       = Usulan::select('t_usulan.created_at as tanggal', 't_usulan.*','nama_form','nama_pegawai','nama_unit_kerja','nama_status','t_usulan.pegawai_id')
                        ->join('t_pegawai','id_pegawai','pegawai_id')
                        ->join('t_unit_kerja','id_unit_kerja','unit_kerja_id')
                        ->join('t_jenis_form','id_jenis_form','form_id')
                        ->join('t_status','id_status','status_proses_id')
                        ->orderBy('status_pengajuan_id', 'ASC')
                        ->orderBy('status_proses_id', 'ASC')
                        ->orderBy('t_usulan.created_at', 'DESC')
                        ->orderBy('id_usulan', 'DESC')
                        ->where('t_jenis_form.kategori', $form);

        $dataTotal  = UsulanRealisasi::join('t_usulan','id_usulan','usulan_id')
                        ->join('t_pegawai','id_pegawai','pegawai_id')
                        ->join('t_jenis_form','id_jenis_form','form_id')
                        ->join('t_unit_kerja','id_unit_kerja','unit_kerja_id')
                        ->select(DB::RAW('SUM(nilai_realisasi) as total'))
                        ->where('t_jenis_form.kategori', $form);

        $unitKerja  = UnitKerja::orderBy('nama_unit_kerja','ASC');
        $bulan      = [];
        $bulanPick  = [];
        $tahunPick  = [];
        $statusPick = [];
        $formPick   = [];
        $ukerPick   = [];
        $satuan     = [];

        for ($i = 1; $i <= 12; $i++) {
            $listBulan[] = [
                'id'         => $i,
                'nama_bulan' => Carbon::create(null, $i, 1)->locale('id')->isoFormat('MMMM')
            ];
        }

        if($request->unit_kerja || $request->bulan || $request->status || $request->jenis_form || $request->tahun) {

            if ($request->unit_kerja) {
                $search     = $data->where('id_unit_kerja', $request->unit_kerja);
                $total      = $dataTotal->where('id_unit_kerja', $request->unit_kerja)->first();
                $uker       = $unitKerja->get();
                $ukerPick   = UnitKerja::where('id_unit_kerja', $request->unit_kerja)->first();
            } else { $uker = $unitKerja->get(); }

            if ($request->bulan) {
                $selectedBulan = explode(',', $request->bulan);
                $bulan         = collect($listBulan)->where('id', '!=', $request->bulan)->all();
                $bulanPick     = collect($listBulan)->filter(function ($item) use ($selectedBulan) {
                    return in_array($item['id'], $selectedBulan);
                });

                $search = $data->where(DB::raw("DATE_FORMAT(t_usulan.tanggal_usulan, '%c')"), $request->bulan);
                $total  = $dataTotal->where(DB::raw("DATE_FORMAT(t_usulan.tanggal_usulan, '%c')"), $request->bulan);
            } else { $bulan    = $listBulan; }

            if ($request->tahun) {
                $selectedTahun = explode(',', $request->tahun);
                $tahunPick     = $request->tahun;

                $search = $data->where(DB::raw("DATE_FORMAT(t_usulan.tanggal_usulan, '%Y')"), $request->tahun);
                $total  = $dataTotal->where(DB::raw("DATE_FORMAT(t_usulan.tanggal_usulan, '%Y')"), $request->tahun);
            } else { $tahunPick = 2023; }

            if ($request->status) {
                $status     = $listStatus->get();
                $statusPick = Status::where('id_status', $request->status)->first();

                if ($request->status == 'persetujuan') {
                    $statusPick = Status::where('id_status', 102)->first();
                    $search = $data->where('status_proses_id', 101)->orWhere('status_proses_id', 102);
                    $total  = $dataTotal->where('status_proses_id', 101)->orWhere('status_proses_id', 102);
                } else {
                    $search = $data->where('status_proses_id', $request->status);
                    $total  = $dataTotal->where('status_proses_id', $request->status);
                }
            } else { $status = $listStatus->get(); }

            if ($request->jenis_form) {
                $jenisForm = $listForm->where('kategori', $form)->get();
                $formPick  = FormUsulan::where('id_jenis_form', $request->jenis_form)->first();
                $search    = $data->where('form_id', $request->jenis_form);
                $total     = $dataTotal->where('form_id', $request->jenis_form);
            } else {
                $jenisForm = $listForm->where('kategori', $form)->get();
            }

            $searchData    = $search->where('t_jenis_form.kategori', $form);
            $dataRealisasi = $total->where('t_jenis_form.kategori', $form);
            $tab           = 2;
        } else {
            $status        = $listStatus->get();
            $bulan         = $listBulan;
            $searchData    = $data;
            $dataRealisasi = $dataTotal;
            $uker          = $unitKerja->get();
            $jenisForm     = $listForm->where('kategori', $form)->get();
        }

        if (Auth::user()->role_id == 4) {
            $usulan         = $searchData->where(DB::raw("DATE_FORMAT(t_usulan.tanggal_usulan, '%Y')"), $tahunPick)->where('user_id', Auth::user()->id)->get();
            $totalRealisasi = $dataRealisasi->where(DB::raw("DATE_FORMAT(t_usulan.tanggal_usulan, '%Y')"), $tahunPick)->where('user_id', Auth::user()->id)->first();
        } else {
            $usulan         = $searchData->where(DB::raw("DATE_FORMAT(t_usulan.tanggal_usulan, '%Y')"), $tahunPick)->get();
            $totalRealisasi = $dataRealisasi->where(DB::raw("DATE_FORMAT(t_usulan.tanggal_usulan, '%Y')"), $tahunPick)->first();
        }

        return view('pages.usulan.show3', compact('bulan','status','bulanPick','tahunPick','statusPick','usulan','form',
        'jenisForm','uker','ukerPick','formPick','satuan','totalRealisasi'));
    }

    public function detailUsulan(Request $request, $form, $id)
    {
        $activeTab = '';
        $usulan = Usulan::where('id_usulan', $id)->first();
        $status = Status::where('kategori_status', 'usulan')->get();
        return view('pages.usulan.detail', compact('form','id','usulan','status','activeTab'));
    }

    public function createUsulan($form, $id)
    {
        $bperbaikan = BidangPerbaikan::orderBy('bidang_perbaikan','ASC')->get();
        $kategoriBarang = KategoriBarang::orderBy('kategori_barang','ASC')->get();

        return view('pages.usulan.'. $form .'.create', compact('form','id','kategoriBarang','bperbaikan'));
    }

    public function editUsulan($form, $id)
    {
        $total  = Usulan::withTrashed()->count();
        $format = str_pad($total + 1, 4, 0, STR_PAD_LEFT);
        $idUsulan    = Carbon::now()->isoFormat('YYMMDDHHmmss').$format;
        $nomorUsulan = strtoupper('UKT/1/'.$format.'/'.Carbon::now()->isoFormat('MMM').'/'.Carbon::now()->isoFormat('Y'));

        $kategoriBarang = KategoriBarang::orderBy('kategori_barang','ASC')->get();
        $usulan = Usulan::where('id_usulan', $id)->first();
        $bperbaikan = BidangPerbaikan::get();
        $merkBarang = Barang::select('id_barang','kategori_id','nup','merk_tipe')->get();
        $atk = Atk::orderBy('deskripsi','asc')->get();
        return view('pages.usulan.'. $form .'.edit', compact('form','id','kategoriBarang','usulan','bperbaikan','merkBarang','atk'));
    }

    public function storeUsulan(Request $request, $form, $id)
    {
        dd($request->all());
        if ($form == 'ukt' || $form == 'gdn' || $form == 'oldat' || $form == 'aadb' || $form == 'atk') {
            $tahunPick  = Carbon::now()->format('Y');
            $total   = Usulan::join('t_jenis_form','id_jenis_form','form_id')
                        ->where('kategori', $form)
                        ->where(DB::raw("DATE_FORMAT(t_usulan.tanggal_usulan, '%Y')"), $tahunPick)
                        ->withTrashed()
                        ->count();

            $format  = str_pad($total + 1, 4, 0, STR_PAD_LEFT);
            $randNum = rand(111,999);
            $kodeForm    = $request->kode_form;
            $idUsulan    = Carbon::now()->isoFormat('YYMMDD').$randNum.$kodeForm.$format;

            $usulan = new Usulan();
            $usulan->id_usulan  = $idUsulan;
            $usulan->user_id    = Auth::user()->id;
            $usulan->pegawai_id = Auth::user()->pegawai_id;
            $usulan->form_id    = $kodeForm;
            $usulan->tanggal_usulan   = Carbon::now();
            $usulan->nomor_usulan     = '-';
            $usulan->status_proses_id = 101;
            $usulan->keterangan       = $request->keterangan;
            $usulan->created_at       = Carbon::now();
            $usulan->save();

            if ($form == 'ukt') {
                $ukt = $request->judul;
                foreach ($ukt as $i => $judul) {
                    $detail = new UsulanUkt();
                    $detail->usulan_id       = $idUsulan;
                    $detail->judul_pekerjaan = $judul;
                    $detail->deskripsi       = $request->deskripsi[$i];
                    $detail->keterangan      = $request->keterangan_pekerjaan[$i];
                    $detail->save();
                }
            } else if ($form == 'gdn') {
                $gdn = $request->bidang_perbaikan;
                foreach ($gdn as $i => $bidang_perbaikan) {
                    $detail = new UsulanGdn();
                    $detail->usulan_id       = $idUsulan;
                    $detail->bperbaikan_id   = $bidang_perbaikan;
                    $detail->judul_pekerjaan = $request->judul[$i];
                    $detail->deskripsi       = $request->deskripsi[$i];
                    $detail->keterangan      = $request->keterangan_pekerjaan[$i];
                    $detail->save();
                }
            } else if ($form == 'oldat') {
                $odt = $request->kode_form == 201 ? $request->barang : $request->merktipe;

                foreach ($odt as $i => $barang) {
                    if ($request->kode_form == 201) {
                        $nilai = str_replace(".", "", $request->estimasi_harga[$i]);
                        $nilai = (int) $nilai;

                        $detail = new UsulanOldat();
                        $detail->usulan_id          = $idUsulan;
                        $detail->kategori_id        = $barang;
                        $detail->merk_tipe          = $request->merk_tipe[$i];
                        $detail->spesifikasi        = $request->spesifikasi[$i];
                        $detail->jumlah_pengadaan   = $request->jumlah[$i];
                        $detail->estimasi_harga     = $nilai;
                        $detail->save();
                    } else {
                        $detail = new UsulanOldat();
                        $detail->usulan_id  = $idUsulan;
                        $detail->barang_id  = $barang;
                        $detail->keterangan_kerusakan  = $request->spesifikasi[$i];
                        $detail->save();
                    }
                }
            } else if ($form == 'aadb') {
                $aadb = $request->kendaraan_id;

                foreach ($aadb as $i => $kendaraan_id) {
                    if ($request->kode_form == 101) {
                        // Pengadaan
                        $total   = UsulanAadb::withTrashed()->count();
                        $id_aadb = str_pad($total + 1, 4, 0, STR_PAD_LEFT);

                        $detail = new UsulanAadb();
                        $detail->id_aadb      = $id_aadb;
                        $detail->usulan_id    = $idUsulan;
                        $detail->kategori_id  = $kendaraan_id;
                        $detail->jenis_aadb   = $request->jenis_aadb;
                        $detail->kualifikasi  = $request->kualifikasi[$i];
                        $detail->merk_tipe    = $request->merk_tipe[$i];
                        $detail->tahun        = $request->tahun[$i];
                        $detail->jumlah_pengadaan  = $request->jumlah_pengadaan[$i];
                        $detail->created_at        =  Carbon::now();
                        $detail->save();
                    } else if ($request->kode_form == 102) {
                        // Servis
                        $kilometer = (int) str_replace(".", "", $request->kilometer[$i]);

                        $total   = UsulanStnk::withTrashed()->count();
                        $id_stnk = str_pad($total + 1, 4, 0, STR_PAD_LEFT);

                        $detail = new UsulanStnk();
                        $detail->id_stnk      = $id_stnk;
                        $detail->usulan_id    = $idUsulan;
                        $detail->kendaraan_id = $kendaraan_id;
                        $detail->kilometer    = $request->kilometer[$i] == '' ? $kilometer : null;
                        $detail->tanggal_servis    = $request->tanggal_servis[$i];
                        $detail->tanggal_ganti_oli = $request->tanggal_ganti_oli[$i];
                        $detail->keterangan        = $request->keterangan_servis[$i];
                        $detail->created_at        =  Carbon::now();
                        $detail->save();
                    } else if ($request->kode_form == 103) {
                        // Tambah usulan STNK
                        $total   = UsulanStnk::withTrashed()->count();
                        $id_stnk = str_pad($total + 1, 4, 0, STR_PAD_LEFT);

                        $status  = $request->status[$i];
                        if ($status == 'true') {
                            $detail = new UsulanStnk();
                            $detail->id_stnk      = $id_stnk;
                            $detail->usulan_id    = $idUsulan;
                            $detail->kendaraan_id = $kendaraan_id;
                            $detail->tanggal_stnk = $request->tanggal_stnk[$i];
                            $detail->keterangan   = $status;
                            $detail->created_at   =  Carbon::now();
                            $detail->save();
                        }
                    } else {
                        // Tambah usulan BBM
                        $total  = UsulanBbm::withTrashed()->count();
                        $id_bbm = str_pad($total + 1, 4, 0, STR_PAD_LEFT);
                        $status = $request->status_pengajuan[$i];
                        if ($status == 'true') {
                            $detail = new UsulanBbm();
                            $detail->id_bbm    = $id_bbm;
                            $detail->usulan_id = $idUsulan;
                            $detail->kendaraan_id     = $kendaraan_id;
                            $detail->bulan_pengadaan  = $request->bulan_pengadaan;
                            $detail->status_pengajuan = $request->status_pengajuan[$i];
                            $detail->created_at       =  Carbon::now();
                            $detail->save();
                        }
                    }
                }
            } else if ($form == 'atk') {
                $atk = $request->atk;
                foreach ($atk as $i => $row)
                {
                    $tambah = new UsulanAtk();
                    $tambah->usulan_id  = $idUsulan;
                    $tambah->atk_id     = $row;
                    $tambah->jumlah_permintaan = $request->jumlah[$i];
                    $tambah->created_at = Carbon::now();
                    $tambah->save();

                    AtkKeranjang::where('id_keranjang', $request->id_keranjang[$i])->forceDelete();
                }
            }

            return redirect()->route('usulan.verif.create', ['form' => $form, 'id' => $idUsulan]);
        }
    }

    public function updateUsulan(Request $request, $form, $id)
    {
        if (Auth::user()->role_id == 2 || $form == 'atk') {
            Usulan::where('id_usulan', $id)->update([
                'tanggal_usulan' => $request->tanggal_usulan,
                'nomor_usulan'   => $request->nomor_usulan,
                'keterangan'     => $request->keterangan
            ]);
        }

        if ($form == 'ukt') {
            $id_detail = $request->id_detail;
            foreach ($id_detail as $i => $id_detail) {
                if (!$id_detail) {
                    $detail = new UsulanUkt();
                    $detail->usulan_id       = $id;
                    $detail->judul_pekerjaan = $request->judul[$i];
                    $detail->deskripsi       = $request->deskripsi[$i];
                    $detail->keterangan      = $request->keterangan[$i];
                    $detail->save();
                } else {
                    UsulanUkt::where('id_ukt', $id_detail)->update([
                        'judul_pekerjaan' => $request->judul[$i],
                        'deskripsi'       => $request->deskripsi[$i],
                        'keterangan'      => $request->keterangan[$i],
                        'deleted_at'      => $request->hapus[$i] ? Carbon::now() : null,
                    ]);
                }
            }
        } else if ($form == 'gdn') {
            $id_detail = $request->id_detail;
            foreach ($id_detail as $i => $id_detail) {
                if (!$id_detail) {
                    $detail = new UsulanGdn();
                    $detail->usulan_id       = $id;
                    $detail->bperbaikan_id   = $request->bidang_perbaikan[$i];
                    $detail->judul_pekerjaan = $request->judul[$i];
                    $detail->deskripsi       = $request->deskripsi[$i];
                    $detail->keterangan      = $request->keterangan[$i];
                    $detail->save();
                } else {
                    if ($request->hapus[$i]) {
                        UsulanGdn::where('id_gdn', $id_detail)->forceDelete();
                    } else {
                        UsulanGdn::where('id_gdn', $id_detail)->update([
                            'bperbaikan_id'   => $request->bidang_perbaikan[$i],
                            'judul_pekerjaan' => $request->judul[$i],
                            'deskripsi'       => $request->deskripsi[$i],
                            'keterangan'      => $request->keterangan[$i],
                            'deleted_at'      => null
                        ]);
                    }
                }
            }
        } else if ($form == 'oldat') {
            $id_detail = $request->id_detail;
            foreach ($id_detail as $i => $id_detail) {
                if (!$id_detail) {
                    if ($request->kode_form == 201) {
                        $nilai = str_replace(".", "", $request->estimasi_harga[$i]);
                        $nilai = (int) $nilai;

                        $detail = new UsulanOldat();
                        $detail->usulan_id          = $id;
                        $detail->kategori_id        = $request->barang[$i];
                        $detail->merk_tipe          = $request->merk_tipe[$i];
                        $detail->spesifikasi        = $request->spesifikasi[$i];
                        $detail->jumlah_pengadaan   = $request->jumlah[$i];
                        $detail->estimasi_harga     = $nilai;
                        $detail->save();
                    } else {
                        $detail = new UsulanOldat();
                        $detail->usulan_id  = $id;
                        $detail->barang_id  = $request->merk_tipe[$i];
                        $detail->keterangan_kerusakan  = $request->spesifikasi[$i];
                        $detail->save();
                    }
                } else {
                    if ($request->kode_form == 201) {
                        $nilai = str_replace(".", "", $request->estimasi_harga[$i]);
                        $nilai = (int) $nilai;

                        UsulanOldat::where('id_oldat', $id_detail)->update([
                            'usulan_id'         => $id,
                            'kategori_id'       => $request->barang[$i],
                            'merk_tipe'         => $request->merk_tipe[$i],
                            'spesifikasi'       => $request->spesifikasi[$i],
                            'jumlah_pengadaan'  => $request->jumlah[$i],
                            'estimasi_harga'    => $nilai,
                            'deleted_at'        => $request->hapus[$i] ? Carbon::now() : null
                        ]);
                    } else {
                        UsulanOldat::where('id_oldat', $id_detail)->update([
                            'usulan_id'  => $id,
                            'barang_id'  => $request->merk_tipe[$i],
                            'keterangan_kerusakan' => $request->spesifikasi[$i],
                            'deleted_at' => $request->hapus[$i] ? Carbon::now() : null
                        ]);
                    }
                }
            }

        } else if ($form == 'aadb') {
            $aadb = $request->kendaraan_id;
            foreach ($aadb as $i => $kendaraan_id) {
                if ($request->kode_form == 101) {
                    // Pengadaan
                    Usulan::where('id_usulan', $id)->update([ 'keterangan'     => $request->keterangan ]);
                    $aadb = UsulanAadb::where('id_aadb', $request->aadb_id[$i])->first();

                    if ($aadb) {
                        UsulanAadb::where('id_aadb', $aadb->id_aadb)->update([
                            'usulan_id'      => $id,
                            'kategori_id'    => $kendaraan_id,
                            'jenis_aadb'     => $request->jenis_aadb,
                            'kualifikasi'    => $request->kualifikasi[$i],
                            'merk_tipe'      => $request->merk_tipe[$i],
                            'tahun'          => $request->tahun[$i],
                            'jumlah_pengadaan' => $request->jumlah_pengadaan[$i],
                            'deleted_at'       => $request->hapus[$i] ? Carbon::now() : null
                        ]);
                    } else {
                        $total   = UsulanAadb::withTrashed()->count();
                        $id_aadb = str_pad($total + 1, 4, 0, STR_PAD_LEFT);
                        $detail = new UsulanAadb();
                        $detail->id_aadb      = $id_aadb;
                        $detail->usulan_id    = $id;
                        $detail->kategori_id  = $kendaraan_id;
                        $detail->jenis_aadb   = $request->jenis_aadb;
                        $detail->kualifikasi  = $request->kualifikasi[$i];
                        $detail->merk_tipe    = $request->merk_tipe[$i];
                        $detail->tahun        = $request->tahun[$i];
                        $detail->jumlah_pengadaan  = $request->jumlah_pengadaan[$i];
                        $detail->created_at        =  Carbon::now();
                        $detail->save();
                    }
                } else if ($request->kode_form == 102) {
                    // Servis
                    $kilometer = (int) str_replace(".", "", $request->kilometer[$i]);
                    $stnk = UsulanStnk::where('id_stnk', $request->stnk_id[$i])->first();

                    if ($stnk) {
                        UsulanStnk::where('id_stnk', $stnk->id_stnk)->update([
                            'usulan_id'     => $id,
                            'kendaraan_id'  => $kendaraan_id,
                            'kilometer'     => $kilometer,
                            'tanggal_servis'    => $request->tanggal_servis[$i],
                            'tanggal_ganti_oli' => $request->tanggal_ganti_oli[$i],
                            'keterangan'        => $request->keterangan_servis[$i],
                            'deleted_at'        => $request->hapus[$i] ? Carbon::now() : null
                        ]);
                    } else {
                        $total   = UsulanStnk::withTrashed()->count();
                        $id_stnk = str_pad($total + 1, 4, 0, STR_PAD_LEFT);
                        $detail = new UsulanStnk();
                        $detail->id_stnk      = $id_stnk;
                        $detail->usulan_id    = $id;
                        $detail->kendaraan_id = $kendaraan_id;
                        $detail->kilometer    = $request->kilometer[$i] == '' ? $kilometer : null;
                        $detail->tanggal_servis    = $request->tanggal_servis[$i];
                        $detail->tanggal_ganti_oli = $request->tanggal_ganti_oli[$i];
                        $detail->keterangan        = $request->keterangan_servis[$i];
                        $detail->created_at   =  Carbon::now();
                        $detail->save();
                    }
                } else if ($request->kode_form == 103) {
                    // Perpanjangan STNK
                    $status = $request->status[$i];
                    $stnk = UsulanStnk::where('id_stnk', $request->stnk_id[$i])->first();

                    if ($stnk) {
                        UsulanStnk::where('id_stnk', $stnk->id_stnk)->update([
                            'usulan_id'     => $id,
                            'kendaraan_id'  => $kendaraan_id,
                            'tanggal_stnk'  => $request->tanggal_stnk[$i],
                            'keterangan'    => $status
                        ]);
                    } else {
                        $total   = UsulanStnk::withTrashed()->count();
                        $id_stnk = str_pad($total + 1, 4, 0, STR_PAD_LEFT);
                        if ($status == 'true') {
                            $detail = new UsulanStnk();
                            $detail->id_stnk      = $id_stnk;
                            $detail->usulan_id    = $id;
                            $detail->kendaraan_id = $kendaraan_id;
                            $detail->tanggal_stnk = $request->tanggal_stnk[$i];
                            $detail->keterangan   = $status;
                            $detail->created_at   =  Carbon::now();
                            $detail->save();
                        }
                    }
                } else {
                    // Permintaan Voucher BBM
                    $status = $request->status_pengajuan[$i];
                    $bbm = UsulanBbm::where('id_bbm', $request->bbm_id[$i])->first();

                    if ($bbm) {
                        UsulanBbm::where('id_bbm', $bbm->id_bbm)->update([
                            'usulan_id'        => $id,
                            'kendaraan_id'     => $kendaraan_id,
                            'bulan_pengadaan'  => $request->bulan_pengadaan,
                            'status_pengajuan' => $request->status_pengajuan[$i]
                        ]);
                    } else {
                        $total  = UsulanBbm::withTrashed()->count();
                        $id_bbm = str_pad($total + 1, 4, 0, STR_PAD_LEFT);
                        if ($status == 'true') {
                            $detail = new UsulanBbm();
                            $detail->id_bbm    = $id_bbm;
                            $detail->usulan_id = $id;
                            $detail->kendaraan_id     = $kendaraan_id;
                            $detail->bulan_pengadaan  = $request->bulan_pengadaan;
                            $detail->status_pengajuan = $request->status_pengajuan[$i];
                            $detail->created_at       =  Carbon::now();
                            $detail->save();
                        }
                    }
                }
            }
        }

        return redirect()->route('usulan.detail', ['form' => $form, 'id' => $id])->with('success', 'Berhasil Menyimpan Perubahan');
    }

    public function deleteUsulan($form, $id)
    {
        Usulan::where('id_usulan', $id)->delete();

        return redirect()->route('usulan.show', $form)->with('success', 'Berhasil Menghapus Usulan');
    }

    public function printUsulan($form, $id)
    {
        $kabagrt = Pegawai::where('jabatan_id', 5)->first();
        $usulan  = Usulan::where('id_usulan', $id)->first();
        return view('pages.usulan.print', compact('form','id','usulan','kabagrt'));
    }

    public function editBast($form, $id)
    {
        $bast = BeritaAcara::where('id_bast', $id)->first();
        return view('pages.usulan.bast.edit', compact('form','id','bast'));
    }

    public function updateBast(Request $request, $form, $id)
    {
        $bast = BeritaAcara::where('id_bast', $id)->first();
        BeritaAcara::where('id_bast', $id)->update([
            'nomor_bast'    => $request->nomor_bast,
            'tanggal_bast'  => $request->tanggal_bast
        ]);

        return redirect()->route('usulan.detail', ['form' => $form, 'id' => $bast->usulan_id])->with('success', 'Berhasil Menyimpan Perubahan');
    }

    public function printBast($form, $id)
    {
        $id = (int) $id;
        $kabagrt    = Pegawai::where('jabatan_id', 5)->first();
        $bast       = BeritaAcara::where('id_bast', $id)->first();
        $usulan     = Usulan::where('id_usulan', $bast->usulan_id)->first();

        if ($usulan->jenis_form == 101) {

        } else {
            $ppk = Pegawai::where('jabatan_id', 8)->first();
        }

        return view('pages.usulan.bast.print', compact('form','id','bast','usulan','kabagrt','ppk'));
    }

    public function confirmUsulan(Request $request, $form, $id)
    {
        $usulan = Usulan::where('id_usulan', $id)->first();
        $status = Usulan::where('id_usulan', $id)->pluck('status_proses_id')->first();

        if ($request->reject) {
            Usulan::where('id_usulan', $id)->update([
                'status_proses_id'    => 100,
                'status_pengajuan_id' => 12,
                'keterangan_tolak'    => $request->keterangan_tolak
            ]);
        } else {
            Usulan::where('id_usulan', $id)->update([
                'status_proses_id'    => 102,
                'status_pengajuan_id' => 11
            ]);

            return redirect()->route('usulan.detail', ['form' => $form, 'id' => $id])->with('success', 'Berhasil Melakukan Validasi');
        }

        return redirect()->route('usulan.show', $form)->with('success', 'Berhasil Konfirmasi Usulan');

    }

    // ===========================================
    //              VALIDASI ATK
    // ===========================================
    public function validationAtk($form, $id)
    {
        $usulan = Usulan::where('id_usulan', $id)->first();
        $atk = Atk::orderBy('deskripsi','asc')->get();
        return view('pages.usulan.atk.validation', compact('form','id','usulan','atk'));
    }

    public function storeValidationAtk(Request $request, $form, $id)
    {
        $usulan = Usulan::where('id_usulan', $id)->first();
        Usulan::where('id_usulan', $id)->update([
            'tanggal_usulan' => $request->tanggal_usulan,
            'nomor_usulan'   => $request->nomor_usulan,
            'keterangan'     => $request->keterangan,
            'status_proses_id'    => $usulan->status_proses_id == 101 ? 102 : $usulan->status_proses_id,
            'status_pengajuan_id' => $usulan->status_proses_id == 101 ? 1 : $usulan->status_pengajuan_id
        ]);

        $id_permintaan = $request->id_permintaan;
        foreach ($id_permintaan as $i => $row) {
            UsulanAtk::where('id_permintaan', $row)->update([
                'atk_id'            => $request->atk[$i],
                'jumlah_permintaan' => $request->permintaan[$i],
                'jumlah_disetujui'  => $request->disetujui[$i],
            ]);
        }

        return redirect()->route('usulan.detail', ['form' => $form, 'id' => $id])->with('success', 'Berhasil Melakukan Validasi');
    }

    public function storeDeliverAtk(Request $request, $form, $id)
    {
        $status     = Status::where('kategori_status', 'usulan')->get();
        $bulan      = Carbon::now()->isoFormat('MM');
        $tahun      = Carbon::now()->isoFormat('Y');
        $usulan     = Usulan::where('id_usulan', $id)->first();
        $totalBast  = BeritaAcara::where(DB::raw("DATE_FORMAT(tanggal_bast, '%Y')"), $tahun)->withTrashed()->count();
        $totalHarga = 0;

        $id_bast   = $totalBast + 1;
        $nomorBast = 'KR.02.04/A.VII.1.2/'.$usulan->form_id.'/'.$id_bast.'/'.$tahun;

        $bast = new BeritaAcara();
        $bast->id_bast      = $id_bast;
        $bast->usulan_id    = $usulan->id_usulan;
        $bast->nomor_bast   = $nomorBast;
        $bast->tanggal_bast = $request->tanggal_bast;
        $bast->notes        = $request->total_realisasi;
        $bast->created_at   = Carbon::now();
        $bast->save();

        $detail = $request->permintaan_id;
        foreach ($detail as $i => $permintaan) {
            $newDetailId = 0;
            if (!$permintaan) {
                $newDetail = new UsulanAtk();
                $newDetail->usulan_id  = $id;
                $newDetail->atk_id     = $request->atk[$i];
                $newDetail->jumlah_permintaan = $request->diserahkan[$i];
                $newDetail->jumlah_disetujui  = $request->diserahkan[$i];
                $newDetail->jumlah_penyerahan = $request->diserahkan[$i];
                $newDetail->created_at = Carbon::now();
                $newDetail->save();

                $newDetailId = $newDetail->id_permintaan;
            } else {
                $usulanAtk = UsulanAtk::where('id_permintaan', $permintaan)->first();

                if (($usulanAtk->jumlah_disetujui - $usulanAtk->jumlah_penyerahan) > 0) {
                    UsulanAtk::where('id_permintaan', $permintaan)->update([
                        'jumlah_disetujui'  => $request->confirmAll == 1 ? $usulanAtk->jumlah_penyerahan + $request->diserahkan[$i] : $request->disetujui[$i],
                        'jumlah_penyerahan' => $usulanAtk->jumlah_penyerahan + $request->diserahkan[$i]
                    ]);
                }
            }

            $detailBast = new BeritaAcaraDetail();
            $detailBast->bast_id = $id_bast;
            $detailBast->usulan_detail_id = !$permintaan ? $newDetailId : $permintaan;
            $detailBast->deskripsi        = $request->diserahkan[$i];
            $detailBast->nilai_realisasi  = null;
            $detailBast->created_at       = Carbon::now();
            $detailBast->save();
            // $totalHarga += $nilai;
        }

        // realisasi
        // $realisasi = BeritaAcara::where('usulan_id', $id)->first();
        // if (!$realisasi) {
        if ($request->mta_kode) {
            $nilai = str_replace(".", "", $request->total_realisasi);
            $nilai = (int) $nilai;

            $realisasi = new UsulanRealisasi();
            $realisasi->usulan_id = $id;
            $realisasi->mta_kode        = $request->mta_kode;
            $realisasi->mta_deskripsi   = $request->mta_deskripsi;
            $realisasi->nilai_realisasi = $nilai;
            $realisasi->jenis_realisasi = 'cash/transfer';
            $realisasi->created_at      = Carbon::now();
            $realisasi->save();
        }
        // } else {
        //     UsulanRealisasi::where('id_realisasi', $realisasi->id_realisasi)->update([
        //         'nilai_realisasi' => $realisasi->nilai_realisasi + $totalHarga
        //     ]);
        // }

        $belumDiserahkan = UsulanAtk::where('usulan_id', $id)->whereRaw('jumlah_penyerahan < jumlah_disetujui')->count();

        if ($belumDiserahkan == 0 && $request->confirmAll == 1) {
            Usulan::where('id_usulan', $id)->update([
                'status_proses_id' => 105,
                'otp_3' => rand(000000,999999)
            ]);
        }

        $request->session()->put('tabs', 'bast');
        return redirect()->route('usulan.detail', ['form' => $form, 'id' => $id])->with('success', 'Berhasil Menyerahkan ATK');
    }

    // ===========================================
    //              LAPORAN USULAN
    // ===========================================

    public function reportUsulan(Request $request, $form)
    {
        $result      = [];
        $tahun       = [2024,2023];
        $tahunPick   = $request->tahun ? $request->tahun : Carbon::now()->format('Y');
        $usulan      = Usulan::join('t_jenis_form','id_jenis_form','form_id')->where('kategori', $form)
                       ->where(DB::raw("(DATE_FORMAT(tanggal_usulan, '%Y'))"), $tahunPick)->get();
        $usulanUker  = Usulan::select('id_unit_kerja', 'nama_unit_kerja', DB::raw('count(id_usulan) as total_usulan'))
            ->leftjoin('t_pegawai', 'id_pegawai', 'pegawai_id')
            ->rightjoin('t_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
            ->join('t_jenis_form','id_jenis_form','form_id')
            ->groupBy('id_unit_kerja', 'nama_unit_kerja')
            ->where('kategori', $form)
            ->where(DB::raw("DATE_FORMAT(t_usulan.tanggal_usulan, '%Y')"), $tahunPick)
            ->get();

        $usulanChart = Usulan::select(
            DB::raw("(DATE_FORMAT(tanggal_usulan, '%Y-%m')) as month"),
            DB::raw('count(id_usulan) as total_usulan')
        )
            ->leftjoin('t_pegawai', 'id_pegawai', 'pegawai_id')
            ->join('t_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
            ->join('t_jenis_form','id_jenis_form','form_id')
            ->groupBy('month')
            ->orderBy('month', 'ASC')
            ->where('kategori', $form)
            ->where(DB::raw("(DATE_FORMAT(tanggal_usulan, '%Y'))"), $tahunPick)
            ->get();

        $chartData = Usulan::select(DB::raw("(DATE_FORMAT(tanggal_usulan, '%Y-%m')) as month"), 'form_id','tanggal_usulan')
            ->leftjoin('t_pegawai', 'id_pegawai', 'pegawai_id')
            ->join('t_jenis_form','id_jenis_form','form_id')
            ->join('t_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
            ->where('kategori', $form)
            ->where(DB::raw("DATE_FORMAT(t_usulan.tanggal_usulan, '%Y')"), $tahunPick)
            ->get();

        if ($form == 'oldat') {
            $result = [['Bulan', 'Pengadaan', 'Perbaikan']];
            foreach ($usulanChart as $value) {
                $month = Carbon::parse($value->month)->isoFormat('MMMM Y');
                $totalUsulan = $chartData->where('month', $value->month)->where('form_id', '201')->count();
                $totalPerbaikan = $chartData->where('month', $value->month)->where('form_id', '202')->count();

                $result[] = [$month, $totalUsulan, $totalPerbaikan];
            }
        } else if ($form == 'aadb') {
            $result = [['Bulan', 'Pengadaan', 'Perbaikan', 'Perpanjangan Stnk', 'Permintaan BBM']];
            foreach ($usulanChart as $value) {
                $month = Carbon::parse($value->month)->isoFormat('MMMM Y');
                $totalUsulan = $chartData->where('month', $value->month)->where('form_id', '101')->count();
                $totalPerbaikan = $chartData->where('month', $value->month)->where('form_id', '102')->count();
                $totalStnk = $chartData->where('month', $value->month)->where('form_id', '103')->count();
                $totalBbm = $chartData->where('month', $value->month)->where('form_id', '104')->count();

                $result[] = [$month, $totalUsulan, $totalPerbaikan, $totalStnk, $totalBbm];
            }
        } else {
            foreach ($usulanChart as $key => $value) {
                $result[] = ['Bulan', 'Total Usulan'];
                $result[++$key] = [
                    Carbon::parse($value->month)->isoFormat('MMMM Y'), $value->total_usulan
                ];
            }
        }


        $chart = json_encode($result);
        return view('pages.usulan.report', compact('tahun','tahunPick','form','usulanUker', 'usulan', 'chart'));
    }

    // ===========================================
    //              KONFIRMASI USULAN
    // ===========================================

    public function createVerification($form, $id)
    {
        User::where('id', Auth::user()->id)->update([
            'sess_modul'   => $form,
            'sess_form_id' => $id
        ]);

        return view('pages.usulan.verifikasi.index');
    }

    public function storeVerification(Request $request, $userId)
    {
        $bulan  = Carbon::now()->isoFormat('MM');
        $tahun  = Carbon::now()->isoFormat('Y');
        $usulan = Usulan::join('t_jenis_form','id_jenis_form','form_id')
                    ->where('id_usulan', Auth::user()->sess_form_id)
                    ->first();

        $modul  = Auth::user()->sess_modul == 'oldat' ? 'odt' : (Auth::user()->sess_modul == 'aadb' ? 'adb' : Auth::user()->sess_modul);
        if ($usulan->status_proses_id == 101) {
            Usulan::where('id_usulan', $usulan->id_usulan)->update([
                'otp_1' => $request->one_time_password
            ]);
        }

        if ($usulan->status_proses_id == 102) {
            $form    = FormUsulan::where('id_jenis_form', $usulan->form_id)->first();
            $total   = Usulan::where('nomor_usulan','!=','-')->where('form_id', $usulan->form_id)
                       ->where(DB::raw("DATE_FORMAT(t_usulan.tanggal_usulan, '%Y')"), $tahun)->count();
            $format  = $total + 1; // str_pad($total + 1, 4, 0, STR_PAD_LEFT);
            $nomorUsulan = $form->klasifikasi.'/'.$usulan->form_id.'/'.$format.'/'.$tahun;

            Usulan::where('id_usulan', $usulan->id_usulan)->update([
                'nomor_usulan'        => strtoupper($nomorUsulan),
                'status_pengajuan_id' => 1,
                'status_proses_id'    => 103,
                'otp_2'               => $request->one_time_password
            ]);
        }

        if ($usulan->status_proses_id == 103) {
            Usulan::where('id_usulan', $usulan->id_usulan)->update([
                'status_proses_id' => 105,
                'otp_3' => rand(000000,999999)
            ]);
            $totalBast = BeritaAcara::withTrashed()->where(DB::raw("DATE_FORMAT(tanggal_bast, '%Y')"), $tahun)->count();
            $total     = BeritaAcara::join('t_usulan','id_usulan','usulan_id')
                        ->join('t_jenis_form','id_jenis_form','form_id')
                        ->where('kategori', $usulan->kategori)
                        ->withTrashed()
                        ->count();

            $id_bast   = $totalBast + 1;
            $nomorBast = $usulan->klasifikasi.'/A.VII.1.2/'.$usulan->form_id.'/'.$id_bast.'/'.$tahun;

            $bast = new BeritaAcara();
            $bast->id_bast      = $id_bast;
            $bast->usulan_id    = $usulan->id_usulan;
            $bast->nomor_bast   = $nomorBast;
            $bast->tanggal_bast = Carbon::now();
            $bast->save();

            if ($usulan->form_id == 501) {
                $detail = UsulanUkt::select('id_ukt as id_detail')->where('usulan_id', $usulan->id_usulan)->get();
            } else if ($usulan->form_id == 401) {
                $detail = UsulanGdn::select('id_gdn as id_detail')->where('usulan_id', $usulan->id_usulan)->get();
            } else if ($usulan->form_id == 301) {
                $detail = UsulanAtk::select('id_permintaan as id_detail')->where('usulan_id', $usulan->id_usulan)->get();
            } else if ($usulan->form_id == 201 || $usulan->form_id == 202) {
                $detail = UsulanOldat::select('id_oldat as id_detail')->where('usulan_id', $usulan->id_usulan)->get();
            } else if ($usulan->form_id == 101) {
                $detail = UsulanAadb::select('id_aadb as id_detail')->where('usulan_id', $usulan->id_usulan)->get();
            } else if ($usulan->form_id == 102) {
                $detail = UsulanStnk::select('id_stnk as id_detail')->where('usulan_id', $usulan->id_usulan)->get();
            } else if ($usulan->form_id == 103) {
                $detail = UsulanStnk::select('id_stnk as id_detail')->where('usulan_id', $usulan->id_usulan)
                ->where('keterangan', 'true')->get();
            } else if ($usulan->form_id == 104) {
                $detail = UsulanBbm::select('id_bbm as id_detail')->where('usulan_id', $usulan->id_usulan)
                ->where('status_pengajuan', 'true')->get();
            }

            foreach ($detail as $i => $row) {
                $detailBast = new BeritaAcaraDetail();
                $detailBast->bast_id = $id_bast;
                $detailBast->usulan_detail_id = $row->id_detail;
                $detailBast->created_at = Carbon::now();
                $detailBast->save();
            }
        }

        if ($usulan->status_proses_id == 105) {
            Usulan::where('id_usulan', $usulan->id_usulan)->update([
                'status_proses_id' => 106,
                'otp_4' => $request->one_time_password,
                'otp_5' => rand(000000,999999)
            ]);
        }

        Google2FA::logout();
        return redirect()->route('usulan.detail', ['form' => Auth::user()->sess_modul, 'id' => Auth::user()->sess_form_id])->with('success', 'Berhasil Memproses Usulan');
    }

    public function exportPdf($form)
    {
        $usulan = Usulan::join('t_jenis_form','id_jenis_form','form_id')->where('t_jenis_form.kategori', $form)->get();

        return view('pages.usulan.pdf', compact('usulan','form'));
    }

    // SELECT FORM ==============================
    public function selectForm(Request $request)
    {
        $search = $request->search;

        if($search == ''){
            $form = FormUsulan::where('kategori', $request->form)->get();
        }else{
            $form = FormUsulan::where('kategori', $request->form)->where('kategori_barang', 'like', '%' .$search . '%')->get();
        }

        $response = array();

        $response[] = array(
            "id"    => "",
            "text"  => "SELURUH PENGAJUAN"
        );

        foreach($form as $data){
            $response[] = array(
                "id"    =>  $data->id_jenis_form,
                "text"  =>  strtoupper($data->nama_form)
            );
        }

        return response()->json($response);
    }

}
