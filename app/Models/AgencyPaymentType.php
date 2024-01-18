<?php
/**
 * @package     Agency
 * @subpackage  Agency Payment Type
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

class AgencyPaymentType extends Model
{
    use HasFactory;
    protected $table = 'agency_payment_types';
    
    protected $guarded = [];

    /**
     * insert new record into database
     */
    public static function createAgencyPaymentType($requestData,$agency_id)
    {
        
        $return = array(
            'status' => 0,
            'message' => 'something went wrong',
            'data' => []
        );
          
        try{
            DB::beginTransaction();
            foreach($requestData['payment_option'] as $type)
            {
                $agencyPaymentTypeData = AgencyPaymentType::create([
                     'agency_id' => $agency_id,
                    'core_payment_type_id' => $type
                ]);
            }
            DB::commit();
            if($agencyPaymentTypeData){
                $return['status'] = 1;
                $return['message'] = 'Agency save successfully';
                $return['data'] = $agencyPaymentTypeData;
            }
        } catch (\Exception $e){
            $return['message'] = 'Error during save agency payment type record : '.$e->getMessage();
        }
        
        
        return $return;
    }
    /**
     * Update record into database
     */
    public static function updateAgencyPaymentType($requestData)
    {
        
        $return = array(
            'status' => 0,
            'message' => 'something went wrong',
            'data' => []
        );
          
        try{
            DB::beginTransaction();
            // remove old record before insert new record with same agency
            $deleteOldRecord = AgencyPaymentType::where('agency_id',$requestData['agency_id'])->delete();
            foreach($requestData['payment_option'] as $type)
            {
                $agencyPaymentTypeData = AgencyPaymentType::create([
                    'agency_id' => $requestData['agency_id'],
                    'core_payment_type_id' => $type
                ]);
            }
            
            
            DB::commit();
            if($agencyPaymentTypeData){
                $return['status'] = 1;
                $return['message'] = 'Agency payment type update successfully';
                $return['data'] = $agencyPaymentTypeData;
            }
        } catch (\Exception $e){
            $return['message'] = 'Error during update agency payment type record : '.$e->getMessage();
        }
        
        
        return $return;
    }
}
