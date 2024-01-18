<?php

namespace Database\Seeders;

use DB;

use Illuminate\Database\Seeder;

class CurrencyExchangeRatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Truncate the 'currency_exchange_rates' table
        DB::table('currency_exchange_rates')->truncate();
        $path = __DIR__ . '/DB_Dump_SQL/currency_exchange_rates.sql';
        DB::unprepared(file_get_contents($path));
        $this->command->info('currency_exchange_rates table seeded!');
    }
}
