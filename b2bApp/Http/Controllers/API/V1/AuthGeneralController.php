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
use DateTime;


class AuthGeneralController extends BaseController
{

    use EmailService;

    /**
     * @OA\Post(
     *   path="/v1/login",
     *   tags={"Authentication"},
     *   summary="Users Login",
     *   description="Pass Email Address Ex:example@gmail.com </br>",
     *   operationId="login",
     *   @OA\RequestBody(
     *     required=true,
     *     description="Login Details", 
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", title="email", description="enter existing email address"),
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
            // set custom rule for email validation
            Validator::extend('email_rule', function ($attribute, $value) {
                return preg_match('/(.+)@(.+)\.(.+)/i', $value);
            }, 'Please enter valid Email Address.');
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|email_rule|exists:users,email',
                'password' => 'required'
            ],[
                'email.email' => 'Please enter valid Email Address.'
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => 'Invalid request', 'data' => [$validator->errors()]], 422);
            }

            // $user = Customer::where('mobile', '=', $request->mobile)->orWhere('email', $request->mobile)->where('status', '!=', 'deleted')->get()->first();
            $user = User::where('email', $request->email)->where('app_name', 'b2bapp')->get()->first();
            $agencyId = User::where('email', $request->email)->where('app_name', 'b2bapp')->value('agency_id');
            $agencyStatus = Agency::where('id', $agencyId)->value('status');
            // echo $agency;die;
            if ($agencyStatus == 'inactive') {
                $success = [];
                return $this->sendError('Agency is In-Active, please contact to administrator', $success, 200);
            }
            if ($agencyStatus == 'terminated') {
                $success = [];
                return $this->sendError('Agency not Found', $success, 200);
            }

            if (!empty($user)) {
                //check that current host if avaible for login user
                $curentDateTime = date("Y-m-d H:i:s");
                $loginAttemptsDetail = DB::table('user_login_attempts')->Where('host', $_SERVER['REMOTE_ADDR'])->where('next_login_available_at', '>', $curentDateTime)->latest('next_login_available_at')->get()->toArray();
                $lockoutTimePerUserOrHostType = Setting::where('config_key', 'loginAttempts|lockOutTimePeriodType')->get('value')[0]['value'];
                if (!empty($loginAttemptsDetail)) {
                    $datetime1 = new DateTime($curentDateTime);
                    $datetime2 = new DateTime($loginAttemptsDetail[0]->next_login_available_at);
                    $interval = $datetime1->diff($datetime2);
                    // echo "<pre>";print_r($interval);die;
                    $days = $interval->d;
                    $hours = $interval->h;
                    $minutes = $interval->i;
                    $secounds = $interval->s;
                    $success = [];
                    if ($lockoutTimePerUserOrHostType == 'minute') {
                        if ($minutes == '0') {

                            return $this->sendError('Your account has been locked, you can login after '.$secounds.' seconds', $success, 200);
                        } else {
                            return $this->sendError('Your account has been locked, you can login after ' . $minutes . ' minutes', $success, 200);
                        }
                    }
                    if ($lockoutTimePerUserOrHostType == 'hour') {
                        if ($hours != '0') {
                            return $this->sendError('Your account has been locked, you can login after ' . $hours . ' hour', $success, 200);
                        } else if ($minutes == '0') {

                            return $this->sendError('Your account has been locked, you can login after '.$secounds.' seconds', $success, 200);
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

                            return $this->sendError('Your account has been locked, you can login after '.$secounds.' seconds', $success, 200);
                        } else {
                            return $this->sendError('Your account has been locked, you can login after ' . $minutes . ' minutes', $success, 200);
                        }
                    }
                }
                if (!Hash::check($request->password, $user['password'])) {

                    $isEnableLoginAttempt = Setting::where('config_key', 'loginAttempts|enable')->get('value')[0]['value'];
                    if ($user != "" && $isEnableLoginAttempt == '1') {

                        $userMobile = User::where('email', $request->email)->value('mobile');

                        //function to check login attempt when credential are wrong 
                        $checkLoginAttempt = self::checkLoginAttempt($userMobile);
                        // echo $checkLoginAttempt;die;
                        $success = [];
                        // return redirect()->route('admin.login')->with('error', $checkLoginAttempt);
                        return $this->sendError($checkLoginAttempt, $success, 200);
                    } else {
                        $success = [];
                        return $this->sendError('Invalid Login Credentials', $success, 200);
                    }
                }
                else
                {
                    $checkAgencyStatus = Agency::where('id',$user['agency_id'])->value('status');
                    if($checkAgencyStatus == 'active')
                    {
                        //set route redirection for b2b
                        return redirect()->route('b2b.dashboard')->with('success', 'Successfully Login');
                    }
                    else
                    {
                        $isPrimaryUser = User::where('mobile',$userMobile)->where('agency_id','!=','0')->value('primary_user');
                        //send mail to user if agency status is inavtive or terminated
                        $agencyLogo = Setting::where('config_key', 'general|basic|colorLogo')->get('value')[0]['value'] ?? Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'];
                        $code = 'AGENCY_BLOCK';
                        $siteName = count(Setting::where('config_key', 'general|basic|siteName')->get('value')) > 0 ? Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'] : "Amar Infotech";
                        $agencyDetails = Agency::where('id',Auth::user()->agency_id)->get()->toArray();
                        // echo "<pre>";print_r($agencyDetails);die;
                        $data = array(
                            // 'agency_name' => ($isPrimaryUser == '1') ? $siteName : $agencyDetails[0]['full_name'],
                            'agency_name' => $agencyDetails[0]['full_name'],
                            'agency_logo' => $agencyDetails[0]['logo']
                        );

                        $getTemplateData = EmailService::agencyBlockMailTemplate($code, $data);
                        // echo "<pre>";print_r($getTemplateData);die;
                        if ($getTemplateData['status'] == 'false') {
                            return back()->with('error', $getTemplateData['error']);
                        } else {
                            $subject = $getTemplateData['data']['subject'];
                            $mailData = $getTemplateData['data']['mailData'];
                            $toEmail = Auth::user()->email;
                            $files = [];

                            // set data in sendEmail function
                            $data = EmailService::sendEmail($toEmail, $subject, $mailData, $files, $siteName);
                            if ($data['status'] == 'false') {
                                return back()->with('error', $data['error']);
                            } else {
                                
                                return view('admin/auth/login')->with('error', 'Your Agncy is terminated or inactive, please contact to administrator');
                            }
                        }
                        
                    }
                }
                // else
                // {
                //      $curentDateTime = date("Y-m-d H:i:s");

                // }

                if (Auth::guard('b2b-api')->loginUsingId($user->id)) {

                    $userData = Auth::guard('b2b-api')->user();
                    //                        auth()->guard($guard)->setUser($userData);
                    if ($user->status == '0') {
                        $success = [];
                        return $this->sendError('User is In-Active, please contact to administrator', $success, 200);
                    } else if ($user->status == '2') {
                        $success = [];
                        return $this->sendError('User not Found', $success, 200);
                    } else {
                        //                            $success['token'] =  $userData->createToken('AuthToken')->accessToken;
                        //                            $user->token=$success['token'];
                        $success = $user;
                        $success['token'] = $userData->createToken('b2bAuthToken')->accessToken;

                        return $this->sendResponse([$success], 'User Login Successfully');
                    }
                } else {
                    $success = [];
                    return $this->sendError('Error During Login', $success, 200);
                }
            } else {

                $success = [];
                return $this->sendError('User not found', $success, 200);
            }
        } catch (Exception $e) {
            $success = [];
            return $this->sendError($success, 'Something went wrong', ['error' => $e], 500);
        }
    }
    //check login attempts criteria based o login attempts preference
    function checkLoginAttempt($username)
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
        // date_default_timezone_set($getTimeZone);

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
        // else if($checkAttemptCount < $loginAttemptPerHost)
        // {   

        //     DB::table('user_login_attempts')->where('username',$username)->delete();
        // }

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
        // $hostAttemptTimeperiodCount = Db::table('user_login_attempts')->where('host',$loginDetails['host'])->where('attempt_at','<',$modifyLoginAttemptDate)->count();
        if ($attemptPerUser == $loginAttemptPerHost)
        // if($loginAttemptPerHost == $hostAttemptTimeperiodCount)
        {

            $lastRow = DB::table('user_login_attempts')->latest()->first();
            DB::table('user_login_attempts')->where('id', $lastRow->id)->update([
                'next_login_available_at' => $modifyNextAttemptDate
            ]);
            if ($isEnableNotification == '1') {
                $userData = User::where('mobile', $username)->orWhere('email', $username)->get()->toArray();

                if ($userData[0]['agency_id'] == '0') {
                    $agencyLogo = count(Setting::where('config_key', 'general|basic|colorLogo')->get('value')) > 0 ? Setting::where('config_key', 'general|basic|colorLogo')->get('value')[0]['value'] : Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'];
                    $agencyName = count(Setting::where('config_key', 'general|basic|siteName')->get('value')) > 0 ? Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'] : "Amar Infotech";
                } else {
                    if ($userData[0]['primary_user'] == '1') {
                        $agencyLogo = count(Setting::where('config_key', 'general|basic|colorLogo')->get('value')) > 0 ? Setting::where('config_key', 'general|basic|colorLogo')->get('value')[0]['value'] : Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'];
                        $agencyName = count(Setting::where('config_key', 'general|basic|siteName')->get('value')) > 0 ? Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'] : "Amar Infotech";
                    } else {
                        $agencyName = Agency::where('id', $userData[0]['agency_id'])->value('full_name');
                        $agencyLogo = Agency::where('id', $userData[0]['agency_id'])->value('logo');
                    }
                }
                $code = 'LOGIN_ATTEMPTS_EXCEED';
                $customerName = ucwords($userData[0]['name']);
                $data = array(
                    'customer_name' => $customerName,
                    // 'site_name' => $siteName,
                    'agency_name' => $agencyName,
                    'agency_logo' => $agencyLogo,
                    'hours' => $lockoutTimePerUserOrHost,
                    'duration' => ucwords($lockoutTimePerUserOrHostType)
                );

                $getTemplateData = $this->mailTemplateBlockAccount($code, $data);
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
                // echo "checkLogiaan";die;
                $userData = User::where('mobile', $username)->orWhere('email', $username)->get()->toArray();
                if ($userData[0]['agency_id'] == '0') {
                    $agencyLogo = count(Setting::where('config_key', 'general|basic|colorLogo')->get('value')) > 0 ? Setting::where('config_key', 'general|basic|colorLogo')->get('value')[0]['value'] : Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'];
                    $agencyName = count(Setting::where('config_key', 'general|basic|siteName')->get('value')) > 0 ? Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'] : "Amar Infotech";
                } else {
                    if ($userData[0]['primary_user'] == '1') {
                        $agencyLogo = count(Setting::where('config_key', 'general|basic|colorLogo')->get('value')) > 0 ? Setting::where('config_key', 'general|basic|colorLogo')->get('value')[0]['value'] : Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'];
                        $agencyName = count(Setting::where('config_key', 'general|basic|siteName')->get('value')) > 0 ? Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'] : "Amar Infotech";
                    } else {
                        $agencyName = Agency::where('id', $userData[0]['agency_id'])->value('full_name');
                        $agencyLogo = Agency::where('id', $userData[0]['agency_id'])->value('logo');
                    }
                }
                $code = 'LOGIN_ATTEMPTS_EXCEED';

                $customerName = ucwords($userData[0]['name']);

                $data = array(
                    'customer_name' => $customerName,
                    // 'site_name' => $siteName,
                    'agency_name' => $agencyName,
                    'agency_logo' => $agencyLogo,
                    'hours' => $lockoutTimePerUserOrHost,
                    'duration' => ucwords($lockoutTimePerUserOrHostType)


                );

                $getTemplateData = $this->mailTemplateBlockAccount($code, $data);
                if ($getTemplateData['status'] == 'false') {
                    return back()->with('error', $getTemplateData['error']);
                } else {
                    $subject = $getTemplateData['data']['subject'];
                    $mailData = $getTemplateData['data']['mailData'];
                    $toEmail = $userData[0]['email'];
                    $files = [];

                    // set data in sendEmail function
                    $data = $this->sendEmail($toEmail, $subject, $mailData, $files, $siteName, $code);
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
     *   path="/v1/check-user-exists",
     *   tags={"Authentication"},
     *   summary="check users if exists or not",
     *   description="Pass Mobile no Ex: 1111111111 without ISD code</br>Or</br>Pass Email Address Ex: example@gmail.com",
     *   operationId="check-user-exists",
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(
     *             required={"user_name"},
     *             @OA\Property(property="user_name", title="user name", type="string", description="pass mobile or email address to check either usre exist or not"),
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
     * Check Users Exists api
     *
     * @return \Illuminate\Http\Response
     */
    public function checkUserExists(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'user_name' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => 'Invalid request', 'data' => [$validator->errors()]], 403);
            }

            $user = User::where('mobile', '=', $request->user_name)->orWhere('email', '=', $request->user_name)
                ->where('status', '!=', 2)
                ->where('app_name', 'b2bapp')
                ->orderBy('id', 'desc')
                ->get()->first();
            //    echo"<pre>";print_r($user);die;
            $success = [];
            if (!empty($user)) {
                if ($user->status == 2) {
                    return $this->sendError('User Not Found', $success, 200);
                } else {
                    return $this->sendResponse([$user], 'User Find Successfully');
                }
            } else {
                return $this->sendError('User Not Found', $success, 200);
            }
        } catch (Exception $e) {
            $success = [];
            return $this->sendError($success, 'Something went wrong', ['error' => $e], 500);
        }
    }


    /**
     * @OA\Post(
     *   path="/v1/otp-send",
     *   tags={"Authentication"},
     *   summary="OTP Send",
     *   description="Pass Email Address Ex: example@gmail.com</br>",
     *   operationId="otpSend",
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(
     *             required={"email"},
     *             @OA\Property(property="email", title="email", type="string", description="enter email address to get otp"),
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
     * Login Send
     *
     * @return \Illuminate\Http\Response
     */
    public function otpSend(Request $request)
    {
        try {
            // set custom rule for gst_no validation
            Validator::extend('email_rule', function ($attribute, $value) {
                return preg_match('/(.+)@(.+)\.(.+)/i', $value);
            }, 'Please enter valid Email Address.');
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|email_rule|exists:users,email',
            ], [
                'email.email' => 'Please enter valid Email Address.'
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => 'Invalid request', 'data' => [$validator->errors()]], 422);
            }
            $agencyId = User::where('email', $request->email)->where('app_name', 'b2bapp')->value('agency_id');
            $agencyStatus = Agency::where('id', $agencyId)->value('status');
            // echo $agency;die;
            if ($agencyStatus == 'inactive') {
                $success = [];
                return $this->sendError('Agency is In-Active, please contact to administrator', $success, 200);
            }
            if ($agencyStatus == 'terminated') {
                $success = [];
                return $this->sendError('Agency not Found', $success, 200);
            }
            // $user = User::where('email', '=', $request->email)->where('app_name', 'b2bapp')->first();
            $userStatus = User::where('email', '=', $request->email)->where('app_name', 'b2bapp')->value('status');
            if ($userStatus == 0) {
                $success = [];
                return $this->sendError('User is In-Active, please contact to administrator', $success, 200);
            }
            $checker = User::where('email', '=', $request->email)->where('status', '!=', '2')->where('app_name', 'b2bapp')->first();

            if (!empty($checker)) {

                //get OTP verification setting key value 'general|otp|phoneVerification'
                $otp_verification = false;
                $otp_setting_data = \App\Models\Setting::where('config_key', 'general|otp|phoneVerification')->get()->toArray();

                if ($checker->status == 1) {
                    $otp = rand(10000, 99999);

                    $currentDate = date('Y-m-d H:i:s');
                    $datee = strtotime($currentDate);
                    $otp_expire_minute = 5;
                    $date1 = strtotime("+" . $otp_expire_minute . "minute", $datee);
                    $otp_expire = date('Y-m-d H:i:s', $date1);

                    $temp = array(
                        "otp" => $otp,
                        "mobile" => $checker->mobile,
                        "expired" => $otp_expire,
                    );

                    $mobile_number = ['mobile' => $checker->mobile];

                    $message = "$otp is your one time password to proceed on Admin Panel. It is valid for $otp_expire_minute minutes";

                    if (!empty($otp_setting_data)) {
                        if ($otp_setting_data[0]['value'] == "on") {
                            $otp_verification = true;
                            //send OTP SMS code
                            $this->sendSms($checker->mobile, $message);
                        }
                    }

                    if (!empty($checker->email)) {

                        //                            //set SEND_OTP data in mailTemplate Function
                        $email = $checker->email;
                        $code = 'SEND_OTP';
                        $siteName = count(Setting::where('config_key', 'general|basic|siteName')->get('value')) > 0 ? Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'] : "Amar Infotech";
                        $customerName = $checker->owner_name;
                        $otp = $otp;
                        $otp_expire_minute = $otp_expire_minute;

                        //send password and username(mobile) on mail
                        $subject = "Send Otp for Travel Portal.";

                        $mailData = "<html><table class='list-table' style='font-family: arial, sans-serif;
                                        border-collapse: collapse;
                                        width: 100%;'> <tr>
                                <td style='padding: 0 0 15px;vertical-align: top;'>";
                        $mailData .=        "Dear " . $customerName;
                        $mailData .= "    </td>
                            </tr>
                            <tr>
                                <td style='padding: 0 0 15px;vertical-align: top;'> ";
                        $mailData .= "        Otp: " . $otp;
                        $mailData .= "    </td>
                            </tr>
                            <tr>
                                <td style='padding: 0 0 15px;vertical-align: top;'>";
                        $mailData .= $otp . " is your one time password to proceed on " . $siteName . " It is valid for " . $otp_expire_minute . " minutes.
                                </td>
                            </tr>
                            </table></html>";

                        $this->sendEmail($email, $subject, $mailData);
                    }
                    AdminUserOtp::updateOrCreate($mobile_number, $temp);

                    $success['otp'] = $otp;
                    $success['otp_verify'] = $otp_verification;
                    $response = array($success);

                    return $this->sendResponse($response, "OTP send successfully to your registered Mobile & E-mail.");
                } else if ($checker->status == '2') {
                    return $this->sendError('User not Found', [], 200);
                } else {
                    return $this->sendError('User is Inactive', [], 200);
                }
            } else {

                $success = [];
                return $this->sendError('User not found', $success, 200);
            }
        } catch (Exception $e) {
            $success = [];
            return $this->sendError($success, 'Something went wrong', ['error' => $e], 500);
        }
    }

    /**
     * @OA\Post(
     *   path="/v1/otp-verify",
     *   tags={"Authentication"},
     *   summary="OTP Verify",
     *   description="Pass Email Address Ex: example@gmail.com</br>",
     *   operationId="otpVerify",
     *   @OA\RequestBody(
     *     required=true,
     *     description="Login Details", 
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(
     *             required={"email","otp"},
     *             @OA\Property(property="email", title="email", type="string", description="enter email on which you get OTP"),
     *             @OA\Property(property="otp", title="otp", type="string", description="enter otp whatever you got on email to verify"),
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
    public function otpVerify(Request $request)
    {
        try {
            // set custom rule for gst_no validation
            Validator::extend('email_rule', function ($attribute, $value) {
                return preg_match('/(.+)@(.+)\.(.+)/i', $value);
            }, 'Please enter valid Email Address.');
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|email_rule',
                'otp' => 'required|numeric',
            ], [
                'email.email' => 'Please enter valid Email Address.'
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => 'Invalid request', 'data' => [$validator->errors()]], 422);
            }
            $agencyId = User::where('email', $request->email)->where('app_name', 'b2bapp')->value('agency_id');
            $agencyStatus = Agency::where('id', $agencyId)->value('status');
            // echo $agency;die;
            if ($agencyStatus == 'inactive') {
                $success = [];
                return $this->sendError('Agency is In-Active, please contact to administrator', $success, 200);
            }
            if ($agencyStatus == 'terminated') {
                $success = [];
                return $this->sendError('Agency not Found', $success, 200);
            }
            // $user = User::where('email', '=', $request->email)->where('app_name', 'b2bapp')->first();
            $userStatus = User::where('email', '=', $request->email)->where('app_name', 'b2bapp')->value('status');
            if ($userStatus == 0) {
                $success = [];
                return $this->sendError('User is In-Active, please contact to administrator', $success, 200);
            }
            $checker = User::select('*', 'id as user_id')->where('email', '=', $request->email)->where('status', 1)->where('status', '!=', '2')->where('app_name', 'b2bapp')->first();
            //  echo"<pre>";print_r($checker);die;
            if (!empty($checker)) {

                $userOtp = AdminUserOtp::where('mobile', '=', $checker->mobile)
                    ->where('otp', '=', $request->otp)
                    ->first();

                if (!empty($userOtp)) {

                    $exipreDate = $userOtp['expired'];
                    $currentDate = date('Y-m-d H:i:s');

                    if (strtotime($currentDate) < strtotime($exipreDate)) {

                        AdminUserOtp::where('mobile', '=', $checker->mobile)
                            ->where('otp', '=', $request->otp)
                            ->delete();

                        Auth::guard('b2b-api')->loginUsingId($checker->id);
                        $userData = Auth::guard('b2b-api')->user();
                        // echo"<pre>";print_r($userData);die;
                        $success = $checker;
                        $success['token'] =  $userData->createToken('b2bAuthToken')->accessToken;

                        return $this->sendResponse($success, 'OTP Verified Successfully');
                    } else {

                        $success = [];
                        return $this->sendError('OTP expired!', $success, 200);
                    }
                } else {
                    $success = [];
                    return $this->sendError('Email and otp does not match', $success, 200);
                }
            } else {
                $success = [];
                return $this->sendError('User not found', $success, 200);
            }
        } catch (Exception $e) {
            $success = [];
            return $this->sendError($success, 'Something went wrong', ['error' => $e], 500);
        }
    }
    /**
     * @OA\Post(
     *   path="/v1/reset-password",
     *   security={
     *     {"bearerAuth": {}}
     *   },
     *   tags={"Authentication"},
     *   summary="Reset Password",
     *   operationId="reset-password",
     *   @OA\Parameter(
     *      name="body",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           collectionFormat="multi",
                 required={"password","confirm_password"},
     *           @OA\Property(property="password", type="string",  ),
     *           @OA\Property(property="confirm_password", type="string",  ),
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
     * OTP Verify
     *
     * @return \Illuminate\Http\Response
     */
    public function resetPassword(Request $request)
    {

        $requestData = $request->all();
        // resetPassword($requestData['password'],$requestData['confirm_password']);
        // echo "<pre>";print_r($request->all());die;
        try {

            //         // set custom rule for password length validation
            //         Validator::extend('password_length', function ($attribute, $value, $parameters, $validator) {
            //             $minPassLength = Setting::where('config_key', 'passwordSecurity|minimumPasswordLength')->get('value')[0]['value'];
            //             return ($minPassLength <= strlen($value)); 

            //         });
            //         //validate pass that should contain digits
            //         Validator::extend('contain_digits', function ($attribute, $value, $parameters, $validator) {
            //             $matches = [];
            //             $minDigitsLength = Setting::where('config_key', 'passwordSecurity|numericCharacter')->get('value')[0]['value'];
            //             return (preg_match_all("/\d/", $value, $matches) >= $minDigitsLength); 

            //         });
            //         //validate pass that should contain special character
            //         Validator::extend('special_character', function ($attribute, $value, $parameters, $validator) {
            //             $matches = [];
            //             $minSpecialcharLength = Setting::where('config_key', 'passwordSecurity|specialCharacter')->get('value')[0]['value'];
            //             return (preg_match_all("/\W/", $value, $matches) >= $minSpecialcharLength); 

            //         });
            //         //validate pass that should contain capital letter
            //         Validator::extend('capital_letter', function ($attribute, $value, $parameters, $validator) {
            //             $minUppercharLength = Setting::where('config_key', 'passwordSecurity|uppercaseCharacter')->get('value')[0]['value'];
            //             return (preg_match('/^(.*?[A-Z]){' . $minUppercharLength . '}/', $value)); 

            //         });
            //         //validate pass that should contain small letter
            //         Validator::extend('small_letter', function ($attribute, $value, $parameters, $validator) {
            //             $minLowercharLength = Setting::where('config_key', 'passwordSecurity|lowercaseCharacter')->get('value')[0]['value'];
            //             return (preg_match('/^(.*?[a-z]){' . $minLowercharLength . '}/', $value)); 

            //         });
            //         //validate pass that shoult contain alphanumeric
            //         Validator::extend('alphanumeric', function ($attribute, $value, $parameters, $validator) {
            //             $minAlphanumericcharLength = Setting::where('config_key', 'passwordSecurity|alphanumericCharacter')->get('value')[0]['value'];
            //             return (preg_match_all("/[a-zA-Z0-9]/", $value, $matches) > $minAlphanumericcharLength); 

            //         });

            //         //setting's values for password security verify
            //         $minPassLength = Setting::where('config_key', 'passwordSecurity|minimumPasswordLength')->get('value')[0]['value'];
            //         $minDigitsLength = Setting::where('config_key', 'passwordSecurity|numericCharacter')->get('value')[0]['value'];
            //         $minSpecialcharLength = Setting::where('config_key', 'passwordSecurity|specialCharacter')->get('value')[0]['value'];
            //         $minUppercharLength = Setting::where('config_key', 'passwordSecurity|uppercaseCharacter')->get('value')[0]['value'];
            //         $minLowercharLength = Setting::where('config_key', 'passwordSecurity|lowercaseCharacter')->get('value')[0]['value'];
            //         $minAlphanumericcharLength = Setting::where('config_key', 'passwordSecurity|alphanumericCharacter')->get('value')[0]['value'];

            $requestData = $request->all();
            $data['key'] = array_keys($requestData)[0];
            $data['password'] = $request->password;
            $responseData = resetPassword($data);
            // echo '<pre>';print_r($data);die;
            if ($responseData['valid'] != '1') {
                return response()->json(['status' => false, 'message' => 'Invalid request', 'data' => $responseData], 422);
            }
            $validator = Validator::make($request->all(), [
                'password' => 'required',
                'confirm_password' => 'required|same:password',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => 'Invalid request', 'data' => [$validator->errors()]], 422);
            }
            if (Auth::user()) {
                $data = [
                    'password' => Hash::make($requestData['password']),
                    'password_updated_at' => date('Y-m-d h:i:s')
                ];


                User::where('id', Auth::id())->update($data);
                return $this->sendResponse([], 'Your Password Reset Successfully');
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
