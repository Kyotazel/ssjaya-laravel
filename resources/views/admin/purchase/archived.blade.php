@extends('layouts.admin')
@section('title', __('Nota Diarsipkan'))

@section('content')
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <div class="card-title mb-0 flex-grow-1">
                <div class="btn btn-primary">
                    <h6 class="mb-0 text-light">Daftar Nota Diarsipkan</h6>
                </div>
            </div>
            <a class="btn btn-success rounded-pill btn-label mr-3" href="{{ route('admin.purchase.index') }}"><i
                    class="fa fa-check"></i> Nota Normal</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="table-data" class="table table-striped table-bordered no-wrap" style="width: 100%">
                    <thead>
                        <tr>
                            <th>Nomor Nota</th>
                            <th>Tanggal Dibuat</th>
                            <th>Sales</th>
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
                                        {{-- @foreach ($statuses as $status)
                                            <option {{ $status == 'PENDING' ? 'selected' : '' }}
                                                value="{{ $status }}">{{ $status }}</option>
                                        @endforeach --}}
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
                    url: '{{ route('admin.purchase.archived') }}'
                },
                columns: [{
                        data: 'code',
                    },
                    {
                        data: 'created_at',
                    },
                    {
                        data: 'sales.nama',
                        className: 'text-center'
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

        $(document).on('click', '.archiveButton', function(e) {
            e.preventDefault();

            let archiveId = $(this).data('id');
            let formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}')

            Swal.fire({
                icon: 'question',
                text: 'Apakah anda yakin ingin mengeluarkan nota ini dari arsip?',
                showCancelButton: true,
                confirmButtonClass: 'btn btn-primary rounded-pill w-xs me-2 mb-1 mr-3',
                confirmButtonText: "Ya , Lunasi",
                cancelButtonText: "Batal",
                cancelButtonClass: 'btn btn-danger rounded-pill w-xs mb-1',
                closeOnConfirm: true,
                closeOnCancel: true,
                buttonsStyling: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `{{ route('admin.purchase.archive', ':id') }}`.replace(':id',
                            archiveId),
                        type: "POST",
                        contentType: false,
                        data: formData,
                        processData: false,
                        dataType: "JSON",
                        success: function(data) {
                            Swal.fire({
                                text: 'Berhasil Mengeluarkan Nota Dari Arsip!',
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            table.ajax.reload();
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            notif_error("Mohon Lengkapi Data");
                        }
                    })
                }
            });
        })
    </script>
@endsection
