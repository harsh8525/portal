<?php

namespace Database\Seeders;

use DB;

use Illuminate\Database\Seeder;

class CurrenciesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Truncate the 'currencies' table
        DB::table('currencies')->truncate();
        $path = __DIR__ . '/DB_Dump_SQL/currencies.sql';
        DB::unprepared(file_get_contents($path));
        $this->command->info('currencies table seeded!');
    }
}
