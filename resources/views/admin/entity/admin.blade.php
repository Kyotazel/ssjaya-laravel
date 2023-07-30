@extends('layouts.admin')
@section('title', __('Admin'))

@section('content')
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <div class="card-title mb-0 flex-grow-1">
                <div class="btn btn-primary">
                    <h6 class="mb-0 text-light">Daftar Admin</h6>
                </div>
            </div>
            <button class="btn btn-success rounded-pill btn-label" onclick="add()"><i
                    class="ri-add-circle-line label-icon"></i> Tambah Data</button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="table-data" class="table table-striped table-bordered no-wrap" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Username</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal zoomIn" id="modal_form" tabindex="-1" aria-modal="true" role="dialog" data-keyboard="false"
        aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form class="form_input">
                    <div class="modal-header">
                        <h5 class="modal-title" id="label_modal">Grid Modals</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <div class="row text-dark">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="name">Nama</label>
                                    <input type="text" name="name" class="form-control" id="name"
                                        placeholder="Masukkan Nama...">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="username">Username</label>
                                    <input type="text" name="username" class="form-control" id="username"
                                        placeholder="Masukkan username...">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" name="password" class="form-control" id="password"
                                        placeholder="Masukkan Password...">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
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
                    url: `{{ route('admin.admin.index') }}`
                },
                columns: [{
                        data: 'id',
                        searchable: false,
                        render: function(data, type, row, meta) {
                            return row.DT_RowIndex
                        }
                    },
                    {
                        data: 'name',
                    },
                    {
                        data: 'username',
                    },
                    {
                        data: 'action',
                        searchable: false,
                        className: 'text-center'
                    }
                ],
                columnDefs: [{
                    targets: '_all',
                    defaultContent: '-'
                }],
            })
        })

        $(document).on('click', '.btn_delete', function() {
            id = $(this).data('id');
            Swal.fire({
                icon: 'question',
                text: 'Yakin ingin menghapus data?',
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
                    $.ajax({
                        url: `{{ route('admin.admin.destroy', ':id') }}`.replace(':id', id),
                        type: "POST",
                        dataType: "JSON",
                        data: {
                            _token: `{{ csrf_token() }}`,
                            _method: `DELETE`
                        },
                        success: function(data) {
                            notif_success(`<b>Sukses : </b> ${data.message}`)
                            table.ajax.reload(null, false);
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            notif_error(textStatus);
                        }
                    })
                }
            });
        });

        function add() {
            save_method = 'add';
            $('.update_photo').css('display', 'block');
            $('.form_input')[0].reset();
            $('.modal-title').html('TAMBAH DATA');
            $('.invalid-feedback').empty();
            $('.invalid-feedback').removeClass('d-block');
            $('.form-control').removeClass('is-invalid');
            $('#modal_form').modal('show');
        }

        $('.btn_save').click(function(e) {
            e.preventDefault();
            $('.form-control').removeClass('is-invalid');
            $('.invalid-feedback').empty();
            $('.invalid-feedback').removeClass('d-block');
            // $('#telepon').val(telp.getRawValue());
            var formData = new FormData($('.form_input')[0]);
            formData.append('_token', `{{ csrf_token() }}`)
            var url;
            var status;
            if (save_method == 'add') {
                url = `{{ route('admin.admin.store') }}`;
                status = "Ditambahkan";
            } else {
                url = `{{ route('admin.admin.update', ':id') }}`.replace(':id', id_use);
                status = "Diubah";
                formData.append('_method', 'PUT');
            }

            $.ajax({
                url: url,
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                dataType: "JSON",
                success: function(data) {
                    notif_success(`<b>Sukses :</b> ${data.message}`);
                    table.ajax.reload(null, false);
                    $("#modal_form").modal("hide");
                },
                error: function(error) {
                    $.each(error.responseJSON.errors, function(field, messages) {
                        $(`[id=${field}]`).addClass('is-invalid');
                        $(`[id=${field}]`).siblings(':last').text(messages[0]);
                        $(`[id=${field}]`).siblings(':last').addClass('d-block');
                    })
                },
            }); //end ajax
        });

        $(document).on('click', '.btn_edit', function() {
            $('.modal-title').html('EDIT DATA');
            $('.invalid-feedback').empty();
            $('.invalid-feedback').removeClass('d-block');
            $('.form-control').removeClass('is-invalid');
            $('.update_photo').css('display', 'block');
            id_use = $(this).data("id")
            save_method = 'update';

            $.ajax({
                url: `{{ route('admin.admin.edit', ':id') }}`.replace(':id', id_use),
                type: 'GET',
                dataType: 'JSON',
                success: function(data) {
                    $.each(data, function(key, value) {
                        $(`[name=${key}]`).val(value);
                        $('#modal_form').modal('show');
                    });
                }
            })
        })
    </script>
@endsection
