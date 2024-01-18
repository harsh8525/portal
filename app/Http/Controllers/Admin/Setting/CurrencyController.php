<?php

/**
 * @package     Settings
 * @subpackage  Currencies
 * @Author      Amar Technolabs Pvt. mailto:ltd(info@amarinfotech.com)
 * @Copyright(C) 2023 [NAME OF THE ORGANISATION THAT ON BEHALF OF THE CODE WE ARE WORKING].
 * @Version 1.0.0
 * module of the Settings.
 */

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use App\Models\Setting;
use App\Models\Currency;
use App\Models\Agency;
use App\Models\CurrencyExchangeRates;
use DateTime;
use App\Models\AgencyCurrency;
use App\Traits\ActiveLog;

class CurrencyController extends Controller
{
    /**
     * Display a listing of the currency.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!hasPermission('CURRENCIES', 'read')) {
            return view('admin/401');
        }
        $header['title'] = "Currencies";
        $header['heading'] = "Currencies";
        return view('admin/setting/currencies/currency')->with(['header' => $header]);
    }

     /**
     * Display a listing of the exchange rate.
     *
     * @return \Illuminate\Http\Response
     */
    public function exchangeRate(Request $request)
    {
        $header['title'] = "Rate Exchange";
        $header['heading'] = "Rate Exchange";
        $filter = array(
            'per_page' => (request()->input('per_page') != NULL) ? request()->input('per_page') : Setting::where('config_key', 'general|setting|pagePerAdminRecords')->get('value')[0]['value'],
            'order_by' => (request()->input('order_by') != NULL) ? request()->input('order_by') : 'created_at',
            'sorting' => (request()->input('sorting') != NULL) ? request()->input('sorting') : 'desc',
            'currency_code' => (request()->input('currency_code') != NULL) ? request()->input('currency_code') : '',
        );
        if (request()->input('currency_code') != NULL) {
            $filter['where'][] = ['from_currency_code', '=', request()->input('currency_code')];
        }
        if (request()->input('currency_code') != NULL) {
            $filter['orWhere'][] = ['to_currency_code', '=', request()->input('currency_code')];
        }
        // echo "<pre>";print_r($filter);die;
        $currencyTypeDataList = CurrencyExchangeRates::getCurrencyexchange($filter);
        $currencyTypeData = $currencyTypeDataList['data'];

        $activityLog['request'] = $request->all();
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $currencyTypeDataList;
        ActiveLog::createBackendActiveLog($activityLog);

        return view('admin/setting/currencies/exchange-rate')->with(['header' => $header, 'currencyTypeData' => $currencyTypeData, 'appliedFilter' => $filter]);
    }

    /**
     * Store a newly created currency in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!hasPermission('CURRENCIES', 'create')) {
            return view('admin/401');
        }
        $requestData = $request->all();
        $resetAllCurrenciesFields = DB::table('currencies')->update([
            'is_allowed' => '0',
            'supplier_allowed_currency' => '0',
            'b2b_allowed_currency' => '0',
            'is_top_cur' => '0',
            'is_default' => '0'
        ]);
        $updateAllowCurrency = DB::table('currencies')->whereIn('id', $requestData['allow_currency_id'])->update([
            'is_allowed' => '1'
        ]);
        $updateDefaultCurrency = DB::table('currencies')->where('id', $requestData['default_display_currency_id'])->update([
            'is_default' => '1'
        ]);
        $updateTopCurrency = DB::table('currencies')->whereIn('id', $requestData['top_currency_id'])->update([
            'is_top_cur' => '1'
        ]);

        $updateSupplierCurrency = DB::table('currencies')->whereIn('id', $requestData['supplier_allowed_curreny_id'])->update([
            'supplier_allowed_currency' => '1'
        ]);


        $updateB2BCurrency = DB::table('currencies')->whereIn('id', $requestData['b2b_allowed_curreny_id'])->update([
            'b2b_allowed_currency' => '1'
        ]);

        $activityLog['request'] = $requestData;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $resetAllCurrenciesFields;
        ActiveLog::createBackendActiveLog($activityLog);

        return redirect()->route('currency.index')->with('success', 'Setting - Currencies Saved Successfully');
    }
    
    /**
     * fetch a listing of the allow currency from agency currency.
     *
     */
    public function getAllowCurrency()
    {
        $currencyData['is_allowed'] = DB::table('currencies')->get()->toArray();
        $currencyData['allowed_true'] = DB::table('currencies')->where('is_allowed', '1')->get()->toArray();
        $currencyData['default_currency'] = DB::table('currencies')->where('is_default', '1')->get()->toArray();
        $currencyData['top_currency'] = DB::table('currencies')->where('is_top_cur', '1')->get()->toArray();
        $currencyData['supplier_currency'] = DB::table('currencies')->where('supplier_allowed_currency', '1')->get()->toArray();
        $currencyData['b2b_currency'] = DB::table('currencies')->where('b2b_allowed_currency', '1')->get()->toArray();
        $currencyData['agencyCurrency'] = [];
        foreach ($currencyData['b2b_currency'] as $currency) {
            $isExist = AgencyCurrency::where('currency_id', $currency->id)->first();
            if ($isExist) {
                array_push($currencyData['agencyCurrency'], $isExist);
            }
        }

        return json_encode($currencyData);
    }


