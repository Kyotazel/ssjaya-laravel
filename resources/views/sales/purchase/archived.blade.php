@extends('layouts.sales')
@section('title', __('Nota Diarsipkan'))

@section('content')
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <div class="card-title mb-0 flex-grow-1">
                <div class="btn btn-primary">
                    <h6 class="mb-0 text-light">Daftar Nota Diarsipkan</h6>
                </div>
            </div>
            <a class="btn btn-success rounded-pill btn-label mr-3" href="{{ route('sales.purchase.index') }}"><i
                    class="fa fa-check"></i> Nota Normal</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="table-data" class="table table-striped table-bordered no-wrap" style="width: 100%">
                    <thead>
                        <tr>
                            <th>Nomor Nota</th>
                            <th>Tanggal Dibuat</th>
                            <th>Apotek</th>
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
                    url: '{{ route('sales.purchase.archived') }}'
                },
                columns: [{
                        data: 'code',
                    },
                    {
                        data: 'created_at',
                    },
                    {
                        data: 'pharmacy.nama_apotek',
                        className: 'text-center'
                    },
                    {
                        data: 'status',
                        className: 'text-center',
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
