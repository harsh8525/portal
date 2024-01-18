<?php

/**
 * @package     GoogleMap
 * @subpackage  GoogleMap
 * @Author      Amar Technolabs Pvt. ltd(info@amarinfotech.com)
 * @Copyright(C) 2023 [NAME OF THE ORGANISATION THAT ON BEHALF OF THE CODE WE ARE WORKING].
 * @Version 1.0.0
 * module of the GoogleMap.
 */

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\API\V1\BaseController as BaseController;
use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp;
use App\Models\Setting;

class GoogleMapController extends BaseController
{
    /**
     * @OA\Post(
     ** path="/v1/google-place-search",
     *   tags={"GoogleMapApi"},
     *   summary="Search for google Place",
     *   description="Calling an API for Search google Places
                ",
     *   operationId="google-place-search",
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
    public function placeSearch(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'search' => 'required|min:3',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => 'Invalid request', 'data' => [$validator->errors()]], 422);
            }

            // check for google map api key exist or not
            $isGoogleMapEnabled = Setting::select('value')->where('config_key', '=', 'general|site|googleApiKey')->first();
            if (!@$isGoogleMapEnabled->value) {
                $success = [];
                return $this->sendError('Google map is not enabled', $success, 200);
            }

            //set data to call google place search api
            $search = $request->search;
            $googleKey = @$isGoogleMapEnabled->value;
            $url = "https://maps.googleapis.com/maps/api/place/queryautocomplete/json?key=" . $googleKey . "&input=" . $search . "&region=in";
            $url = str_replace(' ', '%20', $url);


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

            return $this->sendResponse([$data], 'Successfully fetch Google Places');
        } catch (Exception $ex) {
            $success = [];
            return $this->sendError('Error Google Place API Search', $success);
        }
    }


    /**
     * @OA\Post(
     ** path="/v1/google-detail-place-search",
     *   tags={"GoogleMapApi"},
     *   summary="Search for google Place Detail",
     *   description="Calling an API for Search google Place Detail
                ",
     *   operationId="google-detail-place-search",
     *   @OA\Parameter(
            name="body",
            in="query",
            required=false,
            explode=true,
            @OA\Schema(
                 collectionFormat="multi",
                 required={"place_id"},
                 @OA\Property(property="place_id", type="string",  ),
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
    public function placeDetailSearch(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'place_id' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => 'Invalid request', 'data' => [$validator->errors()]], 422);
            }

            // check for google map api key exist or not
            $isGoogleMapEnabled = Setting::select('value')->where('config_key', '=', 'general|site|googleApiKey')->first();
            if (!@$isGoogleMapEnabled->value) {
                $success = [];
                return $this->sendError('Google map is not enabled', $success, 200);
            }

            //set data to call google place search api
            $place_id = $request->place_id;
            $googleKey = @$isGoogleMapEnabled->value;
            $url = "https://maps.googleapis.com/maps/api/place/details/json?place_id=" . $place_id . "&fields=address_components,formatted_address,geometry,adr_address&key=" . $googleKey;

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

            return $this->sendResponse([$data], 'Successfully Fetch Google Places Detail');
        } catch (Exception $ex) {
            $success = [];
            return $this->sendError('Error Google Place Detail API Search', $success);
        }
    }


    /**
     * @OA\Post(
     ** path="/v1/geo-location-by-ip",
     *   tags={"GoogleMapApi"},
     *   summary="Get Geo Details By IP",
     *   description="Calling an API for Get Geo Detail",
     *   operationId="geo-location-by-ip",
      
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
    public function geoLocationByIp(Request $request)
    {
        try {
            // echo "hello";die;
            $ip = $_SERVER['REMOTE_ADDR'];


            $url = "http://www.geoplugin.net/json.gp?ip=" . $ip;

            
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

            $apiData = json_decode($response, true); // Add the second parameter as `true` to decode as an associative array
            $isoCode = $apiData['geoplugin_currencyCode'];
            $countryCode = $apiData['geoplugin_countryCode'];

            $iso_code = Currency::where('code', $isoCode)->value('code');
            $country_data = Country::select('isd_code','max_mobile_number_length')->where('iso_code', 'IN')->first();

            if ($iso_code) {
                $isd = true;
            } else {
                $isd = false;
            }

            $res['is_available_in_system'] = $isd;
            $res['isd_code'] = $country_data['isd_code'];
            $res['max_mobile_number_length'] = $country_data['max_mobile_number_length'];

            // Merge arrays
            $mergedData = array_merge($apiData, $res);
            // If you need to convert it back to JSON
            $finalJsonString = json_encode($mergedData, JSON_PRETTY_PRINT);
            
            $data = json_decode($finalJsonString);
            return $this->sendResponse([$data], 'Successfully Fetch Geo Location Detail');
        } catch (Exception $ex) {
            $success = [];
            return $this->sendError('Error Geo Location Detail Fetch', $success);
        }
    }
}
