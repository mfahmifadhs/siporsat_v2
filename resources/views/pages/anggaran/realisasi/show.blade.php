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
                    <li class="breadcrumb-item"><a href="{{ route($form.'.home') }}">Beranda</a></li>
                    <li class="breadcrumb-item">Laporan</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<!-- Content Header -->


<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header border-transparent bg-primary">
                <b class="font-weight-bold card-title" style="font-size:medium;">
                    REALISASI
                </b>
            </div>
            <div class="card-body">
                <table id="table-usulan" class="table table-bordered text-center">
                    <thead style="font-size: 15px;">
                        <tr>
                            <th>No</th>
                            <th>ID Usulan</th>
                            <th>Total Realisasi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 15px;">
                        @foreach ($realisasi as $i => $row)
                        <tr>
                            <td class="pt-3">{{ $i+1 }}</td>
                            <td class="pt-3">{{ $row->usulan_id }}</td>
                            <td class="pt-3 h6">Rp {{ number_format($row->nilai_realisasi, 0, ',', '.') }}</td>
                            <td>
                                <a href="{{ route('usulan.detail', ['form' => $form, 'id' => $row->usulan_id]) }}" class="btn btn-primary btn-sm">
                                    Detail <i class="fas fa-arrow-circle-right fa-1x"></i>
                                </a>
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
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script>
    $(function() {
        $("#table-usulan").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "info": false,
            "paging": true

        })
    })
</script>

@endsection

@endsection
