<?php

/**
 * @package     Operational Data
 * @subpackage  Agency Type
 * @Author      Amar Technolabs Pvt. ltd(info@amarinfotech.com)
 * @Copyright(C) 2023 [Travel Portal].
 * @Version 1.0.0
 * module of the Agency Type.
 */

namespace App\Http\Controllers\Admin\OperationalData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\AgencyType;
use App\Traits\ActiveLog;
use URL;
class AgencyTypeController extends Controller
{
    /**
     * Display a listing of the agency type.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         if(!hasPermission('AGENCY_TYPE','read')){
            return view('admin/401');
        }
        $header['title'] = "Agency Type";
        $header['heading'] = "Agency Type";
        $queryStringConcat = '?';

        if(isset($_GET['per_page'])){            
            $queryStringConcat .= ($queryStringConcat == '') ? '?per_page='.$_GET['per_page'] : '&per_page='.$_GET['per_page'].$_SERVER['QUERY_STRING'];
        }
        if(isset($_GET['page'])){
            $queryStringConcat .= ($queryStringConcat == '') ? '?page='.$_GET['page'] : '&page='.$_GET['page'].$_SERVER['QUERY_STRING'] ;
        }  

        $filter = array(
            'per_page' => (request()->input('per_page') != NULL) ? request()->input('per_page') : Setting::where('config_key', 'general|setting|pagePerAdminRecords')->get('value')[0]['value'],
            'order_by' => (request()->input('order_by') != NULL) ? request()->input('order_by') : 'id',
            'sorting' => (request()->input('sorting') != NULL) ? request()->input('sorting') : 'desc',
            'agency_name' => (request()->input('agency_name') != NULL) ? request()->input('agency_name') : '',
            'status' => (request()->input('status') != NULL) ? request()->input('status') : '',
            
        );
        
        if(request()->input('agency_name') != NULL){
            $filter['where'][] = [ 'name','like','%'.request()->input('agency_name').'%'];
        }
        
        if(request()->input('status') != NULL) {
            $filter['where'][] = [ 'is_active','=',request()->input('status')];
        }
        
        $agencyTypeDataList = AgencyType::getAgencyType($filter);    
        $agencyTypeDataCount = AgencyType::count();    
        $agencyTypeData = $agencyTypeDataList['data'];

        $activityLog['request'] =  $filter;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] =  $agencyTypeDataList;
        ActiveLog::createBackendActiveLog($activityLog);
        
        if($agencyTypeDataList['status'] == 1){
            return view('admin/OperationalData/agency-type/index')->with(['header'=>$header,'agencyTypeData'=>$agencyTypeData,'agencyTypeDataCount'=>$agencyTypeDataCount, 'queryStringConcat'=>$queryStringConcat, 'appliedFilter' => $filter ,'i'=> (request()->input('page', 1) - 1) * $filter['per_page']]);
        }else{
            return view('admin/OperationalData/agency-type/index')->with(['error'=> $agencyTypeDataList['message'],'header'=>$header,'agencyTypeData'=>$agencyTypeData,'agencyTypeDataCount'=>$agencyTypeDataCount,'queryStringConcat'=>$queryStringConcat, 'appliedFilter' => $filter ,'i'=> (request()->input('page', 1) - 1) * $filter['per_page']]);
        }
    }

    /**
     * Show the form for creating a new agency type.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!hasPermission('AGENCY_TYPE','create')){
            return view('admin/401');
        }
        $header['title'] = "Agency Type - Add";
        $header['heading'] = "Agency Type - Add";

        $activityLog['request'] =  [];
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] =  [];
        ActiveLog::createBackendActiveLog($activityLog);

        return view('admin/OperationalData/agency-type/add')->with(['header'=>$header]); 
    }

    /**
     * Store a newly created agency type in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!hasPermission('AGENCY_TYPE','create')){
            return view('admin/401');
        }

        $requestData = $request->only(['agency_name','agency_description','status','code']);
        
        $rules = [
            'agency_name' => 'required',
            'agency_description' => 'required|max:300',
            'status'=>'required',
        ];

        $customMessages = [
        ];

        $niceNames = array();

        $this->validate($request, $rules, $customMessages, $niceNames);
        
        $response = AgencyType::createAgencyType($requestData);

        $activityLog['request'] =  $requestData;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] =  $response;
        ActiveLog::createBackendActiveLog($activityLog);
        
        if(!empty($response['data'])){
            return redirect()->route('agency-type.index')->with('success',$response['message']);
        }else{
            return redirect()->route('agency-type.index')->with('error', $response['message']);
        }
    }

    /**
     * Display the specified agency type.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(!hasPermission('AGENCY_TYPE','read')){
            return view('admin/401');
        }
        $header['title'] = 'Agency Type - View';
        $filter = array(
            'id' => $id
        );
        $response = AgencyType::getAgencyType($filter);
        $agencyDetail = $response['data'];

        $activityLog['request'] =  $filter;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] =  $response;
        ActiveLog::createBackendActiveLog($activityLog);

        if($response['status'] == 1 && !empty($response['data'])){
            return view('admin/OperationalData/agency-type/view')->with(['header'=>$header,'agencyDetail'=>$agencyDetail]);
        }else{
            return redirect()->route('agency-type.index')->with('error', $response['message']);
        }
    }
    /**
     * Show the form for editing the specified agency type.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(!hasPermission('AGENCY_TYPE','update')){
            return view('admin/401');
        }
        $header['title'] = 'Agency Type - Edit';
        $header['method'] = 'Edit';
        
        $filter = array(
            'id' => $id,
        );
        $response = AgencyType::getAgencyType($filter);
        $agencyTypeDetail = $response['data'];

        $activityLog['request'] =  $filter;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] =  $response;
        ActiveLog::createBackendActiveLog($activityLog);
        
        if($response['status'] == 1 && !empty($response['data'])){
            return view('admin/OperationalData/agency-type/update')->with(['header'=>$header,'agencyTypeDetail'=>$agencyTypeDetail]);
        }else{
            return redirect()->route('agency-type.index')->with('error', $response['message']);
        }
    }

    /**
     * Update the specified agency type in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(!hasPermission('AGENCY_TYPE','update')){
            return view('admin/401');
        }
        $url = $request->only('redirects_to');
        $requestData = $request->only(['agency_type_id','agency_name','agency_description','status']);
        
        $rules = [
                
            ];

            $customMessages = [
                ];

            $niceNames = array();
            
        $this->validate($request, $rules, $customMessages, $niceNames);
        $response = AgencyType::updateAgencyType($requestData);

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
     * Remove the specified agency type from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteAgency(Request $request)
    {
        if(!hasPermission('AGENCY_TYPE','delete')){
            return view('admin/401');
        }
        $url = URL::previous();
        $agencyIDs = explode(',',$request->input('agency_id'));
        
        $message = "";
        foreach($agencyIDs as $agency_id){
            $response = AgencyType::deleteagency($agency_id);
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

    /**
     * Check agency name exist in agency type.
     *
     * @return \Illuminate\Http\Request
     */
    public function checkExist(Request $request)
    {
        $matchListData = [];
        if(request()->input('agencyName') && request()->input('agencyName') != "") {
            if (request()->input('agency_type_id')) {
                $matchListData = AgencyType::where('name', request()->input('agencyName'))->where('id', '!=', request()->input('agency_type_id'))->get()->toArray();
            } else {
                
                $matchListData = AgencyType::where('name', request()->input('agencyName'))->where('is_active', '!=', 2)->get()->toArray();
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
