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
                    <li class="breadcrumb-item"><a href="{{ route('aadb.home') }}">Beranda</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('aadb.kategori.show') }}">Daftar Kategori AADB</a></li>
                    <li class="breadcrumb-item">Tambah Kategori</li>
                </ol>
            </div>
            <div class="col-sm-6 mt-3">
                <div class="form-group text-right">
                    <a href="{{ route('aadb.kategori.show') }}" class="btn btn-add">
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
                <label class="card-title">Edit Kategori AADB</label>
            </div>
            <form id="form" action="{{ route('aadb.kategori.edit', $id) }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-md-2 mb-4">Kode Kategori*</label>:
                        <div class="col-md-9 mb-4">
                            <input type="number" class="form-control input-border-bottom" name="kode_kategori" value="{{ $kategori->kode_kategori }}">
                        </div>

                        <label class="col-md-2">Nama Kategori*</label>:
                        <div class="col-md-9">
                            <input type="text" class="form-control input-border-bottom" name="kategori_aadb" value="{{ $kategori->kategori_aadb }}"d>
                        </div>
                    </div>
                </div>

                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary" style="margin-right: 5.5em;" onclick="confirmSubmit(event)">
                        <i class="fas fa-save fa-1x"></i> <b>Simpan</b>
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

@section('js')
<script>
    function confirmSubmit(event) {
        event.preventDefault(); // Prevent the default link behavior

        // Mengecek setiap input pada form
        const form = document.getElementById('form');
        const inputs = form.querySelectorAll('input[name="kode_kategori"], input[name="kategori_aadb"]');
        let isFormValid = true;

        inputs.forEach(input => {
            if (input.hasAttribute('required') && input.value.trim() === '') {
                // Jika input required kosong, tandai dengan warna border merah
                input.style.borderColor = 'red';
                isFormValid = false;
                console.log(true)
            } else {
                input.style.borderColor = ''; // Menghapus warna border merah
                console.log(false)
            }
        });

        if (isFormValid) {
            Swal.fire({
                title: 'Simpan Perubahan?',
                text: '',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, simpan!',
                cancelButtonText: 'Batal!',
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        } else {
            Swal.fire({
                title: 'Isian belum lengkap',
                text: 'Silakan lengkapi semua isian yang diperlukan',
                icon: 'error',
            });
        }
    }
</script>
@endsection

@endsection
