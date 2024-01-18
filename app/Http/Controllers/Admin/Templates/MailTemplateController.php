<?php

/**
 * @package     Settings
 * @subpackage  Mail Template 
 * @Author      Amar Technolabs Pvt. ltd(info@amarinfotech.com)
 * @Copyright(C) 2023 [NAME OF THE ORGANISATION THAT ON BEHALF OF THE CODE WE ARE WORKING].
 * @Version 1.0.0
 * module of the Mail Template.
 */

namespace App\Rules;
namespace App\Http\Controllers\Admin\Templates;

use App\Http\Controllers\Controller;
use App\Models\MailTemplate;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Traits\ActiveLog;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\URL;


class MailTemplateController extends Controller
{
    /**
     * Display a listing of the mail template.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         if(!hasPermission('MAIL_TEMPLATES','read')){
            return view('admin/401');
        }

        $header['title'] = "Mail Templates";
        $header['heading'] = "Mail Templates";
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
            $filter['whereHas'][] = ['name', 'like', '%' . request()->input('name') . '%'];
        }
          
        $mailTemplateDataList = MailTemplate::getMailTemplateData($filter);
        $mailTemplateCountData = MailTemplate::count();
        $mailTemplateData = $mailTemplateDataList['data'];

        $activityLog['request'] =  $filter;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] =  $mailTemplateDataList;
        ActiveLog::createBackendActiveLog($activityLog);

        if ($mailTemplateDataList['status'] == 1) {
            return view('admin/templates/mail-template/index')->with(['header' => $header, 'mailTemplateData' => $mailTemplateData,'mailTemplateCountData' => $mailTemplateCountData, 'queryStringConcat' => $queryStringConcat, 'appliedFilter' => $filter,  'i' => (request()->input('page', 1) - 1) * $filter['per_page']]);
        } else {
            return view('admin/templates/mail-template/index')->with(['header' => $header, 'mailTemplateData' => $mailTemplateData, 'mailTemplateCountData' => $mailTemplateCountData,'queryStringConcat' => $queryStringConcat, 'appliedFilter' => $filter, 'i' => (request()->input('page', 1) - 1) * $filter['per_page']]);
        }
    }

    /**
     * Show the form for editing the specified mail template.
     *
     * @param  \App\Models\MailTemplate  $mailTemplate
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(!hasPermission('MAIL_TEMPLATES','update')){
            return view('admin/401');
        }
        $header['title'] = 'Mail Template - Edit';
        $header['method'] = 'Edit';
        $filter = array(
            'id' => $id,
        );
        $getUser = User::all();
        $response = MailTemplate::getMailTemplateData($filter);
        $mailTemplateDetail = $response['data'];

        $activityLog['request'] =  $filter;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] =  $response;
        ActiveLog::createBackendActiveLog($activityLog);

        if ($response['status'] == 1 && !empty($response['data'])) {
            return view('admin/templates/mail-template/update')->with(['header' => $header, 'getUser' => $getUser,'mailTemplateDetail' => $mailTemplateDetail]);
        } else {
            return redirect()->route('mail-template.index')->with('error', $response['message']);
        }
    }

    /**
     * Update the specified mail template in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MailTemplate  $mailTemplate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(!hasPermission('MAIL_TEMPLATES','update')){
            return view('admin/401');
        }
        $url = $request->only('redirects_to');
        $requestData = $request->all();
        
        $rules = [];
        
        $customMessages = []; 
        
        $niceNames = array();
        
        $this->validate($request, $rules, $customMessages, $niceNames);
        $response = MailTemplate::updateMailTemplate($requestData);

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

    /**
     * Upload mail file in database or storage.
     *
     * @return \Illuminate\Http\Request
     */
    public function uploadMailFile(Request $request)
    {
        $request->validate([
            'file' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imageName = time().'.'.$request->file->extension();  
        $request->file->move(storage_path('app/public/template_images'), $imageName);

        chmod(storage_path("app/public/template_images/{$imageName}"), 0777);

        if (file_exists(storage_path("app/public/template_images/{$imageName}"))) {
            return response()->json(['location' => '/storage/template_images/'.$imageName]);
        } else {
            return response()->json(['error' => 'File upload failed'], 500);
        }
    }
}
