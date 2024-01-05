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
                    <li class="breadcrumb-item"><a href="{{ route($form.'.home') }}">Beranda</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('usulan.show', $form) }}">Daftar Usulan</a></li>
                    <li class="breadcrumb-item">Laporan</li>
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
    <div class="container-fluid">
        <form action="{{ route('usulan.report', $form) }}" class="mb-2">
            <div class="row text-right">
                <div class="col-md-10"></div>
                <div class="col-md-1" >
                    <select name="tahun" class="form-control text-center" style="width: 150%;" id="">
                        @foreach($tahun as $index => $tahun)
                        <option value="{{ $tahun }}" <?php echo $tahunPick == $tahun ? 'selected' : '' ?>>{{ $tahun }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-default border-dark">Pilih</button>
                </div>
            </div>
        </form>
        <div class="row">
            <div class="col-md-12 col-12">
                <div class="card border border-secondary">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="chart">
                                    <div class="chartjs-size-monitor">
                                        <div class="chartjs-size-monitor-expand">
                                            <div class=""></div>
                                        </div>
                                        <div class="chartjs-size-monitor-shrink">
                                            <div class=""></div>
                                        </div>
                                    </div>

                                    <div class="position-relative mb-4">
                                        <div id="barchart_material"></div>
                                    </div>
                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="card-footer">
                        <div class="row">
                            <div class="col-sm-3 col-6">
                                <div class="description-block border-right">
                                    <h4>{{ $usulan->whereIn('status_proses_id',[101,102])->count() }} <small>usulan</small></h4>
                                    <span class="description-text">MENUNGGU PERSETUJUAN</span>
                                </div>

                            </div>

                            <div class="col-sm-3 col-6">
                                <div class="description-block border-right">
                                    <h4>{{ $usulan->where('status_proses_id',103)->count() }} <small>usulan</small></h4>
                                    <span class="description-text">SEDANG DIPROSES</span>
                                </div>

                            </div>

                            <div class="col-sm-3 col-6">
                                <div class="description-block border-right">
                                    <h4>{{ $usulan->where('status_proses_id',106)->count() }} <small>usulan</small></h4>
                                    <span class="description-text">SELESAI BERITA ACARA</span>
                                </div>

                            </div>

                            <div class="col-sm-3 col-6">
                                <div class="description-block">
                                    <h4>{{ $usulan->where('status_proses_id', 100)->count() }} <small>usulan</small></h4>
                                    <span class="description-text">PENGAJUAN DITOLAK</span>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="card border border-secondary">
                    <div class="card-body">
                        <label class="text-muted h6">Rekapitulasi Total Usulan Unit Kerja</label>
                        <table id="table-unitkerja" class="table table-bordered text-center">
                            <thead style="font-size: 15px;">
                                <tr>
                                    <th>No</th>
                                    <th>Unit Kerja</th>
                                    <th>Total Usulan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody style="font-size: 15px;">
                                @foreach ($usulanUker as $i => $row)
                                <tr>
                                    <td>{{ $i+1 }}</td>
                                    <td class="text-left">{{ $row->nama_unit_kerja }}</td>
                                    <td class="h6">{{ $row->total_usulan }}</td>
                                    <td>
                                        <form action="{{ route('usulan.show', $form) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="unit_kerja" value="{{ $row->id_unit_kerja }}">
                                            <button type="submit" class="btn btn-primary btn-sm">
                                                Detail <i class="fas fa-arrow-circle-right fa-1x"></i>
                                            </button>
                                        </form>

                                    </td>
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
            "autoWidth": false,
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
                    "width": "15%",
                    "targets": 3
                },
            ]

        })

        // grafik usulan
        let chartData = JSON.parse(`<?php echo $chart; ?>`)
        google.charts.load('current', {
            'packages': ['bar']
        });
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable(chartData);

            var options = {
                height: 300,
                width: 1100,
                chart: {
                    subtitle: 'Rekapitulasi Total Usulan',
                },
                series: {
                    0: {
                        color: '#6495ED'
                    },
                    1: {
                        color: '#DC143C'
                    },
                    2: {
                        color: '#40E0D0'
                    },
                    3: {
                        color: '#FF8C00'
                    },
                }
            };

            var chart = new google.charts.Bar(document.getElementById('barchart_material'));

            chart.draw(data, google.charts.Bar.convertOptions(options));
        }
    })
</script>

@endsection

@endsection
