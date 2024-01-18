<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\CityI18n;
use App\Models\Airport;
use App\Models\State;
use App\Traits\Uuids;
use Illuminate\Support\Facades\DB;

class City extends Model
{
    use HasFactory, Uuids, SoftDeletes;

    public function cityCode()
    {
        return $this->hasMany('App\Models\CityI18n', 'city_id', 'id');
    }
    public function getCountry()
    {
        return $this->belongsTo('App\Models\Country', 'country_code', 'iso_code')->withTrashed()->with('countryCode');
    }

    protected $guarded = [];
    protected static $logAttributes = ['iso_code', 'country_code', 'latitude', 'longitude', 'status'];

    protected static $logName = 'cities';

    /**
     *get list or single or all record to display
     */
    public static function getCityData($option = array())
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
                $query = City::query();
                $query->withTrashed();
                $query->with(['cityCode', 'getCountry']);
                $query->select(
                    "cities.*",
                    DB::raw('(CASE WHEN cities.status = "inactive" THEN "In-Active" '
                        . 'WHEN cities.status = "active" THEN "Active" '
                        . 'END) AS city_status_text'),
                );
                $query->where('cities.id', $config['id']);

                $result = $query->first();
            } catch (\Exception $e) {
                $return['message'] = $e->getMessage();
            }
        } else {
            try {
                $query = City::query();
                $query->withTrashed();
                $query->with(['cityCode', 'getCountry']);
                $query->whereHas('cityCode', function ($q) use ($config) {
                    if (!empty($config['whereHas'])) {
                        foreach ($config['whereHas'] as $where) {
                            $q->where($where[0], $where[1], $where[2]);
                        }
                    }
                });
                $query->select(
                    "cities.*",
                    DB::raw('(CASE WHEN cities.status = "inactive" THEN "In-Active" '
                        . 'WHEN cities.status = "active" THEN "Active" '
                        . 'END) AS city_status_text')
                );

                if ($config['order_by'] == 'city_name') {
                    $query->join('city_i18ns', 'cities.id', '=', 'city_i18ns.city_id')
                        ->where('city_i18ns.language_code', 'en')
                        ->orderBy('city_i18ns.city_name', $config['sorting']);
                }
                if ($config['order_by'] == 'country_name') {
                    $query->join('countries as c', 'cities.country_code', '=', 'c.iso_code')
                        ->join('country_i18ns as ci', 'c.id', '=', 'ci.country_id')
                        ->where('ci.language_code', 'en')
                        ->orderBy('ci.country_name', $config['sorting']);
                }
                if ($config['order_by'] == 'created_at' || $config['order_by'] == 'iso_code' || $config['order_by'] == 'country_code') {

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
            $return['message'] = 'City list successfully';
            $return['data'] = $result;
            return $return;
        } else {
            return $return;
        }
    }

    /**
     * insret new record in database
     */
    public static function createCity($requestData)
    {
        $return = array(
            'status' => 0,
            'message' => 'something went wrong',
            'data' => []
        );

        try {
            $cityData = array(
                'iso_code'  => strtoupper($requestData['iso_code']),
                'country_code'  => $requestData['country_code'],
                'latitude'  => $requestData['latitude'],
                'longitude'  => $requestData['longitude'],
            );
            // save to table
            try {
                DB::beginTransaction();
                $city = City::create($cityData);

                if ($city) {

                    $cityNames = $requestData['city_names'];
                    foreach ($cityNames as $key => $name) {
                        $nameData = array(
                            'city_id' => $city->id,
                            'city_name' => $name['city_name'],
                            'language_code' => $name['language_code']
                        );
                        CityI18n::create($nameData);
                        $citymsg[] = $name['city_name'];
                    }

                    $return['status'] = 1;
                    $return['message'] = 'City [' . implode(', ', $citymsg) . '] saved successfully';
                    $return['data'] = $city;
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
     *update record in database
     */
    public static function updateCity($requestData)
    {
        $return = array(
            'status' => 0,
            'message' => 'something went wrong',
            'data' => []
        );
        $countryCode = $requestData['country_code'];

        try {
            $regionData = array(
                'iso_code'  => $requestData['iso_code'],
                'country_code'  => $countryCode,
                'latitude'  => $requestData['latitude'],
                'longitude'  => $requestData['longitude'],
                'status'  => $requestData['status'],
            );
            // save to table
            try {
                DB::beginTransaction();
                $matchCityData = ['id' => $requestData['city_id']];
                $updateCityData = City::updateOrCreate($matchCityData, $regionData);

                if ($updateCityData) {

                    $cityNames = $requestData['city_names'];
                    foreach ($cityNames as $key => $name) {
                        $nameData = array(
                            'city_id' => $updateCityData->id,
                            'city_name' => $name['city_name'],
                            'language_code' => $name['language_code']
                        );
                        $matchCityData = ['id' => $name['city_i18ns_id']];
                        CityI18n::updateOrCreate($matchCityData, $nameData);
                        $citymsg[] = $name['city_name'];
                    }

                    $return['status'] = 1;
                    $return['message'] = 'City [' . implode(', ', $citymsg) . '] updated successfully';
                    $return['data'] = $updateCityData;
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
     *delete record from database
     */
    public static function deleteCities($delete_city_id)
    {
        $return = array(
            'status' => 0,
            'message' => 'Something went wrong'
        );

        $cityData = City::where('id', $delete_city_id)->with('cityCode')->withTrashed()->first();
        foreach ($cityData['cityCode'] as $key => $name) {
            $nameData = array(
                'city_name' => $name['city_name'],
                'language_code' => $name['language_code']
            );
            $citymsg[] = $name['city_name'];
        }
        $is_dependent = City::checkDependancy($cityData['iso_code'], $delete_city_id);
        if ($is_dependent) {
            //update status to deleted
            City::where('id', $delete_city_id)->delete();
            $module_names = implode(', ', $is_dependent);
            $return['status'] = 1;
            $return['message'] = 'City  [' . implode(', ', $citymsg) . '] exist in [' . $module_names . ']. Hence, it can soft deleted';
        } else {
            City::where('id', $delete_city_id)->forceDelete();

            $return['status'] = 1;
            $return['message'] = 'City [' . implode(', ', $citymsg) . '] deleted successfully';
        }
        return $return;
    }

    public static function checkDependancy($code, $delete_city_id)
    {
        /**
         * in future need to check dependancy to other reference table and need to set true if 
         * any dependancy set in reference tables
         **/
        $dep_modules = [];

        $states_record = State::where('city_code', $code)->count();

        if ($states_record > 0) {
            array_push($dep_modules, 'State');
        }

        $airports_record = Airport::where('city_code', $code)->count();

        if ($airports_record > 0) {
            array_push($dep_modules, 'Airport');
        }

        return $dep_modules;
    }

    /**
     *restore deleted record
     */
    public static function restoreCities($restore_city_id)
    {

        $return = array(
            'status' => 0,
            'message' => 'Something went wrong'
        );

        $cityData = City::withTrashed()->find($restore_city_id);
        if ($cityData) {
            $cityData->restore();
            $return['status'] = 1;
            $return['message'] = 'City [' . $cityData['iso_code'] . '] restored successfully';
        }
        return $return;
    }
}
