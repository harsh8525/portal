@extends('admin.layout.main')
@section('title',$header['title'])

@section('content')

<!-- Include "cropper.js" CSS and JavaScript -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-4 mt-2">
            <div class="col-sm-12 d-flex breadcrumb-style">
                <h1 class="m-0">@lang('customers.addCustomers')</h1>
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('customers.dashboard') </a></li>
                    <li class="breadcrumb-item"><a href="{{ route('customers.index') }}">@lang('customers.moduleHeading')</a></li>
                    <li class="breadcrumb-item active">@lang('customers.add')</li>
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
            <div class="card pb-4 pt-3 px-3 w-100">
                <form method="post" action="{{route('customers.store')}}" id="dataForm" class="form row mb-0 validate" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="customer_id" name="customer_id" autocomplete="off">
                    <div class="brdr-btm row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="select top-space-rem after-drp form-item form-float-style">
                                        <select data-live-search="true" id="title" name="title" class="order-td-input selectpicker select-text height_drp is-valid">
                                            <option value="">Select Title</option>
                                            <option value="mr">Mr.</option>
                                            <option value="mrs">Mrs.</option>
                                            <option value="miss">Miss.</option>
                                        </select>
                                        <label for="title">Title <span class="req-star">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-item form-float-style">
                                        <input type="text" id="first_name" name="first_name" autocomplete="off" required class="is-valid">
                                        <label for="firstname">@lang('customers.firstName') <span class="req-star">*</span></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-item form-float-style">
                                <input type="text" id="last_name" name="last_name" autocomplete="off" required value="{{old('last_name')}}" class="is-valid">
                                <label for="lastname">@lang('customers.lastName') <span class="req-star">*</span></label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="select top-space-rem after-drp form-item form-float-style">
                                        <select data-live-search="true" id="isd_code" name="isd_code" class="order-td-input selectpicker select-text height_drp is-valid">
                                            <option value="">Select ISD Code</option>
                                            @foreach($getCountry as $getIsdCodeName)
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
                                    <div class="form-item form-float-style">
                                        <input type="text" id="mobile" name="mobile" autocomplete="off" required value="{{old('mobile')}}" onkeypress="return isNumber(event)" class="is-valid">
                                        <label for="mobile">@lang('customers.mobileNumber') <span class="req-star">*</span></label>
                                        @error('mobile')
                                        <span id="mobileno-error" class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-item form-float-style">
                                <input type="email" id="email" name="email" autocomplete="off" class="is-valid">
                                <label for="email">@lang('customers.emailAddress') <span class="req-star">*</span></label>
                                @error('email')
                                <span id="mobileno-error" class="error invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-item form-float-style">
                                <input type="text" class="datepicker is-valids is-valid" name="date_of_birth" id="date_of_birth" placeholder="YYYY-MM-dd" autocomplete="off" class="is-valid">
                                <label for="datepicker">Date Of Birth<span class="req-star">*</span></label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="select top-space-rem after-drp form-item form-float-style">
                                <select data-live-search="true" id="gender" name="gender" class="order-td-input selectpicker select-text height_drp is-valid">
                                    <option value="">Select Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                </select>
                                <label for="gender">Gender <span class="req-star">*</span></label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="select top-space-rem after-drp form-item form-float-style">
                                <select data-live-search="true" id="marital_status" name="marital_status" class="order-td-input selectpicker select-text height_drp is-valid">
                                    <option value="">Select Marital Status</option>
                                    <option value="married">Married</option>
                                    <option value="single">Single</option>
                                    <option value="other">Other</option>
                                </select>
                                <label for="marital_status">Marital Status <span class="req-star">*</span></label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-item form-float-style" id="marriage_aniversary_date_div">
                                <input type="text" class="datepicker is-valids is-valid" name="marriage_aniversary_date" id="marriage_aniversary_date" placeholder="YYYY-MM-dd" autocomplete="off">
                                <label for="marriage_aniversary_date">Marriage Aniversary Date</label>
                            </div>
                        </div>
                    </div>

                    <div class="row brdr-btm mt-3 mb-3">
                        <div class="discount">
                            <h5>@lang('customers.addressInformation')</h5>
                        </div>
                        <div class="col-md-6">
                            <div class="form-item form-float-style">
                                <input type="text" id="address1" name="address1" autocomplete="off" value="" class="is-valid">
                                <label for="address">@lang('customers.address1') <span class="req-star">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-item form-float-style">
                                <input type="text" id="address2" name="address2" autocomplete="off" value="" class="is-valid">
                                <label for="address">@lang('customers.address2')</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="select top-space-rem after-drp form-item form-float-style">
                                @component('components.customer_country_city_select', [
                                'name' => 'country_name',
                                'id' => 'country_code',
                                'class' => 'order-td-input selectpicker1 select-text height_drp is-valid select2',
                                'selected' => '',
                                'placeholder' => 'Select Country'
                                ])
                                @endcomponent
                                <label for="country" id="customer-country">@lang('customers.country') <span class="req-star">*</span></label>
                            </div>
                        </div>


                        <div class="col-md-6" id="component_div">
                            <div class="form-item form-float-style serach-rem mb-3">
                                <div class="select top-space-rem after-drp form-float-style">
                                @component('components.customer_state_city_select', [
                                'name' => 'state_code',
                                'id' => 'state_code',
                                'class' => 'order-td-input selectpicker1 select-text height_drp is-valid component_div',
                                'selected' => '',
                                'placeholder' => 'Select State'
                                ])
                                @endcomponent
                                    <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">State <span class="req-star">*</span></label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-item form-float-style serach-rem mb-3">
                                <div class="select top-space-rem after-drp form-float-style">
                                    <select data-live-search="true" id="city_code" name="city_code" class="order-td-input selectpicker1 select-text height_drp is-valid select2 select-validate" style="width: 100%;">
                                    </select>
                                    <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">City <span class="req-star">*</span></label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-item form-float-style mb-2">
                                <input type="number" id="pincode" name="pincode" autocomplete="off" value="" class="is-valid">
                                <label for="pincode">@lang('customers.pincode') <span class="req-star">*</span></label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-item form-float-style">

                                @component('components.crop-image', [
                                'name' => 'profile_photo',
                                'id' => 'profile_photo',
                                'class' => 'file-upload image is-valid'

                                ])
                                @endcomponent
                                <label for="upload-profile">@lang('customers.uploadProfileImage')</label>
                            </div>


                            <p class="upload-img-des mb-0">These images are visible in the customer page.
                                Support jpg, jpeg, or png files.
                            </p>
                            <div id='profile_image_section'>
                                <img data-toggle="popover" id="croppedImagePreview" height="150" width="150" src="" alt="no-image" style="display: none;">
                                <label for="upload-profile">@lang('customers.uploadProfileImage')</label>

                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating form-item mb-0">
                                <div class="form-item form-float-style serach-rem mb-0">
                                    <div class="select top-space-rem after-drp form-float-style ">
                                        <select data-live-search="true" id="status" name="status" class="order-td-input selectpicker select-text height_drp is-valid">
                                            <option value="active" selected="">@lang('customers.active')</option>
                                            <option value="inactive">@lang('customers.inActive')</option>
                                            <option value="terminated">@lang('customers.terminated')</option>

                                        </select>
                                        <label class="select-label searchable-drp">@lang('customers.status')</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" id="croppedImage" name="croppedImage" value="">


                    <div class="cards-btn mt-3">
                        <button type="submit" id="disBtn" class="btn btn-success form-btn-success">@lang('customers.submit')</button>
                        <a href="{{ route('customers.index') }}" type="button" class="btn btn-danger form-btn-danger">@lang('customers.cancel')</a>
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
   $("#city_code").select2({
    placeholder: "Select a city",
});
       
    </script>
