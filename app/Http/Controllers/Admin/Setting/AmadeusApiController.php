<?php

/**
 * @package     Setting
 * @subpackage   Amadeus Api
 * @Author      Amar Technolabs Pvt. ltd(info@amarinfotech.com)
 * @Copyright(C) 2023 [Travel Portal].
 * @Version 1.0.0
 * module of the Amadeus Api.
 */


namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Traits\ActiveLog;
class AmadeusApiController extends Controller
{
    /**
     * Display a listing of the amadeus api.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!hasPermission('AMADEUS_API','read')){
            return view('admin/401');
        }
        $header['title'] = @trans('Amadeus API');
        $header['heading'] = @trans('AmadeusApi');

        return view('admin/setting/amadeus-api')->with(['header'=>$header]);
    }

    /**
     * Store a newly created amadeus api in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!hasPermission('AMADEUS_API','create') && !hasPermission('AMADEUS_API','update')){
            return view('admin/401');
        }
        $data=$request->all();
        unset($data['_token']);
                
        \DB::transaction(function() use ($data) {
            foreach ($data AS $key => $value) {    
              Setting::updateOrCreate(['config_key' => $key],['config_key' => $key,'value' => $value]);
            }
        });

        $activityLog['request'] = $data;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = [];
        ActiveLog::createBackendActiveLog($activityLog);
        
        return redirect()->route('suppliers.index')->with('success','Setting - Amadeus API Saved Successfully');
    }

    /**
     * fetch refresh token from setting database.
     *
     * @return \Illuminate\Http\Response
     */   
    public function getRefreshToken()
    {
        Setting::where('config_key','amadeus|api|secret')->update([
            'value' => ""
        ]);
        return redirect()->back();
    }
}
