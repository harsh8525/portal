<?php

/**
 * @package     B2C
 * @subpackage   Home Banner
 * @Author      Amar Technolabs Pvt. ltd(info@amarinfotech.com)
 * @Copyright(C) 2023 [Travel Portal].
 * @Version 1.0.0
 * module of the  Home Banner.
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HomeBanner;
use App\Models\Category;
use App\Models\Setting;
use Illuminate\Support\Facades\URL;
use Intervention\Image\ImageManagerStatic as Image;
use App\Traits\ActiveLog;
use Illuminate\Validation\Rule;

class HomeBannerController extends Controller
{
    /**
     * Display a listing of the home banner.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        //check for permission
        if (!hasPermission('HOME_BANNERS', 'read')) {
            return view('admin/401');
        }

        $header['title'] = @trans('homeBanner.title');
        $header['heading'] = @trans('homeBanner.moduleHeading');
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
            'banner_name' => (request()->input('banner_name') != NULL) ? request()->input('banner_name') : '',
            'sort_order' => (request()->input('sort_order') != NULL) ? request()->input('sort_order') : '',
            'status' => (request()->input('status') != NULL) ? request()->input('status') : '',
            'panel' => (request()->input('panel') != NULL) ? request()->input('panel') : '',
        );

        if (request()->input('banner_name') != NULL) {

            $filter['whereHas'][] = ['banner_title', 'like', '%' . request()->input('banner_name') . '%'];
        }

        if (request()->input('status') != NULL) {
            $filter['where'][] = ['home_banners.status', '=', request()->input('status')];
        }
        if (request()->input('panel') != NULL) {
            $filter['where'][] = ['home_banners.panel', '=', request()->input('panel')];
        }
        if (request()->input('from_date') != NULL && request()->input('to_date') != NULL) {

            $from_date = date('Y-m-d', strtotime(request()->input('from_date')));
            $to_date = date('Y-m-d', strtotime(request()->input('to_date')));
            $daysToAdd = 1;
            $to_dates = date('Y-m-d', strtotime(request()->input('to_date') . ' + ' . $daysToAdd . ' days'));
            $filter['where'][] = ['from_date', '>=', $from_date];
            $filter['where'][] = ['to_date', '<=', $to_date];
        }

        $bannerListData = HomeBanner::getBanners($filter);
        $bannerDataCount = HomeBanner::count();
        $bannerData = $bannerListData['data'];

        $activityLog['request'] = $filter;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $bannerListData;
        ActiveLog::createBackendActiveLog($activityLog);

        if ($bannerListData['status'] == 1) {
            return view('admin/home-banner/index')->with(['header' => $header, 'bannerData' => $bannerData, 'bannerDataCount' => $bannerDataCount, 'queryStringConcat' => $queryStringConcat, 'appliedFilter' => $filter, 'i' => (request()->input('page', 1) - 1) * $filter['per_page']]);
        } else {
            return view('admin/home-banner/index')->with(['error' => $bannerListData['message'], 'header' => $header, 'bannerData' => $bannerData, 'bannerDataCount' => $bannerDataCount, 'queryStringConcat' => $queryStringConcat, 'appliedFilter' => $filter, 'i' => (request()->input('page', 1) - 1) * $filter['per_page']]);
        }
    }

    /**
     * Show the form for creating a new home banner.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //check for permission
        if (!hasPermission('HOME_BANNERS', 'create')) {
            return view('admin/401');
        }
        $header['title'] = @trans('homeBanner.addHomeBanner');
        $homeBnner = HomeBanner::where('status', 1)->get()->toArray();

        $activityLog['request'] = [];
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = [];
        ActiveLog::createBackendActiveLog($activityLog);

        return view('admin/home-banner/add')->with(['header' => $header, 'homeBnner' => $homeBnner]);
    }

    /**
     * Store a newly created home banner in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!hasPermission('HOME_BANNERS', 'create')) {
            return view('admin/401');
        }

        $requestData = $request->all();

        $rules = [ ];

        $customMessages = [];

        $niceNames = array();

        $response = HomeBanner::createBanner($requestData);

        $activityLog['request'] = $requestData;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $response;
        ActiveLog::createBackendActiveLog($activityLog);

        if (!empty($response['data'])) {
            return redirect()->route('home-banner.index')->with('success', $response['message']);
        } else {
            return redirect()->route('home-banner.index')->with('error', $response['message']);
        }
    }

    /**
     * Display the specified home banner.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!hasPermission('HOME_BANNERS', 'read')) {
            return view('admin/401');
        }

        $header['title'] = 'Home Banner - View';
        $filter = array(
            'id' => $id
        );
        $response = HomeBanner::getBanners($filter);
        $bannerDetail = $response['data'];

        $activityLog['request'] = $filter;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $response;
        ActiveLog::createBackendActiveLog($activityLog);

        if ($response['status'] == 1 && !empty($response['data'])) {
            return view('admin/home-banner/view')->with(['header' => $header, 'bannerDetail' => $bannerDetail]);
        } else {
            return redirect()->route('home-banner.index')->with('error', $response['message']);
        }
    }

    /**
     * Show the form for editing the specified home banner.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!hasPermission('HOME_BANNERS', 'update')) {
            return view('admin/401');
        }
        $header['title'] = 'Home Banner - Edit';
        $header['method'] = 'Edit';
        $filter = array(
            'id' => $id,
        );
        $response = HomeBanner::getBanners($filter);
        $bannerDetail = $response['data'];

        $activityLog['request'] = $filter;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $response;
        ActiveLog::createBackendActiveLog($activityLog);

        if ($response['status'] == 1 && !empty($response['data'])) {
            return view('admin/home-banner/update')->with(['header' => $header, 'bannerDetail' => $bannerDetail]);
        } else {
            return redirect()->route('home-banner.index')->with('error', $response['message']);
        }
    }

    /**
     * Update the specified home banner in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!hasPermission('HOME_BANNERS', 'update')) {
            return view('admin/401');
        }
        $url = $request->only('redirects_to');
        $requestData = $request->all();


        $rules = [];

        $customMessages = [];

        $niceNames = array();

        $this->validate($request, $rules, $customMessages, $niceNames);

        $response = HomeBanner::updateBanner($requestData);

        $activityLog['request'] = $requestData;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $response;
        ActiveLog::createBackendActiveLog($activityLog);

        if (!empty($response['data'])) {
            return redirect()->to($url['redirects_to'])->with('success', $response['message']);
        } else {
            return redirect()->to($url['redirects_to'])->with('error', $response['message']);
        }
    }

    /**
     * Remove the specified home banner from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteBanner(Request $request)
    {
        if (!hasPermission('HOME_BANNERS', 'delete')) {
            return view('admin/401');
        }
        $url = URL::previous();
        $bannerIDs = explode(',', $request->input('banner_id'));

        $message = "";
        foreach ($bannerIDs as $banner_id) {
            $response = HomeBanner::deleteBanner($banner_id);
            $message .= $response['message'] . '</br>';
        }

        $activityLog['request'] = $request->all();
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $response;
        ActiveLog::createBackendActiveLog($activityLog);

        if ($response['status'] == 1) {
            return redirect()->to($url)->with('success', $message);
        } else {
            return redirect()->to($url)->with('error', $response['message']);
        }
    }
}
