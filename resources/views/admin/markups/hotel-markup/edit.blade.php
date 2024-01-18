@extends('admin.layout.main')
@section('title', $header['title'])

@section('content')
<style>
    .select2-search__field {
        color: black !important;
    }

    textarea.select2-search__field {
        width: 41.50em !important;
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
                <h1 class="m-0">Hotel Markup - Edit</h1>
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('adminUser.dashboard') </a></li>
                    <li class="breadcrumb-item"><a href="{{ route('hotel-markups.index') }}">Hotel Markups List</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div><!-- /.col -->

        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<section class="content">
    <div class="container-fluid">
        <!-- Info boxes -->
        <div class="row">
            <div class="card pb-4 w-100 px-3 py-2 mb-3">
                <form id="dataForm" name="dataForm" class="form row mb-0 pt-3 validate" action="{{ route('hotel-markups.update',$markupsDetail['id']) }}" enctype="multipart/form-data" method="post">
                    @csrf
                    @method('PUT')  
                    <input type="hidden" name="markups_id" id="markups_id" value="{{$markupsDetail['id']}}" />
                    <input type="hidden" name="service_type_id" value="{{ $serviceTypeId ?? '' }}">

                    <div class="col-md-6">
                        <div class="col-md-12">
                            <div class="form-floating form-item mb-0">
                                <div class="form-item form-float-style form-group">
                                    <input type="text" name="ruleName" id="ruleName" class="is-valid" value="{{ $markupsDetail['rule_name'] ?? '' }}" required>
                                    <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">Rule Name <span class="req-star">*</span></label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="col-md-12">
                            <div class="form-floating form-item mb-0">
                                <div class="form-item form-float-style form-group">
                                    <input type="text" name="priority" id="priority" class="is-valid" value="{{ $markupsDetail['priority'] ?? '' }}" required>
                                    <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">Priority <span class="req-star">*</span></label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="col-md-6">
                            <div class="form-floating form-item mb-0">
                                <div class="form-item form-float-style serach-rem mb-0">
                                    <div class="select top-space-rem after-drp form-float-style form-group">
                                        <select data-live-search="true" id="serviceType" name="serviceType" class="order-td-input selectpicker select-text height_drp is-valid" style="width: 100%;" disabled>
                                            <option value="flight" @if($service_type=='Flight' ) selected @endif>Flight</option>
                                            <option value="hotel" @if($service_type=='Hotel' ) selected @endif>Hotel</option>
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
                                        <select data-live-search="true" id="channel" name="channel[]" class="order-td-input selectpicker select-text height_drp is-valid" style="width: 100%;" multiple>
                                            <option value="back_office" @if(collect($markupsDetail['getChannel'])->contains('channel', 'back_office'))
                                                selected="selected"
                                                @endif>BackOffice</option>
                                            <option value="b2c" @if(collect($markupsDetail['getChannel'])->contains('channel', 'b2c'))
                                                selected="selected"
                                                @endif>B2C</option>
                                            <option value="b2b" @if(collect($markupsDetail['getChannel'])->contains('channel', 'b2b'))
                                                selected="selected"
                                                @endif>B2B</option>
                                            <option value="mobile" @if(collect($markupsDetail['getChannel'])->contains('channel', 'mobile'))
                                                selected="selected"
                                                @endif>Mobile</option>
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
                                        <select data-live-search="true" id="supplier" name="supplier[]" class="order-td-input selectpicker select-text height_drp is-valid" placeholder="Select Supplier" style="width:100%;" multiple>
                                            @foreach($getSupplier as $supplier)
                                            <option value="{{$supplier['id']}}" @if(collect($markupsDetail['getSupplier'])->contains('supplier_id', $supplier['id']))
                                                selected="selected"
                                                @endif>
                                                {{$supplier['name']}}
                                            </option>
                                            @endforeach
                                        </select>
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
                                            <option value="all" @if($markupsDetail['destination_criteria']=='all' ) selected @endif>All</option>
                                            <option value="country" @if($markupsDetail['destination_criteria']=='country' ) selected @endif>Country</option>
                                            <option value="city" @if($markupsDetail['destination_criteria']=='city' ) selected @endif>City</option>
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
                                        <select data-live-search="true" id="country_d_list" name="destinationName" class="order-td-input select-text height_drp is-valid select2 destination_country_list" style="width: 100%;">

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
                                        <select data-live-search="true" id="city_d_list" name="destinationName" class="order-td-input select-text height_drp is-valid select2 " style="width: 100%;">

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
                                <input type="text" class="is-valid datepicker" name="fromBookingDate" id="fromBookingDate" autocomplete="off" required placeholder="DD/MM/YYYY" class="is-valid" value="{{ \Carbon\Carbon::parse($markupsDetail['from_booking_date'] ?? '')->format('d-m-Y'); }}">
                                <label for="">From Booking Date <span class="req-star">*</span></label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="col-md-12">
                            <div class="form-item form-float-style form-group">
                                <input type="text" class="is-valid datepicker" name="toBookingDate" id="toBookingDate" autocomplete="off" required class="is-valid" placeholder="DD/MM/YYYY" value="{{ \Carbon\Carbon::parse($markupsDetail['to_booking_date'] ?? '')->format('d-m-Y'); }}">
                                <label for="">To Booking Date <span class="req-star">*</span></label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="col-md-12">
                            <div class="form-item form-float-style form-group">
                                <input type="text" class="is-valid datepicker" name="from_check_in_date" id="from_check_in_date" autocomplete="off" required class="is-valid" placeholder="DD/MM/YYYY" value="{{ \Carbon\Carbon::parse($markupsDetail['from_check_in_date'] ?? '')->format('d-m-Y'); }}">
                                <label for="">From CheckIn Date <span class="req-star">*</span></label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="col-md-12">
                            <div class="form-item form-float-style form-group">
                                <input type="text" class="is-valid datepicker" name="to_check_in_date" id="to_check_in_date" autocomplete="off" required class="is-valid" placeholder="DD/MM/YYYY" value="{{ \Carbon\Carbon::parse($markupsDetail['to_check_in_date'] ?? '')->format('d-m-Y'); }}">
                                <label for="">To CheckIn Date <span class="req-star">*</span></label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <div class="col-md-6">
                            <div class="form-floating form-item mb-0">
                                <div class="form-item form-float-style serach-rem mb-0">
                                    <div class="select top-space-rem after-drp form-float-style form-group">
                                        <?php $star_category = explode(',',$markupsDetail['star_category']);
                                        ?>
                                        <select data-live-search="true" id="star_category" name="star_category[]" class="order-td-input selectpicker select-text height_drp is-valid" style="width: 100%;" multiple>
                                        @foreach($star_category as $star)
                                            <option value="1Star" @if($star == '1Star')) selected="selected" @endif>1Star</option>
                                            <option value="2Star" @if($star == '2Star')) selected="selected" @endif>2Star</option>
                                            <option value="3Star" @if($star == '3Star')) selected="selected" @endif>3Star</option>
                                            <option value="4Star" @if($star == '4Star')) selected="selected" @endif>4Star</option>
                                            <option value="5Star" @if($star == '5Star')) selected="selected" @endif>5Star</option>
                                            <option value="6Star" @if($star == '6Star')) selected="selected" @endif>6Star</option>
                                            <option value="7Star" @if($star == '7Star')) selected="selected" @endif>7Star</option>
                                        @endforeach
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
                                <input type="text" name="from_price_range" id="from_price_range" autocomplete="off" value="{{ $markupsDetail['from_price_range'] ?? '' }}" required step="any">
                                <label for="from_price_range">From Price Range <span class="req-star">*</span></label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="col-md-12">
                            <div class="form-item form-float-style form-group">
                                <input type="text" name="to_price_range" id="to_price_range" autocomplete="off" value="{{ $markupsDetail['to_price_range'] ?? '' }}" required step="any">
                                <label for="to_price_range">To Price Range <span class="req-star">*</span></label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <div class="col-md-6">
                            <div class="form-floating form-item mb-0">
                                <div class="form-item form-float-style serach-rem mb-0">
                                    <div class="select top-space-rem after-drp form-float-style form-group">
                                        <select data-live-search="true" id="fareType" name="fareType" class="order-td-input selectpicker select-text height_drp is-valid">
                                            <option disabled selected>Select Fare Type</option>
                                            <option value="commission" @if($markupsDetail['fare_type']==='commission' ) selected @endif>Commission</option>
                                            <option value="net_fare" @if($markupsDetail['fare_type']==='net_fare' ) selected @endif>Net Fare</option>
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
                                            <option value="percentage" @if($markupsDetail['b2c_markup_type']=='percentage' ) selected @endif>%Percentage</option>
                                            <option value="fixed_amount" @if($markupsDetail['b2c_markup_type']=='fixed_amount' ) selected @endif>Fixed amount</option>
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
                                <input type="number" name="b2c_markup" id="b2c_markup" autocomplete="off" required step="any" value="{{ $markupsDetail['b2c_markup'] ?? '' }}">
                                <label for="b2c_markup">B2C Markup Value<span class="req-star">*</span></label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="col-md-12">
                            <div class="form-floating form-item mb-0">
                                <div class="form-item form-float-style serach-rem mb-0">
                                    <div class="select top-space-rem after-drp form-float-style form-group">
                                        <select data-live-search="true" id="b2b_markup_type" name="b2b_markup_type" class="order-td-input selectpicker select-text height_drp is-valid">
                                            <option value="percentage" @if($markupsDetail['b2b_markup_type']=='percentage' ) selected @endif>%Percentage</option>
                                            <option value="fixed_amount" @if($markupsDetail['b2b_markup_type']=='fixed_amount' ) selected @endif>Fixed amount</option>
                                        </select>
                                        <label class="select-label searchable-drp" style="font-size: 12px; font-weight: 400 !important;">Select B2B Markup Type<span class="req-star"></span></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="col-md-12">
                            <div class="form-item form-float-style form-group">
                                <input type="number" name="b2b_markup" id="b2b_markup" autocomplete="off" step="any" value="{{ $markupsDetail['b2b_markup'] ?? '' }}">
                                <label for="b2b_markup">B2B Markup Value<span class="req-star"></span></label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 row mb-3">
                        <div class="col-md-6">
                            <label for="" style="color: #999 !important;">Comm On/Markup On <span class="req-star">*</span></label>
                            <div class="q-a mb-2">
                                <div class="form-check">
                                    <input type="radio" id="comm_markup_base_fare" name="commMarkupOn" data-change="commMarkupOn" class="form-check-input" value="base_fare" @if($markupsDetail['comm_markup_on']==='base_fare' ) checked @endif>
                                    <label class="form-check-label" for="comm_markup_base_fare">Base Fare</label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" id="comm_markup_base_fare_yq" name="commMarkupOn" data-change="commMarkupOn" class="form-check-input" value="base_fare_yq" @if($markupsDetail['comm_markup_on']==='base_fare_yq' ) checked @endif>
                                    <label class="form-check-label" for="comm_markup_base_fare_yq">Base Fare+YQ</label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" id="comm_markup_net_fare" name="commMarkupOn" data-change="commMarkupOn" class="form-check-input" value="net_fare" @if($markupsDetail['comm_markup_on']==='net_fare' ) checked @endif>
                                    <label class="form-check-label" for="comm_markup_net_fare">Net Fare</label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" id="comm_markup_total_fare" name="commMarkupOn" data-change="commMarkupOn" class="form-check-input" value="total_fare" @if($markupsDetail['comm_markup_on']==='total_fare' ) checked @endif>
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
                                        <select data-live-search="true" id="agency" name="agency[]" class="order-td-input select-text height_drp is-valid" style="width: 100%;" multiple>
                                            @foreach($getAgency as $agency)
                                            <option value="{{$agency['id']}}" @if(collect($markupsDetail['getAgent'])->contains('agency_id', $agency['id']))
                                                selected="selected"
                                                @endif>
                                                {{$agency['full_name']}}
                                            </option>
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
                    <!-- Destination Country -->
                    @if($markupsDetail['destination_name'] == 'all')
                    @php
                    $destination_name = 'all';
                    @endphp
                    @else
                    @php
                    $destination_name = '';
                    @endphp
                    @endif
                    @if($markupsDetail['destination_criteria'] == 'country')
                    @if($markupsDetail->getDestinationCountry)
                    @php
                    $orignCountry = [];
                    @endphp
                    @forelse($markupsDetail->getDestinationCountry->countryCode as $country_name)
                    {{ $destinationCountry[] = $country_name['country_name'] ?? '' }}
                    @endforeach
                    @endif
                    @endif
                    <!-- Destination City -->
                    @if($markupsDetail['destination_criteria'] == 'city')
                    @if($markupsDetail->getDestinationCity)
                    @php
                    $orignCity = [];
                    @endphp
                    @forelse($markupsDetail->getDestinationCity->cityCode as $city_name)
                    {{ $destinationCity[] = $city_name['city_name'] ?? '' }}
                    @endforeach
                    @endif
                    @endif
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
    $("#star_category").select2();
    $("#channel").select2();
    $("#agency").select2();
    $("#agencyGroup").select2();
    $(document).ready(function() {
        $("#destinationNameAll").show();
        $("#destinationNameCountry").hide();
        $("#destinationNameCity").hide();
        $('#destinationCriteria').on('change', function() {
            var destination_criteria = $(this).val();
            if (destination_criteria == 'all') {
                $("#destinationNameAll").show();
                $("#destinationNameCountry").hide();
                $("#destinationNameCity").hide();
                $("#destinationNameAirport").hide();
            } else if (destination_criteria == 'country') {
                $("#destinationNameAll").hide();
                $("#destinationNameCountry").show();
                $("#destinationNameCity").hide();
                $("#destinationNameAirport").hide();
            } else if (destination_criteria == 'city') {
                $("#destinationNameAll").hide();
                $("#destinationNameCountry").hide();
                $("#destinationNameCity").show();
                $("#destinationNameAirport").hide();
            } 
        });
        $('#destinationCriteria').trigger('change');
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
        $("#from_check_in_date").datepicker({
            dateFormat: 'dd-mm-yy',
            minDate: dateToday,
            onClose: function(selected) {
                $("#to_check_in_date").datepicker("option", "minDate", selected);
                $(this).valid();
            }
        });
        $("#to_check_in_date").datepicker({
            dateFormat: 'dd-mm-yy',
            minDate: dateToday,
            onClose: function(selected) {
                $("#from_check_in_date").datepicker("option", "maxDate", selected);
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
                "star_category[]": {
                    required: true,
                },
                "destination": {
                    required: true,
                },
                "originName": {
                    required: true,
                },
                "destinationName": {
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
                "from_check_in_date": {
                    required: true,
                },
                "to_check_in_date": {
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
                    numbersOnly: true,
                    priceComparison: true,
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
                    required: "Please enter a Rule name",
                },
                "channel[]": {
                    required: "Please select a Channel",
                },
                "supplier[]": {
                    required: "Please select a Suppliers",
                },
                "star_category[]": {
                    required: "Please select a Star Category",
                },
                "destination": {
                    required: "Please select a Destination",
                },
                "destinationName": {
                    required: "Please select a Destination Name",
                },
                "fromBookingDate": {
                    required: "Please select a From Booking Date",
                },
                "toBookingDate": {
                    required: "Please select a To Booking Date",
                },
                "from_check_in_date": {
                    required: "Please select a From CheckIn Date",
                },
                "to_check_in_date": {
                    required: "Please select a To CheckIn Date",
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
    var originCriteria = '{{ $markupsDetail["origin_criteria"] }}';
    var destinationCriteria = '{{ $markupsDetail["destination_criteria"] }}';

    $(document).ready(function() {

        $('#country_d_list').select2({
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
                        mappedData.unshift({
                            id: 'all',
                            text: 'All'
                        });
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
            placeholder: 'Select country',
        }).on('change', function() {
            $('#city_d_list').empty();
        });


        if (destinationCriteria == 'country') {

            var dataDestinationNameCountry = {
                id: '{{ $markupsDetail["destination_name"] }}',
                text: '{{ isset($destinationCountry) && is_array($destinationCountry) ? implode(", ", $destinationCountry) : $destination_name }}'
            };
            console.log(dataDestinationNameCountry);

            var newDestinationCountryOption = new Option(dataDestinationNameCountry.text, dataDestinationNameCountry.id, false, false);
            console.log(newDestinationCountryOption);
            $('#country_d_list').append(newDestinationCountryOption).trigger('change');
        }

    });
</script>
<script>
    
    $(document).ready(function() {
        $('#city_d_list').select2({
            ajax: {
                url: '/get-only-city-name',
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
                    var mappedData = $.map(data, function(city) {
                        return {
                            id: city.iso_code,
                            text: city.cname
                        };
                    });

                    if (data[0].first_page == 1) {
                        mappedData.unshift({
                            id: 'all',
                            text: 'All'
                        });
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
        }).on('change', function() {

            $('.destination_airport_list').empty();
        });
        $('#city_d_list').val(null).trigger('change');

        if (destinationCriteria == 'city') {
            var datacdl = {
                id: '{{ $markupsDetail["destination_name"] }}',
                text: '{{ isset($destinationCity) && is_array($destinationCity) ? implode(", ", $destinationCity) : $destination_name }}'
            };

            var newOptionCDL = new Option(datacdl.text, datacdl.id, false, false);
            console.log(newOptionCDL)
            $('#city_d_list').append(newOptionCDL).trigger('change');
        }
    });
</script>
<script>
    $('#supplier').select2({
        ajax: {
            url: "{{ route('markups.fetchSupplier') }}",
            type: "get",
            delay: 250,
            data: function(params) {
                return {
                    q: params.term || '',
                    page: params.page || 1,
                    serviceType: '{{$service_type}}',
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