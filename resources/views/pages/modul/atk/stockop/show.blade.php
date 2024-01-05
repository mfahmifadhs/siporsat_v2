@extends('layout.app')

@section('content')

<!-- Content Header -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12 col-12">
                <h1 class="text-capitalize">SIPORSAT</h1>
                <h5>Sistem Pengelolaan Operasional Perkantoran Terpusat</h5>
                <ol class="breadcrumb text-capitalize">
                    <li class="breadcrumb-item"><a href="{{ route('atk.home') }}">Beranda</a></li>
                    <li class="breadcrumb-item active">Stock Opname</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<!-- Content Header -->

<section class="content">
    <div class="container-fluid">
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
        <div class="row">
            <div class="col-md-3">&nbsp;</div>
            <div class="col-md-9">

            </div>
            <div class="col-md-3">
                <label>Menu</label>
                <div class="form-group">
                    <a href="{{ route('atk.stockop.history') }}" class="btn btn-default border-dark p-4 btn-block">
                        <i class="fas fa-clock-rotate-left fa-3x"></i>
                        <h6 class="mt-3 font-weight-bolder">Riwayat</h6>
                    </a>
                </div>
            </div>
            <div class="col-md-9">
                <label>Daftar ATK</label>
                <div class="form-group row">
                    <div class="col-md-3 form-group">
                        <select name="" class="form-control" id="kategori">
                            <option value="">Seluruh Barang</option>
                            @foreach ($kategori as $row)
                            <option value="{{ $row->kode_kategori }}">{{ $row->kategori_atk }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-8 form-group">
                        <input type="text" id="searchInput" class="form-control" placeholder="Cari Barang..." onkeyup="search()">
                    </div>
                    <div class="col-md-1">
                        <a type="button" class="btn btn-app btn-default btn-block border-dark" data-toggle="modal" data-target="#basket">
                            <span class="badge bg-danger mr-3" id="cart-count">{{ Auth::user()->keranjang->count() }}</span>
                            <i class="fas fa-basket-shopping text-black"></i>
                        </a>
                    </div>
                </div>
                <div class="form-group row" id="cardContainer">
                    @foreach ($atk->where('status_id', 1) as $row)
                    <div class="form-group col-md-3 col-6" data-kategori="{{ $row->kategori_id }}">
                        <div class="card card-atk">
                            <div class="card-header">
                                @if ($row->foto_atk)
                                <img src="{{ asset('storage/files/foto_atk/'. $row->foto_atk) }}" class="img-fluid" alt="">
                                @else
                                <img src="https://cdn-icons-png.flaticon.com/512/679/679821.png" class="img-fluid" alt="">
                                @endif
                            </div>
                            <div class="card-body">
                                <h5>{{ $row->id_atk.' - '.$row->kategori->kategori_atk }}</h5>
                                <h4 class="text-p2">{{ $row->deskripsi }}</h4>
                                <h6>{{ 'Rp '. $row->satuanPick->first()?->deskripsi_harga, 0, ',', '.' }}</h6>
                            </div>
                            <div class="card-footer">
                                <form id="form-{{ $row->id_atk }}" action="{{ route('atk.keranjang.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="atk_id" value="{{ $row->id_atk }}">
                                    <div class="input-group">
                                        <a type="button" class="min-button" data-id="data-{{ $row->id_atk }}">
                                            <div class="input-group-append">
                                                <div class="input-group-text rounded-left border-dark" style="height: 31px;">
                                                    -
                                                </div>
                                            </div>
                                        </a>
                                        <input type="hidden" class="form-control">
                                        <input type="text" class="form-control form-control-sm text-center bg-white number" id="data-{{ $row->id_atk }}" name="qty" value="0" min="1">

                                        <a type="button" class="add-button" data-id="data-{{ $row->id_atk }}">
                                            <div class="input-group-append">
                                                <div class="input-group-text border-dark" style="height: 31px;">
                                                    +
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <button class="btn btn-outline-danger btn-block btn-xs add-to-cart-button mt-3 {{ $row->status_id != 1 ? 'disabled' : '' }}" data-id="{{ $row->id_atk }}">
                                        <i class="fas fa-plus"></i> Keranjang
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
        </div>
    </div>
</section>

<!-- Modal -->
<form id="form" action="{{ route('atk.stockop.store') }}" method="POST">
    @csrf
    <div class="modal fade" id="basket" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header border-dark">
                    <h5 class="modal-title text-md">KERANJANG SAYA (<span id="cart-count-in">{{ Auth::user()->keranjang->count() }}</span> item)</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="text-sm">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="text-sm">Keterangan</label>
                        <textarea name="ket_so" class="form-control" placeholder="Contoh: Periode Januari s/d Agustus 2023" required></textarea>
                    </div>
                    <label>Barang</label>
                    <div id="cart-items">
                        @foreach (Auth::user()->keranjang as $row)
                        <table class="table table-bordered">
                            <tbody class="media">
                                <tr>
                                    <td class="text-center text-sm" style="width: 5px;">{{ $loop->iteration }}</td>
                                    <td class="text-center" style="width: 20%;">
                                        <center>
                                            @if ($row->foto_atk)
                                            <img src="{{ asset('storage/files/foto_atk/'. $row->foto_atk) }}" class="img-fluid img-size-50" alt="">
                                            @else
                                            <img src="https://cdn-icons-png.flaticon.com/512/679/679821.png" class="img-fluid img-size-50" alt="">
                                            @endif
                                        </center>
                                    </td>
                                    <td class="text-left" style="width: 55vh;">
                                        <div class="row">
                                            <h6 class="col-md-6 col-6 text-xs font-weight-bold">
                                                {{ $row->atk_id.' - '.$row->atk->kategori->kategori_atk }}
                                            </h6>
                                            <h6 class="col-md-6 col-6 text-xs font-weight-bold text-right">
                                                <a onclick="confirmRemove(<?php echo $row->id_keranjang; ?>)">
                                                    <i class="fas fa-minus-circle fa-1x text-danger"></i>
                                                </a>
                                            </h6>
                                            <h6 class="col-md-12 col-12 text-xs">{{ $row->atk->deskripsi }}</h6>
                                        </div>
                                        <div class="input-group mb-2">
                                            <a type="button" class="qty-button-modal" data-id="data-modal-{{ $row->id_keranjang }}" href="{{ route('atk.keranjang.update', ['aksi' => 'min', 'id' => $row->id_keranjang]) }}">
                                                <div class="input-group-append">
                                                    <div class="input-group-text rounded-left border-dark" style="height: 31px;">
                                                        -
                                                    </div>
                                                </div>
                                            </a>

                                            <input type="hidden" name="atk_id[]" value="{{ $row->atk_id }}" class="form-control">
                                            <input type="hidden" name="keranjang_id[]" value="{{ $row->id_keranjang }}" class="form-control">
                                            <input type="text" class="form-control form-control-sm text-center bg-white number" id="data-modal-{{ $row->id_keranjang }}" name="qty[]" value="{{ $row->kuantitas }}" min="1">

                                            <a type="button" class="qty-button-modal" data-id="data-modal-{{ $row->id_keranjang }}" href="{{ route('atk.keranjang.update', ['aksi' => 'plus', 'id' => $row->id_keranjang]) }}">
                                                <div class="input-group-append">
                                                    <div class="input-group-text border-dark" style="height: 31px;">
                                                        +
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <input type="text" class="form-control form-control-sm" name="ket_detail[]" placeholder="Keterangan Barang">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer border-dark">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                        <i class="fas fa-times-circle"></i> Tutup
                    </button>
                    <button type="button" class="btn btn-primary btn-sm" onclick="confirmSubmit(event)">
                        <i class="fas fa-paper-plane"></i> Submit
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>


@section('js')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script>
    // table
    $("#table-basket").DataTable({
        "responsive": false,
        "lengthChange": true,
        "autoWidth": false,
        "info": false,
        "paging": true,
        "searching": false
    })

    // JUMLAH
    $('.qty').on('input', function() {
        var value = $(this).val().replace(/[^0-9]/g, '');
        var formattedValue = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        $(this).val(formattedValue);
    });

    // format angka
    $('.number').on('input', function() {
        var value = $(this).val().replace(/[^0-9]/g, '');

        var formattedValue = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

        $(this).val(formattedValue);
    });

    // BTN PLUS MIN
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

        $(".qty-button-modal").click(function(event) {
            event.preventDefault(); // Mencegah tindakan default dari tombol
            const dataId = $(this).data("id");
            const link = $(this).attr('href');
            const inputElement = $("#" + dataId);

            $.ajax({
                url: link,
                type: "GET",
                success: function(response) {
                    const updatedKuantitas = response.updatedKuantitas.kuantitas;
                    inputElement.val(updatedKuantitas);
                }
            })
        })
    })

    // TAMBAH KERANJANG
    $(document).ready(function() {
        $(".add-to-cart-button").click(function(event) {
            event.preventDefault(); // Mencegah tindakan default dari tombol

            const formId = $(this).data("id");
            const form = $("#form-" + formId);

            const qty = form.find('input[name="qty"]').val()

            if (qty == 0) {
                Swal.fire({
                    title: 'Jumlah harus lebih dari 1',
                    text: '',
                    icon: 'error',
                    showConfirmButton: false,
                    timer: 1000
                });
            } else {
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

                        form.find('input[name="qty"]').val(0);
                        // Perbarui jumlah item di keranjang
                        $("#cart-count").text(response.cartCount);
                        $("#cart-count-in").text(response.cartCount);
                        $("#cart-items").empty();

                        // Update the cart items
                        updateCartItems(response.cartBasket);
                    },
                    error: function(error) {
                        Swal.fire({
                            title: 'Gagal Menambah Keranjang',
                            text: error.responseText,
                            icon: 'error',
                        });
                    }
                });
            }
        });
    });

    // HAPUS BARANG KERANJANG
    function confirmRemove(itemId) {
        Swal.fire({
            title: 'Berhasil Menghapus Item',
            text: '',
            icon: 'success',
            showCancelButton: true,
            confirmButtonText: 'Ya!',
            cancelButtonText: 'Batal!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Send an AJAX request to delete the item
                $.ajax({
                    type: 'GET',
                    url: '{{ route("atk.keranjang.remove", ["id" => "__ID__"]) }}'.replace('__ID__', itemId),
                    success: function(response) {
                        // Show success message
                        Swal.fire({
                            title: 'Item deleted!',
                            text: '',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1500
                        });

                        // Update the cart items
                        updateCartItems(response.cartBasket);
                    },
                    error: function(error) {
                        // Show error message
                        Swal.fire({
                            title: 'Error deleting item',
                            text: error.responseJSON.message,
                            icon: 'error',
                        });
                    }
                });
            }
        });
    }

    function confirmSubmit(event) {
        event.preventDefault();

        const form = document.getElementById('form');
        const inputs = form.querySelectorAll('input[required], textarea[required]');
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
                title: 'Tambah Pencatatan Stock Opname ?',
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

    function updateCartItems(cartBasket) {
        $("#cart-items").empty();

        $.each(cartBasket, function(index, item) {
            const updateMin = '{{ route("atk.keranjang.update", ["aksi" => "min", "id" => "__ID__"]) }}'.replace('__ID__', item.id_keranjang)
            const updateAdd = '{{ route("atk.keranjang.update", ["aksi" => "add", "id" => "__ID__"]) }}'.replace('__ID__', item.id_keranjang)

            const cartItem = `
            <table class="table table-bordered">
                <tbody class="media">
                    <tr>
                        <td class="text-center text-sm" style="width: 5px;">${index + 1}</td>
                        <td class="text-center" style="width: 20%;">
                            <center>
                            @if ($row->foto_atk)
                            <img src="{{ asset('storage/files/foto_atk/'. $row->foto_atk) }}" class="img-fluid img-size-50" alt="">
                            @else
                            <img src="https://cdn-icons-png.flaticon.com/512/679/679821.png" class="img-fluid img-size-50" alt="">
                            @endif
                            </center>
                        </td>
                        <td class="text-left" style="width: 55vh;">
                            <div class="row">
                                <h6 class="col-md-6 col-6 text-xs font-weight-bold">
                                    ${item.atk_id} - ${item.kategori_atk}
                                </h6>
                                <h6 class="col-md-6 col-6 text-xs font-weight-bold text-right">
                                    <a onclick="confirmRemove(${item.id_keranjang})">
                                        <i class="fas fa-minus-circle fa-1x text-danger"></i>
                                    </a>
                                </h6>
                                <h6 class="col-md-12 col-12 text-xs">${item.deskripsi}</h6>
                            </div>
                            <div class="input-group mb-2">
                                <a type="button" class="qty-button-modal" data-id="data-modal-${item.id_keranjang}" href="${updateMin}">
                                    <div class="input-group-append">
                                        <div class="input-group-text rounded-left border-dark" style="height: 31px;">
                                            -
                                        </div>
                                    </div>
                                </a>

                                <input type="hidden" name="atk_id[]" value="${item.atk_id}" class="form-control">
                                <input type="hidden" name="keranjang_id[]" value="${item.id_keranjang   }" class="form-control">
                                <input type="text" class="form-control form-control-sm text-center bg-white number" id="data-modal-${item.id_keranjang}" name="qty[]" value="${item.kuantitas}" min="1">

                                <a type="button" class="qty-button-modal" data-id="data-modal-${item.id_keranjang}" href="${updateAdd}">
                                    <div class="input-group-append">
                                        <div class="input-group-text border-dark" style="height: 31px;">
                                            +
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <input type="text" class="form-control form-control-sm" name="ket_detail[]" placeholder="Keterangan Barang">
                        </td>
                    </tr>
                </tbody>
            </table>
            `;
            $("#cart-items").append(cartItem);
        });

        $(".qty-button-modal").click(function(event) {
            event.preventDefault(); // Mencegah tindakan default dari tombol
            const dataId = $(this).data("id");
            const link = $(this).attr('href');
            const inputElement = $("#" + dataId);

            $.ajax({
                url: link,
                type: "GET",
                success: function(response) {
                    const updatedKuantitas = response.updatedKuantitas.kuantitas;
                    inputElement.val(updatedKuantitas);
                }
            })
        })
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
            description = cards[i].querySelector(".text-p2").innerText.toLowerCase();

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

    // filter berdasarkan kategori
    document.addEventListener("DOMContentLoaded", function() {
        // Select element
        var kategoriSelect = document.getElementById("kategori");
        var cardContainer = document.getElementById("cardContainer");
        var notif = document.getElementById("notif");

        // Event listener untuk meng-handle perubahan pada dropdown kategori
        kategoriSelect.addEventListener("change", function() {
            // Mendapatkan nilai kategori yang dipilih
            var selectedKategori = this.value.toLowerCase();
            var found = false;

            // Loop melalui semua kartu ATK
            var cards = cardContainer.getElementsByClassName("form-group col-md-3 col-6");
            for (var i = 0; i < cards.length; i++) {
                var card = cards[i];
                var cardKategori = card.getAttribute("data-kategori").toLowerCase();

                // Memeriksa apakah kartu harus ditampilkan atau disembunyikan berdasarkan kategori yang dipilih
                if (selectedKategori === "" || cardKategori === selectedKategori) {
                    card.style.display = "block";
                    found = true
                } else {
                    card.style.display = "none";
                }

                if (!found) {
                    notif.classList.remove("d-none");
                } else {
                    notif.classList.add("d-none");
                }
            }
        });
    });
</script>

@endsection

@endsection
