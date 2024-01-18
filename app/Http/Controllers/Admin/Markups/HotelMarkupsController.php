<?php

namespace App\Http\Controllers\Admin\Markups;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;
use App\Traits\ActiveLog;
use App\Models\Setting;
use App\Models\Suppliers;
use App\Models\ServiceType;
use App\Models\Airport;
use App\Models\Airline;
use App\Models\HotelMarkup;
use App\Models\DefaultMarkup;
use App\Models\Agency;
use App\Models\Country;
use App\Models\City;
use App\Models\Markups;
use DB;

class HotelMarkupsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $service_type = ServiceType::select('code','id')->where('code', 'Hotel')->first();
        $header['title'] = "Markup Rules List";
        $header['heading'] = "Markups Rules List";
        $queryStringConcat = '?';
        if (isset($_GET['per_page'])) {
            $queryStringConcat .= ($queryStringConcat == '') ? '?per_page=' . $_GET['per_page'] : '&per_page=' . $_GET['per_page'];
        }
        if (isset($_GET['page'])) {
            $queryStringConcat .= ($queryStringConcat == '') ? '?page=' . $_GET['page'] : '&page=' . $_GET['page'];
        }

        $appliedFilter = array(
            'per_page' => (request()->input('per_page') != NULL) ? request()->input('per_page') : Setting::where('config_key', 'general|setting|pagePerAdminRecords')->get('value')[0]['value'],
            'order_by' => (request()->input('order_by') != NULL) ? request()->input('order_by') : 'created_at',
            'sorting' => (request()->input('sorting') != NULL) ? request()->input('sorting') : 'desc',
            'rule_name' => (request()->input('rule_name') != NULL) ? request()->input('rule_name') : '',
            'channel' => (request()->input('channel') != NULL) ? request()->input('channel') : '',
            'destinationCriteria' => (request()->input('destinationCriteria') != NULL) ? request()->input('destinationCriteria') : '',
            'from_booking_date' => (request()->input('from_booking_date') != NULL) ? request()->input('from_booking_date') : '',
            'to_booking_date' => (request()->input('to_booking_date') != NULL) ? request()->input('to_booking_date') : '',
            'commMarkupOn' => (request()->input('commMarkupOn') != NULL) ? request()->input('commMarkupOn') : '',
            'service_type_id' =>  $service_type['id'],
            'service_type' =>  $service_type['code'],
        );

        if (request()->input('rule_name') != NULL) {
            $appliedFilter['where'][] = ['rule_name', 'LIKE', '%' .  request()->input('rule_name') . '%'];
        }

        if (request()->input('channel') != NULL) {
            $appliedFilter['whereHas'] = ['channel', '=', request()->input('channel')];
        }
        
        if (request()->input('destinationCriteria') != NULL) {
            $appliedFilter['where'][] = ['destination_criteria', '=', request()->input('destinationCriteria')];
        }
        if (request()->input('from_booking_date') != NULL) {
            $appliedFilter['where'][] = ['from_booking_date', '=', request()->input('from_booking_date')];
        }
        if (request()->input('to_booking_date') != NULL) {
            $appliedFilter['where'][] = ['to_booking_date', '=', request()->input('to_booking_date')];
        }
       
        if (request()->input('commMarkupOn') != NULL) {
            $appliedFilter['where'][] = ['comm_markup_on', '=', request()->input('commMarkupOn')];
        }

        $markupsDataList = HotelMarkup::getHotelMarkupsData($appliedFilter);
        $markupsDataCount = HotelMarkup::count();
        $markupsData = $markupsDataList['data'];

        $activityLog['request'] =  $appliedFilter;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] =  $markupsDataList;
        ActiveLog::createBackendActiveLog($activityLog);

        if ($markupsData['status'] == 1) {
            return view('admin/markups/hotel-markup/index')->with(['header' => $header, 'appliedFilter' => $appliedFilter, 'markupsDataCount' => $markupsDataCount, 'markupsData' => $markupsData, 'queryStringConcat' => $queryStringConcat, 'i' => (request()->input('page', 1) - 1) * $appliedFilter['per_page']]);
        } else {
            return view('admin/markups/hotel-markup/index')->with(['header' => $header, 'appliedFilter' => $appliedFilter, 'markupsData' => $markupsData, 'markupsDataCount' => $markupsDataCount, 'queryStringConcat' => $queryStringConcat, 'i' => (request()->input('page', 1) - 1) * $appliedFilter['per_page']]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $header['title'] = "Hotel - Add";
        $header['heading'] = "Hotel - Add";

        $getServiceTypeData = ServiceType::select('id','code')->where('code', $request['service_type'])->first();
        $serviceTypeId = $getServiceTypeData->id ?? '';
        $serviceType = $getServiceTypeData->code ?? '';
        $getAgency = Agency::where('status', 'active')->get()->toArray();

        $activityLog['request'] =  $request->all();
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] =  [];
        ActiveLog::createBackendActiveLog($activityLog);

        return view('admin/markups/hotel-markup/add')->with(['header' => $header, 'serviceType' => $serviceType, 'serviceTypeId' => $serviceTypeId, 'getAgency' => $getAgency]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $requestData = $request->only([
            'ruleName',
            'priority',
            'service_type_id',
            'channel',
            'supplier',
            'destinationCriteria',
            'destinationName',
            'fromBookingDate',
            'toBookingDate',
            'fromCheckInDate',
            'toCheckInDate',
            'starCategory',
            'fareType',
            'from_price_range',
            'to_price_range',
            'b2c_markup_type',
            'b2c_markup',
            'b2b_markup_type',
            'b2b_markup',
            'commMarkupOn',
            'agency',
            'agencyGroup'
        ]);

        $rules = [
            'ruleName' => 'required',
            'priority' => 'required',
            'channel' => 'required',
            'supplier' => 'required',
            'destinationCriteria' => 'required',
            'destinationName' => 'required',
            'fromBookingDate' => 'required',
            'toBookingDate' => 'required',
            'fromCheckInDate' => 'required',
            'toCheckInDate' => 'required',
            'starCategory' => 'required',
            'fareType' => 'required',
            'from_price_range' => 'required',
            'to_price_range' => 'required',
            'b2c_markup_type' => 'required',
            'b2c_markup' => 'required',
            'commMarkupOn' => 'required'

        ];

        $customMessages = [];

        $niceNames = array();

        $this->validate($request, $rules, $customMessages, $niceNames);
        $requestData['starCategory'] = implode(',', $request->starCategory);

        $response = HotelMarkup::createMarkups($requestData);
        $service_type_id = $response['data']['service_type_id'];
        $getServiceTypeData = ServiceType::select('id', 'name')->where('id', $service_type_id)->first();
        $serviceTypeName = $getServiceTypeData->name ?? '';

        $activityLog['request'] =  $request->all();
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] =  $requestData;
        ActiveLog::createBackendActiveLog($activityLog);

        if ($response['status'] == 1 && !empty($response['data'])) {
            return redirect()->route('hotel-markups.index')->with('success', $response['message']);
        } else {
            return redirect()->route('hotel-markups.index')->with('error', $response['message']);
        }
    }

    /**
     * Display the specified hotel markups.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $checkServiceType = ServiceType::select('core_service_types.id', 'core_service_types.code', 'markups.service_type_id')
            ->join('markups', 'core_service_types.id', 'markups.service_type_id')
            ->where('markups.id', $id)->first();
        $service_type_id = $checkServiceType->id ?? '';
        $service_type = $checkServiceType->code ?? '';

        $header['title'] = "" . $service_type . " - View";
        $header['heading'] = "" . $service_type . " - View";


        $filter = array(
            'id' => $id,
            'service_type_id' => $service_type_id,
        );

        $response = HotelMarkup::getHotelMarkupsData($filter);
        $markupsDetail = $response['data'];
        $service_type = $markupsDetail['getServiceType']['name'] ?? '';

        $activityLog['request'] =  $filter;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] =  $response;
        ActiveLog::createBackendActiveLog($activityLog);

        if ($response['status'] == 1 && !empty($response['data'])) {
            return view('admin/markups/hotel-markup/view')->with(['header' => $header, 'service_type' => $service_type, 'markupsDetail' => $markupsDetail]);
        } else {
            return redirect()->to('hotel-markups.index')->with('error', $response['message']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $checkServiceType = ServiceType::select('core_service_types.id', 'core_service_types.code', 'markups.service_type_id')
            ->join('markups', 'core_service_types.id', 'markups.service_type_id')
            ->where('markups.id', $id)->first();
        $service_type_id = $checkServiceType->id ?? '';
        $service_type = $checkServiceType->code ?? '';

        $header['title'] = "" . $service_type . " - View";
        $header['heading'] = "" . $service_type . " - View";

        $filter = array(
            'id' => $id,
            'service_type_id' => $service_type_id,
        );

        $response = Markups::getMarkupsData($filter);
        $markupsDetail = $response['data'];
        $getAirports = Airport::with('airportName')->where('status', 'active')->get()->toArray();
        $getAirlines = Airline::with('airlineCodeName')->where('status', 'active')->get()->toArray();
        $getSupplier = Suppliers::where('core_service_type_id', $service_type_id)->get()->toArray();
        $getAgency = Agency::where('status', 'active')->get()->toArray();

        $getCountry = Country::with('countryCode')->where('status', 'active')->get()->toArray();
        $getCities = City::with('cityCode')->where('status', 'active')->get()->toArray();

        $activityLog['request'] =  $filter;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] =  $response;
        ActiveLog::createBackendActiveLog($activityLog);

        if ($response['status'] == 1 && !empty($response['data'])) {
            return view('admin/markups/hotel-markup/edit')->with(['header' => $header, 'serviceTypeId' => $service_type_id, 'service_type' => $service_type, 'markupsDetail' => $markupsDetail, 'getAirports' => $getAirports, 'getAirlines' => $getAirlines, 'getSupplier' => $getSupplier, 'getAgency' => $getAgency, 'getCountry' => $getCountry, 'getCities' => $getCities]);
        } else {
            return redirect()->to('hotel-markups.index')->with('error', $response['message']);
        }
    }

    /**
     * Update the specified markups in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $requestData = $request->only([
            'markups_id',
            'ruleName',
            'priority',
            'service_type_id',
            'channel',
            'supplier',
            'destinationCriteria',
            'destinationName',
            'fromBookingDate',
            'toBookingDate',
            'from_check_in_date',
            'to_check_in_date',
            'star_category',
            'from_price_range',
            'to_price_range',
            'fareType',
            'b2c_markup_type',
            'b2c_markup',
            'b2b_markup_type',
            'b2b_markup',
            'commMarkupOn',
            'agency',
            'agencyGroup'
        ]);
        $rules = [];
        
        $customMessages = [];
        
        $niceNames = array();
        $this->validate($request, $rules, $customMessages, $niceNames);
        if(isset($request->star_category)){
        $requestData['star_category'] = implode(',', $request->star_category);
        }
        $response = HotelMarkup::updateMarkups($requestData);
        $service_type_id = $response['data']['service_type_id'];
        $getServiceTypeData = ServiceType::select('id', 'name')->where('id', $service_type_id)->first();
        $serviceTypeName = $getServiceTypeData->name ?? '';

        $activityLog['request'] =  $requestData;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] =  $response;
        ActiveLog::createBackendActiveLog($activityLog);

        if ($response['status'] == 1 &&  !empty($response['data'])) {
            return redirect()->route('hotel-markups.index')->with('success', $response['message']);
        } else {
            return redirect()->route('hotel-markups.index')->with('error', $response['message']);
        }
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
