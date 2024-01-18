<?php

/**
 * @package     Operational Data
 * @subpackage  Payment Methods.
 * @Author      Amar Technolabs Pvt. ltd(info@amarinfotech.com)
 * @Copyright(C) 2023 [Travel Portal].
 * @Version 1.0.0
 * module of the Payment Methods.
 */

namespace App\Http\Controllers\Admin\OperationalData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\PaymentMethod;
use App\Traits\ActiveLog;
use URL;

class PaymentmethodController extends Controller
{
    /**
     * Display a listing of the payment method.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!hasPermission('PAYMENT_METHOD','read')){
            return view('admin/401');
        }
        $header['title'] = "Payment Methods";
        $header['heading'] = "Payment Methods";
        $queryStringConcat = '?';
        
        if(isset($_GET['per_page'])){            
            $queryStringConcat .= ($queryStringConcat == '') ? '?per_page='.$_GET['per_page'] : '&per_page='.$_GET['per_page'].$_SERVER['QUERY_STRING'];
        }
        if(isset($_GET['page'])){
            $queryStringConcat .= ($queryStringConcat == '') ? '?page='.$_GET['page'] : '&page='.$_GET['page'].$_SERVER['QUERY_STRING'];
        }  
        
        $filter = array(
            'per_page' => (request()->input('per_page') != NULL) ? request()->input('per_page') : Setting::where('config_key', 'general|setting|pagePerAdminRecords')->get('value')[0]['value'],
            'order_by' => (request()->input('order_by') != NULL) ? request()->input('order_by') : 'id',
            'sorting' => (request()->input('sorting') != NULL) ? request()->input('sorting') : 'desc',
            'name' => (request()->input('name') != NULL) ? request()->input('name') : '',
            'status' => (request()->input('status') != NULL) ? request()->input('status') : '',
            
        );

        if(request()->input('name') != NULL){
            $filter['where'][] = [ 'core_payment_types.name','like','%'.request()->input('name').'%'];
        }
        
        if(request()->input('status') != NULL) {
            $filter['where'][] = [ 'core_payment_types.is_active','=',request()->input('status')];
        }
        
        $paymentDataList = PaymentMethod::getPeymentMethod($filter);    
        $paymentDataCount = PaymentMethod::count();    

        $paymentData = $paymentDataList['data'];

        $activityLog['request'] = $filter;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $paymentDataList;
        ActiveLog::createBackendActiveLog($activityLog);
        
        if($paymentDataList['status'] == 1){
            return view('admin/OperationalData/payment-method/index')->with(['header'=>$header,'paymentData'=>$paymentData,'paymentDataCount'=>$paymentDataCount,'queryStringConcat'=>$queryStringConcat, 'appliedFilter' => $filter ,'i'=> (request()->input('page', 1) - 1) * $filter['per_page']]);
        }else{
            return view('admin/OperationalData/payment-method/index')->with(['error'=> $paymentDataList['message'],'header'=>$header,'paymentDataList'=>$paymentDataList, 'queryStringConcat'=>$queryStringConcat, 'appliedFilter' => $filter ,'i'=> (request()->input('page', 1) - 1) * $filter['per_page']]);
        }
    }

    /**
     * Show the form for creating a new payment method.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!hasPermission('PAYMENT_METHOD','create')){
            return view('admin/401');
        }
        $header['title'] = "Payment Method- Add";
        $header['heading'] = "Payment Method - Add";
        return view('admin/OperationalData/payment-method/add')->with(['header'=>$header]);
    }

    /**
     * Store a newly created payment method in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!hasPermission('PAYMENT_METHOD','create')){
            return view('admin/401');
        }
        $requestData = $request->only(['name','description','status']);
        
        $rules = [
            'name' => 'required',
            'description' => 'required|max:300',
            'status'=>'required',
        ];

        $customMessages = [
        ];

        $niceNames = array();

        $this->validate($request, $rules, $customMessages, $niceNames);
        
        $response = PaymentMethod::createPaymentMethod($requestData);

        $activityLog['request'] = $requestData;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $response;
        ActiveLog::createBackendActiveLog($activityLog);
        
        if(!empty($response['data'])){
            return redirect()->route('paymentmethod.index')->with('success',$response['message']);
        }else{
            return redirect()->route('paymentmethod.index')->with('error', $response['message']);
        }
    }

    /**
     * Display the specified payment method.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(!hasPermission('PAYMENT_METHOD','read')){
            return view('admin/401');
        }

        $header['title'] = 'Payment Method - View';
        $filter = array(
            'id' => $id
        );
        $response = PaymentMethod::getPeymentMethod($filter);
        $paymentDetail = $response['data'];

        $activityLog['request'] = $filter;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $response;
        ActiveLog::createBackendActiveLog($activityLog);

        if($response['status'] == 1 && !empty($response['data'])){
            return view('admin/OperationalData/payment-method/view')->with(['header'=>$header,'paymentDetail'=>$paymentDetail]);
        }else{
            return redirect()->route('paymentmethod.index')->with('error', $response['message']);
        }
    }

    /**
     * Show the form for editing the specified payment method.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(!hasPermission('PAYMENT_METHOD','update')){
            return view('admin/401');
        }
        $header['title'] = 'Payment Method - Edit';
        $filter = array(
            'id' => $id
        );
        $response = PaymentMethod::getPeymentMethod($filter);
        $paymentDetail = $response['data'];

        $activityLog['request'] = $filter;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $response;
        ActiveLog::createBackendActiveLog($activityLog);

        if($response['status'] == 1 && !empty($response['data'])){
            return view('admin/OperationalData/payment-method/update')->with(['header'=>$header,'paymentDetail'=>$paymentDetail]);
        }else{
            return redirect()->route('paymentmethod.index')->with('error', $response['message']);
        }
        
    }

    /**
     * Update the specified payment method in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(!hasPermission('PAYMENT_METHOD','update')){
            return view('admin/401');
        }
        $url = $request->only('redirects_to');
        $requestData = $request->only(['payment_type_id','name','description','status']);
        
        $rules = [];

            $customMessages = [];

            $niceNames = array();
            
        $this->validate($request, $rules, $customMessages, $niceNames);

        $response = PaymentMethod::updatePaymentMethod($requestData);

        $activityLog['request'] = $requestData;
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
     * Remove the specified payment method from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deletePaymentMethod(Request $request)
    {
        if(!hasPermission('PAYMENT_METHOD','delete')){
            return view('admin/401');
        }
        $url = URL::previous();
        $paymentIDs = explode(',',$request->input('payment_type_id'));
        $message = "";
        foreach($paymentIDs as $payment_type_id){
            $response = PaymentMethod::deletePayment($payment_type_id);
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
    public function checkExist(Request $request)
    {
        $matchListData = [];
        if(request()->input('name') && request()->input('name') != "") {
            if (request()->input('payment_type_id')) {
                $matchListData = PaymentMethod::where('name', request()->input('name'))->where('id', '!=', request()->input('payment_type_id'))->get()->toArray();
            } else {
                
                $matchListData = PaymentMethod::where('name', request()->input('name'))->where('is_active', '!=', 2)->get()->toArray();
            }

            
        }
        if (!empty($matchListData)) 
        {
            echo "false";
        } else {
            echo "true";
        }
    }
}
