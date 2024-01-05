@extends('layout.app')

@section('content')

<!-- Content Header -->
<section class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="text-capitalize">SIPORSAT</h1>
                <h5>Sistem Pengelolaan Operasional Perkantoran Terpusat</h5>
                <ol class="breadcrumb text-capitalize">
                    <li class="breadcrumb-item"><a href="{{ route($form.'.home') }}">Beranda</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('usulan.show', $form) }}">Daftar Usulan</a></li>
                    <li class="breadcrumb-item">Edit Usulan</li>
                </ol>
            </div>
            <div class="col-sm-6 mt-3">
                <div class="form-group text-right">
                    <a href="{{ route('usulan.show', $form) }}" class="btn btn-add">
                        <span class="btn btn-primary rounded-0 mr-1"><i class="fas fa-arrow-left"></i></span>
                        <small>Kembali</small>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Content Header -->

<section class="content">
    <div class="container">
        @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p style="color:white;margin: auto;">{{ $message }}</p>
        </div>
        @endif
        @if ($message = Session::get('failed'))
        <div class="alert alert-danger">
            <p style="color:white;margin: auto;">{{ $message }}</p>
        </div>
        @endif

        <div class="card" style="border-radius: 10px;">
            <div class="card-header text-center font-weight-bold">
                Edit Usulan Alat Angkutan Darat Bermotor (AADB)
            </div>
            <div class="card-header">
                <ul class="nav" id="tab" role="tablist">
                    @if ($usulan->form_id == 101)
                    <li class="nav-item mr-2">
                        <a class="btn btn-default border-secondary bg-primary active" id="pengadaan-tab" data-toggle="pill" href="#pengadaan" role="tab" ls="pengadaan" aria-selected="true">
                            Usulan Pengadaan
                        </a>
                    </li>
                    @elseif ($usulan->form_id == 102)
                    <li class="nav-item">
                        <a class="btn btn-default mr-2 border-secondary bg-primary active" id="perbaikan-tab" data-toggle="pill" href="#perbaikan" role="tab" aria-controls="perbaikan" aria-selected="false">
                            Usulan Perbaikan
                        </a>
                    </li>
                    @elseif ($usulan->form_id == 103)
                    <li class="nav-item">
                        <a class="btn btn-default mr-2 border-secondary bg-primary active" id="stnk-tab" data-toggle="pill" href="#stnk" role="tab" aria-controls="stnk" aria-selected="false">
                            Usulan Perpanjang STNK
                        </a>
                    </li>
                    @else
                    <li class="nav-item">
                        <a class="btn btn-default mr-2 border-secondary bg-primary active" id="bbm-tab" data-toggle="pill" href="#bbm" role="tab" aria-controls="bbm" aria-selected="false">
                            Usulan Voucher BBMN
                        </a>
                    </li>
                    @endif
                </ul>
            </div>
            <div class="tab-content" id="tabContent">
                @if ($usulan->form_id == 101)
                <!-- Usulan Pengadaan -->
                <div class="tab-pane fade active show" id="pengadaan" role="tabpanel" aria-labelledby="pengadaan-tab">
                    <form id="form-pengadaan" action="{{ route('usulan.update', ['form' => $form, 'id' => $id]) }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <label class="text-muted">Usulan Pengadaan AADB</label>
                            <h6 class="text-muted small">Mohon untuk melengkapi rencana pemakaian dan spesifikasi barang yang dibutuhkan.</h6>

                            <input type="hidden" name="kode_form" value="101">
                            <div class="form-group row mt-4">
                                <label class="col-md-2 col-form-label">Jenis Pengadaan</label>
                                <div class="col-md-9 mb-2">
                                    <select name="jenis_aadb" class="form-control">
                                        <option value="sewa">Sewa</option>
                                    </select>
                                </div>
                                <label class="col-md-2 col-form-label">Rencana Pengguna</label>
                                <div class="col-md-9 mb-2">
                                    <textarea type="text" class="form-control" name="keterangan" placeholder="Contoh : Untuk operasional kantor">{{ $usulan->keterangan }}</textarea>
                                </div>
                            </div>
                            @foreach($usulan->usulanAadb as $i => $row)
                            <div class="form-group row mt-2">
                                <input type="hidden" name="aadb_id[]" value="{{ $row->id_aadb }}">
                                <label class="col-md-12 text-muted">Kendaraan {{ $i + 1 }}</label>
                                <label class="col-md-2 col-form-label mb-3">Kendaraan*</label>
                                <div class="col-md-9 mb-3">
                                    <select class="form-control select-kategori" name="kendaraan_id[]" style="width: 100%;" required>
                                        <option value="{{ $row->kendaraan_id }}">{{ $row->aadb->kategori_aadb }}</option>
                                    </select>
                                </div>

                                <label class="col-md-2 mb-3col-form-label">Merk/Tipe*</label>
                                <div class="col-md-9 mb-3">
                                    <input type="text" class="form-control" name="merk_tipe[]" placeholder="Contoh : Honda CRV/Toyota Kijang Innova" value="{{ $row->merk_tipe }}" required>
                                </div>

                                <label class="col-md-2 mb-3 col-form-label">Kualifikasi*</label>
                                <div class="col-md-9 mb-3">
                                    <select name="kualifikasi[]" class="form-control" required>
                                        <option value="">-- Pilih Kualifikasi --</option>
                                        <option value="jabatan" <?php echo $row->kualifikasi == 'jabatan' ? 'selected' : ''?>>
                                            Kendaraan Jabatan
                                        </option>
                                        <option value="operasional" <?php echo $row->kualifikasi == 'operasional' ? 'selected' : ''?>>
                                            Kendaraan Operasional
                                        </option>
                                    </select>
                                </div>

                                <label class="col-md-2 mb-3 col-form-label">Jumlah</label>
                                <div class="col-md-3 mb-3">
                                    <input type="number" class="form-control text-center jumlah" name="jumlah_pengadaan[]" value="{{ $row->jumlah_pengadaan }}" min="1">
                                </div>

                                <label class="col-md-3 mb-3 col-form-label text-center">Tahun Kendaraan*</label>
                                <div class="col-md-3 mb-3">
                                    <input type="number" class="form-control" name="tahun[]" placeholder="Tahun Kendaraan" value="{{ $row->tahun }}" required>
                                </div>

                                <div class="col-md-2 mt-2">&ensp;</div>
                                <div class="col-md-5 mt-2">
                                    <label class="btn btn-danger btn-sm">
                                        <input type="hidden" name="hapus[{{ $i }}]" value="">
                                        <input type="checkbox" autocomplete="off" name="hapus[{{ $i }}]" value="true">
                                        <span class="align-middle small">Hapus</span>
                                    </label>
                                </div>
                            </div>
                            @endforeach

                            <div class="form-group row section-item mt-4">
                                <input type="hidden" name="aadb_id[]" disabled>
                                <label class="col-md-12 text-muted title"></label>

                                <label class="col-md-2 col-form-label mb-3 d-none">Kendaraan*</label>
                                <div class="col-md-9 mb-3 d-none">
                                    <select class="form-control select2-kategori" name="kendaraan_id[]" style="width: 100%;" disabled required>
                                        <option value="">-- Pilih Kendaraan -- </option>
                                    </select>
                                </div>

                                <label class="col-md-2 mb-3col-form-label d-none">Merk/Tipe*</label>
                                <div class="col-md-9 mb-3 d-none">
                                    <input type="text" class="form-control" name="merk_tipe[]" placeholder="Contoh : Honda CRV/Toyota Kijang Innova" disabled required>
                                </div>

                                <label class="col-md-2 mb-3 col-form-label d-none">Kualifikasi*</label>
                                <div class="col-md-9 mb-3 d-none">
                                    <select name="kualifikasi[]" class="form-control" disabled required>
                                        <option value="">-- Pilih Kualifikasi --</option>
                                        <option value="jabatan">Kendaraan Jabatan</option>
                                        <option value="operasional">Kendaraan Operasional</option>
                                    </select>
                                </div>

                                <label class="col-md-2 mb-3 col-form-label d-none">Jumlah</label>
                                <div class="col-md-3 mb-3 d-none">
                                    <input type="number" class="form-control text-center jumlah" name="jumlah_pengadaan[]" value="1" min="1" disabled>
                                </div>

                                <label class="col-md-3 mb-3 col-form-label text-center d-none">Tahun Kendaraan*</label>
                                <div class="col-md-3 mb-3 d-none">
                                    <input type="number" class="form-control" name="tahun[]" placeholder="Tahun Kendaraan" disabled required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-2">&ensp;</div>
                                <div class="col-md-5">
                                    <a href="" class="small btn btn-primary btn-xs btn-tambah-baris">
                                        <i class="fas fa-plus"></i> Tambah Baris
                                    </a>
                                    <a href="" class="small btn btn-danger btn-xs btn-hapus-baris">
                                        <i class="fas fa-times"></i> Hapus Baris
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <button type="submit" class="btn btn-primary" onclick="confirmSubmit(event, 'pengadaan')">
                                <i class="fas fa-paper-plane"></i> Submit
                            </button>
                        </div>
                    </form>
                </div>
                @endif

                @if ($usulan->form_id == 102)
                <!-- Usulan Perbaikan -->
                <div class="tab-pane fade active show" id="perbaikan" role="tabpanel" aria-labelledby="perbaikan-tab">
                    <form id="form-perbaikan" action="{{ route('usulan.update', ['form' => $form, 'id' => $id]) }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <label class="text-muted">Usulan Servis AADB</label>
                            <h6 class="text-muted small">
                                Jika kendaraan tidak muncul, pastikan masa berlaku STNK sudah dilengkapi di halaman informasi kendaraan.
                                <a href="{{ route('aadb.kendaraan.show') }}" class="text-primary">
                                    <b><u>(Daftar AADB)</u></b>
                                </a>
                            </h6>
                            @foreach($usulan->usulanStnk as $i => $row)
                            <input type="hidden" name="kode_form" value="102">
                            <input type="hidden" name="stnk_id[]" value="{{ $row->id_stnk }}">
                            <div class="form-group row mt-4">
                                <label class="col-md-12 text-muted">Kendaraan {{ $i + 1 }}</label>
                                <label class="col-md-2 col-form-label mb-3">Kendaraan*</label>
                                <div class="col-md-9 mb-3">
                                    <select class="form-control select-aadb" name="kendaraan_id[]" style="width: 100%;" required>
                                        <option value="{{ $row->kendaraan_id }}">
                                            {{ $row->aadb->no_plat.' - '.$row->aadb->merk_tipe }}
                                        </option>
                                    </select>
                                </div>
                                <label class="col-md-2 col-form-label mb-3">Kilometer</label>
                                <div class="col-md-9 mb-3">
                                    <div class="input-group">
                                        <input type="text" class="form-control input-format" name="kilometer[]" value="{{ $row->kilometer ? number_format($row->kilometer, 0, ',', '.') : '' }}" placeholder="Kilometer Sekarang">
                                        <div class="input-group-append">
                                            <span class="input-group-text border-secondary">KM</span>
                                        </div>
                                    </div>
                                </div>
                                <label class="col-md-2 col-form-label mb-3">Tanggal Servis</label>
                                <div class="col-md-4 mb-3">
                                    <input type="month" class="form-control" name="tanggal_servis[]" value="{{ $row->tanggal_servis }}">
                                    <span class="text-danger small">Bulan terakhir servis</span>
                                </div>
                                <label class="col-md-2 col-form-label mb-3 text-center">Tanggal Ganti Oli</label>

                                <div class="col-md-3 mb-3">
                                    <input type="month" class="form-control" name="tanggal_ganti_oli[]" value="{{ $row->tanggal_ganti_oli }}">
                                    <span class="text-danger small">Bulan terakhir ganti oli</span>
                                </div>
                                <label class="col-md-2 col-form-label mb-3">Keterangan*</label>
                                <div class="col-md-9 mb-3">
                                    <textarea class="form-control" name="keterangan_servis[]" rows="3"
                                    placeholder="Keterangan Servis, Contoh : Servis rutin/Ganti Oli" required>{{ $row->keterangan }}</textarea>
                                </div>
                                <div class="col-md-2 mt-2">&ensp;</div>
                                <div class="col-md-5 mt-2">
                                    <label class="btn btn-danger btn-sm">
                                        <input type="hidden" name="hapus[{{ $i }}]" value="">
                                        <input type="checkbox" autocomplete="off" name="hapus[{{ $i }}]" value="true">
                                        <span class="align-middle small">Hapus</span>
                                    </label>
                                </div>
                            </div>
                            @endforeach

                            <div class="form-group row section-perbaikan mt-3 d-none">
                                <input type="hidden" name="stnk_id[]" disabled>
                                <label class="col-md-12 text-muted title">Kendaraan</label>
                                <label class="col-md-2 col-form-label mb-3">Kendaraan*</label>
                                <div class="col-md-9 mb-2">
                                    <select class="form-control select2-aadb" name="kendaraan_id[]" style="width: 100%;" required disabled>
                                        <option value="">-- Pilih Kendaraan -- </option>
                                    </select>
                                </div>
                                <label class="col-md-2 col-form-label mb-3">Kilometer</label>
                                <div class="col-md-9 mb-3">
                                    <div class="input-group">
                                        <input type="number" class="form-control input-format" name="kilometer[]" placeholder="Kilometer Sekarang" disabled>
                                        <div class="input-group-append">
                                            <span class="input-group-text border-secondary">KM</span>
                                        </div>
                                    </div>
                                </div>
                                <label class="col-md-2 col-form-label mb-3">Tanggal Servis</label>
                                <div class="col-md-4 mb-3">
                                    <input type="month" class="form-control" name="tanggal_servis[]" disabled>
                                    <span class="text-danger small">Bulan terakhir servis</span>
                                </div>
                                <label class="col-md-2 col-form-label mb-3 text-center">Tanggal Ganti Oli</label>

                                <div class="col-md-3 mb-3">
                                    <input type="month" class="form-control" name="tanggal_ganti_oli[]" disabled>
                                    <span class="text-danger small">Bulan terakhir ganti oli</span>
                                </div>
                                <label class="col-md-2 col-form-label mb-3">Keterangan*</label>
                                <div class="col-md-9 mb-3">
                                    <textarea class="form-control" name="keterangan_servis[]" rows="3" placeholder="Keterangan Servis, Contoh : Servis rutin/Ganti Oli" disabled required></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-2">&ensp;</div>
                                <div class="col-md-5">
                                    <a href="" class="small btn btn-primary btn-xs btn-tambah-baris-perbaikan">
                                        <i class="fas fa-plus"></i> Tambah Baris
                                    </a>
                                    <a href="" class="small btn btn-danger btn-xs btn-hapus-baris-perbaikan">
                                        <i class="fas fa-times"></i> Hapus Baris
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <button type="submit" class="btn btn-primary" onclick="confirmSubmit(event, 'perbaikan')">
                                <i class="fas fa-paper-plane"></i> Submit
                            </button>
                        </div>
                    </form>
                </div>
                @endif

                @if ($usulan->form_id == 103)
                <!-- Usulan STNK -->
                <div class="tab-pane fade active show" id="stnk" role="tabpanel" aria-labelledby="stnk-tab">
                    <form id="form-stnk" action="{{ route('usulan.update', ['form' => $form, 'id' => $id]) }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <label class="text-muted">Usulan Perpanjangan STNK</label>
                            <h6 class="text-muted small">
                                Jika kendaraan tidak muncul, pastikan masa berlaku STNK sudah dilengkapi di halaman informasi kendaraan.
                                <a href="{{ route('aadb.kendaraan.show') }}" class="text-primary">
                                    <b><u>(Daftar AADB)</u></b>
                                </a>
                            </h6>

                            <div class="alert alert-info alert-dismissible mt-4 mb-4" style="font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
                                <div class="row">
                                    <div class="col-md-3"></div>
                                    <div class="col-md-3"></div>
                                </div>
                                <h6>
                                    <i class="icon fas fa-info-circle fa-1x"></i>
                                    Pilih <i class="fas fa-check-square"></i> untuk STNK kendaraan yang akan diperpanjang.
                                </h6>
                                <small>Perpanjangan STNK dilakukan secara reimburse</small>
                            </div>

                            <input type="hidden" name="kode_form" value="103">
                            <div class="form-group">
                                <label>Informasi Kendaraan</label>
                                <table class="table table-bordered text-center">
                                    <thead class="bg-secondary" style="font-size: 14px;">
                                        <tr>
                                            <th>No</th>
                                            <th>No. Plat</th>
                                            <th>Kendaraan</th>
                                            <th>Masa Berlaku STNK</th>
                                            <th class="text-center">
                                                @php
                                                $allRow = Auth::user()->pegawai->unitKerja->aadb->where('jenis_aadb','bmn')->where('tanggal_stnk', '!=', '')->count() == $usulan->usulanStnk->count() ? 'checked' : '';
                                                @endphp
                                                <input type="checkbox" class="mr-1 align-middle" id="selectAll" style="scale: 1.7;" <?php echo $allRow; ?>>
                                                Pilih Semua
                                            </th>
                                        </tr>
                                    </thead>
                                    @php $no = 0; @endphp
                                    <tbody style="font-size: 13px;">
                                        @foreach (Auth::user()->pegawai->unitKerja->aadb->where('jenis_aadb','bmn')->where('tanggal_stnk', '!=', '')->sortBy('tanggal_stnk') as $row)
                                        @php $i = $loop->index; @endphp
                                        <tr>
                                            <td>{{ $i + 1 }}</td>
                                            <td>
                                                <input type="hidden" name="kendaraan_id[]" value="{{ $row->id_kendaraan }}">
                                                {{ $row->no_plat }}
                                            </td>
                                            <td class="text-left">
                                                {{ ucwords(strtolower($row->kategori->kategori_aadb)) }} <br>
                                                {{ $row->merk_tipe.' '.$row->tahun }}
                                            </td>
                                            <td>
                                                <input type="hidden" name="tanggal_stnk[]" value="{{ $row->tanggal_stnk }}">
                                                {{ \Carbon\carbon::parse($row->tanggal_stnk)->isoFormat('DD MMMM Y') }}
                                            </td>
                                            <td>
                                                <input type="hidden" value="false" name="status[{{$i}}]">
                                                @php
                                                $rowCheck = $usulan->usulanStnk->where('kendaraan_id', $row->id_kendaraan);
                                                @endphp

                                                @if($rowCheck->count() > 0)
                                                @foreach ($rowCheck as $subRow)
                                                <input type="hidden" name="stnk_id[]" value="{{ $subRow->id_stnk }}">
                                                <input type="checkbox" class="confirm-check" style="scale: 1.7;" name="status[{{$i}}]" id="checkbox_id{{$i}}" value="true" @if ($subRow->keterangan == 'true') checked @endif>
                                                @endforeach
                                                @else
                                                <!-- Tampilkan checkbox walaupun tidak ada elemen yang berelasi -->
                                                <input type="hidden" name="stnk_id[]" value="">
                                                <input type="checkbox" class="confirm-check" style="scale: 1.7;" name="status[{{$i}}]" id="checkbox_id{{$i}}" value="true">
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <button type="submit" class="btn btn-primary" onclick="confirmSubmit(event, 'stnk')">
                                <i class="fas fa-paper-plane"></i> Submit
                            </button>
                        </div>
                    </form>
                </div>
                @endif

                @if ($usulan->form_id == 104)
                <!-- Usulan Voucher BBM -->
                <div class="tab-pane fade active show" id="bbm" role="tabpanel" aria-labelledby="bbm-tab">
                    <form id="form-bbm" action="{{ route('usulan.update', ['form' => $form, 'id' => $id]) }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <label class="text-muted">Usulan Permintaan Voucher BBM</label>
                            <h6 class="text-muted small">Mohon pastikan informasi nomor plat kendaraan sudah dilengkapi.</h6>

                            <div class="alert alert-info alert-dismissible mt-4 mb-4" style="font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
                                <div class="row">
                                    <div class="col-md-3"></div>
                                    <div class="col-md-3"></div>
                                </div>
                                <h6>
                                    <i class="icon fas fa-info-circle fa-1x"></i>
                                    Pengajuan Voucher BBM untuk bulan berikutnya, maksimal tanggal 20 bulan berjalan
                                </h6>
                                <small> 1. Kendaraan Jabatan : Rp 2.000.000, 2. Kendaraan Operasional : Rp 1.500.000, 3. Kendaraan Bermotor : Rp 200.000</small>
                            </div>

                            <input type="hidden" name="kode_form" value="104">
                            <div class="form-group">
                                <label>Bulan Pengadaan</label>
                                <input type="month" class="form-control" name="bulan_pengadaan" value="{{ $usulan->usulanBbm->pluck('bulan_pengadaan')->first() }}" required>
                            </div>
                            <div class="form-group">
                                <label>Informasi Kendaraan</label>
                                <table class="table table-bordered text-center">
                                    <thead class="bg-secondary" style="font-size: 14px;">
                                        <tr>
                                            <th>No</th>
                                            <th>Jenis AADB</th>
                                            <th>No. Plat</th>
                                            <th>Kendaraan</th>
                                            <th>Kualifikasi</th>
                                            <th class="text-center">
                                                @php
                                                $allRow = Auth::user()->pegawai->unitKerja->aadb->count() == $usulan->usulanBbm->count() ? 'checked' : '';
                                                @endphp
                                                <input type="checkbox" class="mr-1 align-middle" id="selectAll" style="scale: 1.7;" <?php echo $allRow; ?>>
                                                Pilih Semua
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody style="font-size: 13px;">
                                        @foreach (Auth::user()->pegawai->unitKerja->aadb as $i => $row)
                                        <tr>
                                            <td>{{ $i + 1 }}</td>
                                            <td>
                                                <input type="hidden" name="kendaraan_id[]" value="{{ $row->id_kendaraan }}">
                                                {{ ucwords($row->jenis_aadb) }}
                                            </td>
                                            <td class="text-left">{{ $row->no_plat }}</td>
                                            <td class="text-left">{{ ucwords(strtolower($row->kategori->kategori_aadb)) }} <br> {{ $row->merk_tipe }}</td>
                                            <td>{{ ucwords($row->kualifikasi) }}</td>
                                            <td>
                                                <input type="hidden" value="false" name="status_pengajuan[{{$i}}]">
                                                @php
                                                $rowCheck = $usulan->usulanBbm->where('kendaraan_id', $row->id_kendaraan);
                                                @endphp

                                                @if($rowCheck->count() > 0)
                                                @foreach ($rowCheck as $subRow)
                                                <input type="hidden" name="bbm_id[]" value="{{ $subRow->id_bbm }}">
                                                <input type="checkbox" class="confirm-check" style="scale: 1.7;" name="status_pengajuan[{{$i}}]" id="checkbox_id{{$i}}" value="true" @if ($subRow->status_pengajuan == 'true') checked @endif>
                                                @endforeach
                                                @else
                                                <!-- Tampilkan checkbox walaupun tidak ada elemen yang berelasi -->
                                                <input type="hidden" name="bbm_id[]" value="">
                                                <input type="checkbox" class="confirm-check" style="scale: 1.7;" name="status_pengajuan[{{$i}}]" id="checkbox_id{{$i}}" value="true">
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <button type="submit" class="btn btn-primary" onclick="confirmSubmit(event, 'bbm')">
                                <i class="fas fa-paper-plane"></i> Submit
                            </button>
                        </div>
                    </form>
                </div>
                @endif
            </div>

        </div>
    </div>
