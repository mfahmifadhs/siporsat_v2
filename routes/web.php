<?php

use App\Http\Controllers\AnggaranController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BeritaAcara;
use App\Http\Controllers\BeritaAcaraController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\mAadbController;
use App\Http\Controllers\MataAnggaranController;
use App\Http\Controllers\mAtkController;
use App\Http\Controllers\mGdnController;
use App\Http\Controllers\mOldatController;
use App\Http\Controllers\mUktController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\RumahdinasController;
use App\Http\Controllers\UnitkerjaController;
use App\Http\Controllers\UnitutamaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UsulanController;
use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    return view('index');
});

// Authentication
Route::get('/login', function () {
    return view('auth/login');
})->name('login');

// Public  = Akses untuk super admin, admin, super user
// Private = Akses hanya untuk super admin
// Verify  = Akses hanya untuk super user dengan id user tertentu


// Route::get('dashboard', [AuthController::class, 'dashboard']);

Route::get('masuk', [AuthController::class, 'index'])->name('masuk');
Route::get('daftar', [AuthController::class, 'daftar'])->name('daftar-user');
Route::get('keluar', [AuthController::class, 'keluar'])->name('keluar');

Route::post('login/{id}', [AuthController::class, 'postLogin'])->name('post.login');
Route::get('captcha-reload', [AuthController::class, 'reloadCaptcha']);


