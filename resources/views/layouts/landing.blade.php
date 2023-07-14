<!DOCTYPE html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none">

@php
    $provinces = App\Models\Province::get();
@endphp

<!-- Mirrored from themesbrand.com/velzon/html/default/pages-starter.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 08 Aug 2022 01:44:41 GMT -->

<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta name="description" content="Aplikasi toko online SSJAYA.COM - Distributor Obat Herbal" />
    <title>SSJAYA - @yield('title')</title>
    <!-- OpenGraph Data -->
    <meta property="fb:app_id" content="701013790572210">
	<meta property="og:title" content="SSJAYA.COM - Distributor Obat Herbal" />
	<meta property="og:type" content="article" />
	<meta property="og:url" content="{{ url('/') }}" />
	<meta property="og:image" content="https://ssjaya.com/2023/cdn/assets/img/favicon-20201224211821.jpg" />
	<meta property="og:description" content="Aplikasi toko online SSJAYA.COM - Distributor Obat Herbal" />
	<meta property="og:site_name" content="SSJAYA.COM - Distributor Obat Herbal" />

    <link rel="stylesheet preload" as="style" href="{{ asset("assets/themes/landing/css/preload.min.css") }}" />
    <link rel="stylesheet preload" as="style" href="{{ asset("assets/themes/landing/css/libs.min.css") }}" />
    <link rel="shortcut icon" type="image/png" href="{{ asset("assets/images/logo/favicon.png") }}" />

    <link href="{{ asset("") }}assets/themes/velzon/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css">

    <link rel="stylesheet" href="{{ asset("assets/themes/landing/css/index.min.css") }}" />
    <link rel="stylesheet" href="{{ asset("assets/themes/landing/css/news2.min.css") }}" />
    <link rel="stylesheet" href="{{ asset("assets/themes/admin/dist/css/icons/font-awesome/css/fontawesome-all.css") }}" />
    <link rel="stylesheet" href="{{ asset("assets/themes/landing/css/custom.css") }}" />
    <style>
        .header {
            height: 100px;
        }
        .header_nav {
            width: 70%;
        }

        .header_button {
            background-color: #efc368;
            display: block;
            width: 200px;
            padding: 8px;
            border-radius: 20px;
            color: #214842;
            text-align: center;
            font-weight: bold;
        }

        .header_button:hover {
            color: #efc368;
            background-color: #214842;
            text-align: center;
        }

        .nav-link {
            color: #214842;
            position: relative;
            text-decoration: none;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 4px;
            border-radius: 4px;
            background-color: #214842;
            bottom: 0;
            left: 0;
            transform-origin: right;
            transform: scaleX(0);
            transition: transform .3s ease-in-out;
        }

        .nav-link:hover::before {
            transform-origin: left;
            transform: scaleX(1);
        }

        .jadi_mitra_kami {
            display: none;
        }

        @media (max-width: 768px) {
            .header_nav {
                width: 100%;
            }

            .header_trigger {
                order: 4;
            }

            .header_button {
                display: none;
            }

            .header_logo span:not(.logo) {
                display: block;
            }

            .jadi_mitra_kami {
                display: block;
            }
            .jadi_mitra_kami .header_button{
                display: block;
            }
            .copyright {
                margin-top: 60px;
            }
        }
    </style>
</head>

