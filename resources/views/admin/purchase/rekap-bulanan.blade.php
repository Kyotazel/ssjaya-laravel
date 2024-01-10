@extends('layouts.admin')
@section('title', __('Rekap Nota Bulanan'))

@section('content')
    <div class="card">
        <div class="card-header">
            <h4>Filter</h4>
        </div>
        <div class="card-body">
            <form id="filter_form">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="start_date">Nama Sales : </label>
                            <select name="filter_sales" id="filter_sales" class="select2">
                                <option value="">Pilih Sales</option>
                                @foreach ($saless as $sales)
                                    <option value="{{ $sales->id }}">{{ $sales->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="end_date">Tahun : </label>
                            <select name="year" id="year" class="select2">
                                <option value="">Pilih Tahun</option>
                                @for ($i = $firstYear; $i <= $lastYear; $i++)
                                    <option {{ now()->year == $i ? 'selected' : '' }} value="{{ $i }}">
                                        {{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="start_month">Bulan Awal : </label>
                            <select name="start_month" id="start_month" class="select2">
                                <option value="">Pilih Bulan</option>
                                <option value="1">Januari</option>
                                <option value="2">Februari</option>
                                <option value="3">Maret</option>
                                <option value="4">April</option>
                                <option value="5">Mei</option>
                                <option value="6">Juni</option>
                                <option value="7">Juli</option>
                                <option value="8">Agustus</option>
                                <option value="9">September</option>
                                <option value="10">Oktober</option>
                                <option value="11">November</option>
                                <option value="12">Desember</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="end_month">Bulan Akhir : </label>
                            <select name="end_month" id="end_month" class="select2">
                                <option value="">Pilih Bulan</option>
                                <option value="1">Januari</option>
                                <option value="2">Februari</option>
                                <option value="3">Maret</option>
                                <option value="4">April</option>
                                <option value="5">Mei</option>
                                <option value="6">Juni</option>
                                <option value="7">Juli</option>
                                <option value="8">Agustus</option>
                                <option value="9">September</option>
                                <option value="10">Oktober</option>
                                <option value="11">November</option>
                                <option value="12">Desember</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12 text-right">
                        <button type="submit" id="filter_submit" class="btn btn-primary mr-1"><i class="fa fa-filter"></i>
                            Filter Data</button>
                        {{-- <button type="button" id="btn_export_pdf" class="btn btn-danger mr-1"><i
                                class="fas fa-file-pdf"></i>
                            Export Pdf</button> --}}
                        <button type="button" id="btn_export_excel" class="btn btn-success mr-1"><i
                                class="fas fa-file-excel"></i>
                            Export Excel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <div class="card-title mb-0 flex-grow-1">
                <div class="btn btn-primary">
                    <h6 class="mb-0 text-light">Daftar Rekap Nota Belum Lunas </h6>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="table-data" class="table table-striped table-bordered no-wrap" style="width: 100%">
                    <thead>
                        <tr>
                            <th>Area</th>
                            <th>Nomor Nota</th>
                            <th>Tanggal</th>
                            <th>Apotek</th>
                            <th>Jumlah Barang</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            table = $("#table-data").DataTable({})
        })

        $(document).on('click', '#filter_submit', function(e) {
            e.preventDefault();

            if ($('#filter_sales').val() == '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Sales harus dipilih!',
                })
                return false
            }

            if ($('#year').val() == '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Tahun harus dipilih!',
                })
                return false
            }

            if ($('#start_month').val() == '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Bulan Awal harus dipilih!',
                })
                return false
            }

            if ($('#end_month').val() == '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Bulan Akhir harus dipilih!',
                })
                return false
            }

            table.destroy();
            table = $("#table-data").DataTable({
                processing: true,
                serverSide: true,
                fixedColumns: true,
                scrollX: false,
                ajax: {
                    url: '{{ route('admin.purchase.rekap-bulanan') }}',
                    data: function(query) {
                        query.filter_sales = $('#filter_sales').val();
                        query.year = $('#year').val();
                        query.start_month = $('#start_month').val();
                        query.end_month = $('#end_month').val();
                        return query
                    }
                },
                columns: [{
                        data: 'pharmacy.city.nama',
                    },
                    {
                        data: 'code',
                    },
                    {
                        data: 'date',
                        className: 'text-center'
                    },
                    {
                        data: 'pharmacy.nama_apotek',
                        className: 'text-center'
                    },
                    {
                        data: 'products_sum_stock',
                        className: 'text-center'
                    }
                ],
                order: [
                    [1, 'asc']
                ],
                columnDefs: [{
                    targets: '_all',
                    defaultContent: '-'
                }],
            })
        })

        // $(document).on('click', '#btn_export_pdf', function(e) {
        //     e.preventDefault();

        //     let route = '{{ route('admin.purchase.export-pdf-belum-lunas') }}' + '?start_date=' + $(
        //         '#start_date').val() + '&end_date=' + $('#end_date').val()

        //     window.open(route, '_blank')

        // })

        $(document).on('click', '#btn_export_excel', function(e) {
            e.preventDefault();

            if ($('#filter_sales').val() == '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Sales harus dipilih!',
                })
                return false
            }

            if ($('#year').val() == '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Tahun harus dipilih!',
                })
                return false
            }

            if ($('#start_month').val() == '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Bulan Awal harus dipilih!',
                })
                return false
            }

            if ($('#end_month').val() == '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Bulan Akhir harus dipilih!',
                })
                return false
            }

            let route = '{{ route('admin.purchase.export-excel-bulanan') }}' + '?filter_sales=' + $(
                '#filter_sales').val() + '&year=' + $('#year').val() + '&start_month=' + $(
                '#start_month').val() + '&end_month=' + $('#end_month').val()

            window.open(route, '_blank')

        })
    </script>
@endsection
