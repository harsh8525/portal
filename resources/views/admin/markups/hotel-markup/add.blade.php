@extends('admin.layout.main')
@section('title',$header['title'])

@section('content')
<style>
    textarea.select2-search__field {
        width: 40.50em !important;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        color: #000 !important;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__display {
        margin-left: 5px;
    }
</style>
<!-- Include "cropper.js" CSS and JavaScript -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-4 mt-2">
            <div class="col-sm-12 d-flex breadcrumb-style">
                <h1 class="m-0">Hotel Markup -Add</h1>
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('customers.dashboard') </a></li>
                    <li class="breadcrumb-item"><a href="{{ route('hotel-markups.index') }}">Hotel Markup</a></li>
                    <li class="breadcrumb-item active">@lang('customers.add')</li>
                </ol>
            </div><!-- /.col -->

        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<section class="content">
    <div class="container-fluid">
        <!-- Info boxes -->
        <div class="row">
            <div class="card pb-4 w-100 px-3 py-2 mb-3">
                <form id="dataForm" name="dataForm" class="form row mb-0 pt-3 validate" action="{{ route('hotel-markups.store') }}" enctype="multipart/form-data" method="post">
                    @csrf
                    <input type="hidden" name="service_type_id" value="{{ $serviceTypeId ?? '' }}">

                    <div class="col-md-12">
                        <div class="col-md-6">
                            <div class="form-floating form-item mb-0">
                                <div class="form-item form-float-style form-group">
                                    <input type="text" name="ruleName" id="ruleName" class="is-valid" required>
                                    <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">Rule Name <span class="req-star">*</span></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="col-md-12" style="pointer-events: none;" >
                            <div class="form-floating form-item mb-0">
                                <div class="form-item form-float-style serach-rem mb-0">
                                    <div class="select top-space-rem after-drp form-float-style form-group">
                                        <select data-live-search="true" id="serviceType" name="serviceType" class="order-td-input selectpicker select-text height_drp is-valid" style="width: 100%;">
                                            <option value="flight" >Flight</option>
                                            <option value="hotel" selected>Hotel</option>
                                        </select>
                                        <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">Service Type </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="col-md-12">
                            <div class="form-item form-float-style form-group">
                                <input type="text" name="priority" id="priority" autocomplete="off" required step="any">
                                <label for="priority">Priority <span class="req-star">*</span></label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="col-md-12">
                            <div class="form-floating form-item mb-0">
                                <div class="form-item form-float-style serach-rem mb-0">
                                    <div class="select top-space-rem after-drp form-float-style form-group">
                                        <select data-live-search="true" id="channel" name="channel[]" class="order-td-input selectpicker select-text height_drp is-valid" style="width: 100%;" multiple>
                                            <option value="back_office">BackOffice</option>
                                            <option value="b2c">B2C</option>
                                            <option value="b2b">B2B</option>
                                            <option value="mobile">Mobile</option>
                                        </select>
                                        <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">Channel <span class="req-star">*</span></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="col-md-12">
                            <div class="form-floating form-item mb-0">
                                <div class="form-item form-float-style serach-rem mb-0">
                                    <div class="select top-space-rem after-drp form-float-style form-group">
                                        <select data-live-search="true" id="supplier" name="supplier[]" class="order-td-input selectpicker select-text height_drp is-valid" placeholder="Select Supplier" style="width:100%;" multiple></select>
                                        <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">Suppliers <span class="req-star">*</span></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                   <div class="col-md-6">
                        <div class="col-md-12">
                            <div class="form-floating form-item mb-0">
                                <div class="form-item form-float-style serach-rem mb-0">
                                    <div class="select top-space-rem after-drp form-float-style form-group">
                                        <select data-live-search="true" id="destinationCriteria" name="destinationCriteria" class="order-td-input selectpicker select-text height_drp is-valid" style="width: 100%;">
                                            <option value="all">All</option>
                                            <option value="country">Country</option>
                                            <option value="city">City</option>
                                        </select>
                                        <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">Select Destination Criteria <span class="req-star">*</span></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6" id="destinationNameAll">
                        <div class="col-md-12">
                            <div class="form-floating form-item mb-0">
                                <div class="form-item form-float-style serach-rem mb-0">
                                    <div class="select top-space-rem after-drp form-float-style form-group">
                                        <select data-live-search="true" id="destinationName" name="destinationName" class="order-td-input select-text height_drp is-valid select2" style="width: 100%;" disabled>
                                            <option value="all" selected>All</option>
                                        </select>
                                        <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">Select Destination Name <span class="req-star">*</span></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6" id="destinationNameCountry">
                        <div class="col-md-12">
                            <div class="form-floating form-item mb-0">
                                <div class="form-item form-float-style serach-rem mb-0">
                                    <div class="select top-space-rem after-drp form-float-style form-group">
                                        <select data-live-search="true" id="CountryDList" name="destinationName" class="order-td-input select-text height_drp is-valid select2 destination_country_list" style="width: 100%;">
                                        </select>
                                        <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">Select Destination Name <span class="req-star">*</span></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6" id="destinationNameCity">
                        <div class="col-md-12">
                            <div class="form-floating form-item mb-0">
                                <div class="form-item form-float-style serach-rem mb-0">
                                    <div class="select top-space-rem after-drp form-float-style form-group">
                                        <select data-live-search="true" id="CityDList" name="destinationName" class="order-td-input select-text height_drp is-valid select2 CityOList" style="width: 100%;" placeholder="Select Airport">
                                        </select>
                                        <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">Select Destination Name <span class="req-star">*</span></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="col-md-12">
                            <div class="form-item form-float-style form-group">
                                <input type="text" class="is-valid datepicker" name="fromBookingDate" id="fromBookingDate" autocomplete="off" required placeholder="DD/MM/YYYY" class="is-valid">
                                <label for="">From Booking Date <span class="req-star">*</span></label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="col-md-12">
                            <div class="form-item form-float-style form-group">
                                <input type="text" class="is-valid datepicker" name="toBookingDate" id="toBookingDate" autocomplete="off" required class="is-valid" placeholder="DD/MM/YYYY">
                                <label for="">To Booking Date <span class="req-star">*</span></label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="col-md-12">
                            <div class="form-item form-float-style form-group">
                                <input type="text" class="is-valid datepicker" name="fromCheckInDate" id="fromCheckInDate" autocomplete="off" required class="is-valid" placeholder="DD/MM/YYYY">
                                <label for="">From CheckIn Date <span class="req-star">*</span></label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="col-md-12">
                            <div class="form-item form-float-style form-group">
                                <input type="text" class="is-valid datepicker" name="toCheckInDate" id="toCheckInDate" autocomplete="off" required class="is-valid" placeholder="DD/MM/YYYY">
                                <label for="">To CheckIn Date <span class="req-star">*</span></label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="col-md-6">
                            <div class="form-floating form-item mb-0">
                                <div class="form-item form-float-style serach-rem mb-0">
                                    <div class="select top-space-rem after-drp form-float-style form-group">
                                        <select data-live-search="true" id="starCategory" name="starCategory[]" class="order-td-input selectpicker select-text height_drp is-valid" style="width: 100%;" multiple>
                                            <option value="1Star">1Star</option>
                                            <option value="2Star">2Star</option>
                                            <option value="3Star">3Star</option>
                                            <option value="4Star">4Star</option>
                                            <option value="5Star">5Star</option>
                                            <option value="6Star">6Star</option>
                                            <option value="7Star">7Star</option>
                                        </select>
                                        <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">Star Category <span class="req-star">*</span></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="col-md-12">
                            <div class="form-item form-float-style form-group">
                                <input type="text" name="from_price_range" id="from_price_range" autocomplete="off" required step="any">
                                <label for="from_base_fare">From Price Range <span class="req-star">*</span></label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="col-md-12">
                            <div class="form-item form-float-style form-group">
                                <input type="text" name="to_price_range" id="to_price_range" autocomplete="off" required step="any">
                                <label for="to_base_fare">To Price Range <span class="req-star">*</span></label>
                            </div>
                        </div>
                    </div>
              

                    <div class="col-md-12 row mb-3">
                        <div class="col-md-6">
                            <div class="form-floating form-item mb-0">
                                <div class="form-item form-float-style serach-rem mb-0">
                                    <div class="select top-space-rem after-drp form-float-style form-group">
                                        <select data-live-search="true" id="fareType" name="fareType" class="order-td-input selectpicker select-text height_drp is-valid">
                                            <option disabled selected>Select Fare Type</option>
                                            <option value="commission">Commission</option>
                                            <option value="net_fare">Net Fare</option>
                                        </select>
                                        <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">Select Fare Type <span class="req-star">*</span></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="col-md-12">
                            <div class="form-floating form-item mb-0">
                                <div class="form-item form-float-style serach-rem mb-0">
                                    <div class="select top-space-rem after-drp form-float-style form-group">
                                        <select data-live-search="true" id="b2c_markup_type" name="b2c_markup_type" class="order-td-input selectpicker select-text height_drp is-valid">
                                            <option selected value="percentage">%Percentage</option>
                                            <option value="fixed_amount">Fixed amount</option>
                                        </select>
                                        <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">Select B2C Markup Type <span class="req-star">*</span></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="col-md-12">
                            <div class="form-item form-float-style form-group">
                                <input type="number" name="b2c_markup" id="b2c_markup" autocomplete="off" required step="any">
                                <label for="b2c_markup">B2C Markup Value <span class="req-star">*</span></label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="col-md-12">
                            <div class="form-floating form-item mb-0">
                                <div class="form-item form-float-style serach-rem mb-0">
                                    <div class="select top-space-rem after-drp form-float-style form-group">
                                        <select data-live-search="true" id="b2b_markup_type" name="b2b_markup_type" class="order-td-input selectpicker select-text height_drp is-valid">
                                            <option selected value="percentage">%Percentage</option>
                                            <option value="fixed_amount">Fixed amount</option>
                                        </select>
                                        <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">Select B2B Markup Type<span class="req-star"></span></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 row mb-3">
                        <div class="col-md-12">
                            <div class="form-item form-float-style form-group">
                                <input type="number" name="b2b_markup" id="b2b_markup" autocomplete="off" step="any">
                                <label for="b2b_markup">B2B Markup Value</label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 row mb-3">
                        <div class="col-md-6">
                            <label for="" style="color: #999 !important;">Comm On/Markup On <span class="req-star">*</span></label>
                            <div class="q-a mb-2">
                                <div class="form-check">
                                    <input type="radio" id="comm_markup_base_fare" name="commMarkupOn" data-change="commMarkupOn" class="form-check-input is-valid" value="base_fare" required>
                                    <label class="form-check-label" for="comm_markup_base_fare">Base Fare</label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" id="comm_markup_base_fare_yq" name="commMarkupOn" data-change="commMarkupOn" class="form-check-input is-valid" value="base_fare_yq" required>
                                    <label class="form-check-label" for="comm_markup_base_fare_yq">Base Fare+Tax</label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" id="comm_markup_net_fare" name="commMarkupOn" data-change="commMarkupOn" class="form-check-input is-valid" value="net_fare" required>
                                    <label class="form-check-label" for="comm_markup_net_fare">Net Fare</label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" id="comm_markup_total_fare" name="commMarkupOn" data-change="commMarkupOn" class="form-check-input is-valid" value="total_fare" required>
                                    <label class="form-check-label" for="comm_markup_total_fare">Total Fare</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="col-md-12">
                            <div class="form-floating form-item mb-0">
                                <div class="form-item form-float-style serach-rem mb-0">
                                    <div class="select top-space-rem after-drp form-float-style form-group">
                                        <select class="order-td-input selectpicker select2 select-text height_drp is-valid" id="agency" name="agency[]" multiple="multiple" style="width: 100%;">
                                            <option value="">Select Agent Name</option>
                                            @foreach($getAgency as $agency)
                                                <option value="{{ $agency['id'] }}">{{ $agency['full_name'] ?? '' }} </option>
                                            @endforeach
                                        </select>
                                        <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">Select Agent Name</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="col-md-12">
                            <div class="form-floating form-item mb-0">
                                <div class="form-item form-float-style serach-rem mb-0">
                                    <div class="select top-space-rem after-drp form-float-style form-group">
                                        <select class="order-td-input selectpicker select2 select-text height_drp is-valid" id="agencyGroup" name="agencyGroup" style="width: 100%;">
                                            <option value="">Select Agent Group</option>
                                        </select>
                                        <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">Select Agent Group</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                   <div class="cards-btn">
                        <button type="submit" id="disBtn" class="btn btn-success form-btn-success">Submit</button>
                        <a href="{{ route('hotel-markups.index') }}" class="btn btn-danger form-btn-danger">Cancel</a>
                    </div>

                </form>



            </div>
        </div>
        <!-- /.row -->
    </div>
    <!--/. container-fluid -->
