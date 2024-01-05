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
                            <div class="col-md-12 mb-2">Golongan {{ $penghuni->rumahDinas->golongan }}</div>
                            <div class="col-md-3">Lokasi Kota</div>:
                            <div class="col-md-4">{{ $penghuni->rumahDinas->lokasi_kota }}</div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3">Luas Bangunan</div>:
                            <div class="col-md-4">{{ $penghuni->rumahDinas->luas_bangunan }} m<sup>2</sup></div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3">Luas Tanah</div>:
                            <div class="col-md-4">{{ $penghuni->rumahDinas->luas_tanah }} m<sup>2</sup></div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3">Kondisi</div>:
                            <div class="col-md-4">{{ $penghuni->rumahDinas->kondisi }}</div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3">Alamat</div>:
                            <div class="col-md-8 text-justify">{{ $penghuni->rumahDinas->alamat }}</div>
                        </div>
                    </div>
                    <div class="col-md-4 col-6 mt-3">
                        @foreach($penghuni->rumahDinas->fotoRumah->take(1) as $row)
                        <img src="{{ asset('storage/' . $row->nama_file) }}" class="img-fluid">
                        @endforeach
                    </div>
                </div>
            </div>
            <form action="{{ route('penghuni.edit', $penghuni->id_penghuni) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="rumah_id" value="{{ $penghuni->rumah_id }}">
                <div class="card-body">
                    <span class="pb-5">
                        Edit Penghuni
                    </span>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Unit Kerja*</label>
                        <div class="col-md-10">
                            <select id="unit-kerja" name="unit_kerja_id" class="form-control">
                                <option value="{{ $penghuni->pegawai->unitKerja->id_unit_kerja }}">
                                    {{ strtoupper($penghuni->pegawai->unitKerja->nama_unit_kerja) }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Pegawai*</label>
                        <div class="col-md-10">
                            <select id="pegawai" name="pegawai_id" class="form-control">
                                <option value="{{ $penghuni->pegawai_id }}">
                                    {{ strtoupper($penghuni->pegawai->nip.' . '.$penghuni->pegawai->nama_pegawai) }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row mt-2">
                        <label class="col-md-2 col-form-label">Nomor SIP*</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" name="nomor_sip" value="{{ $penghuni->nomor_sip }}">
                        </div>
                    </div>
                    <div class="form-group row mt-2">
                        <label class="col-md-2 col-form-label">Tanggal Mulai*</label>
                        <div class="col-md-4">
                            <input type="date" class="form-control" name="tanggal_masuk" value="{{ $penghuni->tanggal_masuk }}">
                        </div>
                        <label class="col-md-2 col-form-label">Tanggal Selesai*</label>
                        <div class="col-md-4">
                            <input type="date" class="form-control" name="tanggal_keluar" value="{{ $penghuni->tanggal_keluar }}">
                        </div>
                    </div>
                    <div class="form-group row mt-2">
                        <label class="col-md-2 col-form-label">Sertifikat</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" name="sertifikat" value="{{ $penghuni->sertifikat }}">
                        </div>
                    </div>
                    <div class="form-group row mt-2">
                        <label class="col-md-2 col-form-label">NOP PBB</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" name="pbb" value="{{ $penghuni->pbb }}">
                        </div>
                    </div>
                    <div class="form-group row mt-2">
                        <label class="col-md-2 col-form-label">IMB </label>
                        <div class="col-md-10 mt-2">
                            <span class="mr-4">
                                <input type="radio" name="imb" value="ada" <?php echo $penghuni->imb == 'ada' ? 'checked' : ''; ?>> Ada
                            </span>
                            <span class="mr-3">
                                <input type="radio" name="imb" value="tidak ada" <?php echo $penghuni->imb == 'tidak ada' ? 'checked' : ''; ?>> Tidak Ada
                            </span>
                        </div>
                    </div>
                    <div class="form-group row mt-2">
                        <label class="col-md-2 col-form-label">Status Penghuni</label>
                        <div class="col-md-10 mt-2">
                            <span class="mr-4">
                                <input type="radio" name="status_penghuni" value="true" <?php echo $penghuni->status_penghuni == 'true' ? 'checked' : ''; ?>> Aktif
                            </span>
                            <span class="mr-3">
                                <input type="radio" name="status_penghuni" value="false" <?php echo $penghuni->status_penghuni == 'false' ? 'checked' : ''; ?>> Tidak Aktif
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary" onclick="return confirm('Simpan Perubahan ?')">
                        <i class="fas fa-save fa-1x"></i> <b>Simpan</b>
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
