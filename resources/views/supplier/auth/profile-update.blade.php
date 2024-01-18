@extends('supplier.layout.main')
@section('title','Profile-Edit')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">

<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-4 mt-2">
      <div class="col-sm-12 align-items-center d-flex breadcrumb-style">
        <h1 class="m-0">Profile</h1>
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('b2b.dashboard') }}">Dashboard </a></li>
          <li class="breadcrumb-item active">Edit</li>
        </ol>
      </div><!-- /.col -->

    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<section class="content">
  <div class="container-fluid">
    <!-- Info boxes -->
    <div class="row">
      <div class="card pb-4 w-100 px-3 py-2">
        <form method="post" action="{{route('supplier.profile.update',$userDetail['id'])}}" id="dataForm" class="form row mb-0 validate" enctype="multipart/form-data">
          @csrf
          @method('PUT')
          <input type="hidden" name="admin_user_id" id="user_id" value="{{ @$userDetail['id'] }}" />
          <input type="hidden" name="status" id="status" value="{{ @$userDetail['status'] }}" />
          <div class="col-md-6">
            <div class="form-item form-float-style form-group">
              <input type="text" name="fname" id="fname" class="is-valid" value="{{$userDetail['name']}}" autocomplete="off" required>
              <label for="fname">@lang('adminUser.fullName') <span class="req-star">*</span></label>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-item form-float-style form-group" style="pointer-events: none;">
              <input type="email" name="email" id="email" class="is-valid" value="{{$userDetail['email']}}" autocomplete="off" required>
              <label for="email">@lang('adminUser.emailAddress') <span class="req-star">*</span></label>
            </div>
          </div>
          <div class="col-md-6">
            <div class="row">
              <div class="col-md-6">
                <div class="form-item form-float-style" style="pointer-events: none;">
                  <select data-live-search="true" id="isd_code" name="isd_code" class="order-td-input selectpicker select-text height_drp is-valid">
                    <option value="">Select Option</option>
                    @foreach($getIsdCode as $getIsdCodeName)
                    <option value="{{ $getIsdCodeName->isd_code }}" @if ($getIsdCodeName->isd_code== $userDetail->isd )
                      {{'selected="selected"'}}
                      @endif>{{ $getIsdCodeName->isd_code }}
                    </option>
                    @endforeach
                  </select>
                  <label for="isd_code" id="isd-code-customer">ISD Code<span class="req-star">*</span></label>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-item form-float-style form-group" style="pointer-events: none;">
                  <input type="text" name="mobile" id="mobile" maxlength="10" onkeypress="return isNumber(event)" value="{{$userDetail['mobile']}}" autocomplete="off" required>
                  <label for="mobile">@lang('adminUser.mobileNumber') <span class="req-star">*</span></label>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-floating form-float-style form-group required mb-3">
              <div class="form-item form-float-style serach-rem mb-3">
                <div class="select top-space-rem after-drp form-float-style" style="pointer-events: none;">
                  <select data-live-search="true" name="role" id="role" class="order-td-input selectpicker select-text height_drp is-valid">
                    <option value="" disabled>Select Role</option>
                    <option value="{{ $userDetail['role_code'] }}" selected>{{ $userDetail['role_code'] }}
                    </option>
                  </select>
                  <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">@lang('Role') <span class="req-star">*</span></label>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-item form-float-style">
              <input type="file" id="upload-profile" class="is-valid" name="profile_image" class="file-upload" autocomplete="off">
              <label for="upload-profile">@lang('adminUser.uploadProfileImage')</label>
              <!-- {{$userDetail['profile_image']}} -->
              <p style="color: black;font-size: 13px;font-family: system-ui;font-style:italic">Please ensure that you are uploading an image is 1MB or less and one of the following types: JPG,JPEG, or PNG</p>
            </div>
            @if($userDetail['profile_image'] != "")
            <div id='profile_image_section' class="mb-3">
              <img src="{{$userDetail['profile_image']}}" id='user_profile_image' width="150" height="150" class="img_prev mt-0" />
              <!--<a href="javascript:void(0);" onclick="removeProfileImage()">Remove Image</a>-->
              <input type="hidden" id="old_profile_image" name="old_profile_image" value="{{$userDetail['profile_image']}}" />
            </div>
            @endif
          </div>
          <div class="cards-btn">
            <button type="submit" class="btn btn-success form-btn-success">@lang('adminUser.submit')</button>
            <a href="{{ route('supplier.dashboard') }}" class="btn btn-danger form-btn-danger">Cancel</a>
          </div>
        </form>
      </div>
    </div>
