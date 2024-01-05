@extends('layout.app')

@section('content')

<!-- Content Header -->
<section class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="text-capitalize">SIPORSAT</h1>
                <h5>Sistem Pengelolaan Operasional Perkantoran Terpusat</h5>
                <ol class="breadcrumb text-capitalize">
                    <li class="breadcrumb-item"><a href="{{ route('oldat.home') }}">Beranda</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('oldat.barang.show') }}">Daftar Oldat & Meubelair</a></li>
                    <li class="breadcrumb-item active">Detail</li>
                </ol>
            </div>
            <div class="col-sm-6 mt-3">
                <div class="form-group text-right">
                    <a href="{{ route('oldat.barang.show') }}" class="btn btn-add">
                        <span class="btn btn-primary rounded-0 mr-1">
                            <i class="fas fa-arrow-left"></i>
                        </span>
                        <small>Kembali</small>
                    </a>
                </div>
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
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-9">
                        <div class="row">
                            <label class="col-md-12 mb-3">Informasi Barang</label>
                            <label class="col-md-3">ID</label>:
                            <div class="col-md-8">{{ $barang->id_barang }}</div>
                            <label class="col-md-3">Unit Kerja</label>:
                            <div class="col-md-8">{{ $barang->unitKerja->nama_unit_kerja }}</div>
                            <label class="col-md-3">Kode Barang</label>:
                            <div class="col-md-8">{{ $barang->kategori_id.'.'.$barang->nup }}</div>
                            <label class="col-md-3">Nama Barang</label>:
                            <div class="col-md-8">{{ ucfirst(strtoupper($barang->kategori->kategori_barang)) }}</div>
                            <label class="col-md-3">Deskripsi</label>:
                            <div class="col-md-8">{{ $barang->spesifikasi }}</div>
                            <label class="col-md-3">Tanggal Perolehan</label>:
                            <div class="col-md-8">{{ $barang->tahun_perolehan }}</div>
                            <label class="col-md-3">Nilai Perolehan</label>:
                            <div class="col-md-8">Rp {{ number_format($barang->nilai_perolehan, 0, ',', '.') }}</div>
                            <label class="col-md-3">Kondisi</label>:
                            <div class="col-md-8">
                                {{ $barang->kondisi_id == 3 ? 'Rusak Berat' : ($barang->kondisi_id == 2 ? 'Rusak Ringan' : 'Baik') }}
                            </div>
                            <label class="col-md-3">Status</label>:
                            <div class="col-md-8">
                                {{ $barang->kondisi_id == 1 ? 'Aktif' : ($barang->kondisi_id == 2 ? 'Perbaikan' : '') }}
                                {{ $barang->kondisi_id == 3 ? 'Proses Penghapusan' : ($barang->kondisi_id == 2 ? 'Sudah Dihapuskan' : '') }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        @if(Auth::user()->pegawai->unit_kerja_id == $barang->unit_kerja_id || Auth::user()->role_id == 1)
                        <div class="mb-2">
                            <a href="{{ route('oldat.barang.edit', $barang->id_barang) }}" class="btn btn-add btn-warning">
                                <span class="btn rounded-0 mr-1" style="background-color: #e0a800;">
                                    <i class="fas fa-edit"></i>
                                </span>
                                <small>Edit Barang</small>
                            </a>
                        </div>
                        @endif
                        <div class="border border-secondary">
                            @if ($barang->foto_barang)
                            <img src="{{ asset('storage/files/foto_barang/'. $barang->foto_barang) }}" class="img-fluid mt-5" alt="">
                            @else
                            <img src="https://cdn-icons-png.flaticon.com/512/679/679821.png" class="img-fluid" alt="">
                            @endif
                        </div>
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <div class="col-md-6">
                        <label class="col-form-label">Informasi Pengguna</label>
                    </div>
                    @if(Auth::user()->pegawai->unit_kerja_id == $barang->unit_kerja_id || Auth::user()->role_id == 1)
                    <div class="col-md-6 text-right">
                        <a type="button" class="btn btn-add" data-toggle="modal" data-target="#add-pengguna">
                            <span class="btn btn-primary rounded-0 mr-1"><i class="fas fa-add"></i></span>
                            <small>Tambah Pengguna</small>
                        </a>
                    </div>
                    @endif
                </div>
                <table id="table-show" class="table table-bordered text-center" style="font-size: 14px;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Unit Kerja</th>
                            <th>Nama Pegawai</th>
                            <th>Jabatan</th>
                            <th>Keterangan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    @php $no = 1; @endphp
                    <tbody>
                        @foreach($barang->pengguna as $row)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $row->tanggal_pengguna }}</td>
                            <td>{{ $row->barang->unitKerja->nama_unit_kerja }}</td>
                            <td>{{ $row->pegawai->nama_pegawai }}</td>
                            <td>{{ $row->pegawai->nama_jabatan }}</td>
                            <td>{{ $row->keterangan }}</td>
                            <td>
                                <span class="badge p-2 {{ $row->status_id == 1 ? 'badge-success' : 'badge-danger' }}">
                                    {{ $row->status->nama_status }}
                                </span>
                            </td>
                            @if(Auth::user()->role_id == 1 || $row->barang->unit_kerja_id == Auth::user()->pegawai->unit_kerja_id)
                            <td>
                                <form id="form-hapus" action="{{ route('oldat.pengguna.store', $row->id_pengguna) }}" method="post">
                                    @csrf
                                    <a type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#edit-{{ $row->id_pengguna }}">
                                        <i class="fas fa-pen-to-square"></i>
                                    </a>
                                    <input type="hidden" name="barang" value="{{ $barang->id_barang }}">
                                    <input type="hidden" name="aksi" value="hapus">
                                    <button href="" class="btn btn-danger btn-xs" onclick="confirmSubmit(event, 'hapus', 'Hapus Pengguna ?')">
                                        <i class="fas fa-trash-can"></i>
                                    </button>
                                </form>
                            </td>
                            @else
                            <td>Tidak ada aksi</td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<!-- Modal -->
