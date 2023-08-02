@extends('layouts.admin')
@section('title', __('Barang Keluar'))

@section('content')
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <div class="card-title mb-0 flex-grow-1">
                <div class="btn btn-primary">
                    <h6 class="mb-0 text-light">Daftar Barang Keluar</h6>
                </div>
            </div>
            <a class="btn btn-success rounded-pill btn-label" href="{{ route('admin.ongoing-request.create') }}"><i
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
                            <th>Request Date</th>
                            <th>Sales</th>
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

    <div class="modal zoomIn" id="modal_status" tabindex="-1" aria-modal="true" role="dialog" data-keyboard="false"
        aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form class="form_input">
                    <div class="modal-header">
                        <h5 class="modal-title" id="label_modal">Update Status</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <div class="row text-dark">
                            <!--end col-->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        @foreach ($statuses as $status)
                                            <option {{ $status == 'PENDING' ? 'selected' : '' }}
                                                value="{{ $status }}">{{ $status }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <!--end row-->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary btn_save">
                            <span class="spinner-grow spinner-grow-sm d-none" role="status"></span>
                            Submit
                        </button>
                    </div>
                </form>
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
                    url: `{{ route('admin.ongoing-request.index') }}`,
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
                        data: 'request_date',
                    },
                    {
                        data: 'sales.nama',
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
                                return `<button class="btn btn-sm btn_update_status btn-warning" data-id="${row.id}">Menunggu</button>`
                            } else if (row.status == 'APPROVED') {
                                return `<button class="btn btn-sm btn-success">Disetujui</button>`
                            } else if (row.status == 'REJECTED') {
                                return `<button class="btn btn-sm btn-danger">Ditolak</button>`
                            }
                            return `<button class="btn btn-sm btn-secondary">Diarsipkan</button>`
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

        $(document).on('click', '.btn_update_status', function(e) {
            e.preventDefault();
            id_use = $(this).data('id')
            $('#modal_status').modal('show');
        })

        $(document).on('click', '.archiveButton', function(e) {
            e.preventDefault();
            id_use = $(this).data('id')
            $("#status").val('ARCHIVED');
            $('.btn_save').click();
        })

        $(document).on('click', '.btn_save', function(e) {
            e.preventDefault();

            var formData = new FormData($('.form_input')[0]);
            formData.append('_token', `{{ csrf_token() }}`)

            $.ajax({
                url: `{{ route('admin.ongoing-request.status', ':id') }}`.replace(':id', id_use),
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                dataType: "JSON",
                success: function(data) {
                    notif_success(`<b>Sukses :</b> Status Berhasil Diperbarui`);
                    table.ajax.reload(null, false);
                    $("#modal_status").modal("hide");
                },
                error: function(error) {
                    $.each(error.responseJSON.errors, function(field, messages) {
                        $(`[id=${field}]`).addClass('is-invalid');
                        $(`[id=${field}]`).siblings(':last').text(messages[0]);
                        $(`[id=${field}]`).siblings(':last').addClass('d-block');
                    })
                },
            }); //end ajax

        })
    </script>
@endsection
