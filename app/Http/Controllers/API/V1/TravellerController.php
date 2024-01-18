<?php

/**
 * @package     Customers
 * @subpackage  Customer
 * @Author      Amar Technolabs Pvt. ltd(info@amarinfotech.com)
 * @Copyright(C) 2023 [NAME OF THE ORGANISATION THAT ON BEHALF OF THE CODE WE ARE WORKING].
 * @Version 1.0.0
 * module of the Customers.
 */

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\API\V1\BaseController as BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\CustomerTraveller;
use App\Models\Setting;
use App\Models\Country;

class TravellerController extends BaseController
{

    /**
     * @OA\Get(
     *   path="/v1/customer/get-traveller-list",
     *   security={
     *     {"bearerAuth": {}}
     *   },
     *   tags={"Customer"},
     *   summary="Get Traveller List",
     *   description="get Traveller List",
     *   operationId="getTravellerList",
    
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
     * )
     * get driver status
     *
     * @return \Illuminate\Http\Response
     */
    public function getTraveller(Request $request)
    {
        try {

            $perPage = Setting::where('config_key', 'general|setting|pagePerAPIRecords')->value('value');
            $filter = array(
                'customer_id' => Auth::id(),
                'per_page' => $perPage,
            );
            $travellerDataList = CustomerTraveller::getTravellerData($filter);
            $travellerData = $travellerDataList['data'];
            // echo "<pre>";print_r($travellerDataList['data'][0]);die;
            if ($travellerData[0]) {
                $success = true;
                return $this->sendResponse($travellerData, 'Traveller Listed Successfully!', $success);
            } else {
                $success = [];
                return $this->sendError(no_record_found, $success, 200);
            }
        } catch (Exception $e) {
            $success = [];
            return $this->sendError($success, 'Something went wrong', ['error' => $e], 500);
        }
    }
   /**
     * @OA\Get(
     *   path="/v1/customer/get-traveller",
     *   security={
     *     {"bearerAuth": {}}
     *   },
     *   tags={"Customer"},
     *   summary="Get Traveller",
     *   description="need to pass traveller id to get traveller",
     *   operationId="get-traveller",
     *   @OA\Parameter(
     *      name="body",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           collectionFormat="multi",
                 
     *           @OA\Property(property="language_code",default="en", type="string"),
     *           @OA\Property(property="traveller_id",default="", type="string")
     *      )
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
     * )
     * get driver status
     *
     * @return \Illuminate\Http\Response
     */
    public function getTravellerById(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'language_code' => 'nullable|in:en,ar',
                'traveller_id' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => $validator->errors()->first(), 'data' => [$validator->errors()]], 200);
            }
            $perPage = Setting::where('config_key', 'general|setting|pagePerAPIRecords')->value('value');
            $filter = array(
                'id' => $request->traveller_id,
            );
            $travellerDataList = CustomerTraveller::getTravellerData($filter);
            $travellerData = $travellerDataList['data'];
            if ($travellerData) {
                $success = true;
                return $this->sendResponse($travellerData, 'Traveller Listed Successfully!', $success);
            } else {
                $success = [];
                return $this->sendError(no_record_found, $success, 200);
            }
        } catch (Exception $e) {
            $success = [];
            return $this->sendError($success, 'Something went wrong', ['error' => $e], 500);
        }
    }

    /**
     * @OA\Post(
     *   path="/v1/customer/create-traveller",
     *   security={
     *     {"bearerAuth": {}}
     *   },
     *   tags={"Customer"},
     *   summary="Create Traveller",
     *   description="Send request for Create Traveller",
     *   operationId="createTraveller",
     *   @OA\RequestBody(
     *     required=true,
     *     description="Create Traveller", 
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(
     *           required={"title","first_name","last_name","date_of_birth","gender","nationality","id_type","id_number","issue_date","expiry_date","issue_country"},
     *           @OA\Property(property="language_code", type="string",description="pass language_code either 'en' or 'ar'", default="en" ),
     *           @OA\Property(property="title", type="string",description="pass title either 'mr','mrs' or 'miss'", default="" ),
     *           @OA\Property(property="first_name", type="string",description="need to pass a first name", default="" ),
     *           @OA\Property(property="second_name", type="string",description="pass a second name", default="" ),
     *           @OA\Property(property="last_name", type="string",description="need to pass last name", default="" ),
     *           @OA\Property(property="date_of_birth", type="string",description="need to pass date of birth in Y-m-d format", default="" ),
     *           @OA\Property(property="gender", type="string",description="need to pass gender either 'male' or 'female'", default="" ),
     *           @OA\Property(property="nationality", type="string",description="need to pass iso_code that fetch by call API 'get-countries'", default="" ),
     *           @OA\Property(property="id_type", type="string",description="need to pass id type either 'passport' or 'national_id'", default="" ),
     *           @OA\Property(property="id_number", type="integer",description="need to pass id number as passed id type", default="" ),
     *           @OA\Property(property="issue_date", type="string",description="need to pass issue date in Y-m-d format", default="" ),
     *           @OA\Property(property="expiry_date", type="string",description="need to pass expiry date in Y-m-d format", default="" ),
     *           @OA\Property(property="issue_country", type="string",description="need to pass iso_code that fetch by call API 'get-countries'", default="" ),
     *           @OA\Property(property="status", type="string",description="pass status either 'active' or 'inactive'", default="" ),
     *           @OA\Property(property="upload_document", type="string", format="binary",description="select document image *ensure that you are uploading an image is 2MB or less and one of the following types: JPG,JPEG, or PNG"),
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
    public function createTraveller(Request $request)
    {
        $return = array(
            'status' => 0,
            'data' => [],
            'message' => 'Something went wrong'
        );
        try {
            $validator = Validator::make($request->all(), [
                'language_code' => 'nullable|in:en,ar',
                'title' => 'required|in:mr,mrs,miss',
                'first_name' => 'required',
                'last_name' => 'required',
                'date_of_birth' => 'required|date_format:Y-m-d|before:today',
                'gender' => 'required|in:male,female',
                'nationality' => 'required',
                'id_type' => 'required|in:passport,national_id',
                'id_number' => 'required',
                'issue_date' => 'required|date_format:Y-m-d',
                'expiry_date' => 'required|date_format:Y-m-d|after:issue_date',
                'issue_country' => 'required',
                'status' => 'nullable|in:active,inactive',
                'upload_document' => 'nullable|mimes:jpeg,jpg,png|max:2048',

            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => $validator->errors()->first(), 'data' => [$validator->errors()]], 200);
            }
            //validation foe iso code
            $isoCodenationality = Country::select('iso_code')->where('iso_code', request()->input('nationality'))->where('status', 'active')->value('iso_code');

            if (request()->input('nationality') != $isoCodenationality) {
                $success = [];
                return $this->sendError('Please Enter Valid nationality(iso_code)', $success, 400);
            }
            $isoCode = Country::select('iso_code')->where('iso_code', request()->input('issue_country'))->where('status', 'active')->value('iso_code');
            if (request()->input('issue_country') != $isoCode) {
                $success = [];
                return $this->sendError('Please Enter Valid issue country(iso_code)', $success, 400);
            }
            $travellerData = $request->all();
            $requestData = array(
                'title' => $travellerData['title'],
                'first_name' => $travellerData['first_name'],
                'second_name' => $travellerData['second_name'] ? $travellerData['second_name'] : '',
                'last_name' => $travellerData['last_name'],
                'date_of_birth' => $travellerData['date_of_birth'],
                'gender' => $travellerData['gender'],
                'nationality_id' => $travellerData['nationality'],
                'id_type' => $travellerData['id_type'],
                'id_number' => $travellerData['id_number'],
                'issue_date' => $travellerData['issue_date'],
                'expiry_date' => $travellerData['expiry_date'],
                'country_id' => $travellerData['issue_country'],
                'status' => $travellerData['status'],
                'document' => $travellerData['upload_document'],
            );
            $requestData['customer_id'] = Auth::id();

            $response = CustomerTraveller::createTravellers($requestData);
            if ($response) {
                $success = true;
                return $this->sendResponse($response, 'Traveller Saved Successfully!', $success);
            } else {
                $success = [];
                return $this->sendError('Traveller Not Saved', $success, 200);
            }
        } catch (\Exception $e) {
            $success = [];
            return $this->sendError($success, 'Something went wrong', ['error' => $e], 500);
        }
    }

    /**
     * @OA\Post(
     *   path="/v1/customer/update-traveller",
     *   security={
     *     {"bearerAuth": {}}
     *   },
     *   tags={"Customer"},
     *   summary="Update Traveller",
     *   description="Send request for Update Traveller",
     *   operationId="updateTraveller",
     *   @OA\RequestBody(
     *     required=true,
     *     description="Update Traveller", 
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(
     *           required={"traveller_id","title","first_name","last_name","date_of_birth","gender","nationality","id_type","id_number","issue_date","expiry_date","issue_country"},
     *           @OA\Property(property="language_code", type="string",description="pass language_code either 'en' or 'ar'", default="en" ),
     *           @OA\Property(property="traveller_id", type="string", default="" ,description="need to pass a traveller id as user want to update"),
     *           @OA\Property(property="title", type="string",description="pass title either 'mr','mrs' or 'miss'", default="" ),
     *           @OA\Property(property="first_name", type="string",description="need to pass a first name", default="" ),
     *           @OA\Property(property="second_name", type="string",description="pass a second name", default="" ),
     *           @OA\Property(property="last_name", type="string",description="need to pass last name", default="" ),
     *           @OA\Property(property="date_of_birth", type="string",description="need to pass date of birth in Y-m-d format", default="" ),
     *           @OA\Property(property="gender", type="string",description="need to pass gender either 'male' or 'female'", default="" ),
     *           @OA\Property(property="nationality", type="string",description="need to pass iso_code that fetch by call API 'get-countries'", default="" ),
     *           @OA\Property(property="id_type", type="string",description="need to pass id type either 'passport' or 'national_id'", default="" ),
     *           @OA\Property(property="id_number", type="integer",description="need to pass id number as passed id type", default="" ),
     *           @OA\Property(property="issue_date", type="string",description="need to pass issue date in Y-m-d format", default="" ),
     *           @OA\Property(property="expiry_date", type="string",description="need to pass expiry date in Y-m-d format", default="" ),
     *           @OA\Property(property="issue_country", type="string",description="need to pass iso_code that fetch by call API 'get-countries'", default="" ),
     *           @OA\Property(property="status", type="string",description="pass status either 'active' or 'inactive'", default="" ),
     *           @OA\Property(property="upload_document", type="string", format="binary",description="select document image *ensure that you are uploading an image is 2MB or less and one of the following types: JPG,JPEG, or PNG"),
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
    public function updateTraveller(Request $request)
    {
        $return = array(
            'status' => 0,
            'data' => [],
            'message' => 'Something went wrong'
        );
        try {
            $travellerId = CustomerTraveller::where('id', request()->input('traveller_id'))->where('customer_id', Auth()->user()->id)->value('id');
            if (!$travellerId) {
                $success = [];
                return $this->sendError(no_record_found, $success, 400);
            }
            $validator = Validator::make($request->all(), [
                'language_code' => 'nullable|in:en,ar',
                'title' => 'required|in:mr,mrs,miss',
                'first_name' => 'required',
                'traveller_id' => 'required',
                'last_name' => 'required',
                'date_of_birth' => 'required|date_format:Y-m-d|before:today',
                'gender' => 'required|in:male,female',
                'nationality' => 'required',
                'id_type' => 'required|in:passport,national_id',
                'id_number' => 'required',
                'issue_date' => 'required|date_format:Y-m-d',
                'expiry_date' => 'required|date_format:Y-m-d|after:issue_date',
                'issue_country' => 'required',
                'status' => 'nullable|in:active,inactive',
                'upload_document' => 'nullable|mimes:jpeg,jpg,png|max:2048',

            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => $validator->errors()->first(), 'data' => [$validator->errors()]], 200);
            }
            //validation foe iso code
            $isoCodenationality = Country::select('iso_code')->where('iso_code', request()->input('nationality'))->where('status', 'active')->value('iso_code');

            if (request()->input('nationality') != $isoCodenationality) {
                $success = [];
                return $this->sendError('Please Enter Valid nationality(iso_code)', $success, 400);
            }
            $isoCode = Country::select('iso_code')->where('iso_code', request()->input('issue_country'))->where('status', 'active')->value('iso_code');
            if (request()->input('issue_country') != $isoCode) {
                $success = [];
                return $this->sendError('Please Enter Valid issue country(iso_code)', $success, 400);
            }
            $travellerData = $request->all();
            // echo "<pre>";print_r($travellerData);die;
            $requestData = array(
                'traveller_id' => $travellerData['traveller_id'],
                'title' => $travellerData['title'],
                'first_name' => $travellerData['first_name'],
                'second_name' => $travellerData['second_name'] ? $travellerData['second_name'] : '',
                'last_name' => $travellerData['last_name'],
                'date_of_birth' => $travellerData['date_of_birth'],
                'gender' => $travellerData['gender'],
                'nationality_id' => $travellerData['nationality'],
                'id_type' => $travellerData['id_type'],
                'id_number' => $travellerData['id_number'],
                'issue_date' => $travellerData['issue_date'],
                'expiry_date' => $travellerData['expiry_date'],
                'country_id' => $travellerData['issue_country'],
                'status' => $travellerData['status'],
                'document' => $travellerData['upload_document'],
            );

            $requestData['customer_id'] = Auth::id();
            $response = CustomerTraveller::updateTraveller($requestData);

            if ($response) {
                $success = true;
                return $this->sendResponse($response, 'Traveller Updated Successfully!', $success);
            } else {
                $success = [];
                return $this->sendError('Traveller Not Saved', $success, 200);
            }
        } catch (\Exception $e) {
            $success = [];
            return $this->sendError($success, 'Something went wrong', ['error' => $e], 500);
        }
    }

    /**
     * @OA\Delete(
     *   path="/v1/customer/delete-traveller/{traveller_id}",
     *   security={
     *     {"bearerAuth": {}}
     *   },
     *   tags={"Customer"},
     *   summary="Delete traveller",
     *   description="need to pass traveller Id to delete traveller for multi traveller delete pass traveller id separated by commas",
     *   operationId="traveller-delete",
     *      @OA\Parameter(
     *         name="traveller_id",
     *         in="path",
     *         required=true,
     *         description="need to pass traveller Id to delete traveller for multi traveller delete pass traveller id separated by commas"
     *      ),
     * @OA\Parameter(
     *      name="language_code",
     *      in="query",
     *      required=false,
     *      description="Language code parameter either 'en' or 'ar'",
     *      @OA\Schema(
     *          type="string",
     *          example="en",
     *      )
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
     * )
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {

            $customer_id = Auth::id();
            

            $travellerIDs = explode(',', $id);
            $message = "";
            foreach ($travellerIDs as $teavell_id) {
                $traveller_id = CustomerTraveller::where('id', $teavell_id)->where('customer_id', $customer_id)->value('id');
                if ($traveller_id) {
                    $response = CustomerTraveller::deleteTravellers($traveller_id);
                    $message .= $response['message'] . '</br>';
                }
            }
            if (empty($response)) {
                return $this->sendError(no_record_found, [], 200);
            } else {
                return $this->sendResponse([], 'Traveller Deleted Successfully.');
            }
            // } else {
            //     return $this->sendError('Traveller Not Found', [], 200);
            // }
        } catch (Exception $e) {
            $success = [];
            return $this->sendError($success, 'Something went wrong', ['error' => $e], 500);
        }
    }
}
