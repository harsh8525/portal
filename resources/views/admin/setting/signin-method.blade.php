@extends('admin.layout.main')
@section('title', $header['title'])

@section('content')
<!-- Start Content-Header -->
<div class="content-header">
  <!-- Start Container-Fluid -->
  <div class="container-fluid">
    <!-- Start Row -->
    <div class="row mb-4 mt-2"><!-- Start  -->
      <div class="col-sm-12 d-flex breadcrumb-style">
        <h1 class="m-0">{{ $header['heading'] }}</h1>
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('general.dashboard') </a></li>
          <li class="breadcrumb-item active">Singin Method</li>
        </ol>
      </div>
    </div>
    <!--End row -->
  </div>
  <!--End container-fluid -->
</div><!--End content-header -->

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
          <form id="signinMethodForm" name="signinMethodForm" class="form row mb-3 ml-0 mr-0 mt-0 validate" action="{{ route('signin-method.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">

              <div class="col-md-6 discount brdr-btm"><!--Start Google Div -->
                <h5 class="setting-title">Google</h5>
                <div class="form-check filter-check sub-check mb-3">
                  @php
                  $value = "";
                  @$value = App\Models\Setting::where('config_key', 'signInMethod|google|enable')->get('value')[0]['value'];
                  @endphp
                  <label class="form-check-label" for="check_id">
                    <input type="hidden" name="signInMethod|google|enable" value="0">
                    <input class="form-check-input" type="checkbox" value="1" id="flexCheckDefault3" {{ ($value || old('signInMethod|google|enable',0) === 1) ? 'checked': '' }} name="signInMethod|google|enable">
                    ENABLE<br><b>Allow users to sign-up using their email address and password</b>
                  </label>
                </div>
                <div class="form-item form-float-style form-group">
                  @php
                  $value = "";
                  @$value = App\Models\Setting::where('config_key', 'signInMethod|google|clientId')->get('value')[0]['value'];
                  @endphp
                  <input type="text" value="{{ old('signInMethod|google|clientId') ? old('signInMethod|google|clientId') : $value }}" id="" name="signInMethod|google|clientId" autocomplete="off" class="is-valid">
                  <label for="clientID">CLIENT ID<span class="req-star">*</span></label>
                </div>
                <div class="form-item form-float-style form-group">
                  @php
                  $value = "";
                  @$value = App\Models\Setting::where('config_key', 'signInMethod|google|clientSecret')->get('value')[0]['value'];
                  @endphp
                  <input type="text" value="{{ old('signInMethod|google|clientSecret') ? old('signInMethod|google|clientSecret') : $value }}" id="signInMethod|google|clientSecret" name="signInMethod|google|clientSecret" autocomplete="off" class="is-valid">
                  <label for="clientSecret">CLIENT SECRET<span class="req-star">*</span></label>
                </div>
                <div class="form-item form-float-style form-group">
                  @php
                  $value = "";
                  @$value = App\Models\Setting::where('config_key', 'signInMethod|google|redirectUri')->get('value')[0]['value'];
                  @endphp
                  <input type="text" value="{{ old('signInMethod|google|redirectUri') ? old('signInMethod|google|redirectUri') : $value }}" id="signInMethod|google|redirectUri" name="signInMethod|google|redirectUri" autocomplete="off" class="is-valid">
                  <label for="googleredirectURL">REDIRECT URI<span class="req-star">*</span></label>
                  <p class="" style="color: black;font-size: 16px;font-weight: 500;font-style: italic;">To complete set up,add this OAuth redirect URi to your Google app Configuration.</p>

                </div>
                <div class="form-item form-float-style form-group">
                  @php
                  $value = "";
                  @$value = App\Models\Setting::where('config_key', 'signInMethod|google|developerKey')->get('value')[0]['value'];
                  @endphp
                  <input type="text" value="{{ old('signInMethod|google|developerKey') ? old('signInMethod|google|developerKey') : $value }}" id="signInMethod|google|developerKey" name="signInMethod|google|developerKey" autocomplete="off" class="is-valid">
                  <label for="developerKey">DEVELOPER KEY<span class="req-star">*</span></label>
                </div>
              </div><!-- End Google Div -->
              <div class="col-md-6 discount brdr-btm"><!--Start Facebook Div -->
                <h5 class="setting-title">Facebook</h5>
                <div class="form-check filter-check sub-check mb-3">
                  @php
                  $value = "";
                  @$value = App\Models\Setting::where('config_key', 'signInMethod|facebook|enable')->get('value')[0]['value'];
                  @endphp
                  <label class="form-check-label" for="check_id">
                    <input type="hidden" name="signInMethod|facebook|enable" value="0">
                    <input class="form-check-input" type="checkbox" value="1" {{ ($value || old('signInMethod|facebook|enable',0) === 1) ? 'checked': '' }} id="flexCheckDefault4" name="signInMethod|facebook|enable">
                    ENABLE<br><b>Allow users to sign-up using their email address and password</b>
                  </label>
                </div>
                <div class="form-item form-float-style form-group">
                  @php
                  $value = "";
                  @$value = App\Models\Setting::where('config_key', 'signInMethod|facebook|appId')->get('value')[0]['value'];
                  @endphp
                  <input type="text" value="{{ old('signInMethod|facebook|appId') ? old('signInMethod|facebook|appId') : $value }}" id="signInMethod|facebook|appId" name="signInMethod|facebook|appId" autocomplete="off" class="is-valid">
                  <label for="appID">APP ID<span class="req-star">*</span></label>
                </div>
                <div class="form-item form-float-style form-group">
                  @php
                  $value = "";
                  @$value = App\Models\Setting::where('config_key', 'signInMethod|facebook|appSecret')->get('value')[0]['value'];
                  @endphp
                  <input type="text" value="{{ old('signInMethod|facebook|appSecret') ? old('signInMethod|facebook|appSecret') : $value }}" id="signInMethod|facebook|appSecret" name="signInMethod|facebook|appSecret" autocomplete="off" class="is-valid">
                  <label for="appsecret">APP SECRET<span class="req-star">*</span></label>
                </div>
                <div class="form-item form-float-style form-group">
                  @php
                  $value = "";
                  @$value = App\Models\Setting::where('config_key', 'signInMethod|facebook|redirectUri')->get('value')[0]['value'];
                  @endphp
                  <input type="text" value="{{ old('signInMethod|facebook|redirectUri') ? old('signInMethod|facebook|redirectUri') : $value }}" id="signInMethod|facebook|redirectUri" name="signInMethod|facebook|redirectUri" autocomplete="off" class="is-valid">
                  <label for="facebookRedirectURI">REDIRECT URI<span class="req-star">*</span></label>
                  <p class="" style="color: black;font-size: 16px;font-weight: 500;font-style: italic;">To complete set up,add this OAuth redirect URi to your Facebook app Configuration</p>
                </div>
                <div class="form-item form-float-style form-group">
                  @php
                  $value = "";
                  @$value = App\Models\Setting::where('config_key', 'signInMethod|facebook|redirectUriLogout')->get('value')[0]['value'];
                  @endphp
                  <input type="text" value="{{ old('signInMethod|facebook|redirectUriLogout') ? old('signInMethod|facebook|redirectUriLogout') : $value }}" id="signInMethod|facebook|redirectUriLogout" name="signInMethod|facebook|redirectUriLogout" autocomplete="off" class="is-valid">
                  <label for="facebookRedirectURLogout">REDIRECT URI LOGOUT<span class="req-star">*</span></label>
                </div>
              </div><!-- End Facebook Div -->
              <div class="col-md-6 discount brdr-btm mt-3"><!--Start Instagram Div -->
                <h5 class="setting-title">Instagram</h5>
                <div class="form-check filter-check sub-check mb-3">
                  @php
                  $value = "";
                  @$value = App\Models\Setting::where('config_key', 'signInMethod|instagram|enable')->get('value')[0]['value'];
                  @endphp
                  <label class="form-check-label" for="check_id">
                    <input type="hidden" name="signInMethod|instagram|enable" value="0">
                    <input class="form-check-input" type="checkbox" value="1" {{ ($value || old('signInMethod|instagram|enable',0) === 1) ? 'checked': '' }} id="flexCheckDefault4" name="signInMethod|instagram|enable">
                    ENABLE<br><b>Allow users to sign-up using their email address and password</b>
                  </label>
                </div>
                <div class="form-item form-float-style form-group">
                  @php
                  $value = "";
                  @$value = App\Models\Setting::where('config_key', 'signInMethod|instagram|appId')->get('value')[0]['value'];
                  @endphp
                  <input type="text" value="{{ old('signInMethod|instagram|appId') ? old('signInMethod|instagram|appId') : $value }}" id="signInMethod|instagram|appId" name="signInMethod|instagram|appId" autocomplete="off" class="is-valid">
                  <label for="appID">APP ID<span class="req-star">*</span></label>
                </div>
                <div class="form-item form-float-style form-group">
                  @php
                  $value = "";
                  @$value = App\Models\Setting::where('config_key', 'signInMethod|instagram|appSecret')->get('value')[0]['value'];
                  @endphp
                  <input type="text" value="{{ old('signInMethod|instagram|appSecret') ? old('signInMethod|instagram|appSecret') : $value }}" id="signInMethod|instagram|appSecret" name="signInMethod|instagram|appSecret" autocomplete="off" class="is-valid">
                  <label for="appsecret">APP SECRET<span class="req-star">*</span></label>
                </div>
                <div class="form-item form-float-style form-group">
                  @php
                  $value = "";
                  @$value = App\Models\Setting::where('config_key', 'signInMethod|instagram|redirectUri')->get('value')[0]['value'];
                  @endphp
                  <input type="text" value="{{ old('signInMethod|instagram|redirectUri') ? old('signInMethod|instagram|redirectUri') : $value }}" id="signInMethod|instagram|redirectUri" name="signInMethod|instagram|redirectUri" autocomplete="off" class="is-valid">
                  <label for="instagramRedirectURI">REDIRECT URI<span class="req-star">*</span></label>
                  <p class="" style="color: black;font-size: 16px;font-weight: 500;font-style: italic;">To complete set up,add this OAuth redirect URi to your Instagram app Configuration</p>
                </div>
              </div><!-- End Instagram Div -->
              <div class="col-md-6 discount mt-3"><!--Start Google Div -->
                <h5 class="setting-title">Apple</h5>
                <div class="form-check filter-check sub-check mb-3">
                  @php
                  $value = "";
                  @$value = App\Models\Setting::where('config_key', 'signInMethod|apple|enable')->get('value')[0]['value'];
                  @endphp
                  <label class="form-check-label" for="check_id">
                    <input type="hidden" name="signInMethod|apple|enable" value="0">
                    <input class="form-check-input" type="checkbox" value="1" id="flexCheckDefault3" {{ ($value || old('signInMethod|apple|enable',0) === 1) ? 'checked': '' }} name="signInMethod|apple|enable">
                    ENABLE<br><b>Allow users to sign-up using their email address and password</b>
                  </label>
                </div>
                <div class="form-item form-float-style form-group">
                  @php
                  $value = "";
                  @$value = App\Models\Setting::where('config_key', 'signInMethod|apple|clientId')->get('value')[0]['value'];
                  @endphp
                  <input type="text" value="{{ old('signInMethod|apple|clientId') ? old('signInMethod|apple|clientId') : $value }}" id="" name="signInMethod|apple|clientId" autocomplete="off" class="is-valid">
                  <label for="clientID">CLIENT ID<span class="req-star">*</span></label>
                </div>
                <div class="form-item form-float-style form-group">
                  @php
                  $value = "";
                  @$value = App\Models\Setting::where('config_key', 'signInMethod|apple|keyTxtFile')->get('value')[0]['value'];
                  @endphp
                  <input type="file" accept=".txt" value="{{ old('signInMethod|apple|keyTxtFile') ? old('signInMethod|apple|keyTxtFile') : $value }}" id="" name="signInMethod|apple|keyTxtFile" autocomplete="off" class="is-valid">
                  <label for="">Key.txt File <span class="req-star">*</span></label>
                  <p style="width: 100%"><a href="{{ $value }}" target="_blank">{{ $value }}</a></p>
                </div>
                <div class="form-item form-float-style form-group">
                  @php
                  $value = "";
                  @$value = App\Models\Setting::where('config_key', 'signInMethod|apple|team_id')->get('value')[0]['value'];
                  @endphp
                  <input type="text" value="{{ old('signInMethod|apple|team_id') ? old('signInMethod|apple|team_id') : $value }}" id="" name="signInMethod|apple|team_id" autocomplete="off" class="is-valid">
                  <label for="clientID">Team ID<span class="req-star">*</span></label>
                </div>
                <div class="form-item form-float-style form-group">
                  @php
                  $value = "";
                  @$value = App\Models\Setting::where('config_key', 'signInMethod|apple|key_id')->get('value')[0]['value'];
                  @endphp
                  <input type="text" value="{{ old('signInMethod|apple|key_id') ? old('signInMethod|apple|key_id') : $value }}" id="" name="signInMethod|apple|key_id" autocomplete="off" class="is-valid">
                  <label for="">Key ID<span class="req-star">*</span></label>
                </div>
                <div class="form-item form-float-style form-group">
                  @php
                  $value = "";
                  @$value = App\Models\Setting::where('config_key', 'signInMethod|apple|clientSecret')->get('value')[0]['value'];
                  @endphp
                  <input type="text" value="{{ old('signInMethod|apple|clientSecret') ? old('signInMethod|apple|clientSecret') : $value }}" id="signInMethod|apple|clientSecret" name="signInMethod|apple|clientSecret" autocomplete="off" class="is-valid">
                  <label for="clientSecret">CLIENT SECRET<span class="req-star">*</span></label>
                </div>
                <div class="form-item form-float-style form-group">
                  @php
                  $value = "";
                  @$value = App\Models\Setting::where('config_key', 'signInMethod|apple|redirectUrl')->get('value')[0]['value'];
                  @endphp
                  <input type="text" value="{{ old('signInMethod|apple|redirectUrl') ? old('signInMethod|apple|redirectUrl') : $value }}" id="signInMethod|apple|redirectUrl" name="signInMethod|apple|redirectUrl" autocomplete="off" class="is-valid">
                  <label for="appleredirectURL">REDIRECT URL<span class="req-star">*</span></label>
                  <p class="" style="color: black;font-size: 16px;font-weight: 500;font-style: italic;">To complete set up,add this OAuth redirect URL to your Apple app Configuration.</p>
                </div>
              </div><!-- End Apple Div -->
              <div class="col-md-6 discount brdr-btm mt-3"><!--Start Instagram Div -->
                <h5 class="setting-title">Twitter</h5>
                <div class="form-check filter-check sub-check mb-3">
                  @php
                  $value = "";
                  @$value = App\Models\Setting::where('config_key', 'signInMethod|twitter|enable')->get('value')[0]['value'];
                  @endphp
                  <label class="form-check-label" for="check_id">
                    <input type="hidden" name="signInMethod|twitter|enable" value="0">
                    <input class="form-check-input" type="checkbox" value="1" {{ ($value || old('signInMethod|twitter|enable',0) === 1) ? 'checked': '' }} id="flexCheckDefault4" name="signInMethod|twitter|enable">
                    ENABLE<br><b>Allow users to sign-up using their email address and password</b>
                  </label>
                </div>
                <div class="form-item form-float-style form-group">
                  @php
                  $value = "";
                  @$value = App\Models\Setting::where('config_key', 'signInMethod|twitter|clientId')->get('value')[0]['value'];
                  @endphp
                  <input type="text" value="{{ old('signInMethod|twitter|clientId') ? old('signInMethod|twitter|clientId') : $value }}" id="signInMethod|twitter|clientId" name="signInMethod|twitter|clientId" autocomplete="off" class="is-valid">
                  <label for="clientId">APP ID<span class="req-star">*</span></label>
                </div>
                <div class="form-item form-float-style form-group">
                  @php
                  $value = "";
                  @$value = App\Models\Setting::where('config_key', 'signInMethod|twitter|clientSecret')->get('value')[0]['value'];
                  @endphp
                  <input type="text" value="{{ old('signInMethod|twitter|clientSecret') ? old('signInMethod|twitter|clientSecret') : $value }}" id="signInMethod|twitter|clientSecret" name="signInMethod|twitter|clientSecret" autocomplete="off" class="is-valid">
                  <label for="clientSecret">APP SECRET<span class="req-star">*</span></label>
                </div>
                <div class="form-item form-float-style form-group">
                  @php
                  $value = "";
                  @$value = App\Models\Setting::where('config_key', 'signInMethod|twitter|redirectUri')->get('value')[0]['value'];
                  @endphp
                  <input type="text" value="{{ old('signInMethod|twitter|redirectUri') ? old('signInMethod|twitter|redirectUri') : $value }}" id="signInMethod|twitter|redirectUri" name="signInMethod|twitter|redirectUri" autocomplete="off" class="is-valid">
                  <label for="instagramRedirectURI">REDIRECT URI<span class="req-star">*</span></label>
                  <p class="" style="color: black;font-size: 16px;font-weight: 500;font-style: italic;">To complete set up,add this OAuth redirect URi to your Twitter app Configuration</p>
                </div>
              </div><!-- End Instagram Div -->
              <div class="cards-btn">
                <button type="submit" class="btn btn-success form-btn-success">Submit</button>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-danger form-btn-danger">Cancel</a>
              </div>
            </div>
          </form>
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



