<?php

 /**
 * @package     Operational Data
 * @subpackage   Service Type
 * @Author      Amar Technolabs Pvt. ltd(info@amarinfotech.com)
 * @Copyright(C) 2023 [Travel Portal].
 * @Version 1.0.0
 * module of the Service Type.
 */


namespace App\Http\Controllers\Admin\OperationalData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\ServiceType;
use App\Traits\ActiveLog;
use URL;

class ServiceTypeController extends Controller
{
    /**
     * Display a listing of the service type.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!hasPermission('SERVICE_TYPE','read')){
            return view('admin/401');
        }
       $header['title']="Service Type";
       $header['heading']="Service Type";
       $queryStringConcat="?";

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
            'service_name' => (request()->input('service_name') != NULL) ? request()->input('service_name') : '',
            'status' => (request()->input('status') != NULL) ? request()->input('status') : '',

        );
        if(request()->input('service_name') != NULL){
            $filter['where'][] = [ 'name','like','%'.request()->input('service_name').'%'];
        }
        if(request()->input('description')!= NULL){
            $filter['where'][] = [ 'description','like','%'.request()->input('description').'%'];
        }
        if(request()->input('guideline')!= NULL){
            $filter['where'][] = [ 'guideline','like','%'.request()->input('guideline').'%'];
        }
       
        if(request()->input('status') != NULL) {
            $filter['where'][] = [ 'is_active','=',request()->input('status')];
        }
        
        $serviceTypeDataList = ServiceType::getServiceType($filter);  
        $serviceTypeCount = ServiceType::count();

        $serviceTypeData = $serviceTypeDataList['data'];

        $activityLog['request'] = $filter;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $serviceTypeDataList;
        ActiveLog::createBackendActiveLog($activityLog);

        if($serviceTypeDataList['status'] == 1){
            return view('admin/OperationalData/service-type/index')->with(['header'=>$header,'serviceTypeData'=>$serviceTypeData,'serviceTypeCount'=>$serviceTypeCount,'queryStringConcat'=>$queryStringConcat, 'appliedFilter' => $filter ,'i'=> (request()->input('page', 1) - 1) * $filter['per_page']]);
        }else{
            return view('admin/OperationalData/service-type/index')->with(['error'=> $serviceTypeDataList['message'],'header'=>$header,'serviceTypeData'=>$serviceTypeData,
            'serviceTypeCount'=>$serviceTypeCount,'queryStringConcat'=>$queryStringConcat, 'appliedFilter' => $filter ,'i'=> (request()->input('page', 1) - 1) * $filter['per_page']]);
        }
    }

    /**
     * Show the form for creating a new service type.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!hasPermission('SERVICE_TYPE','create')){
            return view('admin/401');
        }
        $header['title'] = "Service Type -Add";
        $header['heading'] = "Service Type - Add";

        $activityLog['request'] = [];
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = [];
        ActiveLog::createBackendActiveLog($activityLog);

        return view('admin/OperationalData/service-type/add')->with(['header'=>$header]);
    }

    /**
     * Store a newly created service type in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!hasPermission('SERVICE_TYPE','create')){
            return view('admin/401');
        }
        $requestData=$request->only(['service_name','service_description','guideline','profile_image','status']);

        $rules = [
            'service_name' => 'required',
            'service_description' => 'required|max:300',
            'guideline'=>'required',
            'status'=>'required',
        ];

        $customMessages = [
        ];

        $niceNames = array();

        $this->validate($request, $rules, $customMessages, $niceNames);
        
        $response = ServiceType::createServiceType($requestData);

        $activityLog['request'] = $requestData;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $response;
        ActiveLog::createBackendActiveLog($activityLog);
        
        if(!empty($response['data'])){
            return redirect()->route('service-type.index')->with('success',$response['message']);
        }else{
            return redirect()->route('service-type.index')->with('error', $response['message']);
        }
    }

    /**
     * Display the specified service type.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(!hasPermission('SERVICE_TYPE','read')){
            return view('admin/401');
        }
        $header['title'] = 'Service Type - View';
        $filter = array(
            'id' => $id
        );
        $response = ServiceType::getServiceType($filter);
        $serviceDetail = $response['data'];

        $activityLog['request'] = $filter;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $response;
        ActiveLog::createBackendActiveLog($activityLog);

        if($response['status'] == 1 && !empty($response['data'])){
            return view('admin/OperationalData/service-type/view')->with(['header'=>$header,'serviceDetail'=>$serviceDetail]);
        }else{
            return redirect()->route('service-type.index')->with('error', $response['message']);
        }
    }

    /**
     * Show the form for editing the specified service type.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(!hasPermission('SERVICE_TYPE','update')){
            return view('admin/401');
        }

        $header['title'] = 'Service Type - Edit';
        $header['method'] = 'Edit';
        $filter = array(
            'id' => $id,
        );

        $response = ServiceType::getServiceType($filter);
        $serviceTypeDetail = $response['data'];

        $activityLog['request'] = $filter;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $response;
        ActiveLog::createBackendActiveLog($activityLog);
        
        if($response['status'] == 1 && !empty($response['data'])){
            return view('admin/OperationalData/service-type/update')->with(['header'=>$header,'serviceTypeDetail'=>$serviceTypeDetail]);
        }else{
            return redirect()->route('service-type.index')->with('error', $response['message']);
        }
    }

    /**
     * Update the specified service type in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(!hasPermission('SERVICE_TYPE','update')){
            return view('admin/401');
        }
        $url = $request->only('redirects_to');
        $requestData=$request->only('service_type_id','service_name','service_description','guideline','profile_image','old_image','status');
        $rules=[];
        $customMessages=[];
        $niceNames=array();

        $this->validate($request, $rules,  $customMessages, $niceNames);
        
        $response = ServiceType::updateServiceType($requestData);

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
     * Remove the specified service type from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteService(Request $request)
    {
        if(!hasPermission('SERVICE_TYPE','delete')){
            return view('admin/401');
        }
        $url = URL::previous();
        $serviceIDs = explode(',',$request->input('service_id'));
        
        $message = "";
        foreach($serviceIDs as $service_id){
            $response = ServiceType::deleteService($service_id);
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
     * Check service name exist in service type.
     *
     * @return \Illuminate\Http\Request
     */
    public function checkExist(Request $request)
    {
        $matchListData = [];
        if(request()->input('serviceName') && request()->input('serviceName') != "") {
            if (request()->input('service_type_id')) {
                $matchListData = ServiceType::where('name', request()->input('serviceName'))->where('id', '!=', request()->input('service_type_id'))->get()->toArray();
            } else {
                
                $matchListData = ServiceType::where('name', request()->input('serviceName'))->where('is_active', '!=', 2)->get()->toArray();
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
