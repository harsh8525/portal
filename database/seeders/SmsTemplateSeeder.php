<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SmsTemplate;
use Illuminate\Support\Facades\DB;

class SmsTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $smsData = array(
            
            array(
                'code' => 'SEND_OTP',
                'to_phone_no' => '',
                'sms_data' => array(
                    array(
                        'name' => 'Send OTP',
                        'content' => 'Use OTP : {{Otp}} to verify. OTP is valid for {{Otp_expire_minute}} minutes.',
                        'language_code' => 'en',
                    ),
                    array(
                        'name' => 'أرسل كلمة مرور لمرة واحدة',
                        'content' => 'استخدم OTP : {{Otp}} للتحقق. كلمة المرور لمرة واحدة (OTP) صالحة لمدة {{Otp_expire_minute}} دقيقة.',
                        'language_code' => 'ar',
                    )
                    ),
                ),

        );

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \App\Models\SmsTemplateI18ns::truncate();
        SmsTemplate::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        foreach ($smsData as $key => $sms) {
            SmsTemplate::createSeederSmsTemplates($sms);
        }
    }
}
