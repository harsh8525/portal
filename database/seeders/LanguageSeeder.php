<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Language;
use Illuminate\Support\Facades\DB;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $language = [
            [
                'language_code' => 'en',
                'language_name' => 'English',
                'language_type' => 'LTR',
                'sort_order' => 2,
                'is_default' => 0,
                'status' => 1,
            ],
            [
                'language_code' => 'ar',
                'language_name' => 'Arabic',
                'language_type' => 'RTL',
                'sort_order' => 1,
                'is_default' => 1,
                'status' => 1,
            ]
        ];

        //User::updateOrCreate(['email'=>'ai.developer16@gmail.com'],$user);
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Language::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        Language::upsert($language, 'code');
    }
}
