<?php

/**
 * @package     Users
 * @subpackage   Users
 * @Author      Amar Technolabs Pvt. ltd(info@amarinfotech.com)
 * @Copyright(C) 2023 [Travel Portal].
 * @Version 1.0.0
 * module of the  Users.
 */

namespace App\Http\Controllers\Admin\Setting;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\AppUsers;
use App\Models\User;
use App\Models\Agency;
use App\Traits\EmailService;
use App\Traits\ActiveLog;
use App\Models\Country;
use App\Models\Role;
use App\Models\Module;
use App\Models\ActivityLog;
use App\Models\ApiLogin;
use Illuminate\Support\Str;
use DB;
use Carbon\Carbon;
use URL;

class AdminUserController extends Controller
{
    use EmailService;
    /**
     * Display a listing of the user.
     *
     * @return \Illuminate\Http\Response    
     */
    public function index()
    {
        if (!hasPermission('USERS_LIST', 'read')) {
            return view('admin/401');
        }
        $header['title'] = @trans('adminUser.title');
        $header['heading'] = @trans('adminUser.moduleHeading');

        $queryStringConcat = '?';
        if (isset($_GET['per_page'])) {
            $queryStringConcat .= ($queryStringConcat == '') ? '?per_page=' . $_GET['per_page'] : '&per_page=' . $_GET['per_page'];
        }
        if (isset($_GET['page'])) {
            $queryStringConcat .= ($queryStringConcat == '') ? '?page=' . $_GET['page'] : '&page=' . $_GET['page'];
        }
        $filter = array(
            'per_page' => (request()->input('per_page') != NULL) ? request()->input('per_page') : Setting::where('config_key', 'general|setting|pagePerAdminRecords')->get('value')[0]['value'],
            'order_by' => (request()->input('order_by') != NULL) ? request()->input('order_by') : 'created_at',
            'sorting' => (request()->input('sorting') != NULL) ? request()->input('sorting') : 'desc',
            'status' => (request()->input('status') != NULL) ? request()->input('status') : '',
            'name' => (request()->input('name') != NULL) ? request()->input('name') : '',
            'mobile' => (request()->input('mobile') != NULL) ? request()->input('mobile') : '',
            'email' => (request()->input('email') != NULL) ? request()->input('email') : '',
            'user_role' => (request()->input('user_role') != NULL) ? request()->input('user_role') : '',
            'agancy_name' => (request()->input('agancy_name') != NULL) ? request()->input('agancy_name') : '',

        );
        if (request()->input('name') != NULL) {
            $filter['where'][] = ['users.name', 'like', '%' . request()->input('name') . '%'];
        }
        if (request()->input('mobile') != NULL) {

            $filter['where'][] = ['users.mobile', 'like', '%' . request()->input('mobile') . '%'];
        }

        if (request()->input('email') != NULL) {
            $filter['where'][] = ['users.email', 'like', '%' . request()->input('email') . '%'];
        }
        if (request()->input('agancy_name') != NULL) {
            $filter['where'][] = ['agencies.full_name', 'like', '%' . request()->input('agancy_name') . '%'];
        }
        if (request()->input('user_role') != NULL) {

            $filter['where'][] = ['users.role_code', 'like', '%' . request()->input('user_role') . '%'];
        }


        if (request()->input('status') != NULL) {
            $filter['where'][] = ['users.status', '=', request()->input('status')];
        }

        $AdminUserListData = User::getAdminUsers($filter);
        $AdminUserCountData = User::count();
        $getAgencyName = Agency::get();

        $AdminUserData = $AdminUserListData['data'];

        // user activity log
        $activityLog['request'] =  request()->input();
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] =  $AdminUserData;
        ActiveLog::createBackendActiveLog($activityLog);

