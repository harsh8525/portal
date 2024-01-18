<?php

/**
 * @package     Reports
 * @subpackage   Agency Report
 * @Author      Amar Technolabs Pvt. ltd(info@amarinfotech.com)
 * @Copyright(C) 2023 [Travel Portal].
 * @Version 1.0.0
 * module of the  Agency Report.
 */

namespace App\Http\Controllers\Admin\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
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
use App\Exports\AgencyExport;
use App\Exports\AgencyNewExport;
use Carbon\Carbon;
use App\Traits\EmailService;
use Illuminate\Support\Facades\Hash;
use DB;
use App\Traits\ActiveLog;
use App;
use PDF;

class AgencyReportController extends Controller
{

     /**
     * The displayed listing is the result of applying a filter to the agency report.
     *
     * @return \Illuminate\Http\Request
     */
    public function index(Request $request)
    {
        
        $header['title'] = "Report";
        $header['heading'] = "Report";
        
        $queryStringConcat = '?';
        if(isset($_GET['per_page'])){            
            $queryStringConcat .= ($queryStringConcat == '') ? '?per_page='.$_GET['per_page'] : '&per_page='.$_GET['per_page'] ;
        }
        if(isset($_GET['page'])){
            $queryStringConcat .= ($queryStringConcat == '') ? '?page='.$_GET['page'] : '&page='.$_GET['page'] ;
        }
        
        $filter = array(
            'per_page' => (request()->input('per_page') != NULL) ? request()->input('per_page') : Setting::where('config_key', 'general|setting|pagePerAdminRecords')->get('value')[0]['value'],
            'order_by' => (request()->input('order_by') != NULL) ? request()->input('order_by') : 'created_at',
            'sorting' => (request()->input('sorting') != NULL) ? request()->input('sorting') : 'desc',
            'mobile_verified' => (request()->input('mobile_verified') != NULL) ? request()->input('mobile_verified') : '',
            'status' => (request()->input('status') != NULL) ? request()->input('status') : '',

        );
        $transactionDate = 'today';
        $fromDate = date('Y-m-d');
        $toDate = date('Y-m-d');
        $agency_type = @$_GET['name'];
        $agency_name = @$_GET['full_name'];
        $agency_status = @$_GET['status'];
      
        if (isset($_GET['transactionDate']) && $_GET['transactionDate'] != "") {
            $transactionDate = $_GET['transactionDate'];
            if($transactionDate == 'all_dates'){
                $fromDate = '';
                $toDate = '';
            }
        }
        if (isset($_GET['fromDate']) && $_GET['fromDate'] != "") {
            $fromDate = date('Y-m-d', strtotime($_GET['fromDate']));
        }
        if (isset($_GET['toDate']) && $_GET['toDate'] != "") {
            $toDate = date('Y-m-d', strtotime($_GET['toDate']));
        }
        if($transactionDate != 'all_dates'){
            $filter['dates'][] = [$fromDate,$toDate];
        }
        if (request()->input('full_name') != NULL) {
            $filter['where'][] = ['full_name', 'like', '%' . request()->input('full_name') . '%'];
        }
        if (request()->input('phone_no') != NULL) {
            $filter['where'][] = ['phone_no', 'like', '%' . request()->input('phone_no') . '%'];
        }
        if (request()->input('name') != NULL) {
            $filter['where'][] = ['core_agency_type_id', '=', request()->input('name')];
        }
        if (request()->input('status') != NULL) {
            $filter['where'][] = ['status', '=', request()->input('status')];
        }
        $pagination = $filter['per_page'];
        $orderTotalAmount = Agency::getMasterAgencyReport($filter,$pagination, false, true);
        $agencyData = Agency::get()->toArray();
        $agencyType = AgencyType::where('id',$agency_type)->value('name');
        $agencyTypeName = AgencyType::get()->toArray();

        $activityLog['request'] = $filter;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $orderTotalAmount;
        ActiveLog::createBackendActiveLog($activityLog);

            return view('admin/reports/agencyReport/agency-report')->with([
                'header'=>$header,
                'orderTotalAmount' => $orderTotalAmount,
                'queryStringConcat'=>$queryStringConcat,
                'transactionDate' => $transactionDate,
                'fromDate' => $fromDate,
                'toDate' => $toDate,
                'agency_type' => $agency_type,
                'agency_name' => $agency_name,
                'agency_status' => $agency_status,
                'agencyTypeName' => $agencyTypeName,
                'appliedFilter' => $filter ,
                'agencyData' => $agencyData ,
                'agencyType' => $agencyType ,
                'full_name' => $request->full_name,
                 'i'=> (request()->input('page', 1) - 1) * $filter['per_page']]);

    }

