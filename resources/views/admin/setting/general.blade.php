@extends('admin.layout.main')
@section('title', $header['title'])

@section('content')
<style>
  .select2-search__field {
    color: black !important;
  }

  textarea.select2-search__field {
    width: 41.50em !important;
  }

  .select2-container--default .select2-selection--multiple .select2-selection__choice {
    color: #000 !important;
  }

  .select2-container--default .select2-selection--multiple .select2-selection__choice__display {
    margin-left: 5px;
  }
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">

<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-4 mt-2">
      <div class="col-sm-12 d-flex breadcrumb-style">
        <h1 class="m-0">{{ $header['heading'] }}</h1>
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('general.dashboard') </a></li>
          <li class="breadcrumb-item active">@lang('general.moduleHeading')</li>
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
            <form id="basicForm" class="brdr-btm form validate mb-3 ml-0 mr-0 mt-0" action="{{ route('general.basic') }}" method="post" enctype="multipart/form-data">
              @csrf
              <h5 class="setting-title">@lang('general.basicInformation')</h5>
              <div class="form-item form-float-style">
                <input type="file" id="upload-color-logo" name="general|basic|colorLogo" class="file-upload" autocomplete="off" class="is-valid">
                <label for="upload-color-logo">@lang('general.uploadColorLogo') <span class="req-star">*</span></label>
              </div>
              @php
              $logo = "";
              @$logo = App\Models\Setting::where('config_key', 'general|basic|colorLogo')->get('value')[0]['value'];
              @endphp
              @if($logo)
              <image src="{{@$logo}}" width="90px" class="img_prev mt-0 mb-3 p-2">
                @endif
                <div class="form-item form-float-style">
                  <input type="file" id="upload-white-logo" name='general|basic|whiteLogo' class="upload-white-logo" autocomplete="off" class="is-valid">
                  <label for="upload-white-logo">@lang('general.uploadWhiteLogo') <span class="req-star">*</span></label>
                </div>
                @php
                $logo = "";
                @$logo = App\Models\Setting::where('config_key', 'general|basic|whiteLogo')->get('value')[0]['value'];
                @endphp
                @if($logo)
                <image src="{{$logo}}" width="90px" class="img_prev mt-0 mb-3 p-2">
                  @endif
                  <div class="form-item form-float-style">
                    <input type="file" id="upload-fav" name='general|basic|favicon' class="file-upload" autocomplete="off" class="is-valid">
                    <label for="upload-fav">@lang('general.uploadFaviconLogo') <span class="req-star">*</span></label>
                  </div>
                  @php
                  $logo = "";
                  @$logo = App\Models\Setting::where('config_key', 'general|basic|favicon')->get('value')[0]['value'];
                  @endphp
                  @if($logo)
                  <image src="{{$logo}}" width="90px" class="img_prev mt-0 mb-3 p-2">
                    @endif
                    <div class="form-item form-float-style">
                      @php
                      $value = "";
                      @$value = App\Models\Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'];
                      @endphp
                      <input type="text" value="{{ @old('general|basic|siteName')?@old('general|basic|siteName'):$value }}" id="compname" name='general|basic|siteName' autocomplete="off" class="is-valid">
                      <label for="compname">@lang('general.companyName') <span class="req-star">*</span></label>
                    </div>
                    <div class="form-item form-float-style">
                      @php
                      $value = "";
                      @$value = App\Models\Setting::where('config_key', 'general|basic|siteUrl')->get('value')[0]['value'];
                      @endphp
                      <input type="text" value="{{ @old('general|basic|siteUrl')?@old('general|basic|siteUrl'):$value }}" name='general|basic|siteUrl' id="weburl" autocomplete="off" class="is-valid">
                      <label for="weburl">@lang('general.companyWebsiteURl') <span class="req-star">*</span></label>
                    </div>
                    <div class="form-item form-float-style">
                      @php
                      $value = "";
                      @$value = App\Models\Setting::where('config_key', 'general|basic|siteEmail')->get('value')[0]['value'];
                      @endphp
                      <input type="text" value="{{ @old('general|basic|siteEmail')?@old('general|basic|siteEmail'):$value }}" name='general|basic|siteEmail' id="emailadd" autocomplete="off" class="is-valid">
                      <label for="emailadd">@lang('general.companyEmailAddress') <span class="req-star">*</span></label>
                    </div>
                    <div class="form-item form-float-style">
                      @php
                      $value = "";
                      @$value = App\Models\Setting::where('config_key', 'general|basic|sitePhoneNo')->get('value')[0]['value'];
                      @endphp
                      <input type="text" name='general|basic|sitePhoneNo' value="{{ @old('general|basic|sitePhoneNo')?@old('general|basic|sitePhoneNo'):$value }}" id="phoneno" autocomplete="off" class="is-valid">
                      <label for="phoneno">@lang('general.companyPhoneNumber') <span class="req-star">*</span></label>
                    </div>

                    <div class="form-item form-float-style">
                      @php
                      $value = "";
                      @$value = App\Models\Setting::where('config_key', 'general|basic|shortAboutUsEn')->get('value')[0]['value'];
                      @endphp
                      <textarea name='general|basic|shortAboutUsEn' id="shortAboutUsEn" autocomplete="off" class="is-valid">{{ @old('general|basic|shortAboutUsEn')?@old('general|basic|shortAboutUsEn'):$value }}</textarea>
                      <label for="shortAboutUsEn">@lang('general.shortAboutUsEnglish') <span class="req-star">*</span></label>
                    </div>
                    <div class="form-item form-float-style">
                      @php
                      $value = "";
                      @$value = App\Models\Setting::where('config_key', 'general|basic|shortAboutUsAr')->get('value')[0]['value'];
                      @endphp
                      <textarea name='general|basic|shortAboutUsAr' id="shortAboutUsAr" autocomplete="off" class="is-valid">{{ @old('general|basic|shortAboutUsAr')?@old('general|basic|shortAboutUsAr'):$value }}</textarea>
                      <label for="shortAboutUsAr">@lang('general.shortAboutUsArabic') <span class="req-star">*</span></label>
                    </div>

                    <div class="cards-btn">
                      <button type="submit" class="btn btn-success form-btn-success">@lang('general.submit')</button>
                    </div>
            </form>

          </div>
          <div class="col-md-6 discount">
            <h5 class="setting-title">@lang('general.maintenanceMode')</h5>
            <form class="brdr-btm form validate mb-3 ml-0 mr-0 mt-0" method="post" action="{{ route('general.maintenance') }}" id="maintenanceModeForm" name="maintenanceModeForm">
              @csrf

              @php
              $value = "";
              @$value = App\Models\Setting::where('config_key', 'general|maintenanceMode')->get('value')[0]['value'];
              @endphp
              <div class="q-a mb-2">
                <div class="form-check">
                  <input type="radio" id="radioSuccess10" name="general|maintenanceMode" data-change="maintenance_mode" class="form-check-input maintenanceMode" value="on" {{ (old('general|maintenanceMode') == "on" || $value == "on") ? 'checked' : '' }} checked>
                  <label class="form-check-label" for="radioSuccess10">@lang('general.on')</label>
                </div>


                <div class="form-check">
                  <input type="radio" id="radioSuccess11" name="general|maintenanceMode" data-change="maintenance_mode" class="form-check-input maintenanceMode" value="off" {{ (old('general|maintenanceMode') == "off" || $value == "off") ? 'checked' : '' }}>
                  <label class="form-check-label" for="radioSuccess11">@lang('general.off')</label>
                </div>

              </div>
              <div class="form-group" id="maintenance_mode">
                <div class="form-item form-float-style w-100">
                  @php
                  $value = "";
                  @$value = App\Models\Setting::where('config_key', 'general|maintenanceMode|message')->get('value')[0]['value'];
                  @endphp
                  <input type="text" id="maintenanceMessage" name="general|maintenanceMode|message" value="{{ @old('general|maintenanceMode|message')?@old('general|maintenanceMode|message'):$value }}" autocomplete="off" class="is-valid">
                  <label for="maintenanceMessage">@lang('general.maintenanceMessage')</label>
                </div>
              </div>
              <div class="cards-btn">
                <button type="submit" class="btn btn-success form-btn-success">@lang('general.submit')</button>
              </div>
            </form>

            <h5 class="setting-title">OTP Verification</h5>
            <form class="brdr-btm form validate mb-3 ml-0 mr-0 mt-0" method="post" action="{{ route('general.otp-phoneVerification') }}">
              @csrf

              @php
              $value = "";
              @$value = App\Models\Setting::where('config_key', 'general|otp|phoneVerification')->get('value')[0]['value'];
              @endphp
              <div class="q-a mb-2">
                <div class="form-check">
                  <input type="radio" id="radioSuccess12" name="general|otp|phoneVerification" class="form-check-input" value="on" {{ (old('general|otp|phoneVerification') == "on" || $value == "on") ? 'checked' : '' }} checked>
                  <label class="form-check-label" for="radioSuccess12">@lang('general.on')</label>
                </div>
                <div class="form-check">
                  <input type="radio" id="radioSuccess17" name="general|otp|phoneVerification" class="form-check-input" value="off" {{ (old('general|otp|phoneVerification') == "off" || $value == "off") ? 'checked' : '' }}>
                  <label class="form-check-label" for="radioSuccess17">@lang('general.off')</label>
                </div>
              </div>
              <div class="cards-btn">
                <button type="submit" class="btn btn-success form-btn-success">@lang('general.submit')</button>
              </div>
            </form>
            <h5 class="setting-title">@lang('general.androidAutoUpdate')</h5>
            <form class="brdr-btm form-horizontal mb-3" method="post" action="{{ route('general.mobile') }}" id="androidVersion" name="androidVersion">
              @csrf
              <input type="hidden" name="updateFor" value="android">
              @php
              $value = "";
              @$value = App\Models\Setting::where('config_key', 'general|androidUpdate')->get('value')[0]['value'];
              @endphp
              <div class="q-a mb-2">
                <div class="form-check">
                  <input type="radio" id="radioSuccess13" name="general|androidUpdate" class="form-check-input mobileUpdate" data-change='android_update' value="optional" {{ (old('general|androidUpdate') == "optional" || $value == "optional") ? 'checked' : '' }} checked>
                  <label class="form-check-label" for="radioSuccess13">@lang('general.optional')</label>
                </div>
                <div class="form-check">
                  <input type="radio" id="radioSuccess14" name="general|androidUpdate" class="form-check-input mobileUpdate" data-change='android_update' value="forcefully" {{ (old('general|androidUpdate') == "forcefully" || $value == "forcefully") ? 'checked' : '' }}>
                  <label class="form-check-label" for="radioSuccess14">@lang('general.forcefully')</label>
                </div>
              </div>
              <div class="form-group" id="android_update">
                <div class="form-item form-float-style w-100">
                  @php
                  $value = "";
                  @$value = App\Models\Setting::where('config_key', 'general|android|version')->get('value')[0]['value'];
                  @endphp
                  <input type="text" id="andrverison" name="general|android|version" value="{{ @old('general|android|version')?@old('general|android|version'):$value }}" autocomplete="off" class="is-valid">
                  <label for="andrverison">@lang('general.androidVersion')</label>
                </div>
              </div>
              <div class="cards-btn">
                <button type="submit" class="btn btn-success form-btn-success">@lang('general.submit')</button>
              </div>
            </form>
            <h5 class="setting-title">@lang('general.iosAutoUpdate')</h5>
            <form class="form-horizontal brdr-btm" method="post" action="{{ route('general.mobile') }}" id="iosVersion" name="iosVersion">
              @csrf
              <input type="hidden" name="updateFor" value="ios">
              @php
              $value = "";
              @$value = App\Models\Setting::where('config_key', 'general|iosUpdate')->get('value')[0]['value'];
              @endphp
              <div class="q-a mb-2">
                <div class="form-check">
                  <input type="radio" id="radioSuccess15" name="general|iosUpdate" class="form-check-input mobileUpdate" data-change='ios_update' value="optional" {{ (old('general|iosUpdate') == "optional" || $value == "optional") ? 'checked' : '' }} checked>
                  <label class="form-check-label" for="radioSuccess15">@lang('general.optional')</label>
                </div>
                <div class="form-check">
                  <input type="radio" id="radioSuccess16" name="general|iosUpdate" class="form-check-input mobileUpdate" data-change='ios_update' value="forcefully" {{ (old('general|iosUpdate') == "forcefully" || $value == "forcefully") ? 'checked' : '' }}>
                  <label class="form-check-label" for="radioSuccess16">@lang('general.forcefully')</label>
                </div>
              </div>
              <div id="ios_update">
                <div class="form-item form-float-style w-100">
                  @php
                  $value = "";
                  @$value = App\Models\Setting::where('config_key', 'general|ios|version')->get('value')[0]['value'];
                  @endphp
                  <input type="text" id="iosver" name="general|ios|version" value="{{ @old('general|ios|version')?@old('general|ios|version'):$value }}" autocomplete="off" class="is-valid">
                  <label for="iosver">@lang('general.iosVersion')</label>
                </div>
              </div>
              <div class="cards-btn">
                <button type="submit" class="btn btn-success form-btn-success">@lang('general.submit')</button>
              </div>
            </form>
           

          </div>
        </div>
        <div class="pt-0">
          <div class="col-md-12 discount p-0">
            <div class="row">
              <form class="form validate brdr-btm row mb-3" action="{{ route('general.additionalInformation') }}" method="post" id="additionalInfoForm">
                @csrf
                <div class="col-md-12 discount">
                  <div>
                    <h5 class="setting-title">@lang('general.additionalInformation')</h5>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-item form-float-style">
                          @php
                          $value = "";
                          @$value = App\Models\Setting::where('config_key', 'general|site|googleApiKey')->get('value')[0]['value'];
                          @endphp
                          <input type="text" name="general|site|googleApiKey" value="{{ @old('general|site|googleApiKey')?@old('general|site|googleApiKey'):$value }}" id="general|site|googleApiKey" autocomplete="off" class="is-valid">
                          <label for="googleapi">@lang('general.googleMapAPIKey') <span class="req-star">*</span></label>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-item form-float-style">
                          @php
                          $value = "";
                          @$value = App\Models\Setting::where('config_key', 'general|site|inquiryEmail')->get('value')[0]['value'];
                          @endphp
                          <input type="email" name="general|site|inquiryEmail" id="general|site|inquiryEmail" value="{{ @old('general|site|inquiryEmail')?@old('general|site|inquiryEmail'):$value }}" autocomplete="off" class="is-valid">
                          <label for="inqemail">@lang('general.inquiryEmailAddress') <span class="req-star">*</span></label>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-item form-float-style">
                          @php
                          $value = "";
                          @$value = App\Models\Setting::where('config_key', 'general|site|footerText')->get('value')[0]['value'];
                          @endphp
                          <input type="text" name="general|site|footerText" id="general|site|footerText" value="{{ @old('general|site|footerText')?@old('general|site|footerText'):$value }}" autocomplete="off" class="is-valid">
                          <label for="footer-text">@lang('general.footerText') <span class="req-star">*</span></label>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-floating">
                          <div class="select top-space-rem after-drp form-item form-float-style">
                            @php
                            $value = "";
                            @$value = App\Models\Setting::where('config_key', 'general|setting|ResetMonth')->get('value')[0]['value'];
                            @endphp
                            <select data-live-search="true" name="general|setting|ResetMonth" id="month" class="order-td-input selectpicker select-text height_drp is-valid">
                              <?php for ($i = 1; $i <= 12; $i++) : ?>
                                <?php
                                if ($i <= 9) {
                                  $i = '0' . $i;
                                }
                                ?>
                                <option {{ (old('general|setting|ResetMonth') == $i) || ($value == $i) ? 'selected' : ''}} value="<?= $i; ?>"><?= $i; ?></option>
                              <?php endfor ?>
                            </select>
                            <label class="select-label searchable-drp">@lang('general.orderIDResetMonth')</label>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="select top-space-rem after-drp form-item form-float-style">
                          @php
                          $value = "";
                          @$value = App\Models\Setting::where('config_key', 'general|site|defaultISDCode')->get('value')[0]['value'];
                          @endphp
                          <select data-live-search="true" id="isd_code" name="general|site|defaultISDCode" class="order-td-input selectpicker select-text height_drp is-valid">
                            <option value="">Select ISD Code</option>
                            @foreach($getCountry as $getIsdCodeName)
                            <option value="{{ $getIsdCodeName->isd_code }}" {{ (old('general|site|defaultISDCode') == $getIsdCodeName->isd_code) || ($value == $getIsdCodeName->isd_code) ? 'selected' : ''}}>
                              {{ $getIsdCodeName->isd_code }}
                              @foreach($getIsdCodeName->countryCode as $countries)
                              {{ $countries->country_name }}@if(!$loop->last), @endif
                              @endforeach
                            </option>
                            @endforeach
                          </select>
                          <label for="ISD Code" id="isd-code-customer">Default ISD Code<span class="req-star">*</span></label>
                          @error('email')
                          <span id="isd-error" class="error invalid-feedback-isd-code">{{ $message }}</span>
                          @enderror
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="select top-space-rem after-drp form-item form-float-style">
                          @php
                          $value = "";
                          @$value = App\Models\Setting::where('config_key', 'general|site|defaultLanguageCode')->get('value')[0]['value'];
                          @endphp
                          <select data-live-search="true" id="isd_code" name="general|site|defaultLanguageCode" class="order-td-input selectpicker select-text height_drp is-valid">
                            <option value="">Select Language</option>
                            @foreach($getLanguages as $language)
                            <option value="{{ $language->language_code }}" {{ (old('general|site|defaultLanguageCode') == $language->language_code) || ($value == $language->language_code) ? 'selected' : ''}}>
                              {{ $language->language_name }}
                            </option>
                            @endforeach
                          </select>
                          <label for="ISD Code" id="isd-code-customer">Default Language<span class="req-star">*</span></label>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-item form-float-style">
                          @php
                          $value = "";
                          @$value = App\Models\Setting::where('config_key', 'general|site|defaultVatPercentage')->get('value')[0]['value'];
                          @endphp
                          <input type="text" name="general|site|defaultVatPercentage" id="general|site|defaultVatPercentage" value="{{ @old('general|site|defaultVatPercentage')?@old('general|site|defaultVatPercentage'):$value }}" autocomplete="off" class="is-valid">
                          <label for="default-Vat-Percentage">Default VAT Percentage (%)<span class="req-star">*</span></label>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="select top-space-rem after-drp form-item form-float-style">
                          @php
                          $value = "";
                          @$value = App\Models\Setting::where('config_key', 'general|site|defaultCountry')->get('value')[0]['value'];
                          @endphp
                          @component('components.country_city_select', [
                          'name' => 'general|site|defaultCountry',
                          'id' => 'country_code',
                          'class' => 'order-td-input selectpicker1 select-text height_drp is-valid select2',
                          'selected' => @old('general|site|defaultCountry')?@old('general|site|defaultCountry'):$value,
                          'placeholder' => 'Select Country'
                          ])
                          @endcomponent
                          <label for="country" id="customer-country">Default Country <span class="req-star">*</span></label>
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div class="select top-space-rem after-drp form-item form-float-style">
                          @php
                          $value = "";
                          @$value = App\Models\Setting::where('config_key', 'general|site|arabic_speak_country')->get('value')[0]['value'];
                          @endphp
                          <?php
                          $value = explode(',', $value);
                          $selectedValues = is_array($value) ? $value : [$value];
                          ?>
                          @component('components.country_city_select', [
                          'name' => 'general|site|arabic_speak_country[]',
                          'id' => 'arabic_country_code',
                          'class' => 'order-td-input selectpicker1 select-text height_drp is-valid select2',
                          'selected' => $selectedValues,
                          'placeholder' => 'Select Country',
                          'multiple' => true
                          ])
                          @endcomponent
                          <label for="arabic_speak_country" id="customer-country">Arabic Speak Country <span class="req-star">*</span></label>
                        </div>
                      </div>
                    </div>
                    <div class="cards-btn">
                      <button type="submit" class="btn btn-success form-btn-success">@lang('general.submit')</button>
                    </div>
                  </div>
                </div>
              </form>

            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- /.row -->
  </div>
  <!--/. container-fluid -->
