@extends('layout.app')

@section('content')

<!-- Content Header -->
<section class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-8">
                <h1 class="text-capitalize font-weight-bold">Keranjang</h1>
                <h4 class="small text-muted mt-2">Harga barang dapat berubah, harga realisasi dapat dilihat
                    pada detail pengajuan ATK, pada kolom <b>Harga Realisasi.</b></h4>
                <h4 class="small text-muted mt-2">Setelah klik <b>Submit</b>, maka barang yang di keranjang akan hilang</h4>
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

        <form id="form" action="{{ route('usulan.create', ['form' => $form, 'id' => $id]) }}" method="POST">
            @csrf
            <input type="hidden" name="kode_form" value="301">
            <div class="row">
                <div class="col-md-7">
                    <div class="row">
                        @php $no = 1; @endphp
                        @foreach (Auth::user()->keranjang as $row)
                        <div class="col-md-12 media">
                            <span class="mt-3 mr-3">{{ $no++ }}</span>
                            @if ($row->foto_atk)
                            <img src="{{ asset('storage/files/foto_atk/'. $row->foto_atk) }}" class="img-fluid img-size-50 mr-3" alt="">
                            @else
                            <img src="https://cdn-icons-png.flaticon.com/512/679/679821.png" class="img-fluid img-size-50 mr-3" alt="">
                            @endif
                            <div class="media-body">
                                <div class="form-group row">
                                    <div class="col-md-7">
                                        <h6 class="m-0 text-xs">{{ $row->atk->kategori->kategori_atk }}</h6>
                                        <h6 class="m-0 text-md">{{ $row->atk->deskripsi }}</h6>
                                        <h6 class="mt-2 text-xs text-muted">Rp {{ number_format($row->atk->satuanPick->first()?->harga, 0, ',', '.').' / '.$row->atk->satuanPick->first()?->satuan }}</h6>
                                        <input type="hidden" name="id_keranjang[]" value="{{ $row->id_keranjang }}">
                                        <input type="hidden" name="atk[]" value="{{ $row->atk_id }}">
                                        <input type="hidden" name="harga[]" value="{{ $row->atk->satuanPick->first()?->harga }}">
                                    </div>
                                    <div class="col-md-5">
                                        <div class="input-group mb-3">
                                            <div class="input-group-append">
                                                <div class="input-group-text rounded-left">
                                                    <a type="button" class="add-button" data-id="{{ $row->id_keranjang }}" href="{{ route('atk.keranjang.update', ['aksi' => 'min', 'id' => $row->id_keranjang]) }}">
                                                        <i class="fas fa-minus-circle" aria-hidden="true"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            <input type="text" class="form-control text-center bg-white" id="kuantitas-{{ $row->id_keranjang }}" name="jumlah[]" value="{{ $row->kuantitas }}" min="1" readonly>
                                            <div class="input-group-append">
                                                <div class="input-group-text rounded-left">
                                                    <a type="button" class="add-button" data-id="{{ $row->id_keranjang }}" href="{{ route('atk.keranjang.update', ['aksi' => 'plus', 'id' => $row->id_keranjang]) }}">
                                                        <i class="fas fa-plus-circle" aria-hidden="true"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="input-group-append ml-3">
                                                <div class="input-group-text rounded-left">
                                                    <a href="{{ route('atk.keranjang.remove', $row->id_keranjang) }}" onclick="confirmRemove(event)">
                                                        <i class="fas fa-trash-alt text-danger" aria-hidden="true"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-md-1"></div>
                <div class="col-md-4">
                    <div class="card rounded position-fixed" style="width: 24%;">
                        <div class="card-body">
                            <div class="form-group">
                                <span class="text-red" style="font-size: 10px;">
                                    *Pengajuan bulan berikut, maks. tanggal 20 bulan berjalan
                                </span>
                                <label>Tanggal : {{ \Carbon\carbon::now()->isoFormat('DD MMMM Y') }}</label>
                            </div>
                            <div class="form-group">
                                <label>Rencana Pengguna</label>
                                <textarea type="month" name="keterangan" class="form-control" rows="5" placeholder="Permintaan ATK bulan September 2023" required></textarea>
                            </div>
                            <div class="form-group text-right mt-4">
                                <button type="submit" class="btn btn-primary btn-sm" onclick="confirmSubmit(event)">
                                    <i class="fas fa-paper-plane"></i> Submit
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

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
            var value = $(this).val().replace(/[^0-9]/g, '');
            var formattedValue = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            $(this).val(formattedValue);
        });
    })

    // alert
    $(document).ready(function() {
        $(".add-button").click(function(event) {
            event.preventDefault(); // Mencegah tindakan default dari tombol
            const dataId = $(this).data("id");
            const link = $(this).attr('href');
            $.ajax({
                url: link,
                type: "GET",
                success: function(response) {
                    const updatedKuantitas = response.updatedKuantitas.kuantitas;
                    $(`#kuantitas-${dataId}`).val(updatedKuantitas);
                }
            })
        })
    })

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
