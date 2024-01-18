@extends('admin.layout.main')
@section('title',$header['title'])

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-4 mt-2">
            <div class="col-sm-12 d-flex breadcrumb-style">
                <h1 class="m-0">{{ $header['title'] }}</h1>
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('customers.dashboard') </a></li>
                    <li class="breadcrumb-item"><a href="{{ route('customers.index') }}">@lang('customers.moduleHeading')</a></li>
                    <li class="breadcrumb-item active">@lang('customers.edit')</li>
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
                <form method="post" action="{{route('customers.update',$customerDetail['id'])}}" id="dataForm" class="form row mb-0 validate" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="redirects_to" id="redirects_to" value="{{ URL::previous() }}">
                    <input type="hidden" name="customer_id" id="customer_id" value="{{$customerDetail['id']}}" />
                    <input type="hidden" name="customer_address_id" id="customer_address_id" value="{{$customerDetail['id']}}" />
                    <div class="brdr-btm row">
                        <div class="col-md-3">
                            <div class="select top-space-rem after-drp form-item form-float-style">
                                <select data-live-search="true" id="title" name="title" class="order-td-input selectpicker select-text height_drp is-valid">
                                    <option value="">Select Title</option>
                                    <option @if($customerDetail['title']=='mr' ) selected="selected" @endif value="mr">Mr.</option>
                                    <option @if($customerDetail['title']=='mrs' ) selected="selected" @endif value="mrs">Mrs.</option>
                                    <option @if($customerDetail['title']=='miss' ) selected="selected" @endif value="miss">Miss.</option>
                                </select>
                                <label for="title">Title <span class="req-star">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-item form-float-style">
                                <input type="text" id="first_name" name="first_name" class="is-valid" autocomplete="off" required value="{{$customerDetail['first_name']}}">
                                <label for="firmname">@lang('customers.firstName')<span class="req-star">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-item form-float-style">
                                <input type="text" id="last_name" name="last_name" class="is-valid" autocomplete="off" required value="{{$customerDetail['last_name']}}">
                                <label for="ownername">@lang('customers.lastName') <span class="req-star">*</span></label>
                            </div>
                        </div>
                        <?php $isd = Str::before($customerDetail['mobile'], ' '); ?>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="select top-space-rem after-drp form-item form-float-style" @if($customerDetail['is_mobile_verified']=='1' ) style="pointer-events:none;" @endif>
                                        <select data-live-search="true" id="isd_code" name="isd_code" class="order-td-input selectpicker select-text height_drp is-valid">
                                            <option value="">Select ISD Code</option>

                                            @foreach($getIsdCode as $getIsdCodeName)
                                            <option value="{{ $getIsdCodeName->isd_code }}" @if($customerDetail && $getIsdCodeName->isd_code == $isd)
                                                selected="selected"
                                                @endif>
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
                                        <?php

                                        $searchArray = preg_replace(
                                            '/\+(?:998|996|995|994|993|992|977|976|975|974|973|972|971|970|968|967|966|965|964|963|962|961|960|886|880|856|855|853|852|850|692|691|690|689|688|687|686|685|683|682|681|680|679|678|677|676|675|674|673|672|670|599|598|597|595|593|592|591|590|509|508|507|506|505|504|503|502|501|500|423|421|420|389|387|386|385|383|382|381|380|379|378|377|376|375|374|373|372|371|370|359|358|357|356|355|354|353|352|351|350|299|298|297|1-264|1-441|1-284|1-345|1-473|1-671|1-876|1-670|1-664|1-869|1-787|1-758|1-784|1-868|1-649|1-340|7-370|1-721|1-684|1-767|1-809|1-242|1-246|1-268|291|290|269|268|267|266|265|264|263|262|261|260|258|257|256|255|254|253|252|251|250|249|248|246|245|244|243|242|241|240|239|238|237|236|235|234|233|232|231|230|229|228|227|226|225|224|223|222|221|220|218|216|213|212|211|98|95|94|93|92|91|90|86|84|82|81|66|65|64|63|62|61|60|58|57|56|55|54|53|52|51|49|48|47|46|45|44\D?1624|44\D?1534|44\D?1481|44|43|41|40|39|36|34|33|32|31|30|27|20|7|1\D?939|1\D?876|1\D?869|1\D?868|1\D?849|1\D?829|1\D?809|1\D?787|1\D?784|1\D?767|1\D?758|1\D?721|1\D?684|1\D?671|1\D?670|1\D?664|1\D?649|1\D?473|1\D?441|1\D?345|1\D?340|1\D?284|1\D?268|1\D?264|1\D?246|1\D?242|1)\D?/',
                                            '',
                                            $customerDetail['mobile']
                                        );
                                        $data = $customerDetail['mobile'];
                                        $cust_mobile = substr($data, strpos($data, " ") + 1);
                                        ?>
                                        <input type="text" id="mobile" name="mobile" class="is-valid" autocomplete="off" required value="{{$cust_mobile}}" onkeypress="return isNumber(event)" @if($customerDetail['is_mobile_verified']=='1' ) echo readonly="readonly" @endif>
                                        <label for="mobileno">@lang('customers.mobileNumber')<span class="req-star">*</span></label>
                                        @error('mobile')
                                        <span id="mobileno-error" class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="form-item form-float-style">
                                <input type="email" value="{{$customerDetail['email'] ?? ''}}" id="email" class="is-valid" name="email" autocomplete="off" @if($customerDetail['is_email_verified']=='1' ) echo readonly="readonly" @endif>
                                <label for="email-add-user">@lang('customers.emailAddress')<span class="req-star">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-item form-float-style">
                                <input type="text" class="datepicker is-valids is-valid" name="date_of_birth" id="date_of_birth" placeholder="YYYY-MM-dd" autocomplete="off" value="{{$customerDetail['date_of_birth'] ?? ''}}">
                                <label for="datepicker">Date Of Birth<span class="req-star">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="select top-space-rem after-drp form-item form-float-style">
                                <select data-live-search="true" id="gender" name="gender" class="order-td-input selectpicker select-text height_drp is-valid">
                                    <option value="">Select Gender</option>
                                    <option @if($customerDetail['gender']=='male' ) selected="selected" @endif value="male">Male</option>
                                    <option @if($customerDetail['gender']=='female' ) selected="selected" @endif value="female">Female</option>
                                    <option @if($customerDetail['gender']=='other' ) selected="selected" @endif value="other">Other</option>
                                </select>
                                <label for="gender">Gender <span class="req-star">*</span></label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="select top-space-rem after-drp form-item form-float-style">
                                <select data-live-search="true" id="marital_status" name="marital_status" class="order-td-input selectpicker select-text height_drp is-valid">
                                    <option value="">Select Marital Status</option>
                                    <option @if($customerDetail['marital_status']=='married' ) selected="selected" @endif value="married">Married</option>
                                    <option @if($customerDetail['marital_status']=='single' ) selected="selected" @endif value="single">Single</option>
                                    <option @if($customerDetail['marital_status']=='other' ) selected="selected" @endif value="other">Other</option>
                                </select>
                                <label for="marital_status">Marital Status <span class="req-star">*</span></label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-item form-float-style" id="marriage_aniversary_date_div">
                                <input type="text" class="datepicker is-valids is-valid" name="marriage_aniversary_date" id="marriage_aniversary_date" placeholder="YYYY-MM-dd" autocomplete="off" value="{{ $customerDetail['marriage_aniversary_date'] ?? '' }}">
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
                                <input type="text" id="address" class="is-valid" name="address1" autocomplete="off" value="{{ $customerDetail['getCustomerAddress']['address1'] ?? '' }}">
                                <label for="address">@lang('customers.address1') <span class="req-star">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-item form-float-style">
                                <input type="text" id="address2" name="address2" autocomplete="off" value="{{$customerDetail['getCustomerAddress']['address2'] ?? '' }}">
                                <label for="address2">@lang('customers.address2') </label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-item form-float-style serach-rem mb-3">
                                <div class="select top-space-rem after-drp form-float-style">
                                    @component('components.customer_country_city_select', [
                                    'name' => 'country',
                                    'id' => 'country_code',
                                    'class' => 'order-td-input selectpicker1 select-text height_drp is-valid select2',
                                    'selected' => $customerDetail['getCustomerAddress']['getCountry']['iso_code'] ?? '',
                                    'placeholder' => 'Select Country'
                                    ])
                                    @endcomponent
                                    <label for="country" id="customer-country">@lang('customers.country') <span class="req-star">*</span></label>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="form-item form-float-style">
                                @component('components.customer_state_city_select', [
                                'name' => 'state_code',
                                'id' => 'state_code',
                                'class' => 'order-td-input selectpicker1 select-text height_drp is-valid select2',
                                'selected' => $customerDetail['getCustomerAddress']['getState']['id'] ?? '',
                                'country_code' => $customerDetail['getCustomerAddress']['getState']['country_code'] ?? '',
                                'placeholder' => 'Select State'
                                ])
                                @endcomponent
                               
                                <label for="state">@lang('customers.state') <span class="req-star">*</span></label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-item form-float-style">
                                <select data-live-search="true" id="city_code" name="city_code" class="order-td-input selectpicker1 select-text height_drp is-valid select2" style="width: 100%;">
                                    <option value="">Select City</option>
                                    @if(!empty($getCities))
                                    @foreach($getCities as $city)
                                    <option value="{{ $city['id'] ?? '' }}" @if(isset($customerAddress["city"]) && $city["id"]==$customerAddress["city"]) selected="selected" @endif>
                                        @foreach($city['city_code'] as $cname)
                                        {{ $cname['city_name'] }}
                                        @endforeach
                                    </option>
                                    @endforeach
                                    @endif
                                </select>
                                <label for="city">@lang('customers.city') <span class="req-star">*</span></label>
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="form-item form-float-style mb-2">
                                <input type="number" id="pincode" class="is-valid" name="pincode" autocomplete="off" value="{{ isset($customerDetail['getCustomerAddress']) ? $customerDetail['getCustomerAddress']['pincode'] : '' }}" required>
                                <label for="pincode">@lang('customers.pincode')<span class="req-star">*</span></label>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-6">
                        <div class="form-item form-float-style">
                            <input type="hidden" id="old_photo" name="old_photo" class="file-upload" autocomplete="off" class="is-valid" value="{{ $customerDetail['profile_photo'] }}">
                            @component('components.crop-image', [
                            'name' => 'profile_photo',
                            'id' => 'upload_image',
                            'class' => 'file-upload is-valid image'

                            ])
                            @endcomponent
                            <p class="upload-img-des mb-0">These images are visible in the customer page.
                                Support jpg, jpeg, or png files.
                            </p>

                            <div id='profile_image_section'>
                                <img data-toggle="popover" id="croppedImagePreview" height="150" width="150" src="{{ $customerDetail['profile_photo'] ?: URL::asset('assets/images/no-image.png')}}" alt="">
                                <label for="upload-profile">@lang('customers.uploadProfileImage')</label>

                            </div>
                            <input type="hidden" id="croppedImage" name="croppedImage" value="">
                        </div>

                    </div>
                    <div class="col-md-6">
                        <div class="form-floating form-item mb-0">
                            <div class="form-item form-float-style serach-rem mb-0">
                                <div class="select top-space-rem after-drp form-float-style ">
                                    <select data-live-search="true" id="slect_finish" name="status" class="order-td-input selectpicker select-text height_drp is-valid">
                                        <option @if($customerDetail['status']=='active' ) selected="selected" @endif value="active" selected="">@lang('customers.active')</option>
                                        <option @if($customerDetail['status']=='inactive' ) selected="selected" @endif value="inactive">@lang('customers.inActive')</option>
                                        <option @if($customerDetail['status']=='terminated' ) selected="selected" @endif value="terminated">@lang('customers.terminated')</option>

                                    </select>
                                    <label class="select-label searchable-drp">@lang('customers.status')</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="cards-btn mt-3">
                        <button type="submit" id="disBtn" class="btn btn-success form-btn-success">@lang('customers.submit')</button>
                        <a href="{{ route('customers.index') }}" type="button" class="btn btn-danger form-btn-danger">Cancel</a>
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
    $(document).ready(function() {
        $(document).ready(function() {
            $('#state_code').select2();
        });
        $(document).ready(function() {
            $('#city_code').select2();
        });
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
        $('#marital_status').trigger("change");
    });
