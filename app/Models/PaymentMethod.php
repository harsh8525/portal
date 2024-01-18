<?php

/**
 * @package     Operational Data
 * @subpackage  Payment Method
 * @Author      Amar Technolabs Pvt. ltd(info@amarinfotech.com)
 * @Copyright(C) 2023 [Travel Portal].
 * @Version 1.0.0
 * module of the Payment Method.
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

class PaymentMethod extends Model
{
    use HasFactory, LogsActivity;
    protected $table = 'core_payment_types';

    protected static $logAttributes = ['name', 'description', 'is_active'];

    protected static $logName = 'core_payment_types';
    protected $guarded = [];

    /**
     * get list or single or all records to display
     */
    public static function getPeymentMethod($option = array())
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
                $query = PaymentMethod::query();

                $query->select(
                    'core_payment_types.*',
                    DB::raw('(CASE WHEN core_payment_types.is_active = "0" THEN "In-Active" '
                        . 'WHEN core_payment_types.is_active = "1" THEN "Active" '
                        . 'END) AS payment_type_status_text')
                );
                $query->where('id', $config['id']);

                $result = $query->first();
            } catch (\Exception $e) {
                $return['message'] = $e->getMessage();
            }
        } else {
            try {
                $query = PaymentMethod::query();
                $query->select(
                    'core_payment_types.*',
                    DB::raw('(CASE WHEN core_payment_types.is_active = "0" THEN "In-Active" '
                        . 'WHEN core_payment_types.is_active = "1" THEN "Active" '
                        . 'END) AS payment_type_status_text')
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
            $return['message'] = 'Payment Method list successfully';
            $return['data'] = $result;
            return $return;
        } else {
            return $return;
        }
    }

    /**
     * insert data in database
     */
    public static function createPaymentMethod($requestData)
    {

        $return = array(
            'status' => 0,
            'message' => 'something went wrong',
            'data' => []
        );

        $paymentArrayData = array(
            'name' => $requestData['name'],
            'description' => $requestData['description'],
            'is_active' => $requestData['status'],
        );
        try {
            DB::beginTransaction();
            $paymentMethodData = PaymentMethod::create($paymentArrayData);

            DB::commit();
            if ($paymentMethodData) {
                $return['status'] = 1;
                $return['message'] = 'Payment Methods [' . $paymentMethodData['name'] . '] save successfully';
                $return['data'] = $paymentMethodData;
            }
        } catch (\Exception $e) {
            $return['message'] = 'Error during save home banner record : ' . $e->getMessage();
        }

        return $return;
    }

    /**
     * update record in database
     */
    public static function updatePaymentMethod($requestData)
    {

        $return = array(
            'status' => 0,
            'message' => 'something went wrong',
            'data' => []
        );
        try {

            @$paymentTypeDetails = array(
                'id' => $requestData['payment_type_id'],
                'name' => $requestData['name'],
                'description' => $requestData['description'],
                'is_active' => $requestData['status'],
            );

            try {
                DB::beginTransaction();

                $matchPaymentType = ['id' => $paymentTypeDetails['id']];
                $paymentTypeData = PaymentMethod::updateOrCreate($matchPaymentType, $paymentTypeDetails);

                DB::commit();
                if ($paymentTypeData) {
                    $return['status'] = 1;
                    $return['message'] = 'Payment Methods [' . $paymentTypeData['name'] . '] Updated Successfully';
                    $return['data'] = $paymentTypeData;
                }
            } catch (\Exception $e) {
                $return['message'] = 'Error during save user record : ' . $e->getMessage();
            }
        } catch (\Exception $e) {
            $return['message'] = 'Something went wrong : ' . $e->getMessage();
        }
        return $return;
    }

    /**
     * delete record from database
     */
    public static function deletePayment($payment_type_id)
    {

        $is_dependent = PaymentMethod::checkDependancy($payment_type_id);
        $return = array(
            'status' => 0,
            'message' => 'Something went wrong'
        );

        $paymentTypeData = PaymentMethod::where('id', $payment_type_id)->first()->toArray();
        if (!empty($is_dependent)) {

            //update status to deleted
            PaymentMethod::where('id', $payment_type_id)->update(['is_active' => 2]);
            $module_names = implode(', ', $is_dependent);
            $return['status'] = 1;

            $return['message'] = 'Payment Methods [' . $paymentTypeData['name'] . '] exist in [' . $module_names . ']. Hence, it can soft deleted';
        } else {

            PaymentMethod::where('id', $payment_type_id)->delete();
            $return['status'] = 1;
            $return['message'] = 'Payment Methods [' . $paymentTypeData['name'] . '] Deleted successfully';
        }
        return $return;
    }

    public static function checkDependancy($payment_type_id)
    {
        /**
         * in future need to check dependancy to other reference table and need to set true if 
         * any dependancy set in reference tables
         **/
        $dep_modules = [];
        $agency_payment_type_record = AgencyPaymentType::where('core_payment_type_id', $payment_type_id)->count();

        if ($agency_payment_type_record > 0) {
            array_push($dep_modules, 'AgencyPaymentType');
        }
        return $dep_modules;
    }
}
