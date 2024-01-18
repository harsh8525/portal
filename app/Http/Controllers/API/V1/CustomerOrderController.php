<?php

/**
 * @package     Customer Order
 * @subpackage  Customre Order
 * @Author      Amar Technolabs Pvt. ltd(info@amarinfotech.com)
 * @Copyright(C) 2023 [NAME OF THE ORGANISATION THAT ON BEHALF OF THE CODE WE ARE WORKING].
 * @Version 1.0.0
 * module of the Customer Order.
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
use App\Traits\AmadeusService;
use Illuminate\Support\Facades\DB;
use App\Traits\ActiveLog;
use Illuminate\Support\Arr;
use Auth;
use URL;

class CustomerOrderController extends BaseController
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
     ** path="/v1/customer/bookings",
     *   security={
     *     {"bearerAuth": {}}
     *   },
     *   tags={"Booking"},
     *   summary="get customer booking list",
     *   description="get authorized customer's bookings list<br>",
     *   operationId="customerBookings",
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
    public function getCustomerOrders(Request $request)
    {
         
        try {
            if (isset($request->per_page) && $request->per_page != "") {
                $this->perPage = $request->per_page;
            } else {
                $this->perPage = Setting::where('config_key', 'general|setting|pagePerAPIRecords')->value('value');
            }
            $customerBookingDetails = [];
            $modifiedCustomerDetailsArr = [];

            $query = Bookings::query();
            $query->select('bookings.*');
            $query->with(['getFlightBookingTraveler','getServiceType','getSupplier','getCustomer','getAgency']);
            $query->where('customer_id', Auth::id());

            $bookingDetails = $query->paginate($this->perPage);

            foreach ($bookingDetails as $data) {
                $flightOfferDetail = json_decode($data['booking_details'], true);

                $customerBookingDetails['booking_detail'] = $data;
                // Display airline list into response
                $customerBookingDetails['airlineList'] = $this->getAirlineList($flightOfferDetail['data']['flightOffers']);
                
                // Display airport list into response
                $customerBookingDetails['airportList'] = $this->getAirportList($flightOfferDetail['dictionaries']['locations']);

                //display layover list into response
                $customerBookingDetails['layover'] = $this->getLayoverList($flightOfferDetail['data']['flightOffers']);

                array_push($modifiedCustomerDetailsArr, $customerBookingDetails);
            }

            $modifiedCustomerDetailsCollection = collect($modifiedCustomerDetailsArr);
            // Pagination information
            $paginationInformation = [
                'total' => $bookingDetails->total(),
                'per_page' => $bookingDetails->perPage(),
                'current_page' => $bookingDetails->currentPage(),
                'last_page' => $bookingDetails->lastPage(),
                'from' => $bookingDetails->firstItem(),
                'to' => $bookingDetails->lastItem(),
            ];
            $perPageParameter = $this->perPage;
            $queryString = "per_page=$perPageParameter";
            $output = [
                    'current_page' => $bookingDetails->currentPage(),
                    'data' => $modifiedCustomerDetailsCollection->values()->toArray(),
                    'first_page_url' => $bookingDetails->url(1) . '&' . $queryString,
                    'from' => $bookingDetails->firstItem(),
                    'last_page' => $bookingDetails->lastPage(),
                    'last_page_url' => $bookingDetails->url($bookingDetails->lastPage()) . '&' . $queryString,
                    'links' => [
                        [
                            'url' => $bookingDetails->previousPageUrl() . '&' . $queryString,
                            'label' => '&laquo; Previous',
                            'active' => $bookingDetails->onFirstPage(),
                        ],
                        [
                            'url' => $bookingDetails->url(1) . '&' . $queryString,
                            'label' => '1',
                            'active' => $bookingDetails->currentPage() === 1,
                        ],
                        [
                            'url' => $bookingDetails->nextPageUrl() . '&' . $queryString,
                            'label' => 'Next &raquo;',
                            'active' => $bookingDetails->hasMorePages(),
                        ],
                    ],
                    'next_page_url' => $bookingDetails->nextPageUrl() . '&' . $queryString,
                    'path' => $bookingDetails->path() . '?' . $queryString,
                    'per_page' => $bookingDetails->perPage(),
                    'prev_page_url' => $bookingDetails->previousPageUrl() . '&' . $queryString,
                    'to' => $bookingDetails->lastItem(),
                    'total' => $bookingDetails->total(),
                ];

                if ($output) {
                $success = 1;
                return $this->sendResponse($output, 'Get Customer Booking Orders List Successfully!', $success);
                } else {
                    $success = [];
                    return $this->sendError('Booking List Not Found', $success, 200);
                }
                

            //in success response need to send active airport list with only fields [airport_code, airport_name, city_name, latitude, longitude]

        } catch (Exception $ex) {
            return $this->sendError($data, 'Something went wrong', ['error' => $ex->getMessage()], 500);
        }
    }
    /**
     * @OA\Get(
     ** path="/v1/customer/booking-detail",
     *   tags={"Booking"},
     *   summary="get booking detail",
     *   description="get flight order booking detail using booking id<br><br>",
     *   operationId="flightBookingOrder",
     *   @OA\Parameter(
            name="body",
            in="query",
            required=false,
            explode=true,
            @OA\Schema(
                 collectionFormat="multi",
                 required={"booking_id"},
                 @OA\Property(property="booking_id", type="string"),
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
    public function getFlightOrder(Request $request)
    {
        
        $data = [];
        $requestData = $request->only(['booking_id']);
        //set validation for search keyword
        $validator = Validator::make($requestData, [
            'booking_id' => 'required|exists:bookings,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'Invalid request', 'data' => [$validator->errors()]], 200);
        }
        try {
            
            $flightOrderData = $this->getFlightOrderDetails($requestData['booking_id']);
            
            return $this->sendResponse($flightOrderData, 'Booking Details Fetched Successfully');
            

        } catch (Exception $ex) {
            return $this->sendError($data, 'Something went wrong', ['error' => $ex->getMessage()], 500);
        }
    }
    
}
