@extends('layout.app')

@section('content')

<!-- content-wrapper -->
<section class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"></small></h1>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container">
        <div class="panel panel-default text-center" style="margin-top: 15vh;">
            <div class="panel-heading font-weight-bold">Verifikasi Dokumen</div>
            <hr>
            <div class="panel-body">
                <form id="form" class="form-horizontal" method="POST" action="{{ route('usulan.verif.store', Auth::user()->id) }}">
                    {{ csrf_field() }}
                    <input type="hidden" name="modul" value="{{ Auth::user()->sess_modul }}">
                    <input type="hidden" name="form_id" value="{{ Auth::user()->sess_form_id }}">
                    <input type="hidden" name="bast_id" value="{{ Auth::user()->sess_bast_id }}">
                    <div class="form-group">
                        <p>Mohon untuk memasukan kode <strong>OTP</strong> yang diterima pada aplikasi <b>Google Authenticator</b>. <br>
                            Pastikan, anda memasukan kode OTP terkini, karena kode OTP akan berubah setiap 30 detik.</p>

                        @if($errors->any())
                        <b style="color: red">{{$errors->first()}}</b><br>
                        @endif
                        <label for="one_time_password" class="col-md-4 control-label">Masukkan Kode OTP</label>


                        <div class="col-md-12">
                            <input id="one_time_password" type="number" class="form-control text-center" name="one_time_password"
                            required autofocus min=6>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-12 col-md-offset-4">
                            <button type="submit" class="btn btn-primary" onclick="confirmSubmit(event)">
                                Submit
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

@section('js')
<script>
    function confirmSubmit(event) {
        event.preventDefault(); // Prevent the default link behavior
        Swal.fire({
            title: 'Veifikasi Dokumen?',
            text: 'Verifikasi dokumen usulan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Verifikasi!',
            cancelButtonText: 'Batal!',
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('form');
                form.submit();
            }
        });
    }
</script>
@endsection


@endsection
