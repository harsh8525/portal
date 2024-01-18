<?php

  /**
 * @package     Operational Data
 * @subpackage   Suppliers
 * @Author      Amar Technolabs Pvt. ltd(info@amarinfotech.com)
 * @Copyright(C) 2023 [Travel Portal].
 * @Version 1.0.0
 * module of the  Suppliers.
 */


namespace App\Http\Controllers\Admin\OperationalData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Suppliers;
use App\Models\ServiceType;
use App\Traits\ActiveLog;
use URL;

class SuppliersController extends Controller
{
    /**
     * Display a listing of the supplier.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function index()
    {
        if(!hasPermission('SUPPLIERS','read')){
            return view('admin/401');
        }
        $header['title'] = "Suppliers";
        $header['heading'] = "Suppliers";
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
            'suppliers_name' => (request()->input('suppliers_name') != NULL) ? request()->input('suppliers_name') : '',
            'status' => (request()->input('status') != NULL) ? request()->input('status') : '',
            
        );
        if(request()->input('suppliers_name') != NULL){
            $filter['where'][] = [ 'core_suppliers.name','like','%'.request()->input('suppliers_name').'%'];
        }
        
        if(request()->input('status') != NULL) {
            $filter['where'][] = [ 'core_suppliers.is_active','=',request()->input('status')];
        }
        
        $supplierDataList = Suppliers::getSuppliers($filter);
        $supplierDataCount = Suppliers::count();
        $supplierData = $supplierDataList['data'];
        
        $activityLog['request'] = $filter;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $supplierDataList;
        ActiveLog::createBackendActiveLog($activityLog);

        if($supplierDataList['status'] == 1){
            return view('admin/OperationalData/suppliers/index')->with(['header'=>$header,'supplierData'=>$supplierData,'supplierDataCount'=>$supplierDataCount, 'queryStringConcat'=>$queryStringConcat, 'appliedFilter' => $filter ,'i'=> (request()->input('page', 1) - 1) * $filter['per_page']]);
        }else{
            return view('admin/OperationalData/suppliers/index')->with(['error'=> $supplierDataList['message'],'header'=>$header,'supplierData'=>$supplierData,'supplierDataCount'=>$supplierDataCount, 'queryStringConcat'=>$queryStringConcat, 'appliedFilter' => $filter ,'i'=> (request()->input('page', 1) - 1) * $filter['per_page']]);
        }
    }

    /**
     * Show the form for creating a new supplier.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!hasPermission('SUPPLIERS','create')){
            return view('admin/401');
        }

        $header['title'] = "Suppliers - Add";
        $header['heading'] = "Suppliers - Add";
        $dataServiceType = ServiceType::where('is_active','1')->get()->toArray();

        $activityLog['request'] = [];
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = [];
        ActiveLog::createBackendActiveLog($activityLog);

        return view('admin/OperationalData/suppliers/add')->with(['header'=>$header,'dataServiceType'=>$dataServiceType]);
    }

    /**
     * Store a newly created supplier in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request)
    {
        if(!hasPermission('SUPPLIERS','create')){
            return view('admin/401');
        }

        $requestData=$request->all();

        $rules = [
            'supplier_name' => 'required',
            'status'=>'required',
            'core_service_type_id'=>'required',
        ];

        $customMessages = [
        ];

        $niceNames = array();

        $this->validate($request, $rules, $customMessages, $niceNames);
        
        $response = Suppliers::createSupplier($requestData);

        $activityLog['request'] = $requestData;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $response;
        ActiveLog::createBackendActiveLog($activityLog);
        
        if(!empty($response['data'])){
            return redirect()->route('suppliers.index')->with('success',$response['message']);
        }else{
            return redirect()->route('suppliers.index')->with('error', $response['message']);
        }
    }

    /**
     * Display the specified supplier.
     *
     * @param  int  $id
     */
    public function show($id)
    {
        if(!hasPermission('SUPPLIERS','read')){
            return view('admin/401');
        }

        $header['title'] = 'Suppliers - View';
        $filter = array(
            'id' => $id
        );
        $response = Suppliers::getSuppliers($filter);
        $supplierDetail = $response['data'];

        $activityLog['request'] = $filter;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $response;
        ActiveLog::createBackendActiveLog($activityLog);

        if($response['status'] == 1 && !empty($response['data'])){

            return view('admin/OperationalData/suppliers/view')->with(['header'=>$header,'supplierDetail'=>$supplierDetail]);
        }else{
            return redirect()->route('suppliers.index')->with('error', $response['message']);
        }
    }

    /**
     * Show the form for editing the specified supplier.
     *
     * @param  int  $id
     */
    public function edit($id)
    {
        if(!hasPermission('SUPPLIERS','update')){
            return view('admin/401');
        }

        $header['title'] = 'Suppliers - Edit';
        $header['method'] = 'Suppliers - Edit';

        $filter = array(
            'id' => $id,
        );
        $dataServiceType = ServiceType::where('is_active','1')->get()->toArray();
        $response = Suppliers::getSuppliers($filter);
        $supplierDetail = $response['data'];

        $activityLog['request'] = $filter;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $response;
        ActiveLog::createBackendActiveLog($activityLog);

        if($response['status'] == 1 && !empty($response['data'])){
            return view('admin/OperationalData/suppliers/update')->with(['header'=>$header,'supplierDetail'=>$supplierDetail,'dataServiceType'=>$dataServiceType]);
        }else{
            return redirect()->route('suppliers.index')->with('error', $response['message']);
        }
    }

    /**
     * Update the specified supplier in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     */
    public function update(Request $request, $id)
    {
        if(!hasPermission('SUPPLIERS','update')){
            return view('admin/401');
        }
        $url = $request->only('redirects_to');
        $requestData=$request->all();
        $rules=[];
        $customMessages=[];
        $niceNames=array();

        $this->validate($request, $rules,  $customMessages, $niceNames);
        
        $response = Suppliers::updateSupplier($requestData);

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
     * Remove the specified supplier from storage.
     *
     * @return \Illuminate\Http\Request
     */
    public function deletesupplier(Request $request)
    {
        if(!hasPermission('SUPPLIERS','delete')){
            return view('admin/401');
        }
        $url = URL::previous();
        $supplierIDs = explode(',',$request->input('supplier_id'));
       
        $message = "";
        foreach($supplierIDs as $supplier_id){
            $response = Suppliers::deleteSupplier($supplier_id);
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
     * Check supplier name exist in supplier.
     *
     * @return \Illuminate\Http\Request
     */
    public function checkExist(Request $request)
    {
        $matchListData = [];
        if(request()->input('supplierName') && request()->input('supplierName') != "") {
            if (request()->input('supplier_type_id')) {
                $matchListData = Suppliers::where('name', request()->input('supplierName'))->where('id', '!=', request()->input('supplier_type_id'))->get()->toArray();
            } else {
                
                $matchListData = Suppliers::where('name', request()->input('supplierName'))->orWhere('code', request()->input('supplierName'))->where('is_active', '!=', 2)->get()->toArray();
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
