<?php

/**
 * @package     Reports
 * @subpackage   Log Report
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
use App\Models\CustomerActivityLog;
use App\Models\BackendCustomerActivityLog;
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
use App\Exports\LogExport;
use App\Exports\BackendLogExport;
use App\Exports\UserNewExport;
use Carbon\Carbon;
use App\Traits\EmailService;
use Illuminate\Support\Facades\Hash;
use DB;
use App;

class LogReportController extends Controller
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
            'status' => (request()->input('status') != NULL) ? request()->input('status') : '',

        );
        $pagination = $filter['per_page'];
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

        $logData = CustomerActivityLog::getLogReport($filter, $pagination, false, true);

        return view('admin/reports/logReport/log-report')->with([
            'header' => $header,
            'logData' => $logData,
            'queryStringConcat' => $queryStringConcat,
            'transactionDate' => $transactionDate,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
            'i' => (request()->input('page', 1) - 1) * $filter['per_page']
        ]);
    }

    /**
     * Generates an export of the master user report based on the provided request parameters.
     *
     * @return \Illuminate\Http\Request
     */
    public function generateLogReportExport(Request $request)
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

        ob_end_clean();
        ob_start();
        return Excel::download(new LogExport($filter, $transactionDate, $fromDate, $toDate), 'Log-report_' . date('Y-m-d') . '.xls');
    }

    /**
     * Generates an pdf of the master user report based on the provided request parameters.
     *
     * @return \Illuminate\Http\Request
     */
    public function generateLogReportPdf(Request $request)
    {
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
            'status' => (request()->input('status') != NULL) ? request()->input('status') : '',

        );
        $transactionDate = 'today';
        $fromDate = date('Y-m-d');
        $toDate = date('Y-m-d');

        $user_status = @$_GET['user_status'];
        if ($user_status == 'Active') {
            $status = 1;
        }
        if ($user_status == 'Inactive') {
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
        $logData = CustomerActivityLog::getLogReport($filter, false);
        ini_set('max_execution_time', -1);
        ini_set('memory_limit', '1024M'); // or a higher value

        $view = view('admin/reports/logReport/generatePDF')->with(compact('header', 'logData', 'fromDate', 'toDate', 'transactionDate'));
        $html = $view->render();
        $pdf = App::make('dompdf.wrapper');
        $customPaper = array(0, 0, 80, 500);
        $pdf->set_paper('A4', 'landscape');

        $pdf->loadHTML($html);
        return $pdf->stream();
    }


    /**
     * The displayed listing is the result of applying a filter to the Frontend log report.
     *
     * @return \Illuminate\Http\Request
     */
    public function backendlogReport(Request $request)
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
            'status' => (request()->input('status') != NULL) ? request()->input('status') : '',

        );
        $pagination = $filter['per_page'];
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

        $logData = BackendCustomerActivityLog::getLogReport($filter, $pagination, false, true);
        // echo "<pre>";print_r($logData);die;
        return view('admin/reports/logReport/backend-log-report')->with([
            'header' => $header,
            'logData' => $logData,
            'queryStringConcat' => $queryStringConcat,
            'transactionDate' => $transactionDate,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
            'i' => (request()->input('page', 1) - 1) * $filter['per_page']
        ]);
    }
    /**
     * Generates an pdf of the master user report based on the provided request parameters.
     *
     * @return \Illuminate\Http\Request
     */
    public function generateBackendLogReportPdf(Request $request)
    {
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
            'status' => (request()->input('status') != NULL) ? request()->input('status') : '',

        );
        $transactionDate = 'today';
        $fromDate = date('Y-m-d');
        $toDate = date('Y-m-d');

        $user_status = @$_GET['user_status'];
        if ($user_status == 'Active') {
            $status = 1;
        }
        if ($user_status == 'Inactive') {
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
        $logData = BackendCustomerActivityLog::getLogReport($filter, false);
        ini_set('max_execution_time', -1);
        ini_set('memory_limit', '1024M'); // or a higher value

        $view = view('admin/reports/logReport/backendGeneratePDF')->with(compact('header', 'logData', 'fromDate', 'toDate', 'transactionDate'));
        $html = $view->render();
        $pdf = App::make('dompdf.wrapper');
        $customPaper = array(0, 0, 80, 500);
        $pdf->set_paper('A4', 'landscape');

        $pdf->loadHTML($html);
        return $pdf->stream();
    }

    /**
     * Generates an export of the master user report based on the provided request parameters.
     *
     * @return \Illuminate\Http\Request
     */
    public function generateBackendLogReportExport(Request $request)
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

        ob_end_clean();
        ob_start();
        return Excel::download(new BackendLogExport($filter, $transactionDate, $fromDate, $toDate), 'Backend-Log-report_' . date('Y-m-d') . '.xls');
    }
}
