@extends('admin.layout.main')
@section('title',$header['title'])

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-4 mt-2">
            <div class="col-sm-12 d-flex breadcrumb-style">
                <h1 class="m-0">@lang('airport.addAirport')</h1>
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('airport.dashboard') </a></li>
                    <li class="breadcrumb-item"><a href="{{ route('airports.index') }}">@lang('airport.moduleHeading')</a></li>
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
                <form method="post" action="{{route('airports.store')}}" id="dataForm" class="form row mb-0 validate" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="id" name="id" autocomplete="off">
                    <div class="brdr-btm row">
                        <div class="col-md-6">
                            <div class="form-item form-float-style">
                                <input type="text" id="airport_name_en" name="airport_names[0][airport_name]" autocomplete="off" class="is-valid" required value="">
                                <input type="hidden" id="language_code_en" name="airport_names[0][language_code]" autocomplete="off" class="is-valid" value="en">
                                <label for="name">@lang('airport.airportNameEn') <span class="req-star">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-item form-float-style">
                                <input type="text" id="airport_name_ar" name="airport_names[1][airport_name]" autocomplete="off" class="is-valid" required value="">
                                <input type="hidden" id="language_code_ar" name="airport_names[1][language_code]" autocomplete="off" class="is-valid" value="ar">
                                <label for="name">@lang('airport.airportNameAr') <span class="req-star">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-item form-float-style">
                                <input type="text" class="is-valid" minlength="3" maxlength="3" id="iata_code" name="iata_code" autocomplete="off" required value="">
                                <label for="code">@lang('airport.code') <span class="req-star">*</span></label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-item form-float-style serach-rem mb-3">
                                <div class="select top-space-rem after-drp form-float-style  ">
                                    @component('components.country_city_select', [
                                    'name' => 'country_code',
                                    'id' => 'country_code',
                                    'class' => 'order-td-input selectpicker1 select-text height_drp is-valid select2 select-validate',
                                    'selected' => '',
                                    'placeholder' => 'Select Country'
                                    ])
                                    @endcomponent
                                    <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">Country Name <span class="req-star">*</span></label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-item form-float-style serach-rem mb-3">
                                <div class="select top-space-rem after-drp form-float-style  ">
                                    <select data-live-search="true" id="city_code" name="city_code" class="order-td-input selectpicker1 select-text height_drp is-valid select2 select-validate" style="width: 100%;">
                                    </select>
                                    <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">City Name <span class="req-star">*</span></label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-item form-float-style">
                                <input type="text" id="latitude" name="latitude" class="is-valid" autocomplete="off">
                                <label for="email-add-user">@lang('airport.latitude') <span class="req-star">*</span></label>

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-item form-float-style">
                                <input type="text" id="longitude" name="longitude" class="is-valid" autocomplete="off">
                                <label for="email-add-user">@lang('airport.longitude') <span class="req-star">*</span></label>

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating form-item mb-0">
                                <div class="form-item form-float-style serach-rem mb-0">
                                    <div class="select top-space-rem after-drp form-float-style">
                                        <select data-live-search="true" id="status" name="status" class="order-td-input selectpicker select-text height_drp is-valid">
                                            <option value="active" selected="">@lang('airport.active')</option>
                                            <option value="inactive">@lang('airport.inActive')</option>
                                        </select>
                                        <label class="select-label searchable-drp">@lang('airport.status')</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="cards-btn mt-3">
                        <button type="submit" id="disBtn" class="btn btn-success form-btn-success">@lang('airport.submit')</button>
                        <a href="{{ route('airports.index') }}" type="button" class="btn btn-danger form-btn-danger">@lang('airport.cancel')</a>
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
    //make GST value uppercase
    $('#usergstno').keyup(function() {
        $(this).val($(this).val().toUpperCase());
    });
    $('#userpackeg-gst').keyup(function() {
        $(this).val($(this).val().toUpperCase());
    });

    //address readonly event script
    $('#searchAddressChecked').change(function() {
        if (this.checked) {
            $("#setting-search-add").attr('readonly', true);
            $("#address").attr('readonly', false);
            $("#country").attr('readonly', false);
            $("#state").attr('readonly', false);
            $("#city").attr('readonly', false);
            $("#pincode").attr('readonly', false);
        } else {
            $("#setting-search-add").attr('readonly', false);
            $("#address").attr('readonly', true);
            $("#country").attr('readonly', true);
            $("#state").attr('readonly', true);
            $("#city").attr('readonly', true);
            $("#pincode").attr('readonly', true);
        }
    });
    jQuery.validator.addMethod("lettersonly", function(value, element) {
        return this.optional(element) || /^[a-z ]+$/i.test(value);
    }, "Letters only please");
    $.validator.addMethod("email_regex", function(value, element, regexpr) {
        return this.optional(element) || regexpr.test(value);
    }, "Please enter a valid Email Address.");

    $("#slect_finish").change(function() {
        var choice = jQuery(this).val();
        //if you just want to remove them all
        $("#slect_finish-error").remove();

        if ($(this).val() == '') {
            $('#slect_finish-error').show();
        } else {
            $('#slect_finish-error').hide();
        }

    });

    // Listen for file input change event
    $('.select-validate').on('change', function() {
        if ($(this).valid()) {
            // If the file is valid, remove the 'is-invalid' class
            $(this).removeClass('is-invalid');
            // Remove the 'invalid-feedback' element
            $(this).next('.invalid-feedback').remove();
        }
    });
    $.validator.addMethod('latitude', function(value, element) {
        return this.optional(element) ||
            value.length >= 4 && /^(?=.)-?((8[0-5]?)|([0-7]?[0-9]))?(?:\.[0-9]{1,20})?$/.test(value);
    }, 'Your Latitude format has error.')

    // Longitude
    $.validator.addMethod('longitude', function(value, element) {
        return this.optional(element) ||
            value.length >= 4 && /^(?=.)-?((0?[8-9][0-9])|180|([0-1]?[0-7]?[0-9]))?(?:\.[0-9]{1,20})?$/.test(value);
    }, 'Your Longitude format has error.')

    $(function() {
        //jquery Form validation
        $('#dataForm').validate({
            onkeyup: false, //turn off auto validate whilst typing
            rules: {
                "airport_names[0][airport_name]": {
                    required: true,
                    noSpace: true,
                    remote: {
                        url: "{{route('airports.checkAirportNameEnExist') }}",
                        type: "POST",
                        data: {
                            "airport_names[0][airport_name]": function() {
                                return $("#airport_name_en").val();
                            },
                            "_token": '{{ csrf_token() }}'
                        }
                    }
                },
                "airport_names[1][airport_name]": {
                    required: true,
                    noSpace: true,
                    remote: {
                        url: "{{route('airports.checkAirportNameArExist') }}",
                        type: "POST",
                        data: {
                            "airport_names[1][airport_name]": function() {
                                return $("#airport_name_ar").val();
                            },
                            "_token": '{{ csrf_token() }}'
                        }
                    }
                },
                iata_code: {
                    required: true,
                    lettersonly: true,
                    noSpace: true,
                    maxlength: 3,
                    minlength: 3,
                    remote: {
                        url: "{{route('airports.checkAirportCodeExist') }}",
                        type: "POST",
                        data: {
                            iata_code: function() {
                                return $("#iata_code").val();
                            },
                            "_token": '{{ csrf_token() }}'
                        }
                    }
                },
                country_code: {
                    required: true,
                    noSpace: true,
                },
                city_code: {
                    required: true,
                    noSpace: true,
                },
                latitude: {
                    required: true,
                    noSpace: true,
                    latitude: true,
                    remote: {
                        url: "{{route('airports.checkAirportLatitudeExist') }}",
                        type: "POST",
                        data: {
                            iata_code: function() {
                                return $("#latitude").val();
                            },
                            "_token": '{{ csrf_token() }}'
                        }
                    }
                },
                longitude: {
                    required: true,
                    noSpace: true,
                    longitude: true,
                    remote: {
                        url: "{{route('airports.checkAirportLongitudeExist') }}",
                        type: "POST",
                        data: {
                            iata_code: function() {
                                return $("#longitude").val();
                            },
                            "_token": '{{ csrf_token() }}'
                        }
                    }
                },
            },
            messages: {
                "airport_names[0][airport_name]": {
                    required: "Please enter an Airport Name English",
                    remote: "Airport Name English is already taken"
                },
                "airport_names[1][airport_name]": {
                    required: "Please enter an Airport Name Arabic",
                    remote: "Airport Name Arabic is already taken"
                },
                iata_code: {
                    required: "Please enter an ISO Code",
                    remote: "ISO Code is already taken"

                },
                country_code: {
                    required: "Please select a Country"
                },
                city_code: {
                    required: "Please select a City"
                },
                latitude: {
                    required: "Please enter a Latitude",
                    remote: "Latitude is already taken"
                },
                longitude: {
                    required: "Please enter a Longitude",
                    remote: "Longitude is already taken"
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


<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>

@append