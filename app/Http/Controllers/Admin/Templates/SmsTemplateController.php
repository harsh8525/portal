<?php

/**
 * @package     Settings
 * @subpackage  SMS Template 
 * @Author      Amar Technolabs Pvt. ltd(info@amarinfotech.com)
 * @Copyright(C) 2023 [NAME OF THE ORGANISATION THAT ON BEHALF OF THE CODE WE ARE WORKING].
 * @Version 1.0.0
 * module of the SMS Template.
 */
namespace App\Http\Controllers\Admin\Templates;

use App\Http\Controllers\Controller;
use App\Models\SmsTemplate;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Traits\ActiveLog;


class SmsTemplateController extends Controller
{
    /**
     * Display a listing of the SMS template.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() 
    {
        if(!hasPermission('SMS_TEMPLATES','read')){
            return view('admin/401');
        }
        $header['title'] = "SMS Templates";
        $header['heading'] = "SMS Templates";
        $queryStringConcat = '?';
        if (isset($_GET['per_page'])) {
            $queryStringConcat .= ($queryStringConcat == '') ? '?per_page=' . $_GET['per_page'] : '&per_page=' . $_GET['per_page'];
        }
        if (isset($_GET['page'])) {
            $queryStringConcat .= ($queryStringConcat == '') ? '?page=' . $_GET['page'] : '&page=' . $_GET['page'];
        }

        $filter = array(
            'per_page' => (request()->input('per_page') != NULL) ? request()->input('per_page') : Setting::where('config_key', 'general|setting|pagePerAdminRecords')->get('value')[0]['value'],
            'order_by' => (request()->input('order_by') != NULL) ? request()->input('order_by') : 'id',
            'sorting' => (request()->input('sorting') != NULL) ? request()->input('sorting') : 'desc',
            'name' => (request()->input('name') != NULL) ? request()->input('name') : '',
            'status' => (request()->input('status') != NULL) ? request()->input('status') : '',
        );

        if (request()->input('name') != NULL) {
            $filter['where'][] = ['name', 'like', '%' . request()->input('name') . '%'];
        }

        $smsTemplateDataList = SmsTemplate::getSmsTemplateData($filter);
        $smsTemplateDataCount = SmsTemplate::count();
        $smsTemplateData = $smsTemplateDataList['data'];

        $activityLog['request'] =  $filter;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] =  $smsTemplateDataList;
        ActiveLog::createBackendActiveLog($activityLog);

        if ($smsTemplateDataList['status'] == 1) {
            return view('admin/templates/sms-template/index')->with(['header' => $header, 'smsTemplateData' => $smsTemplateData,'smsTemplateDataCount' => $smsTemplateDataCount, 'queryStringConcat' => $queryStringConcat, 'appliedFilter' => $filter,  'i' => (request()->input('page', 1) - 1) * $filter['per_page']]);
        } else {
            return view('admin/templates/sms-template/index')->with(['header' => $header, 'mailTemplateData' => $smsTemplateData,'smsTemplateDataCount' => $smsTemplateDataCount, 'queryStringConcat' => $queryStringConcat, 'appliedFilter' => $filter, 'i' => (request()->input('page', 1) - 1) * $filter['per_page']]);
        }
    }

    /**
     * Show the form for editing the specified SMS template.
     *
     * @param  \App\Models\SmsTemplate  $smsTemplate
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(!hasPermission('SMS_TEMPLATES','update')){
            return view('admin/401');
        }
        $header['title'] = 'SMS Template - Edit';
        $header['method'] = 'Edit';
        $filter = array(
            'id' => $id,
        );
        $response = SmsTemplate::getSmsTemplateData($filter);
        $smsTemplateDetail = $response['data'];

        $activityLog['request'] =  $filter;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] =  $response;
        ActiveLog::createBackendActiveLog($activityLog);

        if ($response['status'] == 1 && !empty($response['data'])) {
            return view('admin/templates/sms-template/update')->with(['header' => $header, 'smsTemplateDetail' => $smsTemplateDetail]);
        } else {
            return redirect()->route('sms-template.index')->with('error', $response['message']);
        }
    }

    /**
     * Update the specified SMS template in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SmsTemplate  $smsTemplate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SmsTemplate $smsTemplate)
    {
        if(!hasPermission('SMS_TEMPLATES','update')){
            return view('admin/401');
        }
        $url = $request->only('redirects_to');
        $requestData = $request->all();

        $rules = [];

        $customMessages = [];

        $niceNames = array();

        $this->validate($request, $rules, $customMessages, $niceNames);
        $response = SmsTemplate::updateSmsTemplate($requestData);

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
