@extends('layouts.landing')
@section('title', __('Tentang kami'))

@section('style')
    <link rel="stylesheet" href="{{ asset('assets/themes/landing/css/about.min.css') }}" />
    <style>
        .features-list_item {
            padding-top: 40px;
            padding-bottom: 40px;
        }

        .header {
            height: 100px;
        }
    </style>
@endsection

@section('content')
    <section class="features" style="padding-bottom: 40px; padding-top:20px">
        <div class="container">
            <div class="features_header">
                <h2 class="features_header-title">Tentang Kami</h2>
            </div>
            <ul class="features-list d-md-flex flex-wrap justify-content-xl-between">
                <li class="features-list_item col-md-6 col-xl-4 d-flex flex-column align-items-center" data-order="1"
                    data-aos="fade-up">
                    <h4 class="title mb-4">Kantor Marketing</h4>
                    <div class="content">
                        <p class="text">
                            Jalan Raung Gang Belimbing No. 6, Kec. Mojoroto, Kota Kediri, Jawa Timur.
                        </p>
                        <br>

                    </div>
                </li>
                <li class="features-list_item col-md-6 col-xl-4 d-flex flex-column align-items-center" data-order="2"
                    data-aos="fade-up">
                    <h4 class="title mb-4">Warehouse / Gudang</h4>
                    <div class="content">
                        <p class="text">
                            Perumahan Mutiara Jayabaya Blok C 17, Kec. Mojoroto, Kota Kediri, Jawa Timur.
                        </p>
                        <br>

                    </div>
                </li>
                <li class="features-list_item col-md-6 col-xl-4 d-flex flex-column align-items-center" data-order="3"
                    data-aos="fade-up">
                    <h4 class="title">Moto</h4>
                    <p class="text"></p>
                </li>
                <li class="features-list_item col-md-6 col-xl-4 d-flex flex-column align-items-center" data-order="4"
                    data-aos="fade-up">
                    <h4 class="title">Foto Kantor</h4>
                    <img src="{{ asset('assets/images/example/office.jpeg') }}" alt="Gambar Kantor" style="width: 80%;">
                </li>
                <li class="features-list_item col-md-6 col-xl-8 d-flex flex-column align-items-center" data-order="5"
                    data-aos="fade-up">
                    <h4 class="title">Alamat Kantor</h4>
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d15810.173125325015!2d112.0008265!3d-7.8380643!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e78574b2d30520f%3A0x61ec4e01ff56dbfd!2sCV.%20SS%20JAYA%20GRUP!5e0!3m2!1sid!2sid!4v1675083535880!5m2!1sid!2sid"
                        width="600" height="100%" style="border:0;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                </li>
            </ul>
        </div>
    </section>

@endsection
