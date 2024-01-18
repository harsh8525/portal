<?php
/**
 * @package     Operational Data
 * @subpackage  Payment GatewayAttempts
 * @Author      Amar Technolabs Pvt. ltd(info@amarinfotech.com)
 * @Copyright(C) 2023 [Travel Portal].
 * @Version 1.0.0
 * module of the Payment Gateway.
 */

namespace App\Http\Controllers\Admin\OperationalData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentGateway;
use App\Models\Setting;
use Illuminate\Support\Facades\URL;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Validation\Rule;
use App\Traits\ActiveLog;
use App\Models\User;
use Devinweb\LaravelHyperpay\Facades\Hyperpay;
use Devinweb\LaravelHyperpay\Facades\LaravelHyperpay;
use Illuminate\Support\Str;
use GuzzleHttp\Client;



class PaymentGatewayController extends Controller
{
    
    /**
     * Display a listing of the payment gateway.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!hasPermission('PAYMENT_GATEWAY','read')){
            return view('admin/401');
        }
         $header['title']= "Payment Gateway";
        $header['heading']= "Payment Gateway";
        
        $queryStringConcat = '?';
        if(isset($_GET['per_page'])){            
            $queryStringConcat .= ($queryStringConcat == '') ? '?per_page='.$_GET['per_page'] : '&per_page='.$_GET['per_page'].$_SERVER['QUERY_STRING'] ;
        }
        if(isset($_GET['page'])){
            $queryStringConcat .= ($queryStringConcat == '') ? '?page='.$_GET['page'] : '&page='.$_GET['page'].$_SERVER['QUERY_STRING'] ;
        }
        

        $filter = array(
            'per_page' => (request()->input('per_page') != NULL) ? request()->input('per_page') : Setting::where('config_key', 'general|setting|pagePerAdminRecords')->get('value')[0]['value'],
            'order_by' => (request()->input('order_by') != NULL) ? request()->input('order_by') : 'id',
            'sorting' => (request()->input('sorting') != NULL) ? request()->input('sorting') : 'desc',
            'name' => (request()->input('name') != NULL) ? request()->input('name') : '',
            'status' => (request()->input('status') != NULL) ? request()->input('status') : '',
            
        );
        
        if(request()->input('name') != NULL){
            $filter['where'][] = [ 'core_payment_gateways.name','like','%'.request()->input('name').'%'];
        }
        
        if(request()->input('status') != NULL) {
            $filter['where'][] = [ 'core_payment_gateways.is_active','=',request()->input('status')];
        }
        
        $paymentListData = PaymentGateway::getPayment($filter);   
        $paymentData = $paymentListData['data'];
        $paymentCountData = PaymentGateway::count();

        $activityLog['request'] =  $filter;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] =  $paymentListData;
        ActiveLog::createBackendActiveLog($activityLog);

        if($paymentListData['status'] == 1){
            return view('admin/OperationalData/payment-gateway/index')->with(['header'=>$header,'paymentData'=>$paymentData,'paymentCountData'=>$paymentCountData,'queryStringConcat'=>$queryStringConcat, 'appliedFilter' => $filter ,'i'=> (request()->input('page', 1) - 1) * $filter['per_page']]);
        }else{
            return view('admin/OperationalData/payment-gateway/index')->with(['error'=> $paymentListData['message'],'header'=>$header,'paymentData'=>$paymentData, 'paymentCountData'=>$paymentCountData,'queryStringConcat'=>$queryStringConcat, 'appliedFilter' => $filter ,'i'=> (request()->input('page', 1) - 1) * $filter['per_page']]);
        }
    }

    /**
     * Show the form for creating a new payment gateway.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!hasPermission('PAYMENT_GATEWAY','create')){
            return view('admin/401');
        }

        $header['title']= "Payment Gateway - Add";
        $header['heading']= "Payment Gateway - Add";

        $activityLog['request'] =  [];
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] =  [];
        ActiveLog::createBackendActiveLog($activityLog);

        return view('admin/OperationalData/payment-gateway/add')->with(['header'=>$header]);
    }

    /**
     * Store a newly created payment gateway in storage - Insert Form Data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!hasPermission('PAYMENT_GATEWAY','create')){
            return view('admin/401');
        }

        $requestData = $request->all();
        $rules = [];

            $customMessages = [
                ];

            $niceNames = array();
        
        $response = PaymentGateway::createPayment($requestData);

        $activityLog['request'] = $requestData;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $response;
        ActiveLog::createBackendActiveLog($activityLog);
        
        if(!empty($response['data'])){
            return redirect()->route('payment-gateway.index')->with('success',$response['message']);
        }else{
            return redirect()->route('payment-gateway.index')->with('error', $response['message']);
        }
    }

    /**
     * Display the specified payment gateway.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * 
     */
    public function show($id)
    {
        if(!hasPermission('PAYMENT_GATEWAY','read')){
            return view('admin/401');
        }
        $header['title'] = 'Payment Gateway - View';
        $header['heading'] = 'Payment Gateway - View';
        $filter = array(
            'id' => $id
        );
        $response = PaymentGateway::getPayment($filter);
        $paymentDetail = $response['data'];
        
        if($response['status'] == 1 && !empty($response['data'])){
            return view('admin/OperationalData/payment-gateway/view')->with(['header'=>$header,'paymentDetail'=>$paymentDetail]);
        }else{
            return redirect()->route('payment-gateway.index')->with('error', $response['message']);
        }

    }

