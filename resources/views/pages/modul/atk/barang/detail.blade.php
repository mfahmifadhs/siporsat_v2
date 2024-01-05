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
                    <li class="breadcrumb-item"><a href="{{ route('atk.home') }}">Beranda</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('atk.show') }}">Referensi ATK</a></li>
                    <li class="breadcrumb-item active">Detail</li>
                </ol>
            </div>
            <div class="col-sm-6 mt-3">
                <div class="form-group text-right">
                    <a href="{{ route('atk.show') }}" class="btn btn-add">
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
                    <div class="col-md-10">
                        <div class="row">
                            <label class="col-md-12 mb-3">Informasi ATK</label>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-md-4">Kode</label>:
                                    <div class="col-md-7">{{ $atk->kategori_id }}</div>
                                    <label class="col-md-4">Jenis ATK</label>:
                                    <div class="col-md-7">{{ strtoupper($atk->jenis_atk) }}</div>
                                    <label class="col-md-4">Nama ATK</label>:
                                    <div class="col-md-7">{{ $atk->kategori->kategori_atk }}</div>
                                    <label class="col-md-4">Deskripsi</label>:
                                    <div class="col-md-7">{{ $atk->deskripsi }}</div>
                                    <label class="col-md-4">Keterangan</label>:
                                    <div class="col-md-7">{{ $atk->keterangan_atk }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        @if(Auth::user()->pegawai->jabatan_id == 12)
                        <div class="text-right mb-2">
                            <a href="" class="btn btn-add btn-warning">
                                <span class="btn rounded-0 mr-1" style="background-color: #e0a800;">
                                    <i class="fas fa-edit"></i>
                                </span>
                                <small>Edit ATK</small>
                            </a>
                        </div>
                        @endif
                        <div class="border border-secondary">
                            @if ($atk->foto_atk)
                            <img src="{{ asset('storage/files/foto_atk/'. $atk->foto_atk) }}" class="img-fluid" alt="">
                            @else
                            <img src="https://cdn-icons-png.flaticon.com/512/679/679821.png" class="img-fluid" alt="">
                            @endif
                        </div>
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <div class="col-md-6">
                        <label class="col-form-label">Satuan ATK</label>
                    </div>
                    @if(Auth::user()->pegawai->jabatan_id == 12)
                    <div class="col-md-6 text-right">
                        <a type="button" class="btn btn-add" data-toggle="modal" data-target="#add-satuan">
                            <span class="btn btn-primary rounded-0 mr-1"><i class="fas fa-add"></i></span>
                            <small>Tambah Satuan</small>
                        </a>
                    </div>
                    @endif
                </div>
                <label class="text-muted">Satuan Distribusi</label>
                <table id="table-show" class="table table-bordered text-center" style="font-size: 14px;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Satuan</th>
                            <th>Harga Satuan</th>
                            <th>Keterangan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    @php $no = 1; @endphp
                    <tbody>
                        @foreach($atk->satuan->where('jenis_satuan','distribusi') as $row)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $row->created_at }}</td>
                            <td>{{ $row->satuan }}</td>
                            <td>Rp {{ number_format($row->harga, 0, ',', '.') }}</td>
                            <td>{{ $row->deskripsi }}</td>
                            <td>
                                <span class="badge p-2 {{ $row->status_id == 1 ? 'badge-success' : 'badge-danger' }}">
                                    {{ $row->status?->nama_status ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                            </td>
                            @if(Auth::user()->pegawai->jabatan_id == 12)
                            <td>
                                <a type="button" class="btn btn-primary btn-sm" data-toggle="dropdown">
                                    <i class="fas fa-bars"></i>
                                </a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item btn" type="button" data-toggle="modal" data-target="#edit-{{ $row->id_satuan }}">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a class="dropdown-item btn" href="{{ route('atk.satuan.destroy', ['atkId' => $row->atk_id, 'id' => $row->id_satuan]) }}"
                                    onclick="confirmHapus(event)">
                                        <i class="fas fa-trash"></i> Hapus
                                    </a>
                                </div>
                            </td>
                            @else
                            <td>Tidak ada aksi</td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <hr>
                <label class="text-muted">Satuan Pembelian</label>
                <table id="table-show2" class="table table-bordered text-center" style="font-size: 14px;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Satuan</th>
                            <th>Keterangan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    @php $no = 1; @endphp
                    <tbody>
                        @foreach($atk->satuan->where('jenis_satuan','pembelian') as $row)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $row->created_at }}</td>
                            <td>{{ $row->satuan }}</td>
                            <td>{{ $row->deskripsi }}</td>
                            <td>
                                <span class="badge p-2 {{ $row->status_id == 1 ? 'badge-success' : 'badge-danger' }}">
                                    {{ $row->status?->nama_status ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                            </td>
                            @if(Auth::user()->role_id == 2)
                            <td>
                                <a type="button" class="btn btn-primary btn-sm" data-toggle="dropdown">
                                    <i class="fas fa-bars"></i>
                                </a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item btn" type="button" data-toggle="modal" data-target="#edit-{{ $row->id_satuan }}">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a class="dropdown-item btn" href="{{ route('atk.satuan.destroy', ['atkId' => $row->atk_id, 'id' => $row->id_satuan]) }}"
                                    onclick="confirmHapus(event)">
                                        <i class="fas fa-trash"></i> Hapus
                                    </a>
                                </div>
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

<!-- Modal Tambah -->
<div class="modal fade" id="add-satuan" tabindex="-1" role="dialog" aria-labelledby="add-satuan" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Satuan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form-tambah" action="{{ route('atk.satuan.store', $atk->id_atk) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <label class="text-muted small">
                        Satuan ATK dengan status <b>Aktif</b> akan yang digunakan untuk pengajuan.
                    </label>
                    <div class="form-group">
                        <label>Jenis Satuan</label>
                        <select name="jenis" class="form-control">
                            <option value="distribusi">Distribusi</option>
                            <option value="pembelian">Pembelian</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Nama Satuan</label>
                        <input type="text" name="satuan" class="form-control" placeholder="Nama Satuan ATK" required>
                    </div>
                    <div class="form-group">
                        <label>Harga Satuan</label>
                        <input type="text" name="harga" class="form-control price" placeholder="Harga Satuan" required>
                        <small>untuk satuan pembelian, harga tidak perlu ditambahkan</small>
                    </div>
                    <div class="form-group">
                        <label>Keterangan</label>
                        <textarea name="deskripsi" class="form-control" placeholder="Keterangan satuan"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="1">Aktif</option>
                            <option value="">Tidak Aktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" onclick="confirmSubmit(event, 'tambah', 'Tambah Satuan ?')">
                        <i class="fas fa-paper-plane"></i> Submit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit -->
@foreach ($atk->satuan as $row)
<div class="modal fade" id="edit-{{ $row->id_satuan }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Satuan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form-{{ $row->id_satuan }}" action="{{ route('atk.satuan.update', $row->id_satuan) }}" method="POST">
                @csrf
                <input type="hidden" name="atk_id" value="{{ $atk->id_atk }}">
                <div class="modal-body">
                    <label class="text-muted small">
                        Satuan ATK dengan status <b>Aktif</b> akan yang digunakan untuk pengajuan.
                    </label>
                    <div class="form-group">
                        <label>Jenis Satuan</label>
                        <select name="jenis" class="form-control">
                            <option value="distribusi" <?php echo $row->jenis_satuan == 'distribusi' ? 'selected' : '' ?>>Distribusi</option>
                            <option value="pembelian" <?php echo $row->jenis_satuan == 'pembelian' ? 'selected' : '' ?>>Pembelian</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Nama Satuan</label>
                        <input type="text" name="satuan" class="form-control" placeholder="Nama Satuan ATK" value="{{ $row->satuan }}" required>
                    </div>
                    <div class="form-group">
                        <label>Range Harga</label>
                        <input type="text" name="range_harga" class="form-control" placeholder="Range harga barang" required>
                        <small>Contoh: 4.500 - 7.500</small>
                    </div>
                    <!-- <div class="form-group">
                        <label>Harga Satuan</label>
                        <input type="text" name="harga" class="form-control price" placeholder="Harga Satuan" value="{{ number_format($row->harga, 0, ',', '.') }}" required>
                        <small>untuk satuan pembelian, harga tidak perlu ditambahkan</small>
                    </div> -->
                    <div class="form-group">
                        <label>Keterangan</label>
                        <textarea name="deskripsi" class="form-control" placeholder="Keterangan satuan">{{ $row->deskripsi }}</textarea>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="1" <?php echo $row->status_id == 1 ? 'selected' : '' ?>>Aktif</option>
                            <option value="" <?php echo $row->status_id == '' ? 'selected' : '' ?>>Tidak Aktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" onclick="confirmSubmit(event, '{{ $row->id_satuan }}', 'Simpan Perubahan ?')">
                        <i class="fas fa-paper-plane"></i> Submit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@section('js')
<script>
    function showDetail(id) {
        var modal_target = "#detail-" + id;
        $(modal_target).modal('show');
    }

    $('.price').on('input', function() {
        var value = $(this).val().replace(/[^0-9]/g, '');
        var formattedValue = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        $(this).val(formattedValue);
    });

    function confirmSubmit(event, formId, action) {
        event.preventDefault();
        Swal.fire({
            title: action,
            text: '',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya!',
            cancelButtonText: 'Batal!',
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('form-' + formId);
                form.submit();
            }
        });
    }

    function confirmHapus(event) {
        event.preventDefault(); // Prevent the default link behavior

        Swal.fire({
            title: 'Hapus Satuan?',
            text: '',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal!',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = event.target.href; // Proceed with the link navigation
            }
        });
    }

    $(function() {
        let atk     = '{{ ucwords(strtolower($atk->kategori->kategori_atk." ".$atk->deskripsi)) }}'
        let kodeAtk = '{{ $atk->kategori_atk }}'
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
                    title: 'Satuan ATK',
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6]
                    },
                    messageTop: atk + '\n' + kodeAtk
                },
                {
                    extend: 'excel',
                    text: ' <i class="fas fa-file-excel"></i> Excel',
                    className: 'bg-success',
                    title: 'Satuan ATK',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6]
                    },
                    messageTop: atk + ' (' + kodeAtk + ')'
                }
            ],
            "bDestroy": true
        }).buttons().container().appendTo('#table-show_wrapper .col-md-6:eq(0)');

        $("#table-show2").DataTable({
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
                    title: 'Satuan ATK',
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6]
                    },
                    messageTop: atk + '\n' + kodeAtk
                },
                {
                    extend: 'excel',
                    text: ' <i class="fas fa-file-excel"></i> Excel',
                    className: 'bg-success',
                    title: 'Satuan ATK',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6]
                    },
                    messageTop: atk + ' (' + kodeAtk + ')'
                }
            ],
            "bDestroy": true
        }).buttons().container().appendTo('#table-show2_wrapper .col-md-6:eq(0)');
    })
</script>
@endsection

@endsection
