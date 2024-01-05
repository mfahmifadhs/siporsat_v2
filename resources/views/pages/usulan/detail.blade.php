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
                    <li class="breadcrumb-item">Detail Usulan</li>
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
                Detail Usulan {{ $usulan->form->nama_form }}
            </div>
            <div class="card-header">
                <div class="timeline-steps aos-init aos-animate" data-aos="fade-up">
                    @foreach ($status as $row)
                    @if ($row->id_status != 100)
                    <div class="timeline-step">
                        <div class="timeline-content">
                            @php $status_id = $usulan->status_proses_id; @endphp

                            @if ($row->id_status == $status_id && $status_id != 106)
                            <i class="fas fa-dot-circle fa-2x text-danger"></i>
                            @elseif ($status_id > $row->id_status  || $status_id == 106)
                            <i class="fas fa-dot-circle fa-2x text-success"></i>
                            @elseif ($status_id < $row->id_status)
                            <i class="fas fa-dot-circle fa-2x text-secondary"></i>
                            @endif
                            <p class="text-muted mb-0 mb-lg-0 mt-2">{{ $row->nama_status }}</p>
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>
            <div class="card-body">
                <!-- <ul class="nav mb-3" id="tab" role="tablist">
                    <li class="nav-item mr-2">
                        <a class="btn btn-default active bg-primary border-secondary" id="usulan-tab" data-toggle="pill" href="#usulan" role="tab" ls="usulan" aria-selected="true">
                            Informasi Usulan
                        </a>
                    </li>
                    @if ($usulan->realisasi->count() != 0)
                    <li class="nav-item">
                        <a class="btn btn-default mr-2 border-secondary " id="realisasi-tab" data-toggle="pill" href="#realisasi" role="tab" aria-controls="realisasi" aria-selected="false">
                            Realisasi
                        </a>
                    </li>
                    @endif
                    @if ($usulan->bast->count() != 0 && $form == 'atk')
                    <li class="nav-item">
                        <a class="btn btn-default mr-2 border-secondary " id="bast-tab" data-toggle="pill" href="#bast" role="tab" aria-controls="bast" aria-selected="false">
                            Berita Acara
                        </a>
                    </li>
                    @endif
                </ul> -->
                <div class="tab-content" id="tabContent">
                    <!-- Informasi Usulan -->
                    <div class="tab-pane fade active show" id="usulan" role="tabpanel" aria-labelledby="usulan-tab">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-md-12 text-muted mb-3">Informasi Pengusul</label>
                                    <div class="col-md-4">ID</div>:
                                    <div class="col-md-7">{{ $usulan->id_usulan }}</div>
                                    <div class="col-md-4">Tanggal Usulan</div>:
                                    <div class="col-md-7">
                                        {{ \Carbon\carbon::parse($usulan->tanggal_usulan)->isoFormat('DD MMMM Y') }}
                                    </div>
                                    <div class="col-md-4">Nomor Surat</div>:
                                    <div class="col-md-7">{{ $usulan->nomor_usulan }}</div>
                                    @if ($usulan->form_id != 401 && $usulan->form_id != 501)
                                    <div class="col-md-4">Perihal</div>:
                                    <div class="col-md-7">
                                        {{ $usulan->form->nama_form }}
                                    </div>
                                    @endif
                                    <div class="col-md-4">Pengusul</div>:
                                    <div class="col-md-7">{{ $usulan->pegawai->nama_pegawai }}</div>
                                    <div class="col-md-4">Unit Kerja</div>:
                                    <div class="col-md-7">{{ $usulan->pegawai->unitKerja->nama_unit_kerja }}</div>
                                    @if ($usulan->keterangan)
                                    <div class="col-md-4">Keterangan</div>:
                                    <div class="col-md-7">{{ $usulan->keterangan }}</div>
                                    @endif
                                    <div class="col-md-4">Aksi</div>:
                                    <div class="col-md-7">
                                        @if ($usulan->status_proses_id < 103 && Auth::user()->role_id == 4 || Auth::user()->role_id == 1)
                                            <a href="{{ route('usulan.edit', ['form' => $form, 'id' => $usulan->id_usulan]) }}" class="btn btn-warning btn-xs   ">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            @endif

                                            @if (Auth::user()->pegawai->jabatan_id == 11 && $form == 'atk' && $usulan->status_proses_id < 103)
                                            <a href="{{ route('atk.validation.edit', ['form' => $form, 'id' => $usulan->id_usulan]) }}" class="btn btn-warning btn-xs">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            @endif

                                            @if ($usulan->status_proses_id > 102)
                                            <a href="{{ route('usulan.print', ['form' => $form, 'id' => $usulan->id_usulan]) }}" class="btn btn-danger btn-xs" target="_blank">
                                                <i class="fas fa-print"></i> Cetak
                                            </a>
                                            @endif
                                    </div>
                                </div>
                            </div>
                            @if ($usulan->bast->count() != 0 && $form != 'atk')
                            <div class="col-md-6">
                                @foreach ($usulan->bast as $i => $row)
                                <div class="form-group row">
                                    <label class="col-md-12 text-muted mb-3">Informasi Berita Acara</label>
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <div class="col-md-4">ID</div>:
                                            <div class="col-md-7">{{ $row->id_bast.''.\Carbon\carbon::parse($row->created_at)->isoFormat('HHmmDDMMYY') }}</div>
                                            <div class="col-md-4">Tanggal Bast</div>:
                                            <div class="col-md-7">
                                                {{ \Carbon\carbon::parse($row->tanggal_bast)->isoFormat('DD MMMM Y') }}
                                            </div>
                                            <div class="col-md-4">Nomor Bast</div>:
                                            <div class="col-md-7">{{ $row->nomor_bast }}</div>
                                            <div class="col-md-4">Penerima</div>:
                                            <div class="col-md-7">{{ $usulan->pegawai->nama_pegawai }}</div>
                                            <div class="col-md-4">Unit Kerja</div>:
                                            <div class="col-md-7">{{ $usulan->pegawai->unitKerja->nama_unit_kerja }}</div>
                                            @if ($usulan->keterangan)
                                            <div class="col-md-4">Keterangan</div>:
                                            <div class="col-md-7">{{ $usulan->keterangan }}</div>
                                            @endif
                                            <div class="col-md-4">Aksi</div>:
                                            <div class="col-md-7">

                                                @if ($usulan->status_proses_id >= 105 && Auth::user()->role_id == 2)
                                                <a href="{{ route('bast.edit', ['form' => $form, 'id' => $row->id_bast]) }}" class="btn btn-warning btn-xs rounded">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                @elseif ($usulan->status_proses_id == 106)
                                                @foreach ($usulan->bast->take(1) as $row)
                                                <a href="{{ route('bast.print', ['form' => $form, 'id' => $row->id_bast]) }}" class="btn btn-danger btn-xs" target="_blank">
                                                    <i class="fas fa-print"></i> Cetak
                                                </a>
                                                @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>
                        <hr>
                        <div class="form-group row">
                            <label class="col-md-12 text-muted mb-3">
                                Informasi Realisasi <br>
                                @if ($usulan->realisasi->count() == 0 && Auth::user()->role_id == 2 && $usulan->status_proses_id > 103)
                                <a href="{{ route('anggaran.realisasi.edit', ['form' => $form, 'id' => $usulan->id_usulan]) }}" class="btn btn-primary btn-xs">
                                    <i class="fas fa-plus"></i> Tambah
                                </a>
                                @elseif (Auth::user()->role_id == 2 && $usulan->status_proses_id > 103)
                                <a href="{{ route('anggaran.realisasi.edit', ['form' => $form, 'id' => $usulan->id_usulan]) }}" class="btn btn-warning btn-xs mt-2">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                @endif
                            </label>
                            @foreach ($usulan->realisasi as $row)
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <small>{{ \Carbon\carbon::parse($row->created_at)->isoFormat('DD MMMM Y') }}</small>
                                    </div>
                                    <div class="card-body">
                                        <small>({{ $row->mta_kode }}) {{ $row->mta_deskripsi }}</small>
                                        <h5 class="mt-2">{{ 'Rp '. number_format($row->nilai_realisasi, 0, ',', '.') }}</h5>
                                        @if ($row->keterangan)
                                        <small>{{ $row->keterangan }}</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <hr>
                        <div class="form-group row">
                            <label class="col-md-12 text-muted mb-3">Informasi Pekerjaan</label>
                            <div class="col-md-12">
                                @php
                                $row1 = ($form == 'aadb' && $usulan->form_id != 101) ? 'No. Plat' : (($form == 'atk') ? 'Nama Barang' : 'Pekerjaan');
                                $row2 = $form == 'aadb' && $usulan->form_id != 101 ? 'Kendaraan' : 'Deskripsi';
                                $row3 = $form == 'atk' ? 'Permintaan' : 'Keterangan'
                                @endphp
                                <table id="table-show" class="table table-bordered text-center" style="font-size: 15px;">
                                    <thead style="font-size: 15px;">
                                        <tr>
                                            <th class="align-middle">No</th>
                                            <th class="align-middle">{{ $row1 }}</th>
                                            <th class="align-middle">{{ $row2 }}</th>
                                            <th class="align-middle">{{ $row3 }}</th>
                                            @if ($form == 'atk')
                                            <th class="align-middle">Disetujui</th>
                                            @if ($usulan->bast->count() != 0)
                                            <th>
                                                Berita Acara
                                                <div class="row border-top border-dark mt-1">
                                                    <div class="col-md-6 mt-2">Nomor Surat</div>
                                                    <div class="col-md-3 mt-2">Tanggal</div>
                                                    <div class="col-md-3 mt-2">Diserahkan</div>
                                                </div>
                                            </th>
                                            @endif
                                            @endif
                                        </tr>
                                    </thead>
                                    @php $no = 1; @endphp
                                    @php $noBast = 1; @endphp
                                    <tbody style="font-size: 13px;">
                                        @foreach ($usulan->usulanGdn as $row)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td class="text-left" style="width: 25%;">
                                                {{ ucwords(strtolower($row->bperbaikan->bidang_perbaikan)) }} <br>
                                                {{ $row->judul_pekerjaan }}
                                            </td>
                                            <td class="text-left" style="width: 45%;">{!! nl2br(e($row->deskripsi)) !!}</td>
                                            <td class="text-left" style="width: 30%;">{!! nl2br(e($row->keterangan)) !!}</td>
                                        </tr>
                                        @endforeach

                                        @foreach ($usulan->usulanUkt as $row)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td class="text-left" style="width: 35%;">{{ $row->judul_pekerjaan }}</td>
                                            <td class="text-left" style="width: 35%;">{!! nl2br(e($row->deskripsi)) !!}</td>
                                            <td class="text-left">{!! nl2br(e($row->keterangan)) !!}</td>
                                        </tr>
                                        @endforeach

                                        @foreach ($usulan->usulanOldat as $row)
                                        @if ($usulan->form_id == 201)
                                        <tr class="text-capitalized">
                                            <td class="align-top">{{ $no++ }}</td>
                                            <td class="text-left align-top">
                                                Permintaan Pengadaan {{ ucfirst(strtolower($row->kategori?->kategori_barang)) }}
                                                sebanyak {{ $row->jumlah_pengadaan }} unit dengan estimasi harga
                                                Rp {{ number_format($row->estimasi_harga, 0, ',', '.') }}
                                            </td>
                                            <td class="text-left align-top">{!! nl2br(e($row->spesifikasi)) !!}</td>
                                            <td class="text-left align-top">{!! nl2br(e($usulan->keterangan)) !!}</td>
                                        </tr>
                                        @elseif ($usulan->form_id == 202)
                                        <tr class="text-capitalized">
                                            <td>{{ $no++ }}</td>
                                            <td class="text-left">
                                                Perbaikan {{ ucfirst(strtolower($row->barang?->kategori->kategori_barang)) }}
                                                ({{ $row->barang?->kategori_id.'.'.$row->barang?->nup }})
                                            </td>
                                            <td class="text-left">
                                                {{ ucfirst(strtolower($row->barang?->merk_tipe.' '.$row->barang?->spesifikasi)) }}
                                            </td>
                                            <td class="text-left">{!! nl2br(e($row->keterangan_kerusakan)) !!}</td>
                                        </tr>
                                        @endif
                                        @endforeach

                                        @foreach ($usulan->usulanAadb as $row)
                                        <tr class="text-capitalized">
                                            <td class="align-top" style="width: 0%;">{{ $no++ }}</td>
                                            <td class="text-left align-top" style="width: 35%;">
                                                Permintaan Pengadaan {{ ucwords(strtolower($row->jenis_aadb.' '.$row->aadb?->kategori_aadb)) }}
                                                sebanyak {{ $row->jumlah_pengadaan.' unit' }}
                                            </td>
                                            <td class="align-top" style="width: 20%;">{{ $row->merk_tipe.' '.$row->tahun }}</td>
                                            <td class="text-left align-top" style="width: 30%;">{!! nl2br(e($usulan->keterangan)) !!}</td>
                                        </tr>
                                        @endforeach

                                        @foreach ($usulan->usulanStnk as $row)
                                        @if ($usulan->form_id == 102)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td style="width: 20%;">{{ $row->aadb->no_plat }}</td>
                                            <td style="width: 30%;">{{ $row->aadb->merk_tipe.' '.$row->aadb->tahun }}</td>
                                            <td class="text-left">
                                                {{ $row->keterangan }}
                                                @if ($row->kilometer)
                                                , Kilometer saat ini {{ number_format($row->kilometer, 0, ',', '.') }} km
                                                @endif
                                                @if ($row->tanggal_servis)
                                                , Terakhir servis {{ \Carbon\carbon::parse($row->tanggal_servis)->isoFormat('MMMM Y') }}
                                                @endif
                                                @if ($row->tanggal_ganti_oli)
                                                , Terakhir ganti oli {{ \Carbon\carbon::parse($row->tanggal_ganti_oli)->isoFormat('MMMM Y') }}
                                                @endif
                                            </td>
                                        </tr>
                                        @elseif ($usulan->form_id == 103)
                                        @if ($row->keterangan == 'true')
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td style="width: 25%;">{{ $row->aadb->no_plat }}</td>
                                            <td style="width: 35%;">{{ $row->aadb->merk_tipe.' '.$row->aadb->tahun }}</td>
                                            <td class="text-left">
                                                Masa Berlaku STNK :
                                                {{ \Carbon\carbon::parse($row->tanggal_stnk)->isoFormat('DD MMMM Y') }}
                                            </td>
                                        </tr>
                                        @endif
                                        @endif
                                        @endforeach

                                        @foreach ($usulan->usulanBbm->where('status_pengajuan', 'true') as $row)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $row->aadb->no_plat }}</td>
                                            <td>{{ $row->aadb->merk_tipe.' '.$row->aadb->tahun }}</td>
                                            <td>{{ \Carbon\carbon::parse($row->bulan_pengadaan)->isoFormat('MMMM Y') }}</td>
                                        </tr>
                                        @endforeach

                                        @foreach ($usulan->usulanAtk as $subRow)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td class="text-left">{{ $subRow->atk->kategori->kategori_atk }}</td>
                                            <td class="text-left">{{ $subRow->atk->deskripsi }}</td>
                                            <td>{{ $subRow->jumlah_permintaan }} {{ optional($subRow->atk->satuan->where('jenis_satuan', 'distribusi')->first())->satuan }}</td>
                                            <td>
                                                @if ($subRow->jumlah_disetujui)
                                                {{ $subRow->jumlah_disetujui }} {{ optional($subRow->atk->satuan->where('jenis_satuan', 'distribusi')->first())->satuan }}
                                                @endif
                                            </td>
                                            @if($usulan->bast->count() != 0)
                                            <td>
                                                @foreach($usulan->bast as $rowBast)
                                                @foreach($rowBast->detail->where('usulan_detail_id', $subRow->id_permintaan) as $rowDetail)
                                                <div class="row border-bottom border-secondary mb-2">
                                                    <div class="col-md-6 font-weight-bold">
                                                        <a href="{{ route('bast.detail', ['form' => $form, 'id' => $rowBast->id_bast]) }}">
                                                            {{ $noBast++ .'. '.$rowDetail->bast->nomor_bast }}
                                                        </a>
                                                    </div>
                                                    <div class="col-md-3">&ensp;{{ $rowDetail->bast->tanggal_bast }}</div>
                                                    <div class="col-md-3">{{ $rowDetail->deskripsi.' '. optional($subRow->atk->satuan->where('jenis_satuan', 'distribusi')->first())->satuan }}</div>
                                                </div>
                                                @endforeach
                                                @endforeach
                                            </td>
                                            @endif
                                        </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- Alokasi Anggaran -->
                    <div class="tab-pane fade" id="realisasi" role="tabpanel" aria-labelledby="realisasi-tab">
                        <div class=" form-group row">
                            <div class="col-md-6 text-left">
                                <label class="text-muted">Informasi Realisasi</label>
                            </div>
                            @if (Auth::user()->role_id == 2)
                            <div class="col-md-6 text-right">
                                <a href="{{ route('anggaran.realisasi.edit', ['form' => $form, 'id' => $id]) }}" class="btn btn-warning btn-sm rounded">
                                    <i class="fas fa-edit"></i>Edit
                                </a>
                            </div>
                            @endif
                        </div>
                        <table class="table table-bordered text-center">
                            <thead style="font-size: 15px;">
                                <tr>
                                    <th>No</th>
                                    <th>Mata Anggaran</th>
                                    <th style="width: 20%;">Nilai Realisasi</th>
                                    <th style="width: 15%;">Pembayaran</th>
                                    <th style="width: 20%;">Keterangan</th>
                                </tr>
                            </thead>
                            @php $no = 1; @endphp
                            <tbody style="font-size: 13px;">
                                @foreach ($usulan->realisasi as $row)
                                <tr>
                                    <td class="pt-3">{{ $no++ }}</td>
                                    <td class="pt-3 text-center">({{ $row->mta_kode }}) {{ $row->mta_deskripsi }}</td>
                                    <td class="pt-3">{{ 'Rp '. number_format($row->nilai_realisasi, 0, ',', '.') }}</td>
                                    <td class="pt-3">{{ $row->jenis_realisasi }}</td>
                                    <td class="pt-3">{{ $row->keterangan }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- Berita Acara -->
                    @if ($usulan->bast->count() != 0 && $form == 'atk')
                    <div class="tab-pane fade" id="bast" role="tabpanel" aria-labelledby="bast-tab">
                        <div class="row">
                            <div class="col-md-12 text-muted mb-3">Informasi Berita Acara</div>
                            <div class="col-md-12">
                                @php
                                $row1 = $usulan->form->kategori == 'AADB' ? 'No. Plat' : 'Pekerjaan';
                                $row2 = $usulan->form->kategori == 'AADB' ? 'Kendaraan' : 'Spesifikasi';
                                @endphp
                                <table id="table-detail" class="table table-bordered text-center">
                                    <thead class="bg-light" style="font-size: 14px;">
                                        <tr>
                                            <th style="width: 0%;">No</th>
                                            <th style="width: 20%;">Tanggal Bast</th>
                                            <th style="width: 20%;">Nomor Surat</th>
                                            <th style="width: 10%;">Total Barang</th>
                                            <th style="width: 20%;">Total Realisasi</th>
                                            <th style="width: 10%;">Aksi</th>
                                        </tr>
                                    </thead>
                                    @php $no = 1; @endphp
                                    <tbody style="font-size: 13px;">
                                        @foreach ($usulan->bast as $i => $row)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $row->tanggal_bast }}</td>
                                            <td>{{ $row->nomor_bast }}</td>
                                            <td>{{ $row->detail->count() }}</td>
                                            <td>{{ $row->detail->sum('nilai_realisasi') }}</td>
                                            <td>
                                                <a href="{{ route('bast.detail', ['form' => $form, 'id' => $row->id_bast]) }}" class="btn btn-primary btn-sm" target="_blank">
                                                    <i class="fas fa-arrow-up-right-from-square"></i> Detail
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>


    </div>