Route::group(['middleware' => 'auth'], function () {

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('profil/{id}', [UserController::class, 'showProfile'])->name('user.profile.show');
    Route::get('profil/reset-auth/{id}', [UserController::class, 'resetAuth'])->name('user.reset.auth');
    Route::post('bulan/select', [UserController::class, 'selectBulan']);
    Route::post('status/select', [UserController::class, 'selectStatus']);
    Route::post('unit-kerja/select', [UnitkerjaController::class, 'selectUnitkerja']);
    Route::post('kendaraan/kategori/select', [mAadbController::class, 'selectKategori']);
    Route::post('atk/select', [mAtkController::class, 'selectAtk']);
    Route::post('atk/kategori/select', [mAtkController::class, 'selectKategori']);
    Route::post('kendaraan/select', [mAadbController::class, 'selectKendaraan']);
    Route::post('profil/{id}', [UserController::class, 'confirmGoogle2fa'])->name('user.confirm.google2fa');

    Route::get('oldat', [mOldatController::class, 'index'])->name('oldat.home');
    Route::get('aadb', [mAadbController::class, 'index'])->name('aadb.home');
    Route::get('atk', [mAtkController::class, 'index'])->name('atk.home');
    Route::get('bast/detail/{form}/{id}', [BeritaAcaraController::class, 'detail'])->name('bast.detail');
    Route::get('aadb/kendaraan', [mAadbController::class, 'showAadb'])->name('aadb.kendaraan.show');
    Route::get('aadb/kategori', [mAadbController::class, 'showKategori'])->name('aadb.kategori.show');
    Route::get('aadb/detail/{id}', [mAadbController::class, 'detailAadb'])->name('aadb.kendaraan.detail');
    Route::get('oldat/barang', [mOldatController::class, 'showBarang'])->name('oldat.barang.show');
    Route::get('oldat/kategori', [mOldatController::class, 'showKategori'])->name('oldat.kategori.show');

    // atk
    Route::get('atk/daftar', [mAtkController::class, 'showAtk'])->name('atk.show');
    Route::get('atk/detail/{id}', [mAtkController::class, 'detailAtk'])->name('atk.detail');
    Route::get('atk/select/first/satuan/{selectedAtkId}', [mAtkController::class, 'selectFirstAtk']);
    Route::post('atk/keranjang/tambah', [mAtkController::class, 'storeKeranjang'])->name('atk.keranjang.store');

    Route::get('keranjang/remove/{id}', [mAtkController::class, 'removeKeranjang'])->name('atk.keranjang.remove');
    Route::get('keranjang/update/{aksi}/{id}', [mAtkController::class, 'updateKeranjang'])->name('atk.keranjang.update');

    Route::get('atk/satuan', [mAtkController::class, 'showSatuan'])->name('atk.satuan.show');
    Route::get('atk/satuan/hapus/{atkId}/{id}', [mAtkController::class, 'deleteSatuan'])->name('atk.satuan.destroy');
    Route::post('atk/satuan/tambah/{id}', [mAtkController::class, 'storeSatuan'])->name('atk.satuan.store');
    Route::post('atk/satuan/edit/{id}', [mAtkController::class, 'updateSatuan'])->name('atk.satuan.update');

    Route::get('validasi/{form}/{id}', [UsulanController::class, 'validationAtk'])->name('atk.validation');
    Route::get('validasi/edit/{form}/{id}', [UsulanController::class, 'validationAtk'])->name('atk.validation.edit');
    Route::get('atk/kategori', [mAtkController::class, 'showKategori'])->name('atk.kategori.show');
    Route::post('atk/validasi/{form}/{id}', [UsulanController::class, 'storeValidationAtk'])->name('atk.validation.store');
    Route::post('atk/penyerahan/{form}/{id}', [UsulanController::class, 'storeDeliverAtk'])->name('atk.deliver.store');


    // aadb
    Route::get('aadb/kendaraan/tambah', [mAadbController::class, 'createAadb'])->name('aadb.kendaraan.create');
    Route::get('aadb/kendaraan/edit/{id}', [mAadbController::class, 'editAadb'])->name('aadb.kendaraan.edit');
    Route::get('aadb/kendaraan/hapus/{id}', [mAadbController::class, 'deleteAadb'])->name('aadb.kendaraan.delete');
    Route::post('aadb/kendaraan', [mAadbController::class, 'showAadb'])->name('aadb.kendaraan.show');
    Route::post('aadb/kendaraan/tambah', [mAadbController::class, 'storeAadb'])->name('aadb.kendaraan.store');
    Route::post('aadb/kendaraan/edit/{id}', [mAadbController::class, 'updateAadb'])->name('aadb.kendaraan.update');
    Route::post('aadb/pengguna/proses/{id}', [mAadbController::class, 'processPengguna'])->name('aadb.pengguna.store');

    // oldat
    Route::get('oldat/barang/tambah', [mOldatController::class, 'createBarang'])->name('oldat.barang.create');
    Route::get('oldat/barang/edit/{id}', [mOldatController::class, 'editBarang'])->name('oldat.barang.edit');
    Route::get('oldat/barang/detail/{id}', [mOldatController::class, 'detailBarang'])->name('oldat.barang.detail');
    Route::get('oldat/barang', [mOldatController::class, 'showBarang'])->name('oldat.barang.show');
    Route::get('oldat/barang/hapus/{id}', [mOldatController::class, 'deleteBarang'])->name('oldat.barang.delete');
    Route::post('oldat/barang', [mOldatController::class, 'filterBarang'])->name('oldat.barang.filter');
    Route::post('oldat/barang/edit/{id}', [mOldatController::class, 'updateBarang'])->name('oldat.barang.update');
    Route::post('oldat/barang/tambah', [mOldatController::class, 'storeBarang'])->name('oldat.barang.store');
    Route::post('oldat/pengguna/proses/{id}', [mOldatController::class, 'processPengguna'])->name('oldat.pengguna.store');
    Route::post('oldat/barang/daftar', [mOldatController::class, 'selectBarang'])->name('oldat.select');
    Route::post('oldat/kategori/daftar', [mOldatController::class, 'selectKategori'])->name('oldat.kategori.select');


    Route::get('rumahtangga', [mUktController::class, 'index'])->name('ukt.home');
    Route::get('anggaran/realisasi/{form}/{ukerId}', [AnggaranController::class, 'showRealisasi'])->name('realisasi.show');
    Route::get('gedung-bangunan', [mGdnController::class, 'index'])->name('gdn.home');
    Route::post('gedung-bangunan/bperbaikan', [mGdnController::class, 'showBidangPerbaikan'])->name('gdn.bperbaikan');

    // Usulan
    Route::get('usulan/{form}', [UsulanController::class, 'showUsulan'])->name('usulan.show');
    Route::get('usulan/detail/{form}/{id}', [UsulanController::class, 'detailUsulan'])->name('usulan.detail');
    Route::get('usulan/edit/{form}/{id}', [UsulanController::class, 'editUsulan'])->name('usulan.edit');
    Route::get('usulan/print/{form}/{id}', [UsulanController::class, 'printUsulan'])->name('usulan.print');
    Route::get('usulan/hapus/{form}/{id}', [UsulanController::class, 'deleteUsulan'])->name('usulan.delete');
    Route::get('usulan/laporan/{form}', [UsulanController::class, 'reportUsulan'])->name('usulan.report');
    Route::get('usulan/verif/{form}/{id}', [UsulanController::class, 'createVerification'])->name('usulan.verif.create');
    Route::get('usulan/export/pdf/{form}', [UsulanController::class, 'exportPdf'])->name('usulan.export.pdf');
    Route::get('bast/print/{form}/{id}', [UsulanController::class, 'printBast'])->name('bast.print');
    Route::get('bast/edit/{form}/{id}', [UsulanController::class, 'editBast'])->name('bast.edit');

    // Route::post('usulan/{form}', [UsulanController::class, 'showUsulan'])->name('usulan.show');
    Route::post('usulan/{form}', [UsulanController::class, 'filterUsulan'])->name('usulan.filter');
    Route::post('usulan/edit/{form}/{id}', [UsulanController::class, 'updateUsulan'])->name('usulan.update');
    Route::post('usulan/verif/{userId}', [UsulanController::class, 'storeVerification'])->name('usulan.verif.store')->middleware('2fa');
    Route::post('bast/update/{form}/{id}', [UsulanController::class, 'updateBast'])->name('bast.update');
    Route::post('form/select', [UsulanController::class, 'selectForm']);
    Route::post('usulan/laporan/{form}', [UsulanController::class, 'reportUsulan'])->name('usulan.report');

    // Akses User
    Route::group(['middleware' => ['access:user']], function () {
        Route::get('usulan/bast/pengusul/{form}/{id}', [UsulanController::class, 'createVerification'])->name('usulan.bast.pengusul');

        Route::get('usulan/tambah/{form}/{id}', [UsulanController::class, 'createUsulan'])->name('usulan.create');
        Route::post('usulan/tambah/{form}/{id}', [UsulanController::class, 'storeUsulan'])->name('usulan.create');
    });


    // Akses super admin ,super user dan admin user
    Route::group(['middleware' => ['access:admin']], function () {
        // Usulan
        Route::get('usulan/konfirmasi/{form}/{id}', [UsulanController::class, 'createVerification'])->name('usulan.confirm');
        Route::get('usulan/bast/kabag/{form}/{id}', [UsulanController::class, 'createVerification'])->name('usulan.bast.kabag');

        Route::get('usulan/konfirmasi/pj/{form}/{id}', [UsulanController::class, 'confirmUsulan'])->name('usulan.confirm.pj');
        Route::post('usulan/konfirmasi/pj/{form}/{id}', [UsulanController::class, 'confirmUsulan'])->name('usulan.confirm.reject');

        Route::get('usulan/proses/{form}/{id}', [AnggaranController::class, 'createRealisasi'])->name('anggaran.realisasi.create');
        Route::get('anggaran/realisasi/edit/{form}/{id}', [AnggaranController::class, 'editRealisasi'])->name('anggaran.realisasi.edit');
        Route::get('anggaran/realisasi/hapus/{form}/{id}', [AnggaranController::class, 'deleteRealisasi'])->name('anggaran.realisasi.delete');
        Route::post('anggaran/realisasi/tambah/{form}/{id}', [AnggaranController::class, 'storeRealisasi'])->name('anggaran.realisasi.store');
        Route::post('anggaran/realisasi/edit/{form}/{id}', [AnggaranController::class, 'updateRealisasi'])->name('anggaran.realisasi.update');


    });

    // Akses super admin
    Route::group(['middleware' => ['access:private']], function () {

        // Aadb
        Route::get('aadb/kategori/tambah', [mAadbController::class, 'createKategori'])->name('aadb.kategori.create');
        Route::get('aadb/kategori/edit/{id}', [mAadbController::class, 'editKategori'])->name('aadb.kategori.edit');
        Route::get('aadb/kategori/hapus/{id}', [mAadbController::class, 'deleteKategori'])->name('aadb.kategori.delete');
        Route::post('aadb/kategori/tambah', [mAadbController::class, 'storeKategori'])->name('aadb.kategori.store');
        Route::post('aadb/kategori/edit/{id}', [mAadbController::class, 'updateKategori'])->name('aadb.kategori.update');
        // Anggaran
        Route::get('anggaran/alokasi', [AnggaranController::class, 'showAlokasi'])->name('alokasi_anggaran.show');
        Route::get('anggaran/alokasi/edit/{$id}', [AnggaranController::class, 'showAlokasi'])->name('alokasi_anggaran.edit');
        Route::get('anggaran/alokasi/delete/{$id}', [AnggaranController::class, 'showAlokasi'])->name('alokasi_anggaran.delete');
        Route::get('anggaran/alokasi/{id}', [AnggaranController::class, 'detailAlokasi'])->name('alokasi_anggaran.detail');

        // Mata Anggaran
        Route::get('mata-anggaran', [MataAnggaranController::class, 'show'])->name('mata_anggaran.show');
        Route::get('mata-anggaran/tambah/{id}', [MataAnggaranController::class, 'create'])->name('mata_anggaran.create');
        Route::get('mata-anggaran/edit/{ctg}/{id}', [MataAnggaranController::class, 'edit'])->name('mata_anggaran.edit');
        Route::post('mata-anggaran/edit/{ctg}/{id}', [MataAnggaranController::class, 'update'])->name('mata_anggaran.edit');
        Route::post('mata-anggaran/tambah/{id}', [MataAnggaranController::class, 'store'])->name('mata_anggaran.post');
        Route::post('mata-anggaran/select/{id}', [MataAnggaranController::class, 'selectMataAnggaran']);
        Route::post('kategori-anggaran/select/{id}', [MataAnggaranController::class, 'selectKategoriAnggaran']);

        // User atau pengguna
        Route::get('pengguna', [UserController::class, 'show'])->name('user.show');
        Route::get('pengguna/detail/{id}', [UserController::class, 'detail'])->name('user.detail');
        Route::get('pengguna/tambah', [UserController::class, 'create'])->name('user.create');
        Route::get('pengguna/edit/{id}', [UserController::class, 'edit'])->name('user.edit');
        Route::get('pengguna/hapus/{id}', [UserController::class, 'delete'])->name('user.delete');
        Route::post('pengguna/tambah', [UserController::class, 'store'])->name('user.post');
        Route::post('pengguna/edit/{id}', [UserController::class, 'update'])->name('user.edit');

        // Pegawai
        Route::get('pegawai', [PegawaiController::class, 'show'])->name('pegawai.show');
        Route::get('pegawai/select/{id}', [PegawaiController::class, 'selectPegawai']);
        Route::get('pegawai/detail/{id}', [PegawaiController::class, 'detail'])->name('pegawai.detail');
        Route::get('pegawai/tambah', [PegawaiController::class, 'create'])->name('pegawai.create');
        Route::get('pegawai/edit/{id}', [PegawaiController::class, 'edit'])->name('pegawai.edit');
        Route::get('pegawai/hapus/{id}', [PegawaiController::class, 'delete'])->name('pegawai.delete');
        Route::post('pegawai/tambah', [PegawaiController::class, 'store'])->name('pegawai.post');
        Route::post('pegawai/edit/{id}', [PegawaiController::class, 'update'])->name('pegawai.edit');

        // Unit Kerja
        Route::get('unit-kerja', [UnitkerjaController::class, 'show'])->name('unit_kerja.show');
        Route::get('unit-kerja/tambah', [UnitkerjaController::class, 'create'])->name('unit_kerja.create');
        Route::get('unit-kerja/edit/{id}', [UnitkerjaController::class, 'edit'])->name('unit_kerja.edit');
        Route::get('unit-kerja/hapus/{id}', [UnitkerjaController::class, 'delete'])->name('unit_kerja.delete');
        Route::post('unit-kerja/tambah', [UnitkerjaController::class, 'store'])->name('unit_kerja.store');
        Route::post('unit-kerja/edit/{id}', [UnitkerjaController::class, 'update'])->name('unit_kerja.edit');

        // Unit Utama
        Route::get('unit-utama', [UnitutamaController::class, 'show'])->name('unit_utama.show');
        Route::get('unit-utama/tambah', [UnitutamaController::class, 'create'])->name('unit_utama.create');
        Route::get('unit-utama/edit/{id}', [UnitutamaController::class, 'edit'])->name('unit_utama.edit');
        Route::get('unit-utama/hapus/{id}', [UnitutamaController::class, 'delete'])->name('unit_utama.delete');
        Route::post('unit-utama/tambah', [UnitutamaController::class, 'store'])->name('unit_utama.store');
        Route::post('unit-utama/edit/{id}', [UnitutamaController::class, 'update'])->name('unit_utama.edit');
    });

    // Akses seluruh user
    Route::group(['middleware' => ['access:public']], function () {
        // oldat
        Route::get('oldat/kategori/tambah', [mOldatController::class, 'createKategori'])->name('oldat.kategori.create');
        Route::get('oldat/kategori/edit/{id}', [mOldatController::class, 'editKategori'])->name('oldat.kategori.edit');
        Route::get('oldat/kategori/hapus/{id}', [mOldatController::class, 'deleteKategori'])->name('oldat.kategori.delete');
        Route::post('oldat/kategori/tambah', [mOldatController::class, 'storeKategori'])->name('oldat.kategori.create');
        Route::post('oldat/kategori/edit/{id}', [mOldatController::class, 'updateKategori'])->name('oldat.kategori.edit');
    });

    // Akses untuk PSEDIA
    Route::group(['middleware' => ['access:psedia']], function () {
        // ATK
        Route::get('atk/stockop', [mAtkController::class, 'showStockop'])->name('atk.stockop.show');
        Route::get('atk/stockop/riwayat', [mAtkController::class, 'historyStockop'])->name('atk.stockop.history');
        Route::get('atk/stockop/remove/{stockopId}/{id}', [mAtkController::class, 'removeStockop'])->name('atk.stockop.remove');
        Route::get('atk/stockop/update/{aksi}/{id}', [mAtkController::class, 'updateStockop'])->name('atk.stockop.update');
        Route::post('atk/stockop/update/{aksi}/{id}', [mAtkController::class, 'updateStockop'])->name('atk.stockop.update');
        Route::post('atk/stockop', [mAtkController::class, 'storeStockop'])->name('atk.stockop.store');

        Route::get('atk/kategori/hapus/{id}', [mAtkController::class, 'deleteKategori'])->name('atk.kategori.destroy');
        Route::post('atk/kategori/tambah', [mAtkController::class, 'storeKategori'])->name('atk.kategori.store');
        Route::post('atk/kategori/edit/{id}', [mAtkController::class, 'updateKategori'])->name('atk.kategori.update');
        Route::post('atk/tambah', [mAtkController::class, 'storeAtk'])->name('atk.store');
        Route::post('atk/upload', [mAtkController::class, 'uploadAtk'])->name('atk.upload.store');
        Route::post('atk/edit/{id}', [mAtkController::class, 'updateAtk'])->name('atk.update');
        Route::get('atk/hapus/{id}', [mAtkController::class, 'deleteAtk'])->name('atk.destroy');
    });

    // Rumah Dinas
    Route::get('rumah-dinas', [RumahdinasController::class, 'showRumah'])->name('rumah_dinas.show');
    Route::get('rumah-dinas/detail/{id}', [RumahdinasController::class, 'detailRumah'])->name('rumah_dinas.detail');
    Route::get('rumah-dinas/tambah', [RumahdinasController::class, 'createRumah'])->name('rumah_dinas.create');
    Route::get('rumah-dinas/edit/{id}', [RumahdinasController::class, 'editRumah'])->name('rumah_dinas.edit');
    Route::get('rumah-dinas/hapus/{id}', [RumahdinasController::class, 'deleteRumah'])->name('rumah_dinas.delete');
    Route::get('rumah-dinas/edit-foto/{id}', [RumahdinasController::class, 'editFoto'])->name('rumah_dinas.foto.edit');
    Route::post('rumah-dinas/edit/{id}', [RumahdinasController::class, 'updateRumah'])->name('rumah_dinas.edit');
    Route::post('rumah-dinas/tambah', [RumahdinasController::class, 'storeRumah'])->name('rumah_dinas.post');
    Route::post('rumah-dinas/edit-foto/{id}', [RumahdinasController::class, 'updateFoto'])->name('rumah_dinas.foto.edit');
    Route::get('rumah-dinas/hapus-foto/{id}', [RumahdinasController::class, 'deleteFoto'])->name('rumah_dinas.foto.hapus');
    Route::get('rumah-dinas/export', [RumahdinasController::class, 'exportRumah'])->name('rumah_dinas.export');

    Route::get('rumah-dinas/penghuni/{id}', [RumahdinasController::class, 'createPenghuni'])->name('penghuni.create');
    Route::post('rumah-dinas/penghuni/{id}', [RumahdinasController::class, 'storePenghuni'])->name('penghuni.create');
    Route::get('rumah-dinas/penghuni/edit/{id}', [RumahdinasController::class, 'editPenghuni'])->name('penghuni.edit');
    Route::post('rumah-dinas/penghuni/edit/{id}', [RumahdinasController::class, 'updatePenghuni'])->name('penghuni.edit');








});
