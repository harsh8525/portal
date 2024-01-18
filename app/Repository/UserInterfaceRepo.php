<?php

namespace App\Repository;

interface UserInterfaceRepo{
    
    function getAll($data=[]): array;
}