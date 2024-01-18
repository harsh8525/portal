<?php

/**
 * @package     Geography
 * @subpackage   Airports
 * @Author      Amar Technolabs Pvt. ltd(info@amarinfotech.com)
 * @Copyright(C) 2023 [Travel Portal].
 * @Version 1.0.0
 * module of the  Airport.
 */

namespace App\Http\Controllers\Admin\Geography;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\Airport;
use App\Models\AirportI18ns;
use App\Models\Setting;
use App\Models\Country;
use App\Imports\AirportImport;
use App\Jobs\AirportExcelImportJob;
use App\Traits\ActiveLog;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\City;
use DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AirportsController extends Controller
{
    /**
     * Display a listing of the airport.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!hasPermission('AIRPORTS', 'read')) {
            return view('admin/401');
        }

        $header['title'] = "Airports";
        $header['heading'] = "Airports";
        $queryStringConcat = '?';
        if (isset($_GET['per_page'])) {
            $queryStringConcat .= ($queryStringConcat == '') ? '?per_page=' . $_GET['per_page'] : '&per_page=' . $_GET['per_page'];
        }
        if (isset($_GET['page'])) {
            $queryStringConcat .= ($queryStringConcat == '') ? '?page=' . $_GET['page'] : '&page=' . $_GET['page'];
        }

        $filter = array(
            'per_page' => (request()->input('per_page') != NULL) ? request()->input('per_page') : Setting::where('config_key', 'general|setting|pagePerAdminRecords')->get('value')[0]['value'],
            'order_by' => (request()->input('order_by') != NULL) ? request()->input('order_by') : 'created_at',
            'sorting' => (request()->input('sorting') != NULL) ? request()->input('sorting') : 'desc',
            'airport_name' => (request()->input('airport_name') != NULL) ? request()->input('airport_name') : '',
            'iata_code' => (request()->input('iata_code') != NULL) ? request()->input('iata_code') : '',
            'country_code' => (request()->input('country_code') != NULL) ? request()->input('country_code') : '',
            'city_code' => (request()->input('city_code') != NULL) ? request()->input('city_code') : '',
            'status' => (request()->input('status') != NULL) ? request()->input('status') : '',
        );

        if (request()->input('airport_name') != NULL) {
            $filter['where'][] = ['airport_name', 'like', '%' . request()->input('airport_name') . '%'];
        }
        if (request()->input('iata_code') != NULL) {
            $filter['where'][] = ['iata_code', 'like', '%' . request()->input('iata_code') . '%'];
        }

        if (request()->input('country_code') != NULL) {
            $filter['where'][] = ['country_code', '=', request()->input('country_code')];
        }

        if (request()->input('city_code') != NULL) {
            $filter['where'][] = ['city_code', '=', request()->input('city_code')];
        }

        if (request()->input('status') != NULL) {
            $filter['where'][] = ['status', '=', request()->input('status')];
        }

        $airPortDataList = Airport::getAirPortData($filter);
        $airPortDataCount = Airport::count();
        $airPortData = $airPortDataList['data'];
        $getCountries = Country::with('countryCode')->where('status', 'active')->get();
        $getCities = City::with('cityCode')->where('status', 'active')->get();

        $activityLog['request'] =  $filter;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] =  $airPortDataList;
        ActiveLog::createBackendActiveLog($activityLog);

        if ($airPortDataList['status'] == 1) {
            return view('admin/geography/airports/index')->with(['header' => $header, 'airPortData' => $airPortData, 'getCountries' => $getCountries, 'getCities' => $getCities, 'airPortDataCount' => $airPortDataCount, 'queryStringConcat' => $queryStringConcat, 'appliedFilter' => $filter,  'i' => (request()->input('page', 1) - 1) * $filter['per_page']]);
        } else {
            return view('admin/geography/airports/index')->with(['header' => $header, 'airPortData' => $airPortData, 'getCountries' => $getCountries, 'getCities' => $getCities, 'queryStringConcat' => $queryStringConcat, 'appliedFilter' => $filter, 'i' => (request()->input('page', 1) - 1) * $filter['per_page']]);
        }
    }

    /**
     * Show the form for creating a new airport.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!hasPermission('AIRPORTS', 'create')) {
            return view('admin/401');
        }
        $header['title'] = @trans('Airports - Add');
        $header['heading'] = @trans('user.moduleHeading');
        $customers = Airport::get()->toArray();
        $getCountry = Country::with('countryCode')
            ->orderBy('iso_code', 'asc')
            ->get();

        $getCity = City::with('cityCode')
            ->orderBy('iso_code', 'asc')
            ->get();

        $activityLog['request'] =  [];
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] =  [];
        ActiveLog::createBackendActiveLog($activityLog);

        return view('admin/geography/airports/add')->with(['header' => $header, 'customers' => $customers, 'getCountry' => $getCountry, 'getCity' => $getCity]);
    }

    /**
     * Store a newly created airport in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!hasPermission('AIRPORTS', 'create')) {
            return view('admin/401');
        }
        $requestData = $request->only([
            'airport_names', 'iata_code', 'country_code', 'city_code', 'latitude', 'longitude', 'status'
        ]);
        $rules = [];

        $customMessages = [];

        $niceNames = array();

        $this->validate($request, $rules, $customMessages, $niceNames);
        $response = Airport::createAirport($requestData);

        $activityLog['request'] =  $requestData;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $response;
        ActiveLog::createBackendActiveLog($activityLog);

        if (!empty($response['data'])) {
            return redirect()->route('airports.index')->with('success', $response['message']);
        } else {
            return redirect()->route('airports.index')->with('error', $response['message']);
        }
    }

    /**
     * Show the form for editing the specified airport.
     *
     * @param  \App\Models\Airports  $airports
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!hasPermission('AIRPORTS', 'update')) {
            return view('admin/401');
        }
        $header['title'] = 'Airports - Edit';
        $header['method'] = 'Edit';
        $filter = array(
            'id' => $id,
        );
        $response = Airport::getAirPortData($filter);
        $airportDetail = $response['data'];
        $checkCountrySoftDeletedData = Country::onlyTrashed()->with('countryCode')->where('iso_code', $airportDetail['country_code'])->where('status', 'active')->get()->first();
        if ($checkCountrySoftDeletedData) {
            $getCities = City::with('cityCode')->where('country_code', $checkCountrySoftDeletedData['country_code'])->where('status', 'active')->get()->toArray();
        } else {
            $getCities = City::with('cityCode')->where('country_code', $airportDetail['country_code'])->where('status', 'active')->get()->toArray();
        }

        $activityLog['request'] =  $filter;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $response;
        ActiveLog::createBackendActiveLog($activityLog);

        if ($response['status'] == 1 && !empty($response['data'])) {
            return view('admin/geography/airports/update')->with(['header' => $header, 'airportDetail' => $airportDetail, 'getCities' => $getCities]);
        } else {
            return redirect()->route('airports.index')->with('error', $response['message']);
        }
    }

    /**
     * Update the specified airport in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Airports  $airports
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!hasPermission('AIRPORTS', 'update')) {
            return view('admin/401');
        }
        $url = $request->only('redirects_to');
        $requestData = $request->only(['airport_id', 'airport_names', 'iata_code', 'country_code', 'city_code', 'latitude', 'longitude', 'status']);

        $rules = [];
        $customMessages = [];

        $niceNames = array();

        $this->validate($request, $rules, $customMessages, $niceNames);


        $response = Airport::updateAirport($requestData);

        $activityLog['request'] =  $requestData;
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
     * Restore the specified airport from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteAirport(Request $request)
    {
        if (!hasPermission('AIRPORTS', 'delete')) {
            return view('admin/401');
        }
        $url = URL::previous();
        $airportsIDs = explode(',', $request->input('delete_airport_id'));

        $message = "";
        foreach ($airportsIDs as $delete_airport_id) {
            $response = Airport::deleteAirports($delete_airport_id);
            $message .= $response['message'] . '</br>';
        }

        $activityLog['request'] =  $request->all();
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
     * Restore the specified airports from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function restoreAirport(Request $request)
    {
        if (!hasPermission('AIRPORTS', 'delete')) {
            return view('admin/401');
        }
        $url = URL::previous();
        $restore_airport_id = $request->input('restore_airport_id');
        $message = "";
        $response = Airport::restoreAirports($restore_airport_id);
        $message .= $response['message'] . '</br>';

        $activityLog['request'] =  $request->all();
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
     * Check unique airport code.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkAirportCodeExist(Request $request)
    {
        $matchListData = [];
        if (request()->input('iata_code') && request()->input('iata_code') != "") {
            if (request()->input('airport_id')) {
                $matchListData = Airport::withTrashed()->where('iata_code', request()->input('iata_code'))->where('id', '!=', request()->input('airport_id'))->get()->toArray();
            } else {

                $matchListData = Airport::withTrashed()->where('iata_code', request()->input('iata_code'))->get()->toArray();
            }
        }
        if (!empty($matchListData)) {
            echo "false";
        } else {
            echo "true";
        }
    }

    /**
     * Check unique airport latitude.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkAirportLatitudeExist(Request $request)
    {
        $matchListData = [];
        if (request()->input('latitude') && request()->input('latitude') != "") {
            if (request()->input('airport_id')) {
                $matchListData = Airport::withTrashed()->where('latitude', request()->input('latitude'))->where('id', '!=', request()->input('airport_id'))->get()->toArray();
            } else {
                $matchListData = Airport::withTrashed()->where('latitude', request()->input('latitude'))->get()->toArray();
            }
        }
        if (!empty($matchListData)) {
            echo "false";
        } else {
            echo "true";
        }
    }

    /**
     * Check unique airport longitude.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkAirportLongitudeExist(Request $request)
    {
        $matchListData = [];
        if (request()->input('longitude') && request()->input('longitude') != "") {
            if (request()->input('airport_id')) {
                $matchListData = Airport::withTrashed()->where('longitude', request()->input('longitude'))->where('id', '!=', request()->input('airport_id'))->get()->toArray();
            } else {
                $matchListData = Airport::withTrashed()->where('longitude', request()->input('longitude'))->get()->toArray();
            }
        }
        if (!empty($matchListData)) {
            echo "false";
        } else {
            echo "true";
        }
    }

    /**
     * get country name list.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function fetchCountryName(Request $request)
    {
        $requestData = $request->all();
        $term = $request->input('term');
        $page = $request->input('page');
        return getCountryName($term, $page,$requestData);
    }

    /**
     * get city name depond on country list.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function fetchCityName(Request $request, $country_name)
    {
        $term = $request->input('term');
        $page = $request->input('page');
        return getCityName($term, $page, $country_name);
    }

    /**
     * get state name depond on country list.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function fetchStateName(Request $request, $city_id)
    {
        $term = $request->input('term');
        $page = $request->input('page');
        return getStateName($term, $page, $city_id);
    }

    /**
     * get city name list.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function fetchOnlyCityName(Request $request)
    {
        $requestData = $request->all();
        $term = $request->input('term');
        $page = $request->input('page');
        return getOnlyCityName($term, $page, $requestData);
    }

    /**
     * Check unique airport name in english.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function checkAirportNameEnExist(Request $request)
    {
        $airportName0 = request()->input('airport_names.0.airport_name');
        $airportI18nsId0 = request()->input('airport_names.0.airport_i18ns_id');

        $matchData = [];
        if ($airportName0 && $airportName0 != "") {
            if ($airportI18nsId0) {
                $matchData = AirportI18ns::where('airport_name', $airportName0)->where('id', '!=', $airportI18nsId0)->get()->toArray();
            } else {
                $matchData = AirportI18ns::where('airport_name', $airportName0)->get()->toArray();
            }
        }
        if (!empty($matchData)) {
            echo "false";
        } else {
            echo "true";
        }
    }

    /**
     * Check unique airport name in arabic.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkAirportNameArExist(Request $request)
    {
        $airportName1 = request()->input('airport_names.1.airport_name');
        $airportI18nsId1 = request()->input('airport_names.1.airport_i18ns_id');

        $matchData = [];
        if ($airportName1 && $airportName1 != "") {
            if ($airportI18nsId1) {
                $matchData = AirportI18ns::where('airport_name', $airportName1)->where('id', '!=', $airportI18nsId1)->get()->toArray();
            } else {
                $matchData = AirportI18ns::where('airport_name', $airportName1)->get()->toArray();
            }
        }
        if (!empty($matchData)) {
            echo "false";
        } else {
            echo "true";
        }
    }

    /**
     * import file with data in airport list
     */
    public function importAirport(Request $request)
    {  
        try {

            $request->validate([
                'file' => 'required|mimes:xls',
            ]);

            $file = $request->file('file');
            $path = $file->storeAs('public/import-docs', $file->getClientOriginalName());            

            $fullPath = storage_path("app/{$path}");
            chmod($fullPath, 0777);
            
            AirportExcelImportJob::dispatchNow($fullPath);
            return back();
        
        } catch (ValidationException $e) {
            $failures = $e->failures();
            return back()->with('failures', $failures);
        }
        
    }
}
