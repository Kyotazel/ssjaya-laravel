@extends('layouts.sales')
@section('title', __('Tambahkan Nota'))

@section('style')
    <style>
    </style>
@endsection

@section('content')
    <form class="form_input">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <div class="card-title mb-0 flex-grow-1">
                    <h3 class="mb-0 text-dark">Tambah Nota</h3>
                </div>
                <a class="btn btn-lg btn-outline-primary rounded-pill btn-label mr-2"
                    href="{{ route('admin.purchase.index') }}">
                    Batalkan</a>
                <button type="button" class="btn btn-lg btn-primary rounded-pill btn-label btn_submit"><i
                        class="fa fa-save"></i>
                    Simpan</button>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="pharmacy_id">Apotek</label>
                            <select name="pharmacy_id" id="pharmacy_id" class="form-control">
                                <option value="">Pilih Apotek</option>
                                @foreach ($pharmacies as $pharmacy)
                                    <option value="{{ $pharmacy->id_apotek }}">{{ $pharmacy->nama_apotek }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="yellow_image">Nota Setor / Kuning</label>
                            <input type="file" name="yellow_image" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex align-items-center">
                <div class="card-title mb-0 flex-grow-1">
                    <h3 class="mb-0 text-dark">Data Produk</h3>
                </div>
            </div>
        </div>

        @php
            $indexProduct = 1;
        @endphp

        <div class="data-apotek">
            <div class="card pharmacy_card">
                <div class="card-body">
                    <div class="product_item">
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-md btn-outline-primary btn-block rounded-pill btn_add_product">
                                <i class="ri-add-circle-line label-icon"></i> Tambah
                                Produk
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('script')
    <script>
        let response_data, selected_product_id, selectedProductId;

        let products = @json($products);

        let product_index = {{ $indexProduct + 1 }};

        function appendProduct(index) {
            let html = `
                        <div class="row">
                            <div class="col-md-8 product_data" data-id="${product_index}">
                                <div class="form-group">
                                    <label for="products[${product_index}][product_id]">Produk</label>
                                    <select name="products[${product_index}][product_id]" id="products[${product_index}][product_id]" class="form-control">
                                        <option value="">Pilih Produk</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 product_data" data-id="${product_index}">
                                <div class="form-group">
                                    <label for="products[${product_index}][stock]">Jumlah</label>
                                    <input type="number" class="form-control" name="products[${product_index}][stock]" id="products[${product_index}][stock]">
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
                        url: `{{ route('sales.purchase.store') }}`,
                        type: "POST",
                        data: formData,
                        contentType: false,
                        processData: false,
                        dataType: "JSON",
                        success: function(data) {
                            window.location.href = `{{ route('sales.purchase.index') }}`;
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
