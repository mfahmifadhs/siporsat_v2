@extends('layout.app')

@section('content')

<!-- Content Header -->
<section class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-1"></div>
            <div class="col-sm-9">
                <div class="float-left">
                    <h1 class="text-capitalize">SIPORSAT</h1>
                    <h5>Sistem Pengelolaan Operasional Perkantoran Terpusat</h5>
                    <ol class="breadcrumb text-capitalize">
                        <li class="breadcrumb-item"><a href="{{ route('atk.home') }}">Beranda</a></li>
                        <li class="breadcrumb-item active">Stock Opname</li>
                    </ol>
                </div>
                <div class="float-right mt-5">
                    <a href="{{ route('atk.stockop.show') }}" class="btn btn-add">
                        <span class="btn btn-primary rounded-0 mr-1"><i class="fas fa-arrow-left"></i></span>
                        <small>Kembali</small>
                    </a>
                </div>
            </div>
            <div class="col-sm-2"></div>
        </div>
    </div>
</section>
<!-- Content Header -->

<section class="content">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">

            </div>
            <div class="col-sm-1"></div>
            <div class="col-sm-9">
                <div class="card card-outline card-primary">
                    <div class="card-header">Riwayat Stock Opname</div>
                    <div class="card-body">
                        <table id="table" class="table text-center text-sm">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Keterangan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stockop as $row)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ Carbon\carbon::parse($row->tanggal_stockop)->isoFormat('DD MMMM Y') }}</td>
                                    <td>{{ $row->keterangan }}</td>
                                    <td>
                                        <a class="btn btn-primary btn-sm" data-toggle="modal" data-target="#detail-{{ $row->id_stockop }}">
                                            <i class="fas fa-info-circle"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-sm-2"></div>
        </div>
    </div>
</section>

