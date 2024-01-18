<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class LoginAttampts extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $settingData = [
            //Login Attempts Records
            ['config_key'=>'loginAttempts|enable','value'=>'0'],
            ['config_key'=>'loginAttempts|perHost','value'=>'1'],
            ['config_key'=>'loginAttempts|perUser','value'=>'1'],
            ['config_key'=>'loginAttempts|simultaneousUser','value'=>'3'],
            ['config_key'=>'loginAttempts|loginTimePeriod','value'=>'3'],
            ['config_key'=>'loginAttempts|loginTimePeriodType','value'=>'minute'],
            ['config_key'=>'loginAttempts|lockOutTimePeriod','value'=>'3'],
            ['config_key'=>'loginAttempts|lockOutTimePeriodType','value'=>'minute'],
            ['config_key'=>'loginAttempts|emailNotification','value'=>'0'],
        ];
        
        Setting::upsert($settingData,'config_key');
    }
}
