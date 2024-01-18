@extends('admin.layout.main')
@section('title',$header['title'])

@section('content')
<?php app()->setLocale("en"); ?>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-4 mt-2">
            <div class="col-sm-12 d-flex breadcrumb-style">
                <h1 class="m-0">@lang('city.addCity')</h1>
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('airport.dashboard') </a></li>
                    <li class="breadcrumb-item"><a href="{{ route('cities.index') }}">Cities</a></li>
                    <li class="breadcrumb-item active">@lang('city.add')</li>
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
                <form method="post" action="{{route('cities.store')}}" id="cityForm" class="form row mb-0 validate" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="id" name="id" autocomplete="off">
                    <div class="brdr-btm row">
                        <div class="col-md-6">
                            <div class="form-item form-float-style">
                                <input type="text" id="city_name_en" name="city_names[0][city_name]" autocomplete="off" class="is-valid" required value="">
                                <input type="hidden" id="language_code_en" name="city_names[0][language_code]"" autocomplete=" off" class="is-valid" value="en">
                                <label for="name">@lang('city.cityNameEn') <span class="req-star">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-item form-float-style">
                                <input type="text" id="city_name_ar" name="city_names[1][city_name]" autocomplete="off" class="is-valid" required value="">
                                <input type="hidden" id="language_code_ar" name="city_names[1][language_code]" autocomplete="off" class="is-valid" value="ar">
                                <label for="name">@lang('city.cityNameAr') <span class="req-star">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-item form-float-style">
                                <input type="text" class="is-valid" id="iso_code" name="iso_code" autocomplete="off" required value="">
                                <label for="code">@lang('city.iso_code') <span class="req-star">*</span></label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-item form-float-style">
                                <div class="select top-space-rem after-drp form-float-style">
                                    @component('components.country_city_select', [
                                    'name' => 'country_code',
                                    'id' => 'country_code',
                                    'class' => 'order-td-input selectpicker1 select-text height_drp is-valid',
                                    'selected' => '',
                                    'placeholder' => 'Select Country'
                                    ])
                                    @endcomponent
                                    <label for="code">@lang('city.country_code') <span class="req-star">*</span></label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-item form-float-style">
                                <input type="text" id="latitude" name="latitude" class="is-valid" autocomplete="off">
                                <label for="email-add-user">@lang('city.latitude') <span class="req-star">*</span></label>

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-item form-float-style">
                                <input type="text" id="longitude" name="longitude" class="is-valid" autocomplete="off">
                                <label for="email-add-user">@lang('city.longitude') <span class="req-star">*</span></label>

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating form-item mb-0">
                                <div class="form-item form-float-style serach-rem mb-0">
                                    <div class="select top-space-rem after-drp form-float-style">
                                        <select data-live-search="true" id="status" name="status" class="order-td-input selectpicker select-text height_drp is-valid">
                                            <option value="1" selected="">@lang('city.active')</option>
                                            <option value="2">@lang('city.inActive')</option>
                                        </select>
                                        <label class="select-label searchable-drp">@lang('city.status')</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="cards-btn mt-3">
                        <button type="submit" id="disBtn" class="btn btn-success form-btn-success">@lang('city.submit')</button>
                        <a href="{{ route('cities.index') }}" type="button" class="btn btn-danger form-btn-danger">@lang('city.cancel')</a>
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
    // Listen for file input change event

    $('.select-validate').on('change', function() {
        if ($(this).valid()) {
            $(this).removeClass('is-invalid');
            $(this).next('.invalid-feedback').remove();
        }
    });
    $(function() {
        //addmethod validation for letterswithoutspaces
        $.validator.addMethod("letterswithoutspaces", function(value, element) {
            return this.optional(element) || /^[A-Za-z]+$/.test(value);
        }, "Please enter letters without spaces.");

        //jquery Form validation
        $.validator.addMethod('latitude', function(value, element) {
            return this.optional(element) ||
                value.length >= 4 && /^(?=.)-?((8[0-5]?)|([0-7]?[0-9]))?(?:\.[0-9]{1,20})?$/.test(value);
        }, 'Your Latitude format has error.')

        // Longitude
        $.validator.addMethod('longitude', function(value, element) {
            return this.optional(element) ||
                value.length >= 4 && /^(?=.)-?((0?[8-9][0-9])|180|([0-1]?[0-7]?[0-9]))?(?:\.[0-9]{1,20})?$/.test(value);
        }, 'Your Longitude format has error.')


        $('#cityForm').validate({
            onkeyup: false, //turn off auto validate whilst typing
            rules: {
                "city_names[0][city_name]": {
                    required: true,
                    noSpace: true
                },
                "city_names[1][city_name]": {
                    required: true,
                    noSpace: true
                },
                iso_code: {
                    required: true,
                    noSpace: true,
                    letterswithoutspaces: true,
                    remote: {
                        url: "{{route('cities.checkISOCodeExist') }}",
                        type: "POST",
                        data: {
                            iso_code: function() {
                                return $("#iso_code").val();
                            },
                            "_token": '{{ csrf_token() }}'
                        }
                    }
                },
                country_code: {
                    noSpace: true,
                    required: true,
                },
                latitude: {
                    required: true,
                    noSpace: true,
                    latitude: true,
                    remote: {
                        url: "{{route('cities.checkCityLatitudeExist') }}",
                        type: "POST",
                        data: {
                            latitude: function() {
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
                        url: "{{route('cities.checkCityLongitudeExist') }}",
                        type: "POST",
                        data: {
                            longitude: function() {
                                return $("#longitude").val();
                            },
                            "_token": '{{ csrf_token() }}'
                        }
                    }
                },
            },
            messages: {
                "city_names[0][city_name]": {
                    required: "Please enter a City Name English"
                },
                "city_names[1][city_name]": {
                    required: "Please enter a City Name Arabic"
                },
                iso_code: {
                    required: "Please enter an ISO Code",
                    remote: "ISO Code is already taken"
                },
                country_code: {
                    required: "Please select country Code"
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