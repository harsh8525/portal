<?php

/**
 * @package     Geography
 * @subpackage   Country
 * @Author      Amar Technolabs Pvt. ltd(info@amarinfotech.com)
 * @Copyright(C) 2023 [Travel Portal].
 * @Version 1.0.0
 * module of the  Country.
 */

namespace App\Http\Controllers\Admin\Geography;

use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Country;
use App\Models\CountryI18ns;
use Illuminate\Support\Facades\URL;
use App\Imports\CountryImport;
use App\Jobs\CountryExcelImportJob;
use DB;
use App\Jobs\ProcessCsv;
use Maatwebsite\Excel\Facades\Excel;

class CountryController extends Controller
{
    /**
     * Display a listing of the country.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!hasPermission('COUNTRIES','read')){
            return view('admin/401');
        }

        $header['title'] = "Countries";
        $header['heading'] = "Countries";
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
            'iso_code' => (request()->input('iso_code') != NULL) ? request()->input('iso_code') : '',
            'isd_code' => (request()->input('isd_code') != NULL) ? request()->input('isd_code') : '',
            'status' => (request()->input('status') != NULL) ? request()->input('status') : '',
        ); 

        if (request()->input('iso_code') != NULL) {
            $filter['where'][] = ['iso_code', 'like', '%' . request()->input('iso_code') . '%'];
        }
        if (request()->input('isd_code') != NULL) {
            $filter['where'][] = ['isd_code', 'like', '%' . request()->input('isd_code') . '%'];
        }
        
        if (request()->input('status') != NULL) {
            $filter['where'][] = ['status', '=', request()->input('status')];
        }

        $countryDataList = Country::getCountryData($filter);
        $countryDataCount = Country::count();
        $countryData = $countryDataList['data'];
        if ($countryDataList['status'] == 1) {
            return view('admin/geography/countries/index')->with(['header' => $header, 'countryData' => $countryData,'countryDataCount' => $countryDataCount, 'queryStringConcat' => $queryStringConcat, 'appliedFilter' => $filter,  'i' => (request()->input('page', 1) - 1) * $filter['per_page']]);
        } else {
            return view('admin/geography/countries/index')->with(['header' => $header, 'countryData' => $countryData, 'queryStringConcat' => $queryStringConcat, 'appliedFilter' => $filter, 'i' => (request()->input('page', 1) - 1) * $filter['per_page']]);
        }
    }

    /**
     * Show the form for creating a new country.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!hasPermission('COUNTRIES','create')){
            return view('admin/401');
        }
        $header['title'] = @trans('Countries - Add');
        $header['heading'] = @trans('user.moduleHeading');
        $countries = Country::get()->toArray();
        return view('admin/geography/countries/add')->with(['header' => $header, 'countries' => $countries]);
    }

    /**
     * Store a newly created country in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!hasPermission('COUNTRIES','create')){
            return view('admin/401');
        }
        $requestData = $request->only([
            'country_names','iso_code', 'isd_code', 'max_mobile_number_length',  'status'
        ]);

        $rules = [];

        $customMessages = [];

        $niceNames = array();

        $this->validate($request, $rules, $customMessages, $niceNames);
        $response = Country::createCountry($requestData);

        if (!empty($response['data'])) {
            return redirect()->route('countries.index')->with('success', $response['message']);
        } else {
            return redirect()->route('countries.index')->with('error', $response['message']);
        }
    }

    /**
     * Show the form for editing the specified country.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(!hasPermission('COUNTRIES','update')){
            return view('admin/401');
        }
        $header['title'] = 'Countries - Edit';
        $header['method'] = 'Edit';
        $filter = array(
            'id' => $id,
        );
        
        $response = Country::getCountryData($filter);
        $countryDetail = $response['data'];
        if ($response['status'] == 1 && !empty($response['data'])) {
            return view('admin/geography/countries/update')->with(['header' => $header, 'countryDetail' => $countryDetail]);
        } else {
            return redirect()->route('countries.index')->with('error', $response['message']);
        }
    }

    /**
     * Update the specified country in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(!hasPermission('COUNTRIES','update')){
            return view('admin/401');
        }
        $url = $request->only('redirects_to');
        $requestData = $request->only(['country_id', 'country_names','iso_code', 'isd_code', 'max_mobile_number_length',  'status']);

        $rules = [];
        $customMessages = [];

        $niceNames = array();

        $this->validate($request, $rules, $customMessages, $niceNames);


        $response = Country::updateCountry($requestData);
        if (!empty($response['data'])) {
            return redirect()->to($url['redirects_to'])->with('success', $response['message']);
        } else {
            return redirect()->to($url['redirects_to'])->with('error', $response['message']);
        }
    }

    /**
     * Remove the specified country from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteCountry(Request $request)
    {
        if(!hasPermission('COUNTRIES','delete')){
            return view('admin/401');
        }
        $url = URL::previous();
        $countriesIDs = explode(',', $request->input('delete_country_id'));

        $message = "";
        foreach ($countriesIDs as $delete_country_id) {
            $response = Country::deleteCountries($delete_country_id);
            $message .= $response['message'] . '</br>';
        }

        if ($response['status'] == 1) {
            return redirect()->to($url)->with('success', $message);
        } else {
            return redirect()->to($url)->with('error', $response['message']);
        }
    }

    /**
     * Check unique country name in english.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function checkCountryNameEnExist(Request $request)
    {
        $matchListData1 = [];

        $countryName0 = request()->input('country_names.0.country_name');
        $countryI18nsId0 = request()->input('country_names.0.country_i18ns_id');

        $matchData = [];
        if ($countryName0 && $countryName0 != "") {
            if ($countryI18nsId0) {
                $matchData = CountryI18ns::where('country_name', $countryName0)->where('language_code','en')->where('id', '!=', $countryI18nsId0)->get()->toArray();
            } else {
                $matchData = CountryI18ns::where('country_name', $countryName0)->where('language_code','en')->get()->toArray();
            }
        }
        if (!empty($matchData)) {
            echo "false";
        } else {
            echo "true";
        }
    }

     /**
     * Restore the specified country from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restoreCountry(Request $request)
    {
        if(!hasPermission('COUNTRIES','delete')){
            return view('admin/401');
        }
        $url = URL::previous();
        $restore_country_id = $request->input('restore_country_id');

        $message = "";
        $response = Country::restoreCountries($restore_country_id);
        $message .= $response['message'] . '</br>';

        if ($response['status'] == 1) {
            return redirect()->to($url)->with('success', $message);
        } else {
            return redirect()->to($url)->with('error', $response['message']);
        }
    }

    /**
     * Check unique country name in arabic.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkCountryNameArExist(Request $request)
    {
        $matchListData1 = [];

        $countryName1 = request()->input('country_names.1.country_name');
        $countryI18nsId1 = request()->input('country_names.1.country_i18ns_id');

        $matchData = [];
        if ($countryName1 && $countryName1 != "") {
            if ($countryI18nsId1) {
                $matchData = CountryI18ns::where('country_name', $countryName1)->where('language_code','ar')->where('id', '!=', $countryI18nsId1)->get()->toArray();
            } else {
                $matchData = CountryI18ns::where('country_name', $countryName1)->where('language_code','ar')->get()->toArray();
            }
        }
        if (!empty($matchData)) {
            echo "false";
        } else {
            echo "true";
        }
    }

    /**
     * Check unique ISO code.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkISOCodeExist(Request $request)
    {
        $matchListData1 = [];
        if (request()->input('iso_code') && request()->input('iso_code') != "") {
            if (request()->input('country_id')) {
                $matchListData1 = Country::withTrashed()->where('iso_code', request()->input('iso_code'))->where(
                    'id',
                    '!=',
                    request()->input('country_id')
                )->get()->toArray();
            } else {

                $matchListData1 = Country::withTrashed()->where('iso_code', request()->input('iso_code'))->get()->toArray();
            }
        }
        if (!empty($matchListData1)) {
            echo "false";
        } else {
            echo "true";
        }
    }

    /**
     * Check unique ISD Code.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkISDCodeExist(Request $request)
    {
        $matchListData1 = [];
        if (request()->input('isd_code') && request()->input('isd_code') != "") {
            if (request()->input('country_id')) {
                $isd_code = request()->input('isd_code');
                if (strpos($isd_code, '+') === false) {
                    $isd_code = '+' . $isd_code;
                }
                $matchListData1 = Country::withTrashed()->where('isd_code', $isd_code)->where(
                    'id',
                    '!=',
                    request()->input('country_id')
                )->get()->toArray();
            } else {
                $isd_code = request()->input('isd_code');
                if (strpos($isd_code, '+') === false) {
                    $isd_code = '+' . $isd_code;
                }
                $matchListData1 = Country::withTrashed()->where('isd_code', $isd_code)->get()->toArray();
            }
        }
        if (!empty($matchListData1)) {
            echo "false";
        } else {
            echo "true";
        }
    }

    /**
     * Import country csv file into storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function importCountryCsv(Request $request)
    {
        try {

            $request->validate([
                'file' => 'required|mimes:xls',
            ]);

            $file = $request->file('file');
            $path = $file->storeAs('public/import-docs', $file->getClientOriginalName());            

            $fullPath = storage_path("app/{$path}");

            chmod($fullPath, 0777);
            
            CountryExcelImportJob::dispatchNow($fullPath);
            return back();
        
        } catch (ValidationException $e) {
            $failures = $e->failures();
            return back()->with('failures', $failures);
        }
    }
}
