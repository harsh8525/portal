<?php
/**
 * @package     Settings
 * @subpackage  Login Attempts
 * @Author      Amar Technolabs Pvt. ltd(info@amarinfotech.com)
 * @Copyright(C) 2023 [NAME OF THE ORGANISATION THAT ON BEHALF OF THE CODE WE ARE WORKING].
 * @Version 1.0.0
 * module of the Login Attempts.
 */
namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Traits\ActiveLog;

class LoginAttemptController extends Controller
{
    /**
     * Display a listing of the login attempts.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!hasPermission('LOGIN_ATTEMPTS','read')){
            return view('admin/401');
        }

        $header['title'] = 'Login Attempts';
        $header['heading'] = 'Login Attempts';

        return view('admin/setting/login-attempt')->with(['header'=>$header]);
    }

    /**
     * Store a newly created login attempts in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!hasPermission('LOGIN_ATTEMPTS','create')){
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
        
        return redirect()->route('login-attempt.index')->with('success','Setting - Login Attempts Saved Successfully');
    }
}