<script>

    $(document).ready(function() {
        $('#isd_code').on('change', function() {
            var isdCode = $('#isd_code').val();
            $('#country_code').val() = isdCode;
            alert(isdCode); 

        })
    });
    $(document).ready(function() {
        const titleSelect = $('#title');
        let genderSelect = $('#gender');

        // Initialize selectpicker
        titleSelect.selectpicker();
        genderSelect.selectpicker();

        // Event listener for the title select
        titleSelect.on('change', function() {
            if ($(this).val() === 'mr') {
                genderSelect.selectpicker('val', 'male');
            } else if ($(this).val() === 'mrs' || $(this).val() === 'miss') {
                genderSelect.selectpicker('val', 'female');
            } else {
                genderSelect.selectpicker('val', '');
            }
            genderSelect.selectpicker('destroy');
            genderSelect = $('#gender');
            genderSelect.selectpicker();
        });
    });

   
    
    $(document).ready(function() {
        $("#marriage_aniversary_date_div").css('pointer-events', 'none');

        $('#marital_status').on('change', function() {
            var marital_status = $('#marital_status').val();
            if (marital_status == 'married') {
                $("#marriage_aniversary_date_div").css('pointer-events', 'auto');
            } else {
                $("#marriage_aniversary_date_div").css('pointer-events', 'none');
                document.getElementById('marriage_aniversary_date').value = '';
            }
           
        });
    });

    //make GST value uppercase
    $('#usergstno').keyup(function() {
        $(this).val($(this).val().toUpperCase());
    });
    $('#userpackeg-gst').keyup(function() {
        $(this).val($(this).val().toUpperCase());
    });

    //address readonly event script
    $('#searchAddressChecked').change(function() {
        if (this.checked) {
            $("#setting-search-add").attr('readonly', true);
            $("#address").attr('readonly', false);
            $("#country").attr('readonly', false);
            $("#state").attr('readonly', false);
            $("#city").attr('readonly', false);
            $("#pincode").attr('readonly', false);
        } else {
            $("#setting-search-add").attr('readonly', false);
            $("#address").attr('readonly', true);
            $("#country").attr('readonly', true);
            $("#state").attr('readonly', true);
            $("#city").attr('readonly', true);
            $("#pincode").attr('readonly', true);
        }
    });


    jQuery.validator.addMethod("lettersonly", function(value, element) {
        return this.optional(element) || /^[a-z ]+$/i.test(value);
    }, "Letters only please");
    $.validator.addMethod("email_regex", function(value, element, regexpr) {
        return this.optional(element) || regexpr.test(value);
    }, "Please enter a valid Email.");

    $.validator.addMethod("validateUserMobile", function(value, element) {
        var data = {
                "_token": '{{ csrf_token() }}',
                "mobile": value
            },
            eReport = ''; //error report

        $.ajax({
            type: "POST",
            url: "{{route('admin.customers.checkCustomerMobileExist')}}",
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
    $("#isd_code").change(function() {
        var choice = jQuery(this).val();

        $("#isd_code-error").remove();

        if ($(this).val() == '') {
            $('#isd_code-error').show();
        } else {
            $('#isd_code-error').hide();
        }

    });
    $("#slect_finish").change(function() {
        var choice = jQuery(this).val();

        $("#slect_finish-error").remove();

        if ($(this).val() == '') {
            $('#slect_finish-error').show();
        } else {
            $('#slect_finish-error').hide();
        }

    });

    $.validator.addMethod("tenDigits", function(value, element) {
        return this.optional(element) || /^\d{10}$/.test(value);
    }, "Please enter exactly 10 digits.");

    $.validator.addMethod("mobileValidation", function(value, element) {
        var validator = this;
        var isValid = false;
        var isd_code = document.getElementById("isd_code").value;


        $.ajax({
            url: "{{route('admin.customers.checkCustomerMobileExist')}}",
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
    $.validator.addMethod("birthday", function(value, element) {
        var birthDate = $("#date_of_birth").val();

        // Perform date comparison
        return new Date(birthDate) <= new Date(); // Ensure birth date is not in the future
    }, "Date Of Birth must be a valid date and not in the future.");
    $.validator.addMethod("marriageAniversaryDate", function(value, element) {
        if ($("#marital_status").val() == 'married') {
            var birthDate = $("#marriage_aniversary_date").val();

            return new Date(birthDate) <= new Date(); // Ensure birth date is not in the future
        } else {
            return true;
        }

        // Perform date comparison
    }, "Marriage Aniversary Date must be a valid date and not in the future.");
    $(function() {
        $('.datepicker').datepicker({
            dateFormat: 'yy-mm-dd',
            changeYear: true,
            yearRange: '1900:C', // Specify a range of selectable years

        });

        //jquery Form validation
        $('#dataForm').validate({
            onkeyup: false,
            rules: {
                title: {
                    required: true,
                },
                gender: {
                    required: true,
                },
                marital_status: {
                    required: true,
                },
                marriage_aniversary_date: {
                    marriageAniversaryDate: true,
                },
                first_name: {
                    noSpace: true,
                    maxlength: 100,
                },
                last_name: {
                    required: true,
                    noSpace: true,
                },
                mobile: {
                    required: true,
                    noSpace: true,
                    mobileValidation: true,
                },
                email: {
                    required: true,
                    noSpace: true,
                    email: true,
                    email_regex: /\b[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b/i,
                    remote: {
                        url: "{{route('admin.customers.checkEmailUserExist')}}",
                        type: "post",
                        data: {
                            email: function() {
                                return $("#email").val();
                            },

                            "_token": '{{ csrf_token() }}'
                        }
                    }
                },

                address1: {
                    noSpace: true,
                    required: true,
                },
                country: {
                    noSpace: true,
                    required: true,
                },
                state_code: {
                    noSpace: true,
                    required: true,
                },
                city_code: {
                    noSpace: true,
                    required: true,
                },
                isd_code: {
                    noSpace: true,
                    required: true

                },
                country_name: {
                    required: true
                },
                pincode: {
                    noSpace: true,
                    required: true,
                    digits: true
                },
                date_of_birth: {
                    required: true,
                    birthday: true,
                },
                profile_photo: {
                    accept: "image/jpg,image/jpeg,image/png",
                    maxsize: 1000000,
                },
            },


            messages: {
                title: {
                    required: "Please select a Title"
                },
                gender: {
                    required: "Please select a Gender"
                },
                marital_status: {
                    required: "Please select a Marital Status"
                },
                marriage_aniversary_date: {
                    required: "Please select a Marriage Aniversary Date"
                },
                first_name: {
                    required: "Please enter a First Name"

                },
                last_name: {
                    required: "Please enter a Last Name"
                },
                mobile: {
                    required: "Please enter a Mobile Number",
                    minlength: "Please enter valid Mobile Number",
                    remote: "Mobile Number is already taken",
                },
                isd_code: {
                    required: "Please Select an ISD Code"
                },
                email: {
                    required: "Please enter an Email",
                    email: "Please enter a valid Email",
                    remote: "Email is already taken"
                },
                profile_photo: {
                    accept: "Please select image format must be .jpg, .jpeg or .png.",
                    maxsize: "Please upload image size less than 1MB"

                },
                address1: {
                    required: "Please enter an Address1"
                },
                country: {
                    required: "Please select a Country"
                },
                state: {
                    required: "Please select a State"
                },
                city_code: {
                    required: "Please select a City"
                },
                country_name: {
                    required: "Please select a Country",
                },
                pincode: {
                    required: "Please enter a Zip code"
                },
                date_of_birth: {
                    required: "Please enter a Date Of Birth"
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