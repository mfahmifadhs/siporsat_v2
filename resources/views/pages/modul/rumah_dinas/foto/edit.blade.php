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
                    <li class="breadcrumb-item"><a href="{{ route('rumah_dinas.show') }}">Daftar Rumah Dinas</a></li>
                    <li class="breadcrumb-item active">Tambah Penghuni</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<!-- Content Header -->

<section class="content">
    <div class="container">

        <div class="card" style="border-radius: 10px;">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-8 col-6">
                        <div class="form-group row">
                            <div class="col-md-12 mb-2">Golongan {{ $rumah->golongan }}</div>
                            <div class="col-md-3">Lokasi Kota</div>:
                            <div class="col-md-4">{{ $rumah->lokasi_kota }}</div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3">Luas Bangunan</div>:
                            <div class="col-md-4">{{ $rumah->luas_bangunan }} m<sup>2</sup></div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3">Luas Tanah</div>:
                            <div class="col-md-4">{{ $rumah->luas_tanah }} m<sup>2</sup></div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3">Kondisi</div>:
                            <div class="col-md-4">{{ $rumah->kondisi }}</div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3">Alamat</div>:
                            <div class="col-md-8 text-justify">{{ $rumah->alamat }}</div>
                        </div>
                    </div>
                    <div class="col-md-4 col-6 mt-3">
                        @foreach($rumah->fotoRumah->take(1) as $row)
                        <img src="{{ asset('storage/' . $row->nama_file) }}" class="img-fluid">
                        @endforeach
                    </div>
                </div>
            </div>
            <form action="{{ route('rumah_dinas.foto.edit', $rumah->id_rumah) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <span class="pb-5">
                        Edit Foto Rumah
                    </span>
                    <div class="form-group row">
                        @foreach ($foto as $row)
                        <div class="col-md-3">
                            <input type="hidden" name="id_foto[]" value="{{ $row->id_foto }}">
                            <div id="preview-container-{{ $row->id_foto }}" class="img-fluid">
                                <img src="{{ asset('storage/' . $row->nama_file) }}" class="img-fluid mb-2 preview-image-{{ $row->id_foto }}">
                            </div>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" multiple accept="image/*" id="foto-input-{{ $row->id_foto }}" name="foto_rumah[]">
                                    <label class="custom-file-label border border-dark" for="foto-input-{{ $row->id_foto }}">Choose file</label>
                                </div>
                                <div class="input-group-append">
                                    <a href="{{ route('rumah_dinas.foto.hapus', $row->id_foto) }}" class="input-group-text border border-dark"
                                    onclick="return confirm('Apakah ingin menghapus data ini ?')">
                                        <i class="fas fa-trash"></i> Hapus
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary" onclick="return confirm('Simpan Perubahan ?')">
                        <i class="fas fa-save fa-1x"></i> <b>Simpan</b>
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

@section('js')
<script>
    function handleFileSelect(event, fotoId) {
        const fileInput = event.target;
        const previewContainer = document.getElementById('preview-container-' + fotoId);
        const previewImage = document.querySelector('.preview-image-' + fotoId);

        previewContainer.innerHTML = ''; // Clear previous previews

        const files = fileInput.files;
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            const reader = new FileReader();

            reader.onload = function(e) {
                const imgElement = document.createElement('img');
                imgElement.classList.add('preview-image');
                imgElement.classList.add('img-fluid');
                imgElement.style.width = '100%';
                imgElement.src = e.target.result;
                previewContainer.appendChild(imgElement);
            }

            reader.readAsDataURL(file);
        }
    }

    // Dapatkan elemen-elemen input file dengan class yang sesuai
    const fileInputs = document.querySelectorAll('[id^="foto-input-"]');

    // Loop melalui elemen-elemen input file dan tambahkan event listener
    fileInputs.forEach(function(fileInput) {
        const fotoId = fileInput.id.split('-')[2];
        fileInput.addEventListener('change', function(event) {
            handleFileSelect(event, fotoId);
        });
    });
</script>
@endsection

@endsection
