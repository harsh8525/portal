@extends('admin.layout.main')
@section('title',$header['title'])

@section('content')
<?php app()->setLocale("en"); ?>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-4 mt-2">
            <div class="col-sm-12 d-flex breadcrumb-style">
                <h1 class="m-0">@lang('airline.addairline')</h1>
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('airline.dashboard') </a></li>
                    <li class="breadcrumb-item"><a href="{{ route('airlines.index') }}">Airlines</a></li>
                    <li class="breadcrumb-item active">@lang('airline.add')</li>
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
                <form method="post" action="{{route('airlines.store')}}" id="airlineForm" class="form row mb-0 validate" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="id" name="id" autocomplete="off">
                    <div class="brdr-btm row">
                        <div class="col-md-6">
                            <div class="form-item form-float-style">
                                <input type="text" id="airline_name_en" name="airline_names[0][airline_name]" autocomplete="off" class="is-valid" required value="">
                                <input type="hidden" id="language_code_en" name="airline_names[0][language_code]" autocomplete="off" class="is-valid" value="en">
                                <label for="name">@lang('airline.airlineNameEn') <span class="req-star">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-item form-float-style">
                                <input type="text" id="airline_name_ar" name="airline_names[1][airline_name]" autocomplete="off" class="is-valid" required value="">
                                <input type="hidden" id="language_code_ar" name="airline_names[1][language_code]" autocomplete="off" class="is-valid" value="ar">
                                <label for="name">@lang('airline.airlineNameAr') <span class="req-star">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-item form-float-style">
                                <input type="text" class="is-valid" id="airline_code" name="airline_code" autocomplete="off" required value="" maxlength="3">
                                <label for="code">@lang('airline.airline_code') <span class="req-star">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-item form-float-style">
                                <input type="file" id="airline_logo" name="airline_logo" class="file-upload select-validate" autocomplete="off" class="is-valid">
                                <label for="agencyLogo">Airline Logo <span class="req-star">*</span></label>
                                <p style="color: black;font-size: 13px;font-family: system-ui;font-style:italic">Please ensure that you are uploading an image is 1MB or less and dimension width:180px & height:105px one of the following types: JPG,JPEG, or PNG</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating form-item mb-0">
                                <div class="form-item form-float-style serach-rem mb-0">
                                    <div class="select top-space-rem after-drp form-float-style">
                                        <select data-live-search="true" id="status" name="status" class="order-td-input selectpicker select-text height_drp is-valid">
                                            <option value="1" selected="">@lang('airline.active')</option>
                                            <option value="2">@lang('airline.inActive')</option>
                                        </select>
                                        <label class="select-label searchable-drp">@lang('airline.status')</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="cards-btn mt-3">
                        <button type="submit" id="disBtn" class="btn btn-success form-btn-success">@lang('airline.submit')</button>
                        <a href="{{ route('airlines.index') }}" type="button" class="btn btn-danger form-btn-danger">@lang('airline.cancel')</a>
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
    // Listen for file input change event

    $('.select-validate').on('change', function() {
        if ($(this).valid()) {
            // If the file is valid, remove the 'is-invalid' class
            $(this).removeClass('is-invalid');
            // Remove the 'invalid-feedback' element
            $(this).next('.invalid-feedback').remove();
        }
    });
    $(function() {
        //jquery Form validation
        $.validator.addMethod('latitude', function(value, element) {
            return this.optional(element) ||
                value.length >= 4 && /^(?=.)-?((8[0-5]?)|([0-7]?[0-9]))?(?:\.[0-9]{1,20})?$/.test(value);
        }, 'Your Latitude format has error.')

        // Longitude validation
        $.validator.addMethod('longitude', function(value, element) {
            return this.optional(element) ||
                value.length >= 4 && /^(?=.)-?((0?[8-9][0-9])|180|([0-1]?[0-7]?[0-9]))?(?:\.[0-9]{1,20})?$/.test(value);
        }, 'Your Longitude format has error.')
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
                            "_token": '{{ csrf_token() }}'
                        }
                    }
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
                            "_token": '{{ csrf_token() }}'
                        }
                    }
                },
                airline_logo: {
                    required: true,
                    extension: "jpeg|png|jpg",
                    maxsize: 1000000,
                },

            },
            messages: {
                "airline_names[0][airline_name]": {
                    required: "Please enter an Airline Name English",
                    remote: "Airline Name English is already taken"
                },
                "airline_names[1][airline_name]": {
                    required: "Please enter an Airline Name Arabic",
                    remote: "Airline Name Arabic is already taken"
                },
                airline_code: {
                    required: "Please enter an Airline Code",
                    remote: "ISO Code is already taken"
                },
                airline_logo: {
                    required: "Please select an Airline Logo",
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
    $(document).ready(function() {
        $('#country_code').select2({
            ajax: {
                url: '/fetchCountryCode',
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
                    return {
                        results: $.map(data, function(country) {
                            return {
                                id: country.airline_code,
                                text: country.cname,
                                pagination: {
                                    more: country.length >= 10 // Adjust based on your pagination logic
                                }
                            }
                        })
                    };
                },
                cache: true
            },
            placeholder: 'Select a country',
        });
    })
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>

@append