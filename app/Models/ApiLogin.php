<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;
use App\Traits\Uuids;
use App\Models\ApiLogin;

class ApiLogin extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable, Uuids;
    protected $table = "api_logins";
    protected $guarded = [];
    protected $fillable = ['type','name','password'];

    /**
     * get list or single or all records to display
     */
    public static function getApiUserDetail($option = array())
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
                $query = ApiLogin::query();
                $query->select(
                    'api_logins.*'
                );
                $query->where('id', $config['id']);
                $query->orderBy($config['order_by'], $config['sorting']);

                $result = $query->first();
            } catch (\Exception $e) {
                $return['message'] = $e->getMessage();
            }
        } else {
            try {
                $query = ApiLogin::query();
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
            $return['message'] = 'Api Users list successfully';
            $return['data'] = $result;
            return $return;
        } else {
            return $return;
        }
    }


    /**
     * insert new record in database
     */
    public static function createApiUserLogin($requestData)
    {
        $return = array(
            'status' => 0,
            'message' => 'something went wrong',
            'data' => []
        );

        try {
            $apiuserloginData = array(
                'id'  => $requestData['id'],
                'type'  => $requestData['type'],
                'name'  => $requestData['name'],
                'password'  => $requestData['password']
            );
            // save to table
            try {
                DB::beginTransaction();
                $apiLogin = ApiLogin::create($apiuserloginData);
                if ($apiLogin) {
                    $return['status'] = 1;
                    $return['message'] = 'Api user login records saved successfully';
                    $return['data'] = $apiLogin;
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
}
