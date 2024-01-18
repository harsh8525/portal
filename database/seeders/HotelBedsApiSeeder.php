<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class HotelBedsApiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $deletedata = Setting::where('config_key', '=','hotelbeds|api|live|Api-key')->delete();
                            //    ->where('config_key', '=','hotelbeds|api|live|Api-key')->delete();
        $settingData = [
            //Login Attempts Records
            ['config_key'=>'hotelbeds|api|credential','value'=>'test'],
            
            ['config_key'=>'hotelbeds|api|test|endPoint','value'=>' https://api.test.hotelbeds.com/'],
            ['config_key'=>'hotelbeds|api|test|ApiKey','value'=>'403ab05fa97feb6974ac92345398ea8f'],
            ['config_key'=>'hotelbeds|api|test|secret','value'=>'222b597af5'],
            
            ['config_key'=>'hotelbeds|api|live|endPoint','value'=>' https://api.test.hotelbeds.com/'],
            ['config_key'=>'hotelbeds|api|live|ApiKey','value'=>'403ab05fa97feb6974ac92345398ea8f'],
            ['config_key'=>'hotelbeds|api|live|secret','value'=>'222b597af5'],
            
        ];
        
        Setting::upsert($settingData,'config_key');
    }
}
