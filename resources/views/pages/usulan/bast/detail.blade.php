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
                    <li class="breadcrumb-item">Detail Berita Acara</li>
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
                Detail Berita Acara {{ $bast->usulan->form->nama_form }}
            </div>
            <div class="card-header">
                <div class="timeline-steps aos-init aos-animate" data-aos="fade-up">
                    @foreach ($status as $row)
                    @if ($row->id_status != 100)
                    <div class="timeline-step">
                        <div class="timeline-content">
                            @php $status_id = $bast->usulan->status_proses_id; @endphp

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
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-md-12 text-muted mb-3">Informasi Berita Acara</label>
                            <div class="col-md-9">
                                <div class="form-group row">
                                    <div class="col-md-4">ID</div>:
                                    <div class="col-md-7">{{ $bast->id_bast.''.\Carbon\carbon::parse($bast->created_at)->isoFormat('HHmmDDMMYY') }}</div>
                                    <div class="col-md-4">Tanggal Bast</div>:
                                    <div class="col-md-7">
                                        {{ \Carbon\carbon::parse($bast->tanggal_bast)->isoFormat('DD MMMM Y') }}
                                    </div>
                                    <div class="col-md-4">Nomor Bast</div>:
                                    <div class="col-md-7">{{ $bast->nomor_bast }}</div>
                                    <div class="col-md-4">Penerima</div>:
                                    <div class="col-md-7">{{ $bast->usulan->pegawai->nama_pegawai }}</div>
                                    <div class="col-md-4">Unit Kerja</div>:
                                    <div class="col-md-7">{{ $bast->usulan->pegawai->unitKerja->nama_unit_kerja }}</div>
                                    @if ($bast->usulan->keterangan)
                                    <div class="col-md-4">Keterangan</div>:
                                    <div class="col-md-7">{{ $bast->usulan->keterangan }}</div>
                                    @endif
                                    <div class="col-md-4">Aksi</div>:
                                    <div class="col-md-7">
                                        @if ($bast->usulan->status_proses_id == 106)
                                        <a href="{{ route('bast.print', ['form' => $form, 'id' => $bast->id_bast]) }}" class="btn btn-danger btn-sm" target="_blank">
                                            <i class="fas fa-print"></i> Cetak
                                        </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-md-12 text-muted mb-3">Informasi Pengusul</label>
                            <div class="col-md-4">ID</div>:
                            <div class="col-md-7">{{ $bast->usulan->id_usulan }}</div>
                            <div class="col-md-4">Tanggal Usulan</div>:
                            <div class="col-md-7">
                                {{ \Carbon\carbon::parse($bast->usulan->tanggal_usulan)->isoFormat('DD MMMM Y') }}
                            </div>
                            <div class="col-md-4">Nomor Surat</div>:
                            <div class="col-md-7">{{ $bast->usulan->nomor_usulan }}</div>
                            @if ($bast->usulan->form_id != 401 && $bast->usulan->form_id != 501)
                            <div class="col-md-4">Perihal</div>:
                            <div class="col-md-7">
                                {{ $bast->usulan->form->nama_form }}
                            </div>
                            @endif
                            <div class="col-md-4">Pengusul</div>:
                            <div class="col-md-7">{{ $bast->usulan->pegawai->nama_pegawai }}</div>
                            <div class="col-md-4">Unit Kerja</div>:
                            <div class="col-md-7">{{ $bast->usulan->pegawai->unitKerja->nama_unit_kerja }}</div>
                            @if ($bast->usulan->keterangan)
                            <div class="col-md-4">Keterangan</div>:
                            <div class="col-md-7">{{ $bast->usulan->keterangan }}</div>
                            @endif
                            <div class="col-md-4">Aksi</div>:
                            <div class="col-md-7">
                                @if ($bast->usulan->status_proses_id < 103 && Auth::user()->role_id == 4 || Auth::user()->role_id == 1)
                                <a href="{{ route('usulan.edit', ['form' => $form, 'id' => $bast->usulan->id_usulan]) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                @endif

                                @if ($bast->usulan->status_proses_id != 105 && Auth::user()->pegawai->jabatan_id == 11 && $form == 'atk')
                                <a href="{{ route('atk.validation.edit', ['form' => $form, 'id' => $bast->usulan->id_usulan]) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                @endif

                                @if ($bast->usulan->status_proses_id > 102)
                                <a href="{{ route('usulan.print', ['form' => $form, 'id' => $bast->usulan->id_usulan]) }}" class="btn btn-danger btn-sm" target="_blank">
                                    <i class="fas fa-print"></i> Cetak
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <label class="col-md-12 text-muted mb-3">Informasi Barang</label>
                    <div class="col-md-12">
                        @php
                        $row1 = ($bast->usulan->form->kategori == 'AADB' && $bast->usulan->form_id != 101) ? 'No. Plat' : (($form == 'atk') ? 'Nama Barang' : 'Pekerjaan');
                        $row2 = $bast->usulan->form->kategori == 'AADB' && $bast->usulan->form_id != 101 ? 'Kendaraan' : 'Spesifikasi';
                        $row3 = $form == 'atk' ? 'Permintaan' : 'Keterangan'
                        @endphp
                        <table id="table-show" class="table table-bordered text-center" style="font-size: 15px;">
                            <thead style="font-size: 15px;">
                                <tr>
                                    <th style="width: 0%;">No</th>
                                    <th>{{ $row1 }}</th>
                                    <th>{{ $row2 }}</th>
                                    <th>{{ $row3 }}</th>
                                    @if ($form == 'atk')
                                    <th>Disetujui</th>
                                    <th>Penyerahan</th>
                                    @endif
                                </tr>
                            </thead>
                            @php $no = 1; @endphp
                            <tbody style="font-size: 13px;">
                                @foreach ($bast->detail as $subRow)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td class="text-left">{{ $subRow->usulanAtk->atk->kategori->kategori_atk }}</td>
                                    <td class="text-left">{{ $subRow->usulanAtk->atk->deskripsi }}</td>
                                    <td>{{ $subRow->usulanAtk->jumlah_permintaan }} {{ optional($subRow->usulanAtk->atk->satuan->where('jenis_satuan', 'distribusi')->first())->satuan }}</td>
                                    <td>{{ $subRow->usulanAtk->jumlah_disetujui }} {{ optional($subRow->usulanAtk->atk->satuan->where('jenis_satuan', 'distribusi')->first())->satuan }}</td>
                                    <td>{{ $subRow->deskripsi }} {{ optional($subRow->usulanAtk->atk->satuan->where('jenis_satuan', 'distribusi')->first())->satuan }}</td>
                                </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


    </div>
</section>

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
            "searching": true
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
