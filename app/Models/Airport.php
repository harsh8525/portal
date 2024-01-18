<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\URL;
use App\Models\AirportI18ns;
use App\Traits\Uuids;
use DB;

class Airport extends Model
{
    use HasFactory, Uuids, SoftDeletes;

    protected $guarded = [];
    protected static $logAttributes = ['iata_code', 'city_code', 'country_code', 'latitude', 'longitude'];

    protected static $logName = 'airports';

    public function airportName()
    {
        return $this->hasMany('App\Models\AirportI18ns', 'airport_id', 'id');
    }

    public function getCountry()
    {
        return $this->belongsTo('App\Models\Country', 'country_code', 'iso_code')->withTrashed()->with('countryCode');
    }

    public function getCity()
    {
        return $this->belongsTo('App\Models\City', 'city_code', 'iso_code')->withTrashed()->with('cityCode');
    }


    /**
     * get list or single or all record to display
     */
    public static function getAirPortData($option = array())
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
                $query = Airport::query();
                $query->withTrashed();
                $query->with(['airportName', 'getCountry', 'getCity']);
                $query->select(
                    "airports.*",
                    DB::raw('(CASE WHEN airports.status = "inactive" THEN "In-Active" '
                        . 'WHEN airports.status = "active" THEN "Active" '
                        . 'END) AS airport_status_text'),
                );
                $query->where('airports.id', $config['id']);

