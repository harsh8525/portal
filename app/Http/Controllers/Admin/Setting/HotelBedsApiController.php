<?php

/**
 * @package     Settings
 * @subpackage  Hotel Beds Api
 * @Author      Amar Technolabs Pvt. mailto:ltd(info@amarinfotech.com)
 * @Copyright(C) 2023 [NAME OF THE ORGANISATION THAT ON BEHALF OF THE CODE WE ARE WORKING].
 * @Version 1.0.0
 * module of the Hotel Beds Api.
 */


namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Traits\ActiveLog;

class HotelBedsApiController extends Controller
{
    /**
     * Display a listing of the hotel beds api.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!hasPermission('HOTEL_BEDS_API','read')){
            return view('admin/401');
        }
        $header['title'] = @trans('Hotel Beds API');
        $header['heading'] = @trans('HotelBedsApi');

        return view('admin/setting/hotelbeds-api')->with(['header'=>$header]);
    }

    /**
     * Store a newly created hotel beds api in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!hasPermission('HOTEL_BEDS_API','create') && !hasPermission('HOTEL_BEDS_API','update')){
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
        return redirect()->route('suppliers.index')->with('success','Setting - Hotel Beds API Saved Successfully');
    }
}
