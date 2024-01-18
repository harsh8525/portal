<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\RolePermission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Insert/Update Role Data
        $roleData = [
            ['name'=>'B2B Agency Owner','code'=>'B2B_AGENCY_OWNER','description'=>'This role is for data entry operator that manage master data like categories, products etc...'],
            ['name'=>'Supplier Agency Owner','code'=>'SUPPLIER_AGENCY_OWNER','description'=>'This role is for sales team to manage order'],
        ];
        Role::upsert($roleData,'code');
        
        //******************* DATA ENTRY PERMISSION SEEDER ********************//
        // Insert/Update Data Entry Category Module RolePermission Data
        $dataEntryCategoryPermissionData = [
            'role_code'=>'B2B_AGENCY_OWNER',
            'module_code'=>'USERS',
            'create'=>'1',
            'read'=>'1',
            'update'=>'1',
            'delete'=>'1',
            'import'=>'1',
            'export'=>'1'            
        ];        
        RolePermission::updateOrCreate(['role_code'=>'B2B_AGENCY_OWNER','module_code'=>'USERS'],$dataEntryCategoryPermissionData);
        // Insert/Update Data Entry Category Module RolePermission Data
        $dataEntryProductPermissionData = [
            'role_code'=>'B2B_AGENCY_OWNER',
            'module_code'=>'ROLES_PERMISSIONS',
            'create'=>'1',
            'read'=>'1',
            'update'=>'1',
            'delete'=>'1',
            'import'=>'1',
            'export'=>'1'            
        ];        
        RolePermission::updateOrCreate(['role_code'=>'B2B_AGENCY_OWNER','module_code'=>'ROLES_PERMISSIONS'],$dataEntryProductPermissionData);
        
        
        
        //******************* SALES TEAM PERMISSION SEEDER ********************//
        // Insert/Update Sales Team Order Module RolePermission Data
        $salesTeamOrderPermissionData = [
            'role_code'=>'SUPPLIER_AGENCY_OWNER',
            'module_code'=>'USERS',
            'create'=>'1',
            'read'=>'1',
            'update'=>'1',
            'delete'=>'1',
            'import'=>'0',
            'export'=>'0'            
        ];
        RolePermission::updateOrCreate(['role_code'=>'SUPPLIER_AGENCY_OWNER','module_code'=>'USERS'],$salesTeamOrderPermissionData);
        // Insert/Update Data Entry Category Module RolePermission Data
        $salesTeamOrderChallanPermissionData = [
            'role_code'=>'SUPPLIER_AGENCY_OWNER',
            'module_code'=>'ROLES_PERMISSIONS',
            'create'=>'1',
            'read'=>'1',
            'update'=>'1',
            'delete'=>'1',
            'import'=>'0',
            'export'=>'0'            
        ];        
        RolePermission::updateOrCreate(['role_code'=>'SUPPLIER_AGENCY_OWNER','module_code'=>'ROLES_PERMISSIONS'],$salesTeamOrderChallanPermissionData);
    }
}
