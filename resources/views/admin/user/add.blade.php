@extends('admin.layout.main')
@section('title', $header['title'])

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-4 mt-2">
            <div class="col-sm-12 align-items-center d-flex breadcrumb-style">
                <h1 class="m-0">{{ $header['heading'] }}</h1>
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('adminUser.dashboard') </a></li>
                    <li class="breadcrumb-item"><a href="{{ route('user.index') }}">@lang('adminUser.moduleHeading')</a></li>
                    <li class="breadcrumb-item active">@lang('adminUser.addNew')</li>
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
                <form class="form row mb-0 pt-3 validate" action="{{ route('user.store') }}" enctype="multipart/form-data" method="post" id="dataForm" name="dataForm">
                    @csrf
                    <div class="col-md-6">
                        <div class="form-item form-float-style form-group">
                            <input type="text" name="fname" id="fname" class="first_name" autocomplete="off" required>
                            <label for="fname">@lang('adminUser.fullName') <span class="req-star">*</span></label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-item form-float-style form-group">
                            <input type="email" name="email" id="email" autocomplete="off" class="is-valid">
                            <label for="email">@lang('adminUser.emailAddress') <span class="req-star">*</span></label>
                            @error('email')
                            <span id="mobileno-error" class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="select top-space-rem after-drp form-item form-float-style">
                                    <select data-live-search="true" id="isd_code" name="isd_code" class="order-td-input selectpicker select-text height_drp is-valid">
                                        <option value="">Select ISD Code</option>
                                        @foreach($getIsdCode as $getIsdCodeName)
                                        <option value="{{ $getIsdCodeName->isd_code }}">
                                            {{ $getIsdCodeName->isd_code }}
                                            @foreach($getIsdCodeName->countryCode as $countries)
                                            {{ $countries->country_name }}@if(!$loop->last), @endif
                                            @endforeach
                                        </option>
                                        @endforeach
                                    </select>
                                    <label for="ISD Code" id="isd-code-customer">ISD Code<span class="req-star">*</span></label>
                                    @error('email')
                                    <span id="isd-error" class="error invalid-feedback-isd-code">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-item form-float-style form-group">
                                    <input type="text" name="mobile" id="mobile" onkeypress="return isNumber(event)" autocomplete="off" required>
                                    <label for="mobile">@lang('adminUser.mobileNumber') <span class="req-star">*</span></label>
                                    @error('mobile')
                                    <span id="mobileno-error" class="error invalid-feedback">{{ $message }}</span>
                                    @enderror
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
                            <div class="form-item form-float-style serach-rem mb-3">
                                <div class="select top-space-rem after-drp form-float-style ">
                                    <select data-live-search="true" name="role" id="role" class="order-td-input selectpicker select-text height_drp is-valid select-validate" required>
                                        <option value="" selected disabled>Select Role</option>
                                        <option value="SUPER_ADMIN">Super Admin</option>
                                        @foreach(App\Models\Role::select('name','code','role_type')->where('status','1')->get() as $role)
                                        @if($role->role_type == 'manager')
                                        <option value="{{ $role['code'] }}">{{ $role['name'] }}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                    <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">@lang('adminUser.selectRole') <span class="req-star">*</span></label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mt-3">
                        <div class="form-floating form-float-style form-group required mb-3">
                            <div class="form-item form-float-style">
                                @component('components.crop-image', [
                                'name' => 'profile_image',
                                'id' => 'profile_image',
                                'class' => 'file-upload is-valid image'

                                ])
                                @endcomponent
                                <label for="upload-profile">Uplaod Image</label>
                            </div>
                            <p class="upload-img-des mb-0">This image is visible in the supplier page.
                                Support jpg, jpeg, or png files.
                            </p>
                            <div id='profile_image_section'>
                                <img data-toggle="popover" id="croppedImagePreview" height="150" width="150" src="" alt="no-image" style="display: none;">
                                <label for="upload-profile">@lang('travellers.uploadProfileImage')</label>
                            </div>
                        </div>
                    </div>
            </div>
            <input type="hidden" id="croppedImage" name="croppedImage" value="">
            <div class="cards-btn">
                <button type="submit" id="disBtn" onclick="createUserFunction(event)" class="btn btn-success form-btn-success">@lang('adminUser.submit')</button>
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


<script>
    // Listen for file input change event

    $('.select-validate').on('change', function() {
        if ($(this).valid()) {
            // If the file is valid, remove the 'is-invalid' class
            $(this).removeClass('is-invalid');
            // Remove the 'invalid-feedback' element
            $(this).next('.invalid-feedback').remove();
        }
    });
    $("#isd_code").change(function() {
        var choice = jQuery(this).val();
        //if you just want to remove them all
        $("#isd_code-error").remove();

        if ($(this).val() == '') {
            $('#isd_code-error').show();
        } else {
            $('#isd_code-error').hide();
        }

    });

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

        $.validator.addMethod("email_regex", function(value, element, regexpr) {
            return this.optional(element) || regexpr.test(value);
        }, "Please enter a valid Email Address.");

        $.validator.addMethod("tenDigits", function(value, element) {
            return this.optional(element) || /^\d{10}$/.test(value);
        }, "Please enter exactly 10 digits.");

        $.validator.addMethod("mobileValidation", function(value, element) {
            var validator = this;
            var isValid = false;
            var isd_code = document.getElementById("isd_code").value;

            $.ajax({
                url: "{{route('admin.user.checkAdminUser')}}",
                method: "POST",
                data: {
                    mobile: value,
                    isd_code: isd_code,
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
                    mobileValidation: true,
                    noSpace: true,
                },
                email: {
                    required: true,
                    email: true,
                    email_regex: /\b[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b/i,

                    remote: {
                        url: "{{route('admin.user.checkEmailAgencyUserExist')}}",
                        type: "post",
                        data: {
                            email: function() {
                                return $("#email").val();
                            },
                            "_token": '{{ csrf_token() }}'
                        }
                    },
                    noSpace: true
                },

                role: {
                    required: true,
                    noSpace: true
                },
                isd_code: {
                    required: true,
                    noSpace: true,
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
                    required: "Please enter a Mobile Number",
                    minlength: "Please enter valid Mobile Number",
                    remote: "Mobile Number is already taken.",
                },
                email: {
                    required: "Plese enter an Email Address",
                    remote: "Email Address is already taken."
                },

                role: {
                    required: "Please select a Role",
                },

                isd_code: {
                    required: "Please select an ISD Code"
                }

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
            },
            submitHandler: function(form) {
                $("#disBtn").attr("disabled", true);
                form.submit();
            }
        });

    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
<style>
    .isd_code-error {
        display: none;
        width: 100%;
        margin-top: 0.25rem;
        font-size: .875em;
        color: #dc3545;
    }
</style>
@append