<?php
/**
 * @package     Settings
 * @subpackage  Singin Method
 * @Author      Amar Technolabs Pvt. ltd(info@amarinfotech.com)
 * @Copyright(C) 2023 [NAME OF THE ORGANISATION THAT ON BEHALF OF THE CODE WE ARE WORKING].
 * @Version 1.0.0
 * module of the Singin Method.
 */
namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\URL;
use App\Traits\ActiveLog;

class SinginMethodController extends Controller
{
    /**
     * Display a listing of the singin method.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!hasPermission('SIGN_IN_METHOD','read')){
            return view('admin/401');
        }
        $header['title'] = "Signin Method";
        $header['heading'] = "Signin Method";
        return view('admin/setting/signin-method')->with(['header'=>$header]);
    }

    /**
     * Store a newly created singin method in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         if(!hasPermission('SIGN_IN_METHOD','create') && !hasPermission('SIGN_IN_METHOD','update')){
            return view('admin/401');
        }
        $data=$request->all();
        unset($data['_token']);
        if(isset($data['signInMethod|apple|keyTxtFile'])){
            $appleKeyFile = 'apple-key.'.$data['signInMethod|apple|keyTxtFile']->extension();  
            $data['signInMethod|apple|keyTxtFile']->move(storage_path('app/public/images/sign_in_method'), $appleKeyFile);
            $url = URL::to('/storage/').'/images/sign_in_method/'.$appleKeyFile;
            $data['signInMethod|apple|keyTxtFile'] = $url;
        }

        $activityLog['request'] = $data;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $data;
        ActiveLog::createBackendActiveLog($activityLog);
        
        \DB::transaction(function() use ($data) {
            foreach ($data AS $key => $value) {    
                Setting::updateOrCreate(['config_key' => $key],['config_key' => $key,'value' => $value]);
            }
        });
        
        return redirect()->route('signin-method.index')->with('success','Setting - Signin Method Saved Successfully');
    }
}