</script>
<!-- Page specific script -->
<script>
    //make GST value uppercase
    $('#usergstno').keyup(function() {
        $(this).val($(this).val().toUpperCase());
    });
    $('#userpackeg-gst').keyup(function() {
        $(this).val($(this).val().toUpperCase());
    });

    //on change profile set old_profile_image blank
    $('#upload-profile').change(function() {
        $("#old_profile_image").val('');
    });
    //on change profile set old_gst_certificate blank
    $('#upload-gst-cert').change(function() {
        $("#old_gst_certificate").val('');
    });
    //on change profile set old_company_certificate blank
    $('#upload-comp-cert').change(function() {
        $("#old_company_certificate").val('');
    });

    //remove profile image
    function removeProfileImage() {
        $('#profile_image_section').hide();
        $("#old_profile_image").val('');
    }

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


    $.validator.addMethod("email_regex", function(value, element, regexpr) {
        return this.optional(element) || regexpr.test(value);
    }, "Please enter a valid Email");

    $.validator.addMethod("website_regex", function(value, element, regexpr) {
        return this.optional(element) || regexpr.test(value);
    }, "Please enter a valid website.");

    $.validator.addMethod("tenDigits", function(value, element) {
        return this.optional(element) || /^\d{10}$/.test(value);
    }, "Please enter exactly 10 digits.");

    $.validator.addMethod("validateUserMobile", function(value, element) {
        var data = {
                "_token": '{{ csrf_token() }}',
                "mobile": value
            },
            eReport = ''; //error report

        $.ajax({
            type: "POST",
            url: "{{route('admin.customers.checkUser')}}",
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
    $.validator.addMethod("mobileValidation", function(value, element) {
        var validator = this;
        var isValid = false;
        var isd_code = document.getElementById("isd_code").value;
        var customer_id = document.getElementById("customer_id").value;


        $.ajax({
            url: "{{route('admin.customers.checkCustomerMobileExist')}}",
            method: "POST",
            data: {
                mobile: value,
                isd_code: isd_code,
                customer_id: customer_id,
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
                    mobileValidation: true,
                    noSpace: true,
                },
                email: {
                    required: true,
                    email: true,
                    email_regex: /\b[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b/i,
                    noSpace: true,
                    remote: {
                        url: "{{route('admin.customers.checkEmailUserExist') }}",
                        type: "POST",
                        data: {
                            email: function() {
                                return $("#email").val();
                            },
                            customer_id: function() {
                                return $("#customer_id").val();
                            },
                            "_token": '{{ csrf_token() }}'
                        }
                    }
                },
                isd_code: {
                    required: true,
                    noSpace: true
                },
                address1: {
                    required: true,
                    noSpace: true
                },
                country: {
                    required: true,
                    noSpace: true
                },
                state: {
                    required: true,
                    noSpace: true
                },
                city_code: {
                    required: true,
                    noSpace: true
                },
                country_name: {
                    required: true,
                },
                pincode: {
                    required: true,
                    noSpace: true
                },
                date_of_birth: {
                    required: true,
                    noSpace: true,
                    birthday: true
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
                    required: "Please Select a ISD Code"
                },
                email: {
                    required: "Please enter an Email",
                    email: "Please enter a valid Email",
                    remote: "Email is already taken"
                },

                address1: {
                    required: "Please enter an Address"
                },
                profile_photo: {
                    accept: "Please select image format must be .jpg, .jpeg or .png.",
                    maxsize: "Please upload image size less than 1MB"

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
            submitHandler: function(form, event) {
                var user_pass = $("#password").val();
                var gen_pass = $("#genpass").val();
                if (user_pass == "" && gen_pass == "") {
                    var confirm_pass = confirm("Want to continue without generat password? User register first time so password is required to login into application.");
                    if (confirm_pass) {
                        $("#disBtn").attr("disabled", true);
                        form.submit();
                    } else {
                        event.preventDefault();
                        return false;
                    }
                }
                $("#disBtn").attr("disabled", true);
                form.submit();
            }
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>

@append