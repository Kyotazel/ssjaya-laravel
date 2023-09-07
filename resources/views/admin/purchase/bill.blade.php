@extends('layouts.admin')
@section('title', __('Nota Titip Tagihan'))

@section('style')
    <style>
        .list {
            border-bottom: 1px solid #E2E1E1;
            padding-top: 16px;
            padding-bottom: -4px;
            align-items: center;
            min-height: 62px;
            margin: 0 10px;
        }

        .item-data-total {
            background-color: white;
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 20px 15px;
            box-shadow: 0px 9px 15px 0px rgba(116, 107, 107, 0.08);

        }

        .item-data-total h2 {
            font-size: 20px;
            font-weight: 500;
            line-height: 30px;
            margin: 0;
        }

        .item-data-total p {
            font-weight: 400;
            font-size: 14px;
            line-height: 18px;
            color: #686f82;
            margin: 0;
        }

        .item-data-total span {
            color: #686f82;
            font-size: 14px;
            font-weight: 500;
            line-height: normal;
        }
    </style>
@endsection

@section('content')
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <div class="card-title mb-0 flex-grow-1">
                <div class="btn btn-primary">
                    <h4 class="mb-0 text-light">Nota Titip Tagihan</h4>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col">
                    <div class="row list">
                        <div class="col-auto p-0">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <rect x="2" y="3" width="20" height="18" rx="4.8"
                                    stroke="#181B32" stroke-width="1.8" />
                                <path d="M2 7L9.00146 12.6012C10.7545 14.0036 13.2455 14.0036 14.9985 12.6012L22 7"
                                    stroke="#181B32" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                        <div class="col">
                            <h5>
                                Nomor Nota
                            </h5>
                            <p>
                                {{ $purchase->code }}
                            </p>
                        </div>
                    </div>
                    <div class="row list">
                        <div class="col-auto p-0">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <ellipse cx="12" cy="17.5" rx="7" ry="3.5" stroke="#181B32"
                                    stroke-width="1.5" stroke-linejoin="round" />
                                <circle cx="12" cy="7" r="4" stroke="#181B32" stroke-width="1.5"
                                    stroke-linejoin="round" />
                            </svg>
                        </div>
                        <div class="col">
                            <h5>
                                Nama Apotek
                            </h5>
                            <p>
                                {{ $purchase->pharmacy->nama_apotek }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="row list">
                        <div class="col-auto p-0">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M10.2321 4.02857C11.0178 2.65714 12.9822 2.65714 13.7679 4.02857L21.7235 17.9143C22.5092 19.2857 21.527 21 19.9556 21H4.04444C2.47297 21 1.49081 19.2857 2.27654 17.9143L10.2321 4.02857Z"
                                    stroke="#333333" stroke-width="1.5" />
                                <circle cx="12" cy="17" r="1" fill="#333333" />
                                <path d="M12 8V14" stroke="#333333" stroke-width="1.5" stroke-linecap="round" />
                            </svg>
                        </div>
                        <div class="col">
                            <h5>
                                Nama Sales
                            </h5>
                            <p>
                                {{ $purchase->sales->nama }}
                            </p>
                        </div>
                    </div>
                    <div class="row list">
                        <div class="col-auto p-0">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M16 2V5" stroke="#181B32" stroke-width="1.875" stroke-linecap="round" />
                                <path d="M8 2V5" stroke="#181B32" stroke-width="1.875" stroke-linecap="round" />
                                <path
                                    d="M3 8.5C3 5.73858 5.23858 3.5 8 3.5H16C18.7614 3.5 21 5.73858 21 8.5V17C21 19.7614 18.7614 22 16 22H8C5.23858 22 3 19.7614 3 17V8.5Z"
                                    stroke="#181B32" stroke-width="1.875" />
                                <path d="M3 9H21" stroke="#181B32" stroke-width="1.875" stroke-linecap="round" />
                            </svg>
                        </div>
                        <div class="col">
                            <h5>
                                Status
                            </h5>
                            <p>
                                {{ $purchase->status }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex align-items-center">
            <div class="card-title mb-0 flex-grow-1">
                <div class="btn btn-primary">
                    <h4 class="mb-0 text-light">List Nota Barang Keseluruhan</h4>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered mt-2">
                        <thead>
                            <tr class="table-active">
                                <th>Nama Produk</th>
                                <th>Jumlah</th>
                                {{-- <th>Harga Satuan</th>
                                <th>Total</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($purchase->products as $product)
                                <tr>
                                    <td>{{ $product->product->nama }}</td>
                                    <td>{{ $product->stock }}</td>
                                    {{-- <td>Rp. {{ number_format($product->price) }}</td>
                                    <td>Rp. {{ number_format($product->stock * $product->price) }}</td> --}}
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex align-items-center">
            <div class="card-title mb-0 flex-grow-1">
                <div class="btn btn-primary">
                    <h4 class="mb-0 text-light">Nota Titip Tagihan</h4>
                </div>
                <div class="float-right">
                    <a href="{{ route('admin.purchase.bill.create', $purchase->id) }}" class="btn btn-outline-primary"><i
                            class="fa fa-plus"></i> Upload Nota Titip
                        Tagihan</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered mt-2">
                        <thead>
                            <tr class="table-active">
                                <th>Nomor Nota</th>
                                <th>Tanggal Upload</th>
                                <th class="text-center">Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($purchase->bills as $bill)
                                <tr>
                                    <td>{{ $bill->code }}</td>
                                    <td>{{ $bill->created_at->format('d M Y') }}</td>
                                    <td class="text-center"><button class="btn btn-outline-primary detailBill"
                                            data-item="{{ $bill }}">Detail</button></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal zoomIn" id="modal_paper" tabindex="-1" aria-modal="true" role="dialog" data-keyboard="false"
        aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form class="form_input">
                    <div class="modal-header">
                        <h5 class="modal-title" id="label_modal">Grid Modals</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="row list">
                                    <div class="col-auto p-0">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <rect x="2" y="3" width="20" height="18"
                                                rx="4.8" stroke="#181B32" stroke-width="1.8" />
                                            <path
                                                d="M2 7L9.00146 12.6012C10.7545 14.0036 13.2455 14.0036 14.9985 12.6012L22 7"
                                                stroke="#181B32" stroke-width="1.8" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                    </div>
                                    <div class="col">
                                        <h5>
                                            Nomor Nota
                                        </h5>
                                        <p id="bill-code">
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row my-5">
                            <div class="col-md-12">
                                <h3 class="text-center">Nota</h3>
                                <div class="text-center" style="margin: auto">
                                    <img src="" id="imageFile" class="text-center"
                                        style="margin: auto; max-height:400px; max-width:700px">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-bordered mt-2">
                                    <thead>
                                        <tr class="table-active">
                                            <th>Nama Produk</th>
                                            <th>Jumlah</th>
                                            {{-- <th>Harga Satuan</th>
                                            <th>Total</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody class="modalTbody">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        let id = {{ $purchase->id }};

        $(document).on('click', '.detailBill', function(e) {
            e.preventDefault();

            let item = $(this).data('item')

            $('#bill-code').html(item.code);
            $('#imageFile').attr('src', item.image_url);
            $('.modalTbody').empty();
            $.each(item.products, function(index, product) {
                $('.modalTbody').append(`
                <tr>
                <td>${product.product.nama}</td>
                <td>${product.stock}</td>
                </tr>
                `)
            })

            $('#modal_paper').modal('show');

        })
    </script>
@endsection
