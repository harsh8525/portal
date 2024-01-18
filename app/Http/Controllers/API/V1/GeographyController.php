<?php

/**
 * @package     Geography
 * @subpackage  Geography
 * @Author      Amar Technolabs Pvt. ltd(info@amarinfotech.com)
 * @Copyright(C) 2023 [NAME OF THE ORGANISATION THAT ON BEHALF OF THE CODE WE ARE WORKING].
 * @Version 1.0.0
 * module of the Geography.
 */

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Country;
use App\Models\CountryI18ns;
use App\Models\Setting;
use App\Models\State;
use App\Models\City;

class GeographyController extends BaseController
{
    /**
     * @OA\Get(
     ** path="/v1/geography/get-countries",
     *   tags={"Geography"},
     *   summary="get countries list",
     *   description="get countries list<br><br>Pass Country name or iso code or isd code in Search<br>Pass Per page",
     *   operationId="get-countries",
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
    public function getCountries(Request $request)
    {

        $data = [];
        $requestData = $request->only(['search']);
        //set validation for search keyword
        $validator = Validator::make($requestData, []);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'Invalid request', 'data' => [$validator->errors()]], 200);
        }
        try {
            if (isset($request->per_page) && $request->per_page != "") {
                $this->perPage = $request->per_page;
            } else {
                $this->perPage = Setting::where('config_key', 'general|setting|pagePerAPIRecords')->value('value');
            }

            $getCountryList = Country::select('id', 'iso_code', 'isd_code', 'max_mobile_number_length', 'status')
                ->with(['countryCode' => function ($country) {
                    $country->select(['country_id', 'country_name', 'language_code']);
                }])
                ->whereHas('countryCode', function ($q) use ($requestData) {
                    $q->orHaving('country_name', 'like', '%' . $requestData['search'] . '%');
                })
                ->orWhere('isd_code', '+'.$requestData['search'])
                ->orWhere('iso_code', $requestData['search'])
                ->orderByRaw("iso_code = '{$requestData['search']}' DESC, iso_code ASC")
                ->paginate($this->perPage);

            $CountryData = collect($getCountryList->items())->map(function ($country) {
                $country_en = '';
                $country_ar = '';

                foreach ($country['countryCode'] as $countryName) {
                    switch ($countryName['language_code']) {
                        case 'en':
                            $country_en = $countryName['country_name'];
                            break;
                        case 'ar':
                            $country_ar = $countryName['country_name'];
                            break;
                    }
                }

                return [
                    'id' => $country['id'],
                    'iso_code' => $country['iso_code'],
                    'isd_code' => $country['isd_code'],
                    'max_mobile_number_length' => $country['max_mobile_number_length'],
                    'status' => $country['status'],
                    'country_en' => $country_en,
                    'country_ar' => $country_ar,
                ];
            });

            $searchParameter = $requestData['search'];
            $perPageParameter = $this->perPage;
            $queryString = "search=$searchParameter&per_page=$perPageParameter";
            $output = [
                'current_page' => $getCountryList->currentPage(),
                'data' => $CountryData->values()->toArray(),
                'first_page_url' => $getCountryList->url(1) . '&' . $queryString,
                'from' => $getCountryList->firstItem(),
                'last_page' => $getCountryList->lastPage(),
                'last_page_url' => $getCountryList->url($getCountryList->lastPage()) . '&' . $queryString,
                'links' => [
                    [
                        'url' => $getCountryList->previousPageUrl() . '&' . $queryString,
                        'label' => '&laquo; Previous',
                        'active' => $getCountryList->onFirstPage(),
                    ],
                    [
                        'url' => $getCountryList->url(1) . '&' . $queryString,
                        'label' => '1',
                        'active' => $getCountryList->currentPage() === 1,
                    ],
                    [
                        'url' => $getCountryList->nextPageUrl() . '&' . $queryString,
                        'label' => 'Next &raquo;',
                        'active' => $getCountryList->hasMorePages(),
                    ],
                ],
                'next_page_url' => $getCountryList->nextPageUrl() . '&' . $queryString,
                'path' => $getCountryList->path() . '?' . $queryString,
                'per_page' => $getCountryList->perPage(),
                'prev_page_url' => $getCountryList->previousPageUrl() . '&' . $queryString,
                'to' => $getCountryList->lastItem(),
                'total' => $getCountryList->total(),
            ];

            if ($output) {
                $success = 1;
                return $this->sendResponse($output, 'Country Listed Successfully!', $success);
            } else {
                $success = [];
                return $this->sendError('Country List Not Found', $success, 200);
            }
            //in success response need to send active country list with only fields [country_code, country_name, city_name, latitude, longitude]

        } catch (Exception $ex) {
            return $this->sendError($data, 'Something went wrong', ['error' => $ex->getMessage()], 500);
        }
    }
    /**
     * @OA\Get(
     ** path="/v1/geography/get-country",
     *   tags={"Geography"},
     *   summary="get country list with name arabic and english",
     *   description="get country list <br><br>",
     *   operationId="countryList",
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
    public function getCountryDetails(Request $request)
    {
        
        $getCountryDetails = Country::select('countries.id', 'countries.iso_code', 'countries.isd_code')
            ->with(['countryCode' => function ($query) {
                $query->orderBy('country_name', 'asc');
            }])
            ->orderBy('iso_code', 'asc')
            ->get()
            ->toArray();

        // Initialize an empty array to store the transformed data
        $transformedData = [];

        // Iterate through each record in the original data
        foreach ($getCountryDetails as $country) {
            // Extract relevant information from the original record
            $id = $country['id'];
            $isoCode = $country['iso_code'];
            $isdCode = $country['isd_code'];

            // Iterate through the "country_code" array to find language-specific country names
            $countryNameEn = null;
            $countryNameAr = null;

            foreach ($country['country_code'] as $countryCode) {
                if ($countryCode['language_code'] == 'en') {
                    $countryNameEn = $countryCode['country_name'];
                } elseif ($countryCode['language_code'] == 'ar') {
                    $countryNameAr = $countryCode['country_name'];
                }
            }

            // Build a new array with the desired structure
            $transformedData[] = [
                'id' => $id,
                'iso_code' => $isoCode,
                'isd_code' => $isdCode,
                'country_name_en' => $countryNameEn,
                'country_name_ar' => $countryNameAr,
            ];
        }
        $success = 1;
        return $this->sendResponse($transformedData, 'Country List Fetched Successfully.', $success);
    }
    /**
     * @OA\Get(
     ** path="/v1/geography/get-states",
     *   tags={"Geography"},
     *   summary="get states list with name arabic and english",
     *   description="get states list <br>pass country code to get country related state list<br>",
     *   operationId="stateList",
     *   @OA\Parameter(
     *       name="body",
     *       in="query",
     *       required=false,
     *       explode=true,
     *       @OA\Schema(
     *            collectionFormat="multi",
     *            required={"country_code"},
     *            @OA\Property(property="country_code", type="string",  ),
     *            @OA\Property(property="state_name", type="string",  )
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
    public function getStateDetails(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'country_code' => 'required|exists:states,country_code',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'Invalid request', 'data' => [$validator->errors()]], 422);
        }
        $getStateDetails = State::select('states.id', 'states.iso_code', 'states.country_code', 'states.latitude', 'states.longitude','states.country_code')
            ->with(['stateName' => function ($q) use($request){
                $q->orderBy('state_name', 'asc');
            }])
            ->whereHas('stateName', function ($q) use ($request) {
                $q->orHaving('state_name', 'like', '%' . $request['state_name'] . '%');
            })
            ->where('country_code', $request->country_code)
            ->orderBy('iso_code', 'asc')
            ->LIMIT(50)
            ->get()
            ->toArray();

        // Initialize an empty array to store the transformed data
        $transformedData = [];
        if($getStateDetails){    
        // Iterate through each record in the original data
        foreach ($getStateDetails as $state) {
            // Extract relevant information from the original record
            $id = $state['id'];
            $country_code = $state['country_code'];
            $isoCode = $state['iso_code'];
            $latitute = $state['latitude'];
            $longitude = $state['longitude'];

            // Iterate through the "country_code" array to find language-specific country names
            $stateNameEn = null;
            $stateNameAr = null;

            foreach ($state['state_name'] as $countryCode) {
                if ($countryCode['language_code'] == 'en') {
                    $stateNameEn = $countryCode['state_name'];
                } elseif ($countryCode['language_code'] == 'ar') {
                    $stateNameAr = $countryCode['state_name'];
                }
            }

            // Build a new array with the desired structure
            $transformedData[] = [
                'id' => $id,
                'country_code' => $country_code,
                'iso_code' => $isoCode,
                'latitude' => $latitute,
                'longitude' => $longitude,
                'state_name_en' => $stateNameEn,
                'state_name_ar' => $stateNameAr,
            ];
        }
        $success = true;
        return $this->sendResponse($transformedData, 'State List Fetched Successfully', $success);
    }else{
        $success = false;
        return $this->sendResponse($transformedData, 'Record Not Found', $success);
    }
    }
    /**
     * @OA\Get(
     ** path="/v1/geography/get-city",
     *   tags={"Geography"},
     *   summary="get city list with name arabic and english",
     *   description="get city list <br>pass country code to get country related city list<br>",
     *   operationId="cityList",
     *   @OA\Parameter(
     *       name="body",
     *       in="query",
     *       required=false,
     *       explode=true,
     *       @OA\Schema(
     *            collectionFormat="multi",
     *            required={"country_code"},
     *            @OA\Property(property="country_code", type="string",  ),
     *            @OA\Property(property="city_name", type="string",  )
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
    public function getCityDetails(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'country_code' => 'required|exists:cities,country_code',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'Invalid request', 'data' => [$validator->errors()]], 422);
        }
        $getCityDetails = City::select('cities.id', 'cities.iso_code', 'cities.country_code', 'cities.latitude', 'cities.longitude')
            ->with(['cityCode' => function ($q) {
                $q->orderBy('city_name', 'asc');
            }])
            ->whereHas('cityCode', function ($q) use ($request) {
                $q->orHaving('city_name', 'like', '%' . $request['city_name'] . '%');
            })
            ->where('country_code', $request->country_code)
            ->orderBy('iso_code', 'asc')
            ->LIMIT(50)
            ->get()
            ->toArray();

        // Initialize an empty array to store the transformed data
        $transformedData = [];
        if($getCityDetails){
        // Iterate through each record in the original data
        foreach ($getCityDetails as $city) {
            // Extract relevant information from the original record
            $id = $city['id'];
            $country_code = $city['country_code'];
            $isoCode = $city['iso_code'];
            $latitute = $city['latitude'];
            $longitude = $city['longitude'];

            // Iterate through the "country_code" array to find language-specific country names
            $cityNameEn = null;
            $cityNameAr = null;

            foreach ($city['city_code'] as $countryCode) {
                if ($countryCode['language_code'] == 'en') {
                    $cityNameEn = $countryCode['city_name'];
                } elseif ($countryCode['language_code'] == 'ar') {
                    $cityNameAr = $countryCode['city_name'];
                }
            }

            // Build a new array with the desired structure
            $transformedData[] = [
                'id' => $id,
                'country_code' => $country_code,
                'iso_code' => $isoCode,
                'latitude' => $latitute,
                'longitude' => $longitude,
                'city_name_en' => $cityNameEn,
                'city_name_ar' => $cityNameAr,
            ];
        }
        $success = true;
        return $this->sendResponse($transformedData, 'State List Fetched Successfully', $success);
    }else{
        $success = false;
        return $this->sendResponse($transformedData, 'Record Not Found', $success);
        }
    }
}
