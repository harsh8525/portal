@extends('admin.layout.app')
@section('title', $header['title'])
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
    <?php
    ?>
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
    <h2>@lang('resetPassword.resetPasswordHeading')</h2>
    <p>@lang('resetPassword.description')
    </p>
    <div class="px-3 col-md-12">
        <li style="color:black;font-size: small;">Minimum length should be {{App\Models\Setting::where('config_key','passwordSecurity|minimumPasswordLength')->get('value')[0]['value']}}</li>
        <li style="color:black;font-size: small;">At least {{App\Models\Setting::where('config_key','passwordSecurity|uppercaseCharacter')->get('value')[0]['value']}} upper case characters</li>
        <li style="color:black;font-size: small;">At least {{App\Models\Setting::where('config_key','passwordSecurity|lowercaseCharacter')->get('value')[0]['value']}} lower case characters</li>
        <li style="color:black;font-size: small;">At least {{App\Models\Setting::where('config_key','passwordSecurity|numericCharacter')->get('value')[0]['value']}} numeric characters</li>
        <li style="color:black;font-size: small;">At least {{App\Models\Setting::where('config_key','passwordSecurity|specialCharacter')->get('value')[0]['value']}} special characters</li>
        <li style="color:black;font-size: small;">At least {{App\Models\Setting::where('config_key','passwordSecurity|alphanumericCharacter')->get('value')[0]['value']}} alphanumeric characters</li>
    </div>
    <form action="{{ route('admin.customer-reset-password.save') }}" class="form row mx-0 validate" method="post" name="dataForm" id="dataForm">
        @csrf
        <input type="hidden" name="email" value="{{$email}}">
        <div class="login-page p-0">
            <div class="form-item form-float-style">
                <input type="password" id="password" name="password" class="is-valid" autocomplete="off">
                <label for="newpswrd">@lang('resetPassword.password')</label>
                <span toggle="#password-field" class="fa fa-fw fa-eye field_icon toggle-password icon-view"></span>
            </div>
            <div class="form-item  form-float-style">
                <input type="password" id="confirm_pass" class="input-password id-valid" name="confirm_password" autocomplete="off">
                <label for="confirm-pswrd5">@lang('resetPassword.confirmPassword')</label>

                <span toggle="#password-field" class="fa fa-fw fa-eye field_icon toggle-confirm-password icon-view"></span>
            </div>
        </div>
        <div class="login-bottom">
            <button type="submit" id="disBtn" class="btn login-btn">@lang('resetPassword.resetPassword')</button>
        </div>
    </form>
</div>

<script src="{{ URL::asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script>
    jQuery(document).ready(function() {
        jQuery.validator.addMethod("lettersonly", function(value, element) {
            return this.optional(element) || /^[a-z ]+$/i.test(value);
        }, "Letters only please");
        $.validator.addMethod("passwordValidation", function(value, element) {
            var validator = this;
            var isValid = false;
            $.ajax({
                url: "{{route('admin.reset-password.validate')}}",
                method: "POST",
                data: {
                    password: value,
                    _token: '{{ csrf_token() }}'
                },
                async: false,
                success: function(response) {
                    if (response.valid === false) {
                        isValid = false;
                        validator.settings.messages[element.name].passwordValidation = response.message;
                    } else {
                        isValid = true;
                    }
                }
            });
            return isValid;
        }, "");
        $(function() {
            //jquery Form validation
            $('*[value=""]').removeClass('is-valid');
            $('#dataForm').validate({
                rules: {
                    password: {
                        required: true,
                        passwordValidation: true
                    },
                    confirm_password: {
                        required: function(element) {
                            return $("#password").val() != "";
                        },
                        equalTo: "#password"
                    },
                },
                messages: {
                    password: {
                        required: "Please enter a New Password",
                    },
                    confirm_password: {
                        required: "Please enter a Confirm Password",
                        equalTo: "Confirm Password does not match"
                    },
                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-item').append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                },
                submitHandler: function(form) {
                    $("#disBtn").attr("disabled", true);
                    form.submit();
                }
            });
        });
        // INCLUDE JQUERY & JQUERY UI 1.12.1
        $(function() {
            $("#datepicker").datepicker({
                dateFormat: "dd-mm-yy",
                duration: "fast"
            });
        });
    });
</script>

<script>
    $("body").on('click', '.toggle-confirm-password', function() {
        $(this).toggleClass("fa-eye fa-eye-slash");
        var input = $("#confirm_pass");
        if (input.attr("type") === "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }

    });
    $("body").on('click', '.toggle-password', function() {
        $(this).toggleClass("fa-eye fa-eye-slash");
        var input = $("#password");
        if (input.attr("type") === "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }

    });
</script>
@endsection