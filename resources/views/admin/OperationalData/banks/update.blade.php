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
              <h1 class="m-0">Banks- Edit</h1>
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard </a></li>
                <li class="breadcrumb-item"><a href="{{ route('banks.index') }}">Banks</a></li>
                <li class="breadcrumb-item active">Edit</li>
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
              <form id="dataForm" action="{{route('banks.update',$banksTypeDetail['id'])}}" class="form row pt-3 mb-0 validate" enctype="multipart/form-data" method="post">
                @csrf
                @method('PUT')
                <input type="hidden" name="redirects_to" id="redirects_to" value="{{ URL::previous() }}">
                <input type="hidden" value="{{ $banksTypeDetail['id'] }}" name="bank_type_id" id="agency_type_id">
                <div class="col-md-12 row">
                    <div class="col-md-6">
                      <div class="form-item form-float-style">
                        <input name="bank_code" type="text" id="bank_code" autocomplete="off" required value="{{ $banksTypeDetail['bank_code'] }}"class="is-valid">
                        <label for="home-banner">Bank Code <span class="req-star">*</span></label>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-12 row">
                    <div class="col-md-6">
                      <div class="form-item form-float-style">
                        <input name="beneficiary_name" type="text" id="beneficiary_name" autocomplete="off" required value="{{ $banksTypeDetail['beneficiary_name'] }}"class="is-valid">
                        <label for="home-banner">Beneficiary Name <span class="req-star">*</span></label>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-12 row">
                    <div class="col-md-6">
                      <div class="form-item form-float-style">
                        <input name="account_number" type="text" id="account_number" onkeypress="return isNumber(event)"autocomplete="off"maxlength="16"required value="{{ $banksTypeDetail['account_number'] }}"class="is-valid">
                        <label for="home-banner">Account Number <span class="req-star">*</span></label>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-12 row">
                    <div class="col-md-6">
                      <div class="form-item form-float-style">
                        <input name="bank_name" type="text" id="bank_name" autocomplete="off" required value="{{ $banksTypeDetail['bank_name'] }}"class="is-valid">
                        <label for="home-banner">Bank Name  <span class="req-star">*</span></label>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-12 row">
                    <div class="col-md-6">
                      <div class="form-item form-float-style">
                        <input name="bank_address" type="text" id="bank_address" autocomplete="off" required value="{{ $banksTypeDetail['bank_address'] }}"class="is-valid">
                        <label for="home-banner">Bank Address <span class="req-star">*</span></label>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-12 row">
                    <div class="col-md-6">
                      <div class="form-item form-float-style">
                        <input name="swift_code" type="text" id="swift_code"onkeypress="return isNumber(event)"autocomplete="off" required value="{{ $banksTypeDetail['swift_code'] }}"class="is-valid">
                        <label for="home-banner">Swift Code <span class="req-star">*</span></label>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-12 row">
                    <div class="col-md-6">
                      <div class="form-item form-float-style">
                        <input name="iban_number" type="text" id="iban_number"onkeypress="return isNumber(event)" autocomplete="off" required value="{{ $banksTypeDetail['iban_number'] }}"class="is-valid">
                        <label for="home-banner">Ibna Number <span class="req-star">*</span></label>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-12 row">
                    <div class="col-md-6">
                      <div class="form-item form-float-style">
                        <input name="sort_code" type="text" id="sort_code" autocomplete="off"required value="{{ $banksTypeDetail['sort_code'] }}"class="is-valid">
                        <label for="home-banner">Sort Code <span class="req-star">*</span></label>
                      </div>
                    </div>
                  </div>
                <div class="col-md-12 row">
                  <div class="col-md-6">
                    <div class="form-floating form-item mb-0">
                        <div class="form-item form-float-style serach-rem mb-0">
                            <div class="select top-space-rem after-drp form-float-style ">
                            <select data-live-search="true" id="status" name="status" class="order-td-input selectpicker select-text height_drp is-valid">
                                <option value="1" selected @if($banksTypeDetail['status'] == '1') selected="selected" @endif>Active</option>
                                <option value="0" @if($banksTypeDetail['status'] == '0') selected="selected" @endif>In-active</option>
                            </select>
                            <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">Status </label>
                            </div>                        
                        </div>
                      </div>
                  </div>
                </div>
                <div class="cards-btn mt-3">
                  <button type="submit" id="disBtn" class="btn btn-success form-btn-success">Submit</button>
                  <a href="{{ route('banks.index') }}" type="button" class="btn btn-danger form-btn-danger">Cancel</a>
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
          bank_code: {
            required: true,
            noSpace:true,
          },
          beneficiary_name: {
            required: true,
            lettersonly: true,
            noSpace:true,
          },
          account_number: {
            required: true,
            noSpace:true,
            maxlength: 16,
          },
          bank_name: {
            lettersonly: true,
            required: true,
            noSpace:true,
          },
          bank_address: {
            required: true,
            noSpace:true,
          },
          swift_code: {
            required: true
          },
          iban_number: {
            required: true
          },
          sort_code: {
            required: true
          },
        },

        messages: {
          bank_code: {
            required: "Please enter a Bank code",
          },  
          beneficiary_name: {
            required: "Please enter a Beneficiary Name"
          },
          account_number: {
            required: "Please enter an Account Number"
          },
          bank_name: {
            required: "Please enter a Bank Name"
          },
          bank_address: {
            required: "Please enter a Bank Address"
          },
          swift_code: {
            required: "Please enter a Swift Code"
          },
          iban_number: {
            required: "Please enter an Iban Number"
          },
          sort_code: {
            required: "Please enter a Sort Code"
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