</section>
@endsection
@section('js')
<script>
    $("#channel").select2();
    $("#starCategory").select2();
    $("#agency").select2();
    $("#agencyGroup").select2();

    

    $(document).ready(function(){
        $("#destinationNameAll").show();
        $("#destinationNameCountry").hide();
        $("#destinationNameCity").hide();
        $('#destinationCriteria').on('change', function() {
            var destination_criteria = $(this).val();
            if (destination_criteria == 'all')
            {
                $("#destinationNameAll").show();
                $("#destinationNameCountry").hide();
                $("#destinationNameCity").hide();
            }else if(destination_criteria == 'country'){
                $("#destinationNameAll").hide();
                $("#destinationNameCountry").show();
                $("#destinationNameCity").hide();
            }else if(destination_criteria == 'city'){
                $("#destinationNameAll").hide();
                $("#destinationNameCountry").hide();
                $("#destinationNameCity").show();
            }
        });
    });

    $(document).ready(function domReady() {
        $(".js-select2").select2({
            placeholder: "Select Airlines",
            theme: "material"
        });

        $(".select2-selection__arrow")
            .addClass("material-icons")
            .html("arrow_drop_down");
    });

    var dateToday = new Date();
    $(function() {
        $("#fromBookingDate").datepicker({
            dateFormat: 'dd-mm-yy',
            minDate: dateToday,
            onClose: function(selected) {
                $("#toBookingDate").datepicker("option", "minDate", selected);
                $(this).valid();
            }
        });
        $("#toBookingDate").datepicker({
            dateFormat: 'dd-mm-yy',
            minDate: dateToday,
            onClose: function(selected) {
                $("#fromBookingDate").datepicker("option", "maxDate", selected);
                $(this).valid();
            }
        });
    });

    $(function() {
        $("#fromCheckInDate").datepicker({
            dateFormat: 'dd-mm-yy',
            minDate: dateToday,
            onClose: function(selected) {
                $("#toCheckInDate").datepicker("option", "minDate", selected);
                $(this).valid();
            }
        });
        $("#toCheckInDate").datepicker({
            dateFormat: 'dd-mm-yy',
            minDate: dateToday,
            onClose: function(selected) {
                $("#fromCheckInDate").datepicker("option", "maxDate", selected);
                $(this).valid();
            }
        });
    });
