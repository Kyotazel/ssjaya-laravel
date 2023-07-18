@extends('layouts.sales')
@section('title', __('Laporan Kunjungan'))

@section('style')

@endsection

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
                            <input type="date" class="form-control" value="{{ date('Y-m-d') }}" name="filter_timestamp"
                                id="filter_timestamp">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="filter_apotek">Apotek : </label>
                            <select name="filter_apotek" id="filter_apotek" class="select2">
                                <option value="">Pilih Apotek</option>
                                @foreach ($pharmacies as $pharmacy)
                                    <option value="{{ $pharmacy->id_apotek }}">{{ $pharmacy->nama_apotek }}</option>
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
            <button class="btn btn-success rounded-pill btn-label" onclick="add()"><i
                    class="mdi mdi-folder-image label-icon"></i> Upload Kunjungan</button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="table-data" class="table table-striped table-bordered no-wrap" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Apotek</th>
                            <th>Foto Kunjungan</th>
                            <th>Tanggal</th>
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
                                    <label for="id_apotek">Apotek</label>
                                    <select name="id_apotek" id="id_apotek" class="select2">
                                        <option value="">Pilih Apotek</option>
                                        @foreach ($pharmacies as $pharmacy)
                                            <option value="{{ $pharmacy->id_apotek }}">{{ $pharmacy->nama_apotek }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="image">Gambar Kunjungan</label>
                                    <input type="file" name="image" id="image" class="form-control">
                                    <div class="invalid-feedback"></div>
                                </div>
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
                    url: `{{ route('sales.visit.index') }}`,
                    data: function(query) {
                        query.timestamp = $("#filter_timestamp").val();
                        query.apotek = $("#filter_apotek").val();

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
            var formData = new FormData($('.form_input')[0]);
            formData.append('_token', `{{ csrf_token() }}`)
            var url;
            var status;
            url = `{{ route('sales.visit.store') }}`;
            status = "Ditambahkan";

            $.ajax({
                url: url,
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                dataType: "JSON",
                success: function(data) {
                    notif_success(`<b>Sukses :</b> Data berhasil ${status}`);
                    table.ajax.reload(null, false);
                    $("#modal_form").modal("hide");
                },
                error: function(error) {
                    $.each(error.responseJSON.errors, function(field, messages) {
                        $(`[id=${field}]`).addClass('is-invalid');
                        $(`[id=${field}]`).siblings(':last').text(messages[0]);
                        $(`[id=${field}]`).siblings(':last').addClass('d-block');
                    })
                    notif_error(textStatus);
                },
            }); //end ajax
        });

        $(document).on('click', '[data-toggle="modal"]', function() {
            var titleLabel = $(this).data('title');
            var imageSrc = $(this).data('img');
            $("#modalImage").attr("src", imageSrc);
            $("#imageModalLabel").text(titleLabel);
        });
    </script>
@endsection
