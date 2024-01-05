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
                    <li class="breadcrumb-item"><a href="{{ route('rumah_dinas.show') }}">Daftar Rumah Dinas</a></li>
                    <li class="breadcrumb-item active">Tambah Penghuni</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<!-- Content Header -->

<section class="content">
    <div class="container">

        <div class="card" style="border-radius: 10px;">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-8 col-6">
                        <div class="form-group row">
                            <div class="col-md-12 mb-2">Golongan {{ $rumah->golongan }}</div>
                            <div class="col-md-3">Lokasi Kota</div>:
                            <div class="col-md-4">{{ $rumah->lokasi_kota }}</div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3">Luas Bangunan</div>:
                            <div class="col-md-4">{{ $rumah->luas_bangunan }} m<sup>2</sup></div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3">Luas Tanah</div>:
                            <div class="col-md-4">{{ $rumah->luas_tanah }} m<sup>2</sup></div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3">Kondisi</div>:
                            <div class="col-md-4">{{ $rumah->kondisi }}</div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3">Alamat</div>:
                            <div class="col-md-8 text-justify">{{ $rumah->alamat }}</div>
                        </div>
                    </div>
                    <div class="col-md-4 col-6 mt-3">
                        @foreach($rumah->fotoRumah->take(1) as $row)
                        <img src="{{ asset('storage/' . $row->nama_file) }}" class="img-fluid">
                        @endforeach
                    </div>
                </div>
            </div>
            <form action="{{ route('penghuni.create', $rumah->id_rumah) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <span class="pb-5">
                        Tambah Penghuni
                    </span>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Unit Kerja*</label>
                        <div class="col-md-10">
                            <select id="unit-kerja" name="unit_kerja_id" class="form-control">
                                <option value="">-- PILIH UNIT KERJA --</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Pegawai*</label>
                        <div class="col-md-10">
                            <select id="pegawai" name="pegawai_id" class="form-control" required>
                                <option value="">-- PILIH PEGAWAI --</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row mt-2">
                        <label class="col-md-2 col-form-label">Nomor SIP*</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" name="nomor_sip" placeholder="Contoh : HK.01.07/II/2031/2020">
                        </div>
                    </div>
                    <div class="form-group row mt-2">
                        <label class="col-md-2 col-form-label">Tanggal Mulai*</label>
                        <div class="col-md-4">
                            <input type="date" class="form-control" name="tanggal_masuk">
                        </div>
                        <label class="col-md-2 col-form-label">Tanggal Selesai*</label>
                        <div class="col-md-4">
                            <input type="date" class="form-control" name="tanggal_keluar">
                        </div>
                    </div>
                    <div class="form-group row mt-2">
                        <label class="col-md-2 col-form-label">Sertifikat</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" name="sertifikat" placeholder="Contoh : HK.01.07/II/2031/2020">
                        </div>
                    </div>
                    <div class="form-group row mt-2">
                        <label class="col-md-2 col-form-label">NOP PBB</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" name="pbb" placeholder="Contoh :2.75.020.003">
                        </div>
                    </div>
                    <div class="form-group row mt-2">
                        <label class="col-md-2 col-form-label">IMB</label>
                        <div class="col-md-10 mt-2">
                            <span class="mr-4">
                                <input type="radio" name="imb" value="ada" required> Ada
                            </span>
                            <span class="mr-3">
                                <input type="radio" name="imb" value="tidak ada" required> Tidak Ada
                            </span>
                        </div>
                    </div>
                    <div class="form-group row mt-2">
                        <label class="col-md-2 col-form-label">Status Penghuni</label>
                        <div class="col-md-10 mt-2">
                            <span class="mr-4">
                                <input type="radio" name="status_penghuni" value="true"> Aktif
                            </span>
                            <span class="mr-3">
                                <input type="radio" name="status_penghuni" value="false"> Tidak Aktif
                            </span>
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
    $(function() {
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content')
        $("#unit-kerja").select2({
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
        $("#pegawai").select2();
        $('#unit-kerja').change(function() {
            var unit_kerja = $(this).val()

            if (unit_kerja) {
                $.ajax({
                    type: "GET",
                    url: "/pegawai/select/" + unit_kerja,
                    dataType: 'JSON',
                    success: function(res) {
                        if (res) {
                            $("#pegawai").empty();
                            $.each(res, function(index, pegawai) {
                                var nama_pegawai = pegawai.nama_pegawai.toUpperCase();
                                $("#pegawai").append(
                                    '<option value="' + pegawai.id_pegawai + '">' + pegawai.nip + ' - ' + nama_pegawai + '</option>'
                                );
                            });
                        } else {
                            $("#pegawai").empty();
                        }
                    }
                });
            } else {
                $("#pegawai").empty();
            }
        });

    })
</script>
@endsection

@endsection