</script>
<script>
    $(function() {

        $.validator.addMethod('numbersOnly', function(value, element) {
            return this.optional(element) || /^[0-9]+(\.[0-9]+)?$/.test(value);
        }, 'Please enter a valid number');

        $('*[value=""]').removeClass('is-valid');

        $('#dataForm').validate({
            rules: {
                "ruleName": {
                    required: true,
                },
                "channel[]": {
                    required: true,
                },
                "supplier[]": {
                    required: true,
                },
                "destinationName": {
                    required: true,
                },
                "fromBookingDate": {
                    required: true,
                },
                "toBookingDate": {
                    required: true,
                },
                "fromCheckInDate": {
                    required: true,
                },
                "toCheckInDate": {
                    required: true,
                },
                "starCategory[]": {
                    required: true,
                },
                "agentName[]": {
                    required: true,
                },
                "agentGroup": {
                    required: true,
                },
                "from_price_range": {
                    required: true,
                    noSpace: true,
                    numbersOnly: true,
                },
                "to_price_range": {
                    required: true,
                    noSpace: true,
                    numbersOnly: true,
                },
                "b2c_markup_type": {
                    required: true,
                },
                "b2c_markup": {
                    required: true,
                },
                "fareType": {
                    required: true,
                },
                "priority": {
                    required: true,
                    noSpace: true,
                    numbersOnly: true,
                },
                'commMarkupOn':{
                    required: true
                }
            },
            messages: {
                "ruleName": {
                    required: "Please enter a Rule name",
                },
                "channel[]": {
                    required: "Please select a Channel",
                },
                "supplier[]": {
                    required: "Please select a Suppliers",
                },
                "destination": {
                    required: "Please select a Destination",
                },
                "starCategory[]": {
                    required: "Please select a Star Category",
                },
                "agentName[]": {
                    required: "Please select an Agent Name",
                },
                "agentGroup": {
                    required: "Please select an Agent Group",
                },
                "fromBookingDate": {
                    required: "Please select a From Booking Date",
                },
                "toBookingDate": {
                    required: "Please select a To Booking Date",
                },
                "fromCheckInDate": {
                    required: "Please select a From CheckIn Date",
                },
                "toCheckInDate": {
                    required: "Please select a To CheckIn Date",
                },
                "from_price_range": {
                    required: "Please enter a From Price Range",
                    numbersOnly: 'Please enter only numbers',
                },
                "to_price_range": {
                    required: "Please enter a To Price Range",
                    numbersOnly: 'Please enter only numbers',
                },
                "b2c_markup_type": {
                    required: "Please select a B2C Markup Type",
                },
                "b2c_markup": {
                    required: "Please enter a B2C Markup",
                },
                "fareType": {
                    required: "Please select a Fare Type",
                },
                "priority": {
                    required: "Please enter a Priority",
                    numbersOnly: 'Please enter only numbers',
                },
                "commMarkupOn": {
                    required: "Please Select a Comm On/Markup On"
                }
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
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
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
<script>
    $('#CountryDList').select2({
        ajax: {
            url: '/get-country-name',
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
                var mappedData = $.map(data, function(country) {
                    return {
                        id: country.iso_code,
                        text: country.cname
                    };
                });

                if (data[0].first_page == 1) {
                mappedData.unshift({ id: 'all', text: 'All' });
            }

                return {
                    results: mappedData,
                    pagination: {
                        more: mappedData.length >= 10
                    }
                };
            },
            cache: true
        }
    });
</script>
<script>
     $('#CityDList').select2({
        ajax: {
            url: '/get-only-city-name',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    term: params.term,
                    page: params.page || 1,
                    "_token": '{{ csrf_token() }}'
                };
            },
            processResults: function (data) {
                var mappedData = $.map(data, function (city) {
                    return {
                        id: city.iso_code,
                        text: city.cname
                    };
                });

                if (data[0].first_page == 1) {
                mappedData.unshift({ id: 'all', text: 'All' });
            }

                return {
                    results: mappedData,
                    pagination: {
                        more: mappedData.length >= 10
                    }
                };
            },
            cache: true
        },
        placeholder: 'Select city',
    });
</script>
<script>
   $('#supplier').select2({
        ajax: {
            url: "{{ route('markups.fetchSupplier') }}",
            // dataType: 'json',
            type: "get",
            delay: 250,
            data: function(params) {
                return {
                    q: params.term || '',
                    page: params.page || 1,
                    serviceType: '{{$serviceType}}',
                    "_token": '{{ csrf_token() }}'
                };
            },
            processResults: function(data) {
                var results = [];
                console.log('supplier data :', data);

                data.forEach(function(option) {
                    results.push({
                        id: option.id,
                        text: option.name
                    });
                });

                return {
                    results: results,
                    pagination: {
                        more: data.length >= 10 // Adjust based on your pagination logic
                    }
                };
            },
            cache: true
        },
        minimumInputLength: 0
    });
</script>
@append