<?php

namespace Database\Seeders;

use App\Models\State;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $stateData = array(
            array(
                'iso_code' => 'VIC',
                'country_code' => 'AU',
                'latitude' => '-17.60856',
                'longitude' => '143.18389',
                'status' => 'active',
                'state_names' => array(
                    array(
                        'state_name' => 'Victoria',
                        'language_code' => 'en',
                    ),
                    array(
                        'state_name' => 'فيكتوريا',
                        'language_code' => 'ar',
                    )
                )
            ),
            array(
                'iso_code' => 'ALT',
                'country_code' => 'RU',
                'latitude' => '5.26139',
                'longitude' => '-3.92629',
                'status' => 'active',
                'state_names' => array(
                    array(
                        'state_name' => 'Altai',
                        'language_code' => 'en',
                    ),
                    array(
                        'state_name' => 'ألتاي',
                        'language_code' => 'ar',
                    )
                )
            ),
            array(
                'iso_code' => 'VT',
                'country_code' => 'US',
                'latitude' => '45.0021',
                'longitude' => '37.34727',
                'status' => 'active',
                'state_names' => array(
                    array(
                        'state_name' => 'Vermont',
                        'language_code' => 'en',
                    ),
                    array(
                        'state_name' => 'فيرمونت',
                        'language_code' => 'ar',
                    )
                )
            ),
            array(
                'iso_code' => 'ZA-EC',
                'country_code' => 'SA',
                'latitude' => '30.37112',
                'longitude' => '48.22834',
                'status' => 'active',
                'state_names' => array(
                    array(
                        'state_name' => 'Eastern Cape',
                        'language_code' => 'en',
                    ),
                    array(
                        'state_name' => 'الرأس الشرقي',
                        'language_code' => 'ar',
                    )
                )
            ),
            array(
                'iso_code' => 'ZA-FS',
                'country_code' => 'SA',
                'latitude' => '30.37111',
                'longitude' => '48.22833',
                'status' => 'active',
                'state_names' => array(
                    array(
                        'state_name' => 'Gauteng',
                        'language_code' => 'en',
                    ),
                    array(
                        'state_name' => 'غوتنغ',
                        'language_code' => 'ar',
                    )
                )
            ),
        );

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \App\Models\StateI18ns::truncate();
        State::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        foreach ($stateData as $key => $state) {
            State::createState($state);
        }
    }
}
