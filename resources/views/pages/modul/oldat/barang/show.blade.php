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
                    <li class="breadcrumb-item"><a href="{{ route('oldat.home') }}">Beranda</a></li>
                    <li class="breadcrumb-item active">Daftar Oldat & Meubelair</li>
                </ol>
            </div>
            <div class="col-sm-6 mt-3">
                <div class="form-group text-right">
                    <a href="{{ route('oldat.barang.create') }}" class="btn btn-add">
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
                <label class="card-title">Daftar BMN Olah Data & Meubelair</label>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-header mt-3">
                <form action="{{ route('oldat.barang.filter') }}" method="POST">
                    @csrf
                    <input type="hidden" name="filter" value="true">
                    <div class="row">
                        <div class="col-md-5 col-12">
                            <div class="form-group">
                                <select name="unit_kerja" class="form-control form-control-sm" <?php echo Auth::user()->role_id == 4 ? 'disabled' : '' ?>>
                                    @if(Auth::user()->role_id == 4)
                                    <option value="{{ Auth::user()->pegawai->unit_kerja_id }}">
                                        {{ strtoupper(Auth::user()->pegawai->unitKerja->nama_unit_kerja) }}
                                    </option>
                                    @else
                                    @if (!$ukerPick)
                                        <option value="">SELURUH UNIT KERJA</option>
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
                                <select name="barang" class="form-control form-control-sm">
                                    @if (!$oldatPick)
                                        <option value="">SELURUH BARANG</option>
                                    @else
                                    <option value="{{ $oldatPick->kode_kategori }}">
                                        {{ strtoupper($oldatPick->kode_kategori.' - '.$oldatPick->kategori_barang) }}
                                    </option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 col-12 text-center">
                            <div class="form-group">
                                <button class="btn btn-primary btn-sm font-weight-bold">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                                <a href="{{ route('oldat.barang.show') }}" class="btn btn-danger btn-sm font-weight-bold">
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
                <table id="table-barang" class="table table-bordered text-center" style="font-size: 12px;">
                    <thead>
                        <tr>
                            <th>id Barang</th>
                            <th class="text-center">No</th>
                            <th>Kode Barang</th>
                            <th>NUP</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Merk/Tipe</th>
                            <th>Nilai Perolehan</th>
                            <th>Tahun Perolehan</th>
                            <th>Kondisi</th>
                            <th>Unit Kerja</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <?php $no = 1; ?>
                    <tbody></tbody>
                </table>
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
        $('[name="barang"]').select2({
            ajax: {
                url: "{{ url('oldat/kategori/daftar') }}",
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
            $("#table-barang").DataTable({
                "responsive": true,
                "lengthChange": true,
                "autoWidth": false,
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "Semua"]
                ],
                columnDefs: [{
                        targets: -1,
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
                        "aTargets": [0, 2, 3]
                    }
                ],
                order: [
                    [1, 'asc']
                ],
                buttons: [{
                        extend: 'pdf',
                        text: ' <i class="fas fa-file-pdf"></i> PDF',
                        className: 'bg-danger',
                        title: 'Daftar Barang Olah Data BMN & Meubelair',
                        exportOptions: {
                            columns: [4, 5, 6, 10]
                        },
                        messageTop: datetime
                    },
                    {
                        extend: 'excel',
                        text: ' <i class="fas fa-file-excel"></i> Excel',
                        className: 'bg-success',
                        title: 'Daftar Barang Olah Data BMN & Meubelair',
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
                        },
                        messageTop: datetime
                    }
                ],
                "bDestroy": true
            }).buttons().container().appendTo('#table-barang_wrapper .col-md-6:eq(0)');
        });
        // $('.table-container').hide()
        setTimeout(showTable, 1000);
    })

    function showTable() {
        let dataTable = $('#table-barang').DataTable();
        let dataBarang = <?php echo json_encode($barang); ?>;

        dataTable.clear();
        let no = 1;
        dataBarang.forEach(element => {
            dataTable.row.add([
                element.id_barang,
                no,
                element.kategori_id,
                element.nup,
                element.kategori_id + `.` + element.nup,
                `<td class="text-left">` + element.kategori_barang + `</td>`,
                `<span class="text-left">` + element.merk_tipe?.toString() || '' + `</span>`,
                `Rp ` + String(element.nilai_perolehan).replace(/(.)(?=(\d{3})+$)/g, '$1,'),
                element.tahun_perolehan,
                element.kondisi_id == 1 ? 'Baik' : element.kondisi_id == 2 ? 'Rusak Ringan' : 'Rusak Berat',
                element.nama_unit_kerja
            ]);
            no++;
        });
        dataTable.draw();
        $('.loading').hide();
    }

    $('#table-barang tbody').on('click', '.btn-detail', function() {
        let dataTable = $('#table-barang').DataTable();
        let row = dataTable.row($(this).parents('tr')).data();
        console.log('datas', row[0]);
        window.location.href = "{{ route('oldat.barang.detail', '') }}" + '/' + row[0];
    });

    $('#table-barang tbody').on('click', '.btn-edit', function() {
        let dataTable = $('#table-barang').DataTable();
        let row = dataTable.row($(this).parents('tr')).data();
        console.log('datas', row[0]);
        window.location.href = "{{ route('oldat.barang.edit', '') }}" + '/' + row[0];
    });

    $('#table-barang tbody').on('click', '.btn-hapus', function() {
        let dataTable = $('#table-barang').DataTable();
        let row = dataTable.row($(this).parents('tr')).data();
        let id = row[0];

        Swal.fire({
            title: 'Hapus Barang?',
            text: 'Anda yakin ingin menghapus barang ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal!',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "{{ route('oldat.barang.delete', '') }}" + '/' + id;
            }
        });
    });
</script>
@endsection

@endsection
