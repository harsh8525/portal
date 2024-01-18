<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settingData = [

            //Basic Information Records
            ['config_key' => 'general|basic|colorLogo', 'value' => ''],
            ['config_key' => 'general|basic|whiteLogo', 'value' => ''],
            ['config_key' => 'general|basic|favicon', 'value' => ''],
            ['config_key' => 'general|basic|siteName', 'value' => 'Travel Portal'],
            ['config_key' => 'general|basic|siteUrl', 'value' => 'https://www.travelportal.in/'],
            ['config_key' => 'general|basic|siteEmail', 'value' => 'info@travelportal.com'],
            ['config_key' => 'general|basic|sitePhoneNo', 'value' => '+91 9879998799'],

            //Address Information Records
            ['config_key' => 'general|basic|storeAddress', 'value' => '4th Floor, Sunrise Avenue, Stadium - Commerce Six Road, Opp: Saraspur Nagrik Bank, Swastik Society, Navrangpura, Ahmedabad'],
            ['config_key' => 'general|basic|storeCountry', 'value' => 'India'],
            ['config_key' => 'general|basic|storeState', 'value' => 'Gujarat'],
            ['config_key' => 'general|basic|storeCity', 'value' => 'Ahmedabad'],
            ['config_key' => 'general|basic|storePincode', 'value' => '380016'],
            ['config_key' => 'general|basic|storeGSTNo', 'value' => '22ABDCB1280M1N2'],


            //Maintenance Mode Records
            ['config_key' => 'general|maintenanceMode', 'value' => 'off'], //it set as "on" than message is required
            ['config_key' => 'general|maintenanceMode|message', 'value' => ''],

            //OTP verification Records
            ['config_key' => 'general|otp|phoneVerification', 'value' => 'off'], //it set as "on" than otp is required


            //Android Auto Update Records
            ['config_key' => 'general|androidUpdate', 'value' => 'optional'], //if set as "forcefully" than version is required
            ['config_key' => 'general|android|version', 'value' => ''],

            //iOS Auto Update Records
            ['config_key' => 'general|iosUpdate', 'value' => 'optional'], //if set as "forcefully" than version is required
            ['config_key' => 'general|ios|version', 'value' => ''],

            //Additional Information Records
            ['config_key' => 'general|setting|pagePerAdminRecords', 'value' => '20'],
            ['config_key' => 'general|setting|pagePerAPIRecords', 'value' => '20'],
            ['config_key' => 'general|setting|ResetMonth', 'value' => '4'],
            ['config_key' => 'general|site|dateFormat', 'value' => 'd/m/y'],
            ['config_key' => 'general|site|timeFormat', 'value' => 'h:m A'],
            ['config_key' => 'general|site|timeZone', 'value' => 'Asia/Kolkata'],
            ['config_key' => 'general|site|googleApiKey', 'value' => 'AIzaSyBSJqpjYRXr7zv6UyNl2xWLOM_t60myiyo'],
            ['config_key' => 'general|site|inquiryEmail', 'value' => 'info@amarinfotech.com'],
            ['config_key' => 'general|site|footerText', 'value' => 'Copyright © 2022 Amar Infotech. All rights reserved'],
            ['config_key' => 'general|site|defaultISDCode', 'value' => ''],
            ['config_key' => 'general|site|defaultLanguageCode', 'value' => 'en'],

            //B2C URL For Email Verification Records
            ['config_key' => 'general|b2cUrl', 'value' => 'https://rehlati.mydemoapp.us/'],

            //SMTP Details Records
            ['config_key' => 'mail|smtp|emailNotification', 'value' => '1'],
            ['config_key' => 'mail|smtp|server', 'value' => '1'],
            ['config_key' => 'mail|smtp|host', 'value' => 'mail.amarinfotech.com'],
            ['config_key' => 'mail|smtp|fromEmail', 'value' => 'info@amarinfotech.com'],
            ['config_key' => 'mail|smtp|smtpServer', 'value' => 'mail.amarinfotech.com'],
            ['config_key' => 'mail|smtp|userName', 'value' => 'jatin.p@amarinfotech.com'],
            ['config_key' => 'mail|smtp|password', 'value' => 'A:jhN];b>9(L<M&C'],
            ['config_key' => 'mail|smtp|security', 'value' => 'TLS'],
            ['config_key' => 'mail|smtp|port', 'value' => '587']
        ];
        Setting::upsert($settingData, 'config_key');
    }
}
