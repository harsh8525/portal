<?php

/**
 * @package     Reports
 * @subpackage   User Report
 * @Author      Amar Technolabs Pvt. ltd(info@amarinfotech.com)
 * @Copyright(C) 2023 [Travel Portal].
 * @Version 1.0.0
 * module of the  User Report.
 */

namespace App\Http\Controllers\Admin\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\UserType;
use App\Models\ServiceType;
use App\Models\User;
use App\Models\Agency;
use App\Models\UserAddress;
use App\Models\UserPaymentType;
use App\Models\UserServiceType;
use App\Models\PaymentGateway;
use App\Models\UserPaymentGateway;
use App\Models\Setting;
use App\Models\Country;
use App\Models\UserCurrency;
use Illuminate\Support\Str;
use App\Exports\UserExport;
use App\Exports\UserNewExport;
use Carbon\Carbon;
use App\Traits\ActiveLog;
use App\Traits\EmailService;
use Illuminate\Support\Facades\Hash;
use DB;
use App;

class UserReportController extends Controller
{
    /**
     * The displayed listing is the result of applying a filter to the user report.
     *
     * @return \Illuminate\Http\Request
     */
    public function index(Request $request)
    {
        $header['title'] = "Report";
        $header['heading'] = "Report";

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
            'mobile_verified' => (request()->input('mobile_verified') != NULL) ? request()->input('mobile_verified') : '',
            'status' => (request()->input('status') != NULL) ? request()->input('status') : '',

        );
        $pagination = $filter['per_page'];
        $transactionDate = 'today';
        $fromDate = date('Y-m-d');
        $toDate = date('Y-m-d');
        $user_status = '';
        if (request()->input('status') && request()->input('status') == 1) {
            $user_status = 'Active';
        }else if (request()->input('status') && request()->input('status') == 0) {
            $user_status = 'Inactive';
        }else{

            $user_status =request()->input('status');
        }
        $user_name = @$_GET['name'];
        $agency_id = @$_GET['agency_id'];

