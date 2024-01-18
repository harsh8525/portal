<?php

namespace B2BApp\Http\Controllers\API\V1;

use B2BApp\Http\Controllers\API\V1\BaseController as BaseController;
use B2BApp\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Str;
use App\Models\AppUsers;
use App\Models\Agency;
use App\Models\AppUserOtp;
use App\Models\AppUserAddresses;
use App\Models\Setting;
use App\Models\Customer;
use App\Models\User;
use App\Models\AdminUserOtp;
use App\Models\UserLoginHistory;
use DB;
use App\Traits\EmailService;
use Carbon\Carbon;


class AgencyController extends BaseController {

    use EmailService;
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::user()->token()->name != 'b2bAuthToken') {
                return response()->json(['status' => false, 'message' => 'Not authorized user', 'data' => []], 401);
            }

            return $next($request);
        });
    }
    
    /**
     * @OA\Get(
     *   path="/v1/agency/get-agency",
     *   security={
     *     {"bearerAuth": {}}
     *   },
     *   tags={"Agency"},
     *   summary="Get User Agency",
     *   description="get User Agency",
     *   operationId="get-agency",
    
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
    public function userAgency(Request $request){
        try{
          
        
            $filter = array(
                'id' => Auth::user()->agency_id
            );
            $response = Agency::getAgency($filter);
            $userDetail = $response['data'];
            
            
            if ($response['status'] == 1 && !empty($response['data'])) {
                return $this->sendResponse([$userDetail], 'User Agency Fetched Successfully');
            }
        }catch(Exception $e){
            $success = [];
            return $this->sendError($success,'Something went wrong', ['error'=>$e], 500);
        }
        
    }
    /**
     * @OA\Post(
     *   path="/v1/agency/agency-update",
     *   security={
     *     {"bearerAuth": {}}
     *   },
     *   tags={"Agency"},
     *   summary="Agency Update",
     *   description="update agency",
     *   operationId="update-agency",
     *   @OA\RequestBody(
     *     required=true,
     *     description="Agency Update", 
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(
     *            required={"agency_name","short_name","contact_name","position","email","license_number","phone_number","fax_number",
     *                              "web_url","status","address","country","state","city","pincode","payment_option","service_type","payment_gateway","enable_currency"},
     *           @OA\Property(property="agency_name", type="string", default="",description="enter agency name"),
     *           @OA\Property(property="short_name", type="string", default="",description="enter agency short name"),
     *           @OA\Property(property="contact_name", type="string", default="",description="enter agency contact name"),
     *           @OA\Property(property="position", type="string", default="",description="enter agency position"),
     *           @OA\Property(property="email", type="string", default="",description="enter agency email address"),
     *           @OA\Property(property="license_number", type="string", default="",description="enter agency license number"),
     *           @OA\Property(property="phone_number", type="string", default="",description="enter agency phone number"),
     *           @OA\Property(property="fax_number", type="string", default="",description="enter agency fax number"),
     *           @OA\Property(property="web_url", type="string", default="",description="enter agency web URL"),
     *           @OA\Property(property="status", type="string", default="",description="need to pass status for agency active or in-active, or terminated for active=1, in-active=0 terminated=2"),
     *           @OA\Property(property="agency_logo", type="file", default="", format="binary",description="select agency logo, uploading an image with 2MB or less and logo must be type of: JPG,JPEG, or PNG" ),
     *           @OA\Property(property="is_stop_buy", type="string", default="",description="need to pass is_stop_buy value 1 or 0 where 1=true and 0=false"),
     *           @OA\Property(property="is_search_only", type="string", default="",description="need to pass is_search_only value 1 or 0 where 1=true and 0=false"),
     *           @OA\Property(property="is_cancel_right", type="string", default="",description="need to pass is_cancel_right value 1 or 0 where 1=true and 0=false"),
     *           @OA\Property(property="iata_number", type="string", default="",description="enter agency IATA number"),
     *             @OA\Property(property="place_id", type="string"),
     *             @OA\Property(property="address", type="string", description="enter agency address"),
     *             @OA\Property(property="country", type="string", description="enter agency country"),
     *             @OA\Property(property="state", type="string", description="enter agency state"),
     *             @OA\Property(property="city", type="string", description="enter agency city"),
     *             @OA\Property(property="pincode", type="string", description="enter agency pincode"),
     *           @OA\Property(property="payment_option", type="string", default="",description="enter agency payment option's existing id seperated by comma"),
     *           @OA\Property(property="service_type", type="string", default="",description="enter agency service type's existing id seperated by comma"),
     *           @OA\Property(property="payment_gateway", type="string", default="",description="enter agency payment gateway's existing id seperated by comma"),
     *           @OA\Property(property="enable_currency", type="string", default="",description="enter agency enable currency's existing id seperated by comma"),
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
     * )
     *
     * @return \Illuminate\Http\Response
     */
    public function updateAgency(Request $request)
    {
        // echo "<pre>";print_r($request->all());die;
        
        try {
            // set custom rule for email validation
            Validator::extend('email_rule', function ($attribute, $value) {
                return preg_match('/(.+)@(.+)\.(.+)/i', $value);
            },'The email format is invalid.');
            $webUrlRegex = '/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/';
            $validator = Validator::make($request->all(), [
                'agency_name' => 'required',
                'short_name' => 'required',
                'contact_name' => 'required',
                'position' => 'required',
                'email' => 'required|email_rule',
                'license_number' => 'required',
                'phone_number' => 'required|numeric',
                'fax_number' => 'required',
                'web_url' => 'required|regex:'.$webUrlRegex,
                'status' => 'required|in:1,0,2',
                'is_stop_buy' => 'nullable|in:1,0',
                'is_search_only' => 'nullable|in:1,0',
                'is_cancel_right' => 'nullable|in:1,0',
                'agency_logo' => 'nullable|mimes:jpeg,jpg,png|max:2048',
                'iata_number' => 'nullable|regex:/^\d{7}$/',
                'address' => 'required',
                'country' => 'required',
                'state' => 'required',
                'city' => 'required'
            ],[
                'iata_number.regex' => 'The field must contain exactly 7 digits.'
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => 'Invalid request', 'data' => [$validator->errors()]], 200);
            }
            
            try{
                $data = [
                    'agency_id' => Auth::user()->id,
                    'agency_name' => $request->agency_name,
                    'short_name' => $request->short_name,
                    'contact' => $request->contact_name,
                    'position' => $request->position,
                    'license_number' => $request->license_number,
                    'phone_no' => $request->phone_number,
                    'fax_no' => $request->fax_number,
                    'email' => $request->email,   
                    'web_url' => $request->web_url,
                    'iata_number' => $request->iata_number,   
                    'agency_logo' => $request->agency_logo,
                    'status' => $request->status,   
                    

                ];
            }catch (Exception $ex) {
            $success = [];
            return $this->sendError('Error During Update Agency', $success);
        }
            
            $response = User::updateUser($userData);
            // echo "<pre>";
            // print_r($response);
            // die;
            // $appUserData = AppUsers::updateUser($requestData);

            if ($response['data']) {
                $success = 1;
                return $this->sendResponse($response, 'User Updated Successfully', $success);
            } else {
                $success = [];
                return $this->sendError('User Not Update', $success, 200);
            }
        } catch (Exception $ex) {
            $success = [];
            return $this->sendError('Error During Update Agency', $success);
        }
    }
    
    
    
}
