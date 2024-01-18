<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Country;

class CountrySeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $countryData = array(
            array(
                'iso_code' => 'AU',
                'isd_code' => '+61',
                'max_mobile_number_length' => '10',
                'status' => 'active',
                'country_names' => array(
                    array(
                        'country_name' => 'Australia',
                        'language_code' => 'en',
                    ),
                    array(
                        'country_name' => 'أستراليا',
                        'language_code' => 'ar',
                    )
                )
            ),
            array(
                'iso_code' => 'US',
                'isd_code' => '+1',
                'max_mobile_number_length' => '10',
                'status' => 'active',
                'country_names' => array(
                    array(
                        'country_name' => 'USA',
                        'language_code' => 'en',
                    ),
                    array(
                        'country_name' => 'الولايات المتحدة الأمريكية',
                        'language_code' => 'ar',
                    )
                )
            ),
            array(
                'iso_code' => 'RU',
                'isd_code' => '70',
                'max_mobile_number_length' => '10',
                'status' => 'active',
                'country_names' => array(
                    array(
                        'country_name' => 'Russia',
                        'language_code' => 'en',
                    ),
                    array(
                        'country_name' => 'روسيا',
                        'language_code' => 'ar',
                    )
                )
            ),
            array(
                'iso_code' => 'IR',
                'isd_code' => '+966',
                'max_mobile_number_length' => '10',
                'status' => 'active',
                'country_names' => array(
                    array(
                        'country_name' => 'Iran',
                        'language_code' => 'en',
                    ),
                    array(
                        'country_name' => 'إيران',
                        'language_code' => 'ar',
                    )
                )
            ),
            array(
                'iso_code' => 'SA',
                'isd_code' => '+966',
                'max_mobile_number_length' => '10',
                'status' => 'active',
                'country_names' => array(
                    array(
                        'country_name' => 'Saudi Arabia',
                        'language_code' => 'en',
                    ),
                    array(
                        'country_name' => 'المملكة العربية السعودية',
                        'language_code' => 'ar',
                    )
                )
            ),
        );

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \App\Models\CountryI18ns::truncate();
        Country::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        foreach ($countryData AS $key => $country) {
            Country::createCountry($country);
        }
    }
}
