@extends('admin.layout.main')
@section('title', @$header['title'])


@section('content')
<style>
  .p-font {
    color: black;
    font-size: small;
    font-style: italic;
  }
</style>
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-4 mt-2">
      <div class="col-sm-12 align-items-center d-flex breadcrumb-style">
        <h1 class="m-0">{{ $header['title'] }}</h1>
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('smtp.dashboard') </a></li>
          <li class="breadcrumb-item active">Login Attempt Setting</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
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
  <div class="container-fluid">
    <!-- Info boxes -->
    <div class="row">
      <div class="card pb-4 pt-2 px-3 w-100">
        <form class="form row pt-3 mb-3 validate" id="dataForm" name="dataForm" action="{{ route('login-attempt.store') }}" method="post">
          @csrf
          <div class="mb-3">
            <div class="pr-5 d-flex">
              <span class="order-dis pr-3 smtptop">ENABLE LOGIN ATTEMPTS</span>
              <div class="form-check filter-check sub-check">
                @php
                $value = "";
                @$value = App\Models\Setting::where('config_key', 'loginAttempts|enable')->get('value')[0]['value'];
                @endphp
                <input type="hidden" name="loginAttempts|enable" value="0">
                <input class="form-check-input" name="loginAttempts|enable" type="checkbox" value="1" {{ ($value || old('loginAttempts|enable',0) === 1) ? 'checked': '' }} id="flexCheckDefault2">
                <label class="form-check-label" for="flexCheckDefault2">
                  Check this box to enable the login attempts on this site
                </label>
              </div>
            </div>
          </div>
          <div id="">
            <div class="col-md-6">
              <div class="form-item form-float-style form-group">
                @php
                $value = "";
                @$value = App\Models\Setting::where('config_key', 'loginAttempts|perHost')->get('value')[0]['value'];
                @endphp
                <input type="text" name="loginAttempts|perHost" id="" value="{{ old('loginAttempts|perHost') ? old('loginAttempts|perHost') : $value }}" autocomplete="off" class="is-valid">
                <label for="host-smtp">MAX LOGIN ATTEMPTS PER HOST <span class="text-red">*</span></label>
                <p class="p-font">The number of login attempts a user has before their hostor computer is locked out of the system.</p>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-item form-float-style form-group">
                @php
                $value = "";
                @$value = App\Models\Setting::where('config_key', 'loginAttempts|perUser')->get('value')[0]['value'];
                @endphp
                <input type="text" name="loginAttempts|perUser" id="" value="{{ old('loginAttempts|perUser') ? old('loginAttempts|perUser') : $value }}" autocomplete="off" class="is-valid">
                <label for="host-smtp">MAX LOGIN ATTEMPTS PER USER <span class="text-red">*</span></label>
                <p class="p-font">The number of login attempts a user has before their hostor computer is locked out of the system.Note that this is different from hosts in case an attacker is using multiple computers.In addition,if they are using your login name you could be locked out yourself.</p>
              </div>
            </div>
            <div class="col-md-6">
              <div class="d-flex" style="gap: 1rem;">
                <div class="form-item form-float-style form-group mb-0" style="width: 49%;">
                  @php
                  $value = "";
                  @$value = App\Models\Setting::where('config_key', 'loginAttempts|loginTimePeriod')->get('value')[0]['value'];
                  @endphp
                  <input type="text" name="loginAttempts|loginTimePeriod" id="" value="{{ old('loginAttempts|loginTimePeriod') ? old('loginAttempts|loginTimePeriod') : $value }}" autocomplete="off" class="is-valid">
                  <label for="loginTimePeriod">LOGIN TIME PERIOD <span class="text-red">*</span></label>
                </div>
                <div class="form-floating form-item mb-0" style="width: 49%;">
                  <div class="form-item form-float-style serach-rem mb-0">
                    <div class="select top-space-rem after-drp form-float-style ">
                      @php
                      $value = "";
                      @$value = App\Models\Setting::where('config_key', 'loginAttempts|loginTimePeriodType')->get('value')[0]['value'];
                      @endphp
                      <select data-live-search="true" name="loginAttempts|loginTimePeriodType" id="" class="order-td-input selectpicker select-text height_drp is-valid">

                        <option {{ (@old('loginAttempts|loginTimePeriodType') == "minute") || ( $value == "minute") ? 'selected' : '' }} value="minute">minute</option>
                        <option {{ (@old('loginAttempts|loginTimePeriodType') == "hour") || ( $value == "hour") ? 'selected' : '' }} value="hour">hour</option>
                        <option {{ (@old('loginAttempts|loginTimePeriodType') == "day") || ( $value == "day") ? 'selected' : '' }} value="day">day</option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
              <p class="p-font" style="clear: both;">The number of minutes in which bad logins should be remember.</p>
            </div>
            <div class="col-md-6">
              <div class="d-flex" style="gap: 1rem;">
                <div class="form-item form-float-style form-group mb-0" style="width: 49%;">
                  @php
                  $value = "";
                  @$value = App\Models\Setting::where('config_key', 'loginAttempts|lockOutTimePeriod')->get('value')[0]['value'];
                  @endphp
                  <input type="text" name="loginAttempts|lockOutTimePeriod" id="" value="{{ old('loginAttempts|lockOutTimePeriod') ? old('loginAttempts|lockOutTimePeriod') : $value }}" autocomplete="off" class="is-valid">
                  <label for="lockOutTimePeriod">LOCKOUT TIME PERIOD <span class="text-red">*</span></label>
                </div>
                <div class="form-floating form-item mb-0" style="width: 49%;">
                  <div class="form-item form-float-style serach-rem mb-0">
                    <div class="select top-space-rem after-drp form-float-style ">
                      @php
                      $value = "";
                      @$value = App\Models\Setting::where('config_key', 'loginAttempts|lockOutTimePeriodType')->get('value')[0]['value'];
                      @endphp
                      <select data-live-search="true" name="loginAttempts|lockOutTimePeriodType" id="" class="order-td-input selectpicker select-text height_drp is-valid">
                        <option {{ (@old('loginAttempts|lockOutTimePeriodType') == "minute") || ( $value == "minute") ? 'selected' : '' }} value="minute">minute</option>
                        <option {{ (@old('loginAttempts|lockOutTimePeriodType') == "hour") || ( $value == "hour") ? 'selected' : '' }} value="hour">hour</option>
                        <option {{ (@old('loginAttempts|lockOutTimePeriodType') == "day") || ( $value == "day") ? 'selected' : '' }} value="day">day</option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
              <p class="p-font" style="clear: both;">The length of time a host or user will be banned from this site after hitting the limits of bad logins.</p>
            </div>
            <div class="mb-3">
              <div class="pr-5 d-flex">
                <span class="order-dis pr-3 smtptop">ENABLE NOTIFICATIONS</span>
                <div class="form-check filter-check sub-check">
                  @php
                  $value = "";
                  @$value = App\Models\Setting::where('config_key', 'loginAttempts|emailNotification')->get('value')[0]['value'];
                  @endphp
                  <input type="hidden" name="loginAttempts|emailNotification" value="0">
                  <input class="form-check-input" name="loginAttempts|emailNotification" type="checkbox" value="1" {{ ($value || old('loginAttempts|emailNotification',0) === 1) ? 'checked': '' }} id="flexCheckDefault3">
                  <label class="form-check-label" for="flexCheckDefault3">
                    Enabling this feature will trigger an email to be sent to the specified email address whenever a user is locked out of the system.
                  </label>
                </div>
              </div>
            </div>
          </div>
          <div class="cards-btn">
            <button type="submit" class="btn btn-success form-btn-success">Submit</button>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-danger form-btn-danger">Cancel</a>
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
  // INCLUDE JQUERY & JQUERY UI 1.12.1
  $(function() {
    $('*[value=""]').removeClass('is-valid');

    $("#datepicker").datepicker({
      dateFormat: "dd-mm-yy",
      duration: "fast"
    });
  });
