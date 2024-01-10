@extends('layouts.admin')
@section('title', __('Rekap Nota Belum Lunas'))

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
                            <label for="start_date">Tanggal Awal : </label>
                            <input type="date" class="form-control" value="{{ now()->subMonths(3)->format('Y-m-d') }}"
                                name="start_date" id="start_date">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="end_date">Tanggal Akhir : </label>
                            <input type="date" class="form-control" value="{{ now()->format('Y-m-d') }}" name="end_date"
                                id="end_date">
                        </div>
                    </div>
                    <div class="col-md-12 text-right">
                        <button type="submit" id="filter_submit" class="btn btn-primary mr-1"><i class="fa fa-filter"></i>
                            Filter Data</button>
                        <button type="button" id="btn_export_pdf" class="btn btn-danger mr-1"><i
                                class="fas fa-file-pdf"></i>
                            Export Pdf</button>
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
                        query.start_date = $('#start_date').val();
                        query.end_date = $('#end_date').val();
                        query.status = 'BELUM LUNAS';
                        return query
                    }
                },
                columns: [{
                        data: 'code',
                        sortable: true,
                    },
                    {
                        data: 'date',
                        sortable: true,
                    },
                    {
                        data: 'sales.nama',
                        className: 'text-center',
                        sortable: true,
                    },
                    {
                        data: 'pharmacy.nama_apotek',
                        className: 'text-center',
                        sortable: true,
                    }
                ],
                columnDefs: [{
                    targets: '_all',
                    defaultContent: '-'
                }],
                order: [
                    [1, 'desc']
                ],
            })
        })

        $(document).on('click', '#filter_submit', function(e) {
            e.preventDefault();
            table.ajax.reload();
        })

        $(document).on('click', '#btn_export_pdf', function(e) {
            e.preventDefault();

            let route = '{{ route('admin.purchase.export-pdf-belum-lunas') }}' + '?start_date=' + $(
                '#start_date').val() + '&end_date=' + $('#end_date').val()

            window.open(route, '_blank')

        })

        $(document).on('click', '#btn_export_excel', function(e) {
            e.preventDefault();

            let route = '{{ route('admin.purchase.export-excel-belum-lunas') }}' + '?start_date=' + $(
                '#start_date').val() + '&end_date=' + $('#end_date').val()

            window.open(route, '_blank')

        })
    </script>
@endsection
