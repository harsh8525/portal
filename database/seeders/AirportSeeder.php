<?php

namespace Database\Seeders;
use App\Models\Airport;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AirportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $airportData = array(
            array(
                'iata_code' => 'JFK',
                'city_code' => 'NYC',
                'country_code' => 'US',
                'latitude' => '40.63983',
                'longitude' => '-73.77874',
                'status' => 'active',
                'airport_names' => array(
                    array(
                        'airport_name' => 'New York-John F Kennedy Intl-USA(JFK)',
                        'language_code' => 'en',
                    ),
                    array(
                        'airport_name' => 'نيويورك-مطار جون أف كندي الدولي-الولايات المتحدة الأمريكية(JFK)',
                        'language_code' => 'ar',
                    )
                )
            ),
            array(
                'iata_code' => 'DME',
                'city_code' => 'MOW',
                'country_code' => 'RU',
                'latitude' => '55.4145',
                'longitude' => '37.8999',
                'status' => 'active',
                'airport_names' => array(
                    array(
                        'airport_name' => 'Moscow-Domodedovo Arpt-Russia(DME)',
                        'language_code' => 'en',
                    ),
                    array(
                        'airport_name' => 'موسكو-مطار دوموديدوفو الدولي  -روسيا(DME)',
                        'language_code' => 'ar',
                    )
                )
            ),
            array(
                'iata_code' => 'IFN',
                'city_code' => 'IFN',
                'country_code' => 'IR',
                'latitude' => '32.75084',
                'longitude' => '51.86127',
                'status' => 'active',
                'airport_names' => array(
                    array(
                        'airport_name' => 'Isfahan-Isfahan International Airport-Iran(IFN)',
                        'language_code' => 'en',
                    ),
                    array(
                        'airport_name' => 'أصفهان-مطار أصفهان الدولي (الشهيد بهشتي الدولي)-إيران(IFN)',
                        'language_code' => 'ar',
                    )
                )
            ),
            array(
                'iata_code' => 'MED',
                'city_code' => 'MED',
                'country_code' => 'SA',
                'latitude' => '40.63983',
                'longitude' => '-73.77874',
                'status' => 'active',
                'airport_names' => array(
                    array(
                        'airport_name' => 'Madinah-Prince Mohammad Bin Abdulaziz Arpt-Saudi Arabia(MED)',
                        'language_code' => 'en',
                    ),
                    array(
                        'airport_name' => 'المدينة المنورة-مطار الأمير محمد بن عبدالعزيز الدولي-المملكة العربية السعودية(MED)',
                        'language_code' => 'ar',
                    )
                )
            ),
            array(
                'iata_code' => 'LGA',
                'city_code' => 'NYC',
                'country_code' => 'US',
                'latitude' => '40.77607',
                'longitude' => '-73.87269',
                'status' => 'active',
                'airport_names' => array(
                    array(
                        'airport_name' => 'New York-La Guardia-USA(LGA)',
                        'language_code' => 'en',
                    ),
                    array(
                        'airport_name' => 'نيويورك-مطار لاغوارديا-الولايات المتحدة الأمريكية(LGA)',
                        'language_code' => 'ar',
                    )
                )
            ),
        );

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \App\Models\AirportI18ns::truncate();
        Airport::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        foreach ($airportData AS $key => $airport) {
            Airport::createAirport($airport);
        }
    }
}
