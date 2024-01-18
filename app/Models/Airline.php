<?php

/**
 * @package     Geography
 * @subpackage  Airline
 * @Author      Amar Technolabs Pvt. ltd(info@amarinfotech.com)
 * @Copyright(C) 2023 [Travel Portal].
 * @Version 1.0.0
 * module of the Geography.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Carbon;
use Spatie\Activitylog\Traits\LogsActivity;
use DateTime;
use App\Models\AirlineI18ns;
use App\Traits\Uuids;
use Illuminate\Support\Facades\DB;
use Intervention\Image\ImageManagerStatic as Image;

class Airline extends Model
{
    use HasFactory, LogsActivity, Uuids, SoftDeletes;
    protected $table = 'airlines';
    protected $guarded = [];
    protected static $logAttributes = ['airline_code', 'airline_name', 'status'];
    public function airlineCodeName()
    {
        return $this->hasMany('App\Models\AirlineI18ns', 'airline_id', 'id');
    }

    /**
     * get list or single or all record to display
     */
    public static function getAirlineData($option = array())
    {
        $return = array(
            'status' => 0,
            'message' => 'something went wrong',
            'data' => []
        );

        $data = array(
            'id' => '',
            'order_by' => 'created_at',
            'sorting' => 'desc',
            'where' => array(),
            'whereHas' => array(),
            'orWhere' => array()
        );
        $config = array_merge($data, $option);
        $result = [];
        if ($config['id'] != '') {
            try {
                $query = Airline::query();
                $query->withTrashed();
                $query->with(['airlineCodeName']);
                $query->select(
                    "airlines.*",
                    DB::raw('(CASE WHEN airlines.status = "inactive" THEN "In-Active" '
                        . 'WHEN airlines.status = "active" THEN "Active" '
                        . 'END) AS airline_status_text'),
                );
                $query->where('airlines.id', $config['id']);

                $result = $query->first();
            } catch (\Exception $e) {
                $return['message'] = $e->getMessage();
            }
        } else {
            try {
                $query = Airline::query();
                $query->withTrashed();
                $query->with(['airlineCodeName']);
                $query->whereHas('airlineCodeName', function ($q) use ($config) {
                    if (!empty($config['whereHas'])) {
                        foreach ($config['whereHas'] as $where) {
                            $q->where($where[0], $where[1], $where[2]);
                        }
                    }
                });
                $query->select(
                    "airlines.*",
                    DB::raw('(CASE WHEN airlines.status = "inactive" THEN "In-Active" '
                        . 'WHEN airlines.status = "active" THEN "Active" '
                        . 'END) AS airline_status_text')
                );
                if ($config['order_by'] == 'airline_name') {
                    $query->join('airline_i18ns', 'airlines.id', '=', 'airline_i18ns.airline_id')
                        ->where('airline_i18ns.language_code', 'en')
                        ->orderBy('airline_i18ns.airline_name', $config['sorting']);
                }
                if ($config['order_by'] == 'airline_code') {
                    $query->join('airlines as c', 'airlines.airline_code', '=', 'c.airline_code')
                        ->join('airline_i18ns as ci', 'c.id', '=', 'ci.airline_id')
                        ->where('ci.language_code', 'en')
                        ->orderBy('c.airline_code', $config['sorting']);
                }
                if ($config['order_by'] == 'created_at' || $config['order_by'] == 'airline_code' || $config['order_by'] == 'airline_name') {

                    $query->orderBy($config['order_by'], $config['sorting']);
                }
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
            $return['message'] = 'airline list successfully';
            $return['data'] = $result;
            return $return;
        } else {
            return $return;
        }
    }
    /**
     * insert new record in database
     */
    public static function createAirline($requestData)
    {
        $return = array(
            'status' => 0,
            'message' => 'something went wrong',
            'data' => []
        );

        try {
            $airlineData = array(
                'airline_code'  => strtoupper($requestData['airline_code']),
                'status'  => $requestData['status'],
            );
            if (isset($requestData['airline_logo'])) {
                //upload profile image
                try {
                    $destinationPath = storage_path() . '/app/public/airlineLogo/';
                    if (!is_dir($destinationPath)) {
                        /* Directory does not exist, so lets create it. */
                        mkdir($destinationPath, 0777);
                    }
                    $file = $requestData['airline_logo']->extension();
                    $image_resize = Image::make($requestData['airline_logo']);
                    $image_resize->resize(180, 105);
                    $fileName =  $airlineData['airline_code'] . '.' . $file;
                    $image_resize->save($destinationPath . $fileName);
                    $url = URL::to('/storage/') . '/airlineLogo/' . $fileName;
                    $airlineData['airline_logo'] = $url;
                } catch (\Exception $e) {
                    $return['message'] = 'Error during save profile image : ' . $e->getMessage();
                }
            } else {
                //upload with no-image
                try {
                    $airlineData['airline_logo'] = "";
                } catch (\Exception $e) {
                    $return['message'] = 'Error during save no-image profile image : ' . $e->getMessage();
                }
            }
            // save to table
            try {
                DB::beginTransaction();
                $airline = Airline::create($airlineData);


                if ($airline) {

                    $airlineNames = $requestData['airline_names'];
                    foreach ($airlineNames as $key => $name) {
                        $nameData = array(
                            'airline_id' => $airline->id,
                            'airline_name' => $name['airline_name'],
                            'language_code' => $name['language_code']
                        );
                        AirlineI18ns::create($nameData);

                        $airlinemsg[] = $name['airline_name'];
                    }

                    $return['status'] = 1;
                    $return['message'] = 'Airline [' . implode(', ', $airlinemsg) . '] saved successfully';
                
                    $return['data'] = $airline;
                }
                DB::commit();
            } catch (\Exception $e) {
                $return['message'] = 'Error during save record : ' . $e->getMessage();
            }
        } catch (\Exception $e) {
            $return['message'] = 'Something went wrong : ' . $e->getMessage();
        }
        return $return;
    }

    /**
     * update record into database
     */
    public static function updateairline($requestData)
    {
        $return = array(
            'status' => 0,
            'message' => 'something went wrong',
            'data' => []
        );

        try {
            $airlineData = array(
                'airline_code'  => strtoupper($requestData['airline_code']),
                'status'  => $requestData['status'],
            );
            if (isset($requestData['airline_logo'])) {
                //upload profile image
                try {
                    $destinationPath = storage_path() . '/app/public/airlineLogo/';
                    if (!is_dir($destinationPath)) {
                        /* Directory does not exist, so lets create it. */
                        mkdir($destinationPath, 0777);
                    }
                    $file = $requestData['airline_logo']->extension();

                    $image_resize = Image::make($requestData['airline_logo']);
                    $image_resize->resize(180, 105);
                    $fileName =  $airlineData['airline_code'] . '.' . $file;
                    $image_resize->save($destinationPath . $fileName);
                    $url = URL::to('/storage/') . '/airlineLogo/' . $fileName;
                    $airlineData['airline_logo'] = $url;
                } catch (\Exception $e) {
                    $return['message'] = 'Error during save profile image : ' . $e->getMessage();
                }
            } else {
                $airlineData['airline_logo'] = $requestData['old_airline_logo'];
            }
            // save to table
            try {
                DB::beginTransaction();
                $matchairlineData = ['id' => $requestData['airline_id']];
                $updateairlineData = Airline::updateOrCreate($matchairlineData, $airlineData);

                if ($updateairlineData) {

                    $airlineNames = $requestData['airline_names'];
                    foreach ($airlineNames as $key => $name) {
                        $nameData = array(
                            'airline_id' => $updateairlineData->id,
                            'airline_name' => $name['airline_name'],
                            'language_code' => $name['language_code']
                        );
                        $matchairlineData = ['id' => $name['airline_i18ns_id']];
                        AirlineI18ns::updateOrCreate($matchairlineData, $nameData);
                        $airlinemsg[] = $name['airline_name'];
                    }

                    $return['status'] = 1;
                    $return['message'] = 'airline [' . implode(', ', $airlinemsg) . '] updated successfully';
                    $return['data'] = $updateairlineData;
                }
                DB::commit();
            } catch (\Exception $e) {
                $return['message'] = 'Error during save record : ' . $e->getMessage();
            }
        } catch (\Exception $e) {
            $return['message'] = 'Something went wrong : ' . $e->getMessage();
        }
        return $return;
    }

    /**
     * remove record from database
     */
    public static function deleteAirline($delete_airline_id)
    {

        $return = array(
            'status' => 0,
            'message' => 'Something went wrong'
        );

        $airlineData = Airline::where('id', $delete_airline_id)->with('airlineCodeName')->withTrashed()->first()->toArray();
        $is_dependent = Airline::checkDependancy($airlineData['airline_code'], $delete_airline_id);
        foreach ($airlineData['airline_code_name'] as $key => $name) {
            $nameData = array(
                'airline_name' => $name['airline_name'],
                'language_code' => $name['language_code']
            );
            $airportmsg[] = $name['airline_name'];
        }
        if ($is_dependent) {
            Airline::where('id', $delete_airline_id)->delete();
            $module_names = implode(', ', $is_dependent);
            $return['status'] = 1;
            $return['message'] = 'Airline Name [' . implode(', ', $airportmsg) . '] exist in [' . $module_names . ']. Hence, it can soft deleted';
        } else {
            Airline::where('id', $delete_airline_id)->forceDelete();

            $return['status'] = 1;
            $return['message'] = 'Airline [' . implode(', ', $airportmsg) . '] deleted successfully';
        }
        return $return;
    }

    public static function checkDependancy($code, $delete_airline_id)
    {
        /**
         * in future need to check dependancy to other reference table and need to set true if 
         * any dependancy set in reference tables
         **/
        $dep_modules = [];
        $airports_record = FeatureFlight::where('airline_code', $code)->count();
        if ($airports_record > 0) {
            array_push($dep_modules, 'FeatureFlights');
        }
        return $dep_modules;
    }

    /**
     * restore deleted record
     **/
    public static function restoreAirlines($restore_airline_id)
    {

        $return = array(
            'status' => 0,
            'message' => 'Something went wrong'
        );

        $airlineData = Airline::withTrashed()->find($restore_airline_id);
        if ($airlineData) {
            $airlineData->restore();
            $return['status'] = 1;
            $return['message'] = 'Airline [' . $airlineData['airline_code'] . '] restored successfully';
        }
        return $return;
    }
}
