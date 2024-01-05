@extends('layout.app')

@section('content')

<!-- Content Header -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="text-capitalize">SIPORSAT</h1>
                <h5>Sistem Pengelolaan Operasional Perkantoran Terpusat</h5>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right text-capitalize">
                    <li class="breadcrumb-item">Alat Tulis Kantor</li>
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
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-3 col-12">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ $usulan->whereIn('status_proses_id',[101,102])->count() }} <small>usulan</small></h3>
                                <p>MENUNGGU PERSETUJUAN</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-user-check"></i>
                            </div>
                            <div class="text-center p-2 border border-top">
                                <form action="{{ route('usulan.show','atk') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="persetujuan">
                                    <button class="btn btn-default btn-xs border-secondary">
                                        Selengkapnya <i class="fas fa-arrow-circle-right"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ $usulan->where('status_proses_id',103)->count() }} <small>usulan</small></h3>
                                <p>SEDANG DIPROSES</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-hourglass-half"></i>
                            </div>
                            <div class="text-center p-2 border border-top">
                                <form action="{{ route('usulan.show','atk') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="103">
                                    <button class="btn btn-default btn-xs border-secondary">
                                        Selengkapnya <i class="fas fa-arrow-circle-right"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ $usulan->where('status_proses_id',106)->count() }} <small>usulan</small></h3>
                                <p>SELESAI BERITA ACARA</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="text-center p-2 border border-top">
                                <form action="{{ route('usulan.show','atk') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="106">
                                    <button class="btn btn-default btn-xs border-secondary">
                                        Selengkapnya <i class="fas fa-arrow-circle-right"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ $usulan->where('status_pengajuan_id', 100)->count() }} <small>usulan</small></h3>
                                <p>PENGAJUAN DITOLAK</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-times-circle"></i>
                            </div>
                            <div class="text-center p-2 border border-top">
                                <form action="{{ route('usulan.show','atk') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="100">
                                    <button class="btn btn-default btn-xs border-secondary">
                                        Selengkapnya <i class="fas fa-arrow-circle-right"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


@if(Auth::user()->role_id != 4)
<section class="content">
    <div class="container-fluid">
        <div class="row">

            <div class="col-md-12">
                <hr>
            </div>

            <div class="col-md-6">
                <div class="form-group row">
                    <h6 class="text-left col-md-12 font-weight-bold">Menu</h6>
                    <div class="col-md-6">
                        <a href="{{ route('usulan.show', 'atk') }}" class="btn btn-default border-secondary p-4 btn-block">
                            <i class="fas fa-copy fa-3x"></i>
                            <h6 class="mt-3 font-weight-bolder">Daftar Usulan</h6>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('atk.stockop.show') }}" class="btn btn-default border-secondary p-4 btn-block">
                            <i class="fas fa-clipboard-list fa-3x"></i>
                            <h6 class="mt-3 font-weight-bolder">Stock Opname</h6>
                        </a>
                    </div>
                </div>

                @if (Auth::user()->role_id != 4)
                <div class="form-group row">
                    <h6 class="text-left col-md-12">Laporan</h6>
                    <div class="col-md-6">
                        <a href="{{ route('realisasi.show', ['form' => 'atk', 'ukerId' => Auth::user()->pegawai->unit_kerja_id]) }}" class="btn btn-default border-secondary p-4 btn-block">
                            <i class="fas fa-file-alt fa-3x"></i>
                            <h6 class="mt-3 font-weight-bolder">Anggaran</h6>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('usulan.report', 'atk') }}" class="btn btn-default border-secondary p-4 btn-block">
                            <i class="fas fa-chart-line fa-3x"></i>
                            <h6 class="mt-3 font-weight-bolder">Laporan</h6>
                        </a>
                    </div>
                </div>
                @endif
            </div>

            <div class="col-md-6">
                <div class="form-group row">
                    <h6 class="text-left col-md-12">Daftar ATK</h6>
                    <div class="col-md-6 form-group">
                        <a href="{{ route('atk.show') }}" class="btn btn-default border-secondary p-4 btn-block">
                            <i class="fas fa-book-open-reader fa-3x"></i>
                            <h6 class="mt-3 font-weight-bolder">Daftar Referensi ATK</h6>
                        </a>
                    </div>
                    <div class="col-md-6 form-group">
                        <a href="{{ route('atk.kategori.show') }}" class="btn btn-default border-secondary p-4 btn-block">
                            <i class="fas fa-file-pen fa-3x"></i>
                            <h6 class="mt-3 font-weight-bolder">Daftar Kategori ATK</h6>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif


