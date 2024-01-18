<?php
/**
 * @package     Dashboard
 * @subpackage  Agency Address
 * @Author      Amar Technolabs Pvt. ltd(info@amarinfotech.com)
 * @Copyright(C) 2023 [NAME OF THE ORGANISATION THAT ON BEHALF OF THE CODE WE ARE WORKING].
 * @Version 1.0.0
 * module of the Dashboard.
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

class AgencyAddress extends Model
{
    use HasFactory;

    protected $table = 'agency_addresses';
    
    protected $guarded = [];

    /**
     * insert new record into database
     */
    public static function createAgencyAddress($requestData,$agency_id)
    {
        
        $return = array(
            'status' => 0,
            'message' => 'something went wrong',
            'data' => []
        );
          
        $agencyAddressData = array(
            'agency_id'=> $agency_id,
            'address1'=> $requestData['address1'],
            'country' => $requestData['country'],
            'state' => $requestData['state'],
            'city' => $requestData['city'],
            'pincode' => $requestData['zip_code'],
        );
        
        try{
            DB::beginTransaction();
               
            $agencyData = AgencyAddress::create($agencyAddressData);
            
            DB::commit();
            if($agencyData){
                $return['status'] = 1;
                $return['message'] = 'Agency save successfully';
                $return['data'] = $agencyData;
            }
        } catch (\Exception $e){
            $return['message'] = 'Error during save agency record : '.$e->getMessage();
        }
        
        
        return $return;
    }

    /**
     * update record into database
     */
    public static function updateAgencyAddress($requestData)
    {
        
        $return = array(
            'status' => 0,
            'message' => 'something went wrong',
            'data' => []
        );
          
        $agencyAddressDetails = array(
            'id'=>$requestData['agency_address_id'],
            'agency_id'=> $requestData['agency_id'],
            'address1'=> $requestData['address1'],
            'state' => $requestData['state'],
            'city' => $requestData['city'],
            'country' => $requestData['country'],
            'pincode' => $requestData['zip_code'],
            
        );
        try{
            DB::beginTransaction();
            $matchAgencyAddress = ['id'=>$agencyAddressDetails['id']];   
            $agencyAddressData = AgencyAddress::updateOrCreate($matchAgencyAddress,$agencyAddressDetails);
            
            DB::commit();
            if($agencyAddressData){
                $return['status'] = 1;
                $return['message'] = 'Agency address save successfully';
                $return['data'] = $agencyAddressData;
            }
        } catch (\Exception $e){
            $return['message'] = 'Error during save agency record : '.$e->getMessage();
        }
        
        
        return $return;
    }
}
