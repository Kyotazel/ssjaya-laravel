<!DOCTYPE html>
<html dir="ltr" translate="no">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/logo/favicon.png') }}" type="image/x-icon">
    <title>Admin - @yield('title')</title>
    <!-- This page css -->
    <!-- Custom CSS -->
    <link href="{{ asset('assets/themes/admin/assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}"
        rel="stylesheet">
    <link href="{{ asset('assets/themes/admin/dist/css/style.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/themes/velzon/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="{{ asset('assets/themes/velzon/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet"
        type="text/css" />

    <style>
        .select2-container {
            display: block;
        }

        .ck-editor__editable_inline {
            min-height: 400px;
        }
    </style>

    @yield('style')

</head>

<body>
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper" data-theme="light" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed" data-boxed-layout="full">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <header class="topbar" data-navbarbg="skin6">
            <nav class="navbar top-navbar navbar-expand-md">
                <div class="navbar-header" data-logobg="skin6">
                    <!-- This is for the sidebar toggle which is visible on mobile only -->
                    <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)"><i
                            class="ti-menu ti-close"></i></a>
                    <!-- ============================================================== -->
                    <!-- Logo -->
                    <!-- ============================================================== -->
                    <div class="navbar-brand">
                        <!-- Logo icon -->
                        <a href="#">
                            <b class="logo-icon">
                                <!-- Dark Logo icon -->
                                <img src="{{ asset('assets/images/logo/favicon.png') }}" alt="homepage"
                                    class="dark-logo" />
                                <!-- Light Logo icon -->
                                <img src="{{ asset('assets/images/logo/favicon.png') }}" alt="homepage"
                                    class="light-logo" />
                            </b>
                            <!--End Logo icon -->
                            <!-- Logo text -->
                            <span class="logo-text">
                                <!-- dark Logo text -->
                                <img src="{{ asset('assets/images/logo/logo-text.png') }}" alt="homepage"
                                    class="dark-logo" style="height: 28px;" />
                                <!-- Light Logo text -->
                                <img src="{{ asset('assets/images/logo/logo-text.png') }}" class="light-logo"
                                    alt="homepage" style="height: 200px;" />
                            </span>
                        </a>
                    </div>
                    <!-- ============================================================== -->
                    <!-- End Logo -->
                    <!-- ============================================================== -->
                    <!-- ============================================================== -->
                    <!-- Toggle which is visible on mobile only -->
                    <!-- ============================================================== -->
                    <a class="topbartoggler d-block d-md-none waves-effect waves-light" href="javascript:void(0)"
                        data-toggle="collapse" data-target="#navbarSupportedContent"
                        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><i
                            class="ti-more"></i></a>
                </div>
                <!-- ============================================================== -->
                <!-- End Logo -->
                <!-- ============================================================== -->
                <div class="navbar-collapse collapse" id="navbarSupportedContent">
                    <!-- ============================================================== -->
                    <!-- toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav float-left mr-auto ml-3 pl-1">
                        <!-- ============================================================== -->
                        <!-- create new -->
                        <!-- ============================================================== -->
                        <li class="nav-item">
                            <a class="nav-link" href="#" role="button">
                                @yield('title')
                            </a>
                        </li>
                    </ul>
                    <a href="{{ route('admin.logout') }}" class="text-dark"><i data-feather="log-out"
                            class="feather-icon"></i> Logout</a>
                </div>
            </nav>
        </header>
        <!-- ============================================================== -->
        <!-- End Topbar header -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <aside class="left-sidebar" data-sidebarbg="skin6">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar" data-sidebarbg="skin6">
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">
                        <li class="sidebar-item">
                            <a href="{{ route('admin.dashboard') }}" class="sidebar-link">
                                <i class="mdi mdi-home"></i>
                                <span class='hide-menu'>Dashboard</span>
                            </a>
                        </li>

                        <li class='nav-small-cap'><span class='hide-menu'>Master Data</span></li>
                        <li class="sidebar-item">
                            <a href="{{ route('admin.carousel.index') }}" class="sidebar-link">
                                <i class="ri-speed-line"></i>
                                <span class='hide-menu'>Carousel</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ route('admin.product.index') }}" class="sidebar-link">
                                <i class="ri-product-hunt-line"></i>
                                <span class='hide-menu'>Produk</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ route('admin.pharmacy.index') }}" class="sidebar-link">
                                <i class="mdi mdi-home-group"></i>
                                <span class='hide-menu'>Apotek</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="#" class="sidebar-link has-arrow" aria-expanded="false">
                                <i class="ri-article-line"></i>
                                <span class='hide-menu'>Artikel</span>
                            </a>
                            <ul aria-expanded='false' class='collapse  first-level base-level-line'>
                                <li class="sidebar-item">
                                    <a href="{{ route('admin.blog-category.index') }}" class="sidebar-link">Kategori
                                        Artikel</a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ route('admin.blog.index') }}" class="sidebar-link">Artikel</a>
                                </li>
                            </ul>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ route('admin.testimoni.index') }}" class="sidebar-link">
                                <i class="ri-account-box-line"></i>
                                <span class='hide-menu'>Testimoni</span>
                            </a>
                        </li>
                        <li class="sidebar-item has-arrow" aria-expanded="false">
                            <a href="#" class="sidebar-link">
                                <i class="ri-community-line"></i>
                                <span class='hide-menu'>Alamat</span>
                            </a>
                            <ul aria-expanded="false" class="collapse first-level base-level-line">
                                <li class="sidebar-item">
                                    <a href="{{ route('admin.province.index') }}" class="sidebar-link">Provinsi</a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ route('admin.city.index') }}" class="sidebar-link">Kota</a>
                                </li>
                            </ul>
                        </li>

                        <li class='nav-small-cap'><span class='hide-menu'>Transaksi Data</span></li>
                        <li class="sidebar-item">
                            <a href="{{ route('admin.ongoing-request.index') }}" class="sidebar-link">
                                <i data-feather="log-out" class="feather-icon"></i>
                                <span class='hide-menu'>Barang Keluar</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ route('admin.deposit-report.index') }}" class="sidebar-link">
                                <i data-feather="log-in" class="feather-icon"></i>
                                <span class='hide-menu'>Setoran Barang</span>
                            </a>
                        </li>


                        <li class='nav-small-cap'><span class='hide-menu'>Laporan</span></li>
                        <li class="sidebar-item">
                            <a href="{{ route('admin.visit.index') }}" class="sidebar-link">
                                <i class="fa fa-image"></i>
                                <span class='hide-menu'>Laporan Kunjungan</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ route('admin.sales-report.index') }}" class="sidebar-link">
                                <i class="fa fa-user"></i>
                                <span class='hide-menu'>Produk Sales</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ route('admin.pharmacy-report.index') }}" class="sidebar-link">
                                <i class="fa fa-home"></i>
                                <span class='hide-menu'>Produk Apotek</span>
                            </a>
                        </li>

                    </ul>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </aside>
        <!-- ============================================================== -->
        <!-- End Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <div class="page-breadcrumb">
                <div class="d-flex align-items-center">
                    <h4 class="page-title text-truncate text-dark font-weight-medium mb-0">@yield('title')</h4>
                    <div class="ml-auto">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb m-0 p-0">
                                <li class="breadcrumb-item text-muted active" aria-current="page">SSJaya</li>
                                <li class="breadcrumb-item text-muted" aria-current="page">@yield('title')</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">

                        {{-- Main Content --}}
                        @yield('content')

                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- footer -->
            <!-- ============================================================== -->
            <footer class="footer text-center">
                Copyright 2023 @ SSjaya
            </footer>
            <!-- ============================================================== -->
            <!-- End footer -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- End Page -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="{{ asset('assets/themes/admin/assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/themes/admin/assets/libs/popper.js/dist/umd/popper.min.js') }}"></script>
    <script src="{{ asset('assets/themes/admin/assets/libs/popper.js/dist/umd/popper.min.js') }} "></script>
    <script src="{{ asset('assets/themes/admin/assets/libs/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <!-- apps -->
    <script src="{{ asset('assets/themes/admin/dist/js/app-style-switcher.js') }}"></script>
    <script src="{{ asset('assets/themes/admin/dist/js/feather.min.js') }}"></script>
    <script src="{{ asset('assets/themes/admin/assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js') }}">
    </script>
    <script src="{{ asset('assets/themes/admin/dist/js/sidebarmenu.js') }}"></script>
    <script src="{{ asset('assets/themes/admin/assets/extra-libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/themes/admin/dist/js/pages/datatable/datatable-basic.init.js') }}"></script>
    <script src="{{ asset('assets/themes/velzon/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ asset('assets/themes/velzon/libs/feather-icons/feather.min.js') }}"></script>
    <!-- Sweet Alerts js -->
    <script src="{{ asset('assets/themes/velzon/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <!-- CKEditor -->
    <script src="https://cdn.ckeditor.com/ckeditor5/36.0.0/classic/ckeditor.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!--Custom JavaScript -->
    <script src="{{ asset('assets/themes/admin/dist/js/custom.min.js') }}"></script>

    <script src="{{ asset('assets/config.js') }}"></script>

    @yield('script')
</body>

</html>
