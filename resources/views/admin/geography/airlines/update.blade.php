@extends('admin.layout.main')
@section('title',$header['title'])

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-4 mt-2">
            <div class="col-sm-12 d-flex breadcrumb-style">
                <h1 class="m-0">@lang('airline.editairline')</h1>
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('airline.dashboard') </a></li>
                    <li class="breadcrumb-item"><a href="{{ route('airlines.index') }}">@lang('airline.moduleHeading')</a></li>
                    <li class="breadcrumb-item active">@lang('airline.edit')</li>
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
                <form method="post" action="{{route('airlines.update',$airlineDetail['id'])}}" id="airlineForm" class="form row mb-0 validate" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="redirects_to" id="redirects_to" value="{{ URL::previous() }}">
                    <input type="hidden" name="airline_id" id="airline_id" value="{{$airlineDetail['id']}}" />
                    
                    <div class="brdr-btm row">
                        <div class="col-md-6">
                            <div class="form-item form-float-style">
                                <input type="text" id="airline_name_en" name="airline_names[0][airline_name]" autocomplete="off" class="is-valid" required value="{{$airlineDetail['airlineCodeName'][0]['airline_name']}}">
                                <input type="hidden" id="language_code_en" name="airline_names[0][language_code]" autocomplete="off" class="is-valid" value="en">
                                <input type="hidden" id="airline_i18ns_en_id" name="airline_names[0][airline_i18ns_id]" autocomplete="off" class="is-valid" value="{{$airlineDetail['airlineCodeName'][0]['id']}}">
                                <label for="name">@lang('airline.airlineNameEn') <span class="req-star">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-item form-float-style">
                                <input type="text" id="airline_name_ar" name="airline_names[1][airline_name]" autocomplete="off" class="is-valid" required value="{{$airlineDetail['airlineCodeName'][1]['airline_name']}}">
                                <input type="hidden" id="language_code_ar" name="airline_names[1][language_code]" autocomplete="off" class="is-valid" value="ar">
                                <input type="hidden" id="airline_i18ns_ar_id" name="airline_names[1][airline_i18ns_id]" autocomplete="off" class="is-valid" value="{{$airlineDetail['airlineCodeName'][1]['id']}}">
                                <label for="name">@lang('airline.airlineNameAr') <span class="req-star">*</span></label>
                            </div>
                        </div>

                      
                        <div class="col-md-6">
                            <div class="form-item form-float-style">
                                <input type="text" class="is-valid" id="airline_code" name="airline_code" autocomplete="off" required value="{{$airlineDetail['airline_code']}}" maxlength="3">
                                <label for="code">@lang('airline.airline_code') <span class="req-star">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-6">
                        <div class="form-item form-float-style">
                            <input type="file" id="upload-profile" class="is-valid" name="airline_logo" class="file-upload" autocomplete="off">
                            <label for="upload-profile">@lang('adminUser.uploadProfileImage')</label>
                            <p style="color: black;font-size: 13px;font-family: system-ui;font-style:italic">Please ensure that you are uploading an image is 1MB or less and dimension width:180px & height:105px one of the following types: JPG,JPEG, or PNG</p>
                        </div>
                        @if($airlineDetail['airline_logo'] != "" || URL::asset('assets/images/airlineLogo/'.$airlineDetail['airline_code'].'.png') != '' )
                        <div id='airline_logo_section' class="mb-3">
                            <img src="{{$airlineDetail['airline_logo'] ? $airlineDetail['airline_logo'] : URL::asset('assets/images/airlineLogo/'.$airlineDetail['airline_code'].'.png')}}" id='user_airline_logo' width="180" height="105" class="img_prev mt-0" />
                            <input type="hidden" id="old_airline_logo" name="old_airline_logo" value="{{$airlineDetail['airline_logo']}}" />
                        </div>
                        @endif

                    </div>
                        
                        <div class="col-md-6">
                            <div class="form-floating form-item mb-0">
                                <div class="form-item form-float-style serach-rem mb-0">
                                    <div class="select top-space-rem after-drp form-float-style ">
                                        <select data-live-search="true" id="status" name="status" class="order-td-input selectpicker select-text height_drp is-valid">
                                            <option @if($airlineDetail['status']=='active' ) selected="selected" @endif value="active" selected="">@lang('airline.active')</option>
                                            <option @if($airlineDetail['status']=='inactive' ) selected="selected" @endif value="inactive">@lang('airline.inActive')</option>
                                        </select>
                                        <label class="select-label searchable-drp">Status</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>


                    <div class="cards-btn mt-3">
                        <button type="submit" id="disBtn" class="btn btn-success form-btn-success">@lang('airline.submit')</button>
                        <a href="{{ route('airlines.index') }}" type="button" class="btn btn-danger form-btn-danger">Cancel</a>
                    </div>
                </form>

            </div>
            <!-- /.row -->
        </div>
        <!--/. container-fluid -->
