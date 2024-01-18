@extends('admin.layout.main')
@section('title',$header['title'])

@section('content')
<!-- Include "cropper.js" CSS and JavaScript -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-4 mt-2">
            <div class="col-sm-12 d-flex breadcrumb-style">
                <h1 class="m-0">@lang('customers.moduleHeading')</h1>
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('travellers.dashboard') </a></li>
                    <li class="breadcrumb-item"><a href="{{ route('customers.index') }}">@lang('customers.moduleHeading') </a></li>
                    <li class="breadcrumb-item"><a href="{{ route('travellers.index',['customer_id' => $customerId]) }}">@lang('travellers.moduleHeading')</a></li>
                    <li class="breadcrumb-item active">@lang('travellers.add')</li>
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
        <h4 class="fw-bold">
            @lang('Travellers - Add')
        </h4>
        <div class="row">
            <div class="card pb-4 pt-3 px-3 w-100">
                <form method="post" action="{{route('travellers.store')}}" id="dataForm" class="form row mb-0 validate" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="customer_id" name="customer_id" value="{{ $customerId }}" autocomplete="off">
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
                                        <input type="text" id="first_name" placeholder="Enter @lang('travellers.firstName')" name="first_name" autocomplete="off" required class="is-valid">
                                        <label for="firstname">@lang('travellers.firstName') <span class="req-star">*</span></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-item form-float-style">
                                <input type="text" id="second_name" placeholder="Enter @lang('travellers.secondName')" name="second_name" autocomplete="off" value="{{old('secound_name')}}" class="is-valid">
                                <label for="secoundname">@lang('travellers.secondName') </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-item form-float-style">
                                <input type="text" id="last_name" name="last_name" placeholder="Enter @lang('travellers.lastName')" autocomplete="off" required value="{{old('last_name')}}" class="is-valid">
                                <label for="lastname">@lang('travellers.lastName') <span class="req-star">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-item form-float-style">
                                <input type="text" class="datepicker is-valids is-valid" name="date_of_birth" id="date_of_birth" placeholder="dd/MM/YYYY" autocomplete="off" class="is-valid">
                                <label for="datepicker">Date Of Birth<span class="req-star">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="select top-space-rem after-drp form-item form-float-style">
                                <select data-live-search="true" id="gender" name="gender" class="order-td-input selectpicker select-text height_drp is-valid">
                                    <option value="">Select Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <!-- <option value="other">Other</option> -->
                                </select>
                                <label for="gender">Gender <span class="req-star">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-6">
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
                            <div class="select top-space-rem after-drp form-item form-float-style">
                                <select data-live-search="true" id="id_type" name="id_type" class="order-td-input selectpicker select-text height_drp is-valid">
                                    <option value="">Select ID Type</option>
                                    <option value="passport">Passport</option>
                                    <option value="national_id">National ID</option>
                                </select>
                                <label for="id_type">@lang('travellers.idType') <span class="req-star">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-item form-float-style">
                                <input type="text" id="id_number" placeholder="Enter ID Number (Passport/National ID)" name="id_number" class="is-valid" autocomplete="off" required>
                                <label for="firmname">@lang('travellers.idNumber')<span class="req-star">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-item form-float-style">
                                <input type="text" placeholder="Enter ID Issue Date" class="datepicker is-valids is-valid" name="issue_date" id="issue_date" placeholder="dd/MM/YYYY" autocomplete="off">
                                <label for="issue_date">Issue Date <span class="req-star">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-item form-float-style">
                                <input type="text" placeholder="Enter ID Expiry Date" class="datepicker is-valids is-valid" name="expiry_date" id="expiry_date" placeholder="dd/MM/YYYY" autocomplete="off">
                                <label for="expiry_date">Expiry Date <span class="req-star">*</span></label>
                            </div>
                            <span id="expiry"></span>
                        </div>
                        <div class="col-md-6">
                            <div class="select top-space-rem after-drp form-item form-float-style">
                                @component('components.country_city_select', [
                                'name' => 'country_id',
                                'id' => 'country_id',
                                'class' => 'order-td-input selectpicker1 select-text height_drp is-valid select2',
                                'selected' => '',
                                'placeholder' => 'Select Issue Country'
                                ])
                                @endcomponent
                                <label for="country" id="country_id">Issue Country <span class="req-star">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating form-item mb-0">
                                <div class="form-item form-float-style serach-rem mb-0">
                                    <div class="select top-space-rem after-drp form-float-style ">
                                        <select data-live-search="true" id="status" name="status" class="order-td-input selectpicker select-text height_drp is-valid">
                                            <option value="active" selected="">@lang('travellers.active')</option>
                                            <option value="inactive">@lang('travellers.inActive')</option>
                                            <!-- <option value="terminated">@lang('travellers.terminated')</option> -->
                                        </select>
                                        <label class="select-label searchable-drp">@lang('travellers.status')</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-item form-float-style">
                                @component('components.crop-image', [
                                'name' => 'profile_photo',
                                'id' => 'upload_image',
                                'class' => 'file-upload is-valid image'

                                ])
                                @endcomponent
                                <label for="upload-profile">Uplaod Document</label>
                            </div>
                            <p class="upload-img-des mb-0">These images are visible in the customer page.
                                Support jpg, jpeg, or png files.
                            </p>
                            <div id='profile_image_section'>
                                <img data-toggle="popover" id="croppedImagePreview" height="150" width="150" src="" alt="no-image" style="display: none;">
                                <label for="upload-profile">@lang('travellers.uploadProfileImage')</label>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="croppedImage" name="croppedImage" value="">
                    <div class="cards-btn mt-3">
                        <button type="submit" id="disBtn" class="btn btn-success form-btn-success">@lang('travellers.submit')</button>
                        <a href="{{ route('travellers.index',['customer_id' => $customerId]) }}" type="button" class="btn btn-danger form-btn-danger">@lang('travellers.cancel')</a>
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
    $("#datepicker").datepicker({
        changeMonth: true, // this will help you to change month as you like
        changeYear: true, // this will help you to change years as you like
        yearRange: "1900:2100"
    });
    $("#date_of_birth").datepicker({
        changeMonth: true, // this will help you to change month as you like
        changeYear: true, // this will help you to change years as you like
        yearRange: "1900:c"
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
                genderSelect.selectpicker('val', ''); // Reset gender if another title is selected
            }
            genderSelect.selectpicker('destroy'); // Destroy the selectpicker
            genderSelect = $('#gender'); // Reassign the select element
            genderSelect.selectpicker(); // Reinitialize the selectpicker
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
    //To Copy Generated Password

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
        //if you just want to remove them all
        $("#isd_code-error").remove();

        if ($(this).val() == '') {
            $('#isd_code-error').show();
        } else {
            $('#isd_code-error').hide();
        }

    });
    $("#slect_finish").change(function() {
        var choice = jQuery(this).val();
        //if you just want to remove them all
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
    $.validator.addMethod("passportNationalIdValidation", function(value, element) {
        var selectedValue = $("#id_type").val(); // Assuming you have a select element with the id "id_type"

        // Check if the selected value is "national_id"
        if (selectedValue === "national_id") {
            // Accept all values for national_id without specific validation
            return /^[^\n]+$/i.test(value);
        }

        // For passport, validate the format
        if (selectedValue === "passport") {
            // Validate passport format
            return /^[A-Z0-9]{8,}$/i.test(value); // Adjust the regex according to your passport format
        }

        // If no match found, return false
        return false;
    }, "Invalid format for selected ID Type.");

    $.validator.addMethod("dateComparison", function(value, element) {
        var fromDate = $("#issue_date").val();
        var toDate = $("#expiry_date").val();
        // Perform date comparison
        return new Date(fromDate) < new Date(toDate);
    }, "Expiry Date must be greater than Issue Date");
    $.validator.addMethod("birthday", function(value, element) {
        var birthDate = $("#date_of_birth").val();

        // Perform date comparison
        return new Date(birthDate) <= new Date(); // Ensure birth date is not in the future
    }, "Date Of Birth must be a valid date and not in the future.");

    $(function() {

        //jquery Form validation
        $('#dataForm').validate({
            onkeyup: false, //turn off auto validate whilst typing
            rules: {
                title: {
                    required: true,
                },
                first_name: {
                    required: true,
                    noSpace: true,
                    maxlength: 100,
                },

                last_name: {
                    required: true,
                    noSpace: true,
                },
                date_of_birth: {
                    noSpace: true,
                    required: true,
                    birthday: true,
                },
                gender: {
                    required: true,
                },
                nationality_id: {
                    required: true,
                },
                marital_status: {
                    required: true,
                },
                id_type: {
                    required: true,
                },
                id_number: {
                    required: true,
                    noSpace: true,
                    passportNationalIdValidation: true,
                },
                issue_date: {
                    required: true,
                },
                expiry_date: {
                    required: true,
                    dateComparison: true,
                },
                country_id: {
                    required: true,
                },
                document: {
                    extension: "jpeg|png|jpg|svg",
                    maxsize: 1000000,
                },
            },

            messages: {
                title: {
                    required: "Please select a Title"
                },
                first_name: {
                    required: "Please enter a First Name"

                },
                second_name: {
                    required: "Please enter a Second Name"

                },
                last_name: {
                    required: "Please enter a Last Name"
                },
                date_of_birth: {
                    required: "Please enter a Date Of Birth"
                },
                gender: {
                    required: "Please select a Gender"
                },
                nationality_id: {
                    required: "Please Select a Nationality"
                },
                id_type: {
                    required: "Please enter an ID type"
                },
                id_number: {
                    required: "Please enter an ID Number"
                },
                issue_date: {
                    required: "Please select an Issue Date"
                },
                expiry_date: {
                    required: "Please select an Expiry Date"
                },
                country_id: {
                    required: "Please select an Issue Country"
                },
                ducument: {
                    required: "Please upload a Profile Image",
                    extension: "Please select image format must be .jpg, .jpeg or .png",
                    maxsize: "Please upload image size less than 1MB"
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