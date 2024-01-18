@extends('admin.layout.main')
@section('title', $header['title'])

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-4 mt-2">
            <div class="col-sm-12 align-items-center d-flex breadcrumb-style">
                <h1 class="m-0">{{ $header['heading'] }}</h1>
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('adminUser.dashboard') </a></li>
                    <li class="breadcrumb-item"><a href="{{ route('payment-gateway.index') }}">Payment Gateway</a></li>
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
            <div class="card pb-4 w-100 px-3 py-2">
                <form class="form row mb-0 pt-3 validate" action="{{route('payment-gateway.update',$paymentDetail['id'])}}" enctype="multipart/form-data" method="post" id="dataForm" name="dataForm">
                    @csrf 
                    @method('PUT')
                    <input type="hidden" name="redirects_to" id="redirects_to" value="{{ URL::previous() }}">
                    <input type="hidden" name="payment_id" id="payment_id" value="{{ $paymentDetail['id'] }}" />
                    <div class="col-md-12 row">
                  <div class="col-md-6">
                    <div class="form-item form-float-style form-group">
                      <input name="name" type="text" id="name" class="is-valid" autocomplete="off" required value="{{$paymentDetail['name']}}">
                      <label for="name">Name <span class="req-star">*</span></label>
                    </div>
                  </div>
                </div>
                <div class="col-md-12 row">
                    <div class="col-md-6">
                        <div class="form-item form-float-style form-group">
                            <textarea name="description" class="is-valid" required>{{$paymentDetail['description']}}</textarea>
                            <label for="description">Description <span class="req-star">*</span></label>
                        </div>
                  </div>
                </div>
            <div class="col-md-12 row mb-3">
                <div class="col-md-6">
                    <div class="form-item form-float-style form-group">
                        <input type="hidden" name="old_photo" id="logo" autocomplete="off" class="file-upload" value="{{$paymentDetail->logo}}">
                        <input type="file" name="profile_image" id="image_preview" autocomplete="off" class="file-upload">
                        <label for="logo">Gateway Logo</label>
                        <p style="color: black;font-size: 13px;font-family: system-ui;font-style:italic">Please ensure that you are uploading an image is 1MB or less and one of the following types: JPG,JPEG, or PNG</p>
                        <img id="image_icon"src="{{$paymentDetail->logo ?? URL::asset('assets/images/no-image.png') }}" name="logo" width="150" height="150" class="img_prev">
                    </div>
                </div>
            </div>
                 <div class="col-md-12 row mb-3">
                  <div class="col-md-6">
                    <div class="form-floating form-item mb-0">
                        <div class="form-item form-float-style serach-rem mb-0">
                            <div class="select top-space-rem after-drp form-float-style ">
                            <select data-live-search="true" id="status" name="status" class="order-td-input selectpicker select-text height_drp is-valid">
                                <option @if($paymentDetail['is_active'] =='1' ) selected="selected" @endif value="1">Active</option>
                                <option @if($paymentDetail['is_active'] =='0' ) selected="selected" @endif value="0">In-active</option>
                            </select>
                            <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">Status <span class="req-star">*</span></label>
                            </div>                        
                        </div>
                      </div>
                  </div>
                </div>
                    <div class="cards-btn">
                        <button type="submit" id="disBtn" class="btn btn-success form-btn-success">Submit</button>
                        <a href="{{ route('payment-gateway.index') }}" class="btn btn-danger form-btn-danger">Cancel</a>
                    </div>
                </form>
            </div>
            <!-- /.row -->
        </div>
        <!--/. container-fluid -->
</section>
@endsection
@section('js')


<script>
    /* Form Validation */
     $(function () {

    $('*[value=""]').removeClass('is-valid');

        $('#dataForm').validate({
            rules: {
                name: {
                    required: true,
                    lettersonly: true,
                    noSpace: true,
                    maxlength:100,
                    remote: {
                    url: "{{route('admin.payment-gateway.checkExistName')}}",
                    type: "post",
                    data: {
                      name: function() {
                        return $("#name").val();
                      },
                      payment_id: function() {
                        return $("#payment_id").val();
                      },
                      "_token": '{{ csrf_token() }}'
                    }
                },
                },
                description: {
                    required: true,
                     maxlength:300,
                },
                profile_image: {
                    required: false,
                    extension: "jpeg|png|jpg",
                    maxsize: 1000000,
                },
                
            },

            messages: {
                name: {
                    required: "Please enter a Name",
                      maxlength:"Please Enter only 100 characters",
                       remote:"Name already exist"
                },
                description: {
                    required: "Please enter a Description",
                    maxlength: "Please enter only 300 characters"
                    
                },
                 profile_image: {
                    required: "Please select a Profile Image",
                    extension: "Please select Image extension must be JPEG, PNG, or JPG",
                    maxsize: "Please select Image size must be 1MB or less"
                    
                },
                
                
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
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

    /* Image Upload To Preview */
   function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function (e) {
                $('#image_icon').attr('src', e.target.result);
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    $("#image_preview").change(function(){
        readURL(this);
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>

@append