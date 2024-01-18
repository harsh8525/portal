<?php

namespace B2BApp\Http\Controllers\B2B;

use B2BApp\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Agency;
use App\Models\AgencyType;
use App\Models\ServiceType;
use App\Models\PaymentGateway;
use App\Models\GeoRegionLists;
use App\Models\AgencyCurrency;
use App\Models\AgencyAddress;
use App\Models\AgencyPaymentType;
use App\Models\AgencyServiceType;
use App\Models\AgencyPaymentGateway;
use Auth;
use DB;

class AgencyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // echo "hello";die;
        //  if(!hasPermission('AGENCY','read')){
        //     return view('admin/401');
        // }
// echo Auth::user()->id;die;
        $header['title'] = "Agency - View";
        $header['heading'] = "Agency - View";
        $query = User::query();
            $query->select('agency_id')->where('id', Auth::guard('b2b')->user()->id);
            $dd = $query->get();
        $id = $dd[0]['agency_id'];    
        // echo "<pre>";print_r($id);die;
        $filter = array(
            'id' => $id
        );
        $response = Agency::getAgency($filter);
        $agencyDetail = $response['data'];
        // echo "<pre>"; print_r($agencyDetail);die;


        if ($response['status'] == 1 && !empty($response['data'])) {
            return view('b2b/agency/view')->with(['header' => $header, 'agencyDetails' => $agencyDetail]);
        } else {
            return redirect()->route('b2b.dashboard')->with('error', $response['message']);
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
        // if(!hasPermission('AGENCY','update')){
        //     return view('admin/401');
        // }

        $header['title'] = 'Agency - Edit';
        $header['method'] = 'Edit';
        $filter = array(
            'id' => $id,
        );
        $response = Agency::getAgency($filter);
        $agencyDetail = $response['data'];
        $agencyCreateData['agency_type'] = AgencyType::whereIn('is_active',['1','2'])->orderBy('name','asc')->get()->toArray();
        $agencyCreateData['payment_option'] = DB::table('core_payment_types')->where('is_active','1')->orderBy('name','asc')->get()->toArray();
        $agencyCreateData['service_type'] = ServiceType::where('is_active','1')->orderBy('name','asc')->get()->toArray();
        $agencyCreateData['payment_gateway'] = PaymentGateway::where('is_active','1')->orderBy('name','asc')->get()->toArray();
        $agencyCreateData['country_list'] = GeoRegionLists::where('region_type','Country')->get()->toArray();
        // echo "<pre>"; print_r($agencyCreateData['payment_gateway']);die;
        if($response['status'] == 1 && !empty($response['data'])){
               
            return view('b2b/agency/update')->with(['header'=>$header,'agencyDetail'=>$agencyDetail,'agencyCreateData'=>$agencyCreateData]);
        }else{
            return redirect()->route('b2b.dashboard')->with('error', $response['message']);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * created by vijay Prajapati
     * created date 31-07-2023
     */
    public function update(Request $request, $id)
    {
        // if(!hasPermission('AGENCY','update')){
        //     return view('admin/401');
        // }
        
        $requestGeneralInfoData = $request->only(['agency_id','agency_name','short_name','contact','position','email','license_number','agency_type_id','phone_no',
                                        'fax_no','web_url','status','agency_logo','search_only','stop_by','cancel_right','old_logo']);
        $requestAgencyCurrencies = $request->only(['agency_id','enable_currency_id']);
        $requestAgencyAddressData = $request->only(['agency_address_id','agency_id','address1','address2','city','state','country','zip_code']);
        $requestAgencyPaymentTypesData = $request->only(['agency_id','payment_option']);
        $requestAgencyServiceTypesData = $request->only(['agency_id','service_type']);
        $requestAgencyPaymenyGatewayData = $request->only(['agency_id','payment_gateway']);
        
        // echo '<pre>';print_r($requestAgencyPaymentTypesData);die;
        $rules = [
                
            ];

            $customMessages = [
                ];

            $niceNames = array();
            
            // $this->validate($customMessages, $niceNames);
        
        $response = Agency::updateAgency($requestGeneralInfoData);

        $agencyType = AgencyType::where('id',$response['data']['core_agency_type_id'])->value('code');
        // echo "<pre>";print_r($response);die;
        if(!empty($response['data'])){
            $responseAgencyCurrencies = AgencyCurrency::updateAgencyCurrency($requestAgencyCurrencies);
            $responseAgencyAddress = AgencyAddress::updateAgencyAddress($requestAgencyAddressData);
            if($agencyType != 'SUPPLIER')
            {
                $responseAgencyPaymentOptions = AgencyPaymentType::updateAgencyPaymentType($requestAgencyPaymentTypesData);
                $responseAgencyServiceTypes = AgencyServiceType::updateAgencyServiceType($requestAgencyServiceTypesData);
                $responseAgencyPaymentGateway = AgencyPaymentGateway::updateAgencyPaymentGateway($requestAgencyPaymenyGatewayData);
            }


        }else{
            return redirect()->route('b2b.dashboard')->with('error', $response['message']);
        }
        
        if(!empty($response['data'])){
            
            return redirect()->route('b2b_agency.agency.show',Auth::guard('b2b')->user()->id)->with('success',$response['message']);
        }else{
            return redirect()->route('b2b_agency.agency.show',Auth::guard('b2b')->user()->id)->with('error', $response['message']);
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
    public function getCurrency(Request $request)
    {
        $getAgencyTypeCode = AgencyType::where('id',$request->agencyCode)->value('code');
        if($getAgencyTypeCode == 'B2B') 
        {
            $currencyData = DB::table('currencies')->select('id','name')->where('b2b_allowed_currency','1')->get()->toArray();
        }
        else if($getAgencyTypeCode == 'SUPPLIER') 
        {
            $currencyData = DB::table('currencies')->select('id','name')->where('supplier_allowed_currency','1')->get()->toArray();
        }
        
        
       return json_encode($currencyData);
    }
   
    /**
     * function to check either email already exist or not
     * created by vijay Prajapati
     * created date 31-07-2023
     */
    public function checkAgencyEmailExist(Request $request) {
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
     * created by vijay Prajapati
     * created date 31-07-2023
     */
    public function checkAgencyPhoneExist(Request $request) {
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
     * created by vijay Prajapati
     * created date 31-07-2023
     */
    public function checkAgencyFaxExist(Request $request) {
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
     * created by vijay Prajapati
     * created date 31-07-2023
     */
    public function checkAgencyWebURLExist(Request $request) {
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
     * created by vijay Prajapati
     * created date 10-07-2023
     */
    public function checkUserEmailExist(Request $request) {
        
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
     * created by vijay Prajapati
     * created date 10-07-2023
     */
    public function checkUserMobileExist(Request $request) {
        
        $matchListData = [];
        if (request()->input('operatorMobile') && request()->input('operatorMobile') != "") {
            if (request()->input('agency_id')) {
                $matchListData = User::where('mobile', request()->input('operatorMobile'))->where('id', '!=', request()->input('agency_id'))->get()->toArray();
            } else {
                $matchListData = User::where('mobile', request()->input('operatorMobile'))->where('status', '!=', '2')->get()->toArray();
            }
        }
        if (!empty($matchListData)) {
            echo "false";
        } else {
            echo "true";
        }
    }

}
