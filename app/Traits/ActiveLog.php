<?php

/**
 * @package     Operational Data
 * @subpackage  Suppliers
 * @Author      Amar Technolabs Pvt. ltd(info@amarinfotech.com)
 * @Copyright(C) 2023 [Travel Portal].
 * @Version 1.0.0
 * module of the Suppliers.
 */

namespace App\Traits;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Carbon;
use Intervention\Image\ImageManagerStatic as Image;
use Spatie\Activitylog\Traits\LogsActivity;
use App\Models\ActivityLog;
use App\Models\CustomerActivityLog;
use App\Models\BackendCustomerActivityLog;
use Illuminate\Support\Facades\Auth;
use DateTime;
use DB;
use Illuminate\Support\Facades\Hash;

trait ActiveLog
{
    use HasFactory;
    protected $table = 'customer_activity_logs';
    protected $guarded = [];
    
    public static function createActiveLog($requestData)
    {
       
        $activeLogData = self::getActiveLogData($requestData);
        
        try {
            DB::beginTransaction();
            $paymentMethodData = CustomerActivityLog::create($activeLogData);

            DB::commit();
            if ($paymentMethodData) {
                $return['status'] = 1;
                $return['message'] = 'Active Log [' . $paymentMethodData['name'] . '] save successfully';
                $return['data'] = $paymentMethodData;
            }
        } catch (\Exception $e) {
            $return['message'] = 'Error during save Active Log record : ' . $e->getMessage();
        }

        // return $return;
    }
    public static function createBackendActiveLog($requestData)
    {
      
       $activeLogData = self::getActiveLogData($requestData);
        try {
            DB::beginTransaction();
            $paymentMethodData = BackendCustomerActivityLog::create($activeLogData);

            DB::commit();
            if ($paymentMethodData) {
                $return['status'] = 1;
                $return['message'] = 'Active Log [' . $paymentMethodData['name'] . '] save successfully';
                $return['data'] = $paymentMethodData;
            }
        } catch (\Exception $e) {
            $return['message'] = 'Error during save Active Log record : ' . $e->getMessage();
        }

        // return $return;
    }

    public static function getBrowserName($userAgent) {
        
        $browserName = "Unknown";
        
        if(preg_match('/iPod/i', $userAgent)){
            $browserName = 'iPod';
        }else if(preg_match('/iPhone/i', $userAgent)){
            $browserName = 'iPhone';
        }else if(preg_match('/iPad/i', $userAgent)){
            $browserName = 'iPad';
        }else if(preg_match('/Android/i', $userAgent)){
            $browserName = 'Android';
        }else if(preg_match('/webOS/i', $userAgent)){
            $browserName = 'webOS';
        }else{
            if (preg_match('/MSIE/i', $userAgent) && !preg_match('/Opera/i', $userAgent)) {
                $browserName = 'Internet Explorer';
            } elseif (preg_match('/Firefox/i', $userAgent)) {
                $browserName = 'Mozilla Firefox';
            } elseif (preg_match('/Chrome/i', $userAgent)) {
                $browserName = 'Google Chrome';
            } elseif (preg_match('/Safari/i', $userAgent)) {
                $browserName = 'Safari';
            } elseif (preg_match('/Opera/i', $userAgent)) {
                $browserName = 'Opera';
            } elseif (preg_match('/Edge/i', $userAgent)) {
                $browserName = 'Microsoft Edge';
            } elseif (preg_match('/Dart/i', $userAgent)) {
                $browserName = 'Mobile';
            }
        }
        
        
        return $browserName;
    }
    public static function getActiveLogData($requestData) {

        $requestUrl = $requestData['request_url'];
        $responseData = json_encode($requestData['response']);
        $requestDetails = json_encode($requestData['request']) ??  '';
        $return = array(
            'status' => 0,
            'message' => 'something went wrong',
            'data' => []
        );
        $ipAddress = $_SERVER['REMOTE_ADDR'];

            $url= "http://www.geoplugin.net/json.gp?ip=".$ipAddress;
            
            $headers = [
                'Content-Type: application/json',
            ];

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            // execute!
            $response = curl_exec($ch);
            // close the connection, release resources used
            curl_close($ch);
            
            $apiData= json_decode($response);
            $countryName = $apiData->geoplugin_countryName; 
            $cityName = $apiData->geoplugin_city; 

        $userAgent = $_SERVER['HTTP_USER_AGENT'];

        $currentBrowser = self::getBrowserName($userAgent);
        $user_id = Auth::user()->id ?? 'guest';
        
       return $activeLogData = array(
            'user_id' => $user_id ?? '',
            'device_id' => $ipAddress ?? '',
            'browser_name' => $currentBrowser ?? '',
            'country' => $countryName ?? '',
            'city' => $cityName ?? '',
            'request_url' => $requestUrl ?? '',
            'request' => $requestDetails ?? '',
            'response' => $responseData ?? '',
        );
    }
}
