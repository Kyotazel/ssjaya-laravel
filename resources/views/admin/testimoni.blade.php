@extends('layouts.admin')
@section('title', __('Testimoni'))

@section('content')
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <div class="card-title mb-0 flex-grow-1">
                <div class="btn btn-primary">
                    <h6 class="mb-0 text-light">Daftar Testimoni</h6>
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
                            <th>Foto</th>
                            <th>Produk</th>
                            <th>Komentar</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
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
                            <!--end col-->
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="nama">Nama</label>
                                    <input type="text" name="nama" class="form-control" id="nama"
                                        placeholder="Masukkan Nama...">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="jabatan">Jabatan</label>
                                    <input type="text" name="jabatan" class="form-control" id="jabatan"
                                        placeholder="Pembeli...">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="id_produk">Produk</label>
                                    <select name="id_produk" id="id_produk" class="form-control" id="id_produk">
                                        <option value="">Pilih Produk</option>
                                        <?php foreach($products as $product) : ?>
                                        <option value="<?= $product->id ?>"><?= $product->nama ?></option>
                                        <?php endforeach ?>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="komentar">Komenntar</label>
                                    <textarea name="komentar" class="form-control" placeholder="Masukkan komentar..." id="komentar"></textarea>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="image">Foto</label>
                                    <input type="file" name="image" class="form-control" id="image">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <p class="text-danger update_photo">*) Apabila foto tidak diupdate, tidak perlu upload</p>
                            </div>
                            <!--end col-->
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

    <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Image</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <img src="" id="modalImage" class="img-fluid">
                </div>
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
                    url: `{{ route('admin.testimoni.index') }}`
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
                    },
                    {
                        data: 'image_url',
                    },
                    {
                        data: 'product.nama',
                    },
                    {
                        data: 'komentar',
                        className: 'text-wrap'
                    },
                    {
                        data: 'status',
                        className: 'text-center'
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
                        url: `{{ route('admin.testimoni.destroy', ':id') }}`.replace(':id', id),
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
                url = `{{ route('admin.testimoni.store') }}`;
                status = "Ditambahkan";
            } else {
                url = `{{ route('admin.testimoni.update', ':id') }}`.replace(':id', id_use);
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
                url: `{{ route('admin.testimoni.edit', ':id') }}`.replace(':id', id_use),
                type: 'GET',
                dataType: 'JSON',
                success: function(data) {
                    $.each(data, function(key, value) {
                        $(`[name=${key}]`).val(value);
                    });
                    $('#modal_form').modal('show');
                }
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
                url: `{{ route('admin.testimoni.status', ':id') }}`.replace(':id', id),
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
