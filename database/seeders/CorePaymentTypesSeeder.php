<?php

namespace Database\Seeders;

use DB;

use Illuminate\Database\Seeder;

class CorePaymentTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Truncate the 'core_payment_types' table
        DB::table('core_payment_types')->truncate();

        $path = __DIR__ . '/DB_Dump_SQL/core_payment_types.sql';
        DB::unprepared(file_get_contents($path));
        $this->command->info('core_payment_types table seeded!');
    }
}