</script>


<!-- Page specific script -->
<script>
  document.getElementById('flexCheckDefault1').onclick = function() {
    var checkboxes = document.getElementsByName('check');
    for (var checkbox of checkboxes) {
      checkbox.checked = this.checked;
    }
  }
</script>

<script>
  $(function() {
    //jquery Form validation
    $('#dataForm').validate({
      rules: {
        "loginAttempts|perHost": {
          required: true,
          digits: true,
          maxlength: 2
        },
        "loginAttempts|perUser": {
          required: true,
          digits: true,
          maxlength: 2
        },
        "loginAttempts|loginTimePeriod": {
          required: true,
          digits: true,
          maxlength: 2
        },
        "loginAttempts|lockOutTimePeriod": {
          required: true,
          digits: true,
          maxlength: 2
        }
      },

      messages: {
        "loginAttempts|perHost": {
          required: "Please enter a Per Host",
          maxlength: "Please enter no more than 2 digits."

        },
        "loginAttempts|perUser": {
          required: "Please enter a Per User",
          maxlength: "Please enter no more than 2 digits."
        },

        "loginAttempts|loginTimePeriod": {
          required: "Please enter a Login Time Period",
          maxlength: "Please enter no more than 2 digits."
        },
        "loginAttempts|lockOutTimePeriod": {
          required: "Please enter a Lock-Out Time Period",
          maxlength: "Please enter no more than 2 digits."
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
      }
    });
  });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>

@append