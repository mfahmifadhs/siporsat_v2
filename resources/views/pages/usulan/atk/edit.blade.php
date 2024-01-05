@extends('layout.app')

@section('content')

<!-- Content Header -->
<section class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-8">
                <h1 class="text-capitalize font-weight-bold">Edit Usulan <small class="text-sm">({{ $usulan->id_usulan }})</small></h1>
                <h4 class="small text-muted mt-2">Harga barang dapat berubah, harga realisasi dapat dilihat </h4>
                <h4 class="small text-muted mt-2">Pada detail pengajuan ATK, pada kolom <b>Harga Realisasi.</b></h4>
            </div>
            <div class="col-sm-4 mt-3">
                <div class="form-group text-right">
                    <a href="{{ route('atk.home') }}" class="btn btn-add">
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

        <form id="form" action="{{ route('usulan.update', ['form' => $form, 'id' => $id]) }}" method="POST">
            @csrf
            <input type="hidden" name="kode_form" value="301">
            <div class="card">
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-12 form-group">
                            <span class="text-red" style="font-size: 10px;">
                                *Pengajuan bulan berikut, maks. tanggal 20 bulan berjalan
                            </span>
                        </div>
                        @if (Auth::user()->role_id == 4)
                        <div class="col-md-3 form-group">
                            <label>Tanggal Usulan</label>
                            <input type="date" class="form-control form-control-sm" name="tanggal_usulan" value="{{ \Carbon\carbon::parse($usulan->tanggal_usulan)->isoFormat('YYYY-MM-DD') }}" readonly>
                            <input type="hidden" name="nomor_usulan" value="{{ $usulan->nomor_usulan }}">
                        </div>
                        @else
                        <div class="col-md-4 form-group">
                            <label>No. Usulan</label>
                            <input type="text" class="form-control form-control-sm bg-light" name="nomor_usulan" value="{{ $usulan->nomor_usulan }}" readonly>
                        </div>

                        <div class="col-md-4 form-group">
                            <label>Tanggal Usulan</label>
                            <input type="date" class="form-control form-control-sm" name="tanggal_usulan" value="{{ \Carbon\carbon::parse($usulan->tanggal_usulan)->isoFormat('YYYY-MM-DD') }}">
                        </div>
                        @endif
                        <div class="col-md-4 form-group">
                            <label>Rencana Pengguna</label>
                            <input type="text" name="keterangan" class="form-control form-control-sm" rows="5" placeholder="Permintaan ATK bulan September 2023" required value="{{ $usulan->keterangan }}">
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="text-right mb-2">
                        <a href="" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#add-item">
                            <i class="fas fa-plus-circle"></i> Tambah
                        </a>
                        <a href="" class="btn btn-danger btn-xs">
                            <i class="fas fa-sync"></i> Reload
                        </a>
                    </div>
                    <table class="table table-bordered text-sm">
                        <thead class="text-center">
                            <tr>
                                <th style="width: 0%;">No</th>
                                <th style="width: 8%;">Foto</th>
                                <th style="width: 27%;">Nama Barang</th>
                                <th style="width: 15%;">Jumlah Permintaan</th>
                                <th style="width: 10%;">Satuan</th>
                                <th style="width: 5%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($usulan->usulanAtk as $row)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td class="text-center">
                                    @if ($row->foto_atk)
                                    <img src="{{ asset('storage/files/foto_atk/'. $row->foto_atk) }}" class="img-fluid img-size-50" alt="">
                                    @else
                                    <img src="https://cdn-icons-png.flaticon.com/512/679/679821.png" class="img-fluid img-size-50" alt="">
                                    @endif
                                </td>
                                <td>
                                    <select name="atk[]" class="form-control form-control-sm" style="width: 100%;">
                                        @foreach ($atk as $subRow)
                                        <option value="{{ $subRow->id_atk }}" <?php echo $subRow->id_atk == $row->atk_id ? 'selected' : '' ?>>
                                            {{ $subRow->deskripsi }}
                                        </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <div class="input-group mt-1">
                                        <div class="input-group-append">
                                            <div class="input-group-text rounded-left" style="height: 84%;">
                                                <a type="button" class="min-button" data-id="disetujui-{{ $row->id_permintaan }}">
                                                    <i class="fas fa-minus-circle" aria-hidden="true"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <input type="text" class="form-control form-control-sm text-center bg-white number" id="disetujui-{{ $row->id_permintaan }}" name="disetujui[]" value="{{ $row->jumlah_permintaan }}" min="1">

                                        <!-- @if ($usulan->status_proses_id == 101)
                                        <input type="text" class="form-control form-control-sm text-center bg-white number" id="disetujui-{{ $row->id_permintaan }}" name="disetujui[]" value="{{ $row->jumlah_permintaan }}" min="1">
                                        <input type="hidden" class="form-control form-control-sm text-center bg-white number" name="permintaan[]" value="{{ $row->jumlah_permintaan }}" min="1">
                                        @else
                                        <input type="text" class="form-contro form-control-sml text-center bg-white number" id="disetujui-{{ $row->id_permintaan }}" name="disetujui[]" value="{{ $row->jumlah_disetujui }}" min="1">
                                        @endif -->
                                        <div class="input-group-append">
                                            <div class="input-group-text rounded-left" style="height: 84%;">
                                                <a type="button" class="add-button" data-id="disetujui-{{ $row->id_permintaan }}">
                                                    <i class="fas fa-plus-circle" aria-hidden="true"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <input type="text" class="form-control form-control-sm text-center bg-white" value="{{ $row->atk->satuanPick->first()?->satuan }}" readonly>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('atk.keranjang.remove', $row->id_permintaan) }}" class="btn btn-danger" onclick="confirmSubmit(event)">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary btn-sm" onclick="confirmSubmit(event)">
                        <i class="fas fa-paper-plane"></i> Submit
                    </button>
                </div>
            </div>
        </form>
    </div>
