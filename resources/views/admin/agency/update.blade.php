@extends('admin.layout.main')
@section('title',$header['title'])

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">

<style>
  .form-item input.is-valids+label {
    font-size: 11px;
    top: -5px;
  }
</style>


<!-- /.Start content-header -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-4 mt-2">
      <div class="col-sm-12 d-flex breadcrumb-style">
        <h1 class="m-0">Agency - Edit</h1>
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard </a></li>
          <li class="breadcrumb-item"><a href="{{ route('agency.index') }}">Agencies</a></li>
          <li class="breadcrumb-item active">Edit</li>
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
        <form id="basicForm" class="form validate mb-3 ml-0 mr-0 mt-0" action="{{ route('agency.update',$agencyDetail['id']) }}" method="post" enctype="multipart/form-data">
          @csrf
          <div class="row mb-3">
            <div class="col-md-6 discount">
              @method('PUT')
              <input type="hidden" name="redirects_to" id="redirects_to" value="{{ URL::previous() }}">
              <input type="hidden" name="agency_id" id="agency_id" value="{{ $agencyDetail['id'] }}">
              <input type="hidden" name="agency_address_id" value="{{ $agencyDetail['agencyAddress']['id'] }}">
              <input type="hidden" name="user_id" value="{{ $agencyDetail['user_id'] }}">

              <h5 class="setting-title">General Information</h5>
              <div class="form-item form-float-style form-group">
                <input type="text" value="{{ $agencyDetail['full_name'] }}" id="agency_name" name="agency_name" autocomplete="off" class="is-valid">
                <label for="Agency Name">Agency Name <span class="req-star">*</span></label>
              </div>
              <div class="form-item form-float-style form-group">
                <input type="text" value="{{ $agencyDetail['short_name'] }}" id="short_name" name="short_name" autocomplete="off" class="is-valid">
                <label for="">Short Name (Alias)<span class="req-star">*</span></label>
              </div>
              <div class="form-item form-float-style form-group">
                <input type="text" value="{{ $agencyDetail['contact_person_name'] }}" id="contact" name="contact" autocomplete="off" class="is-valid">
                <label for="Contact Name">Contact Name <span class="req-star">*</span></label>
              </div>
              <div class="form-item form-float-style form-group">
                <input type="text" value="{{ $agencyDetail['designation'] }}" id="position" name="position" autocomplete="off" class="is-valid" placeholder="Manager">
                <label for="position">Position<span class="req-star">*</span></label>
              </div>
              <div class="form-item form-float-style form-group">
                <input type="email" value="{{ $agencyDetail['email'] }}" id="email" name="email" autocomplete="off" class="is-valid">
                <label for="email">Email <span class="req-star">*</span></label>
              </div>
              <div class="form-item form-float-style form-group">
                <input type="text" value="{{ $agencyDetail['license_number'] }}" id="license_number" name="license_number" autocomplete="off" class="is-valid">
                <label for="licenceNumber">License Number <span class="req-star">*</span></label>
              </div>



              <div class="form-floating form-float-style form-group required mb-3">
                <div class="form-item form-float-style serach-rem mb-3">
                  <div class="select top-space-rem after-drp form-float-style">
                    <select data-live-search="true" name="agency_type_id" id="agency_type_id" class="order-td-input selectpicker select-text height_drp is-valid" disabled>
                      <option value="" selected disabled>Select Agency Type</option>
                      @foreach($agencyCreateData['agency_type'] as $value)
                      <option value="{{ $value['id'] }}" data-code="{{$value['code']}}" @if($agencyDetail['core_agency_type_id']==$value['id']) selected="selected" @endif>{{ $value['name'] }}</option>
                      @endforeach
                    </select>
                    <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">Agency Type <span class="req-star">*</span></label>
                  </div>
                </div>
              </div>

              <div class="form-item form-float-style form-group">
                <input type="text" name="phone_no" value="{{ $agencyDetail['phone_no'] }}" id="phone_no" autocomplete="off" class="is-valid">
                <label for="phoneNo">Phone No<span class="req-star">*</span></label>
              </div>
              <div class="form-item form-float-style form-group">

                <input type="text" name="fax_no" value="{{ $agencyDetail['fax_no'] }}" id="fax_no" autocomplete="off" class="is-valid">
                <label for="faxNo">Fax No<span class="req-star">*</span></label>
              </div>
              <div class="form-item form-float-style form-group">

                <input type="text" value="{{ $agencyDetail['web_link'] }}" name="web_url" id="web_url" autocomplete="off" class="is-valid">
                <label for="weburl">WEB URL<span class="req-star">*</span></label>
              </div>
              <div class="form-floating form-float-style form-group required mb-3">
                <div class="form-item form-float-style serach-rem mb-3">
                  <div class="select top-space-rem after-drp form-float-style ">
                    <select data-live-search="true" name="status" id="status" class="order-td-input selectpicker select-text height_drp is-valid">
                      <option value="" disabled>Select Status</option>
                      <option @if($agencyDetail['status']=='active' ) selected="selected" @endif value="active" selected>Active</option>
                      <option @if($agencyDetail['status']=='inactive' ) selected="selected" @endif value="inactive">In-Active</option>
                      <option @if($agencyDetail['status']=='terminated' ) selected="selected" @endif value="terminated">Terminate</option>
                    </select>
                    <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">@lang('adminUser.status')</label>
                  </div>
                </div>
              </div>

              <div class="form-item form-float-style form-group">
                <input type="hidden" id="old_logo" name="old_logo" class="file-upload" value="{{$agencyDetail->logo}}" autocomplete="off">
                <input type="file" id="agency_logo" name="agency_logo" class="file-upload" autocomplete="off" class="is-valid">
                <label for="agencyLogo">Agency Logo <span class="req-star">*</span></label>
                <img src="{{$agencyDetail->logo}}" name="profile_image" width="150" height="150" class="img_prev">
              </div>
              <div class="form-item form-float-style form-group">

                <input type="text" value="{{ $agencyDetail['iata_number'] }}" name="iata_number" id="iata_number" maxlength="7" onkeypress="return isNumber(event)" autocomplete="off" class="is-valid">
                <label for="iata_number">IATA Number</label>
              </div>
              <hr style="border-top: 2px dashed black">
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

                <input type="text" class="is-valid removeReadOnly" value="{{ $agencyDetail['agencyAddress']['address1'] }}" id="address" name='address1' autocomplete="off" readonly>
                <label for="country">Address <span class="req-star">*</span></label>
              </div>

              <div class="form-item form-float-style form-group">

                <input type="text" class="is-valid workingCityAutocomplete1 removeReadOnly" value="{{ $agencyDetail['agencyAddress']['city'] }}" name='city' id="city" autocomplete="off" readonly>
                <label for="city">City <span class="req-star">*</span></label>
              </div>
              <div class="form-item form-float-style form-group">
                <input type="text" class="is-valid autocomplete1 removeReadOnly" value="{{ $agencyDetail['agencyAddress']['state'] }}" name='state' id="state" autocomplete="off" readonly>
                <label for="state">State <span class="req-star">*</span></label>
              </div>
              <div class="form-item form-float-style form-group">
                <input type="text" class="is-valid autocomplete1 removeReadOnly" value="{{ $agencyDetail['agencyAddress']['country'] }}" name='country' id="country" autocomplete="off" readonly>
                <label for="country">Country <span class="req-star">*</span></label>
              </div>
              <div class="form-item form-float-style form-group">

                <input type="text" class="is-valid removeReadOnly" value="{{ $agencyDetail['agencyAddress']['pincode'] }}" name="zip_code" id="zip_code" autocomplete="off" readonly>
                <label for="zipcode">Zip Code <span class="req-star">*</span></label>
              </div>


            </div>



            <div class="col-md-6 discount">

              <h5 class="setting-title">System Option</h5>

              <div class="row m-b-0" style="border:0.5px solid #cbbaba">
                <div class="">
                  <div class="form-check" style="color:#5a5151;margin-top: 10px">
                    <input type="checkbox" id="stop_by" name="stop_by" class="form-check-input" @if($agencyDetail['is_stop_buy']=='1' ) checked="checked" @endif value="1">
                    <label class="" for="stop_by">Stop Buy</label>
                  </div>

                  <div class="form-check" style="color:#5a5151">
                    <input type="checkbox" id="search_only" name="search_only" class="form-check-input" @if($agencyDetail['is_search_only']=='1' ) checked="checked" @endif value="1">
                    <label class="" for="search_only">Search Only</label>
                  </div>

                  <div class="form-check" style="color:#5a5151">
                    <input type="checkbox" id="radioSuccess14" name="cancel_right" class="form-check-input" @if($agencyDetail['is_cancel_right']=='1' ) checked="checked" @endif value="1">
                    <label class="" for="radioSuccess14">Cancel Right</label>
                  </div>
                </div>
              </div>
              @if(!$agencyCreateData['payment_option'])
              <div class="row m-b-0 form-group" style="border:0.5px solid #cbbaba;margin-top:10px;" id="paymentTypes">
                <div class="">
                  <span style="color:black">Payment Option <span class="text-red">*</span></span>
                  <input type="hidden" id="payment_option_empty" name="payment_option_empty" class="form-check-input" value="">
                  <label class="" for="travel"></label>
                </div>
              </div>
              @else
              <div class="row m-b-0 form-group" style="border:0.5px solid #cbbaba;margin-top:10px;" id="paymentTypes">
                <?php $agencyPaymentTypesIDs = App\Models\AgencyPaymentType::where('agency_id', $agencyDetail->id)->pluck('core_payment_type_id')->toArray();;
                ?>
                <div class="">
                  <span style="color:black">Payment Option <span class="text-red">*</span></span>
                  @foreach($agencyCreateData['payment_option'] as $value)
                  <div class="form-check" style="color:#5a5151;margin-top: 10px">
                    <input type="checkbox" id="payment_option" name="payment_option[]" class="form-check-input" value="{{ $value->id }}" @if(in_array($value->id, @$agencyPaymentTypesIDs)) checked="checked" @endif>
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
                  <input type="hidden" id="service_type_empty" name="service_type_empty" class="form-check-input" value="">
                  <label class="" for="travel"></label>
                </div>
              </div>
              @else
              <div class="row m-b-0 form-group" style="border:0.5px solid #cbbaba; margin-top:10px;" id="serviceType">
                <div class="">
                  <span style="color:black">Service Type <span class="text-red">*</span></span>
                  <?php $agencyServiceTypeIDs = App\Models\AgencyServiceType::where('agency_id', $agencyDetail->id)->pluck('core_service_type_id')->toArray();
                  ?>
                  @foreach($agencyCreateData['service_type'] as $value)
                  <div class="form-check" style="color:#5a5151;margin-top: 10px">
                    <input type="checkbox" id="service_type" name="service_type[]" class="form-check-input" value="{{ $value['id'] }}" @if(in_array($value['id'], @$agencyServiceTypeIDs)) checked="checked" @endif>
                    <label class="" for="travel">{{ $value['name'] }}</label>
                  </div>
                  @endforeach
                </div>
              </div>
              @endif



              @if(!$agencyCreateData['payment_gateway'])
              <div class="row m-b-0 form-group" style="border:0.5px solid #cbbaba; margin-top:10px;" id="paymentGateway">
                <div class="">
                  <span style="color:black">Agency Payment Gateway <span class="text-red">*</span></span>
                  <input type="hidden" id="payment_gateway_empty" name="payment_gateway_empty" class="form-check-input" value="">
                  <label class="" for="travel"></label>
                </div>
              </div>
              @else
              <div class="row m-b-0 form-group" style="border:0.5px solid #cbbaba; margin-top:10px;" id="paymentGateway">
                <div class="">
                  <?php $agencyPaymentsTypeIDs = App\Models\AgencyPaymentGateway::where('agency_id', $agencyDetail->id)->pluck('core_payment_gateway_id')->toArray();
                  ?>
                  <span style="color:black">Agency Payment Gateway <span class="text-red">*</span></span>
                  @foreach($agencyCreateData['payment_gateway'] as $value)
                  <div class="form-check" style="color:#5a5151;margin-top: 10px">
                    <input type="checkbox" id="payment_gateway" name="payment_gateway[]" class="form-check-input" value="{{ $value['id'] }}" @if(in_array($value['id'], @$agencyPaymentsTypeIDs)) checked="checked" @endif>
                    <label class="" for="travel">{{ $value['name'] }}</label>
                  </div>
                  @endforeach
                </div>
              </div>
              @endif
              <div class="row m-b-0 form-group" style="border:0.5px solid #cbbaba; margin-top:10px;">
                <div class="form-item form-float-style">
                  <span style="color:black">Enable Currencies <span class="text-red">*</span></span>
                  <select class="selectpicker is-valid" data-live-search="true" multiple value="" name="enable_currency_id[]" id="enable_currency_id" autocomplete="off" style="margin-top: 14px;">

                  </select>
                </div>
              </div>

            </div>
            <div class="cards-btn">
              <button type="submit" class="btn btn-success form-btn-success">Submit</button>
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
      var selectedOption = $('option:selected', this).attr('data-code');
      //hide payment types, service type and payment gateways if agency type is supplier
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

  /* Form Validation*/

  $(function() {
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
      // alert(isd_code);

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
      // ignore: [],
      rules: {
        'agency_name': {
          required: true,
          noSpace: true
        },
        'short_name': {
          required: true,
          noSpace: true
        },
        'contact': {
          required: true,
          noSpace: true

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
              agency_id: function() {
                return $("#agency_id").val();
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
              agency_id: function() {
                return $("#agency_id").val();
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
              agency_id: function() {
                return $("#agency_id").val();
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
              agency_id: function() {
                return $("#agency_id").val();
              },
              "_token": '{{ csrf_token() }}'
            }
          }
        },
        'status': {
          required: true,
        },
        'agency_logo': {
          required: false,
          extension: "jpeg|png|jpg",
          maxsize: 1000000,
        },
        'iata_number': {
          minlength: 7,
          maxlength: 7

        },
        'payment_option': {
          required: true,
        },
        'service_type_empty': {
          required: true,
        },
        'service_type[]': {
          required: true,
        },
        'enable_currency_id[]': {
          required: true,
        },
        'payment_gateway[]': {
          required: true,
        },
        'payment_gateway_empty': {
          required: true,
        },
        'payment_option_empty': {
          required: true,
        },
        'payment_option[]': {
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
        },
        'operator_mobile': {
          required: true,
          digits: true,
          minlength: 10,
          maxlength: 10
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
        'short_name': {
          required: "Please enter a Short Name",
        },
        'contact': {
          required: "Please enter a Contact Name",
        },
        'position': {
          required: "Please enter a Position name",
        },
        'email': {
          required: "Please enter an Email",
          email_regex: "Please enter valid email",
          remote: "Email already exist"
        },
        'license_number': {
          required: "Please enter a licence number",

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
          required: "Please enter a Fax No",
          digits: "Please enter numeric only",
          remote: "Fax No already exist"
        },
        'web_url': {
          required: "Please enter an Web Url",
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
        'payment_option_empty': {
          required: "Please add atleast one Payment option",
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
        form.submit();
      }
    });

  });

  /* Dropdown */
  $(document).ready(function() {
    var agencyCode = $('#agency_type_id').val();
    $.ajax({
      type: "GET",
      url: '{{ route("admin.get_currency") }}',
      data: {
        "_token": '{{ csrf_token() }}',
        "agencyCode": agencyCode
      },
      success: function(data) {
        optionoldselected = <?php echo json_encode($agencyDetail['agencyEnableCurrencies']) ?>;
        var obj = JSON.parse(data);

        $('#enable_currency_id').html('');

        $('#enable_currency_id').selectpicker('destroy');
        $('#enable_currency_id').selectpicker();
        $.each(obj, function(key, value) {
          optionText = value['name'];
          optionValue = value['id'];

          selected = "";

          $.each(optionoldselected, function(index1, value1) {
            if (optionValue == value1['currency_id']) {
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

    $("#agency_type_id").change(function() {
      var agencyCode = this.value;
      $.ajax({
        type: "GET",
        url: '{{ route("admin.get_currency") }}',
        data: {
          "_token": '{{ csrf_token() }}',
          "agencyCode": agencyCode
        },
        success: function(data) {


          var obj = JSON.parse(data);

          $('#enable_currency_id').html('');

          $('#enable_currency_id').selectpicker('destroy');
          $('#enable_currency_id').selectpicker();
          $.each(obj, function(key, value) {
            optionText = value['name'];
            optionValue = value['id'];
            selected = "";

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