@extends('admin.layout.main')
@section('title',$header['title'])

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">

<style>
  .form-item input.is-valids+label {
    font-size: 11px;
    top: -5px;
  }

  .hidden {
    display: none;
  }
</style>


<!-- /.Start content-header -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-4 mt-2">
      <div class="col-sm-12 d-flex breadcrumb-style">
        <h1 class="m-0">Agency - Add</h1>
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard </a></li>
          <li class="breadcrumb-item"><a href="{{ route('agency.index') }}">Agencies</a></li>
          <li class="breadcrumb-item active">Add</li>
        </ol>
      </div><!-- /.col -->

    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.End content-header -->



<!-- Main content -->
<section class="content">
  <!-- Start container fluid -->
  <div class="container-fluid">
    <!-- Start Row Div -->
    <div class="row">
      <form id="basicForm" class="form validate mb-3 ml-0 mr-0 mt-0" action="{{ route('agency.store') }}" method="post" enctype="multipart/form-data">
        <div class="card pb-4 pt-4 px-3 w-100">

          <div class="col-md-12">
            <div class="form-group">
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
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-6 discount">
              @csrf
              <h5 class="setting-title">General Information</h5>
              <div class="form-item form-float-style form-group">
                <input type="text" value="" id="agency_name" name="agency_name" autocomplete="off" class="is-valid">
                <label for="Agency Name">Agency Name <span class="req-star">*</span></label>
              </div>
              <div class="form-item form-float-style form-group">
                <input type="text" value="" id="short_name" name="short_name" autocomplete="off" class="is-valid">
                <label for="">Short Name (Alias)<span class="req-star">*</span></label>
              </div>
              <div class="form-item form-float-style form-group">
                <input type="text" value="" id="contact" name="contact" autocomplete="off" class="is-valid">
                <label for="Contact Name">Contact Name <span class="req-star">*</span></label>
              </div>
              <div class="form-item form-float-style form-group">
                <input type="text" value="" id="position" name="position" autocomplete="off" class="is-valid" placeholder="Manager">
                <label for="position">Position <span class="req-star">*</span></label>
              </div>
              <div class="form-item form-float-style form-group">
                <input type="email" value="" id="email" name="email" autocomplete="off" class="is-valid">
                <label for="email">Email <span class="req-star">*</span></label>
              </div>
              <div class="form-item form-float-style form-group">
                <input type="text" value="" id="license_number" name="license_number" autocomplete="off" class="is-valid">
                <label for="licenceNumber">License Number <span class="req-star">*</span></label>
              </div>




              <div class="form-floating form-float-style form-group required mb-3 ">
                <div class="form-item form-float-style serach-rem mb-3">
                  <div class="select top-space-rem after-drp form-float-style form-group">
                    <select data-live-search="true" name="agency_type_id" id="agency_type_id" class="order-td-input selectpicker select-text height_drp is-valid">
                      <option value="" selected disabled>Select Agency Type</option>
                      @foreach($agencyCreateData['agency_type'] as $value)
                      <option value="{{ $value['id'] }}" data-code="{{$value['code']}}">{{ $value['name'] }}</option>
                      @endforeach
                    </select>
                    <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">Agency Type <span class="req-star">*</span></label>
                  </div>
                </div>
              </div>

              <div class="form-item form-float-style form-group">
                <input type="text" name="phone_no" value="" id="phone_no" autocomplete="off" class="is-valid">
                <label for="phoneNo">Phone No<span class="req-star">*</span></label>
              </div>
              <div class="form-item form-float-style form-group">

                <input type="text" name="fax_no" value="" id="fax_no" autocomplete="off" class="is-valid">
                <label for="faxNo">Fax No<span class="req-star">*</span></label>
              </div>
              <div class="form-item form-float-style form-group">

                <input type="text" value="" name="web_url" id="web_url" autocomplete="off" class="is-valid">
                <label for="weburl">WEB URL<span class="req-star">*</span></label>
              </div>
              <div class="form-floating form-float-style form-group required mb-3">
                <div class="form-item form-float-style serach-rem mb-3">
                  <div class="select top-space-rem after-drp form-float-style form-group">
                    <select data-live-search="true" name="status" id="status" class="order-td-input selectpicker select-text height_drp is-valid select-validate">
                      <option value="" selected>Select Status</option>
                      <option value="active">Active</option>
                      <option value="inactive">In-active</option>
                      <option value="terminated">Terminated</option>
                    </select>
                    <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">@lang('adminUser.status') <span class="text-red">*</span></label>
                  </div>
                </div>
              </div>

              <div class="form-item form-float-style form-group">
                <input type="file" id="agency_logo" name="agency_logo" class="file-upload select-validate" autocomplete="off" class="is-valid">
                <label for="agencyLogo">Agency Logo <span class="req-star">*</span></label>
              </div>
              <div class="form-item form-float-style form-group">

                <input type="text" value="" name="iata_number" id="iata_number" maxlength="7" onkeypress="return isNumber(event)" autocomplete="off" class="is-valid">
                <label for="iata_number">IATA Number</label>
              </div>
              <hr style="border-top: 2px dashed black">
              <!-- end general information -->

              <h5 class="setting-title">Address Information</h5>

              <div class="form-item form-float-style mb-2s">

                <input type="text" id="setting-search-add" name="searchAddress" value="" class="autocomplete" id="searchAddress" autocomplete="on">
                <label for="setting-search-add">Search Address</label>
                <div id="menu-container"></div>

              </div>
              <div class="form-check filter-check sub-check mb-3">

                <input class="form-check-input" type="checkbox" value="" id="searchAddressChecked" name="searchAddress">
                <label class="form-check-label" for="searchAddressChecked">
                  Enter Manual Address
                </label>
              </div>
              <div class="form-item form-float-style form-group">

                <input type="text" class="is-valid removeReadOnly" value="" id="address" name='address1' autocomplete="off" readonly>
                <label for="country">Address <span class="req-star">*</span></label>
              </div>

              <div class="form-item form-float-style form-group">

                <input type="text" class="is-valid workingCityAutocomplete1 removeReadOnly" value="" name='city' id="city" autocomplete="off" readonly>
                <label for="city">City <span class="req-star">*</span></label>

              </div>
              <div class="form-item form-float-style form-group">
                <input type="text" class="is-valid removeReadOnly" value="" name='state' id="state" autocomplete="off" readonly>
                <label for="state">State <span class="req-star">*</span></label>
              </div>
              <div class="form-item form-float-style form-group">
                <input type="text" class="is-valid removeReadOnly" value="" name='country' id="country" autocomplete="off" readonly>
                <label for="country">Country <span class="req-star">*</span></label>
              </div>

              <div class="form-item form-float-style form-group">

                <input type="text" class="is-valid removeReadOnly" value="" name="zip_code" id="pincode" autocomplete="off" readonly>
                <label for="zipcode">Zip Code <span class="req-star">*</span></label>
              </div>


            </div><!-- End general information div-->



            <div class="col-md-6 discount"><!-- Start System Option div -->

              <h5 class="setting-title">System Option</h5>

              <div class="row m-b-0" style="border:0.5px solid #cbbaba">
                <div class="">
                  <div class="form-check" style="color:#5a5151;margin-top: 10px">
                    <input type="checkbox" id="stop_by" name="stop_by" class="form-check-input " value="1">
                    <label class="" for="stop_by">Stop Buy</label>
                  </div>

                  <div class="form-check" style="color:#5a5151">
                    <input type="checkbox" id="search_only" name="search_only" class="form-check-input " value="1">
                    <label class="" for="search_only">Search Only</label>
                  </div>

                  <div class="form-check" style="color:#5a5151">
                    <input type="checkbox" id="radioSuccess14" name="cancel_right" class="form-check-input" value="1">
                    <label class="" for="radioSuccess14">Cancel Right</label>
                  </div>
                </div>
              </div>
              @if(!$agencyCreateData['payment_option'])
              <div class="row m-b-0 form-group" style="border:0.5px solid #cbbaba;margin-top:10px;" id="paymentTypes">
                <div class="">
                  <span style="color:black">Payment Option <span class="text-red">*</span></span>
                  <input type="hidden" id="paymentd_option" name="pmt_opt" class="form-check-input" value="">
                  <label class="" for="travel"></label>
                </div>
              </div>
              @else
              <div class="row m-b-0 form-group" style="border:0.5px solid #cbbaba;margin-top:10px;" id="paymentTypes">
                <div class="">
                  <span style="color:black">Payment Option <span class="text-red">*</span></span>
                  @foreach($agencyCreateData['payment_option'] as $value)
                  <div class="form-check" style="color:#5a5151;margin-top: 10px">
                    <input type="checkbox" id="payment_option" name="payment_option[]" class="form-check-input" value="{{ $value->id }}">
                    <label class="" for="travel">{{ $value->name }}</label>
                  </div>
                  @endforeach
                </div>
              </div>
              @endif
              @if(!$agencyCreateData['service_type'])
              <div class="row m-b-0 form-group" style="border:0.5px solid #cbbaba; margin-top:10px;" id="serviceType">
                <div class="">
                  <span style="color:black">Service Type <span class="text-red">*</span></span>
                  <input type="hidden" id="service_type" name="service_type_empty" class="form-check-input" value="">
                  <label class="" for="travel"></label>
                </div>
              </div>
              @else
              <div class="row m-b-0 form-group" style="border:0.5px solid #cbbaba; margin-top:10px;" id="serviceType">
                <div class="">
                  <span style="color:black">Service Type <span class="text-red">*</span></span>
                  @foreach($agencyCreateData['service_type'] as $value)
                  <div class="form-check" style="color:#5a5151;margin-top: 10px">
                    <input type="checkbox" id="service_type" name="service_type[]" class="form-check-input" value="{{ $value['id'] }}">
                    <label class="" for="travel">{{ $value['name'] }}</label>
                  </div>
                  @endforeach
                </div>
              </div>
              @endif



              <div class="row m-b-0 hidden" style="border:0.5px solid #cbbaba; margin-top:10px;" id="enableCurrency">
                <div class="form-item form-float-style form-group">
                  <span style="color:black">Enable Currencies <span class="text-red">*</span></span>
                  <select class="selectpicker is-valid select-validate" data-live-search="true" multiple value="" name="enable_currency_id[]" id="enable_currency_id" autocomplete="off" style="margin-top: 14px;">

                  </select>
                </div>
              </div>

              @if(!$agencyCreateData['payment_gateway'])
              <div class="row m-b-0 form-group" style="border:0.5px solid #cbbaba; margin-top:10px;" id="paymentGateway">
                <div class="">
                  <span style="color:black">Agency Payment Gateway <span class="text-red">*</span></span>
                  <input type="hidden" id="payment_gateway_empty" name="payment_gateway_empty" class="form-check-input" value="">
                  <label class="" for="travel">{{ $value['name'] }}</label>
                </div>
              </div>
              @else
              <div class="row m-b-0 form-group" style="border:0.5px solid #cbbaba; margin-top:10px;" id="paymentGateway">
                <div class="">
                  <span style="color:black">Agency Payment Gateway <span class="text-red">*</span></span>
                  @foreach($agencyCreateData['payment_gateway'] as $value)
                  <div class="form-check" style="color:#5a5151;margin-top: 10px">
                    <input type="checkbox" id="payment_gateway" name="payment_gateway[]" class="form-check-input" value="{{ $value['id'] }}">
                    <label class="" for="travel">{{ $value['name'] }}</label>
                  </div>
                  @endforeach
                </div>
              </div>
              @endif
              <hr style="border-top: 2px dashed black">
              <h5 class="setting-title">Operator Details</h5>
              <div class="form-item form-float-style form-group">
                <input type="text" value="" name="operator_full_name" id="operator_full_name" autocomplete="off" class="is-valid">
                <label for="fullName" style="left:15px">Full Name<span class="req-star">*</span></label>
              </div>
              <div class="form-item form-float-style form-group">
                <input type="text" value="" name="operator_email" id="operator_email" autocomplete="off" class="is-valid">
                <label for="email" style="left:15px">Email<span class="req-star">*</span></label>
              </div>
              <div class="form-item form-float-style">
                <div class="row">
                  <div class="col-md-6">
                    <div class="select top-space-rem after-drp form-item form-float-style form-group">
                      <select data-live-search="true" id="isd_code" name="isd_code" class="order-td-input selectpicker select-text height_drp is-valid select-validate">
                        <option value="" disabled selected>Select ISD Code</option>
                        @foreach($agencyCreateData['getIsdCode'] as $getIsdCodeName)
                        <option value="{{ $getIsdCodeName->isd_code }}">
                          {{ $getIsdCodeName->isd_code }}
                          @foreach($getIsdCodeName->countryCode as $countries)
                          {{ $countries->country_name }}@if(!$loop->last), @endif
                          @endforeach
                        </option>
                        @endforeach
                      </select>
                      <label for="ISD Code" id="isd-code-customer">ISD Code<span class="req-star">*</span></label>

                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-item form-float-style form-group">
                      <input type="text" name="operator_mobile" id="operator_mobile" onkeypress="return isNumber(event)" autocomplete="off" class="is-valid">
                      <label for="mobile">Mobile Number <span class="req-star">*</span></label>

                    </div>
                  </div>

                </div>

              </div>


            </div><!-- End System option div -->



            <div class="col-md-6 discount">
              <div class="cards-btn">
                <button type="submit" id="disBtn" class="btn btn-success form-btn-success">Submit</button>
                <a href="{{ route('agency.index') }}" class="btn btn-danger form-btn-danger">Cancel</a>
              </div>

            </div>
      </form>

    </div>
  </div>
  </div>
  </div>
  </div>
  </div>



  </div>
  <!-- /.row -->
  </div>
  <!-- End Row Div -->

  </div>

  <!-- End container fluid -->
