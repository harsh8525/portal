<?php

/**
 * @package     Users
 * @subpackage  Roll & Permission
 * @Author      Amar Technolabs Pvt. ltd(info@amarinfotech.com)
 * @Copyright(C) 2023 [Travel Portal].
 * @Version 1.0.0
 * module of the Service Type.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\RolePermission;
use App\Models\User;
use DB;

class Role extends Model
{
    use HasFactory;
    protected $guarded = [];

    /**
     * get list or single or all records to display
     */
    public static function getRoleDetail($option = array())
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
                $query = Role::query();
                $query->select(
                    'roles.*',
                    DB::raw('(CASE WHEN roles.status = "0" THEN "In-Active" '
                        . 'WHEN roles.status = "1" THEN "Active" '
                        . 'END) AS roles_status_text'),
                    DB::raw('(CASE WHEN roles.role_type = "manager" THEN "Manager" '
                        . 'WHEN roles.role_type = "supplier" THEN "Supplier" '
                        . 'WHEN roles.role_type = "b2b" THEN "B2B" '
                        . 'END) AS roles_type_text')
                );
                $query->where('id', $config['id']);
                $query->orderBy($config['order_by'], $config['sorting']);

                $result = $query->first();
            } catch (\Exception $e) {
                $return['message'] = $e->getMessage();
            }
        } else {
            try {
                $query = Role::query();
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
            $return['message'] = 'Role & Permission list successfully';
            $return['data'] = $result;
            return $return;
        } else {
            return $return;
        }
    }

    /**
     * update record in database
     */
    public static function updateRollPermission($requestData)
    {

        $return = array(
            'status' => 0,
            'message' => 'something went wrong',
            'data' => []
        );

        $roleData = array(
            'id' => $requestData['permission_id'],
            'name' => $requestData['name'],
            'code' => str_replace(' ', '_', strtoupper($requestData['name'])),
            'description' => $requestData['description'],
            'role_type' => $requestData['role_type'],
            'status' => $requestData['status'],
            'updated_at' => now(),
        );
        try {
            DB::beginTransaction();
            $matchRecord = ['id' => $roleData['id']];
            $roleAdd = Role::updateOrCreate($matchRecord, $roleData);
            RolePermission::where('role_code', $requestData['role_code'])->delete();
            if (isset($requestData['roll_permission'])) {
                foreach ($requestData['roll_permission'] as $key => $val) {
                    RolePermission::create($val);
                }
            }
            DB::commit();
            if ($roleAdd) {
                $return['status'] = 1;
                $return['message'] = 'Role [' . $roleAdd['name'] . '] Updated Succefully';
                $return['data'] = $roleAdd;
            }
        } catch (\Exception $e) {
            $return['message'] = 'Error during save record : ' . $e->getMessage();
        }
        return $return;
    }

    /**
     * insert record in database
     */
    public static function storeRollPermission($requestData)
    {
        $return = array(
            'status' => 0,
            'message' => 'something went wrong',
            'data' => []
        );

        $addRoleData = array(
            'name' => $requestData['name'],
            'code' => str_replace(' ', '_', strtoupper($requestData['name'])),
            'description' => $requestData['description'],
            'role_type' => $requestData['role_type'],
            'status' => $requestData['status'],
        );
        try {
            DB::beginTransaction();

            $roleAdd = Role::create($addRoleData);
            if (isset($requestData['add_roll_permission'])) {
                foreach ($requestData['add_roll_permission'] as $key => $val) {
                    RolePermission::create($val);
                }
            }
            DB::commit();
            if ($roleAdd) {
                $return['status'] = 1;
                $return['message'] = 'Role [' . $roleAdd['name'] . '] Saved Successfully';
                $return['data'] = $roleAdd;
            }
        } catch (\Exception $e) {
            $return['message'] = 'Error during save record : ' . $e->getMessage();
        }
        return $return;
    }

    /**
     * delete record from database
     */
    public static function deleteRollPermission($role_id)
    {

        $return = array(
            'status' => 0,
            'message' => 'Something went wrong'
        );

        $roleData = Role::where('id', $role_id)->first()->toArray();
        $is_dependent = Role::checkDependancy($roleData['code'], $role_id);
        if ($is_dependent) {
            //update status to deleted
            Role::where('id', $role_id)->update(['status' => 2]);
            $module_names = implode(', ', $is_dependent);
            $return['status'] = 1;
            $return['message'] = 'Role Name [' . $roleData['name'] . '] exist in [' . $module_names . ']. Hence, it can soft deleted';
        } else {

            Role::where('id', $role_id)->delete();
            RolePermission::where('role_code', $roleData['code'])->delete();
            $return['status'] = 1;
            $return['message'] = 'Role Name [' . $roleData['name'] . '] deleted successfully';
        }

        return $return;
    }
    public static function checkDependancy($code, $id)
    {
        /**
         * in future need to check dependancy to other reference table and need to set true if 
         * any dependancy set in reference tables
         **/
        $dep_modules = [];
        //check for home banner module
        $rolleCheckDep = User::where('role_code', $code)->count();

        if ($rolleCheckDep > 0) {
            array_push($dep_modules, 'Users');
        }
        return $dep_modules;
    }
}
