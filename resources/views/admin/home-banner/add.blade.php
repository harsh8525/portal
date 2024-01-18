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
        <h1 class="m-0">Home Banner - Add</h1>
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard </a></li>
          <li class="breadcrumb-item"><a href="{{ route('home-banner.index') }}">Home Banners</a></li>
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
        <form id="dataForm" action="{{route('home-banner.store')}}" class="form row pt-3 mb-0 validate" enctype="multipart/form-data" method="post">
          @csrf
          <div class="col-md-12 row">
            <div class="col-md-6">
              <div class="form-item form-float-style">
                <input id="banner_name_english" type="text" name="banner_names[0][banner_name]" autocomplete="off" required value="">
                <input type="hidden" id="language_code_en" name="banner_names[0][language_code]" autocomplete=" off" class="is-valid" value="en">
                <label for="home-banner">Home Banner Title English <span class="req-star">*</span></label>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-item form-float-style">
                <input id="banner_name_arabic" type="text" name="banner_names[1][banner_name]" autocomplete="off" required value="">
                <input type="hidden" id="language_code_ar" name="banner_names[1][language_code]" autocomplete="off" class="is-valid" value="ar">
                <label for="home-banner">Home Banner Title Arabic <span class="req-star">*</span></label>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-floating form-item mb-3">
                <div class="form-item form-float-style serach-rem mb-3">
                  <div class="select top-space-rem after-drp form-float-style ">
                    <select data-live-search="true" name="panel" id="panel" class="order-td-input selectpicker select-text height_drp is-valid">
                      <option value="b2c" selected>B2C</option>
                      <option value="b2b">B2B</option>
                      <option value="supplier">Supplier</option>
                    </select>

                    <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">Select Panel</label>
                  </div>
                </div>
              </div>

            </div>
          </div>
          <div class="col-md-6 d-none">
            <div class="select top-space-rem after-drp form-item form-float-style">
              @component('components.country_city_select', [
              'name' => 'country_name',
              'id' => 'country_code',
              'class' => 'order-td-input selectpicker1 select-text height_drp is-valid select2',
              'selected' => '',
              'placeholder' => 'Select Country'
              ])
              @endcomponent
              <label for="country" id="customer-country">@lang('customers.country') <span class="req-star">*</span></label>
            </div>
          </div>
          <div class="col-md-12 row">

            <div class="col-md-6" id="uploadBanner">
              <div class="form-item form-float-style">
                <input type="hidden" id="croppedImage" name="croppedImage" value="">
                @component('components.crop-image', [
                'name' => 'upload_banner',
                'id' => 'upload_banner',
                'class' => 'file-upload is-valid image'

                ])
                @endcomponent
                <label for="upload_banner">Upload Home Banner<span class="req-star">*</span></label>
              </div>
              <p id="error_image" class="text-danger"></p>

              <p class="upload-img-des mb-0">These images are visible in the home banner page.
                Support jpg, jpeg, or png files. Recommended size width:3200px and height:685px
              </p>
              <div id='profile_image_section'>
                <img data-toggle="popover" id="croppedImagePreview" height="150" width="100%" src="" alt="no-image" style="display: none;">
                <label for="upload-profile">Upload Home Banner</label>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-item form-float-style">
                <input name="sort_order" type="number" id="addbanner-sort-order" autocomplete="off" onkeypress="return isNumber(event)" value="" required>
                <label for="addbanner-sort-order">Sort Order <span class="req-star">*</span></label>
              </div>
            </div>
          </div>

          <div class="col-md-12 row">
            <div class="col-md-6">
              <div class="form-item form-float-style">
                <input type="text" class="datepicker is-valids is-valid" name="from_date" id="from_date" placeholder="DD/MM/YYYY" autocomplete="off">
                <label for="datepicker">From Date<span class="req-star">*</span></label>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-item form-float-style">
                <input type="text" class="datepicker is-valids is-valid" name="to_date" id="to_date" placeholder="DD/MM/YYYY" autocomplete="off">
                <label for="datepicker">To Date<span class="req-star">*</span></label>
              </div>
            </div>
          </div>
          <div class="col-md-12 row">
          </div>
          <div class="col-md-12 row">
          </div>
          <div class="col-md-12 row">
            <div class="col-md-6">
              <div class="form-floating form-item mb-0">
                <div class="form-item form-float-style serach-rem mb-0">
                  <div class="select top-space-rem after-drp form-float-style ">
                    <select data-live-search="true" id="status" name="status" class="order-td-input selectpicker select-text height_drp is-valid">
                      <option value="1" selected>Active</option>
                      <option value="0">In-Active</option>
                    </select>
                    <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">Status</label>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="cards-btn mt-3">
            <button type="submit" id="disBtn" class="btn btn-success form-btn-success">Submit</button>
            <a href="{{ route('home-banner.index') }}" type="button" class="btn btn-danger form-btn-danger">Cancel</a>
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
  var dateToday = new Date();
  $(function() {
    $("#from_date").datepicker({
      dateFormat: 'dd-mm-yy',
      minDate: dateToday,
      onClose: function(selected) {
        if (selected.length <= 0) {
          $("#to_date").datepicker('disable');
        } else {
          $("#to_date").datepicker('enable');
        }
        $("#to_date").datepicker("option", "minDate", selected);
        $(this).valid();
      }
    });
    $("#to_date").datepicker({
      dateFormat: 'dd-mm-yy',
      minDate: dateToday,
      onClose: function(selected) {
        if (selected.length <= 0) {
          $("#from_date").datepicker('disable');
        } else {
          $("#from_date").datepicker('enable');
        }
        $("#from_date").datepicker("option", "maxDate", selected);
        $(this).valid();
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
  jQuery.validator.addMethod("lettersOrArabic", function(value, element) {
    return /^[\u0600-\u06FF\s]+$/.test(value);
  });
  $(function() {
    //jquery Form validation
    $('*[value=""]').removeClass('is-valid');

    $('#dataForm').validate({
      rules: {
        "banner_names[0][banner_name]": {
          required: true,
          noSpace: true,
          maxlength: 100
        },
        "banner_names[1][banner_name]": {
          required: true,
          noSpace: true,
          maxlength: 100
        },
        sort_order: {
          required: true,
          digits: true
        },
        croppedImage: {
          required: true,
        
        },
        upload_banner: {
            required: {
                depends: function(element) {
                    return $("#croppedImage").val() === '';
                }
            }
        },
        video_link: {
          required: true,
        },
        mobile_banner: {
          required: false,
          extension: "jpg|jpeg|png",
          maxsize: 1000000

        },
        
        category_id: {
          required: true,
        }
      },


      messages: {
        "banner_names[0][banner_name]": {
          required: "Please enter a Home Banner Name In English"

        },
        'banner_names[1][banner_name]': {
          required: "Please enter a Home Banner Name In Arabic",
          lettersOrArabic: 'Please enter valid Arabic text.'

        },
        sort_order: {
          required: "Please enter a Sort Number"
        },
        upload_banner: {
          required: "Please Upload Home Banner Image",
          extension: "Please select image format must be .jpg, .jpeg or .png",
          maxsize: "Please upload image size less than 1MB"

        },
        video_link: {
          required: "Please Enter Home Banner's Video Link",
        },
        mobile_banner: {
          required: "Please Upload Mobile Home Banner Image",
          extension: "Please select image format must be .jpg, .jpeg or .png",
          maxsize: "Please upload image size less than 1MB"
        },
        from_date: {
          required: "Please select a From Date"
        },
        to_date: {
          required: "Please select a To Date"
        },
        category_id: {
          required: "Please select a Category"
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
      },
      submitHandler: function(form) {
        $("#disBtn").attr("disabled", true);
        form.submit();
      }
    });
  });



  // Check all fields on submit
  document.addEventListener('submit', function(event) {

    // Only run on forms flagged for validation
    if (!event.target.classList.contains('validate')) return;

    // Get all of the form elements
    var fields = event.target.elements;

    // Store the first field with an error to a variable so we can bring it into focus later
    var error, hasErrors;
    for (var i = 0; i < fields.length; i++) {
      error = hasError(fields[i]);
      if (error) {
        showError(fields[i], error);
        if (!hasErrors) {
          hasErrors = fields[i];
        }
      }
    }

    // If there are errrors, don't submit form and focus on first element with error
    if (hasErrors) {
      event.preventDefault();
      hasErrors.focus();
    }
  }, false);
</script>

@append