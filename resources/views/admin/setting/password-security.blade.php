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
          <li class="breadcrumb-item active">Password Security</li>
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
        <form class="form row pt-3 mb-3 validate" id="dataForm" name="dataForm" action="{{ route('password-security.store') }}" method="post">
          @csrf

          <div id="">
            <div class="col-md-6 form-group">
              <div class="d-flex">
                <div class="form-item form-float-style mb-0" style="width: 100%;">
                  @php
                  $value = "";
                  @$value = App\Models\Setting::where('config_key', 'passwordSecurity|expiryDays')->get('value')[0]['value'];
                  @endphp
                  <input type="text" name="passwordSecurity|expiryDays" id="" value="{{ old('passwordSecurity|expiryDays') ? old('passwordSecurity|expiryDays') : $value }}" autocomplete="off" class="is-valid">
                  <label for="passwordExpiry">PASSWORD EXPIRY <span class="text-red">*</span></label>
                </div>
                <div class="form-floating form-item mb-0" style="width: 20%;">
                  <div class="form-item form-float-style serach-rem mb-0">
                    <input type="text" name="" id="" value="Days" autocomplete="off" class="is-valid" readonly>
                  </div>
                </div>
              </div>
              <p class="p-font" style="clear: both;">The number of minutes in which bad logins should be remember.</p>
            </div>
            <div class="col-md-6 form-group">
              <div class="d-flex">
                <div class="form-item form-float-style  mb-0" style="width: 100%;">
                  @php
                  $value = "";
                  @$value = App\Models\Setting::where('config_key', 'passwordSecurity|expireNotifyDays')->get('value')[0]['value'];
                  @endphp
                  <input type="text" name="passwordSecurity|expireNotifyDays" id="expireNotifyDays" value="{{ old('passwordSecurity|expireNotifyDays') ? old('passwordSecurity|expireNotifyDays') : $value }}" autocomplete="off" class="is-valid">
                  <label for="notifyUserOnpasswordExpiry">NOTIFY USER ON PASSWORD EXPIRY <span class="text-red">*</span></label>
                </div>
                <div class="form-floating form-item mb-0" style="width: 20%;">
                  <div class="form-item form-float-style serach-rem mb-0">
                    <input type="text" name="" id="" value="Days" autocomplete="off" class="is-valid" readonly>
                  </div>
                </div>
              </div>
              <p class="p-font" style="clear: both;">A days before Password Expiration duration,Email Notification will be sent to User.For e.g. 5,2</p>
            </div>
            <div class="col-md-12 mb-3">
              <div class="pr-5 d-flex">
                <span class="order-dis pr-3">NOTIFY USER ON PASSWORD CHANGE</span>
                <div class="form-check filter-check sub-check">
                  @php
                  $value = "";
                  @$value = App\Models\Setting::where('config_key', 'passwordSecurity|changePasswordNotify')->get('value')[0]['value'];
                  @endphp
                  <input type="hidden" name="passwordSecurity|changePasswordNotify" value="0">
                  <input class="form-check-input" name="passwordSecurity|changePasswordNotify" type="checkbox" value="1" {{ ($value || old('passwordSecurity|changePasswordNotify',0) === 1) ? 'checked': '' }} id="flexCheckDefault2">
                  <label class="form-check-label" for="flexCheckDefault2">
                    Enable for notifying user when current password has been changed.
                  </label>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-item form-float-style form-group">
                @php
                $value = "";
                @$value = App\Models\Setting::where('config_key', 'passwordSecurity|minimumPasswordLength')->get('value')[0]['value'];
                @endphp
                <input type="text" name="passwordSecurity|minimumPasswordLength" id="password" value="{{ old('passwordSecurity|minimumPasswordLength') ? old('passwordSecurity|minimumPasswordLength') : $value }}" autocomplete="off" class="is-valid">
                <label for="minimunPasswordLength">MINIMUM PASSWORD LENGTH <span class="text-red">*</span></label>
                <p class="p-font">The minimum length of characters for creating a new password.</p>
              </div>
            </div>
            <div class="col-md-12 mb-3">
              <div class="pr-5" style="display: flex; align-items: flex-start;">
                <span class="order-dis pr-3">CHARACTER REQUIREMENTS <span class="text-red">*</span></span>
                <div class="">
                  <div class="form-check filter-check sub-check form-group" style="display: flex; align-items: flex-start;">
                    <span class="order-dis pr-3">At least</span>
                    @php
                    $value = "";
                    @$value = App\Models\Setting::where('config_key', 'passwordSecurity|specialCharacter')->get('value')[0]['value'];
                    @endphp
                    <input class="w-25" type="text" name="passwordSecurity|specialCharacter" id="char_special" value="{{ old('passwordSecurity|specialCharacter') ? old('passwordSecurity|specialCharacter') : $value }}" autocomplete="off" class="is-valid">
                    <span style="padding-left: 1rem; color: #343a40; font-size: 14px;">special characters</span>
                  </div>
                  <div class="form-check filter-check sub-check form-group" style="display: flex; align-items: flex-start;">
                    <span class="order-dis pr-3">At least</span>
                    @php
                    $value = "";
                    @$value = App\Models\Setting::where('config_key', 'passwordSecurity|alphanumericCharacter')->get('value')[0]['value'];
                    @endphp
                    <input class="w-25" type="text" name="passwordSecurity|alphanumericCharacter" id="char_alphanumeric" value="{{ old('passwordSecurity|alphanumericCharacter') ? old('passwordSecurity|alphanumericCharacter') : $value }}" autocomplete="off" class="is-valid">
                    <span style="padding-left: 1rem; color: #343a40; font-size: 14px;">alphanumeric characters</span>
                  </div>
                  <div class="form-check filter-check sub-check form-group" style="display: flex; align-items: flex-start;">
                    <span class="order-dis pr-3">At least</span>
                    @php
                    $value = "";
                    @$value = App\Models\Setting::where('config_key', 'passwordSecurity|uppercaseCharacter')->get('value')[0]['value'];
                    @endphp
                    <input class="w-25" type="text" name="passwordSecurity|uppercaseCharacter" id="char_upper" value="{{ old('passwordSecurity|uppercaseCharacter') ? old('passwordSecurity|uppercaseCharacter') : $value }}" autocomplete="off" class="is-valid">
                    <span style="padding-left: 1rem; color: #343a40; font-size: 14px;">uppercase characters</span>
                  </div>
                  <div class="form-check filter-check sub-check form-group" style="display: flex; align-items: flex-start;">
                    <span class="order-dis pr-3">At least</span>
                    @php
                    $value = "";
                    @$value = App\Models\Setting::where('config_key', 'passwordSecurity|lowercaseCharacter')->get('value')[0]['value'];
                    @endphp
                    <input class="w-25" type="text" name="passwordSecurity|lowercaseCharacter" id="char_lower" value="{{ old('passwordSecurity|lowercaseCharacter') ? old('passwordSecurity|lowercaseCharacter') : $value }}" autocomplete="off" class="is-valid">
                    <span style="padding-left: 1rem; color: #343a40; font-size: 14px;">lowercase characters</span>
                  </div>
                  <div class="form-check filter-check sub-check form-group" style="display: flex; align-items: flex-start;">
                    <span class="order-dis pr-3">At least</span>
                    @php
                    $value = "";
                    @$value = App\Models\Setting::where('config_key', 'passwordSecurity|numericCharacter')->get('value')[0]['value'];
                    @endphp
                    <input class="w-25" type="text" name="passwordSecurity|numericCharacter" id="char_numeric" value="{{ old('passwordSecurity|numericCharacter') ? old('passwordSecurity|numericCharacter') : $value }}" autocomplete="off" class="is-valid">
                    <span style="padding-left: 1rem; color: #343a40; font-size: 14px;">numeric characters</span>
                  </div>
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
  $.validator.addMethod("commaBetweenDigits", function(value, element) {
    return this.optional(element) || /^\d,\d$/.test(value);
  }, "Please enter a comma between two digits.");
  $.validator.addMethod("commaBetweenUniqueDigits", function(value, element) {
    // Regular expression to match two or more consecutive digits
    var regex = /\d{1,}/;

    // Check if the value matches the regex
    if (regex.test(value)) {
      // Remove any existing commas from the value
      var strippedValue = value.replace(/,/g, '');

      // Check if the stripped value contains only unique digits
      var uniqueDigits = [...new Set(strippedValue)];
      if (uniqueDigits.length === strippedValue.length) {
        // Add a comma between each pair of digits
        var formattedValue = strippedValue.replace(/\B(?=(\d{1})+(?!\d))/g, ",");

        // Update the input value with the formatted value
        $(element).val(formattedValue);
      } else {
        return false; // Unique digits condition not met
      }
    }

    // Always return true since we're modifying the input value
    return true;
  }, "Please enter a valid number with commas between unique digits.");
  $(function() {
    $.validator.addMethod('uniqueDigits', function(value, element) {
      var digits = value.split(',');

      // Remove empty values and duplicates
      var uniqueDigits = [...new Set(digits.filter(digit => digit !== "" && !digit.startsWith("0")))];

      // Check if the number of unique digits is the same as the original number of digits
      return uniqueDigits.length === digits.length;
    }, 'Please enter comma-separated digits with unique values.');
    //jquery Form validation
    $('#dataForm').validate({
      rules: {
        "passwordSecurity|expiryDays": {
          required: true,
          digits: true
        },
        "passwordSecurity|expireNotifyDays": {
          required: true,
          pattern: /^[0-9,]+$/,
          uniqueDigits: true

        },
        "passwordSecurity|minimumPasswordLength": {
          required: true,
          digits: true,
          remote: {
            url: "{{ route('checkPasswordLength') }}",
            type: "post",
            data: {
              password: function() {
                return $("#password").val();
              },
              charSpecial: function() {
                return $("#char_special").val();
              },
              charAlphanumeric: function() {
                return $("#char_alphanumeric").val();
              },
              charNumeric: function() {
                return $("#char_numeric").val();
              },
              charUpper: function() {
                return $("#char_upper").val();
              },
              charLower: function() {
                return $("#char_lower").val();
              },
              "_token": '{{ csrf_token() }}'
            }
          }
        },
        "passwordSecurity|specialCharacter": {
          required: true,
          digits: true
        },
        "passwordSecurity|alphanumericCharacter": {
          required: true,
          digits: true
        },
        "passwordSecurity|uppercaseCharacter": {
          required: true,
          digits: true
        },
        "passwordSecurity|lowercaseCharacter": {
          required: true,
          digits: true
        },
        "passwordSecurity|numericCharacter": {
          required: true,
          digits: true
        }
      },




      messages: {
        "passwordSecurity|expiryDays": {
          required: "Please enter a passsword expiry days",

        },
        "passwordSecurity|expireNotifyDays": {
          required: "Please enter a days before Password Expiration duration",
          pattern: 'Please enter a valid number'

        },
        "passwordSecurity|minimumPasswordLength": {
          required: "Please enter a minimum password length",
          remote: "Password length must be euqal or more than sum of required characters"
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
      }
    });
  });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>

@append