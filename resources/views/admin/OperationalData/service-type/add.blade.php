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
              <h1 class="m-0">Service Type - Add </h1>
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard </a></li>
                <li class="breadcrumb-item"><a href="{{ route('service-type.index') }}">Service Type</a></li>
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
              <form id="dataForm" action="{{route('service-type.store')}}" class="form row pt-3 mb-0 validate" enctype="multipart/form-data" method="post">
                @csrf
                <div class="col-md-12 row">
                  <div class="col-md-6">
                    <div class="form-item form-float-style">
                      <input name="service_name" type="text" id="service_name" autocomplete="off" required value="">
                      <label for="home-banner"> Name <span class="req-star">*</span></label>
                    </div>
                  </div>
                </div>
                <div class="col-md-12 row">
                  <div class="col-md-6">
                    <div class="form-item form-float-style">
                        <textarea name="service_description"></textarea>
                        <label for="">Description <span class="req-star">*</span></label>
                    </div>
                  </div>
                  <div class="col-md-12 row">
                    <div class="col-md-6">
                      <div class="form-item form-float-style">
                        <input name="guideline" type="text" id="guideline" autocomplete="off"required value="">
                        <label for="home-banner">Guideline <span class="req-star">*</span></label>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-12 row">
                <div class="col-md-6">
                    <div class="form-item form-float-style form-group">
                        <input type="file" name="profile_image" id="profile_image" autocomplete="off" required class="file-upload">
                        <label for="profile_image">Image<span class="req-star">*</span></label>
                         <p style="color: black;font-size: 13px;font-family: system-ui;font-style:italic">Please ensure that you are uploading an image is 1MB or less and one of the following types: JPG,JPEG, or PNG</p>
                    </div>
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
                <div class="cards-btn mt-3">
                  <button type="submit" id="disBtn" class="btn btn-success form-btn-success">Submit</button>
                  <a href="{{ route('service-type.index') }}" type="button" class="btn btn-danger form-btn-danger">Cancel</a>
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
    
    $(document).ready(function(){
      $("#media_type").change(function(){
        if($(this).val() == 'image'){
          $("#uploadBanner").show();
          $("#videoLink").hide();
        }
        else{
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

    jQuery.validator.addMethod("lettersonly", function (value, element) {
      return this.optional(element) || /^[a-z ]+$/i.test(value);
    }, "Letters only please");
    $(function () {
      //jquery Form validation
      $('*[value=""]').removeClass('is-valid');

      $('#dataForm').validate({
        rules: {
          service_name: {
            required: true,
            lettersonly: true,
            noSpace:true,
            maxlength:100,
            remote:{
              url:"{{ route('service-type.checkExist') }}",
              type:"POST",
              data:{
                serviceName : function(){
                  return $("#service_name").val();
                },
                service_type_id: function() {
                    return $("#service_type_id").val();
                },
                "_token":'{{ csrf_token() }}'
              }
            }
          },
          service_description: {
            required: true,
            noSpace:true,
            maxlength:300,
          },
          guideline:{
            required: true,
            noSpace:true,
            lettersonly: true,
          },
          profile_image: {
            required: true,
            extension: "jpeg|png|jpg",
            maxsize: 1000000,
            },
        },
        messages: {
          service_name: {
            required: "Please enter a Name",
            remote: "Name is already exist"
          },
          service_description: {
            required: "Please enter a Description"
          },
          guideline:{
            required: "Please enter a Guideline"
          },
          
          profile_image:{
            required: "Please select a Profile Image",
            extension: "Please select Image extension must be JPEG, PNG, or JPG",
            maxsize: "Please select Image size must be 1MB or less"
          },
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
          error.addClass('invalid-feedback');
          element.closest('.form-item').append(error);
        },
        highlight: function (element, errorClass, validClass) {
          $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
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