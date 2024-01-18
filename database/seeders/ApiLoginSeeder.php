<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ApiLogin;
use Illuminate\Support\Facades\Hash;

class ApiLoginSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //'b2c_web','b2c_mobile','b2b_web','b2b_mobile'
        $apiUserLogins = [
            [
                'id' => 1,
            	'type'=>'b2c_web',
                'name'=>'b2cweb',
                'password'=>Hash::make('P@ssw0rd')
            ],
            [
                'id' => 2,
            	'type'=>'b2c_mobile',
                'name'=>'b2cmobile',
                'password'=>Hash::make('P@ssw0rd')
            ],
            [
                'id' => 3,
            	'type'=>'b2b_web',
                'name'=>'b2bweb',
                'password'=>Hash::make('P@ssw0rd')
            ],
            [
                'id' => 4,
            	'type'=>'b2b_mobile',
                'name'=>'b2bmobile',
                'password'=> Hash::make('P@ssw0rd')
            ],
        ];
        ApiLogin::truncate();
        foreach ($apiUserLogins as $apilogin) {
            ApiLogin::createApiUserLogin($apilogin);
        }  
    }
}
