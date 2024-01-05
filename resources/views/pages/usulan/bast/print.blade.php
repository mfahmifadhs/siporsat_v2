<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $usulan->id_usulan }}</title>

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

            .print-container {
                width: 100%;
                page-break-inside: avoid;
            }
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
                <h5 style="font-size: 30px;text-transform:uppercase;"><b>{{ $bast->usulan->pegawai->unitKerja->unitUtama->nama_unit_utama    }}</b></h5>
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
        <div class="col-12">
            <div class="form-group row text-center text-capitalize">
                <div class="col-12">BERITA ACARA SERAH TERIMA</div>
                <div class="col-12">Nomor : {{ $bast->nomor_bast }}</div>
            </div>
            <div class="form-group row">
                <div class="col-12">
                    Pada Hari Ini, Tanggal {{ \Carbon\Carbon::parse($bast->tanggal_bast)->isoFormat('DD MMMM Y') }}
                    bertempat Di Kantor Pusat Kementerian Kesehatan Republik Indonesia, kami
                    yang bertanda tangan dibawah Ini:
                    <div class="row mt-2 mb-2">
                        <div class="col-2 ml-5">Nama</div>:
                        <div class="col-8">{{ $bast->usulan->pegawai->nama_pegawai }}</div>
                        <div class="col-2 ml-5">Jabatan</div>:
                        <div class="col-8">{{ $bast->usulan->pegawai->nama_jabatan }}</div>
                        <div class="col-2 ml-5">Unit Kerja</div>:
                        <div class="col-8">{{ $bast->usulan->pegawai->unitKerja->nama_unit_kerja }}</div>
                    </div>
                </div>
                <div class="col-12">
                    Dalam Berita Acara ini bertindak untuk dan atas nama Biro Umum Sekretariat Jenderal, Pejabat Pembuatan Komitmen
                    selaku yang menyerahkan, yang selanjutnya disebut <b>PIHAK PERTAMA</b>.
                    <div class="row mt-2 mb-2">
                        <div class="col-2 ml-5">Nama</div>:
                        <div class="col-8">{{ $ppk->nama_pegawai }}</div>
                        <div class="col-2 ml-5">Jabatan</div>:
                        <div class="col-8">{{ $ppk->nama_jabatan }}</div>
                        <div class="col-2 ml-5">Unit Kerja</div>:
                        <div class="col-8">Biro Umum</div>
                    </div>
                </div>
                <div class="col-12">
                    Dalam Berita Acara ini bertindak untuk dan atas nama Biro umum Selaku Penerima, yang selanjutnya disebut
                    <b>PIHAK KEDUA</b>. Bahwa <b>PIHAK PERTAMA</b> telah menyerahkan barang/pekerjaan dari/kepada <b>PIHAK KEDUA</b>
                    dengan rincian sebagai berikut:
                </div>
            </div>
        </div>
        <div class="col-12 mb-5">
            @php
            $row1 = $form == 'AADB' && $bast->usulan->form_id != 101 ? 'No. Plat' : ($form == 'atk' ? 'Nama Barang' : 'Pekerjaan');
            $row2 = $form == 'AADB' && $bast->usulan->form_id != 101 ? 'Kendaraan' : ($form == 'atk' ? 'Deskripsi' : 'Spesifikasi');
            $row3 = $form == 'atk' ? 'Jumlah Diserahkan' : 'Keterangan';
            @endphp
            <table class="table table-data text-center text-capitalize">
                <thead>
                    <tr>
                        <th style="width: 0%;">No</th>
                        <th>{{ $row1 }}</th>
                        <th>{{ $row2 }}</th>
                        <th>{{ $row3 }}</th>
                    </tr>
                </thead>
                @php $no = 1; @endphp
                <tbody>
                    @foreach ($bast->detail as $subRow)
                    <!-- UKT -->
                    @if ($usulan->form_id == 501)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td class="text-left text-capitalize" style="width: 35%;">
                            {{ ucfirst(strtolower($subRow->usulanUkt->judul_pekerjaan)) }}
                        </td>
                        <td class="text-left" style="width: 35%;">{!! nl2br(e($subRow->usulanUkt->deskripsi)) !!}</td>
                        <td class="text-left">{!! nl2br(e($subRow->usulanUkt->keterangan)) !!}</td>
                    </tr>
                    @endif

                    <!-- GDN -->
                    @if ($usulan->form_id == 401)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td class="text-left" style="width: 25%;">
                            {{ ucfirst(strtolower($subRow->usulanGdn->bperbaikan->bidang_perbaikan)) }} <br>
                            {{ ucfirst(strtolower($subRow->usulanGdn->judul_pekerjaan)) }}
                        </td>
                        <td class="text-left" style="width: 45%;">{!! nl2br(e($subRow->usulanGdn->deskripsi)) !!}</td>
                        <td class="text-left" style="width: 30%;">{!! nl2br(e($subRow->usulanGdn->keterangan)) !!}</td>
                    </tr>
                    @endif

                    <!-- OLDAT -->
                    @if ($usulan->form_id == 201)
                    <tr class="text-capitalized">
                        <td>{{ $no++ }}</td>
                        <td class="text-left" style="width: 50%;">
                            Permintaan Pengadaan {{ ucfirst(strtolower($subRow->usulanOldat->kategori?->kategori_barang)) }}
                            sebanyak {{ $subRow->usulanOldat->jumlah_pengadaan }} unit dengan estimasi harga
                            Rp {{ number_format($subRow->usulanOldat->estimasi_harga, 0, ',', '.') }}
                        </td>
                        <td class="text-left">{!! nl2br(e($subRow->usulanOldat->spesifikasi)) !!}</td>
                        <td class="text-left">{!! nl2br(e($usulan->keterangan)) !!}</td>
                    </tr>
                    @elseif ($usulan->form_id == 202)
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

                    <!-- AADB -->
                    @if ($usulan->form_id == 101)
                    <tr class="text-capitalized">
                        <td class="align-top" style="width: 0%;">{{ $no++ }}</td>
                        <td class="text-left align-top" style="width: 30%;">
                            Permintaan Pengadaan {{ ucwords(strtolower($subRow->usulanAadb->jenis_aadb.' '.$subRow->usulanAadb->aadb?->kategori_aadb)) }}
                            sebanyak {{ $subRow->usulanAadb->jumlah_pengadaan.' unit' }}
                        </td>
                        <td class="align-top" style="width: 20%;">{{ $subRow->usulanAadb->merk_tipe.' '.$subRow->tahun }}</td>
                        <td class="text-left align-top" style="width: 30%;">{!! nl2br(e($bast->usulan->keterangan)) !!}</td>
                    </tr>
                    @endif

                    @if ($usulan->form_id == 102)
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
                    @elseif ($usulan->form_id == 103)
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

                    @if ($usulan->form_id == 104)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $subRow->usulanBbm->aadb->no_plat }}</td>
                        <td>{{ $subRow->usulanBbm->aadb->merk_tipe.' '.$subRow->usulanBbm->aadb->tahun }}</td>
                        <td>{{ \Carbon\carbon::parse($subRow->usulanBbm->bulan_pengadaan)->isoFormat('MMMM Y') }}</td>
                    </tr>
                    @endif

                    @if ($usulan->form_id == 301    )
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td class="text-left">{{ $subRow->usulanAtk->atk->kategori->kategori_atk }}</td>
                        <td class="text-left">{{ $subRow->usulanAtk->atk->deskripsi }}</td>
                        <td>{{ $subRow->deskripsi }} {{ optional($subRow->usulanAtk->atk->satuan->where('jenis_satuan', 'distribusi')->first())->satuan }}</td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-12 mt-5">
            <div class="col-12 text-capitalize">
                <div class="row text-center">
                    <label class="col-sm-4">Menyerahkan, <br> {{ $ppk->nama_jabatan }}</label>
                    <label class="col-sm-4">Menerima, <br> {{ $bast->usulan->pegawai->nama_jabatan }}</label>
                    <label class="col-sm-4">Mengetahui, <br> {{ $kabagrt->nama_jabatan }}</label>
                </div>
            </div>
            <div class="col-12 mt-4">
                <div class="row text-center">
                    <label class="col-sm-4">{!! QrCode::size(100)->generate('https://siporsat.kemkes.go.id/surat/usulan/ukt/'. $bast->usulan->otp_3) !!}</label>
                    <label class="col-sm-4">{!! QrCode::size(100)->generate('https://siporsat.kemkes.go.id/surat/usulan/ukt/'. $bast->usulan->otp_4) !!}</label>
                    <label class="col-sm-4">{!! QrCode::size(100)->generate('https://siporsat.kemkes.go.id/surat/usulan/ukt/'. $bast->usulan->otp_5) !!}</label>
                </div>
            </div>
            <div class="col-12 mt-4">
                <div class="row text-center">
                    <label class="col-sm-4">{{ $ppk->nama_pegawai }}</label>
                    <label class="col-sm-4">{{ $bast->usulan->pegawai->nama_pegawai }}</label>
                    <label class="col-sm-4">{{ $kabagrt->nama_pegawai }}</label>
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