</section>
@endsection
@section('js')
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
<script src="https://cdn.ckeditor.com/4.13.0/standard/ckeditor.js"></script>

<script>
  $(function() {
    $.validator.addMethod("validateUserMobile", function(value, element) {
      var data = {
          "_token": '{{ csrf_token() }}',
          "mobile": value
        },
        eReport = ''; //error report

      $.ajax({
        type: "POST",
        url: "{{route('admin.user.checkAdminUser')}}",
        dataType: "json",
        data: data,
        success: function(data) {
          //console.log(data.status);
          if (data !== 'true') {
            return false;
          } else {
            return true;
          }
        },
        error: function(xhr, textStatus, errorThrown) {
          //console.log('ajax loading error... ... '+textStatus);
          return false;
        }
      });

      // return r;

    }, 'already taken');

    $.validator.addMethod("email_regex", function(value, element, regexpr) {
      return this.optional(element) || regexpr.test(value);
    }, "Please enter a valid Email Address.");

    //on change profile set old_profile_image blank
    $('#upload-profile').change(function() {
      $("#old_profile_image").val('');
    });

    //remove profile image
    function removeProfileImage() {
      $('#profile_image_section').hide();
      $("#old_profile_image").val('');
    }

    $('*[value=""]').removeClass('is-valid');

    $('#dataForm').validate({
      rules: {
        fname: {
          required: true,
          lettersonly: true,
          noSpace: true
        },
        profile_image: {
          extension: "jpeg|png|jpg",
          maxsize: 1000000,
        },
        mobile: {
          required: true,
          digits: true,
          minlength: 10,
          noSpace: true,

        },
        email: {
          required: true,
          email: true,
          noSpace: true,
          email_regex: /\b[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b/i,
          // remote: {
          //     url: "{{route('user.checkEmailAgencyUserExist')}}",
          //     type: "post",
          //     data: {
          //         email: function() {
          //             return $("#email").val();
          //         },
          //         admin_user_id: function() {
          //             return $("#admin_user_id").val();
          //         },
          //         "_token": '{{ csrf_token() }}'
          //     }
          // }

        },
        role: {
          required: true,
          noSpace: true
        }

      },
      messages: {
        fname: {
          required: "Please enter a Full Name",
        },
        profile_image: {
          required: "Please select a Profile Image",
          extension: "Please select image format must be .jpg, .jpeg or .png",
          maxsize: "Please upload image size less than 1MB"

        },
        mobile: {
          required: "Please enter Mobile Number",
          minlength: "Please enter valid Mobile Number",
          remote: "Mobile Number is already taken."

        },
        email: {
          required: "Plese enter an Email Address",
          remote: "Email address is already taken."
        },
        password: {
          required: "Please generate your Password",
        },
        role: {
          required: "Please select a Role",
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



<!-- Page specific script -->
<script>
  CKEDITOR.replace('editor1');
  CKEDITOR.on('instanceReady', function(evt) {
    var editor = evt.editor;

    editor.on('change', function(e) {
      var contentSpace = editor.ui.space('contents');
      var ckeditorFrameCollection = contentSpace.$.getElementsByTagName('iframe');
      var ckeditorFrame = ckeditorFrameCollection[0];
      var innerDoc = ckeditorFrame.contentDocument;
      var innerDocTextAreaHeight = $(innerDoc.body).height();
      console.log(innerDocTextAreaHeight);
    });
  });

  document.getElementById('flexCheckDefault1').onclick = function() {
    var checkboxes = document.getElementsByName('check');
    for (var checkbox of checkboxes) {
      checkbox.checked = this.checked;
    }
  }
</script>

@append