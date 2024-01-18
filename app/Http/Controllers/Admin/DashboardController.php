<?php
/**
 * @package     Dashboard 
 * @subpackage  Dashboard 
 * @Author      Amar Technolabs Pvt. ltd(info@amarinfotech.com)
 * @Copyright(C) 2023 [NAME OF THE ORGANISATION THAT ON BEHALF OF THE CODE WE ARE WORKING].
 * @Version 1.0.0
 * module of the Dashboard.
 */
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Setting;
use DB;
use DateTime;

class DashboardController extends Controller
{
     /**
     * Display a listing of the dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
       
        $header['title'] = @trans('dashboard.title');
        $header['heading']=@trans('dashboard.moduleHeading');
        return view('admin/dashboard/dashboard')->with(['header' => $header]);
        
    }

    /**
     * Get record.
     *
     * @return \Illuminate\Http\Response
     */
    public function getRecord(Request $request)
    {
        $requetData = $request->all();
        
    }

     /**
     * Get duration.
     *
     * @return \Illuminate\Http\Response
     */
    public function getDuration(Request $request)
    {
        if($request->selected_day == 'today')
        {
            $fromDate = date('Y-m-d');
            $toDate = date('Y-m-d');
        }
        else if($request->selected_day == 'yesterday')
        {
            $today = new DateTime();
            $yesterday = $today->modify('-1 day');
            $yesterdayDate = $yesterday->format('Y-m-d');
            $fromDate = $yesterdayDate;
            $toDate = $yesterdayDate;
        }
        else if($request->selected_day == 'current_month')
        {
            $startOfMonth = new DateTime('first day of this month');
            $endOfMonth = new DateTime('last day of this month');
            $fromDate = $startOfMonth->format('Y-m-d');
            $toDate = $endOfMonth->format('Y-m-d');
        }
        else if($request->selected_day == 'last_month')
        {
            // Get the current date
            $now = new DateTime();
            $startOfLastMonth = (new DateTime())->modify('first day of last month');
            $endOfLastMonth = (new DateTime())->modify('last day of last month');

            $fromDate = $startOfLastMonth->format('Y-m-d');
            $toDate = $endOfLastMonth->format('Y-m-d');
        }
        else if($request->selected_day == 'last_six_month')
        {
            $now = new DateTime();
            $startOfLastSixMonths = (new DateTime())->modify('-6 months')->modify('first day of this month');
            $endOfLastSixMonths = (new DateTime())->modify('-1 month')->modify('last day of this month');
            $fromDate = $startOfLastSixMonths->format('Y-m-d');
            $toDate = $endOfLastSixMonths->format('Y-m-d');
        } 
        else if($request->selected_day == 'this_year')
        {
            $year = date('Y');
            $startOfYear = new DateTime("$year-01-01");
            $endOfYear = new DateTime("$year-12-31");
            $fromDate = $startOfYear->format('Y-m-d'); 
            $toDate = $endOfYear->format('Y-m-d');
        }
        $totalOrderCount = thousandsCurrencyFormat(Order::whereBetween(DB::raw('DATE(created_at)'), [$fromDate, $toDate])->count());
        $totalSaleGet = Order::whereBetWeen(DB::raw('DATE(created_at)'), [$fromDate, $toDate])->sum('total_amount');
        $totalProduct = Product::whereBetween(DB::raw('DATE(created_at)'), [$fromDate, $toDate])->count();
        $totalUser = User::whereBetween(DB::raw('DATE(created_at)'), [$fromDate, $toDate])->count();
        $data = [];
        $totalSaleNum = number_format($totalSaleGet);
        $totalSale = "Rs ".$totalSaleNum;
       
        $data = [
            'total_order' => $totalOrderCount,
            'total_sale' => $totalSale,
            'total_product' => $totalProduct,
            'total_user' => $totalUser
        ];
        // return json_encode($data);
        return response()->json($data);
        
    }
}
