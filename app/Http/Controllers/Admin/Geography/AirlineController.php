<?php

/**
 * @package     Geography
 * @subpackage   Airline
 * @Author      Amar Technolabs Pvt. ltd(info@amarinfotech.com)
 * @Copyright(C) 2023 [Travel Portal].
 * @Version 1.0.0
 * module of the  Airline.
 */

namespace App\Http\Controllers\Admin\Geography;

use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Airline;
use App\Models\AirlineI18ns;
use App\Models\Country;
use App\Models\CountryI18ns;
use App\Traits\ActiveLog;
use Illuminate\Support\Facades\URL;
use App\Imports\AirlineImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Jobs\AirlineExcelImportJob;
use DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AirlineController extends Controller
{
    /**
     * Display a listing of the airline.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!hasPermission('AIRLINES', 'read')) {
            return view('admin/401');
        }
        $header['title'] = "Airline";
        $header['heading'] = "Airline";
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
            'airline_name' => (request()->input('airline_name') != NULL) ? request()->input('airline_name') : '',
            'airline_code' => (request()->input('airline_code') != NULL) ? request()->input('airline_code') : '',
            'status' => (request()->input('status') != NULL) ? request()->input('status') : '',
        );

        if (request()->input('airline_code') != NULL) {
            $filter['where'][] = ['airline_code', 'like', '%' . request()->input('airline_code') . '%'];
        }
        if (request()->input('airline_name') != NULL) {
            $filter['whereHas'][] = ['airline_name', 'like', '%' . request()->input('airline_name') . '%'];
        }

        if (request()->input('status') != NULL) {
            $filter['where'][] = ['status', '=', request()->input('status')];
        }

        $airlineDataList = Airline::getAirlineData($filter);
        $airlineDataCount = Airline::count();
        $getCountries = Country::with('countryCode')->where('status', 'active')->get();
        $airlineData = $airlineDataList['data'];

        $activityLog['request'] =  $filter;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] =  $airlineDataList;
        ActiveLog::createBackendActiveLog($activityLog);

        if ($airlineDataList['status'] == 1) {
            return view('admin/geography/airlines/index')->with(['header' => $header, 'getCountries' => $getCountries, 'airlineData' => $airlineData, 'airlineDataCount' => $airlineDataCount, 'queryStringConcat' => $queryStringConcat, 'appliedFilter' => $filter,  'i' => (request()->input('page', 1) - 1) * $filter['per_page']]);
        } else {
            return view('admin/geography/airlines/index')->with(['header' => $header, 'getCountries' => $getCountries, 'airlineData' => $airlineData, 'queryStringConcat' => $queryStringConcat, 'appliedFilter' => $filter, 'i' => (request()->input('page', 1) - 1) * $filter['per_page']]);
        }
    }

    /**
     * Show the form for creating a new airline.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!hasPermission('AIRLINES', 'create')) {
            return view('admin/401');
        }
        $header['title'] = @trans('Airlines - Add');
        $header['heading'] = @trans('airline.moduleHeading');
        $airlines = Airline::get()->toArray();

        $activityLog['request'] =  [];
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] =  [];
        ActiveLog::createBackendActiveLog($activityLog);

        return view('admin/geography/airlines/add')->with(['header' => $header, 'airlines' => $airlines]);
    }

    /**
     * Store a newly created airline in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!hasPermission('AIRLINES', 'create')) {
            return view('admin/401');
        }
        $requestData = $request->only([
            'airline_names', 'airline_code', 'airline_logo', 'status'
        ]);
        $rules = [];

        $customMessages = [];

        $niceNames = array();

        $this->validate($request, $rules, $customMessages, $niceNames);
        $response = Airline::createAirline($requestData);

        $activityLog['request'] =  $requestData;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] =  $response;
        ActiveLog::createBackendActiveLog($activityLog);

        if (!empty($response['data'])) {
            return redirect()->route('airlines.index')->with('success', $response['message']);
        } else {
            return redirect()->route('airlines.index')->with('error', $response['message']);
        }
    }

    /**
     * Show the form for editing the specified airline.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!hasPermission('AIRLINES', 'update')) {
            return view('admin/401');
        }
        $header['title'] = 'Airlines - Edit';
        $header['method'] = 'Edit';
        $filter = array(
            'id' => $id,
        );

        $response = Airline::getAirlineData($filter);
        $airlineDetail = $response['data'];

        $activityLog['request'] =  $filter;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] =  $response;
        ActiveLog::createBackendActiveLog($activityLog);

        if ($response['status'] == 1 && !empty($response['data'])) {
            return view('admin/geography/airlines/update')->with(['header' => $header, 'airlineDetail' => $airlineDetail]);
        } else {
            return redirect()->route('airlines.index')->with('error', $response['message']);
        }
    }

    /**
     * Update the specified airline in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!hasPermission('AIRLINE', 'update')) {
            return view('admin/401');
        }
        $url = $request->only('redirects_to');
        $requestData = $request->only(['airline_id', 'airline_names', 'airline_code', 'airline_logo', 'old_airline_logo', 'status']);

        $rules = [];
        $customMessages = [];

        $niceNames = array();

        $this->validate($request, $rules, $customMessages, $niceNames);


        $response = Airline::updateAirline($requestData);

        $activityLog['request'] =  $requestData;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] =  $response;
        ActiveLog::createBackendActiveLog($activityLog);

        if (!empty($response['data'])) {
            return redirect()->to($url['redirects_to'])->with('success', $response['message']);
        } else {
            return redirect()->to($url['redirects_to'])->with('error', $response['message']);
        }
    }

    /**
     * Remove the specified airline from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteAirline(Request $request)
    {
        if (!hasPermission('AIRLINES', 'delete')) {
            return view('admin/401');
        }
        $url = URL::previous();
        $AirlinesID = explode(',', $request->input('delete_airline_id'));

        $message = "";
        foreach ($AirlinesID as $delete_airline_id) {
            $response = Airline::deleteAirline($delete_airline_id);
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

    /**
     * Restore the specified airlines from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function restoreAirline(Request $request)
    {
        if (!hasPermission('AIRLINES', 'delete')) {
            return view('admin/401');
        }
        $url = URL::previous();
        $restore_airline_id = $request->input('restore_airline_id');
        $message = "";
        $response = Airline::restoreAirlines($restore_airline_id);
        $message .= $response['message'] . '</br>';

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

    /**
     * Check unique airline name in english.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function checkAirlineNameEnExist(Request $request)
    {
        $matchListData1 = [];

        $airlineName0 = request()->input('airline_names.0.airline_name');
        $airlineI18nId0 = request()->input('airline_names.0.airline_i18ns_id');

        $matchData = [];
        if ($airlineName0 && $airlineName0 != "") {
            if ($airlineI18nId0) {
                $matchData = AirlineI18ns::where('airline_name', $airlineName0)->where('id', '!=', $airlineI18nId0)->get()->toArray();
            } else {
                $matchData = AirlineI18ns::where('airline_name', $airlineName0)->get()->toArray();
            }
        }
        if (!empty($matchData)) {
            echo "false";
        } else {
            echo "true";
        }
    }

    /**
     * Check unique airline name in arabic.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkAirlineNameArExist(Request $request)
    {
        $matchListData1 = [];

        $airlineName1 = request()->input('airline_names.1.airline_name');
        $airlineI18nId1 = request()->input('airline_names.1.airline_i18ns_id');

        $matchData = [];
        if ($airlineName1 && $airlineName1 != "") {
            if ($airlineI18nId1) {
                $matchData = AirlineI18ns::where('airline_name', $airlineName1)->where('id', '!=', $airlineI18nId1)->get()->toArray();
            } else {
                $matchData = AirlineI18ns::where('airline_name', $airlineName1)->get()->toArray();
            }
        }
        if (!empty($matchData)) {
            echo "false";
        } else {
            echo "true";
        }
    }

    /**
     * Check unique airline code.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkAirlineCodeExist(Request $request)
    {
        $matchListData1 = [];
        if (request()->input('airline_code') && request()->input('airline_code') != "") {
            if (request()->input('airline_id')) {
                $matchListData1 = Airline::where('airline_code', request()->input('airline_code'))->where(
                    'id',
                    '!=',
                    request()->input('airline_id')
                )->get()->toArray();
            } else {

                $matchListData1 = Airline::where('airline_code', request()->input('airline_code'))->get()->toArray();
            }
        }
        if (!empty($matchListData1)) {
            echo "false";
        } else {
            echo "true";
        }
    }

    /**
     * download sample file to import airport list
     */
    public function downloadSample()
    {
        $currentDate = date('d-m-Y');

        $myFile = "product_sample_file/airline_sample.xls";
        return response()->download($myFile, 'airline_sample_' . $currentDate . '.xls');
    }
    /**
     * import file with data in airline list
     */
    public function importAirline(Request $request)
    {
        try {

            $request->validate([
                'file' => 'required|mimes:xls',
            ]);

            $file = $request->file('file');
            $path = $file->storeAs('public/import-docs', $file->getClientOriginalName());            

            $fullPath = storage_path("app/{$path}");
            chmod($fullPath, 0777);
            
            AirlineExcelImportJob::dispatchNow($fullPath);
            return back();
        
        } catch (ValidationException $e) {
            $failures = $e->failures();
            return back()->with('failures', $failures);
        }
    }
}