      /**
     * fetch a listing of the default currency from currency database.
     *
     */
    public function getDefaultCurrency(Request $request)
    {

        $selectedValues = $request->input('selectedValues');
        $currencyData['currency'] = DB::table('currencies')->whereIn('id', $selectedValues)->get()->toArray();
        $currencyData['allowed_true'] = DB::table('currencies')->where('is_allowed', '1')->get()->toArray();
        $currencyData['default_currency'] = DB::table('currencies')->where('is_default', '1')->get()->toArray();

        //get top currency data 
        $currencyData['top_currency'] = DB::table('currencies')->where('is_top_cur', '1')->get()->toArray();

        //get supplier allowed currency data 
        $currencyData['supplier_currency'] = DB::table('currencies')->where('supplier_allowed_currency', '1')->get()->toArray();

        //get b2b allowed currency data 
        $currencyData['b2b_currency'] = DB::table('currencies')->where('b2b_allowed_currency', '1')->get()->toArray();

        return json_encode($currencyData);
    }

    /**
     *  CurrencyExchangeRate in Insert Data
     *  
     */
    public function getCurrencyExchange(Request $request)
    {
        $currencyId = Currency::select('code')->where('is_allowed', 1)->get()->toArray();
        $currencyArrayTypeData = [];
        $currencyexchangeId = CurrencyExchangeRates::select('id')->get()->toArray();

        //Deletedata in CurrencyExchangeRatetable  
        foreach ($currencyexchangeId as $key => $getId) {
            $ids = explode(",", $getId['id']);
            $result = CurrencyExchangeRates::where('id', $ids)->delete();
        }

        //Insertdata  CurrencyExchangeRatetable
        foreach ($currencyId as $key => $curr) {
            foreach ($currencyId as $key1 => $curr1) {
                $fromCode = $curr['code'];
                $toCode = $curr1['code'];
                $data = file_get_contents("https://www.google.com/finance/quote/$fromCode-$toCode");
                $regex = '/<div class="YMlKec fxKbKc">(.+?)<\/div>/';
                $x = preg_match($regex, $data, $match);
                $currencyExchangeRate = (isset($match[1])) ? $match[1] : 0;
                $combination = array(
                    'from_currency_code' => $fromCode,
                    'to_currency_code' => $toCode,
                    'exchange_rate' => $currencyExchangeRate,
                    'margin' => 0,
                    'update_type' => 1,
                );
                CurrencyExchangeRates::create($combination);
            }
        }
        return response()->json($combination);
    }

    /**
     *  CurrencyExchangeRate Apply to All Margin 
     *  
     */
    public function getCurrencyApplymargin(Request $request)
    {
        $data = $request->all();
        CurrencyExchangeRates::where('id', '!=', '0')->update([
            'margin' => $request->margin,
        ]);
        return redirect()->back();
    }

    /**
     *  CurrencyExchangeRate in Update Single Data
     *  
     */
    public function getCurrencysingleApplymargin(Request $request)
    {
        $result = $request->all();
        CurrencyExchangeRates::where('id', $request['id'])->update([
            'exchange_rate' => $request->exchange_rate,
            'margin' => $request->margin,
            'update_type' => 2,
            'created_at' => date('Y/m/d h:i:s', time()),
        ]);
        return redirect()->back();
    }

    /**
     * function to check either currency used by any agency or not
     * 
     */
    public function checkAgencyCurrencyExist(Request $request)
    {
        $matchListData = [];
        if (request()->input('value') && request()->input('value') != "" && request()->input('code')) {
            $result = AgencyCurrency::query();
            $result->select('agency_currencies.*', 'agencies.id as agencyId', 'agencies.core_agency_type_id as agencyTypeId', 'core_agency_types.name as coreAgencyTypeName', 'core_agency_types.id as coreAgencyTypeId');
            $result->join('agencies', 'agencies.id', 'agency_currencies.agency_id');
            $result->join('core_agency_types', 'core_agency_types.id', 'agencies.core_agency_type_id');
            $result->where('core_agency_types.code', request()->input('code'));
            $result->orWhere('agency_currencies.currency_id', request()->input('value'));
            $result = $result->get();
            $found =  false;
            foreach ($result as $data) {
                if ($data['currency_id'] == request()->input('value')) {
                    $found =  true;
                    break;
                }
            }
            if ($found) {
                $response = ['exists' => $found, 'agency_type' => request()->input('code')];
            } else {
                $response = ['exists' => false];
            }
            return response()->json($response);
        }
    }

    /**
     * function to check allow currency from agency currency
     * 
     */
    public function checkAllowCurrency(Request $request)
    {
        if (request()->input('value') && request()->input('value') != "") {
            $result = AgencyCurrency::query();
            $result->select('agency_currencies');
            $result->where('agency_currencies.currency_id', request()->input('value'));
            $result = $result->count();
            $found =  false;
            if ($result > 0) {
                $found =  true;
            } else {
                $found = false;
            }
            if ($found) {
                $response = ['exists' => $found];
            } else {
                $response = ['exists' => false];
            }
            return response()->json($response);
        }
    }
}
