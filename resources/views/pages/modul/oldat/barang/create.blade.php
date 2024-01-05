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
                    <li class="breadcrumb-item active">Tambah</li>
                </ol>
            </div>
            <div class="col-sm-6 mt-3">
                <div class="form-group text-right">
                    <a href="{{ route('oldat.barang.show') }}" class="btn btn-add">
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
                <label class="card-title">Tambah BMN Olah Data & Meubelair</label>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <form id="form" action="{{ route('oldat.barang.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    @if (Auth::user()->role_id == 1)
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Unit Kerja</label>
                        <select class="col-md-9 form-control" name="unit_kerja_id" required>
                            <option value="">-- Pilih Unit Kerja --</option>
                            @foreach($uker as $row)
                            <option value="{{ $row->id_unit_kerja }}">
                                {{ $row->id_unit_kerja.' - '.$row->nama_unit_kerja }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    @else
                    <input type="hidden" name="unit_kerja_id" value="{{ Auth::user()->pegawai->unit_kerja_id }}">
                    @endif
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Nama Barang*</label>
                        <select class="col-md-5 form-control" name="kategori_id" required>
                            <option value="">-- Pilih Barang --</option>
                            @foreach($kategori as $row)
                            <option value="{{ $row->kode_kategori }}">
                                {{ $row->kode_kategori.' - '.$row->kategori_barang }}
                            </option>
                            @endforeach
                        </select>
                        <label class="col-md-2 col-form-label text-center">NUP*</label>
                        <input type="number" class="col-md-2 form-control text-center" name="nup" min="1" required>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Merk/Tipe*</label>
                        <input type="text" class="col-md-9 form-control" name="merktipe" placeholder="Contoh: Asus/Lenovo" required>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Spesifikasi</label>
                        <input type="text" class="col-md-9 form-control" name="spesifikasi" placeholder="Contoh: RAM 8, SSD 256">
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Nilai Perolehan</label>
                        <input type="text" class="col-md-3 form-control price" name="nilai">
                        <label class="col-md-3 col-form-label text-center">Tanggal Perolehan</label>
                        <input type="date" class="col-md-3 form-control" name="tanggal">
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Kondisi Barang*</label>
                        @php $kondisi = [
                        ['id' => '1', 'val' => 'Baik'], ['id' => '2', 'val' => 'Rusak Ringan'],
                        ['id' => '3', 'val' => 'Rusak Berat']
                        ]; @endphp
                        <select class="col-md-3 form-control" name="kondisi">
                            @foreach($kondisi as $row)
                            <option value="{{ $row['id'] }}">{{ $row['val'] }}</option>
                            @endforeach
                        </select>
                        <label class="col-md-3 col-form-label text-center">Status*</label>
                        @php $status = [
                        ['id' => '1', 'val' => 'Aktif'], ['id' => '2', 'val' => 'Perbaikan'],
                        ['id' => '3', 'val' => 'Proses Penghapusan'], ['id' => '4', 'val' => 'Sudah Dihapuskan']
                        ]; @endphp
                        <select class="col-md-3 form-control" name="status">
                            @foreach($status as $row)
                            <option value="{{ $row['id'] }}">{{ $row['val'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Foto</label>
                        <div class="col-md-3 btn btn-default btn-file border-secondary">
                            <i class="fas fa-paperclip"></i> Upload Foto
                            <input type="file" name="foto_barang" class="image-barang" required>
                            <img id="preview-image-barang" style="max-height: 80px;">
                            <p class="help-block mb-0" style="font-size: 10px;">Max. 5MB</p>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="text-right" style="margin-right: 5.5em;">
                        <button type="submit" class="btn btn-primary btn-md" onclick="confirmSubmit(event)">
                            <i class="fas fa-paper-plane"></i> Submit
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

@section('js')
<script>
    $(function() {
        $('select[name="unit_kerja_id"]').select2();
        $('select[name="kategori_id"]').select2();

        // Format harga
        $('.price').on('input', function() {
            var value = $(this).val().replace(/[^0-9]/g, '');

            var formattedValue = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

            $(this).val(formattedValue);
        });

        // Preview Foto
        $('.image-barang').change(function() {
            let reader = new FileReader()
            reader.onload = (e) => {
                $('#preview-image-barang').attr('src', e.target.result)
            }
            reader.readAsDataURL(this.files[0])
        });

    });

    function confirmSubmit(event) {
        event.preventDefault(); // Prevent the default link behavior

        // Mengecek setiap input pada form
        const form = document.getElementById('form');
        const inputs = form.querySelectorAll('select[name="kategori_id"], input[name="merktipe"], input[name="nup"]');
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
                title: 'Tambah Barang?',
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