    /**
    * Generates an export of the master order report based on the provided request parameters.
    *
    * @return \Illuminate\Http\Request
    */
    public function generateMasterOrderReportExport(Request $request){
        $queryStringConcat = '?';
        if(isset($_GET['per_page'])){            
            $queryStringConcat .= ($queryStringConcat == '') ? '?per_page='.$_GET['per_page'] : '&per_page='.$_GET['per_page'] ;
        }
        if(isset($_GET['page'])){
            $queryStringConcat .= ($queryStringConcat == '') ? '?page='.$_GET['page'] : '&page='.$_GET['page'] ;
        }
        

        $filter = array(
            'per_page' => (request()->input('per_page') != NULL) ? request()->input('per_page') : Setting::where('config_key', 'general|setting|pagePerAdminRecords')->get('value')[0]['value'],
            'order_by' => (request()->input('order_by') != NULL) ? request()->input('order_by') : 'created_at',
            'sorting' => (request()->input('sorting') != NULL) ? request()->input('sorting') : 'desc',
            'mobile_verified' => (request()->input('mobile_verified') != NULL) ? request()->input('mobile_verified') : '',
            'status' => (request()->input('status') != NULL) ? request()->input('status') : '',

        );
        $transactionDate = 'today';
        $fromDate = date('Y-m-d');
        $toDate = date('Y-m-d');
        $agency_type = @$_GET['agency_type'];
        $agencyType = @$_GET['agencyType'];
        $agency_name = @$_GET['agency_name'];
        $agency_status = @$_GET['agency_status'];
        
           
        if (isset($_GET['transactionDate']) && $_GET['transactionDate'] != "") {
            $transactionDate = $_GET['transactionDate'];
            if($transactionDate == 'all_dates'){
                $fromDate = '';
                $toDate = '';
            }
        }
        if (isset($_GET['fromDate']) && $_GET['fromDate'] != "") {
            $fromDate = date('Y-m-d', strtotime($_GET['fromDate']));
        }
        if (isset($_GET['toDate']) && $_GET['toDate'] != "") {
            $toDate = date('Y-m-d', strtotime($_GET['toDate']));
        }
        if($transactionDate != 'all_dates'){
            $filter['dates'][] = [$fromDate,$toDate];
        }
        if (request()->input('agency_name') != NULL) {
            $filter['where'][] = ['full_name', 'like', '%' . request()->input('agency_name') . '%'];
        }
        if (request()->input('phone_no') != NULL) {
            $filter['where'][] = ['phone_no', 'like', '%' . request()->input('phone_no') . '%'];
        }
        if (request()->input('agency_type') != NULL) {
            $filter['where'][] = ['core_agency_type_id', '=', request()->input('agency_type')];
        }
        if (request()->input('agency_status') != NULL) {
            $filter['where'][] = ['status', '=', request()->input('agency_status')];
        }
        ob_end_clean();
        ob_start();
        return Excel::download(new AgencyExport($filter,$transactionDate,$fromDate,$toDate,$agency_type,$agency_name,$agency_status,$agencyType), 'Agency-report_'.date('Y-m-d').'.xls');
    }
    public function generateMasterAgencyReportPdf(Request $request){
        $header['title'] = 'Agency List Reports';
        $header['heading'] = 'Agency List Reports - List';

        //fetch today date range report
        $queryStringConcat = '?';
        if(isset($_GET['per_page'])){            
            $queryStringConcat .= ($queryStringConcat == '') ? '?per_page='.$_GET['per_page'] : '&per_page='.$_GET['per_page'] ;
        }
        if(isset($_GET['page'])){
            $queryStringConcat .= ($queryStringConcat == '') ? '?page='.$_GET['page'] : '&page='.$_GET['page'] ;
        }
        

        $filter = array(
            'per_page' => (request()->input('per_page') != NULL) ? request()->input('per_page') : Setting::where('config_key', 'general|setting|pagePerAdminRecords')->get('value')[0]['value'],
            'order_by' => (request()->input('order_by') != NULL) ? request()->input('order_by') : 'created_at',
            'sorting' => (request()->input('sorting') != NULL) ? request()->input('sorting') : 'desc',
            'mobile_verified' => (request()->input('mobile_verified') != NULL) ? request()->input('mobile_verified') : '',
            'status' => (request()->input('status') != NULL) ? request()->input('status') : '',

        );
        $transactionDate = 'today';
        $fromDate = date('Y-m-d');
        $toDate = date('Y-m-d');
        $agency_type = @$_GET['agency_type'];
        $agencyType = @$_GET['agencyType'];
        $agency_name = @$_GET['agency_name'];
        $agency_status = @$_GET['agency_status'];
        
           
        if (isset($_GET['transactionDate']) && $_GET['transactionDate'] != "") {
            $transactionDate = $_GET['transactionDate'];
            if($transactionDate == 'all_dates'){
                $fromDate = '';
                $toDate = '';
            }
        }
        if (isset($_GET['fromDate']) && $_GET['fromDate'] != "") {
            $fromDate = date('Y-m-d', strtotime($_GET['fromDate']));
        }
        if (isset($_GET['toDate']) && $_GET['toDate'] != "") {
            $toDate = date('Y-m-d', strtotime($_GET['toDate']));
        }
        if($transactionDate != 'all_dates'){
            $filter['dates'][] = [$fromDate,$toDate];
        }
        if (request()->input('agency_name') != NULL) {
            $filter['where'][] = ['full_name', 'like', '%' . request()->input('agency_name') . '%'];
        }
        if (request()->input('phone_no') != NULL) {
            $filter['where'][] = ['phone_no', 'like', '%' . request()->input('phone_no') . '%'];
        }
        if (request()->input('agency_type') != NULL) {
            $filter['where'][] = ['core_agency_type_id', '=', request()->input('agency_type')];
        }
        if (request()->input('agency_status') != NULL) {
            $filter['where'][] = ['status', '=', request()->input('agency_status')];
        }
        $agencyData = Agency::getMasterAgencyReport($filter,false);
        
        $activityLog['request'] = $filter;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $agencyData;
        ActiveLog::createBackendActiveLog($activityLog);
        
        $view = view('admin/reports/agencyReport/generatePDF')->with(compact('header','agencyData','fromDate','toDate','transactionDate','agency_type','agencyType','agency_name','agency_status'));
        $html = $view->render();
        $pdf = App::make('dompdf.wrapper');
        $customPaper = array(0,0,80,500);
        $pdf->set_paper('A4', 'landscape');
        $pdf->loadHTML($html);
        return $pdf->stream();
    }
}
