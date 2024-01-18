<?php

namespace App\Http\Controllers\Admin\OperationalData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repository\UserInterfaceRepo;
use App\Models\Customer;
use App\Models\Coupon;
use App\Models\CustomerAddresses;
use App\Models\Country;
use App\Models\CountryI18ns;
use App\Models\Setting;
use App\Models\Agency;
use App\Models\City;
use App\Models\State;
use App\Models\ServiceType;
use App\Models\GeoRegionCoordinateLists;
use Illuminate\Support\Facades\URL;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Validation\Rule;
use App\Traits\EmailService;
use App\Traits\ActiveLog;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CouponController extends Controller
{
    /**
     * Display a listing of the customers.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if (!hasPermission('COUPONS_LIST', 'read')) {
            return view('admin/401');
        }

        $header['title'] = "Coupons";
        $header['heading'] = "Coupons";

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
            'coupon_name' => (request()->input('coupon_name') != NULL) ? request()->input('coupon_name') : '',
            'coupon_code' => (request()->input('coupon_code') != NULL) ? request()->input('coupon_code') : '',
            'module_type' => (request()->input('module_type') != NULL) ? request()->input('module_type') : '',
            'service_type' => (request()->input('service_type') != NULL) ? request()->input('service_type') : '',
        );
        if (request()->input('coupon_name') != NULL) {
            $filter['whereHas'][] = ['coupon_name', 'like', '%' . request()->input('coupon_name') . '%'];
        }
        if (request()->input('coupon_code') != NULL) {
            $filter['where'][] = ['coupon_code', 'like', '%' . request()->input('coupon_code') . '%'];
        }
        if (request()->input('mobile') != NULL) {
            $filter['where'][] = ['mobile', 'like', '%' . request()->input('mobile') . '%'];
        }

        if (request()->input('email') != NULL) {
            $filter['where'][] = ['email', 'like', '%' . request()->input('email') . '%'];
        }
        if (request()->input('module_type') != NULL) {
            $filter['where'][] = ['customer_type', '=', request()->input('module_type')];
        }
        if (request()->input('service_type') != NULL) {
            $filter['where'][] = ['service_type_id', '=', request()->input('service_type')];
        }
        // echo "<pre>";print_r($filter);die;
        $customerListData = Coupon::getCoupons($filter);
        $couponDataCount = Customer::count();
        $couponData = $customerListData['data'];

        $activityLog['request'] =  $filter;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] =  $customerListData;
        ActiveLog::createBackendActiveLog($activityLog);

        if ($customerListData['status'] == 1) {
            return view('admin/OperationalData/coupons/index')->with(['header' => $header, 'couponData' => $couponData, 'couponDataCount' => $couponDataCount, 'queryStringConcat' => $queryStringConcat, 'appliedFilter' => $filter, 'i' => (request()->input('page', 1) - 1) * $filter['per_page']]);
        } else {
            return view('admin/OperationalData/coupons/index')->with(['error' => $customerListData['message'], 'header' => $header, 'couponData' => $couponData, 'couponDataCount' => $couponDataCount, 'queryStringConcat' => $queryStringConcat, 'appliedFilter' => $filter, 'i' => (request()->input('page', 1) - 1) * $filter['per_page']]);
        }
    }



    public function create()
    {
        if (!hasPermission('COUPONS_LIST', 'create')) {
            return view('admin/401');
        }

        $header['title'] = @trans('coupons.addCoupons');
        $customers = Customer::where('status', 1)->get()->toArray();
        $serviceType = ServiceType::where('is_active', 1)->get()->toArray();
        $getCountry = Country::get();
        $getAgency = Agency::where('status', 'active')->get()->toArray();

        $activityLog['request'] = [];
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] =  [];
        ActiveLog::createBackendActiveLog($activityLog);

        return view('admin/OperationalData/coupons/add')->with(['header' => $header, 'customers' => $customers, 'serviceType' => $serviceType, 'getAgency' => $getAgency]);
    }

    /**
     * Store a newly created customers in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!hasPermission('COUPONS_LIST', 'create')) {
            return view('admin/401');
        }


        $requestData = $request->all();
        $requestData['country'] = Country::where('iso_code', $request->country_name)->value('id');
        $response = Coupon::createCoupon($requestData);

        $activityLog['request'] =  $requestData;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] =  $response;
        ActiveLog::createBackendActiveLog($activityLog);

        if (!empty($response['data'])) {
            return redirect()->route('coupons.index')->with('success', $response['message']);
        } else {
            return redirect()->route('coupons.index')->with('error', $response['message']);
        }
    }

    /**
     * Display the specified coupons.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!hasPermission('COUPONS_LIST', 'read')) {
            return view('admin/401');
        }
        $header['title'] = @trans('coupons.viewCoupons');
        $header['heading'] = @trans('coupons.moduleHeading');
        $filter = array(
            'id' => $id
        );
        $response = Coupon::getCoupons($filter);
        $couponDetail = $response['data'];

        $activityLog['request'] =  $filter;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] =  $response;
        ActiveLog::createBackendActiveLog($activityLog);

        if ($response['status'] == 1 && !empty($response['data'])) {
            return view('admin/OperationalData/coupons/view')->with(['header' => $header, 'couponDetail' => $couponDetail]);
        } else {
            return redirect()->route('coupons.index')->with('error', $response['message']);
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
        if (!hasPermission('COUPONS_LIST', 'update')) {
            return view('admin/401');
        }
        $header['title'] = 'Coupon - Edit';
        $header['heading'] = @trans('customers.moduleHeading');
        $header['method'] = 'Edit';

        $filter = array(
            'id' => $id
        );
        $customers = Customer::where('status', 1)->get()->toArray();
        $response = Coupon::getCoupons($filter);
        $couponDetail = $response['data'];
        $serviceTypeData = ServiceType::where('is_active', 1)->get()->toArray();

        $CustArray = [];
        $applicableCustArray = [];
        if ($couponDetail->customer_type == 'B2C') {
            foreach ($couponDetail->applicableCustomer as $custId) {
                array_push($CustArray, $custId['customer_id']);
                array_push($applicableCustArray, $custId['id']);
            }
        }
        $getAgency = Agency::where('status', 'active')->get()->toArray();

        $agencyArray = [];
        $applicableAgencyArray = [];
        if ($couponDetail['customer_type'] == 'B2B') {
            foreach ($couponDetail->applicableCustomer as $agencyId) {
                array_push($CustArray, $agencyId['agency_id']);
                array_push($applicableCustArray, $agencyId['id']);
            }
        }

        $activityLog['request'] =  $filter;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] =  $response;
        ActiveLog::createBackendActiveLog($activityLog);

        if ($response['status'] == 1 && !empty($couponDetail)) {
            return view('admin/OperationalData/coupons/update')->with(['header' => $header, 'applicableAgencyArray' => $applicableAgencyArray, 'agencyArray' => $agencyArray, 'couponDetail' => $couponDetail, 'getAgency' => $getAgency, 'serviceTypeData' => $serviceTypeData, 'CustArray' => $CustArray, 'applicableCustArray' => $applicableCustArray, 'customers' => $customers]);
        } else {
            return redirect()->route('coupons.index')->with('error', $response['message']);
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
        if (!hasPermission('COUPONS_LIST', 'update')) {
            return view('admin/401');
        }
        $url = $request->redirects_to;
        $requestData = $request->all();
        $requestData['country'] = Country::where('iso_code', $request->country)->value('id');

        $rules = [];
        $customMessages = [];

        $niceNames = array();

        $this->validate($request, $rules, $customMessages, $niceNames);

        $response = Coupon::updateCoupon($requestData);

        $activityLog['request'] =  $requestData;
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
     * Check Coupon Code exist from coupon database.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public static function checkCouponCodeExist(Request $request)
    {

        $coupon_id = request()->input('coupon_id');
        if (request()->input('couponCode') && request()->input('couponCode') != "") {
            if (request()->input('coupon_id')) {
                $couponCode = Coupon::where('coupon_code', request()->input('couponCode'))->where('id', '!=', $coupon_id)->get()->toArray();
            } else {
                $couponCode = Coupon::where('coupon_code', request()->input('couponCode'))->get()->toArray();
            }
        }
        if (!empty($couponCode)) {
            echo "false";
        } else {
            echo "true";
        }
    }



    /**
     *  Remove the specified customers from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteCoupons(Request $request)
    {
        if (!hasPermission('COUPONS_LIST', 'delete')) {
            return view('admin/401');
        }
        $url = URL::previous();
        $couponIDs = explode(',', $request->input('coupon_id'));
        $message = "";
        foreach ($couponIDs as $coupon_id) {
            $response = Coupon::deleteCustomers($coupon_id);
            $message .= $response['message'] . '</br>';
        }

        $activityLog['request'] =  $request->all();
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] =  $response;
        ActiveLog::createBackendActiveLog($activityLog);

        if ($response['status'] == 1) {
            return redirect()->to($url)->with('success', $message);
        } else {
            return redirect()->to($url)->with('error', $response['message']);
        }
    }


    public function cropImg()
    {
        $data = $_POST['image'];

        return response()->json([$data, 'success' => true]);
    }
}
