<?php

/**
 * @package     Settings
 * @subpackage  Role Permission
 * @Author      Amar Technolabs Pvt. ltd(info@amarinfotech.com)
 * @Copyright(C) 2023 [NAME OF THE ORGANISATION THAT ON BEHALF OF THE CODE WE ARE WORKING].
 * @Version 1.0.0
 * module of the Role Permission.
 */

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Role;
use App\Models\Module;
use App\Traits\ActiveLog;
use App\Models\RolePermission;
use DB;
use URL;

class RolePermissionController extends Controller
{
    /**
     * Display a listing of the role permission.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!hasPermission('ROLES_PERMISSION', 'read')) {
            return view('admin/401');
        }
        $header['title'] = @trans('rolePermission.title');
        $header['heading'] = @trans('rolePermission.moduleHeading');

        $queryStringConcat = '?';
        if (isset($_GET['per_page'])) {
            $queryStringConcat .= ($queryStringConcat == '') ? '?per_page=' . $_GET['per_page'] : '&per_page=' . $_GET['per_page'];
        }
        if (isset($_GET['page'])) {
            $queryStringConcat .= ($queryStringConcat == '') ? '?page=' . $_GET['page'] : '&page=' . $_GET['page'];
        }


        $filter = array(
            'per_page' => (request()->input('per_page') != NULL) ? request()->input('per_page') : Setting::where('config_key', 'general|setting|pagePerAdminRecords')->get('value')[0]['value'],
            'order_by' => (request()->input('order_by') != NULL) ? request()->input('order_by') : 'id',
            'sorting' => (request()->input('sorting') != NULL) ? request()->input('sorting') : 'desc',
            'status' => (request()->input('status') != NULL) ? request()->input('status') : '',
            'role_name' => (request()->input('role_name') != NULL) ? request()->input('role_name') : '',

        );
        if (request()->input('role_name') != NULL) {
            $filter['where'][] = ['roles.name', 'like', '%' . request()->input('role_name') . '%'];
        }

        if (request()->input('status') != NULL) {
            $filter['where'][] = ['roles.status', '=', request()->input('status')];
        }
        $rolePermissionListData = Role::getRoleDetail($filter);
        $rolePermissionCount = Role::count();
        $rolePermissionData = $rolePermissionListData['data'];

        $activityLog['request'] = $filter;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $rolePermissionListData;
        ActiveLog::createBackendActiveLog($activityLog);

        if ($rolePermissionListData['status'] == 1) {
            return view('admin/role-permission/index')->with(['header' => $header, 'rolePermissionData' => $rolePermissionData,'rolePermissionCount'=>$rolePermissionCount,'queryStringConcat' => $queryStringConcat, 'appliedFilter' => $filter, 'i' => (request()->input('page', 1) - 1) * $filter['per_page']]);
        } else {
            return view('admin/role-permission/index')->with(['error' => $rolePermissionListData['message'], 'header' => $header, 'rolePermissionData' => $rolePermissionData,'rolePermissionCount'=>$rolePermissionCount,'queryStringConcat' => $queryStringConcat, 'appliedFilter' => $filter, 'i' => (request()->input('page', 1) - 1) * $filter['per_page']]);
        }
    }

    /**
     * Show the form for creating a new role permission.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!hasPermission('ROLES_PERMISSION', 'create')) {
            return view('admin/401');
        }
        $getModuleList = Module::with(['modulePermissions'])->orderBy('sort_order','ASC')->get();
        
        $activityLog['request'] = [];
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = [];
        ActiveLog::createBackendActiveLog($activityLog);

        return view('admin/role-permission/add')->with(['getModuleList' => $getModuleList]);
    }

    /**
     * Store a newly created role permission in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!hasPermission('ROLES_PERMISSION', 'create')) {
            return view('admin/401');
        }
        $requestData = $request->only(['permission_id', 'role_code', 'name', 'description', 'role_type', 'status', 'module']);
        $rules = [
            'name' => 'required',
            'description' => 'required',
            'status' => 'required'
        ];
        $customMessages = [];
        $niceNames = array();

        $this->validate($request, $rules, $customMessages, $niceNames);

        $addRolePermissionData = array();
        if (isset($requestData['module'])) {
            foreach ($requestData['module'] as $moduleKey => $modulePermission) {

                $getRollCode = str_replace(' ', '_', strtoupper($requestData['name']));
                $tempPermission['role_code'] =  $getRollCode;
                $tempPermission['module_code'] = $moduleKey;
                if (isset($modulePermission['create']) && $modulePermission['create'] == 'on') {
                    $tempPermission['create'] = '1';
                } else {
                    $tempPermission['create'] = '0';
                }
                if (isset($modulePermission['read']) && $modulePermission['read'] == 'on') {
                    $tempPermission['read'] = '1';
                } else {
                    $tempPermission['read'] = '0';
                }
                if (isset($modulePermission['update']) && $modulePermission['update'] == 'on') {
                    $tempPermission['update'] = '1';
                } else {
                    $tempPermission['update'] = '0';
                }
                if (isset($modulePermission['delete']) && $modulePermission['delete'] == 'on') {
                    $tempPermission['delete'] = '1';
                } else {
                    $tempPermission['delete'] = '0';
                }
                if (isset($modulePermission['import']) && $modulePermission['import'] == 'on') {
                    $tempPermission['import'] = '1';
                } else {
                    $tempPermission['import'] = '0';
                }
                if (isset($modulePermission['export']) && $modulePermission['export'] == 'on') {
                    $tempPermission['export'] = '1';
                } else {
                    $tempPermission['export'] = '0';
                }

                array_push($addRolePermissionData, $tempPermission);
            }
        }
        $requestData['add_roll_permission'] = $addRolePermissionData;
        $response = Role::storeRollPermission($requestData);

        $activityLog['request'] = $requestData;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $response;
        ActiveLog::createBackendActiveLog($activityLog);

        if (!empty($response['data'])) {
            return redirect()->route('role-permission.index')->with('success', $response['message']);
        } else {
            return redirect()->route('role-permission.index')->with('error', $response['message']);
        }
    }

    /**
     * Display the specified role permission.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!hasPermission('ROLES_PERMISSION', 'read')) {
            return view('admin/401');
        }
        $header['heading'] = @trans('View Role & Permissions');
        $header['title'] = @trans('RolePermission- View');
        $filter = array(
            'id' => $id
        );
        $response = Role::getRoleDetail($filter);
        $roleDetail = $response['data'];
        if ($roleDetail['role_type'] == 'manager') {
            $getModuleList = Module::with(['modulePermissions'])->where('is_managerapp', 1)->orderBy('sort_order', 'ASC')->get();
        } elseif ($roleDetail['role_type'] == 'supplier') {
            $getModuleList = Module::with(['modulePermissions'])->where('is_supplierapp', 1)->orderBy('supplier_sort_order', 'ASC')->get();
        } elseif ($roleDetail['role_type'] == 'b2b') {
            $getModuleList = Module::with(['modulePermissions'])->where('is_b2bapp', 1)->orderBy('b2b_sort_order', 'ASC')->get();
        }
        $rolePermissionData = RolePermission::where('role_code', $roleDetail->code)->get()->toArray();

        $activityLog['request'] = $filter;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $response;
        ActiveLog::createBackendActiveLog($activityLog);

        return view('admin/role-permission/view')->with(['header' => $header, 'roleDetail' => $roleDetail, 'getModuleList' => $getModuleList, 'rolePermissionData' => $rolePermissionData]);
    }

    /**
     * Show the form for editing the specified role permission.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!hasPermission('ROLES_PERMISSION', 'update')) {
            return view('admin/401');
        }
        $header['title'] = @trans('rolePermission.editTitle');
        $header['heading'] = @trans('rolePermission.editHeading');
        $filter = array(
            'id' => $id
        );
        $getModuleList=[];
        $response = Role::getRoleDetail($filter);
        $getRoleDetail = $response['data'];
        
        if ($getRoleDetail['role_type'] == 'manager') {
            $getModuleList = Module::with(['modulePermissions'])->where('is_managerapp', 1)->orderBy('sort_order', 'ASC')->get();
        } elseif ($getRoleDetail['role_type'] == 'supplier') {
            $getModuleList = Module::with(['modulePermissions'])->where('is_supplierapp', 1)->orderBy('supplier_sort_order', 'ASC')->get();
        } elseif ($getRoleDetail['role_type'] == 'b2b') {
            $getModuleList = Module::with(['modulePermissions'])->where('is_b2bapp', 1)->orderBy('b2b_sort_order', 'ASC')->get();
        }
        $rolePermissionData = RolePermission::where('role_code', $getRoleDetail->code)->get()->toArray();

        $activityLog['request'] = $filter;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $response;
        ActiveLog::createBackendActiveLog($activityLog);

        return view('admin/role-permission/update')->with(['header' => $header, 'getRoleDetail' => $getRoleDetail, 'getModuleList' => $getModuleList, 'rolePermissionData' => $rolePermissionData]);
    }

    /**
     * Update the specified role permission in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!hasPermission('ROLES_PERMISSION', 'update')) {
            return view('admin/401');
        }

        $url = $request->redirects_to;

        $requestData = $request->only(['permission_id', 'role_code', 'name', 'description', 'role_type', 'status', 'module', 'module_code', 'id']);

        $rules = [
            'name' => 'required',
            'description' => 'required',
            'status' => 'required'
        ];

        $customMessages = [];

        $niceNames = array();

        $this->validate($request, $rules, $customMessages, $niceNames);

        $rolePermissionData = array();
        if (isset($requestData['module'])) {
            foreach ($requestData['module'] as $moduleKey => $modulePermission) {
                $getRollCode = str_replace(' ', '_', strtoupper($requestData['name']));
                $tempPermission['role_code'] =  $getRollCode;
                $tempPermission['module_code'] = $moduleKey;
                if (isset($modulePermission['create']) && $modulePermission['create'] == 'on') {
                    $tempPermission['create'] = '1';
                } else {
                    $tempPermission['create'] = '0';
                }
                if (isset($modulePermission['read']) && $modulePermission['read'] == 'on') {
                    $tempPermission['read'] = '1';
                } else {
                    $tempPermission['read'] = '0';
                }
                if (isset($modulePermission['update']) && $modulePermission['update'] == 'on') {
                    $tempPermission['update'] = '1';
                } else {
                    $tempPermission['update'] = '0';
                }
                if (isset($modulePermission['delete']) && $modulePermission['delete'] == 'on') {
                    $tempPermission['delete'] = '1';
                } else {
                    $tempPermission['delete'] = '0';
                }
                if (isset($modulePermission['import']) && $modulePermission['import'] == 'on') {
                    $tempPermission['import'] = '1';
                } else {
                    $tempPermission['import'] = '0';
                }
                if (isset($modulePermission['export']) && $modulePermission['export'] == 'on') {
                    $tempPermission['export'] = '1';
                } else {
                    $tempPermission['export'] = '0';
                }

                array_push($rolePermissionData, $tempPermission);
            }
        }
        $requestData['roll_permission'] = $rolePermissionData;
        $response = Role::updateRollPermission($requestData);

        $activityLog['request'] = $requestData;
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $response;
        ActiveLog::createBackendActiveLog($activityLog);

        if (!empty($response['data'])) {
            return redirect()->to($url)->with('success', $response['message']);
        } else {
            return redirect()->to($url)->with('error', $response['message']);
        }
    }

    /**
     * Check name exist from role database.
     *
     * @return \Illuminate\Http\Request
     */
    public function checkExist(Request $request)
    {
        $matchListData = [];
        if (request()->input('name') && request()->input('name') != "") {
            if (request()->input('permission_id')) {
                $matchListData = Role::where('name', ucwords(request()->input('name')))->where('id', '!=', request()->input('permission_id'))->get()->toArray();
            } else {
                $matchListData = Role::where('name', ucwords(request()->input('name')))->where('status', '!=', 2)->get()->toArray();
            }
        }
        if (!empty($matchListData)) {
            echo "false";
        } else {
            echo "true";
        }
    }

