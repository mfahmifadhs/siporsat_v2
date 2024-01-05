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
                    <li class="breadcrumb-item active">Kategori</li>
                </ol>
            </div>
            <div class="col-sm-6 mt-3">
                @if(Auth::user()->role_id == 1)
                <div class="form-group text-right">
                    <a class="btn btn-add" data-toggle="modal" data-target="#btn-add">
                        <span class="btn btn-primary rounded-0 mr-1">
                            <i class="fas fa-add"></i>
                        </span>
                        <small>Tambah</small>
                    </a>
                </div>
                @endif
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
                <label class="card-title">Daftar Kategori Alat Tulis Kantor</label>
            </div>
            <div class="card-body">
                <table id="table-show" class="table table-bordered table-striped text-center" style="font-size: 15px;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Kategori</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    @php $no = 1; @endphp
                    <tbody class="text-capitalize">
                        @foreach($kategori as $row)
                        <tr>
                            <td>{{ $no++ }} </td>
                            <td>{{ $row->kode_kategori }}</td>
                            <td class="text-left">{{ $row->kategori_atk }}</td>
                            <td>
                                @if(Auth::user()->role_id == 1)
                                <a type="button" class="btn btn-primary btn-sm" data-toggle="dropdown">
                                    <i class="fas fa-bars"></i>
                                </a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item btn" type="button" data-toggle="modal" data-target="#edit-{{ $row->id_kategori }}">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a class="dropdown-item btn" href="{{ route('atk.kategori.destroy', $row->id_kategori) }}" onclick="confirmHapus(event)">
                                        <i class="fas fa-trash"></i> Hapus
                                    </a>
                                </div>
                                @endif
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
                <h5 class="modal-title">Tambah Kategori</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form-tambah" action="{{ route('atk.kategori.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <label class="text-muted small">
                        Pastikan Kategori ATK yang akan ditambahkan belum ada di daftar kategori, untuk menghindari terjadinya duplikasi Kategori ATK.
                    </label>
                    <div class="form-group">
                        <label>Kode Kategori</label>
                        <input type="text" name="kode" class="form-control" placeholder="Kode kategori atk" required>
                    </div>
                    <div class="form-group">
                        <label>Nama Kategori</label>
                        <input type="text" name="kategori" class="form-control" placeholder="Nama kategori atk" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" onclick="confirmSubmit(event, 'tambah', 'Tambah Kategori ?')">
                        <i class="fas fa-paper-plane"></i> Submit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit -->
@foreach ($kategori as $row)
<div class="modal fade" id="edit-{{ $row->id_kategori }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Satuan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form-{{ $row->id_kategori }}" action="{{ route('atk.kategori.update', $row->id_kategori) }}" method="POST">
                @csrf
                <div class="modal-body">
                <label class="text-muted small">
                        Pastikan Kategori ATK yang akan ditambahkan belum ada di daftar kategori, untuk menghindari terjadinya duplikasi Kategori ATK.
                    </label>
                    <div class="form-group">
                        <label>Kode Kategori</label>
                        <input type="text" name="kode" class="form-control" placeholder="Kode kategori atk" value="{{ $row->kode_kategori }}" required>
                    </div>
                    <div class="form-group">
                        <label>Nama Kategori</label>
                        <input type="text" name="kategori" class="form-control" placeholder="Nama kategori atk" value="{{ $row->kategori_atk }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" onclick="confirmSubmit(event, '{{ $row->id_kategori }}' ,'Simpan Perubahan ?')",>
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
