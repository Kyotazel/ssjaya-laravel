@extends('layouts.landing')
@section('title', __('Konsultasi'))

@section('style')
    <style>
        .cta {
            background-color: #214842;
            /* background: linear-gradient(249.77deg, #efc368 -1.99%, #214842 43.52%, #efc368 95.86%); */
            min-height: 200px;
            max-width: 70%;
            margin: auto;
            border-radius: 24px;
        }
    </style>
@endsection

@section('content')
    <section class="myproducts" style="margin-bottom: 40px;">
        <div class="featured" style="padding-top: 20px">
            <div class="container">
                <div class="featured_header">
                    <h2 class="featured_header-title">Konsultasi</h2>
                </div>
                <div class="cta">
                    <div class="row" style="padding: 60px 12px 60px 12px;">
                        <div class="col-md-12" style="text-align: center;">
                            <p style="color: white;">CV. SS JAYA GRUP menyediakan layanan konsultasi untuk masalah penyakit
                                anda. Untuk melakukan konsultasi, klik nomor berikut : </p>
                            <a href="https://wa.me/6282327271919" class="btn" style="margin-top: 40px;"><i
                                    class="fab fa-whatsapp" style="margin-right: 12px;"></i>Hubungi Kami</a>
                            <!-- <a href="https://instagram.com/ssjayaherbal" class="btn" style="margin-top: 20px;"><i class="fab fa-instagram" style="margin-right: 12px;"></i>@ssjayaherbal </a> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
