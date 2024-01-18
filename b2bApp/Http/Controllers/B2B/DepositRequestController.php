<?php
/**
 * @package     Deposit 
 * @subpackage  Deposit Request
 * @Author      Amar Technolabs Pvt. ltd(info@amarinfotech.com)
 * @Copyright(C) 2023 [Travel Portal].
 * @Version 1.0.0
 * module of the Deposit.
 */

namespace B2BApp\Http\Controllers\B2B;

use B2BApp\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use App\Models\PaymentGateway;
use App\Models\Setting;
use App\Models\Agency;
use Illuminate\Support\Facades\URL;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Validation\Rule;
use Auth;
use App\Models\User;


class DepositRequestController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * created by shyam poriya
     * created date 30-06-2023
     */
    public function index()
    {
        // if(!hasPermission('PAYMENT_GATEWAY','read')){
        //     return view('admin/401');
        // }
         $header['title']= "Deposit Request";
        $header['heading']= "Payment Gateway";
        
        $queryStringConcat = '?';
        //print_r($_GET);die;
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
        
        // $paymentListData = PaymentGateway::getPayment($filter);    
        // echo "<pre>";print_r($paymentListData);die();
        // $paymentData = $paymentListData['data'];
        
        //  if($paymentListData['status'] == 1){
        //     return view('admin/OperationalData/payment-gateway/index')->with(['header'=>$header,'paymentData'=>$paymentData, 'queryStringConcat'=>$queryStringConcat, 'appliedFilter' => $filter ,'i'=> (request()->input('page', 1) - 1) * $filter['per_page']]);
        // }else{
        //     return view('admin/OperationalData/payment-gateway/index')->with(['error'=> $paymentListData['message'],'header'=>$header,'paymentData'=>$paymentData, 'queryStringConcat'=>$queryStringConcat, 'appliedFilter' => $filter ,'i'=> (request()->input('page', 1) - 1) * $filter['per_page']]);
        // }
          return view('b2b/deposit-request/index')->with(['header'=>$header]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     * created by shyam poriya
     * created date 30-06-2023
      */
    public function create()
    {
        // if(!hasPermission('PAYMENT_GATEWAY','create')){
        //     return view('admin/401');
        // }
        $userId =  Auth::guard('b2b')->user()->agency_id;
        $agencyName = Agency::select('agency_id','full_name')->where('id',$userId)->get()->toArray();
        // echo "<pre>";print_r($agencyName);die;
        
        
        $header['title']= "Deposit Request - Add";
        $header['heading']= "Deposit Request - Add";

        return view('b2b/deposit-request/add')->with(['header'=>$header,'agencyName'=>$agencyName]);
    }

//     /**
//      * Store a newly created resource in storage - Insert Form Data.
//      *
//      * @param  \Illuminate\Http\Request  $request
//      * @return \Illuminate\Http\Response
//      * created by shyam poriya 
//      * created date 30-06-2023
//      */
//     public function store(Request $request)
//     {
//         if(!hasPermission('PAYMENT_GATEWAY','create')){
//             return view('admin/401');
//         }

//         // echo "454545hello";die();
//         $requestData = $request->all();
//         // echo '<pre>';print_r($requestData);die;
//         $rules = [
//                 // 'mobile' => 'required|unique:app_users|max:10',
//             ];

//             $customMessages = [
//                 ];

//             $niceNames = array();
            
//             // $this->validate($customMessages, $niceNames);
        
//         $response = PaymentGateway::createPayment($requestData);
        
//         if(!empty($response['data'])){
//             return redirect()->route('payment-gateway.index')->with('success',$response['message']);
//         }else{
//             return redirect()->route('payment-gateway.index')->with('error', $response['message']);
//         }
//     }

//     /**
//      * Display the specified resource.
//      *
//      * @param  int  $id
//      * @return \Illuminate\Http\Response
//      * created by shyam poriya 
//      * created date 30-06-2023
//      */
    public function show($id)
    {
        // echo "123456789";die;
        // if(!hasPermission('PAYMENT_GATEWAY','read')){
        //     return view('admin/401');
        // }
        $header['title'] = 'Deposit Request - View';
        $header['heading'] = 'Deposit Request - View';
       return view('b2b/deposit-request/view')->with(['header'=>$header]);
        /*$filter = array(
            'id' => $id
        );
        $response = PaymentGateway::getPayment($filter);
        $paymentDetail = $response['data'];
        
        if($response['status'] == 1 && !empty($response['data'])){
//            echo "<pre>"; print_r($userDetail);die;
            return view('b2b/deposit-request/view')->with(['header'=>$header,'paymentDetail'=>$paymentDetail]);
        }else{
            return redirect()->route('payment-gateway.index')->with('error', $response['message']);
        }
*/
    }

//     /**
//      * Show the form for editing the specified resource.
//      *
//      * @param  int  $id
//      * @return \Illuminate\Http\Response
//      * created by shyam poriya 
//      * created date 30-06-2023
//      */
//     public function edit($id)
//     {
//         if(!hasPermission('PAYMENT_GATEWAY','update')){
//             return view('admin/401');
//         }
        
//         $header['title'] = 'Payement Gateway - Edit';
//         $header['heading'] = 'Payment Gateway - Edit';
//         $header['method'] = 'Edit';
//         $filter = array(
//             'id' => $id,
//         );
//         $response = PaymentGateway::getPayment($filter);
      
//         $paymentDetail = $response['data'];
        
//         if($response['status'] == 1 && !empty($response['data'])){
//             //    echo "<pre>"; print_r($bannerDetail);die;
//             return view('admin/OperationalData/payment-gateway/update')->with(['header'=>$header,'paymentDetail'=>$paymentDetail]);
//         }else{
//             return redirect()->route('payment-gateway.index')->with('error', $response['message']);
//         }
//     }

//     /**
//      * Update the specified resource in storage.
//      *
//      * @param  \Illuminate\Http\Request  $request
//      * @param  int  $id
//      * @return \Illuminate\Http\Response
//      * created by shyam poriya 
//      * created date 30-06-2023
//      */
//     public function update(Request $request, $id)
//     {
//         if(!hasPermission('PAYMENT_GATEWAY','update')){
//             return view('admin/401');
//         }
//         $url = $request->only('redirects_to');
//          $requestData = $request->only(['payment_id','name','description','status','logo','profile_image','old_photo']);
//         // echo "<pre>";print_r($requestData);die;
//         $rules = [
                
//             ];

//             $customMessages = [
//                 ];

//             $niceNames = array();
            
//             $this->validate($request, $rules, $customMessages, $niceNames);
        
//     //    print_r($requestData);die;
//         $response = PaymentGateway::updatePayment($requestData);
        
//         if(!empty($response['data'])){
//             return redirect()->to($url['redirects_to'])->with('success',$response['message']);
//         }else{
//             return redirect()->to($url['redirects_to'])->with('error', $response['message']);
//         }
//     }

//     /**
//      * Remove the specified resource from storage.
//      *
//      * @param  int  $id
//      * @return \Illuminate\Http\Response
//      */
//     public function destroy($id)
//     {
//         //
//     }


//     /**
//     * Delete Multiple Record 
//     * created by shyam poriya 
//     * created date 30-06-2023
//     */
//      public function deletePayment(Request $request){    
        
//         if(!hasPermission('PAYMENT_GATEWAY','delete')){
//             return view('admin/401');
//         }
//         $url = URL::previous();
//         $paymentIDs = explode(',',$request->input('payment_id'));
        
//         $message = "";
//         foreach($paymentIDs as $payment_id){
//             $response = PaymentGateway::deletePayment($payment_id);
//             $message .= $response['message'].'</br>';
//         }
        
//         if($response['status'] == 1){
//             return redirect()->to($url)->with('success',$message);
//         }else{
//             return redirect()->to($url)->with('error', $response['message']);
//         }
        
//     }
//      public function checkExistName(Request $request) {
//         $matchListData = [];
//         if (request()->input('name') && request()->input('name') != "") {
//             if (request()->input('payment_id')) {
//                 $matchListData = PaymentGateway::where('name', request()->input('name'))->where('id', '!=', request()->input('payment_id'))->get()->toArray();
               
//             } else {
//                 $matchListData = PaymentGateway::where('name', request()->input('name'))->where('is_active', '!=', '2')->get()->toArray();
//             }
//         }
//         if (!empty($matchListData)) {
//             echo "false";
//         } else {
//             echo "true";
//         }
//     }
}
