
@extends('admin.layout.main')
@section('title', $header['title'])

@section('content')
<style>
    .select2-search__field{
        color:black!important;
    }
</style>
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

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-4 mt-2">
            <div class="col-sm-12 align-items-center d-flex breadcrumb-style">
                <h1 class="m-0">Flight Markup - Add</h1>
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('adminUser.dashboard') </a></li>
                    <li class="breadcrumb-item"><a href="{{ route('flight-markups.index') }}">Flight Markups List</a></li>
                    <li class="breadcrumb-item active">Add </li>
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
                <form id="dataForm" name="dataForm" class="form row mb-0 pt-3 validate" action="{{ route('flight-markups.store') }}" enctype="multipart/form-data" method="post">
                    @csrf
                    <input type="hidden" name="service_type_id" value="{{ $serviceTypeId ?? '' }}">

                    <div class="col-md-6">
                        <div class="col-md-12">
                            <div class="form-floating form-item mb-0">
                                <div class="form-item form-float-style form-group">
                                    <input type="text" name="ruleName" id="ruleName" class="is-valid" required>
                                    <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">Rule Name <span class="req-star">*</span></label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="col-md-12">
                            <div class="form-floating form-item mb-0">
                                <div class="form-item form-float-style form-group">
                                    <input type="text" name="priority" id="priority" class="is-valid" required>
                                    <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">Priority <span class="req-star">*</span></label>
                                </div>
                            </div>
                        </div>
                    </div>
                  <?php $serviceTypeName = 'Flight'; ?>
                    <div class="col-md-6" style="pointer-events: none;">
                        <div class="col-md-12">
                            <div class="form-floating form-item mb-0">
                                <div class="form-item form-float-style serach-rem mb-0">
                                    <div class="select top-space-rem after-drp form-float-style form-group">
                                        <select data-live-search="true" id="serviceType" name="serviceType" class="order-td-input selectpicker select-text height_drp is-valid" style="width: 100%;">
                                            <option value="flight" @if($serviceTypeName == 'Flight') selected @endif>Flight</option>
                                            <option value="hotel" @if($serviceTypeName == 'Hotel') selected @endif>Hotel</option>
                                        </select>
                                        <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">Service Type </label>
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
                                        <select data-live-search="true" id="airlines" name="airlines[]" class="order-td-input select-text height_drp is-valid" style="width: 100%;" multiple>
                                        </select>
                                        <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">Airlines <span class="req-star">*</span></label>
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
                                        <select data-live-search="true" id="originCriteria" name="originCriteria" class="order-td-input selectpicker select-text height_drp is-valid" style="width: 100%;">
                                            <option value="all">All</option>
                                            <option value="country">Country</option>
                                            <option value="city">City</option>
                                            <option value="airport">Airport</option>
                                        </select>
                                        <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">Select Origin Criteria <span class="req-star">*</span></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6" id="originNameAll">
                        <div class="col-md-12">
                            <div class="form-floating form-item mb-0">
                                <div class="form-item form-float-style serach-rem mb-0">
                                    <div class="select top-space-rem after-drp form-float-style form-group">
                                        <select data-live-search="true" id="originName" name="originName" class="order-td-input select-text height_drp is-valid" style="width: 100%;">
                                            <option value="all">All</option>
                                        </select>
                                        <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">Select Origin Name <span class="req-star">*</span></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6" id="originNameCountry">
                        <div class="col-md-12">
                            <div class="form-floating form-item mb-0">
                                <div class="form-item form-float-style serach-rem mb-0">
                                    <div class="select top-space-rem after-drp form-float-style form-group">
                                        <select data-live-search="true" id="CountryList" name="originName" class="order-td-input select-text height_drp is-valid select2 CountryList" style="width: 100%;">
                                            <!-- <option value="all">All</option> -->
                                        </select>
                                        <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">Select Origin Name <span class="req-star">*</span></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6" id="originNameCity">
                        <div class="col-md-12">
                            <div class="form-floating form-item mb-0">
                                <div class="form-item form-float-style serach-rem mb-0">
                                    <div class="select top-space-rem after-drp form-float-style form-group">
                                        <select data-live-search="true" id="CityOList" name="originName" class="order-td-input select-text height_drp is-valid select2 CityOList" style="width: 100%;" placeholder="Select Airport">
                                            <!-- <option value="all">All</option> -->
                                        </select>
                                        <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">Select Origin Name <span class="req-star">*</span></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6" id="originNameAirport">
                        <div class="col-md-12">
                            <div class="form-floating form-item mb-0">
                                <div class="form-item form-float-style serach-rem mb-0">
                                    <div class="select top-space-rem after-drp form-float-style form-group">
                                        <select data-live-search="true" id="originName" name="originName" class="order-td-input select-text height_drp is-valid select2 origin_airport_list" style="width: 100%;" placeholder="Select Airport">
                                            <!-- <option value="all">All</option> -->
                                        </select>
                                        <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">Select Origin Name <span class="req-star">*</span></label>
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
                                            <option value="airport">Airport</option>
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
                                        <select data-live-search="true" id="destinationName" name="destinationName" class="order-td-input select-text height_drp is-valid select2" style="width: 100%;">
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
                                            <!-- <option value="all">All</option> -->
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
                                            <!-- <option value="all">All</option> -->
                                        </select>
                                        <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">Select Destination Name <span class="req-star">*</span></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6" id="destinationNameAirport">
                        <div class="col-md-12">
                            <div class="form-floating form-item mb-0">
                                <div class="form-item form-float-style serach-rem mb-0">
                                    <div class="select top-space-rem after-drp form-float-style form-group">
                                        <select data-live-search="true" id="destinationName" name="destinationName" class="order-td-input select-text height_drp is-valid select2 destination_airport_list" style="width: 100%;" placeholder="Select Airport">
                                            <!-- <option value="all">All</option> -->
                                        </select>
                                        <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">Select Destination Name <span class="req-star">*</span></label>
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
                                        <select data-live-search="true" id="bookingClass" name="bookingClass" class="order-td-input selectpicker select-text height_drp is-valid">
                                            <option disabled selected>Select Booking Class</option>
                                            <option value="all">All</option>
                                            @foreach (range('A', 'Z') as $letter)
                                            <option value="{{ $letter }}"> {{ $letter }}</option>
                                            @endforeach
                                        </select>
                                        <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">Select Booking Class <span class="req-star">*</span></label>
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
                                        <select data-live-search="true" id="cabinClass" name="cabinClass" class="order-td-input selectpicker select-text height_drp is-valid">
                                            <option disabled selected>Select Cabin Class</option>
                                            <option value="first">First</option>
                                            <option value="business">Business</option>
                                            <option value="premium_economy">Premium Economy</option>
                                            <option value="economy">Economy</option>
                                        </select>
                                        <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">Select Cabin Class <span class="req-star">*</span></label>
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
                                <input type="text" class="is-valid datepicker" name="fromTravelDate" id="fromTravelDate" autocomplete="off" required class="is-valid" placeholder="DD/MM/YYYY">
                                <label for="">From Travel Date <span class="req-star">*</span></label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="col-md-12">
                            <div class="form-item form-float-style form-group">
                                <input type="text" class="is-valid datepicker" name="toTravelDate" id="toTravelDate" autocomplete="off" required class="is-valid" placeholder="DD/MM/YYYY">
                                <label for="">To Travel Date <span class="req-star">*</span></label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="col-md-12">
                            <div class="form-floating form-item mb-0">
                                <div class="form-item form-float-style serach-rem mb-0">
                                    <div class="select top-space-rem after-drp form-float-style form-group">
                                        <select data-live-search="true" id="tripType" name="tripType" class="order-td-input selectpicker select-text height_drp is-valid">
                                            <option disabled selected>Select Trip Type</option>
                                            <option value="all">All</option>
                                            <option value="one_way">One Way</option>
                                            <option value="round_trip">Round Trip</option>
                                            <option value="multi_city">Multi City</option>
                                        </select>
                                        <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">Trip Type <span class="req-star">*</span></label>
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
                                        <select data-live-search="true" id="paxType" name="paxType" class="order-td-input selectpicker select-text height_drp is-valid">
                                            <option disabled selected>Select Passenger Type</option>
                                            <option value="all">All</option>
                                            <option value="adult">Adult</option>
                                            <option value="child">Child</option>
                                            <option value="infant">Infant</option>
                                        </select>
                                        <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">Select Passenger Type <span class="req-star">*</span></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="col-md-12">
                            <div class="form-item form-float-style form-group">
                                <input type="text" name="from_price_range" id="from_price_range" autocomplete="off" required step="any">
                                <label for="from_price_range">From Price Range <span class="req-star">*</span></label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="col-md-12">
                            <div class="form-item form-float-style form-group">
                                <input type="text" name="to_price_range" id="to_price_range" autocomplete="off" required step="any">
                                <label for="to_price_range">To Price Range <span class="req-star">*</span></label>
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
                                    <label class="form-check-label" for="comm_markup_base_fare_yq">Base Fare+YQ</label>
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
                        <a href="{{ route('flight-markups.index') }}" class="btn btn-danger form-btn-danger">Cancel</a>
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
    $("#agency").select2();
    $("#agencyGroup").select2();

    $(document).ready(function(){
        $("#originNameAll").show();
        $("#originNameCountry").hide();
        $("#originNameCity").hide();
        $("#originNameAirport").hide();
        $('#originCriteria').on('change', function() {
            var origin_criteria = $(this).val();
            if (origin_criteria == 'all')
            {
                $("#originNameAll").show();
                $("#originNameCountry").hide();
                $("#originNameCity").hide();
                $("#originNameAirport").hide();
            }else if(origin_criteria == 'country'){
                $("#originNameAll").hide();
                $("#originNameCountry").show();
                $("#originNameCity").hide();
                $("#originNameAirport").hide();
            }else if(origin_criteria == 'city'){
                $("#originNameAll").hide();
                $("#originNameCountry").hide();
                $("#originNameCity").show();
                $("#originNameAirport").hide();
            }else if(origin_criteria == 'airport'){
                $("#originNameAll").hide();
                $("#originNameCountry").hide();
                $("#originNameCity").hide();
                $("#originNameAirport").show();
            }
        });
    });

    $(document).ready(function(){
        $("#destinationNameAll").show();
        $("#destinationNameCountry").hide();
        $("#destinationNameCity").hide();
        $("#destinationNameAirport").hide();
        $('#destinationCriteria').on('change', function() {
            var destination_criteria = $(this).val();
            if (destination_criteria == 'all')
            {
                $("#destinationNameAll").show();
                $("#destinationNameCountry").hide();
                $("#destinationNameCity").hide();
                $("#destinationNameAirport").hide();
            }else if(destination_criteria == 'country'){
                $("#destinationNameAll").hide();
                $("#destinationNameCountry").show();
                $("#destinationNameCity").hide();
                $("#destinationNameAirport").hide();
            }else if(destination_criteria == 'city'){
                $("#destinationNameAll").hide();
                $("#destinationNameCountry").hide();
                $("#destinationNameCity").show();
                $("#destinationNameAirport").hide();
            }else if(destination_criteria == 'airport'){
                $("#destinationNameAll").hide();
                $("#destinationNameCountry").hide();
                $("#destinationNameCity").hide();
                $("#destinationNameAirport").show();
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
        $("#fromTravelDate").datepicker({
            dateFormat: 'dd-mm-yy',
            minDate: dateToday,
            onClose: function(selected) {
                $("#toTravelDate").datepicker("option", "minDate", selected);
                $(this).valid();
            }
        });
        $("#toTravelDate").datepicker({
            dateFormat: 'dd-mm-yy',
            minDate: dateToday,
            onClose: function(selected) {
                $("#fromTravelDate").datepicker("option", "maxDate", selected);
                $(this).valid();
            }
        });
    });
    $.validator.addMethod("priceComparison", function(value, element) {
        var fromPrice = parseFloat($("#from_price_range").val());
        var toPrice = parseFloat($("#to_price_range").val());
        // Perform date comparison
        return fromPrice <= toPrice;
    }, "To Price must be greater than From Price");
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
                "origin": {
                    required: true,
                },
                "originName": {
                    required: true,
                },
                "destinationName": {
                    required: true,
                },
                "destination": {
                    required: true,
                },
                "airlines[]": {
                    required: true,
                },
                "fromBookingDate": {
                    required: true,
                },
                "toBookingDate": {
                    required: true,
                },
                "fromTravelDate": {
                    required: true,
                },
                "toTravelDate": {
                    required: true,
                },
                "bookingClass": {
                    required: true,
                },
                "cabinClass": {
                    required: true,
                },
                "tripType": {
                    required: true,
                },
                "paxType": {
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
                    priceComparison: true,
                    numbersOnly: true,
                },
                "b2c_markup_type": {
                    required: true,
                },
                "b2c_markup": {
                    required: true,
                },
                "priority": {
                    required: true,
                    noSpace: true,
                    numbersOnly: true,
                },
            },
            messages: {
                "ruleName": {
                    required: "Please enter a rule name",
                },
                "channel[]": {
                    required: "Please select an Channel",
                },
                "supplier[]": {
                    required: "Please select a Suppliers",
                },
                "originName": {
                    required: "Please select an Origin Name",
                },
                "destinationName": {
                    required: "Please select a Destination Name",
                },
                "origin": {
                    required: "Please select an Origin",
                },
                "destination": {
                    required: "Please select a Destination",
                },
                "airlines[]": {
                    required: "Please select an Airlines",
                },
                "fromBookingDate": {
                    required: "Please select a From Booking Date",
                },
                "toBookingDate": {
                    required: "Please select a To Booking Date",
                },
                "fromTravelDate": {
                    required: "Please select a From Travel Date",
                },
                "toTravelDate": {
                    required: "Please select a To Travel Date",
                },
                "bookingClass": {
                    required: "Please select a Booking Class",
                },
                "cabinClass": {
                    required: "Please select a Cabin Class",
                },
                "tripType": {
                    required: "Please select a Trip Type",
                },
                "paxType": {
                    required: "Please select a Pax Type",
                },
                "from_price_range": {
                    required: "Please enter a From Price Range",
                    numbersOnly: 'Please enter only numbers',
                },
                "to_price_range": {
                    required: "Please select a To Price Range",
                    numbersOnly: 'Please enter only numbers',
                },
                "b2c_markup_type": {
                    required: "Please select a B2C Markup Type",
                },
                "b2c_markup": {
                    required: "Please Enter a B2C Markup",
                },
                "priority": {
                    required: "Please Enter a Priority",
                    numbersOnly: 'Please enter only numbers',
                },
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
    $('#CountryList, #CountryDList').select2({
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
            console.log(data);
            var mappedData = $.map(data, function(country) {
                return {
                    id: country.iso_code,
                    text: country.cname
                };
            });
            
            if (data[0].first_page == 1) {
                mappedData.unshift({ id: 'all', text: 'All' });
            }
            // Add 'All' option only for the first page
            

            return {
                results: mappedData,
                pagination: {
                    more: mappedData.length >= 10
                }
            };
        },
        cache: true
    },
    placeholder: 'Select country',
});

</script>
<script>
     $('#CityOList, #CityDList').select2({
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
                console.log(data);
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
    $('.origin_airport_list, .destination_airport_list').select2({
        ajax: {
            url: "{{ route('markups.fetchOrigin') }}",
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
                var mappedData = $.map(data, function(airport) {
                    return {
                        id: airport.id,
                        text: airport.airname
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
        placeholder: 'Select airport',
        minimumInputLength: 0
    });

   

    $('#airlines').select2({
        ajax: {
            url: "{{ route('markups.fetchAirlines') }}",
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
                var mappedData = $.map(data, function(airline) {
                    return {
                        id: airline.id,
                        text: airline.airname
                    };
                });

                return {
                    results: mappedData,
                    pagination: {
                        more: mappedData.length >= 10
                    }
                };
            },
            cache: true
        },
        minimumInputLength: 0
    });

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
                    serviceType:'{{$serviceTypeName}}',
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