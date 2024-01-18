<?php

/**
 * @package     Agency
 * @subpackage  Agency Payment Gateeway
 * @Author      Amar Technolabs Pvt. ltd(info@amarinfotech.com)
 * @Copyright(C) 2023 [NAME OF THE ORGANISATION THAT ON BEHALF OF THE CODE WE ARE WORKING].
 * @Version 1.0.0
 * module of the Agency.
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Carbon;
use Spatie\Activitylog\Traits\LogsActivity;
use DateTime;
use App\Models\Setting;
use DB;
use Intervention\Image\ImageManagerStatic as Image;
class AgencyPaymentGateway extends Model
{
    use HasFactory;
    protected $table = 'agency_payment_gateways';
    
    protected $guarded = [];

    /**
     * insert new record into database
     */
    public static function createAgencyPaymentGateway($requestData,$agency_id)
    {
        
        $return = array(
            'status' => 0,
            'message' => 'something went wrong',
            'data' => []
        );
          
        try{
            DB::beginTransaction();
            foreach($requestData['payment_gateway'] as $type)
            {
                $agencyPaymentGatewayData = AgencyPaymentGateway::create([
                    'agency_id' => $agency_id,
                    'core_payment_gateway_id' => $type
                ]);
            }
            
            
            DB::commit();
            if($agencyPaymentGatewayData){
                $return['status'] = 1;
                $return['message'] = 'Agency payment gateway save successfully';
                $return['data'] = $agencyPaymentGatewayData;
            }
        } catch (\Exception $e){
            $return['message'] = 'Error during save agency payment gateway record : '.$e->getMessage();
        }
        
        
        return $return;
    }
    /**
     * Update record into database
     */
    public static function updateAgencyPaymentGateway($requestData)
    {
        
        $return = array(
            'status' => 0,
            'message' => 'something went wrong',
            'data' => []
        );
          
        try{
            DB::beginTransaction();
            // remove old record before insert new record with same agency
            $deleteOldRecord = AgencyPaymentGateway::where('agency_id',$requestData['agency_id'])->delete();
            foreach($requestData['payment_gateway'] as $type)
            {
                $agencyPaymentGatewayData = AgencyPaymentGateway::create([
                    'agency_id' => $requestData['agency_id'],
                    'core_payment_gateway_id' => $type
                ]);
            }
            
            
            DB::commit();
            if($agencyPaymentGatewayData){
                $return['status'] = 1;
                $return['message'] = 'Agency payment gateway update successfully';
                $return['data'] = $agencyPaymentGatewayData;
            }
        } catch (\Exception $e){
            $return['message'] = 'Error during update agency payment gateway record : '.$e->getMessage();
        }
        
        
        return $return;
    }
}
