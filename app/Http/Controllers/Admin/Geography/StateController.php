<?php

/**
 * @package     Geography
 * @subpackage   State
 * @Author      Amar Technolabs Pvt. ltd(info@amarinfotech.com)
 * @Copyright(C) 2023 [Travel Portal].
 * @Version 1.0.0
 * module of the  State.
 */

namespace App\Http\Controllers\Admin\Geography;

use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\State;
use App\Models\StateI18ns;
use App\Models\Country;
use App\Models\CountryI18ns;
use App\Models\City;
use App\Models\CityI18n;
use Illuminate\Support\Facades\URL;
use App\Imports\StateImport;
use App\Jobs\StateExcelImportJob;
use DB;
use App\Traits\ActiveLog;

class StateController extends Controller
{
    /**
     * Display a listing of the state.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!hasPermission('STATES','read')){
            return view('admin/401');
        }

        $header['title'] = "States";
        $header['heading'] = "States";
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
            'state_name' => (request()->input('state_name') != NULL) ? request()->input('state_name') : '',
            'iso_code' => (request()->input('iso_code') != NULL) ? request()->input('iso_code') : '',
            'country_code' => (request()->input('country_code') != NULL) ? request()->input('country_code') : '',
            'status' => (request()->input('status') != NULL) ? request()->input('status') : '',
        ); 

        if (request()->input('state_name') != NULL) {
            $filter['where'][] = ['state_name', 'like', '%' . request()->input('state_name') . '%'];
        }

        if (request()->input('iso_code') != NULL) {
            $filter['where'][] = ['iso_code', 'like', '%' . request()->input('iso_code') . '%'];
        }

        if (request()->input('country_code') != NULL) {
            $filter['where'][] = ['country_code', '=', request()->input('country_code')];
        }

        if (request()->input('status') != NULL) {
            $filter['where'][] = ['status', '=', request()->input('status')];
        }

        $stateDataList = State::getStateData($filter);
        $stateDataCount = State::count();
        $stateData = $stateDataList['data'];
        $getCountries = Country::with('countryCode')->where('status','active')->get();

        $activityLog['request'] = $filter;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $stateDataList;
        ActiveLog::createBackendActiveLog($activityLog);

        if ($stateDataList['status'] == 1) {
            return view('admin/geography/states/index')->with(['header' => $header, 'stateData' => $stateData,'stateDataCount' => $stateDataCount, 'getCountries' => $getCountries, 'queryStringConcat' => $queryStringConcat, 'appliedFilter' => $filter,  'i' => (request()->input('page', 1) - 1) * $filter['per_page']]);
        } else {
            return view('admin/geography/states/index')->with(['header' => $header, 'stateData' => $stateData,'getCountries' => $getCountries,'getCities'=>$getCities, 'queryStringConcat' => $queryStringConcat, 'appliedFilter' => $filter, 'i' => (request()->input('page', 1) - 1) * $filter['per_page']]);
        }
    }

    /**
     * Show the form for creating a new state.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!hasPermission('STATES','create')){
            return view('admin/401');
        }
        $header['title'] = @trans('States - Add');
        $header['heading'] = @trans('user.moduleHeading');
        $states = State::get()->toArray();

        $getCountry = Country::with('countryCode')
            ->orderBy('iso_code', 'asc')
            ->get();

            $activityLog['request'] = [];
            $activityLog['request_url'] =  request()->url();
            $activityLog['response'] = [];
            ActiveLog::createBackendActiveLog($activityLog);

        return view('admin/geography/states/add')->with(['header' => $header, 'states' => $states, 'getCountry' => $getCountry]);
    }

    /**
     * Store a newly created state in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!hasPermission('STATES','create')){
            return view('admin/401');
        }
        $requestData = $request->only([
            'state_names','iso_code', 'country_code', 'city_code' ,'latitude','longitude',  'status'
        ]);

        $rules = [];

        $customMessages = [];

        $niceNames = array();

        $this->validate($request, $rules, $customMessages, $niceNames);
        $response = State::createState($requestData);

        $activityLog['request'] = $requestData;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $response;
        ActiveLog::createBackendActiveLog($activityLog);

        if (!empty($response['data'])) {
            return redirect()->route('states.index')->with('success', $response['message']);
        } else {
            return redirect()->route('states.index')->with('error', $response['message']);
        }
    }

    /**
     * Show the form for editing the specified state.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(!hasPermission('STATES','update')){
            return view('admin/401');
        }
        $header['title'] = 'States - Edit';
        $header['method'] = 'Edit';
        $filter = array(
            'id' => $id,
        );
        
        $response = State::getStateData($filter);
        $stateDetail = $response['data'];

        $activityLog['request'] = $filter;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $response;
        ActiveLog::createBackendActiveLog($activityLog);
        
        if ($response['status'] == 1 && !empty($response['data'])) {
            return view('admin/geography/states/update')->with(['header' => $header, 'stateDetail' => $stateDetail]);
        } else {
            return redirect()->route('states.index')->with('error', $response['message']);
        }
    }

    /**
     * Update the specified state in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(!hasPermission('STATES','update')){
            return view('admin/401');
        }
        $url = $request->only('redirects_to');
        $requestData = $request->only([
            'state_id','state_names','iso_code','country_code','city_code','latitude','longitude','status'
        ]);

        $rules = [];
        $customMessages = [];
        $niceNames = array();
        $this->validate($request, $rules, $customMessages, $niceNames);
        $response = State::updateState($requestData);

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
     * Remove the specified state from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteState(Request $request)
    {
        if(!hasPermission('STATES','delete')){
            return view('admin/401');
        }
        $url = URL::previous();
        $statesIDs = explode(',', $request->input('delete_state_id'));

        $message = "";
        foreach ($statesIDs as $delete_state_id) {
            $response = State::deleteStates($delete_state_id);
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
     * Restore the specified state from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restoreState(Request $request)
    {
        if(!hasPermission('STATES','delete')){
            return view('admin/401');
        }
        $url = URL::previous();
        $restore_state_id = $request->input('restore_state_id');

        $message = "";
        $response = State::restoreStates($restore_state_id);
        $message .= $response['message'] . '</br>';

        if ($response['status'] == 1) {
            return redirect()->to($url)->with('success', $message);
        } else {
            return redirect()->to($url)->with('error', $response['message']);
        }
    }

    /**
     * get country name list.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function fetchCountryCode(Request $request)
    {
        $term = $request->input('term');
        $page = $request->input('page');
        return getCountryName($term, $page);   
    }

    /**
     * get city name list depend on country code.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getCities(Request $request, $country_code)
    {
        $term = $request->input('term');
        $page = $request->input('page');
        return getCityName($term, $page, $country_name);
    }

    /**
     * Check unique state name in english.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function checkStateNameEnExist(Request $request)
    {
        $stateName0 = request()->input('state_names.0.state_name');
        $stateI18nsId0 = request()->input('state_names.0.state_i18ns_id');

        $matchData = [];
        if ($stateName0 && $stateName0 != "") {
            if ($stateI18nsId0) {
                $matchData = StateI18ns::where('state_name', $stateName0)->where('id', '!=', $stateI18nsId0)->get()->toArray();
            } else {
                $matchData = StateI18ns::where('state_name', $stateName0)->get()->toArray();
            }
        }
        if (!empty($matchData)) {
            echo "false";
        } else {
            echo "true";
        }
    }

    /**
     * Check unique state name in arabic.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkStateNameArExist(Request $request)
    {
        $stateName1 = request()->input('state_names.1.state_name');
        $stateI18nsId1 = request()->input('state_names.1.state_i18ns_id');

        $matchData = [];
        if ($stateName1 && $stateName1 != "") {
            if ($stateI18nsId1) {
                $matchData = StateI18ns::where('state_name', $stateName1)->where('id', '!=', $stateI18nsId1)->get()->toArray();
            } else {
                $matchData = StateI18ns::where('state_name', $stateName1)->get()->toArray();
            }
        }
        if (!empty($matchData)) {
            echo "false";
        } else {
            echo "true";
        }
    }

    /**
     * Check unique ISO Code.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkISOCodeExist(Request $request)
    {
        $matchListData1 = [];
        if (request()->input('iso_code') && request()->input('iso_code') != "") {
            if (request()->input('state_id')) {
                $matchListData1 = State::withTrashed()->where('iso_code', request()->input('iso_code'))->where(
                    'id',
                    '!=',
                    request()->input('state_id')
                )->get()->toArray();
            } else {

                $matchListData1 = State::withTrashed()->where('iso_code', request()->input('iso_code'))->get()->toArray();
            }
        }
        if (!empty($matchListData1)) {
            echo "false";
        } else {
            echo "true";
        }
    }

    /**
     * Check unique state latitude.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkStateLatitudeExist(Request $request)
    {
        $matchListData = [];
        if (request()->input('latitude') && request()->input('latitude') != "") {
            if (request()->input('state_id')) {
                $matchListData = State::withTrashed()->where('latitude', request()->input('latitude'))->where('id', '!=', request()->input('state_id'))->get()->toArray();
            } else {
                $matchListData = State::withTrashed()->where('latitude', request()->input('latitude'))->get()->toArray();
            }
        }
        if (!empty($matchListData)) {
            echo "false";
        } else {
            echo "true";
        }
    }

    /**
     * Check unique state longitude.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkStateLongitudeExist(Request $request)
    {
        $matchListData = [];
        if (request()->input('longitude') && request()->input('longitude') != "") {
            if (request()->input('state_id')) {
                $matchListData = State::withTrashed()->where('longitude', request()->input('longitude'))->where('id', '!=', request()->input('state_id'))->get()->toArray();
            } else {
                $matchListData = State::withTrashed()->where('longitude', request()->input('longitude'))->get()->toArray();
            }
        }
        if (!empty($matchListData)) {
            echo "false";
        } else {
            echo "true";
        }
    }

    /**
     * Import state csv file into storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function importState(Request $request)
    {
        try {

            $request->validate([
                'file' => 'required|mimes:xls',
            ]);

            $file = $request->file('file');
            $path = $file->storeAs('public/import-docs', $file->getClientOriginalName());            

            $fullPath = storage_path("app/{$path}");
            chmod($fullPath, 0777);
            
            StateExcelImportJob::dispatchNow($fullPath);
            return back();
        
        } catch (ValidationException $e) {
            $failures = $e->failures();
            return back()->with('failures', $failures);
        }
    }
}
