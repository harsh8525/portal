<?php
/**
 * @package     Settings
 * @subpackage  Password Security
 * @Author      Amar Technolabs Pvt. ltd(info@amarinfotech.com)
 * @Copyright(C) 2023 [NAME OF THE ORGANISATION THAT ON BEHALF OF THE CODE WE ARE WORKING].
 * @Version 1.0.0
 * module of the Password Security.
 */
namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Traits\ActiveLog;

class PasswordSecurityController extends Controller
{
    /**
     * Display a listing of the password security.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!hasPermission('PASSWORD_SECURITY','read')){
            return view('admin/401');
        }
        $header['title'] = 'Password Security';
        $header['heading'] = 'Password Security';

        return view('admin/setting/password-security')->with(['header'=>$header]);
    }

    /**
     * Store a newly created password security in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!hasPermission('PASSWORD_SECURITY','create')){
            return view('admin/401');
        }
        $data=$request->all();
        
        //convert comma seperated numeric values into decending order comma seperared string
        $valuesArray = explode(',', $data['passwordSecurity|expireNotifyDays']);
        $valuesArray = array_map('intval', $valuesArray);
        rsort($valuesArray);
        $sortedValues = implode(',', $valuesArray);
        $data['passwordSecurity|expireNotifyDays'] = $sortedValues;
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
        
        return redirect()->route('password-security.index')->with('success','Setting - Password Security Saved Successfully');
    }

    /**
     * Check password length from request parameters.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function checkPasswordLength(Request $request)
    {
        $matchListData = [];
        $matchListData = request()->input('charSpecial') + request()->input('charAlphanumeric') + request()->input('charNumeric') + request()->input('charUpper') + request()->input('charLower');

        if (!empty($matchListData) &&  $matchListData > request()->input('password')) 
        {
            
            echo "false";
            
        } else {
            echo "true";
        }
    }
}
