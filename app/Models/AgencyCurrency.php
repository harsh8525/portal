<?php
/**
 * @package     Agency
 * @subpackage  Agency Currencies
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
class AgencyCurrency extends Model
{
    use HasFactory;
    protected $table = 'agency_currencies';
    
    protected $guarded = [];

    /**
     * insert new record into database
     */
    public static function createAgencyCurrency($requestData,$agency_id)
    {
        $return = array(
            'status' => 0,
            'message' => 'something went wrong',
            'data' => []
        );
          
        try{
            DB::beginTransaction();
            foreach($requestData['enable_currency_id'] as $currencyID)
            {
                $agencyCurrencyData = AgencyCurrency::create([
                    'agency_id' => $agency_id,
                    'currency_id' => $currencyID
                ]);
            }
            
            
            DB::commit();
            if($agencyCurrencyData){
                $return['status'] = 1;
                $return['message'] = 'Agency Currency save successfully';
                $return['data'] = $agencyCurrencyData;
            }
        } catch (\Exception $e){
            $return['message'] = 'Error during save agency currency record : '.$e->getMessage();
        }
        
        
        return $return;
    }
    /**
     * update record into database
     */
    public static function updateAgencyCurrency($requestData)
    {
        $return = array(
            'status' => 0,
            'message' => 'something went wrong',
            'data' => []
        );
          
        try{
            DB::beginTransaction();
            // remove old record before insert new record with same agency
            $deleteOldRecord = AgencyCurrency::where('agency_id',$requestData['agency_id'])->delete();
            foreach($requestData['enable_currency_id'] as $currencyID)
            {
                $agencyCurrencyData = AgencyCurrency::create([
                    'agency_id' => $requestData['agency_id'],
                    'currency_id' => $currencyID
                ]);
            }
            
            
            DB::commit();
            if($agencyCurrencyData){
                $return['status'] = 1;
                $return['message'] = 'Agency Currency save successfully';
                $return['data'] = $agencyCurrencyData;
            }
        } catch (\Exception $e){
            $return['message'] = 'Error during save agency currency record : '.$e->getMessage();
        }
        
        
        return $return;
    }
}
