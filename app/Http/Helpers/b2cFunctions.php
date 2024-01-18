<?php

use App\Models\Country;
use App\Models\City;
use App\Models\State;
use App\Models\Currency;
use App\Models\Airport;
use Carbon\Carbon;
use App\Models\Markups;
use App\Models\Bookings;
use App\Models\Suppliers;
use App\Models\Setting;
use App\Models\DefaultMarkup;
use App\Models\CountryI18ns;

function setDetaultLanguage($languageCode){
    session(['languageCode' => ""]);
    session(['languageCode' => $languageCode]);
}


 /**
 * get Country name list.
 *
 * @param  string  $term
 * @param int $page
 * @return \Illuminate\Http\Response
 */
function getCountryName($term, $page,$critetiaName = null){
    
    $resultsPerPage = 10;
    $offset = ($page - 1) * $resultsPerPage;
    $query = Country::with(['countryCode' => function ($query) {
        $query->orderBy('country_name', 'asc');
    }]);

    if ($term) {
        $query->whereHas('countryCode', function($query) use ($term) {
            $query->where('country_name', 'like', '%' . $term . '%');
        });
    }
    $query->orderBy('iso_code', 'asc');
    $query->offset($offset)->limit($resultsPerPage);
    $countries = $query->get();

    $data = [];
    foreach($countries as $country) {
        $cname = [];
        if (!empty($country['countryCode'])) {
            foreach($country['countryCode'] as $country_name) {
                $cname[] = $country_name['country_name'].' ';
            }
        }
        $data[] = ['iso_code' => $country['iso_code'], 'cname' => $cname,'first_page' => $critetiaName['page']];
    }

    return response()->json($data);
}

 /**
 * get city name depend on country list.
 *
 * @param  string  $term
 * @param int $page
 * @return \Illuminate\Http\Response
 */
function getCityName($term, $page, $country_code){

    $resultsPerPage = 10;
    $offset = ($page - 1) * $resultsPerPage;
    $query = City::with(['cityCode' => function($q){
        $q->orderBy('city_name', 'asc');
    }]);

    if ($term) {
        $query->whereHas('cityCode', function($query) use ($term) {
            $query->where('city_name', 'like', '%' . $term . '%');
        });
    }
    $query->orderBy('iso_code', 'asc');
    $query->where('country_code', $country_code);
    $query->offset($offset)->limit($resultsPerPage);
    $cities = $query->get();

    $data = [];
    foreach($cities as $city) {
        $cname = [];
        if (!empty($city['cityCode'])) {
            foreach($city['cityCode'] as $city_name) {
                $cname[] = $city_name['city_name'].' ';
            }
        }
        $data[] = ['id' => $city['id'], 'cname' => $cname];
    }

    return response()->json($data);
}

 /**
 * get city name list.
 *
 * @param  string  $term
 * @param int $page
 * @return \Illuminate\Http\Response
 */
function getOnlyCityName($term, $page, $requestData){
    $resultsPerPage = 10;
    $offset = ($page - 1) * $resultsPerPage;
    $query = City::with(['cityCode' => function($q){
        $q->orderBy('city_name', 'asc');
    }]);

    if ($term) {
        $query->whereHas('cityCode', function($query) use ($term) {
            $query->where('city_name', 'like', '%' . $term . '%');
        });
    }
    $query->orderBy('iso_code', 'asc');
    $query->offset($offset)->limit($resultsPerPage);
    $cities = $query->get();

    $data = [];
    foreach($cities as $city) {
        $cname = [];
        if (!empty($city['cityCode'])) {
            foreach($city['cityCode'] as $city_name) {
                $cname[] = $city_name['city_name'].' ';
            }
        }
        $data[] = ['iso_code' => $city['iso_code'], 'cname' => $cname, 'first_page' => $requestData['page']];
    }

    return response()->json($data);
}

/**
 * get state name depend on country list.
 *
 * @param  string  $term
 * @param int $page
 * @return \Illuminate\Http\Response
 */
function getStateName($term, $page, $country_code){

    $resultsPerPage = 10;
    $offset = ($page - 1) * $resultsPerPage;
    $query = State::with(['stateName' => function($q) {
        $q->orderBy('state_name','asc');
    }]);

    if ($term) {
        $query->whereHas('stateName', function($query) use ($term) {
            $query->where('state_name', 'like', '%' . $term . '%');
        });
    }
    $query->where('country_code', $country_code);
    $query->orderBy('iso_code','asc');
    $query->offset($offset)->limit($resultsPerPage);
    $states = $query->get();

    $data = [];
    foreach($states as $state) {
        $sname = [];
        if (!empty($state['stateName'])) {
            foreach($state['stateName'] as $state_name) {
                $sname[] = $state_name['state_name'].' ';
            }
        }
        $data[] = ['id' => $state['id'], 'sname' => $sname];
    }

    return response()->json($data);
}

/**
 * get number of nights for stay.
 */
function getNumberOfNights($hotelCheckInDate, $hotelCheckOutDate){
    $checkInDate = Carbon::createFromFormat('m-d-Y', $hotelCheckInDate);
    $checkOutDate = Carbon::createFromFormat('m-d-Y', $hotelCheckOutDate);
    $numberOfNights = $checkOutDate->diffInDays($checkInDate);

    return $numberOfNights;
}

