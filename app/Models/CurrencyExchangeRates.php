<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Carbon;
use App\Models\Currency;
use App\Models\Setting;
use DB;

class CurrencyExchangeRates extends Model
{
    use HasFactory;
    protected $table = 'currency_exchange_rates';
    protected $guarded = [];

    /**
     * get single or list or all record to display
     */
    public static function getCurrencyexchange($option = array())
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
        // echo "<pre>";print_r($config);die;
        $result = [];
        if ($config['id'] != '') {
            try {
                $query = CurrencyExchangeRates::query();
                $query->select('currency_exchange_rates.*');
                $query->where('id', $config['id']);
                $result = $query->first();
            } catch (\Exception $e) {
                $return['message'] = $e->getMessage();
            }
        } else {

            try {
                $query = CurrencyExchangeRates::query();
                $query->select('currency_exchange_rates.*');
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
            $return['message'] = 'Currency list successfully';
            $return['data'] = $result;
            return $return;
        } else {
            return $return;
        }
    }
}