</section>

<div id="usulan-count" data-usulan-count="{{ $usulan->usulanUkt->count() }}"></div>
@section('js')
<script>
    $(function() {
        $(".kategori").select2()
        $("#table-show").DataTable({
            "responsive": false,
            "lengthChange": true,
            "autoWidth": true,
            "info": true,
            "paging": true,
            "searching": true,
            "sort": false
        }).buttons().container().appendTo('#table-show_wrapper .col-md-6:eq(0)')

        $("#table-detail").DataTable({
            "responsive": false,
            "lengthChange": true,
            "autoWidth": true,
            "info": true,
            "paging": true,
            "searching": true
        }).buttons().container().appendTo('#table-detail_wrapper .col-md-6:eq(0)')
    })
</script>
<script>
    const usulanTab = document.getElementById('usulan-tab');
    const realisasiTab = document.getElementById('realisasi-tab');
    const bastTab = document.getElementById('bast-tab');

    usulanTab.addEventListener('click', function() {
        usulanTab.classList.add('bg-primary');
        realisasiTab.classList.remove('bg-primary');
        bastTab.classList.remove('bg-primary');
    });

    realisasiTab.addEventListener('click', function() {
        realisasiTab.classList.add('bg-primary');
        usulanTab.classList.remove('bg-primary');
        bastTab.classList.remove('bg-primary');
    });

    bastTab.addEventListener('click', function() {
        bastTab.classList.add('bg-primary');
        usulanTab.classList.remove('bg-primary');
        realisasiTab.classList.remove('bg-primary');
    });
</script>
@endsection


@endsection
