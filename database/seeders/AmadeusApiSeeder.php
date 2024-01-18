<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class AmadeusApiSeeder extends Seeder
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
            ['config_key'=>'amadeus|api|credential','value'=>'test'],
            
            ['config_key'=>'amadeus|api|test|APIEndPoint','value'=>'https://test.api.amadeus.com '],
            ['config_key'=>'amadeus|api|test|clientId','value'=>'zFKYlQPsA1sJjtId13ab1vSE5FyLraqR'],
            ['config_key'=>'amadeus|api|test|clientSecret','value'=>'wos5It0hZHUbBAdH'],
            ['config_key'=>'amadeus|api|test|grantType','value'=>'client_credentials'],
            
            ['config_key'=>'amadeus|api|live|APIEndPoint','value'=>'https://test.api.amadeus.com '],
            ['config_key'=>'amadeus|api|live|clientId','value'=>'TfLf5r3VXg6aV6A2DpnDqN9XXw5gS0n7'],
            ['config_key'=>'amadeus|api|live|clientSecret','value'=>'rX2luGatua5vm2GMbase'],
            ['config_key'=>'amadeus|api|live|grantType','value'=>'client_credentials'],
            
        ];
        
        Setting::upsert($settingData,'config_key');
    }
}
