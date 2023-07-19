@extends('layouts.sales')
@section('title', __('Apotek'))

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
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="filter_prov">Provinsi : </label>
                            <select name="filter_prov" id="filter_prov" class="select2">
                                <option value="">Pilih Provinsi</option>
                                <?php foreach ($provinces as $province) : ?>
                                <option value="<?= $province->id ?>"><?= $province->nama ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="filter_kota">Kota : </label>
                            <select name="filter_kota" id="filter_kota" class="select2" disabled>
                                <option value="">Pilih Kota</option>
                                <?php foreach ($cities as $city) : ?>
                                <option value="<?= $city->kota ?>"><?= $city->kota ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="filter_product">Produk : </label>
                            <select name="filter_product" id="filter_product" class="select2">
                                <option value="">Pilih Produk</option>
                                <?php foreach ($products as $product) : ?>
                                <option value="<?= ucwords(strtolower($product->nama)) ?>"><?= $product->nama ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12 text-right mt-3">
                        <button type="submit" id="filter_submit" class="btn btn-primary mr-1"><i class="fa fa-filter"></i>
                            Filter Data</button>
                        <button type="button" id="btn_export_excel" class="btn btn-success mr-1"><i
                                class="fas fa-file-excel"></i> Export Excel</button>
                        <button type="button" id="btn_export_pdf" class="btn btn-danger mr-1"><i
                                class="fas fa-file-pdf"></i> Export Pdf</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex align-items-center">
            <div class="card-title mb-0 flex-grow-1">
                <div class="btn btn-primary">
                    <h6 class="mb-0 text-light">Daftar Apotek</h6>
                </div>
            </div>
            <button class="btn btn-success rounded-pill btn-label" onclick="add()"><i
                    class="ri-add-circle-line label-icon"></i> Tambah Data</button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="table-data" class="table table-striped table-bordered no-wrap" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>Kota</th>
                            <th>Produk</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <form action="" method="POST" target="_blank" id="form_excel">
        <input type="hidden" name="filter_prov_excel" id="filter_prov_excel">
        <input type="hidden" name="filter_kota_excel" id="filter_kota_excel">
        <input type="hidden" name="filter_product_excel" id="filter_product_excel">
    </form>

    <form action="" method="POST" target="_blank" id="form_pdf">
        <input type="hidden" name="filter_prov_pdf" id="filter_prov_pdf">
        <input type="hidden" name="filter_kota_pdf" id="filter_kota_pdf">
        <input type="hidden" name="filter_product_pdf" id="filter_product_pdf">
    </form>

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
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="nama_apotek">Nama Apotek</label>
                                    <input type="text" name="nama_apotek" class="form-control" id="nama_apotek"
                                        placeholder="Apotek ...">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="provonsi">Provinsi</label>
                                    <select name="provinsi" id="provinsi" class="select2">
                                        <option value="">Pilih Provinsi</option>
                                        <?php foreach ($provinces as $province) : ?>
                                        <option value="<?= $province->id ?>"><?= $province->nama ?></option>
                                        <?php endforeach ?>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="kota">Kota</label>
                                    <select disabled name="kota" id="kota" class="select2">

                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="alamat">Alamat</label>
                                    <input type="text" name="alamat" id="alamat" class="form-control"
                                        placeholder="Jl. .....">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="produk">Produk</label>
                                    <select name="product[]" id="product" class="select2" multiple>
                                        <?php foreach ($products as $product) : ?>
                                        <option value="<?= ucwords(strtolower($product->nama)) ?>"><?= $product->nama ?>
                                        </option>
                                        <?php endforeach ?>
                                    </select>
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
@endsection

