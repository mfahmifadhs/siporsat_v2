@extends('layout.app')

@section('css')
<style>
    .modul-title {
        background-color: #fff;
        color: #000 !important;
    }

    .modul-title:hover {
        background-color: #f0f0f0;
        cursor: default;
    }

    th {
        color: #000;
    }

    td:hover {
        background-color: #f0f0f0;
    }

    td:hover {
        cursor: pointer;
    }
</style>
@endsection

@section('content')

<!-- Main content -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="text-capitalize">SIPORSAT</h1>
                        <h5>Selamat Datang, {{ Auth::user()->pegawai->nama_pegawai  }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid bg-img">
        <div class="alert alert-info alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-info"></i> Alert!</h5>
            Info alert preview. This alert is dismissable.
        </div>

        <div class="card card-tabs">
            <div class="card-header p-0 pt-1">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active btn btn-primary m-2" id="tabs-2024" data-toggle="pill" href="#table-tabs-2024" role="tab" aria-controls="table-tabs-2024" aria-selected="true">
                            2024
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-primary m-2" id="tabs-2023" data-toggle="pill" href="#table-tabs-2023" role="tab" aria-controls="table-tabs-2023" aria-selected="false">
                            2023
                        </a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    @foreach ($tahun as $index => $tahun)
                    <div class="tab-pane fade {{ $index == 0 ? 'show active' : '' }}" id="table-tabs-{{ $tahun }}" role="tabpanel" aria-labelledby="tabs-{{ $tahun }}">
                        <table class="table table-bordered text-center">
                            <thead style="font-size: 13px;">
                                <tr>
                                    <th style="width: 30%;" class="p-4"></th>
                                    @foreach ($status as $row)
                                    <th class="text-uppercase align-middle">{{ $row->nama_status }}</th>
                                    @endforeach
                                    <th class="align-middle">DITOLAK</th>
                                </tr>
                            </thead>
                            <tbody style="font-size: 14px;">
                                <!-- =================================================
                                        STATUS USULAN (OLDAT)OLDAH DATA BMN DAN MEUBELAIR
                                     ===================================================== -->
                                <tr>
                                    <td class="text-left font-weight-bold modul-title">(OLDAT) Olah Data BMN & Meubelair</td>
                                    <td onclick="processClick('odt', 101)">
                                        @php $total101 = $usulan->whereIn('form_id', [201,202])->where('status_proses_id', 101)->where('tahun', $tahun)->count(); @endphp
                                        <form id="odt-101" action="{{ route('usulan.show', 'oldat') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="101">
                                            <input type="hidden" name="tahun" value="{{ $tahun }}">
                                            <button type="submit" class="bg-transparent border border-transparent font-weight-bold {{ $total101 == 0 ? 'text-success' : 'text-danger' }}">
                                                {{ $total101 }}
                                                <h6 class="text-monospace small mt-0 mb-0">usulan</h6>
                                            </button>
                                        </form>
                                    </td>

                                    <td onclick="processClick('odt', 102)">
                                        @php $total102 = $usulan->whereIn('form_id', [201,202])->where('status_proses_id', 102)->where('tahun', $tahun)->count(); @endphp
                                        <form id="odt-102" action="{{ route('usulan.show', 'oldat') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="102">
                                            <input type="hidden" name="tahun" value="{{ $tahun }}">
                                            <button type="submit" class="bg-transparent border border-transparent font-weight-bold {{ $total102 == 0 ? 'text-success' : 'text-danger' }}">
                                                {{ $total102 }}
                                                <h6 class="text-monospace small mt-0 mb-0">usulan</h6>
                                            </button>
                                        </form>
                                    </td>

                                    <td onclick="processClick('odt', 103)">
                                        @php $total103 = $usulan->whereIn('form_id', [201,202])->where('status_proses_id', 103)->where('tahun', $tahun)->count(); @endphp
                                        <form id="odt-103" action="{{ route('usulan.show', 'oldat') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="103">
                                            <input type="hidden" name="tahun" value="{{ $tahun }}">
                                            <button type="submit" class="bg-transparent border border-transparent font-weight-bold {{ $total103 == 0 ? 'text-success' : 'text-danger' }}">
                                                {{ $total103 }}
                                                <h6 class="text-monospace small mt-0 mb-0">usulan</h6>
                                            </button>
                                        </form>
                                    </td>

                                    <td onclick="processClick('odt', 105)">
                                        @php $total105 = $usulan->whereIn('form_id', [201,202])->where('status_proses_id', 105)->where('tahun', $tahun)->count(); @endphp
                                        <form id="odt-105" action="{{ route('usulan.show', 'oldat') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="105">
                                            <input type="hidden" name="tahun" value="{{ $tahun }}">
                                            <button type="submit" class="bg-transparent border border-transparent font-weight-bold {{ $total105 == 0 ? 'text-success' : 'text-danger' }}">
                                                {{ $total105 }}
                                                <h6 class="text-monospace small mt-0 mb-0">usulan</h6>
                                            </button>
                                        </form>
                                    </td>

                                    <td onclick="processClick('odt', 106)">
                                        @php $total106 = $usulan->whereIn('form_id', [201,202])->where('status_proses_id', 106)->where('tahun', $tahun)->count(); @endphp
                                        <form id="odt-106" action="{{ route('usulan.show', 'oldat') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="106">
                                            <input type="hidden" name="tahun" value="{{ $tahun }}">
                                            <button type="submit" class="bg-transparent border border-transparent font-weight-bold text-success">
                                                {{ $total106 }}
                                                <h6 class="text-monospace small mt-0 mb-0">usulan</h6>
                                            </button>
                                        </form>
                                    </td>

                                    <td onclick="processClick('odt', 100)">
                                        @php $total100 = $usulan->whereIn('form_id', [201,202])->where('status_proses_id', 100)->where('tahun', $tahun)->count(); @endphp
                                        <form id="odt-100" action="{{ route('usulan.show', 'oldat') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="100">
                                            <input type="hidden" name="tahun" value="{{ $tahun }}">
                                            <button type="submit" class="bg-transparent border border-transparent font-weight-bold {{ $total100 == 0 ? 'text-success' : 'text-danger' }}">
                                                {{ $total100 }}
                                                <h6 class="text-monospace small mt-0 mb-0">usulan</h6>
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- ================================================
                                        STATUS USULAN (AADB) ALAT ANGKUTAN DARAT BERMOTOR
                                     ==================================================== -->

                                <tr>
                                    <td class="text-left font-weight-bold modul-title">(AADB) Alat Angkutan Darat Bermotor</td>
                                    <td onclick="processClick('aadb', 101)">
                                        @php $total101 = $usulan->whereIn('form_id', [101,102,103,104])->where('status_proses_id', 101)->where('tahun', $tahun)->count(); @endphp
                                        <form id="aadb-101" action="{{ route('usulan.show', 'aadb') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="101">
                                            <input type="hidden" name="tahun" value="{{ $tahun }}">
                                            <button type="submit" class="bg-transparent border border-transparent font-weight-bold {{ $total101 == 0 ? 'text-success' : 'text-danger' }}">
                                                {{ $total101 }}
                                                <h6 class="text-monospace small mt-0 mb-0">usulan</h6>
                                            </button>
                                        </form>
                                    </td>

                                    <td onclick="processClick('aadb', 102)">
                                        @php $total102 = $usulan->whereIn('form_id', [101,102,103,104])->where('status_proses_id', 102)->where('tahun', $tahun)->count(); @endphp
                                        <form id="aadb-102" action="{{ route('usulan.show', 'aadb') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="102">
                                            <input type="hidden" name="tahun" value="{{ $tahun }}">
                                            <button type="submit" class="bg-transparent border border-transparent font-weight-bold {{ $total102 == 0 ? 'text-success' : 'text-danger' }}">
                                                {{ $total102 }}
                                                <h6 class="text-monospace small mt-0 mb-0">usulan</h6>
                                            </button>
                                        </form>
                                    </td>

                                    <td onclick="processClick('aadb', 103)">
                                        @php $total103 = $usulan->whereIn('form_id', [101,102,103,104])->where('status_proses_id', 103)->where('tahun', $tahun)->count(); @endphp
                                        <form id="aadb-103" action="{{ route('usulan.show', 'aadb') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="103">
                                            <input type="hidden" name="tahun" value="{{ $tahun }}">
                                            <button type="submit" class="bg-transparent border border-transparent font-weight-bold {{ $total103 == 0 ? 'text-success' : 'text-danger' }}">
                                                {{ $total103 }}
                                                <h6 class="text-monospace small mt-0 mb-0">usulan</h6>
                                            </button>
                                        </form>
                                    </td>

                                    <td onclick="processClick('aadb', 105)">
                                        @php $total105 = $usulan->whereIn('form_id', [101,102,103,104])->where('status_proses_id', 105)->where('tahun', $tahun)->count(); @endphp
                                        <form id="aadb-105" action="{{ route('usulan.show', 'aadb') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="105">
                                            <input type="hidden" name="tahun" value="{{ $tahun }}">
                                            <button type="submit" class="bg-transparent border border-transparent font-weight-bold {{ $total105 == 0 ? 'text-success' : 'text-danger' }}">
                                                {{ $total105 }}
                                                <h6 class="text-monospace small mt-0 mb-0">usulan</h6>
                                            </button>
                                        </form>
                                    </td>

                                    <td onclick="processClick('aadb', 106)">
                                        @php $total106 = $usulan->whereIn('form_id', [101,102,103,104])->where('status_proses_id', 106)->where('tahun', $tahun)->count(); @endphp
                                        <form id="aadb-106" action="{{ route('usulan.show', 'aadb') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="106">
                                            <input type="hidden" name="tahun" value="{{ $tahun }}">
                                            <button type="submit" class="bg-transparent border border-transparent font-weight-bold text-success">
                                                {{ $total106 }}
                                                <h6 class="text-monospace small mt-0 mb-0">usulan</h6>
                                            </button>
                                        </form>
                                    </td>

                                    <td onclick="processClick('aadb', 100)">
                                        @php $total100 = $usulan->whereIn('form_id', [101,102,103,104])->where('status_proses_id', 100)->where('tahun', $tahun)->count(); @endphp
                                        <form id="aadb-100" action="{{ route('usulan.show', 'aadb') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="100">
                                            <input type="hidden" name="tahun" value="{{ $tahun }}">
                                            <button type="submit" class="bg-transparent border border-transparent font-weight-bold {{ $total100 == 0 ? 'text-success' : 'text-danger' }}">
                                                {{ $total100 }}
                                                <h6 class="text-monospace small mt-0 mb-0">usulan</h6>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                <!-- ================================================
                                        STATUS USULAN (ATK) ALAT TULIS KANTOR
                                     ==================================================== -->
                                <tr>
                                    <td class="text-left font-weight-bold modul-title">(ATK) Alat Tulis Kantor</td>
                                    <td onclick="processClick('atk', 101)">
                                        @php $total101 = $usulan->where('form_id', 301)->where('status_proses_id', 101)->where('tahun', $tahun)->count(); @endphp
                                        <form id="atk-101" action="{{ route('usulan.show', 'atk') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="101">
                                            <input type="hidden" name="tahun" value="{{ $tahun }}">
                                            <button type="submit" class="bg-transparent border border-transparent font-weight-bold {{ $total101 == 0 ? 'text-success' : 'text-danger' }}">
                                                {{ $total101 }}
                                                <h6 class="text-monospace small mt-0 mb-0">usulan</h6>
                                            </button>
                                        </form>
                                    </td>

                                    <td onclick="processClick('atk', 102)">
                                        @php $total102 = $usulan->where('form_id', 301)->where('status_proses_id', 102)->where('tahun', $tahun)->count(); @endphp
                                        <form id="atk-102" action="{{ route('usulan.show', 'atk') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="102">
                                            <input type="hidden" name="tahun" value="{{ $tahun }}">
                                            <button type="submit" class="bg-transparent border border-transparent font-weight-bold {{ $total102 == 0 ? 'text-success' : 'text-danger' }}">
                                                {{ $total102 }}
                                                <h6 class="text-monospace small mt-0 mb-0">usulan</h6>
                                            </button>
                                        </form>
                                    </td>

                                    <td onclick="processClick('atk', 103)">
                                        @php $total103 = $usulan->where('form_id', 301)->where('status_proses_id', 103)->where('tahun', $tahun)->count(); @endphp
                                        <form id="atk-103" action="{{ route('usulan.show', 'atk') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="103">
                                            <input type="hidden" name="tahun" value="{{ $tahun }}">
                                            <button type="submit" class="bg-transparent border border-transparent font-weight-bold {{ $total103 == 0 ? 'text-success' : 'text-danger' }}">
                                                {{ $total103 }}
                                                <h6 class="text-monospace small mt-0 mb-0">usulan</h6>
                                            </button>
                                        </form>
                                    </td>

                                    <td onclick="processClick('atk', 105)">
                                        @php $total105 = $usulan->where('form_id', 301)->where('status_proses_id', 105)->where('tahun', $tahun)->count(); @endphp
                                        <form id="atk-105" action="{{ route('usulan.show', 'atk') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="105">
                                            <input type="hidden" name="tahun" value="{{ $tahun }}">
                                            <button type="submit" class="bg-transparent border border-transparent font-weight-bold {{ $total105 == 0 ? 'text-success' : 'text-danger' }}">
                                                {{ $total105 }}
                                                <h6 class="text-monospace small mt-0 mb-0">usulan</h6>
                                            </button>
                                        </form>
                                    </td>

                                    <td onclick="processClick('atk', 106)">
                                        @php $total106 = $usulan->where('form_id', 301)->where('status_proses_id', 106)->where('tahun', $tahun)->count(); @endphp
                                        <form id="atk-106" action="{{ route('usulan.show', 'atk') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="106">
                                            <input type="hidden" name="tahun" value="{{ $tahun }}">
                                            <button type="submit" class="bg-transparent border border-transparent font-weight-bold text-success">
                                                {{ $total106 }}
                                                <h6 class="text-monospace small mt-0 mb-0">usulan</h6>
                                            </button>
                                        </form>
                                    </td>

                                    <td onclick="processClick('atk', 100)">
                                        @php $total100 = $usulan->where('form_id', 301)->where('status_proses_id', 100)->where('tahun', $tahun)->count(); @endphp
                                        <form id="atk-100" action="{{ route('usulan.show', 'atk') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="100">
                                            <input type="hidden" name="tahun" value="{{ $tahun }}">
                                            <button type="submit" class="bg-transparent border border-transparent font-weight-bold {{ $total100 == 0 ? 'text-success' : 'text-danger' }}">
                                                {{ $total100 }}
                                                <h6 class="text-monospace small mt-0 mb-0">usulan</h6>
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- ====================================
                                        STATUS USULAN (GDN) GEDUNG BANGUNAN
                                     ======================================== -->

                                <tr>
                                    <td class="text-left font-weight-bold modul-title">(GDN) Gedung dan Bangunan</td>
                                    <td onclick="processClick('gdn', 101)">
                                        @php $total101 = $usulan->where('form_id', 401)->where('status_proses_id', 101)->where('tahun', $tahun)->count(); @endphp
                                        <form id="gdn-101" action="{{ route('usulan.show', 'gdn') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="101">
                                            <input type="hidden" name="tahun" value="{{ $tahun }}">
                                            <button type="submit" class="bg-transparent border border-transparent font-weight-bold {{ $total101 == 0 ? 'text-success' : 'text-danger' }}">
                                                {{ $total101 }}
                                                <h6 class="text-monospace small mt-0 mb-0">usulan</h6>
                                            </button>
                                        </form>
                                    </td>

                                    <td onclick="processClick('gdn', 102)">
                                        @php $total102 = $usulan->where('form_id', 401)->where('status_proses_id', 102)->where('tahun', $tahun)->count(); @endphp
                                        <form id="gdn-102" action="{{ route('usulan.show', 'gdn') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="102">
                                            <input type="hidden" name="tahun" value="{{ $tahun }}">
                                            <button type="submit" class="bg-transparent border border-transparent font-weight-bold {{ $total102 == 0 ? 'text-success' : 'text-danger' }}">
                                                {{ $total102 }}
                                                <h6 class="text-monospace small mt-0 mb-0">usulan</h6>
                                            </button>
                                        </form>
                                    </td>

                                    <td onclick="processClick('gdn', 103)">
                                        @php $total103 = $usulan->where('form_id', 401)->where('status_proses_id', 103)->where('tahun', $tahun)->count(); @endphp
                                        <form id="gdn-103" action="{{ route('usulan.show', 'gdn') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="103">
                                            <input type="hidden" name="tahun" value="{{ $tahun }}">
                                            <button type="submit" class="bg-transparent border border-transparent font-weight-bold {{ $total103 == 0 ? 'text-success' : 'text-danger' }}">
                                                {{ $total103 }}
                                                <h6 class="text-monospace small mt-0 mb-0">usulan</h6>
                                            </button>
                                        </form>
                                    </td>

                                    <td onclick="processClick('gdn', 105)">
                                        @php $total105 = $usulan->where('form_id', 401)->where('status_proses_id', 105)->where('tahun', $tahun)->count(); @endphp
                                        <form id="gdn-105" action="{{ route('usulan.show', 'gdn') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="105">
                                            <input type="hidden" name="tahun" value="{{ $tahun }}">
                                            <button type="submit" class="bg-transparent border border-transparent font-weight-bold {{ $total105 == 0 ? 'text-success' : 'text-danger' }}">
                                                {{ $total105 }}
                                                <h6 class="text-monospace small mt-0 mb-0">usulan</h6>
                                            </button>
                                        </form>
                                    </td>

                                    <td onclick="processClick('gdn', 106)">
                                        @php $total106 = $usulan->where('form_id', 401)->where('status_proses_id', 106)->where('tahun', $tahun)->count(); @endphp
                                        <form id="gdn-106" action="{{ route('usulan.show', 'gdn') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="106">
                                            <input type="hidden" name="tahun" value="{{ $tahun }}">
                                            <button type="submit" class="bg-transparent border border-transparent font-weight-bold text-success">
                                                {{ $total106 }}
                                                <h6 class="text-monospace small mt-0 mb-0">usulan</h6>
                                            </button>
                                        </form>
                                    </td>

                                    <td onclick="processClick('gdn', 100)">
                                        @php $total100 = $usulan->where('form_id', 401)->where('status_proses_id', 100)->where('tahun', $tahun)->count(); @endphp
                                        <form id="gdn-100" action="{{ route('usulan.show', 'gdn') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="100">
                                            <input type="hidden" name="tahun" value="{{ $tahun }}">
                                            <button type="submit" class="bg-transparent border border-transparent font-weight-bold {{ $total100 == 0 ? 'text-success' : 'text-danger' }}">
                                                {{ $total100 }}
                                                <h6 class="text-monospace small mt-0 mb-0">usulan</h6>
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- =========================================
                                        STATUS USULAN (UKT) URUSAN KERUMAHTANGGAAN
                                     ============================================= -->
                                <tr>
                                    <td class="text-left font-weight-bold modul-title">(UKT) Urusan Kerumahtanggaan</td>
                                    <td onclick="processClick('ukt', 101)">
                                        @php $total101 = $usulan->where('form_id', 501)->where('status_proses_id', 101)->where('tahun', $tahun)->count(); @endphp
                                        <form id="ukt-101" action="{{ route('usulan.show', 'ukt') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="101">
                                            <input type="hidden" name="tahun" value="{{ $tahun }}">
                                            <button type="submit" class="bg-transparent border border-transparent font-weight-bold {{ $total101 == 0 ? 'text-success' : 'text-danger' }}">
                                                {{ $total101 }}
                                                <h6 class="text-monospace small mt-0 mb-0">usulan</h6>
                                            </button>
                                        </form>
                                    </td>

                                    <td onclick="processClick('ukt', 102)">
                                        @php $total102 = $usulan->where('form_id', 501)->where('status_proses_id', 102)->where('tahun', $tahun)->count(); @endphp
                                        <form id="ukt-102" action="{{ route('usulan.show', 'ukt') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="102">
                                            <input type="hidden" name="tahun" value="{{ $tahun }}">
                                            <button type="submit" class="bg-transparent border border-transparent font-weight-bold {{ $total102 == 0 ? 'text-success' : 'text-danger' }}">
                                                {{ $total102 }}
                                                <h6 class="text-monospace small mt-0 mb-0">usulan</h6>
                                            </button>
                                        </form>
                                    </td>

                                    <td onclick="processClick('ukt', 103)">
                                        @php $total103 = $usulan->where('form_id', 501)->where('status_proses_id', 103)->where('tahun', $tahun)->count(); @endphp
                                        <form id="ukt-103" action="{{ route('usulan.show', 'ukt') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="103">
                                            <input type="hidden" name="tahun" value="{{ $tahun }}">
                                            <button type="submit" class="bg-transparent border border-transparent font-weight-bold {{ $total103 == 0 ? 'text-success' : 'text-danger' }}">
                                                {{ $total103 }}
                                                <h6 class="text-monospace small mt-0 mb-0">usulan</h6>
                                            </button>
                                        </form>
                                    </td>

                                    <td onclick="processClick('ukt', 105)">
                                        @php $total105 = $usulan->where('form_id', 501)->where('status_proses_id', 105)->where('tahun', $tahun)->count(); @endphp
                                        <form id="ukt-105" action="{{ route('usulan.show', 'ukt') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="105">
                                            <input type="hidden" name="tahun" value="{{ $tahun }}">
                                            <button type="submit" class="bg-transparent border border-transparent font-weight-bold {{ $total105 == 0 ? 'text-success' : 'text-danger' }}">
                                                {{ $total105 }}
                                                <h6 class="text-monospace small mt-0 mb-0">usulan</h6>
                                            </button>
                                        </form>
                                    </td>

                                    <td onclick="processClick('ukt', 106)">
                                        @php $total106 = $usulan->where('form_id', 501)->where('status_proses_id', 106)->where('tahun', $tahun)->count();
                                        @endphp
                                        <form id="ukt-106" action="{{ route('usulan.show', 'ukt') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="106">
                                            <input type="hidden" name="tahun" value="{{ $tahun }}">
                                            <button type="submit" class="bg-transparent border border-transparent font-weight-bold text-success">
                                                {{ $total106 }}
                                                <h6 class="text-monospace small mt-0 mb-0">usulan</h6>
                                            </button>
                                        </form>
                                    </td>

                                    <td onclick="processClick('ukt', 100)">
                                        @php $total100 = $usulan->where('form_id', 501)->where('status_proses_id', 100)->where('tahun', $tahun)->count(); @endphp
                                        <form id="ukt-100" action="{{ route('usulan.show', 'ukt') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="100">
                                            <input type="hidden" name="tahun" value="{{ $tahun }}">
                                            <button type="submit" class="bg-transparent border border-transparent font-weight-bold {{ $total100 == 0 ? 'text-success' : 'text-danger' }}">
                                                {{ $total100 }}
                                                <h6 class="text-monospace small mt-0 mb-0">usulan</h6>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</section>

@section('js')
<script>
    $(function() {
        $("#table").DataTable({
            "responsive": true,
            "lengthChange": false,
            "searching": false,
            "info": false,
            "paging": false,
            "autoWidth": false,
        }).buttons().container().appendTo('#table_wrapper .col-md-6:eq(0)');
    });

    function processClick(prefix, id) {
        // Dapatkan ID dari elemen formulir
        var formId = prefix + '-' + id;

        // Dapatkan formulir dengan ID tersebut
        var form = document.getElementById(formId);

        // Periksa apakah formulir ditemukan
        if (form) {
            // Submit formulir
            form.submit();
        }
    }
</script>
@endsection

@endsection
