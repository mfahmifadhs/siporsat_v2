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
                    <li class="breadcrumb-item"><a href="{{ route('atk.home') }}">ATK</a></li>
                    <li class="breadcrumb-item active">Satuan</li>
                </ol>
            </div>
            <div class="col-sm-6 mt-3">
                <div class="form-group text-right">
                    <a class="btn btn-add" data-toggle="modal" data-target="#btn-add">
                        <span class="btn btn-primary rounded-0 mr-1">
                            <i class="fas fa-add"></i>
                        </span>
                        <small>Tambah</small>
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
        <div class="card card-primary card-outline">
            <div class="card-header">
                <label class="card-title">Daftar Satuan Alat Tulis Kantor</label>
            </div>
            <div class="card-body">
                <table id="table-show" class="table table-bordered table-striped" style="font-size: 15px;">
                    <thead class="text-center">
                        <tr>
                            <th>No</th>
                            <th>Satuan</th>
                            <th>Deskripsi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    @php $no = 1; @endphp
                    <tbody class="text-capitalize">
                        @foreach($satuan as $row)
                        <tr>
                            <td class="pt-3 text-center">{{ $no++ }} </td>
                            <td class="pt-3 text-center">{{ $row->satuan }}</td>
                            <td class="pt-3">{{ $row->deskripsi }}</td>
                            <td class="text-center">
                                <a type="button" class="btn btn-primary btn-sm" data-toggle="dropdown">
                                    <i class="fas fa-bars"></i>
                                </a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item btn" type="button" data-toggle="modal" data-target="#edit-{{ $row->id_satuan }}">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a class="dropdown-item btn" href="{{ route('atk.satuan.destroy', $row->id_satuan) }}" onclick="confirmHapus(event)">
                                        <i class="fas fa-trash"></i> Hapus
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<!-- Modal Tambah -->
<div class="modal fade" id="btn-add" tabindex="-1" role="dialog" aria-labelledby="btn-add" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Satuan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form-tambah" action="{{ route('atk.satuan.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <label class="text-muted small">
                        Pastikan Satuan yang akan ditambahkan belum ada di daftar satuan, untuk menghindari terjadinya duplikasi Satuan ATK.
                    </label>
                    <div class="form-group">
                        <label>Nama Satuan</label>
                        <input type="text" name="satuan" class="form-control" placeholder="Nama satuan atk" required>
                    </div>
                    <div class="form-group">
                        <label>Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" placeholder="Deskripsi satuan"></textarea>
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
@foreach ($satuan as $row)
<div class="modal fade" id="edit-{{ $row->id_satuan }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Satuan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form-{{ $row->id_satuan }}" action="{{ route('atk.satuan.update', $row->id_satuan) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <label class="text-muted small">
                        Pastikan Satuan yang akan ditambahkan belum ada di daftar satuan, untuk menghindari terjadinya duplikasi Satuan ATK.
                    </label>
                    <div class="form-group">
                        <label>Nama Satuan</label>
                        <input type="text" name="satuan" class="form-control" placeholder="Nama satuan atk" value="{{ $row->satuan }}" required>
                    </div>
                    <div class="form-group">
                        <label>Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" placeholder="Deskripsi satuan">{{ $row->deskripsi }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" onclick="confirmSubmit(event, '{{ $row->id_satuan }}' ,'Simpan Perubahan ?')",>
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
    function showEdit(id) {
        var modal_target = "#edit-" + id;
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
            "responsive": true,
            "lengthChange": true,
            "autoWidth": true,
            "info": true,
            "paging": true,
            "searching": true,
            buttons: [{
                    extend: 'pdf',
                    text: ' <i class="fas fa-file-pdf"></i> PDF',
                    className: 'bg-danger',
                    title: 'Kategori AADB',
                    exportOptions: {
                        columns: [0, 1, 2]
                    },
                    messageTop: datetime
                },
                {
                    extend: 'excel',
                    text: ' <i class="fas fa-file-excel"></i> Excel',
                    className: 'bg-success',
                    title: 'Kategori AADB',
                    exportOptions: {
                        columns: [0, 1, 2]
                    },
                    messageTop: datetime
                }
            ],
            "bDestroy": true
        }).buttons().container().appendTo('#table-show_wrapper .col-md-6:eq(0)');
    })

    function confirmSubmit(event, formId, action) {
        event.preventDefault();

        const form = document.getElementById('form-' + formId);
        const requiredInputs = form.querySelectorAll('input[required], textarea[required]');

        let allInputsValid = true;

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
</script>
@endsection

@endsection
