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
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('rumah_dinas.show') }}">Rumah Dinas</a></li>
                    <li class="breadcrumb-item active">Edit Rumah Dinas</li>
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
                <h3 class="card-title">Edit Rumah Dinas</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <form action="{{ route('rumah_dinas.edit', $rumah->id_rumah) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Lokasi Kota*</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" name="lokasi_kota" value="{{ $rumah->lokasi_kota }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Golongan</label>
                        <div class="col-md-10 mt-2">
                            <span class="mr-4">
                                <input type="radio" name="golongan" value="I" <?php echo $rumah->golongan == 'I' ? 'checked' : ''; ?>> Gol. I
                            </span>
                            <span class="mr-3">
                                <input type="radio" name="golongan" value="II" <?php echo $rumah->golongan == 'II' ? 'checked' : ''; ?>> Gol. II
                            </span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Luas Bangunan*</label>
                        <div class="col-md-10">
                            <div class="input-group mb-2">
                                <input type="text" class="form-control" name="luas_bangunan" value="{{ $rumah->luas_bangunan }}">
                                <div class="input-group-prepend">
                                    <div class="input-group-text border border-dark">m<sup>2</sup></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Luas Tanah*</label>
                        <div class="col-md-10">
                            <div class="input-group mb-2">
                                <input type="text" class="form-control" name="luas_tanah" value="{{ $rumah->luas_tanah }}">
                                <div class="input-group-prepend">
                                    <div class="input-group-text border border-dark">m<sup>2</sup></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Kondisi*</label>
                        <div class="col-md-10">
                            <select name="kondisi" class="form-control" required>
                                <option value="baik" {{ $rumah->kondisi == 'baik' ? 'selected' : '' }}>Baik</option>
                                <option value="rusak ringan" {{ $rumah->kondisi == 'rusak ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                                <option value="rusak berat" {{ $rumah->kondisi == 'rusak berat' ? 'selected' : '' }}>Rusak Berat</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Alamat*</label>
                        <div class="col-md-10">
                            <textarea name="alamat" class="form-control" rows="2" placeholder="Alamat Lengkap">{{ $rumah->alamat }}</textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">&nbsp;</label>
                        <div class="col-md-10">
                            <div id="preview-container"></div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary" onclick="return confirm('Simpan perubahan ?')">
                        <i class="fas fa-save fa-1x"></i> <b>Simpan</b>
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

@section('js')
<script>
    $(document).ready(function() {
        $('.input-format').on('input', function() {
            // Menghapus karakter selain angka (termasuk tanda titik koma sebelumnya)
            var value = $(this).val().replace(/[^0-9]/g, '');

            // Format dengan menambahkan titik koma setiap tiga digit
            var formattedValue = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

            // Memasukkan nilai yang sudah diformat kembali ke input
            $(this).val(formattedValue);
        });
    });

    function handleFileSelect(event) {
        const previewContainer = document.getElementById('preview-container');
        previewContainer.innerHTML = ''; // Clear previous previews

        const files = event.target.files;
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            const reader = new FileReader();

            reader.onload = function(e) {
                const imgElement = document.createElement('img');
                imgElement.classList.add('preview-image');
                imgElement.src = e.target.result;
                previewContainer.appendChild(imgElement);
            }

            reader.readAsDataURL(file);
        }
    }

    const fileInput = document.getElementById('foto-input');
    fileInput.addEventListener('change', handleFileSelect);
</script>
@endsection

@endsection
