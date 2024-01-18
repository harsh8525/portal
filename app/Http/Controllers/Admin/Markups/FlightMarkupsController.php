<?php

/**
 * @package     Markups
 * @subpackage   Markups
 * @Author      Amar Technolabs Pvt. ltd(info@amarinfotech.com)
 * @Copyright(C) 2023 [Travel Portal].
 * @Version 1.0.0
 * module of the  Markups.
 */

namespace App\Http\Controllers\Admin\Markups;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;
use App\Models\Setting;
use App\Models\Suppliers;
use App\Models\ServiceType;
use App\Traits\ActiveLog;
use App\Models\Airport;
use App\Models\Airline;
use App\Models\Markups;
use App\Models\DefaultMarkup;
use App\Models\Agency;
use App\Models\Country;
use App\Models\City;
use DB;

class FlightMarkupsController extends Controller
{
    /**
     * Display a listing of the markups.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $service_type = ServiceType::select('code', 'id')->where('code', 'Flight')->first();
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
            'origin' => (request()->input('origin') != NULL) ? request()->input('origin') : '',
            'rule_name' => (request()->input('rule_name') != NULL) ? request()->input('rule_name') : '',
            'channel' => (request()->input('channel') != NULL) ? request()->input('channel') : '',
            'originCriteria' => (request()->input('originCriteria') != NULL) ? request()->input('originCriteria') : '',
            'destinationCriteria' => (request()->input('destinationCriteria') != NULL) ? request()->input('destinationCriteria') : '',
            'from_booking_date' => (request()->input('from_booking_date') != NULL) ? request()->input('from_booking_date') : '',
            'to_booking_date' => (request()->input('to_booking_date') != NULL) ? request()->input('to_booking_date') : '',
            'cabinClass' => (request()->input('cabinClass') != NULL) ? request()->input('cabinClass') : '',
            'commMarkupOn' => (request()->input('commMarkupOn') != NULL) ? request()->input('commMarkupOn') : '',
            'service_type_id' => $service_type['id'],
        );

        if (request()->input('rule_name') != NULL) {
            $appliedFilter['where'][] = ['rule_name', 'LIKE', '%' .  request()->input('rule_name') . '%'];
        }

        if (request()->input('channel') != NULL) {
            $appliedFilter['whereHas'] = ['channel', '=', request()->input('channel')];
        }
        if (request()->input('originCriteria') != NULL) {
            $appliedFilter['where'][] = ['origin_criteria', '=', request()->input('originCriteria')];
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
        if (request()->input('cabinClass') != NULL) {
            $appliedFilter['where'][] = ['cabin_class', '=', request()->input('cabinClass')];
        }
        if (request()->input('commMarkupOn') != NULL) {
            $appliedFilter['where'][] = ['comm_markup_on', '=', request()->input('commMarkupOn')];
        }


        $markupsDataList = Markups::getMarkupsData($appliedFilter);
        $markupsDataCount = Markups::count();
        $markupsData = $markupsDataList['data'];

        $activityLog['request'] =  $appliedFilter;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] =  $markupsData;
        ActiveLog::createBackendActiveLog($activityLog);

        if ($markupsData['status'] == 1) {
            return view('admin/markups/flight-markup/index')->with(['header' => $header, 'appliedFilter' => $appliedFilter, 'service_type' => $service_type, 'markupsDataCount' => $markupsDataCount, 'markupsData' => $markupsData, 'queryStringConcat' => $queryStringConcat, 'i' => (request()->input('page', 1) - 1) * $appliedFilter['per_page']]);
        } else {
            return view('admin/markups/flight-markup/index')->with(['header' => $header, 'appliedFilter' => $appliedFilter, 'service_type' => $service_type, 'markupsData' => $markupsData, 'markupsDataCount' => $markupsDataCount, 'queryStringConcat' => $queryStringConcat, 'i' => (request()->input('page', 1) - 1) * $appliedFilter['per_page']]);
        }
    }


    /**
     * Show the form for creating a new markups.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $getServiceTypeData = ServiceType::select('id', 'code')->where('code', $request['service_type'])->first();
        $serviceTypeId = $getServiceTypeData->id ?? '';
        $serviceType = $getServiceTypeData->code ?? '';
        $header['title'] = $serviceType . " - Add";
        $header['heading'] = $serviceType . " - Add";

        $getAgency = Agency::where('status', 'active')->get()->toArray();

        $activityLog['request'] =  $request->all();
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] =  [];
        ActiveLog::createBackendActiveLog($activityLog);

        return view('admin/markups/flight-markup/add')->with(['header' => $header, 'serviceTypeId' => $serviceTypeId, 'serviceTypeName' => $serviceType, 'getAgency' => $getAgency]);
    }

    /**
     * Store a newly created markups in storage.
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
            'originCriteria',
            'originName',
            'destinationCriteria',
            'destinationName',
            'airlines',
            'fromBookingDate',
            'toBookingDate',
            'fromTravelDate',
            'toTravelDate',
            'bookingClass',
            'cabinClass',
            'tripType',
            'paxType',
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

        $rules = [
            'ruleName' => 'required',
            'priority' => 'required',
            'channel' => 'required',
            'supplier' => 'required',
            'originCriteria' => 'required',
            'originName' => 'required',
            'destinationCriteria' => 'required',
            'destinationName' => 'required',
            'airlines' => 'required',
            'fromBookingDate' => 'required',
            'toBookingDate' => 'required',
            'fromTravelDate' => 'required',
            'toTravelDate' => 'required',
            'bookingClass' => 'required',
            'cabinClass' => 'required',
            'tripType' => 'required',
            'paxType' => 'required',
            'from_price_range' => 'required',
            'to_price_range' => 'required',
            'fareType' => 'required',
            'b2c_markup_type' => 'required',
            'b2c_markup' => 'required',
            'commMarkupOn' => 'required'

        ];

        $customMessages = [];

        $niceNames = array();

        $this->validate($request, $rules, $customMessages, $niceNames);
        $response = Markups::createMarkups($requestData);

        $service_type_id = $response['data']['service_type_id'];
        $getServiceTypeData = ServiceType::select('id', 'name')->where('id', $service_type_id)->first();
        $serviceTypeName = $getServiceTypeData->name ?? '';

        $activityLog['request'] =  $request->all();
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] =  $requestData;
        ActiveLog::createBackendActiveLog($activityLog);

        if ($response['status'] == 1 && !empty($response['data'])) {
            return redirect()->route('flight-markups.index')->with('success', $response['message']);
        } else {
            return redirect()->route('flight-markups.index')->with('error', $response['message']);
        }
    }

    /**
     * Display the specified markups.
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

        $response = Markups::getMarkupsData($filter);
        $markupsDetail = $response['data'];
        $service_type = $markupsDetail['getServiceType']['name'] ?? '';

        $activityLog['request'] =  $filter;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] =  $response;
        ActiveLog::createBackendActiveLog($activityLog);

        if ($response['status'] == 1 && !empty($response['data'])) {
            return view('admin/markups/flight-markup/view')->with(['header' => $header, 'service_type' => $service_type, 'markupsDetail' => $markupsDetail]);
        } else {
            return redirect()->to('flight-markups.index')->with('error', $response['message']);
        }
    }

    /**
     * Show the form for editing the specified markups.
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
            return view('admin/markups/flight-markup/edit')->with(['header' => $header, 'serviceTypeId' => $service_type_id, 'service_type' => $service_type, 'markupsDetail' => $markupsDetail, 'getAirports' => $getAirports, 'getAirlines' => $getAirlines, 'getSupplier' => $getSupplier, 'getAgency' => $getAgency, 'getCountry' => $getCountry, 'getCities' => $getCities]);
        } else {
            return redirect()->to('markups/manage?service_type=' . $service_type)->with('error', $response['message']);
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
            'originCriteria',
            'originName',
            'destinationCriteria',
            'destinationName',
            'airlines',
            'fromBookingDate',
            'toBookingDate',
            'fromTravelDate',
            'toTravelDate',
            'bookingClass',
            'cabinClass',
            'tripType',
            'paxType',
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
        $response = Markups::updateMarkups($requestData);

        $service_type_id = $response['data']['service_type_id'];
        $getServiceTypeData = ServiceType::select('id', 'name')->where('id', $service_type_id)->first();
        $serviceTypeName = $getServiceTypeData->name ?? '';

        $activityLog['request'] =  $requestData;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] =  $response;
        ActiveLog::createBackendActiveLog($activityLog);

        if ($response['status'] == 1 &&  !empty($response['data'])) {
            return redirect()->route('flight-markups.index')->with('success', $response['message']);
        } else {
            return redirect()->route('flight-markups.index')->with('error', $response['message']);
        }
    }

    /**
     * Remove the specified markups from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }



    /**
     * Fetch airport for origin.
     *
     * 
     * @return \Illuminate\Http\Response
     */
    public function fetchOriginList(Request $request)
    {

        $requestData = $request->all();
        $term = $request->input('term');
        $page = $request->input('page');
        $resultsPerPage = 10;
        $offset = ($page - 1) * $resultsPerPage;

        $query = Airport::with('airportName');

        if ($term) {
            $query->whereHas('airportName', function ($query) use ($term) {
                $query->where('airport_name', 'like', '%' . $term . '%');
            });
        }
        $query->offset($offset)->limit($resultsPerPage);
        $query->orderBy('iata_code', 'asc');
        $airports = $query->get();

        $data = [];
        foreach ($airports as $airport) {
            $airname = [];
            if (!empty($airport['airportName'])) {
                foreach ($airport['airportName'] as $airport_name) {
                    $airname[] = $airport_name['airport_name'] . ' ';
                }
            }
            $data[] = ['id' => $airport['id'], 'airname' => $airname, 'first_page' => $page];
        }

        return response()->json($data);
    }

