<?php

namespace Database\Seeders;
use App\Models\Setting;

use Illuminate\Database\Seeder;

class SignInMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settingData = [
            //Login Attempts Records
            ['config_key'=>'signInMethod|email|enable','value'=>'1'],
            ['config_key'=>'signInMethod|google|enable','value'=>'1'],
            ['config_key'=>'signInMethod|google|clientId','value'=>'520628315448-qkhehlpbvn631r49uebnmeuag60ja42p.apps.googleusercontent.com'],
            ['config_key'=>'signInMethod|google|clientSecret','value'=>'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'],
            ['config_key'=>'signInMethod|google|redirectUri','value'=>'{{SITE_URL}}/login/google/callback'],
            ['config_key'=>'signInMethod|google|developerKey','value'=>'AIzaSyD4ZCwOuJUtpxtITVQKx0FULcuuZb933RY'],
            ['config_key'=>'signInMethod|facebook|enable','value'=>'1'],
            ['config_key'=>'signInMethod|facebook|appId','value'=>'560719262905490'],
            ['config_key'=>'signInMethod|facebook|appSecret','value'=>'de2c15176a09147798d9637b501b9821'],
            ['config_key'=>'signInMethod|facebook|redirectUri','value'=>'{{SITE_URL}}/login/facebook/callback'],
            ['config_key'=>'signInMethod|facebook|redirectUriLogout','value'=>'{{SITE_URL}}/logout/facebook'],
            ['config_key'=>'signInMethod|instagram|enable','value'=>'1'],
            ['config_key'=>'signInMethod|instagram|appId','value'=>'231701339923811'],
            ['config_key'=>'signInMethod|instagram|appSecret','value'=>'c274380030447989b259d741b9c769a8'],
            ['config_key'=>'signInMethod|instagram|redirectUri','value'=>'{{SITE_URL}}/login/instagram/callback'],
            ['config_key'=>'signInMethod|twitter|enable','value'=>'1'],
            ['config_key'=>'signInMethod|twitter|clientId','value'=>'dhSQjY0JfxtPa8Rdx0HeaJ3AH'],
            ['config_key'=>'signInMethod|twitter|clientSecret','value'=>'5rUQejrKawneDqOfwIg5HND8mvqx2wV7dWlIifyrDjdPV5zSgw'],
            ['config_key'=>'signInMethod|twitter|redirectUri','value'=>'{{SITE_URL}}/login/twitter/callback'],
        ];
        
        Setting::upsert($settingData,'config_key');
    }
}
