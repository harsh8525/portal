<?php

namespace App\Repository;

use App\Repository\UserInterfaceRepo;
use App\Models\AppUsers;

class UserRepo implements UserInterfaceRepo{
        
    function getAll($data=[]): array {
        $returnData = AppUsers::all()->toArray();
        
        return $returnData;
    }
}
