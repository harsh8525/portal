<?php
/**
 * @package     Settings
 * @subpackage  Smtp
 * @Author      Amar Technolabs Pvt. ltd(info@amarinfotech.com)
 * @Copyright(C) 2023 [NAME OF THE ORGANISATION THAT ON BEHALF OF THE CODE WE ARE WORKING].
 * @Version 1.0.0
 * module of the Smtp.
 */
namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\Setting;
use App\Traits\ActiveLog;

class SmtpController extends Controller
{
    /**
     * Display a listing of the smtp.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!hasPermission('SMTP_SETTINGS','read')){
            return view('admin/401');
        }
        $header['title'] = @trans('smtp.title');
        $header['heading'] = @trans('smtp.moduleHeading');

        return view('admin/setting/smtp')->with(['header'=>$header]);
    }

    /**
     * Update or create the specified smtp in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function smtp(Request $request){
        
        $data=$request->all();
        unset($data['_token']);
                
        \DB::transaction(function() use ($data) {
            foreach ($data AS $key => $value) {    
                Setting::updateOrCreate(['config_key' => $key],['config_key' => $key,'value' => $value]);
            }
        });
        
        return redirect()->route('smtp.index')->with('success','Setting - SMTP Saved Successfully');
        
    }

    /**
     * Store a newly created smtp in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
         if(!hasPermission('SMTP_SETTINGS','create') && !hasPermission('SMTP_SETTINGS','update')){
            return view('admin/401');
        }
        $data=$request->all();
        unset($data['_token']);
              
        $activityLog['request'] = $data;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $data;
        ActiveLog::createBackendActiveLog($activityLog);

        \DB::transaction(function() use ($data) {
            foreach ($data AS $key => $value) {    
                Setting::updateOrCreate(['config_key' => $key],['config_key' => $key,'value' => $value]);
            }
        });
        
        return redirect()->route('smtp.index')->with('success','Setting - SMTP Saved Successfully');
    }
}
