<?php

namespace Database\Seeders;

use DB;

use Illuminate\Database\Seeder;

class CoreSuppliersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Truncate the 'core_suppliers' table
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('core_suppliers')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $path = __DIR__ . '/DB_Dump_SQL/core_suppliers.sql';
        DB::unprepared(file_get_contents($path));
        $this->command->info('core_suppliers table seeded!');
    }
}
