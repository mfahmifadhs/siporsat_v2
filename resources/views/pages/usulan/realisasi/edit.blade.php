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
                    <li class="breadcrumb-item"><a href="{{ route($form.'.home') }}">Beranda</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('usulan.show', $form) }}">Daftar Usulan</a></li>
                    <li class="breadcrumb-item">Realisasi Usulan</li>
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

        <div class="card" style="border-radius: 10px;">
            <div class="card-header text-center font-weight-bold">
                Edit Realisasi Usulan {{ $usulan->form->nama_form }}
            </div>
            <div class="card-header">
                <div class="timeline-steps aos-init aos-animate" data-aos="fade-up">
                    @foreach ($status as $row)
                    @if ($row->id_status != 100)
                    <div class="timeline-step">
                        <div class="timeline-content">
                            @if ($row->id_status == $usulan->status_proses_id)
                            <i class="fas fa-dot-circle fa-2x text-danger"></i>
                            @elseif ($usulan->status_proses_id > $row->id_status)
                            <i class="fas fa-dot-circle fa-2x text-success"></i>
                            @elseif ($usulan->status_proses_id < $row->id_status)
                                <i class="fas fa-dot-circle fa-2x text-secondary"></i>
                                @endif
                                <p class="text-muted mb-0 mb-lg-0 mt-2">{{ $row->nama_status }}</p>
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>

            <form id="form" action="{{ route('anggaran.realisasi.update', ['form' => $form, 'id' => $usulan->id_usulan]) }}" method="POST">
                @csrf
                <div class="card-body">
                    <ul class="nav mb-3" id="tab" role="tablist">
                        <li class="nav-item">
                            <a class="btn btn-default mr-2 border-secondary active bg-primary" id="realisasi-tab" data-toggle="pill" href="#realisasi" role="tab" aria-controls="realisasi" aria-selected="false">
                                Realisasi
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-default border-secondary" id="usulan-tab" data-toggle="pill" href="#usulan" role="tab" ls="usulan" aria-selected="true">
                                Informasi Usulan
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content" id="tabContent">
                        <!-- Informasi Usulan -->
                        <div class="tab-pane fade" id="usulan" role="tabpanel" aria-labelledby="usulan-tab">
                            <div class="row">
                                <div class="col-md-12 text-muted mb-3">Informasi Pengusul</div>
                                <div class="col-md-9">
                                    <div class="form-group row">
                                        <div class="col-md-2">ID</div>:
                                        <div class="col-md-8">{{ $usulan->id_usulan }}</div>
                                        <div class="col-md-2">Tanggal Usulan</div>:
                                        <div class="col-md-8">
                                            {{ \Carbon\carbon::parse($usulan->tanggal_usulan)->isoFormat('DD MMMM Y') }}
                                        </div>
                                        <div class="col-md-2">Nomor Surat</div>:
                                        <div class="col-md-8">{{ $usulan->nomor_usulan }}</div>
                                        <div class="col-md-2">Pengusul</div>:
                                        <div class="col-md-8">{{ $usulan->pegawai->nama_pegawai }}</div>
                                        <div class="col-md-2">Unit Kerja</div>:
                                        <div class="col-md-8">{{ $usulan->pegawai->unitKerja->nama_unit_kerja }}</div>
                                    </div>
                                </div>
                                <div class="col-md-3 text-right">
                                    @if ($usulan->status_proses_id < 103)
                                    <a href="{{ route('usulan.edit', ['form' => $form, 'id' => $usulan->id_usulan]) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    @endif
                                    @if ($usulan->status_proses_id > 102)
                                    <a href="{{ route('usulan.print', ['form' => $form, 'id' => $usulan->id_usulan]) }}" class="btn btn-danger btn-sm" target="_blank">
                                        <i class="fas fa-print"></i> Cetak
                                    </a>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12 text-muted mb-3">Informasi Pekerjaan</div>
                                <div class="col-md-12">
                                    @php
                                        $row1 = ($form == 'aadb' && $usulan->form_id != 101) ? 'No. Plat' : (($form == 'atk') ? 'Nama Barang' : 'Pekerjaan');
                                        $row2 = $form == 'aadb' && $usulan->form_id != 101 ? 'Kendaraan' : 'Deskripsi';
                                        $row3 = $form == 'atk' ? 'Permintaan' : 'Keterangan'
                                    @endphp
                                    <table id="table-show" class="table table-bordered text-center" style="font-size: 15px;">
                                        <thead style="font-size: 15px;">
                                            <tr>
                                                <th style="width: 0%;">No</th>
                                                <th style="width: 30%;">{{ $row1 }}</th>
                                                <th style="width: 40%;">{{ $row2 }}</th>
                                                <th style="width: 30%;">{{ $row3 }}</th>
                                            </tr>
                                        </thead>
                                        @php $no = 1; @endphp
                                        <tbody style="font-size: 13px;">
                                            @foreach ($usulan->usulanUkt as $row)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td class="text-left">{{ $row->judul_pekerjaan }}</td>
                                                <td class="text-left">{!! nl2br(e($row->deskripsi)) !!}</td>
                                                <td class="text-left">{!! nl2br(e($row->keterangan)) !!}</td>
                                            </tr>
                                            @endforeach

                                            @foreach ($usulan->usulanGdn as $row)
                                            <tr>
                                                <td style="width: 0%;">{{ $no++ }}</td>
                                                <td class="text-left text-capitalize">
                                                    {{ strtolower($row->bperbaikan->bidang_perbaikan) }} <br>
                                                    {{ strtolower($row->judul_pekerjaan) }}
                                                </td>
                                                <td class="text-left">{!! nl2br(e($row->deskripsi)) !!}</td>
                                                <td class="text-left" style="width: 25%;">{!! nl2br(e($row->keterangan)) !!}</td>
                                            </tr>
                                            @endforeach

                                            @foreach ($usulan->usulanOldat as $row)
                                            @if ($usulan->form_id == 201)
                                            <tr class="text-capitalized">
                                                <td>{{ $no++ }}</td>
                                                <td class="text-left">
                                                    Permintaan Pengadaan {{ ucfirst(strtolower($row->kategori?->kategori_barang)) }}
                                                    sebanyak {{ $row->jumlah_pengadaan }} unit dengan estimasi harga
                                                    Rp {{ number_format($row->estimasi_harga, 0, ',', '.') }}
                                                </td>
                                                <td class="text-left">{!! nl2br(e($row->spesifikasi)) !!}</td>
                                                <td class="text-left">{!! nl2br(e($usulan->keterangan)) !!}</td>
                                            </tr>
                                            @elseif ($usulan->form_id == 202)
                                            <tr class="text-capitalized">
                                                <td>{{ $no++ }}</td>
                                                <td class="text-left">
                                                    Perbaikan {{ ucfirst(strtolower($row->barang->kategori->kategori_barang)) }}
                                                    ({{ $row->barang->kategori_id.'.'.$row->barang->nup }})
                                                </td>
                                                <td class="text-left">
                                                    {{ ucfirst(strtolower($row->barang->merk_tipe.' '.$row->barang?->spesifikasi)) }}
                                                </td>
                                                <td class="text-left">{!! nl2br(e($row->keterangan_kerusakan)) !!}</td>
                                            </tr>
                                            @endif
                                            @endforeach

                                            @foreach ($usulan->usulanStnk as $row)
                                            @if ($usulan->form_id == 102)
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            @elseif ($usulan->form_id == 103)
                                            @if ($row->keterangan == 'true')
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td style="width: 25%;">{{ $row->aadb->no_plat }}</td>
                                                <td style="width: 35%;">{{ $row->aadb->merk_tipe.' '.$row->aadb->tahun }}</td>
                                                <td class="text-left">
                                                    Masa Berlaku STNK :
                                                    {{ \Carbon\carbon::parse($row->tanggal_stnk)->isoFormat('DD MMMM Y') }}
                                                </td>
                                            </tr>
                                            @endif
                                            @endif
                                            @endforeach

                                            @foreach ($usulan->usulanBbm->where('status_pengajuan', 'true') as $row)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td>{{ $row->aadb->no_plat }}</td>
                                                <td>{{ $row->aadb->merk_tipe.' '.$row->aadb->tahun }}</td>
                                                <td>{{ \Carbon\carbon::parse($row->bulan_pengadaan)->isoFormat('MMMM Y') }}</td>
                                            </tr>
                                            @endforeach

                                            @foreach ($usulan->usulanAtk as $subRow)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td class="text-left">{{ $subRow->atk->kategori->kategori_atk }}</td>
                                                <td class="text-left">{{ $subRow->atk->deskripsi }}</td>
                                                <td>{{ $subRow->jumlah_permintaan }} {{ optional($subRow->atk->satuan->where('jenis_satuan', 'distribusi')->first())->satuan }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- Alokasi Anggaran -->
                        <div class="tab-pane fade active show " id="realisasi" role="tabpanel" aria-labelledby="realisasi-tab">
                            @foreach ($usulan->realisasi as $i => $row)
                            <div class="form-group row section-data">
                                <input type="hidden" name="realisasi_id[]" value="{{ $row->id_realisasi }}">
                                <div class="col-md-12 text-muted mb-3 title">Realisasi Anggaran {{ $i + 1 }}</div>
                                <!-- <label class="col-md-2 col-form-label">Mata Anggaran</label>:
                                <div class="col-md-9">
                                    <select class="form-control select-border-bottom" name="id_mta[]">
                                        <option value="">-- Pilih Mata Anggaran --</option>
                                        @foreach ($mta4 as $rowMta4)
                                        <optgroup label="{{ $rowMta4->kode_mta_4.'. '.$rowMta4->nama_mta_4 }}">
                                            @foreach ($rowMta4->mataAnggaran->groupBy('kode_mta_ctg') as $kodeMtaCtg => $items)
                                        <optgroup label="&emsp;{{ $items[0]->mataAnggaranCtg->kode_mta_ctg.'. '.$items[0]->mataAnggaranCtg->nama_mta_ctg }}">
                                            @foreach ($items->where('unit_kerja_id', Auth::user()->pegawai->unitKerja->id_unit_kerja) as $rowMta)
                                            <option value="{{ $rowMta->id_mta }}" <?php echo $rowMta->id_mta == $row->mta_id ? 'selected' : '' ?>>
                                                ⭕ {{ $rowMta->nama_mta }}
                                            </option>
                                            @endforeach
                                        </optgroup>
                                        @endforeach
                                        </optgroup>
                                        @endforeach
                                    </select>
                                </div> -->
                                <label class="col-md-2 col-form-label">Tanggal</label>:
                                <div class="col-md-9">
                                    <input type="date" name="mta_tanggal[]" class="form-control input-border-bottom" value="{{ \Carbon\carbon::parse($row->created_at)->isoFormat('Y-MM-DD') }}"
                                    placeholder="Contoh: 521119/524111" required>
                                </div>
                                <label class="col-md-2 col-form-label">Kode</label>:
                                <div class="col-md-9">
                                    <input type="text" name="mta_kode[]" class="form-control input-border-bottom" value="{{ $row->mta_kode }}"
                                    placeholder="Contoh: 521119/524111" required>
                                </div>
                                <label class="col-md-2 col-form-label">Deskripsi</label>:
                                <div class="col-md-9">
                                    <input type="text" name="mta_deskripsi[]" class="form-control input-border-bottom" value="{{ $row->mta_deskripsi }}"
                                    placeholder="Contoh: Penyelenggaraan Kegiatan dan Operasional Kepala Biro Umum" required>
                                </div>
                                <label class="col-md-2 col-form-label">Nilai Realisasi</label>:
                                <div class="col-md-9">
                                    <input type="text" name="nilai_realisasi[]" class="form-control input-border-bottom price" value="{{ number_format($row->nilai_realisasi, 0, ',', '.') }}" required>
                                </div>
                                <label class="col-md-2 col-form-label">Jenis Realisasi</label>:
                                <div class="col-md-9">
                                    <select name="jenis_realisasi[]" class="form-control select-border-bottom" required>
                                        <option value="">-- Pilih Jenis Pembayaran --</option>
                                        <option value="cash/transfer" <?php echo $row->jenis_realisasi == 'cash/transfer' ? 'selected' : '' ?>>Cash/Transfer</option>
                                        <option value="reimburse" <?php echo $row->jenis_realisasi == 'reimburse' ? 'selected' : '' ?>>Reimburse</option>
                                    </select>
                                </div>
                                <label class="col-md-2 col-form-label">Keterangan</label>:
                                <div class="col-md-9">
                                    <input type="text" class="form-control select-border-bottom" name="keterangan[]" value="{{ $row->keterangan }}">
                                </div>
                                <div class="col-md-2 mt-2">&ensp;</div>
                                <div class="col-md-5 mt-2">
                                    <label class="btn btn-danger btn-sm">
                                        <input type="hidden" name="hapus[{{ $i }}]" value="">
                                        <input type="checkbox" autocomplete="off" name="hapus[{{ $i }}]" value="true">
                                        <span class="align-middle small">Hapus</span>
                                    </label>
                                </div>
                            </div>
                            @endforeach

                            <!-- Section more -->
                            <div class="form-group row section-item d-none">
                                <div class="col-md-12 text-muted mb-3 title">Realisasi Anggaran 1</div>
                                <input type="hidden" name="realisasi_id[]" value="" disabled>
                                <!-- <label class="col-md-2 col-form-label">Mata Anggaran</label>:
                                <div class="col-md-9">
                                    <input type="hidden" name="hapus[]" value="" disabled>
                                    <select class="form-control select-border-bottom" name="id_mta[]" disabled>
                                        <option value="">-- Pilih Mata Anggaran --</option>
                                        @foreach ($mta4 as $rowMta4)
                                        <optgroup label="{{ $rowMta4->kode_mta_4.'. '.$rowMta4->nama_mta_4 }}">
                                            @foreach ($rowMta4->mataAnggaran->groupBy('kode_mta_ctg') as $kodeMtaCtg => $items)
                                        <optgroup label="&emsp;{{ $items[0]->mataAnggaranCtg->kode_mta_ctg.'. '.$items[0]->mataAnggaranCtg->nama_mta_ctg }}">
                                            @foreach ($items->where('unit_kerja_id', Auth::user()->pegawai->unitKerja->id_unit_kerja) as $rowMta)
                                            <option value="{{ $rowMta->id_mta }}">⭕ {{ $rowMta->nama_mta }}</option>
                                            @endforeach
                                        </optgroup>
                                        @endforeach
                                        </optgroup>
                                        @endforeach
                                    </select>
                                </div> -->
                                <label class="col-md-2 col-form-label">Tanggal</label>:
                                <div class="col-md-9">
                                    <input type="date" name="mta_tanggal[]" class="form-control input-border-bottom" disabled required>
                                </div>
                                <label class="col-md-2 col-form-label">Kode</label>:
                                <div class="col-md-9">
                                    <input type="text" name="mta_kode[]" class="form-control input-border-bottom"
                                    placeholder="Contoh: 521119/524111" disabled required>
                                </div>
                                <label class="col-md-2 col-form-label">Deskripsi</label>:
                                <div class="col-md-9">
                                    <input type="text" name="mta_deskripsi[]" class="form-control input-border-bottom"
                                    placeholder="Contoh: Penyelenggaraan Kegiatan dan Operasional Kepala Biro Umum" disabled required>
                                </div>
                                <label class="col-md-2 col-form-label">Nilai Realisasi</label>:
                                <div class="col-md-9">
                                    <input type="text" name="nilai_realisasi[]" class="form-control input-border-bottom price" disabled required>
                                </div>
                                <label class="col-md-2 col-form-label">Jenis Realisasi</label>:
                                <div class="col-md-9">
                                    <select name="jenis_realisasi[]" class="form-control select-border-bottom" disabled required>
                                        <option value="">-- Pilih Jenis Pembayaran --</option>
                                        <option value="cash/transfer">Cash/Transfer</option>
                                        <option value="reimburse">Reimburse</option>
                                    </select>
                                </div>
                                <label class="col-md-2 col-form-label">Keterangan</label>:
                                <div class="col-md-9">
                                    <input type="text" class="form-control select-border-bottom" name="keterangan[]" disabled placeholder="Keterangan tambahan">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-5 mt-2">
                                    <a href="" class="small btn btn-primary btn-xs btn-tambah-baris">
                                        <i class="fas fa-plus"></i> Tambah Baris
                                    </a>
                                    <a href="" class="small btn btn-danger btn-xs btn-hapus-baris">
                                        <i class="fas fa-times"></i> Hapus Baris
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary btn-md" onclick="confirmSubmit(event)">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                    </div>
                </div>
            </form>
        </div>


    </div>
</section>
<div id="usulan-count" data-usulan-count="{{ $usulan->realisasi->count() }}"></div>
@section('js')
<script>
    $(function() {
        $(".kategori").select2()
        $("#table-show").DataTable({
            "responsive": false,
            "lengthChange": true,
            "autoWidth": true,
            "info": true,
            "paging": true,
            "searching": true
        }).buttons().container().appendTo('#table-show_wrapper .col-md-6:eq(0)')

        $('.price').on('input', function() {
            // Menghapus karakter selain angka (termasuk tanda titik koma sebelumnya)
            var value = $(this).val().replace(/[^0-9]/g, '');

            // Format dengan menambahkan titik koma setiap tiga digit
            var formattedValue = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

            // Memasukkan nilai yang sudah diformat kembali ke input
            $(this).val(formattedValue);
        });

        $(document).on('click', '.btn-tambah-baris', function(e) {
            e.preventDefault();
            var total = parseInt(document.getElementById('usulan-count').getAttribute('data-usulan-count'));
            var container = $('.section-item');
            var templateRow = $('.section-item').first().clone();
            templateRow.find(':input').val('');
            templateRow.find(':input').removeAttr('disabled');
            templateRow.find('.jumlah').val('1');
            templateRow.find('input[name="nilai_realisasi[]"]').addClass('price');
            templateRow.removeClass('d-none');

            templateRow.find('.price').on('input', function() {
                var value = $(this).val().replace(/[^0-9]/g, '');
                var formattedValue = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                $(this).val(formattedValue);
            });

            templateRow.find('.title').text('Realisasi Anggaran ' + (total + container.length));
            $('.section-item:last').after(templateRow);
            toggleHapusBarisButton();

            templateRow.find('.input-format').on('input', function() {
                var value = $(this).val().replace(/[^0-9]/g, '');
                var formattedValue = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                $(this).val(formattedValue);
            });
        });

        $(document).on('click', '.btn-hapus-baris', function(e) {
            e.preventDefault();
            var container = $('.section-item');
            if (container.length > 1) {
                $(this).closest('.form-group').prev('.section-item').remove();
                toggleHapusBarisButton();
            } else {
                alert('Minimal harus ada satu baris.');
            }
        });

        // Inisialisasi tombol "Hapus Baris" saat halaman dimuat
        $('.btn-hapus-baris').toggle($('.section-item').length > 1);

        function toggleHapusBarisButton() {
            var container = $('.section-item');
            var btnHapusBaris = $('.btn-hapus-baris');
            btnHapusBaris.toggle(container.length > 1);
        }
    })

    function confirmSubmit(event) {
        event.preventDefault();

        const form = document.getElementById('form');
        const requiredInputs = form.querySelectorAll('input[required]:not(:disabled), select[required]:not(:disabled), textarea[required]:not(:disabled)');

        let allInputsValid = true;

        requiredInputs.forEach(input => {
            if (input.value.trim() === '') {
                input.style.borderColor = 'red';
                allInputsValid = false;
            } else {
                input.style.borderColor = '';
            }
        });

        if (allInputsValid) {
            Swal.fire({
                title: 'Simpan Perubahan?',
                text: 'konfirmasi perubahan realisasi anggaran',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, simpan!',
                cancelButtonText: 'Batal!',
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        } else {
            Swal.fire({
                title: 'Error',
                text: 'Ada input yang diperlukan yang belum diisi.',
                icon: 'error'
            });
        }
    }
</script>
<script>
    const usulanTab = document.getElementById('usulan-tab');
    const realisasiTab = document.getElementById('realisasi-tab');

    usulanTab.addEventListener('click', function() {
        usulanTab.classList.add('bg-primary');
        realisasiTab.classList.remove('bg-primary');
        bastTab.classList.remove('bg-primary');
    });

    realisasiTab.addEventListener('click', function() {
        realisasiTab.classList.add('bg-primary');
        usulanTab.classList.remove('bg-primary');
        bastTab.classList.remove('bg-primary');
    });
</script>
@endsection


@endsection
