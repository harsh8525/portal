@extends('admin.layout.main')
@section('title',$header['title'])

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-4 mt-2">
            <div class="col-sm-12 d-flex breadcrumb-style">
                <h1 class="m-0">@lang('state.addState')</h1>
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('state.dashboard') </a></li>
                    <li class="breadcrumb-item"><a href="{{ route('states.index') }}">@lang('state.moduleHeading')</a></li>
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
                <form method="post" action="{{route('states.store')}}" id="stateForm" class="form row mb-0 validate" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="id" name="id" autocomplete="off">
                    <div class="brdr-btm row">
                        <div class="col-md-6">
                            <div class="form-item form-float-style">
                                <input type="text" id="state_name_en" name="state_names[0][state_name]" autocomplete="off" class="is-valid" required value="">
                                <input type="hidden" id="language_code_en" name="state_names[0][language_code]"" autocomplete=" off" class="is-valid" value="en">
                                <label for="name">@lang('state.stateNameEn') <span class="req-star">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-item form-float-style">
                                <input type="text" id="state_name_ar" name="state_names[1][state_name]" autocomplete="off" class="is-valid" required value="">
                                <input type="hidden" id="language_code_ar" name="state_names[1][language_code]" autocomplete="off" class="is-valid" value="ar">
                                <label for="name">@lang('state.stateNameAr') <span class="req-star">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-item form-float-style">
                                <input type="text" class="is-valid" id="iso_code" name="iso_code" autocomplete="off" required value="">
                                <label for="code">@lang('state.iso_code') <span class="req-star">*</span></label>
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
                            <div class="form-item form-float-style">
                                <input type="text" id="latitude" name="latitude" class="is-valid" autocomplete="off">
                                <label for="email-add-user">@lang('state.latitude') <span class="req-star">*</span></label>

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-item form-float-style">
                                <input type="text" id="longitude" name="longitude" class="is-valid" autocomplete="off">
                                <label for="email-add-user">@lang('state.longitude') <span class="req-star">*</span></label>

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating form-item mb-0">
                                <div class="form-item form-float-style serach-rem mb-0">
                                    <div class="select top-space-rem after-drp form-float-style">
                                        <select data-live-search="true" id="status" name="status" class="order-td-input selectpicker select-text height_drp is-valid">
                                            <option value="1" selected="">@lang('state.active')</option>
                                            <option value="2">@lang('state.inActive')</option>
                                        </select>
                                        <label class="select-label searchable-drp">@lang('state.status')</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="cards-btn mt-3">
                        <button type="submit" id="disBtn" class="btn btn-success form-btn-success">@lang('country.submit')</button>
                        <a href="{{ route('states.index') }}" type="button" class="btn btn-danger form-btn-danger">@lang('country.cancel')</a>
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
            $(this).removeClass('is-invalid');
            $(this).next('.invalid-feedback').remove();
        }
    });
    //addmethod validation for letterswithoutspaces
    $.validator.addMethod("letterswithoutspaces", function(value, element) {
        return this.optional(element) || /^[A-Za-z]+$/.test(value);
    }, "Please enter letters without spaces.");

    $.validator.addMethod('latitude', function(value, element) {
        return this.optional(element) ||
            value.length >= 4 && /^(?=.)-?((8[0-5]?)|([0-7]?[0-9]))?(?:\.[0-9]{1,20})?$/.test(value);
    }, 'Your Latitude format has error.');
    $.validator.addMethod('longitude', function(value, element) {
        return this.optional(element) ||
            value.length >= 4 && /^(?=.)-?((0?[8-9][0-9])|180|([0-1]?[0-7]?[0-9]))?(?:\.[0-9]{1,20})?$/.test(value);
    }, 'Your Longitude format has error.');

    $(function() {

        //jquery Form validation
        $('#stateForm').validate({
            onkeyup: false, //turn off auto validate while typing
            rules: {
                "state_names[0][state_name]": {
                    required: true,
                    noSpace: true,

                },
                "state_names[1][state_name]": {
                    required: true,
                    noSpace: true,

                },
                iso_code: {
                    required: true,
                    noSpace: true,
                },
                country_code: {
                    required: true,
                    noSpace: true,
                },
                latitude: {
                    required: true,
                    noSpace: true,
                    latitude: true,
                },
                longitude: {
                    required: true,
                    noSpace: true,
                    longitude: true,
                },
            },
            messages: {
                "state_names[0][state_name]": {
                    required: "Please enter a State Name English",

                },
                "state_names[1][state_name]": {
                    required: "Please enter a State Name Arabic",

                },
                iso_code: {
                    required: "Please enter an ISO Code"
                },
                country_code: {
                    required: "Please select a Country"
                },
                latitude: {
                    required: "Please enter a Latitude"
                },
                longitude: {
                    required: "Please enter a Longitude"
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