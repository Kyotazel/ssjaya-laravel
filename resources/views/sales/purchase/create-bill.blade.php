@extends('layouts.sales')
@section('title', __('Tambahkan Nota'))

@section('style')
    <style>
    </style>
@endsection

@section('content')
    <form class="form_input">
        @csrf
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <div class="card-title mb-0 flex-grow-1">
                    <h3 class="mb-0 text-dark">Data Produk</h3>
                </div>
                <a class="btn btn-lg btn-outline-primary rounded-pill btn-label mr-2"
                    href="{{ route('sales.purchase.index') }}">
                    Batalkan</a>
                <button type="button" class="btn btn-lg btn-primary rounded-pill btn-label btn_submit"><i
                        class="fa fa-save"></i>
                    Simpan</button>
            </div>
        </div>

        @php
            $indexProduct = 1;
        @endphp

        <div class="data-apotek">
            <div class="card pharmacy_card">
                <div class="card-body">

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="">Nota</label>
                            <input type="file" name="image" class="form-control">
                        </div>
                    </div>

                    <div class="product_item">
                        @foreach ($purchase->products as $purchaseProduct)
                            <div class="row">
                                <div class="col-md-8 product_data" data-id="{{ $indexProduct }}">
                                    <div class="form-group">
                                        <label for="products[{{ $indexProduct }}][product_id]">Produk</label>
                                        <select name="products[{{ $indexProduct }}][product_id]"
                                            id="products[{{ $indexProduct }}][product_id]" class="form-control">
                                            <option value="">Pilih Produk</option>
                                            @foreach ($products as $product)
                                                <option {{ $purchaseProduct->product_id == $product->id ? 'selected' : '' }}
                                                    value="{{ $product->id }}">{{ $product->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 product_data" data-id="{{ $indexProduct }}">
                                    <div class="form-group">
                                        <label for="products[{{ $indexProduct }}][stock]">Jumlah</label>
                                        <input type="number" class="form-control" value="{{ $purchaseProduct->stock }}"
                                            name="products[{{ $indexProduct }}][stock]"
                                            id="products[{{ $indexProduct }}][stock]">
                                    </div>
                                </div>
                                <div class="col-md-1 product_data" data-id="{{ $indexProduct }}">
                                    <button class="btn btn-lg btn-outline-danger rounded-pill btn-label btn_delete_product"
                                        data-id="{{ $indexProduct++ }}"><i class="fa fa-trash"></i>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('script')
    <script>
        let response_data, selected_product_id, selectedProductId;

        let id = {{ $id }};

        let products = @json($products);

        let product_index = {{ $indexProduct }};

        function appendProduct(index) {
            let html = `
                        <div class="row">
                            <div class="col-md-6 product_data" data-id="${product_index}">
                                <div class="form-group">
                                    <label for="products[${product_index}][product_id]">Produk</label>
                                    <select name="products[${product_index}][product_id]" id="products[${product_index}][product_id]" class="form-control">
                                        <option value="">Pilih Produk</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 product_data" data-id="${product_index}">
                                <div class="form-group">
                                    <label for="products[${product_index}][stock]">Jumlah</label>
                                    <input type="number" class="form-control" name="products[${product_index}][stock]" id="products[${product_index}][stock]">
                                </div>
                            </div>
                            <div class="col-md-3 product_data" data-id="${product_index}">
                                <div class="form-group">
                                    <label for="products[${product_index}][price]">Harga Satuan</label>
                                    <input type="number" class="form-control" name="products[${product_index}][price]" id="products[${product_index}][price]">
                                </div>
                            </div>
                            <div class="col-md-1 product_data" data-id="${product_index}">
                                <button class="btn btn-lg btn-outline-danger rounded-pill btn-label btn_delete_product" data-id="${product_index}"><i class="fa fa-trash"></i>
                            </div>
                        </div>`;

            $(`.product_item`).append(html);
            let selectElem = $(`select[name="products[${product_index}][product_id]"]`);
            selectElem.empty();
            selectElem.append('<option value="">Pilih Produk</option>');
            products.forEach(product => {
                selectElem.append(
                    `<option value="${product.id}">${product.nama}</option>`
                )
            })

            product_index++;
        }

        $("#pharmacy_id").select2();

        $(document).on('click', '.btn_add_product', function(e) {
            e.preventDefault();
            let id = $(this).data('id');
            appendProduct(id);
        })

        $(document).on('click', '.btn_delete_product', function(e) {
            e.preventDefault();
            $(this).parent().parent().remove();
        })

        function onProductSelectChange(element) {
            selectedProductId = $(element).val();
            id = $(element).closest('.pharmacy_card').data('id');
            $(`.product_data[data-parent=${id}]`).remove();

        }


        $(document).on('change', '[name=sales_id]', function(e) {
            let id = $(this).val();
            $.ajax({
                url: `{{ route('pharmacy', ':id') }}`.replace(':id', id),
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    Swal.showLoading();

                    let options = [{
                        id: '',
                        text: 'Pilih Apotek'
                    }]

                    data.forEach(function(item) {
                        options.push({
                            id: item.id_apotek,
                            text: item.nama_apotek,
                        })
                    })

                    $('#pharmacy_id').empty().select2({
                        data: options
                    });

                    Swal.close();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    notif_error(textStatus);
                }
            })

        })

        $(document).on('click', '.btn_submit', function(e) {
            e.preventDefault();
            let formData = new FormData($('.form_input')[0]);

            Swal.fire({
                icon: 'question',
                text: 'apakah anda yakin data yang dimasukkan sudah sesuai?',
                showCancelButton: true,
                confirmButtonClass: 'btn btn-primary rounded-pill w-xs me-2 mb-1 mr-3',
                confirmButtonText: "Ya , Simpan",
                cancelButtonText: "Batal",
                cancelButtonClass: 'btn btn-danger rounded-pill w-xs mb-1',
                closeOnConfirm: true,
                closeOnCancel: true,
                buttonsStyling: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `{{ route('sales.purchase.bill.store', ':id') }}`.replace(':id', id),
                        type: "POST",
                        data: formData,
                        contentType: false,
                        processData: false,
                        dataType: "JSON",
                        success: function(data) {
                            window.location.href = `{{ route('sales.purchase.bill', ':id') }}`
                                .replace(':id', '{{ $purchase->id }}');
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            notif_error("Mohon Lengkapi Data");
                        }
                    })
                }
            });
        })
    </script>
@endsection
