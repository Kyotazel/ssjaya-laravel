@extends('layouts.admin')
@section('title', __('Detail Artikel'))

@section('content')
    <div class="card">
        <div class="card-body text-dark">
            <h2>{{ $blog->judul }}</h2>
            <br>
            <img src="{{ $blog->image_url }}" alt="" style="width: 100%;">
            <div class="row mt-3">
                <div class="col-md-12 text-secondary">
                    <div class="row">
                        <div class="col-md-4">
                            <i class="fa fa-clock text-success"></i>
                            {{ date('d M Y', strtotime($blog->tgl)) }}
                        </div>
                        <div class="col-md-4">
                            <i class="fa fa-bookmark text-success"></i> {{ $blog->category->name }}
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mt-5">
                    {!! $blog->konten !!}
                </div>
            </div>
        </div>
    </div>
@endsection
