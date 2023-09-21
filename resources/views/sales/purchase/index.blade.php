@extends('layouts.sales')
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
            <a class="btn btn-secondary rounded-pill btn-label mr-3" href="{{ route('sales.purchase.archived') }}"><i
                    class="fa fa-trash"></i> Nota Diarsipkan</a>
            <a class="btn btn-success rounded-pill btn-label" href="{{ route('sales.purchase.create') }}"><i
                    class="ri-add-circle-line label-icon"></i> Tambah Data</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="table-data" class="table table-striped table-bordered no-wrap" style="width: 100%">
                    <thead>
                        <tr>
                            <th>Nomor Nota</th>
                            <th>Tanggal Dibuat</th>
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
                    url: '{{ route('sales.purchase.index') }}',
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
                        data: 'created_at',
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
    </script>
@endsection
