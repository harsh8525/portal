@extends('admin.layout.main')
@section('title',$header['title'])
@section('content')
<style>
    textarea.select2-search__field {
        width: 40.50em !important;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        color: #000 !important;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        margin-top: -0.8rem;
    }

    .select2-container--default.select2-container--focus .select2-selection--multiple {
        margin-top: 7px;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__display {
        margin-left: 5px !important;
    }
</style>
<!-- Include "cropper.js" CSS and JavaScript -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-4 mt-2">
            <div class="col-sm-12 d-flex breadcrumb-style">
                <h1 class="m-0">@lang('coupons.addCoupons')</h1>
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('coupons.dashboard') </a></li>
                    <li class="breadcrumb-item"><a href="{{ route('coupons.index') }}">@lang('coupons.moduleHeading')</a></li>
                    <li class="breadcrumb-item active">@lang('coupons.add')</li>
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
                <form method="post" action="{{route('coupons.store')}}" id="dataForm" class="form row mb-0 validate" enctype="multipart/form-data">
                    @csrf
                    <div class="brdr-btm row">
                        <div class="col-md-6">
                            <div class="select top-space-rem after-drp form-item form-float-style">
                                <select data-live-search="true" id="customer_type" name="customer_type" class="order-td-input selectpicker select-text height_drp is-valid">
                                    <option value="B2B">B2B</option>
                                    <option value="B2C" selected>B2C</option>
                                </select>
                                <label for="customer_type">Module Type </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-item input-group mb-3">
                                <input type="text" id="random_number" name="coupon_code" class="form-control bg-white" aria-label="Generate Offer Code" aria-describedby="basic-addon2">
                                <div class="input-group-append">
                                    <button class="btn btn-info btn-flat" id="button" type="button" onclick="randomString()">Generate Offer Code</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-item form-float-style">
                                <input type="text" id="coupon_name_en" name="coupon_names[0][coupon_name]" autocomplete="off" class="is-valid" required value="">
                                <input type="hidden" id="language_code_en" name="coupon_names[0][language_code]" autocomplete="off" class="is-valid" value="en">
                                <label for="firstname">@lang('coupons.couponName') English <span class="req-star">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-item form-float-style">
                                <input type="text" id="coupon_name_ar" name="coupon_names[1][coupon_name]" autocomplete="off" class="is-valid" required value="">
                                <input type="hidden" id="language_code_ar" name="coupon_names[1][language_code]" autocomplete="off" class="is-valid" value="ar">
                                <label for="firstname">@lang('coupons.couponName') Arabic <span class="req-star">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-item form-float-style">
                                <input type="text" id="coupon_amount" name="coupon_amount" autocomplete="off" required class="is-valid">
                                <label for="firstname">@lang('coupons.couponAmount') <span class="req-star">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="select top-space-rem after-drp form-item form-float-style">
                                <select data-live-search="true" id="discount_type" name="discount_type" class="order-td-input selectpicker select-text height_drp is-valid">
                                    <option value="">Select Discount Type</option>
                                    <option value="amount">Amount </option>
                                    <option value="percentage">Percentage </option>
                                </select>
                                <label for="Discount Type">Discount Type<span class="req-star">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-item form-float-style">
                                <input type="text" class="datepicker is-valids is-valid" name="from_date" id="from_date" placeholder="dd/MM/YYYY" autocomplete="off" class="is-valid">
                                <label for="datepicker">From Date<span class="req-star">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-item form-float-style">
                                <input type="text" class="datepicker is-valids is-valid" name="to_date" id="to_date" placeholder="dd/MM/YYYY" autocomplete="off" class="is-valid">
                                <label for="datepicker">To Date<span class="req-star">*</span></label>
                            </div>
                        </div>
                    </div>
                    <div class="row brdr-btm mt-3 mb-3">
                        <div class="discount">
                            <h5>Usage Restriction</h5>
                        </div>
                        <div class="col-md-6">
                            <div class="form-item form-float-style">
                                <input type="text" id="minimum_spend" name="minimum_spend" autocomplete="off" required class="is-valid">
                                <label for="firstname">Minimum Spend <span class="req-star">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-item form-float-style">
                                <input type="text" id="maximum_spend" name="maximum_spend" autocomplete="off" required class="is-valid">
                                <label for="firstname">Maximum Spend <span class="req-star">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="select top-space-rem after-drp form-item form-float-style">
                                <select data-live-search="true" id="service_type" name="service_type" class="order-td-input selectpicker select-text height_drp is-valid">
                                    <option value="">Select Service Type</option>
                                    @foreach($serviceType as $data)
                                    <option value="{{ $data['id'] }}">{{ $data['name'] }} </option>
                                    @endforeach
                                </select>
                                <label for="Service Type">Only For Services<span class="req-star">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-6" id="agencyShowHide">
                            <div class="form-item form-float-style form-group multiu-select-cutsm" id="allAgen">
                                <select class="order-td-input selectpicker select-text height_drp is-valid" id="agency" name="agency[]" multiple="multiple" style="width: 100%;">
                                    @foreach($getAgency as $agency)
                                    <option value="{{ $agency['id'] }}">{{ $agency['full_name'] ?? '' }} </option>
                                    @endforeach
                                </select>
                                <label for="Agency">Agency<span class="req-star">*</span></label>
                            </div>
                            <div class="all-cust-checkbx">
                                <input type="checkbox" class="is-valid" name="agencyAll" id="allAgency" height="20" width="20">
                                <label for="agen">All Agency</label>
                            </div>
                        </div>
                        <div class="col-md-6" id="customerShowHide">
                            <div class="form-item form-float-style form-group multiu-select-cutsm" id="allCust">
                                <select class="order-td-input selectpicker select-text height_drp is-valid" id="customer" name="customer[]" multiple="multiple" style="width: 500px;">
                                    @foreach($customers as $data)
                                    @if($data['first_name'])
                                    <option value="{{ $data['id'] }}">{{ $data['first_name'].' '.$data['last_name'] }} </option>
                                    @endif
                                    @endforeach
                                </select>
                                <label for="Customer">Customer<span class="req-star">*</span></label>
                            </div>
                            <div class="all-cust-checkbx">
                                <input type="checkbox" class="is-valid" name="customerAll" id="allCustomer" height="20" width="20" onchange="handleCheckboxChange()">
                                <label for="cust">All Customer</label>
                            </div>
                        </div>
                    </div>
                    <div class="row brdr-btm mt-3 mb-3">
                        <div class="discount">
                            <h5>Usage Limits</h5>
                        </div>
                        <div class="col-md-6">
                            <div class="form-item form-float-style">
                                <input type="text" id="limit_per_coupon" name="limit_per_coupon" autocomplete="off" required class="is-valid">
                                <label for="firstname">Limit Per Coupon <span class="req-star">*</span></label>
                            </div>
                        </div>
                        <div class="row brdr-btm mt-3 mb-3">
                            <div class="discount">
                                <h5>Featured Image</h5>
                            </div>
                            <div class="d-none">
                                @component('components.country_city_select', [
                                'name' => 'country_name',
                                'id' => 'country_code',
                                'class' => 'order-td-input selectpicker1 select-text height_drp is-valid select2',
                                'selected' => '',
                                'placeholder' => 'Select Country'
                                ])
                                @endcomponent
                            </div>
                        <div class="col-md-6">
                            <div class="form-item form-float-style">
                                <input type="text" id="limit_per_customer" name="limit_per_customer" autocomplete="off" required class="is-valid">
                                <label for="firstname">Limit Per Customer <span class="req-star">*</span></label>
                            </div>
                        </div>
                    </div>
                        <div class="col-md-6">
                            <div class="form-item form-float-style">
                                @component('components.crop-image', [
                                'name' => 'upload_image',
                                'id' => 'upload_image',
                                'class' => 'file-upload image is-valid'

                                ])
                                @endcomponent
                                <label for="upload-profile">Upload Image</label>
                            </div>
                            <p class="upload-img-des mb-0">These images are visible in the Coupon page.
                                Support jpg, jpeg, or png files.
                            </p>
                            <div id='profile_image_section'>
                                <img data-toggle="popover" id="croppedImagePreview" height="150" width="150" src="" alt="no-image" style="display: none;">
                                <label for="upload-profile">Upload Image</label>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="croppedImage" name="croppedImage" value="">
                    <div class="cards-btn mt-3">
                        <button type="submit" id="disBtn" class="btn btn-success form-btn-success">@lang('coupons.submit')</button>
                        <a href="{{ route('coupons.index') }}" type="button" class="btn btn-danger form-btn-danger">@lang('customers.cancel')</a>
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
    $("#agency").select2();
    $("#customer").select2();

    $(document).ready(function domReady() {
        $(".js-select2").select2({
            placeholder: "Select Airlines",
            theme: "material"
        });

        $(".select2-selection__arrow")
            .addClass("material-icons")
            .html("arrow_drop_down");
    });

    //show hide by customer(B2C) and agency(B2B)
    $("#agencyShowHide").hide();
    $('#customer_type').on('change', function() {
        var moduleType = $(this).val();
        if (moduleType == 'B2B') {
            $("#agencyShowHide").show();
            $("#customerShowHide").hide();
        } else if (moduleType == 'B2C') {
            $("#agencyShowHide").hide();
            $("#customerShowHide").show();
        } else {
            $("#agencyShowHide").hide();
            $("#customerShowHide").show();
        }
    });

    // Add custom method for date comparison
    $.validator.addMethod("dateComparison", function(value, element) {
        var fromDate = $("#from_date").val();
        var toDate = $("#to_date").val();
        // Perform date comparison
        return new Date(fromDate) < new Date(toDate);
    }, "To Date must be greater than or equal to From Date");
    $.validator.addMethod("minMaxComparison", function(value, element) {
        var minimum_spend = $("#minimum_spend").val();
        var maximum_spend = $("#maximum_spend").val();
        // Perform date comparison
        return parseFloat(minimum_spend) < parseFloat(maximum_spend);
    }, "Maximum Spend must be greater than Minimum Spend");
    
    $('#dataForm').validate({
        onkeyup: false,
        rules: {
            upload_image: {
                accept: "image/jpg,image/jpeg,image/png",
                maxsize: 1000000,
            },
            'coupon_names[0][coupon_name]': {
                required: true,
            },
            'coupon_names[1][coupon_name]': {
                required: true,
            },
            customer_type: {
                required: true,
            },
            coupon_amount: {
                number: true,
                noSpace: true,
                maxlength: 100,
            },
            discount_type: {
                required: true,
            },
            from_date: {
                required: true,
            },
            coupon_code: {
                required: true,
                remote: {
                    url: "{{route('checkCouponCodeExist')}}",
                    type: "POST",
                    data: {
                        couponCode: function() {
                            return $("#random_number").val();
                        },
                        "_token": '{{ csrf_token() }}'
                    }
                }
            },

            to_date: {
                required: true,
                dateComparison: true
            },
            minimum_spend: {
                noSpace: true,
                required: true,
                number: true,
            },
            maximum_spend: {
                noSpace: true,
                required: true,
                number: true,
                minMaxComparison: true,
            },
            service_type: {
                required: true,
            },
            limit_per_coupon: {
                noSpace: true,
                required: true,
                number: true,
            },
            limit_per_customer: {
                noSpace: true,
                required: true,
                number: true,
            },
        },


        messages: {
            'coupon_names[0][coupon_name]': {
                required: "Please enter a Coupon Name English"
            },
            'coupon_names[1][coupon_name]': {
                required: "Please enter a Coupon Name Arabic"
            },
            customer_type: {
                required: "Please select a Customer Type"
            },
            coupon_code: {
                required: "Please enter a Coupon Code",
                remote: "Coupon Code is already taken"
            },
            coupon_amount: {
                required: "Please enter a Coupon Amount",
            },
            discount_type: {
                required: "Please select a Discount Type",
            },
            from_date: {
                required: "Please select a From Date",
            },
            to_date: {
                required: "Please Select a To Date",
            },
            upload_image: {
                accept: "Please select image format must be .jpg, .jpeg or .png.",
                maxsize: "Please upload image size less than 1MB"
            },
            maximum_spend: {
                required: "Please select a Maximum Spend"
            },
            minimum_spend: {
                required: "Please select a Minimum Spend"
            },
            service_type: {
                required: "Please select a Service Type"
            },
            customer: {
                required: "Please select a customer",
            },
            limit_per_coupon: {
                required: "Please enter a Limit Per Coupon"
            },
            limit_per_customer: {
                required: "Please enter a Limit Per Customer"
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

    function randomString() {
        var chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz";
        var string_length = 6;
        var randomstring = '';
        for (var i = 0; i < string_length; i++) {
            var rnum = Math.floor(Math.random() * chars.length);
            randomstring += chars.substring(rnum, rnum + 1);
        }
        //display the generated string   
        document.getElementById("random_number").value = randomstring;
    }

    jQuery.validator.addMethod("lettersonly", function(value, element) {
        return this.optional(element) || /^[a-z ]+$/i.test(value);
    }, "Letters only please");

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
    $(function() {
        $('.datepicker').datepicker({
            dateFormat: 'yy-mm-dd',
            changeYear: true,
            yearRange: '1900:2100', // Specify a range of selectable years
        });
    });
</script>
<script>
    var checkbox = document.getElementById('allCustomer');
    var mySelect = document.getElementById('customer');
    $("#allCustomer").change(function() {

        if (checkbox.checked == true) {
            document.getElementById("allCust").style.pointerEvents = "none";
            $("#customer").val(null).trigger("change");

        } else {

            document.getElementById("allCust").style.pointerEvents = "auto";
        }
    });
    var checkboxacg = document.getElementById('allAgency');
    var mySelectacg = document.getElementById('agency');
    $("#allAgency").change(function() {

        if (checkboxacg.checked == true) {
            document.getElementById("allAgen").style.pointerEvents = "none";
            $("#agency").val(null).trigger("change");

        } else {

            document.getElementById("allAgen").style.pointerEvents = "auto";
        }
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
@append