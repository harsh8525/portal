<?php

/**
 * @package     B2C
 * @subpackage   FeatureFlight
 * @Author      Amar Technolabs Pvt. ltd(info@amarinfotech.com)
 * @Copyright(C) 2023 [Travel Portal].
 * @Version 1.0.0
 * module of the  FeatureFlight.
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Models\Setting;
use App\Models\FeatureFlight;
use App\Traits\ActiveLog; 
use App\Models\Airport;
use App\Models\Airline;
use App\Models\Currency;

class FeatureFlightController extends Controller
{
    /**
     * Display a listing of the feature flight.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $header['title'] = "Featured Flights";
        $header['heading'] = "Featured Flights";
        $queryStringConcat = '?';

        if (isset($_GET['per_page'])) {
            $queryStringConcat .= ($queryStringConcat == '') ? '?per_page=' . $_GET['per_page'] : '&per_page=' . $_GET['per_page'] . $_SERVER['QUERY_STRING'];
        }
        if (isset($_GET['page'])) {
            $queryStringConcat .= ($queryStringConcat == '') ? '?page=' . $_GET['page'] : '&page=' . $_GET['page'] . $_SERVER['QUERY_STRING'];
        }

        $filter = array(
            'per_page' => (request()->input('per_page') != NULL) ? request()->input('per_page') : Setting::where('config_key', 'general|setting|pagePerAdminRecords')->get('value')[0]['value'],
            'order_by' => (request()->input('order_by') != NULL) ? request()->input('order_by') : 'created_at',
            'sorting' => (request()->input('sorting') != NULL) ? request()->input('sorting') : 'desc',
            'airline_code' => (request()->input('airline_code') != NULL) ? request()->input('airline_code') : '',
            'from_airport_code' => (request()->input('from_airport_code') != NULL) ? request()->input('from_airport_code') : '',
            'to_airport_code' => (request()->input('to_airport_code') != NULL) ? request()->input('to_airport_code') : '',
            'airport_name' => (request()->input('airport_name') != NULL) ? request()->input('airport_name') : '',
            'status' => (request()->input('status') != NULL) ? request()->input('status') : '',
        );

        if (request()->input('airline_code') != NULL) {
            $filter['where'][] = ['airline_code', '=', request()->input('airline_code')];
        }

        if (request()->input('from_airport_code') != NULL) {
            $filter['where'][] = ['from_airport_code', '=', request()->input('from_airport_code')];
        }

        if (request()->input('to_airport_code') != NULL) {
            $filter['where'][] = ['to_airport_code', '=', request()->input('to_airport_code')];
        }

        if (request()->input('status') != NULL) {
            $filter['where'][] = ['status', '=', request()->input('status')];
        }

        $FeatureFlightDataList = FeatureFlight::getFeatureFlightType($filter);
        $featureFlighTypeDataCount = FeatureFlight::count();
        $getAirportlist = Airport::with('airportName')->get();
        $getAirlinelist = Airline::with('airlineCodeName')->get();
        $featureFlighTypeData = $FeatureFlightDataList['data'];

        $activityLog['request'] = $filter;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $FeatureFlightDataList;
        ActiveLog::createBackendActiveLog($activityLog);

        if ($FeatureFlightDataList['status'] == 1) {
            return view('admin/feature-flight/index')->with(['header' => $header, 'featureFlighTypeDataCount' => $featureFlighTypeDataCount, 'featureFlighTypeData' => $featureFlighTypeData, 'getAirportlist' => $getAirportlist, 'getAirlinelist' => $getAirlinelist, 'queryStringConcat' => $queryStringConcat, 'appliedFilter' => $filter, 'i' => (request()->input('page', 1) - 1) * $filter['per_page']]);
        } else {
            return view('admin/feature-flight/index')->with(['error' => $FeatureFlightDataList['message'], 'header' => $header, 'featureFlighTypeDataCount' => $featureFlighTypeDataCount, 'featureFlighTypeData' => $featureFlighTypeData, 'getAirportlist' => $getAirportlist, 'getAirlinelist' => $getAirlinelist, 'featureFlighTypeData' => $featureFlighTypeData, 'queryStringConcat' => $queryStringConcat, 'appliedFilter' => $filter, 'i' => (request()->input('page', 1) - 1) * $filter['per_page']]);
        }
    }

    /**
     * Show the form for creating a new feature flight.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $header['title'] = "Featured Flights - Add";
        $header['heading'] = "Featured Flights- Add";
        $getAirportlist = Airport::with('airportName')->get();
        $getAirlinelist = Airline::get();
        $getDefaultCurrency = Currency::where('is_default', '1')->get()->toArray();

        $activityLog['request'] = [];
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = [];
        ActiveLog::createBackendActiveLog($activityLog);

        return view('admin/feature-flight/add')->with(['header' => $header, 'getAirportlist' => $getAirportlist, 'getAirlinelist' => $getAirlinelist, 'getDefaultCurrency' => $getDefaultCurrency]);
    }

    /**
     * Store a newly created feature flight in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $requestData = $request->only(['airline_code', 'from_airport_code', 'to_airport_code', 'price', 'location_image', 'status']);

        $rules = [
            'airline_code' => 'required',
            'from_airport_code' => 'required',
            'to_airport_code' => 'required',
            'price' => 'required',
            'status' => 'required'
        ];

        $customMessages = [];

        $niceNames = array();

        $this->validate($request, $rules, $customMessages, $niceNames);

        $response = FeatureFlight::createFeatureFlightType($requestData);

        $activityLog['request'] = $requestData;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $response;
        ActiveLog::createBackendActiveLog($activityLog);

        if (!empty($response['data'])) {
            return redirect()->route('feature-flight.index')->with('success', $response['message']);
        } else {
            return redirect()->route('feature-flight.index')->with('error', $response['message']);
        }
    }

    /**
     * Display the specified feature flight.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $header['title'] = 'Featured Flights - View';
        $filter = array(
            'id' => $id
        );
        $response = FeatureFlight::getFeatureFlightType($filter);
        $getDefaultCurrency = Currency::where('is_default', '1')->get()->toArray();
        $featureflightDetail = $response['data'];

        $activityLog['request'] = $filter;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $response;
        ActiveLog::createBackendActiveLog($activityLog);

        if ($response['status'] == 1 && !empty($response['data'])) {
            return view('admin/feature-flight/view')->with(['header' => $header, 'featureflightDetail' => $featureflightDetail, 'getDefaultCurrency' => $getDefaultCurrency]);
        } else {
            return redirect()->route('feature-flight.index')->with('error', $response['message']);
        }
    }

    /**
     * Show the form for editing the specified feature flight.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $header['title'] = 'Featured Flights - Edit';
        $header['method'] = 'Edit';

        $filter = array(
            'id' => $id,
        );
        $response = FeatureFlight::getFeatureFlightType($filter);
        $getAirportlist = Airport::with('airportName')->get();
        $getAirlinelist = Airline::with('airlineCodeName')->get();
        $getDefaultCurrency = Currency::where('is_default', '1')->get()->toArray();
        $featureflightDetail = $response['data'];

        $activityLog['request'] = $filter;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $response;
        ActiveLog::createBackendActiveLog($activityLog);

        if ($response['status'] == 1 && !empty($response['data'])) {
            return view('admin/feature-flight/update')->with(['header' => $header, 'featureflightDetail' => $featureflightDetail, 'getAirportlist' => $getAirportlist, 'getAirlinelist' => $getAirlinelist, 'getDefaultCurrency' => $getDefaultCurrency]);
        } else {
            return redirect()->route('feature-flight.index')->with('error', $response['message']);
        }
    }

    /**
     * Update the specified feature flight in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $url = $request->only('redirects_to');
        $requestData = $request->only(['feature_flight_id', 'airline_code', 'from_airport_code', 'to_airport_code', 'location_image', 'price', 'old_image', 'status']);

        $rules = [];

        $customMessages = [];

        $niceNames = array();

        $this->validate($request, $rules, $customMessages, $niceNames);
        $response = FeatureFlight::updateFeatureFlightType($requestData);

        $activityLog['request'] = $requestData;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $response;
        ActiveLog::createBackendActiveLog($activityLog);

        if (!empty($response['data'])) {
            return redirect()->to($url['redirects_to'])->with('success', $response['message']);
        } else {
            return redirect()->to($url['redirects_to'])->with('error', $response['message']);
        }
    }

    /**
     * Remove the specified feature flight from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteFeatureFlight(Request $request)
    {
        $url = URL::previous();
        $featuresIDs = explode(',', $request->input('feature_flight_id'));

        $message = "";
        foreach ($featuresIDs as $feature_flight_id) {
            $response = FeatureFlight::deleteFeatureFlight($feature_flight_id);
            $message .= $response['message'] . '</br>';
        }

        $activityLog['request'] = $request->all();
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $response;
        ActiveLog::createBackendActiveLog($activityLog);

        if ($response['status'] == 1) {
            return redirect()->to($url)->with('success', $message);
        } else {
            return redirect()->to($url)->with('error', $response['message']);
        }
    }

    /**
     * get airline code from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function fetchAirlineCode(Request $request)
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
            $data[] = ['airline_code' => $airline['airline_code'], 'airname' => $airname];
        }

        return response()->json($data);
    }

    /**
     * get airport list.
     *
     * @param  string  $term
     * @param int $page
     * @return \Illuminate\Http\Response
     */
    function getAirportName(Request $request)
    {

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
            $data[] = ['iata_code' => $airport['iata_code'], 'airname' => $airname];
        }

        return response()->json($data);
    }
}