                $result = $query->first();
            } catch (\Exception $e) {
                $return['message'] = $e->getMessage();
            }
        } else {
            try {
                $query = Airport::query();
                $query->withTrashed();
                $query->with(['airportName', 'getCountry', 'getCity']);
                $query->select(
                    "airports.*",
                    DB::raw('(CASE WHEN airports.status = "inactive" THEN "In-Active" '
                        . 'WHEN airports.status = "active" THEN "Active" '
                        . 'END) AS airport_status_text'),
                );

                if ($config['order_by'] == 'airport_name') {
                    $query->join('airport_i18ns', 'airports.id', '=', 'airport_i18ns.airport_id')
                        ->where('airport_i18ns.language_code', 'en')
                        ->orderBy('airport_i18ns.airport_name', $config['sorting']);
                }

                if ($config['order_by'] == 'created_at' || $config['order_by'] == 'iata_code' || $config['order_by'] == 'latitude' || $config['order_by'] == 'longitude') {
                    $query->orderBy($config['order_by'], $config['sorting']);
                }

                if ($config['order_by'] == 'country_name') {
                    $query->join('countries as c', 'airports.country_code', '=', 'c.iso_code')
                        ->join('country_i18ns as ci', 'c.id', '=', 'ci.country_id')
                        ->where('ci.language_code', 'en')
                        ->orderBy('ci.country_name', $config['sorting']);
                }

                if ($config['order_by'] == 'city_name') {
                    $query->join('cities as city', 'airports.city_code', '=', 'city.iso_code')
                        ->join('city_i18ns as city_i18', 'city.id', '=', 'city_i18.city_id')
                        ->where('city_i18.language_code', 'en')
                        ->orderBy('city_i18.city_name', $config['sorting']);
                }

                if (!empty($config['where'])) {
                    foreach ($config['where'] as $where) {
                        $query->whereHas('airportName', function ($q) use ($where) {
                            $q->where($where[0], $where[1], $where[2]);
                        });
                    }
                }
                if (!empty($config['orWhere'])) {
                    foreach ($config['orWhere'] as $orWhere) {
                        $query->orWhereHas('airportName', function ($q) use ($orWhere) {
                            $q->where($orWhere[0], $orWhere[1], $orWhere[2]);
                        });
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
            $return['message'] = 'Aitport list successfully';
            $return['data'] = $result;
            return $return;
        } else {
            return $return;
        }
    }

    //insert new record in database
    public static function createAirport($requestData)
    {
        $return = array(
            'status' => 0,
            'message' => 'something went wrong',
            'data' => []
        );

        try {
            $airportData = array(
                'iata_code'  => $requestData['iata_code'],
                'city_code'  => $requestData['city_code'],
                'country_code'  => $requestData['country_code'],
                'latitude'  => $requestData['latitude'],
                'longitude'  => $requestData['longitude'],
                'status'  => $requestData['status'],
            );
            // save to table
            try {
                DB::beginTransaction();
                $airport = Airport::create($airportData);

                if ($airport) {

                    $airportNames = $requestData['airport_names'];
                    foreach ($airportNames as $key => $name) {
                        $nameData = array(
                            'airport_id' => $airport->id,
                            'airport_name' => $name['airport_name'],
                            'language_code' => $name['language_code']
                        );
                        AirportI18ns::create($nameData);
                        $airportmsg[] = $name['airport_name'];
                    }

                    $return['status'] = 1;
                    $return['message'] = 'Airport [' . implode(', ', $airportmsg) . '] saved successfully';
                    $return['data'] = $airport;
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
 //update record in database
    public static function updateAirport($requestData)
    {
        $return = array(
            'status' => 0,
            'message' => 'something went wrong',
            'data' => []
        );

        try {
            $airportData = array(
                'iata_code'  => $requestData['iata_code'],
                'city_code'  => $requestData['city_code'],
                'country_code'  => $requestData['country_code'],
                'latitude'  => $requestData['latitude'],
                'longitude'  => $requestData['longitude'],
                'status'  => $requestData['status'],
            );
            // save to table
            try {
                DB::beginTransaction();
                $matchAirportData = ['id' => $requestData['airport_id']];
                $updateAirportData = Airport::updateOrCreate($matchAirportData, $airportData);

                if ($updateAirportData) {

                    $airportNames = $requestData['airport_names'];
                    foreach ($airportNames as $key => $name) {
                        $nameData = array(
                            'airport_id' => $updateAirportData->id,
                            'airport_name' => $name['airport_name'],
                            'language_code' => $name['language_code']
                        );
                        $matchAirportData = ['id' => $name['airport_i18ns_id']];
                        AirportI18ns::updateOrCreate($matchAirportData, $nameData);
                        $airportmsg[] = $name['airport_name'];
                    }

                    $return['status'] = 1;
                    $return['message'] = 'Airport [' . implode(', ', $airportmsg) . '] updated successfully';
                    $return['data'] = $updateAirportData;
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

    //remove record from database
    public static function deleteAirports($delete_airport_id)
    {
        $return = array(
            'status' => 0,
            'message' => 'Something went wrong'
        );

        $airportData = Airport::where('id', $delete_airport_id)->with('airportName')->withTrashed()->first()->toArray();
        $is_dependent = Airport::checkDependancy($airportData['iata_code'], $delete_airport_id);
        foreach ($airportData['airport_name'] as $key => $name) {
            $nameData = array(
                'airport_name' => $name['airport_name'],
                'language_code' => $name['language_code']
            );
            $airportmsg[] = $name['airport_name'];
        }
        if ($is_dependent) {
            //update status to deleted
            Airport::where('id', $delete_airport_id)->delete();
            $module_names = implode(', ', $is_dependent);
            $return['status'] = 1;
            $return['message'] = 'Airport Name [' . implode(', ', $airportmsg) . '] exist in [' . $module_names . ']. Hence, it can soft deleted';
        } else {
            Airport::where('id', $delete_airport_id)->forceDelete();

            $return['status'] = 1;
            $return['message'] = 'Airport [' . implode(', ', $airportmsg) . '] deleted successfully';
        }
        return $return;
    }

    public static function checkDependancy($code, $delete_airport_id)
    {
        /**
         * in future need to check dependancy to other reference table and need to set true if 
         * any dependancy set in reference tables
         **/
        $dep_modules = [];
        $airports_record = FeatureFlight::where('from_airport_code', $code)->orwhere('to_airport_code', $code)->count();
        if ($airports_record > 0) {
            array_push($dep_modules, 'FeatureFlights');
        }
        return $dep_modules;
    }

    //restore deleted record 
    public static function restoreAirports($restore_airport_id)
    {

        $return = array(
            'status' => 0,
            'message' => 'Something went wrong'
        );

        $airportData = Airport::withTrashed()->find($restore_airport_id);
        if ($airportData) {
            $airportData->restore();
            $return['status'] = 1;
            $return['message'] = 'Airport [' . $airportData['iata_code'] . '] restored successfully';
        }
        return $return;
    }
}
