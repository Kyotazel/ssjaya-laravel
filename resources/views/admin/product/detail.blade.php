@extends('layouts.admin')
@section('title', __('Detail Produk'))

@section('content')
    <div class="card">
        <div class="card-body text-dark">
            <h2>{{ $product->nama }}</h2>
            <br>
            <img src="{{ $product->image_url }}" alt="" style="width: 20%; margin-right: 20px">
            <img src="{{ $product->merk_image_url }}" alt="Merk Produk" style="width: 20%;">
            <div class="row mt-3">
                <div class="col-md-12">
                    <h4>Harga : Rp. <?= str_replace(',', '.', number_format($product->harga)) ?></h4>
                </div>
                <div class="col-md-12 mt-5">
                    <h2>Deskripsi</h2>
                    <hr>
                    {!! $product->deskripsi !!}
                </div>
                <div class="col-md-12 mt-5">
                    <h2>Aturan pakai</h2>
                    <hr>
                    {!! $product->aturan !!}
                </div>
                <div class="col-md-12 mt-5">
                    <h2>Khasiat / Manfaat</h2>
                    <hr>
                    {!! $product->manfaat !!}
                </div>
                <div class="col-md-12 mt-5">
                    <h2>Komposisi</h2>
                    <hr>
                    <div class="row">
                        @foreach ($product->compositions as $composition)
                            <div class="col-md-1" style="text-align: center;"><img src="{{ $composition->image_url }}"
                                    alt="{{ $composition->nama }}" style="width: 50%;"></div>
                            <div class="col-md-11">{!! $composition->nama !!}</div>
                        @endforeach
                    </div>
                </div>
                <div class="col-md-12 mt-5">
                    <h2>Sertifikasi</h2>
                    <hr>
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
@endsection
