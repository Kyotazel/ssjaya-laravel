@extends('layouts.landing')
@section('title', __($article->judul))

@section('style')
<style>
    .article-content {
        text-align: justify;
    }
    .article-content p {
        margin-bottom: 1rem;
        text-indent: 40px;
        margin-top: 0.5rem;
    }
    .article-content ul, .article-content ol {
        margin-bottom: 1rem;
        margin-top: 0.5rem;
        margin-left: 3rem;
    }
    .article-content ul {
        list-style: disc;
    }
    .article-content ol {
        list-style-type: decimal;
    }
</style>
@endsection

@section('content')
<div class="container" style="margin-top: 160px; margin-bottom: 40px; background-color: white; padding: 40px">
    <h2>{{ $article->judul}}</h2>
    <br>
    <img src="{{ $article->image_url }}" alt="" style="width: 100%;">
    <div class="row mt-3">
        <div class="col-md-12 text-secondary">
            <div class="row">
                <div class="col-md-3">
                    <i class="fa fa-clock text-success"></i> {{ date('d M Y', strtotime($article->tgl)) }}
                </div>
                <div class="col-md-3">
                    <i class="fa fa-bookmark text-success"></i> {{ $article->category->name}}
                </div>
            </div>
        </div>
        <div class="col-md-12 mt-5">
            <div class="article-content container">
                {!! $article->konten !!}
            </div>
        </div>
    </div>
</div>
@endsection