<?php

/**
 * @package     Agenecy 
 * @subpackage  Agenecy 
 * @Author      Amar Technolabs Pvt. ltd(info@amarinfotech.com)
 * @Copyright(C) 2023 [NAME OF THE ORGANISATION THAT ON BEHALF OF THE CODE WE ARE WORKING].
 * @Version 1.0.0
 * module of the Agenecy.
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AgencyType;
use App\Models\ServiceType;
use App\Models\Agency;
use App\Models\AgencyAddress;
use App\Models\AgencyPaymentType;
use App\Models\AgencyServiceType;
use App\Models\PaymentGateway;
use App\Models\AgencyPaymentGateway;
use App\Models\Setting;
use App\Models\User;
use App\Models\Country;
use App\Models\AgencyCurrency;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Traits\EmailService;
use App\Traits\ActiveLog;
use Illuminate\Support\Facades\Hash;
use DB;

class AgencyController extends Controller
{
    use EmailService;
    /**
     * Display a listing of the agency.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!hasPermission('AGENCY', 'read')) {
            return view('admin/401');
        }
        $header['title'] = "Agencies";
        $header['heading'] = "Agencies";
        $queryStringConcat = '?';
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
            'status' => (request()->input('status') != NULL) ? request()->input('status') : '',
            'agency_type_id' => (request()->input('agency_type_id') != NULL) ? request()->input('agency_type_id') : '',

        );

        if (request()->input('agency_name') != NULL) {
            $filter['where'][] = ['full_name', 'like', '%' . request()->input('agency_name') . '%'];
        }
        if (request()->input('contact_name') != NULL) {
            $filter['where'][] = ['contact_person_name', 'like', '%' . request()->input('contact_name') . '%'];
        }
        if (request()->input('agency_id') != NULL) {
            $filter['where'][] = ['agency_id', '=', request()->input('agency_id')];
        }
        if (request()->input('agency_type_id') != NULL) {
            $filter['where'][] = ['core_agency_type_id', '=', request()->input('agency_type_id')];
        }

        if (request()->input('status') != NULL) {
            $filter['where'][] = ['status', '=', request()->input('status')];
        }
        $agencyDataList = Agency::getAgency($filter);
        $agencyDataCount = Agency::count();
        $agencyData = $agencyDataList['data'];

        $activityLog['request'] = $filter;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $agencyDataList;
        ActiveLog::createBackendActiveLog($activityLog);

        if ($agencyDataList['status'] == 1) {
            return view('admin/agency/index')->with(['header' => $header, 'agencyData' => $agencyData, 'agencyDataCount' => $agencyDataCount, 'queryStringConcat' => $queryStringConcat, 'appliedFilter' => $filter, 'i' => (request()->input('page', 1) - 1) * $filter['per_page']]);
        } else {
            return view('admin/agency/index')->with(['error' => $agencyDataList['message'], 'header' => $header, 'agencyData' => $agencyData, 'agencyDataCount' => $agencyDataCount, 'queryStringConcat' => $queryStringConcat, 'appliedFilter' => $filter, 'i' => (request()->input('page', 1) - 1) * $filter['per_page']]);
        }
    }

    /**
     * Show the form for creating a new agency.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!hasPermission('AGENCY', 'create')) {
            return view('admin/401');
        }

        $header['title'] = "Agency - Add";
        $header['heading'] = "Agency - Add";

        $agencyCreateData['agency_type'] = AgencyType::where('is_active', '1')->orderBy('name', 'asc')->get()->toArray();
        $agencyCreateData['payment_option'] = DB::table('core_payment_types')->where('is_active', '1')->orderBy('name', 'asc')->get()->toArray();
        $agencyCreateData['service_type'] = ServiceType::where('is_active', '1')->orderBy('name', 'asc')->get()->toArray();
        $agencyCreateData['payment_gateway'] = PaymentGateway::where('is_active', '1')->orderBy('name', 'asc')->get()->toArray();
        $agencyCreateData['getIsdCode'] = Country::with('countryCode')->get();

        $activityLog['request'] = [];
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = [];
        ActiveLog::createBackendActiveLog($activityLog);

        return view('admin/agency/add')->with(['header' => $header, 'agencyCreateData' => $agencyCreateData]);
    }

    /**
     * Store a newly created agency in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * 
     */
    public function store(Request $request)
    {
        if (!hasPermission('AGENCY', 'create')) {
            return view('admin/401');
        }

        $requestGeneralInfoData = $request->only([
            'agency_name', 'short_name', 'contact', 'position', 'email', 'license_number', 'agency_type_id', 'phone_no',
            'fax_no', 'web_url', 'status', 'agency_logo', 'search_only', 'stop_by', 'cancel_right', 'iata_number'
        ]);
        $requestAgencyOperatorDetails = $request->only(['operator_full_name', 'operator_email', 'isd_code', 'operator_mobile', 'agency_type_id']);
        $requestAgencyCurrencies = $request->only(['enable_currency_id']);
        $requestAgencyAddressData = $request->only(['address1', 'address2', 'city', 'state', 'country', 'zip_code']);
        $requestAgencyPaymentTypesData = $request->only(['payment_option']);
        $requestAgencyServiceTypesData = $request->only(['service_type']);
        $requestAgencyPaymenyGatewayData = $request->only(['payment_gateway']);

        $rules = [];

        $customMessages = [];

        $niceNames = array();

        $response = Agency::createAgency($requestGeneralInfoData);

        $agencyType = AgencyType::where('id', $response['data']['core_agency_type_id'])->value('code');

        if (!empty($response['data'])) {
            $agency_id = $response['data']['id'];

            if ($agencyType == 'B2B') {
                $responseAgencyPaymentOptions = AgencyPaymentType::createAgencyPaymentType($requestAgencyPaymentTypesData, $agency_id);
                $responseAgencyServiceTypes = AgencyServiceType::createAgencyServiceType($requestAgencyServiceTypesData, $agency_id);
                $responseAgencyPaymentGateway = AgencyPaymentGateway::createAgencyPaymentGateway($requestAgencyPaymenyGatewayData, $agency_id);
            }
            $responseAgencyCurrencies = AgencyCurrency::createAgencyCurrency($requestAgencyCurrencies, $agency_id);
            $responseAgencyAddress = AgencyAddress::createAgencyAddress($requestAgencyAddressData, $agency_id);
            $responseAgencyOperatorDetails = User::createAgencyUser($requestAgencyOperatorDetails, $agency_id);
        } else {
            return redirect()->route('agency.index')->with('error', $response['message']);
        }

        $activityLog['request'] = $requestGeneralInfoData;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $response;
        ActiveLog::createBackendActiveLog($activityLog);

        if (!empty($response['data'])) {

            return redirect()->route('agency.index')->with('success', $response['message']);
        } else {
            return redirect()->route('agency.index')->with('error', $response['message']);
        }
    }

    /**
     * Display the specified agency.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!hasPermission('AGENCY', 'read')) {
            return view('admin/401');
        }

        $header['title'] = "Agency - View";
        $header['heading'] = "Agency - View";

        $filter = array(
            'id' => $id
        );
        $response = Agency::getAgency($filter);
        $agencyDetail = $response['data'];

        $activityLog['request'] = $filter;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $response;
        ActiveLog::createBackendActiveLog($activityLog);

        if ($response['status'] == 1 && !empty($response['data'])) {
            return view('admin/agency/view')->with(['header' => $header, 'agencyDetails' => $agencyDetail]);
        } else {
            return redirect()->route('agency.index')->with('error', $response['message']);
        }
    }

    /**
     * Show the form for editing the specified agency.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!hasPermission('AGENCY', 'update')) {
            return view('admin/401');
        }

        $header['title'] = 'Agency - Edit';
        $header['method'] = 'Edit';
        $filter = array(
            'id' => $id,
        );
        $response = Agency::getAgency($filter);
        $agencyDetail = $response['data'];
        $agencyCreateData['agency_type'] = AgencyType::whereIn('is_active', ['1', '2'])->orderBy('name', 'asc')->get()->toArray();
        $agencyCreateData['payment_option'] = DB::table('core_payment_types')->where('is_active', '1')->orderBy('name', 'asc')->get()->toArray();
        $agencyCreateData['service_type'] = ServiceType::where('is_active', '1')->orderBy('name', 'asc')->get()->toArray();
        $agencyCreateData['payment_gateway'] = PaymentGateway::where('is_active', '1')->orderBy('name', 'asc')->get()->toArray();

        $activityLog['request'] = $filter;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $response;
        ActiveLog::createBackendActiveLog($activityLog);

        if ($response['status'] == 1 && !empty($response['data'])) {

            return view('admin/agency/update')->with(['header' => $header, 'agencyDetail' => $agencyDetail, 'agencyCreateData' => $agencyCreateData]);
        } else {
            return redirect()->route('agency.index')->with('error', $response['message']);
        }
    }

    /**
     * Update the specified agency in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * 
     */
    public function update(Request $request, $id)
    {
        if (!hasPermission('AGENCY', 'update')) {
            return view('admin/401');
        }

        $url = $request->only('redirects_to');

        $requestGeneralInfoData = $request->only([
            'agency_id', 'agency_name', 'short_name', 'contact', 'position', 'email', 'license_number', 'agency_type_id', 'phone_no',
            'fax_no', 'web_url', 'status', 'agency_logo', 'search_only', 'stop_by', 'cancel_right', 'old_logo', 'iata_number'
        ]);
        $requestAgencyCurrencies = $request->only(['agency_id', 'enable_currency_id']);
        $requestAgencyAddressData = $request->only(['agency_address_id', 'agency_id', 'address1', 'address2', 'city', 'state', 'country', 'zip_code']);
        $requestAgencyPaymentTypesData = $request->only(['agency_id', 'payment_option']);
        $requestAgencyServiceTypesData = $request->only(['agency_id', 'service_type']);
        $requestAgencyPaymenyGatewayData = $request->only(['agency_id', 'payment_gateway']);

        $rules = [];

        $customMessages = [];

        $niceNames = array();

        $response = Agency::updateAgency($requestGeneralInfoData);
        $agencyType = AgencyType::where('id', $response['data']['core_agency_type_id'])->value('code');

        if ($response['data']['status'] == 'terminated') {

            $isPrimaryUser = User::where('mobile', $response['data']['phone_no'])->where('agency_id', '!=', '0')->value('primary_user');
            $PrimaryUserEmail = User::where('agency_id', $response['data']['id'])->where('primary_user', '1')->value('email');
            //send mail to user if agency status is inavtive or terminated
            $agencyLogo = Setting::where('config_key', 'general|basic|colorLogo')->get('value')[0]['value'] ?? Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'];
            $code = 'AGENCY_BLOCK';
            $siteName = count(Setting::where('config_key', 'general|basic|siteName')->get('value')) > 0 ? Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'] : "Amar Infotech";
            $agencyDetails = Agency::where('id', $response['data']['agency_id'])->get()->toArray();

            $data = array(
                'agency_name' => $response['data']['full_name'],
                'agency_logo' => $response['data']['logo']
            );

            $getTemplateData = EmailService::agencyBlockMailTemplate($code, $data);

            if ($getTemplateData['status'] == 'false') {
                return back()->with('error', $getTemplateData['error']);
            } else {
                $subject = $getTemplateData['data']['subject'];
                $mailData = $getTemplateData['data']['mailData'];
                $toEmail = $PrimaryUserEmail;
                $files = [];

                // set data in sendEmail function
                $data = EmailService::sendEmail($toEmail, $subject, $mailData, $files, $siteName);
            }
        }
        if (!empty($response['data'])) {
            $responseAgencyCurrencies = AgencyCurrency::updateAgencyCurrency($requestAgencyCurrencies);
            $responseAgencyAddress = AgencyAddress::updateAgencyAddress($requestAgencyAddressData);
            if ($agencyType != 'SUPPLIER') {
                $responseAgencyPaymentOptions = AgencyPaymentType::updateAgencyPaymentType($requestAgencyPaymentTypesData);
                $responseAgencyServiceTypes = AgencyServiceType::updateAgencyServiceType($requestAgencyServiceTypesData);
                $responseAgencyPaymentGateway = AgencyPaymentGateway::updateAgencyPaymentGateway($requestAgencyPaymenyGatewayData);
            }
        } else {
            return redirect()->route('agency.index')->with('error', $response['message']);
        }
        $activityLog['request'] = $requestGeneralInfoData;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $response;
        ActiveLog::createBackendActiveLog($activityLog);

        if (!empty($response['data'])) {

            return redirect()->to($url['redirects_to'])->with('success', $response['message']);
        } else {
            return redirect()->to($url['redirects_to'])->with('error', $response['message']);
        }
    }

    /**
     * Fetch currency from database.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCurrency(Request $request)
    {
        $getAgencyTypeCode = AgencyType::where('id', $request->agencyCode)->value('code');
        if ($getAgencyTypeCode == 'B2B') {
            $currencyData = DB::table('currencies')->select('id', 'name')->where('b2b_allowed_currency', '1')->get()->toArray();
        } else {
            $currencyData = DB::table('currencies')->select('id', 'name')->where('supplier_allowed_currency', '1')->get()->toArray();
        }


        return json_encode($currencyData);
    }

    /**
     * Remove the specified agency from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteAgency(Request $request)
    {
        if (!hasPermission('AGENCY', 'delete')) {
            return view('admin/401');
        }

        $agencyIDs = explode(',', $request->input('agency_id'));

        $message = "";
        foreach ($agencyIDs as $agency_id) {
            $response = Agency::deleteAgency($agency_id);
            $message .= $response['message'] . '</br>';
        }
        
        $activityLog['request'] = $request->all();
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $response;
        ActiveLog::createBackendActiveLog($activityLog);

        if ($response['status'] == 1) {
            return redirect()->route('agency.index')->with('success', $message);
        } else {
            return redirect()->route('agency.index')->with('error', $response['message']);
        }
    }

    /**
     * function to check either email already exist or not
     * 
     */
    public function checkAgencyEmailExist(Request $request)
    {
        $matchListData = [];
        if (request()->input('email') && request()->input('email') != "") {
            if (request()->input('agency_id')) {
                $matchListData = Agency::where('email', request()->input('email'))->where('id', '!=', request()->input('agency_id'))->get()->toArray();
            } else {
                $matchListData = Agency::where('email', request()->input('email'))->where('status', '!=', 'deleted')->get()->toArray();
            }
        }
        if (!empty($matchListData)) {
            echo "false";
        } else {
            echo "true";
        }
    }

    /**
     * function to check either agency phone already exist or not
     * 
     */
    public function checkAgencyPhoneExist(Request $request)
    {
        $matchListData = [];
        if (request()->input('phone_no') && request()->input('phone_no') != "") {
            if (request()->input('agency_id')) {
                $matchListData = Agency::where('phone_no', request()->input('phone_no'))->where('id', '!=', request()->input('agency_id'))->get()->toArray();
            } else {
                $matchListData = Agency::where('phone_no', request()->input('phone_no'))->where('status', '!=', 'deleted')->get()->toArray();
            }
        }
        if (!empty($matchListData)) {
            echo "false";
        } else {
            echo "true";
        }
    }

    /**
     * function to check either agency fax no already exist or not
     * 
     */
    public function checkAgencyFaxExist(Request $request)
    {
        $matchListData = [];
        if (request()->input('fax_no') && request()->input('fax_no') != "") {
            if (request()->input('agency_id')) {
                $matchListData = Agency::where('fax_no', request()->input('fax_no'))->where('id', '!=', request()->input('agency_id'))->get()->toArray();
            } else {
                $matchListData = Agency::where('fax_no', request()->input('fax_no'))->where('status', '!=', 'deleted')->get()->toArray();
            }
        }
        if (!empty($matchListData)) {
            echo "false";
        } else {
            echo "true";
        }
    }

    /**
     * function to check either agency web url already exist or not
     * 
     */
    public function checkAgencyWebURLExist(Request $request)
    {
        $matchListData = [];
        if (request()->input('web_url') && request()->input('web_url') != "") {
            if (request()->input('agency_id')) {
                $matchListData = Agency::where('web_link', request()->input('web_url'))->where('id', '!=', request()->input('agency_id'))->get()->toArray();
            } else {
                $matchListData = Agency::where('web_link', request()->input('web_url'))->where('status', '!=', 'deleted')->get()->toArray();
            }
        }
        if (!empty($matchListData)) {
            echo "false";
        } else {
            echo "true";
        }
    }

    /**
     * function to check either user email already exist or not
     * 
     */
    public function checkUserEmailExist(Request $request)
    {

        $matchListData = [];
        if (request()->input('operatorEmail') && request()->input('operatorEmail') != "") {
            if (request()->input('agency_id')) {
                $matchListData = User::where('email', request()->input('operatorEmail'))->where('id', '!=', request()->input('agency_id'))->get()->toArray();
            } else {
                $matchListData = User::where('email', request()->input('operatorEmail'))->where('status', '!=', '2')->get()->toArray();
            }
        }
        if (!empty($matchListData)) {
            echo "false";
        } else {
            echo "true";
        }
    }

    /**
     * function to check either user mobile already exist or not
     * 
     */
    public function checkUserMobileExist(Request $request)
    {

        $IsdCodeLength = Country::where('isd_code', request()->input('isd_code'))->value('max_mobile_number_length');
        $mobileLength = strlen(request()->input('mobile'));
        if ($IsdCodeLength != $mobileLength) {
            $response['valid'] = false;
            $response['message'] = "Mobile number should contain atleast " . $IsdCodeLength . " Numbers";
        } elseif (request()->input('mobile') && request()->input('mobile') != "") {
            if (request()->input('agency_id')) {
                $userListData = Agency::where('mobile', request()->input('mobile'))->where('status', '!=', 2)->where('id', '!=', request()->input('agency_id'))->get()->toArray();
            } else {
                $userListData = Agency::where('mobile', request()->input('mobile'))->where('status', '!=', 2)->get()->toArray();
            }
            if (!empty($userListData)) {
                $response['valid'] = false;
                $response['message'] = "Mobile number alresdy exists";
            } else {
                $response['valid'] = true;
            }
        }
        return response()->json($response);
    }

    /**
     * function to check either IATA number already exist or not
     * 
     */
    public function checkIATANumberExist(Request $request)
    {

        $matchListData = [];
        if (request()->input('iata_number') && request()->input('iata_number') != "") {
            if (request()->input('agency_id')) {
                $matchListData = Agency::where('iata_number', request()->input('iata_number'))->where('id', '!=', request()->input('agency_id'))->get()->toArray();
            } else {
                $matchListData = Agency::where('iata_number', request()->input('iata_number'))->where('status', '!=', 'deleted')->get()->toArray();
            }
        }
        if (!empty($matchListData)) {
            echo "false";
        } else {
            echo "true";
        }
    }
}
