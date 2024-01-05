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
                    <li class="breadcrumb-item">Daftar Usulan</li>
                </ol>
            </div>
            <div class="col-sm-6 mt-3">
                <div class="form-group text-right">
                    <a href="{{ route('usulan.create', ['form' => $form, 'id' => '*']) }}" class="btn btn-add">
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
                <h3 class="card-title font-weight-bold">Daftar Usulan</h3>
            </div>
            <div class="card-header mt-2">
                <form action="{{ route('usulan.show', $form) }}" method="POST">
                    @csrf
                    <input type="hidden" name="filter" value="true">
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-10">
                            <div class="row">
                                <div class="form-group col-4">
                                    <select name="unit_kerja" class="form-control form-control-sm">
                                        <option value="">Seluruh Unit Kerja</option>
                                        @php
                                        $arrUker = Auth::user()->pegawai->unitKerja->unit_utama_id == 46593 && $form != 'ukt' && $form != 'gdn' ? $uker->where('unit_utama_id', 46593) : $uker;
                                        @endphp
                                        @foreach ($arrUker as $row)
                                        <option value="{{ $row->id_unit_kerja }}" <?php echo $ukerPick ? ($row->id_unit_kerja == $ukerPick->id_unit_kerja ? 'selected' : '') : '' ?>>
                                            {{ $row->nama_unit_kerja }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-4">
                                    <select name="bulan" class="form-control form-control-sm">
                                        <option value="">Seluruh Bulan</option>
                                        @if ($bulanPick)
                                        <option value="{{ $bulanPick->first()['id'] }}" selected>{{ $bulanPick->first()['nama_bulan'] }}</option>
                                        @endif
                                        @foreach ($bulan as $i => $row)
                                        <option value="{{ $row['id'] }}">{{ $row['nama_bulan'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-4">
                                    <select name="status" class="form-control form-control-sm small">
                                        <option value="">Seluruh Status</option>
                                        @foreach ($status as $row)
                                        <option value="{{ $row->id_status }}" <?php echo $statusPick ? ($row->id_status == $statusPick->id_status ? 'selected' : '') : '' ?>>
                                            {{ $row->nama_status }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                @if ($form != 'gdn' && $form != 'ukt' && $form != 'atk')
                                <div class="form-group col-4">
                                    <select name="jenis_form" class="form-control form-control-sm small">
                                        <option value="">Seluruh Jenis Pengajuan</option>
                                        @foreach ($jenisForm as $row)
                                        <option value="{{ $row->id_jenis_form }}" <?php echo $formPick ? ($formPick->id_jenis_form == $row->id_jenis_form ? 'selected' : '') : '' ?>>
                                            {{ $row->nama_form }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-2 text-center">
                            <button class="btn btn-primary btn-sm font-weight-bold">
                                <i class="fas fa-search"></i> Cari
                            </button>
                            <a href="{{ route('usulan.show', $form) }}" class="btn btn-danger btn-sm font-weight-bold">
                                <i class="fas fa-undo"></i> Muat
                            </a>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-body table-responsive">
                <table id="table-show" class="table table-bordered text-center" style="font-size: 15px;">
                    <thead>
                        <tr>
                            <th style="width: 0%;">No</th>
                            <th style="width: 28%;">Pengusul</th>
                            <th>Usulan</th>
                            <th style="width: 15%;">Status</th>
                            <th style="width: 0%;">Aksi</th>
                            <!-- UNTUK EXPORT -->
                            <td>ID USULAN</td>
                            <td>PERIHAL</td>
                            <td>TANGGAL</td>
                            <td>NO. SURAT</td>
                            <td>STATUS</td>
                            <td>UNIT KERJA</td>
                        </tr>
                    </thead>
                    @php $no = 1; @endphp
                    <tbody class="text-capitalize" style="font-size: 13px;">
                        @foreach ($usulan as $i => $row)
                        <tr>
                            <td class="text-center">
                                @if($row->status_pengajuan_id == null)
                                <i class="fas fa-clock text-warning"></i>
                                @elseif($row->status_pengajuan_id == 11)
                                <i class="fas fa-check-circle text-green"></i>
                                @elseif($row->status_pengajuan_id == 12)
                                <i class="fas fa-times-circle text-red"></i>
                                @endif
                                {{ $i+1 }}
                            </td>
                            <td class="text-left align-top">
                                <div class="row">
                                    <div class="col-md-3">ID</div>:
                                    <div class="col-md-8">
                                        {{ $row->id_usulan }}
                                    </div>
                                    <div class="col-md-3">Tanggal</div>:
                                    <div class="col-md-8">
                                        {{ \Carbon\Carbon::parse($row->created_at)->isoFormat('DD MMM Y') }} |
                                        {{ \Carbon\Carbon::parse($row->created_at)->isoFormat('HH:mm') }}
                                    </div>
                                    @if ($row->form_id != 401 && $row->form_id != 501)
                                    <div class="col-md-3">Perihal</div>:
                                    <div class="col-md-8">
                                        {{ $row->form->nama_form }}
                                    </div>
                                    @endif
                                    <div class="col-md-3">No. Surat</div>:
                                    <div class="col-md-8">
                                        {{ $row->nomor_usulan }}
                                    </div>
                                    <div class="col-md-3">Nama</div>:
                                    <div class="col-md-8">
                                        {{ $row->pegawai->nama_pegawai }}
                                    </div>
                                    <div class="col-md-3">Unit Kerja</div>:
                                    <div class="col-md-8 text-capitalize">
                                        {{ $row->pegawai->unitKerja->nama_unit_kerja }}
                                    </div>
                                </div>
                            </td>
                            <td class="text-left align-top">
                                @if ($row->form_id == 301)
                                <div class="form-group row">
                                    <div class="col-md-2">Keterangan</div>:
                                    <div class="col-md-9 text-capitalize">
                                        {{ $row->keterangan }}
                                    </div>

                                    <div class="col-md-2">Barang</div>:
                                    <div class="col-md-9 text-capitalize hide-text">
                                        @foreach($row->usulanAtk as $subRow)
                                        {{ $subRow->atk->deskripsi }} ({{ $subRow->jumlah_permintaan }} {{ optional($subRow->atk->satuan->where('jenis_satuan', 'distribusi')->first())->satuan }})
                                        @if (!$loop->last) , @endif
                                        @endforeach
                                    </div>
                                </div>
                                @endif

                                @foreach($row->usulanUkt->take(1) as $subRow)
                                <div class="form-group row">
                                    <div class="col-md-2">Pekerjaan</div>:
                                    <div class="col-md-9 text-capitalize">
                                        {{ $subRow->judul_pekerjaan }}
                                    </div>
                                    <div class="col-md-2">Spesifikasi</div>:
                                    <div class="col-md-9 text-capitalize hide-text">
                                        {!! nl2br(e($subRow->deskripsi)) !!}
                                    </div>
                                    <div class="col-md-12"></div>
                                    <div class="col-md-2">Keterangan</div>:
                                    <div class="col-md-9 text-capitalize">
                                        {!! nl2br(e($subRow->keterangan)) !!}
                                    </div>
                                </div>
                                @endforeach

                                @foreach($row->usulanGdn->take(1) as $subRow)
                                <div class="form-group row">
                                    <div class="col-md-2">Pekerjaan</div>:
                                    <div class="col-md-9 text-capitalize">
                                        {{ ucfirst(strtolower($subRow->bperbaikan->bidang_perbaikan)) }}
                                    </div>
                                    <div class="col-md-2">Lokasi</div>:
                                    <div class="col-md-9 text-capitalize">
                                        {{ $subRow->judul_pekerjaan }}
                                    </div>
                                    <div class="col-md-2">Deskripsi</div>:
                                    <div class="col-md-9 text-capitalize hide-text">
                                        {!! nl2br(e($subRow->deskripsi)) !!}
                                    </div>
                                    <div class="col-md-12"></div>
                                    <div class="col-md-2">Keterangan</div>:
                                    <div class="col-md-9 text-capitalize hide-text">
                                        {!! nl2br(e($subRow->keterangan)) !!}
                                    </div>
                                </div>
                                @endforeach

                                <!-- PENGADAAN OLDAT -->
                                @if($row->form_id == 201)
                                <div class="form-group row">
                                    <div class="col-md-2">Barang</div>:
                                    <div class="col-md-9 text-capitalize">
                                        @foreach ($row->usulanOldat as $subRow)
                                        {{ ucfirst(strtolower($subRow->kategori?->kategori_barang.' '.$subRow->merk_tipe)) }}
                                        ({{ $subRow->jumlah_pengadaan.' Unit' }})
                                        @if (!$loop->last) , @endif
                                        @endforeach
                                    </div>
                                    <div class="col-md-2">Keterangan</div>:
                                    <div class="col-md-9">{{ $row->keterangan }}</div>
                                </div>
                                @endif

                                <!-- SERVIS OLDAT -->
                                @if($row->form_id == 202)
                                <div class="form-group row">
                                    <div class="col-md-2">Barang</div>:
                                    <div class="col-md-9 text-capitalize">
                                        @foreach ($row->usulanOldat as $no => $subRow)
                                        {{ $no + 1 }}. {{ ucfirst(strtolower($subRow->barang?->kategori?->kategori_barang.' '.$subRow->merk_tipe)) }}
                                        @if (!$loop->last) , @endif
                                        @endforeach
                                    </div>
                                    <div class="col-md-2">Keterangan</div>:
                                    <div class="col-md-9">
                                        @foreach ($row->usulanOldat as $no => $subRow)
                                        {{ $no + 1 }}. {{ $subRow->keterangan_kerusakan }}
                                        @if (!$loop->last) , @endif
                                        @endforeach
                                    </div>
                                </div>
                                @endif

                                <!-- PENGADAAN AADB -->
                                @if ($row->form_id == 101)
                                <div class="form-group row">
                                    <div class="col-md-2">Barang</div>:
                                    <div class="col-md-9 text-capitalize">
                                        @foreach ($row->usulanAadb as $subRow)
                                        {{ ucwords(strtolower($subRow->jenis_aadb.' '.$subRow->aadb?->kategori_aadb)) }} {{ $subRow->merk_tipe.' '.$subRow->tahun }}
                                        ({{ $subRow->jumlah_pengadaan.' Unit' }})
                                        @if (!$loop->last) , @endif
                                        @endforeach
                                    </div>
                                    <div class="col-md-2">Keterangan</div>:
                                    <div class="col-md-9">{{ $row->keterangan }}</div>
                                </div>
                                @endif

                                <!-- SERVIS AADB -->
                                @if ($row->form_id == 102)
                                <div class="form-group row">
                                    <div class="col-md-2">Total</div>:
                                    <div class="col-md-9 text-capitalize">
                                        {{ $row->usulanStnk->count() }} Kendaraan
                                    </div>
                                    <div class="col-md-2">Kendaraan</div>:
                                    <div class="col-md-9 hide-text">
                                        @foreach ($row->usulanStnk as $no => $subRow)
                                        {{ $no + 1 }}. ({{ $subRow->aadb->no_plat }}) {{ $subRow->aadb->merk_tipe }} :
                                        {{ $subRow->keterangan }}
                                        @if ($row->kilometer)
                                        , Kilometer saat ini {{ number_format($subRow->kilometer, 0, ',', '.') }} km
                                        @endif
                                        @if ($subRow->tanggal_servis)
                                        , Terakhir servis {{ \Carbon\carbon::parse($subRow->tanggal_servis)->isoFormat('MMMM Y') }}
                                        @endif
                                        @if ($subRow->tanggal_ganti_oli)
                                        , Terakhir ganti oli {{ \Carbon\carbon::parse($subRow->tanggal_ganti_oli)->isoFormat('MMMM Y') }}
                                        @endif.
                                        <div style="margin-top: 3px;"></div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif

                                <!-- PERPANJANGAN STNK -->
                                @if ($row->form_id == 103)
                                <div class="form-group row">
                                    <div class="col-md-2">Total</div>:
                                    <div class="col-md-9 text-capitalize">
                                        {{ $row->usulanStnk->count() }} Kendaraan
                                    </div>
                                    <div class="col-md-2">Kendaraan</div>:
                                    <div class="col-md-9 hide-text">
                                        @foreach ($row->usulanStnk as $no => $subRow)
                                        {{ $no + 1 }}. ({{ $subRow->aadb->no_plat }}) {{ $subRow->aadb->merk_tipe }}
                                        Masa Berlaku Stnk {{ \Carbon\carbon::parse($subRow->tanggal_stnk)->isoFormat('DD MMMM Y') }}<br>
                                        @endforeach
                                    </div>
                                </div>
                                @endif

                                <!-- PERMINTAAN VOUCHER BBM -->
                                @if($row->form_id == 104)
                                <div class="form-group row">
                                    <div class="col-md-2">Bulan</div>:
                                    <div class="col-md-9 text-capitalize">
                                        @foreach ($row->usulanBbm->take(1) as $subRow)
                                        Permintaan <b>{{ \Carbon\carbon::parse($subRow->bulan_pengadaan)->isoFormat('MMMM Y') }}</b>
                                        @endforeach
                                    </div>
                                    <div class="col-md-2">Total</div>:
                                    <div class="col-md-9 text-capitalize">
                                        {{ $row->usulanBbm->count() }} Kendaraan
                                    </div>
                                    <div class="col-md-2">Kendaraan</div>:
                                    <div class="col-md-9 hide-text">
                                        @foreach ($row->usulanBbm as $subRow)
                                        ({{ $subRow->aadb->no_plat }}) {{ $subRow->aadb->merk_tipe }}
                                        @if (!$loop->last) , @endif
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            </td>
                            <td class="text-center" style="font-size: 13px;">
                                <div class="mt-2">
                                    @if (!$row->otp_1)
                                    <span class="badge badge-sm badge-danger p-2">
                                        BELUM DIKONFIRMASI
                                    </span>
                                    @else
                                    @if ($row->status_proses_id == 100)
                                    <span class="text-danger">{{ $row->keterangan_tolak }}</span>
                                    @elseif ($row->status_proses_id == 106)
                                    <span class="badge badge-sm badge-success p-2 text-uppercase">
                                        {{ $row->statusProses?->nama_status }}
                                    </span>
                                    @else
                                    <span class="badge badge-sm badge-warning p-2 text-uppercase">
                                        {{ $row->statusProses?->nama_status }}
                                    </span>
                                    @endif
                                    @endif
                                </div>
                            </td>
                            <td class="text-center">
                                @if ($row->status_pengajuan_id != 2)
                                <a type="button" class="btn btn-primary" data-toggle="dropdown">
                                    <i class="fas fa-bars"></i>
                                </a>
                                @endif
                                <div class="dropdown-menu">
                                    @if (!$row->otp_1 && $row->user_id == Auth::user()->id )
                                    <a href="{{ route('usulan.verif.create', ['form' => $form, 'id' => $row->id_usulan]) }}" class="dropdown-item btn" type="button">
                                        <i class="fas fa-file-signature"></i> Verifikasi OTP
                                    </a>
                                    @endif

                                    @if ($row->status_proses_id == 101)
                                    @if ($form != 'atk')
                                    <a class="dropdown-item btn" type="button" data-toggle="modal" data-target="#detail-{{ $row->id_usulan }}">
                                        @if (Auth::user()->pegawai->jabatan_id == 11)
                                        <i class="fas fa-user-check"></i> Validasi @else <i class="fas fa-info-circle"></i> Detail
                                        @endif
                                    </a>
                                    @else
                                        @if (Auth::user()->pegawai->jabatan_id == 11)
                                        <a href="{{ route('atk.validation', ['form' => $form, 'id' => $row->id_usulan]) }}" class="dropdown-item btn" type="button">
                                            <i class="fas fa-user-check"></i> Validasi
                                        </a>
                                        @else
                                        <a class="dropdown-item btn" type="button" data-toggle="modal" data-target="#detail-{{ $row->id_usulan }}">
                                            <i class="fas fa-info-circle"></i> Detail
                                        </a>
                                        @endif
                                    @endif
                                    @endif

                                    @if ($row->status_proses_id == 102)
                                    <a class="dropdown-item btn" type="button" data-toggle="modal" data-target="#detail-{{ $row->id_usulan }}">
                                        @if (Auth::user()->pegawai->jabatan_id == 5) <i class="fas fa-user-check"></i> Verifikasi @else <i class="fas fa-info-circle"></i> Detail @endif
                                    </a>
                                    @endif

                                    @if ($row->status_proses_id == 103 && Auth::user()->pegawai->jabatan_id == 11)
                                    <a href="{{ route('anggaran.realisasi.create', ['form' => $form, 'id' => $row->id_usulan]) }}" class="dropdown-item btn" type="button">
                                        <i class="fas fa-hand-holding-usd"></i> Proses
                                    </a>
                                    @endif

                                    @if ($row->status_proses_id == 105 && $row->user_id == Auth::user()->id)
                                    <a href="{{ route('usulan.bast.pengusul', ['form' => $form, 'id' => $row->id_usulan]) }}" class="dropdown-item btn" type="button" onclick="return confirm105(event)">
                                        <i class="fas fa-check-circle"></i> Diterima
                                    </a>
                                    @endif

                                    @if ($row->status_proses_id == 106 && Auth::user()->pegawai->jabatan_id == 5)
                                    <a href="{{ route('usulan.bast.kabag', ['form' => $form, 'id' => $row->id_usulan]) }}" class="dropdown-item btn" type="button" onclick="return confirm106(event)">
                                        <i class="fas fa-check-circle"></i> Konfirmasi
                                    </a>
                                    @endif

                                    @if ($row->status_proses_id < 103 && Auth::user()->role_id == 4 && $form != 'atk' || Auth::user()->role_id == 1)
                                    <a href="{{ route('usulan.edit', ['form' => $form, 'id' => $row->id_usulan]) }}" class="dropdown-item btn" type="button">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    @elseif ($row->status_proses_id < 102 && Auth::user()->role_id == 4 && $form == 'atk' || Auth::user()->role_id == 1)
                                    <a href="{{ route('usulan.edit', ['form' => $form, 'id' => $row->id_usulan]) }}" class="dropdown-item btn" type="button">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    @elseif (Auth::user()->role_id == 2 && $form == 'atk' && $row->status_proses_id < 103)
                                    <a href="{{ route('atk.validation.edit', ['form' => $form, 'id' => $row->id_usulan]) }}" class="dropdown-item btn" type="button">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    @endif

                                    @if (Auth::user()->id == $row->user_id && $row->status_proses_id <= 102)
                                    <a href="{{ route('usulan.delete', ['form' => $form, 'id' => $row->id_usulan]) }}" class="dropdown-item btn" type="button" onclick="confirmCancel(event)">
                                        <i class="fas fa-ban"></i> Batalkan
                                    </a>
                                    @endif

                                    @if ($row->status_proses_id >= 103)
                                    <a class="dropdown-item btn" type="button" data-toggle="modal" data-target="#detail-{{ $row->id_usulan }}">
                                        <i class="fas fa-info-circle"></i> Detail
                                    </a>
                                    @endif

                                    @if(Auth::user()->role_id == 1)
                                    <a href="{{ route('usulan.delete', ['form' => $form, 'id' => $row->id_usulan]) }}" class="dropdown-item btn" type="button" onclick="confirmCancel(event)">
                                        <i class="fas fa-trash"></i> Hapus
                                    </a>
                                    @endif

                                    @if($row->bast->count() > 0 && $form == 'atk')
                                    <a class="dropdown-item btn" type="button" data-toggle="modal" data-target="#bast-{{ $row->id_usulan }}">
                                        <i class="fas fa-file-circle-check"></i> Berita Acara
                                    </a>
                                    @endif
                                </div>
                            </td>
                            <td>{{ $row->id_usulan }}</td>
                            <td>{{ $row->form->nama_form }}</td>
                            <td>{{ $row->tanggal_usulan }}</td>
                            <td>{{ $row->nomor_usulan }}</td>
                            <td>{{ $row->statusProses?->nama_status }}</td>
                            <td>{{ $row->pegawai->unitKerja->nama_unit_kerja }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<!-- Modal Usulan -->
@foreach ($usulan as $row)
<div class="modal fade" id="detail-{{ $row->id_usulan }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-center">Detail Usulan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Informasi Pengusul -->
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="text-muted col-md-12">Informasi Pengusul</label>
                            <div class="col-md-3">ID</div>:
                            <div class="col-md-8">{{ $row->id_usulan }}</div>
                            <div class="col-md-3">Tanggal Usulan</div>:
                            <div class="col-md-8">{{ \Carbon\carbon::parse($row->tanggal_usulan)->isoFormat('DD MMMM Y') }}</div>
                            <div class="col-md-3">Nomor Surat</div>:
                            <div class="col-md-8">{{ $row->nomor_usulan }}</div>
                            @if ($row->form_id != 401 && $row->form_id != 501)
                            <div class="col-md-3">Perihal</div>:
                            <div class="col-md-8">
                                {{ $row->form->nama_form }}
                            </div>
                            @endif
                            <div class="col-md-3">Pengusul</div>:
                            <div class="col-md-8">{{ $row->pegawai->nama_pegawai }}</div>
                            <div class="col-md-3">Unit Kerja</div>:
                            <div class="col-md-8">{{ $row->pegawai->unitKerja->nama_unit_kerja }}</div>
                            <div class="col-md-3">Keterangan</div>:
                            <div class="col-md-8">
                                {{ $row->keterangan ? $row->keterangan : '-' }}
                            </div>
                            @if (Auth::user()->pegawai->jabatan_id == 11 && $row->status_proses_id == 101 && $form == 'atk')
                            <div class="col-md-3">Aksi</div>:
                            <div class="col-md-8">
                                <a href="{{ route('usulan.edit', ['form' => $form, 'id' => $row->id_usulan]) }}" class="btn btn-warning btn-xs">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                    <!-- Informasi Bast -->
                    @if ($row->bast->count() != 0 && $form != 'atk')
                    <div class="col-md-6">
                        @foreach ($row->bast as $i => $subRow)
                        <div class="form-group row">
                            <label class="col-md-12 text-muted mb-3">Informasi Berita Acara</label>
                            <div class="col-md-9">
                                <div class="form-group row">
                                    <div class="col-md-4">ID</div>:
                                    <div class="col-md-7">{{ $subRow->id_bast.''.\Carbon\carbon::parse($subRow->created_at)->isoFormat('HHmmDDMMYY') }}</div>
                                    <div class="col-md-4">Tanggal Bast</div>:
                                    <div class="col-md-7">
                                        {{ \Carbon\carbon::parse($subRow->tanggal_bast)->isoFormat('DD MMMM Y') }}
                                    </div>
                                    <div class="col-md-4">Nomor Bast</div>:
                                    <div class="col-md-7">{{ $subRow->nomor_bast }}</div>
                                    <div class="col-md-4">Penerima</div>:
                                    <div class="col-md-7">{{ $row->pegawai->nama_pegawai }}</div>
                                    <div class="col-md-4">Unit Kerja</div>:
                                    <div class="col-md-7">{{ $row->pegawai->unitKerja->nama_unit_kerja }}</div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                    <!-- PID 1 : Pengecekan Usulan -->
                    @if (Auth::user()->pegawai->jabatan_id == 11 && $row->status_proses_id == 101)
                    <div class="col-md-6 text-right">
                        <label class="text-muted">Konfirmasi Usulan</label><br>
                        <form id="form-penolakan-{{ $row->id_usulan }}" action="{{ route('usulan.confirm.reject', ['form' => $form, 'id' => $row->id_usulan]) }}" method="POST">
                            @csrf
                            <input type="hidden" name="reject" value="true">
                            <a href="{{ route('usulan.confirm.pj', ['form' => $form, 'id' => $row->id_usulan]) }}" class="btn btn-success btn-xs text-uppercase p-2 rounded" onclick="return confirm101(event)" id="btn-setuju">
                                <i class="fas fa-check"></i> Setuju
                            </a>
                            <a type="submit" class="btn btn-danger btn-xs text-uppercase p-2 rounded btn-tolak" data-target="{{ $row->id_usulan }}">
                                <i class="fas fa-times"></i> Tolak
                            </a>
                            <div id="form-keterangan{{ $row->id_usulan }}" class="pt-4" style="display: none;">
                                <textarea name="keterangan_tolak" class="form-control" rows="5" id="keterangan" placeholder="Keterangan tolak"></textarea>
                                <button type="submit" class="btn btn-danger btn-xs text-uppercase mt-2 p-2 rounded" onclick="confirmTolak(event, 'form-penolakan-{{ $row->id_usulan }}')">
                                    Submit
                                </button>
                            </div>
                        </form>
                    </div>
                    @endif
                    <!-- PID 2 : Persetujuan -->
                    @if (Auth::user()->pegawai->jabatan_id == 5 && $row->status_proses_id == 102)
                    <div class="col-md-6 text-right">
                        <label class="text-muted">Konfirmasi Usulan</label><br>
                        <a href="{{ route('usulan.confirm', ['form' => $form, 'id' => $row->id_usulan]) }}" class="btn btn-success btn-xs text-uppercase p-2 rounded" onclick="confirm102(event)">
                            <i class="fas fa-check"></i> Konfirmasi
                        </a>
                    </div>
                    @endif
                    <div class="col-md-12">
                        <label class="text-muted">Informasi Pekerjaan</label>
                        <table class="table table-bordered text-center">
                            @php
                            $row1 = ($row->form->kategori == 'AADB' && $row->form_id != 101) ? 'No. Plat' : (($form == 'atk') ? 'Nama Barang' : 'Pekerjaan');
                            $row2 = $row->form->kategori == 'AADB' && $row->form_id != 101 ? 'Kendaraan' : 'Spesifikasi';
                            $row3 = $form == 'atk' ? 'Permintaan' : 'Keterangan'
                            @endphp
                            <thead style="font-size: 15px;">
                                <tr>
                                    <th class="align-middle">No</th>
                                    <th class="align-middle">{{ $row1 }}</th>
                                    <th class="align-middle">{{ $row2 }}</th>
                                    <th class="align-middle">{{ $row3 }}</th>
                                    @if ($form == 'atk')
                                    <th class="align-middle">Disetujui</th>
                                    @if ($row->bast->count() != 0)
                                    <th>
                                        Berita Acara
                                        <div class="row border-top border-dark mt-1">
                                            <div class="col-md-6 mt-2">Nomor Surat</div>
                                            <div class="col-md-3 mt-2">Tanggal</div>
                                            <div class="col-md-3 mt-2">Jumlah</div>
                                        </div>
                                    </th>
                                    @endif
                                    @endif
                                </tr>
                            </thead>
                            @php $no = 1; @endphp
                            <tbody style="font-size: 13px;">
                                @foreach ($row->usulanUkt as $subRow)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td class="text-left">{{ $subRow->judul_pekerjaan }}</td>
                                    <td class="text-left">{!! nl2br(e($subRow->deskripsi)) !!}</td>
                                    <td class="text-left">{!! nl2br(e($subRow->keterangan)) !!}</td>
                                </tr>
                                @endforeach

                                @foreach ($row->usulanGdn as $subRow)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td class="text-left text-capitalize">
                                        {{ ucfirst(strtolower($subRow->bperbaikan->bidang_perbaikan)) }} <br>
                                        {{ ucfirst(strtolower($subRow->judul_pekerjaan)) }}
                                    </td>
                                    <td class="text-left">{!! nl2br(e($subRow->deskripsi)) !!}</td>
                                    <td class="text-left">{!! nl2br(e($subRow->keterangan)) !!}</td>
                                </tr>
                                @endforeach

                                @foreach ($row->usulanOldat as $subRow)
                                @if ($row->form_id == 201)
                                <tr class="text-capitalized">
                                    <td>{{ $no++ }}</td>
                                    <td class="text-left">
                                        Permintaan Pengadaan {{ ucfirst(strtolower($subRow->kategori?->kategori_barang)) }}
                                        sebanyak {{ $subRow->jumlah_pengadaan }} unit dengan estimasi harga
                                        Rp {{ number_format($subRow->estimasi_harga, 0, ',', '.') }}
                                    </td>
                                    <td class="text-left">{!! nl2br(e($subRow->spesifikasi)) !!}</td>
                                    <td class="text-left">{!! nl2br(e($row->keterangan)) !!}</td>
                                </tr>
                                @elseif ($row->form_id == 202)
                                <tr class="text-capitalized">
                                    <td>{{ $no++ }}</td>
                                    <td class="text-left">
                                        Perbaikan {{ ucfirst(strtolower($subRow->barang?->kategori->kategori_barang)) }}
                                        ({{ $subRow->barang?->kategori_id.'.'.$subRow->barang?->nup }})
                                    </td>
                                    <td class="text-left">
                                        {{ ucfirst(strtolower($subRow->barang?->merk_tipe.' '.$subRow->barang?->spesifikasi)) }}
                                    </td>
                                    <td class="text-left">{!! nl2br(e($subRow->keterangan_kerusakan)) !!}</td>
                                </tr>
                                @endif
                                @endforeach

                                @foreach ($row->usulanAadb as $subRow)
                                <tr class="text-capitalized">
                                    <td class="align-top" style="width: 0%;">{{ $no++ }}</td>
                                    <td class="text-left align-top" style="width: 35%;">
                                        Permintaan Pengadaan {{ ucwords(strtolower($subRow->jenis_aadb.' '.$subRow->aadb?->kategori_aadb)) }}
                                        sebanyak {{ $subRow->jumlah_pengadaan.' unit' }}
                                    </td>
                                    <td class="align-top" style="width: 20%;">{{ $subRow->merk_tipe.' '.$subRow->tahun }}</td>
                                    <td class="text-left align-top" style="width: 30%;">{!! nl2br(e($row->keterangan)) !!}</td>
                                </tr>
                                @endforeach

                                @foreach ($row->usulanStnk as $subRow)
                                @if ($row->form_id == 102)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td style="width: 20%;">{{ $subRow->aadb->no_plat }}</td>
                                    <td style="width: 30%;">{{ $subRow->aadb->merk_tipe.' '.$subRow->aadb->tahun }}</td>
                                    <td class="text-left">
                                        {{ $subRow->keterangan }}
                                        @if ($subRow->kilometer)
                                        , Kilometer saat ini {{ number_format($subRow->kilometer, 0, ',', '.') }} km
                                        @endif
                                        @if ($subRow->tanggal_servis)
                                        , Terakhir servis {{ \Carbon\carbon::parse($subRow->tanggal_servis)->isoFormat('MMMM Y') }}
                                        @endif
                                        @if ($subRow->tanggal_ganti_oli)
                                        , Terakhir ganti oli {{ \Carbon\carbon::parse($subRow->tanggal_ganti_oli)->isoFormat('MMMM Y') }}
                                        @endif.
                                    </td>
                                </tr>
                                @elseif ($row->form_id == 103)
                                @if ($subRow->keterangan == 'true')
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td style="width: 25%;">{{ $subRow->aadb->no_plat }}</td>
                                    <td style="width: 35%;">{{ $subRow->aadb->merk_tipe.' '.$subRow->aadb->tahun }}</td>
                                    <td class="text-left">
                                        Masa Berlaku STNK :
                                        {{ \Carbon\carbon::parse($subRow->tanggal_stnk)->isoFormat('DD MMMM Y') }}
                                    </td>
                                </tr>
                                @endif
                                @endif
                                @endforeach

                                @foreach ($row->usulanBbm->where('status_pengajuan', 'true') as $subRow)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $subRow->aadb->no_plat }}</td>
                                    <td>{{ $subRow->aadb->merk_tipe.' '.$subRow->aadb->tahun }}</td>
                                    <td>{{ \Carbon\carbon::parse($subRow->bulan_pengadaan)->isoFormat('MMMM Y') }}</td>
                                </tr>
                                @endforeach

                                @foreach ($row->usulanAtk as $subRow)
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

                                    @if ($row->bast->count() != 0)
                                    <td>
                                        @php $no = 1; @endphp
                                        @foreach($row->bast as $rowBast)
                                        @foreach($rowBast->detail->where('usulan_detail_id', $subRow->id_permintaan) as $rowDetail)
                                        <div class="row border-bottom border-secondary mb-2">
                                            <div class="col-md-6">{{ $no++ .'. '.$rowDetail->bast->nomor_bast }}</div>
                                            <div class="col-md-3">&ensp;{{ $rowDetail->bast->tanggal_bast }}</div>
                                            <div class="col-md-3">{{ $rowDetail->deskripsi.' '. optional($subRow->atk->satuan->where('jenis_satuan', 'distribusi')->first())->satuan }}</div>
                                        </div>
                                        @endforeach
                                        @endforeach
                                    </td>
                                    @endif
                                </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="{{ route('usulan.detail', ['form' => $form, 'id' => $row->id_usulan]) }}" class="btn btn-default btn-sm border-secondary">
                    Selengkapnya <i class="fas fa-arrow-alt-circle-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- Modal Berita Acara -->
@foreach ($usulan as $row)
<div class="modal fade" id="bast-{{ $row->id_usulan }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-center">Berita Acara</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Informasi Pengusul -->
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="text-muted col-md-12">Informasi Pengusul</label>
                            <div class="col-md-3">ID</div>:
                            <div class="col-md-8">{{ $row->id_usulan }}</div>
                            <div class="col-md-3">Tanggal Usulan</div>:
                            <div class="col-md-8">{{ \Carbon\carbon::parse($row->tanggal_usulan)->isoFormat('DD MMMM Y') }}</div>
                            <div class="col-md-3">Nomor Surat</div>:
                            <div class="col-md-8">{{ $row->nomor_usulan }}</div>
                            @if ($row->form_id != 401 && $row->form_id != 501)
                            <div class="col-md-3">Perihal</div>:
                            <div class="col-md-8">
                                {{ $row->form->nama_form }}
                            </div>
                            @endif
                            <div class="col-md-3">Pengusul</div>:
                            <div class="col-md-8">{{ $row->pegawai->nama_pegawai }}</div>
                            <div class="col-md-3">Unit Kerja</div>:
                            <div class="col-md-8">{{ $row->pegawai->unitKerja->nama_unit_kerja }}</div>
                            <div class="col-md-3">Keterangan</div>:
                            <div class="col-md-8">
                                {{ $row->keterangan ? $row->keterangan : '-' }}
                            </div>
                            @if (Auth::user()->pegawai->jabatan_id == 11 && $row->status_proses_id == 101 && $form == 'atk')
                            <div class="col-md-3">Aksi</div>:
                            <div class="col-md-8">
                                <a href="{{ route('usulan.edit', ['form' => $form, 'id' => $row->id_usulan]) }}" class="btn btn-warning btn-xs">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-12">
                        <label class="text-muted">Informasi Berita Acara</label>
                        <table class="table table-bordered text-center">
                            <thead style="font-size: 15px;">
                                <tr>
                                    <th style="width: 0%;">No</th>
                                    <th>Tanggal Bast</th>
                                    <th>Nomor Surat</th>
                                    <th>Total Barang</th>
                                    <th>Total Realisasi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            @php $no = 1; @endphp
                            <tbody style="font-size: 13px;">
                                @foreach ($row->bast->sortByDesc('tanggal_bast') as $i => $subRow)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $subRow->tanggal_bast }}</td>
                                    <td>{{ $subRow->nomor_bast }}</td>
                                    <td>{{ $subRow->detail->count() }}</td>
                                    <td>Rp {{ number_format($subRow->detail->sum('nilai_realisasi'), 0, ',', '.') }}</td>
                                    <td>
                                        <a href="{{ route('bast.detail', ['form' => $form, 'id' => $subRow->id_bast]) }}" class="btn btn-primary btn-sm" target="_blank">
                                            <i class="fas fa-arrow-up-right-from-square"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="{{ route('usulan.detail', ['form' => $form, 'id' => $row->id_usulan]) }}" class="btn btn-default btn-sm border-secondary">
                    Selengkapnya <i class="fas fa-arrow-alt-circle-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- Modal Berita Acara -->

@section('js')
<script>
    function showDetail(id) {
        var modal_target = "#detail-" + id;
        $(modal_target).modal('show');
    }

    $(function() {
        var currentdate = new Date();
        var datetime = "Tanggal: " + currentdate.getDate() + "/" +
            (currentdate.getMonth() + 1) + "/" +
            currentdate.getFullYear() + " \n Pukul: " +
            currentdate.getHours() + ":" +
            currentdate.getMinutes() + ":" +
            currentdate.getSeconds()

        $("#table-show").DataTable({
            "responsive": false,
            "lengthChange": true,
            "autoWidth": true,
            "info": true,
            "paging": true,
            "searching": true,
            columnDefs: [{
                "bVisible": false,
                "aTargets": [5, 6, 7, 8, 9, 10]
            }],
            buttons: [{
                    extend: 'pdf',
                    text: '<i class="fas fa-file-pdf"></i> PDF',
                    className: 'bg-danger',
                    action: function(e, dt, button, config) {
                        // Mendapatkan URL routing PDF
                        var pdfUrl = `{{ route('usulan.export.pdf', $form) }}`;

                        // Membuat elemen <a> sementara
                        var tempLink = document.createElement('a');
                        tempLink.href = pdfUrl;
                        tempLink.target = '_blank'; // Menentukan atribut target
                        tempLink.style.display = 'none'; // Menyembunyikan elemen

                        // Menambahkan elemen ke dalam dokumen
                        document.body.appendChild(tempLink);

                        // Mengklik elemen untuk membuka tautan dalam tab atau jendela baru
                        tempLink.click();

                        // Menghapus elemen setelah pengguna mengklik tautan
                        document.body.removeChild(tempLink);
                    },
                },
                {
                    extend: 'excel',
                    text: ' <i class="fas fa-file-excel"></i> Excel',
                    className: 'bg-success',
                    title: 'Show',
                    exportOptions: {
                        columns: [0, 5, 6, 7, 8, 9, 10]
                    },
                    messageTop: datetime
                }
            ],
            "bDestroy": true
        }).buttons().container().appendTo('#table-show_wrapper .col-md-6:eq(0)')
    })

    function confirmCancel(event) {
        event.preventDefault(); // Prevent the default link behavior
        Swal.fire({
            title: 'Batalkan Usulan?',
            text: 'Usulan yang dibatalkan akan hilang.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, konfirmasi!',
            cancelButtonText: 'Tidak, batalkan!',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = event.target.href;
            }
        });
    }

    function confirmTolak(event, formId) {
        event.preventDefault(); // Prevent the default link behavior
        Swal.fire({
            title: 'Apakah usulan ditolak?',
            text: 'Konfirmasi persetujuan usulan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, konfirmasi!',
            cancelButtonText: 'Tidak, batalkan!',
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById(formId);
                form.submit();
            }
        });
    }

    function confirm101(event) {
        event.preventDefault(); // Prevent the default link behavior
        Swal.fire({
            title: 'Apakah disetujui?',
            text: 'Konfirmasi persetujuan usulan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, konfirmasi!',
            cancelButtonText: 'Tidak, batalkan!',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = event.target.href; // Proceed with the link navigation
            }
        });
    }

    function confirm102(event) {
        event.preventDefault(); // Prevent the default link behavior
        Swal.fire({
            title: 'Konfirmasi persetujuan?',
            text: 'Konfirmasi persetujuan usulan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, konfirmasi!',
            cancelButtonText: 'Tidak, batalkan!',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = event.target.href; // Proceed with the link navigation
            }
        });
    }

    function confirm105(event) {
        event.preventDefault(); // Prevent the default link behavior
        Swal.fire({
            title: 'Apakah pekerjaan sudah diterima?',
            text: 'Konfirmasi berita acara serah terima.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, konfirmasi!',
            cancelButtonText: 'Tidak, batalkan!',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = event.target.href; // Proceed with the link navigation
            }
        });
    }

    function confirm106(event) {
        event.preventDefault(); // Prevent the default link behavior
        Swal.fire({
            title: 'Konfirmasi pekerjaan?',
            text: 'Konfirmasi berita acara serah terima.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, konfirmasi!',
            cancelButtonText: 'Tidak, batalkan!',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = event.target.href; // Proceed with the link navigation
            }
        });
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var elements = document.querySelectorAll('.hide-text');

        elements.forEach(function(element) {
            var maxHeight = parseFloat(window.getComputedStyle(element).getPropertyValue('max-height'));

            if (element.scrollHeight > maxHeight) {
                var showMoreLink = document.createElement('a');
                showMoreLink.className = 'show-more';
                showMoreLink.textContent = 'Show More';

                var hideText = function() {
                    element.style.maxHeight = maxHeight + 'px';
                    showMoreLink.style.display = 'block';
                    showMoreLink.textContent = 'Tampilkan lebih banyak';
                };

                var showMore = function() {
                    element.style.maxHeight = 'none';
                    showMoreLink.style.display = 'block';
                    showMoreLink.textContent = 'Tampilkan lebih sedikit';
                };

                showMoreLink.addEventListener('click', function() {
                    if (element.style.maxHeight === 'none') {
                        hideText();
                    } else {
                        showMore();
                    }
                });

                element.insertAdjacentElement('afterend', showMoreLink);
                hideText();
            }
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var btnSetuju = document.getElementById('btn-setuju');
        var btnTolakList = document.querySelectorAll('.btn-tolak');

        btnSetuju.addEventListener('click', function(event) {
            hideAllForms();
        });

        btnTolakList.forEach(function(btnTolak) {
            btnTolak.addEventListener('click', function(event) {
                event.preventDefault();
                var target = btnTolak.getAttribute('data-target');
                var formKeterangan = document.getElementById('form-keterangan' + target);
                hideAllForms();
                formKeterangan.style.display = 'block';
            });
        });

        function hideAllForms() {
            var allForms = document.querySelectorAll('[id^="form-keterangan"]');
            allForms.forEach(function(form) {
                form.style.display = 'none';
            });
        }
    });
</script>
@endsection

@endsection
