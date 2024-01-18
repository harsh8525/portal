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
          <li class="breadcrumb-item active">Invoice Setting</li>
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
          <div class="col-md-12 discount">
            <form id="generalForm" class="brdr-btm form validate mb-3 ml-0 mr-0 mt-0" action="{{ route('invoice.general') }}" method="post" enctype="multipart/form-data">
              @csrf
              <h3 class="setting-title">General</h3><br>
              <div class="col-md-12 d-none" id="component_div">
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
              <div class="row">
                <div class="col-6">
                  <div class="col-md-12">
                    <div class="form-item form-float-style">
                      @php
                      $logo = "";
                      @$logo = App\Models\Setting::where('config_key', 'invoice|general|logoEnglish')->get('value')[0]['value'];
                      @endphp
                      <!-- use component for multiple crop image in single page -->
                      <input type="hidden" id="oldQrCodeImage" name="oldQrCodeImage" class="file-upload" autocomplete="off" class="is-valid" value="{{ $logo ?? '' }}">
                      @component('components.multiple-crop-image', [
                      'name' => 'appDownloadPreference|qrCodeImage',
                      'id' => 'qr_code_image',
                      'class' => 'file-upload image is-valid'

                      ])
                      @endcomponent
                      <label for="upload-profile">Logo English</label>
                    </div>


                    <p class="upload-img-des mb-0">These images are visible in the customer page.
                      Support jpg, jpeg, or png files.
                    </p>

                    <div id='profile_image_section'>
                      @if(isset($logo))
                      <img data-toggle="popover" id="croppedImagePreview" height="" width="90px" src="{{ $logo ?? ''}}" alt="{{ $logo ?? '' }}">
                      @else
                      <img data-toggle="popover" id="croppedImagePreview" height="" width="90px" src="" alt="no-image" style="display: none;">
                      @endif
                      <label for="upload-profile">Logo Arabic</label>
                    </div>
                  </div>
                  <input type="hidden" id="croppedImage" name="appDownloadPreference|qrCodeImage" value="">
                  <div class="col-md-12">
                    <div class="form-item form-float-style">
                      @php
                      $logo = "";
                      @$logo = App\Models\Setting::where('config_key', 'invoice|general|logoArabic')->get('value')[0]['value'];
                      @endphp
                      <!-- use component for multiple crop image in single page -->
                      <input type="hidden" id="oldBannerImageEn" name="oldBannerImageEn" class="file-upload" autocomplete="off" class="is-valid" value="{{ $logo ?? '' }}">
                      @component('components.multiple-crop-image', [
                      'name' => 'appDownloadPreference|bannerImageEn',
                      'id' => 'banner_image_en',
                      'class' => 'file-upload image is-valid'
                      ])
                      @endcomponent
                      <label for="upload-profile">Logo Arabic</label>
                    </div>


                    <p class="upload-img-des mb-0">These images are visible in the customer page.
                      Support jpg, jpeg, or png files.
                    </p>

                    <div id='profile_image_section'>
                      @if(isset($logo))
                      <img data-toggle="popover" id="secondCroppedImagePreview" height="" width="90" src="{{ $logo ?? ''}}" alt="{{ $logo ?? '' }}">
                      @else
                      <img data-toggle="popover" id="secondCroppedImagePreview" height="" width="90" src="" alt="no-image" style="display: none;">
                      @endif
                      <label for="upload-profile">Download Banner Image English</label>

                    </div>
                  </div>
                  <input type="hidden" id="secondCroppedImage" name="appDownloadPreference|bannerImageEn" value="">
                  <div class="form-item form-float-style">
                    @php
                    $value = "";
                    @$value = App\Models\Setting::where('config_key', 'invoice|general|billingCompanyNameEn')->get('value')[0]['value'];
                    @endphp
                    <input type="text" value="{{ @old('invoice|general|billingCompanyNameEn')?@old('invoice|general|billingCompanyNameEn'):$value }}" id="billingCompanyNameEn" name='invoice|general|billingCompanyNameEn' autocomplete="off" class="is-valid">
                    <label for="compname">Billing Company Name English <span class="req-star">*</span></label>
                  </div>
                  <div class="form-item form-float-style">
                    @php
                    $value = "";
                    @$value = App\Models\Setting::where('config_key', 'invoice|general|billingCompanyNameAr')->get('value')[0]['value'];
                    @endphp
                    <input type="text" value="{{ @old('invoice|general|billingCompanyNameAr')?@old('invoice|general|billingCompanyNameAr'):$value }}" id="billingCompanyNameAr" name='invoice|general|billingCompanyNameAr' autocomplete="off" class="is-valid">
                    <label for="compname">Billing Company Name Arabic <span class="req-star">*</span></label>
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-item form-float-style">
                    @php
                    $value = "";
                    @$value = App\Models\Setting::where('config_key', 'invoice|general|agencyIATANumber')->get('value')[0]['value'];
                    @endphp
                    <input type="text" value="{{ @old('invoice|general|agencyIATANumber')?@old('invoice|general|agencyIATANumber'):$value }}" id="agencyIATANumber" name='invoice|general|agencyIATANumber' autocomplete="off" class="is-valid">
                    <label for="shortAboutUs">Agency IATA Number<span class="req-star">*</span></label>
                  </div>
                  <div class="form-item form-float-style">
                    @php
                    $value = "";
                    @$value = App\Models\Setting::where('config_key', 'invoice|general|cityName')->get('value')[0]['value'];
                    @endphp
                    <input type="text" value="{{ @old('invoice|general|cityName')?@old('invoice|general|cityName'):$value }}" id="cityName" name='invoice|general|cityName' autocomplete="off" class="is-valid">
                    <label for="shortAboutUs">City<span class="req-star">*</span></label>
                  </div>
                  <div class="form-item form-float-style">
                    @php
                    $value = "";
                    @$value = App\Models\Setting::where('config_key', 'invoice|general|countryName')->get('value')[0]['value'];
                    @endphp
                    <input type="text" value="{{ @old('invoice|general|countryName')?@old('invoice|general|countryName'):$value }}" id="countryName" name='invoice|general|countryName' autocomplete="off" class="is-valid">
                    <label for="shortAboutUs">Country<span class="req-star">*</span></label>
                  </div>
                  <div class="form-item form-float-style">
                    @php
                    $value = "";
                    @$value = App\Models\Setting::where('config_key', 'invoice|general|addressEn')->get('value')[0]['value'];
                    @endphp
                    <textarea name='invoice|general|addressEn' id="addressEn" autocomplete="off" class="is-valid">{{ @old('invoice|general|addressEn')?@old('invoice|general|addressEn'):$value }}</textarea>
                    <label for="shortAboutUs">Address English<span class="req-star">*</span></label>
                  </div>
                  <div class="form-item form-float-style">
                    @php
                    $value = "";
                    @$value = App\Models\Setting::where('config_key', 'invoice|general|addressAr')->get('value')[0]['value'];
                    @endphp
                    <textarea name='invoice|general|addressAr' id="addressAr" autocomplete="off" class="is-valid">{{ @old('invoice|general|addressAr')?@old('invoice|general|addressAr'):$value }}</textarea>
                    <label for="shortAboutUs">Address Arabic<span class="req-star">*</span></label>
                  </div>
                </div>
                <div class="cards-btn">
                  <button type="submit" class="btn btn-success form-btn-success">@lang('general.submit')</button>
                </div>
              </div>
            </form>
          </div>
          <div class="row mb-3">
            <div class="col-md-6 discount">
              <form id="salesForm" class="brdr-btm form validate mb-3 ml-0 mr-0 mt-0" action="{{ route('invoice.sales') }}" method="post" enctype="multipart/form-data">
                @csrf
                <h3 class="setting-title">Sales</h3><br>
                <h5 class="setting-title">Invoice Guideline</h5>
                <div class="form-item form-float-style">
                  @php
                  $value = "";
                  @$value = App\Models\Setting::where('config_key', 'invoice|sales|invoice|guidelineTextEn')->get('value')[0]['value'];
                  @endphp
                  <input type="text" value="{{ @old('invoice|sales|invoice|guidelineTextEn')?@old('invoice|sales|invoice|guidelineTextEn'):$value }}" id="guidelineTextEn" name='invoice|sales|invoice|guidelineTextEn' autocomplete="off" class="is-valid">
                  <label for="compname">Guideline Text English <span class="req-star">*</span></label>
                </div>
                <div class="form-item form-float-style">
                  @php
                  $value = "";
                  @$value = App\Models\Setting::where('config_key', 'invoice|sales|invoice|guidelineTextAr')->get('value')[0]['value'];
                  @endphp
                  <input type="text" value="{{ @old('invoice|sales|invoice|guidelineTextAr')?@old('invoice|sales|invoice|guidelineTextAr'):$value }}" id="guidelineTextAr" name='invoice|sales|invoice|guidelineTextAr' autocomplete="off" class="is-valid">
                  <label for="compname">Guideline Text Arabic <span class="req-star">*</span></label>
                </div>
                <div class="form-item form-float-style">
                  @php
                  $value = "";
                  @$value = App\Models\Setting::where('config_key', 'invoice|sales|invoice|termsAndConditionsEn')->get('value')[0]['value'];
                  @endphp
                  <textarea name='invoice|sales|invoice|termsAndConditionsEn' id="editor_en" autocomplete="off" class="is-valid editor">{{ @old('invoice|sales|invoice|termsAndConditionsEn')?@old('invoice|sales|invoice|termsAndConditionsEn'):$value }}</textarea>
                  <label for="termsAndConditionsEn">Terms & Conditions English<span class="req-star">*</span></label>
                </div>
                <div class="form-item form-float-style">
                  @php
                  $value = "";
                  @$value = App\Models\Setting::where('config_key', 'invoice|sales|invoice|termsAndConditionsAr')->get('value')[0]['value'];
                  @endphp
                  <textarea name='invoice|sales|invoice|termsAndConditionsAr' id="" autocomplete="off" class="is-valid editor">{{ @old('invoice|sales|invoice|termsAndConditionsAr')?@old('invoice|sales|invoice|termsAndConditionsAr'):$value }}</textarea>
                  <label for="termsAndConditionsAr">Terms & Conditions Arabic<span class="req-star">*</span></label>
                </div>
                <div class="form-item form-float-style">
                  @php
                  $value = "";
                  @$value = App\Models\Setting::where('config_key', 'invoice|sales|invoice|notesEn')->get('value')[0]['value'];
                  @endphp
                  <textarea name='invoice|sales|invoice|notesEn' id="editor_en" autocomplete="off" class="is-valid editor">{{ @old('invoice|sales|invoice|notesEn')?@old('invoice|sales|invoice|notesEn'):$value }}</textarea>
                  <label for="shortAboutUs">Notes English<span class="req-star">*</span></label>
                </div>
                <div class="form-item form-float-style brdr-btm">
                  @php
                  $value = "";
                  @$value = App\Models\Setting::where('config_key', 'invoice|sales|invoice|notesAr')->get('value')[0]['value'];
                  @endphp
                  <textarea name='invoice|sales|invoice|notesAr' id="editor_ar" autocomplete="off" class="is-valid editor">{{ @old('invoice|sales|invoice|notesAr')?@old('invoice|sales|invoice|notesAr'):$value }}</textarea>
                  <label for="shortAboutUs">Notes Arabic<span class="req-star">*</span></label>
                </div>
                <h5 class="setting-title">Refund Guideline</h5>
                <div class="form-item form-float-style">
                  @php
                  $value = "";
                  @$value = App\Models\Setting::where('config_key', 'invoice|sales|refGuidelineTextEn')->get('value')[0]['value'];
                  @endphp
                  <input type="text" value="{{ @old('invoice|sales|refGuidelineTextEn')?@old('invoice|sales|refGuidelineTextEn'):$value }}" id="refGuidelineTextEn" name='invoice|sales|refGuidelineTextEn' autocomplete="off" class="is-valid">
                  <label for="compname">Guideline Text English <span class="req-star">*</span></label>
                </div>
                <div class="form-item form-float-style">
                  @php
                  $value = "";
                  @$value = App\Models\Setting::where('config_key', 'invoice|sales|refGuidelineTextAr')->get('value')[0]['value'];
                  @endphp
                  <input type="text" value="{{ @old('invoice|sales|refGuidelineTextAr')?@old('invoice|sales|refGuidelineTextAr'):$value }}" id="refGuidelineTextAr" name='invoice|sales|refGuidelineTextAr' autocomplete="off" class="is-valid">
                  <label for="compname">Guideline Text Arabic <span class="req-star">*</span></label>
                </div>
                <div class="form-item form-float-style">
                  @php
                  $value = "";
                  @$value = App\Models\Setting::where('config_key', 'invoice|sales|refTermsAndConditionsEn')->get('value')[0]['value'];
                  @endphp
                  <textarea name='invoice|sales|refTermsAndConditionsEn' id="editor_en" autocomplete="off" class="is-valid editor">{{ @old('invoice|sales|refTermsAndConditionsEn')?@old('invoice|sales|refTermsAndConditionsEn'):$value }}</textarea>
                  <label for="refTermsAndConditionsEn">Terms & Conditions English<span class="req-star">*</span></label>
                </div>
                <div class="form-item form-float-style">
                  @php
                  $value = "";
                  @$value = App\Models\Setting::where('config_key', 'invoice|sales|refTermsAndConditionsAr')->get('value')[0]['value'];
                  @endphp
                  <textarea name='invoice|sales|refTermsAndConditionsAr' id="editor_ar" autocomplete="off" class="is-valid editor">{{ @old('invoice|sales|refTermsAndConditionsAr')?@old('invoice|sales|refTermsAndConditionsAr'):$value }}</textarea>
                  <label for="refTermsAndConditionsAr">Terms & Conditions Arabic<span class="req-star">*</span></label>
                </div>
                <div class="form-item form-float-style">
                  @php
                  $value = "";
                  @$value = App\Models\Setting::where('config_key', 'invoice|sales|refNotesEn')->get('value')[0]['value'];
                  @endphp
                  <textarea name='invoice|sales|refNotesEn' id="editor_en" autocomplete="off" class="is-valid editor">{{ @old('invoice|sales|refNotesEn')?@old('invoice|sales|refNotesEn'):$value }}</textarea>
                  <label for="refNotesEn">Notes English<span class="req-star">*</span></label>
                </div>
                <div class="form-item form-float-style">
                  @php
                  $value = "";
                  @$value = App\Models\Setting::where('config_key', 'invoice|sales|refNotesAr')->get('value')[0]['value'];
                  @endphp
                  <textarea name='invoice|sales|refNotesAr' id="editor_ar" autocomplete="off" class="is-valid editor">{{ @old('invoice|sales|refNotesAr')?@old('invoice|sales|refNotesAr'):$value }}</textarea>
                  <label for="refNotesAr">Notes Arabic<span class="req-star">*</span></label>
                </div>

                <div class="cards-btn">
                  <button type="submit" class="btn btn-success form-btn-success">@lang('general.submit')</button>
                </div>
              </form>
            </div>
            <!-- </div> -->
            <div class="col-md-6 discount">
              <form id="purchaseForm" class="brdr-btm form validate mb-3 ml-0 mr-0 mt-0" action="{{ route('invoice.purchase') }}" method="post" enctype="multipart/form-data">
                @csrf
                <h3 class="setting-title">Purchase</h3><br>
                <h5 class="setting-title">Invoice Guideline</h5>
                <div class="form-item form-float-style">
                  @php
                  $value = "";
                  @$value = App\Models\Setting::where('config_key', 'invoice|purchase|guidelineTextEn')->get('value')[0]['value'];
                  @endphp
                  <input type="text" value="{{ @old('invoice|purchase|guidelineTextEn')?@old('invoice|purchase|guidelineTextEn'):$value }}" id="guidelineTextEn" name='invoice|purchase|guidelineTextEn' autocomplete="off" class="is-valid">
                  <label for="compname">Guideline Text English <span class="req-star">*</span></label>
                </div>
                <div class="form-item form-float-style">
                  @php
                  $value = "";
                  @$value = App\Models\Setting::where('config_key', 'invoice|purchase|guidelineTextAr')->get('value')[0]['value'];
                  @endphp
                  <input type="text" value="{{ @old('invoice|purchase|guidelineTextAr')?@old('invoice|purchase|guidelineTextAr'):$value }}" id="guidelineTextAr" name='invoice|purchase|guidelineTextAr' autocomplete="off" class="is-valid">
                  <label for="compname">Guideline Text Arabic <span class="req-star">*</span></label>
                </div>
                <div class="form-item form-float-style">
                  @php
                  $value = "";
                  @$value = App\Models\Setting::where('config_key', 'invoice|purchase|termsAndConditionsEn')->get('value')[0]['value'];
                  @endphp
                  <textarea name='invoice|purchase|termsAndConditionsEn' id="editor_en" autocomplete="off" class="is-valid editor">{{ @old('invoice|purchase|termsAndConditionsEn')?@old('invoice|purchase|termsAndConditionsEn'):$value }}</textarea>
                  <label for="shortAboutUs">Terms & Conditions English<span class="req-star">*</span></label>
                </div>
                <div class="form-item form-float-style">
                  @php
                  $value = "";
                  @$value = App\Models\Setting::where('config_key', 'invoice|purchase|termsAndConditionsAr')->get('value')[0]['value'];
                  @endphp
                  <textarea name='invoice|purchase|termsAndConditionsAr' id="editor_ar" autocomplete="off" class="is-valid editor">{{ @old('invoice|purchase|termsAndConditionsAr')?@old('invoice|purchase|termsAndConditionsAr'):$value }}</textarea>
                  <label for="shortAboutUs">Terms & Conditions Arabic<span class="req-star">*</span></label>
                </div>
                <div class="form-item form-float-style">
                  @php
                  $value = "";
                  @$value = App\Models\Setting::where('config_key', 'invoice|purchase|notesEn')->get('value')[0]['value'];
                  @endphp
                  <textarea name='invoice|purchase|notesEn' id="editor_en" autocomplete="off" class="is-valid editor">{{ @old('invoice|purchase|notesEn')?@old('invoice|purchase|notesEn'):$value }}</textarea>
                  <label for="shortAboutUs">Notes English<span class="req-star">*</span></label>
                </div>
                <div class="form-item form-float-style brdr-btm">
                  @php
                  $value = "";
                  @$value = App\Models\Setting::where('config_key', 'invoice|purchase|notesAr')->get('value')[0]['value'];
                  @endphp
                  <textarea name='invoice|purchase|notesAr' id="editor_ar" autocomplete="off" class="is-valid editor">{{ @old('invoice|purchase|notesAr')?@old('invoice|purchase|notesAr'):$value }}</textarea>
                  <label for="shortAboutUs">Notes Arabic<span class="req-star">*</span></label>
                </div>
                <h5 class="setting-title">Debit & Credit Note</h5>
                <div class="form-item form-float-style">
                  @php
                  $value = "";
                  @$value = App\Models\Setting::where('config_key', 'invoice|purchase|debitCredGuidelineTextEn')->get('value')[0]['value'];
                  @endphp
                  <input type="text" value="{{ @old('invoice|purchase|debitCredGuidelineTextEn')?@old('invoice|purchase|debitCredGuidelineTextEn'):$value }}" id="refGuidelineTextEn" name='invoice|purchase|debitCredGuidelineTextEn' autocomplete="off" class="is-valid">
                  <label for="compname">Guideline Text English <span class="req-star">*</span></label>
                </div>
                <div class="form-item form-float-style">
                  @php
                  $value = "";
                  @$value = App\Models\Setting::where('config_key', 'invoice|purchase|debitCredGuidelineTextAr')->get('value')[0]['value'];
                  @endphp
                  <input type="text" value="{{ @old('invoice|purchase|debitCredGuidelineTextAr')?@old('invoice|purchase|debitCredGuidelineTextAr'):$value }}" id="refGuidelineTextAr" name='invoice|purchase|debitCredGuidelineTextAr' autocomplete="off" class="is-valid">
                  <label for="compname">Guideline Text Arabic <span class="req-star">*</span></label>
                </div>
                <div class="form-item form-float-style">
                  @php
                  $value = "";
                  @$value = App\Models\Setting::where('config_key', 'invoice|purchase|debitCredTermsAndConditionsEn')->get('value')[0]['value'];
                  @endphp
                  <textarea name='invoice|purchase|debitCredTermsAndConditionsEn' id="editor_en" autocomplete="off" class="is-valid editor">{{ @old('invoice|purchase|debitCredTermsAndConditionsEn')?@old('invoice|purchase|debitCredTermsAndConditionsEn'):$value }}</textarea>
                  <label for="shortAboutUs">Terms & Conditions English<span class="req-star">*</span></label>
                </div>
                <div class="form-item form-float-style">
                  @php
                  $value = "";
                  @$value = App\Models\Setting::where('config_key', 'invoice|purchase|debitCredTermsAndConditionsAr')->get('value')[0]['value'];
                  @endphp
                  <textarea name='invoice|purchase|debitCredTermsAndConditionsAr' id="editor_ar" autocomplete="off" class="is-valid editor">{{ @old('invoice|purchase|debitCredTermsAndConditionsAr')?@old('invoice|purchase|debitCredTermsAndConditionsAr'):$value }}</textarea>
                  <label for="shortAboutUs">Terms & Conditions Arabic<span class="req-star">*</span></label>
                </div>
                <div class="form-item form-float-style">
                  @php
                  $value = "";
                  @$value = App\Models\Setting::where('config_key', 'invoice|purchase|debitCredNotesEn')->get('value')[0]['value'];
                  @endphp
                  <textarea name='invoice|purchase|debitCredNotesEn' id="editor_en" autocomplete="off" class="is-valid editor">{{ @old('invoice|purchase|debitCredNotesEn')?@old('invoice|purchase|debitCredNotesEn'):$value }}</textarea>
                  <label for="shortAboutUs">Notes English<span class="req-star">*</span></label>
                </div>
                <div class="form-item form-float-style">
                  @php
                  $value = "";
                  @$value = App\Models\Setting::where('config_key', 'invoice|purchase|debitCredNotesAr')->get('value')[0]['value'];
                  @endphp
                  <textarea name='invoice|purchase|debitCredNotesAr' id="editor_ar" autocomplete="off" class="is-valid editor">{{ @old('invoice|purchase|debitCredNotesAr')?@old('invoice|purchase|debitCredNotesAr'):$value }}</textarea>
                  <label for="shortAboutUs">Notes Arabic<span class="req-star">*</span></label>
                </div>

                <div class="cards-btn">
                  <button type="submit" class="btn btn-success form-btn-success">@lang('general.submit')</button>
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


  $(function() {
    //jquery Form validation
    $('#salesForm').validate({
      ignore: "",
      rules: {
        "invoice|sales|invoice|guidelineTextEn": {
          required: true
        },
        "invoice|sales|invoice|guidelineTextAr": {
          required: true
        },
        "invoice|sales|invoice|termsAndConditionsEn": {
          required: true
        },
        "invoice|sales|invoice|termsAndConditionsAr": {
          required: true
        },
        "invoice|sales|invoice|notesEn": {
          required: true
        },
        "invoice|sales|invoice|notesAr": {
          required: true
        },
        "invoice|sales|refGuidelineTextEn": {
          required: true
        },
        "invoice|sales|refGuidelineTextAr": {
          required: true
        },
        "invoice|sales|refTermsAndConditionsEn": {
          required: true
        },
        "invoice|sales|refTermsAndConditionsAr": {
          required: true
        },
        "invoice|sales|refNotesEn": {
          required: true
        },
        "invoice|sales|refNotesAr": {
          required: true
        },
      },


      messages: {

        "invoice|sales|invoice|guidelineTextEn": {
          required: "Please enter a Guideline Text English"
        },
        "invoice|sales|invoice|guidelineTextAr": {
          required: "Please enter a Guideline Text Arabic"
        },
        "invoice|sales|invoice|termsAndConditionsEn": {
          required: "Please enter a Terms & Conditions English"
        },
        "invoice|sales|invoice|termsAndConditionsAr": {
          required: "Please enter a Terms & Conditions Arabic"
        },
        "invoice|sales|invoice|notesEn": {
          required: "Please enter a Notes English"
        },
        "invoice|sales|invoice|notesAr": {
          required: "Please enter a Notes Arabic"
        },
        "invoice|sales|refGuidelineTextEn": {
          required: "Please enter a Guideline Text English"
        },
        "invoice|sales|refGuidelineTextAr": {
          required: "Please enter a Guideline Text Arabic"
        },
        "invoice|sales|refTermsAndConditionsEn": {
          required: "Please enter a Terms & Conditions English"
        },
        "invoice|sales|refTermsAndConditionsAr": {
          required: "Please enter a Terms & Conditions Arabic"
        },
        "invoice|sales|refNotesEn": {
          required: "Please enter a Notes English"
        },
        "invoice|sales|refNotesAr": {
          required: "Please enter a Notes Arabic"
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
    // check value is empty remove valid class
    $('*[value=""]').removeClass('is-valid');
    //jquery Form validation
    $('#purchaseForm').validate({
      ignore: "",
      rules: {
        "invoice|purchase|guidelineTextEn": {
          required: true
        },
        "invoice|purchase|guidelineTextAr": {
          required: true
        },
        "invoice|purchase|termsAndConditionsEn": {
          required: true
        },
        "invoice|purchase|termsAndConditionsAr": {
          required: true
        },
        "invoice|purchase|notesEn": {
          required: true
        },
        "invoice|purchase|notesAr": {
          required: true
        },
        "invoice|purchase|debitCredGuidelineTextEn": {
          required: true
        },
        "invoice|purchase|debitCredGuidelineTextAr": {
          required: true
        },
        "invoice|purchase|debitCredTermsAndConditionsEn": {
          required: true
        },
        "invoice|purchase|debitCredTermsAndConditionsAr": {
          required: true
        },
        "invoice|purchase|debitCredNotesEn": {
          required: true
        },
        "invoice|purchase|debitCredNotesAr": {
          required: true
        },
      },


      messages: {

        "invoice|purchase|guidelineTextEn": {
          required: "Please enter a Guideline Text English"
        },
        "invoice|purchase|guidelineTextAr": {
          required: "Please enter a Guideline Text Arabic"
        },
        "invoice|purchase|termsAndConditionsEn": {
          required: "Please enter a Terms & Conditions English"
        },
        "invoice|purchase|termsAndConditionsAr": {
          required: "Please enter a Terms & Conditions Arabic"
        },
        "invoice|purchase|notesEn": {
          required: "Please enter a Notes English"
        },
        "invoice|purchase|notesAr": {
          required: "Please enter a Notes Arabic"
        },
        "invoice|purchase|debitCredGuidelineTextEn": {
          required: "Please enter a Guideline Text English"
        },
        "invoice|purchase|debitCredGuidelineTextAr": {
          required: "Please enter a Guideline Text Arabic"
        },
        "invoice|purchase|debitCredTermsAndConditionsEn": {
          required: "Please enter a Terms & Conditions English"
        },
        "invoice|purchase|debitCredTermsAndConditionsAr": {
          required: "Please enter a Terms & Conditions Arabic"
        },
        "invoice|purchase|debitCredNotesEn": {
          required: "Please enter a Notes English"
        },
        "invoice|purchase|debitCredNotesAr": {
          required: "Please enter a Notes Arabic"
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
    $('#generalForm').validate({
      rules: {
        'invoice|general|logoEnglish': {
          accept: "image/jpg,image/jpeg,image/png",
          maxsize: 1000000,
        },
        'appDownloadPreference|qrCodeImage': {
          accept: "image/jpg,image/jpeg,image/png",
          maxsize: 1000000,
        },
        'appDownloadPreference|bannerImageEn': {
          accept: "image/jpg,image/jpeg,image/png",
          maxsize: 1000000,
        },
        'invoice|general|logoArabic': {
          accept: "image/jpg,image/jpeg,image/png",
          maxsize: 1000000,
        },
        'invoice|general|billingCompanyNameEn': {
          required: true
        },
        'invoice|general|billingCompanyNameAr': {
          required: true,
        },
        'invoice|general|agencyIATANumber': {
          required: true,
          minlength: 7,
          maxlength: 7,
        },
        'invoice|general|cityName': {
          required: true,
        },
        'invoice|general|countryName': {
          required: true,
        },
        'invoice|general|addressEn': {
          required: true,
        },
        'invoice|general|addressAr': {
          required: true,
        },

      },
      messages: {
        'invoice|general|logoEnglish': {
          accept: "Please select image format must be .jpg, .jpeg or .png.",
          maxsize: "Please upload image size less than 1MB"
        },
        'appDownloadPreference|qrCodeImage': {
          accept: "Please select image format must be .jpg, .jpeg or .png.",
          maxsize: "Please upload image size less than 1MB"
        },
        'appDownloadPreference|bannerImageEn': {
          accept: "Please select image format must be .jpg, .jpeg or .png.",
          maxsize: "Please upload image size less than 1MB"
        },
        'invoice|general|logoArabic': {
          accept: "Please select image format must be .jpg, .jpeg or .png.",
          maxsize: "Please upload image size less than 1MB"
        },
        'invoice|general|billingCompanyNameEn': {
          required: "Please enter a Billing Company Name English",
        },
        'invoice|general|billingCompanyNameAr': {
          required: "Please enter a Billing Company Name Arabic",
        },
        'invoice|general|agencyIATANumber': {
          required: "Please enter an Agency IATA Number",
        },
        'invoice|general|cityName': {
          required: "Please enter a City",
        },
        'invoice|general|countryName': {
          required: "Please enter a Country",
        },
        'invoice|general|addressEn': {
          required: "Please enter an Address English",
        },
        'invoice|general|addressAr': {
          required: "Please select an Address Arabic",
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

<script>
  document.getElementById('flexCheckDefault1').onclick = function() {
    var checkboxes = document.getElementsByName('check');
    for (var checkbox of checkboxes) {
      checkbox.checked = this.checked;
    }
  }
</script>
@append