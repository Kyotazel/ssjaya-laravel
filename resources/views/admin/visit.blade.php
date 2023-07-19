@extends('layouts.admin')
@section('title', __('Laporan Kunjungan'))

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
                            <label for="filter_timestamp">Tanggal : </label>
                            <input type="date" class="form-control" value="<?= date('Y-m-d') ?>" name="filter_timestamp"
                                id="filter_timestamp">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="filter_sales">Sales : </label>
                            <select name="filter_sales" id="filter_sales" class="select2">
                                <option value="">Pilih Sales</option>
                                @foreach ($saless as $sales)
                                    <option value="{{ $sales->id_sales }}">{{ $sales->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12 text-right">
                        <button type="submit" id="filter_submit" class="btn btn-primary mr-1"><i class="fa fa-filter"></i>
                            Filter Data</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex align-items-center">
            <div class="card-title mb-0 flex-grow-1">
                <div class="btn btn-primary">
                    <h6 class="mb-0 text-light">Daftar Laporan Kunjungan</h6>
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
                            <th>Apotek</th>
                            <th>Foto Kunjungan</th>
                            <th>Tanggal</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-full-width">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Image</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="margin: auto;">
                    <img src="" id="modalImage" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        var table, save_method, id_use;
        let filter_timestamp = $("#filter_timestamp").val();

        $(document).ready(function() {
            table = $("#table-data").DataTable({
                processing: true,
                serverSide: true,
                fixedColumns: true,
                scrollX: false,
                ajax: {
                    url: `{{ route('admin.visit.index') }}`,
                    data: function(query) {
                        query.timestamp = $("#filter_timestamp").val();
                        query.sales = $("#filter_sales").val();

                        return query;
                    }
                },
                columns: [{
                        data: 'id_laporan',
                        searchable: false,
                        render: function(data, type, row, meta) {
                            return row.DT_RowIndex
                        }
                    },
                    {
                        data: 'sales.nama'
                    },
                    {
                        data: 'pharmacy.nama_apotek',
                    },
                    {
                        data: 'image_url',
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'timestamps',
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'status'
                    }
                ],
                columnDefs: [{
                    targets: '_all',
                    defaultContent: '-'
                }],
            })
        })

        $("#filter_submit").click(function(e) {
            e.preventDefault();
            table.ajax.reload()
        })

        $(document).on('click', '.change_to_active', function() {
            var selector = $(this);
            let status = 1;
            Swal.fire({
                icon: 'question',
                text: 'Ganti menjadi aktif?',
                showCancelButton: true,
                confirmButtonClass: 'btn btn-primary rounded-pill w-xs me-2 mb-1 mr-3',
                confirmButtonText: "Ya , Lanjutkan",
                cancelButtonText: "Batal",
                cancelButtonClass: 'btn btn-danger rounded-pill w-xs mb-1',
                closeOnConfirm: true,
                closeOnCancel: true,
                buttonsStyling: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    update_status(selector, status)
                }
            })
        });

        $(document).on('click', '.change_to_not_active', function() {
            var selector = $(this);
            let status = 0;
            Swal.fire({
                icon: 'question',
                text: 'Ganti menjadi tidak aktif?',
                showCancelButton: true,
                confirmButtonClass: 'btn btn-primary rounded-pill w-xs me-2 mb-1 mr-3',
                confirmButtonText: "Ya , Lanjutkan",
                cancelButtonText: "Batal",
                cancelButtonClass: 'btn btn-danger rounded-pill w-xs mb-1',
                closeOnConfirm: true,
                closeOnCancel: true,
                buttonsStyling: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    update_status(selector, status)
                }
            })
        });


        function update_status(selector, status) {
            var id = selector.data('id');
            $.ajax({
                url: `{{ route('admin.visit.status', ':id') }}`.replace(':id', id),
                type: "POST",
                dataType: "JSON",
                data: {
                    _token: '{{ csrf_token() }}',
                    status: status
                },
                success: function(data) {
                    notif_success("Data berhasil diupdate");
                    table.ajax.reload();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    notif_error(textStatus);
                }

            }); //end ajax
        }

        $(document).on('click', '[data-toggle="modal"]', function() {
            var titleLabel = $(this).data('title');
            var imageSrc = $(this).data('img');
            $("#modalImage").attr("src", imageSrc);
            $("#imageModalLabel").text(titleLabel);
        });
    </script>
@endsection
