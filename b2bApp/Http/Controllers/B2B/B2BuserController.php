<?php

namespace B2BApp\Http\Controllers\B2B;

use B2BApp\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\AppUsers;
use App\Models\User;
use App\Models\Agency;
use B2BApp\Traits\EmailService;
use App\Models\GeoCountryLists;
use App\Models\Role;
use App\Models\Module;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use DB;
use Carbon\Carbon;

class B2BuserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // echo Auth::id();die;
        // echo "hello user";die;
        if (!hasPermission('USERS', 'read')) {
            return view('b2b/401');
        }
        $header['title'] = @trans('adminUser.title');
        $header['heading'] = @trans('adminUser.moduleHeading');

        $queryStringConcat = '?';
        // print_r($_GET);die;
        if (isset($_GET['per_page'])) {
            $queryStringConcat .= ($queryStringConcat == '') ? '?per_page=' . $_GET['per_page'] : '&per_page=' . $_GET['per_page'];
        }
        if (isset($_GET['page'])) {
            $queryStringConcat .= ($queryStringConcat == '') ? '?page=' . $_GET['page'] : '&page=' . $_GET['page'];
        }
        $filter = array(
            'per_page' => (request()->input('per_page') != NULL) ? request()->input('per_page') : Setting::where('config_key', 'general|setting|pagePerAdminRecords')->get('value')[0]['value'],
            'order_by' => (request()->input('order_by') != NULL) ? request()->input('order_by') : 'id',
            'sorting' => (request()->input('sorting') != NULL) ? request()->input('sorting') : 'desc',
            // 'mobile_verified' => (request()->input('mobile_verified') != NULL) ? request()->input('mobile_verified') : '',
            'status' => (request()->input('status') != NULL) ? request()->input('status') : '',
            'name' => (request()->input('name') != NULL) ? request()->input('name') : '',
            'mobile' => (request()->input('mobile') != NULL) ? request()->input('mobile') : '',
            'email' => (request()->input('email') != NULL) ? request()->input('email') : '',
            'user_role' => (request()->input('user_role') != NULL) ? request()->input('user_role') : '',
            'agancy_name' => (request()->input('agancy_name') != NULL) ? request()->input('agancy_name') : '',

        );
        // echo "<pre>"; print_r($filter);die;
        if (request()->input('name') != NULL) {
            $filter['where'][] = ['users.name', 'like', '%' . request()->input('name') . '%'];
        }
        if (request()->input('mobile') != NULL) {
            // echo "if";die;

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

        //if (request()->input('agancy_name') != NULL) {
        $filter['where'][] = ['users.agency_id', '=', Auth::guard('b2b')->user()->agency_id];
        //}
        // echo request()->input('status');die;
        if (request()->input('status') != NULL) {
            $filter['where'][] = ['users.status', '=', request()->input('status')];
        }

        $AdminUserListData = User::getAdminUsers($filter);
        $getAgencyName = Agency::get();

        $AdminUserData = $AdminUserListData['data'];
        // echo "<pre>"; print_r($AdminUserData);die;
        if ($AdminUserListData['status'] == 1) {
            return view('b2b/user/index')->with(['header' => $header, 'AdminUserData' => $AdminUserData, 'getAgencyName' => $getAgencyName, 'queryStringConcat' => $queryStringConcat, 'appliedFilter' => $filter, 'i' => (request()->input('page', 1) - 1) * $filter['per_page']]);
        } else {
            return view('b2b/user/index')->with(['error' => $AdminUserListData['message'], 'header' => $header, 'getAgencyName' => $getAgencyName, 'AdminUserData' => $AdminUserData, 'queryStringConcat' => $queryStringConcat, 'appliedFilter' => $filter, 'i' => (request()->input('page', 1) - 1) * $filter['per_page']]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!hasPermission('USERS', 'create')) {
            return view('b2b/401');
        }

        // if(!hasPermission('ADMIN_USERS','create')){
        //     return view('admin/401');
        // }
        $getAgencyName = Agency::get();
        $getIsdCode = GeoCountryLists::get();
        $getRole = Role::where('role_type', 'b2b')->get();
        $header['title'] = "User - Add";
        $header['heading'] = "User -";
        return view('b2b/user/add')->with(['header' => $header, 'getIsdCode' => $getIsdCode, 'getRole' => $getRole]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if (!hasPermission('USERS', 'create')) {
            return view('b2b/401');
        }
        $requestData = $request->only(['fname', 'mobile', 'email', 'profile_image', 'role', 'status', 'isd_code']);

        $rules = [
            'mobile' => 'required|max:10',
        ];

        $customMessages = [];

        $niceNames = array();

        $this->validate($request, $rules, $customMessages, $niceNames);

        //send password notification logic
        // if (isset($requestData['password']) && $requestData['password'] != "") {
        //AppUsers::sendPasswordNotification($requestData);

        $response = User::createUser($requestData);

        if (!empty($response['data'])) {
            return redirect()->route('b2b_user.user.index')->with('success', $response['message']);
        } else {
            return redirect()->route('b2b_user.user.index')->with('error', $response['message']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!hasPermission('USERS', 'read')) {
            return view('b2b/401');
        }
        $header['title'] = 'User - View';
        $header['heading'] = 'User - View';

        $filter = array(
            'id' => $id
        );
        $response = User::getAdminUsers($filter);
        $activityLog = User::getActivityList($filter);

        // echo "<pre>";
        // print_r($activityLog);
        // die;
        $userDetail = $response['data'];
        $userName = User::where('id', $activityLog['data']['id'])->value('name');

        $query = ActivityLog::query();
        $query->select(
            "activity_log.*",
        );
        $query->where('activity_log.subject_id', $id);
        $result = $query->get();


        if ($response['status'] == 1 && !empty($response['data'])) {
            return view('b2b/user/view')->with(['header' => $header, 'userDetail' => $userDetail, 'activityLog' => $activityLog, 'userName' => $userName, 'result' => $result]);
        } else {
            return redirect()->route('b2b_user.user.index')->with('error', $response['message']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!hasPermission('USERS', 'update')) {
            return view('b2b/401');
        }

        // if(!hasPermission('ADMIN_USERS','update')){
        //     return view('admin/401');
        // }
        $getIsdCode = GeoCountryLists::get();
        $header['title'] = 'Edit - User';
        $header['heading'] = 'Edit - User';

        $filter = array(
            'id' => $id
        );
        $response = User::getAdminUsers($filter);
        $userDetail = $response['data'];
        //$getUserRoleDaetail = User::select('id', 'name', 'app_name', 'role_code')->get()->toArray();
        $getRolNameDesc = '';

        $getRole = Role::where('role_type', 'b2b')->get();
        // echo "<pre>";print_r($userDetail);die;
        // echo "<pre>";print_r($getRole);die;
        if ($response['status'] == 1 && !empty($response['data'])) {
            //            echo "<pre>"; print_r($userDetail);die;
            return view('b2b/user/update')->with(['header' => $header, 'userDetail' => $userDetail, 'getIsdCode' => $getIsdCode, 'getRole' => $getRole]);
        } else {
            return redirect()->route('b2b_user.user.index')->with('error', $response['message']);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!hasPermission('USERS', 'update')) {
            return view('b2b/401');
        }
        $requestData = $request->only(['admin_user_id', 'fname', 'password', 'email', 'profile_image', 'old_profile_image', 'role', 'status', 'isd_code', 'mobile']);

        $rules = [
            'mobile' => 'required|max:10',
        ];

        $customMessages = [];

        $niceNames = array();

        $this->validate($request, $rules, $customMessages, $niceNames);

        $response = User::updateUser($requestData);

        //send password notification logic
        if (isset($requestData['password']) && $requestData['password'] != "") {
            //AppUsers::sendPasswordNotification($requestData);
            $password = $requestData['password'];
            $fname = $requestData['fname'];
            $mobile = $requestData['mobile'] ?? "";
            $email = $requestData['email'];

            // if ($email != "") {
            //     //send password and username(mobile) on mail
            //     $subject = "Login credentials for KOIL Application.";

            //     $mailData = "<html><table border=0>";
            //     $mailData .= "<tr><td><h1>Hello ". ucwords($fname).",</h1> </td></tr>";               
            //     $mailData .= "<tr><td><p>Please check bellow mentioned credentials to login into mobile application</p> </td></tr>";
            //     $mailData .= "<tr><td>username: " . $mobile . '<td/></tr>';
            //     $mailData .= "<tr><td>password: " . $password . '</td><tr/>';
            //     $mailData .= "</table></html>";

            //     $this->sendEmail($email, $subject, $mailData);
            // }

            // if ($mobile != "") {
            //     //send password and username(mobile) on SMS
            // }
        }


        if (!empty($response['data'])) {
            return redirect()->route('b2b_user.user.index')->with('success', $response['message']);
        } else {
            return redirect()->route('b2b_user.user.index')->with('error', $response['message']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function checkAdminUserExist(Request $request)
    {
        if (request()->input('mobile') && request()->input('mobile') != "") {
            if (request()->input('admin_user_id')) {
                $userListData = User::where('mobile', request()->input('mobile'))->where('status', '!=', 2)->where('id', '!=', request()->input('admin_user_id'))->get()->toArray();
            } else {
                $userListData = User::where('mobile', request()->input('mobile'))->where('status', '!=', 2)->get()->toArray();
            }
        }

        if (!empty($userListData)) {
            echo "false";
        } else {
            echo "true";
        }
    }

    public function deleteUser(Request $request)
    {

        if (!hasPermission('USERS', 'delete')) {
            return view('b2b/401');
        }
        // if(!hasPermission('ADMIN_USERS','delete')){
        //     return view('admin/401');
        // }
        $userIDs = explode(',', $request->input('admin_user_id'));

        $message = "";
        foreach ($userIDs as $user_id) {
            $response = User::deleteUser($user_id);
            $message .= $response['message'] . '</br>';
        }

        if ($response['status'] == 1) {
            return redirect()->route('b2b_user.user.index')->with('success', $message);
        } else {
            return redirect()->route('b2b_user.user.index')->with('error', $response['message']);
        }
    }
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

    public function sendActivationEmail($id)
    {

        $isMail = Setting::select('value')->where('config_key', '=', 'mail|smtp|server')->first();
        $siteEmail = count(Setting::where('config_key', 'general|basic|siteEmail')->get('value')) > 0 ? Setting::where('config_key', 'general|basic|siteEmail')->get('value')[0]['value'] : "";
        // $userData = User::select('users.*','agencies.full_name as agency_name')->join('agencies','agencies.id','users.agency_id')->where('users.id',$id)->get()->toArray();

        //get user details
        $userData = User::where('id', $id)->get()->toArray();
        if (isset($isMail) && $isMail->value == '0') {
            return redirect()->route('agency.show', $id)->with('success', 'SMTP Setting Not Found!');
        } else {

            // $agencyLogo = Setting::where('config_key', 'general|basic|colorLogo')->get('value')[0]['value'] ?? Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'];
            $code = 'USER_SIGNUP';
            // $siteName = count(Setting::where('config_key', 'general|basic|siteName')->get('value')) > 0 ? Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'] : "Amar Infotech";


            $getTemplateData = EmailService::userSignUpMailTemplate($code, $userData[0]);
            // echo "<pre>";
            // print_r($getTemplateData);
            // die;
            if ($getTemplateData['status'] == 'false') {
                return back()->with('error', $getTemplateData['error']);
            } else {
                $subject = $getTemplateData['data']['subject'];
                $mailData = $getTemplateData['data']['mailData'];
                $toEmail = $userData[0]['email'];
                $files = [];

                // set data in sendEmail function
                $data = EmailService::sendEmail($toEmail, $subject, $mailData, $files, $getTemplateData['agencyName']);
                if ($data['status'] == 'false') {
                    return back()->with('error', $data['error']);
                } else {

                    // return redirect()->route('user.show',$id)->with('success', 'An email has been sent to your registered email address.');
                    return redirect()->back()->with('success', 'An email has been sent to your registered email address.');
                }
            }
        }
    }
}
