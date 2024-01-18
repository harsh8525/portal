<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class AgencyRelatedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path = __DIR__.'/DB_Dump_SQL/agency_related_table_dumps.sql';
        DB::unprepared(file_get_contents($path));
        $this->command->info('agency_related_table_dumps table seeded!');
    }
}
