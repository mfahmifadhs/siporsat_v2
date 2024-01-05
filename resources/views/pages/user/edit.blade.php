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
                    <li class="breadcrumb-item active">Edit Pengguna</li>
                </ol>
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
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Edit Pengguna</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <form id="form" action="{{ route('user.edit', $user->id) }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Role*</label>
                        <div class="col-md-10 mt-2">
                            <span class="mr-4">
                                <input type="radio" name="role_id" value="1" <?php echo $user->role_id == 1 ? 'checked' : ''; ?>> SUPER ADMIN
                            </span>
                            <span class="mr-4">
                                <input type="radio" name="role_id" value="2" <?php echo $user->role_id == 2 ? 'checked' : ''; ?>> ADMIN USER
                            </span>
                            <span class="mr-4">
                                <input type="radio" name="role_id" value="3" <?php echo $user->role_id == 3 ? 'checked' : ''; ?>> SUPER USER
                            </span>
                            <span class="mr-4">
                                <input type="radio" name="role_id" value="4" <?php echo $user->role_id == 4 ? 'checked' : ''; ?>> USER
                            </span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Unit Kerja</label>
                        <div class="col-md-10">
                            <select id="unit-kerja" name="unit_kerja_id" class="form-control" disabled>
                                <option value="{{ $user->pegawai->unitKerja->id_unit_kerja }}">
                                    {{ strtoupper($user->pegawai->unitKerja->nama_unit_kerja) }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Pegawai</label>
                        <div class="col-md-10">
                            <select id="pegawai" name="pegawai_id" class="form-control" disabled>
                                <option value="{{ $user->pegawai->id_pegawai }}">
                                    {{ strtoupper($user->pegawai->nip.' - '.$user->pegawai->nama_pegawai) }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Username</label>
                        <div class="col-md-10">
                            <div class="input-group">
                                <input type="text" class="form-control" name="username" value="{{ $user->username }}" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Password Lama</label>
                        <div class="col-md-10">
                            <div class="input-group">
                                <input type="password" class="form-control" id="old-password" placeholder="Masukkan Password">
                                <div class="input-group-append">
                                    <span class="input-group-text border-secondary">
                                        <i class="fa fa-eye-slash" id="eye-icon-old"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Password Baru</label>
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
                        <label class="col-md-2 col-form-label">Konfirmasi Password</label>
                        <div class="col-md-10">
                            <div class="input-group">
                                <input type="password" class="form-control" id="conf-password" placeholder="Konfirmasi Password">
                                <div class="input-group-append">
                                    <span class="input-group-text border-secondary">
                                        <i class="fa fa-eye-slash" id="eye-icon-conf"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Status Pengguna</label>
                        <div class="col-md-4">
                            <select class="form-control" name="status_id" required>
                                @foreach ($status as $row)
                                <option value="{{ $row->id_status }}" <?php echo $row->id_status ==  $user->status_id ? 'selected' : ''; ?>>
                                    {{ $row->nama_status }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary" onclick="confirmSubmit(event)">
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

    })

    $(document).ready(function() {
        $("#eye-icon-old").click(function() {
            var password = $("#old-password")
            var icon = $("#eye-icon")
            if (password.attr("type") == "password") {
                password.attr("type", "text")
                icon.removeClass("fa-eye-slash").addClass("fa-eye")
            } else {
                password.attr("type", "password")
                icon.removeClass("fa-eye").addClass("fa-eye-slash")
            }
        });
        $("#eye-icon-pass").click(function() {
            var password = $("#password")
            var icon = $("#eye-icon")
            if (password.attr("type") == "password") {
                password.attr("type", "text")
                icon.removeClass("fa-eye-slash").addClass("fa-eye")
            } else {
                password.attr("type", "password")
                icon.removeClass("fa-eye").addClass("fa-eye-slash")
            }
        });

        $("#eye-icon-conf").click(function() {
            var password = $("#conf-password")
            var icon = $("#eye-icon")
            if (password.attr("type") == "password") {
                password.attr("type", "text")
                icon.removeClass("fa-eye-slash").addClass("fa-eye")
            } else {
                password.attr("type", "password")
                icon.removeClass("fa-eye").addClass("fa-eye-slash")
            }
        });
    })
</script>

<script>
    function confirmSubmit(event) {
        event.preventDefault();

        const form = document.getElementById('form');

        var now_password = '{{ $user->password_teks }}';
        var old_password = $("#old-password").val();
        var password = $("#password").val();
        var conf_password = $("#conf-password").val();

        if (password != conf_password && password != null) {
            Swal.fire({
                title: 'Konfirmasi password tidak sama!',
                icon: 'error'
            });
            return false;
        }

        if (old_password != now_password && old_password) {
            Swal.fire({
                title: 'Password lama Anda salah!',
                icon: 'error'
            });
            return false;
        }

        if (old_password == now_password && !password) {
            Swal.fire({
                title: 'Anda belum menambahkan Password Baru!',
                icon: 'error'
            });
            return false;
        }

        if (!old_password) {
            $('input[name="password"]').val(now_password);
        }

        Swal.fire({
            title: 'Simpan perubahan?',
            text: '',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, simpan!',
            cancelButtonText: 'Batal!',
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });

    }
</script>
@endsection

@endsection
