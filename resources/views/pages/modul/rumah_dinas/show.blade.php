@extends('layout.app')

@section('content')

<!-- Content Header -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="text-capitalize">SIPORSAT</h1>
                <h5>Sistem Pengelolaan Operasional Perkantoran Terpusat</h5>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right text-capitalize">
                    <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Rumah Dinas</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<!-- Content Header -->

<section class="content">
    <div class="container-fluid">
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

        <div class="form-group">
            <a href="{{ route('rumah_dinas.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i> Tambah Rumah Dinas
            </a>
        </div>

        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Daftar Rumah Dinas</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-header">
                <a href="{{ route('rumah_dinas.export') }}" class="btn btn-primary">Export</a>
            </div>
            <div class="card-body">
                <table id="table-show" class="table table-bordered table-striped" style="font-size: 15px;">
                    <thead class="text-center">
                        <tr>
                            <th class="text-center">No</th>
                            <th>Penghuni</th>
                            <th>Alamat</th>
                            <th>Lt / Lb</th>
                            <th>Kondisi</th>
                            <th>Foto</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    @php $no = 1; @endphp
                    <tbody class="text-capitalize">
                        @foreach($rumah as $row)
                        <tr>
                            <td class="pt-3 text-center">{{ $no++ }} </td>
                            <td class="pt-3">
                                @foreach ($row->penghuni->where('status_penghuni', 'true')->take(1) as $subRow)
                                    {{ $subRow->pegawai->unitKerja?->nama_unit_kerja }} <br>
                                    {{ $subRow->pegawai->nama_pegawai }} <br>
                                    {{ $subRow->nomor_sip }}
                                @endforeach
                            </td>
                            <td class="pt-3 text-justify">
                                Golongan {{ $row->golongan }} <br>
                                {{ $row->alamat.', '.$row->lokasi_kota }}
                            </td>
                            <td class="pt-3 text-center text-lowercase">
                                {{ $row->luas_tanah }} m<sup>2</sup> /
                                {{ $row->luas_bangunan }} m<sup>2</sup>
                            </td>
                            <td class="pt-3 text-center">{{ $row->kondisi }}</td>
                            <td class="text-center">
                                @foreach ($row->fotoRumah->take(1) as $subRow)
                                <img src="{{ asset('storage/' . $subRow->nama_file) }}" width="150">
                                @endforeach
                            </td>
                            <td class="text-center">
                                <a type="button" class="btn btn-primary btn-sm" data-toggle="dropdown">
                                    <i class="fas fa-bars"></i>
                                </a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item btn" type="button" href="{{ route('rumah_dinas.detail', $row->id_rumah) }}">
                                        <i class="fas fa-info-circle"></i> Detail
                                    </a>
                                    @if (Auth::user()->role_id == 1)
                                    <a class="dropdown-item btn" type="button" href="{{ route('rumah_dinas.edit', $row->id_rumah) }}">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a class="dropdown-item btn" type="button" href="{{ route('rumah_dinas.delete', $row->id_rumah) }}" onclick="return confirm('Ingin Menghapus Data?')">
                                        <i class="fas fa-trash"></i> Hapus
                                    </a>
                                    @endif
                                </div>
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
        $("#table-show").DataTable({
            "responsive": false,
            "lengthChange": true,
            "autoWidth": true,
            "info": true,
            "paging": true,
            "searching": true,
            "columnDefs": [
                { "width": "0%", "targets": 0 },
                { "width": "20%", "targets": 2 },
                { "width": "15%", "targets": 3 },
                { "width": "10%", "targets": 4 },
                { "width": "10%", "targets": 5 },
                { "width": "0%", "targets": 6 },
            ]
        }).buttons().container().appendTo('#table-show_wrapper .col-md-6:eq(0)')
    })
</script>
@endsection

@endsection
