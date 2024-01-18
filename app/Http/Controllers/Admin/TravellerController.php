<?php


/**
 * @package     Customers
 * @subpackage   Traveller
 * @Author      Amar Technolabs Pvt. ltd(info@amarinfotech.com)
 * @Copyright(C) 2023 [Travel Portal].
 * @Version 1.0.0
 * module of the  Traveller.
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Setting;
use App\Models\CustomerTraveller;
use App\Models\Customer;
use App\Models\CustomerAddresses;
use App\Traits\ActiveLog; 
use App\Models\Country;
use App\Models\City;
use App\Models\CountryI18ns;
use App\Traits\EmailService;
use Carbon\Carbon;

class TravellerController extends Controller
{
    use EmailService;
    /**
     * Display a listing of the travellers.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!hasPermission('TRAVELLERS', 'read')) {
            return view('admin/401');
        }

        $header['title'] = "Travellers";
        $header['heading'] = "Travellers";
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
            'date_of_birth' => (request()->input('date_of_birth') != NULL) ? request()->input('date_of_birth') : '',
            'nationality_id' => (request()->input('nationality_id') != NULL) ? request()->input('nationality_id') : '',
            'customer_id' => (request()->input('customer_id') != NULL) ? request()->input('customer_id') : '',
            'status' => (request()->input('status') != NULL) ? request()->input('status') : '',
        );

        if (request()->input('customer_id') != NULL) {
            $filter['where'][] = ['customer_id', '=',  request()->input('customer_id') ];
        }
        if (request()->input('full_name') != NULL) {
            $fullName = request()->input('full_name');
            $filter['where'][] = [
                DB::raw("CONCAT(first_name, ' ', last_name)"),
                'LIKE',
                '%' . $fullName . '%'
            ];
        }

        if (request()->input('date_of_birth') != NULL) {
            $filter['where'][] = ['date_of_birth', '=', request()->input('date_of_birth')];
        }

        if (request()->input('nationality_id') != NULL) {
            $filter['where'][] = ['nationality_id', '=', request()->input('nationality_id')];
        }


        if (request()->input('status') != NULL) {
            $filter['where'][] = ['status', '=', request()->input('status')];
        }
        $customer_id = $request->customer_id;
        $filter['customer_id'] = $customer_id;
        $travellerDataList = CustomerTraveller::getTravellerData($filter);
        $travellerData = $travellerDataList['data'];
        $getCountries = Country::with('countryCode')->where('status', 'active')->get();
        $getCities = City::with('cityCode')->where('status', 'active')->get();

        $activityLog['request'] =  $filter;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] =  $travellerDataList;
        ActiveLog::createBackendActiveLog($activityLog);

        if ($travellerDataList['status'] == 1) {
            return view('admin/travellers/index')->with(['header' => $header, 'travellerData' => $travellerData, 'getCountries' => $getCountries, 'getCities' => $getCities, 'customer_id' => $customer_id, 'queryStringConcat' => $queryStringConcat, 'appliedFilter' => $filter,  'i' => (request()->input('page', 1) - 1) * $filter['per_page']]);
        } else {
            return view('admin/travellers/index')->with(['header' => $header, 'travellerData' => $travellerData, 'getCountries' => $getCountries, 'getCities' => $getCities, 'customer_id' => $customer_id, 'queryStringConcat' => $queryStringConcat, 'appliedFilter' => $filter, 'i' => (request()->input('page', 1) - 1) * $filter['per_page']]);
        }
    }

    /**
     * Show the form for creating a new travellers.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (!hasPermission('CUSTOMERS_LIST', 'create')) {
            return view('admin/401');
        }
        $customerId = $request->customer_id;
        $header['title'] = @trans('travellers.addTravellers');
        $customers = Customer::where('status', 1)->get()->toArray();
        $getCountry = Country::get();

        $activityLog['request'] =  [];
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] =  [];
        ActiveLog::createBackendActiveLog($activityLog);

        return view('admin/travellers/add')->with(['header' => $header, 'customers' => $customers, 'customerId' => $customerId, 'getCountry' => $getCountry]);
    }

    /**
     * Store a newly created travellers in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $requestData = $request->all();
        $customer_id = $requestData['customer_id'];

        $response = CustomerTraveller::createTravellers($requestData);

        $activityLog['request'] =  $requestData;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] =  $response;
        ActiveLog::createBackendActiveLog($activityLog);

        if (!empty($response['data'])) {
            return redirect()->route('travellers.index', compact('customer_id'))->with('success', $response['message']);
        } else {
            return redirect()->back()->with('error', $response['message']);
        }
    }

    /**
     * Display the specified travellers.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!hasPermission('TRAVELLER_LIST', 'read')) {
            return view('admin/401');
        }
        $header['title'] = @trans('travellers.viewTravellers');
        $header['heading'] = @trans('customers.moduleHeading');
        $filter = array(
            'id' => $id
        );
        $response = CustomerTraveller::getTravellerData($filter);
        $getCountry = Country::with('countryCode')->where('iso_code', $response['data']['country_id'])->get()->first();
        $getNationality = Country::with('countryCode')->where('iso_code', $response['data']['nationality_id'])->get()->first();
        $travellerDetail = $response['data'];

        $activityLog['request'] = $filter;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $response;
        ActiveLog::createBackendActiveLog($activityLog);

        if ($response['status'] == 1 && !empty($response['data'])) {
            return view('admin/travellers/view')->with(['header' => $header, 'travellerDetail' => $travellerDetail, 'getCountry' => $getCountry, 'getNationality' => $getNationality]);
        } else {
            return redirect()->route('travellers.index')->with('error', $response['message']);
        }
    }

    /**
     * Show the form for editing the specified travellers.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!hasPermission('CUSTOMERS_LIST', 'update')) {
            return view('admin/401');
        }
        $header['title'] = 'Traveller - Edit';
        $header['heading'] = @trans('customers.moduleHeading');
        $header['method'] = 'Edit';

        $filter = array(
            'id' => $id
        );
        $response = CustomerTraveller::getTravellerData($filter);
        $travellerDetail = $response['data'];
        $getCountry = Country::with('countryCode')->where('iso_code', $travellerDetail['country_id'])->where('status', 'active')->get()->first();
        
        $activityLog['request'] =  $filter;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] =  $response;
        ActiveLog::createBackendActiveLog($activityLog);
        
        if ($response['status'] == 1 && !empty($response['data'])) {
            return view('admin/travellers/update')->with(['header' => $header, 'travellerDetail' => $travellerDetail, 'getCountry' => $getCountry]);
        } else {
            return redirect()->route('travellers.index')->with('error', $response['message']);
        }
    }

    /**
     * Update the specified travellers in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $url = $request->redirects_to;
        $requestData = $request->all();
        $requestData['country'] = Country::where('iso_code', $request->country)->value('id');

        $rules = [];
        $customMessages = [];

        $niceNames = array();

        $this->validate($request, $rules, $customMessages, $niceNames);

        $customer_id = $requestData['customer_id'];
        $response = CustomerTraveller::updateTraveller($requestData);

        $activityLog['request'] = $requestData;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $response;
        ActiveLog::createBackendActiveLog($activityLog);

        if (!empty($response['data'])) {
            return redirect()->route('travellers.index', compact('customer_id'))->with('success', $response['message']);
        } else {
            return redirect()->to($url)->with('error', $response['message']);
        }
    }

    /**
     * Remove the specified travellers from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteTravellers(Request $request)
    {
       
        $url = URL::previous();
        $customerIDs = explode(',', $request->input('traveller_id'));
        $message = "";
        foreach ($customerIDs as $traveller_id) {
            $response = CustomerTraveller::deleteTravellers($traveller_id);
            $message .= $response['message'] . '</br>';
        }

        $activityLog['request'] = $request->all();
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $response;
        ActiveLog::createBackendActiveLog($activityLog);

        if ($response['status'] == 1) {
            return redirect()->to($url)->with('success', $message);
        } else {
            return redirect()->to($url)->with('error', $response['message']);
        }
    }

    /**
     * Restore the specified travellers from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restoreTraveller(Request $request)
    {
        if (!hasPermission('TRAVELLER', 'delete')) {
            return view('admin/401');
        }
        $url = URL::previous();
        $restore_traveller_id = $request->input('restore_traveller_id');
        $message = "";
        $response = CustomerTraveller::restoreTravellers($restore_traveller_id);
        $message .= $response['message'] . '</br>';

        if ($response['status'] == 1) {
            return redirect()->to($url)->with('success', $message);
        } else {
            return redirect()->to($url)->with('error', $response['message']);
        }
    }
}
