<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuids;

class DefaultMarkup extends Model
{
    use HasFactory, Uuids, SoftDeletes;

    protected $table = 'default_markups';
    protected $guarded = [];
    protected $dates = ['deleted_at'];

    public function getServiceType()
    {
        return $this->belongsTo('App\Models\ServiceType', 'service_type_id');
    }

    public function getSupplier()
    {
        return $this->hasMany('App\Models\DefaultMarkupsSupplier', 'default_markups_id');
    }

    public static function getDefaultMarkupsData($option = array())
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
            'status' => '',
            'where' => array(),
            'orWhere' => array()
        );

        $config = array_merge($data, $option);
        $result = [];
        if ($config['id'] != '') {
            try {
                $query = DefaultMarkup::query();
                $query->with('getServiceType', 'getSupplier.geDefaultMarkupsSupplier');
                $query->select(
                    'default_markups.*'
                );
                $query->where('id', $config['id']);
                $query->where('service_type_id', $config['service_type_id']);
                $result = $query->first();
            } catch (\Exception $e) {
                $return['message'] = $e->getMessage();
            }
        } else {
            try {
                $checkServiceType = ServiceType::where('name', $config['service_type'])->first();
                $service_type_id = $checkServiceType->id ?? '';
                
                $query = DefaultMarkup::query();
                $query->with('getServiceType', 'getSupplier.geDefaultMarkupsSupplier');
                
                $query->orderBy($config['order_by'], $config['sorting']);
                
                if (!empty($config['where'])) {
                    foreach ($config['where'] as $where) {
                        $query->where('default_markups.' . $where[0], $where[1], $where[2]);
                    }
                }
                
                if (!empty($config['orWhere'])) {
                    foreach ($config['orWhere'] as $orWhere) {
                        $query->orWhere($orWhere[0], $orWhere[1], $orWhere[2]);
                    }
                }
                $query->where('service_type_id', $service_type_id);
                $result = $query->paginate($config['per_page']);
                $result->setPath('?per_page=' . $config['per_page']);
            } catch (\Exception $e) {
                $return['message'] = $e->getMessage();
            }
        }

        if (!empty($result)) {
            $return['status'] = 1;
            $return['message'] = 'Markups list successfully';
            $return['data'] = $result;
            return $return;
        } else {
            return $return;
        }
    }

    public static function createDefaultFlightMarkups($requestData)
    {

        $return = array(
            'status' => 0,
            'message' => 'something went wrong',
            'data' => []
        );

        $markupsArrayData = array(
            'service_type_id' => $requestData['service_type_id'],
            'b2c_markup_type' => $requestData['b2c_markup_type'],
            'b2c_markup' => $requestData['b2c_markup'],
            'b2b_markup_type' => $requestData['b2b_markup_type'],
            'b2b_markup' => $requestData['b2b_markup'],
        );
        try {
            DB::beginTransaction();
            $markupsArrayData = DefaultMarkup::create($markupsArrayData);

            $defaultMarkupsId = $markupsArrayData->id; // Get the last inserted ID

            //insert multiple channel flow

            //insert multiple supplier flow
            foreach ($requestData['supplier'] as $supplier) {
                $MarkupSupplierData = array('default_markups_id' => $defaultMarkupsId, 'supplier_id' => $supplier);

                $data = DefaultMarkupsSupplier::create($MarkupSupplierData);
            }

            DB::commit();

            if ($markupsArrayData) {
                $return['status'] = 1;
                $return['message'] = 'Default Markup saved successfully';
                $return['data'] = $markupsArrayData;
            }
        } catch (\Exception $e) {
            $return['message'] = 'Error during save Markups : ' . $e->getMessage();
        }
        return $return;
    }

    public static function updateDefaultMarkups($requestData)
    {

        $return = array(
            'status' => 0,
            'message' => 'something went wrong',
            'data' => []
        );
        try {
            $markupsArrayData = array(
                'id' => $requestData['default_markups_id'],
                'service_type_id' => $requestData['service_type_id'],
                'b2c_markup_type' => $requestData['b2c_markup_type'],
                'b2c_markup' => $requestData['b2c_markup'],
                'b2b_markup_type' => $requestData['b2b_markup_type'],
                'b2b_markup' => $requestData['b2b_markup'],
            );

            try {

                DB::beginTransaction();
                $matchMarkups = ['id' => $markupsArrayData['id']];
                $markups = DefaultMarkup::updateOrCreate($matchMarkups, $markupsArrayData);


                //insert multiple supplier flow
                DefaultMarkupsSupplier::where('default_markups_id', $markupsArrayData['id'])->delete();
                foreach ($requestData['supplier'] as $supplier) {
                    $MarkupSupplierData = array('default_markups_id' => $markupsArrayData['id'], 'supplier_id' => $supplier);
                    DefaultMarkupsSupplier::create($MarkupSupplierData);
                }

                DB::commit();
                if ($markups) {
                    $return['status'] = 1;
                    $return['message'] = 'Default Markups Updated Successfully';
                    $return['data'] = $markups;
                }
            } catch (\Exception $e) {
                $return['message'] = 'Error during update Markups : ' . $e->getMessage();
            }
        } catch (\Exception $e) {
            $return['message'] = 'Something went wrong : ' . $e->getMessage();
        }
        return $return;
    }

      /**
     * delete record fron database
     */
    public static function deleteDefaultMarkups($markups_id)
    {
        $is_dependent = DefaultMarkup::checkDependancy($markups_id);
        $return = array(
            'status' => 0,
            'message' => 'Something went wrong'
        );
        $markups = DefaultMarkup::where('id', $markups_id)->first()->toArray();
        if ($is_dependent) {
            //update status to deleted
            DefaultMarkup::where('id', $markups_id)->delete();
            $module_names = implode(', ', $is_dependent);
            $return['status'] = 1;
            $return['message'] = 'Default Markup exist. Hence, it can soft deleted';
        } else {
            DefaultMarkup::where('id', $markups_id)->forceDelete();

            $return['status'] = 1;
            $return['message'] = 'Default Markup deleted successfully';
        }

        return $return;
    }
    public static function checkDependancy($markups_id)
    {
        /**
         * in future need to check dependancy to other reference table and need to set true if 
         * any dependancy set in reference tables
         **/
        $dep_modules = [];

        return $dep_modules;
    }
}
