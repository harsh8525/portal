<?php

/**
 * @package     Settings
 * @subpackage  General
 * @Author      Amar Technolabs Pvt. mailto:ltd(info@amarinfotech.com)
 * @Copyright(C) 2023 [NAME OF THE ORGANISATION THAT ON BEHALF OF THE CODE WE ARE WORKING].
 * @Version 1.0.0
 * module of the General.
 */

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\URL;
use App\Models\Country;
use App\Traits\ActiveLog; 
use App\Models\Language;

class GeneralController extends Controller
{
    /**
     * Display a listing of the general.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!hasPermission('GENERAL', 'read')) {
            return view('admin/401');
        }

        $header['title'] = @trans('general.title');
        $header['heading'] = @trans('general.moduleHeading');

        $timezones = \DateTimeZone::listIdentifiers();
        $items = array();
        foreach ($timezones as $timezoneId) {
            $timezone = new \DateTimeZone($timezoneId);
            $offsetInSeconds = $timezone->getOffset(new \DateTime());
            $items[$timezoneId] = $offsetInSeconds;
        }
        asort($items);
        array_walk($items, function (&$offsetInSeconds, $timezoneId) {
            $offsetPrefix = $offsetInSeconds < 0 ? '-' : '+';
            $offset = gmdate('H:i', abs($offsetInSeconds));
            $offset = "(GMT${offsetPrefix}${offset}) " . explode('/', $timezoneId)[0] . '/' . @explode('/', $timezoneId)[1];
            $offsetInSeconds = $offset;
        });

        $getCountry = Country::get();
        $getLanguages = Language::get();

        return view('admin/setting/general')->with(['header' => $header, 'items' => $items, 'getCountry' => $getCountry, 'getLanguages' => $getLanguages]);
    }

    /**
     * Update or create the specified basic info in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function basic(Request $request)
    {
        if (!hasPermission('GENERAL', 'create') && !hasPermission('GENERAL', 'update')) {
            return view('admin/401');
        }
        $data = $request->all();
        unset($data['_token']);

        if (isset($data['general|basic|colorLogo'])) {
            $colorLogo = 'color-logo.' . $data['general|basic|colorLogo']->extension();
            $data['general|basic|colorLogo']->move(storage_path('app/public/images/general'), $colorLogo);
            $url = URL::to('/storage/') . '/images/general/' . $colorLogo;
            $data['general|basic|colorLogo'] = $url;
        }

        if (isset($data['general|basic|whiteLogo'])) {
            $whiteLogo = 'white-logo.' . $data['general|basic|whiteLogo']->extension();
            $data['general|basic|whiteLogo']->move(storage_path('app/public/images/general'), $whiteLogo);
            $url = URL::to('/storage/') . '/images/general/' . $whiteLogo;
            $data['general|basic|whiteLogo'] = $url;
        }

        if (isset($data['general|basic|favicon'])) {
            $favicon = 'favicon.' . $data['general|basic|favicon']->extension();
            $data['general|basic|favicon']->move(storage_path('app/public/images/general'), $favicon);
            $url = URL::to('/storage/') . '/images/general/' . $favicon;
            $data['general|basic|favicon'] = $url;
        }
        $activityLog['request'] = $request->all();
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $data;
        ActiveLog::createBackendActiveLog($activityLog);

        \DB::transaction(function () use ($data) {
            foreach ($data as $key => $value) {
                Setting::updateOrCreate(['config_key' => $key], ['config_key' => $key, 'value' => $value]);
            }
        });

        return redirect()->route('general.index')->with('success', 'Setting - Genaral [Basic] details Saved Successfully');
    }

     /**
     *  Update or create the specified maintenance in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function maintenance(Request $request)
    {

        if (!hasPermission('GENERAL', 'create') && !hasPermission('GENERAL', 'update')) {
            return view('admin/401');
        }
        $data = $request->all();

        if (!isset($data['general|maintenanceMode'])) {
            $data['general|maintenanceMode'] =  'off';
            $data['general|maintenanceMode|message'] =  '';
        }

        unset($data['_token']);

        $activityLog['request'] = $request->all();
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $data;
        ActiveLog::createBackendActiveLog($activityLog);

        \DB::transaction(function () use ($data) {
            foreach ($data as $key => $value) {
                Setting::updateOrCreate(['config_key' => $key], ['config_key' => $key, 'value' => $value]);
            }
        });

        return redirect()->route('general.index')->with('success', 'Setting - General [Maintenance Message] Saved Successfully');
    }

    /**
     * Update or create the specified additional info in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function additionalInfo(Request $request)
    {

        if (!hasPermission('GENERAL', 'create') && !hasPermission('GENERAL', 'update')) {
            return view('admin/401');
        }

        $data = $request->all();
        $data['general|site|arabic_speak_country'] = implode(',', $data['general|site|arabic_speak_country']);
        // echo "<pre>";print_r($data['general|site|arabic_speak_country']);die;
        $data['general|setting|ResetMonth'] = (int)$data['general|setting|ResetMonth'];
        unset($data['_token']);

        $activityLog['request'] = $request->all();
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $data;
        ActiveLog::createBackendActiveLog($activityLog);

        \DB::transaction(function () use ($data) {
            foreach ($data as $key => $value) {
                Setting::updateOrCreate(['config_key' => $key], ['config_key' => $key, 'value' => $value]);
            }
        });

        return redirect()->route('general.index')->with('success', 'Setting - General [Additional Settings] Saved Successfully');
    }

     /**
     * Update or create the specified bank details in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function bankDetails(Request $request)
    {

        $data = $request->all();

        unset($data['_token']);

        $activityLog['request'] = $request->all();
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $data;
        ActiveLog::createBackendActiveLog($activityLog);

        \DB::transaction(function () use ($data) {
            foreach ($data as $key => $value) {
                Setting::updateOrCreate(['config_key' => $key], ['config_key' => $key, 'value' => $value]);
            }
        });

        return redirect()->route('general.index')->with('success', 'Setting - General [Bank Details] Saved Successfully');
    }

     /**
     * Update or create the specified order discount in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function orderDiscount(Request $request)
    {

        $data = $request->all();
        unset($data['_token']);

        $activityLog['request'] = $request->all();
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $data;
        ActiveLog::createBackendActiveLog($activityLog);

        \DB::transaction(function () use ($data) {
            foreach ($data as $key => $value) {
                Setting::updateOrCreate(['config_key' => $key], ['config_key' => $key, 'value' => $value]);
            }
        });

        return redirect()->route('general.index')->with('success', 'Setting - General [Final Order Dicsount Settings] Saved Successfully');
    }

     /**
     * Update or create the specified mobile info in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function mobile(Request $request)
    {

        if (!hasPermission('GENERAL', 'create') && !hasPermission('GENERAL', 'update')) {
            return view('admin/401');
        }
        $data = $request->all();
        $updateFor = $data['updateFor'];

        unset($data['_token']);
        unset($data['updateFor']);

        $activityLog['request'] = $request->all();
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $data;
        ActiveLog::createBackendActiveLog($activityLog);

        \DB::transaction(function () use ($data) {
            foreach ($data as $key => $value) {
                Setting::updateOrCreate(['config_key' => $key], ['config_key' => $key, 'value' => $value]);
            }
        });

        return redirect()->route('general.index')->with('success', ucfirst($updateFor) . ' Version Saved Successfully');
    }

    /**
     * Update or create the specified otp verification info in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function OtpVerification(Request $request)
    {

        if (!hasPermission('GENERAL', 'create') && !hasPermission('GENERAL', 'update')) {
            return view('admin/401');
        }
        $data = $request->all();

        if (!isset($data['general|otp|phoneVerification'])) {
            $data['general|otp|phoneVerification'] =  'off';
        }

        unset($data['_token']);

        $activityLog['request'] = $request->all();
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $data;
        ActiveLog::createBackendActiveLog($activityLog);

        \DB::transaction(function () use ($data) {
            foreach ($data as $key => $value) {
                Setting::updateOrCreate(['config_key' => $key], ['config_key' => $key, 'value' => $value]);
            }
        });

        return redirect()->route('general.index')->with('success', 'Setting - General [OTP Verification] Saved Successfully');
    }
}
