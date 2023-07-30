@extends('layouts.sales')
@section('title', __('Setoran Barang'))

@section('content')
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <div class="card-title mb-0 flex-grow-1">
                <div class="btn btn-primary">
                    <h6 class="mb-0 text-light">Daftar Barang Keluar</h6>
                </div>
            </div>
            <a class="btn btn-success rounded-pill btn-label" href="{{ route('sales.deposit-report.create') }}"><i
                    class="ri-add-circle-line label-icon"></i> Tambah Data</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="table-data" class="table table-striped table-bordered no-wrap" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Created At</th>
                            <th>Jumlah Apotek</th>
                            <th>Jumlah Produk</th>
                            <th>Status</th>
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
            let id_use;
            table = $("#table-data").DataTable({
                processing: true,
                serverSide: true,
                fixedColumns: true,
                scrollX: false,
                ajax: {
                    url: `{{ route('sales.deposit-report.index') }}`,
                },
                columns: [{
                        data: 'id',
                        searchable: false,
                        render: function(data, type, row, meta) {
                            return row.DT_RowIndex
                        }
                    },
                    {
                        data: 'code',
                    },
                    {
                        data: 'created_at',
                    },
                    {
                        data: 'pharmacies_count',
                        className: 'text-center'
                    },
                    {
                        data: 'product_sum',
                        searchable: false,
                        orderable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'status',
                        className: 'text-center',
                        render: function(data, type, row, meta) {
                            if (row.status == 'PENDING') {
                                return `<button class="btn btn-sm btn_update_status btn-warning" data-id="${row.id}">Pending</button>`
                            } else if (row.status == 'APPROVED') {
                                return `<button class="btn btn-sm btn-success">Approved</button>`
                            } else {
                                return `<button class="btn btn-sm btn-danger">Rejected</button>`
                            }
                        }
                    },
                    {
                        data: 'action',
                        className: 'text-center',
                        searchable: false,
                        orderable: false,
                    }
                ],
                columnDefs: [{
                    targets: '_all',
                    defaultContent: '-'
                }],
            })
        })
    </script>
@endsection
