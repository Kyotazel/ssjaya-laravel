@extends('layouts.admin')
@section('title', __('Rekap Nota Belum Lunas'))

@section('content')
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <div class="card-title mb-0 flex-grow-1">
                <div class="btn btn-primary">
                    <h6 class="mb-0 text-light">Daftar Rekap Nota Belum Lunas </h6>
                </div>
            </div>
            <button type="button" id="btn_export_pdf" class="btn btn-danger mr-1"><i class="fas fa-file-pdf"></i>
                Export Pdf</button>
            <button type="button" id="btn_export_excel" class="btn btn-success mr-1"><i class="fas fa-file-excel"></i>
                Export Excel</button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="table-data" class="table table-striped table-bordered no-wrap" style="width: 100%">
                    <thead>
                        <tr>
                            <th>Nomor Nota</th>
                            <th>Tanggal</th>
                            <th>Sales</th>
                            <th>Apotek</th>
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
            let id_use, year, month, day;
            table = $("#table-data").DataTable({
                processing: true,
                serverSide: true,
                fixedColumns: true,
                scrollX: false,
                ajax: {
                    url: '{{ route('admin.purchase.index') }}',
                    data: function(query) {
                        query.status = 'BELUM LUNAS';

                        return query
                    }
                },
                columns: [{
                        data: 'code',
                    },
                    {
                        data: 'date',
                    },
                    {
                        data: 'sales.nama',
                        className: 'text-center'
                    },
                    {
                        data: 'pharmacy.nama_apotek',
                        className: 'text-center'
                    }
                ],
                columnDefs: [{
                    targets: '_all',
                    defaultContent: '-'
                }],
            })
        })

        $(document).on('click', '#btn_export_pdf', function(e) {
            e.preventDefault();

            let route = '{{ route('admin.purchase.export-pdf-belum-lunas') }}'

            window.open(route, '_blank')

        })

        $(document).on('click', '#btn_export_excel', function(e) {
            e.preventDefault();

            let route = '{{ route('admin.purchase.export-excel-belum-lunas') }}'

            window.open(route, '_blank')

        })
    </script>
@endsection
