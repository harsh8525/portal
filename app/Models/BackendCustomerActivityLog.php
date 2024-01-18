<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BackendCustomerActivityLog extends Model
{
    use HasFactory;
    protected $table = 'backend_customer_activity_logs';
    
    protected $guarded = [];
   
    /**
     * get list or single or all record to display
     */
    public static function getLogs($option = array())
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
            'mobile_verified' => '',
            'where' => array(),
            'orWhere' => array()
        );

        $config = array_merge($data, $option);
        $result = [];
        if ($config['id'] != '') {
            try {
                $query = BackendCustomerActivityLog::query();
                $query->select(
                    "customer_activity_logs.*",
                );
                $query->where('customer_activity_logs.id', $config['id']);
                $result = $query->first();
                // echo "<pre>";print_r($result);die;
            } catch (\Exception $e) {
                $return['message'] = $e->getMessage();
            }
        } else {
            try {
                $query = BackendCustomerActivityLog::query();
                $query->select(
                    "customer_activity_logs.*",
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
            $return['message'] = 'Log list successfully';
            $return['data'] = $result;
            return $return;
        } else {
            return $return;
        }
    }
    static public function getLogReport($option = array(), $pagination = true, $getTotalPrice = false)
    {
        $data = array(
            'id' => '',
            'order_by' => 'created_at',
            'sorting' => 'desc',
            'status' => '',
            'where' => array(),
            'orWhere' => array(),
            'per_page' => 10, // Set a default value for per_page, adjust as needed
        );

        $config = array_merge($data, $option);
        $query = BackendCustomerActivityLog::query();

        if (!empty($config['dates'])) {
            foreach ($config['dates'] as $dates) {
                $query->whereDate('created_at', '>=', $dates[0])->whereDate('created_at', '<=', $dates[1]);
            }
        }

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

        if ($pagination) {
            $result = $query->paginate($config['per_page']);
            $result->setPath('?per_page=' . $config['per_page']);
        } else {
            $result = $query->latest()->get();
        }


        return $result;
    }
}
