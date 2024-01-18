@extends('admin.layout.main')
@section('title',$header['title'])

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">

<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-4 mt-2">
      <div class="col-sm-12 align-items-center d-flex breadcrumb-style">
        <h1 class="m-0">Change Password</h1>
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard </a></li>
          <li class="breadcrumb-item active">Change Password</li>
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
      <div class="card pb-4 pt-2 px-3 col-md-8">
        <form class="form row pt-3 mb-3 validate" action="{{ route('admin.change-password.update',$userDetail['id']) }}" method="post" enctype="multipart/form-data" name="dataForm" id="dataForm">
          @csrf

          <input type="hidden" name="user_id" id="user_id" value="{{ $userDetail['id'] }}" />
          <input type="hidden" name="old_password" id="old_password" value="{{ $userDetail['password'] }}" />


          <div class="col-md-4">
            <div class="form-item form-float-style">
              <input type="text" id="current_password" name="current_password" autocomplete="off" value="" class="is-valid">
              <label for="current_password">Current Password</label>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-item form-float-style">
              <input type="text" id="new_password" name="new_password" autocomplete="off" value="" class="is-valid">
              <label for="newpswrd">New Password</label>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-item form-float-style">
              <input type="text" id="confirm-pswrd" name="confirm_password" autocomplete="off" value="" class="is-valid">
              <label for="confirm-pswrd">Confirm New Password</label>
            </div>
          </div>
          <div class="cards-btn">
            <button type="submit" id="disBtn" class="btn btn-success form-btn-success">Submit</button>
            <a href="{{ url()->previous() }}" class="btn btn-danger form-btn-danger">Cancel</a>
          </div>
        </form>

      </div>
      <div class="card pb-4 pt-2 px-3 col-md-4">
        <li style="color:black;font-size: medium;">Minimum length should be {{App\Models\Setting::where('config_key','passwordSecurity|minimumPasswordLength')->get('value')[0]['value']}}</li>
        <li style="color:black;font-size: medium;">At least {{App\Models\Setting::where('config_key','passwordSecurity|uppercaseCharacter')->get('value')[0]['value']}} upper case characters</li>
        <li style="color:black;font-size: medium;">At least {{App\Models\Setting::where('config_key','passwordSecurity|lowercaseCharacter')->get('value')[0]['value']}} lower case characters</li>
        <li style="color:black;font-size: medium;">At least {{App\Models\Setting::where('config_key','passwordSecurity|numericCharacter')->get('value')[0]['value']}} numeric characters</li>
        <li style="color:black;font-size: medium;">At least {{App\Models\Setting::where('config_key','passwordSecurity|specialCharacter')->get('value')[0]['value']}} special characters</li>
        <li style="color:black;font-size: medium;">At least {{App\Models\Setting::where('config_key','passwordSecurity|alphanumericCharacter')->get('value')[0]['value']}} alphanumeric characters</li>
      </div>
    </div>
    <!-- /.row -->
  </div>
  <!--/. container-fluid -->
</section>
@endsection
@section('js')
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
<script src="https://cdn.ckeditor.com/4.13.0/standard/ckeditor.js"></script>

<script>
  jQuery.validator.addMethod("lettersonly", function(value, element) {
    return this.optional(element) || /^[a-z ]+$/i.test(value);
  }, "Letters only please");


  $.validator.addMethod("passwordValidation", function(value, element) {
    var validator = this;
    var isValid = false;

    $.ajax({
      url: "{{route('admin.change-password.validate')}}",
      method: "POST",
      data: {
        newPassword: value,
        _token: '{{ csrf_token() }}'
      },
      async: false,
      success: function(response) {
        if (response.valid === false) {
          isValid = false;
          validator.settings.messages[element.name].passwordValidation = response.message;
        } else {
          isValid = true;
        }

      }
    });

    return isValid;
  }, "");
  $(function() {
    //jquery Form validation
    $('*[value=""]').removeClass('is-valid');

    $('#dataForm').validate({
      rules: {
        current_password: {
          required: true,
          remote: {
            url: "{{route('admin.current-password.validate')}}",
            type: "post",
            data: {
              currentPassword: function() {
                return $("#current_password").val();
              },
              "_token": '{{ csrf_token() }}'
            }
          }
        },

        new_password: {
          required: true,

          passwordValidation: true
        },
        confirm_password: {
          required: function(element) {
            return $("#new_password").val() != "";
          },
          equalTo: "#new_password"
        },


      },


      messages: {
        current_password: {
          required: "Please enter a Current Password",
          remote: "Entered current password is wrong "
        },
        new_password: {
          required: "Please enter a New Password",
        },
        confirm_password: {
          required: "Please enter a Confirm Password",
          equalTo: "Confirm Password does not match"
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
  // INCLUDE JQUERY & JQUERY UI 1.12.1
  $(function() {
    $("#datepicker").datepicker({
      dateFormat: "dd-mm-yy",
      duration: "fast"
    });
  });
</script>

@append