<?php

/**
 * @package     Geography
 * @subpackage   City
 * @Author      Amar Technolabs Pvt. ltd(info@amarinfotech.com)
 * @Copyright(C) 2023 [Travel Portal].
 * @Version 1.0.0
 * module of the  City.
 */

namespace App\Http\Controllers\Admin\Geography;

use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\City;
use App\Models\CityI18n;
use App\Models\Country;
use App\Models\CountryI18ns;
use Illuminate\Support\Facades\URL;
use App\Imports\CityImport;
use App\Jobs\CityExcelImportJob;
use DB;

class CityController extends Controller
{
    /**
     * Display a listing of the city.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!hasPermission('CITY', 'read')) {
            return view('admin/401');
        }

        $header['title'] = "Cities";
        $header['heading'] = "Cities";
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
            'city_name' => (request()->input('city_name') != NULL) ? request()->input('city_name') : '',
            'iso_code' => (request()->input('iso_code') != NULL) ? request()->input('iso_code') : '',
            'country_code' => (request()->input('country_code') != NULL) ? request()->input('country_code') : '',
            'status' => (request()->input('status') != NULL) ? request()->input('status') : '',
        );

        if (request()->input('iso_code') != NULL) {
            $filter['where'][] = ['iso_code', 'like', '%' . request()->input('iso_code') . '%'];
        }
        if (request()->input('city_name') != NULL) {
            $filter['whereHas'][] = ['city_name', 'like', '%' . request()->input('city_name') . '%'];
        }
        if (request()->input('country_code') != NULL) {
            $filter['where'][] = ['country_code', '=', request()->input('country_code')];
        }

        if (request()->input('status') != NULL) {
            $filter['where'][] = ['status', '=', request()->input('status')];
        }

        $cityDataList = City::getCityData($filter);
        $cityDataCount = City::count();
        $getCountries = Country::with('countryCode')->where('status','active')->get();
        $cityData = $cityDataList['data'];
        if ($cityDataList['status'] == 1) {
            return view('admin/geography/cities/index')->with(['header' => $header, 'getCountries' => $getCountries, 'cityData' => $cityData, 'cityDataCount' => $cityDataCount, 'queryStringConcat' => $queryStringConcat, 'appliedFilter' => $filter,  'i' => (request()->input('page', 1) - 1) * $filter['per_page']]);
        } else {
            return view('admin/geography/cities/index')->with(['header' => $header, 'getCountries' => $getCountries, 'cityData' => $cityData, 'queryStringConcat' => $queryStringConcat, 'appliedFilter' => $filter, 'i' => (request()->input('page', 1) - 1) * $filter['per_page']]);
        }
    }

    /**
     * Show the form for creating a new city.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!hasPermission('CITIES', 'create')) {
            return view('admin/401');
        }
        $header['title'] = @trans('Cities - Add');
        $header['heading'] = @trans('city.moduleHeading');
        $cities = City::get()->toArray();
        $countries = Country::get()->toArray();
        return view('admin/geography/cities/add')->with(['header' => $header, 'cities' => $cities, 'countries' => $countries]);
    }

    /**
     * Store a newly created city in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!hasPermission('CITIES', 'create')) {
            return view('admin/401');
        }
        $requestData = $request->only([
            'city_names', 'iso_code', 'country_code', 'latitude', 'longitude',  'status'
        ]);

        $rules = [];

        $customMessages = [];

        $niceNames = array();

        $this->validate($request, $rules, $customMessages, $niceNames);
        $response = City::createCity($requestData);

        if (!empty($response['data'])) {
            return redirect()->route('cities.index')->with('success', $response['message']);
        } else {
            return redirect()->route('cities.index')->with('error', $response['message']);
        }
    }

    /**
     * Show the form for editing the specified city.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!hasPermission('CITIES', 'update')) {
            return view('admin/401');
        }
        $header['title'] = 'Cities - Edit';
        $header['method'] = 'Edit';
        $filter = array(
            'id' => $id,
        );
        
        $response = City::getCityData($filter);
        $cityDetail = $response['data'];
        $countryId = Country::with('countryCode')->where('iso_code',$cityDetail['country_code'])->value('id');
        $filter1 = array(
            'id' => $countryId,
        );
        $countries = Country::getCountryData($filter1);
        $countryDetail = $countries['data'];
        if ($response['status'] == 1 && !empty($response['data'])) {
            return view('admin/geography/cities/update')->with(['header' => $header, 'cityDetail' => $cityDetail, 'countryDetail' => $countryDetail]);
        } else {
            return redirect()->route('cities.index')->with('error', $response['message']);
        }
    }

    /**
     * Update the specified city in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!hasPermission('CITIES', 'update')) {
            return view('admin/401');
        }
        $url = $request->only('redirects_to');
        $requestData = $request->only(['city_id', 'city_names', 'iso_code', 'country_code', 'latitude', 'longitude',  'status']);

        $rules = [];
        $customMessages = [];

        $niceNames = array();

        $this->validate($request, $rules, $customMessages, $niceNames);


        $response = City::updateCity($requestData);
        if (!empty($response['data'])) {
            return redirect()->to($url['redirects_to'])->with('success', $response['message']);
        } else {
            return redirect()->to($url['redirects_to'])->with('error', $response['message']);
        }
    }

    /**
     * Remove the specified city from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteCity(Request $request)
    {
        if (!hasPermission('CITIS', 'delete')) {
            return view('admin/401');
        }
        $url = URL::previous();
        $citiesID = explode(',', $request->input('delete_city_id'));

        $message = "";
        foreach ($citiesID as $delete_city_id) {
            $response = City::deleteCities($delete_city_id);
            $message .= $response['message'] . '</br>';
        }

        if ($response['status'] == 1) {
            return redirect()->to($url)->with('success', $message);
        } else {
            return redirect()->to($url)->with('error', $response['message']);
        }
    }

    /**
     * Restore the specified city from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restoreCity(Request $request)
    {
        if(!hasPermission('CITIES','delete')){
            return view('admin/401');
        }
        $url = URL::previous();
        $restore_city_id = $request->input('restore_city_id');

        $message = "";
        $response = City::restoreCities($restore_city_id);
        $message .= $response['message'] . '</br>';

        if ($response['status'] == 1) {
            return redirect()->to($url)->with('success', $message);
        } else {
            return redirect()->to($url)->with('error', $response['message']);
        }
    }

    /**
     * Check unique city name in english.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkCityNameEnExist(Request $request)
    {
        $matchListData1 = [];

        $cityName0 = request()->input('city_names.0.city_name');
        $cityI18nId0 = request()->input('city_names.0.city_i18ns_id');

        $matchData = [];
        if ($cityName0 && $cityName0 != "") {
            if ($cityI18nId0) {
                $matchData = CityI18n::where('city_name', $cityName0)->where('id', '!=', $cityI18nId0)->get()->toArray();
            } else {
                $matchData = CityI18n::where('city_name', $cityName0)->get()->toArray();
            }
        }
        if (!empty($matchData)) {
            echo "false";
        } else {
            echo "true";
        }
    }

    /**
     * Check unique city name in arabic.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkCityNameArExist(Request $request)
    {
        $matchListData1 = [];

        $cityName1 = request()->input('city_names.1.city_name');
        $cityI18nId1 = request()->input('city_names.1.city_i18ns_id');

        $matchData = [];
        if ($cityName1 && $cityName1 != "") {
            if ($cityI18nId1) {
                $matchData = CityI18n::where('city_name', $cityName1)->where('id', '!=', $cityI18nId1)->get()->toArray();
            } else {
                $matchData = CityI18n::where('city_name', $cityName1)->get()->toArray();
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
            if (request()->input('city_id')) {
                $matchListData1 = City::withTrashed()->where('iso_code', request()->input('iso_code'))->where(
                    'id',
                    '!=',
                    request()->input('city_id')
                )->get()->toArray();
            } else {

                $matchListData1 = City::withTrashed()->where('iso_code', request()->input('iso_code'))->get()->toArray();
            }
        }
        if (!empty($matchListData1)) {
            echo "false";
        } else {
            echo "true";
        }
    }

     /**
     * Check unique city latitude.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkCityLatitudeExist(Request $request)
    {
        $matchListData = [];
        if (request()->input('latitude') && request()->input('latitude') != "") {
            if (request()->input('city_id')) {
                $matchListData = City::withTrashed()->where('latitude', request()->input('latitude'))->where('id', '!=', request()->input('city_id'))->get()->toArray();
            } else {
                $matchListData = City::withTrashed()->where('latitude', request()->input('latitude'))->get()->toArray();
            }
        }
        if (!empty($matchListData)) {
            echo "false";
        } else {
            echo "true";
        }
    }

    /**
     * Check unique city longitude.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkCityLongitudeExist(Request $request)
    {
        $matchListData = [];
        if (request()->input('longitude') && request()->input('longitude') != "") {
            if (request()->input('city_id')) {
                $matchListData = City::withTrashed()->where('longitude', request()->input('longitude'))->where('id', '!=', request()->input('city_id'))->get()->toArray();
            } else {
                $matchListData = City::withTrashed()->where('longitude', request()->input('longitude'))->get()->toArray();
            }
        }
        if (!empty($matchListData)) {
            echo "false";
        } else {
            echo "true";
        }
    }
    
    /**
     * get country name.
     *
     * @return \Illuminate\Http\Response
     */
    public function fetchCountryCode(Request $request)
    {
        $term = $request->input('term');
        $page = $request->input('page');
        return getCountryName($term, $page);  
    }

    /**
     * import file with data in city list
     */
    public function importCity(Request $request)
    {  
        try {

            $request->validate([
                'file' => 'required|mimes:xls',
            ]);

            $file = $request->file('file');
            $path = $file->storeAs('public/import-docs', $file->getClientOriginalName());            

            $fullPath = storage_path("app/{$path}");

            chmod($fullPath, 0777);
            
            CityExcelImportJob::dispatchNow($fullPath);
            return back();
        
        } catch (ValidationException $e) {
            $failures = $e->failures();
            return back()->with('failures', $failures);
        }
        
    }
}
