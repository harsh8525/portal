<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = [
            [
            	'agency_id'=>0,
                'name'=>'TravelPortal Super Admin',
                'email'=>'ai.developer16@gmail.com',
                'mobile'=>'9876543210',            
                'email_verified_at'=>date('Y-m-d h:i:s'),
                'password'=>Hash::make('P@ssw0rd'),
                'role_code'=>'SUPER_ADMIN',
                'status'=>'1',
                'app_name'=>'managerapp',
                'created_at'=>date('Y-m-d h:i:s'),
                'updated_at'=>date('Y-m-d h:i:s')
            ],
            [
            	'agency_id'=>1,
                'name'=>'Thomas Anderson',
                'email'=>'somya@amarinfotech.com',
                'mobile'=>'9999999999',            
                'email_verified_at'=>date('Y-m-d h:i:s'),
                'password'=>Hash::make('P@ssw0rd'),
                'role_code'=>'B2B_AGENCY_OWNER',
                'status'=>'1',
                'app_name'=>'b2bapp',
                'created_at'=>date('Y-m-d h:i:s'),
                'updated_at'=>date('Y-m-d h:i:s')
            ],
            [
            	'agency_id'=>2,
                'name'=>'Jack Smith',
                'email'=>'supplier@travelportal.com',
                'mobile'=>'5555555555',            
                'email_verified_at'=>date('Y-m-d h:i:s'),
                'password'=>Hash::make('P@ssw0rd'),
                'role_code'=>'SUPPLIER_AGENCY_OWNER',
                'status'=>'1',
                'app_name'=>'supplierapp',
                'created_at'=>date('Y-m-d h:i:s'),
                'updated_at'=>date('Y-m-d h:i:s')
            ]
        ];
        
        //User::upsert($user,'name');
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        User::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        foreach ($user AS $key => $u) {
            $test = User::create($u);
        } 
    }
}
