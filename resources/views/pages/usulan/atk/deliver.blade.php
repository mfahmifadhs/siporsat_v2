@extends('layout.app')

@section('content')

<!-- Content Header -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-8">
                <h1 class="text-capitalize font-weight-bold">Penyerahan ATK <small class="text-sm">({{ $usulan->id_usulan }})</small></h1>
                <h4 class="small text-muted mt-2">
                    Pastikan bahwa barang yang diserahkan sesuai dengan yang diberikan oleh Petugas Gudang.
                </h4>
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

        <form id="form" action="{{ route('atk.deliver.store', ['form' => $form, 'id' => $id]) }}" method="POST">
            @csrf
            <input type="hidden" name="kode_form" value="301">
            <div class="row">
                <div class="col-md-12">
                    <div class="card rounded">

                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group row">
                                        <label class="text-muted col-md-12">Informasi Usulan</label>
                                        <label class="col-md-3 col-form-label">No. Usulan</label>
                                        <div class="col-md-1 col-form-label">:</div>
                                        <input type="text" class="form-control select-border-bottom col-md-8 bg-light" value="{{ $usulan->nomor_usulan }}" readonly>
                                        <label class="col-md-3 col-form-label">Tanggal Usulan</label>
                                        <div class="col-md-1 col-form-label">:</div>
                                        <input type="text" class="form-control select-border-bottom col-md-8 bg-light" value="{{ \Carbon\carbon::parse($usulan->tanggal_usulan)->isoFormat('DD MMMM Y') }}" readonly>
                                        <label class="col-md-3 col-form-label">Keterangan</label>
                                        <div class="col-md-1 col-form-label">:</div>
                                        <input type="text" class="form-control select-border-bottom col-md-8 bg-light" rows="5" value="{{ $usulan->keterangan }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-1"></div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="text-muted col-md-12">Informasi Berita Acara & Realisasi</label>
                                        <label class="col-md-3 col-form-label">Tanggal Bast*</label>
                                        <div class="col-md-1 col-form-label">:</div>
                                        <input type="date" class="form-control select-border-bottom bg-white col-md-8" name="tanggal_bast" required>
                                        <label class="col-md-3 col-form-label">Kode MAK</label>
                                        <div class="col-md-1 col-form-label">:</div>
                                        <input type="number" class="form-control select-border-bottom bg-white col-md-8" name="mta_kode">
                                        <label class="col-md-3 col-form-label">Deskripsi</label>
                                        <div class="col-md-1 col-form-label">:</div>
                                        <input type="text" class="form-control select-border-bottom bg-white col-md-8" name="mta_deskripsi" placeholder="Contoh: Penyelenggaraan Kegiatan dan Operasional Kepala Biro Umum">
                                        <label class="col-md-3 col-form-label">Total Realisasi</label>
                                        <div class="col-md-1 col-form-label">:</div>
                                        <input type="text" class="form-control select-border-bottom col-md-8 bg-white number" name="total_realisasi" placeholder="Total Realisasi">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            @php $no = 1; @endphp
                            <label>Apakah Seluruh Barang Sudah Diserahkan ?*</label>
                            <div class="form-check">
                                <input class="form-check-input" name="confirmAll" type="radio" value="1" required id="checkTrue">
                                <label class="form-check-label" for="checkTrue">
                                    Ya
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="confirmAll" type="radio" value="0" required id="checkFalse">
                                <label class="form-check-label" for="checkFalse">
                                    Tidak
                                </label>
                            </div>
                            <div class="table-responsive mt-2">
                                <table id="table-show" class="table table-bordered">
                                    <thead style="font-size: 14px;" class="bg-light">
                                        <tr class="text-center">
                                            <th>No</th>
                                            <th>Nama Barang</th>
                                            <th style="width: 15%;">Satuan</th>
                                            <!-- <th style="width: 18%;">Harga Satuan</th> -->
                                            <th style="width: 20%;">Jumlah Disetujui</th>
                                            <th style="width: 20%;">Diserahkan</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    @php $totalRow = 0; $totalData = 0; @endphp
                                    <tbody class="small">
                                        @foreach ($usulan->usulanAtk as $i => $row)
                                        @if(($row->jumlah_disetujui - $row->jumlah_penyerahan) > 0)
                                        <tr>
                                            <td class="text-center">
                                                @php
                                                    $totalRow++;
                                                    $totalData += $i + 1;
                                                @endphp
                                                {{ $no++ }}
                                            </td>
                                            <td>
                                                <select name="atk[]" class="form-control form-control-sm atk" style="width: 100%;">
                                                    @foreach ($atk as $subRow)
                                                    <option value="{{ $subRow->id_atk }}" <?php echo $subRow->id_atk == $row->atk_id ? 'selected' : '' ?>>
                                                        {{ $subRow->deskripsi }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="text-center">{{ $row->atk->satuanPick->first()?->satuan }}</td>
                                            <!-- <td>
                                                <div class="input-group">
                                                    <div class="input-group-append">
                                                        <div class="input-group-text rounded-left font-weight-bold small border-dark" style="height: 31px;">Rp</div>
                                                    </div>
                                                    <input type="hidden" class="form-control">
                                                    <input type="text" class="form-control form-control-sm text-center bg-white number" name="harga_realisasi[]" placeholder="Harga">
                                                </div>
                                            </td> -->
                                            <td>
                                                <div class="input-group">
                                                    <div class="input-group-append">
                                                        <div class="input-group-text rounded-left border-dark" style="height: 31px;">
                                                            <a type="button" class="min-buttons" data-id="disetujui-{{ $row->id_permintaan }}">
                                                                <i class="fas fa-minus-circle fa-1x" aria-hidden="true"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" class="form-control" name="permintaan_id[]" value="{{ $row->id_permintaan }}">
                                                    <input type="text" class="form-control form-control-sm text-center bg-white number" id="disetujui-{{ $row->id_permintaan }}" name="disetujui[]" value="{{ $row->jumlah_disetujui }}" min="1" readonly>
                                                    <div class="input-group-append">
                                                        <div class="input-group-text border-dark" style="height: 31px;">
                                                            <a type="button" class="add-buttons" data-id="disetujui-{{ $row->id_permintaan }}">
                                                                <i class="fas fa-plus-circle fa-1x" aria-hidden="true"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group">
                                                    <div class="input-group-append">
                                                        <div class="input-group-text rounded-left border-dark" style="height: 31px;">
                                                            <a type="button" class="min-button" data-id="diserahkan-{{ $row->id_permintaan }}">
                                                                <i class="fas fa-minus-circle fa-1x" aria-hidden="true"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" class="form-control">
                                                    <input type="text" class="form-control form-control-sm text-center bg-white number" id="diserahkan-{{ $row->id_permintaan }}" name="diserahkan[]" value="{{ $row->jumlah_disetujui - $row->jumlah_penyerahan }}" min="1">
                                                    <div class="input-group-append">
                                                        <div class="input-group-text border-dark" style="height: 31px;">
                                                            <a type="button" class="add-button" data-id="diserahkan-{{ $row->id_permintaan }}">
                                                                <i class="fas fa-plus-circle fa-1x" aria-hidden="true"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('atk.keranjang.remove', $row->id_permintaan) }}" class="btn btn-danger" onclick="confirmSubmit(event)">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endif
                                        @endforeach
                                        <tr class="section-item table-row d-none">
                                            <td class="text-center num"></td>
                                            <td>
                                                <input type="hidden" name="permintaan_id[]" value="" disabled>
                                                <select name="atk[]" class="form-control form-control-sm atk-more" style="width: 100%;" disabled>
                                                    <option value="">-- PILIH ATK --</option>
                                                    @foreach ($atk as $subRow)
                                                    <option value="{{ $subRow->id_atk }}">
                                                        {{ $subRow->deskripsi }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td></td>
                                            <td>
                                                <div class="input-group">
                                                    <div class="input-group-append">
                                                        <div class="input-group-text rounded-left border-dark" style="height: 31px;">
                                                            <a type="button" class="min-button-appr">
                                                                <i class="fas fa-minus-circle fa-1x" aria-hidden="true"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" class="form-control">
                                                    <input type="text" class="form-control form-control-sm text-center bg-white number" name="disetujui[]" placeholder="sesuai penyerahan" disabled>
                                                    <div class="input-group-append">
                                                        <div class="input-group-text border-dark" style="height: 31px;">
                                                            <a type="button" class="add-button-appr">
                                                                <i class="fas fa-plus-circle fa-1x" aria-hidden="true"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group">
                                                    <div class="input-group-append">
                                                        <div class="input-group-text rounded-left border-dark" style="height: 31px;">
                                                            <a type="button" class="min-button-give">
                                                                <i class="fas fa-minus-circle fa-1x" aria-hidden="true"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" class="form-control">
                                                    <input type="text" class="form-control form-control-sm text-center bg-white number" name="diserahkan[]" value="1" min="1" disabled>
                                                    <div class="input-group-append">
                                                        <div class="input-group-text border-dark" style="height: 31px;">
                                                            <a type="button" class="add-button-give">
                                                                <i class="fas fa-plus-circle fa-1x" aria-hidden="true"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <a href="" class="small btn btn-primary btn-xs btn-tambah-baris">
                                    <i class="fas fa-plus"></i> Tambah Baris
                                </a>
                                <a href="" class="small btn btn-danger btn-xs btn-hapus-baris">
                                    <i class="fas fa-times"></i> Hapus Baris
                                </a>
                            </div>
                        </div>
                        @if ($totalData != 0)
                        <div class="card-footer text-right">
                            <button type="submit" class="btn btn-primary btn-sm" onclick="confirmSubmit(event)">
                                <i class="fas fa-paper-plane"></i> Submit
                            </button>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

@section('js')
<script>
    $(function() {
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $('.atk').select2()
        $("#table-show").DataTable({
            "responsive": false,
            "lengthChange": false,
            "autoWidth": false,
            "info": false,
            "paging": false,
            "searching": false,
            "sorting": false
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
            console.log(dataId)
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
            title: 'Batalkan Permintaan ?',
            text: 'Apakah anda akan membatalkan permintaan barang ini ?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, batalkan!',
            cancelButtonText: 'Batal!'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = event.target.href;
            }
        });
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
            } else if (input.type === 'radio' && input.name === 'confirmAll') {
                const radioButtons = document.querySelectorAll('input[name="confirmAll"]');
                const isChecked = Array.from(radioButtons).some(radio => radio.checked);

                if (!isChecked) {
                    radioButtons.forEach(radio => {
                        radio.style.borderColor = 'red';
                    });
                    isFormValid = false;
                }
            } else {
                input.style.borderColor = '';
            }
        });

        if (isFormValid) {
            Swal.fire({
                title: 'Konfirmasi Penyerahan ATK ?',
                text: '',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, serahkan!',
                cancelButtonText: 'Batal!'
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

<script>
    $(function() {
        $(document).on('click', '.btn-tambah-baris', function(e) {
            e.preventDefault();
            var container = $('.section-item');
            var templateRow = container.first().clone();
            var totalData   = <?php echo $totalRow; ?>;

            templateRow.removeClass('d-none');
            templateRow.find('.num').text(container.length + totalData);

            container.last().after(templateRow);
            toggleHapusBarisButton();
            templateRow.find('.atk-more').removeAttr('disabled').select2();
            templateRow.find('[name="permintaan_id[]"]').removeAttr('disabled');
            templateRow.find('[name="diserahkan[]"]').removeAttr('disabled');
            templateRow.find('[name="disetujui[]"]').removeAttr('disabled').prop('readonly', true);

            templateRow.find('[name="diserahkan[]"]').attr('id', 'give-' + container.length);

            templateRow.find('.add-button-give').attr('data-id', 'give-' + container.length);
            templateRow.find('.min-button-give').attr('data-id', 'give-' + container.length);

            templateRow.find(".add-button-give").click(handleAddButtonClick);
            templateRow.find(".min-button-give").click(handleMinusButtonClick);
        });

        $(document).on('click', '.btn-hapus-baris', function(e) {
            e.preventDefault();
            var container = $('.section-item');
            container.last().remove()
            toggleHapusBarisButton();
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
            console.log(dataId)
            const inputElement = $("#" + dataId);
            let currentValue = parseInt(inputElement.val());
            currentValue++; // Menambah nilai

            inputElement.val(currentValue);
        }


        function handleMinusButtonClick(event) {
            event.preventDefault();
            const dataId = $(this).data("id");
            const inputElement = $("#" + dataId);
            let currentValue = parseInt(inputElement.val());

            if (currentValue > 0) { // Periksa agar tidak kurang dari 1
                currentValue--; // Mengurangkan nilai
            }

            inputElement.val(currentValue);
        }
    });
</script>
@endsection


@endsection
