<?php

/**
 * @package     AmadeusFlights
 * @subpackage  FlightManagement
 * @Author      Amar Technolabs Pvt. ltd(info@amarinfotech.com)
 * @Copyright(C) 2023 [NAME OF THE ORGANISATION THAT ON BEHALF OF THE CODE WE ARE WORKING].
 * @Version 1.0.0
 * module of the Flights.
 */

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use App\Models\Setting;
use DB;
use App\Models\AirlineI18ns;
use App\Models\Airport;
use App\Models\Airline;
use App\Traits\WithinEarth;
use App\Models\DefaultMarkup;
use App\Models\CountryI18ns;
use App\Models\Markups;
use App\Models\Bookings;
use App\Models\Suppliers;
use URL;

trait AmadeusService
{
    use WithinEarth;
    public function checkTokenHealth()
    {
        if ($this->amadeusAPISecret == "") {
            $this->generateToken();
        } else {
            $this->refreshToken();
        }
    }

    public function refreshToken()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->amadeusAPIEndPoint . "/v1/security/oauth2/token/" . $this->amadeusAPISecret,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $tokenInformation = json_decode($response, true);
        if ($tokenInformation["state"] != "approved") {
            $this->generateToken();
        } else {
            $accessToken = $tokenInformation["access_token"];
            $this->amadeusAPISecret = $accessToken;
        }
    }

    public function generateToken()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->amadeusAPIEndPoint . "/v1/security/oauth2/token",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "client_id=$this->amadeusAPIClientID&client_secret=$this->amadeusAPIClientSecret&grant_type=$this->amadeusAPIGrantType",
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));
        $response = curl_exec($curl);

        curl_close($curl);

        $tokenResponse = json_decode($response, true);
        $accessToken = $tokenResponse["access_token"];
        $this->amadeusAPISecret = $accessToken;
        Setting::updateOrCreate(['config_key' => "amadeus|api|secret"], ["value" => $accessToken]);
    }


    /**
     * create flight offer search api with origin and processed response
     */
    public function flightOfferSearch($requestData, $toCurrencyCode = 'USD')
    {

        $arrData = json_decode($requestData);
        
        $this->checkTokenHealth();

        
        $appURL = $this->amadeusAPIEndPoint . "/v2/shopping/flight-offers";

        // Initialize cURL
        $curl = curl_init();

        // Set the cURL options
        curl_setopt_array($curl, [
            CURLOPT_URL => $appURL,

            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $requestData,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                "Authorization: Bearer $this->amadeusAPISecret",
            ],
        ]);
        // Execute the cURL request
        $response = curl_exec($curl);

        $flightOffersData = json_decode($response, true);

        if (isset($flightOffersData['errors'])) {
            $success = $flightOffersData['errors'][0]['title'];
            unset($flightOffersData['errors']);
            $flightOffersData['data'] = [];
            $flightOffersData['success'] = $success;
            return $flightOffersData;
        } else if (empty($flightOffersData['data'])) {
            $flightOffersData['airportList'] = [];
            $uniqueLocationArr = [];
            foreach ($arrData->originDestinations as $airportInfo) {
                if (!in_array($airportInfo->originLocationCode, $uniqueLocationArr)) {
                    $airport_name = DB::table('airports')->join('airport_i18ns', 'airport_i18ns.airport_id', 'airports.id')->select('airport_i18ns.airport_name')->where('airports.iata_code', $airportInfo->originLocationCode)->where('airport_i18ns.language_code', strtolower($arrData->languageCode))->first();
                    $city_name_en = DB::table('cities')->join('city_i18ns', 'city_i18ns.city_id', 'cities.id')->select('city_i18ns.city_name')->where('cities.iso_code', $airportInfo->originLocationCode)->where('city_i18ns.language_code', strtolower($arrData->languageCode))->first();
                    $country_code = Airport::where('iata_code', $airportInfo->originLocationCode)->value('country_code');
                    $country_name_en = DB::table('countries')
                        ->join('country_i18ns', 'country_i18ns.country_id', 'countries.id')
                        ->where('country_i18ns.language_code', strtolower($arrData->languageCode))
                        ->where('iso_code', $country_code)
                        ->first();
                    $tempAirportArr = [
                        'code' => $airportInfo->originLocationCode,
                        'airport_name' => ($airport_name != "") ? $airport_name->airport_name : "",
                        'city_name' => ($city_name_en != "") ? $city_name_en->city_name : "",
                        'country_name' => ($country_name_en != "") ? $country_name_en->country_name : "",

                    ];
                    $uniqueLocationArr[] = $airportInfo->originLocationCode;
                    array_push($flightOffersData['airportList'], $tempAirportArr);
                }
                if (!in_array($airportInfo->destinationLocationCode, $uniqueLocationArr)) {
                    $airport_name = DB::table('airports')->join('airport_i18ns', 'airport_i18ns.airport_id', 'airports.id')->select('airport_i18ns.airport_name')->where('airports.iata_code', $airportInfo->destinationLocationCode)->where('airport_i18ns.language_code', strtolower($arrData->languageCode))->first();
                    $city_name_en = DB::table('cities')->join('city_i18ns', 'city_i18ns.city_id', 'cities.id')->select('city_i18ns.city_name')->where('cities.iso_code', $airportInfo->destinationLocationCode)->where('city_i18ns.language_code', strtolower($arrData->languageCode))->first();
                    $country_code = Airport::where('iata_code', $airportInfo->destinationLocationCode)->value('country_code');
                    $country_name_en = DB::table('countries')
                        ->join('country_i18ns', 'country_i18ns.country_id', 'countries.id')
                        ->where('country_i18ns.language_code', strtolower($arrData->languageCode))
                        ->where('iso_code', $country_code)
                        ->first();
                    $tempAirportArrTwo = [
                        'code' => $airportInfo->destinationLocationCode,
                        'airport_name' => ($airport_name != "") ? $airport_name->airport_name : "",
                        'city_name' => ($city_name_en != "") ? $city_name_en->city_name : "",
                        'country_name' => ($country_name_en != "") ? $country_name_en->country_name : "",

                    ];
                    $uniqueLocationArr[] = $airportInfo->destinationLocationCode;
                    array_push($flightOffersData['airportList'], $tempAirportArrTwo);
                }
            }
            $flightOffersData['success'] = "No direct flights found";
            return $flightOffersData;
        }

        $collection = collect($flightOffersData['data']);

        $newFlightOffersData = [];

        //add new object(origin and processed) for same response 
        $newArr = $collection->map(function ($item) use ($arrData, $toCurrencyCode) {
            $processedItem = $item;

            foreach ($processedItem['itineraries'] as $iKey => $itineraries) {
                $processedItem['itineraries'][$iKey]['durationText'] = getHourMinute($itineraries['duration']);
            }

            $processedItem['supplier'] = "AMADEUS";
            //get converted total price using traits
            $convertedTotalPrice = convertCurrencyExchangeRate($processedItem['price']['total'], 'SAR', $toCurrencyCode, []);
            
            //set requested currency into currency key
            $processedItem['price']['currency'] = ($arrData->languageCode == 'AR') ? strval($convertedTotalPrice['data']['symbol']) : strval($convertedTotalPrice['data']['toCurrencyCode']);
            $processedItem['price']['total'] = strval($convertedTotalPrice['data']['convertedRate']);

            //get converted base price using traits
            $convertedBasePrice = convertCurrencyExchangeRate($processedItem['price']['base'], 'SAR', $toCurrencyCode, []);
            $processedItem['price']['base'] = strval($convertedBasePrice['data']['convertedRate']);
            //get converted grandTotal using traits
            $convertedGrandTotal = convertCurrencyExchangeRate($processedItem['price']['grandTotal'], 'SAR', $toCurrencyCode, []);
            $processedItem['price']['grandTotal'] = strval($convertedGrandTotal['data']['convertedRate']);
            return [
                'original' => $item,
                'processed' => $processedItem
            ];
        });

        $newFlightOffersData['data']['data'] = $newArr->values()->all();
        $flightOffersData['data'] = $newFlightOffersData['data']['data'];


        //dynamically create array for airportList
        $flightOffersData['airportList'] = [];
        foreach ($flightOffersData['dictionaries']['locations'] as $key => $dictionaries) {


            $airport_name = DB::table('airports')->join('airport_i18ns', 'airport_i18ns.airport_id', 'airports.id')->select('airport_i18ns.airport_name')->where('airports.iata_code', $key)->where('airport_i18ns.language_code', strtolower($arrData->languageCode))->first();
            $city_name_en = DB::table('cities')->join('city_i18ns', 'city_i18ns.city_id', 'cities.id')->select('city_i18ns.city_name')->where('cities.iso_code', $key)->where('city_i18ns.language_code', strtolower($arrData->languageCode))->first();
            $country_name_en = DB::table('countries')->join('country_i18ns', 'country_i18ns.country_id', 'countries.id')->select('country_i18ns.country_name')->where('countries.iso_code', $dictionaries['countryCode'])->where('country_i18ns.language_code', strtolower($arrData->languageCode))->first();
            $country_code = Airport::where('iata_code', $key)->value('country_code');
            $country_name_en = DB::table('countries')
                ->join('country_i18ns', 'country_i18ns.country_id', 'countries.id')
                ->where('country_i18ns.language_code', strtolower($arrData->languageCode))
                ->where('iso_code', $country_code)
                ->first();

            $tempArr = [
                'code' => $key,
                'airport_name' => ($airport_name != "") ? $airport_name->airport_name : "",
                'city_name' => ($city_name_en != "") ? $city_name_en->city_name : "",
                'country_name' => ($country_name_en != "") ? $country_name_en->country_name : "",

            ];
            array_push($flightOffersData['airportList'], $tempArr);
        }

        //dynamically create array for aircraftList
        $flightOffersData['aircraftList'] = [];
        foreach ($flightOffersData['dictionaries']['aircraft'] as $aircraftKey => $aircraft) {
            $tempAircraftArr = [
                'code' => $aircraftKey,
                'name' => $aircraft,

            ];
            array_push($flightOffersData['aircraftList'], $tempAircraftArr);
        }

        //dynamically create array for airlineList
        $flightOffersData['airlineList'] = [];
        foreach ($flightOffersData['dictionaries']['carriers'] as $codeKey => $carriers) {
            $airlineName_en = DB::table('airlines')->join('airline_i18ns', 'airline_i18ns.airline_id', 'airlines.id')->select('airline_i18ns.airline_name')->where('airlines.airline_code', $codeKey)->where('airline_i18ns.language_code', strtolower($arrData->languageCode))->first();
            $isExistAirlineCode = Airline::where('airline_code', $codeKey)->where('airline_logo', '!=', '')->first();

            if (!empty(json_decode($isExistAirlineCode, true))) {
                $LogoURL = $isExistAirlineCode->airline_logo;
                $logoName = basename($LogoURL);
                $path = 'public/airlineLogo/' . $logoName; // The path to your image file in the storage
                $airlineLogo = URL::to('/') . Storage::url($path);
            } else {
                $airlineLogo = URL::to('/') . '/assets/images/airlineLogo/' . $codeKey . '.png';
            }

            $tempCodeArr = [
                'code' => $codeKey,
                'name' => ($airlineName_en != "") ? $airlineName_en->airline_name : "",
                'logo' => $airlineLogo
            ];
            array_push($flightOffersData['airlineList'], $tempCodeArr);
        }

        //dynamically create array for layovers
        $flightOffersData['layover'] = [];

        $processedSegments = []; // Initialize an array to keep track of processed segments
        foreach ($flightOffersData['data'] as $key => $data) {
            foreach ($data['processed']['itineraries'] as $itineraries) {
                $segments = $itineraries['segments'];
                for ($i = 0; $i < count($segments) - 1; $i++) {
                    $currentFlight = $segments[$i];
                    $nextFlight = $segments[$i + 1];

                    // Check if the current segment has already been processed
                    $currentSegmentId = $currentFlight['id'];
                    if (!in_array($currentSegmentId, $processedSegments)) {
                        $arrivalTime = $currentFlight['arrival']['at'];
                        $departureTime = $nextFlight['departure']['at'];
                        $difference = getTimeDifference($departureTime, $arrivalTime);

                        $layoverArray = [
                            'fromSegmentId' => $currentFlight['id'],
                            'toSegmentId' => $nextFlight['id'],
                            'layoverDurationText' => $difference['hours'] . 'h ' . $difference['minutes'] . 'm',
                            'layoverDuration' => (($difference['hours'] < 9) ? str_pad($difference['hours'], 2, '0', STR_PAD_LEFT) : $difference['hours']) . ':' . $difference['minutes']
                        ];
                        array_push($flightOffersData['layover'], $layoverArray);

                        // Add the current segment to the processedSegments array to avoid duplicates
                        $processedSegments[] = $currentSegmentId;
                    }
                }
            }
        }

        return $flightOffersData;
    }

    /**
     * Search flight offer api with origin and processed response
     */
    public function flightOfferSearchGet($requestData)
    {
        $this->checkTokenHealth();



        $appURL = $this->amadeusAPIEndPoint . "/v2/shopping/flight-offers";
        $postfields =   http_build_query($requestData);
        // Initialize cURL
        $curl = curl_init();

        // Set the cURL options
        curl_setopt_array($curl, [
            CURLOPT_URL => $appURL . "?" . $postfields,

            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/x-www-form-urlencoded',
                "Authorization: Bearer $this->amadeusAPISecret",
            ],
        ]);

        // Execute the cURL request
        $response = curl_exec($curl);
        $flightOffersData = json_decode($response, true);
        $flightOffersData['from_name'] = DB::table('geo_airport_lists')->where('airport_code', $requestData['originLocationCode'])->value('airport_name');
        $flightOffersData['to_name'] = DB::table('geo_airport_lists')->where('airport_code', $requestData['destinationLocationCode'])->value('airport_name');
        $flightOffersData['departure_date'] = $requestData['departureDate'];
        $flightOffersData['flight_count'] = count($flightOffersData['data']);

        return $flightOffersData;
    }

    /**
     * fetch flight offers price data
     */
    public function flightOfferPriceGet($requestData)
    {
        $requestTemp = [
            'data' => $requestData
        ];
        $postfields = json_encode($requestTemp, JSON_PRETTY_PRINT);
        // $arrData = json_decode($requestData);
        
        $this->checkTokenHealth();

        $appURL = $this->amadeusAPIEndPoint . "/v1/shopping/flight-offers/pricing?include=detailed-fare-rules%2Cbags";

        // Initialize cURL
        $curl = curl_init();

        // Set the cURL options
        curl_setopt_array($curl, [
            CURLOPT_URL => $appURL,

            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $postfields,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                "Authorization: Bearer $this->amadeusAPISecret"
            ],
        ]);

        // Execute the cURL request
        $response = curl_exec($curl);

        $flightOffersData = json_decode($response, true);
        if (isset($flightOffersData['errors'])) {
            return $flightOffersData;
        }
            
        // Add a new key 'originalFlightOffers' with a copy of 'flightOffers' into response
        $flightOffersData['data']['originalFlightOffers'] = array_map(function ($flightOffer) {
            return $flightOffer;
        }, $flightOffersData['data']['flightOffers']);
        

        // Use array_map to transform the data
        $flightOffersData['data']['flightOffers'] = array_map(function ($flightOffer) use($requestData){
            // Replace values in the nested arrays as needed
            $flightOffer = $this->getPricesWithMarkup($flightOffer,$requestData);

            return $flightOffer;
        }, $flightOffersData['data']['flightOffers']);

        //dynamically create array for airportList
        $flightOffersData['airportList'] = [];
        foreach ($flightOffersData['dictionaries']['locations'] as $key => $dictionaries) {


            $airport_name = DB::table('airports')->join('airport_i18ns', 'airport_i18ns.airport_id', 'airports.id')->select('airport_i18ns.airport_name')->where('airports.iata_code', $key)->where('airport_i18ns.language_code', strtolower($requestData['languageCode']))->first();
            $city_name_en = DB::table('cities')->join('city_i18ns', 'city_i18ns.city_id', 'cities.id')->select('city_i18ns.city_name')->where('cities.iso_code', $key)->where('city_i18ns.language_code', strtolower($requestData['languageCode']))->first();
            $country_name_en = DB::table('countries')->join('country_i18ns', 'country_i18ns.country_id', 'countries.id')->select('country_i18ns.country_name')->where('countries.iso_code', $dictionaries['countryCode'])->where('country_i18ns.language_code', strtolower($requestData['languageCode']))->first();
            $country_code = Airport::where('iata_code', $key)->value('country_code');
            $country_name_en = DB::table('countries')
                ->join('country_i18ns', 'country_i18ns.country_id', 'countries.id')
                ->where('country_i18ns.language_code', strtolower($requestData['languageCode']))
                ->where('iso_code', $country_code)
                ->first();

            $tempArr = [
                'code' => $key,
                'airport_name' => ($airport_name != "") ? $airport_name->airport_name : "",
                'city_name' => ($city_name_en != "") ? $city_name_en->city_name : "",
                'country_name' => ($country_name_en != "") ? $country_name_en->country_name : "",

            ];
            array_push($flightOffersData['airportList'], $tempArr);
        }
        
        
        // Dynamically create array for airlineList
        $flightOffersData['airlineList'] = [];
        foreach ($flightOffersData['data']['flightOffers'] as $flightOffer) {
            foreach ($flightOffer['itineraries'] as $itinerary) {
                foreach ($itinerary['segments'] as $segment) {
                    $carrierCode = $segment['carrierCode'];
                    $airlineName_en = DB::table('airlines')
                        ->join('airline_i18ns', 'airline_i18ns.airline_id', 'airlines.id')
                        ->select('airline_i18ns.airline_name')
                        ->where('airlines.airline_code', $carrierCode)
                        ->where('airline_i18ns.language_code', strtolower($requestData['languageCode']))
                        ->first();

                    $isExistAirlineCode = Airline::where('airline_code', $carrierCode)
                        ->where('airline_logo', '!=', '')
                        ->first();

                    if (!empty(json_decode($isExistAirlineCode, true))) {
                        $LogoURL = $isExistAirlineCode->airline_logo;
                        $logoName = basename($LogoURL);
                        $path = 'public/airlineLogo/' . $logoName; // The path to your image file in the storage
                        $airlineLogo = URL::to('/') . Storage::url($path);
                    } else {
                        $airlineLogo = URL::to('/') . '/assets/images/airlineLogo/' . $carrierCode . '.png';
                    }

                    $tempCodeArr = [
                        'code' => $carrierCode,
                        'name' => ($airlineName_en != "") ? $airlineName_en->airline_name : "",
                        'logo' => $airlineLogo
                    ];

                    // Check if the carrierCode is not already in the array
                    if (!in_array($tempCodeArr['code'], array_column($flightOffersData['airlineList'], 'code'))) {
                        $flightOffersData['airlineList'][] = $tempCodeArr;
                    }
                }
            }
        }

        // Check if flightOffers key exists and it is an array
        if (isset($flightOffersData['data']['flightOffers']) && is_array($flightOffersData['data']['flightOffers'])) {
            //dynamically create array for layovers
            $flightOffersData['layover'] = [];

            $processedSegments = []; // Initialize an array to keep track of processed segments
            // Iterate through each flight offer
            foreach ($flightOffersData['data']['flightOffers'] as $flightOffer) {
                // Check if itineraries key exists and it is an array
                if (isset($flightOffer['itineraries']) && is_array($flightOffer['itineraries'])) {
                    // Iterate through each itinerary
                    foreach ($flightOffer['itineraries'] as $itineraries) {
                        $segments = $itineraries['segments'];
                        for ($i = 0; $i < count($segments) - 1; $i++) {
                            $currentFlight = $segments[$i];
                            $nextFlight = $segments[$i + 1];

                            // Check if the current segment has already been processed
                            $currentSegmentId = $currentFlight['id'];
                            if (!in_array($currentSegmentId, $processedSegments)) {
                                $arrivalTime = $currentFlight['arrival']['at'];
                                $departureTime = $nextFlight['departure']['at'];
                                $difference = getTimeDifference($departureTime, $arrivalTime);

                                $layoverArray = [
                                    'fromSegmentId' => $currentFlight['id'],
                                    'toSegmentId' => $nextFlight['id'],
                                    'layoverDurationText' => $difference['hours'] . 'h ' . $difference['minutes'] . 'm',
                                    'layoverDuration' => (($difference['hours'] < 9) ? str_pad($difference['hours'], 2, '0', STR_PAD_LEFT) : $difference['hours']) . ':' . $difference['minutes']
                                ];
                                array_push($flightOffersData['layover'], $layoverArray);

                                // Add the current segment to the processedSegments array to avoid duplicates
                                $processedSegments[] = $currentSegmentId;
                            }
                        }
                    }
                }
            }
        }
        $flightOffersData['supplier'] = 'AMADEUS';
        return $flightOffersData;
    }

    /**
     * fetch flight offers price details with amenities
     */
    public function flightOfferPriceUpsellingGet($requestData)
    {
        $jsonDecode = json_decode($requestData, true);

        $this->checkTokenHealth();

        $appURL = $this->amadeusAPIEndPoint . "/v1/shopping/flight-offers/upselling";

        // Initialize cURL
        $curl = curl_init();

        // Set the cURL options
        curl_setopt_array($curl, [
            CURLOPT_URL => $appURL,

            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $requestData,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                "Authorization: Bearer $this->amadeusAPISecret"
            ],
        ]);

        // Execute the cURL request
        $response = curl_exec($curl);

        $flightOffersData = json_decode($response, true);

        if (empty($flightOffersData['data'])) {
            $flightOffersData['success'] = "No amenities found in this flight";
            return $flightOffersData;
        }

        return $flightOffersData;
    }

    /**
     * fetch flight offers seatmap details with amenities
     */
    public function flightOfferSeatmapAmenities($requestData)
    {
        $this->checkTokenHealth();

        $appURL = $this->amadeusAPIEndPoint . "/v1/shopping/seatmaps";

        // Initialize cURL
        $curl = curl_init();

        // Set the cURL options
        curl_setopt_array($curl, [
            CURLOPT_URL => $appURL,

            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $requestData,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                "Authorization: Bearer $this->amadeusAPISecret"
            ],
        ]);

        // Execute the cURL request
        $response = curl_exec($curl);

        $flightOffersData = json_decode($response, true);
        if (empty($flightOffersData['data'])) {
            $flightOffersData['success'] = "No amenities found in this flight";
            return $flightOffersData;
        }
        //dynamically create array for airlineList
        $flightOffersData['amenities'] = [];
        foreach ($flightOffersData['data'] as $data) {

            $Airline[$data['id']] = [];
            foreach ($data['aircraftCabinAmenities'] as $amenitiesKey => $amenities) {

                $tempAmenitiesArr = [
                    'amenities' => $amenitiesKey,
                    'icon' => URL::to('/') . '/assets/images/amenities/' . $amenitiesKey . '.svg',
                    'segmentId' => $data['segmentId']
                ];
                array_push($Airline[$data['id']], $tempAmenitiesArr);
            }
            array_push($flightOffersData['amenities'], $Airline[$data['id']]);
        }

        return $flightOffersData;
    }
    /**
     * create flight order using traveller's details
     * created date 19-12-2023
     */
    public function flightNewOrderCreate($requestData)
    {
        $this->checkTokenHealth();

        //convert array into json request for amadeus api
        $postfields = json_encode($requestData, JSON_PRETTY_PRINT);

        
        $appURL = $this->amadeusAPIEndPoint . "/v1/booking/flight-orders";

        // Initialize cURL
        $curl = curl_init();

        // Set the cURL options
        curl_setopt_array($curl, [
            CURLOPT_URL => $appURL,

            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $postfields,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                "Authorization: Bearer $this->amadeusAPISecret"
            ],
        ]);

        // Execute the cURL request
        $response = curl_exec($curl);

        $flightOffersData = json_decode($response, true);
        
        if (isset($flightOffersData['errors'])) {
            return $flightOffersData;
        }
        
        
        // Add a new key 'originalFlightOffers' with a copy of 'flightOffers' into response
        $flightOffersData['data']['originalFlightOffers'] = array_map(function ($flightOffer) {
            return $flightOffer;
        }, $flightOffersData['data']['flightOffers']);
       
        // Use array_map to transform the data
        $flightOffersData['data']['flightOffers'] = array_map(function ($flightOffer) use($requestData){
            // Replace values in the nested arrays as needed
            $flightOffer = $this->getPricesWithMarkup($flightOffer,$requestData);

            return $flightOffer;
        }, $flightOffersData['data']['flightOffers']);
        // echo "<pre>";print_r($flightOffersData);die;
        return $flightOffersData;
    }
    /**
     * use flightoffers obejct to replace and modify and return new array with added markup and tax
     * use arrData which is json request of flight-offer-pricing api
     */
    public function getPricesWithMarkup($flightOffersData, $requestData = [])
    {
        // Initialize an empty array to store the IATA codes
        $iataCodes = [];

        // Loop through each itinerary and segment to extract the IATA codes
        foreach ($flightOffersData['itineraries'] as $itinerary) {
            foreach ($itinerary['segments'] as $segment) {
                $iataCodes[] = Airport::where('iata_code',$segment['departure']['iataCode'])->get('country_code')[0]['country_code'];
                $iataCodes[] = Airport::where('iata_code',$segment['arrival']['iataCode'])->get('country_code')[0]['country_code'];
            }
        }

        // Remove duplicates using array_unique
        $uniqueIataCodes = array_unique($iataCodes);

        // Check if all values are the same
        $areAllValuesSame = count($uniqueIataCodes) === 1;
        $getDefaultAgencyCountry = Setting::where('config_key', 'general|site|defaultCountry')->get('value')[0]['value'];
        
        $isDomestic = 'no';
        if ($areAllValuesSame && in_array($getDefaultAgencyCountry,$iataCodes)) {
            
            $isDomestic = "yes";
            
        }
        
        
        $query = Markups::query();
                    $query->with(['getServiceType', 'getChannel', 'getOriginCountry.countryCode', 'getOriginCity.cityCode', 'getOriginAirport.AirportName', 'getDestinationCountry.countryCode', 'getDestinationCity.cityCode', 'getDestinationAirport.AirportName', 'getAirline.getMarkupsAirline', 'getSupplier.getMarkupsSupplier', 'getAgent']);
                    $query->select(
                        'markups.*'
                    );
                    $query->where('priority', '1');
                    $result = $query->first();
        
        
        
        
        if($result)
        {
            //get airline codes into array which we need to add markup
            $airlineCodeArr = collect($result['getAirline'])->map(function($item){
            return $item['airline_code'];
                
            })->all();

            //get supplier ids on which we need to add markup
            $supplierArr = collect($result['getSupplier'])->map(function($item){
                $supplierCode = Suppliers::where('id',$item['supplier_id'])->get('code')[0]['code'];
                return $supplierCode;
                
            })->all();

            $markupIDArr = [];
            
            foreach ($flightOffersData['itineraries'] as $itinerary) {
                foreach ($itinerary['segments'] as $segment) {
                    
                    //get only date from departure['at'] format
                    $dateString = $segment['departure']['at'];
                    $dateTime = new DateTime($dateString);
                    $departureTime = $dateTime->format('Y-m-d');

                    $carrierCode = $segment['carrierCode'];

                    // Check conditions: departure time and carrier code
                    if ($departureTime >= $result['from_travel_date'] &&
                        $departureTime <= $result['to_travel_date'] && 
                        date('Y-m-d') >= $result['from_booking_date'] &&
                        date('Y-m-d') <= $result['to_booking_date'] &&
                        in_array($carrierCode, $airlineCodeArr)) {
                        
                        // Add the 'id' value to the temparr array
                        $markupIDArr[] = $segment['id'];
                    }
                }
            }
            

            
            if(!empty($markupIDArr))
            {
                foreach ($flightOffersData['travelerPricings'] as &$travelerPricing) {
                    // Check traveler type and initiate the condition
                    if ($travelerPricing['travelerType'] === strtoupper($result['pax_type'])) {
                        foreach ($travelerPricing['fareDetailsBySegment'] as $fareDetail) {
                            // Check conditions: cabin, class, segmentId
                            if (
                                $fareDetail['cabin'] === strtoupper($result['cabin_class']) &&
                                $fareDetail['class'] === $result['booking_class'] &&
                                in_array($fareDetail['segmentId'], $markupIDArr) &&
                                in_array($flightOffersData['price']['supplier'],$supplierArr)
                            ) {
                                $sumBase = 0;
                                $sumTotal = 0;
                                if($result['comm_markup_on'] == 'base_fare')
                                {
                                    if($travelerPricing['price']['base'] >= $result['from_price_range'] && $travelerPricing['price']['base'] <= $result['to_price_range'])
                                    {
                                        $travelerPricing['price']['base'] = priceWithMarkup($result['b2c_markup_type'],$result['b2c_markup'],$travelerPricing['price']['base']); 
                                        $sumBase += $travelerPricing['price']['base'];
                                    }
                                }
                                else if($result['comm_markup_on'] == 'total_fare') 
                                {
                                    if($travelerPricing['price']['total'] >= $result['from_price_range'] && $travelerPricing['price']['total'] <= $result['to_price_range'])
                                    {
                                        $travelerPricing['price']['total'] = priceWithMarkup($result['b2c_markup_type'],$result['b2c_markup'],$travelerPricing['price']['total']); 
                                    }
                                    
                                }
                                else if($result['comm_markup_on'] == 'net_fare')
                                {
                                    //logic for net fare
                                }
                                else
                                {
                                    //logic for base_fare+YQ
                                }

                                
                            }
                        }
                    }
                }
            }
        
            
        }
        else
        {
            $getSupplierID = Suppliers::where('code','AMADEUS')->get('id')[0]['id'];

            //get default markup details 
            $query = DefaultMarkup::with(['getSupplier' => function($q) use($getSupplierID){
                $q->where('supplier_id',$getSupplierID);
            }])->first();
            
            if($requestData['agencyId'] == '0')
            {
                $markupType = $query['b2c_markup_type'];
                $markupValue = $query['b2c_markup'];
            }
            else
            {
                $markupType = $query['b2b_markup_type'];
                $markupValue = $query['b2b_markup'];
            }
            $travelersTotalPriceSum = $travelersBasePriceSum = $totalTaxAmount = $amdinTravelersTotalPriceSum = $adminTravelersBasePriceSum =  0;
            $generalVATPercentage = Setting::where('config_key', 'general|site|defaultVatPercentage')->get('value')[0]['value'];
            foreach ($flightOffersData['travelerPricings'] as &$travelerPricing) {
                // Initialize the sum variable
                $sumOfAllAmounts = 0;

                //set travelers wise converted currency rate start
                foreach ($travelerPricing['price']['taxes'] as $tax) {
                    $sumOfAllAmounts += $tax['amount'];
                }
                
                $convertedTotalPrice = convertCurrencyExchangeRate($travelerPricing['price']['total'],'SAR',$requestData['currencyCode'], $requestData);
                $convertedBasePrice = convertCurrencyExchangeRate($travelerPricing['price']['base'],'SAR',$requestData['currencyCode'], $requestData);
                $convertedTaxAmount = convertCurrencyExchangeRate($sumOfAllAmounts,'SAR',$requestData['currencyCode'], $requestData);
                

                //get admin currency converted values
                $adminConvertedTotalPrice = convertCurrencyExchangeRate($travelerPricing['price']['total'], $requestData['currencyCode'],'SAR', $requestData);
                $adminConvertedBasePrice = convertCurrencyExchangeRate($travelerPricing['price']['base'], $requestData['currencyCode'],'SAR', $requestData);
                $adminConvertedTaxAmount = convertCurrencyExchangeRate($sumOfAllAmounts, $requestData['currencyCode'],'SAR', $requestData);




                $displayCurrency = ($requestData['languageCode'] == 'AR') ? $convertedTotalPrice['data']['symbol'] : $convertedTotalPrice['data']['toCurrencyCode'];
                //remove vat from traveler's base price
                $baseRemovedVAT = $convertedBasePrice['data']['convertedRate'] / (1 + ($generalVATPercentage / 100));

                //remove vat from traveler's base price for admin
                $adminBaseRemovedVAT = $adminConvertedBasePrice['data']['convertedRate'] / (1 + ($generalVATPercentage / 100));

                $travelerVAT = ($isDomestic == 'yes') ? $baseRemovedVAT * $generalVATPercentage / 100 : $convertedBasePrice['data']['convertedRate'] * $generalVATPercentage / 100;

                //get admin VAt
                $adminTravelersVAT = ($isDomestic == 'yes') ? $adminBaseRemovedVAT * $generalVATPercentage / 100 : $adminConvertedBasePrice['data']['convertedRate'] * $generalVATPercentage / 100;

                $travelerPricing['processedPrice'] = [
                    'currency' => $displayCurrency,
                    'totalConverted' => number_format($convertedTotalPrice['data']['convertedRate'],'2','.',''),
                    'totalFormated' => $convertedTotalPrice['data']['formattedPrice'],
                    'baseFareConverted' => strval($convertedBasePrice['data']['convertedRate']),
                    'baseFareFormated' => $convertedBasePrice['data']['formattedPrice'],
                    'taxTotalConverted' => number_format($convertedTaxAmount['data']['convertedRate'],'2','.',''),
                    'taxTotalFormated' => $convertedTaxAmount['data']['formattedPrice'],
                    'travelerVAT' => number_format($travelerVAT,'2','.',''),
                    'adminTotalConverted' => number_format($adminConvertedTotalPrice['data']['convertedRate'],'2','.',''),
                    'adminBaseFareConverted' => number_format($adminConvertedBasePrice['data']['convertedRate'],'2','.',''),
                    'adminTaxTotalConverted' => number_format($adminConvertedTaxAmount['data']['convertedRate'],'2','.',''),
                    'adminTravelerVAT' => number_format($adminTravelersVAT,'2','.',''),
                    'traveler_id' => $travelerPricing['travelerId'],
                    'traveler_type' => $travelerPricing['travelerType'],

                ];
                /*set travelers wise converted currency rate end*/

                

                //get sum of total and base of all travelers
                $travelersTotalPriceSum += $convertedTotalPrice['data']['convertedRate'];
                $travelersBasePriceSum += $baseRemovedVAT;
                $totalTaxAmount += $sumOfAllAmounts;

                //get admin currency price data
                $amdinTravelersTotalPriceSum += $adminConvertedTotalPrice['data']['convertedRate'];
                $adminTravelersBasePriceSum += $adminBaseRemovedVAT;
            
            }
            //get customer converted currency calculation start
            $domesticPrice = $travelersBasePriceSum * $generalVATPercentage / 100;
            
            $serviceFee = ($markupType == 'percentage') ? $travelersTotalPriceSum * $markupValue / 100 : $markupValue; 
            $vat = ($isDomestic == 'yes') ?  $domesticPrice + ($serviceFee * $generalVATPercentage / 100) : $serviceFee * $generalVATPercentage / 100;
            $totalInclusiveTax = $travelersTotalPriceSum  + $serviceFee + $vat;
            $customerServiceCharge = $serviceFee * $generalVATPercentage / 100;
            //get customer converted currency calculation start

            //get customer converted currency calculation start
            $adminDomesticPrice = $adminTravelersBasePriceSum * $generalVATPercentage / 100;
            
            $adminServiceFee = ($markupType == 'percentage') ? $amdinTravelersTotalPriceSum * $markupValue / 100 : $markupValue; 
            $adminVat = ($isDomestic == 'yes') ?  $adminDomesticPrice + ($adminServiceFee * $generalVATPercentage / 100) : $adminServiceFee * $generalVATPercentage / 100;
            $adminTotalInclusiveTax = $amdinTravelersTotalPriceSum  + $adminServiceFee + $adminVat;
            $adminServiceCharge = $adminServiceFee * $generalVATPercentage / 100;
            //get customer converted currency calculation start
            
            //get grandTotal of all kind of prices with seperate key 
            $flightOffersData['processedPrice'] = [
                'currency' => $displayCurrency,
                'serviceFee' => number_format($serviceFee , '2','.',''),
                'vat' => number_format($vat, '2', '.', ''),
                'totalPrice' => number_format($travelersTotalPriceSum,'2','.',''),
                'grandTotal' => number_format($totalInclusiveTax, '2', '.', ''),
                'markupType' => $markupType,
                'markupValue' => $markupValue,
                'totalTaxAmount' => $totalTaxAmount,
                'exchangeRate' => strval($convertedTotalPrice['data']['marginedExchangeRate']),
                'margin' => strval($convertedTotalPrice['data']['margin']),
                'supplier' => 'AMADEUS',
                'serviceCharge' => $generalVATPercentage,
                'customerServiceCharge' => $customerServiceCharge,
                'amdinTravelersTotalPriceSum' => number_format($amdinTravelersTotalPriceSum,'2','.',''),
                'adminExchangeRate' => strval($adminConvertedTotalPrice['data']['marginedExchangeRate']),
                'adminCurrencyMarkup' => strval($adminConvertedTotalPrice['data']['margin']),
                'adminGrandTotal' => number_format($adminTotalInclusiveTax, '2', '.', ''),
                'adminServiceFee' => number_format($adminServiceFee , '2','.',''),
                'adminVat' => number_format($adminVat, '2', '.', ''),
                'adminServiceCharge' => number_format($adminServiceCharge,'2','.','')

                
                
            ];
        }
        
        return $flightOffersData;
    }
    public function getFlightOrderDetails($bookingId)
    {
        $bookingDetails = Bookings::getBookingData(['id' => $bookingId]);
        
        $this->checkTokenHealth();
        
        $appURL = $this->amadeusAPIEndPoint . "/v1/booking/flight-orders/".$bookingDetails['data']['supplier_booking_ref'];

        // Initialize cURL
        $curl = curl_init();

        // Set the cURL options
        curl_setopt_array($curl, [
            CURLOPT_URL => $appURL,

            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                "Authorization: Bearer $this->amadeusAPISecret"
            ],
        ]);

        // Execute the cURL request
        $response = curl_exec($curl);

        $flightOrderData = json_decode($response, true);

        if (empty($flightOrderData['data'])) {
            $flightOrderData['success'] = "No detail found";
            return $flightOrderData;
        }

        //display airline list into response
        $flightOrderData['airlineList'] = $this->getAirlineList($flightOrderData['data']['flightOffers']);

        //display airport list into response
        $flightOrderData['airportList'] = $this->getAirportList($flightOrderData['dictionaries']['locations']);

        //display layover list into response
        $flightOrderData['layover'] = $this->getLayoverList($flightOrderData['data']['flightOffers']);
        //display flight details
        $flightOrderData['bookingDetail'] = $bookingDetails['data'];
        return $flightOrderData;
    }
    /**
     * comman function to get airline list in default admin panel's language code
     * created date 05-01-2024
     */
    function getAirlineList($flightOffersDetails)
    {
        $flightOffersData = [];
        $getDefaultLanguageCode = Setting::where('config_key', 'general|site|defaultLanguageCode')->get('value')[0]['value'];
        foreach ($flightOffersDetails as $flightOffer) {
            foreach ($flightOffer['itineraries'] as $itinerary) {
                foreach ($itinerary['segments'] as $segment) {
                    $carrierCode = $segment['carrierCode'];
                    $airlineName_en = DB::table('airlines')
                        ->join('airline_i18ns', 'airline_i18ns.airline_id', 'airlines.id')
                        ->select('airline_i18ns.airline_name')
                        ->where('airlines.airline_code', $carrierCode)
                        ->where('airline_i18ns.language_code', strtolower($getDefaultLanguageCode))
                        ->first();

                    $isExistAirlineCode = Airline::where('airline_code', $carrierCode)
                        ->where('airline_logo', '!=', '')
                        ->first();

                    if (!empty(json_decode($isExistAirlineCode, true))) {
                        $LogoURL = $isExistAirlineCode->airline_logo;
                        $logoName = basename($LogoURL);
                        $path = 'public/airlineLogo/' . $logoName; // The path to your image file in the storage
                        $airlineLogo = URL::to('/') . Storage::url($path);
                    } else {
                        $airlineLogo = URL::to('/') . '/assets/images/airlineLogo/' . $carrierCode . '.png';
                    }

                    $tempCodeArr = [
                        'code' => $carrierCode,
                        'name' => ($airlineName_en != "") ? $airlineName_en->airline_name : "",
                        'logo' => $airlineLogo
                    ];

                    // Check if the carrierCode is not already in the array
                    if (!in_array($tempCodeArr['code'], array_column($flightOffersData, 'code'))) {
                        $flightOffersData[] = $tempCodeArr;
                    }
                }
            }
        }
        return $flightOffersData;
    }
    /**
     * comman function to get airport list in default admin panel's language code
     * created date 05-01-2024
     */
    function getAirportList($flightDictionaries)
    {
        $getDefaultLanguageCode = Setting::where('config_key', 'general|site|defaultLanguageCode')->get('value')[0]['value'];
        $flightOffersData = [];
        foreach ($flightDictionaries as $key => $dictionaries) {


            $airport_name = DB::table('airports')->join('airport_i18ns', 'airport_i18ns.airport_id', 'airports.id')->select('airport_i18ns.airport_name')->where('airports.iata_code', $key)->where('airport_i18ns.language_code', strtolower($getDefaultLanguageCode))->first();
            $city_name_en = DB::table('cities')->join('city_i18ns', 'city_i18ns.city_id', 'cities.id')->select('city_i18ns.city_name')->where('cities.iso_code', $key)->where('city_i18ns.language_code', strtolower($getDefaultLanguageCode))->first();
            $country_name_en = DB::table('countries')->join('country_i18ns', 'country_i18ns.country_id', 'countries.id')->select('country_i18ns.country_name')->where('countries.iso_code', $dictionaries['countryCode'])->where('country_i18ns.language_code', strtolower($getDefaultLanguageCode))->first();
            $country_code = Airport::where('iata_code', $key)->value('country_code');
            $country_name_en = DB::table('countries')
                ->join('country_i18ns', 'country_i18ns.country_id', 'countries.id')
                ->where('country_i18ns.language_code', strtolower($getDefaultLanguageCode))
                ->where('iso_code', $country_code)
                ->first();

            $tempArr = [
                'code' => $key,
                'airport_name' => ($airport_name != "") ? $airport_name->airport_name : "",
                'city_name' => ($city_name_en != "") ? $city_name_en->city_name : "",
                'country_name' => ($country_name_en != "") ? $country_name_en->country_name : "",

            ];
            array_push($flightOffersData, $tempArr);
        }
        return $flightOffersData;
    }
    /**
     * comman function to get layovers list
     * created date 08-01-2024
     */
    function getLayoverList($flightOffersDetails){
        $flightOffersData = [];

        $processedSegments = []; // Initialize an array to keep track of segments
        foreach ($flightOffersDetails as $flightOffer) {
            foreach ($flightOffer['itineraries'] as $itineraries) {
                $segments = $itineraries['segments'];
                for ($i = 0; $i < count($segments) - 1; $i++) {
                    $currentFlight = $segments[$i];
                    $nextFlight = $segments[$i + 1];

                    // Check if the current segment has already been processedSegments array
                    $currentSegmentId = $currentFlight['id'];
                    if (!in_array($currentSegmentId, $processedSegments)) {
                        $arrivalTime = $currentFlight['arrival']['at'];
                        $departureTime = $nextFlight['departure']['at'];
                        $difference = getTimeDifference($departureTime, $arrivalTime);

                        $layoverArray = [
                            'fromSegmentId' => $currentFlight['id'],
                            'toSegmentId' => $nextFlight['id'],
                            'layoverDurationText' => $difference['hours'] . 'h ' . $difference['minutes'] . 'm',
                            'layoverDuration' => (($difference['hours'] < 9) ? str_pad($difference['hours'], 2, '0', STR_PAD_LEFT) : $difference['hours']) . ':' . $difference['minutes']
                        ];
                        array_push($flightOffersData, $layoverArray);

                        // Add the current segment to the processedSegments array to avoid duplicates
                        $processedSegments[] = $currentSegmentId;
                    }
                }
            }
        }

        return $flightOffersData;
    }
}
