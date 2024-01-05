@extends('layout.app')

@section('content')

<!-- Content Header -->
<section class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="text-capitalize">SIPORSAT</h1>
                <h5>Sistem Pengelolaan Operasional Perkantoran Terpusat</h5>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right text-capitalize">
                    <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('rumah_dinas.show') }}">Daftar Rumah Dinas</a></li>
                    <li class="breadcrumb-item active">DetailRumah Dinas</li>
                </ol>
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
            <div class="card-header">
                <div class="row">
                    <div class="col-md-8 col-6">
                        <div class="form-group row">
                            <div class="col-md-12 mb-2">Golongan {{ $rumah->golongan }}</div>
                            <div class="col-md-3">Lokasi Kota</div>:
                            <div class="col-md-4">{{ $rumah->lokasi_kota }}</div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3">Luas Bangunan</div>:
                            <div class="col-md-4">{{ $rumah->luas_bangunan }} m<sup>2</sup></div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3">Luas Tanah</div>:
                            <div class="col-md-4">{{ $rumah->luas_tanah }} m<sup>2</sup></div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3">Kondisi</div>:
                            <div class="col-md-4">{{ $rumah->kondisi }}</div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3">Alamat</div>:
                            <div class="col-md-8 text-justify">{{ $rumah->alamat }}</div>
                        </div>
                    </div>
                    <div class="col-md-4 col-6 mt-3">
                        @foreach($rumah->fotoRumah->take(1) as $row)
                            <img src="{{ asset('storage/' . $row->nama_file) }}" class="img-fluid">
                        @endforeach
                        <div class="row no-gutters">
                            @foreach($rumah->fotoRumah as $row)
                            <span class="col-md-3 p-0 bg-default">
                                <img src="{{ asset('storage/' . $row->nama_file) }}" class="img-fluid" style="height: 50px;">
                            </span>
                            @endforeach
                        </div>
                        <a href="{{ route('rumah_dinas.foto.edit', $rumah->id_rumah) }}" class="btn btn-primary btn-sm mt-2" title="edit foto">
                            <i class="fas fa-edit"></i> Edit Foto
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <a href="{{ route('penghuni.create', $rumah->id_rumah) }}" class="btn btn-primary mb-3">
                    <i class="fas fa-plus-circle"></i> Tambah Penghuni
                </a>
                <table id="table" class="table table-striped m-0">
                    <thead>
                        <tr class="pb-4">
                            <th class="text-center">No</th>
                            <th>Nama Penghuni</th>
                            <th>Nomor SIP</th>
                            <th>Sertifikat</th>
                            <th>PBB</th>
                            <th>IMB</th>
                            <th class="p-0 text-center">Tanggal <br> Masuk</th>
                            <th class="p-0 text-center">Tanggal <br> Keluar</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rumah->penghuni as $i => $row)
                        <tr class="text-justify">
                            <td class="text-center">
                                {{ $i + 1 }}
                                <a href="{{ route('penghuni.edit', $row->id_penghuni) }}" class="font-weight-bold" title="Edit Penghuni">
                                    <i class="fas fa-edit text-primary"></i>
                                </a>
                            </td>
                            <td>
                                {{ $row->pegawai->unitKerja->nama_unit_kerja }} <br>
                                {{ $row->pegawai->nama_pegawai }}
                            </td>
                            <td>{{ $row->nomor_sip }}</td>
                            <td>{{ $row->sertifikat }}</td>
                            <td>{{ $row->pbb }}</td>
                            <td>{{ $row->imb }}</td>
                            <td class="text-center">{{ $row->tanggal_masuk }}</td>
                            <td class="text-center">{{ $row->tanggal_keluar }}</td>
                            <td class="text-center">
                                @if ($row->status_penghuni == true)
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-danger">Tidak Aktif</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>


@section('js')
<script>
    $(function() {
        $("#table").DataTable({
            "responsive": false,
            "lengthChange": true,
            "autoWidth": true,
            "info": true,
            "paging": true,
            "searching": true,
            "columnDefs": [
                { "width": "0%", "targets": 0 },
                { "width": "18%", "targets": 1 },
                { "width": "15%", "targets": 2 },
                { "width": "20%", "targets": 3 },
                { "width": "10%", "targets": 6 },
                { "width": "10%", "targets": 7 },
                { "width": "5%", "targets": 8 },
            ]
        }).buttons().container().appendTo('#table-show_wrapper .col-md-6:eq(0)')
    })
</script>
@endsection


@endsection
