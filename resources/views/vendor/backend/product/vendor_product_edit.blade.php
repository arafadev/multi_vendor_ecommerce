@extends('vendor.vendor_dashboard')
@section('title', 'Edit product')
@section('css')
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">

    <link href="{{ asset('adminbackend/assets/plugins/input-tags/css/tagsinput.css') }}" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
@endsection
@section('vendor')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <div class="page-content">

        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Edit Vendor Product</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Vendor Product</li>
                    </ol>
                </nav>
            </div>

        </div>
        <!--end breadcrumb-->

        <div class="card">
            <div class="card-body p-4">
                <h5 class="card-title">Edit Vendor Product</h5>
                <hr />

                <form id="myForm" method="post" action="{{ route('vendor.update.product') }}">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    <div class="form-body mt-4">
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="border border-3 p-4 rounded">


                                    <div class="form-group mb-3">
                                        <label for="inputProductTitle" class="form-label">Product Name</label>
                                        <input type="text" name="product_name" class="form-control"
                                            id="inputProductTitle" value="{{ $product->product_name }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="inputProductTitle" class="form-label">Product Tags</label>
                                        <input type="text" name="product_tags" class="form-control visually-hidden"
                                            data-role="tagsinput" value="{{ $product->product_tags }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="inputProductTitle" class="form-label">Product Size</label>
                                        <input type="text" name="product_size" class="form-control visually-hidden"
                                            data-role="tagsinput" value="{{ $product->product_size }} ">
                                    </div>

                                    <div class="mb-3">
                                        <label for="inputProductTitle" class="form-label">Product Color</label>
                                        <input type="text" name="product_color" class="form-control visually-hidden"
                                            data-role="tagsinput" value="{{ $product->product_color }}">
                                    </div>



                                    <div class="form-group mb-3">
                                        <label for="inputProductDescription" class="form-label">Short Description</label>
                                        <textarea name="short_desc" class="form-control" id="inputProductDescription" rows="3">
                                        {{ $product->short_desc }}
                                                </textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="inputProductDescription" class="form-label">Long Description</label>
                                        <textarea id="mytextarea" name="long_desc">
				 {!! $product->long_desc !!}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="border border-3 p-4 rounded">
                                    <div class="row g-3">

                                        <div class="form-group col-md-6">
                                            <label for="inputPrice" class="form-label">Product Price</label>
                                            <input type="text" name="selling_price" class="form-control" id="inputPrice"
                                                value="{{ $product->selling_price }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="inputCompareatprice" class="form-label">Discount Price </label>
                                            <input type="text" name="discount_price" class="form-control"
                                                id="inputCompareatprice" value="{{ $product->discount_price }}">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="inputCostPerPrice" class="form-label">Product Code</label>
                                            <input type="text" name="product_code" class="form-control"
                                                id="inputCostPerPrice" value="{{ $product->product_code }}">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="inputStarPoints" class="form-label">Product Quantity</label>
                                            <input type="text" name="product_qty" class="form-control"
                                                id="inputStarPoints" value="{{ $product->product_qty }}">
                                        </div>


                                        <div class="form-group col-12">
                                            <label for="inputProductType" class="form-label">Product Brand</label>
                                            <select name="brand_id" class="form-select" id="inputProductType">
                                                <option></option>
                                                @foreach ($brands as $brand)
                                                    <option value="{{ $brand->id }}"
                                                        {{ $brand->id == $product->brand_id ? 'selected' : '' }}>
                                                        {{ $brand->brand_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group col-12">
                                            <label for="inputVendor" class="form-label">Product Category</label>
                                            <select name="category_id" class="form-select" id="inputVendor">
                                                <option></option>
                                                @foreach ($categories as $cat)
                                                    <option value="{{ $cat->id }}"
                                                        {{ $cat->id == $product->category_id ? 'selected' : '' }}>
                                                        {{ $cat->category_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group col-12">
                                            <label for="inputCollection" class="form-label">Product SubCategory</label>
                                            <select name="subcategory_id" class="form-select" id="inputCollection">
                                                <option></option>
                                                @foreach ($subcategory as $subcat)
                                                    <option value="{{ $subcat->id }}"
                                                        {{ $subcat->id == $product->subcategory_id ? 'selected' : '' }}>
                                                        {{ $subcat->subcategory_name }}</option>
                                                @endforeach

                                            </select>
                                        </div>
                                        <div class="col-12">

                                            <div class="row g-3">

                                                <div class="col-md-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input" name="hot_deals" type="checkbox"
                                                            value="1" id="flexCheckDefault"
                                                            {{ $product->hot_deals == 1 ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="flexCheckDefault"> Hot
                                                            Deals</label>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input" name="featured" type="checkbox"
                                                            value="1" id="flexCheckDefault"
                                                            {{ $product->featured == 1 ? 'checked' : '' }}>
                                                        <label class="form-check-label"
                                                            for="flexCheckDefault">Featured</label>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input" name="special_offer"
                                                            type="checkbox" value="1" id="flexCheckDefault"
                                                            {{ $product->special_offer == 1 ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="flexCheckDefault">Special
                                                            Offer</label>
                                                    </div>
                                                </div>


                                                <div class="col-md-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input" name="special_deals"
                                                            type="checkbox" value="1" id="flexCheckDefault"
                                                            {{ $product->special_deals == 1 ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="flexCheckDefault">Special
                                                            Deals</label>
                                                    </div>
                                                </div>



                                            </div> <!-- // end row  -->

                                        </div>

                                        <hr>


                                        <div class="col-12">
                                            <div class="d-grid">
                                                <input type="submit" class="btn btn-primary px-4"
                                                    value="Save Changes" />

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end row-->
                    </div>
            </div>

            </form>

        </div>

    </div>


    <!-- /// Main Image Thambnail Update ////// -->

    <div class="page-content">
        <h6 class="mb-0 text-uppercase">Update Main Image Thambnail </h6>
        <hr>
        <div class="card">
            <form method="post" action="{{ route('vendor.update.product.thambnail') }}" enctype="multipart/form-data">
                @csrf

                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="old_img" value="{{ $product->product_thambnail }}">

                <div class="card-body">
                    <div class="mb-3">
                        <label for="formFile" class="form-label">Chose Thambnail Image </label>
                        <input name="product_thambnail" class="form-control" type="file" id="formFile">
                    </div>


                    <div class="mb-3">
                        <label for="formFile" class="form-label"> </label>
                        <img src="{{ asset($product->product_thambnail) }}" style="width:100px; height:100px">
                    </div>

                    <input type="submit" class="btn btn-primary px-4" value="Save Changes" />

                </div>

            </form>

        </div>
    </div>


    <!-- /// End Main Image Thambnail Update ////// -->

    <!-- /// Update Multi Image  ////// -->

    <div class="page-content">
        <h6 class="mb-0 text-uppercase">Update Multi Image </h6>
        <hr>
        <div class="card">
            <div class="card-body">
                <table class="table mb-0 table-striped">
                    <thead>
                        <tr>
                            <th scope="col">#Sl</th>
                            <th scope="col">Image</th>
                            <th scope="col">Change Image </th>
                            <th scope="col">Delete </th>
                        </tr>
                    </thead>
                    <tbody>

                        <form method="post" action="{{ route('vendor.update.product.multiimage') }}"
                            enctype="multipart/form-data">
                            @csrf

                            @foreach ($multiImgs as $key => $img)
                                <tr>
                                    <th scope="row">{{ $key + 1 }}</th>
                                    <td> <img src="{{ asset($img->photo_name) }}" style="width:70; height: 40px;"> </td>
                                    <td> <input type="file" class="form-group" name="multi_img[{{ $img->id }}]">
                                    </td>
                                    <td>
                                        <input type="submit" class="btn btn-primary px-4" value="Update Image " />
                                        <a href="{{ route('vendor.product.multiimg.delete', $img->id) }}"
                                            class="btn btn-danger" id="delete"> Delete </a>

                                    </td>
                                </tr>
                            @endforeach

                        </form>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- /// End Update Multi Image  ////// -->
@endsection

@section('js')
    <script src="{{ asset('adminbackend/assets/plugins/input-tags/js/tagsinput.js') }}"></script>

    <script src='https://cdn.tiny.cloud/1/vdqx2klew412up5bcbpwivg1th6nrh3murc6maz8bukgos4v/tinymce/5/tinymce.min.js'
        referrerpolicy="origin"></script>

    <script>
        tinymce.init({
            selector: '#mytextarea'
        });
    </script>

    <script type="text/javascript">
        function mainThamUrl(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#mainThmb').attr('src', e.target.result).width(80).height(80);
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>


    <script>
        $(document).ready(function() {
            $('#multiImg').on('change', function() { //on file input change
                if (window.File && window.FileReader && window.FileList && window
                    .Blob) //check File API supported browser
                {
                    var data = $(this)[0].files; //this file data

                    $.each(data, function(index, file) { //loop though each file
                        if (/(\.|\/)(gif|jpe?g|png)$/i.test(file
                                .type)) { //check supported file type
                            var fRead = new FileReader(); //new filereader
                            fRead.onload = (function(file) { //trigger function on successful read
                                return function(e) {
                                    var img = $('<img/>').addClass('thumb').attr('src',
                                            e.target.result).width(100)
                                        .height(80); //create image element
                                    $('#preview_img').append(
                                        img); //append image to output element
                                };
                            })(file);
                            fRead.readAsDataURL(file); //URL representing the file's data.
                        }
                    });

                } else {
                    alert("Your browser doesn't support File API!"); //if File API is absent
                }
            });
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('select[name="category_id"]').on('change', function() {
                var category_id = $(this).val();
                if (category_id) {
                    $.ajax({
                        url: "{{ url('/vendor/subcategory/ajax') }}/" + category_id,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            $('select[name="subcategory_id"]').html('');
                            var d = $('select[name="subcategory_id"]').empty();
                            $.each(data, function(key, value) {
                                $('select[name="subcategory_id"]').append(
                                    '<option value="' + value.id + '">' + value
                                    .subcategory_name + '</option>');
                            });
                        },
                    });
                } else {
                    alert('danger');
                }
            });
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
@endsection
