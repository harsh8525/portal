@extends('admin.layout.main')
@section('title',$header['title'])

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-4 mt-2">
            <div class="col-sm-12 align-items-center d-flex breadcrumb-style">
                <h1 class="m-0">{{ $header['heading'] }}</h1>
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('adminUser.dashboard') </a></li>
                    <li class="breadcrumb-item active"><a href="{{ route('user.index') }}">@lang('adminUser.moduleHeading')</a></li>
                    <li class="breadcrumb-item active">@lang('adminUser.edit')</li>
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
            <div class="card pb-4 w-100 px-3 py-2">
                <form method="post" action="{{route('user.update',$userDetail['id'])}}" id="dataForm" class="form row mb-0 validate" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="redirects_to" id="redirects_to" value="{{ URL::previous() }}">
                    <input type="hidden" name="admin_user_id" id="admin_user_id" value="{{$userDetail['id']}}" />
                    <div class="col-md-6">
                        <div class="form-item form-float-style form-group">
                            <input type="text" name="fname" id="fname" class="is-valid" value="{{$userDetail['name']}}" autocomplete="off" required>
                            <label for="fname">@lang('adminUser.fullName') <span class="req-star">*</span></label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-item form-float-style form-group">
                            <input type="email" name="email" id="email" class="is-valid" value="{{$userDetail['email']}}" autocomplete="off" required>
                            <label for="email">@lang('adminUser.emailAddress') <span class="req-star">*</span></label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="select top-space-rem after-drp form-item form-float-style">
                                    <select data-live-search="true" id="isd_code" name="isd_code" class="order-td-input selectpicker select-text height_drp is-valid">
                                        <option value="">Select Option</option>
                                        @foreach($getIsdCode as $getIsdCodeName)
                                        <option value="{{ $getIsdCodeName->isd_code }}" @if ($getIsdCodeName->isd_code== $userDetail->isd )
                                            {{'selected="selected"'}}
                                            @endif>{{ $getIsdCodeName->isd_code }}
                                            @foreach($getIsdCodeName->countryCode as $countries)
                                            {{ $countries->country_name }}@if(!$loop->last), @endif
                                            @endforeach
                                        </option>
                                        @endforeach
                                    </select>
                                    <label for="isd_code" id="isd-code-customer">ISD Code<span class="req-star">*</span></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-item form-float-style form-group">
                                    <input type="text" name="mobile" id="mobile" onkeypress="return isNumber(event)" value="{{$userDetail['mobile']}}" autocomplete="off" required>
                                    <label for="mobile">@lang('adminUser.mobileNumber') <span class="req-star">*</span></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="col-md-6">
                        <div class="form-item form-float-style form-group">
                            <input type="email" name="email" id="email" class="is-valid" value="{{$userDetail['email']}}" autocomplete="off" required>
                            <label for="email">@lang('adminUser.emailAddress') <span class="req-star">*</span></label>
                        </div>
                    </div> -->
                    <div class="col-md-6">
                        <div class="form-floating form-float-style form-group required mb-3">
                            <div class="form-item form-float-style serach-rem mb-3">
                                <div class="select top-space-rem after-drp form-float-style ">
                                    <select data-live-search="true" name="role" id="role" class="order-td-input selectpicker select-text height_drp is-valid" required>
                                        <option value="" selected disabled>Select Role</option>
                                        @if($userDetail['app_name'] == 'managerapp' )
                                        <option value="SUPER_ADMIN" selected>SUPER ADMIN</option>
                                        @endif
                                        @foreach( $getRole as $role)
                                        <option value="{{ $role['code'] }}" @if($role['code']==$userDetail['role_code']) {{'selected="selected"'}} @endif>{{ $role['name'] }}
                                        </option>
                                        @endforeach
                                    </select>
                                    <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">@lang('adminUser.selectRole') <span class="req-star">*</span></label>
                                </div>
                            </div>
                        </div>
                    </div>
                 
                    <div class="col-md-6 d-none">
                        <div class="select top-space-rem after-drp form-item form-float-style">
                            @component('components.country_city_select', [
                            'name' => 'nationality_id',
                            'id' => 'country_code',
                            'class' => 'order-td-input selectpicker1 select-text height_drp is-valid select2',
                            'selected' => '',
                            'placeholder' => 'Select Nationality'
                            ])
                            @endcomponent
                            <label for="country" id="customer-country">@lang('travellers.nationality') <span class="req-star">*</span></label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating form-float-style form-group required mb-3">
                            <div class="form-item form-float-style">
                                <input type="hidden" id="old_photo" name="old_photo" class="file-upload" autocomplete="off" class="is-valid" value="{{ $userDetail['profile_photo'] }}">
                                @component('components.crop-image', [
                                'name' => 'profile_image',
                                'id' => 'profile_image',
                                'class' => 'file-upload is-valid image'

                                ])
                                @endcomponent
                                <p class="upload-img-des mb-0">These images are visible in the user page.
                                    Support jpg, jpeg, or png files.
                                </p>

                                <div id='profile_image_section'>
                                    <img data-toggle="popover" id="croppedImagePreview" height="150" width="150" src="{{ $userDetail['profile_image'] ?: URL::asset('assets/images/no-image.png')}}" alt="">
                                    <label for="upload-profile">Upload Profile Image</label>

                                </div>
                                <input type="hidden" id="croppedImage" name="croppedImage" value="">
                            </div>

                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating form-float-style form-group required mb-3">
                            <div class="form-item form-float-style serach-rem mb-3">
                                <div class="select top-space-rem after-drp form-float-style ">
                                    <select data-live-search="true" id="slect_finish" name="status" class="order-td-input selectpicker select-text height_drp is-valid">
                                        <option @if($userDetail['status']=='1' ) selected="selected" @endif value="1" selected="">Active</option>
                                        <option @if($userDetail['status']=='0' ) selected="selected" @endif value="0">In-Active</option>
                                    </select>
                                    <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">@lang('adminUser.status')</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="cards-btn">
                        <button type="submit" class="btn btn-success form-btn-success">@lang('adminUser.submit')</button>
                        <a href="{{ route('user.index') }}" class="btn btn-danger form-btn-danger">@lang('adminUser.cancel')</a>
                    </div>
                </form>
            </div>
            <!-- /.row -->
        </div>
        <!--/. container-fluid -->
