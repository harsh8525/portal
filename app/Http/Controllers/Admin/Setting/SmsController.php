<?php
/**
 * @package     Settings
 * @subpackage  Sms
 * @Author      Amar Technolabs Pvt. ltd(info@amarinfotech.com)
 * @Copyright(C) 2023 [NAME OF THE ORGANISATION THAT ON BEHALF OF THE CODE WE ARE WORKING].
 * @Version 1.0.0
 * module of the Sms.
 */
namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SmsController extends Controller
{
    /**
     * Display a listing of the sms.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!hasPermission('SMS_SETTINGS','read')){
            return view('admin/401');
        }
        
        $header['title'] = 'SMS Setting';
        $header['heading'] = 'SMS Setting';

        return view('admin/setting/sms')->with(['header'=>$header]);
    }
}
