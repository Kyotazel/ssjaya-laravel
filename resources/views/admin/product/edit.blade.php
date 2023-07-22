@extends('layouts.admin')
@section('title', __('Edit Produk'))

@section('content')
    <div class="card">
        <div class="card-body">
            <form class="form_input" method="POST" action="{{ route('admin.product.update', $product->id) }}"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="nama">Nama</label>
                            <input type="text" name="nama"
                                class="form-control {{ $errors->has('nama') ? 'is-invalid' : '' }}"
                                placeholder="Masukkan Nama..." value="{{ $product->nama }}">
                            <div class="invalid-feedback">{{ $errors->has('nama') ? $errors->first('nama') : '' }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="harga">Harga</label>
                            <input type="number" name="harga"
                                class="form-control {{ $errors->has('harga') ? 'is-invalid' : '' }}" placeholder="20000..."
                                value="{{ $product->harga }}">
                            <div class="invalid-feedback">{{ $errors->has('harga') ? $errors->first('harga') : '' }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="aturan">Aturan Pakai</label>
                            <input type="text" name="aturan"
                                class="form-control {{ $errors->has('aturan') ? 'is-invalid' : '' }}"
                                placeholder="3 x sehari..." value="{{ $product->aturan }}">
                            <div class="invalid-feedback">{{ $errors->has('aturan') ? $errors->first('aturan') : '' }}</div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="image">Gambar Produk</label>
                            <img src="{{ $product->image_url }}" class="for_update" alt=""
                                style="display: block; height: 200px">
                            <p class="text-danger for_update" style="display: block;">*) Apabila tidak diubah maka jangan
                                upload foto</p>
                            <input type="file" name="image"
                                class="form-control {{ $errors->has('image') ? 'is-invalid' : '' }}">
                            <div class="invalid-feedback">{{ $errors->has('image') ? $errors->first('image') : '' }}</div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="merk">Merk Produk</label>
                            <img src="{{ $product->merk_image_url }}" class="for_update" alt=""
                                style="display: block; height: 200px">
                            <p class="text-danger for_update" style="display: block;">*) Apabila tidak diubah maka jangan
                                upload foto</p>
                            <input type="file" name="merk"
                                class="form-control {{ $errors->has('merk') ? 'is-invalid' : '' }}">
                            <div class="invalid-feedback">{{ $errors->has('merk') ? $errors->first('merk') : '' }}</div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="deskripsi">Deskripsi</label>
                            <textarea name="deskripsi" id="deskripsi" class="form-control {{ $errors->has('deskripsi') ? 'is-invalid' : '' }}"
                                style="height: 400px;">{{ $product->deskripsi }}</textarea>
                            <div class="invalid-feedback">
                                {{ $errors->has('deskripsi') ? $errors->first('deskripsi') : '' }}</div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="manfaat">Khasiat / Manfaat</label>
                            <textarea name="manfaat" id="manfaat" class="form-control {{ $errors->has('manfaat') ? 'is-invalid' : '' }}"
                                style="height: 400px;">{{ $product->manfaat }}</textarea>
                            <div class="invalid-feedback">{{ $errors->has('manfaat') ? $errors->first('manfaat') : '' }}
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
            .create(document.querySelector('#deskripsi'))
            .then(newEditor => {
                ck_deskripsi = newEditor;
            })
            .catch(error => {
                console.error(error);
            });

        ClassicEditor
            .create(document.querySelector('#manfaat'))
            .then(newEditor => {
                ck_manfaat = newEditor;
            })
            .catch(error => {
                console.error(error);
            });
    </script>
@endsection
