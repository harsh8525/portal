@extends('admin.layout.main')
@section('title', @$header['title'])

@section('content')
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-4 mt-2">
      <div class="col-sm-12 align-items-center d-flex breadcrumb-style">
        <h1 class="m-0">{{ $header['title'] }}</h1>
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('smtp.dashboard') </a></li>
          <li class="breadcrumb-item active">@lang('smtp.moduleHeading')</li>
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
        <form class="form row pt-3 mb-3 validate" id="dataForm" name="dataForm" action="{{ route('smtp.store') }}" method="post">
          @csrf
          <div class="mb-3">
            <div class="pr-5 d-flex">
              <span class="order-dis pr-3 smtptop">@lang('smtp.enableEmailNotification')</span>
              <div class="form-check filter-check sub-check">
                @php
                $value = "";
                @$value = App\Models\Setting::where('config_key', 'mail|smtp|emailNotification')->get('value')[0]['value'];
                @endphp
                <input type="hidden" name="mail|smtp|emailNotification" value="0">
                <input class="form-check-input" name="mail|smtp|emailNotification" type="checkbox" value="1" {{ ($value || old('mail|smtp|emailNotification',0) === 1) ? 'checked': '' }} id="flexCheckDefault2">
                <label class="form-check-label" for="flexCheckDefault2">
                  @lang('smtp.enableEmailCheckbox')
                </label>
              </div>
            </div>
            <div class="pr-5 d-flex">
              <span class="order-dis pr-3 smtptop">@lang('smtp.enableSMTP')</span>
              <div class="form-check filter-check sub-check">
                @php
                $value = "";
                @$value = App\Models\Setting::where('config_key', 'mail|smtp|server')->get('value')[0]['value'];
                @endphp
                <input type="hidden" name="mail|smtp|server" value="0">
                <input class="form-check-input smtp-enable" name="mail|smtp|server" type="checkbox" value="1" {{ ($value || old('mail|smtp|server',0) === 1) ? 'checked': '' }} id="flexCheckDefault3">
                <label class="form-check-label" for="flexCheckDefault3">
                  @lang('smtp.enableSMTPCheckbox')
                </label>
              </div>
            </div>
          </div>
          <div id="smtpOption">
            <div class="col-md-6">
              <div class="form-item form-float-style form-group">
                @php
                $value = "";
                @$value = App\Models\Setting::where('config_key', 'mail|smtp|host')->get('value')[0]['value'];
                @endphp
                <input type="text" name="mail|smtp|host" id="host" value="{{ old('mail|smtp|host') ? old('mail|smtp|host') : $value }}" autocomplete="off" class="is-valid">
                <label for="host-smtp">@lang('smtp.host')</label>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-item form-float-style form-group">
                @php
                $value = "";
                @$value = App\Models\Setting::where('config_key', 'mail|smtp|fromEmail')->get('value')[0]['value'];
                @endphp
                <input type="email" name="mail|smtp|fromEmail" id="from_email" value="{{ old('mail|smtp|fromEmail') ? old('mail|smtp|fromEmail') : $value  }}" autocomplete="off" class="is-valid">
                <label for="email-smtp">@lang('smtp.fromEmail')</label>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-item form-float-style">
                @php
                $value = "";
                @$value = App\Models\Setting::where('config_key', 'mail|smtp|smtpServer')->get('value')[0]['value'];
                @endphp
                <input type="text" name="mail|smtp|smtpServer" id="smtp_server" value="{{ old('mail|smtp|smtpServer') ? old('mail|smtp|smtpServer') : $value }}" autocomplete="off" class="is-valid">
                <label for="server-smtp">@lang('smtp.smtpServer')</label>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-item form-float-style form-group">
                @php
                $value = "";
                @$value = App\Models\Setting::where('config_key', 'mail|smtp|userName')->get('value')[0]['value'];
                @endphp
                <input type="text" name="mail|smtp|userName" id="user_name" value="{{ old('mail|smtp|userName') ? old('mail|smtp|userName') : $value }}" autocomplete="off" class="is-valid">
                <label for="smtp-user">@lang('smtp.userName')</label>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-item form-float-style form-group">
                @php
                $value = "";
                @$value = App\Models\Setting::where('config_key', 'mail|smtp|password')->get('value')[0]['value'];
                @endphp
                <input type="password" name="mail|smtp|password" id="password" value="{{ old('mail|smtp|password') ? old('mail|smtp|password') : $value }}" autocomplete="off" class="is-valid">
                <label for="smtp-password">@lang('smtp.password')</label>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-floating form-item mb-3">
                <div class="form-item form-float-style serach-rem mb-3">
                  <div class="select top-space-rem after-drp form-float-style ">
                    @php
                    $value = "";
                    @$value = App\Models\Setting::where('config_key', 'mail|smtp|security')->get('value')[0]['value'];
                    @endphp
                    <select data-live-search="true" name="mail|smtp|security" id="security" class="order-td-input selectpicker select-text height_drp is-valid">
                      <option value="" selected>Select Security</option>
                      <option {{ (@old('mail|smtp|security') == "SSL") || ( $value == "SSL") ? 'selected' : '' }} value="SSL">SSL</option>
                      <option {{ (@old('mail|smtp|security') == "TLS") || ( $value == "TLS") ? 'selected' : '' }} value="TLS">TLS</option>
                    </select>
                    <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">@lang('smtp.security')</label>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-item form-float-style form-group">
                @php
                $value = "";
                @$value = App\Models\Setting::where('config_key', 'mail|smtp|port')->get('value')[0]['value'];
                @endphp
                <input type="text" name="mail|smtp|port" id="port" value="{{ old('mail|smtp|port') ? old('mail|smtp|port') : $value }}" autocomplete="off" class="is-valid">
                <label for="smtp-port">@lang('smtp.port')</label>
              </div>
            </div>
          </div>
          <div class="cards-btn">
            <button type="submit" class="btn btn-success form-btn-success">@lang('smtp.submit')</button>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-danger form-btn-danger">@lang('smtp.cancel')</a>
          </div>
        </form>
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

    $("#security").change(function() {
      if (this.value == "SSL") {
        $('#port').val('465');
      } else if (this.value == "TLS") {
        $('#port').val('587');
      } else {
        $('#port').val('');
      }
    });
    $(".smtp-enable").change(function() {
      if ($(this).is(":checked") && $(this).val() === "1") {
        $("#smtpOption").show();
      } else {
        $("#smtpOption").hide();
      }
    });
    $(".smtp-enable").trigger('change');


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
        "mail|smtp|host": {
          required: true,
        },
        "mail|smtp|fromEmail": {
          required: false,
          email: true
        },
        'mail|smtp|userName': {
          required: true,
        },
        "mail|smtp|password": {
          required: true,
          minlength: 8
        },
        "mail|smtp|security": {
          required: true
        },
        "mail|smtp|port": {
          required: true,
          digits: true
        }
      },


      messages: {
        "mail|smtp|host": {
          required: "Please enter a Host",
          email: "Please enter a valid Host"
        },
        "mail|smtp|fromEmail": {
          required: "Please enter an Email"
        },
        'mail|smtp|userName': {
          required: "Please enter a User Name"
        },
        "mail|smtp|password": {
          required: "Please enter a Password"
        },
        "mail|smtp|security": {
          required: "Please select a Security"
        },
        "mail|smtp|port": {
          required: "Please enter a Port Number"
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