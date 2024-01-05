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
                    <li class="breadcrumb-item"><a href="{{ route('aadb.kendaraan.show') }}">Daftar AADB</a></li>
                    <li class="breadcrumb-item active">Tambah</li>
                </ol>
            </div>
            <div class="col-sm-6 mt-3">
                <div class="form-group text-right">
                    <a href="{{ route('aadb.kendaraan.show') }}" class="btn btn-add">
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
                <label class="card-title">Tambah Alat Angkutan Darat Bermotor (AADB)</label>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <form id="form" action="{{ route('aadb.kendaraan.store') }}" method="POST" enctype="multipart/form-data">
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
                        <label class="col-md-2 col-form-label">Nama Kendaraan*</label>
                        <select class="col-md-9 form-control" name="kategori" required>
                            <option value="">-- Pilih Kendaraan --</option>
                            @foreach($kategori as $row)
                            <option value="{{ $row->kode_kategori }}">
                                {{ $row->kode_kategori.' - '.$row->kategori_aadb }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Kualifikasi*</label>
                        @php $kualifikasi = ['jabatan','operasional'] @endphp
                        <select class="col-md-9 form-control" name="kualifikasi" required>
                            <option value="">-- Pilih Kualifikasi --</option>
                            @foreach($kualifikasi as $row)
                            <option value="{{ $row }}">{{ ucfirst($row) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Merk/Tipe*</label>
                        <input type="text" class="col-md-9 form-control" name="merktipe" placeholder="Contoh: Suzuki Ertiga" required>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Keterangan*</label>
                        <input type="text" class="col-md-9 form-control" name="keterangan" placeholder="Contoh: Kendaraan Operasional Menteri" required>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Jenis AADB</label>
                        <div class="col-md-1 col-form-label">
                            <input type="radio" class="" name="jenis_aadb" value="bmn">
                            <span>BMN</span>
                        </div>
                        <div class="col-md-3 col-form-label">
                            <input type="radio" class="" name="jenis_aadb" value="sewa">
                            <span>Sewa</span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">No. Plat*</label>
                        <input type="text" class="col-md-3 form-control text-center" name="no_plat" required>
                        <label class="col-md-3 col-form-label text-center">No. Plat Dinas</label>
                        <input type="text" class="col-md-3 form-control text-center" name="no_plat_dinas">
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Masa Berlaku STNK</label>
                        <input type="date" class="col-md-3 form-control text-center" name="tanggal_stnk">
                        <label class="col-md-3 col-form-label text-center">Tahun Kendaraan</label>
                        <input type="number" class="col-md-3 form-control text-center" name="tahun">
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Nilai Perolehan (Rp)</label>
                        <input type="text" class="col-md-3 form-control price" name="nilai_perolehan">
                        <label class="col-md-3 col-form-label text-center">Tanggal Perolehan</label>
                        <input type="date" class="col-md-3 form-control" name="tanggal_perolehan">
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Nomor BPKB</label>
                        <input type="text" class="col-md-3 form-control" name="no_bpkb">
                        <label class="col-md-3 col-form-label text-center">Nomor Rangka</label>
                        <input type="text" class="col-md-3 form-control" name="no_rangka">
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label text-center">Nomor Mesin</label>
                        <input type="text" class="col-md-3 form-control" name="no_mesin">
                        <label class="col-md-3 col-form-label text-center">Kondisi*</label>
                        <select class="col-md-3 form-control" name="kondisi">
                            @foreach($kondisi as $row)
                            <option value="{{ $row->id_kondisi }}">{{ $row->nama_kondisi }}</option>
                            @endforeach
                        </select>

                    </div>

                    <div class="form-group row">
                        <label class="col-md-2 col-form-label text-center">Status*</label>
                        <select class="col-md-3 form-control" name="status">
                            @foreach($status->where('kategori_status','barang') as $row)
                            <option value="{{ $row->id_status }}">{{ $row->nama_status }}</option>
                            @endforeach
                        </select>
                        <label class="col-md-3 col-form-label text-center">Foto</label>
                        <div class="col-md-3 btn btn-default btn-file border-secondary">
                            <i class="fas fa-paperclip"></i> Upload Foto
                            <input type="file" name="foto_atk" class="image-aadb">
                            <img id="preview-image-aadb" style="max-height: 80px;">
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
        $('select[name="kategori"]').select2();

        // Format harga
        $('.price').on('input', function() {
            var value = $(this).val().replace(/[^0-9]/g, '');

            var formattedValue = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

            $(this).val(formattedValue);
        });

        // Preview Foto
        $('.image-aadb').change(function() {
            let reader = new FileReader()
            reader.onload = (e) => {
                $('#preview-image-aadb').attr('src', e.target.result)
            }
            reader.readAsDataURL(this.files[0])
        });

    });

    function confirmSubmit(event) {
        event.preventDefault(); // Prevent the default link behavior

        // Mengecek setiap input pada form
        const form = document.getElementById('form');
        const inputs = form.querySelectorAll('select[required], input[required]');
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
