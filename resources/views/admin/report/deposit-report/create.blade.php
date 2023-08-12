@extends('layouts.admin')
@section('title', __('Tambahkan Setoran Barang'))

@section('style')
    <style>
    </style>
@endsection

@section('content')
    <form class="form_input">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <div class="card-title mb-0 flex-grow-1">
                    <h3 class="mb-0 text-dark">Tambah Setoran Barang</h3>
                </div>
                <a class="btn btn-lg btn-outline-primary rounded-pill btn-label mr-2"
                    href="{{ route('admin.deposit-report.index') }}">
                    Batal</a>
                <button type="button" class="btn btn-lg btn-primary rounded-pill btn-label btn_submit"><i
                        class="fa fa-save"></i>
                    Simpan</button>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="sales_id">Sales</label>
                            <select name="sales_id" id="sales_id" class="form-control">
                                <option value="">Pilih Sales</option>
                                @foreach ($saless as $sales)
                                    <option value="{{ $sales->id }}">{{ $sales->nama }}</option>
                                @endforeach
                            </select>
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
            </div>
        </div>

        <div class="data-apotek">

        </div>
    </form>
@endsection

@section('script')
    <script>
        let response_data, selected_product_id, selectedProductId;

        let index = 0;

        function appendPharmacy(data = null) {
            let html = `<div class="card pharmacy_card" data-id="${index}">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-11">
                                        <div class="form-group">
                                            <label for="pharmacies[${index}][pharmacy_id]">Apotek</label>
                                            <select name="pharmacies[${index}][pharmacy_id]" id="pharmacies[${index}][pharmacy_id]" onchange="onProductSelectChange(this)" class="form-control select2Pharmacy">
                                                <option value="${data?.id_apotek}">${data?.nama_apotek}</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="pharmacies[${index}][image]">Nota</label>
                                            <input type="file" name="pharmacies[${index}][image]" id="pharmacies[${index}][image]" class="form-control"/>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <button class="btn btn-lg btn-danger rounded-pill btn-label btn_delete_pharmacy" data-id=${index}><i class="fa fa-trash"></i>
                                    </div>
                                </div>
                                <div class="row product_item_${index}">
                                </div>
                            </div>
                        </div>`
            $('.data-apotek').append(html);
            $('.select2Pharmacy').select2();

            data.products.forEach(function(product) {
                appendProduct(index, product);
            })

            index++;
        }

        let product_index = 0;

        function appendProduct(index, data = null) {
            let html = `
                    <div class="col-md-5 product_data" data-parent="${index}" data-id="${product_index}">
                        <div class="form-group">
                            <label for="pharmacies[${index}][products][${product_index}][product_id]">Produk</label>
                            <select name="pharmacies[${index}][products][${product_index}][product_id]" id="pharmacies[${index}][products][${product_index}][product_id]" class="form-control">
                                <option value="${data?.id}">${data?.product?.nama}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 product_data" data-parent="${index}" data-id="${product_index}">
                        <div class="form-group">
                            <label for="pharmacies[${index}][products][${product_index}][price]">Total Setoran</label>
                            <input type="number" value="${data?.price_stock}" class="form-control product-price" data-id=${product_index} name="pharmacies[${index}][products][${product_index}][price]" id="pharmacies[${index}][products][${product_index}][price]">
                        </div>
                    </div>
                    <div class="col-md-3 product_data" data-parent="${index}" data-id="${product_index}">
                        <div class="form-group">
                            <label for="pharmacies[${index}][products][${product_index}][stock]">Jumlah</label>
                            <input type="number" value="${data?.stock}" class="form-control product-stock" data-prev-price=${data?.price_stock} data-prev-stock=${data?.stock} data-id=${product_index} name="pharmacies[${index}][products][${product_index}][stock]" id="pharmacies[${index}][products][${product_index}][stock]">
                        </div>
                    </div>
                    <div class="col-md-1 product_data" data-parent="${index}" data-id="${product_index}">
                        <button class="btn btn-lg btn-outline-danger rounded-pill btn-label btn_delete_product" data-id="${product_index}"><i class="fa fa-trash"></i>
                    </div>`;

            $(`.product_item_${index}`).append(html);

            product_index++;
        }

        $(document).on('keyup', '.product-stock', function(e) {
            e.preventDefault();
            thisIndex = $(this).data('id');
            prevStock = $(this).data('prev-stock')
            prevPrice = $(this).data('prev-price')
            stock = $(this).val();
            if (stock > prevStock) {
                $(this).val(prevStock);
                stock = prevStock
            }
            price = $(`.product-price[data-id=${thisIndex}]`).val()
            pricePerItem = prevPrice / prevStock;
            priceNow = pricePerItem * stock;
            $(`.product-price[data-id=${thisIndex}]`).val(priceNow);
        })

        $(document).on('click', '.btn_delete_pharmacy', function(e) {
            e.preventDefault();
            let id = $(this).data('id');
            $(`.pharmacy_card[data-id=${id}]`).remove();
        })

        $(document).on('click', '.btn_delete_product', function(e) {
            e.preventDefault();
            let id = $(this).data('id');
            $(`.product_data[data-id=${id}]`).remove();
        })

        $(document).on('change', '[name=sales_id]', function(e) {
            let id = $(this).val();
            if (id == '') {
                return;
            }
            $('.pharmacy_card').remove();
            $.ajax({
                url: `{{ route('pharmacy.report', ':id') }}`.replace(':id', id),
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    Swal.showLoading()
                    pharmacies = data;

                    newPharmacies = [];
                    pharmacies.forEach(function(pharmacy) {
                        const pharmacyId = pharmacy.pharmacy_id;
                        if (!newPharmacies[pharmacyId]) {
                            newPharmacies[pharmacyId] = {
                                id_apotek: pharmacy.pharmacy.id_apotek,
                                nama_apotek: pharmacy.pharmacy.nama_apotek,
                                products: []
                            };
                        }
                        newPharmacies[pharmacyId].products.push({
                            id: pharmacy.id,
                            product_id: pharmacy.product_id,
                            stock: pharmacy.stock,
                            price_stock: pharmacy.price_stock,
                            product: {
                                id: pharmacy.product.id,
                                nama: pharmacy.product.nama
                            }
                        });
                    })

                    newPharmacies.forEach(function(pharmacy) {
                        appendPharmacy(pharmacy);
                    })

                    Swal.close()

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    notif_error(jqXHR.responseJSON.message);
                }
            })

        })

        $(document).on('click', '.btn_submit', function(e) {
            e.preventDefault();
            let formData = new FormData($('.form_input')[0]);
            formData.append('_token', '{{ csrf_token() }}')

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
                        url: `{{ route('admin.deposit-report.store') }}`,
                        type: "POST",
                        data: formData,
                        contentType: false,
                        processData: false,
                        dataType: "JSON",
                        success: function(data) {
                            window.location.href = `{{ route('admin.deposit-report.index') }}`;
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