</section>
<!-- /.content -->

@endsection
@section('js')
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>

<script>
  //dealer
  $.validator.addMethod('dealerMinStrict', function(value, el, param) {
    if ($("#dealer_max_val").val() != "" && value != "") {
      var maxVal = parseInt($("#dealer_max_val").val());
      return value < maxVal;
    } else {
      return true;
    }
  }, 'value should minimum value than maximum value');
  $.validator.addMethod('dealerMaxStrict', function(value, el, param) {
    if ($("#dealer_min_val").val() != "" && value != "") {
      var minVal = parseInt($("#dealer_min_val").val());
      return value > minVal;
    } else {
      return true;
    }
  }, 'value should maximum value than minimum value');

  //exclusive dealer
  $.validator.addMethod('exDealerMinStrict', function(value, el, param) {
    if ($("#ex_dealer_max_val").val() != "" && value != "") {
      var maxVal = parseInt($("#ex_dealer_max_val").val());
      return value < maxVal;
    } else {
      return true;
    }
  }, 'value should minimum value than maximum value');
  $.validator.addMethod('exDealerMaxStrict', function(value, el, param) {
    if ($("#ex_dealer_min_val").val() != "" && value != "") {
      var minVal = parseInt($("#ex_dealer_min_val").val());
      return value > minVal;
    } else {
      return true;
    }
  }, 'value should maximum value than minimum value');

  //channel partner
  $.validator.addMethod('channelPartnerMinStrict', function(value, el, param) {
    if ($("#channel_partner_max_val").val() != "" && value != "") {
      var maxVal = parseInt($("#channel_partner_max_val").val());
      return value < maxVal;
    } else {
      return true;
    }
  }, 'value should minimum value than maximum value');
  $.validator.addMethod('channelPartnerMaxStrict', function(value, el, param) {
    if ($("#channel_partner_min_val").val() != "" && value != "") {
      var minVal = parseInt($("#channel_partner_min_val").val());
      return value > minVal;
    } else {
      return true;
    }
  }, 'value should maximum value than minimum value');

  //channel partner
  $.validator.addMethod('distributorMinStrict', function(value, el, param) {
    if ($("#dist_max_val").val() != "" && value != "") {
      var maxVal = parseInt($("#dist_max_val").val());
      return value < maxVal;
    } else {
      return true;
    }
  }, 'value should minimum value than maximum value');
  $.validator.addMethod('distributorMaxStrict', function(value, el, param) {
    if ($("#dist_min_val").val() != "" && value != "") {
      var minVal = parseInt($("#dist_min_val").val());
      return value > minVal;
    } else {
      return true;
    }
  }, 'value should maximum value than minimum value');

  $(function() {
    //jquery Form validation
    $('#finalOrderForm').validate({
      rules: {
        "general|finalOrderDiscount|dealer|min": {
          required: function(element) {
            return $("#dealer_min_disc").val() != "" || $("#dealer_max_val").val() != "" || $("#dealer_max_disc").val() != "";
          },
          dealerMinStrict: true
        },
        "general|finalOrderDiscount|dealer|minDiscount": {
          required: function(element) {
            return $("#dealer_min_val").val() != "" || $("#dealer_max_val").val() != "" || $("#dealer_max_disc").val() != "";
          }
        },
        "general|finalOrderDiscount|dealer|max": {
          required: function(element) {
            return $("#dealer_min_val").val() != "" || $("#dealer_min_disc").val() != "" || $("#dealer_max_disc").val() != "";
          },
          dealerMaxStrict: true
        },
        "general|finalOrderDiscount|dealer|maxDiscount": {
          required: function(element) {
            return $("#dealer_min_val").val() != "" || $("#dealer_min_disc").val() != "" || $("#dealer_max_val").val() != "";
          }
        },
        "general|finalOrderDiscount|exclusiveDealer|min": {
          required: function(element) {
            return $("#ex_dealer_min_disc").val() != "" || $("#ex_dealer_max_val").val() != "" || $("#ex_dealer_max_disc").val() != "";
          },
          exDealerMinStrict: true
        },
        "general|finalOrderDiscount|exclusiveDealer|minDiscount": {
          required: function(element) {
            return $("#ex_dealer_min_val").val() != "" || $("#ex_dealer_max_val").val() != "" || $("#ex_dealer_max_disc").val() != "";
          }
        },
        "general|finalOrderDiscount|exclusiveDealer|max": {
          required: function(element) {
            return $("#ex_dealer_min_val").val() != "" || $("#ex_dealer_min_disc").val() != "" || $("#ex_dealer_max_disc").val() != "";
          },
          exDealerMaxStrict: true
        },
        "general|finalOrderDiscount|exclusiveDealer|minDiscount": {
          required: function(element) {
            return $("#ex_dealer_min_val").val() != "" || $("#ex_dealer_min_disc").val() != "" || $("#ex_dealer_max_val").val() != "";
          }
        },
        "general|finalOrderDiscount|channelPartner|min": {
          required: function(element) {
            return $("#channel_partner_min_disc").val() != "" || $("#channel_partner_max_val").val() != "" || $("#channel_partner_max_disc").val() != "";
          },
          channelPartnerMinStrict: true
        },
        "general|finalOrderDiscount|channelPartner|minDiscount": {
          required: function(element) {
            return $("#channel_partner_min_val").val() != "" || $("#channel_partner_max_val").val() != "" || $("#channel_partner_max_disc").val() != "";
          }
        },
        "general|finalOrderDiscount|channelPartner|max": {
          required: function(element) {
            return $("#channel_partner_min_val").val() != "" || $("#channel_partner_min_disc").val() != "" || $("#channel_partner_max_disc").val() != "";
          },
          channelPartnerMaxStrict: true
        },
        "general|finalOrderDiscount|channelPartner|maxDiscount": {
          required: function(element) {
            return $("#channel_partner_min_val").val() != "" || $("#channel_partner_min_disc").val() != "" || $("#channel_partner_max_val").val() != "";
          }
        },
        "general|finalOrderDiscount|distributor|min": {
          required: function(element) {
            return $("#dist_min_disc").val() != "" || $("#dist_max_val").val() != "" || $("#dist_max_disc").val() != "";
          },
          distributorMinStrict: true
        },
        "general|finalOrderDiscount|distributor|minDiscount": {
          required: function(element) {
            return $("#dist_min_val").val() != "" || $("#dist_max_val").val() != "" || $("#dist_max_disc").val() != "";
          }
        },
        "general|finalOrderDiscount|distributor|max": {
          required: function(element) {
            return $("#dist_min_val").val() != "" || $("#dist_min_disc").val() != "" || $("#dist_max_disc").val() != "";
          },
          distributorMaxStrict: true
        },
        "general|finalOrderDiscount|distributor|maxDiscount": {
          required: function(element) {
            return $("#dist_min_val").val() != "" || $("#dist_min_disc").val() != "" || $("#dist_max_val").val() != "";
          }
        },
      },


      messages: {

        "general|finalOrderDiscount|dealer|min": {
          required: "Please enter minimum value"
        },
        "general|finalOrderDiscount|dealer|minDiscount": {
          required: "Please enter minimum discount value"
        },
        'general|finalOrderDiscount|dealer|max': {
          required: "Please enter maximum value"
        },
        'general|finalOrderDiscount|dealer|maxDiscount': {
          required: "Please enter maximum discount value"
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
      }
    });
  });
</script>

<script type="text/javascript">
  $(function() {

    // $(".collapse").trigger("click");

    $(".mobileUpdate").change(function() {
      var changeVal = $(this).attr('data-change');

      if ($(this).is(":checked") && $(this).val() === "forcefully") {
        $("#" + changeVal).show();
      } else {
        $("#" + changeVal).hide();
      }

    });
    $(".mobileUpdate").trigger('change');
  });

  $(function() {
    $(".maintenanceMode").change(function() {
      var maintenanceMode = $("input:radio.maintenanceMode:checked").val();

      if (maintenanceMode == "on") {

        $("#maintenance_mode").show();
      } else {

        $("#maintenance_mode").hide();
      }

    });
    $(".maintenanceMode").trigger('change');


  });
</script>
<script>
  // INCLUDE JQUERY & JQUERY UI 1.12.1
  $(function() {
    $('*[value=""]').removeClass('is-valid');

    $("#datepicker").datepicker({
      dateFormat: "dd-mm-yy",
      duration: "fast"
    });

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
  });

  const inputs = document.querySelectorAll("input");

  inputs.forEach((input) => {
    input.addEventListener("blur", (event) => {
      if (event.target.value) {
        input.classList.add("is-valid");
      } else {
        input.classList.remove("is-valid");
      }
    });
  });

  const textareas = document.querySelectorAll("textarea");

  textareas.forEach((textarea) => {
    textarea.addEventListener("blur", (event) => {
      if (event.target.value) {
        textarea.classList.add("is-valid");
      } else {
        textarea.classList.remove("is-valid");
      }
    });
  });
</script>
<script>
  $(function() {
    // Add a custom validation method for checking character length
    $.validator.addMethod("exactLength", function(value, element, param) {
        return this.optional(element) || value.length < param;
    }, "Please enter exactly {0} characters.");
    // check value is empty remove valid class
    $('*[value=""]').removeClass('is-valid');
    //jquery Form validation
    $('#basicForm').validate({
      rules: {
        'general|basic|colorLogo': {

          extension: "jpg|jpeg|png",
          maxsize: 1000000

        },
        'general|basic|whiteLogo': {

          extension: "jpg|jpeg|png",
          maxsize: 1000000


        },
        'general|basic|favicon': {

          extension: "png|jpeg|jpg",
          maxsize: 1000000


        },
        'general|basic|siteName': {
          required: true
        },
        'general|basic|siteUrl': {
          required: true

        },
        'general|basic|sitePhoneNo': {
          required: true,

        },
        'general|basic|shortAboutUsEn': {
          required: true,
          exactLength: 100
        },
        'general|basic|shortAboutUsAr': {
          required: true,
          exactLength: 100
        },
        'general|basic|siteEmail': {
          required: true,
          email: true
        }
      },


      messages: {
        'general|basic|siteName': {
          required: "Please enter a Company Name"
        },
        'general|basic|siteUrl': {
          required: "Please enter a Company Website URL",
          url: true,
        },
        'general|basic|colorLogo': {
          required: "Please Upload Color Logo Image",
          extension: "Please select image format must be .jpg, .jpeg or .png",
          maxsize: "Please upload image size less than 1MB"

        },
        'general|basic|whiteLogo': {
          required: "Please Upload White Logo Image",
          extension: "Please select image format must be .jpg, .jpeg or .png",
          maxsize: "Please upload image size less than 1MB"

        },
        'general|basic|favicon': {
          required: "Please Upload Favicon Image",
          extension: "Please select image format must be .jpg, .jpeg or .png",
          maxsize: "Please upload image size less than 1MB"

        },
        'general|basic|sitePhoneNo': {
          required: "Please enter a Company Phone Number "
        },
        'general|basic|shortAboutUsEn': {
          required: "Please enter a Short About Us English",
          exactLength: "Please enter exactly 100 characters."
        },
        'general|basic|shortAboutUsAr': {
          required: "Please enter a Short About Us Arabic",
          exactLength: "Please enter exactly 100 characters."
        },
        'general|basic|siteEmail': {
          required: "Please enter a Company Email Address"
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

    //maintenance message validtion
    //additional information validation
    $('#maintenanceModeForm').validate({
      rules: {
        'general|maintenanceMode|message': {
          required: true
        },
      },
      messages: {
        'general|maintenanceMode|message': {
          required: "Please enter a Maintenance Message"
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
      }
    });
    //additional information validation
    $('#additionalInfoForm').validate({
      rules: {
        'general|site|googleApiKey': {
          required: true
        },
        'general|site|inquiryEmail': {
          required: true,
          email: true
        },
        'general|site|footerText': {
          required: true
        },
        'general|setting|pagePerAdminRecords': {
          required: true,
          min: 1,
          max: 100
        },
        'general|setting|pagePerAPIRecords': {
          required: true,
          min: 1,
          max: 100
        },
        'general|site|defaultLanguageCode': {
          required: true,
        },
        'general|site|defaultVatPercentage': {
          required: true,
          number: true,
          range: [0, 100]
        },
      },
      messages: {
        'general|site|googleApiKey': {
          required: "Please enter a Google Map API Key"
        },
        'general|site|inquiryEmail': {
          required: "Please enter an Inquiry Email"
        },
        'general|site|footerText': {
          required: "Please enter a Footer Text",
        },
        'general|setting|pagePerAdminRecords': {
          required: "Please enter a Per Page Record for Admin",
        },
        'general|setting|pagePerAPIRecords': {
          required: "Please enter a Per Page Record for API",
        },
        'general|site|defaultLanguageCode': {
          required: "Please select a Default Language",
        },
        'general|site|defaultVatPercentage': {
          required: "Please enter a Default VAT Percentage",
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

    //bank details validation
    $('#bankDetailsForm').validate({
      rules: {
        'general|bankDetail|beneficiaryName': {

          required: true
        },
        'general|bankDetail|beneficiaryAccountNo': {
          required: true,
        },
        'general|bankDetail|bankName': {

          required: true

        },
        'general|bankDetail|branchName': {
          required: true,
        },
        'general|bankDetail|ifscCode': {
          required: true,
        }


      },


      messages: {
        'general|bankDetail|beneficiaryName': {
          required: "Please enter a Beneficiary's Name"
        },
        'general|bankDetail|beneficiaryAccountNo': {
          required: "Please enter a Benificiary's Account Number"

        },
        'general|bankDetail|bankName': {
          required: "Please enter a Bank Name",

        },
        'general|bankDetail|branchName': {
          required: "Please enter a Branch Name",
        },
        'general|bankDetail|ifscCode': {
          required: "Please enter an IFSC Code ",
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

    // android auto update
    $('#androidVersion').validate({
      rules: {
        "general|android|version": {
          required: true
        }

      },
      messages: {
        "general|android|version": {
          required: "Please enter an Android Version"
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

    $('#iosVersion').validate({
      rules: {
        "general|ios|version": {
          required: true
        }

      },
      messages: {
        "general|ios|version": {
          required: "Please enter an IOS Version"
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
</script>

<script>
  document.getElementById('flexCheckDefault1').onclick = function() {
    var checkboxes = document.getElementsByName('check');
    for (var checkbox of checkboxes) {
      checkbox.checked = this.checked;
    }
  }
</script>
@append