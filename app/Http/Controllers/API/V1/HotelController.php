<?php

/**
 * @package     Hotels
 * @subpackage  Hotel
 * @Author      Amar Technolabs Pvt. ltd(info@amarinfotech.com)
 * @Copyright(C) 2023 [NAME OF THE ORGANISATION THAT ON BEHALF OF THE CODE WE ARE WORKING].
 * @Version 1.0.0
 * module of the Hotels.
 */

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\API\V1\BaseController as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Airport;
use App\Models\City;
use App\Models\Setting;
use App\Models\Airline;
use App\Models\AirlineI18ns;
use App\Models\Suppliers;
use App\Traits\WithinEarth;
use App\Traits\HotelBeds;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client;
use Carbon\Carbon;
use URL, File;

class HotelController extends BaseController
{

    use WithinEarth, HotelBeds;

    /*
     * default controller that use to set default values for this class
     */
    public function __construct()
    {
        $this->perPage = count(Setting::where('config_key', 'general|setting|pagePerAPIRecords')->get('value')) > 0 ? Setting::where('config_key', 'general|setting|pagePerAPIRecords')->get('value')[0]['value'] : "20";
    }

    /**
     * @OA\Post(
     ** path="/v1/hotel/hotel-auto-search",
     *   security={
     *     {"bearerAuth": {}}
     *   },
     *   tags={"Hotel"},
     *   summary="This will response hotel list of the requested keywords ",
     *   description="Get hotel list on base of requested keywords(City name or Hotel name),<br>
                      The language code (e.g., 'en' for English, 'ar' for Arabic)<br><br>",
     *   operationId="hotel-auto-search",
     *   @OA\RequestBody(
     *     required=true,
     *     description="get hotel list on base of requested keywords", 
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(
     *             required={"search"},
     *             @OA\Property(property="search", type="string", description=" Represents the keyword used to search for either a hotel name or city name."),
     *             @OA\Property(property="languageCode", default="en", type="string", description="Denotes the language code(en/ar) used for localization or specifying the language of the search results")
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
    public function hotelSearch(Request $request)
    {
        $data = [];
        $requestData = $request->only([
            'search',
            'languageCode'
        ]);
   
        $validator = Validator::make($requestData, [
            'search' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'Invalid request', 'data' => [$validator->errors()]], 200);
        }
       
        try {
            $hotalSearchData = $this->getHotelSearch($requestData);
            if(!empty($hotalSearchData)){
                if (isset($hotalSearchData['statusCode'])) {
                    if($hotalSearchData['statusCode'] == 400){
                        return $this->sendError('The request contains bad syntax or request parameters are not informed.');
                    }elseif ($hotalSearchData['statusCode'] == 403) {
                        return $this->sendError('Access to this API has been disallowed.');
                    }elseif ($hotalSearchData['statusCode'] == 401) {
                        return $this->sendError('Authorization field missing, or Request signature verification failed.');
                    }elseif ($hotalSearchData['statusCode'] == 405) {
                        return $this->sendError('Request method "POST" not supported.');
                    }elseif ($hotalSearchData['statusCode'] == 500) {
                        return $this->sendError('Internal server error.');
                    }
                }else{
                    return $this->sendResponse($hotalSearchData, 'Get hotel search successfuly.');
                }
            }else{
                return $this->sendError('Hotel not found.');
            }
        } catch (Exception $e) {
            $success = [];
            return $this->sendError($success, 'Something went wrong', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Post(
     *   path="/v1/hotel/hotel-availability",
     *   security={
     *     {"bearerAuth": {}}
     *   },
     *   tags={"Hotel"},
     *   summary="Send request for get Availability for hotels",
     *   description="This API provides information about hotel availability :<br>
                                 The language code (e.g., 'en' for English, 'ar' for Arabic).<br>
                                The currency code used for transactions, Please use the ISO 4217 standard codes (e.g., SAR, USD, EUR).<br>
                                destinationCode : Represents destination code(City ISO Code) to get from hotel search.<br>
                                checkIn : Represents the date of hotel check-in in YYYY-MM-DD format.<br>
                                checkOut : Represents the date of hotel check-out in YYYY-MM-DD format.<br>
                                rooms : Number of requested rooms of this occupancy.<br>
                                adults : Number of adult passengers for this room.<br>
                                children : Number of child passengers for this room.<br>
                                Passenger type, defines if the passenger is adult(AD) or child(CH).<br>
                                Age of the passenger (required for children passengers).",
     *   operationId="hotel-availability",
     *   @OA\RequestBody(
     *     required=true,
     *     description="Get Availability by hotel code", 
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(
     *              required={"destinationCode","checkIn","checkOut","occupancies","rooms","adults","children"},
     *              @OA\Property(property="languageCode", default="en", type="string", description="Denotes the language code(en/ar) used for localization or specifying the language of the search results"),
     *              @OA\Property(property="currency",example="SAR",type="string",description="Represents convert to currency"),
     *              @OA\Property(property="agencyId", type="string", default="0", example="0", description="The agency name throught which gt hotel"),
     *              @OA\Property(property="destinationCode",example="PMI",type="string",description="Represents destination code(City ISO Code) to get from hotel search"),
     *              @OA\Property(property="checkIn", default="2024-05-15", type="string", description="The date when the reservation check-in will occur. Format: YYYY-MM-DD"),
     *              @OA\Property(property="checkOut", default="2024-05-30", type="string", description="The date when the reservation check-out will occur. Format: YYYY-MM-DD"),
     *              @OA\Property(property="rooms", title="rooms",
     *                  minItems=1, maxItems=18,
     *                  type="array", 
     *                  description="An array represents rooms",
     *                  @OA\Items(
     *                          @OA\Property(
     *                                      property="adults",
     *                                      default="2",
     *                                      example="2",
     *                                      type="integer", 
     *                                      description="Represents total number of Adults."
     *                          ),
     *                          @OA\Property( 
     *                                      property="children",
     *                                      example="1",
     *                                      type="integer",
     *                                      description="Represents total number of child."
     *                          ),
     *                          @OA\Property( 
     *                                      property="ages",
     *                                      example="[4]",
     *                                      type="string",
     *                                      description="Age of the passenger (required for children passengers)."
     *                          )
     *                 )
     *              ),
     *              @OA\Property(property="occupancies", title="occupancies",
     *                  minItems=1, maxItems=18,
     *                  type="array", 
     *                  description="An array represents occupancies",
     *                  @OA\Items(
     *                          @OA\Property(
     *                                      property="rooms",
     *                                      default="1", 
     *                                      example="1",
     *                                      type="integer", 
     *                                      description="Represents Room Number."
     *                          ),
     *                          @OA\Property(
     *                                      property="adults",
     *                                      default="2",
     *                                      example="2",
     *                                      type="integer", 
     *                                      description="Represents total number of Adults."
     *                          ),
     *                          @OA\Property( 
     *                                      property="children",
     *                                      example="1",
     *                                      type="integer",
     *                                      description="Represents total number of child."
     *                          ),
     *                          @OA\Property( 
     *                                      property="paxes",
     *                                      minItems=1, maxItems=18,
     *                                      type="array",
     *                                      description="Represents total number of child.",
     *                                      @OA\Items(
     *                                          @OA\Property(
     *                                              property="type",
     *                                              default="CH", 
     *                                              example="CH",
     *                                              type="string", 
     *                                              description="Passenger type, defines if the passenger is adult(AD) or child(CH)."
     *                                          ),
     *                                          @OA\Property(
     *                                              property="age",
     *                                              default="4",
     *                                              example="4",
     *                                              type="string", 
     *                                              description="Age of the passenger (required for children passengers)."
     *                                          )
     *                                      ),
     *                           ) 
     *                 )
     *             ),
     *          )
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
    public function hotelAvailability(Request $request)
    {
        $data = [];
        $requestData = $request->only([
            'currency',
            'supplier',
            'destinationCode',
            'checkIn',
            'checkOut',
            'occupancies',
            'languageCode'
        ]);

        $validator = Validator::make($requestData, [
            'destinationCode' => 'required',
            'checkIn' => 'required',
            'checkOut' => 'required',
            'occupancies' => 'required|array',
            'occupancies.*.rooms' => 'required|integer',
            'occupancies.*.adults' => 'required|integer',
            'occupancies.*.children' => 'required|integer',
            'occupancies.*.paxes' => 'required|array'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'Invalid request', 'data' => [$validator->errors()]], 200);
        }
        
        try {
            $hotalAvailabilityData = $this->getHotelAvailability($requestData);

            $defaultCurrency = "EUR";
            $currency = $requestData['currency'] ?? '';
            $checkAllowedCurrency = convertCurrencyExchangeRate('1', $defaultCurrency, $currency, []);

            if ($checkAllowedCurrency['status'] == false) {
                $success = [];
                return $this->sendError($success, 'Currency not allowed.'); 
            }else{

                if(!empty($hotalAvailabilityData)){
                    if (isset($hotalAvailabilityData['statusCode'])) {
                        if($hotalAvailabilityData['statusCode'] == 400){
                            return $this->sendError('The request contains bad syntax or request parameters are not informed.');
                        }elseif ($hotalAvailabilityData['statusCode'] == 403) {
                            return $this->sendError('Access to this API has been disallowed.');
                        }elseif ($hotalAvailabilityData['statusCode'] == 401) {
                            return $this->sendError('Authorization field missing, or Request signature verification failed.');
                        }elseif ($hotalAvailabilityData['statusCode'] == 405) {
                            return $this->sendError('Request method "POST" not supported.');
                        }elseif ($hotalAvailabilityData['statusCode'] == 500) {
                            return $this->sendError('Internal server error.');
                        }
                    }else{
                        return $this->sendResponse($hotalAvailabilityData, 'Get hotel lists successfuly.');
                    }
                }
            }

        } catch (Exception $e) {
            $success = [];
            return $this->sendError($success, 'Something went wrong', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Post(
     **  path="/v1/hotel/hotel-details",
     *   security={
     *     {"bearerAuth": {}}
     *   },
     *   tags={"Hotel"},
     *   summary="This will response hotel details of the requested keywords ",
     *   description="get hotel details on base of requested keywords(hotel codes) <br><br>
                                The language code (e.g., 'en' for English, 'ar' for Arabic).<br>
                                The currency code used for transactions, Please use the ISO 4217 standard codes (e.g., SAR, USD, EUR).<br>
                                checkIn : Represents the date of hotel check-in in YYYY-MM-DD format.<br>
                                checkOut : Represents the date of hotel check-out in YYYY-MM-DD format.<br>
                                rooms : Number of requested rooms of this occupancy.<br>
                                adults : Number of adult passengers for this room.<br>
                                children : Number of child passengers for this room.<br>
                                Passenger type, defines if the passenger is adult(AD) or child(CH).<br>
                                Age of the passenger (required for children passengers).",
     *   operationId="hotel-details",
     *   @OA\RequestBody(
     *     required=true,
     *     description="get hotel details on base of requested keywords", 
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(
     *             required={"checkIn","checkOut","occupancies","paxes","rooms","adults","children","hotelCode"},
     *              @OA\Property(property="languageCode", default="en", type="string", description="Denotes the language code(en/ar) used for localization or specifying the language of the search results"),
     *              @OA\Property(property="currency",example="SAR",type="string",description="Represents convert to currency"),
     *              @OA\Property(property="agencyId", type="string", default="0", example="0", description="The agency name throught which gt hotel"),
     *              @OA\Property(property="checkIn", default="2024-05-15", type="string", description="The date when the reservation check-in will occur. Format: YYYY-MM-DD"),
     *              @OA\Property(property="checkOut", default="2024-05-30", type="string", description="The date when the reservation check-out will occur. Format: YYYY-MM-DD"),
     *              @OA\Property(property="rooms", title="rooms",
     *                  minItems=1, maxItems=18,
     *                  type="array", 
     *                  description="An array represents rooms",
     *                  @OA\Items(
     *                          @OA\Property(
     *                                      property="adults",
     *                                      default="2",
     *                                      example="2",
     *                                      type="integer", 
     *                                      description="Represents total number of Adults."
     *                          ),
     *                          @OA\Property( 
     *                                      property="children",
     *                                      example="1",
     *                                      type="integer",
     *                                      description="Represents total number of child."
     *                          ),
     *                          @OA\Property( 
     *                                      property="ages",
     *                                      example="[4]",
     *                                      type="string",
     *                                      description="Age of the passenger (required for children passengers)."
     *                          )
     *                 )
     *              ),
     *              @OA\Property(property="occupancies", title="occupancies",
     *                  minItems=1, maxItems=18,
     *                  type="array", 
     *                  description="An array represents occupancies",
     *                  @OA\Items(
     *                          @OA\Property(
     *                                      property="rooms",
     *                                      default="1", 
     *                                      example="1",
     *                                      type="integer", 
     *                                      description="Represents Room Number."
     *                          ),
     *                          @OA\Property(
     *                                      property="adults",
     *                                      default="2",
     *                                      example="2",
     *                                      type="integer", 
     *                                      description="Represents total number of Adults."
     *                          ),
     *                          @OA\Property( 
     *                                      property="children",
     *                                      example="1",
     *                                      type="integer",
     *                                      description="Represents total number of child." 
     *                          ),
     *                          @OA\Property( 
     *                                      property="paxes",
     *                                      minItems=1, maxItems=18,
     *                                      type="array",
     *                                      description="Represents total number of child.",
     *                                      @OA\Items(
     *                                          @OA\Property(
     *                                              property="type",
     *                                              default="CH", 
     *                                              example="CH",
     *                                              type="string", 
     *                                              description="Passenger type, defines if the passenger is adult(AD) or child(CH)."
     *                                          ),
     *                                          @OA\Property(
     *                                              property="age",
     *                                              default="4",
     *                                              example="4",
     *                                              type="string", 
     *                                              description="Age of the passenger (required for children passengers)."
     *                                          )
     *                                      ),
     *                           ) 
     *                 )
     *             ),
     *             @OA\Property(property="hotelCode", type="string", default="314", example="314", description="Represents hotel code for hotel details list"),
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
    public function hotelDetail(Request $request)
    {
        $data = [];
        $requestData = $request->only([
            'currency',
            'agencyId',
            'checkIn',
            'checkOut',
            'occupancies',
            'languageCode',
            'hotelCode'
        ]);

        $validator = Validator::make($requestData, [
            'checkIn' => 'required',
            'checkOut' => 'required',
            'hotelCode' => 'required',
            'occupancies' => 'required|array',
            'occupancies.*.rooms' => 'required|integer',
            'occupancies.*.adults' => 'required|integer',
            'occupancies.*.children' => 'required|integer',
            'occupancies.*.paxes' => 'required|array'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'Invalid request', 'data' => [$validator->errors()]], 200);
        }
       
        try {
            $hotalDetailData = $this->getHotelDetail($requestData);

            $defaultCurrency = "EUR";
            $currency = $requestData['currency'] ?? '';
            $checkAllowedCurrency = convertCurrencyExchangeRate('1', $defaultCurrency, $currency, []);

            if ($checkAllowedCurrency['status'] == false) {
                $success = [];
                return $this->sendError($success, 'Currency not allowed.'); 
            }else{
                if(!empty($hotalDetailData)){
                    if (isset($hotalDetailData['statusCode'])) {
                        if($hotalDetailData['statusCode'] == 400){
                            return $this->sendError('The request contains bad syntax or request parameters are not informed.');
                        }elseif ($hotalDetailData['statusCode'] == 403) {
                            return $this->sendError('Access to this API has been disallowed.');
                        }elseif ($hotalDetailData['statusCode'] == 401) {
                            return $this->sendError('Authorization field missing, or Request signature verification failed.');
                        }elseif ($hotalDetailData['statusCode'] == 405) {
                            return $this->sendError('Request method "POST" not supported.');
                        }elseif ($hotalDetailData['statusCode'] == 500) {
                            return $this->sendError('Internal server error.');
                        }
                    }else{
                        return $this->sendResponse($hotalDetailData, 'Get hotel details successfully.');
                    }
                }
            }
        } catch (Exception $e) {
            $success = [];
            return $this->sendError($success, 'Something went wrong', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Post(
     ** path="/v1/hotel/check-rate",
     *   security={
     *     {"bearerAuth": {}}
     *   },
     *   tags={"Hotel"},
     *   summary="Check rooms rate by rateKey",
     *   description="Get a detailed and updated breakdown of an hotel room rate,<br>",
     *   operationId="check-rate",
     *   @OA\RequestBody(
     *     required=true,
     *     description="get hotel list on base of requested keywords,<br>
                        The language code (e.g., 'en' for English, 'ar' for Arabic)<br>
                        The currency code used for transactions, Please use the ISO 4217 standard codes (e.g., SAR, USD, EUR).<br>", 
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(
     *             required={"rooms","rateKey"},
     *             @OA\Property(property="languageCode", default="en", type="string", description="Denotes the language code(en/ar) used for localization or specifying the language of the search results"),
     *             @OA\Property(property="currency",example="SAR",type="string",description="Represents convert to currency"),
     *             @OA\Property(property="agencyId", type="string", default="0", example="0", description="The agency name throught which gt hotel"),
     *             @OA\Property(property="rooms", title="rooms",
     *                  minItems=1, maxItems=18,
     *                  type="array", 
     *                  description="An array represents rooms",
     *                  @OA\Items(
     *                          @OA\Property(
     *                                      property="rateKey",
     *                                      type="string", 
     *                                      description="Uses one rateKey, representing a room."
     *                          )
     *                 )
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
    public function hotelCheckRate(Request $request)
    {
        $data = [];
        $requestData = $request->only([
            'languageCode',
            'currency',
            'agencyId',
            'rooms'
        ]);

        $validator = Validator::make($requestData, [
            'rooms' => 'required|array',
            'rooms.*.rateKey' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'Invalid request', 'data' => [$validator->errors()]], 200);
        }
       
        try {
            $checkRateData = $this->getCheckRate($requestData);
            if(!empty($checkRateData)){
                if (isset($checkRateData['statusCode'])) {
                    if($checkRateData['statusCode'] == 400){
                        return $this->sendError('The request contains bad syntax or request parameters are not informed.');
                    }elseif ($checkRateData['statusCode'] == 403) {
                        return $this->sendError('Access to this API has been disallowed.');
                    }elseif ($checkRateData['statusCode'] == 401) {
                        return $this->sendError('Authorization field missing, or Request signature verification failed.');
                    }elseif ($checkRateData['statusCode'] == 405) {
                        return $this->sendError('Request method "POST" not supported.');
                    }elseif ($checkRateData['statusCode'] == 500) {
                        return $this->sendError('Internal server error.');
                    }
                }else{
                    return $this->sendResponse($checkRateData, 'Get hotel room check rate successfuly.');
                }
            }
        } catch (Exception $e) {
            $success = [];
            return $this->sendError($success, 'Something went wrong', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Post(
     ** path="/v1/hotel/booking-confirmation",
     *   security={
     *     {"bearerAuth": {}}
     *   },
     *   tags={"Hotel"},
     *   summary="Hotel Booking Confirmation",
     *   description="The Booking operation requests a reservation confirmation for the specified ratekey or ratekeys,<br>",
     *   operationId="booking-confirmation",
     *   @OA\RequestBody(
     *     required=true,
     *     description="Get hotel booking confirmation detail on base of requested keywords.<br>
                        Information of the holder of the booking.<br>
                        Booking holder name for all the rooms of the booking.<br>
                        Booking holder surname for all rooms of the booking.<br>
                        Internal key that represents a combination of room type, category, board and occupancy.<br>
                        Data of the passengers(paxes) assigned to this room.<br>
                        Passenger type, defines if the passenger is adult(AD) or child(CH).<br>
                        Age of the passenger (required for children passengers).<br>
                        The language code (e.g., 'en' for English, 'ar' for Arabic)<br>
                        The currency code used for transactions, Please use the ISO 4217 standard codes (e.g., SAR, USD, EUR).<br>", 
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(
     *             required={"holder","name","surname","email","phoneNumber","rateKey","type"},
     *             @OA\Property(property="languageCode", default="en", type="string", description="Denotes the language code(en/ar) used for localization or specifying the language of the search results"),
     *             @OA\Property(property="currency",example="SAR",type="string",description="Represents convert to currency"),
     *             @OA\Property(property="agencyId", type="string", default="0", example="0", description="The agency name throught which gt hotel"),
     *             @OA\Property(property="customer_id", type="string",default="0"),
     *             @OA\Property(property="holder", title="holder",
     *                  minItems=1, maxItems=18,
     *                  description="An array represents holder",
     *                          @OA\Property(
     *                                      property="name", default="HolderFirstName",
     *                                      type="string", 
     *                                      description="Booking holder name for all the rooms of the booking."
     *                          ),
     *                           @OA\Property(
     *                                      property="surname", default="HolderLastName",
     *                                      type="string", 
     *                                      description="Booking holder surname for all rooms of the booking."
     *                          ),
     *                          @OA\Property(
     *                                      property="email", default="holder@mail.com",
     *                                      type="string", 
     *                                      description="Booking holder email for all rooms of the booking."
     *                          ),
     *                          @OA\Property(
     *                                      property="phoneNumber", default="+91 0000000000",
     *                                      type="string", 
     *                                      description="Booking holder phone number for all rooms of the booking."
     *                          )
     *             ),
     *             @OA\Property(property="rooms", title="rooms",
     *                  minItems=1, maxItems=18,
     *                  type="array",
     *                  description="An array represents rooms",
     *                  @OA\Items(
     *                          @OA\Property(
     *                                      property="rateKey",
     *                                      type="string", 
     *                                      description="Internal key that represents a combination of room type, category, board and occupancy."
     *                          ),
     *                          @OA\Property(property="paxes", title="paxes",
     *                                      minItems=1, maxItems=18,
     *                                      type="array", 
     *                                      description="Data of the passengers assigned to this room.",
     *                                      @OA\Items(
     *                                             @OA\Property(
     *                                                         property="roomId", default="1",
     *                                                         type="string", 
     *                                                         description="Represent number of room."
     *                                             ),
     *                                             @OA\Property(
     *                                                         property="type", title="type", 
     *                                                         type="string", default="AD",
     *                                                         description="Passenger type, defines if the passenger is adult or child."
     *                                             ),
     *                                             @OA\Property(
     *                                                         property="age", title="age", 
     *                                                         type="string", default="",
     *                                                         description="Age of the passenger (required for children passengers)."
     *                                             ),
     *                                             @OA\Property(
     *                                                         property="name", title="name", 
     *                                                         type="string", default="First Adult Name",
     *                                                         description="Name of the passenger."
     *                                             ),
     *                                             @OA\Property(
     *                                                         property="surname", title="surname", 
     *                                                         type="string", default="Surname",
     *                                                         description="Surname of the passenger."
     *                                             )
     *                                     )
     *                          )
     *                  )
     *             ),
     *             @OA\Property(property="clientReference", type="string", default="IntegrationAgency",  description="Internal booking reference."),
     *             @OA\Property(property="remark", type="string", default="Booking remarks are to be written here.", description="Free text sent to the hotelier. It can be used to request or inform of special requests to hotelier like: “Non-smoking room preferred”, “Twin bed please”, “Upper floor preferred”, “Late arrival”"),
     *             @OA\Property(property="tolerance", type="integer", default="0", description="Margin of price difference (as percentage) accepted when a price difference occurs between Availability/CheckRate and Booking operations. Do not use more than two decimal characters when defining tolerance. Example: to input a tolerance of 5%, you should input 5.00."),
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
    public function hotelBookingConfirmation(Request $request)
    {
        $data = [];
        $requestData = $request->only([
            'languageCode',
            'currency',
            'agencyId',
            'customer_id',
            'holder',
            'rooms',
            'clientReference',
            'remark',
            'tolerance'
        ]);

        $validator = Validator::make($requestData, [
            'holder' => 'required|array',
            'holder.name' => 'required',
            'holder.surname' => 'required',
            'holder.email' => 'required|email',
            'holder.phoneNumber' => 'required',
            'rooms' => 'required|array',
            'rooms.*.rateKey' => 'required',
            'rooms.*.paxes.*.type' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'Invalid request', 'data' => [$validator->errors()]], 200);
        }
       
        try {
            $getBookingData = $this->getBookingConfirmationDetails($requestData);
            if(!empty($getBookingData)){
                if (isset($getBookingData['statusCode'])) {
                    if($getBookingData['statusCode'] == 400){
                        return $this->sendError('The request contains bad syntax or request parameters are not informed.');
                    }elseif ($getBookingData['statusCode'] == 403) {
                        return $this->sendError('Access to this API has been disallowed.');
                    }elseif ($getBookingData['statusCode'] == 401) {
                        return $this->sendError('Authorization field missing, or Request signature verification failed.');
                    }elseif ($getBookingData['statusCode'] == 405) {
                        return $this->sendError('Request method "POST" not supported.');
                    }elseif ($getBookingData['statusCode'] == 500) {
                        return $this->sendError('Internal server error.');
                    }
                }else{
                    return $this->sendResponse($getBookingData, 'Get hotel booking confirmation details successfuly.');
                }
            }else{
                return $this->sendError('Something went wrong');
            }
        } catch (Exception $e) {
            $success = [];
            return $this->sendError($success, 'Something went wrong', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Get(
     *  path="/v1/hotel/booking-detail",
     *   security={
     *     {"bearerAuth": {}}
     *   },
     *   tags={"Hotel"},
     *   summary="Hotel Booking Detail",
     *   description="Retrieve booking details based on the booking id.",
     *   operationId="booking-detail",
     *   @OA\Parameter(
            name="body",
            in="query",
            required=false,
            explode=true,
            @OA\Schema(
                 collectionFormat="multi",
                 required={"booking_id"},
                 @OA\Property(property="booking_id", type="string",  )
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
    public function hotelBookingDetail(Request $request)
    {
        $data = [];
        $requestData = $request->only([
            'booking_id'
        ]);
   
        $validator = Validator::make($requestData, [
            'booking_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'Invalid request', 'data' => [$validator->errors()]], 200);
        }
       
        try {
            $bookingDetailData = $this->getBookingDetail($requestData);
            if(!empty($bookingDetailData)){
                if (isset($bookingDetailData['error'])) {
                    $message = $bookingDetailData['error']['message'];
                    return $this->sendError($message);
                }else{
                    return $this->sendResponse($bookingDetailData, 'Get hotel booking detail successfuly.');
                }
            }
        } catch (Exception $e) {
            $success = [];
            return $this->sendError($success, 'Something went wrong', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Delete(
     ** path="/v1/hotel/booking-cancellation",
     *   security={
     *     {"bearerAuth": {}}
     *   },
     *   tags={"Hotel"},
     *   summary="Hotel Booking Cancellation",
     *   description="Booking cancellation is used either to simulate a cancellation or to perform the actual cancellation.Cancellations always take into account day and time of the destination in order to calculate what cancellation policy should be applied,<br>
                      Booking reference number taken from the confirmation response or from the booking list function. Format: XXX-YYYYYY,<br>
                      The language code (e.g., 'en' for English, 'ar' for Arabic),<br>
                      The currency code used for transactions, Please use the ISO 4217 standard codes (e.g., SAR, USD, EUR).<br>", 
     *   operationId="booking-cancellation",
     *   @OA\RequestBody(
     *     required=true,
     *     description="get hotel cancellation on base of requested keywords", 
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(
     *             required={"bookingId"},
     *             @OA\Property(property="bookingId", type="string", description="Booking reference number taken from the confirmation response or from the booking list function. Format: XXX-YYYYYY."),
     *             @OA\Property(property="languageCode", default="en", type="string", description="Denotes the language code(en/ar) used for localization or specifying the language of the search results"),
     *             @OA\Property(property="currency",example="SAR",type="string",description="Represents convert to currency")
     *        )
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
    public function hotelBookingCancellation(Request $request)
    {
        $data = [];
        $requestData = $request->only([
            'bookingId',
            'currency',
            'languageCode'
        ]);
   
        $validator = Validator::make($requestData, [
            'bookingId' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'Invalid request', 'data' => [$validator->errors()]], 200);
        }
       
        try {
            $bookingCancellationData = $this->getBookingCancellationDetails($requestData);
            if(!empty($bookingCancellationData)){
                if (isset($bookingCancellationData['error'])) {
                    $message = $bookingCancellationData['error']['message'];
                    return $this->sendError($message);
                }else{
                    return $this->sendResponse($bookingCancellationData['booking'], 'Get hotel booking cancellation details successfuly.');
                }
            }
        } catch (Exception $e) {
            $success = [];
            return $this->sendError($success, 'Something went wrong', ['error' => $e->getMessage()], 500);
        }
    }
}