        if (isset($_GET['transactionDate']) && $_GET['transactionDate'] != "") {
            $transactionDate = $_GET['transactionDate'];
            if ($transactionDate == 'all_dates') {
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
        if ($transactionDate != 'all_dates') {
            $filter['dates'][] = [$fromDate, $toDate];
        }
        if (request()->input('name') != NULL) {
            $filter['where'][] = ['name', 'like', '%' . request()->input('name') . '%'];
        }
        if (request()->input('phone_no') != NULL) {
            $filter['where'][] = ['phone_no', 'like', '%' . request()->input('phone_no') . '%'];
        }
        if (request()->input('agency_id') != NULL) {
            $filter['where'][] = ['agency_id', '=', request()->input('agency_id')];
        }
        if (request()->input('status') != NULL) {
            $filter['where'][] = ['status', '=', request()->input('status')];
        }
        $userData = User::getMasterUserReport($filter, $pagination, false, true);
        $userName = User::get();
        $agencyData = Agency::get()->toArray();
        $agency_name = Agency::where('id', $agency_id)->value('full_name');

        $activityLog['request'] = $filter;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $userData;
        ActiveLog::createBackendActiveLog($activityLog);
        
        return view('admin/reports/userReport/user-report')->with([
            'header' => $header,
            'userData' => $userData,
            'agencyData' => $agencyData,
            'queryStringConcat' => $queryStringConcat,
            'transactionDate' => $transactionDate,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
            'agency_name' => $agency_name,
            'userName' => $userName,
            'user_status' => $user_status,
            'user_name' => $user_name,
            'agency_id' => $request->agency_id,
            'i' => (request()->input('page', 1) - 1) * $filter['per_page']
        ]);
    }

      /**
    * Generates an export of the master user report based on the provided request parameters.
    *
    * @return \Illuminate\Http\Request
    */
    public function generateMasterUserReportExport(Request $request)
    {

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
            'mobile_verified' => (request()->input('mobile_verified') != NULL) ? request()->input('mobile_verified') : '',
            'status' => (request()->input('status') != NULL) ? request()->input('status') : '',

        );
        $transactionDate = 'today';
        $fromDate = date('Y-m-d');
        $toDate = date('Y-m-d');

        $user_status = @$_GET['user_status'];
        $user_name = @$_GET['user_name'];
        $agency_name = @$_GET['agency_name'];

        if (isset($_GET['transactionDate']) && $_GET['transactionDate'] != "") {
            $transactionDate = $_GET['transactionDate'];
            if ($transactionDate == 'all_dates') {
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
        if ($transactionDate != 'all_dates') {
            $filter['dates'][] = [$fromDate, $toDate];
        }
        if (request()->input('first_name') != NULL) {
            $filter['where'][] = ['first_name', 'like', '%' . request()->input('first_name') . '%'];
        }
        if (request()->input('user_name') != NULL) {
            $filter['where'][] = ['name', 'like', '%' . request()->input('user_name') . '%'];
        }
        
        if (request()->input('agency_id') != NULL) {
            $filter['where'][] = ['agency_id', '=', request()->input('agency_id')];
        }
        if (request()->input('user_status') != NULL) {
            $filter['where'][] = ['status', '=', request()->input('user_status')];
        }

        $activityLog['request'] = $filter;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = '';
        ActiveLog::createBackendActiveLog($activityLog);

        ob_end_clean();
        ob_start(); 
        return Excel::download(new UserExport($filter, $transactionDate, $fromDate, $toDate, $user_status, $user_name, $agency_name), 'User-report_' . date('Y-m-d') . '.xls');
    }

      /**
    * Generates an pdf of the master user report based on the provided request parameters.
    *
    * @return \Illuminate\Http\Request
    */
    public function generateMasterUserReportPdf(Request $request){
        $header['title'] = 'User List Reports';
        $header['heading'] = 'User List Reports - List';

        //fetch today date range report
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
            'mobile_verified' => (request()->input('mobile_verified') != NULL) ? request()->input('mobile_verified') : '',
            'status' => (request()->input('status') != NULL) ? request()->input('status') : '',

        );
        $transactionDate = 'today';
        $fromDate = date('Y-m-d');
        $toDate = date('Y-m-d');

        $user_status = @$_GET['user_status'];
        if($user_status == 'Active'){
            $status = 1;
        }
        if($user_status == 'Inactive'){
            $status = 0;
        }

        $user_name = @$_GET['user_name'];
        $agency_name = @$_GET['agency_name'];

        if (isset($_GET['transactionDate']) && $_GET['transactionDate'] != "") {
            $transactionDate = $_GET['transactionDate'];
            if ($transactionDate == 'all_dates') {
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
        if ($transactionDate != 'all_dates') {
            $filter['dates'][] = [$fromDate, $toDate];
        }
        if (request()->input('first_name') != NULL) {
            $filter['where'][] = ['first_name', 'like', '%' . request()->input('first_name') . '%'];
        }
        if (request()->input('user_name') != NULL) {
            $filter['where'][] = ['name', 'like', '%' . request()->input('user_name') . '%'];
        }
        
        if (request()->input('agency_id') != NULL) {
            $filter['where'][] = ['agency_id', '=', request()->input('agency_id')];
        }
        if (request()->input('user_status') != NULL) {
            $filter['where'][] = ['status', '=', $status];
        }
        $userData = User::getMasterUserReport($filter,false);
        
        $activityLog['request'] = $filter;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $userData;
        ActiveLog::createBackendActiveLog($activityLog);

        $view = view('admin/reports/userReport/generatePDF')->with(compact('header','userData','fromDate','toDate','transactionDate','user_name','agency_name','user_status'));
        $html = $view->render();
        $pdf = App::make('dompdf.wrapper');
        $customPaper = array(0,0,80,500);
        $pdf->set_paper('A4', 'landscape');
        
        $pdf->loadHTML($html);
        return $pdf->stream();
    }
}
