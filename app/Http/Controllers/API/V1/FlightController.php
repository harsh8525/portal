<?php

/**
 * @package     Flights
 * @subpackage  Flight
 * @Author      Amar Technolabs Pvt. ltd(info@amarinfotech.com)
 * @Copyright(C) 2023 [NAME OF THE ORGANISATION THAT ON BEHALF OF THE CODE WE ARE WORKING].
 * @Version 1.0.0
 * module of the Flights.
 */

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\API\V1\BaseController as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Airport;
use App\Models\GeoRegionLists;
use App\Models\Setting; 
use App\Models\Airline;
use App\Models\Currency;
use App\Models\AirlineI18ns;
use App\Models\Bookings;
use App\Models\CurrencyExchangeRates;
use App\Models\Suppliers;
use App\Models\ServiceType;
use App\Models\Customer;
use App\Models\FlightBookingTraveler;
use App\Traits\AmadeusService;
use Illuminate\Support\Facades\DB;
use App\Traits\ActiveLog;
use Illuminate\Support\Arr;
use URL;

class FlightController extends BaseController
{

    use AmadeusService, ActiveLog;

    /*
     * default controller that use to set default values for this class
     */
    public function __construct()
    {

        $this->perPage = count(Setting::where('config_key', 'general|setting|pagePerAPIRecords')->get('value')) > 0 ? Setting::where('config_key', 'general|setting|pagePerAPIRecords')->get('value')[0]['value'] : "20";

        //set AMADEUS API configuration from config key
        $this->amadeusAPIEnvironment = count(Setting::where('config_key', 'amadeus|api|credential')->get('value')) > 0 ? Setting::where('config_key', 'amadeus|api|credential')->get('value')[0]['value'] : "test";

        if ($this->amadeusAPIEnvironment == 'test') {
            $this->amadeusAPIEndPoint = count(Setting::where('config_key', 'amadeus|api|test|APIEndPoint')->get('value')) > 0 ? trim(Setting::where('config_key', 'amadeus|api|test|APIEndPoint')->get('value')[0]['value']) : "https://test.api.amadeus.com";
            $this->amadeusAPIClientID = count(Setting::where('config_key', 'amadeus|api|test|clientId')->get('value')) > 0 ? Setting::where('config_key', 'amadeus|api|test|clientId')->get('value')[0]['value'] : "zFKYlQPsA1sJjtId13ab1vSE5FyLraqR";
            $this->amadeusAPIClientSecret = count(Setting::where('config_key', 'amadeus|api|test|clientSecret')->get('value')) > 0 ? Setting::where('config_key', 'amadeus|api|test|clientSecret')->get('value')[0]['value'] : "wos5It0hZHUbBAdH";
            $this->amadeusAPIGrantType = count(Setting::where('config_key', 'amadeus|api|test|grantType')->get('value')) > 0 ? Setting::where('config_key', 'amadeus|api|test|grantType')->get('value')[0]['value'] : "client_credentials";
        } else {
            $this->amadeusAPIEndPoint = count(Setting::where('config_key', 'amadeus|api|live|APIEndPoint')->get('value')) > 0 ? trim(Setting::where('config_key', 'amadeus|api|live|APIEndPoint')->get('value')[0]['value']) : "https://test.api.amadeus.com";
            $this->amadeusAPIClientID = count(Setting::where('config_key', 'amadeus|api|live|clientId')->get('value')) > 0 ? Setting::where('config_key', 'amadeus|api|live|clientId')->get('value')[0]['value'] : "zFKYlQPsA1sJjtId13ab1vSE5FyLraqR";
            $this->amadeusAPIClientSecret = count(Setting::where('config_key', 'amadeus|api|live|clientSecret')->get('value')) > 0 ? Setting::where('config_key', 'amadeus|api|live|clientSecret')->get('value')[0]['value'] : "wos5It0hZHUbBAdH";
            $this->amadeusAPIGrantType = count(Setting::where('config_key', 'amadeus|api|live|grantType')->get('value')) > 0 ? Setting::where('config_key', 'amadeus|api|live|grantType')->get('value')[0]['value'] : "client_credentials";
        }

        $this->amadeusAPISecret = count(Setting::where('config_key', 'amadeus|api|secret')->get('value')) > 0 ? Setting::where('config_key', 'amadeus|api|secret')->get('value')[0]['value'] : "";
    }

