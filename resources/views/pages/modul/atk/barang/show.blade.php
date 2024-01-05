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
                    <li class="breadcrumb-item"><a href="{{ route('atk.home') }}">Beranda</a></li>
                    <li class="breadcrumb-item active">Referensi ATK</li>
                </ol>
            </div>
            @if(Auth::user()->pegawai->jabatan_id == 12)
            <div class="col-sm-6 mt-3">
                <div class="form-group text-right">
                    <a class="btn btn-add" data-toggle="modal" data-target="#btn-add">
                        <span class="btn btn-primary rounded-0 mr-1">
                            <i class="fas fa-add"></i>
                        </span>
                        <small>Tambah</small>
                    </a>
                    <a class="btn btn-add" data-toggle="modal" data-target="#btn-upload">
                        <span class="btn btn-primary rounded-0 mr-1">
                            <i class="fas fa-file-arrow-up"></i>
                        </span>
                        <small>Upload</small>
                    </a>
                </div>
            </div>
            @endif
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
                <label class="card-title">Daftar Alat Tulis Kantor</label>
            </div>
            <div class="card-body">
                <table id="table-show" class="table table-bordered table-striped text-center" style="font-size: 14px;">
                    <thead>
                        <tr>
                            <th style="width: 0%;">No</th>
                            <th>Id Atk</th>
                            <th style="width: 12%;">Kode Barang</th>
                            <th style="width: 8%;">Jenis ATK</th>
                            <th>Nama Barang</th>
                            <th>Deskripsi</th>
                            <th style="width: 10%;">Satuan</th>
                            <th style="width: 12%;">Keterangan</th>
                            <th style="width: 15%;">Foto</th>
                            <th style="width: 0%;">Aksi</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    @php $no = 1; @endphp
                    <tbody class="text-capitalize" style="font-size: 13px;">
                        @foreach($atk as $row)
                        <tr>
                            <td>
                                {{ $no++ }}
                                <i class="fas {{ $row->status_id == 1 ? 'fa-square text-success' : 'fa-square text-danger' }} fa-xs"></i>
                            </td>
                            <td>{{ $row->id_atk }}</td>
                            <td>{{ $row->kode_kategori }}</td>
                            <td>{{ strtoupper($row->jenis_atk) }}</td>
                            <td class="text-left">{{ $row->kategori_atk }}</td>
                            <td class="text-left">{{ $row->deskripsi }}</td>
                            <td>{{ optional($row->satuan->where('jenis_satuan', 'distribusi')->first())->satuan }}</td>
                            <td>{{ $row->keterangan_atk }}</td>
                            <td style="width: 10%;">
                                @if($row->foto_atk)
                                <img src="{{ asset('storage/files/foto_atk/'. $row->foto_atk) }}" class="img-fluid">
                                @endif
                            </td>
                            <td>
                                @if(Auth::user()->pegawai->jabatan_id == 12)
                                <a type="button" class="btn btn-primary btn-sm" data-toggle="dropdown">
                                    <i class="fas fa-bars"></i>
                                </a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item btn" href="{{ route('atk.detail', $row->id_atk) }}">
                                        <i class="fas fa-info-circle"></i> Detail
                                    </a>
                                    <a class="dropdown-item btn" type="button" data-toggle="modal" data-target="#edit-{{ $row->id_atk }}">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a class="dropdown-item btn" href="{{ route('atk.destroy', $row->id_atk) }}" onclick="confirmHapus(event)" data-id="{{ $row->id_atk }}">
                                        <i class="fas fa-trash"></i> Hapus
                                    </a>
                                </div>
                                @endif
                            </td>
                            <td>{{ $row->status_id }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<!-- Modal Upload -->
<div class="modal fade" id="btn-upload" role="dialog" aria-labelledby="btn-upload" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload File</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form-upload" action="{{ route('atk.upload.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <label class="text-muted small">
                        Upload file sesuai format (Export file Excel terlebih dahulu).
                    </label>
                    <div class="form-group">
                        <div class="btn btn-default btn-file btn-block border border-secondary p-5">
                            <i class="fas fa-upload"></i> Upload File
                            <input type="file" class="form-control image" name="file_atk[]" onchange="displaySelectedFile(this)" required><br>
                            <span id="selected-file-name"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="submitBtn" onclick="confirmSubmit(event, 'upload', 'Upload File ?')">
                        <i class="fas fa-paper-plane"></i> Submit
                    </button>
                    <button class="btn btn-primary d-none" id="loadingBtn" type="button" disabled>
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Loading...
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<form id="form-tambah" action="{{ route('atk.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="modal fade" id="btn-add" role="dialog" aria-labelledby="btn-add" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah ATK</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label class="text-muted small">
                        Pastikan Satuan yang akan ditambahkan belum ada di daftar satuan, untuk menghindari terjadinya duplikasi Satuan ATK.
                    </label>
                    <div class="form-group">
                        <label>Jenis ATK</label>
                        <select name="jenis" class="form-control form-control-md" style="width: 100%;" required>
                            <option value="">-- Pilih Jenis ATK --</option>
                            <option value="atk">ATK</option>
                            <option value="alkom">ALKOM</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Nama ATK</label>
                        <select name="kategori" class="form-control form-control-md" style="width: 100%;" required>
                            <option value="">-- Pilih ATK --</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Deskripsi</label>
                        <input name="deskripsi" class="form-control" placeholder="Deskripsi barang" required>
                    </div>
                    <div class="form-group">
                        <label>Keterangan</label>
                        <textarea name="keterangan" class="form-control" placeholder="keterangan tambahan"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Foto</label>
                        <div class="btn btn-default btn-block btn-file border-secondary">
                            <i class="fas fa-paperclip"></i> Upload Foto
                            <input type="file" name="foto_kendaraan" class="image-atk">
                            <img id="preview-image-atk" style="max-height: 80px;">
                            <p class="help-block mb-0" style="font-size: 10px;">Max. 5MB</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" onclick="confirmSubmit(event, 'tambah', 'Tambah ATK ?')">
                        <i class="fas fa-paper-plane"></i> Submit
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Modal Edit -->
@foreach ($atk as $row)
<form id="form-{{ $row->id_atk }}" action="{{ route('atk.update', $row->id_atk) }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="modal fade" id="edit-{{ $row->id_atk }}" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit ATK</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label class="text-muted small">
                        Pastikan Satuan yang akan ditambahkan belum ada di daftar satuan, untuk menghindari terjadinya duplikasi Satuan ATK.
                    </label>
                    <div class="form-group">
                        <label>Jenis ATK</label>
                        <select name="jenis" class="form-control form-control-md" style="width: 100%;" required>
                            <option value="">-- Pilih Jenis ATK --</option>
                            <option value="atk" <?php echo $row->jenis_atk == 'atk' ? 'selected' : ''?>>ATK</option>
                            <option value="alkom" <?php echo $row->jenis_atk == 'alkom' ? 'selected' : ''?>>ALKOM</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Nama ATK</label>
                        <select name="kategori" class="form-control form-control-md" style="width: 100%;" required>
                            <option value="{{ $row->kategori_id }}">
                                {{ $row->kategori->kategori_atk }}
                            </option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Deskripsi</label>
                        <input name="deskripsi" class="form-control" placeholder="Deskripsi barang" value="{{ $row->deskripsi }}" required>
                    </div>
                    <div class="form-group">
                        <label>Satuan</label>
                        <input class="form-control" value="{{ optional($row->satuan->where('jenis_satuan', 'distribusi')->first())->satuan }}" readonly>
                        <small>satuan hanya dapat di edit pada menu detail ATK</small>
                    </div>
                    <div class="form-group">
                        <label>Keterangan</label>
                        <textarea name="keterangan" class="form-control" placeholder="keterangan tambahan">{{ $row->keterangan_atk }}</textarea>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="1" <?php echo $row->status_id == 1 ? 'selected' : '' ?>>Tersedia</option>
                            <option value="" <?php echo $row->status_id == '' ? 'selected' : '' ?>>Tidak Tersedia</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Foto</label>
                        <div class="btn btn-default btn-block btn-file border-secondary">
                            <i class="fas fa-paperclip"></i> Upload Foto
                            <input type="file" name="foto_atk" class="image-atk" data-id-atk="{{ $row->id_atk }}">
                            <img id="preview-image-atk-{{ $row->id_atk }}" src="{{ $row->foto_atk ? asset('storage/files/foto_atk/'. $row->foto_atk) : '' }}" style="max-height: 80px;">
                            <p class="help-block mb-0" style="font-size: 10px;">Max. 5MB</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" onclick="confirmSubmit(event, '{{ $row->id_atk }}' ,'Simpan Perubahan ?')" ,>
                        <i class="fas fa-paper-plane"></i> Submit
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endforeach

@section('js')
<script>
    function displaySelectedFile(input) {
        var selectedFileName = "";
        if (input.files.length > 0) {
            // Ambil nama file dari input file
            selectedFileName = input.files[0].name;
        }

        // Tampilkan nama file dalam elemen dengan id "selected-file-name"
        document.getElementById("selected-file-name").textContent = selectedFileName;
    }
    // Preview Foto
    $('.image-atk').change(function() {
        let reader = new FileReader()
        reader.onload = (e) => {
            $('#preview-image-atk').attr('src', e.target.result)
        }
        reader.readAsDataURL(this.files[0])
    });
</script>
<script>
    $('.image-atk').change(function() {
        let idAtk = $(this).data('id-atk');
        let reader = new FileReader();
        reader.onload = (e) => {
            $('#preview-image-atk-' + idAtk).attr('src', e.target.result);
        };
        reader.readAsDataURL(this.files[0]);
    });

    $(function() {
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content')
        $('[name="satuan"]').select2()
        $('[name="kategori"]').select2({
            ajax: {
                url: "{{ url('atk/kategori/select') }}",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        _token: CSRF_TOKEN,
                        search: params.term // search term
                    }
                },
                processResults: function(response) {
                    return {
                        results: response
                    }
                },
                cache: true
            }
        })
    })

    // data table dan export data
    $(function() {
        var currentdate = new Date();
        var datetime = "Tanggal: " + currentdate.getDate() + "/" +
            (currentdate.getMonth() + 1) + "/" +
            currentdate.getFullYear() + " \n Pukul: " +
            currentdate.getHours() + ":" +
            currentdate.getMinutes() + ":" +
            currentdate.getSeconds()

        $("#table-show").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": true,
            "info": true,
            "paging": true,
            "searching": true,
            columnDefs: [{
                "bVisible": false,
                "aTargets": [1,10]
            }],
            buttons: [{
                    extend: 'pdf',
                    text: ' <i class="fas fa-file-pdf"></i> PDF',
                    className: 'bg-danger',
                    title: 'Referensi ATK',
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6, 10]
                    },
                    messageTop: datetime
                },
                {
                    extend: 'excel',
                    text: ' <i class="fas fa-file-excel"></i> Excel',
                    className: 'bg-success',
                    title: 'Referensi ATK',
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 7, 10]
                    },
                    messageTop: 'Mohon untuk tidak menambah/mengubah/menghapus pada kolom ID ATK!'
                }
            ],
            "bDestroy": true
        }).buttons().container().appendTo('#table-show_wrapper .col-md-6:eq(0)');

        function displaySelectedFile(input) {
            var selectedFileName = "";
            if (input.files.length > 0) {
                // Ambil nama file dari input file
                selectedFileName = input.files[0].name;
            }

            // Tampilkan nama file dalam elemen dengan id "selected-file-name"
            document.getElementById("selected-file-name").textContent = selectedFileName;
        }
    })

    function confirmSubmit(event, formId, action) {
        event.preventDefault();
        const submitBtn = document.getElementById('submitBtn');
        const loadingBtn = document.getElementById('loadingBtn');

        const form = document.getElementById('form-' + formId);
        const requiredInputs = form.querySelectorAll('input[required], select[required], textarea[required]');

        let allInputsValid = true;
        let loadingSpinner = document.getElementById('loading-spinner');

        requiredInputs.forEach(input => {
            if (input.value.trim() === '') {
                input.style.borderColor = 'red';
                allInputsValid = false;
            } else {
                input.style.borderColor = '';
            }
        });

        if (allInputsValid) {
            Swal.fire({
                title: action,
                text: '',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya!',
                cancelButtonText: 'Batal!',
            }).then((result) => {
                if (result.isConfirmed) {
                    submitBtn.classList.add('d-none'); // Sembunyikan tombol "Submit"
                    loadingBtn.classList.remove('d-none'); // Tampilkan tombol "Loading"
                    form.submit();
                }
            });
        } else {
            Swal.fire({
                title: 'Error',
                text: 'Ada input yang diperlukan yang belum diisi.',
                icon: 'error'
            });
        }
    }

    function confirmHapus(event) {
        event.preventDefault();

        const idAtk = event.target.getAttribute('data-id');
        Swal.fire({
            title: 'Hapus ATK?',
            text: '',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal!',
        }).then((result) => {
            if (result.isConfirmed) {
                const url = "{{ route('atk.destroy', ':id') }}".replace(':id', idAtk);
                window.location.href = url;
            }
        });
    }
</script>
@endsection

@endsection
