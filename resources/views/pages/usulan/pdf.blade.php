<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ \Carbon\carbon::now()->format('His').'-'. strtoupper($form) }}</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('dist_admin/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist_admin/css/adminlte.min.css') }}">
    <style>
        @media print {
            .pagebreak {
                page-break-after: always;
            }

            .table-data {
                border: 1px solid;
                font-size: 20px;
                vertical-align: middle;
            }

            .table-data th,
            .table-data td {
                border: 1px solid;
                vertical-align: middle;
            }

            .table-data thead th,
            .table-data thead td {
                border: 1px solid;
                vertical-align: middle;
            }
        }

        .divTable {
            border-top: 1px solid;
            border-left: 1px solid;
            border-right: 1px solid;
            font-size: 21px;
        }

        .divThead {
            border-bottom: 1px solid;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container" style="font-size: 20px;">
        <div class="row">
            <div class="col-md-8 text-left text-uppercase h2 font-weight-bold" style="color: #3f6791;">
                Daftar Usulan <br>
                @if ($form == 'ukt' || $form == 'gdn')
                {{ $usulan->first()->form->nama_form }}
                @elseif ($form == 'aadb')
                Alat Angkutan Darat Bermotor
                @elseif ($form == 'oldat')
                Olah Data BMN & Meubelair
                @endif
            </div>
            <div class="col-md-4 text-right h4 mt-2">
                {{ \Carbon\carbon::now()->isoFormat('HH:mm') }} <br>
                {{ \Carbon\carbon::now()->isoFormat('DD MMMM Y') }}
            </div>
        </div>
        <hr>
        <div class="table-responsive mt-5">
            <table class="table table-data text-center">
                <thead>
                    <tr>
                        <th style="width: 0%;">No</th>
                        <th style="width: 25%;">Tanggal</th>
                        <th>Pengusul</th>
                        <th style="width: 25%;">Berita Acara</th>
                        @if ($form != 'ukt' && $form != 'gdn')
                        <th style="width: 10%;">Perihal</th>
                        @endif
                    </tr>
                </thead>
                @php $no = 1; @endphp
                @foreach($usulan->sortByDesc('tanggal_usulan') as $row)
                <thead style="font-size: 18px">
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td class="text-left">
                            {{ \Carbon\carbon::parse($row->tanggal_usulan)->isoFormat('DD-MMM-Y') }} <br>
                            {{ $row->id_usulan }} <br>
                            {{ $row->nomor_usulan }}
                        </td>
                        <td>
                            {{ $row->pegawai->nama_pegawai }} <br>
                            {{ $row->pegawai->unitKerja->nama_unit_kerja }}
                        </td>

                        <td class="text-left">
                            @foreach ($row->bast as $subRow)
                            {{ \Carbon\carbon::parse($subRow->tanggal_bast)->isoFormat('DD-MMM-Y') }} <br>
                            {{ $subRow->id_bast.''.\Carbon\carbon::parse($subRow->created_at)->isoFormat('HHmmDDMMYY') }} <br>
                            {{ $subRow->nomor_bast }}
                            @endforeach
                        </td>

                        @if ($form != 'ukt' && $form != 'gdn')
                        <td>{{ strtoupper($row->form->nama_form) }}</td>
                        @endif
                    </tr>
                </thead>
                @endforeach
            </table>
        </div>
    </div>
    <!-- ./wrapper -->
    <!-- Page specific script -->
    <script>
        window.addEventListener("load", window.print());
    </script>
</body>

</html>
