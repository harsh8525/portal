<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


namespace App\Traits;

use App\Models\Setting;
use DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

trait DateTimeFunction {
    
    public function changeDate($data) {
        $date = str_replace('/', '-', $data);
        $date = date("Y-m-d", strtotime($date) );
        return $date;
    }
    
    public function dateFunction($data) {
        $dateFormat = count(Setting::where('config_key', 'general|site|dateFormat')->get('value')) > 0 ? Setting::where('config_key', 'general|site|dateFormat')->get('value')[0]['value'] : "YYYY/MM/DD";
        if($dateFormat == 'F j, Y'){
            $date = date("F j, Y", strtotime($data) );
        }   
         if($dateFormat == 'Y-m-d'){
            $date = date("Y-m-d", strtotime($data) );
         }
         if($dateFormat == 'd/m/y'){
            $date = date("d/m/Y", strtotime($data) );
         }
         if($dateFormat == 'm/d/y'){
            $date = date("m/d/Y", strtotime($data) );
         }
        
        return $date;
    }
    
}