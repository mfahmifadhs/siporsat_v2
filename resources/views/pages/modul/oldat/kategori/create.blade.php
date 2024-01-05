@extends('layout.app')

@section('content')

<!-- Content Header -->
<section class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="text-capitalize">SIPORSAT</h1>
                <h5>Sistem Pengelolaan Operasional Perkantoran Terpusat</h5>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right text-capitalize">
                    <li class="breadcrumb-item"><a href="{{ route('oldat.home') }}">Beranda</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('oldat.barang.show') }}">Daftar BMN</a></li>
                    <li class="breadcrumb-item">Tambah Kategori BMN</li>
                </ol>
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
                <h3 class="card-title">Tambah Kategori BMN</h3>
            </div>
            <form id="form" action="{{ route('oldat.kategori.create') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group row">
                        <h6 class="col-md-2 mb-4">Kode Kategori*</h6>:
                        <div class="col-md-9 mb-4">
                            <input type="number" class="form-control input-border-bottom" name="kode_kategori" placeholder="Contoh : 3020201010" required>
                        </div>

                        <h6 class="col-md-2">Nama Kategori*</h6>:
                        <div class="col-md-9">
                            <input type="text" class="form-control input-border-bottom" name="kategori_barang" placeholder="Contoh : Lori Dorong" required>
                        </div>
                    </div>
                </div>

                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary" style="margin-right: 5.5em;" onclick="confirmSubmit(event)">
                        <i class="fas fa-paper-plane fa-1x"></i> <b>Submit</b>
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
        const inputs = form.querySelectorAll('input[name="kode_kategori"], input[name="kategori_barang"]');
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
                title: 'Tambah Kategori Barang?',
                text: '',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, tambah!',
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