<body style="background-color: #f2f3f8;">
    <header class="header d-flex flex-wrap align-items-center" data-page="home" data-overlay="true">
        <div class="container d-flex flex-wrap flex-xl-nowrap align-items-center justify-content-between">
            <a class="brand header_logo d-flex align-items-center" href="{{ url('/') }}">
                <span class="logo">
                    <img src="{{ asset('assets/images/logo/favicon.png') }}" alt="logo" style="width: max-content;">
                </span>
                <span class="accent">SS</span>
                <span>JAYA</span>
            </a>
            <nav class="header_nav">
                <ul class="header_nav-list">
                    <li class="header_nav-list_item">
                        <a class="nav-link d-inline-flex align-items-center" href="">
                            Home
                        </a>
                    </li>
                    <li class="header_nav-list_item">
                        <a class="nav-link d-inline-flex align-items-center" href="">
                            Tentang Kami
                        </a>
                    </li>
                    <li class="header_nav-list_item dropdown">
                        <a class="nav-link dropdown-toggle d-inline-flex align-items-center" href="#" data-bs-toggle="collapse" data-bs-target="#mitraApotek" aria-expanded="false" aria-controls="mitraApotek">
                            Mitra Apotek 
                            <i class="icon-caret_down icon"></i>
                        </a>
                        <div class="dropdown-menu collapse" id="mitraApotek">
                            <ul class="dropdown-list">
                                @foreach ($provinces as $province)
                                <li class="list-item nav-item">
                                    <a class="dropdown-item" href=""></a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </li>
                    <li class="header_nav-list_item">
                        <a class="nav-link d-inline-flex align-items-center" href="">
                            Produk
                        </a>
                    </li>
                    <li class="header_nav-list_item">
                        <a class="nav-link d-inline-flex align-items-center" href="">
                            Konsultasi
                        </a>
                    </li>
                    <li class="header_nav-list_item jadi_mitra_kami">
                    <a href="https://seller.ssjaya.com" class="header_button">Jadi Mitra Kami <i class="fas fa-hand-holding-heart"></i></a>
                    </li>
                </ul>
            </nav>
            <span class="header_trigger d-inline-flex d-xl-none flex-column justify-content-between">
                <span class="line line"></span>
                <span class="line line"></span>
                <span class="line line"></span>
                <span class="line line"></span>
            </span>
            <div class="header_user d-flex justify-content-end align-items-center">
                <a href="https://seller.ssjaya.com" class="header_button">Jadi Mitra Kami <i class="fas fa-hand-holding-heart"></i></a>
            </div>
        </div>
    </header>

    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    <div class="main-content" style="min-height: 600px; margin-top: 100px;">
        @yield('content')
    </div>

    <footer class="footer">
        <div class="footer_main section" style="padding-bottom: 40px; padding-top: 40px;">
            <div class="container">
                <div class="row mb-5">
                    <div class="col-md-6">
                        <h4 style="color: white;">Follow Us</h4>
                    </div>
                    <div class="col-md-6">
                        <ul class="social s  d-flex align-items-center accent" style="float: right">
                            <li class="list-item" style="padding-right: 20px;">
                                <a class="link" href="https://www.facebook.com/profile.php?id=100083182727968" target="_blank" rel="noopener norefferer">
                                    <i class="icon-facebook icon"></i>
                                </a>
                            </li>
                            <li class="list-item" style="padding-right: 20px;">
                                <a class="link" href="https://instagram.com/ssjayaherbal" target="_blank" rel="noopener norefferer">
                                    <i class="icon-instagram icon"></i>
                                </a>
                            </li>
                            <li class="list-item" style="padding-right: 20px;">
                                <a class="link" href="mailto:admin@ssjaya.com" target="_blank" rel="noopener norefferer">
                                    <i class="icon-mail icon"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-12" style="padding-top: 32px;">
                        <hr>
                    </div>
                </div>
            </div>
            <div class="container d-flex flex-column flex-md-row flex-wrap flex-xl-nowrap justify-content-xl-between">
                <div class="footer_main-about footer_main-block col-md-6 col-xl-auto">
                    <a class="brand footer_main-about_brand d-flex align-items-center" href="{{ url('/') }}">
                        <span class="logo">
                            <img src="{{ asset("assets/images/logo/favicon.png") }}" alt="logo" style="width: max-content;">
                        </span>
                        <span class="accent">SS</span>
                        <span>JAYA</span>
                    </a>
                    <div class="footer_main-about_wrapper">
                        <b>Kantor Marketing</b>
                        <p class="text">
                            Jalan Raung Gang Belimbing No. 6, Kec. Mojoroto, Kota Kediri, Jawa Timur.

                        </p>
                        <b>Warehouse / Gudang</b>
                        <p class="text">
                            Perumahan Mutiara Jayabaya Blok C 17, Kec. Mojoroto, Kota Kediri, Jawa Timur.
                        </p>

                    </div>
                </div>
                <div class="footer_main-nav footer_main-block col-md-6 col-xl-auto">
                    <h4 class="footer_main-nav_header footer_main-header">Selalu Terhubung</h4>
                    <ul class="footer_main-nav_list d-flex flex-wrap flex-md-column">
                        <li class="list-item">
                            <a class="link d-inline-flex align-items-center" href="https://wa.me/+6282327271919">
                                <i class="icon-whatsapp accent icon"></i>
                                CS 1 (Dea)
                            </a>
                        </li>
                        <li class="list-item">
                            <a class="link d-inline-flex align-items-center" href="https://wa.me/+6282379790404">
                                <i class="icon-whatsapp accent icon"></i>
                                CS 2 (Dina)
                            </a>
                        </li>
                        <li class="list-item">
                            <a class="link d-inline-flex align-items-center" href="https://wa.me/+6282189896363">
                                <i class="icon-whatsapp accent icon"></i>
                                CS 3 (Yuli)
                            </a>
                        </li>
                        <li class="list-item">
                            <a class="link d-inline-flex align-items-center" href="https://wa.me/+6285298988080">
                                <i class="icon-whatsapp accent icon"></i>
                                CS 4 (Anis)
                            </a>
                        </li>
                        <li class="list-item">
                            <a class="link d-inline-flex align-items-center" href="https://wa.me/+6282327270808">
                                <i class="icon-whatsapp accent icon"></i>
                                CS 5 (Ratna)
                            </a>
                        </li>
                        <li class="list-item">
                            <a class="link d-inline-flex align-items-center" href="https://wa.me/+6282189894949">
                                <i class="icon-whatsapp accent icon"></i>
                                CS 6 (Ayik)
                            </a>
                        </li>
                        <li class="list-item">
                            <a class="link d-inline-flex align-items-center" href="https://wa.me/+6282189894848">
                                <i class="icon-whatsapp accent icon"></i>
                                CS 7 (Mirda)
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="footer_main-contacts footer_main-block col-md-6 col-xl-auto">
                    <h4 class="footer_main-contacts_header footer_main-header">Hubungi Kami</h4>
                    <ul class="footer_main-contacts_list">
                        <li class="list-item d-flex align-items-center">
                            <span class="icon d-flex justify-content-center align-items-center">
                                <i class="icon-clock"></i>
                            </span>
                            <div class="wrapper d-flex flex-column">
                                <span>08.00 – 16.00 WIB</span>
                                <span>Senin - Sabtu</span>
                            </div>
                        </li>
                        <li class="list-item d-flex align-items-center">
                            <span class="icon d-flex justify-content-center align-items-center">
                                <i class="icon-call"></i>
                            </span>
                            <div class="wrapper d-flex flex-column">
                                <a class="link" href="tel:+6282371719393"> +6282371719393</a>
                            </div>
                        </li>
                        <li class="list-item d-flex align-items-center">
                            <span class="icon d-flex justify-content-center align-items-center">
                                <i class="icon-mail"></i>
                            </span>
                            <div class="wrapper d-flex flex-column">
                                <a class="link" href="mailto:admin@ssjaya.com">admin@ssjaya.com</a>
                            </div>
                        </li>

                    </ul>
                </div>
                <div class="footer_main-instagram footer_main-block col-md-6 col-xl-auto">
                    <h4 class="footer_main-instagram_header footer_main-header">Sertifikasi</h4>
                    
                     <ul class="socials d-flex align-items-center accent">
                         <li class="list-item">
                            <a class="link" href="#" target="_blank" rel="noopener norefferer">
                                <img style="height: 60px; width: auto; margin-left: 8px; margin-right: 8px" src="{{ asset('assets/images/logo/bpom.jpg') }}" alt="Shopee">
                            </a>
                        </li>
                        <li class="list-item">
                            <a class="link" href="#" target="_blank" rel="noopener norefferer">
                                <img style="height: 60px; width: auto; margin-left: 8px; margin-right: 8px" src="{{ asset('assets/images/logo/halal.jpg') }}" alt="Shopee">
                            </a>
                        </li>
                     </ul>
                    <br>
                    <h4 class="footer_main-instagram_header footer_main-header">Marketplace</h4>
                    <ul class="socials d-flex align-items-center accent">
                        <li class="list-item">
                            <a class="link" href="#" target="_blank" rel="noopener norefferer">
                                <img style="height: 60px; width: auto; margin-left: 8px; margin-right: 8px" src="{{ asset('assets/images/logo/Shopee.jpg') }}" alt="Shopee">
                            </a>
                        </li>
                        <li class="list-item">
                            <a class="link" href="" target="_blank" rel="noopener norefferer">
                                <img style="height: 60px; width: auto; margin-left: 8px; margin-right: 8px" src="{{ asset('assets/images/logo/Tokopedia.jpg') }}" alt="Tokopedia">
                            </a>
                        </li>
                        <li class="list-item">
                            <a class="link" href="mailto:admin@ssjaya.com" target="_blank" rel="noopener norefferer">
                                <img style="height: 60px; width: auto; margin-left: 8px; margin-right: 8px" src="{{ asset('assets/images/logo/Lazada.jpg') }}" alt="Lazada">
                            </a>
                        </li>
                    </ul>
                    <h4 class="footer_main-instagram_header footer_main-header mt-4">Jasa Pengiriman</h4>
                    <ul class="socials d-flex align-items-center accent mt-3">
                        <li class="list-item">
                            <a class="link" href="#" target="_blank" rel="noopener norefferer">
                                <img style="height: 60px; width: auto; margin-left: 8px; margin-right: 8px; background-color: white; padding: 4px" src="{{ asset('assets/images/logo/kurir-jne.jpg') }}" alt="jne">
                            </a>
                        </li>
                        <li class="list-item">
                            <a class="link" href="" target="_blank" rel="noopener norefferer">
                                <img style="height: 60px; width: auto; margin-left: 8px; margin-right: 8px; background-color: white; padding: 4px" src="{{ asset('assets/images/logo/Kurir-jnt.jpg') }}" alt="jnt">
                            </a>
                        </li>
                        <li class="list-item">
                            <a class="link" href="mailto:admin@ssjaya.com" target="_blank" rel="noopener norefferer">
                                <img style="height: 60px; width: auto; margin-left: 8px; margin-right: 8px; background-color: white; padding: 4px" src="{{ asset('assets/images/logo/Kurir-ninja.jpg') }}" alt="ninja">
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div style="text-align: center;" class="copyright">
                    @ 2023 Copyright: <a href="{{ url('/') }}" style="color: #efc368">ssjaya.com</a>
                </div>
        </div>

    </footer>
    </div>
    <!-- end main content-->

    <!-- END layout-wrapper -->

    <!-- JAVASCRIPT -->
    <script src="{{ asset('assets/themes/velzon/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('assets/themes/landing/js/common.min.js') }}"></script>
    <script src="{{ asset('assets/themes/landing/js/index.min.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide-extension-grid@0.4.1/dist/js/splide-extension-grid.min.js"></script>
    <script src="{{ asset('assets/themes/velzon/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    {{-- <script src="' . base_url('application/modules/front-modules/template/js/js-module-configuration.js') . '"></script> --}}

    @stack('script')
</body>


<!-- Mirrored from themesbrand.com/velzon/html/default/pages-starter.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 08 Aug 2022 01:44:41 GMT -->

</html>