<div class="modal fade" id="add-pengguna" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Pengguna Barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form-tambah" action="{{ route('oldat.pengguna.store', '*') }}" method="post">
                @csrf
                <input type="hidden" name="barang" value="{{ $barang->id_barang }}">
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="pegawai">Tanggal Pengguna*</label>
                            <input type="date" class="form-control" name="tanggal" required>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="pegawai">Nama Pegawai*</label>
                            <select name="pegawai" class="form-control pegawai" style="width: 100%;" required>
                                <option value="">-- Pilih Pegawai --</option>
                                @foreach ($pegawai->where('unit_kerja_id', $barang->unit_kerja_id) as $row)
                                <option value="{{ $row->id_pegawai }}">
                                    {{ $row->nama_pegawai }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="pegawai">Status Pemakaian*</label>
                            <select name="status" class="form-control" required>
                                <option value="">-- Pilih Status --</option>
                                @foreach ($status as $row)
                                <option value="{{ $row->id_status }}">
                                    {{ $row->nama_status }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="pegawai">Keterangan</label>
                            <textarea type="text" class="form-control" name="keterangan"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="confirmSubmit(event, 'tambah', 'Tambah Pengguna?')">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit -->
@foreach($barang->pengguna as $data)
<div class="modal fade" id="edit-{{ $data->id_pengguna }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Pengguna Barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form-edit" action="{{ route('oldat.pengguna.store', $data->id_pengguna) }}" method="post">
                @csrf
                <input type="hidden" name="barang" value="{{ $barang->id_barang }}">
                <input type="hidden" name="aksi" value="update">
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="pegawai">Tanggal Pengguna*</label>
                            <input type="date" class="form-control" name="tanggal" value="{{ Carbon\carbon::parse($data->tanggal_pengguna)->format('Y-m-d') }}" required>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="pegawai">Nama Pegawai*</label>
                            <select name="pegawai" class="form-control" required>
                                <option value="">-- Pilih Pegawai --</option>
                                @foreach ($pegawai->where('unit_kerja_id', $barang->unit_kerja_id) as $row)
                                <option value="{{ $row->id_pegawai }}" <?php echo $row->id_pegawai == $data->pegawai_id ? 'selected' : '' ?>>
                                    {{ $row->nama_pegawai }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="pegawai">Status Pemakaian*</label>
                            <select name="status" class="form-control" required>
                                <option value="">-- Pilih Status --</option>
                                @foreach ($status as $row)
                                <option value="{{ $row->id_status }}" <?php echo $row->id_status == $data->status_id ? 'selected' : '' ?>>
                                    {{ $row->nama_status }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="pegawai">Keterangan</label>
                            <textarea type="text" class="form-control" name="keterangan">{{ $data->keterangan }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="confirmSubmit(event, 'edit', 'Simpan Perubahan?')">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@section('js')
<script>
    $('.pegawai').select2()
    function showDetail(id) {
        var modal_target = "#detail-" + id;
        $(modal_target).modal('show');
    }

    function confirmSubmit(event, aksi, msg) {
        event.preventDefault(); // Prevent the default link behavior
        Swal.fire({
            title: msg,
            text: '',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, proses!',
            cancelButtonText: 'Batal!',
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('form-' + aksi);
                form.submit();
            }
        });
    }

    $(function() {
        let barang = '{{ ucwords(strtolower($barang->kategori->kategori_barang." ".$barang->merk_tipe)) }}'
        let kodeBarang = '{{ $barang->kategori_id.".".$barang->nup }}'
        $("#table-show").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "sort": false,
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "Semua"]
            ],
            buttons: [{
                    extend: 'pdf',
                    text: ' <i class="fas fa-file-pdf"></i> PDF',
                    className: 'bg-danger',
                    title: 'Pengguna Barang',
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6]
                    },
                    messageTop: barang + '\n' + kodeBarang
                },
                {
                    extend: 'excel',
                    text: ' <i class="fas fa-file-excel"></i> Excel',
                    className: 'bg-success',
                    title: 'Pengguna Barang',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6]
                    },
                    messageTop: barang + ' (' + kodeBarang + ')'
                }
            ],
            "bDestroy": true
        }).buttons().container().appendTo('#table-show_wrapper .col-md-6:eq(0)');
    })
</script>
@endsection

@endsection
