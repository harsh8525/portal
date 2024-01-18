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
            <form id="dataForm" name="dataForm" class="paymentWidgets" data-brands="VISA AMEX MADA MASTER STC_PAY SADAD_VA TABBY" action="{{ route('checkout-payment-status') }}" enctype="multipart/form-data" method="post" >
            @csrf
            </form>
            <form id="dataForm" name="dataForm" class="paymentWidgets" data-brands="APPLEPAY APPLEPAYTKN" action="{{ route('checkout-payment-status') }}" enctype="multipart/form-data" method="post" >
            @csrf
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
<script async crossorigin src="https://applepay.cdn-apple.com/jsapi/v1.1.0/apple-pay-sdk.js"></script>
<script>

// var wpwlOptions = {
//     style:"card",
//     countryCode: "SA",
//     supportedNetworks: ["stcPay","masterCard", "visa", "mada", "amex", "sadadVa", "tabby"],
// }
// var wpwlOptions = {
//     style: "card",
//     countryCode: "SA",
//     supportedNetworks: ["stcPay", "masterCard", "visa", "mada", "amex", "sadadVa", "tabby", "sadad"],
//     currencyCode: "SAR", // Add the currency code (e.g., SAR for Saudi Riyal)
// }

// var wpwlOptions = {
//   applePay: {
//     displayName: "MyStore",
//     total: { label: "COMPANY, INC." }
//   }
// }

// var wpwlOptions = {
//     "supportedMethods": "https://apple.com/apple-pay",
//     "data": {
//         "version": 3,
//         "merchantIdentifier": "merchant.com.traveldemo.tour",
//         "merchantCapabilities": [
//             "supports3DS"
//         ],
//         "supportedNetworks": [
//             "amex",
//             "discover",
//             "masterCard",
//             "visa"
//         ],
//         "countryCode": "US"
//     }
// }

var wpwlOptions = {
    applePay : {
        displayName: "MyStore",
        total: { label: "COMPANY, INC." },
        merchantCapabilities:["supports3DS"],
        merchantIdentifier: ["merchant.com.traveldemo.tour"],
        supportedNetworks: ["amex","masterCard", "visa","discover"],
        countryCode: "US",
    }
}

// var wpwlOptions= {
//     applePay : {
//         displayName: "MyStore",
//         total: { label: "COMPANY, INC." },
//         merchantCapabilities:["supports3DS"],
//         supportedNetworks: ["masterCard", "visa", "mada"],
//         countryCode: "IN",
//     }
// }
 
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>

@append