        if ($AdminUserListData['status'] == 1) {
            return view('admin/user/index')->with(['header' => $header, 'AdminUserData' => $AdminUserData, 'getAgencyName' => $getAgencyName, 'AdminUserCountData' => $AdminUserCountData, 'queryStringConcat' => $queryStringConcat, 'appliedFilter' => $filter, 'i' => (request()->input('page', 1) - 1) * $filter['per_page']]);
        } else {
            return view('admin/user/index')->with(['error' => $AdminUserListData['message'], 'header' => $header, 'getAgencyName' => $getAgencyName, 'AdminUserData' => $AdminUserData, 'AdminUserCountData' => $AdminUserCountData, 'queryStringConcat' => $queryStringConcat, 'appliedFilter' => $filter, 'i' => (request()->input('page', 1) - 1) * $filter['per_page']]);
        }
    }

    /**
     * Show the form for creating a new user.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!hasPermission('USERS_LIST', 'create')) {
            return view('admin/401');
        }
        $getAgencyName = Agency::get();
        $getIsdCode = Country::with('countryCode')->get();

        $header['title'] = "User - Add";
        $header['heading'] = "User - Add";
        return view('admin/user/add')->with(['header' => $header, 'getIsdCode' => $getIsdCode]);
    }

    /**
     * Store a newly created user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!hasPermission('USERS_LIST', 'create')) {
            return view('admin/401');
        }

        $requestData = $request->all();
        $rules = [
            'mobile' => 'required',
            'profile_image' => 'nullable|mimes:jpeg,png,jpg',
        ];
        
        $customMessages = [];
        
        $niceNames = array();
        
        $this->validate($request, $rules, $customMessages, $niceNames);
        
        $response = User::createUser($requestData);

        // user activity log
        $activityLog['request'] =  $requestData;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] =  $response;
        ActiveLog::createBackendActiveLog($activityLog);

        if (!empty($response['data'])) {
            return redirect()->route('user.index')->with('success', $response['message']);
        } else {
            return redirect()->route('user.index')->with('error', $response['message']);
        }
    }

    /**
     * Display the specified user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!hasPermission('USERS_LIST', 'read')) {
            return view('admin/401');
        }

        $header['title'] = 'User - View';
        $header['heading'] = 'User - View';

        $filter = array(
            'id' => $id
        );
        $response = User::getAdminUsers($filter);
        $activityLog = User::getActivityList($filter);
        $userDetail = $response['data'];

        // user activity log
        $activityLog['request'] =  $id;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] =  $userDetail;
        ActiveLog::createBackendActiveLog($activityLog);

        $query = ActivityLog::query();
        $query->select('activity_log.*', 'users.name as user_name');
        $query->join('users', 'users.id', 'activity_log.causer_id');
        $query->where('activity_log.causer_id', $id);
        $query->orderBy('activity_log.id', 'DESC');
        $result = $query->get();

        if ($response['status'] == 1 && !empty($response['data'])) {
            return view('admin/user/view')->with(['header' => $header, 'userDetail' => $userDetail, 'activityLog' => $activityLog, 'result' => $result]);
        } else {
            return redirect()->route('user.index')->with('error', $response['message']);
        }
    }

    /**
     * Show the form for editing the specified user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        if (!hasPermission('USERS_LIST', 'update')) {
            return view('admin/401');
        }
        $getIsdCode = Country::with('countryCode')->get();
        $header['title'] = 'Edit - User';
        $header['heading'] = 'Edit - User';

        $filter = array(
            'id' => $id
        );
        $response = User::getAdminUsers($filter);
        $userDetail = $response['data'];

        // user activity log
        $activityLog['request'] =  $id;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] =  $userDetail;
        ActiveLog::createBackendActiveLog($activityLog);

        $getRolNameDesc = '';
        if ($userDetail['app_name'] == 'managerapp') {
            $getRole = Role::where('role_type', 'manager')->get()->toArray();
        }
        if ($userDetail['app_name'] == 'b2bapp') {
            $getRole = Role::where('role_type', 'b2b')->get();
        }
        if ($userDetail['app_name'] == 'supplierapp') {
            $getRole = Role::where('role_type', 'supplier')->get();
        }

        if ($response['status'] == 1 && !empty($response['data'])) {
            return view('admin/user/update')->with(['header' => $header, 'userDetail' => $userDetail, 'getIsdCode' => $getIsdCode, 'getRole' => $getRole]);
        } else {
            return redirect()->route('user.index')->with('error', $response['message']);
        }
    }

    /**
     * Update the specified user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!hasPermission('USERS_LIST', 'update')) {
            return view('admin/401');
        }
        $url = $request->only('redirects_to');
        $requestData = $request->all();

        $rules = [
            'mobile' => 'required',
        ];

        $customMessages = [];

        $niceNames = array();

        $this->validate($request, $rules, $customMessages, $niceNames);

        $response = User::updateUser($requestData);

        // user activity log
        $activityLog['request'] =  $requestData;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] =  $response;
        ActiveLog::createBackendActiveLog($activityLog);

        //send password notification logic
        if (isset($requestData['password']) && $requestData['password'] != "") {
            $password = $requestData['password'];
            $fname = $requestData['fname'];
            $mobile = $requestData['mobile'] ?? "";
            $email = $requestData['email'];
        }

        if (!empty($response['data'])) {
            return redirect()->to($url['redirects_to'])->with('success', $response['message']);
        } else {
            return redirect()->to($url['redirects_to'])->with('error', $response['message']);
        }
    }

    /**
     * Check user mobile exist in user.
     *
     * @return \Illuminate\Http\Request
     */
    public function checkAdminUserExist(Request $request)
    {
  
        $IsdCodeLength = Country::where('isd_code', request()->input('isd_code'))->value('max_mobile_number_length');
        $mobileLength = strlen(request()->input('mobile'));
  
        if ($IsdCodeLength != $mobileLength && $IsdCodeLength != 0) {
            $response['valid'] = false;
            $response['message'] = "Mobile Number should contain atleast " . $IsdCodeLength . " numbers";
        } elseif (request()->input('mobile') && request()->input('mobile') != "") {
            if (request()->input('admin_user_id')) {
                $userListData = User::where('mobile', request()->input('mobile'))->where('status', '!=', 2)->where('id', '!=', request()->input('admin_user_id'))->get()->toArray();
            } else {
                $userListData = User::where('mobile', request()->input('mobile'))->where('status', '!=', 2)->get()->toArray();
            }
            if (!empty($userListData)) {
                $response['valid'] = false;
                $response['message'] = "Mobile Number is already taken";
            } else {
                $response['valid'] = true;
            }
        }
        return response()->json($response);
    }

      /**
     * Remove the specified user from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteUser(Request $request)
    {

        $url = URL::previous();

        if (!hasPermission('USERS_LIST', 'delete')) {
            return view('admin/401');
        }
        $userIDs = explode(',', $request->input('admin_user_id'));

        $message = "";
        foreach ($userIDs as $user_id) {
            $response = User::deleteUser($user_id);

            // user activity log
            $activityLog['request'] =  $request->all();
            $activityLog['request_url'] =  request()->url();
            $activityLog['response'] =  $response;
            ActiveLog::createBackendActiveLog($activityLog);
            
            $message .= $response['message'] . '</br>';
        }

        if ($response['status'] == 1) {
            return redirect()->to($url)->with('success', $message);
        } else {
            return redirect()->to($url)->with('error', $response['message']);
        }
    }

      /**
     * Check email agency user exist in user.
     *
     * @return \Illuminate\Http\Request
     */
    public function checkEmailAgencyUserExist(Request $request)
    {
        if (request()->input('email') && request()->input('email') != "") {
            if (request()->input('admin_user_id')) {
                $userListData = User::where('email', request()->input('email'))->where('status', '!=', 2)->where('id', '!=', request()->input('admin_user_id'))->get()->toArray();
            } else {
                $userListData = User::where('email', request()->input('email'))->where('status', '!=', 2)->get()->toArray();
            }
        }

        if (!empty($userListData)) {
            echo "false";
        } else {
            echo "true";
        }
    }

    /* Send email To inactive user */
    public function sendActivationEmail($id)
    {
        $isMail = Setting::select('value')->where('config_key', '=', 'mail|smtp|server')->first();
        $siteEmail = count(Setting::where('config_key', 'general|basic|siteEmail')->get('value')) > 0 ? Setting::where('config_key', 'general|basic|siteEmail')->get('value')[0]['value'] : "";
     
        //get user details
        $userData = User::where('id', $id)->get()->toArray();
     
        if (isset($isMail) && $isMail->value == '0') {
            return redirect()->route('agency.show', $id)->with('success', 'SMTP Setting Not Found!');
        } else {

            $code = 'USER_SIGNUP';
            $getTemplateData = EmailService::userSignUpMailTemplate($code, $userData[0]);
            if ($getTemplateData['status'] == 'false') {
                return back()->with('error', $getTemplateData['error']);
            } else {
                $subject = $getTemplateData['data']['subject'];
                $mailData = $getTemplateData['data']['mailData'];
                $toEmail = $userData[0]['email'];
                $files = [];

                // set data in sendEmail function
                $data = $this->sendEmail($toEmail, $subject, $mailData, $files, $getTemplateData['agencyName']);
                if ($data['status'] == 'false') {
                    return back()->with('error', $data['error']);
                } else {
                    return redirect()->back()->with('success', 'An email has been sent to your registered email address.');
                }
            }
        }
    }

    /**
     * Display a listing of the api users.
     *
     * @return \Illuminate\Http\Response
     */
    public function apiUsers()
    {
        if (!hasPermission('API_USERS', 'read')) {
            return view('admin/401');
        }
        $header['title'] = 'API Users';
        $header['heading'] = 'API Users';

        $queryStringConcat = '?';
        if (isset($_GET['per_page'])) {
            $queryStringConcat .= ($queryStringConcat == '') ? '?per_page=' . $_GET['per_page'] : '&per_page=' . $_GET['per_page'];
        }
        if (isset($_GET['page'])) {
            $queryStringConcat .= ($queryStringConcat == '') ? '?page=' . $_GET['page'] : '&page=' . $_GET['page'];
        }

        $filter = array(
            'per_page' => (request()->input('per_page') != NULL) ? request()->input('per_page') : Setting::where('config_key', 'general|setting|pagePerAdminRecords')->get('value')[0]['value'],
            'order_by' => (request()->input('order_by') != NULL) ? request()->input('order_by') : 'created_at',
            'sorting' => (request()->input('sorting') != NULL) ? request()->input('sorting') : 'desc',
        );
        $apiUserListData = ApiLogin::getApiUserDetail($filter);
        $apiUserCount = ApiLogin::count();
        $apiUserData = $apiUserListData['data'];

        if ($apiUserListData['status'] == 1) {
            return view('admin/api-users/index')->with(['header' => $header, 'apiUserData' => $apiUserData,'apiUserCount'=>$apiUserCount,'queryStringConcat' => $queryStringConcat, 'appliedFilter' => $filter, 'i' => (request()->input('page', 1) - 1) * $filter['per_page']]);
        } else {
            return view('admin/api-users/index')->with(['error' => $apiUserListData['message'], 'header' => $header, 'apiUserData' => $apiUserData,'apiUserCount'=>$apiUserCount,'queryStringConcat' => $queryStringConcat, 'appliedFilter' => $filter, 'i' => (request()->input('page', 1) - 1) * $filter['per_page']]);
        }
    }
}
