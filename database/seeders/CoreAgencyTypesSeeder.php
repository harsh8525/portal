<?php

namespace Database\Seeders;

use DB;

use Illuminate\Database\Seeder;

class CoreAgencyTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Truncate the 'core_agency_types' table
        DB::table('core_agency_types')->truncate();
        $path = __DIR__ . '/DB_Dump_SQL/core_agency_types.sql';
        DB::unprepared(file_get_contents($path));
        $this->command->info('core_agency_types table seeded!');
    }
}