</section>
@endsection
@section('js')

<!-- Page specific script -->

<script>
    $(function() {
        $.validator.addMethod("validateUserMobile", function(value, element) {
            var data = {
                    "_token": '{{ csrf_token() }}',
                    "mobile": value
                },
                eReport = ''; //error report

            $.ajax({
                type: "POST",
                url: "{{route('admin.user.checkAdminUser')}}",
                dataType: "json",
                data: data,
                success: function(data) {
                    if (data !== 'true') {
                        return false;
                    } else {
                        return true;
                    }
                },
                error: function(xhr, textStatus, errorThrown) {
                    return false;
                }
            });

        }, 'already taken');

        $.validator.addMethod("tenDigits", function(value, element) {
            return this.optional(element) || /^\d{10}$/.test(value);
        }, "Please enter exactly 10 digits.");

        $.validator.addMethod("email_regex", function(value, element, regexpr) {
            return this.optional(element) || regexpr.test(value);
        }, "Please enter a valid Email Address.");

        //on change profile set old_profile_image blank
        $('#upload-profile').change(function() {
            $("#old_profile_image").val('');
        });

        //remove profile image
     
        $.validator.addMethod("mobileValidation", function(value, element) {
            var validator = this;
            var isValid = false;
            var isd_code = document.getElementById("isd_code").value;
            var admin_user_id = document.getElementById("admin_user_id").value;

            $.ajax({
                url: "{{route('admin.user.checkAdminUser')}}",
                method: "POST",
                data: {
                    mobile: value,
                    isd_code: isd_code,
                    admin_user_id: admin_user_id,
                    _token: '{{ csrf_token() }}'
                },
                async: false,
                success: function(response) {
                    if (response.valid === false) {
                        isValid = false;
                        validator.settings.messages[element.name].mobileValidation = response.message;
                    } else {
                        isValid = true;
                    }

                }
            });

            return isValid;
        }, "");
        $('*[value=""]').removeClass('is-valid');
  
        $('#dataForm').validate({
            rules: {
                fname: {
                    required: true,
                    lettersonly: true,
                    noSpace: true
                },
                profile_image: {
                    accept: "image/jpg,image/jpeg,image/png",
                    maxsize: 1000000,
                },
                mobile: {
                    required: true,
                    digits: true,
                    noSpace: true,
                    mobileValidation: true,
                },
                email: {
                    required: true,
                    email: true,
                    noSpace: true,
                    email_regex: /\b[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b/i,
                    remote: {
                        url: "{{route('admin.user.checkEmailAgencyUserExist')}}",
                        type: "post",
                        data: {
                            email: function() {
                                return $("#email").val();
                            },
                            admin_user_id: function() {
                                return $("#admin_user_id").val();
                            },
                            "_token": '{{ csrf_token() }}'
                        }
                    }

                },
                role: {
                    required: true,
                    noSpace: true
                }

            },
            messages: {
                fname: {
                    required: "Please enter a Full Name",
                },
                profile_image: {
                    extension: "Please select image format must be .jpg, .jpeg or .png",
                    maxsize: "Please upload image size less than 1MB"

                },
                mobile: {
                    required: "Please enter Mobile Number",
                    minlength: "Please enter valid Mobile Number",
                    remote: "Mobile Number is already taken."

                },
                email: {
                    required: "Plese enter an Email Address",
                    remote: "Email address is already taken."
                },
                password: {
                    required: "Please generate your Password",
                },
                role: {
                    required: "Please select a Role",
                }

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
            }
        });

    });

    var password = document.getElementById("gen_pass");

    function genPassword() {
        var chars = "0123456789abcdefghijklmnopqrstuvwxyz!@#$%^&*()ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        var passwordLength = 12;
        var password = "";
        for (var i = 0; i <= passwordLength; i++) {
            var randomNumber = Math.floor(Math.random() * chars.length);
            password += chars.substring(randomNumber, randomNumber + 1);
        }
        document.getElementById("gen_pass").value = password;
    }

    function copyPassword() {
        var copyText = document.getElementById("gen_pass");
        copyText.select();
        document.execCommand("copy");
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>

@append