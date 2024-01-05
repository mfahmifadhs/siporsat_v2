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
                    <li class="breadcrumb-item">Olah Data BMN & Meubelair</li>
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
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-3 col-12">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ $usulan->whereIn('status_proses_id',[101,102])->count() }} <small>usulan</small></h3>
                                <p>MENUNGGU PERSETUJUAN</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-user-check"></i>
                            </div>
                            <div class="text-center p-2 border border-top">
                                <form action="{{ route('usulan.show','oldat') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="persetujuan">
                                    <button class="btn btn-default btn-xs border-secondary">
                                        Selengkapnya <i class="fas fa-arrow-circle-right"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ $usulan->where('status_proses_id',103)->count() }} <small>usulan</small></h3>
                                <p>SEDANG DIPROSES</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-hourglass-half"></i>
                            </div>
                            <div class="text-center p-2 border border-top">
                                <form action="{{ route('usulan.show','oldat') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="103">
                                    <button class="btn btn-default btn-xs border-secondary">
                                        Selengkapnya <i class="fas fa-arrow-circle-right"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ $usulan->where('status_proses_id',106)->count() }} <small>usulan</small></h3>
                                <p>SELESAI BERITA ACARA</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="text-center p-2 border border-top">
                                <form action="{{ route('usulan.show','oldat') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="106">
                                    <button class="btn btn-default btn-xs border-secondary">
                                        Selengkapnya <i class="fas fa-arrow-circle-right"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ $usulan->where('status_pengajuan_id', 100)->count() }} <small>usulan</small></h3>
                                <p>PENGAJUAN DITOLAK</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-times-circle"></i>
                            </div>
                            <div class="text-center p-2 border border-top">
                                <form action="{{ route('usulan.show','oldat') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="100">
                                    <button class="btn btn-default btn-xs border-secondary">
                                        Selengkapnya <i class="fas fa-arrow-circle-right"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <hr>
            </div>

            <div class="col-md-6">
                <div class="form-group row">
                    <h6 class="text-left col-md-12">Usulan Pengajuan</h6>
                    <div class="col-md-6">
                        <a href="{{ route('usulan.show', 'oldat') }}" class="btn btn-default border-secondary p-4 btn-block">
                            <i class="fas fa-copy fa-3x"></i>
                            <h6 class="mt-3 font-weight-bolder">Daftar Usulan</h6>
                        </a>
                    </div>
                </div>

                @if (Auth::user()->role_id != 4)
                <div class="form-group row">
                    <h6 class="text-left col-md-12">Laporan</h6>
                    <div class="col-md-6">
                        <a href="{{ route('realisasi.show', ['form' => 'oldat', 'ukerId' => Auth::user()->pegawai->unit_kerja_id]) }}" class="btn btn-default border-secondary p-4 btn-block">
                            <i class="fas fa-file-alt fa-3x"></i>
                            <h6 class="mt-3 font-weight-bolder">Anggaran</h6>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('usulan.report', 'oldat') }}" class="btn btn-default border-secondary p-4 btn-block">
                            <i class="fas fa-chart-line fa-3x"></i>
                            <h6 class="mt-3 font-weight-bolder">Laporan</h6>
                        </a>
                    </div>
                </div>
                @endif
            </div>

            <div class="col-md-6">
                <div class="form-group row">
                    <h6 class="text-left col-md-12">Daftar BMN</h6>
                    <div class="col-md-6">
                        <a href="{{ route('oldat.barang.show') }}" class="btn btn-default border-secondary p-4 btn-block">
                            <i class="fas fa-boxes fa-3x"></i>
                            <h6 class="mt-3 font-weight-bolder">Daftar BMN</h6>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('oldat.kategori.show') }}" class="btn btn-default border-secondary p-4 btn-block">
                            <i class="fas fa-boxes fa-3x"></i>
                            <h6 class="mt-3 font-weight-bolder">Kategori BMN</h6>
                        </a>
                    </div>
                </div>
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

        $("#table-unitkerja").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": true,
            "info": false,
            "paging": false,
            "columnDefs": [{
                    "width": "5%",
                    "targets": 0
                },
                {
                    "width": "25%",
                    "targets": 2
                },
                {
                    "width": "25%",
                    "targets": 3
                },
            ]

        })

        $("#table-oldat").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": true,
            "info": false,
            "paging": true,
            "lengthMenu": [
                [11, 25, 50, -1],
                [11, 25, 50, "Semua"]
            ],
            "columnDefs": [{
                    "width": "5%",
                    "targets": 0
                },
                {
                    "width": "25%",
                    "targets": 2
                },
                {
                    "width": "25%",
                    "targets": 3
                },
            ]

        })
    })
</script>

@endsection

@endsection
