@extends('layout.app')

@section('content')

<!-- Content Header -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="text-capitalize">SIPORSAT</h1>
                <h5>Sistem Pengelolaan Operasional Perkantoran Terpusat</h5>
                <ol class="breadcrumb text-capitalize">
                    <li class="breadcrumb-item"><a href="{{ route('aadb.home') }}">Beranda</a></li>
                    <li class="breadcrumb-item active">Daftar AADB</li>
                </ol>
            </div>
            <div class="col-sm-6 mt-3">
                <div class="form-group text-right">
                    <a href="{{ route('aadb.kendaraan.create') }}" class="btn btn-add">
                        <span class="btn btn-primary rounded-0 mr-1"><i class="fas fa-plus"></i></span>
                        <small>Tambah</small>
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

        <div class="card card-primary card-outline">
            <div class="card-header">
                <label class="card-title">Daftar Alat Angkutan Darat Bermotor (AADB)</label>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-header mt-2">
                <form action="{{ route('aadb.kendaraan.show') }}" method="POST">
                    @csrf
                    <input type="hidden" name="filter" value="true">
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-5 col-12">
                            <div class="form-group">
                                <select name="unit_kerja" class="form-control form-control-md" <?php echo Auth::user()->role_id == 4 && Auth::user()->pegawai->unit_kerja_id != 465930 ? 'disabled' : '' ?>>
                                    @if(Auth::user()->role_id == 4 && Auth::user()->pegawai->unit_kerja_id != 465930)
                                    <option value="">{{ strtoupper(Auth::user()->pegawai->unitKerja->nama_unit_kerja) }}</option>
                                    @else
                                    @if (!$ukerPick) <option value="">SELURUH UNIT KERJA</option>
                                    @else
                                    <option value="{{ $ukerPick->id_unit_kerja }}">
                                        {{ strtoupper($ukerPick->nama_unit_kerja) }}
                                    </option>
                                    @endif
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-5 col-12">
                            <div class="form-group">
                                <select name="kendaraan" class="form-control form-control-md">
                                    @if (!$aadbPick) <option value="">SELURUH KENDARAAN</option>
                                    @else
                                    <option value="{{ $aadbPick->id_kategori }}">
                                        {{ strtoupper($aadbPick->kategori_aadb) }}
                                    </option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 col-12 text-right">
                            <div class="form-group">
                                <button class="btn btn-primary btn-md font-weight-bold">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                                <a href="{{ route('aadb.kendaraan.show') }}" class="btn btn-danger btn-md font-weight-bold">
                                    <i class="fas fa-undo"></i> Muat
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-body">
                <div class="alert alert-secondary loading" role="alert">
                    Sedang menyiapkan data ...
                </div>
                <div class="table-responsive">
                    <table id="table-aadb" class="table table-bordered text-center" style="font-size: 13px;">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>No</th>
                                <th style="width: 15%;">Kendaraan</th>
                                <th style="width: 20%;">Merk/Tipe</th>
                                <th style="width: 10%;">No. Plat</th>
                                <th style="width: 20%;">Keterangan</th>
                                <th style="width: 5%;">Kondisi</th>
                                <th>Unit Kerja</th>
                                <th>Aksi</th>
                                <!-- Export -->
                                <th>No</th>
                                <th>KUALIFIKASI</th>
                                <th>KODE</th>
                                <th>KENDARAAN</th>
                                <th>MERK/TIPE</th>
                                <th>NO.PLAT</th>
                                <th>NO.PLAT DINAS</th>
                                <th>TANGGAL STNK</th>
                                <th>TANGGAL PEROLEHAN</th>
                                <th>NILAI PEROLEHAN</th>
                                <th>KETERANGAN</th>
                                <th>KONDISI</th>
                                <th>STATUS</th>
                                <th>UNIT KERJA</th>
                            </tr>
                        </thead>
                        <?php $no = 1; ?>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

