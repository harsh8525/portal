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
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;
use App\Models\CustomerTraveller;
use Illuminate\Support\Str;
use App\Models\Customer;
use App\Models\Country;
use App\Models\State;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\URL;
use App\Models\City;
use App\Models\Agency;
use App\Models\UserLoginHistory;
use App\Models\Setting;
use Carbon\Carbon;
use App\Models\AdminUserOtp;
use App\Traits\EmailService;
use App\Traits\ActiveLog;
use App\Traits\SmsService;
use DB;
use DateTime;
use Illuminate\Validation\Rule;



class CustomerController extends BaseController
{
    use EmailService, SmsService, ActiveLog;

    /**
     * @OA\Post(
     *   path="/v1/customer/customer-login",
     *   tags={"Customer"},
     *   summary="Customer login",
     *   description="Pass language code either en or ar<br>Pass login_with key either mobile or email<br>
        Pass isd code example:+91<br>
        if login with email pass email address Ex:example@gmail.com </br>
            or<br>
        if login with mobile pass mobile number Ex: 9999999999<br>
        Note: isd code field is required when login with mobile",
     *   operationId="customerLogin",
     *   @OA\RequestBody(
     *     required=true,
     *     description="Login Details", 
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(
     *             required={"login_with","mobile_or_email","password"},
     *             @OA\Property(property="language_code",default="en", type="string", description="enter value for language code either 'en' or 'ar'"),
     *             @OA\Property(property="login_with", type="string", description="enter value for login_with either mobile or email"),
     *             @OA\Property(property="isd_code", type="string", description="enter isd code"),
     *             @OA\Property(property="mobile_or_email", type="string", description="enter existing email address or mobile"),
     *             @OA\Property(property="password", type="string" , title="password", description="enter password"),
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
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {

        try {

            //check validation for either mobile or email using extends method
            Validator::extend('check_type', function ($attribute, $value, $parameters, $validator) {
                $condition = $parameters[0]; // Get the condition field name
                $conditionValue = $validator->getData()[$condition]; // Get the value of the condition field

                if ($conditionValue === 'mobile') {
                    // Check if the value is a valid mobile number
                    return preg_match('/^[0-9]+$/', $value);
                } elseif ($conditionValue === 'email') {
                    // Check if the value is a valid email address
                    return filter_var($value, FILTER_VALIDATE_EMAIL);
                }

                return false; // Invalid condition value
            }, 'The customer :conditionValue is in-valid');
            Validator::replacer('check_type', function ($message, $attribute, $rule, $parameters) use ($request) {
                $signupWith = $request->input('login_with'); // Access signup_with from the request
                return str_replace(':conditionValue', $signupWith, $message);
            });
            //isd code validation
            Validator::extend('valid_isd_code', function ($attribute, $value, $parameters, $validator) {
                // Implement the validation logic for ISD code
                return preg_match('/^\+\d+([- ]\d+)?$/', $value);
            }, "The :attribute must start with + followed by numbers, and may be followed by - or space and more numbers.");
            $validator = Validator::make($request->all(), [
                'language_code' => 'nullable|in:en,ar',
                'login_with' => 'required|in:email,mobile',
                'isd_code' => 'nullable|required_if:login_with,mobile|' . ($request->input('login_with') == 'mobile' ? 'valid_isd_code' : ''),
                'mobile_or_email' => 'required|check_type:login_with',
                'password' => 'required',

            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => 'Invalid request', 'data' => [$validator->errors()]], 422);
            }
            $validator->sometimes('isd_code', 'valid_isd_code', function ($input) {
                return $input->type == 'mobile';
            });

            if ($request->login_with == 'mobile') {
                $customer = Customer::where('mobile', '=', $request->isd_code . ' ' . $request->mobile_or_email)->where('status', '!=', 'deleted')->get()->first();
            } else {
                $customer = Customer::where('email', '=', $request->mobile_or_email)->where('status', '!=', 'deleted')->get()->first();
            }
            if (!empty($customer)) {

                //check that current host if avaible for login user
                $curentDateTime = date("Y-m-d H:i:s");
                $loginAttemptsDetail = DB::table('user_login_attempts')->Where('host', $_SERVER['REMOTE_ADDR'])->where('next_login_available_at', '>', $curentDateTime)->latest('next_login_available_at')->get()->toArray();
                $lockoutTimePerUserOrHostType = Setting::where('config_key', 'loginAttempts|lockOutTimePeriodType')->get('value')[0]['value'];
                if (!empty($loginAttemptsDetail)) {
                    $datetime1 = new DateTime($curentDateTime);
                    $datetime2 = new DateTime($loginAttemptsDetail[0]->next_login_available_at);
                    $interval = $datetime1->diff($datetime2);
                    $days = $interval->d;
                    $hours = $interval->h;
                    $minutes = $interval->i;
                    $secounds = $interval->s;
                    $success = [];
                    if ($lockoutTimePerUserOrHostType == 'minute') {
                        if ($minutes == '0') {

                            return $this->sendError('Your account has been locked, you can login after ' . $secounds . ' seconds', $success, 200);
                        } else {
                            return $this->sendError('Your account has been locked, you can login after ' . $minutes . ' minutes', $success, 200);
                        }
                    }
                    if ($lockoutTimePerUserOrHostType == 'hour') {
                        if ($hours != '0') {
                            return $this->sendError('Your account has been locked, you can login after ' . $hours . ' hour', $success, 200);
                        } else if ($minutes == '0') {

                            return $this->sendError('Your account has been locked, you can login after ' . $secounds . ' seconds', $success, 200);
                        } else {
                            return $this->sendError('Your account has been locked, you can login after ' . $minutes . ' minutes', $success, 200);
                        }
                    }
                    if ($lockoutTimePerUserOrHostType == 'day') {
                        if ($days != '0') {
                            return $this->sendError('Your account has been locked, you can login after ' . $days . ' day', $success, 200);
                        } else if ($hours != '0') {
                            return $this->sendError('Your account has been locked, you can login after ' . $hours . ' hour', $success, 200);
                        } else if ($minutes == '0') {

                            return $this->sendError('Your account has been locked, you can login after ' . $secounds . ' seconds', $success, 200);
                        } else {
                            return $this->sendError('Your account has been locked, you can login after ' . $minutes . ' minutes', $success, 200);
                        }
                    }
                }

                if (!Hash::check($request->password, $customer['password'])) {

                    $isEnableLoginAttempt = Setting::where('config_key', 'loginAttempts|enable')->get('value')[0]['value'];
                    if ($customer != "" && $isEnableLoginAttempt == '1') {



                        //function to check login attempt when credential are wrong 
                        $checkLoginAttempt = self::checkLoginAttempt($customer->id, $request->language_code);

                        $success = [];

                        return $this->sendError($checkLoginAttempt, $success, 200);
                    } else {
                        $success = [];
                        return $this->sendError('Invalid Login Credentials', $success, 200);
                    }
                } else {

                    $checkAgencyStatus = Agency::where('id', $customer['agency_id'])->value('status');
                    if ($checkAgencyStatus == 'active') {
                        //set route redirection for b2c
                        $success[] = 1;
                        return $this->sendResponse([$success], 'Customer Login Successfully');
                    } else {

                        //send mail to customer if agency status is inavtive or terminated
                        $agencyLogo = Setting::where('config_key', 'general|basic|colorLogo')->get('value')[0]['value'] ?? Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'];
                        $code = 'AGENCY_BLOCK';
                        $siteName = count(Setting::where('config_key', 'general|basic|siteName')->get('value')) > 0 ? Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'] : "Amar Infotech";
                    }
                }

                if (Auth::guard('appuser')->loginUsingId($customer->id)) {
                    $customerData = Auth::guard('appuser')->user();

                    if ($customer->status == 'inactive') {
                        $success = [];
                        return $this->sendError('User is In-Active, please contact to administrator', $success, 200);
                    } else if ($customer->status == 'deleted' || $customer->status == 'terminated') {
                        $success = [];
                        return $this->sendError('Customer not Found', $success, 200);
                    } else {
                        $success = $customer;
                        $success['token'] = $customerData->createToken('AuthToken')->accessToken;

                        return $this->sendResponse([$success], 'Customer Login Successfully');
                    }
                } else {
                    $success = [];
                    return $this->sendError('Error During Login', $success, 200);
                }
            } else {

                $success = [];
                return $this->sendError('Customer not found', $success, 200);
            }
        } catch (Exception $e) {
            $success = [];
            return $this->sendError($success, 'Something went wrong', ['error' => $e], 500);
        }
    }
    //check login attempts criteria based o login attempts preference
    function checkLoginAttempt($username, $language_code)
    {
        $loginAttemptPerHost = Setting::where('config_key', 'loginAttempts|perHost')->get('value')[0]['value'];
        $loginAttemptPerUser = Setting::where('config_key', 'loginAttempts|perUser')->get('value')[0]['value'];
        $isEnableNotification = Setting::where('config_key', 'loginAttempts|emailNotification')->get('value')[0]['value'];
        $lockoutTimePerUserOrHost = Setting::where('config_key', 'loginAttempts|lockOutTimePeriod')->get('value')[0]['value'];
        $lockoutTimePerUserOrHostType = Setting::where('config_key', 'loginAttempts|lockOutTimePeriodType')->get('value')[0]['value'];
        $lockoutTimePeriod = Setting::where('config_key', 'loginAttempts|lockOutTimePeriod')->get('value')[0]['value'];
        $lockoutTimePeriodType = Setting::where('config_key', 'loginAttempts|lockOutTimePeriodType')->get('value')[0]['value'];
        $loginTimePeriod = Setting::where('config_key', 'loginAttempts|loginTimePeriod')->get('value')[0]['value'];
        $loginTimePeriodType = Setting::where('config_key', 'loginAttempts|loginTimePeriodType')->get('value')[0]['value'];
        $getTimeZone = count(Setting::where('config_key', 'general|site|timeZone')->get('value')) > 0 ? Setting::where('config_key', 'general|site|timeZone')->get('value')[0]['value'] : "Asia/Kolkata";


        //modify date of lockouttimeperiod
        $nextAttemptDay = Carbon::now()->modify("+" . $lockoutTimePeriod . " " . $lockoutTimePeriodType);
        $modifyNextAttemptDate = $nextAttemptDay->format('Y-m-d H:i:s');

        //modify date of logintimeperiod
        $loginAttemptDate = Carbon::now()->modify("+" . $loginTimePeriod . " " . $loginTimePeriodType);
        $modifyLoginAttemptDate = $loginAttemptDate->format('Y-m-d H:i:s');

        //get current datetime
        $currentDate = date("Y-m-d H:i:s");

        //check if next attempt datetime available for the same user and it's greater than now
        $isNextDateAvailable = DB::table('user_login_attempts')->where('username', $username)->where('next_login_available_at', '<', $currentDate)->get()->toArray();

        //check wrong attempt count within givin login time period type
        $checkAttemptCount = Db::table('user_login_attempts')->where('username', $username)->where('attempt_at', '<', $modifyLoginAttemptDate)->count();
        if (!empty($isNextDateAvailable)) {
            DB::table('user_login_attempts')->where('username', $username)->delete();
        }


        $loginDetails = [
            'host' => $_SERVER['REMOTE_ADDR'],
            'username' => $username,
            'attempt_at' => date('Y-m-d H:i:s'),
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $data = DB::table('user_login_attempts')->insert($loginDetails);
        $attemptPerHost = Db::table('user_login_attempts')->where('host', $loginDetails['host'])->count();
        $attemptPerUser = Db::table('user_login_attempts')->where('username', $loginDetails['username'])->count();

        if ($attemptPerUser == $loginAttemptPerHost) {

            $lastRow = DB::table('user_login_attempts')->latest()->first();
            DB::table('user_login_attempts')->where('id', $lastRow->id)->update([
                'next_login_available_at' => $modifyNextAttemptDate
            ]);
            if ($isEnableNotification == '1') {

                $userData = Customer::where('id', $username)->get()->toArray();

                if ($userData[0]['agency_id'] == '0') {
                    $agencyLogo = count(Setting::where('config_key', 'general|basic|colorLogo')->get('value')) > 0 ? Setting::where('config_key', 'general|basic|colorLogo')->get('value')[0]['value'] : Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'];
                    $agencyName = count(Setting::where('config_key', 'general|basic|siteName')->get('value')) > 0 ? Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'] : "Amar Infotech";
                } else {

                    $agencyName = Agency::where('id', $userData[0]['agency_id'])->value('full_name');
                    $agencyLogo = Agency::where('id', $userData[0]['agency_id'])->value('logo');
                }
                $code = 'LOGIN_ATTEMPTS_EXCEED';
                $customerName = ucwords($userData[0]['first_name']);
                $data = array(
                    'customer_name' => $customerName,
                    'agency_name' => $agencyName,
                    'agency_logo' => $agencyLogo,
                    'hours' => $lockoutTimePerUserOrHost,
                    'duration' => ucwords($lockoutTimePerUserOrHostType)
                );

                $getTemplateData = $this->mailTemplateBlockAccount($code, $data, $language_code);
                if ($getTemplateData['status'] == 'false') {

                    return back()->with('error', $getTemplateData['error']);
                } else {
                    $subject = $getTemplateData['data']['subject'];
                    $mailData = $getTemplateData['data']['mailData'];
                    $toEmail = $userData[0]['email'];
                    $files = [];

                    // set data in sendEmail function
                    $data = $this->sendEmail($toEmail, $subject, $mailData, $files, $agencyName, $code);
                    return 'Your account has been locked, you can login after ' . $lockoutTimePeriod . ' ' . $lockoutTimePeriodType;
                }
            } else {
                return 'Your account has been locked, you can login after ' . $lockoutTimePeriod . ' ' . $lockoutTimePeriodType;
            }
        }

        $userAttemptTimeperiodCount = Db::table('user_login_attempts')->where('username', $loginDetails['username'])->where('attempt_at', '<', $modifyLoginAttemptDate)->count();
        if ($loginAttemptPerUser == $userAttemptTimeperiodCount) {

            $lastRow = DB::table('user_login_attempts')->latest()->first();
            DB::table('user_login_attempts')->where('id', $lastRow->id)->update([
                'next_login_available_at' => $modifyNextAttemptDate
            ]);
            if ($isEnableNotification == '1') {

                $userData = Customer::where('id', $username)->get()->toArray();
                if ($userData[0]['agency_id'] == '0') {
                    $agencyLogo = count(Setting::where('config_key', 'general|basic|colorLogo')->get('value')) > 0 ? Setting::where('config_key', 'general|basic|colorLogo')->get('value')[0]['value'] : Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'];
                    $agencyName = count(Setting::where('config_key', 'general|basic|siteName')->get('value')) > 0 ? Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'] : "Amar Infotech";
                } else {

                    $agencyName = Agency::where('id', $userData[0]['agency_id'])->value('full_name');
                    $agencyLogo = Agency::where('id', $userData[0]['agency_id'])->value('logo');
                }
                $code = 'LOGIN_ATTEMPTS_EXCEED';

                $customerName = ucwords($userData[0]['first_name']);

                $data = array(
                    'customer_name' => $customerName,
                    'agency_name' => $agencyName,
                    'agency_logo' => $agencyLogo,
                    'hours' => $lockoutTimePerUserOrHost,
                    'duration' => ucwords($lockoutTimePerUserOrHostType)


                );

                $getTemplateData = $this->mailTemplateBlockAccount($code, $data, $language_code);
                if ($getTemplateData['status'] == 'false') {
                    return back()->with('error', $getTemplateData['error']);
                } else {
                    $subject = $getTemplateData['data']['subject'];
                    $mailData = $getTemplateData['data']['mailData'];
                    $toEmail = $userData[0]['email'];
                    $files = [];

                    // set data in sendEmail function
                    $data = $this->sendEmail($toEmail, $subject, $mailData, $files, $agencyName, $code);
                    return 'Your account has been locked, you can login after ' . $lockoutTimePeriod . ' ' . $lockoutTimePeriodType;
                }
            } else {
                return 'Your account has been locked, you can login after ' . $lockoutTimePeriod . ' ' . $lockoutTimePeriodType;
            }
        } else {
            return 'Invalid Login Credentials';
        }
    }
    /**
     * @OA\Post(
     *   path="/v1/customer/customer-signup",
     *   tags={"Customer"},
     *   summary="Customer signup",
     *   description="Send request for Customer Sign Up using mobile or email",
     *   operationId="customerSignup",
     *   @OA\RequestBody(
     *     required=true,
     *     description="Customer Sign Up", 
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(
     *             required={"language_code","signup_with","customer_mobile_or_email","password" },
     *             @OA\Property(property="language_code",default="en", type="string",description="enter language code either 'en' or 'ar'"),
     *             @OA\Property(property="signup_with", type="string",description="enter value either 'mobile' or 'email'"),
     *             @OA\Property(property="isd_code", type="string",description="isd code is required when signup with mobile number"),
     *             @OA\Property(property="customer_mobile_or_email", type="string",description="enter mobile number if signup_with value is 'mobile' else email address"),
     *             @OA\Property(property="password", type="string",description="enter a password"),
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
    public function signUpCustomer(Request $request)
    {
        try {
            //check validation for either mobile or email using extends method
            Validator::extend('check_type', function ($attribute, $value, $parameters, $validator) {
                $condition = $parameters[0]; // Get the condition field name
                $conditionValue = $validator->getData()[$condition]; // Get the value of the condition field
                
                if ($conditionValue === 'mobile') {
                    // Check if the value is a valid mobile number
                    return preg_match('/^[0-9]+$/', $value);
                } elseif ($conditionValue === 'email') {
                    // Check if the value is a valid email address
                    return filter_var($value, FILTER_VALIDATE_EMAIL);
                }

                return false; // Invalid condition value
            }, 'The customer :conditionValue is in-valid');
            Validator::replacer('check_type', function ($message, $attribute, $rule, $parameters) use ($request) {
                $signupWith = $request->input('signup_with'); // Access signup_with from the request
                return str_replace(':conditionValue', $signupWith, $message);
            });
            //check validation for either mobile or email already exist or not
            Validator::extend('check_if_exist', function ($attribute, $value, $parameters, $validator) {
                $condition = $parameters[0]; // Get the condition field name
                $conditionValue = $validator->getData()[$condition];
                $conditionForISD = $parameters[1]; // Get the condition field name
                $conditionISDValue = $validator->getData()[$conditionForISD];
                
                if ($conditionValue == 'mobile') {
                    // Check if the value is a valid mobile number
                    $query = Customer::where('mobile', $conditionISDValue . ' ' . $value)
                    ->whereNotIn('status', ['terminated', 'deleted'])->get();
                    return !$query->count();
                } elseif ($conditionValue == 'email') {
                    // Check if the value is a valid email address
                    $query = Customer::where('email', $value)
                    ->whereNotIn('status', ['terminated', 'deleted'])->get();
                    return !$query->count();
                }

                return false; // Invalid condition value
            }, 'The customer :conditionValue already exist');

            //replacer validation message for either mobile or email
            Validator::replacer('check_if_exist', function ($message, $attribute, $rule, $parameters) use ($request) {
                $signupWith = $request->input('signup_with'); // Access signup_with from the request
                return str_replace(':conditionValue', $signupWith, $message);
            });
            
            //check mobile length must be as per mentioned in admin panel country module
            Validator::extend('check_mobile_length', function ($attribute, $value, $parameters, $validator) {
                $condition = $parameters[0]; // Get the condition field name
                $conditionValue = $validator->getData()[$condition];
                $conditionForISD = $parameters[1]; // Get the condition field name
                $conditionISDValue = $validator->getData()[$conditionForISD];
                
                if ($conditionValue == 'mobile') {
                    // Check if the value is a valid mobile number
                    $query = Country::where('isd_code', $conditionISDValue)->first();
                    if (!empty($query)) {
                        
                        $mobileLength = $query['max_mobile_number_length'];
                        return (strlen($value) == $mobileLength);
                    } else {
                        return true;
                    }
                } else {
                    return true;
                }
                
                return false; // Invalid condition value
            }, 'The customer mobile number must be contain :mobileLength digits');
            
            //replacer validation message for mobile number length
            Validator::replacer('check_mobile_length', function ($message, $attribute, $rule, $parameters) use ($request) {
                $isdCode = $request->input('isd_code'); // Access signup_with from the request
                $query = Country::where('isd_code', $isdCode)->first();
                return str_replace(':mobileLength', $query['max_mobile_number_length'], $message);
            });

            
            //isd code validation
            Validator::extend('valid_isd_code', function ($attribute, $value, $parameters, $validator) {
                // Implement the validation logic for ISD code
                return preg_match('/^\+\d+([- ]\d+)?$/', $value);
            }, "The :attribute must start with + followed by numbers, and may be followed by - or space and more numbers.");
            
            
            $validator = Validator::make($request->all(), [
                'language_code' => 'required|in:ar,en',
                'signup_with' => 'required|in:email,mobile',
                'isd_code' => 'nullable|required_if:signup_with,mobile|valid_isd_code',
                'customer_mobile_or_email' => 'required|check_type:signup_with|check_if_exist:signup_with,isd_code|check_mobile_length:signup_with,isd_code',
                'password' => 'required',
                
            ]);
            if ($validator->fails()) {
                $firstError = $validator->errors()->first();
                return response()->json(['status' => false, 'message' => $firstError, 'data' => [$validator->errors()]], 200);
            }

            $data['key'] = 'password';
            $data['password'] = $request->password;
            $responseData = resetPassword($data);

            if ($responseData['valid'] != '1') {
                return response()->json(['status' => false, 'message' => $responseData, 'data' => $responseData], 422);
            }

            $requestData = $request->all();
            $customerData = [
                'email' => ($request['signup_with'] == 'email') ? $request['customer_mobile_or_email'] : null,
                'mobile' => ($request['signup_with'] == 'mobile') ? $requestData['isd_code'] . ' ' . $request['customer_mobile_or_email'] : "",
                'password' => Hash::make($requestData['password'])
            ];
            //insert data into customer table
            $customer = Customer::create($customerData);

            if ($customer) {

                $isMail = Setting::select('value')->where('config_key', '=', 'mail|smtp|server')->first();
                if (isset($isMail) && $isMail->value == '0') {

                    return $this->sendResponse([$customer], 'Customer SignUp Saved Successfully');
                } else {
                    if ($customer['email'] != "") {
                        $language_code = Setting::where('config_key', 'general|site|defaultLanguageCode')->get('value')[0]['value'];
                        //send welcome agency mail to primary user
                        $welcomeAgencyCode = 'WELCOME_AGENCY';
                        $agencyLogo = Setting::where('config_key', 'general|basic|colorLogo')->get('value')[0]['value'] ?? Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'];

                        $siteName = count(Setting::where('config_key', 'general|basic|siteName')->get('value')) > 0 ? Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'] : "Amar Infotech";

                        $user = [
                            'agency_logo' => $agencyLogo,
                            'customer_name' => 'customer',
                            'agency_name' => $siteName
                        ];

                        $getWelcomeAgencyTemplateData = EmailService::customerWelcomeMailTemplete($welcomeAgencyCode, $user, $request->language_code);

                        if ($getWelcomeAgencyTemplateData['status'] == 'false') {
                            return back()->with('error', $getWelcomeAgencyTemplateData['error']);
                        } else {
                            $welcomeMailsubject = $getWelcomeAgencyTemplateData['data']['subject'];
                            $welcomeMailData = $getWelcomeAgencyTemplateData['data']['mailData'];
                            $welcomeAgencyToEmail = $customer['email'];
                            $files = [];

                            // set data in sendEmail function
                            $data = EmailService::sendEmail($welcomeAgencyToEmail, $welcomeMailsubject, $welcomeMailData, $files, $siteName);
                        }


                        return $this->sendResponse([$customer], 'Customer signUp saved successfully, verification link send to registered e-mail address');
                    }

                    return $this->sendResponse([$customer], 'Customer signUp saved successfully');
                }
            }
        } catch (Exception $e) {
            $success = [];
            return $this->sendError($success, 'Something went wrong', ['error' => $e], 500);
        }
    }

    /**
     * @OA\Get(
     *   path="/v1/customer/get-customer-profile",
     *   security={
     *     {"bearerAuth": {}}
     *   },
     *   tags={"Customer"},
     *   summary="Get Customer Profile",
     *   description="get Customer Profile",
     *   operationId="get-customer-profile",
     *   @OA\Parameter(
     *      name="body",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           collectionFormat="multi",
                 
     *           @OA\Property(property="language_code",default="en", type="string")
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
    public function customerProfile(Request $request)
    {
        try {



            $filter = array(
                'id' => Auth::id()
            );
            $response = Customer::getCustomers($filter);
            $userDetail = $response['data'];
            $activityLog['request'] =  $request->all();
            $activityLog['request_url'] =  $request->url();
            $activityLog['response'] =  $response;
            ActiveLog::createActiveLog($activityLog);
            if ($response['status'] == 1 && !empty($response['data'])) {
                return $this->sendResponse([$userDetail], 'Customer Fetched Successfully');
            }
        } catch (Exception $e) {
            $success = [];
            return $this->sendError($success, 'Something went wrong', ['error' => $e], 500);
        }
    }
    /**
     * @OA\Post(
     *   path="/v1/customer/update-customer",
     *   security={
     *     {"bearerAuth": {}}
     *   },
     *   tags={"Customer"},
     *   summary="Update Customer",
     *   description="Send request for Update Customer",
     *   operationId="updateCustomer",
     *   @OA\RequestBody(
     *     required=true,
     *     description="Update Customer", 
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(
     *           required={"title","first_name","last_name","isd_code","mobile","email","date_of_birth","gender","marital_status","address1","country_id","state_id","city_id","pincode"},
     *           @OA\Property(property="language_code", type="string",description="pass language_code either 'en' or 'ar'", default="en" ),
     *           @OA\Property(property="title", type="string",description="pass title either 'mr','mrs' or 'miss'", default="" ),
     *           @OA\Property(property="first_name", type="string",description="need to pass a first name", default="" ),
     *           @OA\Property(property="last_name", type="string",description="need to pass last name", default="" ),
     *           @OA\Property(property="isd_code", type="string",description="need to pass isd code with format Ex: +91 or +355 1 or +355-1", default="" ),
     *           @OA\Property(property="mobile", type="string",description="need to pass mobile number", default="" ),
     *           @OA\Property(property="email", type="string",description="need to pass email address", default="" ),
     *           @OA\Property(property="date_of_birth", type="string",description="need to pass date of birth in Y-m-d format", default="" ),
     *           @OA\Property(property="gender", type="string",description="need to pass gender either 'male' or 'female'", default="" ),
     *           @OA\Property(property="marital_status", type="string",description="need to pass marital status one of from (married, single, other)", default="" ),
     *           @OA\Property(property="marriage_aniversary_date", type="string",description="need to pass marriage aniversary date with format Y-m-d", default="" ),
     *           @OA\Property(property="address1", type="string",description="need to pass address1", default="" ),
     *           @OA\Property(property="address2", type="string",description="need to pass address2", default="" ),
     *           @OA\Property(property="country_id", type="string",description="need to pass country id that fetch by call API 'get-country'", default="" ),
     *           @OA\Property(property="state_id", type="string",description="need to pass state id that fetch by call API 'get-states'", default="" ),
     *           @OA\Property(property="city_id", type="string",description="need to pass city id that fetch by call API 'get-city'", default="" ),
     *           @OA\Property(property="pincode", type="string",description="need to pass pincode", default="" ),
     *           @OA\Property(property="profile_photo", type="string", format="binary",description="select profile photo image *ensure that you are uploading an image is 2MB or less and one of the following types: JPG,JPEG, or PNG"),
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
    public function updateCustomer(Request $request)
    {

        $return = array(
            'status' => 0,
            'data' => [],
            'message' => 'Something went wrong'
        );

        $customer_id = Auth::user()->id;
        Validator::extend('check_if_exist', function ($attribute, $value, $parameters, $validator) use ($customer_id) {
            $condition = $parameters[0]; // Get the condition field name
            $conditionValue = $validator->getData()[$condition];

            // Check if the value is a valid mobile number
            $query = Customer::where('mobile', $conditionValue . ' ' . $value)->where('id', '!=', $customer_id)
            ->whereNotIn('status', ['terminated', 'deleted'])->get();
            return !$query->count();


            return false; // Invalid condition value
        }, 'The customer mobile already exist');

        try {

            $validator = Validator::make($request->all(), [
                'language_code' => 'nullable|in:en,ar',
                'title' => 'required|in:mr,mrs,miss',
                'first_name' => 'required',
                'last_name' => 'required',
                'isd_code' => 'required',
                'mobile' => 'required|check_if_exist:isd_code',
                'email' => [
                    'required',
                    Rule::unique('customers', 'email')->where(function ($query) {
                        $query->whereNotIn('status', ['terminated', 'deleted']);
                    })->ignore(Auth::user()->id),
                ],
                
                'date_of_birth' => 'required|date_format:Y-m-d|before:today',
                'gender' => 'required|in:male,female',
                'marital_status' => 'required|in:single,married,other',
                'marriage_aniversary_date' => [
                    'required_if:marital_status,married',
                    function ($attribute, $value, $fail) use ($request) {
                        if ($request->marital_status == 'married') {
                            if (!strtotime($value) || date('Y-m-d', strtotime($value)) !== $value) {
                                $fail("The $attribute does not match the format Y-m-d for married individuals.");
                            } elseif (strtotime($value) >= strtotime('today')) {
                                $fail("The $attribute must be a date before today for married individuals.");
                            }
                        }
                    },
                ],
                'address1' => 'required',
                'address2' => 'nullable',
                'country_id' => 'required',
                'state_id' => 'required',
                'city_id' => 'nullable',
                'pincode' => 'nullable',
                'profile_photo' => 'nullable|mimes:jpeg,jpg,png|max:2048',

            ]);
            if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => $validator->errors()->first(), 'data' => [$validator->errors()]], 200);
            }

            $numlength = strlen((string)request()->input('mobile'));
            $validMobileLength = Country::where('isd_code', request()->input('isd_code'))->where('status', 1)->value('max_mobile_number_length');
            if ($numlength != $validMobileLength) {
                $success = [];
                return $this->sendError('Mobile number must be at least ' . $validMobileLength . ' digits', $success, 400);
            }
            //validation foe country_id
            $isoCodecountry = Country::where('id', request()->input('country_id'))->where('status', 'active')->value('id');

            if (request()->input('country_id') != $isoCodecountry) {
                $success = [];
                return $this->sendError('Please Enter Valid country(id)', $success, 400);
            }
            //validation foe state_id
            $isoCode = State::where('id', request()->input('state_id'))->where('status', 'active')->value('id');
            if (request()->input('state_id') != $isoCode) {
                $success = [];
                return $this->sendError('Please Enter Valid state(id)', $success, 400);
            }
            //validation foe city_id
            $isoCode = City::where('id', request()->input('city_id'))->where('status', 'active')->value('id');
            if (request()->input('city_id') != $isoCode) {
                $success = [];
                return $this->sendError('Please Enter Valid City(id)', $success, 400);
            }
            $customerData = $request->all();
            $requestData = array(
                'customer_id' => Auth::id(),
                'title' => ucwords($customerData['title']),
                'first_name' => ucwords($customerData['first_name']),
                'last_name' => ucwords($customerData['last_name']),
                'mobile' => $customerData['mobile'],
                'isd_code' => $customerData['isd_code'],
                'email' => $customerData['email'],
                'status' => 'active',
                'date_of_birth' => date('Y-m-d', strtotime($customerData['date_of_birth'])),
                'gender' => $customerData['gender'],
                'marital_status' => $customerData['marital_status'],
                'marriage_aniversary_date' => $customerData['marriage_aniversary_date'],
                'profile_photo' => $customerData['profile_photo'],
                'address1' => $customerData['address1'],
                'address2' => $customerData['address2'],
                'country' => $customerData['country_id'],
                'state_code' => $customerData['state_id'],
                'city_code' => $customerData['city_id'],
                'pincode' => $customerData['pincode'],
            );

            $response = Customer::updateCustomer($requestData);
            $activityLog['request'] =  $request->all();
            $activityLog['request_url'] =  $request->url();
            $activityLog['response'] =  $response;
            ActiveLog::createActiveLog($activityLog);
            if ($response) {
                $success = true;
                return $this->sendResponse($response, 'Customer Updated Successfully!', $success);
            } else {
                $success = [];
                return $this->sendError('Customer Not Saved', $success, 200);
            }
        } catch (\Exception $e) {
            $success = [];
            return $this->sendError($success, 'Something went wrong', ['error' => $e], 500);
        }
    }
    /**
     * @OA\Post(
     *   path="/v1/customer/update-profilePicture",
     *   security={
     *     {"bearerAuth": {}}
     *   },
     *   tags={"Customer"},
     *   summary="Update Profile Picture",
     *   description="Send request for Update Profile Picture",
     *   operationId="profilePicture",
     *   @OA\RequestBody(
     *     required=true,
     *     description="Update Profile Picture", 
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(
     *           required={},
     *           @OA\Property(property="profile_photo", type="string", format="binary",description="select profile photo image *ensure that you are uploading an image is 2MB or less and one of the following types: JPG,JPEG, or PNG"),
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
    public function updateProfilePicture(Request $request)
    {
        $return = array(
            'status' => 0,
            'data' => [],
            'message' => 'Something went wrong'
        );

        $customer_id = Auth::user()->id;
        try {

            $validator = Validator::make($request->all(), [
               
                'profile_photo' => 'nullable|mimes:jpeg,jpg,png|max:2048',

            ]);
            if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => $validator->errors()->first(), 'data' => [$validator->errors()]], 200);
            }

            
            $customerData = $request->all();
            $requestData = array(
                'customer_id' => Auth::id(),
                'profile_photo' => $customerData['profile_photo'],
            );
            if (isset($requestData['profile_photo'])) {

                //upload image
                try {

                    $destinationPath = storage_path() . '/app/public/customer/';
                    if (!is_dir($destinationPath)) {
                        mkdir($destinationPath, 0777);
                    }

                    $file = $requestData['profile_photo'];
                    $image_resize = Image::make($requestData['profile_photo']);
                    $image_resize->resize(300, 300);
                    $fileName =  uniqid() . time() . '.' . $requestData['profile_photo']->extension();
                    $image_resize->save($destinationPath . $fileName);
                    $url = URL::to('/storage/') . '/customer/' . $fileName;
                    $customerData['profile_photo'] = $url;
                    if (isset($requestData['old_photo'])) {
                        $p = parse_url($requestData['old_photo']);


                        if ($p['path'] != "") {

                            $image_path = str_replace('/storage/', 'app/public/', $p['path']);
                            $image_path = storage_path($image_path);

                            if (file_exists($image_path)) {
                                unlink($image_path);
                            }
                        }
                    }
                } catch (Exception $e) {
                    $return['message'] = 'Error during save image customer :' . $e->getMessage();
                }
                try {

                    DB::beginTransaction();
                    $matchCustomer = ['id' => $requestData['customer_id']];
                    $customer = Customer::updateOrCreate($matchCustomer, $customerData);
                    DB::commit();
                  
                } catch (\Exception $e) {
                    $return['message'] = 'Error during save user record : ' . $e->getMessage();
                }
            $activityLog['request'] =  $request->all();
            $activityLog['request_url'] =  $request->url();
            $activityLog['response'] =  $customer;
            ActiveLog::createActiveLog($activityLog);
            if ($customer) {
                $success = true;
                return $this->sendResponse($customer, 'Customer Updated Successfully!', $success);
            } else {
                $success = [];
                return $this->sendError('Customer Not Saved', $success, 200);
            }
        } 
    }catch (\Exception $e) {
            $success = [];
            return $this->sendError($success, 'Something went wrong', ['error' => $e], 500);
        }
    }
    /**
     * @OA\Post(
     *   path="/v1/customer/verification-otp/send",
     *   tags={"Customer"},
     *   summary="OTP Send On Mobile or Email",
     *   description="Pass type value either mobile or email<br>Pass ISD Code Ex: +358, it's required when type is mobile<br>Pass mobile_or_email value Ex: 9999999999 or example@gmail.com</br>Pass Language code Ex: en or ar",
     *   operationId="otpVerificationSend",
     *   @OA\Parameter(
     *      name="body",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           collectionFormat="multi",
                 required={"type","isd_code","mobile_or_email","language_code"},
                 
     *           @OA\Property(property="type", type="string"),
     *           @OA\Property(property="isd_code", type="string"),
     *           @OA\Property(property="mobile_or_email", type="string"),
     *           @OA\Property(property="language_code",default="en", type="string"),
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
     * Login Send
     *
     * @return \Illuminate\Http\Response
     */
    public function otpVerificationSend(Request $request)
    {
        try {
            //check validation for either mobile or email using extends method
            Validator::extend('check_type', function ($attribute, $value, $parameters, $validator) {
                $condition = $parameters[0]; // Get the condition field name
                $conditionValue = $validator->getData()[$condition]; // Get the value of the condition field

                if ($conditionValue === 'mobile') {
                    // Check if the value is a valid mobile number
                    return preg_match('/^[0-9]+$/', $value);
                } elseif ($conditionValue === 'email') {
                    // Check if the value is a valid email address
                    return filter_var($value, FILTER_VALIDATE_EMAIL);
                }

                return false; // Invalid condition value
            }, 'The customer :conditionValue is in-valid');
            Validator::replacer('check_type', function ($message, $attribute, $rule, $parameters) use ($request) {
                $signupWith = $request->input('type'); // Access signup_with from the request
                return str_replace(':conditionValue', $signupWith, $message);
            });


            //check mobile length must be as per mentioned in admin panel country module
            Validator::extend('check_mobile_length', function ($attribute, $value, $parameters, $validator) {
                $condition = $parameters[0]; // Get the condition field name
                $conditionValue = $validator->getData()[$condition];
                $conditionForISD = $parameters[1]; // Get the condition field name
                $conditionISDValue = $validator->getData()[$conditionForISD];

                if ($conditionValue == 'mobile') {
                    // Check if the value is a valid mobile number
                    $query = Country::where('isd_code', $conditionISDValue)->first();
                    if (!empty($query)) {

                        $mobileLength = $query['max_mobile_number_length'];
                        return (strlen($value) == $mobileLength);
                    } else {
                        return true;
                    }
                } else {
                    return true;
                }

                return false; // Invalid condition value
            }, 'The customer mobile number must be contain :mobileLength digits');

            //replacer validation message for mobile number length
            Validator::replacer('check_mobile_length', function ($message, $attribute, $rule, $parameters) use ($request) {
                $isdCode = $request->input('isd_code'); // Access signup_with from the request
                $query = Country::where('isd_code', $isdCode)->first();
                return str_replace(':mobileLength', $query['max_mobile_number_length'], $message);
            });


            //isd code validation
            Validator::extend('valid_isd_code', function ($attribute, $value, $parameters, $validator) {
                // Implement the validation logic for ISD code
                return preg_match('/^\+\d+([- ]\d+)?$/', $value);
            }, "The :attribute must start with + followed by numbers, and may be followed by - or space and more numbers.");

            $validator = Validator::make($request->all(), [
                'type' => 'required|in:email,mobile',
                'isd_code' => 'nullable|required_if:type,mobile|' . ($request->input('type') == 'mobile' ? 'valid_isd_code' : ''),
                'mobile_or_email' => 'required|check_type:type|check_mobile_length:type,isd_code',
                'language_code' => 'required|in:ar,en',

            ], [
                'type.in' => 'entered type must be either email or mobile',
                'language_code.in' => 'entered language code is wrong, it must be either ar or en'
            ]);
            $validator->sometimes('isd_code', 'valid_isd_code', function ($input) {
                return $input->type == 'mobile';
            });

            if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => $validator->errors()->first(), 'data' => [$validator->errors()]], 422);
            }
            if ($request['type'] != "email") {

                $getCustomerDetails = Customer::where('mobile', $request->isd_code . ' ' . $request->mobile_or_email)->where('status','active')->first();
            } else {

                $getCustomerDetails = Customer::where('email', $request->mobile_or_email)->where('status','active')->first();
            }


            if ($getCustomerDetails) {

                //get OTP verification setting key value 'general|otp|phoneVerification'
                $otp_verification = false;
                $otp_setting_data = \App\Models\Setting::where('config_key', 'general|otp|phoneVerification')->get()->toArray();

                if ($getCustomerDetails->status == 'active') {
                    if ($request['type'] != 'email') {
                        $otp = rand(100000, 999999);

                        $currentDate = date('Y-m-d H:i:s');
                        $datee = strtotime($currentDate);
                        $otp_expire_minute = 5;
                        $date1 = strtotime("+" . $otp_expire_minute . "minute", $datee);
                        $otp_expire = date('Y-m-d H:i:s', $date1);

                        $temp = array(
                            "otp" => $otp,
                            "mobile" => $request->isd_code . ' ' . $request->mobile_or_email,
                            "expired" => $otp_expire,
                        );

                        $mobile_number = ['mobile' => $request->isd_code . ' ' . $request->mobile_or_email];
                        $message = "$otp is your one time password to proceed on Web Site. It is valid for $otp_expire_minute minutes";

                        if (!empty($otp_setting_data)) {
                            if ($otp_setting_data[0]['value'] == "on") {
                                $otp_verification = true;
                                //send OTP SMS code
                                $isd_mobile = $request->isd_code . $request->mobile_or_email;
                                $this->sendSms($isd_mobile, $message);
                            }
                        }

                        AdminUserOtp::updateOrCreate($mobile_number, $temp);

                        $success['otp'] = $otp;
                        $success['otp_verify'] = $otp_verification;
                        $response = array($success);

                        return $this->sendResponse($response, "OTP send successfully to your registered Mobile");
                    } else {
                        if ($getCustomerDetails['first_name'] != "" && $getCustomerDetails['last_name'] != "") {
                            $customerName = $getCustomerDetails['first_name'] . ' ' . $getCustomerDetails['last_name'];
                        } else {
                            $customerName = 'Customer';
                        }
                        $email = $getCustomerDetails->email;
                        $otp = rand(100000, 999999);

                        $currentDate = date('Y-m-d H:i:s');
                        $datee = strtotime($currentDate);
                        $otp_expire_minute = 5;
                        $date1 = strtotime("+" . $otp_expire_minute . "minute", $datee);
                        $otp_expire = date('Y-m-d H:i:s', $date1);

                        $temp = array(
                            "otp" => $otp,
                            "mobile" => $request->mobile_or_email,
                            "expired" => $otp_expire,
                        );

                        $emailAddress = ['mobile' => $request->mobile_or_email];
                        $code = 'SEND_OTP';
                        $siteName = count(Setting::where('config_key', 'general|basic|siteName')->get('value')) > 0 ? Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'] : "Amar Infotech";
                        $otp = $otp;
                        $otp_expire_minute = $otp_expire_minute;
                        $agencyLogo = Setting::where('config_key', 'general|basic|colorLogo')->get('value')[0]['value'] ?? Setting::where('config_key', 'general|basic|colorLogo')->get('value')[0]['value'];
                        $agencyName = count(Setting::where('config_key', 'general|basic|siteName')->get('value')) > 0 ? Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'] : "Rehlte";
                        $customerMailData = [
                            'customer_name' => $customerName,
                            'otp' => $otp,
                            'otp_expire_minute' => $otp_expire_minute,
                            'site_name' => $siteName,
                            'agencyLogo' => $agencyLogo,
                            'agencyName' => $agencyName
                        ];

                        $getCustomerSendOTPTemplate = EmailService::customerSendOTPTemplete($code, $customerMailData, $request->language_code);

                        if ($getCustomerSendOTPTemplate['status'] == 'false') {
                            return back()->with('error', $getCustomerSendOTPTemplate['error']);
                        } else {
                            $sendOTPSubject = $getCustomerSendOTPTemplate['data']['subject'];
                            $sendOTPMailData = $getCustomerSendOTPTemplate['data']['mailData'];
                            $customerEmail = $email;
                            $files = [];

                            // set data in sendEmail function
                            $data = EmailService::sendEmail($customerEmail, $sendOTPSubject, $sendOTPMailData, $files, $siteName);
                        }
                        if (!empty($otp_setting_data)) {
                            if ($otp_setting_data[0]['value'] == "on") {
                                $otp_verification = true;
                                //send OTP SMS code

                                $this->sendEmail($email, $sendOTPSubject, $sendOTPMailData);
                            }
                        }

                        AdminUserOtp::updateOrCreate($emailAddress, $temp);

                        $success['otp'] = $otp;
                        $success['otp_verify'] = $otp_verification;
                        $response = array($success);
                        if ($request['type'] == 'mobile') {
                            return $this->sendResponse($response, "OTP send successfully to your registered Mobile Number");
                        } else {
                            return $this->sendResponse($response, "OTP send successfully to your registered Email Address");
                        }
                    }
                } else if ($getCustomerDetails->status == 'deleted') {
                    return $this->sendError('Customer not Found', [], 200);
                } else {
                    return $this->sendError('Customer is Inactive or terminated', [], 200);
                }
            } else {
                $success = [];
                return $this->sendError('Customer not found', $success, 200);
            }


            // if (!empty($user)) {
            //     return $this->sendResponse([], 'User Find Successfully, please check otp send api');
            // } else {
            //     $success = [];
            //     return $this->sendError('User Not Found.', $success, 200);
            // }
        } catch (Exception $e) {
            $success = [];
            return $this->sendError($success, 'Something went wrong', ['error' => $e], 500);
        }
    }

    /**
     * @OA\Post(
     *   path="/v1/customer/verification-otp/verify",
     *   tags={"Customer"},
     *   summary="OTP Verify Using Mobile or Email",
     *   description="pass type either mobile or email<be>pass ISD Code Ex: +358, it's required when type value is mobile<br>Pass mobile_or_email value Ex: 8989898989 or example@gmail.com<br>enter otp what ever you got on mobile or email",
     *   operationId="OtpVerify",
     *   @OA\RequestBody(
     *     required=true,
     *     description="The request body for otp verification", 
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(
     *             required={"language_code","type","isd_code","mobile_or_email","otp"},
     *           @OA\Property(property="language_code",default="en", type="string"),
     *             @OA\Property(property="type", type="string", title="type",description="enter type either mobile or email"),
     *             @OA\Property(property="isd_code", type="string", title="isd_code",description="enter exisiting isd code"),
     *             @OA\Property(property="mobile_or_email", type="string", title="mobile",description="enter exisiting mobile or email number on which you got otp"),
     *             @OA\Property(property="otp", type="string",title="otp", description="enter a otp which you got on registered mobile number or email address"),
     *      )
     *    ),
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
    public function OtpVerify(Request $request)
    {


        try {
            //check validation for either mobile or email using extends method
            Validator::extend('check_type', function ($attribute, $value, $parameters, $validator) {
                $condition = $parameters[0]; // Get the condition field name
                $conditionValue = $validator->getData()[$condition]; // Get the value of the condition field

                if ($conditionValue === 'mobile') {
                    // Check if the value is a valid mobile number
                    return preg_match('/^[0-9]+$/', $value);
                } elseif ($conditionValue === 'email') {
                    // Check if the value is a valid email address
                    return filter_var($value, FILTER_VALIDATE_EMAIL);
                }

                return false; // Invalid condition value
            }, 'The customer :conditionValue is in-valid');
            Validator::replacer('check_type', function ($message, $attribute, $rule, $parameters) use ($request) {
                $signupWith = $request->input('type'); // Access signup_with from the request
                return str_replace(':conditionValue', $signupWith, $message);
            });


            //check mobile length must be as per mentioned in admin panel country module
            Validator::extend('check_mobile_length', function ($attribute, $value, $parameters, $validator) {
                $condition = $parameters[0]; // Get the condition field name
                $conditionValue = $validator->getData()[$condition];
                $conditionForISD = $parameters[1]; // Get the condition field name
                $conditionISDValue = $validator->getData()[$conditionForISD];

                if ($conditionValue == 'mobile') {
                    // Check if the value is a valid mobile number
                    $query = Country::where('isd_code', $conditionISDValue)->first();
                    if (!empty($query)) {

                        $mobileLength = $query['max_mobile_number_length'];
                        return (strlen($value) == $mobileLength);
                    } else {
                        return true;
                    }
                } else {
                    return true;
                }

                return false; // Invalid condition value
            }, 'The customer mobile number must be contain :mobileLength digits');

            //replacer validation message for mobile number length
            Validator::replacer('check_mobile_length', function ($message, $attribute, $rule, $parameters) use ($request) {
                $isdCode = $request->input('isd_code'); // Access signup_with from the request
                $query = Country::where('isd_code', $isdCode)->first();
                return str_replace(':mobileLength', $query['max_mobile_number_length'], $message);
            });


            //isd code validation
            Validator::extend('valid_isd_code', function ($attribute, $value, $parameters, $validator) {
                // Implement the validation logic for ISD code
                return preg_match('/^\+\d+([- ]\d+)?$/', $value);
            }, "The :attribute must start with + followed by numbers, and may be followed by - or space and more numbers.");

            $validator = Validator::make($request->all(), [
                'language_code' => 'required|in:ar,en',
                'type' => 'required|in:email,mobile',
                'isd_code' => 'nullable|required_if:type,mobile|' . ($request->input('type') == 'mobile' ? 'valid_isd_code' : ''),
                'mobile_or_email' => 'required|check_type:type|check_mobile_length:type,isd_code',
                'otp' => 'required|numeric',

            ], [
                'type.in' => 'entered type must be either email or mobile',

            ]);
            $validator->sometimes('isd_code', 'valid_isd_code', function ($input) {
                return $input->type == 'mobile';
            });

            if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => $validator->errors()->first(), 'data' => [$validator->errors()]], 422);
            }
            if ($request['type'] != 'email') {

                $checker = Customer::select('*', 'id as user_id')->where('mobile', '=', $request->isd_code . ' ' . $request->mobile_or_email)->where('status', 'active')->where('status', '!=', 'deleted')->first();
            } else {
                $checker = Customer::select('*', 'id as user_id')->where('email', '=',  $request->mobile_or_email)->where('status', 'active')->where('status', '!=', 'deleted')->first();
            }
            if (!empty($checker)) {
                if ($request['type'] != 'email') {

                    $userOtp = AdminUserOtp::where('mobile', '=', $request->isd_code . ' ' . $request->mobile_or_email)
                        ->where('otp', '=', $request->otp)
                        ->first();
                } else {
                    $userOtp = AdminUserOtp::Where('mobile', $request->mobile_or_email)
                        ->where('otp', '=', $request->otp)
                        ->first();
                }

                if (!empty($userOtp)) {

                    $exipreDate = $userOtp['expired'];
                    $currentDate = date('Y-m-d H:i:s');

                    if (strtotime($currentDate) < strtotime($exipreDate)) {

                        AdminUserOtp::where('mobile', '=', $request->isd_code . ' ' . $request->mobile_or_email)->orWhere('mobile', $request->mobile_or_email)
                            ->where('otp', '=', $request->otp)
                            ->delete();


                        Auth::guard('appuser')->loginUsingId($checker->id);
                        $userData = Auth::guard('appuser')->user();
                        $success = $checker;
                        $success['token'] =  $userData->createToken('AuthToken')->accessToken;
                        $updateCustomer = Customer::query();
                        if ($request['type'] != 'email') {

                            $updateCustomer->where('mobile', $request->isd_code . ' ' . $request->mobile_or_email)->update([
                                'is_mobile_verified' => '1'
                            ]);
                        } else {

                            $updateCustomer->where('email',  $request->mobile_or_email)->update([
                                'is_email_verified' => '1'
                            ]);
                        }
                        return $this->sendResponse($success, 'OTP Verified Successfully');
                    } else {

                        $success = [];
                        return $this->sendError('OTP expired!', $success, 200);
                    }
                } else {
                    $success = [];
                    return $this->sendError($request['type'] . ' and otp does not match', $success, 200);
                }
            } else {
                $success = [];
                return $this->sendError('Customer not found', $success, 200);
            }
        } catch (Exception $e) {
            $success = [];
            return $this->sendError($success, 'Something went wrong', ['error' => $e], 500);
        }
    }


    /**
     * @OA\Post(
     *   path="/v1/customer/resend-activation-mail",
     *   security={
     *     {"bearerAuth": {}}
     *   },
     *   tags={"Customer"},
     *   summary="Resend activation mail to customer",
     *   description="send activation mail to customer<br>Pass language code value either en or ar",
     *   operationId="resend-mail",
     *   @OA\RequestBody(
     *     required=true,
     *     description="The request body for resend activation mail", 
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(
     *             required={"language_code"},
     *           @OA\Property(property="language_code",default="en", type="string"),
     *      )
     *    ),
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
    public function customerResendMail(Request $request)
    {
        try {

            if (AUth::user()) {
                $validator = Validator::make($request->all(), [
                    'language_code' => 'required|in:ar,en',
                ]);
                if ($validator->fails()) {
                    return response()->json(['status' => false, 'message' => 'Invalid request', 'data' => [$validator->errors()]], 422);
                }
                $customer = Customer::where('id', Auth::id())->first();

                $isMail = Setting::select('value')->where('config_key', '=', 'mail|smtp|server')->first();
                if (isset($isMail) && $isMail->value == '0') {
                    return $this->sendResponse([$customer], 'Unable to send mail now, please check your SMTP setting');
                } else {
                    if ($customer['email'] != "") {

                        $token = Str::random(60);
                        $updateCustomerToken = DB::table('customer_activation_tokens')
                            ->where(['email' => $customer['email']])
                            ->first();
                        if (!$updateCustomerToken) {
                            \DB::table('customer_activation_tokens')->insert(
                                ['email' => $customer['email'], 'token' => $token, 'created_at' => Carbon::now()]
                            );
                        } else {
                            DB::table('customer_activation_tokens')->where(['email' => $customer['email']])->update(
                                ['token' => $updateCustomerToken->token]
                            );
                            $token = $updateCustomerToken->token;
                        }
                        $code = 'CUSTOMER_SIGN_UP';
                        if ($customer['agency_id'] != 0) {
                            $agencyLogo = Agency::where('id', $customer['agency_id'])->value('logo');
                            $agencyName = Agency::where('id', $customer['agency_id'])->value('full_name');
                        } else {
                            $agencyLogo = Setting::where('config_key', 'general|basic|colorLogo')->get('value')[0]['value'] ?? Setting::where('config_key', 'general|basic|colorLogo')->get('value')[0]['value'];
                            $agencyName = count(Setting::where('config_key', 'general|basic|siteName')->get('value')) > 0 ? Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'] : "Rehlte";
                        }



                        $b2cUrl  = Setting::select('value')->where('config_key', 'general|b2cUrl')->first();
                        $customerEmail = ucwords($customer['email']);

                        $link = $b2cUrl->value . 'email-verification/' . $token;

                        $data = array(
                            'first_name' => "roshan",
                            'site_name' => $agencyName,
                            'agency_name' => $agencyName,
                            'agency_logo' => $agencyLogo,
                            'email' => $customerEmail,
                            'activation_link' => $link
                        );


                        $getCustomerSignUp = $this->customerSignUp($code, $data, $request->language_code);

                        $mailData = $getCustomerSignUp['data']['mailData'];
                        $subject = $getCustomerSignUp['data']['subject'];
                        $mailData = $getCustomerSignUp['data']['mailData'];
                        $toEmail = $customer['email'];
                        $files = [];

                        // set data in sendEmail function
                        $data = $this->sendEmail($toEmail, $subject, $mailData, $files, $agencyName, $code);
                    }
                    return $this->sendResponse([$customer], 'Varification mail send successfully');
                }
            } else {
                $success = [];
                return $this->sendError('Customer not found', $success, 200);
            }
        } catch (Exception $e) {
            $success = [];
            return $this->sendError($success, 'Something went wrong', ['error' => $e], 500);
        }
    }
    /**
     * @OA\Post(
     *   path="/v1/customer/forgot-password-otp/send",
     *   tags={"Customer"},
     *   summary="Send request for forgot password",
     *   description="Pass type value either mobile or email<br>if type mobile then isd_code required else not<br>Pass an Mobile Number and check mobile is exists or not<br>Or<br>
                    Pass email address to get otp<br>Pass language code either ar for arabic  or en for english",
     *   operationId="forgot-password",
     *   @OA\Parameter(
     *      name="body",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           collectionFormat="multi",
      required={"type","isd_code","mobile_or_email","language_code"},
     *           @OA\Property(property="type", type="string",description="pass 'mobile' or 'email'"),
     *           @OA\Property(property="isd_code", type="string",description="pass isd code if you want to get otp on mobile"),
     *           @OA\Property(property="mobile_or_email", type="string"),
     *           @OA\Property(property="language_code",default="en", type="string"),
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
     * Check Users Exists api
     *
     * @return \Illuminate\Http\Response
     */
    public function forgotPassword(Request $request)
    {
        try {
            //check validation for either mobile or email using extends method
            Validator::extend('check_type', function ($attribute, $value, $parameters, $validator) {
                $condition = $parameters[0]; // Get the condition field name
                $conditionValue = $validator->getData()[$condition]; // Get the value of the condition field
                
                if ($conditionValue === 'mobile') {
                    // Check if the value is a valid mobile number
                    return preg_match('/^[0-9]+$/', $value);
                } elseif ($conditionValue === 'email') {
                    // Check if the value is a valid email address
                    return filter_var($value, FILTER_VALIDATE_EMAIL);
                }
                
                return false; // Invalid condition value
            }, 'The customer :conditionValue is in-valid');
            Validator::replacer('check_type', function ($message, $attribute, $rule, $parameters) use ($request) {
                $signupWith = $request->input('type'); // Access signup_with from the request
                return str_replace(':conditionValue', $signupWith, $message);
            });
            
            
            //check mobile length must be as per mentioned in admin panel country module
            Validator::extend('check_mobile_length', function ($attribute, $value, $parameters, $validator) {
                $condition = $parameters[0]; // Get the condition field name
                $conditionValue = $validator->getData()[$condition];
                $conditionForISD = $parameters[1]; // Get the condition field name
                $conditionISDValue = $validator->getData()[$conditionForISD];
                
                if ($conditionValue == 'mobile') {
                    // Check if the value is a valid mobile number
                    $query = Country::where('isd_code', $conditionISDValue)->first();
                    if (!empty($query)) {
                        
                        $mobileLength = $query['max_mobile_number_length'];
                        return (strlen($value) == $mobileLength);
                    } else {
                        return true;
                    }
                } else {
                    return true;
                }

                return false; // Invalid condition value
            }, 'The customer mobile number must be contain :mobileLength digits');
            
            //replacer validation message for mobile number length
            Validator::replacer('check_mobile_length', function ($message, $attribute, $rule, $parameters) use ($request) {
                $isdCode = $request->input('isd_code'); // Access signup_with from the request
                $query = Country::where('isd_code', $isdCode)->first();
                return str_replace(':mobileLength', $query['max_mobile_number_length'], $message);
            });

            
            //isd code validation
            Validator::extend('valid_isd_code', function ($attribute, $value, $parameters, $validator) {
                // Implement the validation logic for ISD code
                return preg_match('/^\+\d+([- ]\d+)?$/', $value);
            }, "The :attribute must start with + followed by numbers, and may be followed by - or space and more numbers.");

            $validator = Validator::make($request->all(), [
                'type' => 'required|in:email,mobile',
                'isd_code' =>
                'nullable|required_if:type,mobile|' . ($request->input('type') == 'mobile' ? 'valid_isd_code' : ''),
                'mobile_or_email' => 'required|check_type:type|check_mobile_length:type,isd_code',
                'language_code' => 'required|in:ar,en',
                
            ], [
                'type.in' => 'entered type must be either email or mobile',
                'language_code.in' => 'entered language code is wrong, it must be either ar or en'
            ]);
            $validator->sometimes('isd_code', 'valid_isd_code', function ($input) {
                return $input->type == 'mobile';
            });
            
            if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => $validator->errors()->first(), 'data' => [$validator->errors()]], 422);
            }
            if ($request['type'] != "email") {
                
                $getCustomerDetails = Customer::where('mobile', $request->isd_code . ' ' . $request->mobile_or_email)->where('status','active')->first();
            } else {
                
                $getCustomerDetails = Customer::where('email', $request->mobile_or_email)->where('status','active')->first();
            }
            
            
            if ($getCustomerDetails) {
                if ($getCustomerDetails['first_name'] != "" && $getCustomerDetails['last_name'] != "") {
                    $customerName = $getCustomerDetails['first_name'] . ' ' . $getCustomerDetails['last_name'];
                } else {
                    $customerName = 'Customer';
                }
                //get OTP verification setting key value 'general|otp|phoneVerification'
                $otp_verification = false;
                $otp_setting_data = \App\Models\Setting::where('config_key', 'general|otp|phoneVerification')->get()->toArray();
                
                if ($getCustomerDetails->status == 'active') {
                    if ($request['type'] != 'email') {
                        $otp = rand(100000, 999999);
                        $currentDate = date('Y-m-d H:i:s');
                        $datee = strtotime($currentDate);
                        $otp_expire_minute = 5;
                        $date1 = strtotime("+" . $otp_expire_minute . "minute", $datee);
                        $otp_expire = date('Y-m-d H:i:s', $date1);

                        $temp = array(
                            "otp" => $otp,
                            "mobile" => $request->isd_code . ' ' . $request->mobile_or_email,
                            "expired" => $otp_expire,
                        );
                        
                        $mobile_number = ['mobile' => $request->isd_code . ' ' . $request->mobile_or_email];
                        $message = "$otp is your one time password to proceed on Web Site. It is valid for $otp_expire_minute minutes";
                        
                        if (!empty($otp_setting_data)) {
                            if ($otp_setting_data[0]['value'] == "on") {
                                $otp_verification = true;
                                //send OTP SMS code
                                $mobile_number = $request->isd_code . $request->mobile_or_email;

                                $this->sendSms($mobile_number, $message);
                            }
                        }
                        
                        $data = AdminUserOtp::updateOrCreate($temp);
                        
                        $success['otp'] = $otp;
                        $success['otp_verify'] = $otp_verification;
                        $response = array($success);
                        
                        return $this->sendResponse($response, "OTP send successfully to your registered Mobile");
                    } else {

                        $email = $getCustomerDetails->email;
                        $otp = rand(100000, 999999);
                        
                        $currentDate = date('Y-m-d H:i:s');
                        $datee = strtotime($currentDate);
                        $otp_expire_minute = 5;
                        $date1 = strtotime("+" . $otp_expire_minute . "minute", $datee);
                        $otp_expire = date('Y-m-d H:i:s', $date1);
                        $mobile_number = ['mobile' => $request->mobile_or_email];
                        $temp = array(
                            "otp" => $otp,
                            "mobile" => $request->mobile_or_email,
                            "expired" => $otp_expire,
                        );
                        AdminUserOtp::updateOrCreate($mobile_number, $temp);
                        $emailAddress = ['mobile' => $request->mobile_or_email];
                        $code = 'SEND_OTP';
                        $siteName = count(Setting::where('config_key', 'general|basic|siteName')->get('value')) > 0 ? Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'] : "Amar Infotech";
                        $otp = $otp;
                        $otp_expire_minute = $otp_expire_minute;
                        $agencyLogo = Setting::where('config_key', 'general|basic|colorLogo')->get('value')[0]['value'] ?? Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'];
                        $agencyName = Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'];
                        $customerMailData = [
                            'customer_name' => $customerName,
                            'agencyLogo' => $agencyLogo,
                            'agencyName' => $agencyName,
                            'otp' => $otp,
                            'otp_expire_minute' => $otp_expire_minute,
                            'site_name' => $siteName
                        ];

                        $getCustomerSendOTPTemplate = EmailService::customerSendOTPTemplete($code, $customerMailData, $request->language_code);
                        if ($getCustomerSendOTPTemplate['status'] == 'false') {
                            return back()->with('error', $getCustomerSendOTPTemplate['error']);
                        } else {
                            $sendOTPSubject = $getCustomerSendOTPTemplate['data']['subject'];
                            $sendOTPMailData = $getCustomerSendOTPTemplate['data']['mailData'];
                            $customerEmail = $email;
                            $files = [];

                            // set data in sendEmail function
                            $data = EmailService::sendEmail($customerEmail, $sendOTPSubject, $sendOTPMailData, $files, $siteName);
                            $success['otp'] = $otp;
                            $response = array($success);
                        }

                        if ($request['type'] != 'email') {
                            return $this->sendResponse($response, "OTP send successfully to your registered Mobile Number");
                        } else {
                           
                            return $this->sendResponse($response, "OTP send successfully to your registered Email Address");
                        }
                    }
                } else if ($getCustomerDetails->status == 'deleted') {
                    return $this->sendError('Customer not Found', [], 200);
                } else {
                    return $this->sendError('Customer is Inactive or terminated', [], 200);
                }
            } else {
                $success = [];
                return $this->sendError('Customer not found', $success, 200);
            }

        } catch (Exception $e) {
            $success = [];
            return $this->sendError($success, 'Something went wrong', ['error' => $e], 500);
        }
    }
    /**
     * @OA\Post(
     *   path="/v1/customer/forgot-password-otp/verify",
     *   tags={"Customer"},
     *   summary="OTP Verify Using Mobile or Email",
     *   description="pass type either mobile or email<be>pass ISD Code Ex: +358, it's required when type value is mobile<br>Pass mobile_or_email value Ex: 8989898989 or example@gmail.com<br>enter otp what ever you got on mobile or email",
     *   operationId="ForgotPasswordOtpVerify",
     *   @OA\RequestBody(
     *     required=true,
     *     description="The request body for otp verification", 
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(
     *             required={"language_code","type","isd_code","mobile_or_email","otp"},
     *             @OA\Property(property="language_code", type="string",default="en"),
     *             @OA\Property(property="type", type="string", title="type",description="enter type either mobile or email"),
     *             @OA\Property(property="isd_code", type="string", title="isd_code",description="enter exisiting isd code"),
     *             @OA\Property(property="mobile_or_email", type="string", title="mobile",description="enter exisiting mobile or email number on which you got otp"),
     *             @OA\Property(property="otp", type="string",title="otp", description="enter a otp which you got on registered mobile number or email address"),
     *      )
     *    ),
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
    public function forgotPasswordOtpVerify(Request $request)
    {

        try {
          
            //check validation for either mobile or email using extends method
            Validator::extend('check_type', function ($attribute, $value, $parameters, $validator) {
                $condition = $parameters[0]; // Get the condition field name
                $conditionValue = $validator->getData()[$condition]; // Get the value of the condition field

                if ($conditionValue === 'mobile') {
                    // Check if the value is a valid mobile number
                    return preg_match('/^[0-9]+$/', $value);
                } elseif ($conditionValue === 'email') {
                    // Check if the value is a valid email address
                    return filter_var($value, FILTER_VALIDATE_EMAIL);
                }

                return false; // Invalid condition value
            }, 'The customer :conditionValue is in-valid');
            Validator::replacer('check_type', function ($message, $attribute, $rule, $parameters) use ($request) {
                $signupWith = $request->input('type'); // Access signup_with from the request
                return str_replace(':conditionValue', $signupWith, $message);
            });


            //check mobile length must be as per mentioned in admin panel country module
            Validator::extend('check_mobile_length', function ($attribute, $value, $parameters, $validator) {
                $condition = $parameters[0]; // Get the condition field name
                $conditionValue = $validator->getData()[$condition];
                $conditionForISD = $parameters[1]; // Get the condition field name
                $conditionISDValue = $validator->getData()[$conditionForISD];

                if ($conditionValue == 'mobile') {
                    // Check if the value is a valid mobile number
                    $query = Country::where('isd_code', $conditionISDValue)->first();
                    if (!empty($query)) {

                        $mobileLength = $query['max_mobile_number_length'];
                        return (strlen($value) == $mobileLength);
                    } else {
                        return true;
                    }
                } else {
                    return true;
                }

                return false; // Invalid condition value
            }, 'The customer mobile number must be contain :mobileLength digits');

            //replacer validation message for mobile number length
            Validator::replacer('check_mobile_length', function ($message, $attribute, $rule, $parameters) use ($request) {
                $isdCode = $request->input('isd_code'); // Access signup_with from the request
                $query = Country::where('isd_code', $isdCode)->first();
                return str_replace(':mobileLength', $query['max_mobile_number_length'], $message);
            });


            //isd code validation
            Validator::extend('valid_isd_code', function ($attribute, $value, $parameters, $validator) {
                // Implement the validation logic for ISD code
                return preg_match('/^\+\d+([- ]\d+)?$/', $value);
            }, "The :attribute must start with + followed by numbers, and may be followed by - or space and more numbers.");

            $validator = Validator::make($request->all(), [
                'language_code' => 'required|in:ar,en',
                'type' => 'required|in:email,mobile',
                'isd_code' => 'nullable|required_if:type,mobile|' . ($request->input('type') == 'mobile' ? 'valid_isd_code' : ''),
                'mobile_or_email' => 'required|check_type:type|check_mobile_length:type,isd_code',
                'otp' => 'required|numeric',

            ], [
                'type.in' => 'entered type must be either email or mobile',

            ]);
            $validator->sometimes('isd_code', 'valid_isd_code', function ($input) {
                return $input->type == 'mobile';
            });

            if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => $validator->errors()->first(), 'data' => [$validator->errors()]], 422);
            }
            if ($request['type'] != 'email') {

                $checker = Customer::select('*', 'id as user_id')->where('mobile', '=', $request->isd_code . ' ' . $request->mobile_or_email)->where('status', 'active')->where('status', '!=', 'deleted')->first();
            } else {
                $checker = Customer::select('*', 'id as user_id')->where('email', '=',  $request->mobile_or_email)->where('status', 'active')->where('status', '!=', 'deleted')->first();
            }
            if (!empty($checker)) {
                if ($request['type'] != 'email') {
                    $mobile = $request->isd_code.' '.$request->mobile_or_email;
                    $userOtp = AdminUserOtp::where('mobile', '=', $mobile)
                        ->where('otp', '=', $request->otp)
                        ->first();
                } else {
                    $userOtp = AdminUserOtp::Where('mobile', $request->mobile_or_email)
                        ->where('otp', '=', $request->otp)
                        ->first();
                }

                if (!empty($userOtp)) {

                    $exipreDate = $userOtp['expired'];
                    $currentDate = date('Y-m-d H:i:s');

                    if (strtotime($currentDate) < strtotime($exipreDate)) {

                        AdminUserOtp::where('mobile', '=', $request->isd_code . ' ' . $request->mobile_or_email)->orWhere('mobile', $request->mobile_or_email)
                            ->where('otp', '=', $request->otp)
                            ->delete();


                        Auth::guard('appuser')->loginUsingId($checker->id);
                        $userData = Auth::guard('appuser')->user();
                        $success = $checker;
                        $success['token'] =  $userData->createToken('AuthToken')->accessToken;
                        // $success['token'] = Str::random(60);


                        return $this->sendResponse($success, 'OTP Verified Successfully');
                    } else {

                        $success = [];
                        return $this->sendError('OTP expired!', $success, 200);
                    }
                } else {
                    $success = [];
                    return $this->sendError($request['type'] . ' and otp does not match', $success, 200);
                }
            } else {
                $success = [];
                return $this->sendError('Customer not found', $success, 200);
            }
        } catch (Exception $e) {
            $success = [];
            return $this->sendError($success, 'Something went wrong', ['error' => $e], 500);
        }
    }
    /**
     * @OA\Post(
     *   path="/v1/customer/reset-password",
     *   security={
     *     {"bearerAuth": {}}
     *   },
     *   tags={"Customer"},
     *   summary="Send request for reset password",
     *   description="Pass language code either ar for arabic  or en for english<br>Enter new password and confirm password both are must be same",
     *   operationId="reset-password",
     *   @OA\Parameter(
     *      name="body",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           collectionFormat="multi",
                required={"language_code","new_password","confirm_password"},
     *           @OA\Property(property="language_code", type="string",default="en"),
     
     *           @OA\Property(property="new_password", type="string"),
     *           @OA\Property(property="confirm_password", type="string"),
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
     * Check Users Exists api
     *
     * @return \Illuminate\Http\Response
     */
    public function resetForgotPassword(Request $request)
    {
        try {
            if (Auth::user()) {
                $customerDetails = Customer::where('id', Auth::id())->first();


                $validator = Validator::make($request->all(), [
                    'language_code' => 'required|in:ar,en',

                    'new_password' => 'required',
                    'confirm_password' => 'required|same:new_password'

                ], [
                    'language_code.in' => 'entered language code is wrong, it must be either ar or en'
                ]);


                if ($validator->fails()) {
                    return response()->json(['success' => false, 'message' => $validator->errors()->first(), 'data' => [$validator->errors()]], 422);
                }

                $data['key'] = 'password';
                $data['password'] = $request->new_password;
                $responseData = resetPassword($data);

                if ($responseData['valid'] != '1') {
                    return response()->json(['success' => false, 'message' => $responseData, 'data' => $responseData], 422);
                }
                if ($customerDetails['email'] != "") {
                    $updatePassword = Customer::where('email', $customerDetails['email'])->update([
                        'password' => Hash::make($request->new_password)
                    ]);
                } else {
                    $updatePassword = Customer::where('mobile', $customerDetails['mobile'])->update([
                        'password' => Hash::make($request->new_password)
                    ]);
                }
                $success = [];
                return $this->sendResponse($updatePassword, 'Password Reset Successfully');
            }
        } catch (Exception $e) {
            $success = [];
            return $this->sendError($success, 'Something went wrong', ['error' => $e], 500);
        }
    }

    /**
     * @OA\Post(
     *   path="/v1/customer/change-password",
     *   security={
     *     {"bearerAuth": {}}
     *   },
     *   tags={"Customer"},
     *   summary="Change Password",
     *   description="end request for Customer Change Password",
     *   operationId="changePassword",
     *  @OA\RequestBody(
     *     required=true,
     *     description="Customer Change Password", 
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(
     *             required={"language_code","old_password","new_password","confirm_password"},
     *           @OA\Property(property="language_code", default="en",type="string",description="need to pass language code either 'en' or 'ar'"),
     *           @OA\Property(property="old_password", type="string",description="need to pass Old Password"),
     *           @OA\Property(property="new_password", type="string",description="need to pass New Password"),
     *           @OA\Property(property="confirm_password", type="string",description="need to pass Confirm Password")
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

            Validator::extend('custom_rule_password', function ($attribute, $value) {
                $query = Customer::where('password', $value)
                    ->where('status', 'active')->where('id', Auth::id())->get();
                return !$query->count();
            }, 'The old password has already been taken');

            $validator = Validator::make($request->all(), [
                'language_code' => 'required|in:ar,en',
                'old_password' =>  [
                    'required',
                    function ($attribute, $value, $fail) {
                        // Check if the provided old password matches the authenticated user's current password
                        if (!\Hash::check($value, auth()->user()->password)) {
                            $fail("The $attribute is incorrect.");
                        }
                    },
                ],
                'new_password' => 'required',
                'confirm_password' => 'required|same:new_password',
            ]);

            $data['key'] = 'password';
            $data['password'] = $request->new_password;
            $responseData = resetPassword($data);

            if ($responseData['valid'] != '1') {
                return response()->json(['success' => false, 'message' => $responseData, 'data' => $responseData], 422);
            }
            if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => $validator->errors()->first(), 'data' => [$validator->errors()]], 422);
            }
            $requestData = $request->all();
            if (Auth::user()) {
                $data = [
                    'password' => Hash::make($requestData['new_password']),
                ];
                $response = Customer::where('id', Auth::id())->update($data);
                if (!empty($response)) {
                    $checkNotifyEnable = Setting::where('config_key', 'passwordSecurity|changePasswordNotify')->get('value')[0]['value'];
                    if ($checkNotifyEnable == '1') {

                        $customerDetail = Customer::where('id', Auth::id())->get()->toArray();

                        $agencyName = Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'];
                        $agencyLogo = Setting::where('config_key', 'general|basic|colorLogo')->get('value')[0]['value'] ?? Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'];
                        $language_code = $requestData['language_code'];

                        $code = 'CHANGE_PASSWORD';
                        $siteName = count(Setting::where('config_key', 'general|basic|siteName')->get('value')) > 0 ? Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'] : "Amar Infotech";
                        $customerName = ucwords($customerDetail[0]['first_name'] . ' ' . $customerDetail[0]['last_name']);

                        $data = array(
                            'customer_name' => $customerName,
                            'site_name' => $siteName,
                            'agency_name' => $agencyName,
                            'agency_logo' => $agencyLogo
                        );

                        $getTemplateData = $this->changePasswordMailTemplate($code, $data, $language_code);

                        if ($getTemplateData['status'] == 'false') {
                            return back()->with('error', $getTemplateData['error']);
                        } else { 
                            $subject = $getTemplateData['data']['subject'];
                            $mailData = $getTemplateData['data']['mailData'];
                            $toEmail = $customerDetail[0]['email'];
                            $files = [];

                            // set data in sendEmail function
                            $data = $this->sendEmail($toEmail, $subject, $mailData, $files, $siteName, $code);
                            $otp_verification = false;
                            $otp_setting_data = \App\Models\Setting::where('config_key', 'general|otp|phoneVerification')->get()->toArray();

                            $currentDate = date('Y-m-d H:i:s');
                            $message = "Dear " . $customerName . ",Your " . $agencyName . " Account Password was Changed on" . $currentDate .
                                "If you did this, you can safely disregard this sms. If you did not do this, please secure your account or Contact Us.Thanks," . $agencyName . " Team!! ";
                            if (!empty($otp_setting_data)) {
                                if ($otp_setting_data[0]['value'] == "on") {
                                    $otp_verification = true;
                                    //send OTP SMS code
                                    $this->sendSms($customerDetail[0]['mobile'], $message);
                                }
                            }
                        }
                    }
                    return $this->sendResponse($success = [], 'Your Password changed Successfully');
                } else {
                    $success = [];
                    return $this->sendError('User not found', $success, 200);
                }
            }
        } catch (Exception $e) {
            $success = [];
            return $this->sendError($success, 'Something went wrong', ['error' => $e], 500);
        }
    }
    /**
     * @OA\Post(
     *   path="/v1/customer/email-verify",
     *  security={
     *     {"bearerAuth": {}}
     *   },
     *   tags={"Customer"},
     *   summary="Verify customer email",
     *   description="Initiates the email verification process for a customer.</br>",
     *   operationId="emailVerify",
     *   @OA\RequestBody(
     *     required=true,
     *     description="The request body for email verification", 
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(
     *             required={"email","token"},
     *             @OA\Property(property="email", type="string", example="test@gmail.com", description="The user's email address."),
     *             @OA\Property(property="token", type="string", example="abcdef123456", description="The verification token for email confirmation."),
     *      )
     *    ),
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
     * Login Send
     *
     * @return \Illuminate\Http\Response
     */
    public function emailVerify(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'token' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => $validator->errors()->first(), 'data' => [$validator->errors()]], 422);
            }

            $checkEmailAlreadyIsVerify = Customer::select('email', 'is_email_verified')->where('email', $request->email)->first();
            if ($checkEmailAlreadyIsVerify) {
                if ($checkEmailAlreadyIsVerify->is_email_verified == 0) {
                    $checkEmailORTokenIsNotExpired = DB::table('customer_activation_tokens')
                        ->where('email', $request->email)
                        ->where('token', $request->token)
                        ->first();

                    if ($checkEmailORTokenIsNotExpired) {

                        $currentTimestamp = Carbon::now()->timestamp;
                        $expiryTimestamp = Carbon::parse($checkEmailORTokenIsNotExpired->created_at)->addHours(24)->timestamp;

                        if ($currentTimestamp <= $expiryTimestamp) {

                            $updateEmailIsVerify = Customer::where(['email' => $checkEmailORTokenIsNotExpired->email])->update(
                                ['is_email_verified' => true]
                            );

                            $deleteRecords = DB::table('customer_activation_tokens')->where(['email' => $checkEmailORTokenIsNotExpired->email])
                                ->delete();

                            $success = [];
                            return $this->sendResponse($success, 'Email Is Verified Successfully');
                        } else {
                            $success = [];
                            return $this->sendResponse($success, 'Token has expired');
                        }
                    } else {
                        $success = [];
                        return $this->sendError('Invalid email or token', $success, 200);
                    }
                } else {
                    $success = [];
                    return $this->sendError('Email Is Already Verified!', $success, 200);
                }
            } else {
                $success = [];
                return $this->sendError('Invalid Email', $success, 200);
            }
        } catch (Exception $e) {
            $success = [];
            return $this->sendError($success, 'Something went wrong', ['error' => $e], 500);
        }
    }


    /**
     * @OA\Post(
     *   path="/v1/customer/close-account",
     *   security={
     *     {"bearerAuth": {}}
     *   },
     *   tags={"Customer"},
     *   summary="Close Account",
     *   description="",
     *   operationId="closeAccount",
     *   @OA\Parameter(
     *      name="body",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           collectionFormat="multi",
     *            required={"language_code","deleted_reason"},  
     *            @OA\Property(property="language_code", type="string", default="en" ),
     *            @OA\Property(property="deleted_reason", type="string",  ),
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
     * Close Account
     *
     * @return \Illuminate\Http\Response
     */
    public function closeAccount(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'language_code' => 'required|in:ar,en',
                'deleted_reason' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => $validator->errors()->first(), 'data' => [$validator->errors()]], 422);
            }
            Customer::where('id', Auth::user()->id)->update([
                'deleted_reason' => $request->deleted_reason,
                'status' => 'deleted',
                'is_email_verified' => '0',
                'is_mobile_verified' => '0',
            ]);
            // $this->logout();
            if (Auth::guard('appuser-api')->check()) {
                $auth = "appuser-api";
            }

            if ($auth) {
                Auth::guard($auth)->user()->token()->revoke();
            }
            $success = [];
            return $this->sendResponse($success, 'User Account Deleted Successfully.');
        } catch (Exception $e) {
            $success = [];
            return $this->sendError($success, 'Something went wrong', ['error' => $e], 500);
        }
    }


    /**
     * @OA\Post(
     *   path="/v1/customer/logout-customer",
     *   security={
     *     {"bearerAuth": {}}
     *   },
     *   tags={"Customer"},
     *   summary="Logout",
     *   description="logout",
     *   operationId="logoutCustomer",
     *   @OA\RequestBody(
     *     required=false,
     *     description="Logout", 
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(
     *             @OA\Property(property="device_id", type="string", example="mobileorwebsitedeviceid"),
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
     * Logout
     *
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {

        $auth = null;

        $user_id = Auth::user()->id;

        $requestData = $request->all();

        if (isset($requestData['device_id']) && $requestData['device_id'] != '') {
            UserLoginHistory::where('user_id', $user_id)
                ->where('device_id', $requestData['device_id'])
                ->delete();
        }

        if (Auth::guard('appuser-api')->check()) {
            $auth = "appuser-api";
        }

        if ($auth) {
            Auth::guard($auth)->user()->token()->revoke();
        }

        $success = [];
        return $this->sendResponse($success, 'User Logout Successfully');
    }
}
