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
                    <a href="{{ route('usulan.detail', ['form' => $form, 'id' => $bast->usulan_id]) }}" class="btn btn-add">
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
                Edit Berita Acara {{ $bast->usulan->form->nama_form }}
            </div>
            <div class="card-body">
                <ul class="nav mb-3" id="tab" role="tablist">
                    <li class="nav-item">
                        <a class="btn btn-default mr-2 border-secondary active bg-primary" id="bast-tab" data-toggle="pill" href="#bast" role="tab" aria-controls="bast" aria-selected="false">
                            Berita Acara
                        </a>
                    </li>
                </ul>
                <div class="tab-content" id="tabContent">
                    <!-- Berita Acara -->
                    <div class="tab-pane fade active show" id="bast" role="tabpanel" aria-labelledby="bast-tab">
                        <div class="row">
                            <div class="col-md-5">
                                <label class="text-muted mb-3">Informasi Usulan</label>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="form-group row">
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
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1"></div>
                            <div class="col-md-6">
                                <label class="text-muted mb-3">Informasi Berita Acara</label>
                                <form id="form" action="{{ route('bast.update', ['form' => $form, 'id' => $id]) }}" method="POST" class="mt-2">
                                    @csrf
                                    <div class="form-group row">
                                        <label class="col-md-3">ID Usulan</label>:
                                        <div class="col-md-8">
                                            <input type="text" name="uusulan_id" class="form-control input-border-bottom bg-white" value="{{ $bast->usulan_id }}" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-3">Nomor Bast</label>:
                                        <div class="col-md-8">
                                            <input type="text" name="nomor_bast" class="form-control input-border-bottom bg-white" value="{{ $bast->nomor_bast }}" readonly required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-3">Tanggal Bast</label>:
                                        <div class="col-md-8">
                                            <input type="date" name="tanggal_bast" class="form-control input-border-bottom" value="{{ $bast->tanggal_bast }}" required>
                                        </div>
                                        <div class="col-md-11 text-right mt-3">
                                            <button type="submit" class="btn btn-primary btn-sm" onclick="confirmSubmit(event)">
                                                <i class="fas fa-paper-plane"></i> Submit
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="col-md-12">
                                @php
                                $row1 = $bast->usulan->form->kategori == 'AADB' && $bast->usulan->form_id != 101 ? 'No. Plat' : 'Pekerjaan';
                                $row2 = $bast->usulan->form->kategori == 'AADB' && $bast->usulan->form_id != 101 ? 'Kendaraan' : 'Spesifikasi';
                                @endphp
                                <label class="text-muted mt-3">Informasi Pekerjaan</label>
                                <table id="table-detail" class="table table-bordered text-center" style="font-size: 15px;">
                                    <thead style="font-size: 15px;">
                                        <tr>
                                            <th style="width: 0%;">No</th>
                                            <th style="width: 30%;">{{ $row1 }}</th>
                                            <th style="width: 40%;">{{ $row2 }}</th>
                                            <th style="width: 30%;">Keterangan</th>
                                        </tr>
                                    </thead>
                                    @php $no = 1; @endphp
                                    <tbody style="font-size: 13px;">
                                        @foreach ($bast->detail as $subRow)
                                        <!-- UKT -->
                                        @if ($bast->usulan->form_id == 501)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td class="text-left text-capitalize">
                                                {{ ucfirst(strtolower($subRow->usulanUkt->judul_pekerjaan)) }}
                                            </td>
                                            <td class="text-left">{!! nl2br(e($subRow->usulanUkt->deskripsi)) !!}</td>
                                            <td class="text-left">{!! nl2br(e($subRow->usulanUkt->keterangan)) !!}</td>
                                        </tr>
                                        @endif
                                        <!-- GDN -->
                                        @if ($bast->usulan->form_id == 401)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td class="text-left text-capitalize">
                                                {{ ucfirst(strtolower($subRow->usulanGdn->bperbaikan->bidang_perbaikan)) }} <br>
                                                {{ ucfirst(strtolower($subRow->usulanGdn->judul_pekerjaan)) }}
                                            </td>
                                            <td class="text-left">{!! nl2br(e($subRow->usulanGdn->deskripsi)) !!}</td>
                                            <td class="text-left">{!! nl2br(e($subRow->usulanGdn->keterangan)) !!}</td>
                                        </tr>
                                        @endif
                                        <!-- OLDAT -->
                                        @if ($bast->usulan->form_id == 201)
                                        <tr class="text-capitalized">
                                            <td>{{ $no++ }}</td>
                                            <td class="text-left">
                                                Permintaan Pengadaan {{ ucfirst(strtolower($subRow->usulanOldat->kategori?->kategori_barang)) }}
                                                sebanyak {{ $subRow->usulanOldat->jumlah_pengadaan }} unit dengan estimasi harga
                                                Rp {{ number_format($subRow->usulanOldat->estimasi_harga, 0, ',', '.') }}
                                            </td>
                                            <td class="text-left">{!! nl2br(e($subRow->usulanOldat->spesifikasi)) !!}</td>
                                            <td class="text-left">{!! nl2br(e($bast->usulan->keterangan)) !!}</td>
                                        </tr>
                                        @elseif ($bast->usulan->form_id == 202)
                                        <tr class="text-capitalized">
                                            <td>{{ $no++ }}</td>
                                            <td class="text-left">
                                                Perbaikan {{ ucfirst(strtolower($subRow->usulanOldat->barang->kategori->kategori_barang)) }}
                                                ({{ $subRow->usulanOldat->barang->kategori_id.'.'.$subRow->usulanOldat->barang->nup }})
                                            </td>
                                            <td class="text-left">
                                                {{ ucfirst(strtolower($subRow->usulanOldat->barang->merk_tipe.' '.$subRow->usulanOldat->barang?->spesifikasi)) }}
                                            </td>
                                            <td class="text-left">{!! nl2br(e($subRow->usulanOldat->keterangan_kerusakan)) !!}</td>
                                        </tr>
                                        @endif


                                        @if ($bast->usulan->form_id == 101)
                                        <tr class="text-capitalized">
                                            <td class="align-top" style="width: 0%;">{{ $no++ }}</td>
                                            <td class="text-left align-top" style="width: 35%;">
                                                Permintaan Pengadaan {{ ucwords(strtolower($subRow->usulanAadb->jenis_aadb.' '.$subRow->usulanAadb->aadb?->kategori_aadb)) }}
                                                sebanyak {{ $subRow->usulanAadb->jumlah_pengadaan.' unit' }}
                                            </td>
                                            <td class="align-top" style="width: 20%;">{{ $subRow->usulanAadb->merk_tipe.' '.$subRow->tahun }}</td>
                                            <td class="text-left align-top" style="width: 30%;">{!! nl2br(e($bast->usulan->keterangan)) !!}</td>
                                        </tr>
                                        @endif

                                        @if ($bast->usulan->form_id == 102)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td style="width: 20%;">{{ $subRow->usulanStnk->aadb->no_plat }}</td>
                                            <td style="width: 30%;">{{ $subRow->usulanStnk->aadb->merk_tipe.' '.$subRow->usulanStnk->aadb->tahun }}</td>
                                            <td class="text-left">
                                                {{ $subRow->usulanStnk->keterangan }}
                                                @if ($subRow->usulanStnk->kilometer)
                                                , Kilometer saat ini {{ number_format($subRow->usulanStnk->kilometer, 0, ',', '.') }} km
                                                @endif
                                                @if ($subRow->usulanStnk->tanggal_servis)
                                                , Terakhir servis {{ \Carbon\carbon::parse($subRow->usulanStnk->tanggal_servis)->isoFormat('MMMM Y') }}
                                                @endif
                                                @if ($subRow->usulanStnk->tanggal_ganti_oli)
                                                , Terakhir ganti oli {{ \Carbon\carbon::parse($subRow->usulanStnk->tanggal_ganti_oli)->isoFormat('MMMM Y') }}
                                                @endif.
                                            </td>
                                        </tr>
                                        @elseif ($bast->usulan->form_id == 103)
                                        @if ($subRow->usulanStnk->keterangan == 'true')
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td style="width: 25%;">{{ $subRow->usulanStnk->aadb->no_plat }}</td>
                                            <td style="width: 35%;">{{ $subRow->usulanStnk->aadb->merk_tipe.' '.$subRow->usulanStnk->aadb->tahun }}</td>
                                            <td class="text-left">
                                                Masa Berlaku STNK :
                                                {{ \Carbon\carbon::parse($subRow->usulanStnk->tanggal_stnk)->isoFormat('DD MMMM Y') }}
                                            </td>
                                        </tr>
                                        @endif
                                        @endif

                                        @if ($bast->usulan->form_id == 104)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $subRow->usulanBbm->aadb->no_plat }}</td>
                                            <td>{{ $subRow->usulanBbm->aadb->merk_tipe.' '.$subRow->usulanBbm->aadb->tahun }}</td>
                                            <td>{{ \Carbon\carbon::parse($subRow->usulanBbm->bulan_pengadaan)->isoFormat('MMMM Y') }}</td>
                                        </tr>
                                        @endif
                                        @endforeach


                                    </tbody>
                                </table>
                            </div>
                            @if ($bast->usulan->bast->count() > 1)
                            <div class="col-md-12">
                                <hr>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
</section>

<div id="usulan-count" data-usulan-count="{{ $bast->usulan->usulanUkt->count() }}"></div>
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

    function confirmSubmit(event) {
        event.preventDefault();

        const form = document.getElementById('form');
        const requiredInputs = form.querySelectorAll('input[required]');

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
@endsection


@endsection