<!-- Modal -->
@php $totalRow = 0; @endphp
@foreach($stockop as $row)
<div class="modal fade" id="detail-{{ $row->id_stockop }}" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-md">
                    <span class="text-sm">{{ Carbon\Carbon::parse($row->tanggal_stockop)->isoFormat('DD MMMM Y') }}</span> <br>
                    <span>Stock Opname {{ $row->keterangan }}</span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered text-center text-sm m-1">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th style="width: 8%;" class="p-2">Foto</th>
                            <th style="width: 32%;">Deskripsi</th>
                            <th style="width: 20%;">Stok</th>
                            <th style="width: 10%;">Satuan</th>
                            <th style="width: 25%;">Keterangan</th>
                        </tr>
                    </thead>
                </table>

                <table class="table table-bordered text-center text-sm m-1">
                    <tbody id="cart-items">
                        @foreach($row->detail as $subRow)
                        <tr>
                            <td>
                                @php $totalRow++; @endphp
                                <a href="{{ route('atk.stockop.remove', ['stockopId' => $row->id_stockop, 'id' => $subRow->id_detail]) }}">
                                    <i class="fas fa-trash text-danger"></i>
                                </a>
                                {{ $loop->iteration }}

                            </td>
                            <td style="width: 8%;">
                                @if ($subRow->atk->foto_atk)
                                <img src="{{ asset('storage/files/foto_atk/'. $subRow->atk->foto_atk) }}" class="img-fluid" alt="">
                                @else
                                <img src="https://cdn-icons-png.flaticon.com/512/679/679821.png" class="img-fluid" alt="">
                                @endif
                            </td>
                            <td class="text-left" style="width: 32%;">
                                {{ $subRow->atk->kategori->kategori_atk }} <br>
                                {{ $subRow->atk->deskripsi }}
                            </td>
                            <td style="width: 20%;">
                                <div class="input-group">
                                    <a type="button" class="qty-button-modal" data-id="data-{{ $subRow->id_detail }}" href="{{ route('atk.stockop.update', ['aksi' => 'min', 'id' => $subRow->id_detail]) }}">
                                        <div class="input-group-append">
                                            <div class="input-group-text border-dark" style="height: 31px;">
                                                -
                                            </div>
                                        </div>
                                    </a>
                                    <input type="hidden" class="form-control">
                                    <input type="text" class="form-control form-control-sm text-center bg-white number" id="data-{{ $subRow->id_detail }}" name="qty" value="{{ $subRow->kuantitas }}" min="1" readonly>

                                    <a type="button" class="qty-button-modal" data-id="data-{{ $subRow->id_detail }}" href="{{ route('atk.stockop.update', ['aksi' => 'add', 'id' => $subRow->id_detail]) }}">
                                        <div class="input-group-append">
                                            <div class="input-group-text border-dark" style="height: 31px;">
                                                +
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </td>
                            <td style="width: 10%;">{{ optional($subRow->atk->satuan->where('jenis_satuan', 'distribusi')->first())->satuan }}</td>
                            <td class="text-left" style="width: 25%;">
                                <form id="updateDescForm-{{ $subRow->id_detail }}" action="{{ route('atk.stockop.update', ['aksi' => 'desc', 'id' => $subRow->id_detail]) }}" method="POST">
                                    @csrf
                                    <div class="input-group">
                                        <input type="text" id="keteranganInput-{{ $subRow->id_detail }}" name="keterangan" class="form-control form-control-sm" value="{{ $subRow->keterangan }}">
                                        <button type="button" class="btn btn-success btn-sm rounded-0 desc-modal" data-id="{{ $subRow->id_detail }}">
                                            <i class="fas fa-save"></i>
                                        </button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <form id="form-new" action="{{ route('atk.stockop.update', ['aksi' => 'new', 'id' => $subRow->id_detail]) }}" method="POST">
                    @csrf
                    <input type="hidden" name="stockop_id" value="{{ $row->id_stockop }}">
                    <table class="table table-bordered text-center text-sm m-1">
                        <tbody class="section-item d-none">
                            <tr>
                                <td>
                                    <a onclick="confirmRemove(<?php echo $subRow->stockop_id; ?>, <?php echo $subRow->id_detail; ?>)">
                                        <i class="fas fa-trash text-danger"></i>
                                    </a>
                                    <span class="num"></span>
                                </td>
                                <td style="width: 8%;">
                                    @if ($subRow->atk->foto_atk)
                                    <img src="{{ asset('storage/files/foto_atk/'. $subRow->atk->foto_atk) }}" class="img-fluid" alt="">
                                    @else
                                    <img src="https://cdn-icons-png.flaticon.com/512/679/679821.png" class="img-fluid" alt="">
                                    @endif
                                </td>
                                <td class="text-left" style="width: 32%;">
                                    <select name="atk_id[]" class="form-control section-atk valAtk" disabled>
                                        <option value="">-- PILIH ATK --</option>
                                        @foreach ($atk->whereNotIn('id_atk', $row->detail->pluck('atk_id')) as $row)
                                        <option value="{{ $row->id_atk }}">{{ $row->kategori->kategori_atk.' - '.$row->deskripsi }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td style="width: 20%;">
                                    <div class="input-group">
                                        <a type="button" class="min-button">
                                            <div class="input-group-append">
                                                <div class="input-group-text border-dark" style="height: 31px;">
                                                    -
                                                </div>
                                            </div>
                                        </a>

                                        <input type="hidden" class="form-control">
                                        <input type="text" class="form-control form-control-sm text-center bg-white number" name="qty[]" value="1" min="1" disabled>

                                        <a type="button" class="add-button">
                                            <div class="input-group-append">
                                                <div class="input-group-text border-dark" style="height: 31px;">
                                                    +
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </td>
                                <td style="width: 10%;"><span class="section-satuan"></span></td>
                                <td style="width: 25%;" class="text-left">
                                    <div class="input-group">
                                        <input id="keteranganInput" type="text" name="keterangan[]" class="form-control form-control-sm" placeholder="Keterangan" disabled>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <a href="" class="small btn btn-primary btn-xs btn-tambah-baris mt-2">
                        <i class="fas fa-plus"></i> Tambah Baris
                    </a>
                    <a href="" class="small btn btn-danger btn-xs btn-hapus-baris mt-2">
                        <i class="fas fa-times"></i> Hapus Baris
                    </a>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                    <i class="fas fa-times-circle"></i> Tutup
                </button>
                <button type="submit" class="btn btn-primary btn-submit d-none btn-sm" onclick="confirmSubmit(event)">
                    <i class="fas fa-save"></i> Simpan
                </button>
            </div>
            </form>
        </div>
    </div>
</div>
@endforeach


@section('js')
@if(session('success'))
    <script>
        $(document).ready(function(){
            $('#detail-' + <?php echo session('stockopId'); ?>).modal('show');
        });
    </script>
@endif

<script>
    // table
    $("#table").DataTable({
        "responsive": true,
        "lengthChange": true,
        "autoWidth": false,
        "info": false,
        "paging": true
    })

    // edit jumlah
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
                    const updatedKuantitas = response.updated.kuantitas;
                    inputElement.val(updatedKuantitas);
                }
            })
        })

    })

    // update desc
    $(".desc-modal").click(function(event) {
        event.preventDefault();
        const dataId = $(this).data("id");
        const form   = $("#updateDescForm-" + dataId);
        const inputElement = $("#keteranganInput-" + dataId);
        $.ajax({
            url: form.attr("action"),
            type: form.attr("method"),
            data: {
                _token: form.find('input[name="_token"]').val(),
                keterangan: inputElement.val()
            },
            success: function(response) {
                const updatedDesc = response.updated.keterangan;
                inputElement.val(updatedDesc);

                Swal.fire({
                    title: 'Tersimpan',
                    text: '',
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 1000
                });
            },
            error: function(error) {
                // Handle error if necessary
                console.log(error);
            }
        });
    });


    // hapus item basket
    function confirmRemove(event) {
        event.preventDefault();
        Swal.fire({
            title: 'Hapus Item Ini ?',
            text: '',
            icon: 'success',
            showCancelButton: true,
            confirmButtonText: 'Ya!',
            cancelButtonText: 'Batal!'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = event.target.href;
            }
        });
    }

    // confirm submit
    function confirmSubmit(event) {
        event.preventDefault();

        const form = document.getElementById('form-new');

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
    }

    // Section Tambah Baris
    $(document).on('click', '.btn-tambah-baris', function(e) {
        e.preventDefault();
        var container = $('.section-item');
        var templateRow = container.first().clone();
        var totalData = <?php echo $totalRow; ?>

        templateRow.removeClass('d-none');
        templateRow.find('.num').text(container.length + totalData);
        container.last().after(templateRow);
        templateRow.find('.section-atk').removeAttr('disabled').select2()
        templateRow.find('[name="qty[]"]').attr('id', container.length).removeAttr('disabled').prop('readonly', true)
        templateRow.find('[name="keterangan[]"]').removeAttr('disabled')
        templateRow.find('.add-button').attr('data-id', container.length)
        templateRow.find('.min-button').attr('data-id', container.length)
        templateRow.find(".add-button").click(handleAddButtonClick)
        templateRow.find(".min-button").click(handleMinusButtonClick)
        toggleHapusBarisButton()
        $('.btn-submit').removeClass('d-none');

        var satuanElement = templateRow.find(".section-satuan")
        var atkDropdown = templateRow.find(".section-atk")

        atkDropdown.change(function() {
            var selectedAtkId = $(this).val();

            if (selectedAtkId !== "") {
                $.ajax({
                    url: '/atk/select/first/satuan/' + selectedAtkId,
                    type: 'GET',
                    success: function(response) {
                        satuanElement.text(response.satuan);
                    },
                    error: function(error) {
                        console.error(error);
                    }
                });
            } else {
                satuanElement.text("");
            }
        });
    });

    $(document).on('click', '.btn-hapus-baris', function(e) {
        e.preventDefault();
        var container = $('.section-item');
        container.last().remove()
        toggleHapusBarisButton();

        if (container.length == 2) {
            $('.btn-submit').addClass('d-none');
        }
    });

    // Inisialisasi tombol "Hapus Baris" saat halaman dimuat
    toggleHapusBarisButton();

    function toggleHapusBarisButton() {
        var container = $('.section-item');
        var btnHapusBaris = $('.btn-hapus-baris');
        btnHapusBaris.toggle(container.length > 1);
    }

    function handleAddButtonClick(event) {
        event.preventDefault();
        const dataId = $(this).data("id");
        const inputElement = $("#" + dataId);
        let currentValue = parseInt(inputElement.val());
        currentValue++;

        inputElement.val(currentValue);
    }


    function handleMinusButtonClick(event) {
        event.preventDefault();
        const dataId = $(this).data("id");
        const inputElement = $("#" + dataId);
        let currentValue = parseInt(inputElement.val());

        if (currentValue > 0) {
            currentValue--;
        }

        inputElement.val(currentValue);
    }
</script>
@endsection

@endsection
