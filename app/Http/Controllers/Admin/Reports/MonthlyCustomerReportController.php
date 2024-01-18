<?php

/**
 * @package     Reports
 * @subpackage   Monthly Customer Report
 * @Author      Amar Technolabs Pvt. ltd(info@amarinfotech.com)
 * @Copyright(C) 2023 [Travel Portal].
 * @Version 1.0.0
 * module of the  Monthly Customer Report.
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
use App\Exports\MonthlyCustomerExport;
use App\Exports\CustomerNewExport;
use Carbon\Carbon;
use App\Traits\EmailService;
use Illuminate\Support\Facades\Hash;
use App\Traits\ActiveLog;
use DB;
use App;

class MonthlyCustomerReportController extends Controller
{
    /**
     * The displayed listing is the result of applying a filter to the monthly customer report.
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
        $transactionDate = 'today';
        $fromDate = date('Y-m-d');
        $toDate = date('Y-m-d');

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
        if (request()->input('phone_no') != NULL) {
            $filter['where'][] = ['phone_no', 'like', '%' . request()->input('phone_no') . '%'];
        }
        if (request()->input('status') != NULL) {
            $filter['where'][] = ['status', '=', request()->input('status')];
        }
        // Query the database to get customer data for the last year
        $result = Customer::whereBetween('created_at', [$fromDate, $toDate])
            ->selectRaw('DATE_FORMAT(created_at, "%M, %Y") as month, COUNT(id) as count')
            ->groupBy(DB::raw('DATE_FORMAT(created_at, "%M, %Y")'))
            ->get();

        $customerData = Customer::getMasterMonthlyCustomerReport($fromDate, $toDate);
        $agencyData = Customer::get()->toArray();

        $activityLog['request'] = $filter;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $customerData;
        ActiveLog::createBackendActiveLog($activityLog);

        
        return view('admin/reports/monthlyCustomerReport/monthly-customer-report')->with([
            'header' => $header,
            'customerData' => $customerData,
            'result' => $result,
            'queryStringConcat' => $queryStringConcat,
            'transactionDate' => $transactionDate,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
            'first_name' => $request->first_name,
            'i' => (request()->input('page', 1) - 1) * $filter['per_page']
        ]);
    }

     /**
    * Generates an export of the master monthly customer report based on the provided request parameters.
    *
    * @return \Illuminate\Http\Request
    */
    public function generateMasterMonthlyCustomerReportExport(Request $request)
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
        return Excel::download(new MonthlyCustomerExport($filter, $transactionDate, $fromDate, $toDate), 'Monthly-Customer-report_' . date('Y-m-d') . '.xls');
    }

     /**
    * Generates an pdf of the master monthly customer report based on the provided request parameters.
    *
    * @return \Illuminate\Http\Request
    */
    public function generateMasterMonthlyCustomerReportPdf(Request $request){
        $header['title'] = 'Customer List Reports';
        $header['heading'] = 'Customer List Reports - List';

       
        $transactionDate = 'today';
        $fromDate = date('Y-m-d');
        $toDate = date('Y-m-d');

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
       
        $customerData = Customer::getMasterMonthlyCustomerReport($fromDate,$toDate);

        $activityLog['request'] = $request->all();
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $customerData;
        ActiveLog::createBackendActiveLog($activityLog);

        $view = view('admin/reports/monthlyCustomerReport/generatePDF')->with(compact('header','customerData','fromDate','toDate','transactionDate'));
        $html = $view->render();
        $pdf = App::make('dompdf.wrapper');
        $customPaper = array(0,0,80,500);
        $pdf->set_paper('A4', 'landscape');
        $pdf->loadHTML($html);
        return $pdf->stream();
    }
}
