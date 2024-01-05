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
                Tambah Usulan Pekerjaan Olah Data BMN
            </div>
            <div class="card-header">
                <ul class="nav" id="tab" role="tablist">
                    <li class="nav-item mr-2">
                        <a class="btn btn-default border-secondary bg-primary active" id="pengadaan-tab" data-toggle="pill" href="#pengadaan" role="tab" ls="pengadaan" aria-selected="true">
                            Usulan Pengadaan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-default mr-2 border-secondary" id="perbaikan-tab" data-toggle="pill" href="#perbaikan" role="tab" aria-controls="perbaikan" aria-selected="false">
                            Usulan Perbaikan
                        </a>
                    </li>
                </ul>
            </div>
            <div class="tab-content" id="tabContent">
                <!-- Usulan Pengadaan -->
                <div class="tab-pane fade active show" id="pengadaan" role="tabpanel" aria-labelledby="pengadaan-tab">
                    <form id="form-pengadaan" action="{{ route('usulan.create', ['form' => $form, 'id' => $id]) }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <label class="text-muted">Usulan Pengadaan Oldat BMN & Meubelair</label>
                            <h6 class="text-muted small">Mohon untuk melengkapi rencana pemakaian dan spesifikasi barang yang dibutuhkan.</h6>

                            <input type="hidden" name="kode_form" value="201">
                            <div class="form-group row mt-4">
                                <label class="col-md-2 col-form-label">Rencana Pengguna</label>
                                <div class="col-md-9 mb-2">
                                    <textarea type="text" class="form-control" name="keterangan" placeholder="Contoh : Untuk operasional kantor"></textarea>
                                </div>
                            </div>
                            <div class="form-group row mt-2">
                                <label class="col-md-12 text-muted">Pengajuan Barang 1</label>
                                <label class="col-md-2 col-form-label">Jenis Barang</label>
                                <div class="col-md-9">
                                    <select type="text" class="form-control select" name="barang[]" required>
                                        <option value="">-- Pilih Jenis Barang -- </option>
                                        @foreach ($kategoriBarang as $row)
                                        <option value="{{ $row->kode_kategori }}">{{ $row->kategori_barang }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row section-item mt-4">
                                <label class="col-md-12 text-muted title d-none">Pengajuan Barang 1</label>
                                <label class="col-md-2 mb-3 col-form-label d-none titleBarang">Jenis Barang</label>
                                <div class="col-md-9 mb-3 d-none pilBarang">
                                    <select type="text" class="form-control select2" name="barang[]" disabled required>
                                        <option value="">-- Pilih Jenis Barang -- </option>
                                        @foreach ($kategoriBarang as $row)
                                        <option value="{{ $row->kode_kategori }}">{{ $row->kategori_barang }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <label class="col-md-2 mb-3col-form-label">Merk/Tipe</label>
                                <div class="col-md-9 mb-3">
                                    <input type="text" class="form-control" name="merk_tipe[]" placeholder="Contoh : Asus/Canon/Lenovo">
                                </div>
                                <label class="col-md-2 mb-3 col-form-label">Spesifikasi</label>
                                <div class="col-md-9 mb-3">
                                    <input class="form-control" name="spesifikasi[]" placeholder="Contoh: Lenovo, 4GB, HDD 1 TB">
                                </div>
                                <label class="col-md-2 mb-3 col-form-label">Jumlah</label>
                                <div class="col-md-2 mb-3">
                                    <input type="number" class="form-control text-center jumlah" name="jumlah[]" value="1" min="1">
                                </div>
                                <label class="col-md-2 mb-3 col-form-label">Estimasi Harga (Rp)</label>
                                <div class="col-md-5 mb-3">
                                    <input type="text" class="form-control input-format" name="estimasi_harga[]" placeholder="Estimasi Harga">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-2">&ensp;</div>
                                <div class="col-md-5">
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
                            <button type="submit" class="btn btn-primary" onclick="confirmSubmit(event, 'pengadaan')">
                                <i class="fas fa-paper-plane"></i> Submit
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Usulan Perbaikan -->
                <div class="tab-pane fade" id="perbaikan" role="tabpanel" aria-labelledby="perbaikan-tab">
                    <form id="form-perbaikan" action="{{ route('usulan.create', ['form' => $form, 'id' => $id]) }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <label class="text-muted">Usulan Perbaikan Oldat BMN & Meubelair</label>
                            <h6 class="text-muted small">Mohon pastikan data barang sudah terinput di database Siporsat.</h6>

                            <input type="hidden" name="kode_form" value="202">
                            <div class="form-group row mt-4">
                                <label class="col-md-12 text-muted">Perbaikan Barang 1</label>
                                <label class="col-md-2 col-form-label mb-3">Nama Barang</label>
                                <div class="col-md-9 mb-3">
                                    <select type="text" class="form-control select-barang" name="barang[]" style="width: 100%;" required>
                                        <option value="">-- Pilih Barang -- </option>
                                        @foreach ($kategoriBarang as $row)
                                        <option value="{{ $row->id_kategori }}">{{ $row->kategori_barang }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <label class="col-md-2 col-form-label">Merk tipe</label>
                                <div class="col-md-9">
                                    <select type="text" class="form-control select-merktipe" name="merktipe[]" required>
                                        <option value="">-- Pilih Merk Tipe Barang -- </option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row section-perbaikan mt-4">
                                <label class="col-md-12 text-muted d-none title">Perbaikan Barang</label>
                                <label class="col-md-2 col-form-label mb-2 d-none">Nama Barang</label>
                                <div class="col-md-9 mb-2 d-none">
                                    <select type="text" class="form-control select-border-bottom select2-barang" name="barang[]" required disabled>
                                        <option value="">-- Pilih Jenis Barang -- </option>
                                        @foreach ($kategoriBarang as $row)
                                        <option value="{{ $row->id_kategori }}">{{ $row->kategori_barang }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <label class="col-md-2 col-form-label mb-3 d-none">Merk tipe</label>
                                <div class="col-md-9 mb-3 d-none">
                                    <select type="text" class="form-control select-border-bottom select2-merktipe" name="merktipe[]" required disabled>
                                        <option value="">-- Pilih Merk Tipe Barang -- </option>
                                    </select>
                                </div>
                                <label class="col-md-2 col-form-label mb-3">Keterangan</label>
                                <div class="col-md-9 mb-3">
                                    <textarea class="form-control" name="spesifikasi[]" placeholder="Contoh : Penambahan memori/LCD Pecah" required></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-2">&ensp;</div>
                                <div class="col-md-5">
                                    <a href="" class="small btn btn-primary btn-xs btn-tambah-baris-perbaikan">
                                        <i class="fas fa-plus"></i> Tambah Baris
                                    </a>
                                    <a href="" class="small btn btn-danger btn-xs btn-hapus-baris-perbaikan">
                                        <i class="fas fa-times"></i> Hapus Baris
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <button type="submit" class="btn btn-primary" onclick="confirmSubmit(event, 'perbaikan')">
                                <i class="fas fa-paper-plane"></i> Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</section>

@section('js')
<script>
    $(function() {
        $(".kategori").select2()
        $(".select").select2()
        $(".select-barang").select2()

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

    const pengadaanTab = document.getElementById('pengadaan-tab');
    const perbaikanTab = document.getElementById('perbaikan-tab');

    pengadaanTab.addEventListener('click', function() {
        pengadaanTab.classList.add('bg-primary');
        perbaikanTab.classList.remove('bg-primary');
    });

    perbaikanTab.addEventListener('click', function() {
        perbaikanTab.classList.add('bg-primary');
        pengadaanTab.classList.remove('bg-primary');
    });

    function confirmSubmit(event, aksi) {
        event.preventDefault();

        const form = document.getElementById('form-' + aksi);
        const requiredInputs = form.querySelectorAll('input[required]:not(:disabled), select[required]:not(:disabled), textarea[required]:not(:disabled)');

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
                title: 'Tambah Usulan?',
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
                title: 'Error',
                text: 'Ada input yang diperlukan yang belum diisi.',
                icon: 'error'
            });
        }
    }
</script>

<!-- Form Pengadaan -->
<script>
    $(function() {
        $(document).on('click', '.btn-tambah-baris', function(e) {
            e.preventDefault();
            var container = $('.section-item');
            var templateRow = $('.section-item').first().clone();
            templateRow.find(':input').val('');
            templateRow.find('.jumlah').val('1');
            templateRow.find('.title').text('Pengajuan Barang ' + (container.length + 1)).removeClass('d-none');
            templateRow.find('.titleBarang').removeClass('d-none')
            templateRow.find('.pilBarang').removeClass('d-none')
            $('.section-item:last').after(templateRow);
            toggleHapusBarisButton();

            templateRow.find('.input-format').on('input', function() {
                var value = $(this).val().replace(/[^0-9]/g, '');
                var formattedValue = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                $(this).val(formattedValue);
            });

            templateRow.find('.select2').removeAttr('disabled').select2();
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

    })
</script>

<!-- Perbaikan -->
<script>
    let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content')
    $('.select-barang').on('change', function() {
        var selectBarang = $(this).val();
        var barang = $('.select-merktipe');
        barang.empty();
        $.ajax({
            url: "{{ route('oldat.select') }}",
            method: 'POST',
            data: {
                jenis_barang: selectBarang
            },
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN
            },
            success: function(response) {
                if (response && response.length > 0) {
                    barang.select2()
                    barang.append('<option value="">-- Pilih Merk Tipe Barang</option>');
                    response.forEach(function(item) {
                        barang.append('<option value="' + item.id + '">' + item.text + '</option>');
                    });
                } else {
                    barang.append('<option value="">-- Pilih Merk Tipe Barang</option>');
                }
            },
            error: function(error) {
                console.error('Error fetching bidang perbaikan data:', error);
                barang.append('<option value="">-- Pilih Merk Tipe Barang</option>');
            }
        })

    })

    $(document).on('click', '.btn-tambah-baris-perbaikan', function(e) {
        e.preventDefault();
        var container = $('.section-perbaikan');
        var templateRow = $('.section-perbaikan').first().clone();
        templateRow.find('.d-none').removeClass('d-none');
        templateRow.find('[disabled]').removeAttr('disabled');
        templateRow.find(':input').val('');
        templateRow.find('.jumlah').val('1');
        templateRow.find('.title').text('Perbaikan Barang ' + (container.length + 1));
        $('.section-perbaikan:last').after(templateRow);
        toggleHapusBarisButton();

        templateRow.find('.input-format').on('input', function() {
            var value = $(this).val().replace(/[^0-9]/g, '');
            var formattedValue = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            $(this).val(formattedValue);
        });

        templateRow.find('.select2-barang').removeAttr('disabled').select2();
        templateRow.find('.select2-merktipe').removeAttr('disabled').select2();

        $('.select2-barang').on('change', function() {
            var selectBarang = $(this).val();
            var barang = $(this).closest('.section-perbaikan').find('.select2-merktipe');
            barang.empty();

            $.ajax({
                url: "{{ route('oldat.select') }}",
                method: 'POST',
                data: {
                    jenis_barang: selectBarang
                },
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': CSRF_TOKEN
                },
                success: function(response) {
                    if (response && response.length > 0) {
                        barang.append('<option value="">-- Pilih Merk Tipe Barang</option>');
                        response.forEach(function(item) {
                            barang.append('<option value="' + item.id + '">' + item.text + '</option>');
                        });
                    } else {
                        barang.append('<option value="">-- Pilih Merk Tipe Barang</option>');
                    }
                },
                error: function(error) {
                    console.error('Error fetching bidang perbaikan data:', error);
                    barang.append('<option value="">-- Pilih Merk Tipe Barang</option>');
                }
            })

        })
    });

    $(document).on('click', '.btn-hapus-baris-perbaikan', function(e) {
        e.preventDefault();
        var container = $('.section-perbaikan');
        if (container.length > 1) {
            $(this).closest('.form-group').prev('.section-perbaikan').remove();
            toggleHapusBarisButton();
        } else {
            alert('Minimal harus ada satu baris.');
        }
    });

    // Inisialisasi tombol "Hapus Baris" saat halaman dimuat
    $('.btn-hapus-baris-perbaikan').toggle($('.section-perbaikan').length > 1);

    function toggleHapusBarisButton() {
        var container = $('.section-perbaikan');
        var btnHapusBaris = $('.btn-hapus-baris-perbaikan');
        btnHapusBaris.toggle(container.length > 1);
    }
</script>
@endsection


@endsection
