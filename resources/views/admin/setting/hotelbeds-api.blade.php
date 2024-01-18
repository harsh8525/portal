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
          <li class="breadcrumb-item active">Hotel Beds API</li>
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
        <form class="form row pt-3 mb-3 validate" id="dataForm" name="dataForm" action="{{ route('hotelbeds-api.store') }}" method="post">
          @csrf
          <div class="col-md-6">
            <div class="form-floating form-item mb-3">
              <div class="form-item form-float-style serach-rem mb-3">
                <div class="select top-space-rem after-drp form-float-style ">
                  @php
                  $value = "";
                  @$value = App\Models\Setting::where('config_key', 'hotelbeds|api|credential')->get('value')[0]['value'];
                  @endphp
                  <select data-live-search="true" name="hotelbeds|api|credential" id="HotelBedsApi" class="order-td-input selectpicker select-text height_drp is-valid">
                    <option value="" selected>Select API enviroment</option>
                    <option {{ (@old('hotelbeds|api|credential') == "test") || ( $value == "test") ? 'selected' : '' }} value="test">Test</option>
                    <option {{ (@old('hotelbeds|api|credential') == "live") || ( $value == "live") ? 'selected' : '' }} value="live">Live</option>
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
                @$value = App\Models\Setting::where('config_key', 'hotelbeds|api|test|endPoint')->get('value')[0]['value'];
                @endphp
                <input type="text" name="hotelbeds|api|test|endPoint" id="endPoint" value="{{ old('hotelbeds|api|test|endPoint') ? old('hotelbeds|api|test|endPoint') : $value }}" autocomplete="off" class="is-valid">
                <label for="endPoint-hotelbedsApi">End Point</label>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-item form-float-style form-group">
                @php
                $value = "";
                @$value = App\Models\Setting::where('config_key', 'hotelbeds|api|test|apiKey')->get('value')[0]['value'];
                @endphp
                <input type="text" name="hotelbeds|api|test|apiKey" id="Api-key" value="{{ old('hotelbeds|api|test|apiKey') ? old('hotelbeds|api|test|apiKey') : $value  }}" autocomplete="off" class="is-valid">
                <label for="ApiKey-hotelbedsApi">Api-Key</label>
              </div>
            </div>
            <div class="brdr-btm col-md-6">
              <div class="form-item form-float-style form-group">
                @php
                $value = "";
                @$value = App\Models\Setting::where('config_key', 'hotelbeds|api|test|secret')->get('value')[0]['value'];
                @endphp
                <input type="text" name="hotelbeds|api|test|secret" id="secret" value="{{ old('hotelbeds|api|test|secret') ? old('hotelbeds|api|test|secret') : $value }}" autocomplete="off" class="is-valid">
                <label for="secret-hotelbedsApi">Secret</label>
              </div>
            </div>
            <div class="col-md-6 mt-3 discount">
              <h5 class="setting-title">Live API Key</h5>
              <div class="form-item form-float-style form-group">
                @php
                $value = "";
                @$value = App\Models\Setting::where('config_key', 'hotelbeds|api|live|endPoint')->get('value')[0]['value'];
                @endphp
                <input type="text" name="hotelbeds|api|live|endPoint" id="endPoint" value="{{ old('hotelbeds|api|live|endPoint') ? old('hotelbeds|api|live|endPoint') : $value }}" autocomplete="off" class="is-valid">
                <label for="endPoint-hotelbedsApi">End Point</label>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-item form-float-style form-group">
                @php
                $value = "";
                @$value = App\Models\Setting::where('config_key', 'hotelbeds|api|live|apiKey')->get('value')[0]['value'];
                @endphp
                <input type="text" name="hotelbeds|api|live|apiKey" id="Api-key" value="{{ old('hotelbeds|api|live|apiKey') ? old('hotelbeds|api|live|apiKey') : $value }}" autocomplete="off" class="is-valid">
                <label for="Api-key-hotelbedsApi">Api-key</label>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-item form-float-style form-group">
                @php
                $value = "";
                @$value = App\Models\Setting::where('config_key', 'hotelbeds|api|live|secret')->get('value')[0]['value'];
                @endphp
                <input type="text" name="hotelbeds|api|live|secret" id="secret" value="{{ old('hotelbeds|api|live|secret') ? old('hotelbeds|api|live|secret') : $value }}" autocomplete="off" class="is-valid">
                <label for="secret-hotelbedsApi">Secret</label>
              </div>
            </div>
          </div>
          <div class="cards-btn">
            <button type="submit" class="btn btn-success form-btn-success">@lang('smtp.submit')</button>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-danger form-btn-danger">Cancle</a>
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
        "hotelbeds|api|test|endPoint": {
          required: true,
        },
        "hotelbeds|api|test|apiKey": {
          required: true,
        },
        'hotelbeds|api|test|secret': {
          required: true,
        },
        "hotelbeds|api|live|endPoint": {
          required: true,
        },
        "hotelbeds|api|live|apiKey": {
          required: true,
        },
        "hotelbeds|api|live|secret": {
          required: true,
        }
      },


      messages: {
        "hotelbeds|api|test|endPoint": {
          required: "Please enter an End Point",
        },
        "hotelbeds|api|test|apiKey": {
          required: "Please enter an Api-Key",
        },
        'hotelbeds|api|test|secret': {
          required: "Please enter a Secret",
        },
        "hotelbeds|api|live|endPoint": {
          required: "Please enter an End Point",
        },
        "hotelbeds|api|live|apiKey": {
          required: "Please select an Api-Key",
        },
        "hotelbeds|api|live|secret": {
          required: "Please enter a Secret",
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