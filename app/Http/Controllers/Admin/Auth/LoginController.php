<?php

/**
 * @package     Auth
 * @subpackage   Login
 * @Author      Amar Technolabs Pvt. ltd(info@amarinfotech.com)
 * @Copyright(C) 2023 [Travel Portal].
 * @Version 1.0.0
 * module of the login.
 */

namespace App\Http\Controllers\Admin\Auth;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AppUsers;
use Auth;
use App\Models\Setting;
use App\Models\Agency;
use App\Models\User;
use App\Models\AdminUserOtp;
use App\Traits\ActiveLog;
use Illuminate\Support\Facades\Hash;
use DB;
use DateTime;
use Illuminate\Support\Facades\Redirect;
use App\Traits\EmailService;
use Illuminate\Support\Str;
use Carbon\Carbon;


class LoginController extends Controller
{
    use EmailService;

    public function adminLogin(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|email',
            'password' => 'required|string',
        ]);
        $getTimeZone = count(Setting::where('config_key', 'general|site|timeZone')->get('value')) > 0 ? Setting::where('config_key', 'general|site|timeZone')->get('value')[0]['value'] : "Asia/Kolkata";
        $username = $request->mobile;
        $password = $request->password;
        $remember = $request->has('remember') ? true : false;
        $curentDateTime = date("Y-m-d H:i:s");

        
        if (is_numeric($username)) {

            $userDetail = User::where('mobile', $username)->where('status','1')->where('app_name', 'managerapp')->value('mobile');
            Auth::attempt(['mobile' => $username, 'password' => $password, 'status' => '1', 'app_name'=> 'managerapp'], $remember);
        } else {
            $userDetail = User::where('email', $username)->where('status','1')->where('app_name', 'managerapp')->value('email');
            Auth::attempt(['email' => $username, 'password' => $password, 'status' => '1', 'app_name'=> 'managerapp'], $remember);
        }
        $loginAttemptsDetail = DB::table('user_login_attempts')->Where('host', $_SERVER['REMOTE_ADDR'])->where('next_login_available_at', '>', $curentDateTime)->latest('next_login_available_at')->get()->toArray();

        $activityLog['request'] =  $request->all();
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] =  $loginAttemptsDetail;
        ActiveLog::createBackendActiveLog($activityLog);

        if(Auth::check()) {
            if ($userDetail != "") {
            
            $userMobile = User::where('mobile', $username)->orWhere('email', $username)->value('mobile');
            $agencyId = User::where('mobile',$userMobile)->value('agency_id');
            //check count of perticular user
            $loginAttemptDetailsForUser = DB::table('user_login_attempts')->Where('username', '!=', $userMobile)->get()->toArray();
            
                
                $isUserEXistInLoginAttempt = DB::table('user_login_attempts')->where('username', $username)->delete();
   
                $app = Auth::user()->app_name;
                if ($app == "managerapp") {
                    return redirect()->route('admin.dashboard')->with('success', 'Successfully Login');
                } else if ($app == "b2bapp") {
                    $checkAgencyStatus = Agency::where('id',$agencyId)->value('status');
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
                   
                        $data = array(
                            'agency_name' => $agencyDetails[0]['full_name'],
                            'agency_logo' => $agencyDetails[0]['logo']
                        );

                        $getTemplateData = EmailService::agencyBlockMailTemplate($code, $data);
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
                    
                } else if ($app == "supplierapp") {
                    $checkAgencyStatus = Agency::where('id',$agencyId)->value('status');
                    if($checkAgencyStatus == 'active')
                    {
                        //set route redirection for b2b
                        return redirect()->route('supplier.dashboard')->with('success', 'Successfully Login');
                    }
                    else
                    {
                        $isPrimaryUser = User::where('mobile',$userMobile)->where('agency_id','!=','0')->value('primary_user');
                        //send mail to user if agency status is inavtive or terminated
                        $agencyLogo = Setting::where('config_key', 'general|basic|colorLogo')->get('value')[0]['value'] ?? Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'];
                        $code = 'AGENCY_BLOCK';
                        $siteName = count(Setting::where('config_key', 'general|basic|siteName')->get('value')) > 0 ? Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'] : "Amar Infotech";
                        $agencyDetails = Agency::where('id',Auth::user()->agency_id)->get()->toArray();
      
                        $data = array(
                            'agency_name' => $agencyDetails[0]['full_name'],
                            'agency_logo' => $agencyDetails[0]['logo']
                        );

                        $getTemplateData = EmailService::agencyBlockMailTemplate($code, $data);
                      
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
                } else {
                    //default redirect to manager
                    return redirect()->route('admin.dashboard')->with('success', 'Successfully Login');
                }
            }else
            {
                
                return redirect()->back()->with('error','User is inactive');
            }
            
        }
        
        $lockoutTimePerUserOrHostType = Setting::where('config_key', 'loginAttempts|lockOutTimePeriodType')->get('value')[0]['value'];
        if (!empty($loginAttemptsDetail)) {
            $datetime1 = new DateTime($curentDateTime);
            $datetime2 = new DateTime($loginAttemptsDetail[0]->next_login_available_at);
            $interval = $datetime1->diff($datetime2);
            $days = $interval->d;
            $hours = $interval->h;
            $minutes = $interval->i;
            if ($lockoutTimePerUserOrHostType == 'minute') {
                if ($minutes == '0') {
                    return redirect()->route('admin.login')->with('error', 'Your account has been locked, you can login after few seconds');
                } else {
                    return redirect()->route('admin.login')->with('error', 'Your account has been locked, you can login after ' . $minutes . ' minutes');
                }
            }
            if ($lockoutTimePerUserOrHostType == 'hour') {
                return redirect()->route('admin.login')->with('error', 'Your account has been locked, you can login after ' . $hours . ' hour');
            }
            if ($lockoutTimePerUserOrHostType == 'day') {
                return redirect()->route('admin.login')->with('error', 'Your account has been locked, you can login after ' . $days . ' day');
            }
        }




        if ($validator->fails()) {
            return redirect()->route('admin.login')->withErrors($validator)->withInput();
        }
        $isEnableLoginAttempt = Setting::where('config_key', 'loginAttempts|enable')->get('value')[0]['value'];
        if ($userDetail != "" && $isEnableLoginAttempt == '1') {

            $userMobile = User::where('mobile', $username)->orWhere('email', $username)->value('mobile');

            //function to check login attempt when credential are wrong 
            $checkLoginAttempt = self::checkLoginAttempt($userMobile);
     
            return redirect()->route('admin.login')->with('error', $checkLoginAttempt);
        } else {
            return redirect()->route('admin.login')->with('error', 'Invalid Login Credentials');
        }
    }
    public function getForgotPassword()
    {
        $header['title'] = @trans('forgotPassword.title');
        return view('admin/auth/forgot-password')->with('header', $header);
    }

    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|email',
        ]);
        if ($validator->fails()) {
            return redirect()->route('admin.forgot-password')->withErrors($validator)->withInput();
        }
        $mobileExists = User::where('email', $request->mobile)->get();
        if (count($mobileExists) == 0) {
            return redirect()->route('admin.forgot-password')->with('error', 'Email Is Not Exist');
        } else {
            try {

                $checker = User::where('email', '=', $request->mobile)->where('status', '!=', '2')->first();

                if (!empty($checker)) {

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
                            "mobile" => $request->mobile,
                            "expired" => $otp_expire,
                        );
                        $mobile_number = ['mobile' => $request->mobile];
                        $site_name = count(Setting::where('config_key', 'general|basic|siteName')->get('value')) > 0 ? Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'] : "Amar Infotech";
                        $message = "$otp is your one time password to proceed on " . $site_name . " It is valid for $otp_expire_minute minutes";

                        if (!empty($otp_setting_data)) {
                            if ($otp_setting_data[0]['value'] == "on") {
                                $otp_verification = true;
                                //send OTP SMS code
                                $this->sendSms($request->mobile, $message);
                            }
                        }

                        if (!empty($checker->email)) {

                            $email = $checker->email;
                            $code = 'SEND_OTP';
                            $siteName = count(Setting::where('config_key', 'general|basic|siteName')->get('value')) > 0 ? Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'] : "Amar Infotech";
                            $customerName = $checker->owner_name;
                            $otp = $otp;
                            $otp_expire_minute = $otp_expire_minute;


                            //send password and username(mobile) on mail
                            $subject = "Send Otp for Travel Portal Application.";

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

                        $activityLog['request'] =  $request->all();
                        $activityLog['request_url'] =  request()->url();
                        $activityLog['response'] =  $response;
                        ActiveLog::createBackendActiveLog($activityLog);

                        return redirect()->route('admin.otp', ['email' => $request->mobile])->with('success', "OTP send successfully to your registered E-mail.")->with(['mobile' => $request->mobile]);
                    } else if ($checker->status == '2') {
                        return redirect()->back()->with('error', 'User not Found');
                    } else {
                        return redirect()->back()->with('error', 'User is Inactive');
                    }
                } else {
                    $success = [];
                    return redirect()->back()->with('error', 'User not found');
                }
            } catch (Exception $e) {
                $success = [];
                return $this->sendError($success, 'Something went wrong', ['error' => $e], 500);
            }
            return redirect()->route('admin.otp')->with('success', 'OTP has been sent successfully');
        }
    }
    public function otpVerify(Request $request)
    {

        try {

            $validator = Validator::make($request->all(), [
                'digit1' => 'required|numeric',
                'digit2' => 'required|numeric',
                'digit3' => 'required|numeric',
                'digit4' => 'required|numeric',
                'digit5' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => 'Invalid request', 'data' => [$validator->errors()]], 422);
            }
            $otpConcat = $request->digit1 . $request->digit2 . $request->digit3 . $request->digit4 . $request->digit5;
            $checker = User::select('*', 'id as user_id')->where('email', '=', $request->mobile)->where('status', 1)->where('status', '!=', '2')->first();
     
            if (!empty($checker)) {

                $userOtp = AdminUserOtp::where('mobile', '=', $request->mobile)
                    ->where('otp', '=', $otpConcat)
                    ->first();

                if (!empty($userOtp)) {

                    $exipreDate = $userOtp['expired'];
                    $currentDate = date('Y-m-d H:i:s');

                    if (strtotime($currentDate) < strtotime($exipreDate)) {

                        AdminUserOtp::where('mobile', '=', $request->mobile)
                            ->where('otp', '=', $otpConcat)
                            ->delete();

                        return redirect()->route('admin.reset-password.create', ['mobile' => $request->mobile])->with('success', 'OTP Verified Successfully', ['mobile' => $request->mobile]);
                    } else {

                        $success = [];
                        return redirect()->back()->with('error', 'OTP expired!');
                    }
                } else {
                    $success = [];
                    return redirect()->back()->with('error', 'Email and otp does not match');
                }
            } else {
                $success = [];
                return redirect()->back()->with('error', 'User not found');
            }
        } catch (Exception $e) {
            return redirect()->back()->with('success', 'Something went wrong' . $e->getMessage());
        }
    }

    public function getOtp(Request $request)
    {
        
        $header['title'] = @trans('otp.title');

        return view('admin/auth/otp')->with(['header' => $header, 'mobile' => $request->email]);
    }
    public function logout()
    {
        Auth::logout();
        return redirect()->route('admin.login');
    }
    public function getResetPassword(Request $request, $mobileNo)
    {

        //get user's mobile number using email address from the table
        if(!filter_var($mobileNo, FILTER_VALIDATE_EMAIL))
        {
            $getEmail = DB::table('password_resets')->where('token',$mobileNo)->value('email');
            $mobile = User::where('email',$getEmail)->where('status','!=','2')->value('mobile');
        }
        else{
            $mobile = User::where('email',$mobileNo)->where('status','!=','2')->value('mobile');
        }
        
        $header['title'] = @trans('resetPassword.title');
        return view('admin/auth/reset-password')->with(['header' => $header, 'mobile' => $mobile]);
    }
    public function reset_password(Request $request)
    {
        
        try {
            $validator = Validator::make($request->all(), [
                'password' => 'required',
                'confirm_password' => 'required|same:password',
            ]);
            
            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => 'Invalid request', 'data' => [$validator->errors()]], 422);
            }
            
            $requestData = $request->all();
            if ($request->mobile) {
                $userDetails = User::where('mobile',$request->mobile)->where('status','!=',2)->get();
           
                $data = [
                    'password' => Hash::make($requestData['password']),
                    'status' => '1'
                ];
                $successMail = User::where('id', $userDetails[0]['id'])->update($data);
      
                if ($successMail) {
                    $userData = User::where('mobile', $request->mobile)->get()->toArray();
                 
                    
                    $code = 'USER_ACCOUNT_ACTIVATION';
                    
                    
                   

                    $getTemplateData = $this->userAccountActivationMailTemplate($code, $userData[0]);
                    $subject = $getTemplateData['data']['subject'];
                    $mailData = $getTemplateData['data']['mailData'];
                    $toEmail = $userData[0]['email'];
                    $files = [];
                    
                    // set data in sendEmail function
                    $data = $this->sendEmail($toEmail, $subject, $mailData, $files, $getTemplateData['agencyName']);
                 
                    DB::table('password_resets')->where('email',$userData[0]['email'])->delete();
                    return redirect()->route('admin.login')->with('success', 'Your Password Saved Successfully');
                }
            } else {
                return redirect()->back()->with('error', 'Link is Expired');
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong' . $e->getMessage());
        }
    }

    //for api reset-password link
    public function api_reset_password(Request $request)
    {
        $updatePassword = DB::table('password_resets')
            ->where(['email' => $request->email, 'token' => $request->token])
            ->first();
        
        if (!$updatePassword) {

            return back()->withInput()->with('error', 'Invalid token!');
        }

        $user = AppUsers::where('email', $request->email)
            ->update(['password' => Hash::make($request->password), 'status' => 1]);

        DB::table('password_resets')->where(['email' => $request->email])->delete();

        $token = $request->token;

        return redirect()->back()->with(['token' => $token]);
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
        if ($attemptPerUser == $loginAttemptPerHost)
        {

            $lastRow = DB::table('user_login_attempts')->latest()->first();
            DB::table('user_login_attempts')->where('id', $lastRow->id)->update([
                'next_login_available_at' => $modifyNextAttemptDate
            ]);
            if ($isEnableNotification == '1') {
                $userData = User::where('mobile', $username)->orWhere('email', $username)->get()->toArray();
                
                if($userData[0]['agency_id'] == '0')
                {
                    $agencyLogo = count(Setting::where('config_key', 'general|basic|colorLogo')->get('value')) > 0 ? Setting::where('config_key', 'general|basic|colorLogo')->get('value')[0]['value'] : Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'];
                    $agencyName = count(Setting::where('config_key', 'general|basic|siteName')->get('value')) > 0 ? Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'] : "Amar Infotech";
                }
                else
                {
                    if($userData[0]['primary_user'] == '1')
                    {
                        $agencyLogo = count(Setting::where('config_key', 'general|basic|colorLogo')->get('value')) > 0 ? Setting::where('config_key', 'general|basic|colorLogo')->get('value')[0]['value'] : Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'];
                        $agencyName = count(Setting::where('config_key', 'general|basic|siteName')->get('value')) > 0 ? Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'] : "Amar Infotech";
                    }
                    else
                    {
                        $agencyName = Agency::where('id', $userData[0]['agency_id'])->value('full_name');
                        $agencyLogo = Agency::where('id', $userData[0]['agency_id'])->value('logo');
                    }
                }
                $code = 'LOGIN_ATTEMPTS_EXCEED';
                $customerName = ucwords($userData[0]['name']);
                $data = array(
                    'customer_name' => $customerName,
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
                $userData = User::where('mobile', $username)->orWhere('email', $username)->get()->toArray();
                if($userData[0]['agency_id'] == '0')
                {
                    $agencyLogo = count(Setting::where('config_key', 'general|basic|colorLogo')->get('value')) > 0 ? Setting::where('config_key', 'general|basic|colorLogo')->get('value')[0]['value'] : Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'];
                    $agencyName = count(Setting::where('config_key', 'general|basic|siteName')->get('value')) > 0 ? Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'] : "Amar Infotech";
                }
                else
                {
                    if($userData[0]['primary_user'] == '1')
                    {
                        $agencyLogo = count(Setting::where('config_key', 'general|basic|colorLogo')->get('value')) > 0 ? Setting::where('config_key', 'general|basic|colorLogo')->get('value')[0]['value'] : Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'];
                        $agencyName = count(Setting::where('config_key', 'general|basic|siteName')->get('value')) > 0 ? Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'] : "Amar Infotech";
                    }
                    else
                    {
                        $agencyName = Agency::where('id', $userData[0]['agency_id'])->value('full_name');
                        $agencyLogo = Agency::where('id', $userData[0]['agency_id'])->value('logo');
                    }
                }
                $code = 'LOGIN_ATTEMPTS_EXCEED';
                
                $customerName = ucwords($userData[0]['name']);

                $data = array(
                    'customer_name' => $customerName,
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

    //password validation
    public function resetPasswordvalidate(Request $request)
    {
        //fetch password security configuration values form setting table
        $minPassLength = Setting::where('config_key', 'passwordSecurity|minimumPasswordLength')->get('value')[0]['value'];
        $minDigitsLength = Setting::where('config_key', 'passwordSecurity|numericCharacter')->get('value')[0]['value'];
        $minSpecialcharLength = Setting::where('config_key', 'passwordSecurity|specialCharacter')->get('value')[0]['value'];
        $minUppercharLength = Setting::where('config_key', 'passwordSecurity|uppercaseCharacter')->get('value')[0]['value'];
        $minLowercharLength = Setting::where('config_key', 'passwordSecurity|lowercaseCharacter')->get('value')[0]['value'];
        $minAlphanumericcharLength = Setting::where('config_key', 'passwordSecurity|alphanumericCharacter')->get('value')[0]['value'];
        $response = [
            'valid' => false,
        ];
        $matches = [];
        //validate pass length based on setting value
        if ($minPassLength > strlen($request->password)) {
            $response['valid'] = false;
            $response['message'] = "Password should contain atleast " . $minPassLength . " charcters";
        }
        //validate pass that should contain digits
        else if (preg_match_all("/\d/", $request->password, $matches) < $minDigitsLength) {
            $response['valid'] = false;
            $response['message'] = "Password should contain at least " . $minDigitsLength . " digit";
        }
        //validate pass that should contain special character
        else if (preg_match_all("/\W/", $request->password, $matches) < $minSpecialcharLength) {
            $response['valid'] = false;
            $response['message'] = "Password should contain at least " . $minSpecialcharLength . " special character";
        }
        //validate pass that should contain capital letter
        else if (!preg_match('/^(.*?[A-Z]){' . $minUppercharLength . '}/', $request->password)) {
            $response['valid'] = false;
            $response['message'] = "Password should contain at least " . $minUppercharLength . " Capital Letter";
        }
        //validate pass that should contain small letter
        else if (!preg_match('/^(.*?[a-z]){' . $minLowercharLength . '}/', $request->password)) {
            $response['valid'] = false;
            $response['message'] = "Password should contain at least " . $minLowercharLength . " small Letter";
        }
        //validate pass that shoult contain alphanumeric
        else if (preg_match_all("/[a-zA-Z0-9]/", $request->password, $matches) < $minAlphanumericcharLength) {
            $response['valid'] = false;
            $response['message'] = "Password should contain at least " . $minAlphanumericcharLength . " alphanumeric character";
        } else {
            $response['valid'] = true;
        }

        return response()->json($response);
    }
}
