<?php

/**
 * @package     Agency
 * @subpackage  Agency Service Type
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

class AgencyServiceType extends Model
{
    use HasFactory;
    protected $table = 'agency_service_types';

    protected $guarded = [];

    /**
     * insert new record into database
     */
    public static function createAgencyServiceType($requestData, $agency_id)
    {

        $return = array(
            'status' => 0,
            'message' => 'something went wrong',
            'data' => []
        );

        try {
            DB::beginTransaction();
            foreach ($requestData['service_type'] as $type) {
                $agencyServiceTypeData = AgencyServiceType::create([
                    'agency_id' => $agency_id,
                    'core_service_type_id' => $type
                ]);
            }
            DB::commit();
            if ($agencyServiceTypeData) {
                $return['status'] = 1;
                $return['message'] = 'Agency save successfully';
                $return['data'] = $agencyServiceTypeData;
            }
        } catch (\Exception $e) {
            $return['message'] = 'Error during save agency service type record : ' . $e->getMessage();
        }


        return $return;
    }
    /**
     * update record into database
     */
    public static function updateAgencyServiceType($requestData)
    {

        $return = array(
            'status' => 0,
            'message' => 'something went wrong',
            'data' => []
        );

        try {
            DB::beginTransaction();
            // remove old record before insert new record with same agency
            $deleteOldRecord = AgencyServiceType::where('agency_id', $requestData['agency_id'])->delete();
            foreach ($requestData['service_type'] as $type) {
                $agencyServiceTypeData = AgencyServiceType::create([
                    'agency_id' => $requestData['agency_id'],
                    'core_service_type_id' => $type
                ]);
            }


            DB::commit();
            if ($agencyServiceTypeData) {
                $return['status'] = 1;
                $return['message'] = 'Agency service type update successfully';
                $return['data'] = $agencyServiceTypeData;
            }
        } catch (\Exception $e) {
            $return['message'] = 'Error during update agency service type record : ' . $e->getMessage();
        }


        return $return;
    }
}
