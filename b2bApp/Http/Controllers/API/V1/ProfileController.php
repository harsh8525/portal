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
use App\Models\AppUserOtp;
use App\Models\AppUserAddresses;
use App\Models\Setting;
use App\Models\Customer;
use App\Models\User;
use App\Models\AdminUserOtp;
use App\Models\Agency;
use App\Models\UserLoginHistory;
use DB;
use App\Traits\EmailService;
use Carbon\Carbon;


class ProfileController extends BaseController
{

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
     *   path="/v1/profile/get-profile",
     *   security={
     *     {"bearerAuth": {}}
     *   },
     *   tags={"Profile"},
     *   summary="Get User Profile",
     *   description="get User Profile",
     *   operationId="get-profile",
    
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
    public function userProfile(Request $request)
    {

        try {


            $filter = array(
                'id' => Auth::id()
            );
            $response = User::getAdminUsers($filter);
            $userDetail = $response['data'];

            if ($response['status'] == 1 && !empty($response['data'])) {
                return $this->sendResponse([$userDetail], 'User Fetched Successfully');
            }
        } catch (Exception $e) {
            $success = [];
            return $this->sendError($success, 'Something went wrong', ['error' => $e], 500);
        }
    }


    /**
     * @OA\Post(
     *   path="/v1/profile/profile-update",
     *   security={
     *     {"bearerAuth": {}}
     *   },
     *   tags={"Profile"},
     *   summary="Send request for Profile Update",
     *   description="Profile update request ",
     *   operationId="profile-update",
     *   @OA\RequestBody(
     *     required=true,
     *     description="Profile Update", 
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(
     *             required={"full_name"},
     *             @OA\Property(property="full_name", type="string",description="pass user's full name"),
     *             @OA\Property(property="profile_image", type="string", format="binary",description="select user's profile image *ensure that you are uploading an image is 2MB or less and one of the following types: JPG,JPEG, or PNG"),
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
    public function profileUpdate(Request $request)
    {
        try {
            // echo "<pre>";print_r(request()->all());die;
            $validator = Validator::make($request->all(), [
                'full_name' => 'required',
                'profile_image' => 'nullable|mimes:jpeg,jpg,png|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => 'Invalid request', 'data' => [$validator->errors()]], 200);
            }
            $appUser = User::find(Auth::id());


            if ($appUser) {
                if ($appUser->status == '0') {
                    $success = [];
                    return $this->sendError('User is In Active, please contact to administrator', $success, 200);
                } else if ($appUser->status == '2') {
                    $success = [];
                    return $this->sendError('User not Found', $success, 200);
                }
            } else {
                return $this->sendResponse([], 'User Not Found');
            }


            //            $appUserAddress = AppUserAddresses::with('GetAppUserAddress')->where('user_id', Auth::id())->first();
            //            echo"<pre>";print_r($appUser);

            $requestData = $request->all();
            // echo "<pre>";print_r($requestData);die;
            $requestData['app_user_id'] = $appUser->id;
            $requestData['fname'] = $request->full_name;
            $requestData['profile_image'] = $request->profile_image ? $request->profile_image : '';
            $requestData['old_profile_image'] = $appUser->profile_image;


            $filter = array(
                'admin_user_id' => $appUser->id,
                'role' => $appUser->role_code,
                'mobile' => $appUser->mobile,
                'email' => $appUser->email,
                'isd_code' => $appUser->isd_code,
                'status' => $appUser->status,
            );
            $filter1 = array_merge($filter, $requestData);
            $response = User::updateUser($filter1);
            // echo "<pre>";print_r($response);die;

            if ($response['status'] == 1 && !empty($response['data'])) {
                return $this->sendResponse([$response], 'Profile Updated Successfully');
            }
        } catch (Exception $e) {
            $success = [];
            return $this->sendError($success, 'Something went wrong', ['error' => $e], 500);
        }
    }

    /**
     * @OA\Post(
     *   path="/v1/profile/change-password",
     *   security={
     *     {"bearerAuth": {}}
     *   },
     *   tags={"Profile"},
     *   summary="Change Password",
     *   operationId="change-b2b-password",
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(
     *             required={"current_password","new_password","confirm_password"},
     *             @OA\Property(property="current_password", type="string", title="current password", description="enter current password"),
     *             @OA\Property(property="new_password", type="string" , title="new password", description="enter new password"),
     *             @OA\Property(property="confirm_password", type="string", title="confirm password", description="enter confirm password"),
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
     * OTP Verify
     *
     * @return \Illuminate\Http\Response
     */
    public function changePassword(Request $request)
    {
        try {
            // // set custom rule for password length validation
            // Validator::extend('password_length', function ($attribute, $value, $parameters, $validator) {
            //     $minPassLength = Setting::where('config_key', 'passwordSecurity|minimumPasswordLength')->get('value')[0]['value'];
            //     return ($minPassLength <= strlen($value)); 

            // });
            // //validate pass that should contain digits
            // Validator::extend('contain_digits', function ($attribute, $value, $parameters, $validator) {
            //     $matches = [];
            //     $minDigitsLength = Setting::where('config_key', 'passwordSecurity|numericCharacter')->get('value')[0]['value'];
            //     return (preg_match_all("/\d/", $value, $matches) >= $minDigitsLength); 

            // });
            // //validate pass that should contain special character
            // Validator::extend('special_character', function ($attribute, $value, $parameters, $validator) {
            //     $matches = [];
            //     $minSpecialcharLength = Setting::where('config_key', 'passwordSecurity|specialCharacter')->get('value')[0]['value'];
            //     return (preg_match_all("/\W/", $value, $matches) >= $minSpecialcharLength); 

            // });
            // //validate pass that should contain capital letter
            // Validator::extend('capital_letter', function ($attribute, $value, $parameters, $validator) {
            //     $minUppercharLength = Setting::where('config_key', 'passwordSecurity|uppercaseCharacter')->get('value')[0]['value'];
            //     return (preg_match('/^(.*?[A-Z]){' . $minUppercharLength . '}/', $value)); 

            // });
            // //validate pass that should contain small letter
            // Validator::extend('small_letter', function ($attribute, $value, $parameters, $validator) {
            //     $minLowercharLength = Setting::where('config_key', 'passwordSecurity|lowercaseCharacter')->get('value')[0]['value'];
            //     return (preg_match('/^(.*?[a-z]){' . $minLowercharLength . '}/', $value)); 

            // });
            // //validate pass that shoult contain alphanumeric
            // Validator::extend('alphanumeric', function ($attribute, $value, $parameters, $validator) {
            //     $minAlphanumericcharLength = Setting::where('config_key', 'passwordSecurity|alphanumericCharacter')->get('value')[0]['value'];
            //     return (preg_match_all("/[a-zA-Z0-9]/", $value, $matches) > $minAlphanumericcharLength); 

            // });

            // //setting's values for password security verify
            // $minPassLength = Setting::where('config_key', 'passwordSecurity|minimumPasswordLength')->get('value')[0]['value'];
            // $minDigitsLength = Setting::where('config_key', 'passwordSecurity|numericCharacter')->get('value')[0]['value'];
            // $minSpecialcharLength = Setting::where('config_key', 'passwordSecurity|specialCharacter')->get('value')[0]['value'];
            // $minUppercharLength = Setting::where('config_key', 'passwordSecurity|uppercaseCharacter')->get('value')[0]['value'];
            // $minLowercharLength = Setting::where('config_key', 'passwordSecurity|lowercaseCharacter')->get('value')[0]['value'];
            // $minAlphanumericcharLength = Setting::where('config_key', 'passwordSecurity|alphanumericCharacter')->get('value')[0]['value'];
            $requestData = $request->all();
            $data['key'] = array_keys($requestData)[1];
            $data['password'] = $request->new_password;
            $responseData = resetPassword($data);
            // echo '<pre>';print_r($data);die;
            if ($responseData['valid'] != '1') {
                return response()->json(['status' => false, 'message' => 'Invalid request', 'data' => $responseData], 422);
            }
            $validator = Validator::make($request->all(), [
                'current_password' => 'required',
                'new_password' => 'required',
                'confirm_password' => 'required|same:new_password',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => 'Invalid request', 'data' => [$validator->errors()]], 422);
            }

            $requestData = $request->all();


            if (!Hash::check($request->current_password, Auth::user()->password)) {

                $success = [];
                return $this->sendError('Invalid Current Password', $success, 200);
            }

            if (Auth::user()) {

                $data = [
                    'password' => Hash::make($requestData['new_password']),
                    'password_updated_at' => date('Y-m-d h:i:s')
                ];
                User::where('id', Auth::id())->update($data);
                $userData = User::where('id', Auth::id())->get()->toArray();


                $checkNotifyEnable = Setting::where('config_key', 'passwordSecurity|changePasswordNotify')->get('value')[0]['value'];
                if ($checkNotifyEnable == '1') {
                    $userDetail = User::where('id', Auth::id())->get()->toArray();
                    $code = 'CHANGE_PASSWORD';
                    $data = processSimpleTemplate($userDetail[0]);
                    // echo "<pre>";print_r($data);die;
                    $userData['agency_logo'] = $data['agency_logo'];
                    $userData['customer_name'] = $data['user_name'];
                    $userData['agency_name'] = $data['agency_name'];
                    $getTemplateData = $this->changePasswordMailTemplate($code, $userData);
                    // echo "<pre>";print_r($getTemplateData);die;
                    if ($getTemplateData['status'] == 'false') {
                        return back()->with('error', $getTemplateData['error']);
                    } else {
                        $subject = $getTemplateData['data']['subject'];
                        $mailData = $getTemplateData['data']['mailData'];
                        $toEmail = $userDetail[0]['email'];
                        $files = [];

                        // set data in sendEmail function
                        $data = $this->sendEmail($toEmail, $subject, $mailData, $files, $code);
                    }
                }

                return $this->sendResponse([], 'Your Password changed Successfully');
            } else {
                $success = [];
                return $this->sendError('User not found', $success, 200);
            }
        } catch (Exception $e) {
            $success = [];
            return $this->sendError($success, 'Something went wrong', ['error' => $e], 500);
        }
    }
}
