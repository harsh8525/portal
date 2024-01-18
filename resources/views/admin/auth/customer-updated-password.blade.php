@extends('admin.layout.app')
@section('title', $header['title'])
<style>
    .swal2-close:focus {
        box-shadow: none !important;
    }
</style>
@section('content')

<div class="bg-clr">
 
    <?php
    ?>
    <div class="logo-login">
        @php
        @$logo = App\Models\Setting::where('config_key', 'general|basic|colorLogo')->get('value')[0]['value'];
        @$value = App\Models\Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'];
        @endphp
        <a href="javascript:void(0)">
            @if($logo)
            <img src="{{@$logo}}" alt="">
            @elseif($value)
            <h1>{{ $value }}</h1>
            @else
            <img src="{{ URL::asset('assets/images/logo.png') }}" alt="">
            @endif
        </a>
    </div>
    <h2>@lang('resetPassword.resetPasswordHeading')</h2>
    @if (session('success'))
    <div class="alert alert-success" role="alert">
        <?php echo session('success'); ?>
    </div>
    @endif
</div>

<script src="{{ URL::asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
@endsection