@section('script')
    <script>
        var table = $("#table-data").DataTable({});
        var save_method, id_use;
        var filter_data = {};

        $(document).ready(function() {
            table.destroy();
            table = $("#table-data").DataTable({
                processing: true,
                serverSide: true,
                fixedColumns: true,
                scrollX: false,
                ajax: {
                    url: `{{ route('sales.pharmacy.index') }}`,
                    data: function(query) {
                        query.kota = $("#filter_kota").val();
                        query.prov = $("#filter_prov").val();
                        query.product = $("#filter_product").val();

                        return query;
                    }
                },
                columns: [{
                        data: 'id_apotek',
                        searchable: false,
                        render: function(data, type, row, meta) {
                            return row.DT_RowIndex
                        }
                    },
                    {
                        data: 'nama_apotek',
                    },
                    {
                        data: 'alamat',
                        searchable: false,
                        className: 'text-wrap'
                    },
                    {
                        data: 'city.id',
                        searchable: false,
                        render: function(data, type, row, meta) {
                            return row.nama;
                        }
                    },
                    {
                        data: 'produk',
                        searchable: false,
                        className: 'text-wrap'
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

        function check_province() {
            if ($("#provinsi").val() != '') {
                $("#kota").removeAttr('disabled');
                $("#alamat").removeAttr('disabled');
            } else {
                $('#kota').attr('disabled', 'disabled');
                $('#alamat').attr('disabled', 'disabled');
            }
        }

        function check_province_filter() {
            if ($("#filter_prov").val() != '') {
                $("#filter_kota").removeAttr('disabled');
            } else {
                $('#filter_kota').attr('disabled', 'disabled');
            }
        }

        $(document).on("change", "#provinsi", function() {
            check_province();
            let idprov = $(this).val();
            let url = `{{ route('city', ':id') }}`.replace(':id', idprov)
            $.ajax({
                url: url,
                dataType: "JSON",
                success: function(data) {
                    html = "<option value=''>Pilih Kota</option>";
                    data.forEach(function(item) {
                        html += '<option value=' + item.id + '>' + item.nama + '</option>';
                    })
                    $("#kota").html(html);
                }
            })
        })

        $(document).on("change", "#filter_prov", function() {
            check_province_filter();
            let idprov = $(this).val();
            let url = `{{ route('city', ':id') }}`.replace(':id', idprov)
            $.ajax({
                url: url,
                dataType: "JSON",
                success: function(data) {
                    html = "<option value=''>Pilih Kota</option>";
                    data.forEach(function(item) {
                        html += '<option value=' + item.id + '>' + item.nama + '</option>';
                    })
                    $("#filter_kota").html(html);
                }
            })
        })

        function add() {
            save_method = 'add';
            $('.form_input')[0].reset();
            $('.modal-title').html('TAMBAH DATA');
            $('.invalid-feedback').empty();
            $('.invalid-feedback').removeClass('d-block');
            $('.form-control').removeClass('is-invalid');
            select2 = $('.select2');
            select2.val(null).trigger('change');
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
                url = `{{ route('sales.pharmacy.store') }}`;
                status = "Ditambahkan";
            } else {
                url = `{{ route('sales.pharmacy.update', ':id') }}`.replace(':id', id_use);
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
                },
            }); //end ajax
        });

        $(document).on('click', '.btn_edit', function() {
            $('.modal-title').html('EDIT DATA');
            $('.invalid-feedback').empty();
            $('.invalid-feedback').removeClass('d-block');
            $('.form-control').removeClass('is-invalid');
            id_use = $(this).data("id")
            save_method = 'update';
            $.ajax({
                url: `{{ route('sales.pharmacy.edit', ':id') }}`.replace(':id', id_use),
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    if (data.status) {
                        produk = data.data.produk;
                        arr_prod = produk.split(", ")

                        productSelect = document.getElementById("product");
                        provinsiSelect = $("#provinsi");
                        kotaSelect = $("#kota");
                        select2 = $(".select2")
                        select2.val(null).trigger('change');

                        provinsiSelect.val(data.data.provinsi).trigger('change');
                        setTimeout(function() {
                            kotaSelect.val(data.data.kota).trigger('change');
                        }, 1000);

                        productSelect.querySelectorAll('option').forEach(option => {
                            if (arr_prod.indexOf(option.value) > -1) {
                                option.selected = true;
                            }
                        });

                        select2.trigger('change');

                        $('[name="nama_apotek"]').val(data.data.nama_apotek);
                        $('[name="alamat"]').val(data.data.alamat);
                        $('#modal_form').modal('show');
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    notif_error(textStatus);
                }
            })
        })

        $(document).on('click', '.btn_delete', function() {
            id = $(this).data('id');
            url = `{{ route('sales.pharmacy.destroy', ':id') }}`.replace(':id', id);
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
                        url: url,
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

        $("#filter_submit").click(function(e) {
            e.preventDefault();
            table.ajax.reload();
        })

        $("#btn_export_excel").click(function(e) {
            e.preventDefault();
            $("#filter_prov_excel").val(filter_prov);
            $("#filter_kota_excel").val(filter_kota);
            $("#filter_product_excel").val(filter_product);
            $("#form_excel").submit();
        })

        $("#btn_export_pdf").click(function(e) {
            e.preventDefault();
            $("#filter_prov_pdf").val(filter_prov);
            $("#filter_kota_pdf").val(filter_kota);
            $("#filter_product_pdf").val(filter_product);
            $("#form_pdf").submit();
        })
    </script>
@endsection
