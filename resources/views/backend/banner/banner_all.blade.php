@extends('admin.admin_dashboard')
@section('title', 'Banners')
@section('css')
    <link href="{{ asset('adminbackend/assets/plugins/datatable/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

@endsection
@section('admin')
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">All Banner</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">All Banner</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <div class="btn-group">
                    <a href="{{ route('add.banner') }}" class="btn btn-primary">Add Banner</a>
                </div>
            </div>
        </div>
        <!--end breadcrumb-->

        <hr />
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Banner Title </th>
                                <th>Banner Url </th>
                                <th>Banner Image </th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($banner as $key => $item)
                                <tr>
                                    <td> {{ $key + 1 }} </td>
                                    <td>{{ $item->banner_title }}</td>
                                    <td>{{ $item->banner_url }}</td>
                                    <td> <img src="{{ asset($item->banner_image) }}" style="width: 70px; height:40px;">
                                    </td>

                                    <td>
                                        <a href="{{ route('edit.banner',$item->id) }}" class="btn btn-info">Edit</a>
                                        <a href="{{ route('delete.banner',$item->id) }}" class="btn btn-danger" id="delete" >Delete</a>

                                    </td>
                                </tr>
                            @endforeach


                        </tbody>

                    </table>
                </div>
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
                        text: "Once deleted, you will not be able to recover this category!",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            $.ajax({
                                url: '{{ route('delete.category', ':id') }}'.replace(':id',
                                    id),
                                type: 'DELETE',
                                dataType: 'json',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function(response) {
                                    swal("Success!",
                                        "category has been deleted successfully!",
                                        "success");
                                    // hide the row from the table
                                    $('tr[data-id="' + id + '"]').hide();
                                },
                                error: function(xhr) {
                                    swal("Error!", "Failed to delete category!", "error");
                                }
                            });
                        }
                    });
            });
        });
    </script>
@endsection
