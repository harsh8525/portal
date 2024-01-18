<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CountryI18ns;
use App\Models\Airport;
use App\Models\City;
use App\Models\State;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\URL;
use DB;
use App\Traits\Uuids;

class Country extends Model
{
    use HasFactory, Uuids, SoftDeletes;

    protected $guarded = [];
    protected static $logAttributes = ['iso_code', 'isd_code', 'max_mobile_number_length', 'status'];

    protected static $logName = 'countries';

    public function countryCode()
    {
        return $this->hasMany('App\Models\CountryI18ns', 'country_id', 'id');
    }

    /**
     * get list or single or all records to display
     */
    public static function getCountryData($option = array())
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
            'orWhere' => array()
        );
        $config = array_merge($data, $option);
        $result = [];
        if ($config['id'] != '') {
            try {
                $query = Country::query();
                $query->withTrashed();
                $query->with('countryCode');
                $query->select(
                    "countries.*",
                    DB::raw('(CASE WHEN countries.status = "inactive" THEN "In-Active" '
                        . 'WHEN countries.status = "active" THEN "Active" '
                        . 'END) AS country_status_text'),
                );
                $query->where('countries.id', $config['id']);

                $result = $query->first();
            } catch (\Exception $e) {
                $return['message'] = $e->getMessage();
            }
        } else {
            try {
                $query = Country::query();
                $query->withTrashed();
                $query->with('countryCode');
                $query->select(
                    "countries.*",
                    DB::raw('(CASE WHEN countries.status = "inactive" THEN "In-Active" '
                        . 'WHEN countries.status = "active" THEN "Active" '
                        . 'END) AS country_status_text')
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
            $return['message'] = 'Country list successfully';
            $return['data'] = $result;
            return $return;
        } else {
            return $return;
        }
    }

    /**
     * insert new record in database
     */
    public static function createCountry($requestData)
    {
        $return = array(
            'status' => 0,
            'message' => 'something went wrong',
            'data' => []
        );
        $isdCode = $requestData['isd_code'];

        if (substr($isdCode, 0, 1) !== '+') {
            $isdCode = '+' . $isdCode;
        }

        try {
            $regionData = array(
                'iso_code'  => $requestData['iso_code'],
                'isd_code'  => $isdCode,
                'max_mobile_number_length'  => $requestData['max_mobile_number_length'],
                'status'  => $requestData['status'],
            );
            // save to table
            try {
                DB::beginTransaction();
                $country = Country::create($regionData);

                if ($country) {

                    $countryNames = $requestData['country_names'];
                    foreach ($countryNames as $key => $name) {
                        $nameData = array(
                            'country_id' => $country->id,
                            'country_name' => $name['country_name'],
                            'language_code' => $name['language_code']
                        );
                        CountryI18ns::create($nameData);
                        $citymsg[] = $name['country_name'];
                    }

                    $return['status'] = 1;
                    $return['message'] = 'Country [' . implode(', ', $citymsg) . '] saved successfully';
                    $return['data'] = $country;
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
     * update record in database
     */
    public static function updateCountry($requestData)
    {
        $return = array(
            'status' => 0,
            'message' => 'something went wrong',
            'data' => []
        );
        $isdCode = $requestData['isd_code'];

        if (substr($isdCode, 0, 1) !== '+') {
            $isdCode = '+' . $isdCode;
        }

        try {
            $regionData = array(
                'iso_code'  => $requestData['iso_code'],
                'isd_code'  => $isdCode,
                'max_mobile_number_length'  => $requestData['max_mobile_number_length'],
                'status'  => $requestData['status'],
            );
            // save to table
            try {
                DB::beginTransaction();
                $matchCountryData = ['id' => $requestData['country_id']];
                $updateCountryData = Country::updateOrCreate($matchCountryData, $regionData);

                if ($updateCountryData) {

                    $countryNames = $requestData['country_names'];
                    foreach ($countryNames as $key => $name) {
                        $nameData = array(
                            'country_id' => $updateCountryData->id,
                            'country_name' => $name['country_name'],
                            'language_code' => $name['language_code']
                        );
                        $matchCountryData = ['id' => $name['country_i18ns_id']];
                        CountryI18ns::updateOrCreate($matchCountryData, $nameData);
                        $citymsg[] = $name['country_name'];
                    }

                    $return['status'] = 1;
                    $return['message'] = 'Country [' . implode(', ', $citymsg) . '] updated successfully';
                    $return['data'] = $updateCountryData;
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
     * delete record from database
     */
    public static function deleteCountries($delete_country_id)
    {

        $return = array(
            'status' => 0,
            'message' => 'Something went wrong'
        );

        $countryData = Country::where('id', $delete_country_id)->with('countryCode')->withTrashed()->first()->toArray();
        $is_dependent = Country::checkDependancy($countryData['iso_code'], $delete_country_id);
        foreach ($countryData['country_code'] as $key => $name) {
            $nameData = array(
                'country_name' => $name['country_name'],
                'language_code' => $name['language_code']
            );
            $countrymsg[] = $name['country_name'];
        }
        if ($is_dependent) {
            //update status to deleted
            Country::where('id', $delete_country_id)->delete();
            $module_names = implode(', ', $is_dependent);
            $return['status'] = 1;
            $return['message'] = 'Country Code [' . implode(', ', $countrymsg) . '] exist in [' . $module_names . ']. Hence, it can soft deleted';
        } else {
            Country::where('id', $delete_country_id)->forceDelete();

            $return['status'] = 1;
            $return['message'] = 'Country [' . implode(', ', $countrymsg) . '] deleted successfully';
        }
        return $return;
    }

    public static function checkDependancy($code, $delete_country_id)
    {
        /**
         * in future need to check dependancy to other reference table and need to set true if 
         * any dependancy set in reference tables
         **/
        $dep_modules = [];
        $cities_record = City::where('country_code', $code)->count();

        if ($cities_record > 0) {
            array_push($dep_modules, 'City');
        }

        $states_record = State::where('country_code', $code)->count();

        if ($states_record > 0) {
            array_push($dep_modules, 'State');
        }

        $airports_record = Airport::where('country_code', $code)->count();

        if ($airports_record > 0) {
            array_push($dep_modules, 'Airport');
        }

        return $dep_modules;
    }

    /**
     * restore deleted record
     */
    public static function restoreCountries($restore_country_id)
    {

        $return = array(
            'status' => 0,
            'message' => 'Something went wrong'
        );

        $countryData = Country::withTrashed()->find($restore_country_id);
        if ($countryData) {
            $countryData->restore();
            $return['status'] = 1;
            $return['message'] = 'Country [' . $countryData['iso_code'] . '] restored successfully';
        }
        return $return;
    }
}