    /**
     * Fetch airline code.
     *
     * 
     * @return \Illuminate\Http\Response
     */
    public function fetchAirlines(Request $request)
    {
        $term = $request->input('term');
        $page = $request->input('page');

        $resultsPerPage = 10;
        $offset = ($page - 1) * $resultsPerPage;
        $query = Airline::with('airlineCodeName');

        if ($term) {
            $query->whereHas('airlineCodeName', function ($query) use ($term) {
                $query->where('airline_name', 'like', '%' . $term . '%');
            });
        }
        $query->offset($offset)->limit($resultsPerPage);
        $query->orderBy('airline_code', 'asc');
        $airlines = $query->get();

        $data = [];
        foreach ($airlines as $airline) {
            $airname = [];
            if (!empty($airline['airlineCodeName'])) {
                foreach ($airline['airlineCodeName'] as $airline_name) {
                    $airname[] = $airline_name['airline_name'] . ' ';
                }
            }
            $data[] = ['id' => $airline['id'], 'airname' => $airname];
        }

        return response()->json($data);
    }

    /**
     * Fetch supplier code.
     *
     * 
     * @return \Illuminate\Http\Response
     */
    public function fetchSupplier(Request $request)
    {
        $requestData = $request->all();
        $page = $request->input('page');
        $resultsPerPage = 10;
        $offset = ($page - 1) * $resultsPerPage;
        $serviceType = ServiceType::where('name', $requestData['serviceType'])->get('id')[0]['id'];
        $getSupplier = Suppliers::where('core_service_type_id', $serviceType);
        $getSupplier->offset($offset) // For par page 10 records
            ->limit($resultsPerPage);
        if ($request->input('q') != "") {
            $getSupplier->where('name', 'LIKE', '%' . $request->input('q') . '%');
        }

        $data = $getSupplier->get()->toArray();
        return response()->json($data);
    }



