@extends('admin.layout.main')
@section('title',$header['title'])

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-4 mt-2">
            <div class="col-sm-12 d-flex breadcrumb-style">
                <h1 class="m-0">@lang('country.addCountry')</h1>
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('country.dashboard') </a></li>
                    <li class="breadcrumb-item"><a href="{{ route('countries.index') }}">@lang('country.moduleHeading')</a></li>
                    <li class="breadcrumb-item active">@lang('airport.add')</li>
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
                <form method="post" action="{{route('countries.store')}}" id="countryForm" class="form row mb-0 validate" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="id" name="id" autocomplete="off">
                    <div class="brdr-btm row">
                        <div class="col-md-6">
                            <div class="form-item form-float-style">
                                <input type="text" id="country_name_en" name="country_names[0][country_name]" autocomplete="off" class="is-valid" required value="">
                                <input type="hidden" id="language_code_en" name="country_names[0][language_code]"" autocomplete=" off" class="is-valid" value="en">
                                <label for="name">@lang('country.countryNameEn') <span class="req-star">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-item form-float-style">
                                <input type="text" id="country_name_ar" name="country_names[1][country_name]" autocomplete="off" class="is-valid" required value="">
                                <input type="hidden" id="language_code_ar" name="country_names[1][language_code]" autocomplete="off" class="is-valid" value="ar">
                                <label for="name">@lang('country.countryNameAr') <span class="req-star">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-item form-float-style">
                                <input type="text" class="is-valid" id="iso_code" name="iso_code" autocomplete="off" required value="">
                                <label for="code">@lang('country.iso_code') <span class="req-star">*</span></label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-item form-float-style">
                                <input type="text" class="is-valid" id="isd_code" name="isd_code" class="workingCityAutocomplete1" autocomplete="off" value="">
                                <label for="city">@lang('country.isd_code') <span class="req-star">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-item form-float-style">
                                <input type="text" id="max_mobile_number_length" name="max_mobile_number_length" class="is-valid" autocomplete="off">
                                <label for="email-add-user">@lang('country.max_mobile_number_length') <span class="req-star">*</span></label>

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating form-item mb-0">
                                <div class="form-item form-float-style serach-rem mb-0">
                                    <div class="select top-space-rem after-drp form-float-style">
                                        <select data-live-search="true" id="status" name="status" class="order-td-input selectpicker select-text height_drp is-valid">
                                            <option value="1" selected="">@lang('country.active')</option>
                                            <option value="2">@lang('country.inActive')</option>
                                        </select>
                                        <label class="select-label searchable-drp">@lang('country.status')</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="cards-btn mt-3">
                        <button type="submit" id="disBtn" class="btn btn-success form-btn-success">@lang('country.submit')</button>
                        <a href="{{ route('countries.index') }}" type="button" class="btn btn-danger form-btn-danger">@lang('country.cancel')</a>
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
    $(function() {
        //addmethod validation for lettersonly
        $.validator.addMethod("letterswithoutspaces", function(value, element) {
            return this.optional(element) || /^[A-Za-z]+$/.test(value);
        }, "Please enter letters without spaces.");

        //addmethod validation for ISD code
        $.validator.addMethod("isdCode", function(value, element) {
            return /^\+\d+$/.test(value);
        }, "ISD code must start with a '+' sign followed by digits.");

        //jquery Form validation
        $('#countryForm').validate({
            onkeyup: false, //turn off auto validate while typing
            rules: {
                "country_names[0][country_name]": {
                    required: true,
                    noSpace: true,
                    remote: {
                        url: "{{route('countries.checkCountryNameEnExist') }}",
                        type: "POST",
                        data: {
                            "country_names[0][country_name]": function() {
                                return $("#country_name_en").val();
                            },
                            "_token": '{{ csrf_token() }}'
                        }
                    }
                },
                "country_names[1][country_name]": {
                    required: true,
                    noSpace: true,
                    remote: {
                        url: "{{route('countries.checkCountryNameArExist') }}",
                        type: "POST",
                        data: {
                            "country_names[1][country_name]": function() {
                                return $("#country_name_ar").val();
                            },
                            "_token": '{{ csrf_token() }}'
                        }
                    }
                },
                iso_code: {
                    required: true,
                    noSpace: true,
                    letterswithoutspaces: true,
                    remote: {
                        url: "{{route('countries.checkISOCodeExist') }}",
                        type: "POST",
                        data: {
                            iso_code: function() {
                                return $("#iso_code").val();
                            },
                            "_token": '{{ csrf_token() }}'
                        }
                    }
                },
                isd_code: {
                    required: true,
                    noSpace: true,
                    isdCode: true,
                    remote: {
                        url: "{{route('countries.checkISDCodeExist') }}",
                        type: "POST",
                        data: {
                            isd_code: function() {
                                return $("#isd_code").val();
                            },
                            "_token": '{{ csrf_token() }}'
                        }
                    }
                },
                max_mobile_number_length: {
                    noSpace: true,
                    required: true,
                    number: true,
                },
            },
            messages: {
                "country_names[0][country_name]": {
                    required: "Please enter a Country Name English",
                    remote: "Country Name English is already taken"
                },
                "country_names[1][country_name]": {
                    required: "Please enter a Country Name Arabic",
                    remote: "Country Name Arabic is already taken"
                },
                iso_code: {
                    required: "Please enter an ISO Code",
                    remote: "ISO Code is already taken"
                },
                isd_code: {
                    required: "Please enter an ISD Code",
                    remote: "ISD Code is already taken"
                },
                max_mobile_number_length: {
                    required: "Please enter a Max Mobile Number Length"
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
                $(element).removeClass(errorClass);
            },
            submitHandler: function(form) {
                $("#disBtn").attr("disabled", true);
                form.submit();
            }
        });
    });
</script>


<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>

@append