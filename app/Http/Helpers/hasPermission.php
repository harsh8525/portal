<?php

use App\Models\User;
use App\Models\RolePermission;
use Illuminate\Support\Facades\Validator;
function activeGuard(){

    foreach(array_keys(config('auth.guards')) as $guard){

        if(auth()->guard($guard)->check()) return $guard;

    }
    return null;
}

function hasPermission($moduleCode,$action){
    $activeGuard = '';
    if(Auth::guard('web')->check())
        $activeGuard = '';
    elseif(Auth::guard('b2b')->check())
        $activeGuard = 'b2b';
    
    if($activeGuard == ""){
        $userID = Auth::id();
    }else{
        $userID = Auth::guard($activeGuard)->id();
    }
    //get user role
    $userData = User::find($userID);
    if($userData->role_code == 'SUPER_ADMIN'){
        return true;
    }else{
        $rolePermissionData = RolePermission::where('role_code',$userData->role_code)
        ->where('module_code',$moduleCode)
        ->where($action,1)->count();
        if($rolePermissionData > 0){
            return true;
        }
    }
    return false;
}
?>