</section>

<!-- Modal -->
<form id="form-new" action="{{ route('atk.update', $id) }}" method="POST">
    @csrf
    <div class="modal fade" id="add-item" tabindex="-1" role="dialog" aria-labelledby="add-item" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="row col-md-12">
                        <div class="col-md-12">
                            <h6 class="modal-title mt-1" id="add-item">
                                <b>Tambah Barang</b> <br>
                                <small class="text-danger">Setelah tambah item baru, silahkan pilih reload agar item yang ditambahkan tampil.</small>
                            </h6>
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Barang</label>
                        <select name="atk[]" class="form-control form-control-sm" style="width: 100%;">
                            @foreach ($atk as $subRow)
                            <option value="{{ $subRow->id_atk }}">
                                {{ $subRow->deskripsi }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Jumlah Permintaan</label>
                        <div class="input-group mt-1">
                            <div class="input-group-append">
                                <div class="input-group-text rounded-left" style="height: 84%;">
                                    <a type="button" class="min-button" data-id="new-{{ $row->id_permintaan }}">
                                        <i class="fas fa-minus-circle" aria-hidden="true"></i>
                                    </a>
                                </div>
                            </div>
                            <input type="text" class="form-control form-control-sm text-center bg-white number" id="new-{{ $row->id_permintaan }}" name="disetujui[]" value="1" min="1">
                            <div class="input-group-append">
                                <div class="input-group-text rounded-left" style="height: 84%;">
                                    <a type="button" class="add-button" data-id="new-{{ $row->id_permintaan }}">
                                        <i class="fas fa-plus-circle" aria-hidden="true"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                        <i class="fas fa-times-circle"></i> Tutup
                    </button>
                    <button type="submit" class="btn btn-primary btn-sm" onclick="confirmAdd(event)">
                        <i class="fas fa-plus-circle"></i> Tambah
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- <div class="modal fade" id="add-item" tabindex="-1" role="dialog" aria-labelledby="add-item" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="row col-md-12">
                    <div class="col-md-8">
                        <h6 class="modal-title mt-1" id="add-item">
                            <b>Tambah Barang</b> <br>
                            <small class="text-danger">Setelah tambah item baru, silahkan pilih reload agar item yang ditambahkan tampil.</small>
                        </h6>
                    </div>
                    <div class="col-md-4 mt-2">
                        <input type="text" id="searchInput" class="form-control" placeholder="Cari Barang..." onkeyup="search()">
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div class="form-group row" id="cardContainer">
                    @foreach ($atk as $row)
                    <div class="form-group col-md-3" data-kategori="{{ $row->kategori_id }}">
                        <div class="card card-atk">
                            <div class="card-header">
                                @if ($row->foto_atk)
                                <img src="{{ asset('storage/files/foto_atk/'. $row->foto_atk) }}" class="img-fluid" alt="">
                                @else
                                <img src="https://cdn-icons-png.flaticon.com/512/679/679821.png" class="img-fluid" alt="">
                                @endif
                            </div>
                            <div class="card-body">
                                <h5>{{ $row->kategori->kategori_atk }}</h5>
                                <h4 class="hide-text-p2">{{ $row->deskripsi }}</h4>
                                <h6 class="mt-2 text-xs text-muted">Rp {{ $row->satuanPick->first()?->deskripsi_harga.' / '.$row->satuanPick->first()?->satuan }}</h6>
                            </div>
                            <div class="card-footer">
                                <form id="form-{{ $row->id_atk }}" action="{{ route('atk.keranjang.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="usulan_id" value="{{ $usulan->id_usulan }}">
                                    <input type="hidden" name="atk_id" value="{{ $row->id_atk }}">
                                    <input type="hidden" name="harga" value="{{ $row->satuan->first()?->harga }}">
                                    <button class="btn btn-outline-danger btn-block btn-sm add-to-cart-button {{ $row->status_id != 1 ? 'disabled' : '' }}" data-id="{{ $row->id_atk }}">
                                        <i class="fas fa-plus"></i> Tambah
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    <div class="col-md-12 d-none" id="notif">
                        <span class="text-center">Data tidak ditemukan...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                    <i class="fas fa-times-circle"></i> Tutup
                </button>
                <a href="#add-item" id="reload-page-button" class="btn btn-danger btn-sm">
                    <i class="fas fa-sync"></i> Reload
                </a>
            </div>
        </div>
    </div>
</div> -->

@section('js')
<script>
    $(function() {
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $('[name="atk[]"]').select2()
        $("#table-show").DataTable({
            "responsive": false,
            "lengthChange": true,
            "autoWidth": true,
            "info": true,
            "paging": true,
            "searching": true
        }).buttons().container().appendTo('#table-show_wrapper .col-md-6:eq(0)')

        $('.input-format').on('input', function() {
            var value = $(this).val().replace(/[^0-9]/g, '');
            var formattedValue = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            $(this).val(formattedValue);
        });
    })

    $('.number').on('input', function() {
        var value = $(this).val().replace(/[^0-9]/g, '');

        var formattedValue = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

        $(this).val(formattedValue);
    });


    // alert
    $(document).ready(function() {
        $(".add-button").click(function(event) {
            event.preventDefault();
            const dataId = $(this).data("id");
            const inputElement = $("#" + dataId);
            let currentValue = parseInt(inputElement.val());
            currentValue++; // Menambah nilai

            inputElement.val(currentValue);
        });

        $(".min-button").click(function(event) {
            event.preventDefault();
            const dataId = $(this).data("id");
            const inputElement = $("#" + dataId);
            let currentValue = parseInt(inputElement.val());

            if (currentValue > 0) { // Periksa agar tidak kurang dari 1
                currentValue--; // Mengurangkan nilai
            }

            inputElement.val(currentValue);
        });

        $("#reload-page-button").click(function(event) {
            event.preventDefault();
            location.reload();
            $("#add-item").modal("show");
        });
    })

    $(document).ready(function() {
        $(".add-to-cart-button").click(function(event) {
            event.preventDefault(); // Mencegah tindakan default dari tombol

            const formId = $(this).data("id");
            const form = $("#form-" + formId);

            $.ajax({
                url: form.attr("action"),
                type: form.attr("method"),
                data: form.serialize(),
                success: function(response) {
                    Swal.fire({
                        title: 'Berhasil Menambah Keranjang',
                        text: '',
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 1000
                    });

                    // Perbarui jumlah item di keranjang
                    $("#cart-count").text(response.cartCount);
                    $("#cart-count-in").text(response.cartCount);
                    $("#cart-items").empty();

                    $.each(response.cartBasket, function(index, item) {
                        const cartItem = `
                        `;

                        $("#cart-items").append(cartItem);
                    });
                },
                error: function(error) {
                    Swal.fire({
                        title: 'Gagal Menambah Keranjang',
                        text: error.responseText,
                        icon: 'error',
                    });
                }
            });
        });
    });

    function confirmRemove(event) {
        Swal.fire({
            title: 'Berhasil Menghapus Item',
            text: '',
            icon: 'success',
            showConfirmButton: false,
            timer: 1000
        }).then((result) => {
            console.log(event.target.href);
            window.location.href = event.target.href;
        });
    }

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

    function confirmAdd(event) {
        event.preventDefault();

        const form = document.getElementById('form-new');

        Swal.fire({
            title: 'Tambah Baru ?',
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
    }

    // pencarian barang
    function search() {
        var input, filter, cards, cardContainer, description, i;
        var notif = document.getElementById("notif");
        input = document.getElementById("searchInput");
        filter = input.value.toLowerCase();
        cardContainer = document.getElementById("cardContainer");
        cards = cardContainer.getElementsByClassName("col-md-3");

        // Sembunyikan notifikasi "Data tidak ditemukan" secara default
        notif.classList.add("d-none");

        var found = false; // Inisialisasi found sebagai false

        for (i = 0; i < cards.length; i++) {
            description = cards[i].querySelector(".hide-text-p2").innerText.toLowerCase();

            if (description.indexOf(filter) > -1) {
                cards[i].style.display = "";
                found = true; // Set found menjadi true jika ada deskripsi yang cocok
            } else {
                cards[i].style.display = "none";
            }
        }

        // Setel notifikasi "Data tidak ditemukan" jika tidak ada deskripsi yang cocok
        if (!found) {
            notif.classList.remove("d-none");
        }
    }

    // Trigger search function when typing in search input
    document.getElementById("searchInput").addEventListener("keyup", search);
</script>
@endsection


@endsection
