<?php

/**
 * @package     Operational Data
 * @subpackage  Service Type
 * @Author      Amar Technolabs Pvt. ltd(info@amarinfotech.com)
 * @Copyright(C) 2023 [Travel Portal].
 * @Version 1.0.0
 * module of the Service Type.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Carbon;
use Intervention\Image\ImageManagerStatic as Image;
use Spatie\Activitylog\Traits\LogsActivity;
use DateTime;
use DB;
use Illuminate\Support\Facades\Hash;
use App\Models\AgencyServiceType;

class ServiceType extends Model
{
    use HasFactory;
    protected $table = 'core_service_types';
    protected $guarded = [];

    protected static $logAttributes = ['name', 'description', 'guideline', 'is_active'];

    protected static $logName = 'core_service_types';

    /*
    * get list or single or all record to display
    */
    public static function getServiceType($option = array())
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
                $query = ServiceType::query();

                $query->select(
                    'core_service_types.*',
                    DB::raw('(CASE WHEN core_service_types.is_active = "0" THEN "In-Active" '
                        . 'WHEN core_service_types.is_active = "1" THEN "Active" '
                        . 'END) AS service_type_status_text')
                );
                $query->where('id', $config['id']);

                $result = $query->first();
            } catch (\Exception $e) {
                $return['message'] = $e->getMessage();
            }
        } else {
            try {
                $query = ServiceType::query();
                $query->select(
                    'core_service_types.*',
                    DB::raw('(CASE WHEN core_service_types.is_active = "0" THEN "In-Active" '
                        . 'WHEN core_service_types.is_active = "1" THEN "Active" '
                        . 'END) AS service_type_status_text')
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
            $return['message'] = 'Service Type list successfully';
            $return['data'] = $result;
            return $return;
        } else {
            return $return;
        }
    }

    /*
    * insert record in database
    */
    public static function createServiceType($requestData)
    {

        $return = array(
            'status' => 0,
            'message' => 'something went wrong',
            'data' => []
        );

        $serviceTypeArrayData = array(
            'name' => $requestData['service_name'],
            'description' => $requestData['service_description'],
            'guideline' => $requestData['guideline'],
            'is_active' => $requestData['status'],
        );

        if (isset($requestData['profile_image'])) {
            try {
                $destinationPath = storage_path() . '/app/public/service_type/';
                if (!is_dir($destinationPath)) {
                    mkdir($destinationPath, 0777);
                }

                $file = $requestData['profile_image'];
                $image_resize = Image::make($requestData['profile_image']);
                $image_resize->resize(75, 80);
                $fileName =  uniqid() . time() . '.' . $requestData['profile_image']->extension();
                $image_resize->save($destinationPath . $fileName);
                $url = URL::to('/storage/') . '/service_type/' . $fileName;
                $serviceTypeArrayData['image'] = $url;
            } catch (Exception $e) {
                $return['message'] = 'Error during save image banner :' . $e->getMessage();
            }
        }

        try {
            DB::beginTransaction();
            $serviceTypeData = ServiceType::create($serviceTypeArrayData);

            DB::commit();
            if ($serviceTypeData) {
                $return['status'] = 1;
                $return['message'] = 'Service Type [' . $serviceTypeData['name'] . '] save successfully';
                $return['data'] = $serviceTypeData;
            }
        } catch (\Exception $e) {
            $return['message'] = 'Error during save home banner record : ' . $e->getMessage();
        }


        return $return;
    }

    /*
    * update record in database
    */
    public static function updateServiceType($requestData)
    {

        $return = array(
            'status' => 0,
            'message' => 'something went wrong',
            'data' => []
        );
        try {

            @$serviceTypeDetails = array(
                'id' => $requestData['service_type_id'],
                'name' => $requestData['service_name'],
                'description' => $requestData['service_description'],
                'guideline' => $requestData['guideline'],
                'image' => $requestData['profile_image'],
                'is_active' => $requestData['status'],
                'image' => $requestData['old_image'],
            );
            if (isset($requestData['profile_image'])) {
                try {
                    $destinationPath = storage_path() . '/app/public/service_type/';
                    if (!is_dir($destinationPath)) {
                        mkdir($destinationPath, 0777);
                    }

                    $file = $requestData['profile_image'];
                    $image_resize = Image::make($requestData['profile_image']);
                    $image_resize->resize(75, 80);
                    $fileName =  uniqid() . time() . '.' . $requestData['profile_image']->extension();
                    $image_resize->save($destinationPath . $fileName);
                    $url = URL::to('/storage/') . '/service_type/' . $fileName;
                    $serviceTypeDetails['image'] = $url;

                    $p = parse_url($requestData['old_image']);
                    if ($p['path'] != "") {
                        $image_path = str_replace('/storage/', 'app/public/', $p['path']);
                        $image_path = storage_path($image_path);

                        if (file_exists($image_path)) {
                            unlink($image_path);
                        }
                    }
                } catch (Exception $e) {
                    $return['message'] = 'Error during save image banner :' . $e->getMessage();
                }
            }
            try {
                DB::beginTransaction();

                $matchServiecType = ['id' => $serviceTypeDetails['id']];
                $serviceTypeData = ServiceType::updateOrCreate($matchServiecType, $serviceTypeDetails);

                DB::commit();
                if ($serviceTypeData) {
                    $return['status'] = 1;
                    $return['message'] = 'Service Type [' . $serviceTypeData['name'] . '] Updated Successfully';
                    $return['data'] = $serviceTypeData;
                }
            } catch (\Exception $e) {
                $return['message'] = 'Error during save user record : ' . $e->getMessage();
            }
        } catch (\Exception $e) {
            $return['message'] = 'Something went wrong : ' . $e->getMessage();
        }
        return $return;
    }

    /*
    *  delete record from database
    */
    public static function deleteService($service_id)
    {
        $is_dependent = ServiceType::checkDependancy($service_id);
        $return = array(
            'status' => 0,
            'message' => 'Something went wrong'
        );

        $serviceTypeData = ServiceType::where('id', $service_id)->first()->toArray();

        if (!empty($is_dependent)) {
            ServiceType::where('id', $service_id)->update(['is_active' => 2]);
            $module_names = implode(', ', $is_dependent);
            $return['status'] = 1;
            $return['message'] = 'Service type [' . $serviceTypeData['name'] . '] exist in [' . $module_names . ']. Hence, it can soft deleted';
        } else {

            ServiceType::where('id', $service_id)->delete();
            $return['status'] = 1;
            $return['message'] = 'Service type [' . $serviceTypeData['name'] . '] Deleted successfully';
        }



        return $return;
    }

    public static function checkDependancy($service_id)
    {
        /**
         * in future need to check dependancy to other reference table and need to set true if 
         * any dependancy set in reference tables
         **/
        $dep_modules = [];
        $agency_service_type_record = AgencyServiceType::where('core_service_type_id', $service_id)->count();
        if ($agency_service_type_record > 0) {
            array_push($dep_modules, 'AgencyServiceType');
        }
        $core_suppliers_record = Suppliers::where('core_service_type_id', $service_id)->count();
        if ($core_suppliers_record > 0) {
            array_push($dep_modules, 'Suppliers');
        }

        return $dep_modules;
    }
}
