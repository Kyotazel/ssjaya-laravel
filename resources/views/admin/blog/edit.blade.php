@extends('layouts.admin')
@section('title', __('Tambahkan Artikel'))

@section('content')
    <div class="card">
        <div class="card-body">
            <form class="form_input" method="POST" action="{{ route('admin.blog.update', $blog->id) }}"
                enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="judul">Judul</label>
                            <input type="text" name="judul"
                                class="form-control {{ $errors->has('judul') ? 'is-invalid' : '' }}"
                                placeholder="Masukkan Judul..." value="{{ $blog->judul }}">
                            <div class="invalid-feedback">{{ $errors->has('judul') ? $errors->first('judul') : '' }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="id_category">Kategori</label>
                            <select name="id_category" id="id_category"
                                class="form-control {{ $errors->has('id_category') ? 'is-invalid' : '' }}">
                                <option value="">Pilih Kategori</option>
                                @foreach ($categories as $category)
                                    <option {{ $blog->id_category == $category->id ? 'selected' : '' }}
                                        value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">
                                {{ $errors->has('id_category') ? $errors->first('id_category') : '' }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="id_produk">Produk</label>
                            <select name="id_produk" id="id_produk"
                                class="form-control {{ $errors->has('id_produk') ? 'is-invalid' : '' }}">
                                <option value="">Pilih Produk</option>
                                @foreach ($products as $product)
                                    <option {{ $blog->id_produk == $product->id ? 'selected' : '' }}
                                        value="{{ $product->id }}">{{ $product->nama }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">
                                {{ $errors->has('id_produk') ? $errors->first('id_produk') : '' }}</div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="image">Gambar</label>
                            <img src="{{ $blog->image_url }}" class="for_update" alt=""
                                style="display: block; height: 200px">
                            <p class="text-danger for_update" style="display: block;">*) Apabila tidak diubah maka jangan
                                upload foto</p>
                            <input type="file" name="image" id="image"
                                class="form-control {{ $errors->has('image') ? 'is-invalid' : '' }}">
                            <div class="invalid-feedback">{{ $errors->has('image') ? $errors->first('image') : '' }}</div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="konten">Konten</label>
                            <textarea name="konten" id="konten" class="form-control {{ $errors->has('konten') ? 'is-invalid' : '' }}"
                                style="height: 400px;">{{ $blog->konten }}</textarea>
                            <div class="invalid-feedback">{{ $errors->has('konten') ? $errors->first('konten') : '' }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary btn_save" style="display:block; width: 100%">
                            <span class="spinner-grow spinner-grow-sm d-none" role="status"></span>
                            Submit
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script>
        ClassicEditor
            .create(document.querySelector('#konten'))
            .then(newEditor => {
                editor = newEditor;
            })
            .catch(error => {
                console.error(error);
            });
    </script>
@endsection