    /**
     * Remove the specified markups from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteMarkups(Request $request)
    {
        if (!hasPermission('Markups', 'delete')) {
            return view('admin/401');
        }
        $url = URL::previous();
        $markupsIDs = explode(',', $request->input('delete_markups_id'));

        $message = "";
        foreach ($markupsIDs as $delete_markups_id) {
            $response = Markups::deleteMarkups($delete_markups_id);
            $message .= $response['message'] . '</br>';
        }

        $activityLog['request'] =  $request->all();
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] =  $response;
        ActiveLog::createBackendActiveLog($activityLog);

        if ($response['status'] == 1) {
            return redirect()->to($url)->with('success', $message);
        } else {
            return redirect()->to($url)->with('error', $response['message']);
        }
    }



    public function addDefaultMarkupData(Request $request)
    {

        $service_type = $request->service_type;
        $header['title'] = $service_type . " - Add";
        $header['heading'] = $service_type . " - Add";

        $getServiceTypeData = ServiceType::select('id')->where('name', $service_type)->first();
        $serviceTypeId = $getServiceTypeData->id ?? '';
        $getAgency = Agency::where('status', 'active')->get()->toArray();

        $activityLog['request'] =  $request->all();
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] =  [];
        ActiveLog::createBackendActiveLog($activityLog);

        return view('admin/markups/flight-markup/addDefaultMarkup')->with(['header' => $header, 'serviceTypes' => $service_type, 'serviceTypeId' => $serviceTypeId, 'getAgency' => $getAgency]);
    }
    public function storeDefaultMarkup(Request $request)
    {
        $requestData = $request->all();

        $rules = [
            'supplier' => 'required',
            'b2c_markup_type' => 'required',
            'b2c_markup' => 'required',
        ];

        $customMessages = [];

        $niceNames = array();

        $this->validate($request, $rules, $customMessages, $niceNames);
        $response = DefaultMarkup::createDefaultFlightMarkups($requestData);

        $service_type_id = $response['data']['service_type_id'];
        $getServiceTypeData = ServiceType::select('id', 'name')->where('id', $service_type_id)->first();
        $serviceTypeName = $getServiceTypeData->name ?? '';

        $activityLog['request'] =  $requestData;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] =  $response;
        ActiveLog::createBackendActiveLog($activityLog);

        if ($response['status'] == 1 && !empty($response['data'])) {
            return redirect()->to('/' . strtolower($serviceTypeName) . '-markups')->with('success', $response['message']);
        } else {
            return redirect()->to('/' . strtolower($serviceTypeName) . '-markups')->with('error', $response['message']);
        }
    }



    public function editDefaultMarkup(Request $request, $id)
    {
        $header['title'] = "Flight - Edit";
        $header['heading'] = "Flight - Edit";

        $defaultMarkup = DefaultMarkup::where('id', $id)->first();
        $filter = array(
            'id' => $id,
            'service_type_id' => $defaultMarkup['service_type_id'],
        );

        $response = DefaultMarkup::getDefaultMarkupsData($filter);
        $markupsDetail = $response['data'];
        $getServiceTypeData = ServiceType::select('id', 'code')->where('id', $defaultMarkup['service_type_id'])->first();
        $serviceTypeId = $getServiceTypeData['id'] ?? '';
        $serviceType = $getServiceTypeData['code'];
        $getSupplier = Suppliers::where('core_service_type_id', $serviceTypeId)->get()->toArray();

        $activityLog['request'] =  $filter;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] =  $response;
        ActiveLog::createBackendActiveLog($activityLog);

        if ($response['status'] == 1 && !empty($response['data'])) {
            return view('admin/markups/flight-markup/editDefaultMarkup')->with(['header' => $header, 'serviceTypeId' => $serviceTypeId, 'serviceType' => $serviceType, 'markupsDetail' => $markupsDetail, 'getSupplier' => $getSupplier]);
        } else {
            return redirect()->to('' . strtolower($serviceType) . '-markups.index')->with('error', $response['message']);
        }
    }

    public function updateDefaultMarkup(Request $request, $id)
    {
        $requestData = $request->only([
            'default_markups_id',
            'service_type_id',
            'supplier',
            'b2c_markup_type',
            'b2c_markup',
            'b2b_markup_type',
            'b2b_markup',
        ]);

        $rules = [];

        $customMessages = [];

        $niceNames = array();

        $this->validate($request, $rules, $customMessages, $niceNames);
        $response = DefaultMarkup::updateDefaultMarkups($requestData);

        $service_type_id = $response['data']['service_type_id'];
        $getServiceTypeData = ServiceType::select('id', 'name')->where('id', $service_type_id)->first();
        $serviceTypeName = $getServiceTypeData->name ?? '';

        $activityLog['request'] =  $requestData;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] =  $response;
        ActiveLog::createBackendActiveLog($activityLog);

        if ($response['status'] == 1 &&  !empty($response['data'])) {
            return redirect()->to('' . strtolower($request->serviceType) . '-markups')->with('success', $response['message']);
        } else {
            return redirect()->to('' . strtolower($request->serviceType) . '-markups')->with('error', $response['message']);
        }
    }

    /**
     * Remove the specified markups from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteDefaultMarkups(Request $request)
    {
        if (!hasPermission('Markups', 'delete')) {
            return view('admin/401');
        }
        $url = URL::previous();
        $markupsIDs = explode(',', $request->input('delete_markups_id'));

        $message = "";
        foreach ($markupsIDs as $delete_markups_id) {
            $response = DefaultMarkup::deleteDefaultMarkups($delete_markups_id);
            $message .= $response['message'] . '</br>';
        }

        $activityLog['request'] =  $request->all();
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] =  $response;
        ActiveLog::createBackendActiveLog($activityLog);

        if ($response['status'] == 1) {
            return redirect()->to($url)->with('success', $message);
        } else {
            return redirect()->to($url)->with('error', $response['message']);
        }
    }
}
