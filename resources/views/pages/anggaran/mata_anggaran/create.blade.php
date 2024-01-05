@extends('layout.app')

@section('content')

<!-- Content Header -->
<section class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="text-capitalize">SIPORSAT</h1>
                <h5>Sistem Pengelolaan Operasional Perkantoran Terpusat</h5>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right text-capitalize">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('mata_anggaran.show') }}">Mata Anggaran</a></li>
                    <li class="breadcrumb-item active">Tambah Mata Anggaran {{ $id == 'ctg' ? 'Kategori' : $id }}</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<!-- Content Header -->

<section class="content">
    <div class="container">
        <div class="card card-primary card-outline">
            <div class="card-header text-capitalize">
                <h3 class="card-title">Tambah Mata Anggaran {{ $id == 'ctg' ? 'Kategori' : $id }}</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <form action="{{ route('mata_anggaran.post', $id) }}" method="POST">
                @csrf
                <div class="card-body">
                    @if ($id == '*')

                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Unit Kerja*</label>
                        <div class="col-md-10">
                            <select id="unit-kerja" name="unit_kerja_id" class="form-control">
                                <option value="">-- PILIH UNIT KERJA --</option>
                            </select>
                        </div>
                    </div>

                    @endif
                    @if ($id == 1 && $id != 'ctg')
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Unit Kerja*</label>
                        <div class="col-md-10">
                            <select id="anggaran-unit-kerja" name="unit_kerja_id" class="form-control" required>
                                <option value="">-- PILIH UNIT KERJA --</option>
                            </select>
                        </div>
                    </div>
                    @endif
                    @if ($id != 1 && $id != 'ctg')
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Jenis*</label>
                        <div class="col-md-10">
                            <select id="mata-anggaran" name="mta_id" class="form-control">
                                <option value="">-- PILIH JENIS MATA ANGGARAN --</option>
                            </select>
                        </div>
                    </div>
                    @endif
                    @if ($id == 5 || $id == '*')
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Kategori*</label>
                        <div class="col-md-10">
                            <select id="kategori-anggaran" name="mta_ctg_id" class="form-control">
                                <option value="">-- PILIH KATEGORI MATA ANGGARAN --</option>
                            </select>
                        </div>
                    </div>
                    @endif
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Kode*</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" name="kode_mta" placeholder="Kode Mata Anggaran" required>
                        </div>
                    </div>
                    @if ($id != 5)
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Deskripsi*</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" name="nama_mta" placeholder="Nama Mata Anggaran" required>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary" onclick="return confirm('Tambah Baru ?')">
                        <i class="fas fa-paper-plane fa-1x"></i> <b>Submit</b>
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

@section('js')
<script>
    // Select
    $(function() {
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content')
        $("#unit-kerja").select2({
            ajax: {
                url: "{{ url('unit-kerja/select/') }}",
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

        $("#anggaran-unit-kerja").select2({
            ajax: {
                url: "{{ url('anggaran-unit-kerja/select/') }}",
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

        $("#mata-anggaran").select2({
            ajax: {
                url: "{{ url('mata-anggaran/select/'. $id) }}",
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

        $("#kategori-anggaran").select2({
            ajax: {
                url: "{{ url('kategori-anggaran/select/'. $id) }}",
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

    $(document).ready(function() {
        $('.input-format').on('input', function() {
            // Menghapus karakter selain angka (termasuk tanda titik koma sebelumnya)
            var value = $(this).val().replace(/[^0-9]/g, '');

            // Format dengan menambahkan titik koma setiap tiga digit
            var formattedValue = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

            // Memasukkan nilai yang sudah diformat kembali ke input
            $(this).val(formattedValue);
        });
    });
</script>
@endsection

@endsection