<script>
  $(function() {


    $.validator.addMethod("email_regex", function(value, element, regexpr) {
      return this.optional(element) || regexpr.test(value);
    }, "Please enter a valid Email Address.");

    $('*[value=""]').removeClass('is-valid');

    $('#signinMethodForm').validate({
      rules: {
        "signInMethod|google|clientId": {
          required: true,
          noSpace: true
        },
        "signInMethod|google|clientSecret": {
          required: true,
          noSpace: true
        },
        "signInMethod|google|redirectUri": {
          required: true,
          noSpace: true
        },
        "signInMethod|google|developerKey": {
          required: true,
          noSpace: true
        },
        "signInMethod|facebook|appId": {
          required: true,
          noSpace: true
        },
        "signInMethod|facebook|appSecret": {
          required: true,
          noSpace: true
        },
        "signInMethod|facebook|redirectUri": {
          required: true,
          noSpace: true
        },
        "signInMethod|facebook|redirectUriLogout": {
          required: true,
          noSpace: true
        },
        "signInMethod|apple|clientId": {
          required: true,
          noSpace: true
        },
        "signInMethod|apple|keyTxtFile": {
          accept: "text/plain"
        },
        "signInMethod|apple|team_id": {
          required: true,
          noSpace: true
        },
        "signInMethod|apple|key_id": {
          required: true,
          noSpace: true
        },
        "signInMethod|apple|clientSecret": {
          required: true,
          noSpace: true
        },
        "signInMethod|apple|redirectUrl": {
          required: true,
          noSpace: true
        },

      },
      messages: {
        "signInMethod|google|clientId": {
          required: "Please enter a google Cliend Id",
          noSpace: "Only Space Not Allowed"
        },
        "signInMethod|google|clientSecret": {
          required: "Please enter a google Cliend Secret",
          noSpace: "Only Space Not Allowed"
        },
        "signInMethod|google|redirectUri": {
          required: "Please enter a google redirectUri",
          noSpace: "Only Space Not Allowed"
        },
        "signInMethod|google|developerKey": {
          required: "Please enter a google developerKey",
          noSpace: "Only Space Not Allowed"
        },
        "signInMethod|facebook|appId": {
          required: "Please enter a facebook appId",
          noSpace: "Only Space Not Allowed"
        },
        "signInMethod|facebook|appSecret": {
          required: "Please enter a facebook appSecret",
          noSpace: "Only Space Not Allowed"
        },
        "signInMethod|facebook|redirectUri": {
          required: "Please enter a facebook redirectUri",
          noSpace: "Only Space Not Allowed"
        },
        "signInMethod|facebook|redirectUriLogout": {
          required: "Please enter a facebook redirectUriLogout",
          noSpace: "Only Space Not Allowed"
        },
        "signInMethod|facebook|redirectUriLogout": {
          required: "Please enter a facebook redirectUriLogout",
          noSpace: "Only Space Not Allowed"
        },
        "signInMethod|apple|clientId": {
          required: "Please enter an apple clientId",
          noSpace: "Only Space Not Allowed"
        },
        "signInMethod|apple|keyTxtFile": {
          accept: "Please choose a valid .txt file."
        },
        "signInMethod|apple|team_id": {
          required: "Please enter an apple team id",
          noSpace: "Only Space Not Allowed"
        },
        "signInMethod|apple|key_id": {
          required: "Please enter an apple key id",
          noSpace: "Only Space Not Allowed"
        },
        "signInMethod|apple|clientSecret": {
          required: "Please enter an apple client secret",
          noSpace: "Only Space Not Allowed"
        },
        "signInMethod|apple|redirectUrl": {
          required: "Please enter an apple redirect url",
          noSpace: "Only Space Not Allowed"
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
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>

@append