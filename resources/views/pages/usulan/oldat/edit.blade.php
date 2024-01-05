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
            <div class="card-header text-center font-weight-bold">
                Tambah Usulan Pekerjaan Olah Data BMN
            </div>
            <div class="card-header">
                <ul class="nav" id="tab" role="tablist">
                    @if ($usulan->form_id == 201)
                    <li class="nav-item mr-2">
                        <a class="btn btn-default border-secondary active bg-primary" id="pengadaan-tab" data-toggle="pill" href="#pengadaan" role="tab" ls="pengadaan" aria-selected="true">
                            Usulan Pengadaan
                        </a>
                    </li>
                    @else
                    <li class="nav-item">
                        <a class="btn btn-default border-secondary active bg-primary" id="perbaikan-tab" data-toggle="pill" href="#perbaikan" role="tab" aria-controls="perbaikan" aria-selected="false">
                            Usulan Perbaikan
                        </a>
                    </li>
                    @endif
                </ul>
            </div>
            <div class="tab-content" id="tabContent">
                @if ($usulan->form_id == 201)
                <!-- Usulan Pengadaan -->
                <div class="tab-pane fade active show" id="pengadaan" role="tabpanel" aria-labelledby="pengadaan-tab">

                    <form id="form-pengadaan" action="{{ route('usulan.update', ['form' => $form, 'id' => $id]) }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <label class="text-muted">Usulan Pengadaan Oldat BMN & Meubelair</label>
                            <h6 class="text-muted small">Mohon untuk melengkapi rencana pemakaian dan spesifikasi barang yang dibutuhkan.</h6>
                            <input type="hidden" name="kode_form" value="201">
                            <div class="form-group row mt-4">
                                <label class="col-md-2 col-form-label">Rencana Pengguna</label>
                                <div class="col-md-9">
                                    <textarea type="text" class="form-control" name="keterangan" placeholder="Contoh : Untuk operasional kantor">{{ $usulan->keterangan }}</textarea>
                                </div>
                            </div>
                            @foreach($usulan->usulanOldat as $i => $row)
                            <div class="form-group row mb-5">
                                <label class="col-md-12 text-muted">Pengadaan Barang {{ $i+1 }}</label>
                                <input type="hidden" name="id_detail[]" value="{{ $row->id_oldat }}">
                                <label class="col-md-2 mb-3 col-form-label">Nama Barang</label>
                                <div class="col-md-9 mb-3">
                                    <select type="text" class="form-control select-border-bottom select-barang" name="barang[]" required>
                                        <option value="">-- Pilih Barang -- </option>
                                        @foreach ($kategoriBarang as $subRow)
                                        <option value="{{ $subRow->kode_kategori }}" <?php echo $subRow->kode_kategori == $row->kategori_id ? 'selected' : '' ?>>
                                            {{ $subRow->kategori_barang }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <label class="col-md-2 col-form-label mb-3">Merk/Tipe</label>
                                <div class="col-md-9 mb-3">
                                    <input type="text" class="form-control" name="merk_tipe[]" placeholder="Contoh : Asus/Canon/Lenovo" value="{{ $row->merk_tipe }}" required>
                                </div>
                                <label class="col-md-2 col-form-label mb-3">Spesifikasi</label>
                                <div class="col-md-9 mb-3">
                                    <input class="form-control" name="spesifikasi[]" placeholder="Contoh: Lenovo, 4GB, HDD 1 TB" value="{{ $row->spesifikasi }}">
                                </div>
                                <label class="col-md-2 col-form-label mb-2">Jumlah</label>
                                <div class="col-md-2 mb-2">
                                    <input type="number" class="form-control text-center jumlah" name="jumlah[]" min="1" value="{{ $row->jumlah_pengadaan }}">
                                </div>
                                <label class="col-md-2 col-form-label mb-2">Estimasi Harga (Rp)</label>
                                <div class="col-md-5 mb-2">
                                    <input type="text" class="form-control input-format" name="estimasi_harga[]" placeholder="Estimasi Harga" value="{{ number_format($row->estimasi_harga, 0, ',', '.') }}">
                                </div>
                                <div class="col-md-2 mt-2">&ensp;</div>
                                <div class="col-md-5 mt-2">
                                    <label class="btn btn-danger btn-sm">
                                        <input type="hidden" name="hapus[{{ $i }}]" value="">
                                        <input type="checkbox" autocomplete="off" name="hapus[{{ $i }}]" value="true">
                                        <span class="align-middle small">Hapus</span>
                                    </label>
                                </div>
                            </div>
                            @endforeach
                            <div class="form-group row d-none section-item mb-5">
                                <label class="col-md-12 text-muted title">Pengadaan Barang {{ $i+1 }}</label>
                                <input type="hidden" name="id_detail[]" disabled>
                                <input type="hidden" name="hapus[]" value="" disabled>
                                <label class="col-md-2 col-form-label mb-3">Jenis Barang</label>
                                <div class="col-md-9 mb-3">
                                    <select type="text" class="form-control select2-barang" name="barang[]" required disabled>
                                        <option value="">-- Pilih Jenis Barang -- </option>
                                        @foreach ($kategoriBarang as $row)
                                        <option value="{{ $row->kode_kategori }}">{{ $row->kategori_barang }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <label class="col-md-2 col-form-label mb-3">Merk/Tipe</label>
                                <div class="col-md-9 mb-3">
                                    <input type="text" class="form-control" name="merk_tipe[]" placeholder="Contoh : Asus/Canon/Lenovo" disabled>
                                </div>
                                <label class="col-md-2 col-form-label mb-3">Spesifikasi</label>
                                <div class="col-md-9 mb-3">
                                    <input class="form-control" name="spesifikasi[]" placeholder="Contoh: Lenovo, 4GB, HDD 1 TB" disabled>
                                </div>
                                <label class="col-md-2 col-form-label mb-2">Jumlah</label>
                                <div class="col-md-2 mb-2">
                                    <input type="number" class="form-control text-center jumlah" name="jumlah[]" value="1" min="1" disabled>
                                </div>
                                <label class="col-md-2 col-form-label mb-2">Estimasi Harga (Rp)</label>
                                <div class="col-md-5 mb-2">
                                    <input type="text" class="form-control input-format" name="estimasi_harga[]" placeholder="Estimasi Harga" disabled>
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
                @else
                <!-- Usulan Perbaikan -->
                <div class="tab-pane fade active show" id="perbaikan" role="tabpanel" aria-labelledby="perbaikan-tab">
                    <form id="form-perbaikan" action="{{ route('usulan.update', ['form' => $form, 'id' => $id]) }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <label class="text-muted">Usulan Perbaikan Oldat BMN & Meubelair</label>
                            <h6 class="text-muted small">Mohon pastikan data barang sudah terinput di database Siporsat.</h6>

                            <input type="hidden" name="kode_form" value="202">
                            @foreach ($usulan->usulanOldat as $i => $row)
                            <div class="form-group row section-data mt-4">
                                <label class="col-md-12 text-muted">Perbaikan Barang {{ $i+1 }}</label>
                                <label class="col-md-2 col-form-label mb-2">Nama Barang</label>
                                <div class="col-md-9 col-form-label mb-2">
                                    <input type="hidden" name="id_detail[]" value="{{ $row->id_oldat }}">
                                    <select type="text" class="form-control select-barang" name="barang[]" required>
                                        <option value="">-- Pilih Barang -- </option>
                                        @foreach ($kategoriBarang as $subRow)
                                        <option value="{{ $subRow->id_kategori }}" <?php echo $subRow->kode_kategori == $row->barang->kategori_id ? 'selected' : '' ?>>
                                            {{ $subRow->kategori_barang }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <label class="col-md-2 col-form-label mb-3">Merk tipe</label>
                                <div class="col-md-9 mb-3">
                                    <select type="text" class="form-control select-merktipe" name="merk_tipe[]" required>
                                        @foreach ($merkBarang->where('kategori_id', $row->barang->kategori_id) as $subRow)
                                        <option value="{{ $subRow->id_barang }}">
                                            {{ $subRow->kategori_id.'.'.$subRow->nup.' - '.$subRow->merk_tipe }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <label class="col-md-2 col-form-label mb-3">Keterangan</label>
                                <div class="col-md-9 mb-3">
                                    <textarea class="form-control" name="spesifikasi[]" placeholder="Contoh: Lenovo, 4GB, HDD 1 TB" required>{{ $row->keterangan_kerusakan }}</textarea>
                                </div>
                                <div class="col-md-2 mt-2">&ensp;</div>
                                <div class="col-md-5 mt-2">
                                    <label class="btn btn-danger btn-sm">
                                        <input type="hidden" name="hapus[{{ $i }}]" value="">
                                        <input type="checkbox" autocomplete="off" name="hapus[{{ $i }}]" value="true">
                                        <span class="align-middle small">Hapus</span>
                                    </label>
                                </div>
                            </div>
                            @endforeach
                            <div class="form-group row section-perbaikan d-none mt-4 mb-5">
                                <label class="col-md-12 text-muted title">Perbaikan Barang {{ $i+1 }}</label>
                                <label class="col-md-2 col-form-label mb-2">Nama Barang</label>
                                <div class="col-md-9 mb-2">
                                    <input type="hidden" name="id_detail[]" disabled>
                                    <input type="hidden" name="hapus[]" value="" disabled>
                                    <select type="text" class="form-control select2-barang" name="barang[]" disabled>
                                        <option value="">-- Pilih Jenis Barang -- </option>
                                        @foreach ($kategoriBarang as $row)
                                        <option value="{{ $row->id_kategori }}">{{ $row->kategori_barang }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <label class="col-md-2 col-form-label mb-3">Merk tipe</label>
                                <div class="col-md-9 mb-3">
                                    <select type="text" class="form-control select2-merktipe" name="merk_tipe[]" disabled>
                                        <option value="">-- Pilih Merk Tipe Barang -- </option>
                                    </select>
                                </div>
                                <label class="col-md-2 col-form-label mb-3">Keterangan</label>
                                <div class="col-md-9 mb-3">
                                    <textarea class="form-control" name="spesifikasi[]" placeholder="Contoh: Lenovo, 4GB, HDD 1 TB" required disabled></textarea>
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
                @endif
            </div>
        </div>
    </div>
</section>

@section('js')
<script>
    $(function() {
        $(".kategori").select2()
        $(".select-barang").select2()
        $(".select-merktipe").select2()
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
        let index = <?php echo count($usulan->usulanOldat) ?>;
        $(document).on('click', '.btn-tambah-baris', function(e) {
            e.preventDefault();
            var container = $('.section-item');
            var templateRow = $('.section-item').first().clone();

            templateRow.find(':input').val('');
            templateRow.find(':input').removeAttr('disabled');
            templateRow.find('.jumlah').val('1');
            templateRow.find('.title').text('Pengadaan Barang ' + (index + container.length));
            $('.section-item:last').after(templateRow);
            templateRow.removeClass('d-none');
            toggleHapusBarisButton();

            templateRow.find('.input-format').on('input', function() {
                var value = $(this).val().replace(/[^0-9]/g, '');
                var formattedValue = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                $(this).val(formattedValue);
            });

            templateRow.find('.select2-barang').removeAttr('disabled').select2();
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
        $('.btn-hapus-baris').toggle($('.section-item').length > index);

        function toggleHapusBarisButton() {
            var container = $('.section-item');
            var btnHapusBaris = $('.btn-hapus-baris');
            btnHapusBaris.toggle(container.length > 1);
        }
    })
</script>

<!-- Form Perbaikan -->
<script>
    $(function() {
        let index = <?php echo count($usulan->usulanOldat) ?>;
        let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content')
        $('.select-barang').on('change', function() {
            var selectBarang = $(this).val();
            var barang = $(this).closest('.section-data').find('.select-merktipe');
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
            templateRow.find(':input').val('');
            templateRow.find('.jumlah').val('1');
            templateRow.find('.title').text('Perbaikan Barang ' + (index + container.length));
            $('.section-perbaikan:last').after(templateRow);
            templateRow.find(':input').removeAttr('disabled');
            templateRow.removeClass('d-none');
            toggleHapusBarisButton();

            templateRow.find('.input-format').on('input', function() {
                var value = $(this).val().replace(/[^0-9]/g, '');
                var formattedValue = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                $(this).val(formattedValue);
            });

            templateRow.find('.select2-barang').removeAttr('disabled').select2();
            templateRow.find('.select2-merktipe').removeAttr('disabled').select2();

            $('.select2-barang').on('change', function() {
                var selectedJenisBarang = $(this).val();
                var barang = $(this).closest('.section-perbaikan').find('.select2-merktipe');
                barang.empty();

                $.ajax({
                    url: "{{ route('oldat.select') }}",
                    method: 'POST',
                    data: {
                        jenis_barang: selectedJenisBarang
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
    })
</script>
@endsection


@endsection
