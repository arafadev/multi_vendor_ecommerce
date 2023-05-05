@extends('admin.admin_dashboard')
@section('title', 'Products')
@section('css')
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@endsection
@section('admin')
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">All Product</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">All Product <span
                                class="badge rounded-pill bg-danger"> {{ count($products) }} </span> </li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <div class="btn-group">
                    <a href="{{ route('add.product') }}" class="btn btn-primary">Add Product</a>
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
                                <th>Image </th>
                                <th>Product Name </th>
                                <th>Price </th>
                                <th>QTY </th>
                                <th>Discount </th>
                                <th>Status </th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $key => $item)
                                <tr data-id="{{ $item->id }}">
                                    <td> {{ $key + 1 }} </td>
                                    <td> <img src="{{ asset($item->product_thambnail) }}" style="width: 70px; height:40px;">
                                    </td>
                                    <td>{{ $item->product_name }}</td>
                                    <td>{{ $item->selling_price }}</td>
                                    <td>{{ $item->product_qty }}</td>
                                    <td>
                                        @if ($item->discount_price == null)
                                            <span class="badge rounded-pill bg-info">No Discount</span>
                                        @else
                                            @php
                                                $amount = $item->selling_price - $item->discount_price;
                                                $discount = ($amount / $item->selling_price) * 100;
                                            @endphp
                                            <span class="badge rounded-pill bg-danger"> {{ round($discount) }}%</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->status == 1)
                                            <a href="{{ route('product.inactive', $item->id) }}" class="btn btn-primary"
                                                title="Inactive"> <i class="fa-solid fa-thumbs-down"></i> </a>
                                        @else
                                            <a href="{{ route('product.active', $item->id) }}" class="btn btn-primary"
                                                title="Active"> <i class="fa-solid fa-thumbs-up"></i> </a>
                                        @endif
                                    </td>

                                    <td>
                                        <a href="{{ route('edit.product', $item->id) }}" class="btn btn-info"
                                            title="Edit Data">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <a href="{{ route('delete.product', $item->id) }}" class="btn btn-danger"
                                            id="delete" title="Delete Data"><i class="fa fa-trash"></i></a>

                                        <a href="{{ route('edit.category', $item->id) }}" class="btn btn-warning"
                                            title="Details Page">
                                            <i class="bi bi-info-circle"></i>
                                        </a>

                                        @if ($item->status == 1)
                                            <a href="{{ route('product.inactive', $item->id) }}" class="btn btn-primary"
                                                title="Inactive">
                                                <span class="fa fa-thumbs-down"></span>
                                            </a>
                                        @else
                                            <a href="{{ route('product.active', $item->id) }}" class="btn btn-primary"
                                                title="Active">
                                                <span class="bi bi-hand-thumbs-up"></span>
                                            </a>
                                        @endif

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
                        text: "Once deleted, you will not be able to recover this product!",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            $.ajax({
                                url: '{{ route('delete.product', ':id') }}'.replace(':id', id),
                                type: 'DELETE',
                                dataType: 'json',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function(response) {
                                    swal("Success!",
                                        "product has been deleted successfully!",
                                        "success");
                                    // hide the row from the table
                                    $('tr[data-id="' + id + '"]').remove();
                                },
                                error: function(xhr) {
                                    swal("Error!", "Failed to delete product!", "error");
                                }
                            });
                        }
                    });
            });
        });
    </script>
@endsection