</section>
@endsection
@section('js')
<!-- Page specific script -->
<script>

    $(function() {
        $.validator.addMethod('latitude', function(value, element) {
        return this.optional(element) ||
            value.length >= 4 && /^(?=.)-?((8[0-5]?)|([0-7]?[0-9]))?(?:\.[0-9]{1,20})?$/.test(value);
    }, 'Your Latitude format has error.')

    // Longitude
    $.validator.addMethod('longitude', function(value, element) {
        return this.optional(element) ||
            value.length >= 4 && /^(?=.)-?((0?[8-9][0-9])|180|([0-1]?[0-7]?[0-9]))?(?:\.[0-9]{1,20})?$/.test(value);
    }, 'Your Longitude format has error.')
        //jquery Form validation
        $('#airlineForm').validate({
            onkeyup: false, //turn off auto validate whilst typing
            rules: {
                "airline_names[0][airline_name]": {
                    required: true,
                    noSpace: true,
                    remote: {
                        url: "{{route('airlines.checkAirlineNameEnExist') }}",
                        type: "POST",
                        data: {
                            "airline_names[0][airline_name]": function() {
                                return $("#airline_name_en").val();
                            },
                            "airline_names[0][airline_i18ns_id]": function() {
                                return $("#airline_i18ns_en_id").val();
                            },
                            "_token": '{{ csrf_token() }}'
                        }
                    }
                },
                "airline_names[1][airline_name]": {
                    required: true,
                    noSpace: true,
                    remote: {
                        url: "{{route('airlines.checkAirlineNameArExist') }}",
                        type: "POST",
                        data: {
                            "airline_names[1][airline_name]": function() {
                                return $("#airline_name_ar").val();
                            },
                            "airline_names[1][airline_i18ns_id]": function() {
                                return $("#airline_i18ns_ar_id").val();
                            },
                            "_token": '{{ csrf_token() }}'
                        }
                    }
                },
                airline_logo: {
                    extension: "jpeg|png|jpg",
                    maxsize: 1000000,
                },
                airline_code: {
                    required: true,
                    noSpace: true,
                    remote: {
                        url: "{{route('airlines.checkAirlineCodeExist') }}",
                        type: "POST",
                        data: {
                            airline_code: function() {
                                return $("#airline_code").val();
                            },
                            airline_id: function() {
                                return $("#airline_id").val();
                            },
                            "_token": '{{ csrf_token() }}'
                        }
                    }
                },
                
            },
            messages: {
                "airline_names[0][airline_name]": {
                    required: "Please enter an Airline Name English",
                    remote: "airline Name English is already taken"
                },
                "airline_names[1][airline_name]": {
                    required: "Please enter an Airline Name Arabic",
                    remote: "airline Name Arabic is already taken"
                },
                airline_code: {
                    required: "Please enter an Airline Code",
                    remote: "ISO Code is already taken"
                },
                airline_logo: {
                    extension: "Please select Logo format must be .jpg, .jpeg or .png",
                    maxsize: "Please select Logo size less than 1MB"
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
            highlight: function(element, errorClass) {
                $(element).removeClass(errorClass); //prevent class to be added to selects
            },
            submitHandler: function(form) {
                $("#disBtn").attr("disabled", true);
                form.submit();
            }
        });
    });
</script>
<script>
   
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>

@append