</section>

@section('js')
<script>
    $(function() {
        $(".kategori").select2()
        $(".select").select2()
        $(".select-barang").select2()

        $("#table-show").DataTable({
            "responsive": false,
            "lengthChange": true,
            "autoWidth": true,
            "info": true,
            "paging": true,
            "searching": true
        }).buttons().container().appendTo('#table-show_wrapper .col-md-6:eq(0)')

        $('.input-format').on('input', function() {
            // Menghapus karakter selain angka (termasuk tanda titik koma sebelumnya)
            var value = $(this).val().replace(/[^0-9]/g, '');

            // Format dengan menambahkan titik koma setiap tiga digit
            var formattedValue = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

            // Memasukkan nilai yang sudah diformat kembali ke input
            $(this).val(formattedValue);
        });
    })

    function confirmSubmit(event, aksi) {
        event.preventDefault();

        const form = document.getElementById('form-' + aksi);
        const requiredInputs = form.querySelectorAll('input[required]:not(:disabled), select[required]:not(:disabled), textarea[required]:not(:disabled)');

        let allInputsValid = true;

        requiredInputs.forEach(input => {
            if (input.value.trim() === '') {
                input.style.borderColor = 'red';
                allInputsValid = false;
            } else {
                input.style.borderColor = '';
            }
        });

        if (allInputsValid) {
            Swal.fire({
                title: 'Simpan Perubahan?',
                text: '',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, simpan!',
                cancelButtonText: 'Batal!',
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        } else {
            Swal.fire({
                title: 'Error',
                text: 'Ada input yang diperlukan yang belum diisi.',
                icon: 'error'
            });
        }
    }
</script>

<!-- Form Pengadaan -->
<script>
    $(function() {
        $('.select-kategori').select2({
            ajax: {
                url: "{{ url('kendaraan/kategori/select') }}",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        _token: CSRF_TOKEN,
                        search: params.term // search term
                    }
                },
                processResults: function(response) {
                    return {
                        results: response
                    }
                },
                cache: true
            }
        })

        $(document).on('click', '.btn-tambah-baris', function(e) {
            e.preventDefault();
            let index = <?php echo count($usulan->usulanAadb) ?>;
            var container = $('.section-item');
            var templateRow = $('.section-item').first().clone();
            templateRow.find('.d-none').removeClass('d-none');
            templateRow.find('[disabled]').removeAttr('disabled');
            templateRow.find(':input').val('');
            templateRow.find('.jumlah').val('1');
            templateRow.find('.title').text('Kendaraan ' + (index + container.length));
            $('.section-item:last').after(templateRow);
            toggleHapusBarisButton();

            templateRow.find('.select2-kategori').select2({
                ajax: {
                    url: "{{ url('kendaraan/kategori/select') }}",
                    type: "post",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            _token: CSRF_TOKEN,
                            search: params.term // search term
                        }
                    },
                    processResults: function(response) {
                        return {
                            results: response
                        }
                    },
                    cache: true
                }
            });
        });

        $(document).on('click', '.btn-hapus-baris', function(e) {
            e.preventDefault();
            var container = $('.section-item');
            if (container.length > 1) {
                $(this).closest('.form-group').prev('.section-item').remove();
                toggleHapusBarisButton();
            } else {
                alert('Minimal harus ada satu baris.');
            }
        });

        // Inisialisasi tombol "Hapus Baris" saat halaman dimuat
        $('.btn-hapus-baris').toggle($('.section-item').length > 1);

        function toggleHapusBarisButton() {
            var container = $('.section-item');
            var btnHapusBaris = $('.btn-hapus-baris');
            btnHapusBaris.toggle(container.length > 1);
        }

    })
