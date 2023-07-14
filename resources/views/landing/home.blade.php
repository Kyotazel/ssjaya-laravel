@extends('layouts.landing')
@section('title', __('Distribuse Obat Herbal'))
@section('content')
    <style>
        .testimonial .info {
            background: url({{ asset('assets/images/example/noise.webp') }} ) center, 0 0/cover #214842;
            background-blend-mode: overlay;
            position: relative;
            overflow: hidden;
        }

        .news_list-item_wrapper .main,
        .news_list-item_wrapper .main_title {
            flex-grow: 0;
        }

        .news_list-item_wrapper {
            -webkit-box-shadow: 0 0 15px rgb(37 143 103 / 10%);
            box-shadow: 0 0 15px rgb(37 143 103 / 10%);
            border-radius: 16px;
            overflow: hidden;
            height: 100%;
            -webkit-transition: .3s ease-in-out;
            -o-transition: .3s ease-in-out;
            transition: .3s ease-in-out;
            background: #fff;
            padding: 12px;
        }

        .btn_view_more {
            color: #214842;
            font-weight: bold;
            border-radius: 12px;
        }

        .media {
            position: relative;
        }

        .category_article {
            position: absolute;
            top: 8px;
            left: 16px;
            background-color: blue;
            color: white;
            padding: 8px;
            font-weight: bolder;
            border-radius: 999em 999em 999em 999em;
        }

        #thumbnail-carousel-product .splide__slide img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            margin: auto;
        }

        #thumbnail-carousel-product .splide__arrow--prev,
        #thumbnail-carousel-product .splide__arrow--next {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            height: 100%;
            width: 100px;
            overflow: hidden;
        }

        #thumbnail-carousel-product .splide__arrow--prev {
            left: -100px;
            border-radius: 50% 0 0 50%;
        }

        #thumbnail-carousel-product .splide__arrow--next {
            right: -100px;
            border-radius: 0 50% 50% 0;
        }

        #thumbnail-carousel-product .splide__slide {
            filter: brightness(0.9);
            background-color: white;
            border: 1px solid lightgrey;
        }

        #thumbnail-carousel-product .splide__slide.is-active {
            filter: brightness(1);
        }

        .menu_home {
            display: none;
        }

        .box_menu_item {
            background-color: #214842;
            width: 160px;
            height: 160px;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: auto;
            margin-bottom: 20px;
            border-radius: 20%;
        }

        .list_menu_item {
            width: 100px;
            height: 100px;
            background-color: #375a54;
            border-radius: 20%;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            opacity: 0.9;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            /* jarak atas dan bawah dari modal */
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            /* lebar modal */
            position: relative;
            animation-name: modalopen;
            animation-duration: 0.5s;
        }

        @keyframes modalopen {
            from {
                top: -300px;
                opacity: 0;
            }

            to {
                top: 0;
                opacity: 1;
            }
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .list_province {
            display: flex;
            flex-direction: row;
            justify-content: center;
            flex-wrap: wrap;
        }

        .list_province>.thisbutton {
            font-size: 16px;
            padding: 8px;
            color: white;
            margin: 10px;
            border-radius: 100px;
            color: mediumseagreen;
            background-color: white;
            border: 1px solid mediumseagreen;
        }

        .list_province>.thisbutton:hover {
            color: white;
            background-color: mediumseagreen;
        }

        .list_province>.selected {
            background-color: mediumseagreen;
            color: white;
        }

        @media (max-width: 768px) {
            #thumbnail-carousel-product {
                display: none;
            }

            .testimoni_product {
                display: none;
            }

            .testimoni_person img {
                width: 50%;
                height: auto;
                margin: auto;
            }

            .menu_home {
                display: block;
            }
        }
    </style>

    <section class="carousel">
        <section id="my-carousel" class="splide" aria-label="My Carousel Image">
            <div class="splide__track">
                <ul class="splide__list">
                    <?php foreach ($carousels as $carousel) : ?>
                    <li class="splide__slide" data-splide-interval="3000">
                        <img src="{{ $carousel->image_url }}" style="width:100%;">
                    </li>
                    <?php endforeach ?>
                </ul>
            </div>
        </section>
    </section>

    <section class="menu_home">
        <div class="featured" style="padding-top: 60px;">
            <div class="container">
                <div class="featured_header">
                    <h2 class="featured_header-title">Menu</h2>
                </div>
                <div class="box_menu_list">
                    <div class="row">
                        <div class="col-6">
                            <a href="" class="box_menu_item">
                                <div class="list_menu_item">
                                    <img src="{{ asset('assets/images/logo/produk.svg') }}" alt=""
                                        style="height: 40px; width: auto; margin-bottom: 8px">
                                    <span style="color: white; text-align:center; font-size: 12px;">Produk</span>
                                </div>
                            </a>
                        </div>
                        <div class="col-6">
                            <button id="myBtn" class="box_menu_item">
                                <div class="list_menu_item">
                                    <img src="{{ asset('assets/images/logo/mitra.svg') }}" alt=""
                                        style="height: 40px; width: auto; margin-bottom: 8px">
                                    <span style="color: white; text-align:center; font-size: 12px">Mitra Apotek</span>
                                </div>
                            </button>
                        </div>
                        <div class="col-6">
                            <a href="" class="box_menu_item">
                                <div class="list_menu_item">
                                    <img src="{{ asset('assets/images/logo/about-us.svg') }}" alt=""
                                        style="height: 40px; width: auto; margin-bottom: 8px">
                                    <span style="color: white; text-align:center; font-size: 12px">Tentang Kami</span>
                                </div>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="s="box_menu_item">
                                <div class="list_menu_item">
                                    <img src="{{ asset('assets/images/logo/konsultasi.svg') }}" alt=""
                                        style="height: 40px; width: auto; margin-bottom: 8px">
                                    <span style="color: white; text-align:center; font-size: 12px">Konsultasi</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="myproducts" style="margin-bottom: 40px;">
        <div class="featured" style="padding-top: 60px;">
            <div class="container">
                <div class="featured_header">
                    <h2 class="featured_header-title">Produk Kami</h2>
                </div>
                <div class="my_list_product" style="max-width: 90%; margin: auto">
                    <section id="main-carousel" class="splide my-4" aria-label="My Awesome Gallery">
                        <div class="splide__track">
                            <ul class="splide__list">
                                @foreach ($products as $product)
                                    @php
                                        $preview = '';
                                        $jumlah_huruf = strlen($product->deskripsi);
                                        if ($jumlah_huruf != 0) {
                                            $preview = substr(strip_tags($product->deskripsi), 0, 100) . '...';
                                        }
                                    @endphp
                                    <li class="splide__slide" data-splide-interval="3000">
                                        <div class="product_title" style="text-align: center;">
                                            <h3 style="color: black;"><?= $product->nama ?></h3>
                                            <div class="row mt-4">
                                                <div class="col-md-5">
                                                    <img src="{{ $product->image_url }}/"
                                                        alt=" {{ $product->nama }} " style="width: 200px; margin:auto">
                                                </div>
                                                <div class="col-md-7">
                                                    <div class="content_product"
                                                        style="margin: auto;margin-top: 40px; max-width: 80%;">
                                                        <h5 style="color: black;"><?= $preview ?></h5>
                                                        <a href="" class="btn"
                                                            style="margin-top: 40px;">Selengkapnya</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </section>

                    <div style="max-width: 700px; margin: auto">
                        <section id="thumbnail-carousel-product" class="splide">
                            <div class="splide__track">
                                <ul class="splide__list">
                                    @foreach ($products as $product)
                                        @php
                                            $photo = $product->image_url;
                                            if ($product->merk_image_url) {
                                                $photo = $product->merk_image_url;
                                            }
                                        @endphp
                                        <li class="splide__slide">
                                            <img src="{{ $photo }}"
                                                alt="<?= $product->nama ?>" style="width: 100%;">
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="testimonial" style="margin-bottom: 40px;">
        <section class="info section" style="padding: 40px;">
            <div class="container" style="text-align: center;">
                <div class="row" style="margin-bottom: 20px;">
                    <div class="col-md-12">
                        <h2 style="color: white;">Ulasan Customer Kami</h2>
                    </div>
                </div>
                <section id="testimoni-carounsel" class="splide my-4" aria-label="My Awesome Gallery">
                    <div class="splide__track">
                        <ul class="splide__list">
                            @foreach ($testimonials as $testimonial)
                                <li class="splide__slide" data-splide-interval="3000">
                                    <div class="row">
                                        <div class="col-md-2 testimoni_person">
                                            <img class="lazy preview"
                                                src="{{ $testimonial->image_url }}"
                                                alt="<?= $testimonial->nama ?>" style="border-radius: 50%;" />
                                        </div>
                                        <div class="col-md-8 testimoni_comment" style="color: white; margin-top:8vh;">
                                            <?= $testimonial->komentar ?></div>
                                        <div class="col-md-2 testimoni_product">
                                            <img class="lazy preview"
                                                src="{{ $testimonial->product->image_url ?? '' }}"
                                                alt="{{ $testimonial->product->nama ?? '' }}" />
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </section>
            </div>
        </section>
    </section>

    <section class="artikel" style="margin-bottom: 40px;">
        <div class="news_title" style="text-align: center; margin-bottom: 30px">
            <h2>Artikel Terbaru</h2>
        </div>
        <div class="container d-lg-flex justify-content-between">
            <div class="">
                <ul class="news_list d-flex flex-wrap">
                    @foreach ($blogs as $blog)
                        @php
                            $preview_article = '';
                            $jumlah_huruf = strlen($blog->konten);
                            if ($jumlah_huruf != 0) {
                                $preview_article = substr(strip_tags($blog->konten), 0, 150) . '...';
                            }
                        @endphp
                        <li class="news_list-item col-md-4">
                            <div class="news_list-item_wrapper d-flex flex-column">
                                <div class="media">
                                    <img class="lazy article_image" src="{{ $blog->image_url }}"
                                        style="height: 250px;" alt="<?= $blog->judul ?>" />
                                    <p class="category_article" style="background-color: <?= $blog->category_color ?>;">
                                        <?= $blog->category_name ?></p>
                                </div>
                                <div class="main d-flex flex-column justify-content-between">
                                    <div class="main_metadata">
                                        <span class="main_metadata-item">
                                            <i class="icon-calendar_day icon"></i>
                                            {{ $blog->tgl }}
                                        </span>
                                    </div>
                                    <a class="main_title" href="" target="_blank"
                                        rel="noopener norefferer"><?= $blog->judul ?></a>
                                    <p class="main_preview"><?= $preview_article ?></p>
                                </div>
                                <div class="view_more" style="margin-top: 20px;">
                                    <a class="btn_view_more" href="">Baca
                                        Selengkapnya</a>
                                </div>
                            </div>
                        </li>
                    @endforeach

                </ul>
            </div>
        </div>
        <div style="text-align: center; margin-top: 20px">
            <a class="btn btn--green" href=""">Lihat Semua Artikel</a>
        </div>
    </section>

    <div id="myModal" class="modal">
        <div class="modal-content">
            <div class="row">
                <div class="col-md-12">
                    <span class="close">&times;</span>
                </div>
                <div class="col-md-12">
                    <div class="row">
                        <div class="list_province">
                            <?php foreach ($provinces as $province) : ?>
                            <a href="" class="thisbutton" data-id="<?= $province->id ?>"><img
                                    src="{{ asset('assets/images/logo/home.svg') }}"
                                    style="display: inline-block; width: 20px; vertical-align: bottom;">
                                <span><?= $province->nama ?></span></a>
                            <?php endforeach ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        var my = new Splide("#my-carousel", {
            type: "loop",
            autoplay: "playing",
            rewind: true,
        });

        my.mount();

        var product = new Splide("#main-carousel", {
            type: "fade",
            autoplay: "playing",
            pagination: false,
            rewind: true,
            arrows: false,
            breakpoints: {
                768: {
                    pagination: true,
                    arrows: true
                },
            },
        });

        var product_thumbnail = new Splide("#thumbnail-carousel-product", {
            arrows: true,
            fixedWidth: 180,
            fixedHeight: 100,
            rewind: true,
            pagination: false,
            isNavigation: true,
            breakpoints: {
                768: {
                    fixedWidth: 60,
                    fixedHeight: 44,
                },
            },
        });

        product.sync(product_thumbnail);
        product.mount();
        product_thumbnail.mount();

        // Testimoni
        var testimoni = new Splide("#testimoni-carounsel", {
            type: "loop",
            autoplay: "playing",
            rewind: true,
        });

        testimoni.mount();


        // ketika tombol di klik, tampilkan modal
        $("#myBtn").on("click", function() {
            $("#myModal").css("display", "block");
        });

        // ketika pengguna mengklik span (x), tutup modal
        $(".close").on("click", function() {
            $("#myModal").css("display", "none");
        });

        // ketika pengguna mengklik di luar modal, tutup modal
        $(window).on("click", function(event) {
            if (event.target == $("#myModal")[0]) {
                $("#myModal").css("display", "none");
            }
        });
    </script>
@endpush
