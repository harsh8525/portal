<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\URL;
use App\Models\StateI18ns;
use App\Traits\Uuids;
use DB;

class State extends Model
{
    use HasFactory, Uuids, SoftDeletes;

    protected $guarded = [];
    protected static $logAttributes = ['iso_code', 'country_code', 'latitude', 'longitude', 'status'];

    protected static $logName = 'states';

    public function stateName()
    {
        return $this->hasMany('App\Models\StateI18ns', 'state_id', 'id');
    }

    public function getCountry()
    {
        return $this->belongsTo('App\Models\Country', 'country_code', 'iso_code')->withTrashed()->with('countryCode');
    }

    /*
    * get list or single or all record to display
    */   
    public static function getStateData($option = array())
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
                $query = State::query();
                $query->withTrashed();
                $query->with(['stateName', 'getCountry']);
                $query->select(
                    "states.*",
                    DB::raw('(CASE WHEN states.status = "inactive" THEN "In-Active" '
                        . 'WHEN states.status = "active" THEN "Active" '
                        . 'END) AS country_status_text'),
                );
                $query->where('states.id', $config['id']);

                $result = $query->first();
            } catch (\Exception $e) {
                $return['message'] = $e->getMessage();
            }
        } else {
            try {
                $query = State::query();
                $query->withTrashed();
                $query->with(['stateName', 'getCountry']);
                $query->select(
                    "states.*",
                    DB::raw('(CASE WHEN states.status = "inactive" THEN "In-Active" '
                        . 'WHEN states.status = "active" THEN "Active" '
                        . 'END) AS country_status_text')
                );

                if ($config['order_by'] == 'state_name') {
                    $query->join('state_i18ns', 'states.id', '=', 'state_i18ns.state_id')
                        ->where('state_i18ns.language_code', 'en')
                        ->orderBy('state_i18ns.state_name', $config['sorting']);
                }

                if ($config['order_by'] == 'created_at' || $config['order_by'] == 'iso_code') {
                    $query->orderBy($config['order_by'], $config['sorting']);
                }

                if ($config['order_by'] == 'country_name') {
                    $query->join('countries as c', 'states.country_code', '=', 'c.iso_code')
                        ->join('country_i18ns as ci', 'c.id', '=', 'ci.country_id')
                        ->where('ci.language_code', 'en')
                        ->orderBy('ci.country_name', $config['sorting']);
                }

                if (!empty($config['where'])) {
                    foreach ($config['where'] as $where) {
                        $query->whereHas('stateName', function ($q) use ($where) {
                            $q->where($where[0], $where[1], $where[2]);
                        });
                    }
                }
                if (!empty($config['orWhere'])) {
                    foreach ($config['orWhere'] as $orWhere) {
                        $query->orWhereHas('stateName', function ($q) use ($orWhere) {
                            $q->orWhere($orWhere[0], $orWhere[1], $orWhere[2]);
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
            $return['message'] = 'State list successfully';
            $return['data'] = $result;
            return $return;
        } else {
            return $return;
        }
    }

    /*
    * insert record in database
    */
    public static function createState($requestData)
    {
        $return = array(
            'status' => 0,
            'message' => 'something went wrong',
            'data' => []
        );
        try {
            $stateData = array(
                'iso_code'  => $requestData['iso_code'],
                'country_code'  => $requestData['country_code'],
                'latitude'  => $requestData['latitude'],
                'longitude'  => $requestData['longitude'],
                'status'  => $requestData['status'],
            );
            try {
                DB::beginTransaction();
                $state = State::create($stateData);

                if ($state) {

                    $stateNames = $requestData['state_names'];
                    foreach ($stateNames as $key => $name) {
                        $nameData = array(
                            'state_id' => $state->id,
                            'state_name' => $name['state_name'],
                            'language_code' => $name['language_code']
                        );

                        StateI18ns::create($nameData);
                        $countrymsg[] = $nameData['state_name'];
                    }

                    $return['status'] = 1;
                    $return['message'] = 'State [' . implode(', ', $countrymsg) . '] saved successfully';
                    $return['data'] = $state;
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

    /*
    * update record in database
    */
    public static function updateState($requestData)
    {
        $return = array(
            'status' => 0,
            'message' => 'something went wrong',
            'data' => []
        );

        try {
            $stateData = array(
                'iso_code'  => $requestData['iso_code'],
                'country_code'  => $requestData['country_code'],
                'latitude'  => $requestData['latitude'],
                'longitude'  => $requestData['longitude'],
                'status'  => $requestData['status'],
            );
            try {
                DB::beginTransaction();
                $matchStateData = ['id' => $requestData['state_id']];
                $updateStateData = State::updateOrCreate($matchStateData, $stateData);

                if ($updateStateData) {

                    $stateNames = $requestData['state_names'];
                    foreach ($stateNames as $key => $name) {
                        $nameData = array(
                            'state_id' => $updateStateData->id,
                            'state_name' => $name['state_name'],
                            'language_code' => $name['language_code']
                        );
                        $matchStateData = ['id' => $name['state_i18ns_id']];
                        StateI18ns::updateOrCreate($matchStateData, $nameData);
                        $countrymsg[] = $nameData['state_name'];
                    }

                    $return['status'] = 1;
                    $return['message'] = 'State [' . implode(', ', $countrymsg) . '] updated successfully';
                    $return['data'] = $updateStateData;
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

    /*
    * delete record from database
    */
    public static function deleteStates($delete_state_id)
    {

        $return = array(
            'status' => 0,
            'message' => 'Something went wrong'
        );

        $stateData = State::where('id', $delete_state_id)->with('stateName')->withTrashed()->first()->toArray();
        $is_dependent = State::checkDependancy($stateData['iso_code'], $delete_state_id);
        foreach ($stateData['state_name'] as $key => $name) {
            $nameData = array(
                'state_name' => $name['state_name'],
                'language_code' => $name['language_code']
            );
            $statemsg[] = $name['state_name'];
        }
        if ($is_dependent) {
            State::where('id', $delete_state_id)->delete();
            $module_names = implode(', ', $is_dependent);
            $return['status'] = 1;
            $return['message'] = 'State Code [' . implode(', ', $statemsg) . '] exist in [' . $module_names . ']. Hence, it can soft deleted';
        } else {
            State::where('id', $delete_state_id)->forceDelete();

            $return['status'] = 1;
            $return['message'] = 'State [' . implode(', ', $statemsg) . '] deleted successfully';
        }
        return $return;
    }

    public static function checkDependancy($code, $delete_state_id)
    {
        /**
         * in future need to check dependancy to other reference table and need to set true if 
         * any dependancy set in reference tables
         **/
        $dep_modules = [];
        return $dep_modules;
    }

    /*
    * restore deleted record
    */
    public static function restoreStates($restore_state_id)
    {

        $return = array(
            'status' => 0,
            'message' => 'Something went wrong'
        );

        $stateData = State::withTrashed()->find($restore_state_id);
        if ($stateData) {
            $stateData->restore();
            $return['status'] = 1;
            $return['message'] = 'State [' . $stateData['iso_code'] . '] restored successfully';
        }
        return $return;
    }
}
