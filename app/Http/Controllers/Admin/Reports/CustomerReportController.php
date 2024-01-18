<?php

/**
 * @package     Reports
 * @subpackage   Customer Report
 * @Author      Amar Technolabs Pvt. ltd(info@amarinfotech.com)
 * @Copyright(C) 2023 [Travel Portal].
 * @Version 1.0.0
 * module of the  Customer Report.
 */

namespace App\Http\Controllers\Admin\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\CustomerType;
use App\Models\ServiceType;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\CustomerPaymentType;
use App\Models\CustomerServiceType;
use App\Models\PaymentGateway;
use App\Models\CustomerPaymentGateway;
use App\Models\Setting;
use App\Models\User;
use App\Models\Country;
use App\Models\CustomerCurrency;
use Illuminate\Support\Str;
use App\Exports\CustomerExport;
use App\Exports\CustomerNewExport;
use Carbon\Carbon;
use App\Traits\EmailService;
use Illuminate\Support\Facades\Hash;
use DB;
use App;
use App\Traits\ActiveLog;

class CustomerReportController extends Controller
{
     /**
     * The displayed listing is the result of applying a filter to the customer report.
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
            'full_name' => (request()->input('full_name') != NULL) ? request()->input('full_name') : '',
            'status' => (request()->input('status') != NULL) ? request()->input('status') : '',

        );
        $pagination = $filter['per_page'];
        $transactionDate = 'today';
        $fromDate = date('Y-m-d');
        $toDate = date('Y-m-d');
        $fullName = @$_GET['full_name'];
        $needle    = ' ';

        if (isset($_GET['transactionDate']) && $_GET['transactionDate'] != "") {
            $transactionDate = $_GET['transactionDate'];
            if ($transactionDate == 'all_dates') {
                $fromDate = '';
                $toDate = '';
            }
        }
        if (request()->input('full_name') != NULL) {
            $fullName = request()->input('full_name');
            $filter['where'][] = [
                DB::raw("CONCAT(first_name, ' ', last_name)"),
                'LIKE',
                '%' . $fullName . '%'
            ];
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
        if (request()->input('phone_no') != NULL) {
            $filter['where'][] = ['phone_no', 'like', '%' . request()->input('phone_no') . '%'];
        }
        if (request()->input('status') != NULL) {
            $filter['where'][] = ['status', '=', request()->input('status')];
        }
        $customerData = Customer::getMasterCustomerReport($filter, $pagination, false, true);
        $custData = Customer::get()->toArray();

        $activityLog['request'] = $filter;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $customerData;
        ActiveLog::createBackendActiveLog($activityLog);

        return view('admin/reports/customerReport/customer-report')->with([
            'header' => $header,
            'customerData' => $customerData,
            'queryStringConcat' => $queryStringConcat,
            'transactionDate' => $transactionDate,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
            'custData' => $custData,
            'appliedFilter' => $filter,
            'fullName' => $fullName,
            'i' => (request()->input('page', 1) - 1) * $filter['per_page']
        ]);
    }

     /**
    * Generates an export of the master order report based on the provided request parameters.
    *
    * @return \Illuminate\Http\Request
    */
    public function generateMasterCustomerReportExport(Request $request)
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
        $f_name = @$_GET['f_name'];
        $f_name = @$_GET['f_name'];
        $needle    = ' ';
        $first_name = strstr($f_name, $needle, true);
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
        if ($first_name != NULL && $first_name != 0) {
            $filter['where'][] = ['first_name', 'like', '%' . $first_name . '%'];
        }
        if (request()->input('phone_no') != NULL) {
            $filter['where'][] = ['phone_no', 'like', '%' . request()->input('phone_no') . '%'];
        }
        if (request()->input('status') != NULL) {
            $filter['where'][] = ['status', '=', request()->input('status')];
        }
        
        $activityLog['request'] = $filter;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = '';
        ActiveLog::createBackendActiveLog($activityLog);
        ob_end_clean();
        ob_start();
        return Excel::download(new CustomerExport($filter, $transactionDate, $fromDate, $toDate, $first_name,$f_name), 'Customer-report_' . date('Y-m-d') . '.xls');
    }

     /**
    * Generates an export of the master customer report based on the provided request parameters.
    *
    * @return \Illuminate\Http\Request
    */
    public function generateMasterCustomerReportPdf(Request $request){
        $header['title'] = 'Customer List Reports';
        $header['heading'] = 'Customer List Reports - List';

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
        $f_name = @$_GET['f_name'];
        $needle    = ' ';
        $first_name = strstr($f_name, $needle, true);

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
        if (request()->input('f_name') != NULL && request()->input('f_name') != 0) {
            $filter['where'][] = ['first_name', 'like', '%' . $first_name . '%'];
        }
        if (request()->input('phone_no') != NULL) {
            $filter['where'][] = ['phone_no', 'like', '%' . request()->input('phone_no') . '%'];
        }
        if (request()->input('status') != NULL) {
            $filter['where'][] = ['status', '=', request()->input('status')];
        }

        $customerData = Customer::getMasterCustomerReport($filter,false);
        
        $activityLog['request'] = $filter;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $customerData;
        ActiveLog::createBackendActiveLog($activityLog);

        $view = view('admin/reports/customerReport/generatePDF')->with(compact('header','customerData','fromDate','toDate','transactionDate','first_name','f_name'));
        $html = $view->render();
        $pdf = App::make('dompdf.wrapper');
        $customPaper = array(0,0,80,500);
        $pdf->set_paper('A4', 'landscape');
        $pdf->loadHTML($html);
        return $pdf->stream();
    }
}
