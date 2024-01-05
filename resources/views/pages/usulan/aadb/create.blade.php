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
                Tambah Usulan Alat Angkutan Darat Bermotor (AADB)
            </div>
            <div class="card-header mt-3">
                <div class="form-group row">
                    <div class="col-md-4">
                        <select id="selectOption" class="form-control" onchange="showSelectedSection()">
                            <option value="101">Permintaan Voucher BBM</option>
                            <option value="102">Pengajuan Servis Kendaraan</option>
                            <option value="103">Pengajuan Perpanjang STNK</option>
                            <option value="104">Pengajuan Pengadaan AADB</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <a id="btn-pilih" class="btn btn-primary"><i class="fas fa-hand-pointer"></i> Pilih</a>
                    </div>
                </div>
            </div>

            <!-- Voucher BBM -->
            <div id="101" class="data-usulan">
                <form id="form-bbm" action="{{ route('usulan.create', ['form' => $form, 'id' => $id]) }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <label class="text-muted">Usulan Permintaan Voucher BBM</label>
                        <h6 class="text-muted small">Mohon pastikan informasi nomor plat kendaraan sudah dilengkapi.</h6>

                        <div class="alert alert-info alert-dismissible mt-4 mb-4" style="font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
                            <div class="row">
                                <div class="col-md-3"></div>
                                <div class="col-md-3"></div>
                            </div>
                            <h6>
                                <i class="icon fas fa-info-circle fa-1x"></i>
                                Pengajuan Voucher BBM untuk bulan berikutnya, maksimal tanggal 20 bulan berjalan
                            </h6>
                            <small> 1. Kendaraan Jabatan : Rp 2.000.000, 2. Kendaraan Operasional : Rp 1.500.000, 3. Kendaraan Bermotor : Rp 200.000</small>
                        </div>

                        <input type="hidden" name="kode_form" value="104">
                        <div class="form-group">
                            <label>Bulan Pengadaan</label>
                            <input type="month" class="form-control" name="bulan_pengadaan" value="{{ \Carbon\Carbon::now()->addMonth()->isoFormat('Y-MM') }}" required>
                        </div>
                        <div class="form-group">
                            <label>Informasi Kendaraan</label>
                            <table class="table table-bordered text-center">
                                <thead class="bg-secondary" style="font-size: 14px;">
                                    <tr>
                                        <th>No</th>
                                        <th>Jenis AADB</th>
                                        <th>No. Plat</th>
                                        <th>Kendaraan</th>
                                        <th>Kualifikasi</th>
                                        <th class="text-center">
                                            <input type="checkbox" class="mr-1 align-middle" id="selectAll" style="scale: 1.7;">
                                            Pilih Semua
                                        </th>
                                    </tr>
                                </thead>
                                <tbody style="font-size: 13px;">
                                    @foreach (Auth::user()->pegawai->unitKerja->aadb as $row)
                                    @php $i = $loop->index; @endphp
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>
                                            <input type="hidden" name="kendaraan_id[]" value="{{ $row->id_kendaraan }}">
                                            {{ ucwords($row->jenis_aadb) }}
                                        </td>
                                        <td>{{ $row->no_plat }}</td>
                                        <td class="text-left">{{ ucwords(strtolower($row->kategori->kategori_aadb)) }} <br> {{ $row->merk_tipe }}</td>
                                        <td>{{ ucwords($row->kualifikasi) }}</td>
                                        <td>
                                            <input type="hidden" value="false" name="status_pengajuan[{{$i}}]">
                                            <input type="checkbox" class="confirm-check" style="scale: 1.7;" name="status_pengajuan[{{$i}}]" id="checkbox_id{{$i}}" value="true">
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-primary" onclick="confirmSubmit(event, 'bbm')">
                            <i class="fas fa-paper-plane"></i> Submit
                        </button>
                    </div>
                </form>
            </div>

            <!-- Servis AADB -->
            <div id="102" class="d-none data-usulan">
                <form id="form-perbaikan" action="{{ route('usulan.create', ['form' => $form, 'id' => $id]) }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <label class="text-muted">Usulan Servis AADB</label>
                        <h6 class="text-muted small">
                            Jika kendaraan tidak muncul, pastikan masa berlaku STNK sudah dilengkapi di halaman informasi kendaraan.
                            <a href="{{ route('aadb.kendaraan.show') }}" class="text-primary">
                                <b><u>(Daftar AADB)</u></b>
                            </a>
                        </h6>

                        <input type="hidden" name="kode_form" value="102">
                        <div class="form-group row mt-4">
                            <label class="col-md-12 text-muted">Kendaraan 1</label>
                            <label class="col-md-2 col-form-label">Kendaraan*</label>
                            <div class="col-md-9">
                                <select class="form-control select-aadb" name="kendaraan_id[]" style="width: 100%;" required>
                                    <option value="">-- Pilih Kendaraan -- </option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row section-perbaikan mt-3">
                            <label class="col-md-12 text-muted d-none title">Kendaraan</label>
                            <label class="col-md-2 col-form-label mb-3 d-none">Kendaraan*</label>
                            <div class="col-md-9 mb-2 d-none">
                                <select class="form-control select2-aadb" name="kendaraan_id[]" style="width: 100%;" required disabled>
                                    <option value="">-- Pilih Kendaraan -- </option>
                                </select>
                            </div>
                            <label class="col-md-2 col-form-label mb-3">Kilometer</label>
                            <div class="col-md-9 mb-3">
                                <div class="input-group">
                                    <input type="number" class="form-control input-format" name="kilometer[]" placeholder="Kilometer Sekarang">
                                    <div class="input-group-append">
                                        <span class="input-group-text border-secondary">KM</span>
                                    </div>
                                </div>
                            </div>
                            <label class="col-md-2 col-form-label mb-3">Tanggal Servis</label>
                            <div class="col-md-4 mb-3">
                                <input type="month" class="form-control" name="tanggal_servis[]">
                                <span class="text-danger small">Bulan terakhir servis</span>
                            </div>
                            <label class="col-md-2 col-form-label mb-3 text-center">Tanggal Ganti Oli</label>

                            <div class="col-md-3 mb-3">
                                <input type="month" class="form-control" name="tanggal_ganti_oli[]">
                                <span class="text-danger small">Bulan terakhir ganti oli</span>
                            </div>
                            <label class="col-md-2 col-form-label mb-3">Keterangan*</label>
                            <div class="col-md-9 mb-3">
                                <textarea class="form-control" name="keterangan_servis[]" rows="3" placeholder="Keterangan Servis, Contoh : Servis rutin/Ganti Oli" required></textarea>
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

            <!-- Perpanjangan STNK -->
            <div id="103" class="d-none data-usulan">
                <form id="form-stnk" action="{{ route('usulan.create', ['form' => $form, 'id' => $id]) }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <label class="text-muted">Usulan Perpanjangan STNK</label>
                        <h6 class="text-muted small">
                            Jika kendaraan tidak muncul, pastikan masa berlaku STNK sudah dilengkapi di halaman informasi kendaraan.
                            <a href="{{ route('aadb.kendaraan.show') }}" class="text-primary">
                                <b><u>(Daftar AADB)</u></b>
                            </a>
                        </h6>

                        <div class="alert alert-info alert-dismissible mt-4 mb-4" style="font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
                            <div class="row">
                                <div class="col-md-3"></div>
                                <div class="col-md-3"></div>
                            </div>
                            <h6>
                                <i class="icon fas fa-info-circle fa-1x"></i>
                                Pilih <i class="fas fa-check-square"></i> untuk STNK kendaraan yang akan diperpanjang.
                            </h6>
                            <small>Perpanjangan STNK dilakukan secara reimburse</small>
                        </div>

                        <input type="hidden" name="kode_form" value="103">
                        <div class="form-group">
                            <label>Informasi Kendaraan</label>
                            <table class="table table-bordered text-center">
                                <thead class="bg-secondary" style="font-size: 14px;">
                                    <tr>
                                        <th>No</th>
                                        <th>No. Plat</th>
                                        <th>Kendaraan</th>
                                        <th>Masa Berlaku STNK</th>
                                        <th class="text-center">
                                            <input type="checkbox" class="mr-1 align-middle" id="selectAllStnk" style="scale: 1.7;">
                                            Pilih Semua
                                        </th>
                                    </tr>
                                </thead>
                                @php $no = 0; @endphp
                                <tbody style="font-size: 13px;">
                                    @foreach (Auth::user()->pegawai->unitKerja->aadb->where('jenis_aadb','bmn')->where('tanggal_stnk', '!=', '')->sortBy('tanggal_stnk') as $row)
                                    @php $i = $loop->index; @endphp
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>
                                            <input type="hidden" name="kendaraan_id[]" value="{{ $row->id_kendaraan }}">
                                            {{ $row->no_plat }}
                                        </td>
                                        <td class="text-left">
                                            {{ ucwords(strtolower($row->kategori->kategori_aadb)) }} <br>
                                            {{ $row->merk_tipe.' '.$row->tahun }}
                                        </td>
                                        <td>
                                            <input type="hidden" name="tanggal_stnk[]" value="{{ $row->tanggal_stnk }}">
                                            {{ \Carbon\carbon::parse($row->tanggal_stnk)->isoFormat('DD MMMM Y') }}
                                        </td>
                                        <td>
                                            <input type="hidden" value="false" name="status[{{$i}}]">
                                            <input type="checkbox" class="confirm-check-stnk" style="scale: 1.7;" name="status[{{$i}}]" id="checkbox_id{{$i}}" value="true">
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-primary" onclick="confirmSubmit(event, 'stnk')">
                            <i class="fas fa-paper-plane"></i> Submit
                        </button>
                    </div>
                </form>
            </div>

            <!-- Pengadaan AADB -->
            <div id="104" class="d-none data-usulan">
                <form id="form-pengadaan" action="{{ route('usulan.create', ['form' => $form, 'id' => $id]) }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <label class="text-muted">Usulan Pengadaan AADB</label>
                        <h6 class="text-muted small">Mohon untuk melengkapi rencana pemakaian dan spesifikasi barang yang dibutuhkan.</h6>

                        <input type="hidden" name="kode_form" value="101">
                        <div class="form-group row mt-4">
                            <label class="col-md-2 col-form-label">Jenis Pengadaan</label>
                            <div class="col-md-9 mb-2">
                                <select name="jenis_aadb" class="form-control">
                                    <option value="sewa">Sewa</option>
                                </select>
                            </div>
                            <label class="col-md-2 col-form-label">Rencana Pengguna</label>
                            <div class="col-md-9 mb-2">
                                <textarea type="text" class="form-control" name="keterangan" placeholder="Contoh : Untuk operasional kantor"></textarea>
                            </div>
                        </div>

                        <div class="form-group row mt-4">
                            <label class="col-md-12 text-muted">Kendaraan 1</label>
                            <label class="col-md-2 col-form-label">Kendaraan*</label>
                            <div class="col-md-9">
                                <select class="form-control select-kategori" name="kendaraan_id[]" style="width: 100%;" required>
                                    <option value="">-- Pilih Kendaraan -- </option>
                                </select>
                            </div>
                        </div>



                        <div class="form-group row section-item mt-4">
                            <label class="col-md-12 text-muted title d-none"></label>

                            <label class="col-md-2 col-form-label mb-3 d-none">Kendaraan*</label>
                            <div class="col-md-9 mb-3 d-none">
                                <select class="form-control select2-kategori" name="kendaraan_id[]" style="width: 100%;" disabled required>
                                    <option value="">-- Pilih Kendaraan -- </option>
                                </select>
                            </div>

                            <label class="col-md-2 mb-3 col-form-label">Merk/Tipe*</label>
                            <div class="col-md-9 mb-3">
                                <input type="text" class="form-control" name="merk_tipe[]" placeholder="Contoh : Honda CRV/Toyota Kijang Innova" required>
                            </div>

                            <label class="col-md-2 mb-3 col-form-label">Kualifikasi*</label>
                            <div class="col-md-9 mb-3">
                                <select name="kualifikasi[]" class="form-control" required>
                                    <option value="">-- Pilih Kualifikasi --</option>
                                    <option value="jabatan">Kendaraan Jabatan</option>
                                    <option value="operasional">Kendaraan Operasional</option>
                                </select>
                            </div>

                            <label class="col-md-2 mb-3 col-form-label">Jumlah</label>
                            <div class="col-md-3 mb-3">
                                <input type="number" class="form-control text-center jumlah" name="jumlah_pengadaan[]" value="1" min="1">
                            </div>

                            <label class="col-md-3 mb-3 col-form-label text-center">Tahun Kendaraan*</label>
                            <div class="col-md-3 mb-3">
                                <input type="number" class="form-control" name="tahun[]" placeholder="Tahun Kendaraan" required>
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


            <!-- <div class="card-header">
                <ul class="nav" id="tab" role="tablist">
                    <li class="nav-item">
                        <a class="btn btn-default mr-2 border-secondary bg-primary active" id="bbm-tab" data-toggle="pill" href="#bbm" role="tab" aria-controls="bbm" aria-selected="false">
                            Usulan Voucher BBM
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-default mr-2 border-secondary" id="perbaikan-tab" data-toggle="pill" href="#perbaikan" role="tab" aria-controls="perbaikan" aria-selected="false">
                            Usulan Servis
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-default mr-2 border-secondary" id="stnk-tab" data-toggle="pill" href="#stnk" role="tab" aria-controls="stnk" aria-selected="false">
                            Usulan Perpanjang STNK
                        </a>
                    </li>
                    <li class="nav-item mr-2">
                        <a class="btn btn-default border-secondary" id="pengadaan-tab" data-toggle="pill" href="#pengadaan" role="tab" ls="pengadaan" aria-selected="true">
                            Usulan Pengadaan
                        </a>
                    </li>
                </ul>
            </div> -->
            <div class="tab-content" id="tabContent">

                <!-- Usulan Voucher BBM -->
                <div class="tab-pane fade active show" id="bbm" role="tabpanel" aria-labelledby="bbm-tab">

                </div>

                <!-- Usulan Perbaikan -->
                <div class="tab-pane fade" id="perbaikan" role="tabpanel" aria-labelledby="perbaikan-tab">

                </div>

                <!-- Usulan STNK -->
                <div class="tab-pane fade" id="stnk" role="tabpanel" aria-labelledby="stnk-tab">

                </div>

                <!-- Usulan Pengadaan -->
                <div class="tab-pane fade " id="pengadaan" role="tabpanel" aria-labelledby="pengadaan-tab">

                </div>
            </div>

        </div>
    </div>