    /**
     * @OA\Get(
     ** path="/v1/flight/search/airport",
     *   tags={"Flight"},
     *   summary="This will response airport list of the requested keywords ",
     *   description="get airport list on base of requested keywords <br><br>",
     *   operationId="airportSearch",
     *   @OA\Parameter(
            name="body",
            in="query",
            required=false,
            explode=true,
            @OA\Schema(
                 collectionFormat="multi",
                 required={"search"},
                 @OA\Property(property="search", type="string",  ),
            ),
        ), 
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     **/
    public function airportSearch(Request $request)
    {

        $data = [];
        $requestData = $request->only(['search']);
        //set validation for search keyword
        $validator = Validator::make($requestData, [
            'search' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'Invalid request', 'data' => [$validator->errors()]], 200);
        }
        try {

            $getAirportList = Airport::select('id', 'iata_code', 'country_code', 'city_code', 'latitude', 'longitude')
                ->with(['airportName' => function ($airport) {
                    $airport->select(['airport_id', 'airport_name', 'language_code']);
                }, 'getCountry' => function ($country) {
                    $country->select(['id', 'iso_code']);
                }, 'getCity' => function ($city) {
                    $city->select(['id', 'iso_code']);
                }])
                ->whereHas('airportName', function ($q) use ($requestData) {
                    $q->select(['airport_name']);
                    $q->orHaving('airport_name', 'like', '%' . $requestData['search'] . '%');
                })
                ->orWhereHas('getCity.cityCode', function ($q) use ($requestData) {
                    $q->select(['city_name']);
                    $q->orHaving('city_name', 'like', '%' . $requestData['search'] . '%');
                })
                ->orWhere('iata_code', $requestData['search'])
                ->orWhere('city_code', $requestData['search'])
                ->orderByRaw("iata_code = '{$requestData['search']}' DESC, iata_code ASC")
                ->limit($this->perPage)
                ->get()
                ->toArray();


            $getAirportList = collect($getAirportList)->map(function ($airport) {
                $airport_en = '';
                $airport_ar = '';
                $country_en = '';
                $country_ar = '';
                $city_en = '';
                $city_ar = '';

                foreach ($airport['airport_name'] as $airportName) {
                    switch ($airportName['language_code']) {
                        case 'en':
                            $airport_en = $airportName['airport_name'];
                            break;
                        case 'ar':
                            $airport_ar = $airportName['airport_name'];
                            break;
                    }
                }

                foreach ($airport['get_country']['country_code'] as $country) {
                    switch ($country['language_code']) {
                        case 'en':
                            $country_en = $country['country_name'];
                            break;
                        case 'ar':
                            $country_ar = $country['country_name'];
                            break;
                    }
                }

                foreach ($airport['get_city']['city_code'] as $city) {
                    switch ($city['language_code']) {
                        case 'en':
                            $city_en = $city['city_name'];
                            break;
                        case 'ar':
                            $city_ar = $city['city_name'];
                            break;
                    }
                }

                $display_name_en = $airport_en . ', ' . $city_en . ', ' . $country_en;
                $display_name_ar = $airport_ar . ', ' . $city_ar . ', ' . $country_ar;

                return [
                    'id' => $airport['id'],
                    'airport_code' => $airport['iata_code'],
                    'airport_en' => $airport_en,
                    'airport_ar' => $airport_ar,
                    'display_name_en' => $display_name_en,
                    'display_name_ar' => $display_name_ar,
                    'city_en' => $city_en,
                    'city_ar' => $city_ar,
                    'country_en' => $country_en,
                    'country_ar' => $country_ar,
                    'latitude' => $airport['latitude'],
                    'longitude' => $airport['longitude']
                ];
            });

            $getAirports = $getAirportList->values()->toArray();
            
            $activityLog['request'] =  $request->all();
            $activityLog['request_url'] =  $request->url();
            $activityLog['response'] =  $getAirports;
            ActiveLog::createActiveLog($activityLog);

            return $this->sendResponse($getAirports, 'Get Airport List');
            //in success response need to send active airport list with only fields [airport_code, airport_name, city_name, latitude, longitude]

        } catch (Exception $ex) {
            return $this->sendError($data, 'Something went wrong', ['error' => $ex->getMessage()], 500);
        }
    }

    /**
     * @OA\Get(
     ** path="/v1/flight/get-airlines",
     *   tags={"Flight"},
     *   summary="get airlines list",
     *   description="get airline list<br><br>",
     *   operationId="get-airlines",
     *   @OA\Parameter(
     *       name="body",
     *       in="query",
     *       required=false,
     *       explode=true,
     *       @OA\Schema(
     *            collectionFormat="multi",
     *            required={"search"},
     *            @OA\Property(property="search", type="string",  ),
     *            @OA\Property(property="per_page", type="string",  ),
     *       ),
     *   ), 
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     **/
    public function getAirlines(Request $request)
    {
        if ($_GET['per_page']) {
            $this->perPage = $_GET['per_page'];
        }
        $data = [];
        $requestData = $request->only(['search']);
        //set validation for search keyword
        $validator = Validator::make($requestData, []);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'Invalid request', 'data' => [$validator->errors()]], 200);
        }
        try {


            $getAirlineList = Airline::select('id', 'airline_code', 'airline_logo', 'is_domestic', 'status')
                ->with(['airlineCodeName' => function ($airport) {
                    $airport->select(['airline_id', 'airline_name', 'language_code']);
                }])
                ->whereHas('airlineCodeName', function ($q) use ($requestData) {
                    $q->orHaving('airline_name', 'like', '%' . $requestData['search'] . '%');
                })
                ->orWhere('airline_code', $requestData['search'])
                ->paginate($this->perPage);
            $airlineData = collect($getAirlineList->items())->map(function ($airline) {
                $airline_en = '';
                $airline_ar = '';

                foreach ($airline['airlineCodeName'] as $airlineName) {
                    switch ($airlineName['language_code']) {
                        case 'en':
                            $airline_en = $airlineName['airline_name'];
                            break;
                        case 'ar':
                            $airline_ar = $airlineName['airline_name'];
                            break;
                    }
                }

                return [
                    'id' => $airline['id'],
                    'airline_code' => $airline['airline_code'],
                    'airline_logo' => $airline['airline_logo'] ? $airline['airline_logo'] : URL::to('/') . '/assets/images/airlineLogo/' . $airline["airline_code"] . '.png',
                    'is_domestic' => $airline['is_domestic'],
                    'status' => $airline['status'],
                    'airline_en' => $airline_en,
                    'airline_ar' => $airline_ar,
                ];
            });
            $queryString = http_build_query([
                'search' => urlencode($requestData['search']),
                'per_page' => urlencode($this->perPage),
            ]);
            $previousPageUrl = $getAirlineList->previousPageUrl();
            if ($previousPageUrl) {
                $previousPageUrl .= '&search=' . urlencode($requestData['search']);
                $previousPageUrl .= '&per_page=' . urlencode($this->perPage);
            }
            $nextPageUrl = $getAirlineList->nextPageUrl();
            if ($nextPageUrl) {
                $nextPageUrl .= '&search=' . urlencode($requestData['search']);
                $nextPageUrl .= '&per_page=' . urlencode($this->perPage);
            }
            $output = [
                'current_page' => $getAirlineList->currentPage(),
                'data' => $airlineData->values()->toArray(),
                'first_page_url' => $getAirlineList->url(1) . '&' . $queryString,
                'from' => $getAirlineList->firstItem(),
                'last_page' => $getAirlineList->lastPage(),
                'last_page_url' => $getAirlineList->url($getAirlineList->lastPage()) . '&' . $queryString,
                'links' => [
                    [
                        'url' => $previousPageUrl,
                        'label' => '&laquo; Previous',
                        'active' => $getAirlineList->onFirstPage(),
                    ],
                    [
                        'url' => $getAirlineList->url(1) . '&' . $queryString,
                        'label' => '1',
                        'active' => $getAirlineList->currentPage() === 1,
                    ],
                    [
                        'url' => $nextPageUrl,
                        'label' => 'Next &raquo;',
                        'active' => $getAirlineList->hasMorePages(),
                    ],
                ],
                'next_page_url' => $nextPageUrl,
                'path' => $getAirlineList->path() . '?' . $queryString,
                'per_page' => $getAirlineList->perPage(),
                'prev_page_url' => $previousPageUrl,
                'to' => $getAirlineList->lastItem(),
                'total' => $getAirlineList->total(),
            ];

            $activityLog['request'] =  $request->all();
            $activityLog['request_url'] =  $request->url();
            $activityLog['response'] =  $output;
            ActiveLog::createActiveLog($activityLog);
            if ($output) {
                $success = 1;
                return $this->sendResponse($output, 'Airline Listed Successfully!', $success);
            } else {
                $success = [];
                return $this->sendError('Airline List Not Found', $success, 200);
            }
        } catch (Exception $ex) {
            return $this->sendError($data, 'Something went wrong', ['error' => $ex->getMessage()], 500);
        }
    }


    /**
     * @OA\Post(
     *   path="/v1/flight/flight-offers-search",
     *   tags={"Flight"},
     *   summary="Send request for flight offers",
     *   description="pass required values to fetch flight offers :<br>
                                   pass originDevice value one of from web, android or ios,<br>
                                   pass searchType must be one of from one-way, round-trip or multi-city,<br>
                                   pass date must be in Y-m-d format Ex:2023-05-28,<br>
                                   pass travelClass value one of from ECONOMY, PREMIUM_ECONOMY, BUSINESS, FIRST",

     *   operationId="flight-offers-search",
     *   @OA\RequestBody(
     *     required=true,
     *     description="flight offers search", 
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(
     *             required={"agencyId","originDevice","searchType","currencyCode","travelerType","originDestinations","travelers","travelClass","includedCheckedBagsOnly","isDirectFlight"},
     *             @OA\Property(property="agencyId", type="string", default="0", example="0", description="The agency name throught which gt flight offers"),
     *             @OA\Property(property="originDevice", type="string", example="web", description="The originDevice descript that in which kind of device you are searching for flights and it must be one of from web,android, ios"),
     *             @OA\Property(property="searchType", type="string", example="one-way", description="The searchType descript that which kind of trip you want to find it must be one of from one-way, round-trip or multi-city"),
     *             @OA\Property(property="currencyCode", type="string", example="EUR", description="The currency code, as defined in ISO 4217, to reflect the currency in which this amount is expressed."),
     *             @OA\Property(property="languageCode", type="string", example="AR"),
     *             @OA\Property(property="originDestinations", title="originDestinations",
     *                 type="array", 
     *                 description="Origins and Destinations must be properly ordered in time (chronological order in accordance with the timezone of each location) to describe the journey consistently. Dates and times must not be past nor more than 365 days in the future, according to provider settings.Number of Origins and Destinations must not exceed the limit defined in provider settings.",
     *                 @OA\Items(
     *                          @OA\Property(
     *                                      property="originLocationCode",
     *                                      default="SYD", 
     *                                      example="SYD",
     *                                      type="string", 
     *                                      description="Origin location, such as a city or an airport. Currently, only the locations defined in IATA are supported."
     *                                      ),
     *                          @OA\Property(
     *                                      property="destinationLocationCode",
     *                                      default="BKK",
     *                                      example="BKK",
     *                                      type="string", 
     *                                      description="Destination location, such as a city or an airport. Currently, only the locations defined in IATA are supported."
     *                                      ),
     
     *                          @OA\Property( 
     *                                      property="departureDate",
     *                                      example="2023-08-29",
     *                                      type="string",
     *                                      description="Dates are specified in the ISO 8601 YYYY-MM-DD format, e.g. 2018-12-25" 
     *                                      ),     
     *                          @OA\Property( 
     *                                      property="returnDate",
     *                                      example="2023-09-08",
     *                                      type="string", 
     *                                      )     
     *                          )
     *             ),
     *             @OA\Property(property="travelers", type="array",title="travelers", minItems=1, maxItems=18 ,description="travelers in the trip <br>Maximum number of passengers older than 2 yo (CHILD, ADULT, YOUGHT): 9.<br>Each adult can travel with one INFANT so maximum total number of passengers: 18",
     *                              @OA\Items(
     *                                      @OA\Property(
     *                                              property="type",
     *                                              example="ADULT", 
     *                                              type="string",
     *                                              description="traveler type<br>age restrictions : CHILD < 12y, HELD_INFANT < 2y, SEATED_INFANT < 2y, SENIOR >=60y",
     *                                      ),
     *                                      @OA\Property(
     *                                              property="count",
     *                                              example="2",   
     *                                              type="string",
     *                                              description="traveler's count"
     *                                      ),
     
     *                              )
     *              ),
     *              @OA\Property(property="travelClass", title="travelClass", type="string",example="BUSINESS", description="quality of service offered in the cabin where the seat is located in this flight. ECONOMY, PREMIUM_ECONOMY, BUSINESS or FIRST class"),
     *              @OA\Property(property="includedCheckedBagsOnly", title="includedCheckedBagsOnly", type="boolean",default=false,description="for allow includedCheckedBagsOnly true or false for travelers"),
     *              @OA\Property(property="isDirectFlight", title="isDirectFlight", type="boolean",default=false,description="for allow to prefer non stop flights enter value '0'"),
     *           )
     *     ),
     *   ),
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     * */

    public function flightOffersSearch(Request $request)
    {
        $requestData = $request->all();
        
        try {
            //handle json request for the validation using helper function
            $validationResponse = handleJsonRequest($requestData);


            if (isset($validationResponse['error'])) {

                return response()->json(['success' => false, 'message' => 'Invalid request', 'data' => $validationResponse['error']], 400);
            }

            $getBaseCurrency = Currency::where('is_base_currency', '1')->first();

            //create array to pass into amadeus api to get response
            $formattedValues = [];
            $flightOfferData = [];
            $flightOfferData['searchType'] = $requestData['searchType'];
            $flightOfferData['currencyCode'] = $getBaseCurrency['code'];

            //check either requested currency available or not
            $getAvailableCurrency = CurrencyExchangeRates::where('to_currency_code',$requestData['currencyCode'])->where('from_currency_code',$flightOfferData['currencyCode'])->first();
            if(!$getAvailableCurrency)
            {
                $success = [];
                return $this->sendError('Invalid Currency', $success, 200);
            }
            
            $flightOfferData['languageCode'] = ($requestData['languageCode'] == "") ? "AR" : $requestData['languageCode'];
            $flightOfferData['originDestinations'] = [];
            $i = 1;

            //create originDestination array to set array
            foreach ($requestData['originDestinations'] as $data) {

                array_push($formattedValues, $i);
                if ($requestData['searchType'] == 'multi-city') {
                    $tempArray = [
                        'id' => $i,
                        'originLocationCode' =>  $data['originLocationCode'],
                        'destinationLocationCode' =>  $data['destinationLocationCode'],
                        'departureDateTimeRange' => [
                            'date' => $data['departureDate']
                        ],
                    ];

                    $i++;
                    array_push($flightOfferData['originDestinations'], $tempArray);
                } else if ($requestData['searchType'] == 'round-trip') {

                    $tempArray = [
                        'id' => $i,
                        'originLocationCode' =>  $data['originLocationCode'],
                        'destinationLocationCode' =>  $data['destinationLocationCode'],
                        'departureDateTimeRange' => [
                            'date' => $data['departureDate']
                        ],
                    ];
                    $i++;
                    array_push($flightOfferData['originDestinations'], $tempArray);
                    $tempArray = [
                        'id' => $i,
                        'originLocationCode' =>  $data['destinationLocationCode'],
                        'destinationLocationCode' =>  $data['originLocationCode'],
                        'departureDateTimeRange' => [
                            'date' => $data['returnDate']
                        ],
                    ];
                    $i++;
                    array_push($flightOfferData['originDestinations'], $tempArray);
                } else {
                    $tempArray = [
                        'id' => $i,
                        'originLocationCode' =>  $data['originLocationCode'],
                        'destinationLocationCode' =>  $data['destinationLocationCode'],
                        'departureDateTimeRange' => [
                            'date' => $data['departureDate']
                        ],
                    ];
                    $i++;
                    array_push($flightOfferData['originDestinations'], $tempArray);
                }
            }

            //create ids array to pass into cabinRestrictions into searchCriteria for flight filteration
            $originDestinationIds = implode(', ', $formattedValues);

            //create array for travelers details
            $flightOfferData['travelers'] = [];
            $result = [];
            $id = 1;
            foreach ($requestData['travelers'] as $traveler) {
                $count = intval($traveler["count"]);
                for ($i = 1; $i <= $count; $i++) {
                    $entry = [
                        "id" => $id,
                        "travelerType" => $traveler["type"]
                    ];
                    if (isset($traveler["type"]) && $traveler["type"] == 'HELD_INFANT') {
                        $entry["associatedAdultId"] = $i;
                    }
                    $result[] = $entry;
                    $id++;
                }
            }
            $flightOfferData['travelers'] = $result;


            $flightOfferData['sources'] = [
                'GDS'
            ];
            $flightOfferData['searchCriteria']['maxFlightOffers'] = 150;
            //get either includedCheckedBagsOnly true or false 
            if (isset($requestData['includedCheckedBagsOnly']) && $requestData['includedCheckedBagsOnly'] == 1) {

                $flightOfferData['searchCriteria']['pricingOptions']['includedCheckedBagsOnly'] = $requestData['includedCheckedBagsOnly'];
            }
            if (isset($requestData['refundableFare']) && $requestData['refundableFare']  == 1) {
                $flightOfferData['searchCriteria']['pricingOptions']['refundableFare'] = $requestData['refundableFare'];
            }


            $flightOfferData['searchCriteria']['flightFilters']['cabinRestrictions'] = [];
            $tempCabinArray = [
                'cabin' => $requestData['travelClass'],
                'coverage' => "ALL_SEGMENTS",
                'originDestinationIds' => $formattedValues
            ];
            array_push($flightOfferData['searchCriteria']['flightFilters']['cabinRestrictions'], $tempCabinArray);

            //get either direct flght true or false
            if (isset($requestData['isDirectFlight']) && $requestData['isDirectFlight'] == 1) {
                $flightOfferData['searchCriteria']['flightFilters']['connectionRestriction']['maxNumberOfConnections'] = 0;
            }

            //convert array into json to pass into amadeus api
            $postfields = json_encode($flightOfferData, JSON_PRETTY_PRINT);

            //fetch reponse of flight offers using amadeus api
            $flightOffersData = $this->flightOfferSearch($postfields, $requestData['currencyCode']);
            
            $activityLog['request'] =  $request->all();
            $activityLog['request_url'] =  $request->url();
            $activityLog['response'] =  $flightOffersData;
            ActiveLog::createActiveLog($activityLog);
            if (empty($flightOffersData['data'])) {

                return $this->sendResponse($flightOffersData, $flightOffersData['success']);
            } else {

                return $this->sendResponse($flightOffersData, 'Flight offers get Successfully');
            }
        } catch (Exception $e) {
            $success = [];
            return $this->sendError($success, 'Something went wrong', ['error' => $e], 500);
        }
    }

    /**
     * @OA\Post(
     *   path="/v1/flight/flight-offers-price",
     *   tags={"Flight"},
     *   summary="Send request for flight offers price",
     *   description="flight offers price request <br><br>",
     *   operationId="flight-offers-price",
     *   @OA\RequestBody(
     *     required=true,
     *     description="flight offer price Body", 
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(
     *             required={"agencyId","currencyCode","type","flightOffers"},
     *             @OA\Property(property="agencyId", type="string", default="0", description="The agency name throught which get flight offers"),
     *             @OA\Property(property="languageCode", type="string", example="AR"),
     *             @OA\Property(property="currencyCode", type="string", example="EUR", description="The currency code, as defined in ISO 4217, to reflect the currency in which this amount is expressed."),
     *             @OA\Property(property="type", type="string", default="flight-offers-pricing"),
     *             @OA\Property(property="flightOffers",
     *                 type="array", 
     *                 description="list of flight offer to price",
     *                 @OA\Items(
     *                          title="Flight-offer",
     *                          type="object",
     *                          description="pass whole response of origin object from the flight offer search response to get pricing details"
     *                          )
     *             ),
     *           )
     *     ),
     *   ),
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     * */
    public function flightOfferPrice(Request $request)
    {
        $requestData = $request->all();

        

        $flightOffersData = $this->flightOfferPriceGet($requestData);

        $activityLog['request'] =  $request->all();
        $activityLog['request_url'] =  $request->url();
        $activityLog['response'] =  $flightOffersData;
        ActiveLog::createActiveLog($activityLog);
        return $this->sendResponse($flightOffersData, 'Flight offers price get Successfully');
    }

    /**
     * @OA\Post(
     *   path="/v1/flight/flight-offers-price-upselling",
     *   tags={"Flight"},
     *   summary="Send request for flight offers price with amenities",
     *   description="flight offers price request with amenities <br>",
     *   operationId="flight-offers-price-upselling",
     *   @OA\RequestBody(
     *     required=true,
     *     description="flight offer price with amenities Body", 
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(
     *             required={"type","flightOffers"},
     *             @OA\Property(property="type", type="string", default="flight-offers-pricing"),
     *             @OA\Property(property="flightOffers",
     *                 type="array", 
     *                 description="list of flight offer to price",
     *                 @OA\Items(
     *                          title="Flight-offer",
     *                          type="object",
     *                          description="pass whole response of origin object from the flight offer search response to get pricing details with amenities"
     *                          )
     *             ),
    
     *           )
     *     ),
     *   ),
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     * */
    public function flightOfferPriceUpselling(Request $request)
    {
        $requestData = $request->all();


        $requestTemp = [
            'data' => $requestData
        ];
        $postfields = json_encode($requestTemp, JSON_PRETTY_PRINT);


        $flightOffersData = $this->flightOfferPriceUpsellingGet($postfields);

        $activityLog['request'] =  $request->all();
        $activityLog['request_url'] =  $request->url();
        $activityLog['response'] =  $flightOffersData;
        ActiveLog::createActiveLog($activityLog);
        return $this->sendResponse($flightOffersData, 'Flight offers price with amenities get Successfully');
    }
    /**
     * @OA\Post(
     *   path="/v1/flight/flight-offers-seatmap-amenities",
     *   tags={"Flight"},
     *   summary="Send request for flight offers seatmap amenities",
     *   description="flight offers amenities request <br><br>",
     *   operationId="flight-offers-price-amenities",
     *   @OA\RequestBody(
     *     required=true,
     *     description="seatmap Body", 
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(
     *             required={"data"},
     *             @OA\Property(property="data",
     *                 type="array", 
     *                 description="pass response of orogin object to get amenities",
     *                 @OA\Items(
     *                          title="Flight-offer",
     *                          type="object",
     *                          description="pass whole response of origin object from the flight offer search response to get amenities details"
     *                          )
     *             ),
     *           )
     *     ),
     *   ),
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     * */
    public function flightOfferPriceAmenities(Request $request)
    {
        $requestData = $request->all();


        $postfields = json_encode($requestData, JSON_PRETTY_PRINT);

        $flightOffersData = $this->flightOfferSeatmapAmenities($postfields);

        $activityLog['request'] =  $request->all();
        $activityLog['request_url'] =  $request->url();
        $activityLog['response'] =  $flightOffersData;
        ActiveLog::createActiveLog($activityLog);
        return $this->sendResponse($flightOffersData, 'Flight offers seatmap get successfully');
    }

    /**
     * @OA\Post(
     *   path="/v1/flight/flight-order-create",
     *   tags={"Flight"},
     *   summary="Send request for flight order create",
     *   description="flight order create request <br><br>",
     *   operationId="flight-order-create",
     *   @OA\RequestBody(
     *     required=true,
     *     description="flight order create Body", 
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(
     *             required={"agencyId","currencyCode","type","flightOffers","data","supplier"},
     *             @OA\Property(property="agencyId", type="string", default="0", description="The agency name throught which get flight offers"),
     *             @OA\Property(property="customer_id", type="string",default="0"),
     *             @OA\Property(property="languageCode", type="string",default="EN"),
     *             @OA\Property(property="currencyCode", type="string", example="EUR", description="The currency code, as defined in ISO 4217, to reflect the currency in which this amount is expressed."),
     *             @OA\Property(property="supplier", type="string", default="AMADEUS", description="enter supplier's name"),
     *             @OA\Property(property="data", type="object", 
     *             @OA\Property(property="type", type="string",default="flight-order"),
     *             @OA\Property(property="flightOffers",
     *                 type="array", 
     *                 description="list of flight offer",
     *                 @OA\Items(
     *                          title="flight-offer",
     *                          type="object",
     *                          description="pass whole response of flight offer pricing response to create order"
     *                          )
     *             ),
     *             @OA\Property(property="travelers",
     *                 type="array", 
     *                 description="list of travelers",
     *                 @OA\Items(
     *                          title="traveler element",
     *                          type="object",
     *                          description="the traveler of the trip",
     
     *                              @OA\Property(property="dateOfBirth", type="string",description="The date of birth in ISO 8601 format (yyyy-mm-dd)"),
     *                              @OA\Property(property="name", type="object",
     *                                  @OA\Property(property="firstName", type="string",description="First name."),
     *                                  @OA\Property(property="lastName", type="string",description="Last name."),
     *                                  @OA\Property(property="middleName", type="string",description="Middle name(s), for example 'Lee' in 'John Lee Smith'.")
     *                               ),
     *                               @OA\Property(property="gender", type="string", example="FEMALE",description="Gender for individual", enum={"MALE", "FEMALE", "UNSPECIFIED", "UNDISCLOSED"} ),
     *                                    @OA\Property(property="contact", type="object",
     *                                          @OA\Property(property="emailAddress", type="string",example="support@mail.com",description="Email address (e.g. john@smith.com)"),
     *                                          @OA\Property(property="phones",
     *                                          type="array",description="list of travelers",
     *                                          @OA\Items(
     *                                                          title="Phone",type="object",description="Phone numbers",
     *                                                          @OA\Property(property="deviceType", type="string",description="Type of the device (LANDLINE, MOBILE or FAX)",enum={"MOBILE", "LANDLINE", "FAX"}),
     *                                                           @OA\Property(property="countryCallingCode", type="string",description="Country calling code of the phone number, as defined by the International Communication Union. Examples - '1' for US, '371' for Latvia."),
     *                                                           @OA\Property(property="number", default="0000000000",pattern="[0-9] {1,15}",type="string",description="Phone number. Composed of digits only. The number of digits depends on the country.")
     *                                                   )
     *                                          ),
     *                                          @OA\Property(property="documents",
     *                                          type="array",description="Advanced Passenger Information - regulatory identity documents - SSR DOCS & DOCO elements",
     *                                          @OA\Items(
     *                                                          title="traveler documents",type="object",description="documents of the traveler",
     *                                                          @OA\Property(property="documentType", type="string", example="VISA",description="the nature/type of the document",enum={"VISA", "PASSPORT","IDENTITY_CARD","KNOWN_TRAVELER","REDRESS"}),
     *                                                          @OA\Property(property="birthPlace", type="string", description="Birth place as indicated on the document"),
     *                                                          @OA\Property(property="issuanceLocation", type="string", description="A more precise information concerning the place where the document has been issued, when available. It may be a country, a state, a city or any other type of location. e.g. New-York"),
     *                                                          @OA\Property(property="issuanceDate", type="string", description="Date at which the document has been issued."),
     *                                                          @OA\Property(property="number", type="string", description="The document number (shown on the document) . E.g. QFU514563221J"),
     *                                                          @OA\Property(property="expiryDate", type="string", description="Date after which the document is not valid anymore."),
     *                                                          @OA\Property(property="issuanceCountry",type="string",pattern="[a-zA-Z]{2}", description="ISO 3166-1 alpha-2 of the country that issued the document"),
     *                                                          @OA\Property(property="validityCountry",example="IN",pattern="[a-zA-Z]{2}",type="string", description="ISO 3166-1 alpha-2 of the country where the document is valid"),
     *                                                          @OA\Property(property="nationality",pattern="[a-zA-Z]{2}",type="string", description="ISO 3166-1 alpha-2 of the nationality appearing on the document"),
     *                                                          @OA\Property(property="holder",example="true",type="string", description="boolean to specify if the traveler is the holder of the document")
     
     *                                                   )
     *                                          )
     *                                      )
     
     *                          )
     *             ),
     *             @OA\Property(property="remarks", type="object",description="remarks", title="Remarks",  
     *                          @OA\Property(property="general",
     *                              type="array",description="list of general remarks",minItems=0,maxItems=200,
     *                              @OA\Items(
     *                                        title="GeneralRemark",type="object",
     *                                        @OA\Property(property="subType", example="GENERAL_MISCELLANEOUS",type="string",description="general remark type",enum={"GENERAL_MISCELLANEOUS", "CONFIDENTIAL", "INVOICE", "QUALITY_CONTROL", "BACKOFFICE", "FULFILLMENT", "ITINERARY", "TICKETING_MISCELLANEOUS", "TOUR_CODE"}),
     *                                                     @OA\Property(property="text",example="PASSENGER NEED ASSISTANCE", type="string",description="remark free text")
     *                                                   )
     *                                          )
     
     *                         ),
     *            @OA\Property(property="ticketingAgreement", type="object",title="	Ticketing Agreement",description="ticketing agreement",
     *                          @OA\Property(property="option", type="string",example="DELAY_TO_QUEUE",description="Ticketing agreement option<br />CONFIRM, when the payment is done<br />DELAY_TO_QUEUE, queue the reservation at a wished date if the payment is not done<br />DELAY_TO_CANCEL, cancel the reservation at a wished date if the payment is not done<br />Queueing and cancellation occurs at local date and time. When no time is specified, reservation is queued or cancelled at 00:00.",enum={"CONFIRM", "DELAY_TO_QUEUE", "DELAY_TO_CANCEL"}),
     *                          @OA\Property(property="delay", type="string",description="Delay before applying automatic process if no issuance in days"),
     *                        ),
     * @OA\Property(
     *     property="contacts",
     *     type="array",
     *     description="List of general contact information",
     *     @OA\Items(
     *         title="Contact",
     *         type="object",
     *         description="Contact information",
     *         @OA\Property(
     *             property="addresseeName",
     *             type="object",description="name",
     *             @OA\Property(property="firstName", type="string", description="First Name."),
     *             @OA\Property(property="lastName", type="string", description="Last Name.")
     *         ),
     *         @OA\Property(property="companyName", example="AMADEUS", type="string", description="Company name"),
     *         @OA\Property(property="purpose", type="string", description="The purpose for which this contact is to be used.",enum={"STANDARD", "INVOICE", "STANDARD_WITHOUT_TRANSMISSION"}),
     *         @OA\Property(property="phones", type="array", maxItems=3, @OA\Items(
     *             title="Phone",
     *             type="object",
     *             description="Phone information",
     *             @OA\Property(property="deviceType", type="string", description="Type of the device (Landline, Mobile or Fax)",enum={"MOBILE", "LANDLINE", "FAX"}),
     *             @OA\Property(property="countryCallingCode", pattern="[0-9+]{2,5}", type="string", description="Country calling code of the phone number."),
     *             @OA\Property(property="number", pattern="[0-9]{1,15}", type="string", description="Phone number")
     *         )),
     *         @OA\Property(property="emailAddress", type="string", description="Email address",example="support@mail.com"),
     *         @OA\Property(property="address", type="object", title="Address information", description="Traveler's address information",
     *             @OA\Property(property="lines", type="array", @OA\Items(type="string", description="Street address, apartment, suite, etc.")),
     *             @OA\Property(property="postalCode", example="28014", type="string", description="Postal code of the country/country code"),
     *             @OA\Property(property="cityName", pattern="[a-zA-Z -]{1,35}", type="string", description="Full city name"),
     *             @OA\Property(property="countryCode", pattern="[a-zA-Z]{2}", type="string", description="Country code")
     *         )
     *     )
     * )

     *           )  
     *           ),
     
     *     ),
     *   ),
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     * */
    public function flightOrderCreate(Request $request)
    {
        $requestData = $request->all();
        

        $validator = Validator::make($requestData['data'], [
            //requested json validation
            'type' => 'required|in:flight-order',
            'flightOffers' => 'required|array|min:1',
            'travelers' => 'required|array|min:1',
            'travelers.*.dateOfBirth' => 'required|date_format:Y-m-d|before:today',
            'travelers.*.name.firstName' => 'required|string',
            'travelers.*.name.lastName' => 'required|string',
            'travelers.*.name.middleName' => 'nullable|string',
            'travelers.*.gender' => 'required|string|in:MALE,FEMALE,UNSPECIFIED,UNDISCLOSED',
            'travelers.*.contact.emailAddress' => 'required|email',
            'travelers.*.contact.phones' => 'required|array|min:1',
            'travelers.*.contact.phones.*.deviceType' => 'required|string|in:MOBILE,LANDLINE,FAX',
            'travelers.*.contact.phones.*.countryCallingCode' => 'required|string',
            'travelers.*.contact.phones.*.number' => 'required|string',
            'travelers.*.contact.documents' => 'required|array|min:1',
            'travelers.*.contact.documents.*.documentType' => 'required|string|in:VISA,PASSPORT,IDENTITY_CARD,KNOWN_TRAVELER,REDRESS',
            // Add more validation rules as needed

            // Remarks validation
            'remarks.general' => 'nullable|array',
            'remarks.general.*.subType' => 'required|string|in:GENERAL_MISCELLANEOUS,CONFIDENTIAL,INVOICE,QUALITY_CONTROL,BACKOFFICE,FULFILLMENT,ITINERARY,TICKETING_MISCELLANEOUS,TOUR_CODE',
            'remarks.general.*.text' => 'required|string',

            // Ticketing Agreement validation
            'ticketingAgreement.option' => 'required|in:CONFIRM,DELAY_TO_QUEUE,DELAY_TO_CANCEL',
            'ticketingAgreement.delay' => 'required|string',

            // Contacts validation
            'contacts' => 'required|array|min:1',
            'contacts.*.addresseeName.firstName' => 'required|string',
            'contacts.*.addresseeName.lastName' => 'required|string',
            'contacts.*.companyName' => 'required|string',
            'contacts.*.purpose' => 'required|string|in:STANDARD,INVOICE,STANDARD_WITHOUT_TRANSMISSION',
            'contacts.*.phones' => 'required|array|min:1',
            'contacts.*.phones.*.deviceType' => 'required|string|in:MOBILE,LANDLINE,FAX',
            'contacts.*.phones.*.countryCallingCode' => 'required|string',
            'contacts.*.phones.*.number' => 'required|string',
            'contacts.*.emailAddress' => 'required|email',
            'contacts.*.address.lines' => 'required|array|min:1',
            'contacts.*.address.postalCode' => 'required|string',
            'contacts.*.address.cityName' => 'required|string',
            'contacts.*.address.countryCode' => 'required|string',

        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'Invalid request', 'data' => [$validator->errors()]], 200);
        }

        
        // Function to add 'id' field to each traveler
        $requestData['data']['travelers'] = array_map(function ($traveler, $index) {
            $traveler = array_merge(['id' => $index + 1], $traveler);
            return $traveler;
        }, $requestData['data']['travelers'], array_keys($requestData['data']['travelers']));

        
        if($requestData['supplier'] == 'AMADEUS')
        {
            if(!isset($requestData['paymentResponse']))
            {
                $requestData['data']['ticketingAgreement']['option'] = 'DELAY_TO_QUEUE';
            }
            $flightOffersData = $this->flightNewOrderCreate($requestData);
        }
        try{
            // echo "<pre>";print_r($flightOffersData);die;
            if(isset($flightOffersData['data']) && !empty($flightOffersData['data']))
            {
                //request has payement response start
                if (isset($requestData['result']['code'])) {
                    $successCodePattern = '/^(000\.000\.|000\.100\.1|000\.[36])/';
                    $successManualReviewCodePattern = '/^(000\.400\.0|000\.400\.100)/';
                    //success status
                    if (preg_match($successCodePattern, $resultJson['result']['code']) || preg_match($successManualReviewCodePattern, $resultJson['result']['code'])) {

                        //get existing supplier_ref_id from table to update the payment status
                        
                        $bookedDetails = Bookings::where('supplier_booking_ref',$flightOffersData['data']['id'])->get()->toArray();
                        echo "<pre>";print_r($bookedDetails);
                        $sccuess = 'Your payment has been processed successfully';
                    } else {
                        //fail case
                        $failed_msg = $resultJson['result']['description'];

                        if (isset($resultJson['card']['bin'])) {
                          $blackBins = require_once('includes/blackBins.php');
                          $searchBin = $resultJson['card']['bin'];
                          if (in_array($searchBin,$blackBins)) {
                            if ($this->lang == 'ar') {
                              $failed_msg = 'عذرا! يرجى اختيار خيار الدفع "مدى" لإتمام عملية الشراء بنجاح.';
                            }else{
                              $failed_msg = 'Sorry! Please select "mada" payment option in order to be able to complete your purchase successfully.';
                            }

                          }
                        }

                    }
                }
                //request has payement response end
               
                $bookingData = [];
                
                foreach($flightOffersData['data']['flightOffers'] as &$flightDetail)
                {
                    //count itineraries's count and get orogin and destination code to check trip type start
                    $itineraryCount = count($flightDetail['itineraries']);

                    if ($itineraryCount == 1) {
                        // Single itinerary
                        $departure = $flightDetail['itineraries'][0]['segments'][0]['departure']['iataCode'];
                        $arrival = $flightDetail['itineraries'][0]['segments'][count($flightDetail['itineraries'][0]['segments']) - 1]['arrival']['iataCode'];
                        $tripType =  "From $departure to $arrival (One-way)";
                        $searchType = 'one-way';
                    } elseif ($itineraryCount == 2) {
                        // Round-trip or multi-city
                        $firstDeparture = $flightDetail['itineraries'][0]['segments'][0]['departure']['iataCode'];
                        $secondArrival = $flightDetail['itineraries'][1]['segments'][count($flightDetail['itineraries'][1]['segments']) - 1]['arrival']['iataCode'];

                        if ($firstDeparture == $secondArrival) {
                            $tripType =  "From {$flightDetail['itineraries'][0]['segments'][0]['departure']['iataCode']} to {$flightDetail['itineraries'][1]['segments'][0]['departure']['iataCode']} (Round-trip)\n";
                            $searchType = 'round-trip';
                        } else {
                            // Multi-city
                            $segments = [];
                            foreach ($flightDetail['itineraries'] as $itinerary) {
                                foreach ($itinerary['segments'] as $segment) {
                                    $segments[] = $segment['departure']['iataCode'] . '-' . $segment['arrival']['iataCode'];
                                }
                            }
                            $multiCityRoute = implode(' via ', $segments);
                            $tripType =  "From {$flightDetail['itineraries'][0]['segments'][0]['departure']['iataCode']} via $multiCityRoute to {$flightDetail['itineraries'][$itineraryCount - 1]['segments'][count($flightDetail['itineraries'][$itineraryCount - 1]['segments']) - 1]['arrival']['iataCode']} (Multi-city)\n";
                            $searchType = 'multi-city';
                        }
                    } else {
                        // Multi-city
                        $segments = [];
                        foreach ($flightDetail['itineraries'] as $itinerary) {
                            foreach ($itinerary['segments'] as $segment) {
                                $segments[] = $segment['departure']['iataCode'] . '-' . $segment['arrival']['iataCode'];
                            }
                        }
                        $multiCityRoute = implode(' via ', $segments);
                        $tripType =  "From {$flightDetail['itineraries'][0]['segments'][0]['departure']['iataCode']} via $multiCityRoute to {$flightDetail['itineraries'][$itineraryCount - 1]['segments'][count($flightDetail['itineraries'][$itineraryCount - 1]['segments']) - 1]['arrival']['iataCode']} (Multi-city)\n";
                        $searchType = 'multi-city';
                    }
                    //count itineraries's count and get orogin and destination code to check trip type end

                    $bookingData['customer_currency'] = $flightDetail['processedPrice']['currency'];
                    $bookingData['customer_language_code'] = $requestData['languageCode'];

                    $bookingData['sub_total'] = $flightDetail['processedPrice']['grandTotal'];
                    $bookingData['tax'] = $flightDetail['processedPrice']['vat'];
                    $bookingData['s_tax'] = $flightDetail['processedPrice']['serviceFee'];
                    $bookingData['s_charge'] = $flightDetail['processedPrice']['customerServiceCharge'];
                    $bookingData['s_discount_type'] = "0";
                    $bookingData['s_discount_value'] = "0";
                    $bookingData['s_discount'] = "0";
                    $bookingData['t_discount_type'] = "0";
                    $bookingData['t_discount_value'] = "0";
                    $bookingData['t_discount'] = "0";
                    $bookingData['t_markup_type'] = $flightDetail['processedPrice']['markupType'];
                    $bookingData['t_markup_value'] = $flightDetail['processedPrice']['markupValue'];
                    $bookingData['t_markup'] = "0";
                    $bookingData['booking_details'] = json_encode($flightOffersData);
                    $bookingData['booking_status'] = "confirmed";
                    $bookingData['admin_currency'] = "SAR";
                    $bookingData['currency_conversion_rate'] = $flightDetail['processedPrice']['exchangeRate'];
                    $bookingData['currency_markup'] = $flightDetail['processedPrice']['margin'];

                    //save admin currency details into array to create
                    $bookingData['admin_sub_total'] = $flightDetail['processedPrice']['adminGrandTotal'];
                    // $bookingData['admin_tax'] = $flightDetail['processedPrice']['adminVat'];
                    $bookingData['admin_tax'] = Setting::where('config_key', 'general|site|defaultVatPercentage')->get('value')[0]['value'];
                    // $bookingData['admin_s_tax'] = $flightDetail['processedPrice']['adminServiceFee'];
                    $bookingData['admin_s_tax'] = $flightDetail['processedPrice']['adminVat'];
                    $bookingData['admin_s_charge'] = $flightDetail['processedPrice']['adminServiceCharge'];
                    $bookingData['admin_currency_conversion_rate'] = $flightDetail['processedPrice']['adminExchangeRate'];
                    $bookingData['admin_currency_markup'] = $flightDetail['processedPrice']['adminCurrencyMarkup'];

                    //generate random unique string for booking ref. value with fix length 20
                    $uniqueRandomString = generateBookingRef();
                    $bookingData['booking_ref'] = $uniqueRandomString;
                    $bookingData['supplier_booking_ref'] = $flightOffersData['data']['id'];
                    $bookingData['pnr_number'] = $flightOffersData['data']['associatedRecords'][0]['reference'];
                    $bookingData['supplier_id'] = Suppliers::where('code',$flightDetail['processedPrice']['supplier'])->get('id')[0]['id'];
                    $bookingData['search_type'] = $searchType;
                    $bookingData['booking_date'] = date('Y-m-d');
                    $bookingData['service_id'] = ServiceType::where('code','Flight')->get('id')[0]['id'];
                    if($requestData['customer_id'] == '0')
                    {
                        $bookingData['is_guest'] = 'true';
                        
                        if (!empty($flightOffersData['data']['travelers'])) {
                            $travelerData = $flightOffersData['data']['travelers'][0];

                            $customerSignUpData = [];

                            foreach ($travelerData as $key => $value) {
                                switch ($key) {
                                    
                                    case 'dateOfBirth':
                                        $customerSignUpData['date_of_birth'] = $value;
                                        break;
                                    case 'gender':
                                        $customerSignUpData['gender'] = strtolower($value);
                                        break;
                                    case 'name':
                                        // Loop through the "name" array
                                        foreach ($value as $nameKey => $nameValue) {
                                            switch ($nameKey) {
                                                case 'firstName':
                                                    $customerSignUpData['first_name'] = $nameValue;
                                                    break;
                                                case 'lastName':
                                                    $customerSignUpData['last_name'] = $nameValue;
                                                    break;
                                                case 'middleName':
                                                    $customerSignUpData['middle_name'] = $nameValue ?? "";
                                                    break;
                                                
                                            }
                                        }
                                        break;
                                    case 'contact':
                                        $customerSignUpData['mobile'] = '+' . $value['phones'][0]['countryCallingCode'] . ' ' . $value['phones'][0]['number'];
                                        $customerSignUpData['email'] = $value['emailAddress'];
                                        
                                        break;
                                    
                                }
                                

                            }
                            // Check if the email address already exists in the database
                            $existingCustomer = Customer::where('email', $customerSignUpData['email'])->where('status', '!=', 'deleted')->first();

                            
                            if ($existingCustomer) {
                                $bookingData['customer_id'] = $existingCustomer->id;
                                $bookingData['is_guest'] = 'false';
                            } else {
                                // Sign up a new customer
                                // unset($customerSignUpData['middle_name']);
                                $newCustomer = Customer::create([
                                    'first_name' => $customerSignUpData['first_name'],
                                    'last_name' => $customerSignUpData['last_name'],
                                    'email' => $customerSignUpData['email'],
                                    'mobile' => $customerSignUpData['mobile'],
                                    'date_of_birth' => $customerSignUpData['date_of_birth'],
                                    'gender' => $customerSignUpData['gender']
                                ]);

                                // Retrieve the new customer's ID
                                $bookingData['customer_id'] = $newCustomer->id;
                            }
                        }
                    }
                    else
                    {
                        $bookingData['is_guest'] = 'false';
                        $bookingData['customer_id'] = $requestData['customer_id'];
                    }
                    $bookingData['agency_id'] = $requestData['agencyId'];
                    $bookingData['description'] = $tripType;
                    

                    //save booking details
                    $saveFlightBooking = Bookings::create($bookingData);
                    if($saveFlightBooking)
                    {
                        foreach($flightDetail['travelerPricings'] as $travelerPricing)
                        {
                            // Retrieve the corresponding traveler based on travelerId
                            $traveler = collect($flightOffersData['data']['travelers'])->firstWhere('id', $travelerPricing['travelerId']);
                            $travelersArr = [
                                'booking_id' => $saveFlightBooking->id,
                                'traveler_total' => $travelerPricing['processedPrice']['totalConverted'],
                                'traveler_base_fare' => $travelerPricing['processedPrice']['baseFareConverted'],
                                'traveler_tax' => $travelerPricing['processedPrice']['taxTotalConverted'],
                                'traveler_s_tax' => $travelerPricing['processedPrice']['travelerVAT'],
                                'admin_total' => $travelerPricing['processedPrice']['adminTotalConverted'],
                                'admin_base_fare' => $travelerPricing['processedPrice']['adminBaseFareConverted'],
                                'admin_tax' => $travelerPricing['processedPrice']['adminTaxTotalConverted'],
                                'admin_s_tax' => $travelerPricing['processedPrice']['adminTravelerVAT'],
                                'traveler_currency' => $travelerPricing['processedPrice']['currency'],
                                'admin_currency' => 'SAR',
                                'booking_class' => 'Z',
                                'booking_cabin' => 'BUSINESS',
                                'traveler_id' => $travelerPricing['processedPrice']['traveler_id'],
                                'traveler_type' => $travelerPricing['processedPrice']['traveler_type'],
                                'gender' => $traveler['gender']
                            ];
                            
                            FlightBookingTraveler::create($travelersArr);
                        }
                    }
                }
                
               
                $activityLog['request'] =  $request->all();
                $activityLog['request_url'] =  $request->url();
                $activityLog['response'] =  $flightOffersData;
                ActiveLog::createActiveLog($activityLog);
                return $this->sendResponse($flightOffersData, 'Flight Order Created Successfully');
            }
            else
            {
                $success = [];
                return $this->sendError($flightOffersData, $success, 200);
            }
        } catch (Exception $e) {
            $success = [];
            return $this->sendError($success, 'Something went wrong', ['error' => $e], 500);
        }
        
    }
    
}
