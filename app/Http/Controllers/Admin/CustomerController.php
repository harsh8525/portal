<?php

/**
 * @package     Settings
 * @subpackage  Customers 
 * @Author      Amar Technolabs Pvt. ltd(info@amarinfotech.com)
 * @Copyright(C) 2023 [NAME OF THE ORGANISATION THAT ON BEHALF OF THE CODE WE ARE WORKING].
 * @Version 1.0.0
 * module of the Customers.
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repository\UserInterfaceRepo;
use App\Models\Customer;
use App\Models\CustomerAddresses;
use App\Models\Country;
use App\Models\CountryI18ns;
use App\Models\Setting;
use App\Models\Agency;
use App\Models\City;
use App\Traits\ActiveLog;
use App\Models\State;
use App\Models\GeoRegionCoordinateLists;
use Illuminate\Support\Facades\URL;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Validation\Rule;
use App\Traits\EmailService;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{

    use EmailService;

    private UserInterfaceRepo $userRepo;

    public function __construct(UserInterfaceRepo $userInterfaceRepo)
    {
        $this->userRepo = $userInterfaceRepo;
    }

    /**
     * Display a listing of the customers.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        if (!hasPermission('CUSTOMERS_LIST', 'read')) {
            return view('admin/401');
        }
        
        $header['title'] = "Customers";
        $header['heading'] = "Customers";
        
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
            'mobile_verified' => (request()->input('mobile_verified') != NULL) ? request()->input('mobile_verified') : '',
            'status' => (request()->input('status') != NULL) ? request()->input('status') : '',
        );
        if (request()->input('full_name') != NULL) {
            $fullName = request()->input('full_name');
            $filter['where'][] = [
                DB::raw("CONCAT(first_name, ' ', last_name)"),
                'LIKE',
                '%' . $fullName . '%'
            ];
        }
        if (request()->input('first_name') != NULL) {
            $filter['where'][] = ['customers.first_name', 'like', '%' . request()->input('first_name') . '%'];
        }
        if (request()->input('last_name') != NULL) {
            $filter['where'][] = ['customers.last_name', 'like', '%' . request()->input('last_name') . '%'];
        }
        if (request()->input('mobile') != NULL) {
            $filter['where'][] = ['customers.mobile', 'like', '%' . request()->input('mobile') . '%'];
        }
        
        if (request()->input('email') != NULL) {
            $filter['where'][] = ['customers.email', 'like', '%' . request()->input('email') . '%'];
        }
        if (request()->input('status') != NULL) {
            $filter['where'][] = ['customers.status', '=', request()->input('status')];
        }
        $customerListData = Customer::getCustomers($filter);
        $customerDataCount = Customer::count();
        $customerData = $customerListData['data'];
        
        $activityLog['request'] =  request()->input();
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] =  $customerData;
        ActiveLog::createBackendActiveLog($activityLog);
        
        if ($customerListData['status'] == 1) {
            return view('admin/customers/index')->with(['header' => $header, 'customerData' => $customerData, 'customerDataCount' => $customerDataCount, 'queryStringConcat' => $queryStringConcat, 'appliedFilter' => $filter, 'i' => (request()->input('page', 1) - 1) * $filter['per_page']]);
        } else {
            return view('admin/customers/index')->with(['error' => $customerListData['message'], 'header' => $header, 'customerData' => $customerData, 'customerDataCount' => $customerDataCount, 'queryStringConcat' => $queryStringConcat, 'appliedFilter' => $filter, 'i' => (request()->input('page', 1) - 1) * $filter['per_page']]);
        }
    }
    
    public function create()
    {
        if (!hasPermission('CUSTOMERS_LIST', 'create')) {
            return view('admin/401');
        }

        $header['title'] = @trans('customers.addCustomers');
        $customers = Customer::where('status', 1)->get()->toArray();
        $getCountry = Country::get();

        $activityLog['request'] =  [];
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] =  [];
        ActiveLog::createBackendActiveLog($activityLog);

        return view('admin/customers/add')->with(['header' => $header, 'customers' => $customers, 'getCountry' => $getCountry]);
    }

    /**
     * Store a newly created customers in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!hasPermission('CUSTOMERS_LIST', 'create')) {
            return view('admin/401');
        }


        $requestData = $request->all();
        $rules = [
            'mobile' => 'required',
        ];

        $customMessages = [];

        $niceNames = array();

        $this->validate($request, $rules, $customMessages, $niceNames);
        $requestData['country'] = Country::where('iso_code', $request->country_name)->value('id');
        $response = Customer::createCustomers($requestData);

        // customer log
        $activityLog['request'] =  $requestData;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] =  $response;
        ActiveLog::createBackendActiveLog($activityLog);

        if (!empty($response['data'])) {

            $customerDetail = Customer::where('id', $response['data']['id'])->get()->toArray();
            $agencyName = Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'];
            $language_code = Setting::where('config_key', 'general|site|defaultLanguageCode')->get('value')[0]['value'];
            $agencyLogo = Setting::where('config_key', 'general|basic|colorLogo')->get('value')[0]['value'] ?? Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'];
            $token = Str::random(60);
            $updatePassword = DB::table('customer_activation_tokens')
                ->where(['email' => $customerDetail[0]['email']])
                ->first();
            if (!$updatePassword) {
                \DB::table('customer_activation_tokens')->insert(
                    ['email' => $customerDetail[0]['email'], 'token' => $token, 'created_at' => Carbon::now()]
                );
            } else {
                DB::table('customer_activation_tokens')->where(['email' => $customerDetail[0]['email']])->update(
                    ['token' => $updatePassword->token]
                );
                $token = $updatePassword->token;
            }

            $code = 'CUSTOMER_SIGN_UP';
            $siteName = count(Setting::where('config_key', 'general|basic|siteName')->get('value')) > 0 ? Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'] : "Rehlte";
            $customerName = ucwords($customerDetail[0]['first_name']);
            $customerEmail = ucwords($customerDetail[0]['email']);

            $link = 'http://admin.' . config('app.url') . '/customer-reset-password/' . $token;

            $data = array(
                'first_name' => $customerName,
                'site_name' => $siteName,
                'agency_name' => $agencyName,
                'agency_logo' => $agencyLogo,
                'email' => $customerEmail,
                'activation_link' => $link
            );

            $user = [
                'agency_logo' => $agencyLogo,
                'customer_name' => 'customer',
                'agency_name' => $siteName
            ];

            $welcomeAgencyCode = 'WELCOME_AGENCY';
            $getWelcomeAgencyTemplateData = EmailService::customerWelcomeMailTemplete($welcomeAgencyCode, $user, $language_code);

            if ($getWelcomeAgencyTemplateData['status'] == 'false') {
                return back()->with('error', $getWelcomeAgencyTemplateData['error']);
            } else {
                $welcomeMailsubject = $getWelcomeAgencyTemplateData['data']['subject'];
                $welcomeMailData = $getWelcomeAgencyTemplateData['data']['mailData'];
                $welcomeAgencyToEmail = $customerDetail[0]['email'];
                $files = [];

                // set data in sendEmail function
                $this->sendEmail($welcomeAgencyToEmail, $welcomeMailsubject, $welcomeMailData, $files, $siteName);
            }

            
            $getCustomerSignUp = $this->customerSignUp($code, $data, $language_code);

            $mailData = $getCustomerSignUp['data']['mailData'];
            $subject = $getCustomerSignUp['data']['subject'];
            $mailData = $getCustomerSignUp['data']['mailData'];
            $toEmail = $customerDetail[0]['email'];
            $files = [];

            // set data in sendEmail function
            $data = $this->sendEmail($toEmail, $subject, $mailData, $files, $siteName, $code);
            
            return redirect()->route('customers.index')->with('success', $response['message']);
        } else {
            return redirect()->back()->with('error', $response['message']);
        }
    }

    /**
     * customers active account method.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function customerActiveAccount(Request $request, $id)
    {
        if (!hasPermission('CUSTOMERS_LIST', 'create')) {
            return view('admin/401');
        }

        $customMessages = [];

        $niceNames = array();

        $customerDetail = Customer::where('id', $id)->get()->toArray();
        $agencyName = Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'];
        $language_code = Setting::where('config_key', 'general|site|defaultLanguageCode')->get('value')[0]['value'];
        $agencyLogo = Setting::where('config_key', 'general|basic|colorLogo')->get('value')[0]['value'] ?? Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'];
        $token = Str::random(60);
        $updatePassword = DB::table('customer_activation_tokens')
            ->where(['email' => $customerDetail[0]['email']])
            ->first();
        if (!$updatePassword) {
            \DB::table('customer_activation_tokens')->insert(
                ['email' => $customerDetail[0]['email'], 'token' => $token, 'created_at' => Carbon::now()]
            );
        } else {
            DB::table('customer_activation_tokens')->where(['email' => $customerDetail[0]['email']])->update(
                ['token' => $updatePassword->token]
            );
            $token = $updatePassword->token;
        }

        $code = 'CUSTOMER_SIGN_UP';
        $siteName = count(Setting::where('config_key', 'general|basic|siteName')->get('value')) > 0 ? Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'] : "Amar Infotech";
        $customerName = ucwords($customerDetail[0]['first_name']);
        $customerEmail = ucwords($customerDetail[0]['email']);

        $link = 'http://admin.' . config('app.url') . '/customer-reset-password/' . $token;

        $data = array(
            'first_name' => $customerName,
            'site_name' => $siteName,
            'agency_name' => $agencyName,
            'agency_logo' => $agencyLogo,
            'email' => $customerEmail,
            'activation_link' => $link
        );

        $getCustomerSignUp = $this->customerSignUp($code, $data, $language_code);

        $mailData = $getCustomerSignUp['data']['mailData'];
        $subject = $getCustomerSignUp['data']['subject'];
        $mailData = $getCustomerSignUp['data']['mailData'];
        $toEmail = $customerDetail[0]['email'];
        $files = [];

        // set data in sendEmail function
        $data = $this->sendEmail($toEmail, $subject, $mailData, $files, $siteName, $code);
        return redirect()->route('customers.index')->with('success', 'Actiovation account ['.$customerDetail[0]['first_name'].' '.$customerDetail[0]['last_name'].'] mail send successfuly.');
       
    }

    /**
     *  Customers reset password form.
     *
     */
    public function getCustomerResetPassword(Request $request, $token)
    {

        //get user's mobile number using email address from the table
        if(!filter_var($token, FILTER_VALIDATE_EMAIL))
        {
            $getEmail = DB::table('customer_activation_tokens')->where('token',$token)->value('email');
            $email = Customer::where('email',$getEmail)->value('email');
        }
        else{
            $email = Customer::where('email',$token)->value('email');
        }
        
        $header['title'] = @trans('resetPassword.title');
        return view('admin/auth/customer-reset-password')->with(['header' => $header, 'email' => $email]);
    }

    /**
     *  Updated to customers password method.
     *
     */
    public function action_customer_reset_password(Request $request)
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
         
            if ($request->email) {
                $customerData = Customer::where('email',$request->email)->first()->toArray();           
                $data = [
                    'password' => Hash::make($requestData['password']),
                    'status' => '1'
                ];
                $successMail = Customer::where('id', $customerData['id'])->update($data);
      
                if ($successMail) {
                    $userData = Customer::where('email', $request->email)->first()->toArray();
                
                    $code = 'USER_ACCOUNT_ACTIVATION';

                    $getTemplateData = $this->userAccountActivationMailTemplate($code, $userData);
                    $subject = $getTemplateData['data']['subject'];
                    $mailData = $getTemplateData['data']['mailData'];
                    $toEmail = $request->email;
                    $files = [];
                    
                    // set data in sendEmail function
                    $data = $this->sendEmail($toEmail, $subject, $mailData, $files, $getTemplateData['agencyName']);
                 
                    DB::table('customer_activation_tokens')->where('email',$customerData['email'])->delete();
                    return redirect()->route('admin.customer-updated-password')->with('success', 'Your Password Saved Successfully');
                }
            } else {
                return redirect()->back()->with('error', 'Link is Expired');
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong' . $e->getMessage());
        }
    }

    /**
     *  Display Customers updated password method.
     *
     */
    public function getCustomerUpdatedResetPassword(Request $request)
    {
        $header['title'] = @trans('resetPassword.title');
        return view('admin/auth/customer-updated-password')->with(['header' => $header]);
    }

    /**
     * Display the specified customers.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!hasPermission('CUSTOMERS_LIST', 'read')) {
            return view('admin/401');
        }
        $header['title'] = @trans('customers.viewCustomers');
        $header['heading'] = @trans('customers.moduleHeading');
        $filter = array(
            'id' => $id
        );
        $response = Customer::getCustomers($filter);
        $getCountry = Country::get();
        $customerDetail = $response['data'];
        // customer log
        $activityLog['request'] =  $id;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] =  $response;
        ActiveLog::createBackendActiveLog($activityLog);

        if ($response['status'] == 1 && !empty($response['data'])) {
            return view('admin/customers/view')->with(['header' => $header, 'customerDetail' => $customerDetail, 'getCountry' => $getCountry]);
        } else {
            return redirect()->route('customers.index')->with('error', $response['message']);
        }
    }

    /**
     * Show the form for editing the specified customers.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!hasPermission('CUSTOMERS_LIST', 'update')) {
            return view('admin/401');
        }
        $header['title'] = 'Customer - Edit';
        $header['heading'] = @trans('customers.moduleHeading');
        $header['method'] = 'Edit';

        $filter = array(
            'id' => $id
        );
        $getIsdCode = Country::get();
        $response = Customer::getCustomers($filter);
        $customerDetail = $response['data'];

        $customerAddress = CustomerAddresses::where('customer_id', $id)->first();

        $activityLog['request'] =  $id;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] =  $response;
        ActiveLog::createBackendActiveLog($activityLog);


        $getCities = [];
        $getStates = [];
        if ($customerAddress) {
            if ($customerAddress['country']) {
                $checkCountrySoftDeletedData = Country::withTrashed()->with('countryCode')->where('id', $customerAddress['country'])->where('status', 'active')->get()->first();
            } else {
                $checkCountrySoftDeletedData = Country::withTrashed()->with('countryCode')->where('status', 'active')->get()->first();
            }

            if ($checkCountrySoftDeletedData) {
                $getCities = City::with('cityCode')->where('country_code', $checkCountrySoftDeletedData['iso_code'])->where('status', 'active')->get()->toArray();
                $getStates = State::with('stateName')->where('country_code', $checkCountrySoftDeletedData['iso_code'])->where('status', 'active')->get()->toArray();
            } else {
                $getCities = City::with('cityCode')->where('status', 'active')->get()->toArray();
                $getStates = State::with('stateName')->where('status', 'active')->get()->toArray();
            }
        }

        if ($response['status'] == 1 && !empty($response['data'])) {
            return view('admin/customers/update')->with(['header' => $header, 'customerDetail' => $customerDetail, 'customerAddress' => $customerAddress, 'getIsdCode' => $getIsdCode, 'getCities' => $getCities, 'getStates' => $getStates]);
        } else {
            return redirect()->route('customers.index')->with('error', $response['message']);
        }
    }

    /**
     * Update the specified customers in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!hasPermission('CUSTOMERS_LIST', 'update')) {
            return view('admin/401');
        }
        $url = $request->redirects_to;
        $requestData = $request->all();
        $requestData['country'] = Country::where('iso_code', $request->country)->value('id');

        $rules = [
            'profile_photo' => 'max:1048',
        ];
        $customMessages = [];

        $niceNames = array();

        $this->validate($request, $rules, $customMessages, $niceNames);

        $response = Customer::updateCustomer($requestData);

        $activityLog['request'] =  $request->all();
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] =  $response;
        ActiveLog::createBackendActiveLog($activityLog);

        if (!empty($response['data'])) {
            return redirect()->to($url)->with('success', $response['message']);
        } else {
            return redirect()->to($url)->with('error', $response['message']);
        }
    }

    /**
     * Check user exist from customer database.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function checkUserExist(Request $request)
    {

        if (request()->input('email') && request()->input('email') != "") {
            if (request()->input('app_user_id')) {
                $userListData = Customer::where('email', request()->input('email'))->where('status', '!=', 'deleted')->where('id', '!=', request()->input('app_user_id'))->get()->toArray();
            } else {
                $userListData = Customer::where('email', request()->input('email'))->where('status', '!=', 'deleted')->get()->toArray();
            }
        }

        if (!empty($userListData)) {
            echo "false";
        } else {
            echo "true";
        }
    }

    /**
     * Check email user exist from customer database.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function checkEmailUserExist(Request $request)
    {
        if (request()->input('email') && request()->input('email') != "") {
            if (request()->input('customer_id')) {
                $userListData = Customer::where('email', request()->input('email'))->where('status', '!=', 0)->where('status', '!=', 'deleted')->where('id', '!=', request()->input('customer_id'))->get()->toArray();
            } else {
                $userListData = Customer::where('email', request()->input('email'))->where('status', '!=', 0)->where('status', '!=', 'deleted')->get()->toArray();
            }
        }

        if (!empty($userListData)) {
            echo "false";
        } else {
            echo "true";
        }
    }

    /**
     * Check customer mobile exist from customer database.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function checkCustomerMobileExist(Request $request)
    {

        $mobile = request()->input('isd_code') . " " . request()->input('mobile');
        $isd_code =  request()->input('isd_code');
        $IsdCodeLength = Country::where('isd_code', request()->input('isd_code'))->value('max_mobile_number_length');
        $mobileLength = strlen(request()->input('mobile'));
        if ($IsdCodeLength != $mobileLength && $IsdCodeLength != 0) {
            $response['valid'] = false;
            $response['message'] = "Mobile number should contain only " . $IsdCodeLength . " digit";
        } elseif (request()->input('mobile') && request()->input('mobile') != "") {
            if (request()->input('customer_id')) {
                $customerListData = Customer::where('mobile', $mobile)->where('status', '!=', 'deleted')->where('id', '!=', request()->input('customer_id'))->first();
            } else {
                $customerListData = Customer::where('mobile', $mobile)->where('status', '!=', 'deleted')->first();
            }
            if (!empty($customerListData)) {
                $response['valid'] = false;
                $response['message'] = "Mobile Number is already taken";
            } else {
                $response['valid'] = true;
            }
        }
        return response()->json($response);
    }

    /**
     *  Remove the specified customers from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteCustomers(Request $request)
    {
        if (!hasPermission('CUSTOMERS_LIST', 'delete')) {
            return view('admin/401');
        }
        $url = URL::previous();
        $customerIDs = explode(',', $request->input('customer_id'));
        $message = "";
        foreach ($customerIDs as $customer_id) {
            $response = Customer::deleteCustomers($customer_id);
            $message .= $response['message'] . '</br>';
            
            $activityLog['request'] =  $request->all();
            $activityLog['request_url'] =  request()->url();
            $activityLog['response'] =  $response;
            ActiveLog::createBackendActiveLog($activityLog);
        }


        if ($response['status'] == 1) {
            return redirect()->to($url)->with('success', $message);
        } else {
            return redirect()->to($url)->with('error', $response['message']);
        }
    }

    /**
     * Generates an export of the user based on the provided request parameters.
     *
     * @return \Illuminate\Http\Request
     */
    public function export(Request $request)
    {

        $queryStringConcat = '?';
        $filter = array(
            'order_by' => (request()->input('order_by') != NULL) ? request()->input('order_by') : 'id',
            'sorting' => (request()->input('sorting') != NULL) ? request()->input('sorting') : 'asc',
            'mobile_verified' => (request()->input('mobile_verified') != NULL) ? request()->input('mobile_verified') : '',
            'status' => (request()->input('status') != NULL) ? request()->input('status') : '',

        );

        if (request()->input('mobile') != NULL) {
            $filter['where'][] = ['app_users.mobile', 'like', '%' . request()->input('mobile') . '%'];
        }

        if (request()->input('email') != NULL) {
            $filter['where'][] = ['app_users.email', 'like', '%' . request()->input('email') . '%'];
        }

        if (request()->input('city') != NULL) {
            $filter['where'][] = ['app_user_addresses.city', 'like', '%' . request()->input('city') . '%'];
        }

        if (request()->input('state') != NULL) {
            $filter['where'][] = ['app_user_addresses.state', 'like', '%' . request()->input('state') . '%'];
        }

        if (request()->input('owner_name') != NULL) {
            $filter['where'][] = ['app_users.owner_name', 'like', '%' . request()->input('owner_name') . '%'];
        }
        if (request()->input('status') != NULL) {
            $filter['where'][] = ['app_users.status', '=', request()->input('status')];
        }
        $currentDate = date('d-m-Y');
        return Excel::download(new UsersExport($filter), 'users_' . $currentDate . '.xls');
    }
    public function cropImg()
    {
        $data = $_POST['image'];

        return response()->json([$data, 'success' => true]);
    }
}
