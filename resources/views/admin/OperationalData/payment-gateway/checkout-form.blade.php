@extends('admin.layout.main')
@section('title', $header['title'])

@section('content')
<style>
    body {background-color:#f6f6f5;}
</style>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-4 mt-2">
            <div class="col-sm-12 align-items-center d-flex breadcrumb-style">
                <h1 class="m-0">{{ $header['heading'] }}</h1>
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('adminUser.dashboard') </a></li>
                    <li class="breadcrumb-item"><a href="{{ route('payment-gateway.index') }}">Payment Gateway </a></li>
                    <li class="breadcrumb-item active">Add</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- Info boxes -->
        <div class="row">
            <form id="dataForm" name="dataForm" class="paymentWidgets" data-brands="VISA MASTER AMEX MADA" action="{{ route('checkout-payment') }}" enctype="multipart/form-data" method="post" >
                @csrf
            <div class="card pb-4 w-100 px-3 py-2">
                <div class="col-md-12 row">
                    <div div class="col-md-6">
                        <div class="form-item form-float-style form-group">
                            <input name="amount" type="text" id="amount" autocomplete="off" required value="">
                            <label for="amount">Amount <span class="req-star">*</span></label>
                        </div>
                    </div>
                </div>
                </div>
                    <div class="cards-btn">
                        <button type="submit" id="disBtn" class="btn btn-success form-btn-success">Pay</button>
                    </div>
                </form>
            </div>
            </div>
            <!-- /.row -->
        </div>
        <!--/. container-fluid -->
</section>
@endsection
@section('js')
@if(isset($checkoutId))
    <script src="https://eu-test.oppwa.com/v1/paymentWidgets.js?checkoutId={{ $checkoutId }}"></script>
@endif
<script>

var wpwlOptions = {style:"card"}
 
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>

@append