@extends('admin.layout.main')
@section('title', @$header['title'])

@section('content')
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-4 mt-2">
      <div class="col-sm-12 align-items-center d-flex breadcrumb-style">
        <h1 class="m-0">{{ $header['title'] }}</h1>
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
          <li class="breadcrumb-item active">Amadeus API</li>
        </ol>
        <div class="breadcrumb-btn">
          <div class="add-breadcrumb" style="width:147px;">
            <a href="{{ route('amadeus.refresh-token') }}" title="Resend Activation Link" style="width:196px;">
              <?xml version="1.0" encoding="utf-8"?><svg fill="#fff" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M13.5 2c-5.621 0-10.211 4.443-10.475 10h-3.025l5 6.625 5-6.625h-2.975c.257-3.351 3.06-6 6.475-6 3.584 0 6.5 2.916 6.5 6.5s-2.916 6.5-6.5 6.5c-1.863 0-3.542-.793-4.728-2.053l-2.427 3.216c1.877 1.754 4.389 2.837 7.155 2.837 5.79 0 10.5-4.71 10.5-10.5s-4.71-10.5-10.5-10.5z"/></svg>
              Refresh Token
            </a>
          </div>
        </div>
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
        <form class="form row pt-3 mb-3 validate" id="dataForm" name="dataForm" action="{{ route('amadeus-api.store') }}" method="post">
          @csrf
          <div class="col-md-6">
            <div class="form-floating form-item mb-3">
              <div class="form-item form-float-style serach-rem mb-3">
                <div class="select top-space-rem after-drp form-float-style ">
                  @php
                  $value = "";
                  @$value = App\Models\Setting::where('config_key', 'amadeus|api|credential')->get('value')[0]['value'];
                  @endphp
                  <select data-live-search="true" name="amadeus|api|credential" id="AmadeusApi" class="order-td-input selectpicker select-text height_drp is-valid">
                    <option value="" selected>Select API enviroment</option>
                    <option {{ (@old('amadeus|api|credential') == "test") || ( $value == "test") ? 'selected' : '' }} value="test">Test</option>
                    <option {{ (@old('amadeus|api|credential') == "live") || ( $value == "live") ? 'selected' : '' }} value="live">Live</option>
                  </select>
                  <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">Active API Enviroment</label>
                </div>
              </div>
            </div>
          </div>
          <div id="smtpOption" class="discount">
            <h5 class="setting-title">Test API Key</h5>
            <div class="col-md-6">
              <div class="form-item form-float-style form-group">
                @php
                $value = "";
                @$value = App\Models\Setting::where('config_key', 'amadeus|api|test|APIEndPoint')->get('value')[0]['value'];
                @endphp
                <input type="text" name="amadeus|api|test|APIEndPoint" id="APIEndPoint" value="{{ old('amadeus|api|test|APIEndPoint') ? old('amadeus|api|test|APIEndPoint') : $value }}" autocomplete="off" class="is-valid">
                <label for="APIEndPoint-amadeusApi">API End Point</label>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-item form-float-style form-group">
                @php
                $value = "";
                @$value = App\Models\Setting::where('config_key', 'amadeus|api|test|clientId')->get('value')[0]['value'];
                @endphp
                <input type="text" name="amadeus|api|test|clientId" id="clientId" value="{{ old('amadeus|api|test|clientId') ? old('amadeus|api|test|clientId') : $value }}" autocomplete="off" class="is-valid">
                <label for="clientId-amadeusApi">Client ID</label>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-item form-float-style form-group">
                @php
                $value = "";
                @$value = App\Models\Setting::where('config_key', 'amadeus|api|test|clientSecret')->get('value')[0]['value'];
                @endphp
                <input type="text" name="amadeus|api|test|clientSecret" id="clientSecret" value="{{ old('amadeus|api|test|clientSecret') ? old('amadeus|api|test|clientSecret') : $value  }}" autocomplete="off" class="is-valid">
                <label for="clientSecret-amadeusApi">Client Secret</label>
              </div>
            </div>
            <div class="brdr-btm col-md-6">
              <div class="form-item form-float-style form-group">
                @php
                $value = "";
                @$value = App\Models\Setting::where('config_key', 'amadeus|api|test|grantType')->get('value')[0]['value'];
                @endphp
                <input type="text" name="amadeus|api|test|grantType" id="grantType" value="{{ old('amadeus|api|test|grantType') ? old('amadeus|api|test|grantType') : $value }}" autocomplete="off" class="is-valid">
                <label for="grantType-amadeusApi">Grant Type</label>
              </div>
            </div>
            <div class="col-md-6 mt-3 discount">
              <h5 class="setting-title">Live API Key</h5>
              <div class="form-item form-float-style form-group">
                @php
                $value = "";
                @$value = App\Models\Setting::where('config_key', 'amadeus|api|live|APIEndPoint')->get('value')[0]['value'];
                @endphp
                <input type="text" name="amadeus|api|live|APIEndPoint" id="grantType" value="{{ old('amadeus|api|live|APIEndPoint') ? old('amadeus|api|live|APIEndPoint') : $value }}" autocomplete="off" class="is-valid">
                <label for="grantType-amadeusApi">API End Point</label>
              </div>
            </div>
          <div class="col-md-6">
            <div class="form-item form-float-style form-group">
              @php
              $value = "";
              @$value = App\Models\Setting::where('config_key', 'amadeus|api|live|clientId')->get('value')[0]['value'];
              @endphp
              <input type="text" name="amadeus|api|live|clientId" id="clientId" value="{{ old('amadeus|api|live|clientId') ? old('amadeus|api|live|clientId') : $value }}" autocomplete="off" class="is-valid">
              <label for="clientId-amadeusApi">Client ID</label>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-item form-float-style form-group">
              @php
              $value = "";
              @$value = App\Models\Setting::where('config_key', 'amadeus|api|live|clientSecret')->get('value')[0]['value'];
              @endphp
              <input type="text" name="amadeus|api|live|clientSecret" id="clientSecret" value="{{ old('amadeus|api|live|clientSecret') ? old('amadeus|api|live|clientSecret') : $value }}" autocomplete="off" class="is-valid">
              <label for="clientSecret-amadeusApi">Client Secret</label>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-item form-float-style form-group">
              @php
              $value = "";
              @$value = App\Models\Setting::where('config_key', 'amadeus|api|live|grantType')->get('value')[0]['value'];
              @endphp
              <input type="text" name="amadeus|api|live|grantType" id="grantType" value="{{ old('amadeus|api|live|grantType') ? old('amadeus|api|live|grantType') : $value }}" autocomplete="off" class="is-valid">
              <label for="grantType-amadeusApi">Grant Type</label>
            </div>
          </div>
          <div class="cards-btn">
            <button type="submit" class="btn btn-success form-btn-success">Submit</button>
            <a href="{{ route('suppliers.index') }}" class="btn btn-danger form-btn-danger">Cancel</a>
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
        "amadeus|api|test|APIEndPoint": {
          required: true,
        },
        "amadeus|api|test|clientId": {
          required: true,
        },
        "amadeus|api|test|clientSecret": {
          required: true,
        },
        'amadeus|api|test|grantType': {
          required: true,
        },
        'amadeus|api|live|APIEndPoint': {
          required: true,
        },
        "amadeus|api|live|clientId": {
          required: true,
        },
        "amadeus|api|live|clientSecret": {
          required: true,
        },
        "amadeus|api|live|grantType": {
          required: true,
        }
      },


      messages: {
        "amadeus|api|test|APIEndPoint": {
          required: "Please enter an API End Point",
        },
        "amadeus|api|test|clientId": {
          required: "Please enter a Client Id",
        },
        "amadeus|api|test|clientSecret": {
          required: "Please enter a Client Secret",
        },
        'amadeus|api|test|grantType': {
          required: "Please enter a Grant Type",
        },
        'amadeus|api|live|APIEndPoint': {
          required: "Please enter an API End Point",
        },
        "amadeus|api|live|clientId": {
          required: "Please enter a Client Id",
        },
        "amadeus|api|live|clientSecret": {
          required: "Please select a Client Secret",
        },
        "amadeus|api|live|grantType": {
          required: "Please enter a Grant Type",
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