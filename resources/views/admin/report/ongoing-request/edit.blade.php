@extends('layouts.admin')
@section('title', __('Perbarui Barang Keluar'))

@section('style')
    <style>
    </style>
@endsection

@php
    $index_counter = 0;
    $index_detail = 0;
@endphp

@section('content')
    <form class="form_input">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <div class="card-title mb-0 flex-grow-1">
                    <h3 class="mb-0 text-dark">Perbarui Barang Keluar</h3>
                </div>
                <a class="btn btn-lg btn-outline-primary rounded-pill btn-label mr-2"
                    href="{{ route('admin.ongoing-request.index') }}">
                    Batalkan</a>
                <button type="button" class="btn btn-lg btn-primary rounded-pill btn-label btn_submit"><i
                        class="fa fa-save"></i>
                    Perbarui</button>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="sales_id">Sales</label>
                            <select name="sales_id" id="sales_id" class="form-control">
                                <option value="">Pilih Sales</option>
                                @foreach ($saless as $sales)
                                    <option {{ $ongoingRequest->sales_id == $sales->id ? 'selected' : '' }}
                                        value="{{ $sales->id }}">{{ $sales->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="request_date">Tanggal Permintaan</label>
                            <input type="date" name="request_date" id="request_date" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex align-items-center">
                <div class="card-title mb-0 flex-grow-1">
                    <h3 class="mb-0 text-dark">Data Apotek</h3>
                </div>
                <button class="btn btn-lg btn-primary rounded-pill btn-label btn_add_apotek">
                    <i class="ri-add-circle-line label-icon"></i> Apotek
                </button>
            </div>
        </div>

        <div class="data-apotek">
            @foreach ($ongoingRequest->pharmacies as $key => $pharmacy)
                <div class="card pharmacy_card" data-id="{{ $index_counter }}">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-11">
                                <div class="form-group">
                                    <label for="pharmacies[{{ $index_counter }}][pharmacy_id]">Apotek</label>
                                    <select name="pharmacies[{{ $index_counter }}][pharmacy_id]"
                                        id="pharmacies[{{ $index_counter }}][pharmacy_id]"
                                        onchange="onProductSelectChange(this)" class="form-control select2pharmacy">
                                        <option value="">Pilih Apotek</option>
                                        @foreach ($pharmacies->where('id_sales', $thisSales->id_sales) as $pharmacy_item)
                                            <option
                                                {{ $pharmacy_item->id_apotek == $pharmacy->pharmacy_id ? 'selected' : '' }}
                                                value="{{ $pharmacy_item->id_apotek }}">{{ $pharmacy_item->nama_apotek }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <button class="btn btn-lg btn-danger rounded-pill btn-label btn_delete_pharmacy"
                                    data-id={{ $index_counter }}><i class="fa fa-trash"></i>
                            </div>
                        </div>
                        <div class="row product_item_{{ $index_counter }}">
                            @foreach ($pharmacy->products as $keyChild => $product)
                                <div class="col-md-5 product_data" data-parent="{{ $index_counter }}"
                                    data-id="{{ $index_detail }}">
                                    <div class="form-group">
                                        <label
                                            for="pharmacies[{{ $index_counter }}][products][{{ $index_detail }}][product_id]">Produk
                                        </label>
                                        <select
                                            name="pharmacies[{{ $index_counter }}][products][{{ $index_detail }}][product_id]"
                                            id="pharmacies[{{ $index_counter }}][products][{{ $index_detail }}][product_id]"
                                            class="form-control">
                                            <option value="">Pilih Produk</option>
                                            @foreach ($products as $product_item)
                                                <option
                                                    {{ $product->pharmacyProduct->product_id == $product_item->id ? 'selected' : '' }}
                                                    value="{{ $product_item->id }}">{{ $product_item->nama }}</option>
                                            @endforeach
                                            {{-- @foreach ($pharmacyProduct->where('pharmacy_id', $pharmacy->pharmacy_id) as $product_item)
                                                <option
                                                    {{ $product_item->id == $product->pharmacy_product_id ? 'selected' : '' }}
                                                    value="{{ $product_item->id }}">{{ $product_item->product->nama }}
                                                </option>
                                            @endforeach --}}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 product_data" data-parent="{{ $index_counter }}"
                                    data-id="{{ $index_detail }}">
                                    <div class="form-group">
                                        <label
                                            for="pharmacies[{{ $index_counter }}][products][{{ $index_detail }}][price]">Harga</label>
                                        <input type="number" class="form-control"
                                            name="pharmacies[{{ $index_counter }}][products][{{ $index_detail }}][price]"
                                            value="{{ $product->price }}"
                                            id="pharmacies[{{ $index_counter }}][products][{{ $index_detail }}][price]">
                                    </div>
                                </div>
                                <div class="col-md-3 product_data" data-parent="{{ $index_counter }}"
                                    data-id="{{ $index_detail }}">
                                    <div class="form-group">
                                        <label
                                            for="pharmacies[{{ $index_counter }}][products][{{ $index_detail }}][stock]">Jumlah</label>
                                        <input type="number" class="form-control"
                                            name="pharmacies[{{ $index_counter }}][products][{{ $index_detail }}][stock]"
                                            value="{{ $product->stock }}"
                                            id="pharmacies[{{ $index_counter }}][products][{{ $index_detail }}][stock]">
                                    </div>
                                </div>
                                <div class="col-md-1 product_data" data-parent="{{ $index_counter }}"
                                    data-id="{{ $index_detail }}">
                                    <button class="btn btn-lg btn-outline-danger rounded-pill btn-label btn_delete_product"
                                        data-id="{{ $index_detail++ }}"><i class="fa fa-trash"></i>
                                </div>
                            @endforeach
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button class="btn btn-md btn-outline-primary btn-block rounded-pill btn_add_product"
                                    data-id="{{ $index_counter++ }}"><i class="ri-add-circle-line label-icon"></i> Tambah
                                    Produk</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </form>
@endsection

@section('script')
    <script>
        let response_data, selected_product_id, selectedProductId;

        $('.select2pharmacy').select2();

        let products = @json($products);

        $.ajax({
            url: `{{ route('pharmacy', ':id') }}`.replace(':id', `{{ $thisSales->id }}`),
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                response_data = data;
            },
            error: function(jqXHR, textStatus, errorThrown) {
                notif_error(textStatus);
            }
        })

        let index = {{ $index_counter }} + 1;

        function appendPharmacy() {
            let html = `<div class="card pharmacy_card" data-id="${index}">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-11">
                                        <div class="form-group">
                                            <label for="pharmacies[${index}][pharmacy_id]">Apotek</label>
                                            <select name="pharmacies[${index}][pharmacy_id]" id="pharmacies[${index}][pharmacy_id]" onchange="onProductSelectChange(this)" class="form-control select2pharmacy">
                                                <option value="">Pilih Apotek</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <button class="btn btn-lg btn-danger rounded-pill btn-label btn_delete_pharmacy" data-id=${index}><i class="fa fa-trash"></i>
                                    </div>
                                </div>
                                <div class="row product_item_${index}">
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <button class="btn btn-md btn-outline-primary btn-block rounded-pill btn_add_product" data-id="${index}"><i
                                                class="ri-add-circle-line label-icon"></i> Tambah Produk</button>
                                    </div>
                                </div>
                            </div>
                        </div>`
            $('.data-apotek').append(html);
            $('.select2pharmacy').select2();
            index++;
        }

        let product_index = {{ $index_detail }} + 1;

        function appendProduct(index) {
            let html = `
                            <div class="col-md-5 product_data" data-parent="${index}" data-id="${product_index}">
                                <div class="form-group">
                                    <label for="pharmacies[${index}][products][${product_index}][product_id]">Produk</label>
                                    <select name="pharmacies[${index}][products][${product_index}][product_id]" id="pharmacies[${index}][products][${product_index}][product_id]" class="form-control">
                                        <option value="">Pilih Produk</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 product_data" data-parent="${index}" data-id="${product_index}">
                                <div class="form-group">
                                    <label for="pharmacies[${index}][products][${product_index}][price]">Harga</label>
                                    <input type="number" class="form-control" name="pharmacies[${index}][products][${product_index}][price]" id="pharmacies[${index}][products][${product_index}][price]">
                                </div>
                            </div>
                            <div class="col-md-3 product_data" data-parent="${index}" data-id="${product_index}">
                                <div class="form-group">
                                    <label for="pharmacies[${index}][products][${product_index}][stock]">Jumlah</label>
                                    <input type="number" class="form-control" name="pharmacies[${index}][products][${product_index}][stock]" id="pharmacies[${index}][products][${product_index}][stock]">
                                </div>
                            </div>
                            <div class="col-md-1 product_data" data-parent="${index}" data-id="${product_index}">
                                <button class="btn btn-lg btn-outline-danger rounded-pill btn-label btn_delete_product" data-id="${product_index}"><i class="fa fa-trash"></i>
                            </div>`;

            $(`.product_item_${index}`).append(html);
            let selectElem = $(`.product_item_${index}`).find(
                `select[name="pharmacies[${index}][products][${product_index}][product_id]"]`);
            selectElem.empty();
            selectElem.append('<option value="">Pilih Produk</option>');
            products.forEach(product => {
                selectElem.append(
                    `<option value="${product.id}">${product.nama}</option>`
                )
            })

            product_index++;
        }

        function fillSelectOptions(index) {
            let selectElem = $(
                `#pharmacies\\[${index}\\]\\[pharmacy_id\\]`
            ); // Pilih elemen <select> berdasarkan index yang diisi dengan escape karakter "\\"
            selectElem.empty(); // Kosongkan elemen <select>

            selectElem.append('<option value="">Pilih Apotek</option>'); // Tambahkan opsi default

            // Tambahkan opsi-opsi dari response_data ke dalam elemen <select>
            if (response_data && response_data.length > 0) {
                response_data.forEach(apotek => {
                    selectElem.append(`<option value="${apotek.id_apotek}">${apotek.nama_apotek}</option>`);
                });
            }
        }

        $(document).on('click', '.btn_delete_pharmacy', function(e) {
            e.preventDefault();
            let id = $(this).data('id');
            $(`.pharmacy_card[data-id=${id}]`).remove();
        })

        $(document).on('click', '.btn_add_apotek', function(e) {
            e.preventDefault();
            appendPharmacy();
            fillSelectOptions(index - 1);
        })

        $(document).on('click', '.btn_add_product', function(e) {
            e.preventDefault();
            let id = $(this).data('id');
            appendProduct(id);
        })

        $(document).on('click', '.btn_delete_product', function(e) {
            e.preventDefault();
            let id = $(this).data('id');
            $(`.product_data[data-id=${id}]`).remove();
        })

        function onProductSelectChange(element) {
            selectedProductId = $(element).val();
            id = $(element).closest('.pharmacy_card').data('id');
            $(`.product_data[data-parent=${id}]`).remove();

            // Lakukan apa pun yang perlu Anda lakukan dengan selectedProductId atau id di sini.
        }


        $(document).on('change', '[name=sales_id]', function(e) {
            let sales_id = $(this).val();
            $('.pharmacy_card').remove();
            $.ajax({
                url: `{{ route('pharmacy', ':id') }}`.replace(':id', sales_id),
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    response_data = data;
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    notif_error(textStatus);
                }
            })

        })

        $(document).on('click', '.btn_submit', function(e) {
            e.preventDefault();
            let formData = new FormData($('.form_input')[0]);
            formData.append('_token', '{{ csrf_token() }}')
            formData.append('_method', 'PUT')

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
                        url: `{{ route('admin.ongoing-request.update', ':id') }}`.replace(':id',
                            `{{ $ongoingRequest->id }}`),
                        type: "POST",
                        data: formData,
                        contentType: false,
                        processData: false,
                        dataType: "JSON",
                        success: function(data) {
                            window.location.href =
                                `{{ route('admin.ongoing-request.index') }}`;
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            notif_error("Mohon Lengkapi Data");
                        }
                    })
                }
            })
        })
    </script>
@endsection
