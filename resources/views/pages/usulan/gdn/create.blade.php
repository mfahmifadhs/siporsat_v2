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
                    <li class="breadcrumb-item">Tambah Usulan</li>
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
            <div class="card-header text-center font-weight-bold">
                Tambah Usulan Pemeliharaan Gedung & Bangunan
            </div>
            <form id="form" action="{{ route('usulan.create', ['form' => $form, 'id' => $id]) }}" method="POST">
                @csrf
                <div class="card-body">
                    <label class="text-muted">Usulan Pekerjaan Pemeliharaan Gedung dan Bangunan</label>
                    <h6 class="text-muted small">Mohon untuk mengisi lokasi perbaikan dan deskripsi pekerjaan secara detail.</h6>

                    <input type="hidden" name="kode_form" value="401">

                    <div class="form-group row section-item mt-4">
                        <label class="col-md-12 mb-2 title text-muted">Pekerjaan 1</label>
                        <label class="col-md-2 col-form-label mb-3">Lokasi Perbaikan</label>
                        <div class="col-md-9 mb-3">
                            <input type="text" class="form-control" name="judul[]" max="100" placeholder="Contoh : Ruang Rapat RTH lantai 4 Gedung Sujudi" required>
                        </div>
                        <label class="col-md-2 col-form-label mb-3">Jenis Perbaikan</label>
                        <div class="col-md-3 mb-3">
                            <select name="jenis_perbaikan[]" class="form-control jenis-bperbaikan" required>
                                <option value="">-- Pilih Jenis Perbaikan</option>
                                <option value="AR">Arsitektural (AR)</option>
                                <option value="LT">Landscape & Tata Graha (LT)</option>
                                <option value="ME">Mekanikal Engineering (ME)</option>
                                <option value="ST">Struktural (ST)</option>
                            </select>
                        </div>
                        <label class="col-md-2 col-form-label mb-3 text-center">Bidang Perbaikan </label>
                        <div class="col-md-4 mb-3">
                            <select name="bidang_perbaikan[]" class="form-control bidang-perbaikan" required>
                                <option value="">-- Pilih Bidang Perbaikan</option>
                            </select>
                        </div>
                        <label class="col-md-2 col-form-label mb-3">Deskripsi</label>
                        <div class="col-md-9 mb-3">
                            <textarea name="deskripsi[]" class="form-control" rows="10" required
                            placeholder="Deskripsi kerusakan atau pekerjaan (contoh : pergantian walpaper/kerusakan pada dinding)"></textarea>
                        </div>
                        <label class="col-md-2 col-form-label mb-3">Keterangan</label>
                        <div class="col-md-9 mb-3">
                            <textarea class="form-control" name="keterangan_pekerjaan[]" placeholder="Keterangan tambahan"></textarea>
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
                        <i class="fas fa-paper-plane fa-1x"></i> <b>Submit</b>
                    </button>
                </div>
            </form>
        </div>


    </div>
</section>

@section('js')
<script>
    let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content')
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

    $('.jenis-bperbaikan').on('change', function() {
        var selectedJenisPerbaikan = $(this).val();
        var bidangPerbaikanSelect = $(this).closest('.section-item').find('.bidang-perbaikan');
        console.log(bidangPerbaikanSelect)
        bidangPerbaikanSelect.empty();

        $.ajax({
            url: "{{ route('gdn.bperbaikan') }}",
            method: 'POST',
            data: {
                jenis_perbaikan: selectedJenisPerbaikan
            },
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN
            },
            success: function(response) {
                // Process the response and populate the bidang_perbaikan options
                if (response && response.length > 0) {
                    bidangPerbaikanSelect.append('<option value="">-- Pilih Bidang Perbaikan</option>');
                    response.forEach(function(item) {
                        bidangPerbaikanSelect.append('<option value="' + item.id + '">' + item.text + '</option>');
                    });
                } else {
                    // If no data is returned, show a default option
                    bidangPerbaikanSelect.append('<option value="">-- Pilih Bidang Perbaikan</option>');
                }
            },
            error: function(error) {
                // Handle the error if the AJAX request fails
                console.error('Error fetching bidang perbaikan data:', error);
                bidangPerbaikanSelect.append('<option value="">-- Pilih Bidang Perbaikan</option>');
            }
        })

    })

    $(document).on('click', '.btn-tambah-baris', function(e) {
        e.preventDefault();
        var container = $('.section-item');
        var templateRow = $('.section-item').first().clone();
        templateRow.find(':input').val('');
        templateRow.find('.jumlah').val('1');
        templateRow.find('.title').text('Pekerjaan ' + (container.length + 1));
        $('.section-item:last').after(templateRow);
        toggleHapusBarisButton();

        templateRow.find('.input-format').on('input', function() {
            var value = $(this).val().replace(/[^0-9]/g, '');
            var formattedValue = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            $(this).val(formattedValue);
        });

        $('.jenis-bperbaikan').on('change', function() {
            var selectedJenisPerbaikan = $(this).val();
            var bidangPerbaikanSelect = $(this).closest('.section-item').find('.bidang-perbaikan');
            console.log(bidangPerbaikanSelect)
            bidangPerbaikanSelect.empty();

            $.ajax({
                url: "{{ route('gdn.bperbaikan') }}",
                method: 'POST',
                data: {
                    jenis_perbaikan: selectedJenisPerbaikan
                },
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': CSRF_TOKEN
                },
                success: function(response) {
                    // Process the response and populate the bidang_perbaikan options
                    if (response && response.length > 0) {
                        bidangPerbaikanSelect.append('<option value="">-- Pilih Bidang Perbaikan</option>');
                        response.forEach(function(item) {
                            bidangPerbaikanSelect.append('<option value="' + item.id + '">' + item.text + '</option>');
                        });
                    } else {
                        // If no data is returned, show a default option
                        bidangPerbaikanSelect.append('<option value="">-- Pilih Bidang Perbaikan</option>');
                    }
                },
                error: function(error) {
                    // Handle the error if the AJAX request fails
                    console.error('Error fetching bidang perbaikan data:', error);
                    bidangPerbaikanSelect.append('<option value="">-- Pilih Bidang Perbaikan</option>');
                }
            })

        })
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
                title: 'Tambah Usulan ?',
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
