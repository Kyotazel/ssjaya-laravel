@extends('layouts.admin')
@section('title', __('Laporan Produk Sales'))

@section('content')
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <div class="card-title mb-0 flex-grow-1">
                <div class="btn btn-primary">
                    <h6 class="mb-0 text-light">Daftar Produk Sales</h6>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="table-data" class="table table-striped table-bordered no-wrap" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Sales</th>
                            <th>Produk Tersisa</th>
                            <th>Produk Terjual</th>
                            <th class="text-center">Action</th>
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
            table = $("#table-data").DataTable({
                processing: true,
                serverSide: true,
                fixedColumns: true,
                scrollX: false,
                ajax: {
                    url: `{{ route('admin.sales-report.index') }}`
                },
                columns: [{
                        data: 'id',
                        searchable: false,
                        render: function(data, type, row, meta) {
                            return row.DT_RowIndex
                        }
                    },
                    {
                        data: 'nama'
                    },
                    {
                        searchable: false,
                        render: function(data, type, row, meta) {
                            return `${row.products_sum_stock} (${row.products_price_stock})`
                        }
                    },
                    {
                        searchable: false,
                        render: function(data, type, row, meta) {
                            return `${row.products_sum_stock_sold} (${row.products_price_stock_sold})`
                        }
                    },
                    {
                        data: 'action'
                    },
                ],
                columnDefs: [{
                    targets: '_all',
                    defaultContent: '-'
                }],
            })
        })
    </script>
@endsection
