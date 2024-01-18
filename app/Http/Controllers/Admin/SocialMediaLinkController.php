<?php

/**
 * @package     B2C
 * @subpackage   Social Media Link
 * @Author      Amar Technolabs Pvt. ltd(info@amarinfotech.com)
 * @Copyright(C) 2023 [Travel Portal].
 * @Version 1.0.0
 * module of the  Social Media Link.
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use App\Models\SocialMediaLink;
use App\Models\Setting;
use App\Traits\ActiveLog;
use Illuminate\Support\Facades\URL;
use Maatwebsite\Excel\Facades\Excel;
use DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class SocialMediaLinkController extends Controller
{
    /**
     * Display a listing of the Social Media Link.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!hasPermission('Social_Media_Link', 'read')) {
            return view('admin/401');
        }
        $header['title'] = "Social Media Links";
        $header['heading'] = "Social Media Links";
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
            'name' => (request()->input('name') != NULL) ? request()->input('name') : '',
            'status' => (request()->input('status') != NULL) ? request()->input('status') : '',
        );

        if (request()->input('name') != NULL) {
            $filter['where'][] = ['name', 'like', '%' . request()->input('name') . '%'];
        }

        if (request()->input('status') != NULL) {
            $filter['where'][] = ['status', '=', request()->input('status')];
        }

        $socialMediaLinkDataList = SocialMediaLink::getSocialMediaLinkData($filter);
        $socialMediaLinkDataCount = SocialMediaLink::count();
        $socialMediaLinkData = $socialMediaLinkDataList['data'];

        $activityLog['request'] =  $filter;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] =  $socialMediaLinkDataList;
        ActiveLog::createBackendActiveLog($activityLog);

        if ($socialMediaLinkDataList['status'] == 1) {
            return view('admin/social-media-link/index')->with(['header' => $header, 'socialMediaLinkData' => $socialMediaLinkData, 'socialMediaLinkDataCount' => $socialMediaLinkDataCount, 'queryStringConcat' => $queryStringConcat, 'appliedFilter' => $filter,  'i' => (request()->input('page', 1) - 1) * $filter['per_page']]);
        } else {
            return view('admin/social-media-link/index')->with(['header' => $header, 'socialMediaLinkData' => $socialMediaLinkData, 'queryStringConcat' => $queryStringConcat, 'appliedFilter' => $filter, 'i' => (request()->input('page', 1) - 1) * $filter['per_page']]);
        }
    }

    /**
     * Show the form for editing the specified Social Media Link.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!hasPermission('Social_Media_Link', 'update')) {
            return view('admin/401');
        }
        $header['title'] = 'Social Media Link - Edit';
        $header['method'] = 'Edit';
        $filter = array(
            'id' => $id,
        );

        $response = SocialMediaLink::getSocialMediaLinkData($filter);
        $socialMediaLinkDetail = $response['data'];

        $activityLog['request'] =  $filter;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] =  $response;
        ActiveLog::createBackendActiveLog($activityLog);

        if ($response['status'] == 1 && !empty($response['data'])) {
            return view('admin/social-media-link/update')->with(['header' => $header, 'socialMediaLinkDetail' => $socialMediaLinkDetail]);
        } else {
            return redirect()->route('social-media-link.index')->with('error', $response['message']);
        }
    }

    /**
     * Update the specified Social Media Link in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!hasPermission('Social_Media_Link', 'update')) {
            return view('admin/401');
        }
        $url = $request->only('redirects_to');
        $requestData = $request->only(['social_media_link_id', 'name', 'link', 'status']);

        $rules = [];
        $customMessages = [];

        $niceNames = array();

        $this->validate($request, $rules, $customMessages, $niceNames);


        $response = SocialMediaLink::updateSocialMediaLink($requestData);

        $activityLog['request'] =  $requestData;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] =  $response;
        ActiveLog::createBackendActiveLog($activityLog);

        if (!empty($response['data'])) {
            return redirect()->to($url['redirects_to'])->with('success', $response['message']);
        } else {
            return redirect()->to($url['redirects_to'])->with('error', $response['message']);
        }
    }
}
