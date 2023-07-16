@extends('layouts.landing')
@section('title', __('Artikel'))

@section('style')
<style>
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
</style>
@endsection

@section('content')
<section class="artikel" style="margin-bottom: 40px; padding-top:20px">
    <div class="news_title" style="text-align: center; margin-bottom: 30px">
        <h2>Artikel Terbaru</h2>
    </div>
    <div class="container d-lg-flex justify-content-between">
        <div class="">
            <ul class="news_list d-flex flex-wrap">
                @foreach ($articles as $article)
                    @php
                        $preview_article = '';
                    $jumlah_huruf = strlen($article->konten);
                    if ($jumlah_huruf != 0) {
                        $preview_article = (substr(strip_tags($article->konten), 0, 150)) . "...";
                    }
                    @endphp
                    <li class="news_list-item col-md-4">
                        <div class="news_list-item_wrapper d-flex flex-column">
                            <div class="media">
                                <img class="lazy article_image" src="{{ $article->image_url }}" style="height: 250px;" alt="{{ $article->judul }}" />
                                <p class="category_article" style="background-color: {{ $article->category->color ?? '' }};">{{ $article->category->name ?? '' }}</p>
                            </div>
                            <div class="main d-flex flex-column justify-content-between">
                                <div class="main_metadata">
                                    <span class="main_metadata-item">
                                        <i class="icon-calendar_day icon"></i>
                                        {{ date('d M Y', strtotime($article->tgl)) }}
                                    </span>
                                </div>
                                <a class="main_title" href="{{ route('article.detail', $article->url) }}" target="_blank" rel="noopener norefferer">{{ $article->judul }}</a>
                                <p class="main_preview">{{ $preview_article }}</p>
                            </div>
                            <div class="view_more" style="margin-top: 20px;">
                                <a class="btn_view_more" href="{{ route('article.detail', $article->url) }}">Baca Selengkapnya</a>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</section>
@endsection