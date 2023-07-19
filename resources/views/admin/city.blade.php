@extends('layouts.admin')
@section('title', __('Kota'))

@section('content')
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <div class="card-title mb-0 flex-grow-1">
                <div class="btn btn-primary">
                    <h6 class="mb-0 text-light">Daftar Kota</h6>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="table-data" class="table table-striped table-bordered no-wrap" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kota / Kabupaten</th>
                            <th>Provinsi</th>
                            <th>Status</th>
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
        var table = $("#table-data").DataTable({});
        var save_method, id_use;

        $(document).ready(function() {
            table.destroy();
            table = $("#table-data").DataTable({
                processing: true,
                serverSide: true,
                fixedColumns: true,
                scrollX: false,
                ajax: {
                    url: `{{ route('admin.city.index') }}`
                },
                columns: [{
                        data: 'id',
                        searchable: false,
                        render: function(data, type, row, meta) {
                            return row.DT_RowIndex
                        }
                    },
                    {
                        data: 'nama',
                        searchable: true
                    },
                    {
                        data: 'province.nama',
                        searchable: false
                    },
                    {
                        data: 'status',
                        className: 'text-center'
                    },
                ],
                order: [
                    [3, 'desc']
                ],
                columnDefs: [{
                    targets: '_all',
                    defaultContent: '-'
                }],
            })
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
                url: `{{ route('admin.city.status', ':id') }}`.replace(':id', id),
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
    </script>
@endsection
