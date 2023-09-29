@extends('layouts.admin')
@section('title', __('Rekap Nota'))

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
                            <label for="year">Tahun : </label>
                            <select name="year" id="year" class="select2">
                                <option value="">Pilih Tahun</option>
                                @for ($i = $firstYear; $i <= $lastYear; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="month">Bulan : </label>
                            <select name="month" id="month" class="select2" disabled>
                                <option value="">Pilih Bulan</option>
                                <option value="1">Januari</option>
                                <option value="2">Februari</option>
                                <option value="3">Maret</option>
                                <option value="4">April</option>
                                <option value="5">Mei</option>
                                <option value="6">Juni</option>
                                <option value="7">Juli</option>
                                <option value="8">Agustus</option>
                                <option value="9">September</option>
                                <option value="10">Oktober</option>
                                <option value="11">November</option>
                                <option value="12">Desember</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="day">Tanggal : </label>
                            <select name="day" id="day" class="select2" disabled>
                                <option value="">Pilih Tanggal</option>
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
                    <h6 class="mb-0 text-light">Daftar Rekap Nota</h6>
                </div>
            </div>
            <a class="btn btn-secondary rounded-pill btn-label mr-3" href="{{ route('admin.purchase.archived') }}"><i
                    class="fa fa-trash"></i> Nota Diarsipkan</a>
            <a class="btn btn-success rounded-pill btn-label" href="{{ route('admin.purchase.create') }}"><i
                    class="ri-add-circle-line label-icon"></i> Tambah Data</a>
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
            let id_use, year, month, day;
            table = $("#table-data").DataTable({
                processing: true,
                serverSide: true,
                fixedColumns: true,
                scrollX: false,
                ajax: {
                    url: '{{ route('admin.purchase.index') }}',
                    data: function(query) {
                        query.year = $('#year').val();
                        query.month = $('#month').val();
                        query.day = $('#day').val();

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

        $(document).on('click', '#filter_submit', function(e) {
            e.preventDefault();
            table.ajax.reload();
        });

        function generateDays(month, year) {
            var daysInMonth = new Date(year, month, 0).getDate();
            var daySelect = $("#day");

            // Clear existing options
            daySelect.empty();
            daySelect.append(new Option('Pilih', ''));

            // Populate days for the selected month
            for (var i = 1; i <= daysInMonth; i++) {
                daySelect.append(new Option(i, i));
            }
        }

        $(document).on('change', '#year', function(e) {
            e.preventDefault()
            year = $(this).val();

            $('#month').prop('disabled', true)
            if (year) {
                $('#month').prop('disabled', false)
            }
        })

        $(document).on('change', '#month', async function(e) {
            e.preventDefault()
            month = $(this).val();

            $('#day').prop('disabled', true)
            if (month) {
                await generateDays(month, year)
                $('#day').prop('disabled', false)

            }
        })

        $(document).on('click', '.btn_update_status', function(e) {
            e.preventDefault();
            id_use = $(this).data('id')
            $('#modal_status').modal('show');
        })

        $(document).on('click', '.archiveButton', function(e) {
            e.preventDefault();

            let archiveId = $(this).data('id');
            let formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}')

            Swal.fire({
                icon: 'question',
                text: 'Apakah anda yakin ingin mengarsipkan nota ini?',
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
                                text: 'Berhasil Mengarsipkan Nota!',
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

        $(document).on('click', '.payOffButton', function(e) {
            e.preventDefault();
            let payId = $(this).data('id');
            let formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}')

            Swal.fire({
                icon: 'question',
                text: 'Apakah anda yakin ingin melunasi nota ini?',
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
                        url: `{{ route('admin.purchase.status', ':id') }}`.replace(':id', payId),
                        type: "POST",
                        contentType: false,
                        data: formData,
                        processData: false,
                        dataType: "JSON",
                        success: function(data) {
                            Swal.fire({
                                text: 'Berhasil Melunasi Nota!',
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