<section class="content {{ Auth::user()->role_id != 4 ? 'd-none' : '' }}">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">&nbsp;</div>
            <div class="col-md-9">

            </div>
            <div class="col-md-3">
                <label>Menu</label>
                <div class="form-group">
                    <a href="{{ route('usulan.show', 'atk') }}" class="btn btn-default border-dark p-4 btn-block">
                        <i class="fas fa-copy fa-3x"></i>
                        <h6 class="mt-3 font-weight-bolder">Daftar Usulan</h6>
                    </a>
                </div>
                <div class="form-group">
                    <a href="{{ route('atk.show') }}" class="btn btn-default border-secondary p-4 btn-block">
                        <i class="fas fa-book-open-reader fa-3x"></i>
                        <h6 class="mt-3 font-weight-bolder">Daftar Referensi ATK</h6>
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
                    <div class="col-md-1 form-group">
                        <a type="button" class="btn btn-app btn-default btn-block border-dark" data-toggle="modal" data-target="#basket">
                            <span class="badge bg-danger mr-3" id="cart-count">{{ Auth::user()->keranjang->count() }}</span>
                            <i class="fas fa-basket-shopping text-black"></i>
                        </a>
                    </div>
                    <!-- <div class="col-md-1 dropdown">
                        <a class="btn btn-app btn-default btn-block border-dark" data-toggle="dropdown" href="#">
                            <span class="badge bg-danger mr-3" id="cart-count">{{ Auth::user()->keranjang->count() }}</span>
                            <i class="fas fa-basket-shopping text-black"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-xl dropdown-menu-right keranjang">
                            <span class="dropdown-item dropdown-header">
                                <div class="row">
                                    <div class="col-md-6 col-12 text-left">
                                        Keranjang Saya (<span id="cart-count-in">{{ Auth::user()->keranjang->count() }}</span> item)
                                    </div>
                                    <div class="col-md-6 col-12 text-right">
                                        <a href="{{ route('usulan.create', ['form' => 'atk', 'id' => '*']) }}">Lihat semua</a>
                                    </div>
                                </div>
                            </span>
                            <div class="dropdown-divider"></div>
                            <div id="cart-items">
                                @foreach (Auth::user()->keranjang as $row)
                                <a href="#" class="dropdown-item">
                                    <div class="media">
                                        @if ($row->foto_atk)
                                        <img src="{{ asset('storage/files/foto_atk/'. $row->foto_atk) }}" class="img-fluid img-size-50 mr-3" alt="">
                                        @else
                                        <img src="https://cdn-icons-png.flaticon.com/512/679/679821.png" class="img-fluid img-size-50 mr-3" alt="">
                                        @endif
                                        <div class="media-body">
                                            <h6 class="dropdown-item-title">
                                                {{ $row->atk->kategori->kategori_atk }}
                                            </h6>
                                            <h5 class="text-sm">
                                                {{ $row->atk->deskripsi }}
                                                <span class="float-right text-sm text-muted">
                                                    {{ $row->kuantitas.' '.$row->atk->satuanPick->first()?->satuan }}
                                                </span>
                                            </h5>
                                        </div>
                                    </div>
                                </a>
                                <div class="dropdown-divider"></div>
                                @endforeach
                            </div>

                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item dropdown-footer">
                                <i class="fas fa-trash"></i> Hapus semua barang
                            </a>
                        </div>
                    </div> -->
                </div>
                <div class="form-group row" id="cardContainer">
                    @foreach ($atk->where('status_id', 1) as $row)
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
                                    <button class="btn btn-outline-danger btn-block btn-sm add-to-cart-button mt-2 {{ $row->status_id != 1 ? 'disabled' : '' }}" data-id="{{ $row->id_atk }}">
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
<form id="form" action="{{ route('usulan.create', ['form' => 'atk', 'id' => '*']) }}" method="POST">
    @csrf
    <div class="modal fade" id="basket" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
            <div class="modal-content">
                <input type="hidden" name="kode_form" value="301">
                <div class="modal-header border-dark">
                    <h5 class="modal-title text-md">KERANJANG SAYA (<span id="cart-count-in">{{ Auth::user()->keranjang->count() }}</span> item)</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Rencana Pengguna</label>
                        <textarea name="keterangan" class="form-control"></textarea>
                    </div>
                    <table class="table table-bordered text-center text-sm m-1">
                        <thead>
                            <tr>
                                <th style="width: 5%;">No</th>
                                <th style="width: 8%;" class="p-2">Foto</th>
                                <th style="width: 30%;">Deskripsi</th>
                                <th style="width: 20%;">Stok</th>
                                <th style="width: 10%;">Satuan</th>
                                <th style="width: 27%;">Keterangan</th>
                            </tr>
                        </thead>
                    </table>


                    <div id="cart-items">
                        @foreach (Auth::user()->keranjang as $row)
                        <input type="hidden" name="atk[]" value="{{ $row->atk_id }}">
                        <input type="hidden" name="harga[]">
                        <input type="hidden" name="id_keranjang[]" value="{{ $row->id_keranjang }}">
                        <table class="table table-bordered text-center text-sm m-1">
                            @php $totalRow = 0; @endphp
                            <tbody>
                                <tr>
                                    <td style="width: 5%;">
                                        @php $totalRow++; @endphp
                                        <a href="#" onclick="confirmRemove(<?php echo $row->id_keranjang; ?>)">
                                            <i class="fas fa-minus-circle fa-1x text-danger"></i>
                                        </a>
                                        {{ $loop->iteration }}

                                    </td>
                                    <td style="width: 8%;">
                                        @if ($row->atk->foto_atk)
                                        <img src="{{ asset('storage/files/foto_atk/'. $row->atk->foto_atk) }}" class="img-fluid" alt="">
                                        @else
                                        <img src="https://cdn-icons-png.flaticon.com/512/679/679821.png" class="img-fluid" alt="">
                                        @endif
                                    </td>
                                    <td class="text-left" style="width: 30%;">
                                        {{ $row->atk->kategori->kategori_atk }} <br>
                                        {{ $row->atk->deskripsi }}
                                    </td>
                                    <td style="width: 20%;">
                                        <div class="input-group">
                                            <div class="input-group-append">
                                                <div class="input-group-text rounded-left" style="height: 31px;">
                                                    <a type="button" class="qty-button-modal" data-id="{{ $row->id_keranjang }}" href="{{ route('atk.keranjang.update', ['aksi' => 'min', 'id' => $row->id_keranjang]) }}">
                                                        <i class="fas fa-minus-circle" aria-hidden="true"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            <input type="text" class="form-control form-control-sm text-center bg-white" id="{{ $row->id_keranjang }}" name="jumlah[]" value="{{ $row->kuantitas }}" min="1" readonly>
                                            <div class="input-group-append">
                                                <div class="input-group-text rounded-left" style="height: 31px;">
                                                    <a type="button" class="qty-button-modal" data-id="{{ $row->id_keranjang }}" href="{{ route('atk.keranjang.update', ['aksi' => 'plus', 'id' => $row->id_keranjang]) }}">
                                                        <i class="fas fa-plus-circle" aria-hidden="true"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="width: 10%;">{{ optional($row->atk->satuan->where('jenis_satuan', 'distribusi')->first())->satuan }}</td>
                                    <td class="text-left" style="width: 27%;">
                                        <input type="text" id="keteranganInput-{{ $row->id_keranjang }}" name="keterangan" class="form-control form-control-sm" placeholder="Keterangan">
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

    // alert
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

                        // $.each(response.cartBasket, function(index, item) {
                        //     const cartItem = `
                        //         <a href="#" class="dropdown-item">
                        //             <div class="media">
                        //                 <img src="${item.foto_atk ? '/storage/files/foto_atk/' + item.foto_atk : 'https://cdn-icons-png.flaticon.com/512/679/679821.png'}" class="img-fluid img-size-50 mr-3" alt="">
                        //                 <div class="media-body">
                        //                     <h6 class="dropdown-item-title">
                        //                         ${item.kategori_atk}
                        //                     </h6>
                        //                     <h5 class="text-sm">
                        //                         ${item.deskripsi}
                        //                         <span class="float-right text-sm text-muted">
                        //                             ${item.kuantitas}
                        //                         </span>
                        //                     </h5>
                        //                 </div>
                        //             </div>
                        //         </a>
                        //         <div class="dropdown-divider"></div>
                        //     `;

                        //     $("#cart-items").append(cartItem);
                        // });
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

        // BTN PLUS MIN
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
            event.preventDefault();
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
    });

    function updateCartItems(cartBasket) {
        $("#cart-items").empty();

        $.each(cartBasket, function(index, item) {
            const updateMin = '{{ route("atk.keranjang.update", ["aksi" => "min", "id" => "__ID__"]) }}'.replace('__ID__', item.id_keranjang)
            const updateAdd = '{{ route("atk.keranjang.update", ["aksi" => "add", "id" => "__ID__"]) }}'.replace('__ID__', item.id_keranjang)
            const removeItem = '{{ route("atk.keranjang.remove", ["id" => "__ID__"]) }}'.replace('__ID__', item.id_keranjang)
            const satuan = ''

            const cartItem = `
                <table class="table table-bordered text-center text-sm m-1">
                    @php $totalRow = 0; @endphp
                    <input type="hidden" name="atk[]" value="${item.atk_id}">
                    <input type="hidden" name="id_keranjang[]" value="${item.id_keranjang}">
                    <tbody>
                        <tr>
                            <td style="width: 5%;">
                                @php $totalRow++; @endphp
                                <a href="#" onclick="confirmRemove(${item.id_keranjang})">
                                    <i class="fas fa-minus-circle fa-1x text-danger"></i>
                                </a>
                                ${index + 1}
                            </td>
                            <td style="width: 8%;">
                                <img src="${item.foto_atk ? '/storage/files/foto_atk/' + item.foto_atk : 'https://cdn-icons-png.flaticon.com/512/679/679821.png'}" class="img-fluid img-size-50 mr-3" alt="">
                            </td>
                            <td class="text-left" style="width: 30%;">
                                ${ item.kategori_atk } <br>
                                ${ item.deskripsi }
                            </td>
                            <td style="width: 20%;">
                                <div class="input-group">
                                    <div class="input-group-append">
                                        <div class="input-group-text rounded-left">
                                            <a type="button" class="qty-button-modal" data-id="${item.id_keranjang}" href="${updateMin}">
                                                <i class="fas fa-minus-circle" aria-hidden="true"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control text-center bg-white" id="${item.id_keranjang}" name="jumlah[]" value="${item.kuantitas}" min="1" readonly>
                                    <div class="input-group-append">
                                        <div class="input-group-text rounded-left">
                                            <a type="button" class="qty-button-modal" data-id="${item.id_keranjang}" href="${updateAdd}">
                                                <i class="fas fa-plus-circle" aria-hidden="true"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td style="width: 10%;">{{ optional($row->atk?->satuan->where('jenis_satuan', 'distribusi')->first())->satuan }}</td>
                            <td class="text-left" style="width: 27%;">
                                <input type="text" id="keteranganInput-{{ $row->id_keranjang }}" name="keterangan" class="form-control form-control-sm" placeholder="Keterangan">
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
            var cards = cardContainer.getElementsByClassName("form-group col-md-3");
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

    // HAPUS BARANG KERANJANG
    function confirmRemove(itemId) {
        Swal.fire({
            title: 'Hapus Item ?',
            text: '',
            icon: 'question',
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

@endsection

@endsection
