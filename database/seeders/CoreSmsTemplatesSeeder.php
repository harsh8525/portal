<?php

namespace Database\Seeders;

use DB;

use Illuminate\Database\Seeder;

class CoreSmsTemplatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Truncate the 'core_sms_templates' table
        DB::table('core_sms_templates')->truncate();
        $path = __DIR__ . '/DB_Dump_SQL/core_sms_templates.sql';
        DB::unprepared(file_get_contents($path));
        $this->command->info('core_sms_templates table seeded!');
    }
}
