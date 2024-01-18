<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class PasswordSecurity extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $settingData = [
            //Password Security Records
            ['config_key'=>'passwordSecurity|expiryDays', 'value'=>'180'],
            ['config_key'=>'passwordSecurity|expireNotifyDays', 'value'=>'2,4'],
            ['config_key'=>'passwordSecurity|changePasswordNotify', 'value'=>'1'],
            ['config_key'=>'passwordSecurity|minimumPasswordLength', 'value'=>'8'],
            ['config_key'=>'passwordSecurity|specialCharacter', 'value'=>'1'],
            ['config_key'=>'passwordSecurity|alphanumericCharacter', 'value'=>'1'],
            ['config_key'=>'passwordSecurity|uppercaseCharacter', 'value'=>'1'],
            ['config_key'=>'passwordSecurity|lowercaseCharacter', 'value'=>'1'],
            ['config_key'=>'passwordSecurity|numericCharacter', 'value'=>'1'],
        ];

        Setting::upsert($settingData, 'config_key');
    }
}
