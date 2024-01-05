<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIPORSAT</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Icon Title -->
    <link rel="icon" type="image/png" href="{{ asset('dist_admin/img/logo-kemenkes-icon.png') }}">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <!-- <link rel="stylesheet" href="{{ asset('dist_admin/plugins/fontawesome-free/css/all.min.css') }}"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('dist_admin/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist_admin/css/adminlte.css') }}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset('dist_admin/plugins/summernote/summernote-bs4.min.css') }}">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('dist_admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist_admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist_admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('dist_admin/plugins/select2/css/select2.min.css') }}">
    <!-- Sweet alert -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.min.css">
    @yield('css')
</head>

<!-- <body class="hold-transition sidebar-mini sidebar-collapse"> -->
@php $isCollapse = Str::startsWith(request()->path(), 'atk/daftar') @endphp
<body class="hold-transition sidebar-mini {{ $isCollapse ? 'sidebar-collapse' : 'sidebar-fixed' }}">
    <div class="wrapper">
        <!-- Preloader -->
        <div class="preloader">
            <div class="loader"></div>
        </div>
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="{{ url('admin-user/dashboard') }}" class="nav-link">Dashboard</a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- Navbar Search -->
                <li class="nav-item">
                    <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                        <i class="fas fa-search"></i>
                    </a>
                    <div class="navbar-search-block">
                        <form class="form-inline">
                            <div class="input-group input-group-sm">
                                <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
                                <div class="input-group-append">
                                    <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </li>

                <!-- Notifications Dropdown Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link text-sm mt-1" data-toggle="dropdown" href="#">
                        <i class="far fa-user-circle"></i>
                        <b>{{ Auth::user()->pegawai->nama_pegawai }}</b>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <span class="dropdown-item dropdown-header">
                            {{ Auth::user()->pegawai->nama_pegawai }} <br> {{ Auth::user()->pegawai->nama_jabatan }}
                        </span>
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('user.profile.show', Auth::user()->id) }}" class="dropdown-item">
                            <i class="fas fa-user mr-2"></i> Profil
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('keluar') }}" class="dropdown-item dropdown-footer">
                            <i class="fas fa-sign-out-alt"></i> Keluar
                        </a>
                    </div>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="index3.html" class="brand-link">
                <img src="{{ asset('dist_admin/img/logo-kemenkes-icon.png') }}" alt="Sistem Informasi Pergudangan" class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">SIPORSAT</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- SidebarSearch Form -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="info">
                        <a href="#" class="d-block">
                            <h6>{{ Auth::user()->pegawai->nama_pegawai }}</h6>
                            <h6 class="small">{{ Auth::user()->pegawai->unitKerja->nama_unit_kerja }}</h6>
                        </a>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2 mb-5">
                    <ul class="nav nav-pills nav-sidebar flex-column m" data-widget="treeview" role="menu" data-accordion="false">
                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}" class="nav-link {{ Request::is('admin-user/dashboard') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>

                        <li class="nav-header font-weight-bold">Menu</li>
                        <li class="nav-item">
                            @php
                            $isOldat = Str::startsWith(request()->path(), 'usulan/oldat') || Str::startsWith(request()->path(), 'usulan/tambah/oldat/') ||
                            Str::startsWith(request()->path(), 'usulan/detail/oldat/');
                            @endphp
                            <a href="{{ route('oldat.home') }}" class="nav-link {{ $isOldat ? 'active' : '' }}">
                                <i class="nav-icon fas fa-laptop"></i>
                                <p>Oldat BMN & Meubelair</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            @php
                            $isAadb = Str::startsWith(request()->path(), 'usulan/aadb') || Str::startsWith(request()->path(), 'usulan/tambah/aadb/') ||
                            Str::startsWith(request()->path(), 'usulan/detail/aadb/') || Str::startsWith(request()->path(), 'aadb/kendaraan/');
                            @endphp
                            <a href="{{ route('aadb.home') }}" class="nav-link {{ $isAadb ? 'active' : '' }}">
                                <i class="nav-icon fas fa-car"></i>
                                <p>Angkutan Darat Bermotor</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            @php
                            $isAtk = Str::startsWith(request()->path(), 'usulan/atk') || Str::startsWith(request()->path(), 'usulan/tambah/atk/') ||
                            Str::startsWith(request()->path(), 'usulan/detail/atk/') || Str::startsWith(request()->path(), 'atk/referensi/');
                            @endphp
                            <a href="{{ route('atk.home') }}" class="nav-link {{ $isAtk ? 'active' : '' }}">
                                <i class="nav-icon fas fa-pencil-ruler"></i>
                                <p>Alat Tulis Kantor</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            @php
                            $isGdn = Str::startsWith(request()->path(), 'usulan/gdn') || Str::startsWith(request()->path(), 'usulan/tambah/gdn/') ||
                            Str::startsWith(request()->path(), 'usulan/detail/gdn/');
                            @endphp
                            <a href="{{ route('gdn.home') }}" class="nav-link {{ $isGdn ? 'active' : '' }}">
                                <i class="nav-icon fas fa-city"></i>
                                <p>Gedung & Bangunan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            @php
                            $isUkt = Str::startsWith(request()->path(), 'usulan/ukt') || Str::startsWith(request()->path(), 'usulan/tambah/ukt/') ||
                            Str::startsWith(request()->path(), 'usulan/detail/ukt/');
                            @endphp
                            <a href="{{ route('ukt.home') }}" class="nav-link {{ $isUkt ? 'active' : '' }}">
                                <i class="nav-icon fas fa-briefcase"></i>
                                <p>Urusan Kerumahtanggaan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('rumah_dinas.show') }}" class="nav-link">
                                <i class="nav-icon fas fa-home"></i>
                                <p>Rumah Dinas Negara</p>
                            </a>
                        </li>

                        @if (Auth::user()->role_id == 1)
                        <li class="nav-header font-weight-bold">Anggaran</li>
                        <li class="nav-item">
                            <a href="{{ route('alokasi_anggaran.show') }}" class="nav-link">
                                <i class="nav-icon fas fa-wallet"></i>
                                <p>Alokasi Anggaran</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-coins"></i>
                                <p>
                                    Mata Anggaran
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('mata_anggaran.show') }}" class="nav-link">
                                        <i class="nav-icon far fa-circle"></i>
                                        <p>Mata Anggaran</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a type="button" class="nav-link" data-toggle="modal" data-target="#tambahMataAnggaran">
                                        <i class="nav-icon far far fa-circle"></i>
                                        <p>Tambah Mata Anggaran</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-header font-weight-bold">Pegawai Kemenkes</li>
                        <li class="nav-item">
                            <a href="{{ route('user.show') }}" class="nav-link">
                                <i class="nav-icon fas fa-users"></i>
                                <p>Pengguna</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('pegawai.show') }}" class="nav-link">
                                <i class="nav-icon fas fa-users"></i>
                                <p>Pegawai</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-address-card"></i>
                                <p>
                                    Unit Kerja
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview ">
                                <li class="nav-item">
                                    <a href="{{ route('unit_kerja.show') }}" class="nav-link">
                                        <i class="nav-icon fas fa"></i>
                                        <p>Daftar Unit Kerja</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('unit_kerja.create') }}" class="nav-link">
                                        <i class="nav-icon fas fa-hand-holding-usds"></i>
                                        <p>Tambah Unit Kerja</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-address-card"></i>
                                <p>
                                    Unit Utama
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview ">
                                <li class="nav-item">
                                    <a href="{{ route('unit_utama.show') }}" class="nav-link">
                                        <i class="nav-icon fas fa"></i>
                                        <p>Daftar Unit Utama</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('unit_utama.create') }}" class="nav-link">
                                        <i class="nav-icon fas fa-hand-holding-usds"></i>
                                        <p>Tambah Unit Utama</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif
                    </ul>
                </nav>
            </div>
        </aside>

        <div class="content-wrapper">
            @yield('content')
            <br>
        </div>


        <!-- Modal tambah mata anggaran -->
        <div class="modal fade" id="tambahMataAnggaran" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <hr>
                            </div>
                            <h5 class="col-md-6 mt-1">Mata Anggaran 01</h5>
                            <div class="col-md-6 text-right">
                                <a href="{{ route('mata_anggaran.create', 1) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus-circle"></i> Tambah
                                </a>
                            </div>
                            <div class="col-md-12">
                                <hr>
                            </div>
                            <h5 class="col-md-6 mt-1">Mata Anggaran 02</h5>
                            <div class="col-md-6 text-right">
                                <a href="{{ route('mata_anggaran.create', 2) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus-circle"></i> Tambah
                                </a>
                            </div>
                            <div class="col-md-12">
                                <hr>
                            </div>
                            <h5 class="col-md-6 mt-1">Mata Anggaran 03</h5>
                            <div class="col-md-6 text-right">
                                <a href="{{ route('mata_anggaran.create', 3) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus-circle"></i> Tambah
                                </a>
                            </div>
                            <div class="col-md-12">
                                <hr>
                            </div>
                            <h5 class="col-md-6 mt-1">Mata Anggaran 04</h5>
                            <div class="col-md-6 text-right">
                                <a href="{{ route('mata_anggaran.create', 4) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus-circle"></i> Tambah
                                </a>
                            </div>
                            <div class="col-md-12">
                                <hr>
                            </div>
                            <h5 class="col-md-6 mt-1">Mata Anggaran</h5>
                            <div class="col-md-6 text-right">
                                <a href="{{ route('mata_anggaran.create', '*') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus-circle"></i> Tambah
                                </a>
                            </div>
                            <div class="col-md-12">
                                <hr>
                            </div>
                            <h5 class="col-md-6 mt-1">Kategori Mata Anggaran</h5>
                            <div class="col-md-6 text-right">
                                <a href="{{ route('mata_anggaran.create', 'ctg') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus-circle"></i> Tambah
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <button id="btnToTop" class="btn-to-top" title="Kembali ke Atas">
            <i class="fas fa-arrow-circle-up"></i>
        </button>


        <!-- Main Footer -->
        <footer class="main-footer">
            <strong>Copyright &copy; 2022 <a href="https://roum.kemkes.go.id/">Biro Umum</a>.</strong>
            All rights reserved.
            <div class="float-right d-none d-sm-inline-block">
                <b>Version</b> 2
            </div>
        </footer>
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->
    <!-- jQuery -->
    <script src="{{ asset('dist_admin/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap -->
    <script src="{{ asset('dist_admin/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- overlayScrollbars -->
    <script src="{{ asset('dist_admin/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('dist_admin/js/adminlte.js') }}"></script>

    <!-- PAGE PLUGINS -->
    <script src="{{ asset('dist_admin/plugins/jquery-mousewheel/jquery.mousewheel.js') }}"></script>
    <script src="{{ asset('dist_admin/plugins/raphael/raphael.min.js') }}"></script>
    <script src="{{ asset('dist_admin/plugins/jquery-mapael/jquery.mapael.min.js') }}"></script>
    <script src="{{ asset('dist_admin/plugins/jquery-mapael/maps/usa_states.min.js') }}"></script>
    <!-- ChartJS -->
    <script src="{{ asset('dist_admin/plugins/chart.js/Chart.min.js') }}"></script>

    <!-- AdminLTE for demo purposes -->
    <script src="{{ asset('dist_admin/js/demo.js') }}"></script>
    <!-- Summernote -->
    <script src="{{ asset('dist_admin/plugins/summernote/summernote-bs4.min.js') }}"></script>
    <!-- DataTables  & Plugins -->
    <script src="{{ asset('dist_admin/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('dist_admin/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('dist_admin/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('dist_admin/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('dist_admin/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('dist_admin/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('dist_admin/plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('dist_admin/plugins/pdfmake/pdfmake.js') }}"></script>
    <script src="{{ asset('dist_admin/plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('dist_admin/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('dist_admin/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('dist_admin/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('dist_admin/plugins/select2/js/select2.full.min.js') }}"></script>
    <!-- ChartJS -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <!-- Include SweetAlert JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.all.min.js"></script>
    @yield('js')
    <script>
        $(function() {
            var url = window.location;
            // for single sidebar menu
            $('ul.nav-sidebar a').filter(function() {
                return this.href == url;
            }).addClass('active');

            // for sidebar menu and treeview
            $('ul.nav-treeview a').filter(function() {
                    return this.href == url;
                }).parentsUntil(".nav-sidebar > .nav-treeview")
                .css({
                    'display': 'block'
                })
                .addClass('menu-open').prev('a')
                .addClass('active');
        });

        document.addEventListener("DOMContentLoaded", function() {
            var btnToTop = document.getElementById("btnToTop");

            // Tampilkan tombol "Kembali ke Atas" saat pengguna menggulir ke bawah
            window.addEventListener("scroll", function() {
                if (document.documentElement.scrollTop > 100) {
                    btnToTop.style.display = "block";
                } else {
                    btnToTop.style.display = "none";
                }
            });

            // Animasi smooth scroll saat tombol "Kembali ke Atas" diklik
            btnToTop.addEventListener("click", function() {
                scrollToTop(1000); // Waktu animasi dalam milidetik (misalnya 1000ms atau 1 detik)
            });

            // Fungsi untuk animasi smooth scroll
            function scrollToTop(duration) {
                var start = window.pageYOffset;
                var startTime = performance.now();

                function scrollAnimation(currentTime) {
                    var elapsedTime = currentTime - startTime;
                    var ease = easeInOut(elapsedTime, start, -start, duration);
                    window.scrollTo(0, ease);
                    if (elapsedTime < duration) {
                        requestAnimationFrame(scrollAnimation);
                    }
                }

                // Fungsi interpolasi untuk animasi ease-in-out
                function easeInOut(t, b, c, d) {
                    t /= d / 2;
                    if (t < 1) return c / 2 * t * t + b;
                    t--;
                    return -c / 2 * (t * (t - 2) - 1) + b;
                }

                requestAnimationFrame(scrollAnimation);
            }
        });
    </script>
</body>

</html>
