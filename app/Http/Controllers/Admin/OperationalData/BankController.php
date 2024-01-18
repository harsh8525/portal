<?php

/**
 * @package     Operational Data
 * @subpackage  Banks.
 * @Author      Amar Technolabs Pvt. ltd(info@amarinfotech.com)
 * @Copyright(C) 2023 [Travel Portal].
 * @Version 1.0.0
 * module of the Banks.
 */

namespace App\Http\Controllers\Admin\OperationalData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Bank;
use App\Traits\ActiveLog;
use Illuminate\Support\Facades\URL;

class BankController extends Controller
{
    /**
     * Display a listing of the bank.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!hasPermission('BANKS','read')){
            return view('admin/401');
        }

        $header['title'] = "Banks";
        $header['heading'] = "Banks";
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
            'bank_name' => (request()->input('bank_name') != NULL) ? request()->input('bank_name') : '',
            'status' => (request()->input('status') != NULL) ? request()->input('status') : '',
            
        );
        
        if(request()->input('bank_name') != NULL){
            $filter['where'][] = [ 'bank_name','like','%'.request()->input('bank_name').'%'];
        }

        if(request()->input('bank_code') != NULL){
            $filter['where'][] = [ 'bank_code','like','%'.request()->input('bank_code').'%'];
        }

        if(request()->input('account_number') != NULL){
            $filter['where'][] = [ 'account_number','like','%'.request()->input('account_number').'%'];
        }

        if(request()->input('status') != NULL) {
            $filter['where'][] = [ 'status','=',request()->input('status')];
        }
        
        $bankTypeDataList = Bank::getBankType($filter);    
        $bankCountData = Bank::count();
        $bankTypeData = $bankTypeDataList['data'];

        $activityLog['request'] =  $filter;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] =  $bankTypeDataList;
        ActiveLog::createBackendActiveLog($activityLog);
        
        if($bankTypeDataList['status'] == 1){
            return view('admin/OperationalData/banks/index')->with(['header'=>$header,'bankTypeData'=>$bankTypeData,'bankCountData'=>$bankCountData, 'queryStringConcat'=>$queryStringConcat, 'appliedFilter' => $filter ,'i'=> (request()->input('page', 1) - 1) * $filter['per_page']]);
        }else{
            return view('admin/OperationalData/banks/index')->with(['error'=> $bankTypeDataList['message'],'header'=>$header,'bankTypeData'=>$bankTypeData,'bankCountData'=>$bankCountData, 'queryStringConcat'=>$queryStringConcat, 'appliedFilter' => $filter ,'i'=> (request()->input('page', 1) - 1) * $filter['per_page']]);
        }
    }

    /**
     * Show the form for creating a new bank.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!hasPermission('BANKS','create')){
            return view('admin/401');
        }

        $header['title'] = "Bank Type - Add";
        $header['heading'] = "Bank Type - Add";

        $activityLog['request'] =  [];
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] =  [];
        ActiveLog::createBackendActiveLog($activityLog);

        return view('admin/OperationalData/banks/add')->with(['header'=>$header]); 
    }

    /**
     * Store a newly created bank in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!hasPermission('BANKS','create')){
            return view('admin/401');
        }

        $requestData = $request->only(['bank_code','beneficiary_name','account_number','bank_name','bank_address','swift_code',
        'iban_number','sort_code','status']);
        
        $rules = [
            'bank_code' => 'required',
            'beneficiary_name' => 'required',
            'account_number' => 'required',
            'bank_name' => 'required',
            'bank_address' => 'required',
            'swift_code' => 'required',
            'iban_number' => 'required',
            'sort_code' => 'required',
            'status'=>'required',
        ];

        $customMessages = [
        ];

        $niceNames = array();

        $this->validate($request, $rules, $customMessages, $niceNames);
        
        $response = Bank::createBankType($requestData);

        $activityLog['request'] =  $requestData;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] =  $response;
        ActiveLog::createBackendActiveLog($activityLog);
        
        if(!empty($response['data'])){
            return redirect()->route('banks.index')->with('success',$response['message']);
        }else{
            return redirect()->route('banks.index')->with('error', $response['message']);
        }
    }

    /**
     * Display the specified bank.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(!hasPermission('BANKS','read')){
            return view('admin/401');
        }

        $header['title'] = 'Banks Type - View';
        $filter = array(
            'id' => $id
        );
        $response = Bank::getBankType($filter);
        $bankDetail = $response['data'];

        $activityLog['request'] =  $filter;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] =  $response;
        ActiveLog::createBackendActiveLog($activityLog);

        if($response['status'] == 1 && !empty($response['data'])){
            return view('admin/OperationalData/banks/view')->with(['header'=>$header,'bankDetail'=>$bankDetail]);
        }else{
            return redirect()->route('banks.index')->with('error', $response['message']);
        }
    }

    /**
     * Show the form for editing the specified bank.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(!hasPermission('BANKS','update')){
            return view('admin/401');
        }

        $header['title'] = 'Banks - Edit';
        $header['method'] = 'Edit';
        
        $filter = array(
            'id' => $id,
        );
        $response = Bank::getBankType($filter);
        $banksTypeDetail = $response['data'];

        $activityLog['request'] =  $filter;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] =  $response;
        ActiveLog::createBackendActiveLog($activityLog);
        
        if($response['status'] == 1 && !empty($response['data'])){
            return view('admin/OperationalData/banks/update')->with(['header'=>$header,'banksTypeDetail'=>$banksTypeDetail]);
        }else{
            return redirect()->route('banks.index')->with('error', $response['message']);
        }
    }

    /**
     * Update the specified bank in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(!hasPermission('BANKS','update')){
            return view('admin/401');
        }
        $url = $request->only('redirects_to');
        $requestData = $request->only(['bank_type_id','bank_code','account_number','beneficiary_name','bank_name','bank_address',
        'swift_code','iban_number','sort_code','status']);
        
        $rules = [
                
            ];

            $customMessages = [
                ];

            $niceNames = array();
            
            $this->validate($request, $rules, $customMessages, $niceNames);
        
        $response = Bank::updateBankType($requestData);

        $activityLog['request'] =  $requestData;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] =  $response;
        ActiveLog::createBackendActiveLog($activityLog);
        
        if(!empty($response['data'])){
            return redirect()->to($url['redirects_to'])->with('success',$response['message']);
        }else{
            return redirect()->to($url['redirects_to'])->with('error', $response['message']);
        }
    }

    /**
     * Remove the specified bank from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deletebank(Request $request)
    {
        if(!hasPermission('BANKS','delete')){
            return view('admin/401');
        }
        $url = URL::previous();
        $bankIDs = explode(',',$request->input('bank_type_id'));
        
        $message = "";
        foreach($bankIDs as $bank_type_id){
            $response = Bank::deletebank($bank_type_id);
            $message .= $response['message'].'</br>';
        }

        $activityLog['request'] =  $request->all();
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] =  $response;
        ActiveLog::createBackendActiveLog($activityLog);
        
        if($response['status'] == 1){
            return redirect()->to($url)->with('success',$message);
        }else{
            return redirect()->to($url)->with('error', $response['message']);
        }
    }
}
