@extends('Admin.layout.app')
@section('title', 'Reset Password')
<style>
    .swal2-close:focus {
        box-shadow: none !important;
    }
</style>
@section('content')

<?php
$email = DB::table('password_resets')->where('token', $token)->get()->first();
?>
@if(empty($email))

@else

<p class="login-box-msg login-pera">Reset Password</p>

<form action="{{ route('admin.api-reset-password.save') }}" method="post" name="loginForm" id="loginForm">
    @csrf

    <input type="hidden" name="token" value="<?= $token ?>">
    <div class="input-group mt-3 mb-3">
        <input type="email" name="email" id="email" class="form-control" value="<?= $email->email ?>" placeholder="{{ __('Email') }}" disabled>
        <input type="hidden" name="email" value="{{$email->email}}">
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-envelope"></span>
            </div>
        </div>
    </div>
    <div class="input-group mb-3">
        <label for="newPassword">New Password</label>
        <div class="input-group">
            <input type="password" name="password" id="password" value="" class="form-control">
            <div class="input-group-append">
                <span toggle="#password-field" class="input-group-text "><i class="fa fa-fw fa-eye toggle-new-password"></i></span>
            </div>
        </div>
    </div>

    <div class="input-group mb-3">
        <label for="confirmNewPassword">Confirm New Password</label>
        <div class="input-group">
            <input type="password" name="confirm_password" id="confirm_password" value="" class="form-control">
            <div class="input-group-append">
                <span toggle="#password-field" class="input-group-text "><i class="fa fa-fw fa-eye field_icon toggle-confirm-password"></i></span>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            <button type="submit" id="btnSubmit" class="btn btn-primary btn-block">{{ __('Submit') }}</button>
        </div>

        <!-- /.col -->
    </div>
    @if(session()->has('error'))
    <div class="row mt-3">
        <div class="col-md-12 text-center alert alert-light text-danger" role="alert">
            {{ session()->get('error') }}
        </div>
    </div>
    @endif
</form>

<p class="mt-3 mb-0 forgote-link">
    <a href="{{ route('admin.login') }}">Login</a>
</p>

@endif
<script src="{{ URL::asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>

<script>
    var data = '<?php echo json_encode($email); ?>';
    data = JSON.parse(data);
    if (data == null) {
        $(function() {
            var Toast = Swal.mixin({
                showCloseButton: false,
                showConfirmButton: true,
            });
            Toast.fire({
                icon: 'success',
                title: "<strong>Thanks!</strong>",
                html: 'Password Reset Successfully.',
            });
        });
    }
    $('#modal-sm').show();
    $('#modal-sm').addClass('show');

    $(function() {
        $('#loginForm').validate({
            rules: {
                password: {
                    required: true,
                    minlength: 8,
                },
                confirm_password: {
                    required: true,
                    equalTo: "#password"
                },
            },
            messages: {
                password: {
                    required: "Please enter New Password",
                },
                confirm_password: {
                    required: "Please enter Confirm Password",
                    equalTo: "Confirm Password does not match"
                },

            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.input-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            },
            submitHandler: function(form) {
                $("#btnSubmit").attr("disabled", true);
                form.submit();
            }
        });

        $(document).on('click', '.toggle-new-password', function() {
            $(this).toggleClass("fa-eye fa-eye-slash");
            var input = $("#password");
            input.attr('type') === 'password' ? input.attr('type', 'text') : input.attr('type', 'password')
        });
        $(document).on('click', '.toggle-confirm-password', function() {
            $(this).toggleClass("fa-eye fa-eye-slash");
            var input = $("#confirm_password");
            input.attr('type') === 'password' ? input.attr('type', 'text') : input.attr('type', 'password')
        });
    });
</script>

@endsection