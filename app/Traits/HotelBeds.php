<?php

/**
 * @package     HotelBeds
 * @subpackage  HotelManagement
 * @Author      Amar Technolabs Pvt. ltd(info@amarinfotech.com)
 * @Copyright(C) 2023 [NAME OF THE ORGANISATION THAT ON BEHALF OF THE CODE WE ARE WORKING].
 * @Version 1.0.0
 * module of the Hotels.
 */

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use Illuminate\Support\Str;
use App\Models\Setting;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\AirlineI18ns;
use App\Models\Airport;
use App\Models\Airline;
use App\Models\Suppliers;
use App\Models\DefaultMarkup;
use App\Models\Bookings;
use App\Models\ServiceType;
use App\Models\Customer;
use App\Models\BookingHotelRooms;
use URL;
use DB;
use Cache;

trait HotelBeds
{
    public $credential;
    public $hotelBedsBaseUrl;
    public $apiKey;
    public $secret;

    public function __construct()
    {
        $this->initializeGlobalCredential();
    }

    private function generateSignature()
    {
        return hash('sha256', $this->apiKey . $this->secret . time());
    }

    public function initializeGlobalCredential()
    {
        $this->credential = Setting::where('config_key', 'hotelbeds|api|credential')->value('value');

        // Determine the base URL, apiKey, and secret based on the credential
        if ($this->credential === 'live') {
            $this->hotelBedsBaseUrl = Setting::where('config_key', 'hotelbeds|api|live|endPoint')->value('value');
            $this->apiKey = Setting::where('config_key', 'hotelbeds|api|live|apiKey')->value('value');
            $this->secret = Setting::where('config_key', 'hotelbeds|api|live|secret')->value('value');
        } else {
            $this->hotelBedsBaseUrl = Setting::where('config_key', 'hotelbeds|api|test|endPoint')->value('value');
            $this->apiKey = Setting::where('config_key', 'hotelbeds|api|test|apiKey')->value('value');
            $this->secret = Setting::where('config_key', 'hotelbeds|api|test|secret')->value('value');
        }
    }

