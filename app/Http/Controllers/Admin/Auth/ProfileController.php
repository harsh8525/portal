<?php

/**
 * @package     Dashboard
 * @subpackage  Profile Update
 * @Author      Amar Technolabs Pvt. ltd(info@amarinfotech.com)
 * @Copyright(C) 2023 [NAME OF THE ORGANISATION THAT ON BEHALF OF THE CODE WE ARE WORKING].
 * @Version 1.0.0
 * module of the Profile Update.
 */

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Setting;
use App\Models\Country;
use App\Models\Agency;
use App\Models\CountryI18ns;
use App\Models\UserLoginHistory;
use App\Models\Role;
use App\Traits\EmailService;
use App\Traits\ActiveLog;
use App\Traits\LoginHistory;
use Illuminate\Support\Str;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;




class ProfileController extends Controller
{
    use EmailService,LoginHistory;

    // Show the form for editing the specified profile update.
    public function edit()
    {

        $filter = array(
            'id' => Auth::id(),
        );
        $response = User::getAdminUsers($filter);
        $userDetail = $response['data'];
        $getIsdCode = Country::get();
        if ($userDetail['app_name'] == 'managerapp') {
            $getRole = Role::where('role_type', 'manager')->get()->toArray();
        }
        if ($userDetail['app_name'] == 'b2bapp') {
            $getRole = Role::where('role_type', 'b2b')->get();
        }
        if ($userDetail['app_name'] == 'supplierapp') {
            $getRole = Role::where('role_type', 'supplier')->get();
        }

        $activityLog['request'] =  $filter;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] =  $response;
        ActiveLog::createBackendActiveLog($activityLog);