</script>

<!-- Perbaikan -->
<script>
    let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content')
    $(function() {
        $('.select-aadb').select2({
            ajax: {
                url: "{{ url('kendaraan/select') }}",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        _token: CSRF_TOKEN,
                        search: params.term // search term
                    }
                },
                processResults: function(response) {
                    return {
                        results: response
                    }
                },
                cache: true
            }
        })
    })

    $(document).on('click', '.btn-tambah-baris-perbaikan', function(e) {
        e.preventDefault();
        let index = <?php echo count($usulan->usulanStnk) ?>;
        var container = $('.section-perbaikan');
        var templateRow = $('.section-perbaikan').first().clone();
        templateRow.removeClass('d-none');
        templateRow.find('[disabled]').removeAttr('disabled');
        templateRow.find(':input').val('');
        templateRow.find('.jumlah').val('1');
        templateRow.find('.title').text('Kendaraan ' + (index + container.length));
        $('.section-perbaikan:last').after(templateRow);
        toggleHapusBarisButton();

        templateRow.find('.input-format').on('input', function() {
            var value = $(this).val().replace(/[^0-9]/g, '');
            var formattedValue = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            $(this).val(formattedValue);
        });

        templateRow.find('.select2-aadb').select2({
            ajax: {
                url: "{{ url('kendaraan/select') }}",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        _token: CSRF_TOKEN,
                        search: params.term // search term
                    }
                },
                processResults: function(response) {
                    return {
                        results: response
                    }
                },
                cache: true
            }
        });

        templateRow.find('.select2-merktipe').select2({
            // Konfigurasi Select2 untuk elemen .select2-merktipe
        });
    });

    $(document).on('click', '.btn-hapus-baris-perbaikan', function(e) {
        e.preventDefault();
        var container = $('.section-perbaikan');
        if (container.length > 1) {
            $(this).closest('.form-group').prev('.section-perbaikan').remove();
            toggleHapusBarisButton();
        } else {
            alert('Minimal harus ada satu baris.');
        }
    });

    // Inisialisasi tombol "Hapus Baris" saat halaman dimuat
    $('.btn-hapus-baris-perbaikan').toggle($('.section-perbaikan').length > 1);

    function toggleHapusBarisButton() {
        var container = $('.section-perbaikan');
        var btnHapusBaris = $('.btn-hapus-baris-perbaikan');
        btnHapusBaris.toggle(container.length > 1);
    }
</script>
@endsection


@endsection
