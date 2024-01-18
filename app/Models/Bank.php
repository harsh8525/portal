<?php

/**
 * @package     Operational Data
 * @subpackage  Banks.
 * @Author      Amar Technolabs Pvt. ltd(info@amarinfotech.com)
 * @Copyright(C) 2023 [Travel Portal].
 * @Version 1.0.0
 * module of the Banks.
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

class Bank extends Model
{
    use HasFactory;
    protected $table = 'core_bank_details';
    protected $guarded = [];
    protected static $logAttributes = [
        'bank_code', 'beneficiary_name', 'account_number',
        'bank_name', 'bank_address', 'swift_code', 'iban_number', 'sort_code', 'status'
    ];

    protected static $logName = 'core_bank_details';

    /**
     * get single or list or all record 
     */
    public static function getBankType($option = array())
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
                $query = Bank::query();

                $query->select(
                    'core_bank_details.*',
                    DB::raw('(CASE WHEN core_bank_details.status = "0" THEN "In-Active" '
                        . 'WHEN core_bank_details.status = "1" THEN "Active" '
                        . 'END) AS bank_type_status_text')
                );
                $query->where('id', $config['id']);

                $result = $query->first();
            } catch (\Exception $e) {
                $return['message'] = $e->getMessage();
            }
        } else {
            try {
                $query = Bank::query();
                $query->select(
                    'core_bank_details.*',
                    DB::raw('(CASE WHEN core_bank_details.status = "0" THEN "In-Active" '
                        . 'WHEN core_bank_details.status = "1" THEN "Active" '
                        . 'END) AS bank_type_status_text')
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
            $return['message'] = 'Banks list successfully';
            $return['data'] = $result;
            return $return;
        } else {
            return $return;
        }
    }

    /**
     * insert new record in database
     */
    public static function createBankType($requestData)
    {

        $return = array(
            'status' => 0,
            'message' => 'something went wrong',
            'data' => []
        );

        $bankTypeArrayData = array(
            'bank_code' => $requestData['bank_code'],
            'beneficiary_name' => $requestData['beneficiary_name'],
            'account_number' => $requestData['account_number'],
            'bank_name' => $requestData['bank_name'],
            'bank_address' => $requestData['bank_address'],
            'swift_code' => $requestData['swift_code'],
            'iban_number' => $requestData['iban_number'],
            'sort_code' => $requestData['sort_code'],
            'status' => $requestData['status'],
        );
        try {
            DB::beginTransaction();
            $bankTypeData = Bank::create($bankTypeArrayData);

            DB::commit();
            if ($bankTypeData) {
                $return['status'] = 1;
                $return['message'] = 'Banks [' . $bankTypeData['bank_name'] . '] save successfully';
                $return['data'] = $bankTypeData;
            }
        } catch (\Exception $e) {
            $return['message'] = 'Error during save home banner record : ' . $e->getMessage();
        }


        return $return;
    }

    /**
     *update record in database
     */
    public static function updateBankType($requestData)
    {

        $return = array(
            'status' => 0,
            'message' => 'something went wrong',
            'data' => []
        );
        try {

            @$bankTypeDetails = array(
                'id' => $requestData['bank_type_id'],
                'bank_code' => $requestData['bank_code'],
                'beneficiary_name' => $requestData['beneficiary_name'],
                'account_number' => $requestData['account_number'],
                'bank_name' => $requestData['bank_name'],
                'bank_address' => $requestData['bank_address'],
                'swift_code' => $requestData['swift_code'],
                'iban_number' => $requestData['iban_number'],
                'sort_code' => $requestData['sort_code'],
                'status' => $requestData['status'],

            );

            try {
                DB::beginTransaction();

                $matchBankType = ['id' => $bankTypeDetails['id']];
                $bankTypeData = Bank::updateOrCreate($matchBankType, $bankTypeDetails);

                DB::commit();
                if ($bankTypeData) {
                    $return['status'] = 1;
                    $return['message'] = 'Banks [' . $bankTypeData['bank_name'] . '] Updated Successfully';
                    $return['data'] = $bankTypeData;
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
     *delete record from database
     */
    public static function deletebank($bank_type_id)
    {
        $is_dependent = Bank::checkDependancy($bank_type_id);
        $return = array(
            'status' => 0,
            'message' => 'Something went wrong'
        );
        $bankTypeData = Bank::where('id', $bank_type_id)->first()->toArray();
        if (($is_dependent)) {
            //update status to deleted
            Bank::where('id', $bank_type_id)->update(['status' => 2]);
            $return['status'] = 1;
            $return['message'] = 'banks [' . $bankTypeData['bank_name'] . '] soft deleted successfully';
        }

        Bank::where('id', $bank_type_id)->delete();
        $return['status'] = 1;
        $return['message'] = 'banks [' . $bankTypeData['bank_name'] . '] Deleted successfully';

        return $return;
    }

    public static function checkDependancy($bank_type_id)
    {
        /**
         * in future need to check dependancy to other reference table and need to set true if 
         * any dependancy set in reference tables
         **/
        $dep_modules = [];

        return $dep_modules;
    }
}