function getLocationByIpAddress(){
    try{
            
        $ip=$_SERVER['REMOTE_ADDR'];


        $url= "http://www.geoplugin.net/json.gp?ip=is_available_in_system:true";
        
        $headers = [
            'Content-Type: application/json',
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // execute!
        $response = curl_exec($ch);
        // close the connection, release resources used
        curl_close($ch);
        
        $apiData= json_decode($response);
        $isoCode = $apiData->geoplugin_currencyCode;
        $iso_code = Currency::where('code',$isoCode)->value('code');
        if($iso_code){
            $isd = true;
        }else{
            $isd = false;
        }
        $url= "http://www.geoplugin.net/json.gp?ip=is_available_in_system:".$isd;
        
        $headers = [
            'Content-Type: application/json',
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // execute!
        $response = curl_exec($ch);
        // close the connection, release resources used
        curl_close($ch);
        
        $data = json_decode($response);

        return $data;
        
    } catch (\Exception $e) {
        error_log('Caught exception: ' . $e->getMessage());
    }

   
}
function replacePriceValues($price, $arrData = [])
{
    
    
    $convertedTotalPrice = convertCurrencyExchangeRate($price['total'], 'SAR', $arrData->data->currencyCode, []);
            
    //set requested currency into currency key
    $price['currency'] = $arrData->data->currencyCode;
    $price['total'] = strval($convertedTotalPrice['data']['convertedRate']);

    //get converted base price using traits
    $convertedBasePrice = convertCurrencyExchangeRate($price['base'], 'SAR', $arrData->data->currencyCode, []);
    $price['base'] = strval($convertedBasePrice['data']['convertedRate']);

    //get converted grandTotal using traits
    $convertedGrandTotal = convertCurrencyExchangeRate($price['grandTotal'], 'SAR', $arrData->data->currencyCode, []);
    $price['grandTotal'] = strval($convertedGrandTotal['data']['convertedRate']);
    $price['supplier'] = "AMADEUS";
    
    return $price;
}
function replaceTravelersPriceValues($price, $arrData = [])
{
    $currencyCode = optional($arrData->data)->currencyCode ?? $arrData->currencyCode;
    
    $convertedTotalPrice = convertCurrencyExchangeRate($price, 'SAR', $currencyCode, []);
    
    
    $price = strval($convertedTotalPrice['data']['convertedRate']);
    return $price;
}

/**
 * use $markupType varible it's either in percentage or fixed
 * use $markupValue it's amount based on $markupType
 * use $price variable which is the price on which we want to imaplement markup calculation
 * use $isDomestic variable it's either yes or no
 */
function priceWithMarkup($markupType,$markupValue,$price,$isDomestic)
{
    
    $generalVATPercentage = Setting::where('config_key', 'general|site|defaultVatPercentage')->get('value')[0]['value'];
    $pricesArr = [];
    if($markupType == 'percentage')
    {
        $pricesArr['base_fare'] = ($isDomestic == 'yes') ? $price / (1 + $generalVATPercentage / 100)  * $markupValue / 100 : $price + ($price * $markupValue / 100);
        $pricesArr['base_fare+YQ'] =  ($price * $markupValue / 100) * $generalVATPercentage / 100;
    }
    else
    {
        $pricesArr['base_fare'] = ($isDomestic == 'yes') ?  $price / (1 + $generalVATPercentage / 100) + $markupValue : $price + $markupValue;
        $pricesArr['base_fare+YQ'] = $markupValue * $generalVATPercentage / 100;
    }
    $pricesArr['net_fare'] = $pricesArr['base_fare'] + $pricesArr['base_fare+YQ'];
    $pricesArr['total'] = $pricesArr['net_fare'];
    $pricesArr['markupAmount'] = $price * $markupValue / 100;
    
    
    return $pricesArr;
}
/**
 * use $markupType varible it's either in percentage or fixed
 * use $markupValue it's amount based on $markupType
 * use $price variable which is the price on which we want to imaplement markup calculation
 */
function priceForTravelersWithMarkup($markupType, $markupValue, $totalPrice, $basePrice,$isDomestic)
{
    $generalVATPercentage = Setting::where('config_key', 'general|site|defaultVatPercentage')->get('value')[0]['value'];
    $pricesArr = [];
    if($markupType == 'percentage')
    {
        $pricesArr['service_fee'] = $totalPrice * $markupValue / 100;
    }
    else
    {
        $pricesArr['service_fee'] = $markupValue;
        $domesticPrice = $totalPrice/1.15;
        $pricesArr['vat'] =  ($isDomestic == 'yes') ? ($domesticPrice * 15 / 100) : "";
    }
    // $pricesArr['total'] = $totalPrice + $pricesArr['service_fee'] + $pricesArr['vat'];
    $pricesArr['base'] = $basePrice;
    
    
    
    return $pricesArr;
}
/**
 * to generate random string for booking ref. id
 */
function generateBookingRef() {
    // Get the last booking reference to determine the auto-incrementing part
    $lastBooking = Bookings::latest('created_at')->first();

    // If there are no previous bookings, start with 1; otherwise, increment the last ID
    $lastId = $lastBooking ? ((int) substr($lastBooking->booking_ref, -7)) + 1 : 1;

    // Generate custom booking_ref based on the current date and auto-incrementing number
    return 'REH' . now()->format('dmY') . str_pad($lastId, 7, '0', STR_PAD_LEFT);
    
}