    /**
     * create hotel search api with requested parameters
     */
    public function getHotelSearch($requestData)
    {
        //Ensure credentials are initialized
        if (!$this->credential || !$this->hotelBedsBaseUrl || !$this->apiKey || !$this->secret) {
            $this->initializeGlobalCredential();
        }

        try{
            $baseUrl = $this->hotelBedsBaseUrl . "/hotel-content-api/1.0/hotels";

            $search = $requestData['search'] ?? '';
            $languageCode = isset($requestData['languageCode']) ? $requestData['languageCode'] : 'en';

            $getCities = City::with(['cityCode' => function ($query) use ($languageCode) {
                $query->where('language_code', $languageCode);
            },'getCountry.countryCode' => function ($query) use ($languageCode) {
                $query->where('language_code', $languageCode);
            }])
            ->whereHas('cityCode', function ($query) use ($search) {
                $query->where('city_name', 'like', '%' . $search . '%');
            })
            ->orderBy('iso_code', 'asc')
            ->get()
            ->toArray();

            $langCode = 'ENG';
            if ($languageCode == 'ar') {
                $langCode = 'ARA';
            }
            
            $resHotels = [];
            $cityData = [];
            $hotelData = [];
            foreach ($getCities as $city) {
                $queryParams = [
                    'fields' => 'all',
                    'destinationCode' => $city['iso_code'],
                    'language' => $langCode,
                    'from' => 1,
                    'to' => 5,
                    'useSecondaryLanguage' => false,
                ];

                $response = Http::withHeaders([
                    'Api-Key' => $this->apiKey,
                    'X-Signature' => $this->generateSignature(),
                ])->get($baseUrl, $queryParams);

                if ($response->failed()) {
                    $data['statusCode'] = $response->status();
                    return $data;
                }

                $decodedResponse = $response->json();

                if ($decodedResponse['hotels'] != NULL) {
                    $hotelsData = $decodedResponse['hotels'];

                    // Collection of city data
                    $cityData[] = [
                        'type' => 'city',
                        'DestinationId' => $city['id'],
                        'DestinationName' => $city['city_code'][0]['city_name'] ?? '',
                        'CountryId' => $city['get_country']['id'] ?? '',
                        'CountryCode' => $city['get_country']['iso_code'] ?? '',
                        'CountryName' => $city['get_country']['country_code'][0]['country_name'] ?? '',
                        'CityId' => $city['id'] ?? '',
                        'CityCode' => $city['iso_code'] ?? '',
                        'CityName' => $city['city_code'][0]['city_name'] ?? '',
                        'HotelCount' => $decodedResponse['total']
                    ];

                    foreach ($hotelsData as $hotel) {

                        // Collection of hotel data
                        $hotelData[] = [
                            'type' => 'hotel',
                            'DestinationId' => $hotel['code'] ?? 0,
                            'DestinationName' => $hotel['name']['content'] ?? '',
                            'Rating' => $hotel['categoryCode'] ?? '',
                            'Address' => $hotel['address']['content'] ?? '',
                            'CountryId' => $city['get_country']['id'] ?? '',
                            'CountryCode' => $city['get_country']['iso_code'] ?? '',
                            'CountryName' => $city['get_country']['country_code'][0]['country_name'] ?? '',
                            'CityId' => $city['id'] ?? '',
                            'CityCode' => $city['iso_code'] ?? '',
                            'CityName' => $city['city_code'][0]['city_name'] ?? '',
                            'HotelCount' => 0
                        ];
                    }  
                }
            }
            $mergeCityHotelArr = array_merge($cityData, $hotelData);

            return $mergeCityHotelArr;

        } catch (Exception $e) {
            $success = [];
            return $this->sendError($success, 'Something went wrong', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * create hotel Availability by hotel code api with requested parameters
     */
    public function getHotelAvailability($requestData)
    {
        //Ensure credentials are initialized
        if (!$this->credential || !$this->hotelBedsBaseUrl || !$this->apiKey || !$this->secret) {
            $this->initializeGlobalCredential();
        }

        try{

            $defaultCurrency = "EUR";
            $supplier = $requestData['supplier'] ?? '';
            $getSupplier = Suppliers::select('code')->where('code',$supplier)->first();
            if ($getSupplier != null) {
                $supplieName = $getSupplier['code'] ?? '';
            }else{
                $supplieName = 'HOTEL_BEDS';
            }
            $languageCode = isset($requestData['languageCode']) ? $requestData['languageCode'] : 'en';
            $checkIn = $requestData['checkIn'] ?? '';
            $checkOut = $requestData['checkOut'] ?? '';
            $occupancies = $requestData['occupancies'];
            $hotel = $requestData['hotel'] ?? '';
            $currency = $requestData['currency'] ?? '';
            $destinationCode = $requestData['destinationCode'] ?? '';

            $langCode = 'ENG';
            if ($languageCode == 'ar') {
                $langCode = 'ARA';
            }

            $hotelCodes = [];
            $baseUrlHotels = $this->hotelBedsBaseUrl . "/hotel-content-api/1.0/hotels";
            $queryParamsHotels = [
                'fields' => 'all',
                'destinationCode' => $destinationCode,
                'language' => $langCode,
                'from' => 1,
                'to' => 50,
                'useSecondaryLanguage' => false,
            ];
            $responseHotels = Http::withHeaders([
                'Api-Key' => $this->apiKey,
                'X-Signature' => $this->generateSignature(),
            ])->get($baseUrlHotels, $queryParamsHotels);

            if ($responseHotels->failed()) {
                $data['statusCode'] = $response->status();
                return $data;
            }

            $decodedResponseHotels = $responseHotels->json();
            $hotelCodes = [];
            if ($decodedResponseHotels['hotels'] != NULL) {
                $hotelsContentData = $decodedResponseHotels['hotels'];
                foreach ($hotelsContentData as $hotels) {
                    $hotelCode = $hotels['code'];
                    $hotelCodes[] = $hotelCode;
                }
            }
            
            // get data from hotel availability api
            $hotelsData = [];
            $baseUrl = $this->hotelBedsBaseUrl . "/hotel-api/1.0/hotels";
            $queryParams = [
                'stay' => [
                    "checkIn" => $checkIn,
                    "checkOut" => $checkOut
                ],
                'occupancies' => [],
                'language' => $langCode,
                "hotels" => [
                    "hotel" => $hotelCodes
                ]
            ];

            foreach ($occupancies as $occupancy) {
                $queryParams['occupancies'][] = [
                    "rooms" => $occupancy['rooms'],
                    "adults" => $occupancy['adults'],
                    "children" => $occupancy['children'],
                    "paxes" => $occupancy['paxes']
                ];
            }

            $response = Http::withHeaders([
                'Api-Key' => $this->apiKey,
                'X-Signature' => $this->generateSignature(),
            ])->post($baseUrl, $queryParams);

            if ($response->failed()) {
                $data['statusCode'] = $response->status();
                return $data;
            }

            $responseDecoded = $response->json();

            if ($responseDecoded === null) {
                $data['success'] = "No hotel found";
                return $data;
            } else {
                if ($responseDecoded['hotels']['total'] > 0) {
                    $hotelsData = $responseDecoded['hotels']['hotels'];
                }
            }
            
            $updatedHotelsData = [];

            // Start Chain Codes api  
            $chainCodeCacheKey = 'chain_codes';
            // Check if chain codes and facilities data exist in the cache
            $chainCodes = Cache::get($chainCodeCacheKey);
            if (!$chainCodes) {
                $chainCodes = $this->fetchChainCodes($this->hotelBedsBaseUrl, $this->apiKey, $this->secret, $langCode);
                Cache::put($chainCodeCacheKey, $chainCodes, now()->addHours(24)); // Cache for 24 hours
            }
            // End Chain Codes api

            // Start Facilities api  
            $facilitiesCacheKey = 'facilities';
            // Check if chain codes and facilities data exist in the cache
            $facilities = Cache::get($facilitiesCacheKey);
            if (!$facilities) {
                $facilities = $this->fetchFacilities($this->hotelBedsBaseUrl, $this->apiKey, $this->secret, $langCode);
                Cache::put($facilitiesCacheKey, $facilities, now()->addHours(24)); // Cache for 24 hours
            }
            // End Facilities api

            foreach ($hotelsData as $rooms) {

                foreach ($hotelsContentData as $hotelContent) {
                    if ($hotelContent['code'] === $rooms['code']) {
                      
                        $rooms['address'] = $hotelContent['address'];
                        $rooms['destinationCode'] = $hotelContent['destinationCode'] ?? '';
                        $rooms['coordinates'] = $hotelContent['coordinates'] ?? [];
                        $rooms['city'] = $hotelContent['city'] ?? '';
                        $rooms['postalCode'] = $hotelContent['postalCode'] ?? '';
                        $rooms['stateCode'] = $hotelContent['stateCode'] ?? '';
                        $rooms['countryCode'] = $hotelContent['countryCode'] ?? '';
                        $rooms['zoneCode'] = $hotelContent['zoneCode'] ?? '';
                        $rooms['categoryCode'] = $hotelContent['categoryCode'] ?? '';
                        $resImages = '';
                        if (isset($hotelContent['images']) && is_array($hotelContent['images'])) {
                            foreach ($hotelContent['images'] as $image) {
                                if (isset($image['imageTypeCode']) && $image['imageTypeCode'] === 'GEN') {
                                    $resImages = 'http://photos.hotelbeds.com/giata/original/'.$image['path'];
                                }
                            }
                        }
                        $rooms['images'] = $resImages; 

                        $rooms['chainCode'] = [];
                        if (isset($hotelContent['chainCode']) && !empty($hotelContent['chainCode'])) {
                            $hotelChainCode = $hotelContent['chainCode'];
                            foreach ($chainCodes as $chain_code) {   
                                if ($chain_code['code'] === $hotelChainCode) {
                                    $rooms['chainCode'] = $chain_code;
                                }
                            }
                        }

                        $rooms['facilities'] = [];
                        $hotelRoomFacilities = $hotelContent['facilities'];
                        foreach ($hotelRoomFacilities as $room_f) {
                            foreach ($facilities as $facilitie) {
                                if ($room_f['facilityCode'] === $facilitie['code'] && $room_f['facilityGroupCode'] === $facilitie['facilityGroupCode']) {
                                    $allFacilitie[] = $facilitie;
                                    $rooms['facilities'] = $allFacilitie;
                                }
                            }
                        }
                    }
                }
                $allHotelRooms = $rooms;
                $hotelRoomList = collect();
                foreach ($rooms['rooms'] as $room) {
                    $hotelRoom = $room;
                    $mergedData = [];
                    $matchingRoom = null;
        
                    foreach ($hotelsContentData as $hotelContentRoom) {
                        foreach ($hotelContentRoom['rooms'] as $secondRoom) {
                            if ($secondRoom['roomCode'] === $room['code']) {
                                $matchingRoom = $secondRoom;
                                break;
                            }
                        }
                    }
                    if ($matchingRoom !== null) {
                        $mergedRoom = array_merge($room, $matchingRoom);
                        $hotelData = $mergedRoom;

                        $hotelRoomRates = collect();
                        foreach ($hotelData['rates'] as $rates) {
                            $hotelRates = $rates;
                            $convertHotelRoomRates = convertCurrencyExchangeRate($rates['net'], $defaultCurrency, $currency, []);

                            if ($convertHotelRoomRates['status'] == false) {
                                $success = [];
                                return $this->sendError($success, 'Currency not allowed.');   
                            }

                            $mergeHotelRoomRates['currencySymbol'] = $convertHotelRoomRates['data']['symbol'];
                            $mergeHotelRoomRates['currency'] = $convertHotelRoomRates['data']['toCurrencyCode'];
                            $mergeHotelRoomRates['net'] = $convertHotelRoomRates['data']['convertedRate'];
                            $mergeHotelRoomRates['displayNet'] = number_format($convertHotelRoomRates['data']['convertedRate'],2);

                            $hotelRoomRatesConverted = array_merge($rates, $mergeHotelRoomRates);
                            
                            $keys = array_keys($hotelRoomRatesConverted);
                            $startPriceIndex = array_search('net', $keys);
                            $newArray = [];

                            foreach ($keys as $key) {
                                $newArray[$key] = $hotelRoomRatesConverted[$key];
                                if ($key === 'net') {
                                    $newArray['currency'] = $mergeHotelRoomRates['currency'];
                                    $newArray['currencySymbol'] = $mergeHotelRoomRates['currencySymbol'];
                                    $newArray['displayNet'] = $mergeHotelRoomRates['displayNet'];
                                }
                            }

                            $hotelRoomRates->push($newArray);
                            
                            $hotelCancellationPolicies= collect();
                            foreach ($rates['cancellationPolicies'] as $cancellationPolicies) {
                                $convertcancellationPoliciesAmount = convertCurrencyExchangeRate($cancellationPolicies['amount'], $defaultCurrency, $currency, []);

                                if ($convertcancellationPoliciesAmount['status'] == false) {
                                    $success = [];
                                    return $this->sendError($success, 'Currency not allowed.');   
                                }

                                $mergecancellationPoliciesAmount['currencySymbol'] = $convertcancellationPoliciesAmount['data']['symbol'];
                                $mergecancellationPoliciesAmount['currency'] = $convertcancellationPoliciesAmount['data']['toCurrencyCode'];
                                $mergecancellationPoliciesAmount['amount'] = $convertcancellationPoliciesAmount['data']['convertedRate'];
                                $mergecancellationPoliciesAmount['displayAmount'] = number_format($convertcancellationPoliciesAmount['data']['convertedRate'],2);
                            
                                $cancellationPoliciesAmountConverted = array_merge($cancellationPolicies, $mergecancellationPoliciesAmount);
                                $hotelCancellationPolicies->push($cancellationPoliciesAmountConverted);
                            }

                            $hotelTaxes = collect();
                            if (isset($rates['taxes']) && isset($rates['taxes']['taxes'])) {
                                foreach ($rates['taxes']['taxes'] as $taxes) {
                                    $convertTaxesAmount = convertCurrencyExchangeRate($taxes['amount'], 'EUR', $currency, []);
                                    $convertTaxesClientAmount = convertCurrencyExchangeRate($taxes['clientAmount'], $defaultCurrency, $currency, []);

                                    if ($convertTaxesAmount['status'] == false) {
                                        $success = [];
                                        return $this->sendError($success, 'Currency not allowed.');   
                                    }

                                    $mergeTaxesAmount['currencySymbol'] = $convertTaxesAmount['data']['symbol'];
                                    $mergeTaxesAmount['currency'] = $convertTaxesAmount['data']['toCurrencyCode'];
                                    $mergeTaxesAmount['amount'] = $convertTaxesAmount['data']['convertedRate'];
                                    $mergeTaxesAmount['displayAmount'] = number_format($convertTaxesAmount['data']['convertedRate'],2);
                                    $mergeTaxesAmount['clientCurrency'] = $convertTaxesAmount['data']['toCurrencyCode'];
                                    $mergeTaxesAmount['clientAmount'] = $convertTaxesClientAmount['data']['convertedRate'];
                                    $mergeTaxesAmount['displayClientAmount'] = number_format($convertTaxesClientAmount['data']['convertedRate'],2);
                                
                                    $taxesAmountConverted = array_merge($taxes, $mergeTaxesAmount);
                                    $hotelTaxes->push($taxesAmountConverted); 
                                }
                            }
                            $lastRoomRate = $hotelRoomRates->pop();
                            $lastRoomRate['cancellationPolicies'] = $hotelCancellationPolicies->toArray();
                            $lastRoomRate['taxes']['taxes'] = $hotelTaxes->toArray();
                            $hotelRoomRates->push($lastRoomRate);
                        
                        }
                        $hotelData['rates'] = $hotelRoomRates->toArray();
                        $hotelRoomList->push($hotelData);

                    }
                }

                $allHotelRooms['rooms'] = $hotelRoomList->toArray();

                $convertMinAmount = convertCurrencyExchangeRate($allHotelRooms['minRate'], $defaultCurrency, $currency, []);
                $convertMaxAmount = convertCurrencyExchangeRate($allHotelRooms['maxRate'], $defaultCurrency, $currency, []); 

                if ($convertMinAmount['status'] == false) {
                    $success = [];
                    return $this->sendError($success, 'Currency not allowed.');   
                }

                $allHotelRooms['currencySymbol'] = $convertMinAmount['data']['symbol'];
                $allHotelRooms['currency'] = $convertMinAmount['data']['toCurrencyCode'];
                $allHotelRooms['minRate'] = $convertMinAmount['data']['convertedRate'];
                $allHotelRooms['maxRate'] = $convertMaxAmount['data']['convertedRate'];
                $allHotelRooms['displayMinRate'] = number_format($convertMinAmount['data']['convertedRate'],2);
                $allHotelRooms['displayMaxRate'] = number_format($convertMaxAmount['data']['convertedRate'],2);
                $allHotelRooms['supplier'] = $supplieName;
                $updatedHotelsData[] = $allHotelRooms;
            }

            return $updatedHotelsData;

        } catch (Exception $e) {
            $success = [];
            return $this->sendError($success, 'Something went wrong', ['error' => $e->getMessage()], 500);
        }
    }

    // get chain codes from hotelbeds api
    public function fetchChainCodes($baseUrl, $apiKey, $secret, $langCode)
    {
        $chainCodeFound = false;
        $start = 1;
        $batchSize = 1000;

        while (!$chainCodeFound) {
            $baseUrlChainCode = $baseUrl . "/hotel-content-api/1.0/types/chains";
            $queryParamsChainCode = [
                'fields' => 'all',
                'language' => $langCode,
                'from' => $start,
                'to' => $start + $batchSize - 1,
                'useSecondaryLanguage' => true,
            ];
            
            // Make the API call
            $responseChainCode = Http::withHeaders([
                'Api-Key' => $apiKey,
                'X-Signature' => $this->generateSignature(),
            ])->get($baseUrlChainCode, $queryParamsChainCode);
            
            if ($responseChainCode->failed()) {
                $data['statusCode'] = $responseChainCode->status();
                return $data;
            }
            
            $decodedResponseChainCode = $responseChainCode->json();
            
            // Check if the chains are found
            if (isset($decodedResponseChainCode['chains']) && $decodedResponseChainCode['chains'] !== null) {
                $chainCodes = $decodedResponseChainCode['chains'];
            }
            
            $start += $batchSize;
            
            if ($start > 2000 || empty($decodedResponseChainCode['chains'])) {
                break;
            }
        }
        return $chainCodes;
    }

    // get facilities from hotelbeds api
    public function fetchFacilities($baseUrl, $apiKey, $secret, $langCode)
    {
        $facilitiesFound = false;
        $startfacilities = 1;
        $batchSizefacilities = 1000;

        while (!$facilitiesFound) {
            $baseUrlFacilities = $baseUrl . "/hotel-content-api/1.0/types/facilities";
            $queryParamsFacilities = [
                'fields' => 'all',
                'language' => $langCode,
                'from' => $startfacilities,
                'to' => $startfacilities + $batchSizefacilities - 1,
                'useSecondaryLanguage' => true,
            ];
            
            // Make the API call
            $responseFacilities = Http::withHeaders([
                'Api-Key' => $apiKey,
                'X-Signature' => $this->generateSignature(),
            ])->get($baseUrlFacilities, $queryParamsFacilities);
            
            if ($responseFacilities->failed()) {
                $data['statusCode'] = $responseFacilities->status();
                return $data;
            }
            
            $decodedResponseFacilities = $responseFacilities->json();
            
            // Check if the chains are found
            if (isset($decodedResponseFacilities['facilities']) && $decodedResponseFacilities['facilities'] !== null) {
                $facilities = $decodedResponseFacilities['facilities'];
            }
            
            $startfacilities += $batchSizefacilities;
            
            if ($startfacilities > 1000 || empty($decodedResponseFacilities['facilities'])) {
                break; 
            }
        }
        return $facilities;
    }

    /**
     * create hotel details api with requested parameters
     */
    public function getHotelDetail($requestData)
    {
        //Ensure credentials are initialized
        if (!$this->credential || !$this->hotelBedsBaseUrl || !$this->apiKey || !$this->secret) {
            $this->initializeGlobalCredential();
        }

        try{
            $defaultCurrency = "EUR";

            $getSupplierID = Suppliers::where('code','HOTEL_BEDS')->get('id')[0]['id'];
            //get default markup details 
            $queryDefaultMarkup = DefaultMarkup::with(['getSupplier' => function($q) use($getSupplierID){
                $q->where('supplier_id',$getSupplierID);
            }])->first();
            
            if($requestData['agencyId'] == '0')
            {
                $markupType = $queryDefaultMarkup['b2c_markup_type'];
                $markupValue = $queryDefaultMarkup['b2c_markup'];
            }
            else
            {
                $markupType = $queryDefaultMarkup['b2b_markup_type'];
                $markupValue = $queryDefaultMarkup['b2b_markup'];
            }

            $languageCode = $requestData['languageCode'] ?? 'en';
            $checkIn = $requestData['checkIn'] ?? '';
            $checkOut = $requestData['checkOut'] ?? '';
            $occupancies = $requestData['occupancies'];
            $hotel = $requestData['hotel'] ?? '';
            $currency = $requestData['currency'] ?? '';
            $hotelCode = $requestData['hotelCode'] ?? '';

            $langCode = 'ENG';
            if ($languageCode == 'ar') {
                $langCode = 'ARA';
            }
            
            $hotelsData = [];
            $baseUrl = $this->hotelBedsBaseUrl . "/hotel-content-api/1.0/hotels/".$hotelCode."/details";
            $queryParams = [
                'language' => $langCode,
                'useSecondaryLanguage' => false,
            ];

            $response = Http::withHeaders([
                'Api-Key' => $this->apiKey,
                'X-Signature' => $this->generateSignature(),
            ])->get($baseUrl, $queryParams);

            if ($response->failed()) {
                $data['statusCode'] = $response->status();
                return $data;
            }

            $hotelavailabilityData = [];
            $queryhotelavailabilityParams = [
                'stay' => [
                    "checkIn" => $checkIn,
                    "checkOut" => $checkOut
                ],
                'occupancies' => [],
                'language' => $langCode,
                'hotels' => [
                    'hotel' => [$hotelCode]
                ]
            ];

            foreach ($occupancies as $occupancy) {
                $queryhotelavailabilityParams['occupancies'][] = [
                    "rooms" => $occupancy['rooms'],
                    "adults" => $occupancy['adults'],
                    "children" => $occupancy['children'],
                    "paxes" => $occupancy['paxes']
                ];
            }

            $hotelavailabilityBaseUrl = $this->hotelBedsBaseUrl . "/hotel-api/1.0/hotels";
            $hotelavailabilityResponse = Http::withHeaders([
                'Api-Key' => $this->apiKey,
                'X-Signature' => $this->generateSignature(),
            ])->post($hotelavailabilityBaseUrl, $queryhotelavailabilityParams);

            if ($hotelavailabilityResponse->failed()) {
                $data['statusCode'] = $hotelavailabilityResponse->status();
                return $data;
            }

            $decodedResponse = $response->json();
            $decodedhotelavailabilityResponse = $hotelavailabilityResponse->json();

            $hotelData = [];
            if (isset($decodedResponse['hotel']) && $decodedResponse['hotel'] !== null) {
                $availableHotel = $decodedResponse['hotel'];
                $matchedRooms = [];
                $matchFound = false; 

                $hotelRoomList = collect();                   
                if ($decodedhotelavailabilityResponse['hotels']['total'] > 0) {
                        $hotelshotelavailabilityData = $decodedhotelavailabilityResponse['hotels']['hotels'];
                      
                        foreach ($hotelshotelavailabilityData as $rooms) {
                            
                            foreach ($rooms['rooms'] as $room) {
                            $hotelRoom = $room;
                            $mergedData = [];
                            $matchingRoom = null;

                            foreach ($availableHotel['rooms'] as $secondRoom) {
                                if ($secondRoom['roomCode'] === $room['code']) {
                                    $matchingRoom = $secondRoom;
                                    break;
                                }
                            }
                            if ($matchingRoom !== null) {
                                $mergedRoom = array_merge($room, $matchingRoom);
                                $hotelRoomRates = collect();
                                    foreach ($mergedRoom['rates'] as $rates) {
                                        $hotelRates = $rates;
                                        $convertHotelRoomRates = convertCurrencyExchangeRate($rates['net'], $defaultCurrency, $currency, []);

                                        if ($convertHotelRoomRates['status'] == false) {
                                            $success = [];
                                            return $this->sendError($success, 'Currency not allowed.');   
                                        }

                                        $mergeHotelRoomRates['currencySymbol'] = $convertHotelRoomRates['data']['symbol'];
                                        $mergeHotelRoomRates['currency'] = $convertHotelRoomRates['data']['toCurrencyCode'];
                                        $convertedRateNet = $convertHotelRoomRates['data']['convertedRate'];
                                        
                                        $serviceFee = ($markupType == 'percentage') ? $convertedRateNet * $markupValue / 100 : $markupValue; 
                                        $totalConvertedRateNet = $convertedRateNet  + $serviceFee;

                                        $mergeHotelRoomRates['net'] = $totalConvertedRateNet;
                                        $mergeHotelRoomRates['displayNet'] = number_format($totalConvertedRateNet,2);

                                        $hotelRoomRatesConverted = array_merge($rates, $mergeHotelRoomRates);
                                        
                                        $keys = array_keys($hotelRoomRatesConverted);
                                        $startPriceIndex = array_search('net', $keys);
                                        $newArray = [];

                                        foreach ($keys as $key) {
                                            $newArray[$key] = $hotelRoomRatesConverted[$key];
                                            if ($key === 'net') {
                                                $newArray['currency'] = $mergeHotelRoomRates['currency'];
                                                $newArray['currencySymbol'] = $mergeHotelRoomRates['currencySymbol'];
                                                $newArray['displayNet'] = $mergeHotelRoomRates['displayNet'];
                                            }
                                        }

                                        $hotelRoomRates->push($newArray);
                                        
                                        $hotelCancellationPolicies= collect();
                                        foreach ($rates['cancellationPolicies'] as $cancellationPolicies) {
                                            $convertcancellationPoliciesAmount = convertCurrencyExchangeRate($cancellationPolicies['amount'], $defaultCurrency, $currency, []);

                                            if ($convertcancellationPoliciesAmount['status'] == false) {
                                                $success = [];
                                                return $this->sendError($success, 'Currency not allowed.');   
                                            }

                                            $mergecancellationPoliciesAmount['currencySymbol'] = $convertcancellationPoliciesAmount['data']['symbol'];
                                            $mergecancellationPoliciesAmount['currency'] = $convertcancellationPoliciesAmount['data']['toCurrencyCode'];
                                            $mergecancellationPoliciesAmount['amount'] = $convertcancellationPoliciesAmount['data']['convertedRate'];
                                            $mergecancellationPoliciesAmount['displayAmount'] = number_format($convertcancellationPoliciesAmount['data']['convertedRate'],2);
                                        
                                            $cancellationPoliciesAmountConverted = array_merge($cancellationPolicies, $mergecancellationPoliciesAmount);
                                            $hotelCancellationPolicies->push($cancellationPoliciesAmountConverted);
                                        }

                                        $hotelTaxes = collect();
                                        if (isset($rates['taxes']) && isset($rates['taxes']['taxes'])) {
                                            foreach ($rates['taxes']['taxes'] as $taxes) {
                                                $convertTaxesAmount = convertCurrencyExchangeRate($taxes['amount'], 'EUR', $currency, []);
                                                $convertTaxesClientAmount = convertCurrencyExchangeRate($taxes['clientAmount'], $defaultCurrency, $currency, []);

                                                if ($convertTaxesAmount['status'] == false) {
                                                    $success = [];
                                                    return $this->sendError($success, 'Currency not allowed.');   
                                                }

                                                $mergeTaxesAmount['currencySymbol'] = $convertTaxesAmount['data']['symbol'];
                                                $mergeTaxesAmount['currency'] = $convertTaxesAmount['data']['toCurrencyCode'];
                                                $mergeTaxesAmount['amount'] = $convertTaxesAmount['data']['convertedRate'];
                                                $mergeTaxesAmount['displayAmount'] = number_format($convertTaxesAmount['data']['convertedRate'],2);
                                                $mergeTaxesAmount['clientCurrency'] = $convertTaxesAmount['data']['toCurrencyCode'];
                                                $mergeTaxesAmount['clientAmount'] = $convertTaxesClientAmount['data']['convertedRate'];
                                                $mergeTaxesAmount['displayClientAmount'] = number_format($convertTaxesClientAmount['data']['convertedRate'],2);
                                            
                                                $taxesAmountConverted = array_merge($taxes, $mergeTaxesAmount);
                                                $hotelTaxes->push($taxesAmountConverted); 
                                            }
                                        }
                                        $lastRoomRate = $hotelRoomRates->pop();
                                        $lastRoomRate['cancellationPolicies'] = $hotelCancellationPolicies->toArray();
                                        $lastRoomRate['taxes']['taxes'] = $hotelTaxes->toArray();
                                        $hotelRoomRates->push($lastRoomRate);
                                    }
                                    $mergedRoom['rates'] = $hotelRoomRates->toArray();
                                    $hotelData[] = $mergedRoom;
                            }
                        }
                    }
                }
                $availableHotel['rooms'] = $hotelData;
                $hotelsData = $availableHotel;
                return $hotelsData;
            }

        } catch (Exception $e) {
            $success = [];
            return $this->sendError($success, 'Something went wrong', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * create hotel rooms check rate api with requested parameters
     */
    public function getCheckRate($requestData)
    {
        //Ensure credentials are initialized
        if (!$this->credential || !$this->hotelBedsBaseUrl || !$this->apiKey || !$this->secret) {
            $this->initializeGlobalCredential();
        }

        try{

            $defaultCurrency = "EUR";
            $currency = $requestData['currency'] ?? '';

            $getSupplierID = Suppliers::where('code','HOTEL_BEDS')->get('id')[0]['id'];
            //get default markup details 
            $queryDefaultMarkup = DefaultMarkup::with(['getSupplier' => function($q) use($getSupplierID){
                $q->where('supplier_id',$getSupplierID);
            }])->first();
            if($requestData['agencyId'] == '0')
            {
                $markupType = $queryDefaultMarkup['b2c_markup_type'];
                $markupValue = $queryDefaultMarkup['b2c_markup'];
            }
            else
            {
                $markupType = $queryDefaultMarkup['b2b_markup_type'];
                $markupValue = $queryDefaultMarkup['b2b_markup'];
            }

            $languageCode = $requestData['languageCode'] ?? 'en';
            $rooms = $requestData['rooms'] ?? [];
            $langCode = 'ENG';
            if ($languageCode == 'ar') {
                $langCode = 'ARA';
            }

            $queryParams = [
                'rooms' => [],
                'language' => $langCode
            ];

            foreach ($rooms as $room) {
                $queryParams['rooms'][] = [
                    "rateKey" => $room['rateKey']
                ];
            }

            $baseUrl = $this->hotelBedsBaseUrl . "/hotel-api/1.0/checkrates";
            $response = Http::withHeaders([
                'Api-Key' => $this->apiKey,
                'X-Signature' => $this->generateSignature(),
            ])->post($baseUrl, $queryParams);

            if ($response->failed()) {
                $data['statusCode'] = $response->status();
                return $data;
            }
            $responseDecoded = $response->json();
            
            $hotelsData = [];
            if ($responseDecoded['hotel']) {
                $hotelsData = $responseDecoded['hotel'];
            }

            $hotelRoomList = collect();
            foreach ($hotelsData['rooms'] as $room) {
                $hotelRoom = $room;

                $hotelRoomRates = collect();
                foreach ($room['rates'] as $rates) {
                    $hotelRates = $rates;
                    $convertHotelRoomRates = convertCurrencyExchangeRate($rates['net'], $defaultCurrency, $currency, []);

                    if ($convertHotelRoomRates['status'] == false) {
                        $success = [];
                        return $this->sendError($success, 'Currency not allowed.');   
                    }

                    $mergeHotelRoomRates['currencySymbol'] = $convertHotelRoomRates['data']['symbol'];
                    $mergeHotelRoomRates['currency'] = $convertHotelRoomRates['data']['toCurrencyCode'];
                    $convertedRateNet = $convertHotelRoomRates['data']['convertedRate'];
                                        
                    $serviceFee = ($markupType == 'percentage') ? $convertedRateNet * $markupValue / 100 : $markupValue; 
                    $totalConvertedRateNet = $convertedRateNet  + $serviceFee;

                    $mergeHotelRoomRates['net'] = $totalConvertedRateNet;
                    $mergeHotelRoomRates['displayNet'] = number_format($totalConvertedRateNet,2);

                    $hotelRoomRatesConverted = array_merge($rates, $mergeHotelRoomRates);
                    
                    $keys = array_keys($hotelRoomRatesConverted);
                    $startPriceIndex = array_search('net', $keys);
                    $newArray = [];

                    foreach ($keys as $key) {
                        $newArray[$key] = $hotelRoomRatesConverted[$key];
                        if ($key === 'net') {
                            $newArray['currency'] = $mergeHotelRoomRates['currency'];
                            $newArray['currencySymbol'] = $mergeHotelRoomRates['currencySymbol'];
                            $newArray['displayNet'] = $mergeHotelRoomRates['displayNet'];
                        }
                    }

                    $hotelRoomRates->push($newArray);
                    
                    $hotelCancellationPolicies= collect();
                    foreach ($rates['cancellationPolicies'] as $cancellationPolicies) {
                        $convertcancellationPoliciesAmount = convertCurrencyExchangeRate($cancellationPolicies['amount'], $defaultCurrency, $currency, []);

                        if ($convertcancellationPoliciesAmount['status'] == false) {
                            $success = [];
                            return $this->sendError($success, 'Currency not allowed.');   
                        }

                        $mergecancellationPoliciesAmount['currencySymbol'] = $convertcancellationPoliciesAmount['data']['symbol'];
                        $mergecancellationPoliciesAmount['currency'] = $convertcancellationPoliciesAmount['data']['toCurrencyCode'];
                        $mergecancellationPoliciesAmount['amount'] = $convertcancellationPoliciesAmount['data']['convertedRate'];
                        $mergecancellationPoliciesAmount['displayAmount'] = number_format($convertcancellationPoliciesAmount['data']['convertedRate'],2);
                    
                        $cancellationPoliciesAmountConverted = array_merge($cancellationPolicies, $mergecancellationPoliciesAmount);
                        $hotelCancellationPolicies->push($cancellationPoliciesAmountConverted);
                    }

                    $hotelTaxes = collect();
                    if (isset($rates['taxes']) && isset($rates['taxes']['taxes'])) {
                        foreach ($rates['taxes']['taxes'] as $taxes) {
                            $convertTaxesAmount = convertCurrencyExchangeRate($taxes['amount'], 'EUR', $currency, []);
                            $convertTaxesClientAmount = convertCurrencyExchangeRate($taxes['clientAmount'], $defaultCurrency, $currency, []);

                            if ($convertTaxesAmount['status'] == false) {
                                $success = [];
                                return $this->sendError($success, 'Currency not allowed.');   
                            }

                            $mergeTaxesAmount['currencySymbol'] = $convertTaxesAmount['data']['symbol'];
                            $mergeTaxesAmount['currency'] = $convertTaxesAmount['data']['toCurrencyCode'];
                            $mergeTaxesAmount['amount'] = $convertTaxesAmount['data']['convertedRate'];
                            $mergeTaxesAmount['displayAmount'] = number_format($convertTaxesAmount['data']['convertedRate'],2);
                            $mergeTaxesAmount['clientCurrency'] = $convertTaxesAmount['data']['toCurrencyCode'];
                            $mergeTaxesAmount['clientAmount'] = $convertTaxesClientAmount['data']['convertedRate'];
                            $mergeTaxesAmount['displayClientAmount'] = number_format($convertTaxesClientAmount['data']['convertedRate'],2);
                        
                            $taxesAmountConverted = array_merge($taxes, $mergeTaxesAmount);
                            $hotelTaxes->push($taxesAmountConverted); 
                        }
                    }
                    $lastRoomRate = $hotelRoomRates->pop();
                    $lastRoomRate['cancellationPolicies'] = $hotelCancellationPolicies->toArray();
                    $lastRoomRate['taxes']['taxes'] = $hotelTaxes->toArray();
                    $hotelRoomRates->push($lastRoomRate);
                
                }
                $room['rates'] = $hotelRoomRates->toArray();
                $hotelRoomList->push($room);
            }

            $hotelsData['rooms'] = $hotelRoomList->toArray();

            $convertTotalNet = convertCurrencyExchangeRate($hotelsData['totalNet'], $defaultCurrency, $currency, []);

            if ($convertTotalNet['status'] == false) {
                $success = [];
                return $this->sendError($success, 'Currency not allowed.');   
            }

            $hotelsData['currencySymbol'] = $convertTotalNet['data']['symbol'];
            $hotelsData['currency'] = $convertTotalNet['data']['toCurrencyCode'];

            $convertedRatetotalNet = $convertTotalNet['data']['convertedRate'];              
            $serviceFee = ($markupType == 'percentage') ? $convertedRatetotalNet * $markupValue / 100 : $markupValue; 
            $totalConvertedRatetotalNet = $convertedRatetotalNet + $serviceFee;

            $hotelsData['totalNet'] = $totalConvertedRatetotalNet;
            $hotelsData['displayTotalNet'] = number_format($totalConvertedRatetotalNet,2);

           return $hotelsData;

        } catch (Exception $e) {
            $success = [];
            return $this->sendError($success, 'Something went wrong', ['error' => $e->getMessage()], 500);
        }
    }

    private function getDetailsByCode($details, $code) {
        foreach ($details as $detail) {
            if (isset($detail['roomCode']) && $detail['roomCode'] == $code) {
                return $detail;
            }
        }
        return [];
    }

    /**
     * create hotel booking confirmation details api with requested parameters
     */
    public function getBookingConfirmationDetails($requestData)
    {
        //Ensure credentials are initialized
        if (!$this->credential || !$this->hotelBedsBaseUrl || !$this->apiKey || !$this->secret) {
            $this->initializeGlobalCredential();
        }

        try{

            $defaultCurrency = "EUR";
            $currency = $requestData['currency'] ?? '';

            $getSupplierID = Suppliers::where('code','HOTEL_BEDS')->get('id')[0]['id'];
            //get default markup details 
            $queryDefaultMarkup = DefaultMarkup::with(['getSupplier' => function($q) use($getSupplierID){
                $q->where('supplier_id',$getSupplierID);
            }])->first();
            
            if($requestData['agencyId'] == '0')
            {
                $markupType = $queryDefaultMarkup['b2c_markup_type'];
                $markupValue = $queryDefaultMarkup['b2c_markup'];
            }
            else
            {
                $markupType = $queryDefaultMarkup['b2b_markup_type'];
                $markupValue = $queryDefaultMarkup['b2b_markup'];
            }

            $languageCode = $requestData['languageCode'] ?? 'en';
            $rooms = $requestData['rooms'] ?? [];
            $langCode = 'ENG';
            if ($languageCode == 'ar') {
                $langCode = 'ARA';
            }

            $queryParams = [
                'holder' => $requestData['holder'],
                'rooms' => [],
                'clientReference' => $requestData['clientReference'] ?? '',
                'remark' => $requestData['remark'] ?? '',
                'tolerance' => $requestData['tolerance'] ?? '',
                'language' => $langCode
            ];

            $queryParams['rooms'] = [];
            foreach ($rooms as $room) {
                $roomData = [
                    'rateKey' => $room['rateKey'],
                    'paxes' => [],
                ];
                foreach ($room['paxes'] as $paxes) {
                    $roomData['paxes'][] = [
                        "roomId" => $paxes['roomId'],
                        "type" => $paxes['type'],
                        "name" => $paxes['name'],
                        "surname" => $paxes['surname'],
                    ];
                }
                $queryParams['rooms'][] = $roomData;
            }

            $baseUrl = $this->hotelBedsBaseUrl . "/hotel-api/1.0/bookings";
            $response = Http::withHeaders([
                'Api-Key' => $this->apiKey,
                'X-Signature' => $this->generateSignature(),
            ])->post($baseUrl, $queryParams);

            if ($response->failed()) {
                $data['statusCode'] = $response->status();
                return $data;
            }
            $responseDecoded = $response->json();
            
            $bookingData = [];
            if ($responseDecoded['booking']) {
                $bookingData = $responseDecoded['booking'];
                
                // Fetch hotel details
                $hotelsData = [];
                $baseUrl = $this->hotelBedsBaseUrl . "/hotel-content-api/1.0/hotels/".$bookingData['hotel']['code']."/details";
                $queryParams = [
                    'useSecondaryLanguage' => false,
                ];

                $response = Http::withHeaders([
                    'Api-Key' => $this->apiKey,
                    'X-Signature' => $this->generateSignature(),
                ])->get($baseUrl, $queryParams);

                if ($response->failed()) {
                    $data['statusCode'] = $response->status();
                    return $data;
                }

                $fetchHotelDetails = $response->json();
                $hotelRoomsDetails = $fetchHotelDetails['hotel'];
            }

            $hotelRoomList = collect();
            foreach($bookingData['hotel']['rooms'] as $rooms){

                //get amenitie, images, wildcards by check room code
                $saveRoomsAmenitie = $this->getDetailsByCode($hotelRoomsDetails['rooms'] ?? [], $rooms['code']);
                $saveRoomsImages = $this->getDetailsByCode($hotelRoomsDetails['images'] ?? [], $rooms['code']);
                $saveRoomsWildCards = $this->getDetailsByCode($hotelRoomsDetails['wildcards'] ?? [], $rooms['code']);

                $hotelRoomsRates = collect();
                foreach ($rooms['rates'] as $rates) {

                    $convertNet = convertCurrencyExchangeRate($rates['net'], $defaultCurrency, $currency, []);

                    if ($convertNet['status'] == false) {
                        $success = [];
                        return $this->sendError($success, 'Currency not allowed.');   
                    }

                    $roomRates['currencySymbol'] = $convertNet['data']['symbol'];
                    $roomRates['currency'] = $convertNet['data']['toCurrencyCode'];
                    $convertedRateNet = $convertNet['data']['convertedRate'];
                                        
                    $serviceFee = ($markupType == 'percentage') ? $convertedRateNet * $markupValue / 100 : $markupValue; 
                    $totalConvertedRateNet = $convertedRateNet  + $serviceFee;

                    $roomRates['net'] = $totalConvertedRateNet;
                    $roomRates['displayNet'] = number_format($totalConvertedRateNet,2);
                    
                    $ratesNetConverted = array_merge($rates, $roomRates);
                    $hotelRoomsRates->push($ratesNetConverted); 

                    $hotelCancellationPolicies= collect();
                    foreach ($rates['cancellationPolicies'] as $cancellationPolicies) {
                        $convertcancellationPoliciesAmount = convertCurrencyExchangeRate($cancellationPolicies['amount'], $defaultCurrency, $currency, []);

                        if ($convertcancellationPoliciesAmount['status'] == false) {
                            $success = [];
                            return $this->sendError($success, 'Currency not allowed.');   
                        }

                        $mergecancellationPoliciesAmount['currencySymbol'] = $convertcancellationPoliciesAmount['data']['symbol'];
                        $mergecancellationPoliciesAmount['currency'] = $convertcancellationPoliciesAmount['data']['toCurrencyCode'];
                        $mergecancellationPoliciesAmount['amount'] = $convertcancellationPoliciesAmount['data']['convertedRate'];
                        $mergecancellationPoliciesAmount['displayAmount'] = number_format($convertcancellationPoliciesAmount['data']['convertedRate'],2);
                    
                        $cancellationPoliciesAmountConverted = array_merge($cancellationPolicies, $mergecancellationPoliciesAmount);
                        $hotelCancellationPolicies->push($cancellationPoliciesAmountConverted);
                    }

                    $lastRoomRate = $hotelRoomsRates->pop();
                    $lastRoomRate['cancellationPolicies'] = $hotelCancellationPolicies->toArray();
                    $hotelRoomsRates->push($lastRoomRate);
                }
                $rooms['rates'] = $hotelRoomsRates->toArray();
                $hotelRoomList->push($rooms);
            }
            $bookingData['hotel']['rooms'] = $hotelRoomList->toArray();

            $convertTotalNet = convertCurrencyExchangeRate($bookingData['hotel']['totalNet'], $defaultCurrency, $currency, []);
            $convertbTotalNet = convertCurrencyExchangeRate($bookingData['totalNet'], $defaultCurrency, $currency, []);
            $convertpendingAmount = convertCurrencyExchangeRate($bookingData['pendingAmount'], $defaultCurrency, $currency, []);

            if ($convertTotalNet['status'] == false) {
                $success = [];
                return $this->sendError($success, 'Currency not allowed.');   
            }

            $bookingData['hotel']['currencySymbol'] = $convertTotalNet['data']['symbol'];
            $bookingData['hotel']['currency'] = $convertTotalNet['data']['toCurrencyCode'];

            $convertedRatetotalNet = $convertTotalNet['data']['convertedRate'];              
            $serviceFee = ($markupType == 'percentage') ? $convertedRatetotalNet * $markupValue / 100 : $markupValue; 
            $totalConvertedRatetotalNet = $convertedRatetotalNet + $serviceFee;
            $bookingData['hotel']['totalNet'] = $totalConvertedRatetotalNet;
            $bookingData['hotel']['displayTotalNet'] = number_format($totalConvertedRatetotalNet,2);

            $bookingData['currencySymbol'] = $convertbTotalNet['data']['symbol'];
            $bookingData['currency'] = $convertbTotalNet['data']['toCurrencyCode'];

            $convertedRateBTotalNet = $convertbTotalNet['data']['convertedRate'];              
            $serviceFeeB = ($markupType == 'percentage') ? $convertedRateBTotalNet * $markupValue / 100 : $markupValue; 
            $totalConvertedBRatetotalNet = $convertedRateBTotalNet + $serviceFeeB;
            $bookingData['totalNet'] = $totalConvertedBRatetotalNet;
            $bookingData['displayTotalNet'] = number_format($totalConvertedBRatetotalNet,2);

            $convertedPendingAmount = $convertpendingAmount['data']['convertedRate'];              
            $serviceFeePA = ($markupType == 'percentage') ? $convertedPendingAmount * $markupValue / 100 : $markupValue; 
            $totalConvertedPARatetotalNet = $convertedPendingAmount + $serviceFeePA;
            $bookingData['pendingAmount'] = $totalConvertedPARatetotalNet;
            $bookingData['displayPendingAmount'] = number_format($totalConvertedPARatetotalNet,2);
            
            //get admin currency converted values
            $adminConvertedTotalPrice = convertCurrencyExchangeRate($bookingData['totalNet'], $requestData['currency'],'SAR', $requestData);
            $adminConvertedRateBTotalNet = $adminConvertedTotalPrice['data']['convertedRate']; 
            $adminServiceFeeB = ($markupType == 'percentage') ? $adminConvertedRateBTotalNet * $markupValue / 100 : $markupValue; 
 
            // Create Hotel Booking Details
            $saveBookingData['customer_currency'] = $currency;
            $saveBookingData['customer_language_code'] = $requestData['languageCode'];
 
            $saveBookingData['sub_total'] = $totalConvertedBRatetotalNet;
            $saveBookingData['tax'] = '0';
            $saveBookingData['s_tax'] = $serviceFeeB;
            $saveBookingData['s_charge'] = '0';
            $saveBookingData['s_discount_type'] = "0";
            $saveBookingData['s_discount_value'] = "0";
            $saveBookingData['s_discount'] = "0";
            $saveBookingData['t_discount_type'] = "0";
            $saveBookingData['t_discount_value'] = "0";
            $saveBookingData['t_discount'] = "0";
            $saveBookingData['t_markup_type'] = $markupType;
            $saveBookingData['t_markup_value'] = $markupValue;
            $saveBookingData['t_markup'] = "0";
            $saveBookingData['booking_details'] = json_encode($bookingData);
            $saveBookingData['booking_status'] = "confirmed";
            $saveBookingData['admin_currency'] = "SAR";
            $saveBookingData['currency_conversion_rate'] = $convertpendingAmount['data']['exchangeRate'];;
            $saveBookingData['currency_markup'] = $convertpendingAmount['data']['margin'];
 
             //save admin currency details into array to create
            $saveBookingData['admin_sub_total'] = $adminConvertedTotalPrice['data']['convertedRate'];
            $saveBookingData['admin_tax'] = '0';
            $saveBookingData['admin_s_tax'] = $adminServiceFeeB;
            $saveBookingData['admin_s_charge'] = $markupValue;
            $saveBookingData['admin_currency_conversion_rate'] = $adminConvertedTotalPrice['data']['exchangeRate'];
            $saveBookingData['admin_currency_markup'] = $adminConvertedTotalPrice['data']['margin'];
 
            //generate random unique string for booking ref. value with fix length 20
            $uniqueRandomString = generateBookingRef();
            $saveBookingData['booking_ref'] = $uniqueRandomString;
            $saveBookingData['supplier_booking_ref'] = $bookingData['reference'];
            $saveBookingData['pnr_number'] = '';
            $saveBookingData['supplier_id'] = Suppliers::where('code','HOTEL_BEDS')->get('id')[0]['id'];
            $saveBookingData['booking_date'] = date('Y-m-d');
            $saveBookingData['service_id'] = ServiceType::where('code','Hotel')->get('id')[0]['id'];
            if($requestData['customer_id'] == '0')
            {
                 $saveBookingData['is_guest'] = 'true';
                 if (!empty($bookingData['holder'])) {
                     $customerData = $requestData['holder'];

                     // Check if the email address already exists in the database
                     $existingCustomer = Customer::where('email', $customerData['email'])->where('status', '!=', 'deleted')->first();
 
                     
                     if ($existingCustomer) {
                         $saveBookingData['customer_id'] = $existingCustomer->id;
                         $saveBookingData['is_guest'] = 'false';
                     } else {
                         // Sign up a new customer
                         $newCustomer = Customer::create([
                             'first_name' => $customerData['name'],
                             'last_name' => $customerData['surname'],
                             'email' => $customerData['email'],
                             'mobile' => $customerData['phoneNumber']
                         ]);
 
                         // Retrieve the new customer's ID
                         $saveBookingData['customer_id'] = $newCustomer->id;
                     }
                 }
            }
            else
            {
                $saveBookingData['is_guest'] = 'false';
                $saveBookingData['customer_id'] = $requestData['customer_id'];
            }
            $saveBookingData['agency_id'] = $requestData['agencyId'];
            $saveBookingData['description'] = $bookingData['hotel']['name'].' '.$bookingData['hotel']['destinationName'];
             
            // store booking details
            $saveHotelBooking = Bookings::create($saveBookingData);

            // store booking hotel rooms details
            $createBookingHotelRoom['booking_id'] = $saveHotelBooking->id;
            $createBookingHotelRoom['hotel_code'] = $hotelRoomsDetails['code'];
            $createBookingHotelRoom['hotel_name'] = $hotelRoomsDetails['name']['content'];
            $createBookingHotelRoom['hotel_coordinates'] = json_encode($hotelRoomsDetails['coordinates']);
            $createBookingHotelRoom['hotel_rating'] = $hotelRoomsDetails['category']['code'];
            $createBookingHotelRoom['hotel_contact'] = json_encode($hotelRoomsDetails['phones']);
            $createBookingHotelRoom['hotel_address'] = $hotelRoomsDetails['address']['content'];
            $createBookingHotelRoom['hotel_wildcards'] = json_encode($saveRoomsWildCards);
            $createBookingHotelRoom['hotel_images'] = json_encode($saveRoomsImages);
            $createBookingHotelRoom['room_type'] = $saveRoomsAmenitie['type']['code'];
            $createBookingHotelRoom['room_code'] = $saveRoomsAmenitie['roomCode'];
            $createBookingHotelRoom['room_name'] = $saveRoomsAmenitie['description'];
            $createBookingHotelRoom['room_facilities'] = json_encode($saveRoomsAmenitie);
            $createBookingHotelRoom['hotel_room_details'] = json_encode($hotelRoomsDetails);
            $saveBookingHotelRooms = BookingHotelRooms::create($createBookingHotelRoom);

            $fetchBookingDetails = Bookings::getBookingData(['id' => $saveHotelBooking->id]);
            
            $getBookingAllDetail = [
                'data' => $bookingData,
                'bookingDetail' => $fetchBookingDetails
            ];

            return $getBookingAllDetail;

        } catch (Exception $e) {
            $success = [];
            return $this->sendError($success, 'Something went wrong', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * create hotel booking detail api with requested parameters
     */
    public function getBookingDetail($requestData)
    {
        //Ensure credentials are initialized
        if (!$this->credential || !$this->hotelBedsBaseUrl || !$this->apiKey || !$this->secret) {
            $this->initializeGlobalCredential();
        }

        try{
          
            $bookingId = $requestData['booking_id'];
            $bookingDetails = Bookings::getBookingData(['id' => $bookingId]);

            $defaultCurrency = "EUR";
            $currency = $bookingDetails['data']['customer_currency'] ?? '';

            $bookingData = json_decode($bookingDetails['data']['booking_details'],true);
            $bookingData['bookingDetail'] = $bookingDetails['data'];
            return $bookingData;
        
        } catch (Exception $e) {
            $success = [];
            return $this->sendError($success, 'Something went wrong', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * create hotel booking cancellation details api with requested parameters
     */
    public function getBookingCancellationDetails($requestData)
    {
        //Ensure credentials are initialized
        if (!$this->credential || !$this->hotelBedsBaseUrl || !$this->apiKey || !$this->secret) {
            $this->initializeGlobalCredential();
        }

        try{
            $defaultCurrency = "EUR";
            $currency = $requestData['currency'] ?? '';
            $bookingId = $requestData['bookingId'];
            $languageCode = $requestData['languageCode'] ?? 'en';
            $langCode = 'ENG';
            if ($languageCode == 'ar') {
                $langCode = 'ARA';
            }

            $baseUrl = $this->hotelBedsBaseUrl . "/hotel-api/1.0/bookings/".$bookingId."?cancellationFlag=CANCELLATION&language=".$langCode;
            $response = Http::withHeaders([
                'Api-Key' => $this->apiKey,
                'X-Signature' => $this->generateSignature(),
            ])->delete($baseUrl);
            $responseDecoded = $response->json();

            return $responseDecoded;
        
        } catch (Exception $e) {
            $success = [];
            return $this->sendError($success, 'Something went wrong', ['error' => $e->getMessage()], 500);
        }
    }
}
