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
                    <li class="breadcrumb-item"><a href="{{ route($form.'.home') }}">Beranda</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('usulan.show', $form) }}">Daftar Usulan</a></li>
                    <li class="breadcrumb-item">Edit Usulan</li>
                </ol>
            </div>
            <div class="col-sm-6 mt-3">
                <div class="form-group text-right">
                    <a href="{{ route('usulan.show', $form) }}" class="btn btn-add">
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

        <div class="card" style="border-radius: 10px;">
            <form id="form" action="{{ route('usulan.update', ['form' => 'ukt', 'id' => $id]) }}" method="POST">
                @csrf
                <div class="card-header text-center font-weight-bold">
                    Edit Usulan Pekerjaan Urusan Kerumahtanggaan
                </div>
                <div class="card-header">
                    <label class="text-muted">Usulan Pekerjaan Urusan Kerumahtanggaan</label>
                    <h6 class="text-muted small">
                        Pekerjaan yang berkaitan dengan urusan kerumahtanggaan, seperti pembelian
                        sewa tanaman, akun zoom dan lain sebagainya
                    </h6>
                </div>
                <div class="card-header">
                    <input type="hidden" name="kode_form" value="501">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="row">
                                <label class="col-md-4">ID</label>:
                                <div class="col-md-7">{{ $id }}</div>
                                <label class="col-md-4">Tanggal Usulan</label>:
                                <div class="col-md-7">
                                    {{ \Carbon\carbon::parse($usulan->tanggal_usulan)->isoFormat('DD MMMM Y') }}
                                </div>
                                <label class="col-md-4">Nomor Surat</label>:
                                <div class="col-md-7">{{ $usulan->nomor_usulan }}</div>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="row">
                                <label class="col-md-4">Pengusul</label>:
                                <div class="col-md-7">{{ $usulan->pegawai->nama_pegawai }}</div>
                                <label class="col-md-4">Jabatan</label>:
                                <div class="col-md-7">{{ $usulan->pegawai->nama_jabatan }}</div>
                                <label class="col-md-4">Unit Kerja</label>:
                                <div class="col-md-7">{{ $usulan->pegawai->unitKerja->nama_unit_kerja }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-group row">

                        @if (Auth::user()->role_id == 2)
                        <label class="col-md-12 col-form-label mb-4 text-muted">Informasi Usulan</label>
                        <label class="col-md-2 mb-3">Tanggal Usulan</label>
                        <div class="col-md-9 col-form-label mb-3">
                            <input type="date" class="form-control" name="tanggal_usulan" value="{{ $usulan->tanggal_usulan }}">
                        </div>
                        <label class="col-md-2 mb-3">Nomor Usulan</label>
                        <div class="col-md-9 mb-3">
                            <input type="text" class="form-control" name="nomor_usulan" value="{{ $usulan->nomor_usulan }}" readonly>
                        </div>
                        @endif


                        @foreach($usulan->usulanUkt as $i => $row)
                        <label class="col-md-12 mb-2 title text-muted">Pekerjaan {{ $i + 1 }}</label>
                        <label class="col-md-2 col-form-label mb-2">Nama Pekerjaan</label>
                        <div class="col-md-9 mb-3">
                            <input type="hidden" name="id_detail[]" value="{{ $row->id_ukt }}">
                            <textarea class="form-control" rows="2" name="judul[]" required>{{ $row->judul_pekerjaan }}</textarea>
                        </div>
                        <label class="col-md-2 col-form-label mb-3">Deskripsi</label>
                        <div class="col-md-9 mb-3">
                            <textarea name="deskripsi[]" class="form-control" rows="10" required>{{ $row->deskripsi }}</textarea>
                        </div>
                        <label class="col-md-2 col-form-label mb-3">Keterangan</label>
                        <div class="col-md-9 mb-3">
                            <textarea class="form-control" rows="2" name="keterangan[]">{{ $row->keterangan }}</textarea>
                        </div>
                        <div class="col-md-2 mt-2">&ensp;</div>
                        <div class="col-md-5 mt-2">
                            <label class="btn btn-danger btn-sm">
                                <input type="hidden" name="hapus[{{ $i }}]" value="">
                                <input type="checkbox" autocomplete="off" name="hapus[{{ $i }}]" value="true">
                                <span class="align-middle small">Hapus</span>
                            </label>
                        </div>
                        <div class="col-md-12">
                            <hr>
                        </div>
                        @endforeach
                    </div>

                    <div class="form-group row section-item d-none">
                        <label class="col-md-12 mb-2 title text-muted">Pekerjaan 1</label>
                        <label class="col-md-2 col-form-label mb-3">Nama Pekerjaan</label>
                        <div class="col-md-9 mb-3">
                            <input type="hidden" name="id_detail[]" value="" disabled>
                            <input type="hidden" name="hapus[]" value="" disabled>
                            <textarea type="text" class="form-control" name="judul[]" max="100" required disabled placeholder="Contoh : Pembelian Sewa Zoom (Max. 100 Karakter)"></textarea>
                        </div>
                        <label class="col-md-2 col-form-label mb-3">Deskripsi</label>
                        <div class="col-md-9 mb-3">
                            <textarea name="deskripsi[]" class="form-control" rows="10" required disabled placeholder="Contoh : Sewa zoom meeting untuk 1 tahun dengan kapasitas 100 orang"></textarea>
                        </div>
                        <label class="col-md-2 col-form-label mb-3">Keterangan</label>
                        <div class="col-md-9 mb-3">
                            <textarea class="form-control" name="keterangan[]" disabled placeholder="Nominal/Nomor Surat/Dan lainnya"></textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-2 mt-2">&ensp;</div>
                        <div class="col-md-5 mt-2">
                            <a href="" class="small btn btn-primary btn-xs btn-tambah-baris">
                                <i class="fas fa-plus"></i> Tambah Baris
                            </a>
                            <a href="" class="small btn btn-danger btn-xs btn-hapus-baris">
                                <i class="fas fa-times"></i> Hapus Baris
                            </a>
                        </div>
                    </div>

                </div>
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary" onclick="confirmSubmit(event)">
                        <i class="fas fa-save fa-1x"></i> <b>Simpan</b>
                    </button>
                </div>
        </div>
        </form>


    </div>
</section>
<div id="usulan-count" data-usulan-count="{{ $usulan->usulanUkt->count() }}"></div>
@section('js')
<script>
    $(function() {
        $(".kategori").select2()
        $("#table-show").DataTable({
            "responsive": false,
            "lengthChange": true,
            "autoWidth": true,
            "info": true,
            "paging": true,
            "searching": true
        }).buttons().container().appendTo('#table-show_wrapper .col-md-6:eq(0)')

        $('.input-format').on('input', function() {
            // Menghapus karakter selain angka (termasuk tanda titik koma sebelumnya)
            var value = $(this).val().replace(/[^0-9]/g, '');

            // Format dengan menambahkan titik koma setiap tiga digit
            var formattedValue = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

            // Memasukkan nilai yang sudah diformat kembali ke input
            $(this).val(formattedValue);
        });
    })

    $(document).on('click', '.btn-tambah-baris', function(e) {
        e.preventDefault();
        var total = parseInt(document.getElementById('usulan-count').getAttribute('data-usulan-count'));
        var container = $('.section-item');
        var templateRow = $('.section-item').first().clone();
        templateRow.find(':input').val('');
        templateRow.find(':input').removeAttr('disabled');
        templateRow.find('.jumlah').val('1');
        templateRow.find('.title').text('Pekerjaan ' + (total + container.length));
        templateRow.removeClass('d-none');
        $('.section-item:last').after(templateRow);
        toggleHapusBarisButton();

        templateRow.find('.input-format').on('input', function() {
            var value = $(this).val().replace(/[^0-9]/g, '');
            var formattedValue = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            $(this).val(formattedValue);
        });
    });

    $(document).on('click', '.btn-hapus-baris', function(e) {
        e.preventDefault();
        var container = $('.section-item');
        if (container.length > 1) {
            $(this).closest('.form-group').prev('.section-item').remove();
            toggleHapusBarisButton();
        } else {
            alert('Minimal harus ada satu baris.');
        }
    });

    // Inisialisasi tombol "Hapus Baris" saat halaman dimuat
    $('.btn-hapus-baris').toggle($('.section-item').length > 1);

    function toggleHapusBarisButton() {
        var container = $('.section-item');
        var btnHapusBaris = $('.btn-hapus-baris');
        btnHapusBaris.toggle(container.length > 1);
    }

    function confirmSubmit(event) {
        event.preventDefault();
        const form = document.getElementById('form');
        const inputs = form.querySelectorAll('select[required]:not(:disabled), input[required]:not(:disabled), textarea[required]:not(:disabled)');
        let isFormValid = true;

        inputs.forEach(input => {
            if (input.hasAttribute('required') && input.value.trim() === '') {
                input.style.borderColor = 'red';
                isFormValid = false;
                console.log(true)
            } else {
                input.style.borderColor = '';
                console.log(false)
            }
        });

        if (isFormValid) {
            Swal.fire({
                title: 'Simpan Perubahan',
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
