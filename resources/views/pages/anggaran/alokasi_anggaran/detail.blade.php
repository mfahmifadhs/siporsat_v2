@extends('layout.app')

@section('content')

<!-- Content Header -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="text-capitalize">SIPORSAT</h1>
                <h5>Sistem Pengelolaan Operasional Perkantoran Terpusat</h5>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right text-capitalize">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('alokasi_anggaran.show') }}">Alokasi Anggaran</a></li>
                    <li class="breadcrumb-item active">{{ $unitKerja->nama_unit_kerja }}</li>
                </ol>
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
                <h3 class="card-title">Alokasi Anggaran {{ $unitKerja->nama_unit_kerja }}</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <ul id="myUL">
                    @foreach ($mta1 as $rowMta1)
                    <li class="mt-2 mb-2 border-top-bottom">
                        <span class="caret open">{{ $rowMta1->kode_mta_1.'. '.$rowMta1->nama_mta_1 }}</span>
                        <ul class="nested">
                            @foreach ($rowMta1->mataAnggaran2 as $rowMta2)
                            <li class="mt-2 mb-2">
                                <span class="caret open">{{ $rowMta2->kode_mta_2.'. '.$rowMta2->nama_mta_2 }}</span>
                                <ul class="nested">
                                    @foreach ($rowMta2->mataAnggaran3 as $rowMta3)
                                    <li class="mt-2 mb-2">
                                        <span class="caret open">{{ $rowMta3->kode_mta_3.'. '.$rowMta3->nama_mta_3}}</span>
                                        <ul class="nested">
                                            @foreach ($rowMta3->mataAnggaran4 as $rowMta4)
                                            <li class="mt-2 mb-2">
                                                <span class="caret open">{{ $rowMta4->kode_mta_4.'. '.$rowMta4->nama_mta_4}}</span>
                                                <ul class="nested">
                                                    @foreach ($rowMta4->mataAnggaran->groupBy('kode_mta_ctg') as $kodeMtaCtg => $items)
                                                    <li class="mt-2 mb-2">
                                                        <span class="caret open">
                                                            {{ $items[0]->mataAnggaranCtg->kode_mta_ctg.'. '.$items[0]->mataAnggaranCtg->nama_mta_ctg }}
                                                        </span>
                                                        <ul class="nested">
                                                            @foreach ($items->where('unit_kerja_id', $unitKerja->id_unit_kerja) as $rowMta)
                                                            <li class="mt-2 mb-1">
                                                                <i class="far fa-circle"></i>
                                                                {{ $rowMta->nama_mta }}
                                                            </li>
                                                            @endforeach
                                                        </ul>
                                                        @endforeach
                                                    </li>
                                                </ul>
                                                @endforeach
                                            </li>
                                        </ul>
                                    </li>
                                    @endforeach
                                </ul>
                            </li>
                            @endforeach
                        </ul>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</section>

@section('js')
<script>
    $(function() {
        $("#table-show").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": true,
            "info": true,
            "paging": true,
            "searching": true
        }).buttons().container().appendTo('#table-show_wrapper .col-md-6:eq(0)')
    })
</script>

<script>
    var toggler = document.getElementsByClassName("caret");
    var i;

    for (i = 0; i < toggler.length; i++) {
        toggler[i].classList.add("caret-down"); // Tambahkan kelas "caret-down" untuk mengubah tampilan panah
        toggler[i].nextElementSibling.style.display = "block"; // Tampilkan elemen berikutnya (ul.nested) secara default
        toggler[i].addEventListener("click", function() {
            this.classList.toggle("caret-down");
            var nested = this.nextElementSibling;
            if (nested.style.display === "block") {
                nested.style.display = "none";
            } else {
                nested.style.display = "block";
            }
        });
    }
</script>
@endsection

@endsection
