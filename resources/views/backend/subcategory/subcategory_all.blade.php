@extends('admin.admin_dashboard')
@section('title', 'Category')
@section('css')
    <link href="{{ asset('adminbackend/assets/plugins/datatable/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

@endsection
@section('admin')
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">SubCategories</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">All SubCategories</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <div class="btn-group">
                <a href="{{ route('add.subcategory') }}" class="btn btn-primary">Add SubCategory</a>

            </div>
        </div>
    </div>
    <h6 class="mb-0 text-uppercase">SubCategories Table</h6>
    <hr />
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="example2" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Category Name</th>
                            <th>SubCategory Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($subcategories as $key => $subcategory)
                            <tr data-id="{{ $subcategory->id }}">
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $subcategory->category->category_name }}</td>
                                <td>{{ $subcategory->subcategory_name }}</td>
                                <td>
                                    <a href="{{ route('edit.subcategory', $subcategory->id) }}" class="btn btn-info">Edit</a>
                                    <a href="#" class="btn btn-danger delete-btn "
                                        data-id="{{ $subcategory->id }}">Delete</a>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>

                </table>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('adminbackend/assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>

    <script src="{{ asset('adminbackend/assets/plugins/datatable/js/dataTables.bootstrap5.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            var table = $('#example2').DataTable({
                lengthChange: false,
                buttons: ['copy', 'excel', 'pdf', 'print']
            });

            table.buttons().container()
                .appendTo('#example2_wrapper .col-md-6:eq(0)');
        });
    </script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        @if (Session::has('message'))
            var type = "{{ Session::get('alert-type', 'info') }}"
            switch (type) {
                case 'info':
                    toastr.info(" {{ Session::get('message') }} ");
                    break;
                case 'success':
                    toastr.success(" {{ Session::get('message') }} ");
                    break;
                case 'warning':
                    toastr.warning(" {{ Session::get('message') }} ");
                    break;
                case 'error':
                    toastr.error(" {{ Session::get('message') }} ");
                    break;
            }
        @endif
    </script>
    <script>
        $(document).ready(function() {
            $('.delete-btn').click(function() {
                var id = $(this).data('id');
                swal({
                        title: "Are you sure?",
                        text: "Once deleted, you will not be able to recover this Subcategory!",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            $.ajax({
                                url: '{{ route('delete.subcategory', ':id') }}'.replace(':id',
                                    id),
                                type: 'DELETE',
                                dataType: 'json',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function(response) {
                                    swal("Success!",
                                        "subcategory has been deleted successfully!",
                                        "success");
                                    // hide the row from the table
                                    $('tr[data-id="' + id + '"]').hide();
                                },
                                error: function(xhr) {
                                    swal("Error!", "Failed to delete subcategory!", "error");
                                }
                            });
                        }
                    });
            });
        });
    </script>
@endsection
