<?php

namespace Database\Seeders;
use App\Models\Airline;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AirlineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $airlineData = array(
            array(
                'airline_code' => '0B',
                'is_domestic' => '0',
                'status' => 'active',
                'airline_names' => array(
                    array(
                        'airline_name' => 'Blue Air',
                        'language_code' => 'en',
                    ),
                    array(
                        'airline_name' => 'سماء زرقاء',
                        'language_code' => 'ar',
                    )
                )
            ),
            array(
                'airline_code' => '0J',
                'is_domestic' => '0',
                'status' => 'active',
                'airline_names' => array(
                    array(
                        'airline_name' => 'Premium Jet AG',
                        'language_code' => 'en',
                    ),
                    array(
                        'airline_name' => 'بريميوم جيت ايه جي',
                        'language_code' => 'ar',
                    )
                )
            ),
            array(
                'airline_code' => '0Y',
                'is_domestic' => '0',
                'status' => 'active',
                'airline_names' => array(
                    array(
                        'airline_name' => 'FlyYeti',
                        'language_code' => 'en',
                    ),
                    array(
                        'airline_name' => 'فلاي يتي',
                        'language_code' => 'ar',
                    )
                )
            ),
            array(
                'airline_code' => '2G',
                'is_domestic' => '0',
                'status' => 'active',
                'airline_names' => array(
                    array(
                        'airline_name' => 'Angara Airlines',
                        'language_code' => 'en',
                    ),
                    array(
                        'airline_name' => 'طيران أنجارا',
                        'language_code' => 'ar',
                    )
                )
            ),
            array(
                'airline_code' => '2N',
                'is_domestic' => '0',
                'status' => 'active',
                'airline_names' => array(
                    array(
                        'airline_name' => 'Nextjet AG',
                        'language_code' => 'en',
                    ),
                    array(
                        'airline_name' => 'نيكست جيت ايه جي',
                        'language_code' => 'ar',
                    )
                )
            ),
        );

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \App\Models\AirlineI18ns::truncate();
        Airline::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        foreach ($airlineData AS $key => $airline) {
            $test =  Airline::createAirline($airline);
            // echo "<pre>";print_r($test);die;
            
        }   
    }
}