      /**
     * Fetch module from module database.
     *
     * @return \Illuminate\Http\Request
     */
    public function fetchModules(Request $request)
    {
        if (request()->input('idrole_type') != '') {
            if (request()->input('permission_id') != '' && request()->input('module_code_id') != "") {
                if (request()->input('idrole_type') == 'manager') {
                    $getModuleList['module_list'] = Module::with(['modulePermissions'])->where('is_managerapp', 1)->orderBy('sort_order', 'ASC')->get();
                } elseif (request()->input('idrole_type') == 'supplier') {
                    $getModuleList['module_list'] = Module::with(['modulePermissions'])->where('is_supplierapp', 1)->orderBy('supplier_sort_order', 'ASC')->get();
                } elseif (request()->input('idrole_type') == 'b2b') {
                    $getModuleList['module_list'] = Module::with(['modulePermissions'])->where('is_b2bapp', 1)->orderBy('b2b_sort_order', 'ASC')->get();
                }
                $getModuleList['module_code_id'] = request()->input('module_code_id');
                $getModuleList['role_code'] = Role::where('code', request()->input('module_code_id'))->value('code');

                return view('admin.role-permission.moduleAjaxUpdate', ['getModuleList' => $getModuleList]);
            } else {
                if (request()->input('idrole_type') == 'manager') {
                    $getModuleList = Module::with(['modulePermissions'])->where('is_managerapp', 1)->orderBy('sort_order', 'ASC')->get();
                } elseif (request()->input('idrole_type') == 'supplier') {
                    $getModuleList = Module::with(['modulePermissions'])->where('is_supplierapp', 1)->orderBy('supplier_sort_order', 'ASC')->get();
                } elseif (request()->input('idrole_type') == 'b2b') {
                    $getModuleList = Module::with(['modulePermissions'])->where('is_b2bapp', 1)->orderBy('b2b_sort_order', 'ASC')->get();
                }
                return view('admin.role-permission.moduleAjax', ['getModuleList' => $getModuleList]);
            }
        }
    }

    /**
     * Remove the specified role permission from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Request
     */
    public function deleteRollPermission(Request $request)
    {
        if (!hasPermission('ROLES_PERMISSION', 'delete')) {
            return view('admin/401');
        }
        $url = URL::previous();
        $roleIDs = explode(',', $request->input('role_id'));
        $message = "";
        foreach ($roleIDs as $role_id) {
            $response = Role::deleteRollPermission($role_id);
            $message .= $response['message'] . '</br>';
        }

        $activityLog['request'] = $request->all();
        $activityLog['request_url'] =  request()->url();
        $activityLog['response'] = $response;
        ActiveLog::createBackendActiveLog($activityLog);

        if ($response['status'] == 1) {
            return redirect()->to($url)->with('success', $message);
        } else {
            return redirect()->to($url)->with('error', $response['message']);
        }
    }
}
