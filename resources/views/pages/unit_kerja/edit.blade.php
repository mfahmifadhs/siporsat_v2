@extends('layout.app')

@section('content')

<!-- Content Header -->
<section class="content-header" style="margin: 0% 15% 0% 15%;">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h1 class="text-capitalize">SIPORSAT</h1>
                <h5>Sistem Pengelolaan Operasional Perkantoran Terpusat</h5>
            </div>
            <div class="col-sm-12">
                <ol class="breadcrumb text-capitalize">
                    <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('unit_kerja.show') }}">Daftar Unit Kerja</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<!-- Content Header -->

<section class="content" style="margin: 0% 15% 0% 15%;">
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
                <h3 class="card-title">Edit Unit Kerja</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <form id="form" action="{{ route('unit_kerja.edit', $unitKerja->id_unit_kerja) }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Unit Utama</label>
                        <div class="col-md-9">
                            <select class="form-control" name="unit_utama_id">
                                <option value="{{ $unitKerja->unit_utama_id }}">
                                    {{ $unitKerja->unitUtama->id_unit_utama.' - '.$unitKerja->unitUtama->nama_unit_utama }}
                                </option>
                                @foreach ($unitUtama->where('id_unit_utama','!=',$unitKerja->unitUtama->id_unit_utama) as $row)
                                <option value="{{ $row->id_unit_utama }}">
                                    {{ $row->id_unit_utama.' - '.$row->nama_unit_utama }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Nama Unit Kerja</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="nama_unit_kerja" value="{{ $unitKerja->nama_unit_kerja }}" placeholder="Nama Unit Utama" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Penanggung Jawab</label>
                        <div class="col-md-9">
                            <select id="pegawai" name="pegawai_id" class="form-control" required>
                                <option value="">-- PILIH PEGAWAI --</option>
                                @foreach($pegawai as $row)
                                <option value="{{ $row->id_pegawai }}" <?php echo $unitKerja->pegawai_id == $row->id_pegawai ? 'selected' : '' ?>>
                                    {{ $row->nama_pegawai }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @if ($unitKerja->unit_utama_id == 4)
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Nilai Alokasi*</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control input-format" name="alokasi_anggaran" placeholder="Nilai Anggaran" value="{{ number_format($unitKerja->alokasi_anggaran, 0, ',', '.') }}" required>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary" onclick="confirmSubmit(event)">
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
        $("#pegawai").select2();
        $('.input-format').on('input', function() {
            // Menghapus karakter selain angka (termasuk tanda titik koma sebelumnya)
            var value = $(this).val().replace(/[^0-9]/g, '');

            // Format dengan menambahkan titik koma setiap tiga digit
            var formattedValue = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

            // Memasukkan nilai yang sudah diformat kembali ke input
            $(this).val(formattedValue);
        });
    });

    function confirmSubmit(event) {
        event.preventDefault();

        const form = document.getElementById('form');
        const inputs = form.querySelectorAll('select[required], input[required], textarea[required]');
        let isFormValid = true;

        inputs.forEach(input => {
            if (input.hasAttribute('required') && input.value.trim() === '') {
                input.style.borderColor = 'red';
                isFormValid = false;
            } else {
                input.style.borderColor = '';
            }
        });

        if (isFormValid) {
            Swal.fire({
                title: 'Simpan Perubahan ?',
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
