@extends('admin.layout.app')
@section('title', @trans('login.title'))
<style>
    .swal2-close:focus {
        box-shadow: none !important;
    }
</style>
@section('content')
<div class="bg-clr">
    @if (session('success'))
    <div class="alert alert-success" role="alert">
        <?php echo session('success'); ?>
    </div>
    @endif
    @if (session('error'))
    <div class="alert alert-danger" role="alert">
        {{ session('error') }}
    </div>
    @endif
    @if (isset($error))
    <div class="alert alert-danger" role="alert">
        {{ $error }}
    </div>
    @endif
    <div class="logo-login">
        @php
        @$logo = App\Models\Setting::where('config_key', 'general|basic|colorLogo')->get('value')[0]['value'];
        @$value = App\Models\Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'];
        @endphp
        <a href="{{ route('admin.login') }}">
            @if($logo)
            <img src="{{@$logo}}" alt="">
            @elseif($value)
            <h1>{{ $value }}</h1>
            @else
            <img src="{{ URL::asset('assets/images/logo.png') }}" alt="">
            @endif
        </a>
    </div>
    <h2>@lang('login.loginHeading')</h2>

    <form action="{{ route('adminLogin') }}" method="post" class="form row mx-0 validate" name="loginForm" id="loginForm">
        @csrf


        <!--Errors variable used from form validation -->
        @if($errors->any())
        <div class="alert alert-light text-danger">
            <ul>
                @foreach($errors->all() as $error)
                <li class="text-red">{{$error}}</li>
                @endforeach
            </ul>
        </div>
        @endif



        <div class="login-page p-0">
            <div class="form-item form-float-style form-group">
                <input type="text" id="login_mobileno" class="is-valid" name="mobile" autocomplete="off" required>
                <label for="login_mobileno">Email Address</label>
            </div>
            <div class="form-item form-float-style form-group">
                <input type="password" id="login_password" class="is-valid" name="password" autocomplete="off" required>
                <label for="login_password">@lang('login.password')</label>
            </div>
            <div class="form-check filter-check sub-check mt-2">
                <input class="form-check-input" type="checkbox" id="flexCheckDefault1">
                <label class="form-check-label" for="flexCheckDefault1">
                    @lang('login.rememberMe')
                </label>
            </div>
        </div>
        @if(session()->has('error'))
        <div class="row mt-3">
            <div class="col-md-12 text-center alert alert-light text-danger" role="alert">
                {{ session()->get('error') }}
            </div>
        </div>
        @endif
        @if (session('login-success'))
        <div class="row mt-3">
            <div class="col-md-12 alert alert-success" role="alert">
                <button class="close" data-dismiss="alert">x</button>
                {{ session('login-success') }}
            </div>
        </div>
        @endif
        <div class="login-bottom">
            <button type="submit" class="btn login-btn">@lang('login.login')</button>
            <a href="{{ route('admin.forgot-password') }}" type="submit" class="btn forgot-btn">@lang('login.forgotPassword')</a>
        </div>
    </form>

</div>
<script>
    $(function() {
        $.validator.addMethod("email_regex", function(value, element, regexpr) {
            return this.optional(element) || regexpr.test(value);
        }, "Please enter a valid Email Address.");

        $('#loginForm').validate({
            rules: {
                mobile: {
                    required: true,
                    email: true,
                    email_regex: /\b[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b/i

                },
                password: {
                    required: true,
                },
            },
            messages: {
                mobile: {
                    required: "Please enter an Email Address",
                },
                password: {
                    required: "Please enter a Password"
                },
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>

@endsection