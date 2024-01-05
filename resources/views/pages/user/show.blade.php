@extends('layout.app')

@section('content')

<!-- Content Header -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="text-capitalize">SIPORSAT</h1>
                <h5>Sistem Pengelolaan Operasional Perkantoran Terpusat</h5>
                <ol class="breadcrumb text-capitalize">
                    <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Daftar Pengguna</li>
                </ol>
            </div>
            <div class="col-sm-6">
                <div class="form-group text-right">
                    <a href="{{ route('user.create') }}" class="btn btn-add">
                        <span class="btn btn-primary rounded-0 mr-1"><i class="fas fa-plus"></i></span>
                        <small>Tambah</small>
                    </a>
                </div>
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
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Daftar Pengguna</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <table id="table-show" class="table table-bordered table-striped text-center" style="font-size: 13px;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th style="width: 20%;">Unit Kerja</th>
                            <th>Nama Pegawai</th>
                            <th style="width: 15%;">Username</th>
                            <th style="width: 15%;">Password</th>
                            <th style="width: 10%;">Akses</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    @php $no = 1; @endphp
                    <tbody>
                        @foreach($user as $row)
                        <tr>
                            <td>{{ $no++ }} </td>
                            <td>{{ $row->pegawai->unitKerja->nama_unit_kerja }}</td>
                            <td class="text-left">{{ $row->pegawai->nama_pegawai }}</td>
                            <td>{{ $row->username }}</td>
                            <td>{{ $row->password_teks }}</td>
                            <td>{{ $row->role->role }}</td>
                            <td>
                                @if ($row->status_id == 1)
                                <span class="badge badge-success p-1">
                                    {{ $row->status->nama_status }}
                                </span>
                                @else
                                <span class="badge badge-danger p-1">
                                    {{ $row->status->nama_status }}
                                </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a type="button" class="btn btn-primary btn-sm" data-toggle="dropdown">
                                    <i class="fas fa-bars"></i>
                                </a>
                                <div class="dropdown-menu">
                                    @if (Auth::user()->role_id == 1)
                                    <a class="dropdown-item btn" type="button" href="{{ route('user.edit', $row->id) }}">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a class="dropdown-item btn" type="button" href="{{ route('user.delete', $row->id) }}" onclick="return confirm('Ingin Menghapus Data?')">
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
            "searching": true
        }).buttons().container().appendTo('#table-show_wrapper .col-md-6:eq(0)')
    })
</script>
@endsection

@endsection
