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
        <h1 class="m-0">Suppliers - Add</h1>
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard </a></li>
          <li class="breadcrumb-item"><a href="{{ route('suppliers.index') }}">Suppliers</a></li>
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
        <form id="dataForm" action="{{route('suppliers.store')}}" class="form row pt-3 mb-0 validate" enctype="multipart/form-data" method="post">
          @csrf
          <div class="col-md-12 row">
            <div class="col-md-6">
              <div class="form-item form-float-style">
                <input name="code" type="hidden" id="code" autocomplete="off" value="">
                <input name="supplier_name" type="text" id="supplier_name" autocomplete="off">
                <label for="home-banner"> Name <span class="req-star">*</span></label>
              </div>
            </div>
          </div>
          <div class="col-md-12 row">
            <div class="col-md-6">
              <div class="form-floating form-item">
                <div class="form-item form-float-style serach-rem">
                  <div class="select top-space-rem after-drp form-float-style ">
                    <select data-live-search="false" id="core_service_type_id" name="core_service_type_id" class="selectpicker order-td-input select-text height_drp is-valid select-validate">
                      <option selected disabled>Select Service Type</option>
                      @foreach($dataServiceType as $list)
                      <option value="{{ $list['id']}}">{{ $list['name']}}</option>
                      @endforeach
                    </select>
                    <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">Service Type <span class="req-star">*</span></label>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-6 d-none">
            <div class="select top-space-rem after-drp form-item form-float-style">
              @component('components.country_city_select', [
              'name' => 'nationality_id',
              'id' => 'country_code',
              'class' => 'order-td-input selectpicker1 select-text height_drp is-valid select2',
              'selected' => '',
              'placeholder' => 'Select Nationality'
              ])
              @endcomponent
              <label for="country" id="customer-country">@lang('travellers.nationality') <span class="req-star">*</span></label>
            </div>
          </div>
          <div class="col-md-12 row">
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
          <div class="col-md-6 mt-3">
            <div class="form-item form-float-style">
              @component('components.crop-image', [
              'name' => 'cover_image',
              'id' => 'cover_image',
              'class' => 'file-upload is-valid image'

              ])
              @endcomponent
              <label for="upload-profile">Uplaod Image</label>
            </div>
            <p class="upload-img-des mb-0">This image is visible in the supplier page.
              Support jpg, jpeg, or png files.
            </p>
            <div id='profile_image_section'>
              <img data-toggle="popover" id="croppedImagePreview" height="150" width="150" src="" alt="no-image" style="display: none;">
              <label for="upload-profile">@lang('travellers.uploadProfileImage')</label>
            </div>
          </div>
      </div>
      <input type="hidden" id="croppedImage" name="croppedImage" value="">
      <div class="cards-btn mt-3">
        <button type="submit" id="disBtn" class="btn btn-success form-btn-success">Submit</button>
        <a href="{{ route('suppliers.index') }}" type="button" class="btn btn-danger form-btn-danger">Cancel</a>
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
  
  $('.select-validate').on('change', function() {
    if ($(this).valid()) {
      // If the file is valid, remove the 'is-invalid' class
      $(this).removeClass('is-invalid');
      // Remove the 'invalid-feedback' element
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
        supplier_name: {
            required: true,
          noSpace:true,
          lettersonly:true,
          maxlength:100,
          remote:{
            url:"{{ route('suppliers.checkExist') }}",
            type:"POST",
            data:{
              supplierName : function(){
                return $("#supplier_name").val();
              },
              "_token":'{{ csrf_token() }}'
            }
          }
        },
        // supplier_description: {
        //   required: true,
        //   noSpace: true,
        //   maxlength: 300,
        // },
        core_service_type_id: {
          required: true,
          noSpace: true,
        },
        cover_image: {
          extension: "jpeg|png|jpg",
          maxsize: 1000000,
        },
      },

      messages: {
        supplier_name: {
          required: "Please enter a Name",
          remote: "Name is already exist",
          lettersonly: "Please enter only Alphabets",
        },
        supplier_description: {
          required: "Please enter a Description"
        },

        core_service_type_id: {
          required: "Please select a ServiceType"
        },

        cover_image: {
          extension: "Please select image format must be .jpg, .jpeg or .png",
          maxsize: "Please upload image size less than 1MB"
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

@append