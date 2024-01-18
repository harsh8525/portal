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


<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-4 mt-2">
      <div class="col-sm-12 d-flex breadcrumb-style">
        <h1 class="m-0">Featured Flights- Add</h1>
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard </a></li>
          <li class="breadcrumb-item"><a href="{{ route('feature-flight.index') }}">Featured Flights</a></li>
          <li class="breadcrumb-item active">Add</li>
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
      <div class="card pb-4 pt-3 px-3 w-100">
        <form id="dataForm" action="{{ route('feature-flight.store') }}" class="form row pt-3 mb-0 validate" enctype="multipart/form-data" method="post">
          @csrf
          <div class="col-md-12 row mb-3">
            <div class="col-md-6">
              <div class="form-floating form-item mb-0">
                <div class="form-item form-float-style serach-rem mb-0">
                  <div class="select top-space-rem after-drp form-float-style">
                    <select data-live-search="true" id="airline_code" name="airline_code" class="order-td-input selectpicker1 select-text height_drp is-valid select2 select-validate" style="width: 100%;">
                    </select>
                    <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">Airline <span class="req-star">*</span></label>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-12 row mb-3">
            <div class="col-md-6">

              <img id="imagePreview" src="" name="" width="150" height="150" class="img_prev" alt="airline_logo" style="display: none;">
            </div>
          </div>
          <div class="col-md-12 row mb-3">
            <div class="col-md-6">
              <div class="form-floating form-item mb-0">
                <div class="form-item form-float-style serach-rem mb-0">
                  <div class="select top-space-rem after-drp form-float-style ">
                    <select data-live-search="true" id="from_airport_code" name="from_airport_code" class="order-td-input selectpicker1 select-text height_drp is-valid select2">
                    </select>
                    <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">From Airport <span class="req-star">*</span></label>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-12 row mb-3">
            <div class="col-md-6">
              <div class="form-floating form-item mb-0">
                <div class="form-item form-float-style serach-rem mb-0">
                  <div class="select top-space-rem after-drp form-float-style">
                    <select data-live-search="true" id="to_airport_code" name="to_airport_code" class="order-td-input selectpicker1 select-text height_drp is-valid select2 select-validate">
                    </select>
                    <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">To Airport <span class="req-star">*</span></label>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-12 row mb-3">
            <div class="col-md-6">
              <div class="form-item form-float-style">
                <input name="price" type="number" id="price" autocomplete="off" value="">
                <label for="home-banner">Price ({{$getDefaultCurrency[0]['code']}})<span class="req-star">*</span></label>
              </div>
            </div>
          </div>

          <div class="col-md-12 row mb-3">
            <div class="col-md-6">
              <div class="form-floating form-item mb-0">
                <div class="form-item form-float-style serach-rem mb-0">
                  <div class="select top-space-rem after-drp form-float-style ">
                    <select data-live-search="true" id="status" name="status" class="order-td-input selectpicker select-text height_drp is-valid">
                      <option value="1" selected>Active</option>
                      <option value="0">In-active</option>
                    </select>
                    <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">Status</label>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="cards-btn mt-3">
            <button type="submit" id="disBtn" class="btn btn-success form-btn-success">Submit</button>
            <a href="{{ route('feature-flight.index') }}" type="button" class="btn btn-danger form-btn-danger">Cancel</a>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- /.row -->
  </div>
  <!--/. container-fluid -->
</section>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>

<script>
  $(document).ready(function() {
    //display arilien logo on change airline name
    $('#airline_code').on('change', function() {
      var selectedImage = $(this).val();
      var imagePreview = $('#imagePreview');
      if (selectedImage) {
        imagePreview.attr('src', "{{ URL::to('/') }}/assets/images/airlineLogo/" + selectedImage + '.png').show();
      } else {
        imagePreview.attr('src', "{{ URL::to('/') }}/assets/images/airlineLogo/" + selectedImage + '.png').hide();
      }

    });
    $("#media_type").change(function() {
      if ($(this).val() == 'image') {
        $("#uploadBanner").show();
        $("#videoLink").hide();
      } else {
        $("#uploadBanner").hide();
        $("#videoLink").show();
      }
    });


  });
</script>
<script>
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
  // Listen for file input change event
  $('.select-validate').on('change', function() {
    if ($(this).valid()) {
      $(this).removeClass('is-invalid');
      $(this).next('.invalid-feedback').remove();
    }
  });
  jQuery.validator.addMethod("lettersonly", function(value, element) {
    return this.optional(element) || /^[a-z ]+$/i.test(value);
  }, "Letters only please");
  $(function() {
    //jquery Form validation
    $('*[value=""]').removeClass('is-valid');

    $('#dataForm').validate({
      rules: {
        airline_code: {
          required: true,
          noSpace: true,
        },
        from_airport_code: {
          required: true,
          noSpace: true,
        },
        to_airport_code: {
          required: true,
          noSpace: true,
        },
        location_image: {
          required: true,
          extension: "jpeg|png|jpg",
          maxsize: 1000000,
        },
        price: {
          required: true,
          noSpace: true,
        },
      },

      messages: {
        airline_code: {
          required: "Please enter an Airlinecode"
        },
        from_airport_code: {
          required: "Please enter a From Airportcode"
        },
        to_airport_code: {
          required: "Please enter a To Airportcode"
        },
        location_image: {
          required: "Please select an  Image",
          extension: "Please select Image extension must be JPEG, PNG, or JPG",
          maxsize: "Please select Image size must be 1MB or less"
        },
        price: {
          required: "Please enter a Price"
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
      },
      submitHandler: function(form) {
        $("#disBtn").attr("disabled", true);
        form.submit();
      }
    });
  });
</script>
<script>
  $(document).ready(function() {
    $('#airline_code').select2({
      ajax: {
        url: "{{ route('feature-flight.fetchAirlineCode') }}",
        dataType: 'json',
        delay: 250,
        data: function(params) {
          return {
            term: params.term,
            page: params.page || 1,
            "_token": '{{ csrf_token() }}'
          };
        },
        processResults: function(data) {
          var mappedData = $.map(data, function(airline) {
            return {
              id: airline.airline_code,
              text: airline.airname
            };
          });

          return {
            results: mappedData,
            pagination: {
              more: mappedData.length >= 10
            }
          };
        },
        cache: true
      },
      placeholder: 'Select Airline',
    });
  });

  $(document).ready(function() {
    $('#from_airport_code').select2({
      ajax: {
        url: "/get-airport-name",
        dataType: 'json',
        delay: 250,
        data: function(params) {
          return {
            term: params.term,
            page: params.page || 1,
            "_token": '{{ csrf_token() }}'
          };
        },
        processResults: function(data) {
          var mappedData = $.map(data, function(airport) {
            return {
              id: airport.iata_code,
              text: airport.airname
            };
          });

          return {
            results: mappedData,
            pagination: {
              more: mappedData.length >= 10
            }
          };
        },
        cache: true
      },
      placeholder: 'Select From Airport',
    });

    $('#to_airport_code').select2({
      ajax: {
        url: "/get-airport-name",
        dataType: 'json',
        delay: 250,
        data: function(params) {
          return {
            term: params.term,
            page: params.page || 1,
            "_token": '{{ csrf_token() }}'
          };
        },
        processResults: function(data) {
          var mappedData = $.map(data, function(airport) {
            return {
              id: airport.iata_code,
              text: airport.airname
            };
          });

          return {
            results: mappedData,
            pagination: {
              more: mappedData.length >= 10
            }
          };
        },
        cache: true
      },
      placeholder: 'Select To Airport',
    });
  });
</script>
@append