@section('js')
<script>
    // Menampilkan daftar
    $(function() {
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content')
        $('[name="unit_kerja"]').select2({
            ajax: {
                url: "{{ url('unit-kerja/select') }}",
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
        $('[name="kendaraan"]').select2({
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

    })
</script>
<script>
    $(document).ready(function() {
        $(function() {

            var currentdate = new Date();
            var datetime = "Tanggal: " + currentdate.getDate() + "/" +
                (currentdate.getMonth() + 1) + "/" +
                currentdate.getFullYear() + " \n Pukul: " +
                currentdate.getHours() + ":" +
                currentdate.getMinutes() + ":" +
                currentdate.getSeconds()

            $("#table-aadb").DataTable({
                "responsive": false,
                "lengthChange": true,
                "autoWidth": false,
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "Semua"]
                ],
                columnDefs: [{
                        targets: 8,
                        data: null,
                        defaultContent: `<a type="button" class="btn btn-primary btn-sm" data-toggle="dropdown">
                            <i class="fas fa-bars"></i>
                        </a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item btn btn-detail">
                                <i class="fas fa-info-circle"></i> Detail
                            </a>
                            <a class="dropdown-item btn btn-edit">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a class="dropdown-item btn btn-hapus">
                                <i class="fas fa-trash"></i> Hapus
                            </a>
                        </div>`,
                    },
                    {
                        "bVisible": false,
                        "aTargets": [0, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22]
                    }
                ],
                "createdRow": function(row, data, dataIndex) {
                    let userUnitKerja = "{{ Auth::user()->pegawai->unitKerja->nama_unit_kerja }}";
                    let rowUnitKerja = data[7]; // Ganti dengan indeks kolom yang sesuai

                    let editButton = row.querySelector(".btn-edit");
                    let deleteButton = row.querySelector(".btn-hapus");

                    if (userUnitKerja == rowUnitKerja) {
                        editButton.classList.remove("d-none");
                        deleteButton.classList.remove("d-none");
                    } else {
                        editButton.classList.add("d-none");
                        deleteButton.classList.add("d-none");
                    }
                },
                order: [
                    [1, 'asc']
                ],
                buttons: [{
                        extend: 'pdf',
                        text: ' <i class="fas fa-file-pdf"></i> PDF',
                        className: 'bg-danger',
                        title: 'Daftar AADB',
                        exportOptions: {
                            columns: [11, 12, 13, 14, 20, 22]
                        },
                        messageTop: datetime
                    },
                    {
                        extend: 'excel',
                        text: ' <i class="fas fa-file-excel"></i> Excel',
                        className: 'bg-success',
                        title: 'Daftar AADB',
                        exportOptions: {
                            columns: [9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21.22]
                        },
                        messageTop: datetime
                    }
                ],
                "bDestroy": true
            }).buttons().container().appendTo('#table-aadb_wrapper .col-md-6:eq(0)');
        });
        // $('.table-container').hide()
        setTimeout(showTable, 1000);
    })

    function showTable() {
        let dataTable = $('#table-aadb').DataTable();
        let dataAadb = <?php echo json_encode($aadb); ?>;

        dataTable.clear();
        let no = 1;
        dataAadb.forEach(element => {
            dataTable.row.add([
                element.id_kendaraan,
                no,
                '<div class="text-left">' + element.kategori_id + '<br>' + element.kategori_aadb + '</div>',
                '<div class="text-left">' + (element.merk_tipe ? element.merk_tipe.toString() + ' ' : '') + (element.tahun ? element.tahun : '') + '</div>',
                element.no_plat,
                element.keterangan,
                element.kondisi_id == 1 ? 'Baik' : element.kondisi_id == 2 ? 'Rusak Ringan' : 'Rusak Berat',
                element.nama_unit_kerja,
                '',
                no,
                element.kualifikasi,
                element.kategori_id,
                element.kategori_aadb,
                (element.merk_tipe ? element.merk_tipe.toString() + ' ' : '') + (element.tahun ? element.tahun : ''),
                element.no_plat,
                element.no_plat_dinas,
                element.tanggal_stnk,
                element.tanggal_perolehan,
                `Rp ` + String(element.nilai_perolehan).replace(/(.)(?=(\d{3})+$)/g, '$1,'),
                element.keterangan,
                element.nama_kondisi,
                element.nama_status,
                element.nama_unit_kerja
            ]);
            no++;
        });
        dataTable.draw();
        $('.loading').hide();
    }

    $('#table-aadb tbody').on('click', '.btn-detail', function() {
        let dataTable = $('#table-aadb').DataTable();
        let row = dataTable.row($(this).parents('tr')).data();
        window.location.href = "{{ route('aadb.kendaraan.detail', '') }}" + '/' + row[0];
    });

    $('#table-aadb tbody').on('click', '.btn-edit', function() {
        let dataTable = $('#table-aadb').DataTable();
        let row = dataTable.row($(this).parents('tr')).data();
        window.location.href = "{{ route('aadb.kendaraan.edit', '') }}" + '/' + row[0];
    });

    $('#table-aadb tbody').on('click', '.btn-hapus', function() {
        let dataTable = $('#table-aadb').DataTable();
        let row = dataTable.row($(this).parents('tr')).data();
        let id = row[0];

        Swal.fire({
            title: 'Hapus Kendaraan?',
            text: 'Anda yakin ingin menghapus kendaraan ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal!',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "{{ route('aadb.kendaraan.delete', '') }}" + '/' + id;
            }
        });
    });
</script>
@endsection

@endsection
