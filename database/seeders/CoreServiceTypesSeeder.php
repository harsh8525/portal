<?php

namespace Database\Seeders;

use App\Models\ServiceType;
use DB;

use Illuminate\Database\Seeder;

class CoreServiceTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $path = __DIR__.'/DB_Dump_SQL/core_service_types.sql';
        // DB::unprepared(file_get_contents($path));
        // $this->command->info('core_service_types table seeded!');

        $service_type = [
            [
                'name' => 'Flight',
                'code' => 'Flight',
                'description' => 'Flight',
                'guideline' => 'Flight',
                'image' => null,
                'sort_order' => 1,
                'is_active' => 1,
            ],
            [
                'name' => 'Hotel',
                'code' => 'Hotel',
                'description' => 'Hotel',
                'guideline' => 'Hotel',
                'image' => null,
                'sort_order' => 2,
                'is_active' => 1,
            ]
        ];

        //User::updateOrCreate(['email'=>'ai.developer16@gmail.com'],$user);
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        ServiceType::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        ServiceType::upsert($service_type, 'code');
    }
}
