@extends('layouts.admin')
@section('title', __('Detail Setoran Barang'))

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
    </style>
@endsection

@section('content')
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <div class="card-title mb-0 flex-grow-1">
                <div class="btn btn-primary">
                    <h4 class="mb-0 text-light">Laporan Setoran Barang</h4>
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
                                Kode
                            </h5>
                            <p>
                                {{ $depositReport->code }}
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
                                Nama Sales
                            </h5>
                            <p>
                                {{ $depositReport->sales->nama }}
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
                                Total Apotek
                            </h5>
                            <p>
                                {{ $depositReport->pharmacies_count }}
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
                                Status
                            </h5>
                            <p>
                                {{ $depositReport->status }}
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
                                Created At
                            </h5>
                            <p>
                                {{ $depositReport->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row m-3 mt-5">
                <div class="col-md-12">
                    <h3 class="text-dark">Ringkasan Produk</h3>
                </div>
                <div class="col-md-6">
                    <div class="card p-3">
                        <div class="row">
                            <div class="col-md-2">
                                <svg width="70" height="71" class="ml-4" viewBox="0 0 60 61" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <rect width="60" height="60.0027" rx="30" fill="#28B7EB" />
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M30.0003 28.7949C31.9995 28.7949 33.6203 27.1742 33.6203 25.1749C33.6203 23.1757 31.9995 21.5549 30.0003 21.5549C28.001 21.5549 26.3803 23.1757 26.3803 25.1749C26.3803 27.1742 28.001 28.7949 30.0003 28.7949ZM30.0003 38.4483C33.9988 38.4483 37.2403 36.8275 37.2403 34.8283C37.2403 32.829 33.9988 31.2083 30.0003 31.2083C26.0017 31.2083 22.7603 32.829 22.7603 34.8283C22.7603 36.8275 26.0017 38.4483 30.0003 38.4483ZM23.2208 31.2312C20.2407 31.415 17.9336 32.6853 17.9336 34.2251C17.9336 35.4944 19.5016 36.5807 21.7221 37.026C21.2259 36.3541 20.9503 35.6106 20.9503 34.8284C20.9503 33.4487 21.8079 32.1891 23.2208 31.2312ZM39.0503 34.8284C39.0503 35.6106 38.7747 36.3541 38.2785 37.026C40.499 36.5807 42.067 35.4944 42.067 34.2251C42.067 32.6853 39.7599 31.415 36.7798 31.2312C38.1927 32.1891 39.0503 33.4487 39.0503 34.8284ZM34.487 28.2343C35.0822 27.3631 35.4302 26.3097 35.4302 25.1749C35.4302 24.7984 35.3919 24.4308 35.3189 24.0758C35.5448 24.0059 35.7848 23.9683 36.0336 23.9683C37.3664 23.9683 38.4469 25.0488 38.4469 26.3816C38.4469 27.7144 37.3664 28.7949 36.0336 28.7949C35.4451 28.7949 34.9058 28.5843 34.487 28.2343ZM23.9669 23.9683C24.2157 23.9683 24.4557 24.0059 24.6816 24.0758C24.6086 24.4308 24.5703 24.7984 24.5703 25.1749C24.5703 26.3097 24.9183 27.3631 25.5135 28.2343C25.0947 28.5843 24.5554 28.7949 23.9669 28.7949C22.6341 28.7949 21.5536 27.7144 21.5536 26.3816C21.5536 25.0488 22.6341 23.9683 23.9669 23.9683Z"
                                        fill="white" />
                                </svg>
                            </div>
                            <div class="col-md-10">
                                <h4>Jumlah Produk</h4>
                                <p>{{ $depositReport->pharmacies_sum }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card p-3">
                        <div class="row">
                            <div class="col-md-2">
                                <svg width="71" height="71" class="ml-4" viewBox="0 0 61 61" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <rect x="0.5" width="60" height="60.0027" rx="30"
                                        fill="#FF9500" />
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M28.0867 28.7945C30.7528 28.7945 32.9141 26.6332 32.9141 23.9671C32.9141 21.301 30.7528 19.1396 28.0867 19.1396C25.4205 19.1396 23.2592 21.301 23.2592 23.9671C23.2592 26.6332 25.4205 28.7945 28.0867 28.7945ZM28.0867 40.863C32.7524 40.863 36.5346 38.7017 36.5346 36.0356C36.5346 33.3695 32.7524 31.2082 28.0867 31.2082C23.421 31.2082 19.6387 33.3695 19.6387 36.0356C19.6387 38.7017 23.421 40.863 28.0867 40.863ZM32.8675 28.5718C34.0173 27.3782 34.7244 25.7553 34.7244 23.9671C34.7244 23.1168 34.5645 22.3038 34.2731 21.5566C36.2021 21.6364 37.7414 23.2254 37.7414 25.174C37.7414 27.1736 36.1205 28.7946 34.1209 28.7946C33.6803 28.7946 33.2581 28.7159 32.8675 28.5718ZM38.3448 36.0355C38.3448 36.6857 38.2003 37.3142 37.9309 37.908C39.9904 37.2697 41.362 36.1292 41.362 34.8286C41.362 33.0108 38.6828 31.506 35.1911 31.2473C37.1349 32.4552 38.3448 34.1539 38.3448 36.0355Z"
                                        fill="white" />
                                </svg>

                            </div>
                            <div class="col-md-10">
                                <h4>Harga Produk</h4>
                                <p>Rp. {{ number_format($depositReport->total_price) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mx-5">
            <div class="col-md-12">
                <table class="table table-bordered">
                    <thead>
                        <tr class="table-active">
                            <th>Product</th>
                            <th>Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($productResumes as $product)
                            <tr>
                                <td>{{ $product['product_name'] }}</td>
                                <td>{{ $product['stock'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row">
                @foreach ($depositReport->pharmacies as $pharmacy)
                    <div class="col-md-12 mt-4">
                        <h4>{{ $pharmacy->pharmacy->nama_apotek }}</h4>
                        <table class="table table-bordered mt-2">
                            <thead>
                                <tr class="table-active">
                                    <th>Product</th>
                                    <th>Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pharmacy->products as $product)
                                    <tr>
                                        <td>{{ $product->pharmacyProduct->product->nama }}</td>
                                        <td>{{ $product->stock }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
