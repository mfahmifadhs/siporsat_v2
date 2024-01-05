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
                    <li class="breadcrumb-item"><a href="{{ route('pegawai.show') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Tambah Pegawai</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<!-- Content Header -->

<section class="content">
    <div class="container">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Tambah Pegawai</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <form action="{{ route('pegawai.post') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Unit Kerja*</label>
                        <div class="col-md-10">
                            <select id="unit-kerja" name="unit_kerja_id" class="form-control">
                                <option value="">-- PILIH UNIT KERJA --</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Nomor Induk Pegawai</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="nip" placeholder="Nomor Induk Pegawai">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Nama Pegawai*</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" name="nama_pegawai" placeholder="Nama Pegawai" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Jabatan*</label>
                        <div class="col-md-10">
                            <select class="form-control" name="jabatan_id" required>
                                <option value="">-- Pilih Jabatan --</option>
                                @foreach ($jabatan as $row)
                                <option value="{{ $row->id_jabatan }}">
                                    {{ $row->nama_jabatan }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Keterangan Jabatan*</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" name="nama_jabatan" placeholder="Contoh : Kasubag Adum, Kabag RT, Staff" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Status Pegawai*</label>
                        <div class="col-md-4">
                            <select class="form-control" name="status_id" required>
                                <option value="">-- Pilih Status --</option>
                                @foreach ($status as $row)
                                <option value="{{ $row->id_status }}">
                                    {{ $row->nama_status }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
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
    $(function () {
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content')
        $("#unit-kerja").select2({
            ajax: {
                url: "{{ url('unit-kerja/select') }}",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                return {
                    _token: CSRF_TOKEN,
                    search: params.term // search term
                }
                },
                processResults: function (response) {
                return {
                    results: response
                }
                },
                cache: true
            }
        })
    })
</script>
@endsection

@endsection
