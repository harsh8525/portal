@extends('admin.layout.app')
@section('title', $header['title'])

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
    <h2>Forgot Password :(</h2>
    <p>@lang('forgotPassword.description')
    </p>
    <form action="{{ route('admin.forgot-password.save') }}" method="post" class="form row mx-0 validate" name="forgotPass" id="forgotPass">
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
                <input type="text" id="mobileno" name="mobile" autocomplete="off" class="is-valid">
                <label for="mobileno">@lang('forgotPassword.mobileNumber')</label>
            </div>
        </div>
        <div class="login-bottom">
            <button type="submit" class="btn login-btn">@lang('forgotPassword.continue')</button>
            <a href="{{ route('admin.login') }}" type="submit" class="btn forgot-btn">@lang('forgotPassword.login')</a>
        </div>

    </form>
</div>
<script>
    $(document).ready(function() {
        $.validator.addMethod("email_regex", function(value, element, regexpr) {
            return this.optional(element) || regexpr.test(value);
        }, "Please enter a valid Email Address.");

        $('#forgotPass').validate({
            rules: {
                mobile: {
                    required: true,
                    email: true,
                    email_regex: /\b[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b/i
                },

            },
            messages: {
                mobile: {
                    required: "Please enter an Email Address",
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