@extends('admin.admin_dashboard')
@section('title', 'Edit Coupone')
@section('admin')


    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Edit Coupon </div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Coupon </li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">

            </div>
        </div>
        <!--end breadcrumb-->
        <div class="container">
            <div class="main-body">
                <div class="row">

                    <div class="col-lg-10">
                        <div class="card">
                            <div class="card-body">

                                <form id="myForm" method="post" action="{{ route('update.coupon') }}">
                                    @csrf

                                    <input type="hidden" name="id" value="{{ $coupon->id }}">

                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">Coupon Name</h6>
                                        </div>
                                        <div class="form-group col-sm-9 text-secondary">
                                            <input type="text" name="coupon_name" class="form-control"
                                                value="{{ $coupon->coupon_name }}" />
                                                <div style="color: red">
                                                    @error('coupon_name')
                                                        {{ $message }}
                                                    @enderror
                                                </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">Coupon Discount(%)</h6>
                                        </div>
                                        <div class="form-group col-sm-9 text-secondary">
                                            <input type="text" name="coupon_discount" class="form-control"
                                                value="{{ $coupon->coupon_discount }}" />
                                                <div style="color: red">
                                                    @error('coupon_discount')
                                                        {{ $message }}
                                                    @enderror
                                                </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">Coupon Validity Date</h6>
                                        </div>
                                        <div class="form-group col-sm-9 text-secondary">
                                            <input type="date" name="coupon_validity" class="form-control"
                                                min="{{ Carbon\Carbon::now()->format('Y-m-d') }}"
                                                value="{{ $coupon->coupon_validity }}" />
                                                <div style="color: red">
                                                    @error('coupon_validity')
                                                        {{ $message }}
                                                    @enderror
                                                </div>
                                        </div>
                                    </div>




                                    <div class="row">
                                        <div class="col-sm-3"></div>
                                        <div class="col-sm-9 text-secondary">
                                            <input type="submit" class="btn btn-primary px-4" value="Save Changes" />
                                        </div>
                                    </div>
                            </div>

                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
