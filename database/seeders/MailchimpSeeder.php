<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class MailchimpSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settingData = [
            //Login Attempts Records
            ['config_key'=>'mailchimp|username','value'=>'travelportal'],
            ['config_key'=>'mailchimp|password','value'=>'P@ssw0rd'],
            ['config_key'=>'mailchimp|formUrl','value'=>'https://gmail.us4.list-manage.com/subscribe/post?u=9d74a0ba4bacfb1c716931c04&amp;id=6da6f720c5'],
        ];
        
        Setting::upsert($settingData,'config_key');
    }
}
