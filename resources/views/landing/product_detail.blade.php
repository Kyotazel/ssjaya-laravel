@extends('layouts.landing')
@section('title', __($product->nama))

@section('style')
    <style>
        .article-content {
            text-align: justify;
        }

        .product-content p {
            margin-bottom: 1rem;
            margin-top: 0.5rem;
        }

        .product-content ul,
        .product-content ol {
            margin-bottom: 1rem;
            margin-top: 0.5rem;
            margin-left: 3rem;
        }

        .product-content ul {
            list-style: disc;
        }

        .product-content ol {
            list-style-type: decimal;
        }
    </style>
@endsection

@section('content')
    <div class="container" style="margin-top: 160px; margin-bottom: 40px; background-color: white; padding: 40px">
        <div class="row">
            <div class="col-md-12">
                <h2 style="text-align:center">{{ $product->nama }}</h2>
                <br>
                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6" style="text-align: center;">
                                <img src="{{ $product->image_url }}" alt="{{ $product->nama }}"
                                    style="width: 60%; margin:auto">
                            </div>
                            <div class="col-md-6" style="text-align:center; margin:auto">
                                <h3>Harga : Rp. {{ str_replace(',', '.', number_format($product->harga)) }}</h3>
                                <a href="https://api.whatsapp.com/send?phone=6282327271919&text=Halo,%20saya%20mau%20pesan%20produk%20*{{ $product->nama }}*.%20Apakah%20masih%20ada%3F"
                                    class="btn" style="margin-top: 40px;"><i class="fab fa-whatsapp"
                                        style="margin-right: 8px;"></i> Beli Sekarang</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mt-5">
                        <div class="row justify-content-center align-items-center mt-1">
                            <div class="col-auto">
                                <h3>Deskripsi</h3>
                            </div>
                            <div class="col">
                                <hr>
                            </div>
                            <div class="product-content">
                                {!! $product->deskripsi !!}
                            </div>
                        </div>
                        <div class="col-md-12 mt-5">
                            <div class="row justify-content-center align-items-center mt-1">
                                <div class="col-auto">
                                    <h3>Aturan pakai</h3>
                                </div>
                                <div class="col">
                                    <hr>
                                </div>
                            </div>
                            <div class="product-content">
                                {{ $product->aturan }}
                            </div>
                        </div>
                        <div class="col-md-12 mt-5">
                            <div class="row justify-content-center align-items-center mt-1">
                                <div class="col-auto">
                                    <h3>Khasiat / Manfaat</h3>
                                </div>
                                <div class="col">
                                    <hr>
                                </div>
                            </div>
                            <div class="product-content">
                                {!! $product->manfaat !!}
                            </div>
                        </div>
                        <div class="col-md-12 mt-5">
                            <div class="row justify-content-center align-items-center mt-1">
                                <div class="col-auto">
                                    <h3>Komposisi</h3>
                                </div>
                                <div class="col">
                                    <hr>
                                </div>
                            </div>
                            <div class="row">
                                @foreach ($product->compositions as $composition)
                                    <div class="col-md-1" style="text-align: center;"><img
                                            src="{{ $composition->image_url }}" alt="{{ $composition->nama }}"
                                            style="width: 50%;"></div>
                                    <div class="col-md-11">{!! $composition->nama !!}</div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-md-12 mt-5">
                            <div class="row justify-content-center align-items-center mt-1">
                                <div class="col-auto">
                                    <h3>Sertifikasi</h3>
                                </div>
                                <div class="col">
                                    <hr>
                                </div>
                            </div>
                            <div class="row">
                                @foreach ($product->certificates as $certificate)
                                    <div class="col-md-3" style="text-align: center;">
                                        <img src="{{ $certificate->image_url }}" alt="{{ $certificate->image_url }}"
                                            style="width: 100%;">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
