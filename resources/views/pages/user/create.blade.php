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
                    <li class="breadcrumb-item"><a href="{{ route('user.show') }}">Daftar Pengguna</a></li>
                    <li class="breadcrumb-item active">Tambah Pengguna</li>
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
                <h3 class="card-title">Tambah Pengguna</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <form action="{{ route('user.post') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Role*</label>
                        <div class="col-md-10 mt-2">
                            <span class="mr-4">
                                <input type="radio" name="role_id" value="1" required> SUPER ADMIN
                            </span>
                            <span class="mr-4">
                                <input type="radio" name="role_id" value="2" required> ADMIN USER
                            </span>
                            <span class="mr-4">
                                <input type="radio" name="role_id" value="3" required> SUPER USER
                            </span>
                            <span class="mr-4">
                                <input type="radio" name="role_id" value="4" required> USER
                            </span>
                        </div>
                    </div>
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
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Password*</label>
                        <div class="col-md-10">
                            <div class="input-group">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan Password">
                                <div class="input-group-append">
                                    <span class="input-group-text border-secondary">
                                        <i class="fa fa-eye-slash" id="eye-icon-pass"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Konfirmasi Password*</label>
                        <div class="col-md-10">
                            <div class="input-group">
                                <input type="password" class="form-control" id="conf-password" name="conf_password" placeholder="Konfirmasi Password" required>
                                <div class="input-group-append">
                                    <span class="input-group-text border-secondary">
                                        <i class="fa fa-eye-slash" id="eye-icon-conf"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Status Pengguna*</label>
                        <div class="col-md-10">
                            <select class="form-control" name="status_id" required>
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
    // Select
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
        $("#pegawai").select2();
        $('#unit-kerja').change(function(){
            var unit_kerja = $(this).val()

            if(unit_kerja){
                $.ajax({
                    type:"GET",
                    url:"/pegawai/select/" + unit_kerja,
                    dataType: 'JSON',
                    success:function(res){
                        if(res){
                            $("#pegawai").empty();
                            $.each(res,function(index, pegawai){
                                var nama_pegawai = pegawai.nama_pegawai.toUpperCase();
                                $("#pegawai").append(
                                    '<option value="'+ pegawai.id_pegawai +'">'+ pegawai.nip +' - '+ nama_pegawai +'</option>'
                                );
                            });
                        }else{
                            $("#pegawai").empty();
                        }
                    }
                });
            }else{
                $("#pegawai").empty();
            }
        });

    })
    // Password
    $(document).ready(function() {
        $("#eye-icon-pass").click(function() {
            var password = $("#password");
            var icon = $("#eye-icon");
            if (password.attr("type") == "password") {
                password.attr("type", "text");
                icon.removeClass("fa-eye-slash").addClass("fa-eye");
            } else {
                password.attr("type", "password");
                icon.removeClass("fa-eye").addClass("fa-eye-slash");
            }
        });

        $("#eye-icon-conf").click(function() {
            var password = $("#conf-password");
            var icon = $("#eye-icon");
            if (password.attr("type") == "password") {
                password.attr("type", "text");
                icon.removeClass("fa-eye-slash").addClass("fa-eye");
            } else {
                password.attr("type", "password");
                icon.removeClass("fa-eye").addClass("fa-eye-slash");
            }
        });

        $("form").submit(function() {
            var password = $("#password").val();
            var conf_password = $("#conf-password").val();
            if (password != conf_password) {
                alert("Konfirmasi password tidak sama!");
                return false;
            }
            return true;
        });
    });
</script>
@endsection

@endsection
