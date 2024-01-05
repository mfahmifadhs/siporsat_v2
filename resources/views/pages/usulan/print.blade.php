<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $usulan->id_form_usulan }}</title>

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
            }

            .table-data th,
            .table-data td {
                border: 1px solid;
            }

            .table-data thead th,
            .table-data thead td {
                border: 1px solid;
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

        .divTbody {
            border-bottom: 1px solid;
            text-transform: capitalize;
        }

        .divTheadtd {
            border-right: 1px solid;
        }

        .divTbodytd {
            border-right: 1px solid;
            padding: 10px;
        }
    </style>
</head>

<body>
    <div class="row">
        <div class="col-2">
            <h2 class="page-header ml-4">
                <img src="{{ asset('dist_admin/img/logo-kemenkes-icon.png') }}">
            </h2>
        </div>
        <div class="col-8 text-center">
            <h2 class="page-header">
                <h5 style="font-size: 30px;text-transform:uppercase;"><b>KEMENTERIAN KESEHATAN REPUBLIK INDONESIA</b></h5>
                <h5 style="font-size: 30px;text-transform:uppercase;"><b>{{ $usulan->pegawai->unitKerja->unitUtama->nama_unit_utama }}</b></h5>
                <p style="font-size: 18px;"><i>
                        Jl. H.R. Rasuna Said Blok X.5 Kav. 4-9, Jakarta 12950 <br>
                        Telepon : (021) 5201590</i>
                </p>
            </h2>
        </div>
        <div class="col-2">
            <h2 class="page-header">
                <img src="{{ asset('dist_admin/img/logo-germas.png') }}" style="width: 128px; height: 128px;">
            </h2>
        </div>
        <div class="col-12">
            <hr style="border-width: medium;border-color: black;">
            <hr style="border-width: 1px;border-color: black;margin-top: -11px;">
        </div>
    </div>
    <div class="row" style="font-size: 20px;">
        <div class="col-9">
            <div class="form-group row">
                <div class="col-2">Nomor</div>
                <div class="col-10 text-uppercase">: {{ $usulan->nomor_usulan }}</div>
                <div class="col-2">Hal</div>
                <div class="col-10 text-capitalize">:
                    {{ $usulan->form->nama_form }}
                </div>
                <div class="col-2 mt-4">Pengusul</div>
                <div class="col-10 text-capitalize mt-4">: {{ ucfirst(strtolower($usulan->pegawai->nama_pegawai)) }} </div>
                <div class="col-2">Jabatan</div>
                <div class="col-10">: {{ $usulan->pegawai->nama_jabatan }} </div>
                <div class="col-2">Unit Kerja</div>
                <div class="col-10 text-capitalize">: {{ ucfirst(strtolower($usulan->pegawai->unitKerja->nama_unit_kerja)) }} </div>
                <div class="col-2">Keterangan</div>
                <div class="col-10 text-capitalize">:
                    {{ $usulan->keterangan ? $usulan->keterangan : '-' }}
                </div>
            </div>
        </div>
        <div class="col-3 text-right">
            <div class="col-12">{{ \Carbon\Carbon::parse($usulan->tanggal_usulan)->isoFormat('DD MMMM Y') }}</div>
        </div>
        <div class="col-12 mt-4">
            @php
            $row1 = ($form == 'aadb' && $usulan->form_id != 101) ? 'No. Plat' : (($form == 'atk') ? 'Nama Barang' : 'Pekerjaan');
            $row2 = $form == 'aadb' && $usulan->form_id != 101 ? 'Kendaraan' : 'Deskripsi';
            $row3 = $form == 'atk' ? 'Permintaan' : 'Keterangan'
            @endphp
            <table class="table table-data text-center">
                <thead>
                    <tr>
                        <th style="width: 0%;">No</th>
                        <th>{{ $row1 }}</th>
                        <th>{{ $row2 }}</th>
                        <th>{{ $row3 }}</th>
                        @if ($form == 'atk')
                        <th>Disetujui</th>
                        @endif
                    </tr>
                </thead>
                @php $no = 1; @endphp
                <tbody>
                    @foreach ($usulan->usulanAtk as $subRow)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td class="text-left">{{ $subRow->atk->kategori->kategori_atk }}</td>
                        <td class="text-left">{{ $subRow->atk->deskripsi }}</td>
                        <td>{{ $subRow->jumlah_permintaan }} {{ optional($subRow->atk->satuan->where('jenis_satuan', 'distribusi')->first())->satuan }}</td>
                        <td>
                            @if ($subRow->jumlah_disetujui)
                            {{ $subRow->jumlah_disetujui }} {{ optional($subRow->atk->satuan->where('jenis_satuan', 'distribusi')->first())->satuan }}
                            @endif
                        </td>
                    </tr>
                    @endforeach

                    @foreach ($usulan->usulanUkt as $row)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td class="text-left" style="width: 35%;">{{ $row->judul_pekerjaan }}</td>
                        <td class="text-left" style="width: 35%;">{!! nl2br(e($row->deskripsi)) !!}</td>
                        <td class="text-left">{!! nl2br(e($row->keterangan)) !!}</td>
                    </tr>
                    @endforeach
                    @foreach ($usulan->usulanGdn as $row)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td class="text-left">{{ $row->judul_pekerjaan }}</td>
                        <td class="text-left">{!! nl2br(e($row->deskripsi)) !!}</td>
                        <td class="text-left">{!! nl2br(e($row->keterangan)) !!}</td>
                    </tr>
                    @endforeach

                    @foreach ($usulan->usulanOldat as $row)
                    @if ($usulan->form_id == 201)
                    <tr class="text-capitalized">
                        <td class="align-top">{{ $no++ }}</td>
                        <td class="text-left align-top">
                            Permintaan Pengadaan {{ ucfirst(strtolower($row->kategori?->kategori_barang)) }}
                            sebanyak {{ $row->jumlah_pengadaan }} unit dengan estimasi harga
                            Rp {{ number_format($row->estimasi_harga, 0, ',', '.') }}
                        </td>
                        <td class="text-left">{!! nl2br(e($row->spesifikasi)) !!}</td>
                        <td class="text-left align-top">{!! nl2br(e($usulan->keterangan)) !!}</td>
                    </tr>
                    @elseif ($usulan->form_id == 202)
                    <tr class="text-capitalized">
                        <td>{{ $no++ }}</td>
                        <td class="text-left">
                            Perbaikan {{ ucfirst(strtolower($row->barang->kategori->kategori_barang)) }}
                            ({{ $row->barang->kategori_id.'.'.$row->barang->nup }})
                        </td>
                        <td class="text-left">
                            {{ ucfirst(strtolower($row->barang->merk_tipe.' '.$row->barang?->spesifikasi)) }}
                        </td>
                        <td class="text-left">{!! nl2br(e($row->keterangan_kerusakan)) !!}</td>
                    </tr>
                    @endif
                    @endforeach

                    @foreach ($usulan->usulanAadb as $row)
                    <tr class="text-capitalized">
                        <td class="align-top">{{ $no++ }}</td>
                        <td class="text-left align-top" style="width: 30%;">
                            Permintaan Pengadaan {{ ucwords(strtolower($row->jenis_aadb.' '.$row->aadb?->kategori_aadb)) }}
                            sebanyak {{ $row->jumlah_pengadaan.' unit' }}
                        </td>
                        <td class="align-top" style="width: 25%;">{{ $row->merk_tipe.' '.$row->tahun }}</td>
                        <td class="text-left align-top" style="width: 30%;">{!! nl2br(e($usulan->keterangan)) !!}</td>
                    </tr>
                    @endforeach

                    @foreach ($usulan->usulanStnk as $row)
                    @if ($usulan->form_id == 102)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td style="width: 20%;">{{ $row->aadb->no_plat }}</td>
                        <td style="width: 30%;">{{ $row->aadb->merk_tipe.' '.$row->aadb->tahun }}</td>
                        <td class="text-left">
                            {{ $row->keterangan }}
                            @if ($row->kilometer)
                            , Kilometer saat ini {{ number_format($row->kilometer, 0, ',', '.') }} km
                            @endif
                            @if ($row->tanggal_servis)
                            , Terakhir servis {{ \Carbon\carbon::parse($row->tanggal_servis)->isoFormat('MMMM Y') }}
                            @endif
                            @if ($row->tanggal_ganti_oli)
                            , Terakhir ganti oli {{ \Carbon\carbon::parse($row->tanggal_ganti_oli)->isoFormat('MMMM Y') }}
                            @endif.
                        </td>
                    </tr>
                    @elseif ($usulan->form_id == 103)
                    @if ($row->keterangan == 'true')
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td style="width: 20%;">{{ $row->aadb->no_plat }}</td>
                        <td style="width: 35%;">{{ $row->aadb->merk_tipe.' '.$row->aadb->tahun }}</td>
                        <td class="text-left">
                            Masa Berlaku STNK :
                            {{ \Carbon\carbon::parse($row->tanggal_stnk)->isoFormat('DD MMMM Y') }}
                        </td>
                    </tr>
                    @endif
                    @endif
                    @endforeach

                    @foreach ($usulan->usulanBbm->where('status_pengajuan', 'true') as $row)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $row->aadb->no_plat }}</td>
                        <td>{{ $row->aadb->merk_tipe.' '.$row->aadb->tahun }}</td>
                        <td>{{ \Carbon\carbon::parse($row->bulan_pengadaan)->isoFormat('MMMM Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-12 mt-5">
            <div class="col-12 text-capitalize">
                <div class="row text-center">
                    <label class="col-sm-6">Yang Mengusulkan, <br> {{ $usulan->pegawai->nama_jabatan }}</label>
                    @if ($usulan->otp_2)
                    <label class="col-sm-6">Disetujui Oleh, <br> {{ $kabagrt->nama_jabatan }}</label>
                    @endif
                </div>
            </div>
            <div class="col-12 mt-4">
                <div class="row text-center">
                    <label class="col-sm-6">{!! QrCode::size(100)->generate('https://siporsat.kemkes.go.id/surat/usulan/ukt/'. $usulan->otp_1) !!}</label>
                    @if ($usulan->otp_2)
                    <label class="col-sm-6">{!! QrCode::size(100)->generate('https://siporsat.kemkes.go.id/surat/usulan/ukt/'. $usulan->otp_2) !!}</label>
                    @endif
                </div>
            </div>
            <div class="col-12 mt-4">
                <div class="row text-center">
                    <label class="col-sm-6">{{ $usulan->pegawai->nama_pegawai }}</label>
                    @if ($usulan->otp_2)
                    <label class="col-sm-6">{{ $kabagrt->nama_pegawai }}</label>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- ./wrapper -->
    <!-- Page specific script -->
    <script>
        window.addEventListener("load", window.print());
    </script>
</body>

</html>
