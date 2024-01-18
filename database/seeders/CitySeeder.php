<?php

namespace Database\Seeders;
use App\Models\City;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cityData = array(
            array(
                'iso_code' => 'SYD',
                'country_code' => 'AU',
                'latitude' => '-17.60856',
                'longitude' => '143.18389',
                'status' => 'active',
                'city_names' => array(
                    array(
                        'city_name' => 'Sydney',
                        'language_code' => 'en',
                    ),
                    array(
                        'city_name' => 'سيدني',
                        'language_code' => 'ar',
                    )
                )
            ),
            array(
                'iso_code' => 'NYC',
                'country_code' => 'US',
                'latitude' => '5.26139',
                'longitude' => '-3.92629',
                'status' => 'active',
                'city_names' => array(
                    array(
                        'city_name' => 'New York',
                        'language_code' => 'en',
                    ),
                    array(
                        'city_name' => 'نيويورك',
                        'language_code' => 'ar',
                    )
                )
            ),
            array(
                'iso_code' => 'MOW',
                'country_code' => 'RU',
                'latitude' => '45.0021',
                'longitude' => '37.34727',
                'status' => 'active',
                'city_names' => array(
                    array(
                        'city_name' => 'Moscow',
                        'language_code' => 'en',
                    ),
                    array(
                        'city_name' => 'موسكو',
                        'language_code' => 'ar',
                    )
                )
            ),
            array(
                'iso_code' => 'IFN',
                'country_code' => 'IR',
                'latitude' => '30.37111',
                'longitude' => '48.22833',
                'status' => 'active',
                'city_names' => array(
                    array(
                        'city_name' => 'Isfahan',
                        'language_code' => 'en',
                    ),
                    array(
                        'city_name' => 'أصفهان',
                        'language_code' => 'ar',
                    )
                )
            ),
            array(
                'iso_code' => 'MED',
                'country_code' => 'SA',
                'latitude' => '30.37111',
                'longitude' => '48.22833',
                'status' => 'active',
                'city_names' => array(
                    array(
                        'city_name' => 'Madinah',
                        'language_code' => 'en',
                    ),
                    array(
                        'city_name' => 'المدينة المنورة',
                        'language_code' => 'ar',
                    )
                )
            ),
        );
        
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \App\Models\CityI18n::truncate();
        City::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        foreach ($cityData AS $key => $city) {
            City::createCity($city);
        }
    }
}