        if ($response['status'] == 1 && !empty($response['data'])) {

            return view('admin/auth/profile-update')->with(['userDetail' => $userDetail, 'getIsdCode' => $getIsdCode, 'getRole' => $getRole]);
        } else {
            return redirect()->back()->with('error', $response['message']);
        }
    }

    //Update the specified profile in storage.
    public function update(Request $request, $id)
    {

        $requestData = $request->only(['admin_user_id', 'fname', 'password', 'email', 'profile_image', 'old_profile_image', 'role', 'status', 'isd_code', 'mobile']);

        $rules = [];

        $customMessages = [];

        $niceNames = array();

        $this->validate($request, $rules, $customMessages, $niceNames);


        $response = User::updateUser($requestData);

        $activityLog['request'] =  $requestData;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] =  $response;
        ActiveLog::createBackendActiveLog($activityLog);

        if (!empty($response['data'])) {

            return redirect()->route('admin.dashboard')->with('success', $response['message']);
        } else {
            return redirect()->back()->with('error', $response['message']);
        }
    }

    //  Store a newly created login history in storage.
    public function addLoginHistory(Request $request)
    {

        $requestData = $request->all();
        $response = LoginHistory::createLoginHistory($requestData);
        return $response;
    }

   
    public function currentPasswordvalidate(Request $request)
    {

        if (request()->input('currentPassword') && request()->input('currentPassword') != "") {

            if (!Hash::check(request()->input('currentPassword'), Auth::user()->password)) {
                echo "false";
            } else {
                echo "true";
            }
        }
    }
    public function changePasswordUserEdit(Request $request)
    {
        $header['title'] = 'Change Password';
        $header['heading'] = 'Change Password';

        $filter = array(
            'id' => Auth::id(),
        );
        $response = User::getAdminUsers($filter);
        $userDetail = $response['data'];

        $activityLog['request'] =  $filter;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] =  $response;
        ActiveLog::createBackendActiveLog($activityLog);

        if ($response['status'] == 1 && !empty($response['data'])) {
            return view('admin/auth/change-password')->with(['userDetail' => $userDetail, 'header' => $header]);
        } else {
            return redirect()->back()->with('error', $response['message']);
        }
    }
    public function changePasswordUserUpdate(Request $request, $id)
    {
        $requestData = $request->only(['user_id', 'confirm_password', 'old_password']);

        $rules = [];

        $customMessages = [];

        $niceNames = array();

        $this->validate($request, $rules, $customMessages, $niceNames);


        $response = User::updateNewPassAdmin($requestData);

        $activityLog['request'] =  $requestData;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] =  $response;
        ActiveLog::createBackendActiveLog($activityLog);

        if (!empty($response['data'])) {
            $checkNotifyEnable = Setting::where('config_key', 'passwordSecurity|changePasswordNotify')->get('value')[0]['value'];
            if ($checkNotifyEnable == '1') {

                $userDetail = User::where('id', $response['data']['id'])->get()->toArray();
                $agencyName = Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'];
                $agencyLogo = Setting::where('config_key', 'general|basic|colorLogo')->get('value')[0]['value'] ?? Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'];
                $language_code = Setting::where('config_key', 'general|site|defaultLanguageCode')->get('value')[0]['value'];
                $code = 'CHANGE_PASSWORD';
                $siteName = count(Setting::where('config_key', 'general|basic|siteName')->get('value')) > 0 ? Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'] : "Amar Infotech";
                $customerName = ucwords($userDetail[0]['name']);

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
                    $toEmail = $userDetail[0]['email'];
                    $files = [];

                    // set data in sendEmail function
                    $data = $this->sendEmail($toEmail, $subject, $mailData, $files, $siteName, $code);
                }
            }
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->back()->with('error', $response['message']);
        }
    }
    public function changePasswordvalidate(Request $request)
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
        if ($minPassLength > strlen($request->newPassword)) {
            $response['valid'] = false;
            $response['message'] = "Password should contain atleast " . $minPassLength . " charcters";
        }
        //validate pass that should contain digits
        else if (preg_match_all("/\d/", $request->newPassword, $matches) < $minDigitsLength) {
            $response['valid'] = false;
            $response['message'] = "Password should contain at least " . $minDigitsLength . " digit";
        }
        //validate pass that should contain special character
        else if (preg_match_all("/\W/", $request->newPassword, $matches) < $minSpecialcharLength) {
            $response['valid'] = false;
            $response['message'] = "Password should contain at least " . $minSpecialcharLength . " special character";
        }
        //validate pass that should contain capital letter
        else if (!preg_match('/^(.*?[A-Z]){' . $minUppercharLength . '}/', $request->newPassword)) {
            $response['valid'] = false;
            $response['message'] = "Password should contain at least " . $minUppercharLength . " Capital Letter";
        }
        //validate pass that should contain small letter
        else if (!preg_match('/^(.*?[a-z]){' . $minLowercharLength . '}/', $request->newPassword)) {
            $response['valid'] = false;
            $response['message'] = "Password should contain at least " . $minLowercharLength . " small Letter";
        }
        //validate pass that shoult contain alphanumeric
        else if (preg_match_all("/[a-zA-Z0-9]/", $request->newPassword, $matches) < $minAlphanumericcharLength) {
            $response['valid'] = false;
            $response['message'] = "Password should contain at least " . $minAlphanumericcharLength . " alphanumeric character";
        } else {
            $response['valid'] = true;
        }

        return response()->json($response);
    }

    /**
     * send password expirt mail to uses
     */
    public static function sendExpiryNotificaton()
    {

        $checkNotifyEnable = Setting::where('config_key', 'passwordSecurity|changePasswordNotify')->get('value')[0]['value'];
        if ($checkNotifyEnable == '1') {

            $checkExpiryDays = Setting::where('config_key', 'passwordSecurity|expiryDays')->get('value')[0]['value'];
            $checkExpiryNotifyDays = Setting::where('config_key', 'passwordSecurity|expireNotifyDays')->get('value')[0]['value'];
            $notifyDaysArray = explode(",", $checkExpiryNotifyDays);
            foreach ($notifyDaysArray as $day) {

                //fetch all user details
                $userData = User::where('password_updated_at', '!=', null)->get()->toArray();

                foreach ($userData as $user) {

                    $expiryDate = Carbon::parse($user['password_updated_at'])->addDays($checkExpiryDays);
                    $beforeExpiryDays = $expiryDate->subDays($day)->format('Y-m-d h:i:s');
                    if (Carbon::now() == $beforeExpiryDays) {

                        if ($user['agency_id'] == '0') {

                            $agencyName = count(Setting::where('config_key', 'general|basic|siteName')->get('value')) > 0 ? Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'] : "Amar Infotech";
                            $agencyLogo = count(Setting::where('config_key', 'general|basic|colorLogo')->get('value')) > 0 ? Setting::where('config_key', 'general|basic|colorLogo')->get('value')[0]['value'] : Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'];
                        } else {

                            if ($user['primary_user'] == '1') {
                                $agencyName = count(Setting::where('config_key', 'general|basic|siteName')->get('value')) > 0 ? Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'] : "Amar Infotech";
                                $agencyLogo = count(Setting::where('config_key', 'general|basic|colorLogo')->get('value')) > 0 ? Setting::where('config_key', 'general|basic|colorLogo')->get('value')[0]['value'] : Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'];
                            } else {
                                $agencyName = Agency::where('id', $user['agency_id'])->value('full_name');
                                $agencyLogo = Agency::where('id', $user['agency_id'])->value('logo');
                            }
                        }


                        $code = 'PASSWORD_EXPIRY';

                        $customerName = ucwords($user['name']);
                        $data = array(
                            'customer_name' => $customerName,
                            'site_name' => $agencyName,
                            'agency_name' => $agencyName,
                            'agency_logo' => $agencyLogo,
                            'password_expiry_day' => $checkExpiryDays
                        );

                        $getTemplateData = $this->passwordExpiryMailTemplate($code, $data);

                        if ($getTemplateData['status'] == 'false') {
                            return back()->with('error', $getTemplateData['error']);
                        } else {
                            $subject = $getTemplateData['data']['subject'];
                            $mailData = $getTemplateData['data']['mailData'];
                            $toEmail = $user['email'];
                            $files = [];

                            // set data in sendEmail function
                            $data = $this->sendEmail($toEmail, $subject, $mailData, $files, $siteName, $code);
                        }
                    }
                }
            }
            return ['success' => 1];
        }
    }
}
