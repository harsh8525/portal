@extends('admin.layout.main')
@section('title',$header['title'])

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-4 mt-2">
            <div class="col-sm-12 d-flex breadcrumb-style">
                <h1 class="m-0">@lang('city.editCity')</h1>
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('city.dashboard') </a></li>
                    <li class="breadcrumb-item"><a href="{{ route('cities.index') }}">@lang('city.moduleHeading')</a></li>
                    <li class="breadcrumb-item active">@lang('city.edit')</li>
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
                <form method="post" action="{{route('cities.update',$cityDetail['id'])}}" id="cityForm" class="form row mb-0 validate" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="redirects_to" id="redirects_to" value="{{ URL::previous() }}">
                    <input type="hidden" name="city_id" id="city_id" value="{{$cityDetail['id']}}" />
                    <input type="hidden" name="c_code" id="c_code" value="{{$cityDetail['country_code']}}" />

                    <div class="brdr-btm row">
                        <div class="col-md-6">
                            <div class="form-item form-float-style">
                                <input type="text" id="city_name_en" name="city_names[0][city_name]" autocomplete="off" class="is-valid" required value="{{$cityDetail['cityCode'][0]['city_name']}}">
                                <input type="hidden" id="language_code_en" name="city_names[0][language_code]" autocomplete="off" class="is-valid" value="en">
                                <input type="hidden" id="city_i18ns_en_id" name="city_names[0][city_i18ns_id]" autocomplete="off" class="is-valid" value="{{$cityDetail['cityCode'][0]['id']}}">
                                <label for="name">@lang('city.cityNameEn') <span class="req-star">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-item form-float-style">
                                <input type="text" id="city_name_ar" name="city_names[1][city_name]" autocomplete="off" class="is-valid" required value="{{$cityDetail['cityCode'][1]['city_name']}}">
                                <input type="hidden" id="language_code_ar" name="city_names[1][language_code]" autocomplete="off" class="is-valid" value="ar">
                                <input type="hidden" id="city_i18ns_ar_id" name="city_names[1][city_i18ns_id]" autocomplete="off" class="is-valid" value="{{$cityDetail['cityCode'][1]['id']}}">
                                <label for="name">@lang('city.cityNameAr') <span class="req-star">*</span></label>
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="form-item form-float-style">
                                <input type="text" class="is-valid" id="iso_code" name="iso_code" autocomplete="off" required value="{{$cityDetail['iso_code']}}">
                                <label for="code">@lang('city.iso_code') <span class="req-star">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-item form-float-style">
                                <div class="select top-space-rem after-drp form-float-style">
                                    @component('components.country_city_select', [
                                    'name' => 'country_code',
                                    'id' => 'country_code',
                                    'class' => 'order-td-input selectpicker1 select-text height_drp is-valid select2',
                                    'selected' => $cityDetail['country_code'],
                                    'placeholder' => 'Select Country'
                                    ])
                                    @endcomponent
                                    <label for="code">@lang('city.country_name') <span class="req-star">*</span></label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-item form-float-style">
                                <input type="text" id="latitude" name="latitude" class="is-valid" autocomplete="off" value="{{$cityDetail['latitude']}}">
                                <label for="email-add-user">@lang('city.latitude') <span class="req-star">*</span></label>

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-item form-float-style">
                                <input type="text" id="longitude" name="longitude" class="is-valid" autocomplete="off" value="{{$cityDetail['longitude']}}">
                                <label for="email-add-user">@lang('city.longitude') <span class="req-star">*</span></label>

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating form-item mb-0">
                                <div class="form-item form-float-style serach-rem mb-0">
                                    <div class="select top-space-rem after-drp form-float-style ">
                                        <select data-live-search="true" id="status" name="status" class="order-td-input selectpicker select-text height_drp is-valid">
                                            <option @if($cityDetail['status']=='active' ) selected="selected" @endif value="active" selected="">@lang('city.active')</option>
                                            <option @if($cityDetail['status']=='inactive' ) selected="selected" @endif value="inactive">@lang('city.inActive')</option>
                                        </select>
                                        <label class="select-label searchable-drp">Status</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>


                    <div class="cards-btn mt-3">
                        <button type="submit" id="disBtn" class="btn btn-success form-btn-success">@lang('city.submit')</button>
                        <a href="{{ route('cities.index') }}" type="button" class="btn btn-danger form-btn-danger">Cancel</a>
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

        //addmethod validation for letterswithoutspaces
        $.validator.addMethod("letterswithoutspaces", function(value, element) {
            return this.optional(element) || /^[A-Za-z]+$/.test(value);
        }, "Please enter letters without spaces.");


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
        $('#cityForm').validate({
            onkeyup: false, //turn off auto validate while typing
            rules: {
                "city_names[0][city_name]": {
                    required: true,
                    noSpace: true,
                    remote: {
                        url: "{{route('cities.checkCityNameEnExist') }}",
                        type: "POST",
                        data: {
                            "city_names[0][city_name]": function() {
                                return $("#city_name_en").val();
                            },
                            "city_names[0][city_i18ns_id]": function() {
                                return $("#city_i18ns_en_id").val();
                            },
                            "_token": '{{ csrf_token() }}'
                        }
                    }
                },
                "city_names[1][city_name]": {
                    required: true,
                    noSpace: true,
                    remote: {
                        url: "{{route('cities.checkCityNameArExist') }}",
                        type: "POST",
                        data: {
                            "city_names[1][city_name]": function() {
                                return $("#city_name_ar").val();
                            },
                            "city_names[1][city_i18ns_id]": function() {
                                return $("#city_i18ns_ar_id").val();
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
                        url: "{{route('cities.checkISOCodeExist') }}",
                        type: "POST",
                        data: {
                            iso_code: function() {
                                return $("#iso_code").val();
                            },
                            city_id: function() {
                                return $("#city_id").val();
                            },
                            "_token": '{{ csrf_token() }}'
                        }
                    }
                },
                isd_code: {
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
                            city_id: function() {
                                return $("#city_id").val();
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
                            city_id: function() {
                                return $("#city_id").val();
                            },
                            "_token": '{{ csrf_token() }}'
                        }
                    }
                },
            },
            messages: {
                "city_names[0][city_name]": {
                    required: "Please enter a City Name English",
                    remote: "City Name English is already taken"
                },
                "city_names[1][city_name]": {
                    required: "Please enter a City Name Arabic",
                    remote: "City Name Arabic is already taken"
                },
                iso_code: {
                    required: "Please enter an ISO Code",
                    remote: "ISO Code is already taken"
                },
                isd_code: {
                    required: "Please enter an ISD Code"
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