</section>

@section('js')
<script>
    $(document).ready(function() {
        $('#btn-pilih').on('click', function() {
            var selectedValue = $('#selectOption').val();

            // Menyembunyikan semua div-section
            $('.data-usulan').addClass('d-none');

            // Menampilkan div-section yang sesuai dengan nilai yang dipilih
            $('#' + selectedValue).removeClass('d-none');
        });
    });

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
            var value = $(this).val().replace(/[^0-9]/g, '');
            var formattedValue = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            $(this).val(formattedValue);
        });

    })

    const pengadaanTab = document.getElementById('pengadaan-tab');
    const perbaikanTab = document.getElementById('perbaikan-tab');
    const stnkTab = document.getElementById('stnk-tab');
    const bbmTab = document.getElementById('bbm-tab');

    // pengadaanTab.addEventListener('click', function() {
    //     pengadaanTab.classList.add('bg-primary');
    //     perbaikanTab.classList.remove('bg-primary');
    //     stnkTab.classList.remove('bg-primary');
    //     bbmTab.classList.remove('bg-primary');
    // });

    // perbaikanTab.addEventListener('click', function() {
    //     perbaikanTab.classList.add('bg-primary');
    //     pengadaanTab.classList.remove('bg-primary');
    //     stnkTab.classList.remove('bg-primary');
    //     bbmTab.classList.remove('bg-primary');
    // });

    // stnkTab.addEventListener('click', function() {
    //     stnkTab.classList.add('bg-primary');
    //     perbaikanTab.classList.remove('bg-primary');
    //     pengadaanTab.classList.remove('bg-primary');
    //     bbmTab.classList.remove('bg-primary');
    // });

    // bbmTab.addEventListener('click', function() {
    //     bbmTab.classList.add('bg-primary');
    //     stnkTab.classList.remove('bg-primary');
    //     perbaikanTab.classList.remove('bg-primary');
    //     pengadaanTab.classList.remove('bg-primary');
    // });


    $('#selectAll').click(function() {
        if ($(this).prop('checked')) {
            $('.confirm-check').prop('checked', true);
        } else {
            $('.confirm-check').prop('checked', false);
        }
    })

    $('#selectAllStnk').click(function() {
        if ($(this).prop('checked')) {
            $('.confirm-check-stnk').prop('checked', true);
        } else {
            $('.confirm-check-stnk').prop('checked', false);
        }
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
        $('.select-kategori').select2({
            ajax: {
                url: "{{ url('kendaraan/kategori/select') }}",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        _token: CSRF_TOKEN,
                        search: params.term // search term
                    }
                },
                processResults: function(response) {
                    return {
                        results: response
                    }
                },
                cache: true
            }
        })

        $(document).on('click', '.btn-tambah-baris', function(e) {
            e.preventDefault();
            var container = $('.section-item');
            var templateRow = $('.section-item').first().clone();
            templateRow.find('.d-none').removeClass('d-none');
            templateRow.find('[disabled]').removeAttr('disabled');
            templateRow.find(':input').val('');
            templateRow.find('.jumlah').val('1');
            templateRow.find('.title').text('Kendaraan ' + (container.length + 1)).removeClass('d-none');
            $('.section-item:last').after(templateRow);
            toggleHapusBarisButton();

            templateRow.find('.select2-kategori').select2({
                ajax: {
                    url: "{{ url('kendaraan/kategori/select') }}",
                    type: "post",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            _token: CSRF_TOKEN,
                            search: params.term // search term
                        }
                    },
                    processResults: function(response) {
                        return {
                            results: response
                        }
                    },
                    cache: true
                }
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

    })
</script>

<!-- Perbaikan -->
<script>
    let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content')
    $(function() {
        $('.select-aadb').select2({
            ajax: {
                url: "{{ url('kendaraan/select') }}",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        _token: CSRF_TOKEN,
                        search: params.term // search term
                    }
                },
                processResults: function(response) {
                    return {
                        results: response
                    }
                },
                cache: true
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
        templateRow.find('.title').text('Kendaraan ' + (container.length + 1));
        $('.section-perbaikan:last').after(templateRow);
        toggleHapusBarisButton();

        templateRow.find('.input-format').on('input', function() {
            var value = $(this).val().replace(/[^0-9]/g, '');
            var formattedValue = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            $(this).val(formattedValue);
        });

        templateRow.find('.select2-aadb').select2({
            ajax: {
                url: "{{ url('kendaraan/select') }}",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        _token: CSRF_TOKEN,
                        search: params.term // search term
                    }
                },
                processResults: function(response) {
                    return {
                        results: response
                    }
                },
                cache: true
            }
        });

        templateRow.find('.select2-merktipe').select2({
            // Konfigurasi Select2 untuk elemen .select2-merktipe
        });
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
