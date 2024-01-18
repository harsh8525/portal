<?php

namespace Database\Seeders;

use DB;

use Illuminate\Database\Seeder;

class CoreBankDetailsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Truncate the 'core_bank_details' table
        DB::table('core_bank_details')->truncate();
        $path = __DIR__ . '/DB_Dump_SQL/core_bank_details.sql';
        DB::unprepared(file_get_contents($path));
        $this->command->info('core_bank_details table seeded!');
    }
}
