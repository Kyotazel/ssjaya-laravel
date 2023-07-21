@extends('layouts.admin')
@section('title', __('Artikel'))

@section('style')
    <style>
        .category_article {
            display: inline-block;
            color: white;
            padding: 8px;
            font-weight: bolder;
            border-radius: 999em 40px 40px 999em;
        }
    </style>
@endsection

@section('content')
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <div class="card-title mb-0 flex-grow-1">
                <div class="btn btn-primary">
                    <h6 class="mb-0 text-light">Daftar Artikel</h6>
                </div>
            </div>
            <a class="btn btn-success rounded-pill btn-label" href="{{ route('admin.blog.create') }}"><i
                    class="ri-add-circle-line label-icon"></i> Tambah Data</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="table-data" class="table table-striped table-bordered no-wrap" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Judul</th>
                            <th>Gambar</th>
                            <th>produk</th>
                            <th>Kategori</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
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
        $(document).ready(function() {
            table = $("#table-data").DataTable({
                processing: true,
                serverSide: true,
                fixedColumns: true,
                scrollX: false,
                ajax: {
                    url: `{{ route('admin.blog.index') }}`
                },
                columns: [{
                        data: 'id',
                        searchable: false,
                        render: function(data, type, row, meta) {
                            return row.DT_RowIndex
                        }
                    },
                    {
                        data: 'judul',
                        className: 'text-wrap'
                    },
                    {
                        data: 'image_url'
                    },
                    {
                        data: 'product_custom',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'category_custom',
                        className: 'text-center',
                        searchable: false,
                        orderable: false
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
                        url: `{{ route('admin.blog.destroy', ':id') }}`.replace(':id', id),
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

        $(document).on('click', '[data-toggle="modal"]', function() {
            var titleLabel = $(this).data('title');
            var imageSrc = $(this).data('img');
            $("#modalImage").attr("src", imageSrc);
            $("#imageModalLabel").text(titleLabel);
        });
    </script>
@endsection
