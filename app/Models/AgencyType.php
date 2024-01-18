<?php

/**
 * @package     Operational Data
 * @subpackage  Agency Type
 * @Author      Amar Technolabs Pvt. ltd(info@amarinfotech.com)
 * @Copyright(C) 2023 [Travel Portal].
 * @Version 1.0.0
 * module of the Agency Type.
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
use DB;
use Illuminate\Support\Facades\Hash;

class AgencyType extends Model
{
    use HasFactory, LogsActivity;
    protected $table = 'core_agency_types';

    protected $guarded = [];
    protected static $logAttributes = ['name', 'description', 'is_active'];

    protected static $logName = 'core_agency_types';

    //Display a listing of the Agencytype Model

    public static function getAgencyType($option = array())
    {
        $return = array(
            'status' => 0,
            'message' => 'something went wrong',
            'data' => []
        );


        $data = array(
            'id' => '',
            'order_by' => 'id',
            'sorting' => 'desc',
            'status' => '',
            'where' => array(),
            'orWhere' => array()
        );

        $config = array_merge($data, $option);
        $result = [];
        if ($config['id'] != '') {
            try {
                $query = AgencyType::query();

                $query->select(
                    'core_agency_types.*',
                    DB::raw('(CASE WHEN core_agency_types.is_active = "0" THEN "In-Active" '
                        . 'WHEN core_agency_types.is_active = "1" THEN "Active" '
                        . 'END) AS agency_type_status_text')
                );
                $query->where('id', $config['id']);

                $result = $query->first();
            } catch (\Exception $e) {
                $return['message'] = $e->getMessage();
            }
        } else {
            try {
                $query = AgencyType::query();
                $query->select(
                    'core_agency_types.*',
                    DB::raw('(CASE WHEN core_agency_types.is_active = "0" THEN "In-Active" '
                        . 'WHEN core_agency_types.is_active = "1" THEN "Active" '
                        . 'END) AS agency_type_status_text')
                );
                $query->orderBy($config['order_by'], $config['sorting']);


                if (!empty($config['where'])) {
                    foreach ($config['where'] as $where) {
                        $query->where($where[0], $where[1], $where[2]);
                    }
                }

                if (!empty($config['orWhere'])) {
                    foreach ($config['orWhere'] as $orWhere) {
                        $query->orWhere($orWhere[0], $orWhere[1], $orWhere[2]);
                    }
                }

                $result = $query->paginate($config['per_page']);
                $result->setPath('?per_page=' . $config['per_page']);
            } catch (\Exception $e) {
                $return['message'] = $e->getMessage();
            }
        }

        if (!empty($result)) {
            $return['status'] = 1;
            $return['message'] = 'Agency Type list successfully';
            $return['data'] = $result;
            return $return;
        } else {
            return $return;
        }
    }
    //insert new record into database.

    public static function createAgencyType($requestData)
    {

        $return = array(
            'status' => 0,
            'message' => 'something went wrong',
            'data' => []
        );

        $agencyTypeArrayData = array(
            'name' => $requestData['agency_name'],
            'description' => $requestData['agency_description'],
            'is_active' => $requestData['status'],
            'code' => str_replace(' ', '_', strtoupper($requestData['agency_name'])),
        );
        try {
            DB::beginTransaction();
            $agencyTypeData = AgencyType::create($agencyTypeArrayData);

            DB::commit();
            if ($agencyTypeData) {
                $return['status'] = 1;
                $return['message'] = 'Agency Type [' . $agencyTypeData['name'] . ']save successfully';
                $return['data'] = $agencyTypeData;
            }
        } catch (\Exception $e) {
            $return['message'] = 'Error during save home banner record : ' . $e->getMessage();
        }


        return $return;
    }
    // update record into database.

    public static function updateAgencyType($requestData)
    {

        $return = array(
            'status' => 0,
            'message' => 'something went wrong',
            'data' => []
        );
        try {

            @$agencyTypeDetails = array(
                'id' => $requestData['agency_type_id'],
                'name' => $requestData['agency_name'],
                'description' => $requestData['agency_description'],
                'is_active' => $requestData['status'],
                'code' => str_replace(' ', '_', strtoupper($requestData['agency_name'])),
            );

            try {
                DB::beginTransaction();
                $matchAgencyType = ['id' => $agencyTypeDetails['id']];
                $agencyTypeData = AgencyType::updateOrCreate($matchAgencyType, $agencyTypeDetails);

                DB::commit();
                if ($agencyTypeData) {
                    $return['status'] = 1;
                    $return['message'] = 'Agency Type [' . $agencyTypeData['name'] . '] Saved Successfully';
                    $return['data'] = $agencyTypeData;
                }
            } catch (\Exception $e) {
                $return['message'] = 'Error during save user record : ' . $e->getMessage();
            }
        } catch (\Exception $e) {
            $return['message'] = 'Something went wrong : ' . $e->getMessage();
        }
        return $return;
    }

    //Remove record from database.

    public static function deleteAgency($agency_id)
    {

        $is_dependent = AgencyType::checkDependancy($agency_id);
        $return = array(
            'status' => 0,
            'message' => 'Something went wrong'
        );

        $agencyTypeData = AgencyType::where('id', $agency_id)->first()->toArray();
        if (($is_dependent)) {

            //update status to deleted
            AgencyType::where('id', $agency_id)->update(['is_active' => 2]);
            $module_names = implode(', ', $is_dependent);
            $return['status'] = 1;
            $return['message'] = 'Agency type [' . $agencyTypeData['name'] . '] exist in [' . $module_names . ']. Hence, it can soft deleted';
        } else {
            AgencyType::where('id', $agency_id)->delete();
            $return['status'] = 1;
            $return['message'] = 'Agency type [' . $agencyTypeData['name'] . '] delete successfully';
        }

        return $return;
    }

    public static function checkDependancy($agency_id)
    {

        /**
         * in future need to check dependancy to other reference table and need to set true if 
         * any dependancy set in reference tables
         **/
        $dep_modules = [];
        $agency_service_type_record = Agency::where('core_agency_type_id', $agency_id)->count();

        if ($agency_service_type_record > 0) {
            array_push($dep_modules, 'Agency');
        }
        return $dep_modules;
    }
}