    /**
     * Show the form for editing the specified payment gateway.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * 
     */
    public function edit($id)
    {
        if(!hasPermission('PAYMENT_GATEWAY','update')){
            return view('admin/401');
        }
        
        $header['title'] = 'Payement Gateway - Edit';
        $header['heading'] = 'Payment Gateway - Edit';
        $header['method'] = 'Edit';
        $filter = array(
            'id' => $id,
        );
        $response = PaymentGateway::getPayment($filter);
      
        $paymentDetail = $response['data'];

        $activityLog['request'] = $filter;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $paymentDetail;
        ActiveLog::createBackendActiveLog($activityLog);
        
        if($response['status'] == 1 && !empty($response['data'])){
            return view('admin/OperationalData/payment-gateway/update')->with(['header'=>$header,'paymentDetail'=>$paymentDetail]);
        }else{
            return redirect()->route('payment-gateway.index')->with('error', $response['message']);
        }
    }

    /**
     * Update the specified payment gateway in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * 
     */
    public function update(Request $request, $id)
    {
        if(!hasPermission('PAYMENT_GATEWAY','update')){
            return view('admin/401');
        }
        $url = $request->only('redirects_to');
        $requestData = $request->only(['payment_id','name','description','status','logo','profile_image','old_photo']);
        $rules = [];

            $customMessages = [];

            $niceNames = array();
            
            $this->validate($request, $rules, $customMessages, $niceNames);
        
        $response = PaymentGateway::updatePayment($requestData);

        $activityLog['request'] = $request->all();
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $response;
        ActiveLog::createBackendActiveLog($activityLog);
        
        if(!empty($response['data'])){
            return redirect()->to($url['redirects_to'])->with('success',$response['message']);
        }else{
            return redirect()->to($url['redirects_to'])->with('error', $response['message']);
        }
    }

    /**
     * Remove the specified payment gateway from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     public function deletePayment(Request $request){    
        
        if(!hasPermission('PAYMENT_GATEWAY','delete')){
            return view('admin/401');
        }
        $url = URL::previous();
        $paymentIDs = explode(',',$request->input('payment_id'));
        
        $message = "";
        foreach($paymentIDs as $payment_id){
            $response = PaymentGateway::deletePayment($payment_id);
            $message .= $response['message'].'</br>';
        }

        $activityLog['request'] = $request->all();
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $response;
        ActiveLog::createBackendActiveLog($activityLog);

        if($response['status'] == 1){
            return redirect()->to($url)->with('success',$message);
        }else{
            return redirect()->to($url)->with('error', $response['message']);
        }
        
    }
    
     /**
     * Check name exist in payment gateway.
     *
     * @return \Illuminate\Http\Request
     */
    public function checkExistName(Request $request) {
        $matchListData = [];
        if (request()->input('name') && request()->input('name') != "") {
            if (request()->input('payment_id')) {
                $matchListData = PaymentGateway::where('name', request()->input('name'))->where('id', '!=', request()->input('payment_id'))->get()->toArray();
               
            } else {
                $matchListData = PaymentGateway::where('name', request()->input('name'))->where('is_active', '!=', '2')->get()->toArray();
            }
        }
        if (!empty($matchListData)) {
            echo "false";
        } else {
            echo "true";
        }
    }