</section>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>

<script>
  $(document).ready(function() {
    $('#agency_type_id').on('change', function() {

      var selectedValue = $(this).val();

      if (selectedValue != null) {
        $("#enableCurrency").removeClass("hidden");
      } else {

        $("#enableCurrency").addClass("hidden");
      }

      var selectedOption = $('option:selected', this).attr('data-code');

      if (selectedOption == 'B2B') {

        $('#paymentTypes').show();
        $('#serviceType').show();
        $('#paymentGateway').show();

      } else {
        $('#paymentTypes').hide();
        $('#serviceType').hide();
        $('#paymentGateway').hide();

      }
    });

    $('#agency_type_id').trigger('change');
  });
</script>
<script>
  $('.select-validate').on('change', function() {
    if ($(this).valid()) {

      $(this).removeClass('is-invalid');

      $(this).next('.invalid-feedback').remove();
    }
  });
  $(function() {
    //set manual addres details fill
    $("#searchAddressChecked").click(function() {
      if ($(this).is(":checked")) {

        $(".removeReadOnly").removeAttr("readonly", false);
        $("#setting-search-add").attr('readonly', true);

      } else {
        $(".removeReadOnly").attr("readonly", true);
        $("#setting-search-add").attr('readonly', false);
      }
    });

    $('*[value=""]').removeClass('is-valid');

    $.validator.addMethod("email_regex", function(value, element, regexpr) {
      return this.optional(element) || regexpr.test(value);
    }, "Please enter a valid Email Address.");

    $.validator.addMethod('validUrl', function(value, element) {
      var url = $.validator.methods.url.bind(this);
      return url(value, element) || url('http://' + value, element);
    }, 'Please enter a valid URL');
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

    $('#basicForm').validate({

      rules: {
        'agency_name': {
          required: true,
          noSpace: true
        },
        'payment_option': {
          required: true,
        },
        'short_name': {
          required: true,
          noSpace: true
        },
        'contact': {
          required: true,
          noSpace: true

        },
        'isd_code': {
          required: true,
        },
        'position': {
          required: true,
          lettersonly: true,
          noSpace: true
        },
        'email': {
          required: true,
          email: true,
          email_regex: /\b[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b/i,
          remote: {
            url: "{{route('admin.agency-email.checkExist')}}",
            type: "post",
            data: {
              email: function() {
                return $("#email").val();
              },
              "_token": '{{ csrf_token() }}'
            }
          }
        },
        'license_number': {
          required: true,
        },
        'agency_type_id': {
          required: true,

        },
        'phone_no': {
          required: true,
          digits: true,
          minlength: 10,
          maxlength: 10,
          remote: {
            url: "{{route('admin.agency-phone.checkExist')}}",
            type: "post",
            data: {
              phone: function() {
                return $("#phone_no").val();
              },
              "_token": '{{ csrf_token() }}'
            }
          }
        },
        'fax_no': {
          required: true,
          digits: true,
          remote: {
            url: "{{route('admin.agency-fax.checkExist')}}",
            type: "post",
            data: {
              fax: function() {
                return $("#fax_no").val();
              },
              "_token": '{{ csrf_token() }}'
            }
          }
        },
        'web_url': {
          required: true,
          validUrl: true,
          remote: {
            url: "{{route('admin.agency-webUrl.checkExist')}}",
            type: "post",
            data: {
              web_url: function() {
                return $("#web_url").val();
              },
              "_token": '{{ csrf_token() }}'
            }
          }
        },
        'status': {
          required: true,
        },
        'agency_logo': {
          required: true,
          extension: "jpeg|png|jpg",
          maxsize: 1000000,
        },
        'iata_number': {
          minlength: 7,
          maxlength: 7

        },
        'pmt_opt': {
          required: true,
        },
        'service_type[]': {
          required: true,
        },
        'service_type_empty': {
          required: true,
        },
        'enable_currency_id[]': {
          required: true,
        },
        'payment_gateway[]': {
          required: true,
        },
        'payment_option[]': {
          required: true,
        },
        'payment_gateway_empty': {
          required: true,
        },
        'operator_full_name': {
          required: true,
          lettersonly: true,
          noSpace: true
        },
        'operator_email': {
          required: true,
          email: true,
          email_regex: /\b[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b/i,
          remote: {
            url: "{{route('admin.user-email.checkExist')}}",
            type: "post",
            data: {
              operatorEmail: function() {
                return $("#operator_email").val();
              },
              "_token": '{{ csrf_token() }}'
            }
          }
        },
        'operator_mobile': {
          required: true,
          digits: true,
          mobileValidation: true,

        },
        'address1': {
          required: true,
        },
        'city': {
          required: true,
        },
        'state': {
          required: true,
        },
        'country': {
          required: true,
        },
        'zip_code': {
          required: true,
          digits: true,
          minlength: 6,
          maxlength: 6
        },

      },
      messages: {
        'agency_name': {
          required: "Please enter an Agency Name",
        },
        'pmt_opt': {
          required: "Please add atleast one Payment option",
        },
        'short_name': {
          required: "Please enter a Short Name",
        },
        'contact': {
          required: "Please enter a Contact Name",
        },
        'position': {
          required: "Please enter a Position Name",
        },
        'email': {
          required: "Please enter an Email",
          email_regex: "Please enter valid Email",
          remote: "Email already exist"
        },
        'license_number': {
          required: "Please enter a Licence Number",

        },
        'agency_type_id': {
          required: "Please select an Agency Type",

        },
        'phone_no': {
          required: "Please enter a Phone No",
          digits: "Please enter numeric only",
          remote: "Phone No already exist"
        },
        'fax_no': {
          required: "Please enter fax no",
          digits: "Please enter numeric only",
          remote: "Fax No already exist"
        },
        'web_url': {
          required: "Please enter an URL",
          url: "Please enter a valid Url",
          remote: "Web URL already exist"
        },
        'status': {
          required: "Please select a status",
        },
        'agency_logo': {
          required: "Please select a Logo",
          extension: "Please select image format must be .jpg, .jpeg or .png",
          maxsize: "Please upload image size less than 1MB"
        },
        'payment_option': {
          required: "Please check atleast one payment option checkbox",
        },
        'service_type[]': {
          required: "Please check atleast one service type checkbox",
        },
        'service_type_empty': {
          required: "Please add atleast one service type",
        },
        'enable_currency_id[]': {
          required: "Please select a currency",
        },
        'payment_gateway[]': {
          required: "Please check atleast one checkbox",
        },
        'payment_gateway_empty': {
          required: "Please add atleast one Payment gateway",
        },
        'payment_option[]': {
          required: "Please check atleast one checkbox",
        },
        'operator_full_name': {
          required: "Please enter an Operator Name",
        },
        'operator_email': {
          required: "Please enter an Email",
          email_regex: "Please enter valid email",
          remote: "Operator Email already exist"
        },
        'isd_code': {
          required: "Please select an ISD Code"
        },
        'operator_mobile': {
          required: "Please enter a Mobile No",
          digits: "Please enter numbers only",
          remote: "Operator Mobile already exist"
        },
        'address1': {
          required: "Please enter a address 1",
        },
        'address2': {
          required: "Please enter a address 2",
        },
        'city': {
          required: "Please enter a City Name",
        },
        'state': {
          required: "Please enter a State Name",
        },
        'country': {
          required: "Please enter a Country Name",
        },
        'zip_code': {
          required: "Please enter a Zip Code",
          digits: "Please enter numbers only"
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
      },
      submitHandler: function(form) {
        $("#disBtn").attr("disabled", true);
        form.submit();
      }
    });

  });

  /* Dropdown Select Agency Type */
  $(document).ready(function() {
    $("#agency_type_id").change(function() {
      var agencyCode = this.value;


      if ($(this).valid()) {
        $(this).removeClass('is-invalid');
        $(this).next('.invalid-feedback').remove();
      }

      $.ajax({
        type: "GET",
        url: '{{ route("admin.get_currency") }}',
        data: {
          "_token": '{{ csrf_token() }}',
          "agencyCode": agencyCode
        },
        success: function(data) {
          optionoldselected = "{{@old('agency_type_id')}}";

          var obj = JSON.parse(data);

          $('#enable_currency_id').html('');

          $('#enable_currency_id').selectpicker('destroy');
          $('#enable_currency_id').selectpicker();
          console.log('else if work:', obj);
          $.each(obj, function(key, value) {
            optionText = value['name'];
            optionValue = value['id'];
            selected = "";
            if (optionValue == optionoldselected) {
              selected = "selected";
            }
            $.each(optionoldselected, function(index1, value1) {
              if (optionValue == value1['id']) {
                selected = "selected";
              }
            });

            $('#enable_currency_id').append(`<option ${selected} value="${optionValue}">
                              ${optionText}
                          </option>`);

          });
          console.log('if supplier refresh');
          $('#enable_currency_id').selectpicker('refresh');



        }
      });
    });
  });
</script>

@append