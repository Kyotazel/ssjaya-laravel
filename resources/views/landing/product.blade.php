@extends('layouts.landing')
@section('title', __('Produk'))

@section('content')
    <section class="myproducts" style="margin-bottom: 40px;">
        <div class="featured" style="padding-top: 20px">
            <div class="container">
                <div class="featured_header">
                    <h2 class="featured_header-title">Produk Kami</h2>
                </div>
                <div class="products_list d-grid">
                    <?php foreach($products as $product) : ?>
                    <div class="products_list-item">
                        <div class="products_list-item_wrapper d-flex flex-column">
                            <div class="media">
                                <a href="{{ route('product.detail', $product->url) }}" target="_blank"
                                    rel="noopener norefferer" style="margin: auto;">
                                    <img class="lazy preview"
                                        data-src="{{ $product->image_url }}"
                                        src="{{ $product->image_url }}" alt="Gokoles"
                                        style="height: 200px" />
                                </a>
                            </div>
                            <div class="main d-flex flex-column align-items-center justify-content-between">
                                <a class="main_title" href="{{ route('product.detail', $product->url) }}"
                                    target="_blank" rel="noopener norefferer"><?= $product->nama ?></a>
                                <div class="main_price">
                                    <span class="price">Rp.
                                        <?= str_replace(',', '.', number_format($product->harga)) ?></span>
                                </div>
                                <a class="btn btn--green" href="{{ route('product.detail', $product->url) }}"
                                    style="font-size: 16px;">
                                    <span>Lihat Selengkapnya</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach ?>
                </div>
            </div>
        </div>
    </section>
@endsection