     /**
     * Test hyper payment gateway.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkout(Request $request)
    {
        $header['title']= "Test Hyperpay Payment Gateway";
        $header['heading']= "Test Hyperpay Payment Gateway ";
        return view('admin/OperationalData/payment-gateway/checkout-form')->with(['header'=>$header]);
    }

    public function payment(Request $request)
    {
        // $brand = $request->brand;
        // if($brand == 'VISA'){
        //     $entityID = '8ac7a4c78b70a957018b7b1aca8e0a1c';
        // }elseif($brand = 'APPLEPAY'){
        //     $entityID = '8ac7a4c78b70a957018b7b2659840a45';
        // }
        // elseif($brand == 'MADA'){
        //     $entityID = '8ac7a4c78b70a957018b7b2596770a3e';
        // }else{
        //     $entityID = '8ac7a4c78b70a957018b7b1aca8e0a1c';
        // }

        try {
        
            $url = "https://eu-test.oppwa.com/v1/checkouts";

            $requestData = [
                'entityId' => '8ac7a4c78b70a957018b7b1aca8e0a1c',
                'amount' => '1.00',
                'currency' => 'SAR',
                'paymentType' => 'DB',
                'bankAccount.country'=> 'SA',
                'customer.merchantCustomerId' => uniqid(),
                'customer.givenName' => 'darshan',
                'customer.middleName' => 'amar',
                'customer.surname' => 'amar',
                'customer.email' => 'darshan@amarinfotech.com',
                'customer.phone' => '+966 11 5111879',
                
            ];

            $postData = http_build_query($requestData);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer OGFjN2E0Yzc4YjcwYTk1NzAxOGI3YjE5ODU2ZDBhMTZ8Y3pDQkhtc2o5TU00Zlo5Sg==',
                'Content-Type: application/x-www-form-urlencoded'
            ]);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Set to true in production
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $responseData = curl_exec($ch);
            if(curl_errno($ch)) {
                return curl_error($ch);
            }
            curl_close($ch);

            $data = json_decode($responseData, true);
            //echo "<pre>"; print_r($data); exit;
        
            $checkoutId = $data['id'] ?? null;

            $header['title']= "Test Hyperpay Payment Gateway";
            $header['heading']= "Test Hyperpay Payment Gateway ";

            return view('admin/OperationalData/payment-gateway/testhyper-payment-gateway', compact('checkoutId','header'));
        } catch (\Exception $e) {
            // Log exceptions
            //dd($e->getMessage());
            // Handle exception as needed
        }
    }

    public function checkPaymentStatus(Request $request)
    {
        // $brand = $request->brand;
        // if($brand == 'VISA'){
        //     $entityID = '8ac7a4c78b70a957018b7b1aca8e0a1c';
        // }elseif($brand = 'APPLEPAY'){
        //     $entityID = '8ac7a4c78b70a957018b7b2659840a45';
        // }
        // elseif($brand == 'MADA'){
        //     $entityID = '8ac7a4c78b70a957018b7b2596770a3e';
        // }else{
        //     $entityID = '8ac7a4c78b70a957018b7b1aca8e0a1c';
        // }
        //dd($request);
       
        $checkoutId = $request->id;
        $resourcePath = $request->resourcePath;
        $url = "https://eu-test.oppwa.com/v1/checkouts/".$checkoutId."/payment";
        $url .= "?entityId=8ac7a4c78b70a957018b7b1aca8e0a1c";
    

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Authorization:Bearer OGFjN2E0Yzc4YjcwYTk1NzAxOGI3YjE5ODU2ZDBhMTZ8Y3pDQkhtc2o5TU00Zlo5Sg=='));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);// this should be set to true in production
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $responseData = curl_exec($ch);
        if(curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);
        $data = json_decode($responseData, true);

        // Check if conversion was successful
        if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            return response()->json(['error' => 'Failed to decode JSON response'], 500);
        }

        // Return the JSON response
        return response()->json($data);
    }
}
