<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MailTemplate;
use Illuminate\Support\Facades\DB;

class MailTemplateSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $mailData = array(
            array(
                'code' => 'CUSTOMER_SIGN_UP',
                'from_email' => 'do-not-reply@travelportal.com',
                'to_email' => '',
                'cc' => '',
                'bcc' => '',
                'mail_data' => array(
                    array(
                        'name' => 'Customer Sign up',
                        'subject' => 'Welcome to {{AgencyName}}',
                        'content' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                            <html xmlns="http://www.w3.org/1999/xhtml">
                            <head>
                            <meta name="viewport" content="width=device-width" />
                            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                            <link href="pages/css/mail.css" media="all" rel="stylesheet" type="text/css" />
                            </head>
                            
                            <body itemscope itemtype="http://schema.org/EmailMessage" style="-webkit-font-smoothing: antialiased;-webkit-text-size-adjust: none;width: 100% !important;height: 100%;line-height: 1.6;background-color: #f6f6f6;margin: 0;font-family: "Raleway", sans-serif;box-sizing: border-box;font-size: 14px;">
                            
                            <table class="body-wrap" style="background-color: #f6f6f6;width: 100%;padding-top: 40px;">
                                <tr>
                                    <td></td>
                                    <td class="container" width="600" style="display: block !important;max-width: 600px !important;margin: 0 auto !important;clear: both !important;">
                                        <div class="content" style="max-width: 600px;margin: 0 auto;display: block;padding: 20px;">
                                            <table class="main" width="100%" cellpadding="0" cellspacing="0" style="background-color: #fff;border: 1px solid #e9e9e9;border-radius: 3px;border: 1px solid #999;">
                                               
                                                <tr>
                                                    <td class="content-wrap" style="padding: 25px;">
                                                        <table width="100%" cellpadding="0" cellspacing="0"> 
                                                             <tr>
                                                               <td style="text-align: center; padding: 0 0 15px">
                                                                   
                                                                         <img src="{{AgencyLogo}}" width="250">
                                                                    
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="content-block" style="padding: 0 0 15px;vertical-align: top;">
                                                                    Dear {{CustomerName}},  
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="content-block" style="padding: 0 0 15px;vertical-align: top;">
                                                                    <p style="font-weight: normal;font-size: 14px; margin: 0;">Welcome to the world of {{AgencyName}}.</p>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="content-block" style="padding: 0 0 15px;vertical-align: top;">
                                                                    We are excited that you have registered with {{AgencyName}} and look forward to meeting all your travel needs!
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="content-block" style="padding: 0 0 15px;vertical-align: top;">
                                                                    Please note that the email address we have on file for you is {{CustomerEmail}}.		
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="content-block" style="padding: 0 0 15px;vertical-align: top;">
                                                                    To get started, just  {{click here}} to confirm your registration.                                    </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="content-block" style="padding: 0 0 15px;vertical-align: top;">
                                                                    If you are not redirected upon link above, please copy and paste the link below into your web browser: {{ActivationLink}}                                  
                                                                 </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="vertical-align: top; padding:15px 0px 0px 0px">
                                                                    Its our pleasure to help you see the world your way!!<br>
                                                                    Sincerely, <br>
                                                                    {{AgencyName}} Team!!
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </td>
                                    <td></td>
                                </tr>
                            </table>
                            
                            </body>
                            </html>',
                        'language_code' => 'en',
                    ),
                    array(
                        'name' => 'تسجيل العميل',
                        'subject' => 'مرحبًا بك في {{AgencyName}}',
                        'content' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                        <html xmlns="http://www.w3.org/1999/xhtml">
                        <head>
                        <meta name="viewport" content="width=device-width" />
                        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                        <link href="pages/css/mail.css" media="all" rel="stylesheet" type="text/css" />
                        <style>
                        * {
                            direction: rtl;
                          }
                        </style>
                        </head>
                        
                        <body itemscope itemtype="http://schema.org/EmailMessage" style="-webkit-font-smoothing: antialiased;-webkit-text-size-adjust: none;width: 100% !important;direction: rtl;height: 100%;line-height: 1.6;background-color: #f6f6f6;margin: 0;font-family: "Raleway", sans-serif;box-sizing: border-box;font-size: 14px;">
                        
                        <table class="body-wrap" style="background-color: #f6f6f6;width: 100%;padding-top: 40px;">
                            <tr>
                                <td></td>
                                <td class="container" width="600" style="display: block !important;max-width: 600px !important;margin: 0 auto !important;clear: both !important;">
                                    <div class="content" style="max-width: 600px;margin: 0 auto;display: block;padding: 20px;">
                                        <table class="main" width="100%" cellpadding="0" cellspacing="0" style="background-color: #fff;border: 1px solid #e9e9e9;border-radius: 3px;border: 1px solid #999">
                        
                                            <tr>
                                                <td class="content-wrap" style="padding: 25px;">
                                                    <table width="100%" cellpadding="0" cellspacing="0"> 
                                                        <tr>
                                                            <td style="text-align: center; padding: 0 0 15px">
                                                                <img src="{{AgencyLogo}}" width="250">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="content-block" style="padding: 0 0 15px;vertical-align: top;">
                                                                عزيزي {{CustomerName}},  
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class "content-block" style="padding: 0 0 15px;vertical-align: top;">
                                                                <p style="font-weight: normal;font-size: 14px; margin: 0;">مرحبًا بك في عالم {{AgencyName}}.</p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="content-block" style="padding: 0 0 15px;vertical-align: top;">
                                                                نحن متحمسون لأنك قد قمت بالتسجيل في {{AgencyName}} ونتطلع إلى تلبية جميع احتياجات سفرك!
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="content-block" style="padding: 0 0 15px;vertical-align: top;">
                                                                يُرجى ملاحظة أن عنوان البريد الإلكتروني الذي لدينا لك هو {{CustomerEmail}}.
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="content-block" style="padding: 0 0 15px;vertical-align: top;">
                                                                للبدء، ما عليك سوى {{click here}} لتأكيد تسجيلك.
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="content-block" style="padding: 0 0 15px;vertical-align: top;">
                                                                إذا لم تتم إعادة توجيهك إلى الرابط أعلاه، يُرجى نسخ ولصق الرابط أدناه في متصفح الويب الخاص بك: {{ActivationLink}}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="vertical-align: top; padding:15px 0px 0px 0px">
                                                                من دواعي سرورنا مساعدتك في رؤية العالم بطريقتك!!<br>
                                                                بصدق, <br>
                                                                فريق {{AgencyName}}!!
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </td>
                                <td></td>
                            </tr>
                        </table>
                        
                        </body>
                        </html>
                        ',
                        'language_code' => 'ar',
                    )
                ),
            ),
            array(
                'code' => 'CUSTOMER_ACCOUNT_ACTIVATION',
                'from_email' => 'do-not-reply@travelportal.com',
                'to_email' => '',
                'cc' => '',
                'bcc' => '',
                'mail_data' => array(
                    array(
                        'name' => 'Customer Account Activation ',
                        'subject' => '{{AgencyName}} Account Activated',
                        'content' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                            <html xmlns="http://www.w3.org/1999/xhtml">
                            <head>
                            <meta name="viewport" content="width=device-width" />
                            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                            <link href="pages/css/mail.css" media="all" rel="stylesheet" type="text/css" />
                            </head>
                            
                            <body itemscope itemtype="http://schema.org/EmailMessage" style="-webkit-font-smoothing: antialiased;-webkit-text-size-adjust: none;width: 100% !important;height: 100%;line-height: 1.6;background-color: #f6f6f6;margin: 0;font-family: "Raleway", sans-serif;box-sizing: border-box;font-size: 14px;">
                            
                            <table class="body-wrap" style="background-color: #f6f6f6;width: 100%;padding-top: 40px;">
                                <tr>
                                    <td></td>
                                    <td class="container" width="600" style="display: block !important;max-width: 600px !important;margin: 0 auto !important;clear: both !important;">
                                        <div class="content" style="max-width: 600px;margin: 0 auto;display: block;padding: 20px;">
                                            <table class="main" width="100%" cellpadding="0" cellspacing="0" style="background-color: #fff;border: 1px solid #e9e9e9;border-radius: 3px;border: 1px solid #999;">
                                                
                                                <tr>
                                                    <td class="content-wrap" style="padding: 25px;">
                                                        <table width="100%" cellpadding="0" cellspacing="0"> 
                                                            <tr>
                                                               <td style="text-align: center; padding: 0 0 15px">
                                                                   
                                                                         <img src="{{AgencyLogo}}" width="250">
                                                                    
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="content-block" style="padding: 0 0 15px;vertical-align: top;">
                                                                    Dear {{CustomerName}},  
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="content-block" style="padding: 0 0 15px;vertical-align: top;">
                                                                    <p style="font-weight: normal;font-size: 14px; margin: 0;">Congratulations! Your account has been activated.</p>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="content-block" style="padding: 0 0 15px;vertical-align: top;">
                                                                    Welcome to {{AgencyName}}, your one stop shop for all Travel related requirements. Managing your travel needs becomes easy for you now!!
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="content-block" style="padding: 0 0 15px;vertical-align: top;">
                                                                    Manage your Trips, Traveller Information, password and other details.
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="content-block" style="padding: 0 0 15px;vertical-align: top;">
                                                                    Sign in now to start using these amazing features.
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="vertical-align: top; padding:15px 0px 0px 0px">
                                                                    Best Regards ,<br>
                                                                    {{AgencyName}} Team!!
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </td>
                                    <td></td>
                                </tr>
                            </table>
                            
                            </body>
                            </html>',
                        'language_code' => 'en',
                    ),
                    array(
                        'name' => 'تفعيل حساب العميل',
                        'subject' => 'تم تفعيل حساب {{AgencyName}}',
                        'content' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                        <html xmlns="http://www.w3.org/1999/xhtml">
                        <head>
                            <meta name="viewport" content="width=device-width" />
                            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                            <link href="pages/css/mail.css" media="all" rel="stylesheet" type="text/css" />
                        </head>
                        
                        <body itemscope itemtype="http://schema.org/EmailMessage" style="direction: rtl;-webkit-font-smoothing: antialiased;-webkit-text-size-adjust: none;width: 100% !important;height: 100%;line-height: 1.6;background-color: #f6f6f6;margin: 0;font-family: "Raleway", sans-serif;box-sizing: border-box;font-size: 14px;">
                        
                        <table class="body-wrap" style="background-color: #f6f6f6;width: 100%;padding-top: 40px;">
                            <tr>
                                <td></td>
                                <td class="container" width="600" style="display: block !important;max-width: 600px !important;margin: 0 auto !important;clear: both !important;">
                                    <div class="content" style="max-width: 600px;margin: 0 auto;display: block;padding: 20px;">
                                        <table class="main" width="100%" cellpadding="0" cellspacing="0" style="background-color: #fff;border: 1px solid #e9e9e9;border-radius: 3px;border: 1px solid #999;">
                        
                                            <tr>
                                                <td class="content-wrap" style="padding: 25px;">
                                                    <table width="100%" cellpadding="0" cellspacing="0">
                                                        <tr>
                                                            <td style="text-align: center; padding: 0 0 15px">
                                                                <img src="{{AgencyLogo}}" width="250">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="content-block" style="padding: 0 0 15px;vertical-align: top;">
                                                                عزيزي {{CustomerName}},
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="content-block" style="padding: 0 0 15px;vertical-align: top;">
                                                                <p style="font-weight: normal;font-size: 14px; margin: 0;">تهانينا! تم تنشيط حسابك.</p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="content-block" style="padding: 0 0 15px;vertical-align: top;">
                                                                مرحبًا بك في {{AgencyName}}, مكانك الواحد لجميع احتياجات السفر. إدارة احتياجات السفر الخاصة بك أصبح أسهل الآن!
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="content-block" style="padding: 0 0 15px;vertical-align: top;">
                                                                قم بإدارة رحلاتك، معلومات المسافرين، كلمة المرور وتفاصيل أخرى.
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="content-block" style="padding: 0 0 15px;vertical-align: top;">
                                                                قم بتسجيل الدخول الآن لبدء استخدام هذه الميزات المذهلة.
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="vertical-align: top; padding: 15px 0px 0px 0px">
                                                                مع خالص التحية,
                                                                فريق {{AgencyName}}!!
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </td>
                                <td></td>
                            </tr>
                        </table>
                        
                        </body>
                        </html>',
                        'language_code' => 'ar',
                    )
                ),
            ),
            array(
                'code' => 'CHANGE_PASSWORD',
                'from_email' => 'do-not-reply@travelportal.com',
                'to_email' => '',
                'cc' => '',
                'bcc' => '',
                'mail_data' => array(
                    array(
                        'name' => 'Change Password',
                        'subject' => 'Account Password Changed at {{AgencyName}}',
                        'content' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                            <html xmlns="http://www.w3.org/1999/xhtml">
                            <head>
                            <meta name="viewport" content="width=device-width" />
                            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                            <link href="pages/css/mail.css" media="all" rel="stylesheet" type="text/css" />
                            </head>
                            
                            <body itemscope itemtype="http://schema.org/EmailMessage" style="-webkit-font-smoothing: antialiased;-webkit-text-size-adjust: none;width: 100% !important;height: 100%;line-height: 1.6;background-color: #f6f6f6;margin: 0;font-family: "Raleway", sans-serif;box-sizing: border-box;font-size: 14px;">
                            
                            <table class="body-wrap" style="background-color: #f6f6f6;width: 100%;padding-top: 40px;">
                                <tr>
                                    <td></td>
                                    <td class="container" width="600" style="display: block !important;max-width: 600px !important;margin: 0 auto !important;clear: both !important;">
                                        <div class="content" style="max-width: 600px;margin: 0 auto;display: block;padding: 20px;">
                                            <table class="main" width="100%" cellpadding="0" cellspacing="0" style="background-color: #fff;border: 1px solid #e9e9e9;border-radius: 3px;border: 1px solid #999;">
                                                <tr>
                                                    <td class="content-wrap" style="padding: 25px;">
                                                        <table width="100%" cellpadding="0" cellspacing="0">
                                                            <tr>
                                                                <td style="text-align: center; padding: 0 0 15px">
                                                                   
                                                                         <img src="{{AgencyLogo}}" width="250">
                                                                    
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="padding: 0 0 15px;vertical-align: top;">
                                                                    <p style="font-weight: normal;font-size: 14px; margin: 0;">Dear {{CustomerName}}, </p>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="padding: 0 0 15px;vertical-align: top;">
                                                                    Your {{AgencyName}} Account Password was Changed on {{DateTime}}. 
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="padding: 0 0 15px;vertical-align: top;">
                                                                    If you did this, you can safely disregard this email. If you did not do this, please secure your account or Contact Us.
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="vertical-align: top; padding:15px 0px 0px 0px">
                                                                    Thanks,<br>
                                                                    {{AgencyName}} Team!!
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </td>
                                    <td></td>
                                </tr>
                            </table>
                            
                            </body>
                            </html>',
                        'language_code' => 'en',
                    ),
                    array(
                        'name' => 'تغيير كلمة المرور',
                        'subject' => 'تم تغيير كلمة مرور الحساب في {{AgencyName}}',
                        'content' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                        <html xmlns="http://www.w3.org/1999/xhtml">
                        <head>
                        <meta name="viewport" content="width=device-width" />
                        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                        <link href="pages/css/mail.css" media="all" rel="stylesheet" type="text/css" />
                        <style>
                        * {
                            direction: rtl;
                          }
                        </style>
                        </head>
                        
                        <body itemscope itemtype="http://schema.org/EmailMessage" style="direction: rtl !importent; -webkit-font-smoothing: antialiased;-webkit-text-size-adjust: none;width: 100% !important;height: 100%;line-height: 1.6;background-color: #f6f6f6;margin: 0;font-family: "Raleway", sans-serif;box-sizing: border-box;font-size: 14px;">
                        
                        <table class="body-wrap" style="background-color: #f6f6f6;width: 100%;padding-top: 40px;">
                            <tr>
                                <td></td>
                                <td class="container" width="600" style="display: block !important;max-width: 600px !important;margin: 0 auto !important;clear: both !important;">
                                    <div class="content" style="max-width: 600px;margin: 0 auto;display: block;padding: 20px;">
                                        <table class="main" width="100%" cellpadding="0" cellspacing="0" style="background-color: #fff;border: 1px solid #e9e9e9;border-radius: 3px;border: 1px solid #999;">
                                            <tr>
                                                <td class="content-wrap" style="padding: 25px;">
                                                    <table width="100%" cellpadding="0" cellspacing="0">
                                                        <tr>
                                                            <td style="text-align: center; padding: 0 0 15px">
                                                               
                                                                     <img src="{{AgencyLogo}}" width="250">
                                                                
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="padding: 0 0 15px;vertical-align: top;">
                                                                <p style="font-weight: normal;font-size: 14px; margin: 0;">عزيزي {{CustomerName}}, </p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="padding: 0 0 15px;vertical-align: top;">
                                                                تم تغيير كلمة المرور لحسابك في {{AgencyName}} في تاريخ {{DateTime}}. 
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="padding: 0 0 15px;vertical-align: top;">
                                                                إذا قمت بهذا بنفسك، يمكنك تجاهل هذا البريد الإلكتروني بأمان. إذا لم تكن أنت من قام بهذا، يرجى تأمين حسابك أو الاتصال بنا.
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="vertical-align: top; padding:15px 0px 0px 0px">
                                                                شكرًا،<br>
                                                                فريق {{AgencyName}}!!
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </td>
                                <td></td>
                            </tr>
                        </table>
                        
                        </body>
                        </html>
                        ',
                        'language_code' => 'ar',
                    )
                ),
            ),
            array(
                'code' => 'PASSWORD_EXPIRY',
                'from_email' => 'do-not-reply@travelportal.com',
                'to_email' => '',
                'cc' => '',
                'bcc' => '',
                'mail_data' => array(
                    array(
                        'name' => 'Password Expiry',
                        'subject' => 'Account Passowrd will expire in {{PasswordExpiryDay}} days!! | {{AgencyName}}',
                        'content' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                        <html xmlns="http://www.w3.org/1999/xhtml">
                        <head>
                        <meta name="viewport" content="width=device-width" />
                        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                        <link href="pages/css/mail.css" media="all" rel="stylesheet" type="text/css" />
                        </head>
                        
                        <body itemscope itemtype="http://schema.org/EmailMessage" style="-webkit-font-smoothing: antialiased;-webkit-text-size-adjust: none;width: 100% !important;height: 100%;line-height: 1.6;background-color: #f6f6f6;margin: 0;font-family: "Raleway", sans-serif;box-sizing: border-box;font-size: 14px;">
                        
                        <table class="body-wrap" style="background-color: #f6f6f6;width: 100%;padding-top: 40px;">
                            <tr>
                                <td></td>
                                <td class="container" width="600" style="display: block !important;max-width: 600px !important;margin: 0 auto !important;clear: both !important;">
                                    <div class="content" style="max-width: 600px;margin: 0 auto;display: block;padding: 20px;">
                                        <table class="main" width="100%" cellpadding="0" cellspacing="0" style="background-color: #fff;border: 1px solid #e9e9e9;border-radius: 3px;border: 1px solid #999;">
                                            
                                            <tr>
                                                <td style="padding: 25px;">
                                                    <table width="100%" cellpadding="0" cellspacing="0"> 
                                                        <tr>
                                                           <td style="text-align: center; padding: 0 0 15px">
                                                               
                                                                     
                                                                     <img src="{{AgencyLogo}}" width="250">
                                                                
                                                                
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="padding: 0 0 15px;vertical-align: top;">
                                                                Dear {{CustomerName}}, 
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="padding: 0 0 15px;vertical-align: top;">
                                                                <p style="font-weight: normal;font-size: 14px; margin: 0;">Please note that your password to {{AgencyName}} account will expire in {{PasswordExpiryDay}} days!</p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="padding: 0 0 15px;vertical-align: top;">
                                                                We want to let you know that your access credentials will be discontinued soon! 
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="padding: 0 0 15px;vertical-align: top;">
                                                                After your evaluation ends, Repeated use of your expired password will lock down the account. 
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="padding: 0 0 15px;vertical-align: top;">
                                                                Please Reset your password and Enjoy best travel deals on {{AgencyName}}!! 
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="vertical-align: top; padding:15px 0px 0px 0px">
                                                                Thanks,<br>
                                                                {{AgencyName}} Team!!
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </td>
                                <td></td>
                            </tr>
                        </table>
                        
                        </body>
                        </html>',
                        'language_code' => 'en',
                    ),
                    array(
                        'name' => 'انتهاء صلاحية كلمة المرور',
                        'subject' => 'ستنتهي صلاحية كلمة مرور الحساب خلال {{Password_ExpireD}} يوم!! | {{AgencyName}}',
                        'content' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                        <html xmlns="http://www.w3.org/1999/xhtml">
                        <head>
                        <meta name="viewport" content="width=device-width" />
                        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                        <link href="pages/css/mail.css" media="all" rel="stylesheet" type="text/css" />
                        </head>
                        
                        <body itemscope itemtype="http://schema.org/EmailMessage" style="direction: rtl;-webkit-font-smoothing: antialiased;-webkit-text-size-adjust: none;width: 100% !important;height: 100%;line-height: 1.6;background-color: #f6f6f6;margin: 0;font-family: "Raleway", sans-serif;box-sizing: border-box;font-size: 14px;">
                        
                        <table class="body-wrap" style="background-color: #f6f6f6;width: 100%;padding-top: 40px;">
                            <tr>
                                <td></td>
                                <td class="container" width="600" style="display: block !important;max-width: 600px !important;margin: 0 auto !important;clear: both !important;">
                                    <div class="content" style="max-width: 600px;margin: 0 auto;display: block;padding: 20px;">
                                        <table class="main" width="100%" cellpadding="0" cellspacing="0" style="background-color: #fff;border: 1px solid #e9e9e9;border-radius: 3px;border: 1px solid #999;">
                        
                                            <tr>
                                                <td style="padding: 25px;">
                                                    <table width="100%" cellpadding="0" cellspacing="0"> 
                                                        <tr>
                                                           <td style="text-align: center; padding: 0 0 15px">
                                                               
                                                                  <img src="{{AgencyLogo}}" width="250">
                                                            
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="padding: 0 0 15px;vertical-align: top;">
                                                            عزيزي {{CustomerName}},
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="padding: 0 0 15px;vertical-align: top;">
                                                            <p style="font-weight: normal;font-size: 14px; margin: 0;">يرجى ملاحظة أن كلمة مرور حساب {{AgencyName}} ستنتهي صلاحيتها في غضون {{PasswordExpiryDay}} يومًا!</p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="padding: 0 0 15px;vertical-align: top;">
                                                            نرغب في إعلامك أن وثائق الدخول الخاصة بك ستتوقف قريبًا!
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="padding: 0 0 15px;vertical-align: top;">
                                                            بعد انتهاء مدة صلاحيتها، ستؤدي محاولة استخدام كلمة المرور الغير صالحة إلى قفل الحساب.
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="padding: 0 0 15px;vertical-align: top;">
                                                            يرجى إعادة تعيين كلمة المرور الخاصة بك والاستمتاع بأفضل عروض السفر على {{AgencyName}}!!
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="vertical-align: top; padding:15px 0px 0px 0px">
                                                            شكرًا،<br>
                                                            فريق {{AgencyName}}!!
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </td>
                            <td></td>
                        </tr>
                        </table>
                        
                        </body>
                        </html>',
                        'language_code' => 'ar',
                    )
                ),
            ),
            array(
                'code' => 'FORGOT_PASSWORD',
                'from_email' => 'do-not-reply@travelportal.com',
                'to_email' => '917359841082',
                'cc' => '917359841082',
                'bcc' => '917359841082',
                'mail_data' => array(
                    array(
                        'name' => 'Forgot Password',
                        'subject' => 'Reset your Password | {{AgencyName}} ',
                        'content' => "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
                        <html xmlns='http://www.w3.org/1999/xhtml'>
                        <head>
                        <meta name='viewport' content='width=device-width' />
                        <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
                        <link href='pages/css/mail.css' media='all' rel='stylesheet' type='text/css' />
                        </head>
                        
                        <body itemscope itemtype='http://schema.org/EmailMessage' style='-webkit-font-smoothing: antialiased;-webkit-text-size-adjust: none;width: 100% !important;height: 100%;line-height: 1.6;background-color: #f6f6f6;margin: 0;font-family: 'Raleway', sans-serif;box-sizing: border-box;font-size: 14px;'>
                        
                        <table class='body-wrap' style='background-color: #f6f6f6;width: 100%;padding-top: 40px;'>
                            <tr>
                                <td></td>
                                <td class='container' width='600' style='display: block !important;max-width: 600px !important;margin: 0 auto !important;clear: both !important;'>
                                    <div class='content' style='max-width: 600px;margin: 0 auto;display: block;padding: 20px;'>
                                        <table class='main' width='100%' cellpadding='0' cellspacing='0' style='background-color: #fff;border: 1px solid #e9e9e9;border-radius: 3px;border: 1px solid #999;'>
                                            
                                            <tr>
                                                <td style='padding: 25px;'>
                                                    <table width='100%' cellpadding='0' cellspacing='0'> 
                                                        <tr>
                                                           <td style='text-align: center; padding: 0 0 15px'>
                                                               
                                                                   
                                                                     <img src='{{AgencyLogo}}' width='250'>
                                                                
                                                                
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style='padding: 0 0 15px;vertical-align: top;'>
                                                                Dear {{CustomerName}}, 
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style='padding: 0 0 15px;vertical-align: top;'>
                                                                <p style='font-weight: normal;font-size: 14px; margin: 0;'>
                                                                    Can't remember your password? Don't worry about it !! It happens.</p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style='padding: 0 0 15px;vertical-align: top;'>
                                                                Follow the instructions below within {{Duration}} hours to reset your password. 
                                                                <a href='{{forgotPasswordLink}}' target='_blank' style='color: rgb(52, 142, 218);'>Click Here</a> 
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style='padding: 0 0 15px;vertical-align: top;'>
                                                                Didn't ask to reset your password? If you didn't ask for your password, it's likely that another user entered your username or email address by mistake while trying to reset their password. If that's the case, you don't need to take any further action and can safely disregard this email.
                                                            </td>
                                                        </tr>
                                                       
                                                        <tr>
                                                            <td style='vertical-align: top; padding:15px 0px 0px 0px'>
                                                                Thanks,<br>
                                                                {{AgencyName}} Team!!
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </td>
                                <td></td>
                            </tr>
                        </table>
                        
                        </body>
                        </html>",
                        'language_code' => 'en',
                    ),
                    array(
                        'name' => 'هل نسيت كلمة السر',
                        'subject' => 'إعادة تعيين كلمة المرور الخاصة بك | {{AgencyName}}',
                        'content' => "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
                        <html xmlns='http://www.w3.org/1999/xhtml'>
                        <head>
                        <meta name='viewport' content='width=device-width' />
                        <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
                        <link href='pages/css/mail.css' media='all' rel='stylesheet' type='text/css' />
                        </head>
                        
                        <body itemscope itemtype='http://schema.org/EmailMessage' style='direction: rtl;-webkit-font-smoothing: antialiased;-webkit-text-size-adjust: none;width: 100% !important;height: 100%;line-height: 1.6;background-color: #f6f6f6;margin: 0;font-family: 'Raleway', sans-serif;box-sizing: border-box;font-size: 14px;'>
                        <table class='body-wrap' style='background-color: #f6f6f6;width: 100%;padding-top: 40px;'>
                            <tr>
                                <td></td>
                                <td class='container' width='600' style='display: block !important;max-width: 600px !important;margin: 0 auto !important;clear: both !important;'>
                                    <div class='content' style='max-width: 600px;margin: 0 auto;display: block;padding: 20px;'>
                                        <table class='main' width='100%' cellpadding='0' cellspacing='0' style='background-color: #fff;border: 1px solid #e9e9e9;border-radius: 3px;border: 1px solid #999;'>
                                            <tr>
                                                <td style='padding: 25px;'>
                                                    <table width='100%' cellpadding='0' cellspacing='0'> 
                                                        <tr>
                                                            <td style='text-align: center; padding: 0 0 15px'>
                                                                <img src='{{AgencyLogo}}' width='250'>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style='padding: 0 0 15px;vertical-align: top;'>
                                                                عزيزي {{CustomerName}},
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style='padding: 0 0 15px;vertical-align: top;'>
                                                                <p style='font-weight: normal;font-size: 14px; margin: 0;'>
                                                                    هل لا تتذكر كلمة المرور الخاصة بك؟ لا تقلق !! ذلك يحدث.
                                                                </p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style='padding: 0 0 15px;vertical-align: top;'>
                                                                اتبع التعليمات أدناه خلال {{Duration}} ساعة لإعادة تعيين كلمة المرور الخاصة بك. 
                                                                <a href='{{forgotPasswordLink}}' target='_blank' style='color: rgb(52, 142, 218);'>انقر هنا</a> 
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style='padding: 0 0 15px;vertical-align: top;'>
                                                                لم تطلب إعادة تعيين كلمة المرور الخاصة بك؟ إذا لم تكن قد طلبت إعادة تعيين كلمة المرور الخاصة بك، فمن المرجح أن يكون مستخدم آخر قد أدخل اسم المستخدم أو عنوان البريد الإلكتروني الخاص بك عن طريق الخطأ أثناء محاولة إعادة تعيين كلمة مروره. إذا كانت هذه هي الحالة، فليس عليك اتخاذ أي إجراء إضافي ويمكنك تجاهل هذا البريد الإلكتروني بأمان.
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style='vertical-align: top; padding:15px 0px 0px 0px'>
                                                                شكراً,<br>
                                                                فريق {{AgencyName}}!!
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </td>
                                <td></td>
                            </tr>
                        </table>
                        </body>
                        </html>
                        ",
                        'language_code' => 'ar',
                    )
                ),
            ),
            array(
                'code' => 'LOGIN_ATTEMPTS_EXCEED',
                'from_email' => 'do-not-reply@travelportal.com',
                'to_email' => '',
                'cc' => '',
                'bcc' => '',
                'mail_data' => array(
                    array(
                        'name' => 'Login Attempts Exceed',
                        'subject' => 'Maximum number of Login attempts exceeded | {{AgencyName}}',
                        'content' => "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
                        <html xmlns='http://www.w3.org/1999/xhtml'>
                        <head><meta name='viewport' content='width=device-width' /><meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
                            <link href='pages/css/mail.css' media='all' rel='stylesheet' type='text/css' />
                            <link data-turbo-eval='false' data-turbolinks-eval='false' href='//travelportal.localhost/_debugbar/assets/stylesheets?v=1675524420&amp;theme=auto' property='stylesheet' rel='stylesheet' type='text/css' /><script src='//travelportal.localhost/_debugbar/assets/javascript?v=1675524420' data-turbolinks-eval='false' data-turbo-eval='false'></script><script data-turbo-eval='false'>jQuery.noConflict(true);</script><script> Sfdump = window.Sfdump || (function (doc) { var refStyle = doc.createElement('style'), rxEsc = /([.*+?^$()|\[\]\/\\])/g, idRx = /\bsf-dump-\d+-ref[012]\w+\b/, keyHint = 0 <= navigator.platform.toUpperCase().indexOf('MAC') ? 'Cmd' : 'Ctrl', addEventListener = function (e, n, cb) { e.addEventListener(n, cb, false); }; refStyle.innerHTML = '.phpdebugbar pre.sf-dump .sf-dump-compact, .sf-dump-str-collapse .sf-dump-str-collapse, .sf-dump-str-expand .sf-dump-str-expand { display: none; }'; doc.head.appendChild(refStyle); refStyle = doc.createElement('style'); doc.head.appendChild(refStyle); if (!doc.addEventListener) { addEventListener = function (element, eventName, callback) { element.attachEvent('on' + eventName, function (e) { e.preventDefault = function () { e.returnValue = false; }; e.target = e.srcElement; callback(e); }); }; } function toggle(a, recursive) { var s = a.nextSibling || {}, oldClass = s.className, arrow, newClass; if (/\bsf-dump-compact\b/.test(oldClass)) { arrow = '▼'; newClass = 'sf-dump-expanded'; } else if (/\bsf-dump-expanded\b/.test(oldClass)) { arrow = '▶'; newClass = 'sf-dump-compact'; } else { return false; } if (doc.createEvent && s.dispatchEvent) { var event = doc.createEvent('Event'); event.initEvent('sf-dump-expanded' === newClass ? 'sfbeforedumpexpand' : 'sfbeforedumpcollapse', true, false); s.dispatchEvent(event); } a.lastChild.innerHTML = arrow; s.className = s.className.replace(/\bsf-dump-(compact|expanded)\b/, newClass); if (recursive) { try { a = s.querySelectorAll('.' + oldClass); for (s = 0; s < a.length; ++s) { if (-1 == a[s].className.indexOf(newClass)) { a[s].className = newClass; a[s].previousSibling.lastChild.innerHTML = arrow; } } } catch (e) { } } return true; }; function collapse(a, recursive) { var s = a.nextSibling || {}, oldClass = s.className; if (/\bsf-dump-expanded\b/.test(oldClass)) { toggle(a, recursive); return true; } return false; }; function expand(a, recursive) { var s = a.nextSibling || {}, oldClass = s.className; if (/\bsf-dump-compact\b/.test(oldClass)) { toggle(a, recursive); return true; } return false; }; function collapseAll(root) { var a = root.querySelector('a.sf-dump-toggle'); if (a) { collapse(a, true); expand(a); return true; } return false; } function reveal(node) { var previous, parents = []; while ((node = node.parentNode || {}) && (previous = node.previousSibling) && 'A' === previous.tagName) { parents.push(previous); } if (0 !== parents.length) { parents.forEach(function (parent) { expand(parent); }); return true; } return false; } function highlight(root, activeNode, nodes) { resetHighlightedNodes(root); Array.from(nodes || []).forEach(function (node) { if (!/\bsf-dump-highlight\b/.test(node.className)) { node.className = node.className + ' sf-dump-highlight'; } }); if (!/\bsf-dump-highlight-active\b/.test(activeNode.className)) { activeNode.className = activeNode.className + ' sf-dump-highlight-active'; } } function resetHighlightedNodes(root) { Array.from(root.querySelectorAll('.sf-dump-str, .sf-dump-key, .sf-dump-public, .sf-dump-protected, .sf-dump-private')).forEach(function (strNode) { strNode.className = strNode.className.replace(/\bsf-dump-highlight\b/, ''); strNode.className = strNode.className.replace(/\bsf-dump-highlight-active\b/, ''); }); } return function (root, x) { root = doc.getElementById(root); var indentRx = new RegExp('^(' + (root.getAttribute('data-indent-pad') || ' ').replace(rxEsc, '\\$1') + ')+', 'm'), options = { 'maxDepth': 1, 'maxStringLength': 160, 'fileLinkFormat': false }, elt = root.getElementsByTagName('A'), len = elt.length, i = 0, s, h, t = []; while (i < len) t.push(elt[i++]); for (i in x) { options[i] = x[i]; } function a(e, f) { addEventListener(root, e, function (e, n) { if ('A' == e.target.tagName) { f(e.target, e); } else if ('A' == e.target.parentNode.tagName) { f(e.target.parentNode, e); } else { n = /\bsf-dump-ellipsis\b/.test(e.target.className) ? e.target.parentNode : e.target; if ((n = n.nextElementSibling) && 'A' == n.tagName) { if (!/\bsf-dump-toggle\b/.test(n.className)) { n = n.nextElementSibling || n; } f(n, e, true); } } }); }; function isCtrlKey(e) { return e.ctrlKey || e.metaKey; } function xpathString(str) { var parts = str.match(/[^'']+|['']/g).map(function (part) { if (''' == part) { return ''\'''; } if (''' == part) { return ''\'''; } return ''' + part + '''; }); return 'concat(' + parts.join(',') + ', '')'; } function xpathHasClass(className) { return 'contains(concat(' ', normalize-space(@class), ' '), ' ' + className + ' ')'; } addEventListener(root, 'mouseover', function (e) { if ('' != refStyle.innerHTML) { refStyle.innerHTML = ''; } }); a('mouseover', function (a, e, c) { if (c) { e.target.style.cursor = 'pointer'; } else if (a = idRx.exec(a.className)) { try { refStyle.innerHTML = '.phpdebugbar pre.sf-dump .' + a[0] + '{background-color: #B729D9; color: #FFF !important; border-radius: 2px}'; } catch (e) { } } }); a('click', function (a, e, c) { if (/\bsf-dump-toggle\b/.test(a.className)) { e.preventDefault(); if (!toggle(a, isCtrlKey(e))) { var r = doc.getElementById(a.getAttribute('href').slice(1)), s = r.previousSibling, f = r.parentNode, t = a.parentNode; t.replaceChild(r, a); f.replaceChild(a, s); t.insertBefore(s, r); f = f.firstChild.nodeValue.match(indentRx); t = t.firstChild.nodeValue.match(indentRx); if (f && t && f[0] !== t[0]) { r.innerHTML = r.innerHTML.replace(new RegExp('^' + f[0].replace(rxEsc, '\\$1'), 'mg'), t[0]); } if (/\bsf-dump-compact\b/.test(r.className)) { toggle(s, isCtrlKey(e)); } } if (c) { } else if (doc.getSelection) { try { doc.getSelection().removeAllRanges(); } catch (e) { doc.getSelection().empty(); } } else { doc.selection.empty(); } } else if (/\bsf-dump-str-toggle\b/.test(a.className)) { e.preventDefault(); e = a.parentNode.parentNode; e.className = e.className.replace(/\bsf-dump-str-(expand|collapse)\b/, a.parentNode.className); } }); elt = root.getElementsByTagName('SAMP'); len = elt.length; i = 0; while (i < len) t.push(elt[i++]); len = t.length; for (i = 0; i < len; ++i) { elt = t[i]; if ('SAMP' == elt.tagName) { a = elt.previousSibling || {}; if ('A' != a.tagName) { a = doc.createElement('A'); a.className = 'sf-dump-ref'; elt.parentNode.insertBefore(a, elt); } else { a.innerHTML += ' '; } a.title = (a.title ? a.title + '\n[' : '[') + keyHint + '+click] Expand all children'; a.innerHTML += elt.className == 'sf-dump-compact' ? '<span>▶</span>' : '<span>▼</span>'; a.className += ' sf-dump-toggle'; x = 1; if ('sf-dump' != elt.parentNode.className) { x += elt.parentNode.getAttribute('data-depth') / 1; } } else if (/\bsf-dump-ref\b/.test(elt.className) && (a = elt.getAttribute('href'))) { a = a.slice(1); elt.className += ' ' + a; if (/[\[{]$/.test(elt.previousSibling.nodeValue)) { a = a != elt.nextSibling.id && doc.getElementById(a); try { s = a.nextSibling; elt.appendChild(a); s.parentNode.insertBefore(a, s); if (/^[@#]/.test(elt.innerHTML)) { elt.innerHTML += ' <span>▶</span>'; } else { elt.innerHTML = '<span>▶</span>'; elt.className = 'sf-dump-ref'; } elt.className += ' sf-dump-toggle'; } catch (e) { if ('&' == elt.innerHTML.charAt(0)) { elt.innerHTML = '…'; elt.className = 'sf-dump-ref'; } } } } } if (doc.evaluate && Array.from && root.children.length > 1) { root.setAttribute('tabindex', 0); SearchState = function () { this.nodes = []; this.idx = 0; }; SearchState.prototype = { next: function () { if (this.isEmpty()) { return this.current(); } this.idx = this.idx < (this.nodes.length - 1) ? this.idx + 1 : 0; return this.current(); }, previous: function () { if (this.isEmpty()) { return this.current(); } this.idx = this.idx > 0 ? this.idx - 1 : (this.nodes.length - 1); return this.current(); }, isEmpty: function () { return 0 === this.count(); }, current: function () { if (this.isEmpty()) { return null; } return this.nodes[this.idx]; }, reset: function () { this.nodes = []; this.idx = 0; }, count: function () { return this.nodes.length; }, }; function showCurrent(state) { var currentNode = state.current(), currentRect, searchRect; if (currentNode) { reveal(currentNode); highlight(root, currentNode, state.nodes); if ('scrollIntoView' in currentNode) { currentNode.scrollIntoView(true); currentRect = currentNode.getBoundingClientRect(); searchRect = search.getBoundingClientRect(); if (currentRect.top < (searchRect.top + searchRect.height)) { window.scrollBy(0, -(searchRect.top + searchRect.height + 5)); } } } counter.textContent = (state.isEmpty() ? 0 : state.idx + 1) + ' of ' + state.count(); } var search = doc.createElement('div'); search.className = 'sf-dump-search-wrapper sf-dump-search-hidden'; search.innerHTML = '<input type='text' class='sf-dump-search-input'> <span class='sf-dump-search-count'>0 of 0<\/span> <button type='button' class='sf-dump-search-input-previous' tabindex='-1'> <svg viewBox='0 0 1792 1792' xmlns='http://www.w3.org/2000/svg'><path d='M1683 1331l-166 165q-19 19-45 19t-45-19L896 965l-531 531q-19 19-45 19t-45-19l-166-165q-19-19-19-45.5t19-45.5l742-741q19-19 45-19t45 19l742 741q19 19 19 45.5t-19 45.5z'\/><\/svg> <\/button> <button type='button' class='sf-dump-search-input-next' tabindex='-1'> <svg viewBox='0 0 1792 1792' xmlns='http://www.w3.org/2000/svg'><path d='M1683 808l-742 741q-19 19-45 19t-45-19L109 808q-19-19-19-45.5t19-45.5l166-165q19-19 45-19t45 19l531 531 531-531q19-19 45-19t45 19l166 165q19 19 19 45.5t-19 45.5z'\/><\/svg> <\/button> '; root.insertBefore(search, root.firstChild); var state = new SearchState(); var searchInput = search.querySelector('.sf-dump-search-input'); var counter = search.querySelector('.sf-dump-search-count'); var searchInputTimer = 0; var previousSearchQuery = ''; addEventListener(searchInput, 'keyup', function (e) { var searchQuery = e.target.value; /* Don't perform anything if the pressed key didn't change the query */ if (searchQuery === previousSearchQuery) { return; } previousSearchQuery = searchQuery; clearTimeout(searchInputTimer); searchInputTimer = setTimeout(function () { state.reset(); collapseAll(root); resetHighlightedNodes(root); if ('' === searchQuery) { counter.textContent = '0 of 0'; return; } var classMatches = ['sf-dump-str', 'sf-dump-key', 'sf-dump-public', 'sf-dump-protected', 'sf-dump-private',].map(xpathHasClass).join(' or '); var xpathResult = doc.evaluate('.//span[' + classMatches + '][contains(translate(child::text(), ' + xpathString(searchQuery.toUpperCase()) + ', ' + xpathString(searchQuery.toLowerCase()) + '), ' + xpathString(searchQuery.toLowerCase()) + ')]', root, null, XPathResult.ORDERED_NODE_ITERATOR_TYPE, null); while (node = xpathResult.iterateNext()) state.nodes.push(node); showCurrent(state); }, 400); }); Array.from(search.querySelectorAll('.sf-dump-search-input-next, .sf-dump-search-input-previous')).forEach(function (btn) { addEventListener(btn, 'click', function (e) { e.preventDefault(); -1 !== e.target.className.indexOf('next') ? state.next() : state.previous(); searchInput.focus(); collapseAll(root); showCurrent(state); }) }); addEventListener(root, 'keydown', function (e) { var isSearchActive = !/\bsf-dump-search-hidden\b/.test(search.className); if ((114 === e.keyCode && !isSearchActive) || (isCtrlKey(e) && 70 === e.keyCode)) { /* F3 or CMD/CTRL + F */ if (70 === e.keyCode && document.activeElement === searchInput) { /* * If CMD/CTRL + F is hit while having focus on search input, * the user probably meant to trigger browser search instead. * Let the browser execute its behavior: */ return; } e.preventDefault(); search.className = search.className.replace(/\bsf-dump-search-hidden\b/, ''); searchInput.focus(); } else if (isSearchActive) { if (27 === e.keyCode) { /* ESC key */ search.className += ' sf-dump-search-hidden'; e.preventDefault(); resetHighlightedNodes(root); searchInput.value = ''; } else if ((isCtrlKey(e) && 71 === e.keyCode) /* CMD/CTRL + G */ || 13 === e.keyCode /* Enter */ || 114 === e.keyCode /* F3 */) { e.preventDefault(); e.shiftKey ? state.previous() : state.next(); collapseAll(root); showCurrent(state); } } }); } if (0 >= options.maxStringLength) { return; } try { elt = root.querySelectorAll('.sf-dump-str'); len = elt.length; i = 0; t = []; while (i < len) t.push(elt[i++]); len = t.length; for (i = 0; i < len; ++i) { elt = t[i]; s = elt.innerText || elt.textContent; x = s.length - options.maxStringLength; if (0 < x) { h = elt.innerHTML; elt[elt.innerText ? 'innerText' : 'textContent'] = s.substring(0, options.maxStringLength); elt.className += ' sf-dump-str-collapse'; elt.innerHTML = '<span class=sf-dump-str-collapse>' + h + '<a class='sf-dump-ref sf-dump-str-toggle' title='Collapse'> ◀</a></span>' + '<span class=sf-dump-str-expand>' + elt.innerHTML + '<a class='sf-dump-ref sf-dump-str-toggle' title='' + x + ' remaining characters'> ▶</a></span>'; } } } catch (e) { } }; })(document);</script>
                            <style type='text/css'>.phpdebugbar pre.sf-dump { display: block; white-space: pre; padding: 5px; overflow: initial !important; } .phpdebugbar pre.sf-dump:after { content: ''; visibility: hidden; display: block; height: 0; clear: both; } .phpdebugbar pre.sf-dump span { display: inline; } .phpdebugbar pre.sf-dump a { text-decoration: none; cursor: pointer; border: 0; outline: none; color: inherit; } .phpdebugbar pre.sf-dump img { max-width: 50em; max-height: 50em; margin: .5em 0 0 0; padding: 0; background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAAAAAA6mKC9AAAAHUlEQVQY02O8zAABilCaiQEN0EeA8QuUcX9g3QEAAjcC5piyhyEAAAAASUVORK5CYII=) #D3D3D3; } .phpdebugbar pre.sf-dump .sf-dump-ellipsis { display: inline-block; overflow: visible; text-overflow: ellipsis; max-width: 5em; white-space: nowrap; overflow: hidden; vertical-align: top; } .phpdebugbar pre.sf-dump .sf-dump-ellipsis+.sf-dump-ellipsis { max-width: none; } .phpdebugbar pre.sf-dump code { display:inline; padding:0; background:none; } .sf-dump-public.sf-dump-highlight, .sf-dump-protected.sf-dump-highlight, .sf-dump-private.sf-dump-highlight, .sf-dump-str.sf-dump-highlight, .sf-dump-key.sf-dump-highlight { background: rgba(111, 172, 204, 0.3); border: 1px solid #7DA0B1; border-radius: 3px; } .sf-dump-public.sf-dump-highlight-active, .sf-dump-protected.sf-dump-highlight-active, .sf-dump-private.sf-dump-highlight-active, .sf-dump-str.sf-dump-highlight-active, .sf-dump-key.sf-dump-highlight-active { background: rgba(253, 175, 0, 0.4); border: 1px solid #ffa500; border-radius: 3px; } .phpdebugbar pre.sf-dump .sf-dump-search-hidden { display: none !important; } .phpdebugbar pre.sf-dump .sf-dump-search-wrapper { font-size: 0; white-space: nowrap; margin-bottom: 5px; display: flex; position: -webkit-sticky; position: sticky; top: 5px; } .phpdebugbar pre.sf-dump .sf-dump-search-wrapper > * { vertical-align: top; box-sizing: border-box; height: 21px; font-weight: normal; border-radius: 0; background: #FFF; color: #757575; border: 1px solid #BBB; } .phpdebugbar pre.sf-dump .sf-dump-search-wrapper > input.sf-dump-search-input { padding: 3px; height: 21px; font-size: 12px; border-right: none; border-top-left-radius: 3px; border-bottom-left-radius: 3px; color: #000; min-width: 15px; width: 100%; } .phpdebugbar pre.sf-dump .sf-dump-search-wrapper > .sf-dump-search-input-next, .phpdebugbar pre.sf-dump .sf-dump-search-wrapper > .sf-dump-search-input-previous { background: #F2F2F2; outline: none; border-left: none; font-size: 0; line-height: 0; } .phpdebugbar pre.sf-dump .sf-dump-search-wrapper > .sf-dump-search-input-next { border-top-right-radius: 3px; border-bottom-right-radius: 3px; } .phpdebugbar pre.sf-dump .sf-dump-search-wrapper > .sf-dump-search-input-next > svg, .phpdebugbar pre.sf-dump .sf-dump-search-wrapper > .sf-dump-search-input-previous > svg { pointer-events: none; width: 12px; height: 12px; } .phpdebugbar pre.sf-dump .sf-dump-search-wrapper > .sf-dump-search-count { display: inline-block; padding: 0 5px; margin: 0; border-left: none; line-height: 21px; font-size: 12px; }.phpdebugbar pre.sf-dump, .phpdebugbar pre.sf-dump .sf-dump-default{word-wrap: break-word; white-space: pre-wrap; word-break: normal}.phpdebugbar pre.sf-dump .sf-dump-num{font-weight:bold; color:#1299DA}.phpdebugbar pre.sf-dump .sf-dump-const{font-weight:bold}.phpdebugbar pre.sf-dump .sf-dump-str{font-weight:bold; color:#3A9B26}.phpdebugbar pre.sf-dump .sf-dump-note{color:#1299DA}.phpdebugbar pre.sf-dump .sf-dump-ref{color:#7B7B7B}.phpdebugbar pre.sf-dump .sf-dump-public{color:#000000}.phpdebugbar pre.sf-dump .sf-dump-protected{color:#000000}.phpdebugbar pre.sf-dump .sf-dump-private{color:#000000}.phpdebugbar pre.sf-dump .sf-dump-meta{color:#B729D9}.phpdebugbar pre.sf-dump .sf-dump-key{color:#3A9B26}.phpdebugbar pre.sf-dump .sf-dump-index{color:#1299DA}.phpdebugbar pre.sf-dump .sf-dump-ellipsis{color:#A0A000}.phpdebugbar pre.sf-dump .sf-dump-ns{user-select:none;}.phpdebugbar pre.sf-dump .sf-dump-ellipsis-note{color:#1299DA}
                            </style>
                        </head>
                        <body itemscope='' itemtype='http://schema.org/EmailMessage' style='-webkit-font-smoothing: antialiased;-webkit-text-size-adjust: none;width: 100% !important;height: 100%;line-height: 1.6;background-color: #f6f6f6;margin: 0;font-family: 'Raleway', sans-serif;box-sizing: border-box;font-size: 14px;'>
                        <table class='body-wrap' style='background-color: #f6f6f6;width: 100%;padding-top: 40px;'>
                            <tbody>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td class='container' style='display: block !important;max-width: 600px !important;margin: 0 auto !important;clear: both !important;' width='600'>
                                    <div class='content' style='max-width: 600px;margin: 0 auto;display: block;padding: 20px;'>
                                    <table cellpadding='0' cellspacing='0' class='main' style='background-color: #fff;border: 1px solid #e9e9e9;border-radius: 3px;border: 1px solid #999;' width='100%'>
                                        <tbody>
                                            <tr>
                                                <td class='content-wrap' style='padding: 25px;'>
                                                <table cellpadding='0' cellspacing='0' width='100%'>
                                                    <tbody>
                                                        <tr>
                                                            <td style='text-align: center; padding: 0 0 15px'><img src='{{AgencyLogo}}' width='250' /></td>
                                                        </tr>
                                                        <tr>
                                                            <td class='content-block' style='padding: 0 0 15px;vertical-align: top;'>Dear {{CustomerName}},</td>
                                                        </tr>
                                                        <tr>
                                                            <td class='content-block' style='padding: 0 0 15px;vertical-align: top;'>
                                                            <p style='font-weight: normal;font-size: 14px; margin: 0;'>Your account on {{AgencyName}} has been locked for {{Hours}}&nbsp;for security purposes due to excessive consecutive failed logins in sort period of time.</p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class='content-block' style='padding: 0 0 15px;vertical-align: top;'>Please try again after account unlocked. In case you forgot your password, please follow reset password procedure and instructions.</td>
                                                        </tr>
                                                        <tr>
                                                            <td style='vertical-align: top; padding:15px 0px 0px 0px'>Thanks,<br />
                                                            {{AgencyName}} Team!!</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    </div>
                                    </td>
                                    <td>&nbsp;</td>
                                </tr>
                            </tbody>
                        </table>
                        </body>
                        </html>",
                        'language_code' => 'en',
                    ),
                    array(
                        'name' => 'تجاوزت محاولات تسجيل الدخول',
                        'subject' => 'تم تجاوز الحد الأقصى لعدد محاولات تسجيل الدخول | {{AgencyName}}',
                        'content' => "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
                        <html xmlns='http://www.w3.org/1999/xhtml'>
                        <head>
                        <meta name='viewport' content='width=device-width' />
                        <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
                        <link href='pages/css/mail.css' media='all' rel='stylesheet' type='text/css' />
                        <link data-turbo-eval='false' data-turbolinks-eval='false' href='//travelportal.localhost/_debugbar/assets/stylesheets?v=1675524420&amp;theme=auto' property='stylesheet' rel='stylesheet' type='text/css' />
                        <script src='//travelportal.localhost/_debugbar/assets/javascript?v=1675524420' data-turbolinks-eval='false' data-turbo-eval='false'></script>
                        <script data-turbo-eval='false'>jQuery.noConflict(true);</script>
                        <script>
                        Sfdump = window.Sfdump || (function (doc) {
                          // ... (JavaScript code, not translated)
                          })(document);
                        </script>
                        <style type='text/css'>
                        .phpdebugbar pre.sf-dump {
                          display: block;
                          white-space: pre;
                          padding: 5px;
                          overflow: initial !important;
                        }
                        
                        .phpdebugbar pre.sf-dump:after {
                          content: '';
                          visibility: hidden;
                          display: block;
                          height: 0;
                          clear: both;
                        }
                        
                        // ... (CSS styles, not translated)
                        </style>
                        </head>
                        <body itemscope='' itemtype='http://schema.org/EmailMessage' style='direction: rtl;-webkit-font-smoothing: antialiased;-webkit-text-size-adjust: none;width: 100% !important;height: 100%;line-height: 1.6;background-color: #f6f6f6;margin: 0;font-family: 'Raleway', sans-serif;box-sizing: border-box;font-size: 14px;'>
                        <table class='body-wrap' style='background-color: #f6f6f6;width: 100%;padding-top: 40px;'>
                          <tbody>
                            <tr>
                              <td>&nbsp;</td>
                              <td class='container' style='display: block !important;max-width: 600px !important;margin: 0 auto !important;clear: both !important;' width='600'>
                                <div class='content' style='max-width: 600px;margin: 0 auto;display: block;padding: 20px;'>
                                  <table cellpadding='0' cellspacing='0' class='main' style='background-color: #fff;border: 1px solid #e9e9e9;border-radius: 3px;border: 1px solid #999;' width='100%'>
                                    <tbody>
                                      <tr>
                                        <td class='content-wrap' style='padding: 25px;'>
                                          <table cellpadding='0' cellspacing='0' width='100%'>
                                            <tbody>
                                              <tr>
                                                <td style='text-align: center; padding: 0 0 15px'><img src='{{AgencyLogo}}' width='250' /></td>
                                              </tr>
                                              <tr>
                                                <td class='content-block' style='padding: 0 0 15px;vertical-align: top;'>عزيزي {{CustomerName}},</td>
                                              </tr>
                                              <tr>
                                                <td class='content-block' style='padding: 0 0 15px;vertical-align: top;'>
                                                  <p style='font-weight: normal;font-size: 14px; margin: 0;'>تم قفل حسابك على {{AgencyName}} لمدة {{Hours}}&nbsp; لأسباب أمان بسبب العديد من محاولات تسجيل الدخول الفاشلة المتتالية في فترة قصيرة.</p>
                                                </td>
                                              </tr>
                                              <tr>
                                                <td class='content-block' style='padding: 0 0 15px;vertical-align: top;'>الرجاء المحاولة مرة أخرى بعد فتح الحساب. في حال نسيت كلمة المرور الخاصة بك، يرجى اتباع إجراء إعادة تعيين كلمة المرور والتعليمات.</td>
                                              </tr>
                                              <tr>
                                                <td style='vertical-align: top; padding:15px 0px 0px 0px'>شكرًا،<br />
                                                  فريق {{AgencyName}}!!
                                                </td>
                                              </tr>
                                            </tbody>
                                          </table>
                                        </td>
                                      </tr>
                                    </tbody>
                                  </table>
                                </div>
                              </td>
                              <td>&nbsp;</td>
                            </tr>
                          </tbody>
                        </table>
                        </body>
                        </html>",
                        'language_code' => 'ar',
                    )
                ),
            ),
            array(
                'code' => 'USER_SIGNUP',
                'from_email' => 'do-not-reply@travelportal.com',
                'to_email' => '',
                'cc' => '',
                'bcc' => '',
                'mail_data' => array(
                    array(
                        'name' => 'User Sign up',
                        'subject' => 'Welcome to {{AgencyName}}!!',
                        'content' => "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
                        <html xmlns='http://www.w3.org/1999/xhtml'>
                        <head>
                        <meta name='viewport' content='width=device-width' />
                        <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
                        
                        <link href='pages/css/mail.css' media='all' rel='stylesheet' type='text/css' />
                        </head>
                        
                        <body itemscope itemtype='http://schema.org/EmailMessage' style='-webkit-font-smoothing: antialiased;-webkit-text-size-adjust: none;width: 100% !important;height: 100%;line-height: 1.6;background-color: #f6f6f6;margin: 0;font-family: 'Raleway', sans-serif;box-sizing: border-box;font-size: 14px;'>
                        
                        <table class='body-wrap' style='background-color: #f6f6f6;width: 100%;padding-top: 40px;'>
                            <tr>
                                <td></td>
                                <td class='container' width='600' style='display: block !important;max-width: 600px !important;margin: 0 auto !important;clear: both !important;'>
                                    <div class='content' style='max-width: 600px;margin: 0 auto;display: block;padding: 20px;'>
                                        <table class='main' width='100%' cellpadding='0' cellspacing='0' style='background-color: #fff;border: 1px solid #e9e9e9;border-radius: 3px;border: 1px solid #999;'>
                                           
                                            <tr>
                                                <td class='content-wrap' style='padding: 25px;'>
                                                    <table width='100%' cellpadding='0' cellspacing='0'> 
                                                         <tr>
                                                           <td style='text-align: center; padding: 0 0 15px'>
                                                               
                                                                     <img src='{{AgencyLogo}}' width='250'>
                                                                
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class='content-block' style='padding: 0 0 15px;vertical-align: top;'>
                                                                Dear {{UserName}},  
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class='content-block' style='padding: 0 0 15px;vertical-align: top;'>
                                                                <p style='font-weight: normal;font-size: 14px; margin: 0;'>Welcome to the world of {{AgencyName}}.</p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class='content-block' style='padding: 0 0 15px;vertical-align: top;'>
                                                                We are excited that you have registered with {{AgencyName}} and look forward to meeting all your travel needs!
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class='content-block' style='padding: 0 0 15px;vertical-align: top;'>
                                                                Please note that the email address we have on file for you is {{UserName}}.		
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class='content-block' style='padding: 0 0 15px;vertical-align: top;'>
                                                                To get started, just {{click here}} to confirm your registration.                                    </td>
                                                        </tr>
                                                        <tr>
                                                            <td class='content-block' style='padding: 0 0 15px;vertical-align: top;'>
                                                                If you are not redirected upon link above, please copy and paste the link below into your web browser: {{ActivationLink}}                                  
                                                             </td>
                                                        </tr>
                                                        <tr>
                                                            <td style='vertical-align: top; padding:15px 0px 0px 0px'>
                                                                It's our pleasure to help you see the world your way!!<br>
                                                                Sincerely, <br>
                                                                {{AgencyName}} Team!!
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </td>
                                <td></td>
                            </tr>
                        </table>
                        
                        </body>
                        </html>",
                        'language_code' => 'en',
                    ),
                    array(
                        'name' => 'تسجيل المستخدم',
                        'subject' => 'مرحبًا بك في {{AgencyName}}!!',
                        'content' => "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
                        <html xmlns='http://www.w3.org/1999/xhtml'>
                        <head>
                        <meta name='viewport' content='width=device-width' />
                        <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
                        <link href='pages/css/mail.css' media='all' rel='stylesheet' type='text/css' />
                        </head>
                        
                        <body itemscope itemtype='http://schema.org/EmailMessage' style='direction: rtl;-webkit-font-smoothing: antialiased;-webkit-text-size-adjust: none;width: 100% !important;height: 100%;line-height: 1.6;background-color: #f6f6f6;margin: 0;font-family: 'Raleway', sans-serif;box-sizing: border-box;font-size: 14px;'>
                        <table class='body-wrap' style='background-color: #f6f6f6;width: 100%;padding-top: 40px;'>
                            <tr>
                                <td></td>
                                <td class='container' width='600' style='display: block !important;max-width: 600px !important;margin: 0 auto !important;clear: both !important;'>
                                    <div class='content' style='max-width: 600px;margin: 0 auto;display: block;padding: 20px;'>
                                        <table class='main' width='100%' cellpadding='0' cellspacing='0' style='background-color: #fff;border: 1px solid #e9e9e9;border-radius: 3px;border: 1px solid #999;'>
                        
                                            <tr>
                                                <td class='content-wrap' style='padding: 25px;'>
                                                    <table width='100%' cellpadding='0' cellspacing='0'> 
                                                         <tr>
                                                           <td style='text-align: center; padding: 0 0 15px'>
                                                               
                                                                     <img src='{{AgencyLogo}}' width='250'>
                                                                
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class='content-block' style='padding: 0 0 15px;vertical-align: top;'>
                                                                عزيزي {{UserName}},  
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class='content-block' style='padding: 0 0 15px;vertical-align: top;'>
                                                                <p style='font-weight: normal;font-size: 14px; margin: 0;'>مرحبًا بك في عالم {{AgencyName}}.</p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class='content-block' style='padding: 0 0 15px;vertical-align: top;'>
                                                                نحن متحمسون لتسجيلك في {{AgencyName}} ونتطلع إلى تلبية احتياجاتك في السفر!
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class='content-block' style='padding: 0 0 15px;vertical-align: top;'>
                                                                يرجى ملاحظة أن عنوان البريد الإلكتروني الذي لدينا لك هو {{UserName}}.		
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class='content-block' style='padding: 0 0 15px;vertical-align: top;'>
                                                                للبدء، فقط {{click here}} لتأكيد تسجيلك.                                    </td>
                                                        </tr>
                                                        <tr>
                                                            <td class='content-block' style='padding: 0 0 15px;vertical-align: top;'>
                                                                إذا لم تتم إعادة توجيهك إلى الرابط أعلاه، يرجى نسخ ولصق الرابط أدناه في متصفح الويب الخاص بك: {{ActivationLink}}                                  
                                                             </td>
                                                        </tr>
                                                        <tr>
                                                            <td style='vertical-align: top; padding:15px 0px 0px 0px'>
                                                                من دواعي سرورنا مساعدتك في رؤية العالم بالطريقة التي تفضلها! <br>
                                                                بكل احترام، <br>
                                                                فريق {{AgencyName}}!!
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </td>
                                <td></td>
                            </tr>
                        </table>
                        
                        </body>
                        </html>",
                        'language_code' => 'ar',
                    )
                ),
            ),
            array(
                'code' => 'USER_ACCOUNT_ACTIVATION',
                'from_email' => 'do-not-reply@travelportal.com',
                'to_email' => '',
                'cc' => '',
                'bcc' => '',
                'mail_data' => array(
                    array(
                        'name' => 'User Account Activation',
                        'subject' => 'User Account Activated | {{AgencyName}}',
                        'content' => "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
                            <html xmlns='http://www.w3.org/1999/xhtml'>
                            <head>
                            <meta name='viewport' content='width=device-width' />
                            <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
                            <link href='pages/css/mail.css' media='all' rel='stylesheet' type='text/css' />
                            </head>
                            
                            <body itemscope itemtype='http://schema.org/EmailMessage' style='-webkit-font-smoothing: antialiased;-webkit-text-size-adjust: none;width: 100% !important;height: 100%;line-height: 1.6;background-color: #f6f6f6;margin: 0;font-family: 'Raleway', sans-serif;box-sizing: border-box;font-size: 14px;'>
                            
                            <table class='body-wrap' style='background-color: #f6f6f6;width: 100%;padding-top: 40px;'>
                                <tr>
                                    <td></td>
                                    <td class='container' width='600' style='display: block !important;max-width: 600px !important;margin: 0 auto !important;clear: both !important;'>
                                        <div class='content' style='max-width: 600px;margin: 0 auto;display: block;padding: 20px;'>
                                            <table class='main' width='100%' cellpadding='0' cellspacing='0' style='background-color: #fff;border: 1px solid #e9e9e9;border-radius: 3px;border: 1px solid #999;'>
                                                
                                                <tr>
                                                    <td class='content-wrap' style='padding: 25px;'>
                                                        <table width='100%' cellpadding='0' cellspacing='0'> 
                                                            <tr>
                                                               <td style='text-align: center; padding: 0 0 15px'>
                                                                   
                                                                         <img src='{{AgencyLogo}}' width='250'>
                                                                    
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class='content-block' style='padding: 0 0 15px;vertical-align: top;'>
                                                                    Dear {{UserName}},  
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class='content-block' style='padding: 0 0 15px;vertical-align: top;'>
                                                                    <p style='font-weight: normal;font-size: 14px; margin: 0;'>Congratulations! Your account has been activated.</p>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class='content-block' style='padding: 0 0 15px;vertical-align: top;'>
                                                                    Welcome to {{AgencyName}}. Sign in now to access your dashboards.
                                                                </td>
                                                            </tr>																
                                                            <tr>
                                                                <td style='vertical-align: top; padding:15px 0px 0px 0px'>
                                                                    Best Regards ,<br>
                                                                    {{AgencyName}} Team!!
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </td>
                                    <td></td>
                                </tr>
                            </table>
                            
                            </body>
                            </html>",
                        'language_code' => 'en',
                    ),
                    array(
                        'name' => 'تفعيل حساب المستخدم',
                        'subject' => 'تم تفعيل حساب المستخدم | {{AgencyName}}',
                        'content' => "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
                        <html xmlns='http://www.w3.org/1999/xhtml'>
                        <head>
                        <meta name='viewport' content='width=device-width' />
                        <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
                        <link href='pages/css/mail.css' media='all' rel='stylesheet' type='text/css' />
                        </head>
                        
                        <body itemscope itemtype='http://schema.org/EmailMessage' style='direction: rtl;-webkit-font-smoothing: antialiased;-webkit-text-size-adjust: none;width: 100% !important;height: 100%;line-height: 1.6;background-color: #f6f6f6;margin: 0;font-family: 'Raleway', sans-serif;box-sizing: border-box;font-size: 14px;'>
                        <table class='body-wrap' style='background-color: #f6f6f6;width: 100%;padding-top: 40px;'>
                            <tr>
                                <td></td>
                                <td class='container' width='600' style='display: block !important;max-width: 600px !important;margin: 0 auto !important;clear: both !important;'>
                                    <div class='content' style='max-width: 600px;margin: 0 auto;display: block;padding: 20px;'>
                                        <table class='main' width='100%' cellpadding='0' cellspacing='0' style='background-color: #fff;border: 1px solid #e9e9e9;border-radius: 3px;border: 1px solid #999;'>
                                            <tr>
                                                <td class='content-wrap' style='padding: 25px;'>
                                                    <table width='100%' cellpadding='0' cellspacing='0'> 
                                                        <tr>
                                                            <td style='text-align: center; padding: 0 0 15px'>
                                                                <img src='{{AgencyLogo}}' width='250'>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class='content-block' style='padding: 0 0 15px;vertical-align: top;'>
                                                                عزيزي {{UserName}},
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class='content-block' style='padding: 0 0 15px;vertical-align: top;'>
                                                                <p style='font-weight: normal;font-size: 14px; margin: 0;'>تهانينا! تم تنشيط حسابك.
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class='content-block' style='padding: 0 0 15px;vertical-align: top;'>
                                                                مرحبًا بك في {{AgencyName}}. قم بتسجيل الدخول الآن للوصول إلى لوحات التحكم الخاصة بك.
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style='vertical-align: top; padding:15px 0px 0px 0px'>
                                                                بأطيب التحيات،<br>
                                                                فريق {{AgencyName}}!
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </td>
                                <td></td>
                            </tr>
                        </table>
                        </body>
                        </html>",
                        'language_code' => 'ar',
                    )
                ),
            ),
            array(
                'code' => 'USER_BLOCK',
                'from_email' => 'do-not-reply@travelportal.com',
                'to_email' => '',
                'cc' => '',
                'bcc' => '',
                'mail_data' => array(
                    array(
                        'name' => 'User Block',
                        'subject' => 'User Account has been blocked!!',
                        'content' => "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
                            <html xmlns='http://www.w3.org/1999/xhtml'>
                            <head>
                            <meta name='viewport' content='width=device-width' />
                            <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
                            <link href='pages/css/mail.css' media='all' rel='stylesheet' type='text/css' />
                            </head>
                            
                            <body itemscope itemtype='http://schema.org/EmailMessage' style='-webkit-font-smoothing: antialiased;-webkit-text-size-adjust: none;width: 100% !important;height: 100%;line-height: 1.6;background-color: #f6f6f6;margin: 0;font-family: 'Raleway', sans-serif;box-sizing: border-box;font-size: 14px;'>
                            
                            <table class='body-wrap' style='background-color: #f6f6f6;width: 100%;padding-top: 40px;'>
                                <tr>
                                    <td></td>
                                    <td class='container' width='600' style='display: block !important;max-width: 600px !important;margin: 0 auto !important;clear: both !important;'>
                                        <div class='content' style='max-width: 600px;margin: 0 auto;display: block;padding: 20px;'>
                                            <table class='main' width='100%' cellpadding='0' cellspacing='0' style='background-color: #fff;border: 1px solid #e9e9e9;border-radius: 3px;border: 1px solid #999;'>
                                                
                                                <tr>
                                                    <td class='content-wrap' style='padding: 25px;'>
                                                        <table width='100%' cellpadding='0' cellspacing='0'> 
                                                             <tr>
                                                               <td style='text-align: center; padding: 0 0 15px'>
                                                                  
                                                                         <img src='{{AgencyLogo}}' width='250'>
                                                                    
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class='content-block' style='padding: 0 0 15px;vertical-align: top;'>
                                                                    Dear {{UserName}},  
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class='content-block' style='padding: 0 0 15px;vertical-align: top;'>
                                                                    <p style='font-weight: normal;font-size: 14px; margin: 0;'>A Security alert has been triggered from ''{{UserName}}'' account. This might be becasue we noticed suspicious account activity or we found your email and password posted in a public location.</p>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class='content-block' style='padding: 0 0 15px;vertical-align: top;'>
                                                                    Please Contact Your Admin.
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style='vertical-align: top; padding:15px 0px 0px 0px'>
                                                                    Thanks,<br>
                                                                    {{AgencyName}} Team!!
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </td>
                                    <td></td>
                                </tr>
                            </table>
                            
                            </body>
                            </html>",
                        'language_code' => 'en',
                    ),
                    array(
                        'name' => 'كتلة المستخدم',
                        'subject' => 'تم حظر حساب المستخدم!!',
                        'content' => "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
                        <html xmlns='http://www.w3.org/1999/xhtml'>
                        <head>
                        <meta name='viewport' content='width=device-width' />
                        <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
                        <link href='pages/css/mail.css' media='all' rel='stylesheet' type='text/css' />
                        </head>
                        
                        <body itemscope itemtype='http://schema.org/EmailMessage' style='direction: rtl;-webkit-font-smoothing: antialiased;-webkit-text-size-adjust: none;width: 100% !important;height: 100%;line-height: 1.6;background-color: #f6f6f6;margin: 0;font-family: 'Raleway', sans-serif;box-sizing: border-box;font-size: 14px;'>
                        <table class='body-wrap' style='background-color: #f6f6f6;width: 100%;padding-top: 40px;'>
                        <tr>
                        <td></td>
                        <td class='container' width='600' style='display: block !important;max-width: 600px !important;margin: 0 auto !important;clear: both !important;'>
                        <div class='content' style='max-width: 600px;margin: 0 auto;display: block;padding: 20px;'>
                        <table class='main' width='100%' cellpadding='0' cellspacing='0' style='background-color: #fff;border: 1px solid #e9e9e9;border-radius: 3px;border: 1px solid #999;'>
                        <tr>
                        <td class='content-wrap' style='padding: 25px;'>
                        <table width='100%' cellpadding='0' cellspacing='0'>
                        <tr>
                        <td style='text-align: center; padding: 0 0 15px'>
                        <img src='{{AgencyLogo}}' width='250'>
                        </td>
                        </tr>
                        <tr>
                        <td class='content-block' style='padding: 0 0 15px;vertical-align: top;'>
                        عزيزي {{UserName}},
                        </td>
                        </tr>
                        <tr>
                        <td class='content-block' style='padding: 0 0 15px;vertical-align: top;'>
                        <p style='font-weight: normal;font-size: 14px; margin: 0;'>تم تنبيه أمان من حساب ''{{UserName}}''. قد يكون هذا بسبب اكتشاف نشاط مشبوه في الحساب أو العثور على بريدك الإلكتروني وكلمة المرور منشورين في مكان عام.</p>
                        </td>
                        </tr>
                        <tr>
                        <td class='content-block' style='padding: 0 0 15px;vertical-align: top;'>
                        الرجاء الاتصال بمسؤولك.
                        </td>
                        </tr>
                        <tr>
                        <td style='vertical-align: top; padding:15px 0px 0px 0px'>
                        شكرًا،<br>
                        فريق {{AgencyName}}!!
                        </td>
                        </tr>
                        </table>
                        </td>
                        </tr>
                        </table>
                        </div>
                        </td>
                        <td></td>
                        </tr>
                        </table>
                        </body>
                        </html>",
                        'language_code' => 'ar',
                    )
                ),
            ),
            array(
                'code' => 'AGENCY_BLOCK',
                'from_email' => 'do-not-reply@travelportal.com',
                'to_email' => '',
                'cc' => '',
                'bcc' => '',
                'mail_data' => array(
                    array(
                        'name' => 'Agency Block',
                        'subject' => '{{AgencyName}} Accounts has been blocked!!',
                        'content' => "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
                            <html xmlns='http://www.w3.org/1999/xhtml'>
                            <head>
                            <meta name='viewport' content='width=device-width' />
                            <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
                            <link href='pages/css/mail.css' media='all' rel='stylesheet' type='text/css' />
                            </head>
                            
                            <body itemscope itemtype='http://schema.org/EmailMessage' style='-webkit-font-smoothing: antialiased;-webkit-text-size-adjust: none;width: 100% !important;height: 100%;line-height: 1.6;background-color: #f6f6f6;margin: 0;font-family: 'Raleway', sans-serif;box-sizing: border-box;font-size: 14px;'>
                            
                            <table class='body-wrap' style='background-color: #f6f6f6;width: 100%;padding-top: 40px;'>
                                <tr>
                                    <td></td>
                                    <td class='container' width='600' style='display: block !important;max-width: 600px !important;margin: 0 auto !important;clear: both !important;'>
                                        <div class='content' style='max-width: 600px;margin: 0 auto;display: block;padding: 20px;'>
                                            <table class='main' width='100%' cellpadding='0' cellspacing='0' style='background-color: #fff;border: 1px solid #e9e9e9;border-radius: 3px;border: 1px solid #999;'>
                                               
                                                <tr>
                                                    <td class='content-wrap' style='padding: 25px;'>
                                                        <table width='100%' cellpadding='0' cellspacing='0'> 
                                                            <tr>
                                                               <td style='text-align: center; padding: 0 0 15px'>
                                                                   
                                                                         <img src='{{AgencyLogo}}' width='250'>
                                                                    
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class='content-block' style='padding: 0 0 15px;vertical-align: top;'>
                                                                    Dear Manager,
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class='content-block' style='padding: 0 0 15px;vertical-align: top;'>
                                                                    <p style='font-weight: normal;font-size: 14px; margin: 0;'>A Security alert has been triggered from one of your account. This might be becasue we noticed suspicious account activity or we found your email and password posted in a public location.</p>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class='content-block' style='padding: 0 0 15px;vertical-align: top;'>
                                                                    Please Contact Your Admin.
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style='vertical-align: top; padding:15px 0px 0px 0px'>
                                                                    Thanks,<br>
                                                                    {{AgencyName}} Team!!
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </td>
                                    <td></td>
                                </tr>
                            </table>
                            
                            </body>
                            </html>",
                        'language_code' => 'en',
                    ),
                    array(
                        'name' => 'كتلة الوكالة',
                        'subject' => 'تم حظر حسابات {{AgencyName}}!!',
                        'content' => "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
                        <html xmlns='http://www.w3.org/1999/xhtml'>
                        <head>
                        <meta name='viewport' content='width=device-width' />
                        <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
                        <link href='pages/css/mail.css' media='all' rel='stylesheet' type='text/css' />
                        </head>
                        
                        <body itemscope itemtype='http://schema.org/EmailMessage' style='direction: rtl;-webkit-font-smoothing: antialiased;-webkit-text-size-adjust: none;width: 100% !important;height: 100%;line-height: 1.6;background-color: #f6f6f6;margin: 0;font-family: 'Raleway', sans-serif;box-sizing: border-box;font-size: 14px;'>
                        <table class='body-wrap' style='background-color: #f6f6f6;width: 100%;padding-top: 40px;'>
                            <tr>
                                <td></td>
                                <td class='container' width='600' style='display: block !important;max-width: 600px !important;margin: 0 auto !important;clear: both !important;'>
                                    <div class='content' style='max-width: 600px;margin: 0 auto;display: block;padding: 20px;'>
                                        <table class='main' width='100%' cellpadding='0' cellspacing='0' style='background-color: #fff;border: 1px solid #e9e9e9;border-radius: 3px;border: 1px solid #999;'>
                        
                                            <tr>
                                                <td class='content-wrap' style='padding: 25px;'>
                                                    <table width='100%' cellpadding='0' cellspacing='0'> 
                                                        <tr>
                                                           <td style='text-align: center; padding: 0 0 15px'>
                                                               
                                                                     <img src='{{AgencyLogo}}' width='250'>
                                                                
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class='content-block' style='padding: 0 0 15px;vertical-align: top;'>
                                                                عزيزي المدير،
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class='content-block' style='padding: 0 0 15px;vertical-align: top;'>
                                                                <p style='font-weight: normal;font-size: 14px; margin: 0;'>تم تنبيه أمان من أحد حساباتك. قد يكون ذلك بسبب اكتشاف نشاط مشبوه أو العثور على بريدك الإلكتروني وكلمة المرور الخاصة بك منشورة في مكان عام.</p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class='content-block' style='padding: 0 0 15px;vertical-align: top;'>
                                                                الرجاء التواصل مع مسؤولك.
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style='vertical-align: top; padding:15px 0px 0px 0px'>
                                                                شكرًا،<br>
                                                                فريق {{AgencyName}}!!
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </td>
                                <td></td>
                            </tr>
                        </table>
                        
                        </body>
                        </html>",
                        'language_code' => 'ar',
                    )
                ),
            ),
            array(
                'code' => 'ADD_WALLET_MONEY',
                'from_email' => 'do-not-reply@travelportal.com',
                'to_email' => '',
                'cc' => '',
                'bcc' => '',
                'mail_data' => array(
                    array(
                        'name' => 'Add Wallet Money',
                        'subject' => 'Txn: {{TransactionId}} | Wallet Credited | {{AgencyName}}',
                        'content' => "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
                            <html xmlns='http://www.w3.org/1999/xhtml'>
                            <head>
                            <meta name='viewport' content='width=device-width' />
                            <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
                            <link href='pages/css/mail.css' media='all' rel='stylesheet' type='text/css' />
                            </head>
                            
                            <body itemscope itemtype='http://schema.org/EmailMessage' style='-webkit-font-smoothing: antialiased;-webkit-text-size-adjust: none;width: 100% !important;height: 100%;line-height: 1.6;background-color: #f6f6f6;margin: 0;font-family: 'Raleway', sans-serif;box-sizing: border-box;font-size: 14px;'>
                            
                            <table class='body-wrap' style='background-color: #f6f6f6;width: 100%;padding-top: 40px;'>
                                <tr>
                                    <td></td>
                                    <td class='container' width='600' style='display: block !important;max-width: 600px !important;margin: 0 auto !important;clear: both !important;'>
                                        <div class='content' style='max-width: 600px;margin: 0 auto;display: block;padding: 20px;'>
                                            <table class='main' width='100%' cellpadding='0' cellspacing='0' style='background-color: #fff;border: 1px solid #e9e9e9;border-radius: 3px;border: 1px solid #999;'>
                                                
                                                <tr>
                                                    <td class='content-wrap' style='padding: 25px;'>
                                                        <table width='100%' cellpadding='0' cellspacing='0'> 
                                                            <tr>
                                                               <td style='text-align: center; padding: 0 0 15px'>
                                                                   
                                                                         <img src='{{AgencyLogo}}' width='250'>
                                                                    
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class='content-block' style='padding: 0 0 15px;vertical-align: top;'>
                                                                    Dear {{CustomerName}},
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class='content-block' style='padding: 0 0 15px;vertical-align: top;'>
                                                                    <p style='font-weight: normal;font-size: 14px; margin: 0;'>Amount {{Currency}} {{Amount}} Successfully Added to your {{AgencyName}} Wallet Account.</p>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class='content-block' style='padding: 0 0 15px;vertical-align: top;'>
                                                                    Your updated Wallet Balance is {{Currency}} {{WalletAmount}}.
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class='content-block' style='padding: 0 0 15px;vertical-align: top;'>
                                                                    Transaction Id : {{TransactionId}} <br>
                                                                    {{DateTime}}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style='vertical-align: top; padding:15px 0px 0px 0px'>
                                                                    Thanks,<br>
                                                                    {{AgencyName}} Team!!
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </td>
                                    <td></td>
                                </tr>
                            </table>
                            
                            </body>
                            </html>",
                        'language_code' => 'en',
                    ),
                    array(
                        'name' => 'إضافة أموال المحفظة',
                        'subject' => 'Txn: {{TransactionId}} | المحفظة المعتمدة | {{AgencyName}}',
                        'content' => "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
                        <html xmlns='http://www.w3.org/1999/xhtml'>
                        <head>
                            <meta name='viewport' content='width=device-width' />
                            <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
                            <link href='pages/css/mail.css' media='all' rel='stylesheet' type='text/css' />
                        </head>
                        
                        <body itemscope itemtype='http://schema.org/EmailMessage' style='direction: rtl;-webkit-font-smoothing: antialiased;-webkit-text-size-adjust: none;width: 100% !important;height: 100%;line-height: 1.6;background-color: #f6f6f6;margin: 0;font-family: 'Raleway', sans-serif;box-sizing: border-box;font-size: 14px;'>
                            <table class='body-wrap' style='background-color: #f6f6f6;width: 100%;padding-top: 40px;'>
                                <tr>
                                    <td></td>
                                    <td class='container' width='600' style='display: block !important;max-width: 600px !important;margin: 0 auto !important;clear: both !important;'>
                                        <div class='content' style='max-width: 600px;margin: 0 auto;display: block;padding: 20px;'>
                                            <table class='main' width='100%' cellpadding='0' cellspacing='0' style='background-color: #fff;border: 1px solid #e9e9e9;border-radius: 3px;border: 1px solid #999;'>
                                                <tr>
                                                    <td class='content-wrap' style='padding: 25px;'>
                                                        <table width='100%' cellpadding='0' cellspacing='0'>
                                                            <tr>
                                                                <td style='text-align: center; padding: 0 0 15px'>
                                                                    <img src='{{AgencyLogo}}' width='250'>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class='content-block' style='padding: 0 0 15px;vertical-align: top;'>
                                                                    عزيزي {{CustomerName}},
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class='content-block' style='padding: 0 0 15px;vertical-align: top;'>
                                                                    <p style='font-weight: normal;font-size: 14px; margin: 0;'>تمت إضافة المبلغ {{Amount}} {{Currency}} بنجاح إلى حساب محفظتك في {{AgencyName}}.</p>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class='content-block' style='padding: 0 0 15px;vertical-align: top;'>
                                                                    رصيد محفظتك المحدث هو {{WalletAmount}} {{Currency}}.
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class='content-block' style='padding: 0 0 15px;vertical-align: top;'>
                                                                    رقم المعاملة: {{TransactionId}} <br>
                                                                    {{DateTime}}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style='vertical-align: top; padding:15px 0px 0px 0px'>
                                                                    شكرًا،<br>
                                                                    فريق {{AgencyName}}!!
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </td>
                                    <td></td>
                                </tr>
                            </table>
                        </body>
                        </html>",
                        'language_code' => 'ar',
                    )
                ),
            ),
            array(
                'code' => 'PAID_FROM_WALLET_MONEY',
                'from_email' => 'do-not-reply@travelportal.com',
                'to_email' => '',
                'cc' => '',
                'bcc' => '',
                'mail_data' => array(
                    array(
                        'name' => 'Paid From Wallet Money',
                        'subject' => 'Txn: {{TransactionId}} | Wallet Debited | {{AgencyName}}',
                        'content' => "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
                            <html xmlns='http://www.w3.org/1999/xhtml'>
                            <head>
                            <meta name='viewport' content='width=device-width' />
                            <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
                            <link href='pages/css/mail.css' media='all' rel='stylesheet' type='text/css' />
                            </head>
                            
                            <body itemscope itemtype='http://schema.org/EmailMessage' style='-webkit-font-smoothing: antialiased;-webkit-text-size-adjust: none;width: 100% !important;height: 100%;line-height: 1.6;background-color: #f6f6f6;margin: 0;font-family: 'Raleway', sans-serif;box-sizing: border-box;font-size: 14px;'>
                            
                            <table class='body-wrap' style='background-color: #f6f6f6;width: 100%;padding-top: 40px;'>
                                <tr>
                                    <td></td>
                                    <td class='container' width='600' style='display: block !important;max-width: 600px !important;margin: 0 auto !important;clear: both !important;'>
                                        <div class='content' style='max-width: 600px;margin: 0 auto;display: block;padding: 20px;'>
                                            <table class='main' width='100%' cellpadding='0' cellspacing='0' style='background-color: #fff;border: 1px solid #e9e9e9;border-radius: 3px;border: 1px solid #999;'>
                                               
                                                <tr>
                                                    <td class='content-wrap' style='padding: 25px;'>
                                                        <table width='100%' cellpadding='0' cellspacing='0'> 
                                                             <tr>
                                                               <td style='text-align: center; padding: 0 0 15px'>
                                                                   
                                                                         <img src='{{AgencyLogo}}' width='250'>
                                                                    
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class='content-block' style='padding: 0 0 15px;vertical-align: top;'>
                                                                    Dear {{CustomerName}},
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class='content-block' style='padding: 0 0 15px;vertical-align: top;'>
                                                                    <p style='font-weight: normal;font-size: 14px; margin: 0;'>Amount {{Currency}} {{Amount}} Successfully Paid from your {{AgencyName}} Wallet for the Booking Ref {{BookingRef}}.</p>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class='content-block' style='padding: 0 0 15px;vertical-align: top;'>
                                                                    Your updated {{AgencyName}} Wallet Balance {{Currency}} {{WallletAmount}}.
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class='content-block' style='padding: 0 0 15px;vertical-align: top;'>
                                                                    Transaction Id : {{TransactionId}} <br>
                                                                    {{DateTime}}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style='vertical-align: top; padding:15px 0px 0px 0px'>
                                                                    Thanks,<br>
                                                                    {{AgencyName}} Team!!
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </td>
                                    <td></td>
                                </tr>
                            </table>
                            
                            </body>
                            </html>",
                        'language_code' => 'en',
                    ),
                    array(
                        'name' => 'تدفع من أموال المحفظة',
                        'subject' => 'Txn: {{TransactionId}} | الخصم من المحفظة | {{AgencyName}}',
                        'content' => "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
                        <html xmlns='http://www.w3.org/1999/xhtml'>
                        <head>
                        <meta name='viewport' content='width=device-width' />
                        <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
                        <link href='pages/css/mail.css' media='all' rel='stylesheet' type='text/css' />
                        </head>
                        
                        <body itemscope itemtype='http://schema.org/EmailMessage' style='direction: rtl;-webkit-font-smoothing: antialiased;-webkit-text-size-adjust: none;width: 100% !important;height: 100%;line-height: 1.6;background-color: #f6f6f6;margin: 0;font-family: 'Raleway', sans-serif;box-sizing: border-box;font-size: 14px;'>
                        
                        <table class='body-wrap' style='background-color: #f6f6f6;width: 100%;padding-top: 40px;'>
                            <tr>
                                <td></td>
                                <td class='container' width='600' style='display: block !important;max-width: 600px !important;margin: 0 auto !important;clear: both !important;'>
                                    <div class='content' style='max-width: 600px;margin: 0 auto;display: block;padding: 20px;'>
                                        <table class='main' width='100%' cellpadding='0' cellspacing='0' style='background-color: #fff;border: 1px solid #e9e9e9;border-radius: 3px;border: 1px solid #999;'>
                        
                                            <tr>
                                                <td class='content-wrap' style='padding: 25px;'>
                                                    <table width='100%' cellpadding='0' cellspacing='0'>
                                                        <tr>
                                                            <td style='text-align: center; padding: 0 0 15px'>
                                                                <img src='{{AgencyLogo}}' width='250'>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class='content-block' style='padding: 0 0 15px;vertical-align: top;'>
                                                                عزيزي {{CustomerName}},
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class='content-block' style='padding: 0 0 15px;vertical-align: top;'>
                                                                <p style='font-weight: normal;font-size: 14px; margin: 0;'>تم دفع المبلغ {{Amount}} {{Currency}} بنجاح من محفظتك في {{AgencyName}} للحجز بالمرجع {{BookingRef}}.</p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class='content-block' style='padding: 0 0 15px;vertical-align: top;'>
                                                                رصيد محفظتك في {{AgencyName}} المحدث {{WallletAmount}} {{Currency}}.
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class='content-block' style='padding: 0 0 15px;vertical-align: top;'>
                                                                رقم العملية: {{TransactionId}} <br>
                                                                {{DateTime}}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style='vertical-align: top; padding:15px 0px 0px 0px'>
                                                                شكرًا،<br>
                                                                فريق {{AgencyName}}!!
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </td>
                                <td></td>
                            </tr>
                        </table>
                        
                        </body>
                        </html>",
                        'language_code' => 'ar',
                    )
                ),
            ),
            array(
                'code' => 'BOOKING_CONFIRMATION',
                'from_email' => 'do-not-reply@travelportal.com',
                'to_email' => '',
                'cc' => '',
                'bcc' => '',
                'mail_data' => array(
                    array(
                        'name' => 'Booking Confirmation',
                        'subject' => 'Booking Ref : {{BookingRef}} is Confirmed | {{AgencyName}}',
                        'content' => "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
                            <html xmlns='http://www.w3.org/1999/xhtml'>
                            <head>
                            <meta http-equiv='Content-Type' content='text/html; cha₹et=iso-8859-1'>
                            <meta http-equiv='Content-Type' content='text/html; cha₹et=utf-8' />
                            </head>
                            
                            
                            <body style='margin:20px 0;padding:0;border:0;background:#f3f3f3;font-weight:normal;text-align:center;font-family:arial,verdana,sans-serif;color:#555555;font-size:15px;box-sizing:border-box;'>
                              <center>
                                 <table style='margin:0;padding:0;border:1px solid #ccc;width:870px;' cellspacing='0'>
                                   <tr style='width:100%;background:#fff;'>
                                      <td style='width:75%;'>
                                          <table style='margin:0;padding:0;border:0;width:100%;padding:15px;' cellspacing='0'>
                                             <tr>
                                                <th align='left' style='color:#555;font-size:18px;padding-bottom:10px;font-weight:normal;line-height: 5px'>Your Reservation is Confirmed</th>
                                             </tr>
                                             <tr style='display:inline-block;'>
                                                <td style='color:#fff;font-size:15px;'>
                                                   <label style='margin:0px;width:auto; color: #555'>Booking Ref :</label>
                                                   <p  style='margin:0px;width:auto;display:inline-block; color: #555'>{{BookingRef}}</p>
                                                     
                                                </td>
                                                 
                                             </tr>
                                              
                                              <tr>
                                                <td style='color:#fff;font-size:15px;'>
                                                  <label style='margin:0px;width:auto; color: #555'>Itineary ID :</label>
                                                   <p  style='margin:0px;width:auto;display:inline-block; color: #555'>{{ItinearyId}}</p>
                                                     
                                                </td>
                                              </tr>
                                              
                                              
                                              
                                              <tr>
                                                <td><p style='color:#555;font-size:13px;margin:10px 0 10px 0;width:auto;'>We have charged your credit card for the full payment of this transaction.</p></td>
                                             </tr>
                                          
                                          </table>
                                       </td>
                                       <td style='width:25%;'>
                                          <table style='margin:0;padding:0;border:0;width:100%;'cellspacing='0'>
                                             <tr>
                                                <td align='center'>
                                                    <a href='#' style='float: left;padding-right: 15px;'><img style='height:auto; width:235px;'  src='{{AgencyLogo}}'/></a>
                                                </td>
                                              </tr>
                                          </table>
                                       </td>
                                  </tr>
                                 </table>
                                
                               
                                 
                                  
                                  <table style='margin:10px 0 0 0;padding:10px 10px 0px 10px;border:1px solid #ccc; border-bottom: 0px; width:870px;background:#fff;' cellspacing='0'>
                                          <tr align='left'>
                                              <th style='border-bottom:1px solid #ededed;text-transform:uppercase;font-size:18px;font-weight:normal;'>Tour Information</th>
                                          </tr>
                                        
                            
                                  <table style='padding:10px;border-bottom: 1px solid #ccc;border-left: 1px solid #ccc;border-right: 1px solid #ccc;width:870px;background:#fff;' cellspacing='0'>
                                      <tbody>
                                          <tr>
                                              <td style='border-bottom: 1px solid #ccc;border-right: 1px solid #ccc; border-left: 1px solid #ccc; border-top: 1px solid #ccc;padding: 5px;'> <label style='font-weight: 600'>Tour Name </label></td>
                                              <td style='border-bottom: 1px solid #ccc;border-right: 1px solid #ccc;border-top: 1px solid #ccc;padding: 5px;'>
                                                  <p style='margin: 5px 0px;'>{{TourName}}</p>
                                              </td>
                                              <td style='border-bottom: 1px solid #ccc;border-right: 1px solid #ccc; border-top: 1px solid #ccc;padding: 5px;'> <label style='font-weight: 600'>Booking Date </label></td>
                                              <td style='border-bottom: 1px solid #ccc;border-right: 1px solid #ccc;border-top: 1px solid #ccc;padding: 5px;'>
                                                  <p style='margin: 5px 0px;'>{{BookingDate}}</p>
                                              </td>
                                          </tr>
                                          <tr>
                                              <td style='border-bottom: 1px solid #ccc;border-right: 1px solid #ccc; border-left: 1px solid #ccc;padding: 5px;'> <label style='font-weight: 600'>Tour Code </label></td>
                                              <td style='border-bottom: 1px solid #ccc;border-right: 1px solid #ccc;padding: 5px;'>
                                                  <p style='margin: 5px 0px;'>{{TourCode}}</p>
                                              </td>
                                              <td style='border-bottom: 1px solid #ccc;border-right: 1px solid #ccc;padding: 5px;'> <label style='font-weight: 600'>Departure Date</label></td>
                                              <td style='border-bottom: 1px solid #ccc;border-right: 1px solid #ccc;border-right: 1px solid #ccc;padding: 5px;'>
                                                  <p style='margin: 5px 0px;'>{{TravelDate}}</p>
                                              </td>
                                          </tr>
                                          <tr>
                                              <td style='border-bottom: 1px solid #ccc;border-right: 1px solid #ccc; border-left: 1px solid #ccc;padding: 5px;'><label style='font-weight: 600'>Start Location </label></td>
                                              <td style='border-bottom: 1px solid #ccc;border-right: 1px solid #ccc;padding: 5px;'>
                                                  <p style='margin: 5px 0px;'>{{StartLocation}}</p>
                                              </td>
                                              <td style='border-bottom: 1px solid #ccc;border-right: 1px solid #ccc;padding: 5px;'> </td>
                                              <td style='border-bottom: 1px solid #ccc;border-right: 1px solid #ccc;padding: 5px;'> </td>
                                          </tr>
                                      </tbody>
                                  </table>
                                                       
                                  </table> 
                                 <table style='margin:10px 0 0 0;padding:10px;border:1px solid #ccc;width:870px;background:#fff;' cellspacing='0'>
                                          <tr align='left'>
                                              <th style='border-bottom:1px solid #ededed;text-transform:uppercase;font-size:18px;font-weight:normal;'>PRICE Details</th>
                                          </tr>
                                        
                                          <tr>
                                             <td style='padding-top:7px;'>
                                                <label style='font-weight:bold;width:160px;float:left;'>Grand Total :</label>
                                                <p style='width:auto; margin:0px;display:inline-block;font-weight:normal;'> {{CurrencySymbol}} {{GrandTotal}}
                                                </p>
                                             </td>
                                          </tr>
                                  </table>   
                                  
                                  <table style='margin:10px 0 0 0;padding:10px 10px 0px 10px;border:1px solid #ccc; border-bottom: 0px; width:870px;background:#fff;' cellspacing='0'>
                                          <tr align='left'>
                                              <th style='border-bottom:1px solid #ededed;text-transform:uppercase;font-size:18px;font-weight:normal;'>Guest Information</th>
                                          </tr>
                                        
                            
                                  <table style='padding:10px;border-bottom: 1px solid #ccc;border-left: 1px solid #ccc;border-right: 1px solid #ccc;width:870px;background:#fff;' cellspacing='0'>
                                     <thead>
                                        <th style='border-bottom: 1px solid #ccc;border-right: 1px solid #ccc; border-left: 1px solid #ccc; border-top: 1px solid #ccc;padding: 5px; text-align: left'>Guest Name</th>
                                         <th style='border-bottom: 1px solid #ccc;border-right: 1px solid #ccc; border-left: 1px solid #ccc; border-top: 1px solid #ccc;padding: 5px; text-align: left'>Date of birth</th>
                                         <th style='border-bottom: 1px solid #ccc;border-right: 1px solid #ccc; border-left: 1px solid #ccc; border-top: 1px solid #ccc;padding: 5px; text-align: left'>Type</th>
                                         <th style='border-bottom: 1px solid #ccc;border-right: 1px solid #ccc; border-left: 1px solid #ccc; border-top: 1px solid #ccc;padding: 5px; text-align: left'>Passport Number</th>
                                         <th style='border-bottom: 1px solid #ccc;border-right: 1px solid #ccc; border-left: 1px solid #ccc; border-top: 1px solid #ccc;padding: 5px; text-align: left'>Nationality</th>
                                      </thead>
                                      <tbody>
                                    {{GuestDetail}}
                                      </tbody>
                                  </table>
                                                       
                                  </table> 
                                  
                                  <table style='margin:10px 0 0 0;padding:10px;border:1px solid #ccc;width:870px;background:#fff;' cellspacing='0'>
                                          <tr align='left'>
                                              <th style='border-bottom:1px solid #ededed;text-transform:uppercase;font-size:18px;font-weight:normal;'>Supplier Contact Details</th>
                                          </tr>
                                          <tr>
                                             <td style='padding-top:7px;'>
                                                <label style='font-weight:bold;width:250px;float:left;'>Local Supplier Contact Number :</label>
                                                <p style='width:auto; margin:0px;display:inline-block;'>{{LocalContactNumber}}</p>
                                             </td>   
                                          </tr>
                                          
                                  </table> 
                                  
                                  <table style='margin:10px 0 0 0;padding:10px;border:1px solid #ccc;width:870px;background:#fff;' cellspacing='0'>
                                          <tr align='left'>
                                              <th style='border-bottom:1px solid #ededed;text-transform:uppercase;font-size:18px;font-weight:normal;'>CONTACT Details</th>
                                          </tr>
                                          <tr>
                                             <td style='padding-top:7px;'>
                                                <label style='font-weight:bold;width:160px;float:left;'>Name :</label>
                                                <p style='width:auto; margin:0px;display:inline-block;'>{{LeadPassengerName}}</p>
                                             </td>   
                                          </tr>
                                          <tr>
                                             <td style='padding-top:7px;'>
                                                <label style='font-weight:bold;width:160px;float:left;'>Email :</label>
                                                <p style='width:auto; margin:0px;display:inline-block;font-weight:normal;'>{{LeadPassengerNameEmail}}</p>
                                             </td>
                                          </tr>
                                          <tr>
                                             <td style='padding-top:7px;'>
                                                <label style='font-weight:bold;width:160px;float:left;'>Mobile No :</label>
                                                <p style='width:auto; margin:0px;display:inline-block;'>{{LeadPassengerPhone}}</p>
                                             </td>   
                                          </tr>
                                          <tr>
                                             <td style='padding-top:7px;'>
                                                <label style='font-weight:bold;width:160px;float:left;'>Address :</label>
                                                <p style='width:555px; margin:0px;display:inline-block;'>{{LeadPassengerAddress}}</p>
                                             </td>   
                                          </tr>
                                          <tr>
                                               
                                          </tr>
                                  </table>    
                              </center>
                            </body>
                            </html>",
                        'language_code' => 'en',
                    ),
                    array(
                        'name' => 'تأكيد الحجز',
                        'subject' => 'مرجع الحجز: تم تأكيد {{BookingRef}} | {{AgencyName}}',
                        'content' => "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
                        <html xmlns='http://www.w3.org/1999/xhtml'>
                        <head>
                            <meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>
                            <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
                        </head>
                        
                        <body style='direction: rtl;margin:20px 0;padding:0;border:0;background:#f3f3f3;font-weight:normal;text-align:center;font-family:arial,verdana,sans-serif;color:#555555;font-size:15px;box-sizing:border-box;'>
                            <center>
                                <table style='margin:0;padding:0;border:1px solid #ccc;width:870px;' cellspacing='0'>
                                    <tr style='width:100%;background:#fff;'>
                                        <td style='width:75%;'>
                                            <table style='margin:0;padding:0;border:0;width:100%;padding:15px;' cellspacing='0'>
                                                <tr>
                                                    <th align='left' style='color:#555;font-size:18px;padding-bottom:10px;font-weight:normal;line-height: 5px'>تم تأكيد حجزك</th>
                                                </tr>
                                                <tr style='display:inline-block;'>
                                                    <td style='color:#fff;font-size:15px;'>
                                                        <label style='margin:0px;width:auto; color: #555'>رقم الحجز :</label>
                                                        <p  style='margin:0px;width:auto;display:inline-block; color: #555'>{{BookingRef}}</p>
                        
                                                    </td>
                        
                                                </tr>
                        
                        
                                                <tr>
                                                    <td style='color:#fff;font-size:15px;'>
                                                        <label style='margin:0px;width:auto; color: #555'>رقم الخطة :</label>
                                                        <p  style='margin:0px;width:auto;display:inline-block; color: #555'>{{ItinearyId}}</p>
                        
                                                    </td>
                                                </tr>
                        
                                                <tr>
                                                    <td><p style='color:#555;font-size:13px;margin:10px 0 10px 0;width:auto;'>لقد تم خصم مبلغ الحجز من بطاقتك الائتمانية.</p></td>
                                                </tr>
                        
                                            </table>
                                        </td>
                                        <td style='width:25%;'>
                                            <table style='margin:0;padding:0;border:0;width:100%;'cellspacing='0'>
                                                <tr>
                                                    <td align='center'>
                                                        <a href='#' style='float: left;padding-right: 15px;'><img style='height:auto; width:235px;'  src='{{AgencyLogo}}'/></a>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                        
                        
                                <table style='margin:10px 0 0 0;padding:10px 10px 0px 10px;border:1px solid #ccc; border-bottom: 0px; width:870px;background:#fff;' cellspacing='0'>
                                    <tr align='left'>
                                        <th style='border-bottom:1px solid #ededed;text-transform:uppercase;font-size:18px;font-weight:normal;'>معلومات الجولة</th>
                                    </tr>
                        
                        
                                    <table style='padding:10px;border-bottom: 1px solid #ccc;border-left: 1px solid #ccc;border-right: 1px solid #ccc;width:870px;background:#fff;' cellspacing='0'>
                                        <tbody>
                                            <tr>
                                                <td style='border-bottom: 1px solid #ccc;border-right: 1px solid #ccc; border-left: 1px solid #ccc; border-top: 1px solid #ccc;padding: 5px;'> <label style='font-weight: 600'>اسم الجولة </label></td>
                                                <td style='border-bottom: 1px solid #ccc;border-right: 1px solid #ccc;border-top: 1px solid #ccc;padding: 5px;'>
                                                    <p style='margin: 5px 0px;'>{{TourName}}</p>
                                                </td>
                                                <td style='border-bottom: 1px solid #ccc;border-right: 1px solid #ccc; border-top: 1px solid #ccc;padding: 5px;'> <label style='font-weight: 600'>تاريخ الحجز </label></td>
                                                <td style='border-bottom: 1px solid #ccc;border-right: 1px solid #ccc;border-top: 1px solid #ccc;padding: 5px;'>
                                                    <p style='margin: 5px 0px;'>{{BookingDate}}</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style='border-bottom: 1px solid #ccc;border-right: 1px solid #ccc; border-left: 1px solid #ccc;padding: 5px;'> <label style='font-weight: 600'>كود الجولة </label></td>
                                                <td style='border-bottom: 1px solid #ccc;border-right: 1px solid #ccc;padding: 5px;'>
                                                    <p style='margin: 5px 0px;'>{{TourCode}}</p>
                                                </td>
                                                <td style='border-bottom: 1px solid #ccc;border-right: 1px solid #ccc;padding: 5px;'> <label style='font-weight: 600'>تاريخ الرحيل</label></td>
                                                <td style='border-bottom: 1px solid #ccc;border-right: 1px solid #ccc;border-right: 1px solid #ccc;padding: 5px;'>
                                                    <p style='margin: 5px 0px;'>{{TravelDate}}</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style='border-bottom: 1px solid #ccc;border-right: 1px solid #ccc; border-left: 1px solid #ccc;padding: 5px;'><label style='font-weight: 600'>مكان البداية </label></td>
                                                <td style='border-bottom: 1px solid #ccc;border-right: 1px solid #ccc;padding: 5px;'>
                                                    <p style='margin: 5px 0px;'>{{StartLocation}}</p>
                                                </td>
                                                <td style='border-bottom: 1px solid #ccc;border-right: 1px solid #ccc;padding: 5px;'> </td>
                                                <td style='border-bottom: 1px solid #ccc;border-right: 1px solid #ccc;padding: 5px;'> </td>
                                            </tr>
                                        </tbody>
                                    </table>
                        
                                </table> 
                                <table style='margin:10px 0 0 0;padding:10px;border:1px solid #ccc;width:870px;background:#fff;' cellspacing='0'>
                                    <tr align='left'>
                                        <th style='border-bottom:1px solid #ededed;text-transform:uppercase;font-size:18px;font-weight:normal;'>تفاصيل السعر</th>
                                    </tr>
                        
                                    <tr>
                                        <td style='padding-top:7px;'>
                                            <label style='font-weight:bold;width:160px;float:left;'>الإجمالي الكبير :</label>
                                            <p style='width:auto; margin:0px;display:inline-block;font-weight:normal;'> {{CurrencySymbol}} {{GrandTotal}}
                                            </p>
                                        </td>
                                    </tr>
                                </table> 
                        
                                <table style='margin:10px 0 0 0;padding:10px 10px 0px 10px;border:1px solid #ccc; border-bottom: 0px; width:870px;background:#fff;' cellspacing='0'>
                                    <tr align='left'>
                                        <th style='border-bottom:1px solid #ededed;text-transform:uppercase;font-size:18px;font-weight:normal;'>معلومات الضيف</th>
                                    </tr>
                        
                                    <table style='padding:10px;border-bottom: 1px solid #ccc;border-left: 1px solid #ccc;border-right: 1px solid #ccc;width:870px;background:#fff;' cellspacing='0'>
                                        <thead>
                                            <th style='border-bottom: 1px solid #ccc;border-right: 1px solid #ccc; border-left: 1px solid #ccc; border-top: 1px solid #ccc;padding: 5px; text-align: left'>اسم الضيف</th>
                                            <th style='border-bottom: 1px solid #ccc;border-right: 1px solid #ccc; border-left: 1px solid #ccc; border-top: 1px solid #ccc;padding: 5px; text-align: left'>تاريخ الميلاد</th>
                                            <th style='border-bottom: 1px solid #ccc;border-right: 1px solid #ccc; border-left: 1px solid #ccc; border-top: 1px solid #ccc;padding: 5px; text-align: left'>نوع</th>
                                            <th style='border-bottom: 1px solid #ccc;border-right: 1px solid #ccc; border-left: 1px solid #ccc; border-top: 1px solid #ccc;padding: 5px; text-align: left'>رقم الجواز</th>
                                            <th style='border-bottom: 1px solid #ccc;border-right: 1px solid #ccc; border-left: 1px solid #ccc; border-top: 1px solid #ccc;padding: 5px; text-align: left'>الجنسية</th>
                                        </thead>
                                        <tbody>
                                            {{GuestDetail}}
                                        </tbody>
                                    </table>
                        
                                </table> 
                        
                                <table style='margin:10px 0 0 0;padding:10px;border:1px solid #ccc;width:870px;background:#fff;' cellspacing='0'>
                                    <tr align='left'>
                                        <th style='border-bottom:1px solid #ededed;text-transform:uppercase;font-size:18px;font-weight:normal;'>معلومات جهة الاتصال للمورد</th>
                                    </tr>
                                    <tr>
                                        <td style='padding-top:7px;'>
                                            <label style='font-weight:bold;width:250px;float:left;'>رقم جهة الاتصال المحلي للمورد :</label>
                                            <p style='width:auto; margin:0px;display:inline-block;'>{{LocalContactNumber}}</p>
                                        </td>   
                                    </tr>
                        
                                </table> 
                        
                                <table style='margin:10px 0 0 0;padding:10px;border:1px solid #ccc;width:870px;background:#fff;' cellspacing='0'>
                                    <tr align='left'>
                                        <th style='border-bottom:1px solid #ededed;text-transform:uppercase;font-size:18px;font-weight:normal;'>تفاصيل الاتصال</th>
                                    </tr>
                                    <tr>
                                        <td style='padding-top:7px;'>
                                            <label style='font-weight:bold;width:160px;float:left;'>الاسم :</label>
                                            <p style='width:auto; margin:0px;display:inline-block;'>{{LeadPassengerName}}</p>
                                        </td>   
                                    </tr>
                                    <tr>
                                        <td style='padding-top:7px;'>
                                            <label style='font-weight:bold;width:160px;float:left;'>البريد الإلكتروني :</label>
                                            <p style='width:auto; margin:0px;display:inline-block;font-weight:normal;'>{{LeadPassengerNameEmail}}</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style='padding-top:7px;'>
                                            <label style='font-weight:bold;width:160px;float:left;'>رقم الجوال :</label>
                                            <p style='width:auto; margin:0px;display:inline-block;'>{{LeadPassengerPhone}}</p>
                                        </td>   
                                    </tr>
                                    <tr>
                                        <td style='padding-top:7px;'>
                                            <label style='font-weight:bold;width:160px;float:left;'>العنوان :</label>
                                            <p style='width:555px; margin:0px;display:inline-block;'>{{LeadPassengerAddress}}</p>
                                        </td>   
                                    </tr>
                                    <tr>
                        
                                    </tr>
                                </table>    
                            </center>
                        </body>
                        </html>",
                        'language_code' => 'ar',
                    )
                ),
            ),
            array(
                'code' => 'PAYMENT_RECEIVED',
                'from_email' => 'do-not-reply@travelportal.com',
                'to_email' => '',
                'cc' => '',
                'bcc' => '',
                'mail_data' => array(
                    array(
                        'name' => 'Payment Received',
                        'subject' => 'Booking Ref : {{BookingRef}} E-Ticket Generated | {{AgencyName}}',
                        'content' => "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
                            <html xmlns='http://www.w3.org/1999/xhtml'>
                            <head>
                            <meta name='viewport' content='width=device-width' />
                            <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
                            <link href='pages/css/mail.css' media='all' rel='stylesheet' type='text/css' />
                            </head>
                            
                            <body itemscope itemtype='http://schema.org/EmailMessage' style='-webkit-font-smoothing: antialiased;-webkit-text-size-adjust: none;width: 100% !important;height: 100%;line-height: 1.6;background-color: #f6f6f6;margin: 0;font-family: 'Raleway', sans-serif;box-sizing: border-box;font-size: 14px;'>
                            
                            <table class='body-wrap' style='background-color: #f6f6f6;width: 100%;padding-top: 40px;'>
                                <tr>
                                    <td></td>
                                    <td class='container' width='600' style='display: block !important;max-width: 600px !important;margin: 0 auto !important;clear: both !important;'>
                                        <div class='content' style='max-width: 600px;margin: 0 auto;display: block;padding: 20px;'>
                                            <table class='main' width='100%' cellpadding='0' cellspacing='0' style='background-color: #fff;border: 1px solid #e9e9e9;border-radius: 3px;border: 1px solid #999;'>
                                               
                                                <tr>
                                                    <td class='content-wrap' style='padding: 25px;'>
                                                        <table width='100%' cellpadding='0' cellspacing='0'> 
                                                            <tr>
                                                               <td style='text-align: center; padding: 0 0 15px'>
                                                                   
                                                                         <img src='{{AgencyLogo}}' width='250'>
                                                                    
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class='content-block' style='padding: 0 0 15px;vertical-align: top;'>
                                                                    Dear {{CustomerName}},
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class='content-block' style='padding: 0 0 15px;vertical-align: top;'>
                                                                    <p style='font-weight: normal;font-size: 14px; margin: 0;'>Txn: {{TransactionID}} is successfull. Details are as follows:</p>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class='content-block' style='padding: 0 0 15px;vertical-align: top;'>
                                                                    Payment Method: {{PaymentMethod}}<br>
                                                                    Amount Paid: {{Currency}} {{Amount}}
                                                                </td>
                                                            </tr>
                                                           
                                                            <tr>
                                                                <td style='vertical-align: top; padding:15px 0px 0px 0px'>
                                                                    Thanks,<br>
                                                                    {{AgencyName}} Team!!
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </td>
                                    <td></td>
                                </tr>
                            </table>
                            
                            </body>
                            </html>",
                        'language_code' => 'en',
                    ),
                    array(
                        'name' => 'تم استلام الدفع',
                        'subject' => 'مرجع الحجز: {{BookingRef}} تم إنشاء تذكرة إلكترونية | {{AgencyName}}',
                        'content' => "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
                        <html xmlns='http://www.w3.org/1999/xhtml'>
                        <head>
                        <meta name='viewport' content='width=device-width' />
                        <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
                        <link href='pages/css/mail.css' media='all' rel='stylesheet' type='text/css' />
                        </head>
                        
                        <body itemscope itemtype='http://schema.org/EmailMessage' style='direction: rtl;-webkit-font-smoothing: antialiased;-webkit-text-size-adjust: none;width: 100% !important;height: 100%;line-height: 1.6;background-color: #f6f6f6;margin: 0;font-family: 'Raleway', sans-serif;box-sizing: border-box;font-size: 14px;'>
                        <table class='body-wrap' style='background-color: #f6f6f6;width: 100%;padding-top: 40px;'>
                            <tr>
                                <td></td>
                                <td class='container' width='600' style='display: block !important;max-width: 600px !important;margin: 0 auto !important;clear: both !important;'>
                                    <div class='content' style='max-width: 600px;margin: 0 auto;display: block;padding: 20px;'>
                                        <table class='main' width='100%' cellpadding='0' cellspacing='0' style='background-color: #fff;border: 1px solid #e9e9e9;border-radius: 3px;border: 1px solid #999;'>
                                           
                                            <tr>
                                                <td class='content-wrap' style='padding: 25px;'>
                                                    <table width='100%' cellpadding='0' cellspacing='0'> 
                                                        <tr>
                                                           <td style='text-align: center; padding: 0 0 15px'>
                                                               
                                                                     <img src='{{AgencyLogo}}' width='250'>
                                                                
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class='content-block' style='padding: 0 0 15px;vertical-align: top;'>
                                                                عزيزي {{CustomerName}},
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class='content-block' style='padding: 0 0 15px;vertical-align: top;'>
                                                                <p style='font-weight: normal;font-size: 14px; margin: 0;'>المعاملة: {{TransactionID}} ناجحة. التفاصيل كما يلي:</p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class='content-block' style='padding: 0 0 15px;vertical-align: top;'>
                                                                طريقة الدفع: {{PaymentMethod}}<br>
                                                                المبلغ المدفوع: {{Currency}} {{Amount}}
                                                            </td>
                                                        </tr>
                                                       
                                                        <tr>
                                                            <td style='vertical-align: top; padding:15px 0px 0px 0px'>
                                                                شكراً،<br>
                                                                فريق {{AgencyName}}!!
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </td>
                                <td></td>
                            </tr>
                        </table>
                        </body>
                        </html>",
                        'language_code' => 'ar',
                    )
                ),
            ),
            array(
                'code' => 'INVOICE_GENERATION',
                'from_email' => 'do-not-reply@travelportal.com',
                'to_email' => '',
                'cc' => '',
                'bcc' => '',
                'mail_data' => array(
                    array(
                        'name' => 'Invoice Generation',
                        'subject' => 'Invoice : {{InvoiceNumber}} | {{AgencyName}}',
                        'content' => "'Invoice Number:{{InvoiceNumber}}                        INVOICE                          
                            {{NameReference}} {{GivenName}} {{SurName}}
                            {{CustomerEmail}}
                            Booking Ref: {{BookingRef}}
                            Booking Date: {{BookingDate}}
                            {{FromAirport}} {{ToAirport}} 
                            {{FlightDate}} / {{People}}
                            Description
                            Departure
                            {{DepartureAirportCode}} {{DepartureTime}}
                            {{DepartureDate}} {{DepartureAirportName}}
                            {{ArrivalAirportCode}} {{ArrivalTime}}
                            {{ArrivalDate}}{{ArrivalAirportName}}
                            Return
                            {{DepartureAirportCode}} {{DepartureTime}}
                            {{DepartureDate}} {{DepartureAirportName}}
                            {{ArrivalAirportCode}} {{ArrivalTime}}
                            {{ArrivalDate}}{{ArrivalAirportName}}
                            Amount
                            {{Currency}}{{BookingAmount}}
                            {{AmountInWords}}
                            Total: {{Currency}}{{BookingAmount}}
                            Customer Support
                            Corporate & Head Office : {{AgencyAddress}}
                            Email:{{AgencyEmail}}
                            URL:{{AgencyUrl}}
                            Number: {{AgencySupportNumber}}
                            NOTE :This is computer Gentrating invoice and doesnot require signature stamp please do not reply this mail it has been sent from email accountant that is not montiroid                        
                            '",
                        'language_code' => 'en',
                    ),
                    array(
                        'name' => 'إنشاء الفاتورة',
                        'subject' => 'الفاتورة : {{InvoiceNumber}} | {{AgencyName}}',
                        'content' => "'رقم الفاتورة: {{InvoiceNumber}} الفاتورة
                        {{NameReference}} {{GivenName}} {{SurName}}
                        {{CustomerEmail}}
                        رقم الحجز: {{BookingRef}}
                        تاريخ الحجز: {{BookingDate}}
                        {{FromAirport}} {{ToAirport}}
                        تاريخ الرحلة: {{FlightDate}} / {{People}}
                        الوصف
                        المغادرة
                        {{DepartureAirportCode}} {{DepartureTime}}
                        {{DepartureDate}} {{DepartureAirportName}}
                        {{ArrivalAirportCode}} {{ArrivalTime}}
                        {{ArrivalDate}} {{ArrivalAirportName}}
                        العودة
                        {{DepartureAirportCode}} {{DepartureTime}}
                        {{DepartureDate}} {{DepartureAirportName}}
                        {{ArrivalAirportCode}} {{ArrivalTime}}
                        {{ArrivalDate}} {{ArrivalAirportName}}
                        المبلغ
                        {{Currency}} {{BookingAmount}}
                        {{AmountInWords}}
                        المجموع: {{Currency}} {{BookingAmount}}
                        دعم العملاء
                        المكتب الرئيسي والشركات: {{AgencyAddress}}
                        البريد الإلكتروني: {{AgencyEmail}}
                        الرابط: {{AgencyUrl}}
                        الرقم: {{AgencySupportNumber}}
                        ملاحظة: هذه الفاتورة تم إنشاؤها بواسطة الكمبيوتر ولا تتطلب توقيعًا. يرجى عدم الرد على هذا البريد الإلكتروني، حيث تم إرساله من حساب البريد الإلكتروني للمحاسبة وليس له أي رقابة.'",
                        'language_code' => 'ar',
                    )
                ),
            ),
            array(
                'code' => 'BOOKING_CANCELLATION_REQUEST',
                'from_email' => 'do-not-reply@travelportal.com',
                'to_email' => '',
                'cc' => '',
                'bcc' => '',
                'mail_data' => array(
                    array(
                        'name' => 'Booking Cancellation Request',
                        'subject' => 'Cancellation Ref: {{CancellationId}} is Pending |  {{AgencyName}}',
                        'content' => "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
                            <html xmlns='http://www.w3.org/1999/xhtml'>
                            <head>
                            <meta http-equiv='Content-Type' content='text/html; cha₹et=iso-8859-1'>
                            <meta http-equiv='Content-Type' content='text/html; cha₹et=utf-8' />
                            </head>
                            
                            
                            <body style='margin:20px 0;padding:0;border:0;background:#f3f3f3;font-weight:normal;text-align:center;font-family:arial,verdana,sans-serif;color:#555555;font-size:15px;box-sizing:border-box;'>
                              <center>
                                <table style='margin:0;padding:0;border:0;width:870px;background: #fff;padding: 15px' cellspacing='0'>
                                   <tr style='width:100%;'>
                                      <td style='color:#666;font-size:16px;'>
                                        <label style='margin:0px;width:100%;font-weight: bold;float: left;padding-bottom: 10px;'>Cancellation Request Generated Successfully</label>
                                        <label style='margin:0px;width:auto;padding: 5px 0px;'>Cancellation ID :</label>
                                        <p  style='margin:0px;width:58%;display:inline-block;padding: 5px 0px;'>{{CancellationId}}</p>
                                        <label style='margin:0px;width:auto;'>Cancellation Date :</label>
                                        <p  style='margin:0px;width:auto;display:inline-block;'>{{CancellationDate}}</p>
                                      </td>
                                      <td style='width:40%;'>
                                        <img src='{{AgencyLogo}}' width='160' height='' style='float: right;'>
                                      </td>
                                   </tr>
                                 </table>
                                 <table style='margin:20px 0 0 0;padding:0;border:0;width:870px;background: #fff;padding: 15px' cellspacing='0'>
                                   <tr style='width:100%;'>
                                      <td style='color:#666;font-size:16px;width: 100%;float: left;'>
                                        <label style='margin:0px;width:100%;font-weight: 600;float: left;padding-bottom: 10px;'>Dear {{CustomerName}},,</label>
                                        <p  style='margin:0px;width:auto;display:inline-block;'>Cancellation Request of your Booking Ref {{BookingRef}} has been generated. You will be notified once cancellation request processed.</p>
                                      </td>
                                      <td style='color:#666;font-size:16px;width: 100%;float: left;'>
                                        <table style='margin:20px 0 0 0;padding:0;border:0;width:;border-collapse: collapse;width:100%' cellspacing='0'>
                                         <thead>
                                           <tr>
                                             <th style='font-size: 14px;padding-right: 15px !important;font-weight: 600;vertical-align: middle;font-weight: 600;padding: 10px 8px;color: #666;text-align: left;border: 1px solid #666'>Cancellation ID</th>
                                             <th style='font-size: 14px;padding-right: 15px !important;font-weight: 600;vertical-align: middle;font-weight: 600;padding: 10px 8px;color: #666;text-align: left;border: 1px solid #666'>Cancellation Status</th>
                                             <th style='font-size: 14px;padding-right: 15px !important;font-weight: 600;vertical-align: middle;font-weight: 600;padding: 10px 8px;color: #666;text-align: left;border: 1px solid #666'>Refund Status</th>
                                           </tr>
                                         </thead>
                                         <tbody>
                                           <tr>
                                             <td style='border-bottom: 1px solid rgba(230, 230, 230, 0.7);padding: 20px 0px 20px 10px;background: #fff;border: 1px solid #666'>{{CancellationId}}</td>
                                             <td style='border-bottom: 1px solid rgba(230, 230, 230, 0.7);padding: 20px 0px 20px 10px;background: #fff;border: 1px solid #666'> {{CancelationStatus}}</td>
                                             <td style='border-bottom: 1px solid rgba(230, 230, 230, 0.7);padding: 20px 0px 20px 10px;background: #fff;border: 1px solid #666'>Pending</td>
                                           </tr>
                                         </tbody>
                                       </table>
                                      </td>
                                    
                                   </tr>
                                 </table>
                                 <table style='margin:0px 0 0 0;padding:0;border:0;width:870px;' cellspacing='0'>
                                     <tr style='background:#fff;padding:15px;box-sizing:border-box;float:left; width:100%;'>
                                       <td style='float:left; width: 100%; border-bottom:2px solid #ededed;'>
                                         <p style='margin:0px;width:auto;display:inline-block;font-size:18px;font-weight:normal;color:#666; text-transform: capitalize;'>Customer Support</p>
                                          </td>
                                         <td style='float: left; width: 65%'>
                                           <p style='color: #666; font-size: 16px; margin: 10px 0px 0px 0px; text-transform: capitalize;'>
                                            Corporate & Head Office
                                           </p>
                                           <label style='color: #666; font-size: 16px; margin: 10px 0px 0px 0px; float: left;';>{{AgencyAddress}}</label>
                                         </td>
                            
                                           <td style='float: right; width: 30%; margin-top: 10px'>
                                             <img src='assets/img/mail.png' style='float: left; padding-right: 10px; margin-top: 3px '>
                                             <span style='color: #666; font-size: 16px'>{{AgencyEmail}}</span>
                                           </td>
                                           <td style='float: right; width: 30%; margin-top: 5px'>
                                             <img src='assets/img/web.png' style='float: left; padding-right: 10px' >
                                             <span style='color: #666; font-size: 16px'>{{AgencyUrl}}</span>
                                           </td>
                                           <td style='margin: 10px 0px 0px 0px; float: left;width: 30%'>
                                           <img src='assets/img/support.png' style='float: left; padding-right: 5px'>
                                           <span style='float: left; color: #04486b; font-size: 18px; margin: 5px 0px 0px 0px'>{{AgencySupportNumber}}</span>
                            
                                           <p style='float: left; margin: 0; color: #666;'>24*7 Available</p>
                                          </td> 
                                       </td> 
                                     </tr>
                                   </table>
                                  <table style='margin:20px 0 0 0;padding:0;border:0;width:870px;' cellspacing='0'>
                                     <tr style='background:#555555;padding:10px;box-sizing:border-box;float:left; width:100%;'>
                                       <td style='float: left;width: 100%'>
                                         <label style='color: #fff; font-size: 16px; float: left;'>NOTE :</label>
                                         <p style='color: #fff; font-size: 15px; float: right; width: 90%; margin: 0'>Please do not reply to this mail.It has been sent from an email account that is not monitored.</p>
                                       </td>
                                     </tr>
                                   </table>
                              </center>
                            </body>
                            </html>",
                        'language_code' => 'en',
                    ),
                    array(
                        'name' => 'طلب إلغاء الحجز',
                        'subject' => 'مرجع الإلغاء: {{CancellationId}} معلق | {{AgencyName}}',
                        'content' => "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
                        <html xmlns='http://www.w3.org/1999/xhtml'>
                        <head>
                            <meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>
                            <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
                        </head>
                        
                        <body style='direction: rtl;margin:20px 0;padding:0;border:0;background:#f3f3f3;font-weight:normal;text-align:center;font-family:arial,verdana,sans-serif;color:#555555;font-size:15px;box-sizing:border-box;'>
                        <center>
                            <table style='margin:0;padding:0;border:0;width:870px;background: #fff;padding: 15px' cellspacing='0'>
                                <tr style='width:100%;'>
                                    <td style='color:#666;font-size:16px;'>
                                        <label style='margin:0px;width:100%;font-weight: bold;float: left;padding-bottom: 10px;'>تم إنشاء طلب إلغاء بنجاح</label>
                                        <label style='margin:0px;width:auto;padding: 5px 0px;'>رقم طلب الإلغاء :</label>
                                        <p style='margin:0px;width:58%;display:inline-block;padding: 5px 0px;'>{{CancellationId}}</p>
                                        <label style='margin:0px;width:auto;'>تاريخ الإلغاء :</label>
                                        <p style='margin:0px;width:auto;display:inline-block;'>{{CancellationDate}}</p>
                                    </td>
                                    <td style='width:40%;'>
                                        <img src='{{AgencyLogo}}' width='160' height='' style='float: right;'>
                                    </td>
                                </tr>
                            </table>
                            <table style='margin:20px 0 0 0;padding:0;border:0;width:870px;background: #fff;padding: 15px' cellspacing='0'>
                                <tr style='width:100%;'>
                                    <td style='color:#666;font-size:16px;width: 100%;float: left;'>
                                        <label style='margin:0px;width:100%;font-weight: 600;float: left;padding-bottom: 10px;'>عزيزي {{CustomerName}},</label>
                                        <p style='margin:0px;width:auto;display:inline-block;'>تم إنشاء طلب إلغاء حجزك برقم {{BookingRef}}. ستتلقى إشعارًا عند معالجة طلب الإلغاء.</p>
                                    </td>
                                    <td style='color:#666;font-size:16px;width: 100%;float: left;'>
                                        <table style='margin:20px 0 0 0;padding:0;border:0;width:;border-collapse: collapse;width:100%' cellspacing='0'>
                                            <thead>
                                                <tr>
                                                    <th style='font-size: 14px;padding-right: 15px !important;font-weight: 600;vertical-align: middle;font-weight: 600;padding: 10px 8px;color: #666;text-align: left;border: 1px solid #666'>رقم طلب الإلغاء</th>
                                                    <th style='font-size: 14px;padding-right: 15px !important;font-weight: 600;vertical-align: middle;font-weight: 600;padding: 10px 8px;color: #666;text-align: left;border: 1px solid #666'>حالة الإلغاء</th>
                                                    <th style='font-size: 14px;padding-right: 15px !important;font-weight: 600;vertical-align: middle;font-weight: 600;padding: 10px 8px;color: #666;text-align: left;border: 1px solid #666'>حالة المبلغ المسترد</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td style='border-bottom: 1px solid rgba(230, 230, 230, 0.7);padding: 20px 0px 20px 10px;background: #fff;border: 1px solid #666'>{{CancellationId}}</td>
                                                    <td style='border-bottom: 1px solid rgba(230, 230, 230, 0.7);padding: 20px 0px 20px 10px;background: #fff;border: 1px solid #666'> {{CancelationStatus}}</td>
                                                    <td style='border-bottom: 1px solid rgba(230, 230, 230, 0.7);padding: 20px 0px 20px 10px;background: #fff;border: 1px solid #666'>معلق</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <table style='margin:0px 0 0 0;padding:0;border:0;width:870px;' cellspacing='0'>
                                <tr style='background:#fff;padding:15px;box-sizing:border-box;float:left; width:100%;'>
                                    <td style='float:left; width: 100%; border-bottom:2px solid #ededed;'>
                                        <p style='margin:0px;width:auto;display:inline-block;font-size:18px;font-weight:normal;color:#666; text-transform: capitalize;'>دعم العملاء</p>
                                    </td>
                                    <td style='float: left; width: 65%'>
                                        <p style='color: #666; font-size: 16px; margin: 10px 0px 0px 0px; text-transform: capitalize;'>
                                            الفرع الرئيسي والشركة
                                        </p>
                                        <label style='color: #666; font-size: 16px; margin: 10px 0px 0px 0px; float: left;';>{{AgencyAddress}}</label>
                                    </td>
                        
                                    <td style='float: right; width: 30%; margin-top: 10px'>
                                        <img src='assets/img/mail.png' style='float: left; padding-right: 10px; margin-top: 3px '>
                                        <span style='color: #666; font-size: 16px'>{{AgencyEmail}}</span>
                                    </td>
                                    <td style='float: right; width: 30%; margin-top: 5px'>
                                        <img src='assets/img/web.png' style='float: left; padding-right: 10px' >
                                        <span style='color: #666; font-size: 16px'>{{AgencyUrl}}</span>
                                    </td>
                                    <td style='margin: 10px 0px 0px 0px; float: left;width: 30%'>
                                        <img src='assets/img/support.png' style='float: left; padding-right: 5px'>
                                        <span style='float: left; color: #04486b; font-size: 18px; margin: 5px 0px 0px 0px'>{{AgencySupportNumber}}</span>
                        
                                        <p style='float: left; margin: 0; color: #666;'>متاح 24/7</p>
                                    </td>
                                </td>
                            </tr>
                        </table>
                        <table style='margin:20px 0 0 0;padding:0;border:0;width:870px;' cellspacing='0'>
                            <tr style='background:#555555;padding:10px;box-sizing:border-box;float:left; width:100%;'>
                                <td style='float: left;width: 100%'>
                                    <label style='color: #fff; font-size: 16px; float: left;'>ملاحظة:</label>
                                    <p style='color: #fff; font-size: 15px; float: right; width: 90%; margin: 0'>يرجى عدم الرد على هذا البريد الإلكتروني. تم إرساله من حساب بريد إلكتروني غير مراقب.</p>
                                </td>
                            </tr>
                        </table>
                        </center>
                        </body>
                        </html>",
                        'language_code' => 'ar',
                    )
                ),
            ),
            array(
                'code' => 'BOOKING_CANCELLATION_REQUEST_PROCESSED',
                'from_email' => 'do-not-reply@travelportal.com',
                'to_email' => '',
                'cc' => '',
                'bcc' => '',
                'mail_data' => array(
                    array(
                        'name' => 'Booking Cancellation Request Processed',
                        'subject' => 'Cancellation Ref: {{CancellationId}} is Processed |  {{AgencyName}}',
                        'content' => "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
                            <html xmlns='http://www.w3.org/1999/xhtml'>
                            <head>
                            <meta http-equiv='Content-Type' content='text/html; cha₹et=iso-8859-1'>
                            <meta http-equiv='Content-Type' content='text/html; cha₹et=utf-8' />
                            </head>
                            
                            
                            <body style='margin:20px 0;padding:0;border:0;background:#f3f3f3;font-weight:normal;text-align:center;font-family:arial,verdana,sans-serif;color:#555555;font-size:15px;box-sizing:border-box;'>
                              <center>
                                <table style='margin:0;border:0;width:870px;background: #fff;padding: 15px' cellspacing='0'>
                                   <tr style='width:100%;'>
                                      <td style='color:#666;font-size:16px;'>
                                        <label style='margin:0px;width:100%;font-weight: bold;float: left;padding-bottom: 10px;'>Refund Request Processed Successfully!!</label>
                                        <label style='margin:0px;width:auto;padding: 5px 0px;'>Cancellation ID :</label>
                                        <p  style='margin:0px;width:58%;display:inline-block;padding: 5px 0px;'>{{CancellationId}}</p>
                                        <label style='margin:0px;width:auto;'>Cancellation Date :</label>
                                        <p  style='margin:0px;width:auto;display:inline-block;'>{{CancellationDate}}</p>
                                      </td>
                                      <td style='width:40%;'>
                                        <img src='{{AgencyLogo}}' width='160' height='' style='float: right;'>
                                      </td>
                                   </tr>
                                 </table>
                                 <table style='margin:20px 0 0 0;border:0;width:870px;background: #fff;padding: 15px' cellspacing='0'>
                                   <tr style='width:100%;'>
                                      <td style='color:#666;font-size:16px;width: 100%;float: left;'>
                                        <label style='margin:0px;width:100%;font-weight: 600;float: left;padding-bottom: 10px;'>Dear {{CustomerName}},,</label>
                                        <p  style='margin:0px;width:auto;display:inline-block;'>Refund of your Booking Ref {{BookingRef}}  is {{RefundStatus}}.</p>
                                      </td>
                                      <td style='color:#666;font-size:16px;width: 100%;float: left;'>
                                        <table style='margin:20px 0 0 0;padding:0;border:0;width:;border-collapse: collapse;width:100%' cellspacing='0'>
                                         <thead>
                                           <tr>
                                             <th style='font-size: 14px;padding-right: 15px !important;font-weight: 600;vertical-align: middle;font-weight: 600;padding: 10px 8px;color: #666;text-align: left;border: 1px solid #666'>Cancellation ID</th>
                                             <th style='font-size: 14px;padding-right: 15px !important;font-weight: 600;vertical-align: middle;font-weight: 600;padding: 10px 8px;color: #666;text-align: left;border: 1px solid #666'>Cancellation Status</th>
                                             <th style='font-size: 14px;padding-right: 15px !important;font-weight: 600;vertical-align: middle;font-weight: 600;padding: 10px 8px;color: #666;text-align: left;border: 1px solid #666'>Refund Status</th>
                                           </tr>
                                         </thead>
                                         <tbody>
                                           <tr>
                                             <td style='border-bottom: 1px solid rgba(230, 230, 230, 0.7);padding: 20px 0px 20px 10px;background: #fff;border: 1px solid #666'>{{CancellationId}}</td>
                                             <td style='border-bottom: 1px solid rgba(230, 230, 230, 0.7);padding: 20px 0px 20px 10px;background: #fff;border: 1px solid #666'>{{CancelationStatus}}</td>
                                             <td style='border-bottom: 1px solid rgba(230, 230, 230, 0.7);padding: 20px 0px 20px 10px;background: #fff;border: 1px solid #666'>{{RefundStatus}}</td>
                                           </tr>
                                         </tbody>
                                       </table>
                                      </td>
                                      <td style='color:#666;font-size:16px;width: 100%;float: left;'>
                                        <table style='margin:20px 0 0 0;padding:0;border:0;width:;border-collapse: collapse;width:100%' cellspacing='0'>
                                         <tbody>
                                           <tr>
                                             <td style='border-bottom: 1px solid rgba(230, 230, 230, 0.7);padding: 10px 0px 10px 10px;background: #fff;border: 1px solid #666'>
                                               <label style='margin:0px;width:auto;padding-bottom: 10px;'>Cancellation Process Date:</label>
                                              <p  style='margin:0px;width:auto;display:inline-block;'>{{CancelationDateTime}} </p>
                                             </td>
                                             <td style='border-bottom: 1px solid rgba(230, 230, 230, 0.7);padding: 10px 0px 10px 10px;background: #fff;border: 1px solid #666'>
                                               <label style='margin:0px;width:auto;padding-bottom: 10px;'>Refund Process Date:</label>
                                              <p  style='margin:0px;width:auto;display:inline-block;'>{{RefundDateTime}}</p>
                                             </td>
                                           </tr>
                                           <tr>
                                              <td style='border-bottom: 1px solid rgba(230, 230, 230, 0.7);padding: 10px 0px 10px 10px;background: #fff;border: 1px solid #666;'>
                                               <label style='margin:0px;width:auto;padding-bottom: 10px;'>Reason For Cancellation :</label>
                                              <p style='margin:0px;width:auto;display:inline-block;'>{{Reason}}</p>
                                             </td>
                                             <td style='border-bottom: 1px solid rgba(230, 230, 230, 0.7);padding: 10px 0px 10px 10px;background: #fff;border: 1px solid #666'>
                                               <label style='margin:0px;width:auto;padding-bottom: 10px;'>Refund Amount :</label>
                                              <p  style='margin:0px;width:auto;display:inline-block;'>{{RefundAmount}}</p>
                                             </td>
                                           </tr>
                                         </tbody>
                                       </table>
                                      </td>
                                     
                                   </tr>
                                 </table>
                                 <table style='margin:0px 0 0 0;border:0;width:870px;' cellspacing='0'>
                                     <tr style='background:#fff;padding:15px;box-sizing:border-box;float:left; width:100%;'>
                                       <td style='float:left; width: 100%; border-bottom:2px solid #ededed;'>
                                         <p style='margin:0px;width:auto;display:inline-block;font-size:18px;font-weight:normal;color:#666; text-transform: capitalize;'>Customer Support</p>
                                          </td>
                                         <td style='float: left; width: 65%'>
                                           <p style='color: #666; font-size: 16px; margin: 10px 0px 0px 0px; text-transform: capitalize;'>
                                            Corporate & Head Office:
                                           </p>
                                           <label style='color: #666; font-size: 16px; margin: 10px 0px 0px 0px; float: left;';>{{AgencyAddress}}</label>
                                         </td>
                            
                                           <td style='float: right; width: 30%; margin-top: 10px'>
                                             <p style='color: #666; font-size: 16px; margin: 10px 0px 0px 0px; text-transform: capitalize;'>
                                            Email:
                                           </p>
                                             <span style='color: #666; font-size: 16px'>{{AgencyEmail}}</span>
                                           </td>
                                           <td style='float: right; width: 30%; margin-top: 5px'>
                                              <p style='color: #666; font-size: 16px; margin: 10px 0px 0px 0px; text-transform: capitalize;'>
                                            Website:
                                           </p>
                                             <span style='color: #666; font-size: 16px'>{{AgencyUrl}}</span>
                                           </td>
                                           <td style='margin: 10px 0px 0px 0px; float: left;width: 30%'>
                                           <p style='color: #666; font-size: 16px; margin: 10px 0px 0px 0px; text-transform: capitalize;'>
                                            ContactNumber:
                                           </p>
                                           <span style='float: left; color: #04486b; font-size: 18px; margin: 5px 0px 0px 0px'>{{AgencySupportNumber}}</span>
                            
                                           <p style='float: left; margin: 0; color: #666;'>24*7 Available</p>
                                          </td> 
                                       </td> 
                                     </tr>
                                   </table>
                                  <table style='margin:20px 0 0 0;padding:0;border:0;width:870px;' cellspacing='0'>
                                     <tr style='background:#555555;padding:10px;box-sizing:border-box;float:left; width:100%;'>
                                       <td style='float: left;width: 100%'>
                                         <label style='color: #fff; font-size: 16px; float: left;'>NOTE :</label>
                                         <p style='color: #fff; font-size: 15px; float: right; width: 90%; margin: 0'>Please do not reply to this mail.It has been sent from an email account that is not monitored.</p>
                                       </td>
                                     </tr>
                                   </table>
                              </center>
                            </body>
                            </html>",
                        'language_code' => 'en',
                    ),
                    array(
                        'name' => 'تمت معالجة طلب إلغاء الحجز',
                        'subject' => 'مرجع الإلغاء: تمت معالجة {{CancellationId}} | {{AgencyName}}',
                        'content' => "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
                        <html xmlns='http://www.w3.org/1999/xhtml'>
                        <head>
                        <meta http-equiv='Content-Type' content='text/html; cha₹et=iso-8859-1'>
                        <meta http-equiv='Content-Type' content='text/html; cha₹et=utf-8' />
                        </head>
                        
                        <body style='direction: rtl;margin:20px 0;padding:0;border:0;background:#f3f3f3;font-weight:normal;text-align:center;font-family:arial,verdana,sans-serif;color:#555555;font-size:15px;box-sizing:border-box;'>
                          <center>
                            <table style='margin:0;border:0;width:870px;background: #fff;padding: 15px' cellspacing='0'>
                               <tr style='width:100%;'>
                                  <td style='color:#666;font-size:16px;'>
                                    <label style='margin:0px;width:100%;font-weight: bold;float: left;padding-bottom: 10px;'>تم معالجة طلب الاسترداد بنجاح!!</label>
                                    <label style='margin:0px;width:auto;padding: 5px 0px;'>رقم الإلغاء:</label>
                                    <p  style='margin:0px;width:58%;display:inline-block;padding: 5px 0px;'>{{CancellationId}}</p>
                                    <label style='margin:0px;width:auto;'>تاريخ الإلغاء:</label>
                                    <p  style='margin:0px;width:auto;display:inline-block;'>{{CancellationDate}}</p>
                                  </td>
                                  <td style='width:40%;'>
                                    <img src='{{AgencyLogo}}' width='160' height='' style='float: right;'>
                                  </td>
                               </tr>
                             </table>
                             <table style='margin:20px 0 0 0;border:0;width:870px;background: #fff;padding: 15px' cellspacing='0'>
                               <tr style='width:100%;'>
                                  <td style='color:#666;font-size:16px;width: 100%;float: left;'>
                                    <label style='margin:0px;width:100%;font-weight: 600;float: left;padding-bottom: 10px;'>عزيزي {{CustomerName}},</label>
                                    <p  style='margin:0px;width:auto;display:inline-block;'>تمت عملية استرداد حجزك بنجاح. رقم الحجز: {{BookingRef}} والحالة: {{RefundStatus}}.</p>
                                  </td>
                                  <td style='color:#666;font-size:16px;width: 100%;float: left;'>
                                    <table style='margin:20px 0 0 0;padding:0;border:0;width:;border-collapse: collapse;width:100%' cellspacing='0'>
                                     <thead>
                                       <tr>
                                         <th style='font-size: 14px;padding-right: 15px !important;font-weight: 600;vertical-align: middle;font-weight: 600;padding: 10px 8px;color: #666;text-align: left;border: 1px solid #666'>رقم الإلغاء</th>
                                         <th style='font-size: 14px;padding-right: 15px !important;font-weight: 600;vertical-align: middle;font-weight: 600;padding: 10px 8px;color: #666;text-align: left;border: 1px solid #666'>حالة الإلغاء</th>
                                         <th style='font-size: 14px;padding-right: 15px !important;font-weight: 600;vertical-align: middle;font-weight: 600;padding: 10px 8px;color: #666;text-align: left;border: 1px solid #666'>حالة الاسترداد</th>
                                       </tr>
                                     </thead>
                                     <tbody>
                                       <tr>
                                         <td style='border-bottom: 1px solid rgba(230, 230, 230, 0.7);padding: 20px 0px 20px 10px;background: #fff;border: 1px solid #666'>{{CancellationId}}</td>
                                         <td style='border-bottom: 1px solid rgba(230, 230, 230, 0.7);padding: 20px 0px 20px 10px;background: #fff;border: 1px solid #666'>{{CancelationStatus}}</td>
                                         <td style='border-bottom: 1px solid rgba(230, 230, 230, 0.7);padding: 20px 0px 20px 10px;background: #fff;border: 1px solid #666'>{{RefundStatus}}</td>
                                       </tr>
                                     </tbody>
                                   </table>
                                  </td>
                                  <td style='color:#666;font-size:16px;width: 100%;float: left;'>
                                    <table style='margin:20px 0 0 0;padding:0;border:0;width:;border-collapse: collapse;width:100%' cellspacing='0'>
                                     <tbody>
                                       <tr>
                                         <td style='border-bottom: 1px solid rgba(230, 230, 230, 0.7);padding: 10px 0px 10px 10px;background: #fff;border: 1px solid #666'>
                                           <label style='margin:0px;width:auto;padding-bottom: 10px;'>تاريخ عملية الإلغاء:</label>
                                          <p  style='margin:0px;width:auto;display:inline-block;'>{{CancelationDateTime}} </p>
                                         </td>
                                         <td style='border-bottom: 1px solid rgba(230, 230, 230, 0.7);padding: 10px 0px 10px 10px;background: #fff;border: 1px solid #666'>
                                           <label style='margin:0px;width:auto;padding-bottom: 10px;'>تاريخ عملية الاسترداد:</label>
                                          <p  style='margin:0px;width:auto;display:inline-block;'>{{RefundDateTime}}</p>
                                         </td>
                                       </tr>
                                       <tr>
                                          <td style='border-bottom: 1px solid rgba(230, 230, 230, 0.7);padding: 10px 0px 10px 10px;background: #fff;border: 1px solid #666;'>
                                           <label style='margin:0px;width:auto;padding-bottom: 10px;'>سبب الإلغاء :</label>
                                          <p style='margin:0px;width:auto;display:inline-block;'>{{Reason}}</p>
                                         </td>
                                         <td style='border-bottom: 1px solid rgba(230, 230, 230, 0.7);padding: 10px 0px 10px 10px;background: #fff;border: 1px solid #666'>
                                           <label style='margin:0px;width:auto;padding-bottom: 10px;'>مبلغ الاسترداد :</label>
                                          <p  style='margin:0px;width:auto;display:inline-block;'>{{RefundAmount}}</p>
                                         </td>
                                       </tr>
                                     </tbody>
                                   </table>
                                  </td>
                                 
                               </tr>
                             </table>
                             <table style='margin:0px 0 0 0;border:0;width:870px;' cellspacing='0'>
                                 <tr style='background:#fff;padding:15px;box-sizing:border-box;float:left; width:100%;'>
                                   <td style='float:left; width: 100%; border-bottom:2px solid #ededed;'>
                                     <p style='margin:0px;width:auto;display:inline-block;font-size:18px;font-weight:normal;color:#666; text-transform: capitalize;'>دعم العملاء</p>
                                      </td>
                                     <td style='float: left; width: 65%'>
                                       <p style='color: #666; font-size: 16px; margin: 10px 0px 0px 0px; text-transform: capitalize;'>
                                        الشركة والمقر الرئيسي:
                                       </p>
                                       <label style='color: #666; font-size: 16px; margin: 10px 0px 0px 0px; float: left;'>{{AgencyAddress}}</label>
                                     </td>
                            
                                       <td style='float: right; width: 30%; margin-top: 10px'>
                                         <p style='color: #666; font-size: 16px; margin: 10px 0px 0px 0px; text-transform: capitalize;'>
                                        البريد الإلكتروني:
                                       </p>
                                         <span style='color: #666; font-size: 16px'>{{AgencyEmail}}</span>
                                       </td>
                                       <td style='float: right; width: 30%; margin-top: 5px'>
                                          <p style='color: #666; font-size: 16px; margin: 10px 0px 0px 0px; text-transform: capitalize;'>
                                        الموقع الإلكتروني:
                                       </p>
                                         <span style='color: #666; font-size: 16px'>{{AgencyUrl}}</span>
                                       </td>
                                       <td style='margin: 10px 0px 0px 0px; float: left;width: 30%'>
                                       <p style='color: #666; font-size: 16px; margin: 10px 0px 0px 0px; text-transform: capitalize;'>
                                        رقم الاتصال:
                                       </p>
                                       <span style='float: left; color: #04486b; font-size: 18px; margin: 5px 0px 0px 0px'>{{AgencySupportNumber}}</span>
                            
                                       <p style='float: left; margin: 0; color: #666;'>متاح على مدار 24 ساعة</p>
                                      </td> 
                                   </td> 
                                 </tr>
                               </table>
                              <table style='margin:20px 0 0 0;padding:0;border:0;width:870px;' cellspacing='0'>
                                 <tr style='background:#555555;padding:10px;box-sizing:border-box;float:left; width:100%;'>
                                   <td style='float: left;width: 100%'>
                                     <label style='color: #fff; font-size: 16px; float: left;'>ملاحظة:</label>
                                     <p style='color: #fff; font-size: 15px; float: right; width: 90%; margin: 0'>الرجاء عدم الرد على هذا البريد الإلكتروني. تم إرساله من عنوان بريد إلكتروني غير مراقب.</p>
                                   </td>
                                 </tr>
                               </table>
                          </center>
                        </body>
                        </html>",
                        'language_code' => 'ar',
                    )
                ),
            ),
            array(
                'code' => 'BOOKING_REFUND_REQUEST_PROCESSED',
                'from_email' => 'do-not-reply@travelportal.com',
                'to_email' => '',
                'cc' => '',
                'bcc' => '',
                'mail_data' => array(
                    array(
                        'name' => 'Booking Refund Request Processed',
                        'subject' => 'Cancellation Ref: {{CancellationId}} is Refunded | {{AgencyName}}',
                        'content' => "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
                            <html xmlns='http://www.w3.org/1999/xhtml'>
                            <head>
                            <meta http-equiv='Content-Type' content='text/html; cha₹et=iso-8859-1'>
                            <meta http-equiv='Content-Type' content='text/html; cha₹et=utf-8' />
                            </head>
                            
                            
                            <body style='margin:20px 0;padding:0;border:0;background:#f3f3f3;font-weight:normal;text-align:center;font-family:arial,verdana,sans-serif;color:#555555;font-size:15px;box-sizing:border-box;'>
                              <center>
                                <table style='margin:0;border:0;width:870px;background: #fff;padding: 15px' cellspacing='0'>
                                   <tr style='width:100%;'>
                                      <td style='color:#666;font-size:16px;'>
                                        <label style='margin:0px;width:100%;font-weight: bold;float: left;padding-bottom: 10px;'>Refund Request Processed Successfully!!</label>
                                        <label style='margin:0px;width:auto;padding: 5px 0px;'>Cancellation ID :</label>
                                        <p  style='margin:0px;width:58%;display:inline-block;padding: 5px 0px;'>{{CancellationId}}</p>
                                        <label style='margin:0px;width:auto;'>Cancellation Date :</label>
                                        <p  style='margin:0px;width:auto;display:inline-block;'>{{CancellationDate}}</p>
                                      </td>
                                      <td style='width:40%;'>
                                        <img src='{{AgencyLogo}}' width='160' height='' style='float: right;'>
                                      </td>
                                   </tr>
                                 </table>
                                 <table style='margin:20px 0 0 0;border:0;width:870px;background: #fff;padding: 15px' cellspacing='0'>
                                   <tr style='width:100%;'>
                                      <td style='color:#666;font-size:16px;width: 100%;float: left;'>
                                        <label style='margin:0px;width:100%;font-weight: 600;float: left;padding-bottom: 10px;'>Dear {{CustomerName}},,</label>
                                        <p  style='margin:0px;width:auto;display:inline-block;'>Refund of your Booking Ref {{BookingRef}} has been Processed successfully. Total Refund Amount:
                                          {{RefundAmount}} (Refund Charges have been waived off for you).</p>
                                      </td>
                                      <td style='color:#666;font-size:16px;width: 100%;float: left;'>
                                        <table style='margin:20px 0 0 0;padding:0;border:0;width:;border-collapse: collapse;width:100%' cellspacing='0'>
                                         <thead>
                                           <tr>
                                             <th style='font-size: 14px;padding-right: 15px !important;font-weight: 600;vertical-align: middle;font-weight: 600;padding: 10px 8px;color: #666;text-align: left;border: 1px solid #666'>Cancellation ID</th>
                                             <th style='font-size: 14px;padding-right: 15px !important;font-weight: 600;vertical-align: middle;font-weight: 600;padding: 10px 8px;color: #666;text-align: left;border: 1px solid #666'>Cancellation Status</th>
                                             <th style='font-size: 14px;padding-right: 15px !important;font-weight: 600;vertical-align: middle;font-weight: 600;padding: 10px 8px;color: #666;text-align: left;border: 1px solid #666'>Refund Status</th>
                                           </tr>
                                         </thead>
                                         <tbody>
                                           <tr>
                                             <td style='border-bottom: 1px solid rgba(230, 230, 230, 0.7);padding: 20px 0px 20px 10px;background: #fff;border: 1px solid #666'>{{CancellationId}}</td>
                                             <td style='border-bottom: 1px solid rgba(230, 230, 230, 0.7);padding: 20px 0px 20px 10px;background: #fff;border: 1px solid #666'>{{CancelationStatus}}</td>
                                             <td style='border-bottom: 1px solid rgba(230, 230, 230, 0.7);padding: 20px 0px 20px 10px;background: #fff;border: 1px solid #666'>{{RefundStatus}}</td>
                                           </tr>
                                         </tbody>
                                       </table>
                                      </td>
                                      <td style='color:#666;font-size:16px;width: 100%;float: left;'>
                                        <table style='margin:20px 0 0 0;padding:0;border:0;width:;border-collapse: collapse;width:100%' cellspacing='0'>
                                         <tbody>
                                          <tr>
                                             <td style='border-bottom: 1px solid rgba(230, 230, 230, 0.7);padding: 10px 0px 10px 10px;background: #fff;border: 1px solid #666'>
                                               <label style='margin:0px;width:auto;padding-bottom: 10px;'>Cancellation Process Date:</label>
                                              <p  style='margin:0px;width:auto;display:inline-block;'>{{CancelationDateTime}} </p>
                                             </td>
                                             <td style='border-bottom: 1px solid rgba(230, 230, 230, 0.7);padding: 10px 0px 10px 10px;background: #fff;border: 1px solid #666'>
                                               <label style='margin:0px;width:auto;padding-bottom: 10px;'>Refund Process Date:</label>
                                              <p  style='margin:0px;width:auto;display:inline-block;'>{{RefundDateTime}}</p>
                                             </td>
                                           </tr>
                                           <tr>
                                              <td style='border-bottom: 1px solid rgba(230, 230, 230, 0.7);padding: 10px 0px 10px 10px;background: #fff;border: 1px solid #666;'>
                                               <label style='margin:0px;width:auto;padding-bottom: 10px;'>Reason For Cancellation :</label>
                                              <p style='margin:0px;width:auto;display:inline-block;'>{{Reason}}</p>
                                             </td>
                                             <td style='border-bottom: 1px solid rgba(230, 230, 230, 0.7);padding: 10px 0px 10px 10px;background: #fff;border: 1px solid #666'>
                                               <label style='margin:0px;width:auto;padding-bottom: 10px;'>Refund Amount :</label>
                                              <p  style='margin:0px;width:auto;display:inline-block;'>{{RefundAmount}}</p>
                                             </td>
                                           </tr>
                                         </tbody>
                                       </table>
                                      </td>
                                     
                                   </tr>
                                 </table>
                                 <table style='margin:0px 0 0 0;border:0;width:870px;' cellspacing='0'>
                                     <tr style='background:#fff;padding:15px;box-sizing:border-box;float:left; width:100%;'>
                                       <td style='float:left; width: 100%; border-bottom:2px solid #ededed;'>
                                         <p style='margin:0px;width:auto;display:inline-block;font-size:18px;font-weight:normal;color:#666; text-transform: capitalize;'>Customer Support</p>
                                          </td>
                                         <td style='float: left; width: 65%'>
                                           <p style='color: #666; font-size: 16px; margin: 10px 0px 0px 0px; text-transform: capitalize;'>
                                            Corporate & Head Office:
                                           </p>
                                           <label style='color: #666; font-size: 16px; margin: 10px 0px 0px 0px; float: left;';>{{AgencyAddress}}</label>
                                         </td>
                            
                                           <td style='float: right; width: 30%; margin-top: 10px'>
                                             <p style='color: #666; font-size: 16px; margin: 10px 0px 0px 0px; text-transform: capitalize;'>
                                            Email:
                                           </p>
                                             <span style='color: #666; font-size: 16px'>{{AgencyEmail}}</span>
                                           </td>
                                           <td style='float: right; width: 30%; margin-top: 5px'>
                                              <p style='color: #666; font-size: 16px; margin: 10px 0px 0px 0px; text-transform: capitalize;'>
                                            Website:
                                           </p>
                                             <span style='color: #666; font-size: 16px'>{{AgencyUrl}}</span>
                                           </td>
                                           <td style='margin: 10px 0px 0px 0px; float: left;width: 30%'>
                                           <p style='color: #666; font-size: 16px; margin: 10px 0px 0px 0px; text-transform: capitalize;'>
                                            ContactNumber:
                                           </p>
                                           <span style='float: left; color: #04486b; font-size: 18px; margin: 5px 0px 0px 0px'>{{AgencySupportNumber}}</span>
                            
                                           <p style='float: left; margin: 0; color: #666;'>24*7 Available</p>
                                          </td> 
                                       </td> 
                                     </tr>
                                   </table>
                                  <table style='margin:20px 0 0 0;padding:0;border:0;width:870px;' cellspacing='0'>
                                     <tr style='background:#555555;padding:10px;box-sizing:border-box;float:left; width:100%;'>
                                       <td style='float: left;width: 100%'>
                                         <label style='color: #fff; font-size: 16px; float: left;'>NOTE :</label>
                                         <p style='color: #fff; font-size: 15px; float: right; width: 90%; margin: 0'>Please do not reply to this mail.It has been sent from an email account that is not monitored.</p>
                                       </td>
                                     </tr>
                                   </table>
                              </center>
                            </body>
                            </html>",
                        'language_code' => 'en',
                    ),
                    array(
                        'name' => 'تمت معالجة طلب استرداد الحجز',
                        'subject' => 'مرجع الإلغاء: تم استرداد {{CancellationId}} | {{AgencyName}}',
                        'content' => "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
                        <html xmlns='http://www.w3.org/1999/xhtml'>
                        <head>
                            <meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>
                            <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
                        </head>
                        
                        <body style='direction: rtl;margin:20px 0;padding:0;border:0;background:#f3f3f3;font-weight:normal;text-align:center;font-family:arial,verdana,sans-serif;color:#555555;font-size:15px;box-sizing:border-box;'>
                            <center>
                                <table style='margin:0;border:0;width:870px;background: #fff;padding: 15px' cellspacing='0'>
                                    <tr style='width:100%;'>
                                        <td style='color:#666;font-size:16px;'>
                                            <label style='margin:0px;width:100%;font-weight: bold;float: left;padding-bottom: 10px;'>تم معالجة طلب استرداد بنجاح!!</label>
                                            <label style='margin:0px;width:auto;padding: 5px 0px;'>رقم الإلغاء:</label>
                                            <p style='margin:0px;width:58%;display:inline-block;padding: 5px 0px;'>{{CancellationId}}</p>
                                            <label style='margin:0px;width:auto;'>تاريخ الإلغاء:</label>
                                            <p style='margin:0px;width:auto;display:inline-block;'>{{CancellationDate}}</p>
                                        </td>
                                        <td style='width:40%;'>
                                            <img src='{{AgencyLogo}}' width='160' height='' style='float: right;'>
                                        </td>
                                    </tr>
                                </table>
                                <table style='margin:20px 0 0 0;border:0;width:870px;background: #fff;padding: 15px' cellspacing='0'>
                                    <tr style='width:100%;'>
                                        <td style='color:#666;font-size:16px;width: 100%;float: left;'>
                                            <label style='margin:0px;width:100%;font-weight: 600;float: left;padding-bottom: 10px;'>عزيزي {{CustomerName}},,</label>
                                            <p style='margin:0px;width:auto;display:inline-block;'>تم معالجة استرداد حجزك بنجاح. إجمالي مبلغ الاسترداد:
                                                {{RefundAmount}} (تم إعفاء رسوم الاسترداد لك).</p>
                                        </td>
                                        <td style='color:#666;font-size:16px;width: 100%;float: left;'>
                                            <table style='margin:20px 0 0 0;padding:0;border:0;width:;border-collapse: collapse;width:100%' cellspacing='0'>
                                                <thead>
                                                    <tr>
                                                        <th style='font-size: 14px;padding-right: 15px !important;font-weight: 600;vertical-align: middle;font-weight: 600;padding: 10px 8px;color: #666;text-align: left;border: 1px solid #666'>رقم الإلغاء</th>
                                                        <th style='font-size: 14px;padding-right: 15px !important;font-weight: 600;vertical-align: middle;font-weight: 600;padding: 10px 8px;color: #666;text-...
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td style='border-bottom: 1px solid rgba(230, 230, 230, 0.7);padding: 20px 0px 20px 10px;background: #fff;border: 1px solid #666'>{{CancellationId}}</td>
                                                        <td style='border-bottom: 1px solid rgba(230, 230, 230, 0.7);padding: 20px 0px 20px 10px;background: #fff;border: 1px solid #666'>{{CancelationStatus}}</td>
                                                        <td style='border-bottom: 1px solid rgba(230, 230, 230, 0.7);padding: 20px 0px 20px 10px;background: #fff;border: 1px solid #666'>{{RefundStatus}}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                        <td style='color:#666;font-size:16px;width: 100%;float: left;'>
                                            <table style='margin:20px 0 0 0;padding:0;border:0;width:;border-collapse: collapse;width:100%' cellspacing='0'>
                                                <tbody>
                                                    <tr>
                                                        <td style='border-bottom: 1px solid rgba(230, 230, 230, 0.7);padding: 10px 0px 10px 10px;background: #fff;border: 1px solid #666'>
                                                            <label style='margin:0px;width:auto;padding-bottom: 10px;'>تاريخ عملية الإلغاء:</label>
                                                            <p style='margin:0px;width:auto;display:inline-block;'>{{CancelationDateTime}} </p>
                                                        </td>
                                                        <td style='border-bottom: 1px solid rgba(230, 230, 230, 0.7);padding: 10px 0px 10px 10px;background: #fff;border: 1px solid #666'>
                                                            <label style='margin:0px;width:auto;padding-bottom: 10px;'>تاريخ عملية الاسترداد:</label>
                                                            <p style='margin:0px;width:auto;display:inline-block;'>{{RefundDateTime}}</p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style='border-bottom: 1px solid rgba(230, 230, 230, 0.7);padding: 10px 0px 10px 10px;background: #fff;border: 1px solid #666'>
                                                            <label style='margin:0px;width:auto;padding-bottom: 10px;'>سبب الإلغاء :</label>
                                                            <p style='margin:0px;width:auto;display:inline-block;'>{{Reason}}</p>
                                                        </td>
                                                        <td style='border-bottom: 1px solid rgba(230, 230, 230, 0.7);padding: 10px 0px 10px 10px;background: #fff;border: 1px solid #666'>
                                                            <label style='margin:0px;width:auto;padding-bottom: 10px;'>مبلغ الاسترداد :</label>
                                                            <p style='margin:0px;width:auto;display:inline-block;'>{{RefundAmount}}</p>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                        
                                    </tr>
                                </table>
                                <table style='margin:0px 0 0 0;border:0;width:870px;' cellspacing='0'>
                                    <tr style='background:#fff;padding:15px;box-sizing:border-box;float:left; width:100%;'>
                                        <td style='float:left; width: 100%; border-bottom:2px solid #ededed;'>
                                            <p style='margin:0px;width:auto;display:inline-block;font-size:18px;font-weight:normal;color:#666; text-transform: capitalize;'>دعم العملاء</p>
                                        </td>
                                        <td style='float: left; width: 65%'>
                                            <p style='color: #666; font-size: 16px; margin: 10px 0px 0px 0px; text-transform: capitalize;'>
                                                الشركة الرئيسية والمقر الرئيسي:
                                            </p>
                                            <label style='color: #666; font-size: 16px; margin: 10px 0px 0px 0px; float: left;'>{{AgencyAddress}}</label>
                                        </td>
                                        <td style='float: right; width: 30%; margin-top: 10px'>
                                            <p style='color: #666; font-size: 16px; margin: 10px 0px 0px 0px; text-transform: capitalize;'>
                                                البريد الإلكتروني:
                                            </p>
                                            <span style='color: #666; font-size: 16px'>{{AgencyEmail}}</span>
                                        </td>
                                        <td style='float: right; width: 30%; margin-top: 5px'>
                                            <p style='color: #666; font-size: 16px; margin: 10px 0px 0px 0px; text-transform: capitalize;'>
                                                الموقع الإلكتروني:
                                            </p>
                                            <span style='color: #666; font-size: 16px'>{{AgencyUrl}}</span>
                                        </td>
                                        <td style='margin: 10px 0px 0px 0px; float: left;width: 30%'>
                                            <p style='color: #666; font-size: 16px; margin: 10px 0px 0px 0px; text-transform: capitalize;'>
                                                رقم الاتصال:
                                            </p>
                                            <span style='float: left; color: #04486b; font-size: 18px; margin: 5px 0px 0px 0px'>{{AgencySupportNumber}}</span>
                                            <p style='float: left; margin: 0; color: #666;'>متاح على مدار 24 ساعة</p>
                                        </td>
                                    </td>
                                    </tr>
                                </table>
                                <table style='margin:20px 0 0 0;padding:0;border:0;width:870px;' cellspacing='0'>
                                    <tr style='background:#555555;padding:10px;box-sizing:border-box;float:left; width:100%;'>
                                        <td style='float: left;width: 100%'>
                                            <label style='color: #fff; font-size: 16px; float: left;'>ملاحظة:</label>
                                            <p style='color: #fff; font-size: 15px; float: right; width: 90%; margin: 0'>يرجى عدم الرد على هذا البريد الإلكتروني. تم إرساله من حساب بريد إلكتروني غير مراقب.</p>
                                        </td>
                                    </tr>
                                </table>
                            </center>
                        </body>
                        </html>",
                        'language_code' => 'ar',
                    )
                ),
            ),

            array(
                'code' => 'DEPOSIT_REQUEST_GENERATION',
                'from_email' => 'do-not-reply@travelportal.com',
                'to_email' => '',
                'cc' => '',
                'bcc' => '',
                'mail_data' => array(
                    array(
                        'name' => 'Deposit Request Generation',
                        'subject' => 'New Deposit Request From {{AgencyName}}',
                        'content' => "'New Deposite Request has generated, following are details,

                            Deposite Request Information
                            Agency Name : {{AgencyName}}
                            Agency Code : {{AgencyCode}}
                            Deposite Amount Request : {{DepositeAmount}}
                            Date and Time of Deposite : {{Date}} {{Time}}
                            Type of Deposite : {{TypeOfDeposite}}
                            Beneficiary Bank : {{BamificiaryBank}}
                            Cheque/DD No. : {{ChequeNumber}}
                            Remark : {{Remark}}'",
                        'language_code' => 'en',
                    ),
                    array(
                        'name' => 'إنشاء طلب الإيداع',
                        'subject' => 'طلب إيداع جديد من {{AgencyName}}',
                        'content' => "                        معلومات طلب الإيداع
                        اسم الوكالة : {{AgencyName}}
                        رمز الوكالة : {{AgencyCode}}
                        المبلغ المطلوب للإيداع : {{DepositeAmount}}
                        تاريخ ووقت الإيداع : {{Date}} {{Time}}
                        نوع الإيداع : {{TypeOfDeposite}}
                        البنك المستفيد : {{BamificiaryBank}}
                        رقم الشيك/الحوالة : {{ChequeNumber}}
                        ملاحظة : {{Remark}}'",
                        'language_code' => 'ar',
                    )
                ),
            ),

            array(
                'code' => 'AGENCY_DEPOSIT_ACCOUNT_CREDITED',
                'from_email' => 'do-not-reply@travelportal.com',
                'to_email' => '',
                'cc' => '',
                'bcc' => '',
                'mail_data' => array(
                    array(
                        'name' => 'Agency Deposit Account Credited',
                        'subject' => 'Txn:  {{TransacrionId}} | Account Credited | {{AgencyName}}',
                        'content' => "'Account Credited Successfully, following are details,

                            Deposite Request Information
                            Agency Name : {{AgencyName}}
                            Agency Code : {{AgencyCode}}
                            Transaction Id : {{TransactionId}}
                            Credited  Amount  : {{CreditedAmount}}
                            Date and Time : {{DateTime}}
                            '",
                        'language_code' => 'en',
                    ),
                    array(
                        'name' => 'تم اعتماد حساب إيداع الوكالة',
                        'subject' => 'Txn: {{TransacrionId}} | تم اعتماد الحساب | {{AgencyName}}',
                        'content' => "'تم تصفية الحساب بنجاح، وفيما يلي التفاصيل،
                        معلومات طلب الإيداع
                        اسم الوكالة: {{AgencyName}}
                        رمز الوكالة: {{AgencyCode}}
                        معرف العملية: {{TransactionId}}
                        المبلغ المُعتمَد: {{CreditedAmount}}
                        التاريخ والوقت: {{DateTime}}'",
                        'language_code' => 'ar',
                    )
                ),
            ),
            array(
                'code' => 'TOP_UP_DONE_SUCCESSFULLY',
                'from_email' => 'do-not-reply@travelportal.com',
                'to_email' => '',
                'cc' => '',
                'bcc' => '',
                'mail_data' => array(
                    array(
                        'name' => 'Top up Done Successfully',
                        'subject' => 'Txn:  {{TransacrionId}} | Top up Done Successfully | {{AgencyName}}',
                        'content' => "'Account Top Up Done Successfully, following are details,

                            Top Up Information
                            Transaction Id : {{TransactionId}}
                            Top Up  Amount  :{{TopUpAmount}}
                            Date and Time : {{DateTime}}
                            '",
                        'language_code' => 'en',
                    ),
                    array(
                        'name' => 'تم تعبئة الرصيد بنجاح',
                        'subject' => 'Txn: {{TransacrionId}} | تمت عملية تعبئة الرصيد بنجاح | {{AgencyName}}',
                        'content' => "'تم إيداع الأموال بنجاح، وفيما يلي التفاصيل،
                        معلومات الإيداع
                        رقم المعاملة: {{TransactionId}}
                        المبلغ المودع: {{TopUpAmount}}
                        التاريخ والوقت: {{DateTime}}'",
                        'language_code' => 'ar',
                    )
                ),
            ),

            array(
                'code' => 'SUPPLIER_CANCELLATION_REQUEST_PROCESSED',
                'from_email' => 'do-not-reply@travelportal.com',
                'to_email' => '',
                'cc' => '',
                'bcc' => '',
                'mail_data' => array(
                    array(
                        'name' => 'Booking Cancellation Request Processed',
                        'subject' => 'Cancellation Ref: {{CancellationId}} is Processed |  {{BookingRef}} ',
                        'content' => "<p>'Cancellation Request Processed Successfully!! Cancellation ID :{{CancellationId}} Cancellation Date :{{CancellationDate}} Dear {{CustomerName}}, Cancellation of your Tour Booking Ref {{Booking Ref}} with Supplier Confirmation Number {{SupplierConfirmation}} has been Processed successfully. Total Cancellation Charge: {{Currency}} {{CancellationAmount}} (Cancellation Charges have been waived off for you). Cancellation ID : {{CancellationId}} Cancellation Status : {{CancelationStatus}} Refund Amount : {{Refund Amount}} Refund Status : {{RefundStatus}} Cancellation Process Date Time :{{CancelationDateTime}} Refund Process Date Time :Pending Cancellation Charges :{{CancelationCharge}} Refund Amount :Pending Reason For Cancellation :{{Reason}} Passenger Name : {{PassangerName}} Customer Support Corporate &amp; Head Office : {{AgencyAddress}} Email:{{AgencyEmail}} URL:{{AgencyUrl}} Number: {{AgencySupportNumber}} NOTE :Please do not reply to this mail.It has been sent from an email account that is not monitored.'&nbsp;</p>",
                        'language_code' => 'en',
                    ),
                    array(
                        'name' => 'تمت معالجة طلب إلغاء الحجز',
                        'subject' => 'مرجع الإلغاء: تمت معالجة {{CancellationId}} | {{BookingRef}}',
                        'content' => "<p>تم معالجة طلب الإلغاء بنجاح!! رقم الإلغاء: {{CancellationId}} تاريخ الإلغاء: {{CancellationDate}} عزيزي {{CustomerName}}, تم معالجة إلغاء حجز جولتك المرجعي {{Booking Ref}} برقم تأكيد المورد {{SupplierConfirmation}} بنجاح. إجمالي رسوم الإلغاء: {{Currency}} {{CancellationAmount}} (تم تخفيف رسوم الإلغاء بالنسبة لك). رقم الإلغاء: {{CancellationId}} حالة الإلغاء: {{CancelationStatus}} مبلغ الاسترداد: {{Refund Amount}} حالة الاسترداد: {{RefundStatus}} تاريخ ووقت عملية الإلغاء: {{CancelationDateTime}} تاريخ ووقت عملية الاسترداد: قيد المعالجة رسوم الإلغاء: {{CancelationCharge}} مبلغ الاسترداد: قيد المعالجة سبب الإلغاء: {{Reason}} اسم المسافر: {{PassangerName}} دعم العملاء المؤسسي والمكتب الرئيسي: {{AgencyAddress}} البريد الإلكتروني: {{AgencyEmail}} عنوان الموقع الإلكتروني: {{AgencyUrl}} رقم الاتصال: {{AgencySupportNumber}} ملاحظة: يرجى عدم الرد على هذا البريد. تم إرساله من حساب بريد إلكتروني غير مراقب.</p>
                        ",
                        'language_code' => 'ar',
                    )
                ),
            ),

            array(
                'code' => 'WELCOME_AGENCY',
                'from_email' => 'do-not-reply@travelportal.com',
                'to_email' => '',
                'cc' => '',
                'bcc' => '',
                'mail_data' => array(
                    array(
                        'name' => 'Welcome Agency',
                        'subject' => 'Welcome to {{AgencyName}}!!',
                        'content' => "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
                            <html xmlns='http://www.w3.org/1999/xhtml'>
                            <head>
                            <meta name='viewport' content='width=device-width' />
                            <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
                           
                            <link href='pages/css/mail.css' media='all' rel='stylesheet' type='text/css' />
                            </head>
                            
                            <body itemscope itemtype='http://schema.org/EmailMessage' style='-webkit-font-smoothing: antialiased;-webkit-text-size-adjust: none;width: 100% !important;height: 100%;line-height: 1.6;background-color: #f6f6f6;margin: 0;font-family: 'Raleway', sans-serif;box-sizing: border-box;font-size: 14px;'>
                            
                            <table class='body-wrap' style='background-color: #f6f6f6;width: 100%;padding-top: 40px;'>
                                <tr>
                                    <td></td>
                                    <td class='container' width='600' style='display: block !important;max-width: 600px !important;margin: 0 auto !important;clear: both !important;'>
                                        <div class='content' style='max-width: 600px;margin: 0 auto;display: block;padding: 20px;'>
                                            <table class='main' width='100%' cellpadding='0' cellspacing='0' style='background-color: #fff;border: 1px solid #e9e9e9;border-radius: 3px;border: 1px solid #999;'>
                                               
                                                <tr>
                                                    <td class='content-wrap' style='padding: 25px;'>
                                                        <table width='100%' cellpadding='0' cellspacing='0'> 
                                                             <tr>
                                                               <td style='text-align: center; padding: 0 0 15px'>
                                                                   
                                                                         <img src='{{AgencyLogo}}' width='250'>
                                                                    
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class='content-block' style='padding: 0 0 15px;vertical-align: top;'>
                                                                    Dear {{UserName}},  
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class='content-block' style='padding: 0 0 15px;vertical-align: top;'>
                                                                    <p style='font-weight: normal;font-size: 14px; margin: 0;'>Welcome to the world of {{AgencyName}}.</p>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class='content-block' style='padding: 0 0 15px;vertical-align: top;'>
                                                                    We are excited that you have registered with {{AgencyName}} and look forward to meeting all your travel needs!
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class='content-block' style='padding: 0 0 15px;vertical-align: top;'>
                                                                    Please note that the email address we have on file for you is {{UserName}}.		
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style='vertical-align: top; padding:15px 0px 0px 0px'>
                                                                    It's our pleasure to help you see the world your way!!<br>
                                                                    Sincerely, <br>
                                                                    {{AgencyName}} Team!!
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </td>
                                    <td></td>
                                </tr>
                            </table>
                            
                            </body>
                            </html>
                            ",
                        'language_code' => 'en',
                    ),
                    array(
                        'name' => 'وكالة الترحيب',
                        'subject' => 'مرحبًا بك في {{AgencyName}}!!',
                        'content' => "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
                        <html xmlns='http://www.w3.org/1999/xhtml'>
                        <head>
                        <meta name='viewport' content='width=device-width' />
                        <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
                      
                        <link href='pages/css/mail.css' media='all' rel='stylesheet' type='text/css' />
                        </head>
                        
                        <body itemscope itemtype='http://schema.org/EmailMessage' style='direction: rtl;-webkit-font-smoothing: antialiased;-webkit-text-size-adjust: none;width: 100% !important;height: 100%;line-height: 1.6;background-color: #f6f6f6;margin: 0;font-family: 'Raleway', sans-serif;box-sizing: border-box;font-size: 14px;'>
                        <table class='body-wrap' style='background-color: #f6f6f6;width: 100%;padding-top: 40px;'>
                            <tr>
                                <td></td>
                                <td class='container' width='600' style='display: block !important;max-width: 600px !important;margin: 0 auto !important;clear: both !important;'>
                                    <div class='content' style='max-width: 600px;margin: 0 auto;display: block;padding: 20px;'>
                                        <table class='main' width='100%' cellpadding='0' cellspacing='0' style='background-color: #fff;border: 1px solid #e9e9e9;border-radius: 3px;border: 1px solid #999;'>
                                           
                                            <tr>
                                                <td class='content-wrap' style='padding: 25px;'>
                                                    <table width='100%' cellpadding='0' cellspacing='0'> 
                                                         <tr>
                                                           <td style='text-align: center; padding: 0 0 15px'>
                                                               
                                                                     <img src='{{AgencyLogo}}' width='250'>
                                                                
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class='content-block' style='padding: 0 0 15px;vertical-align: top;'>
                                                            عزيزي {{UserName}},  
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class='content-block' style='padding: 0 0 15px;vertical-align: top;'>
                                                            <p style='font-weight: normal;font-size: 14px; margin: 0;'>مرحبًا بك في عالم {{AgencyName}}.</p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class='content-block' style='padding: 0 0 15px;vertical-align: top;'>
                                                            نحن متحمسون لأنك قد قمت بالتسجيل مع {{AgencyName}} ونتطلع إلى تلبية جميع احتياجات سفرك!
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class='content-block' style='padding: 0 0 15px;vertical-align: top;'>
                                                            يرجى ملاحظة أن عنوان البريد الإلكتروني الذي لدينا لك هو {{UserName}}.		
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style='vertical-align: top; padding:15px 0px 0px 0px'>
                                                            من دواعي سرورنا مساعدتك في رؤية العالم بطريقتك الخاصة!!
                                                            بصدق، 
                                                            فريق {{AgencyName}}!!
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </td>
                            <td></td>
                        </tr>
                        </table>
                        </body>
                        </html>",
                        'language_code' => 'ar',
                    )
                ),
            ),

            array(
                'code' => 'B2B_QUOTATION_MAIL',
                'from_email' => 'do-not-reply@travelportal.com',
                'to_email' => '',
                'cc' => '',
                'bcc' => '',
                'mail_data' => array(
                    array(
                        'name' => 'b2b Quotation mail',
                        'subject' => 'Itinerary Quotation from {{AgencyName}}!!',
                        'content' => "<p>User Sign up <img style='width: 250px;' src='{{AgencyLogo}}'> Here's That tour you liked {{AgencyName}} sent yourself a reminder to check out this tour. And it makes us happy that you're thinking about your next travel experience! You can get one step closer to booking by reviewing the tour's highlights and checking the availability to find travel dates that fit your schedule. It's our pleasure to help you see the world your way!!</p>
                            <p>Sincerely,</p>
                            <p>&nbsp;</p>
                            <p>{{AgencyName}} Team!!</p>",
                        'language_code' => 'en',
                    ),
                    array(
                        'name' => 'بريد اقتباس B2B',
                        'subject' => 'عرض أسعار خط سير الرحلة من {{AgencyName}}!!',
                        'content' => "<p>تسجيل المستخدم <img style='width: 250px;' src='{{AgencyLogo}}'> هذا الجولة التي أعجبتك {{AgencyName}} أرسلت لنفسك تذكيرًا بالتحقق من هذه الجولة. ويسعدنا أن تفكر في تجربتك السفر القادمة! يمكنك الاقتراب خطوة واحدة من الحجز من خلال مراجعة أبرز الجولة والتحقق من التوافر للعثور على تواريخ السفر التي تتناسب مع جدولك. من دواعي سرورنا مساعدتك في رؤية العالم بطريقتك!</p>
                        <p>بصدق،</p>
                        <p>&nbsp;</p>
                        <p>فريق {{AgencyName}}!!</p>",
                        'language_code' => 'ar',
                    )
                ),
            ),
            array(
                'code' => 'SEND_OTP',
                'from_email' => 'do-not-reply@travelportal.com',
                'to_email' => '',
                'cc' => '',
                'bcc' => '',
                'mail_data' => array(
                    array(
                        'name' => 'Send OTP',
                        'subject' => 'Send Otp for Rehlte Application.',
                        'content' => "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
                        <html xmlns='http://www.w3.org/1999/xhtml'>
                        <head>
                        <meta name='viewport' content='width=device-width' />
                        <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
                        <link href='pages/css/mail.css' media='all' rel='stylesheet' type='text/css' />
                        </head>
                        
                        <body itemscope itemtype='http://schema.org/EmailMessage' style='-webkit-font-smoothing: antialiased;-webkit-text-size-adjust: none;width: 100% !important;height: 100%;line-height: 1.6;background-color: #f6f6f6;margin: 0;font-family: 'Raleway', sans-serif;box-sizing: border-box;font-size: 14px;'>
                        
                        <table class='body-wrap' style='background-color: #f6f6f6;width: 100%;padding-top: 40px;'>
                            <tr>
                                <td></td>
                                <td class='container' width='600' style='display: block !important;max-width: 600px !important;margin: 0 auto !important;clear: both !important;'>
                                    <div class='content' style='max-width: 600px;margin: 0 auto;display: block;padding: 20px;'>
                                        <table class='main' width='100%' cellpadding='0' cellspacing='0' style='background-color: #fff;border: 1px solid #e9e9e9;border-radius: 3px;border: 1px solid #999;'>
                                           
                                            <tr>
                                                <td class='content-wrap' style='padding: 25px;'>
                                                    <table width='100%' cellpadding='0' cellspacing='0'> 
                                                         <tr>
                                                           <td style='text-align: center; padding: 0 0 15px'>
                                                               
                                                                     <img src='{{AgencyLogo}}' width='250'>
                                                                
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class='content-block' style='padding: 0 0 15px;vertical-align: top;'>
                                                                Dear {{CustomerName}},  
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class='content-block' style='padding: 0 0 15px;vertical-align: top;'>
                                                            Greetings from {{AgencyName}}!!
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class='content-block' style='padding: 0 0 15px;vertical-align: top;'>
                                                            Kindly use this OTP : {{Otp}} to verify your email address.
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class='content-block' style='padding: 0 0 15px;vertical-align: top;'>
                                                            OTP is your one time password to proceed on {{SiteName}} It is valid for {{Otp_expire_minute}} minutes.
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class='content-block' style='padding: 0 0 15px;vertical-align: top;'>
                                                            Please do not share OTP with anyone.		
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style='vertical-align: top; padding:15px 0px 0px 0px'>
                                                                Thanks, <br>
                                                                {{AgencyName}} Team!!
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </td>
                                <td></td>
                            </tr>
                        </table>
                        
                        </body>
                        </html>",
                        'language_code' => 'en',
                    ),
                    array(
                        'name' => 'أرسل كلمة مرور لمرة واحدة',
                        'subject' => 'أرسل Otp لتطبيق Rehlte.',
                        'content' => "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
                        <html xmlns='http://www.w3.org/1999/xhtml'>
                        <head>
                        <meta name='viewport' content='width=device-width' />
                        <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
                        <link href='pages/css/mail.css' media='all' rel='stylesheet' type='text/css' />
                        </head>
                        
                        <body itemscope itemtype='http://schema.org/EmailMessage' style='-webkit-font-smoothing: antialiased;-webkit-text-size-adjust: none;width: 100% !important;height: 100%;line-height: 1.6;background-color: #f6f6f6;margin: 0;font-family: 'Raleway', sans-serif;box-sizing: border-box;font-size: 14px;'>
                        
                        <table class='body-wrap' style='background-color: #f6f6f6;width: 100%;padding-top: 40px;'>
                            <tr>
                                <td></td>
                                <td class='container' width='600' style='display: block !important;max-width: 600px !important;margin: 0 auto !important;clear: both !important;'>
                                    <div class='content' style='max-width: 600px;margin: 0 auto;display: block;padding: 20px;'>
                                        <table class='main' width='100%' cellpadding='0' cellspacing='0' style='background-color: #fff;border: 1px solid #e9e9e9;border-radius: 3px;border: 1px solid #999;'>
                                           
                                            <tr>
                                                <td class='content-wrap' style='padding: 25px;'>
                                                    <table width='100%' cellpadding='0' cellspacing='0'> 
                                                         <tr>
                                                           <td style='text-align: center; padding: 0 0 15px'>
                                                               
                                                                     <img src='{{AgencyLogo}}' width='250'>
                                                                
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                        <td class='content-block' style='padding: 0 0 15px;vertical-align: top;'>
                                                        عزيزي {{CustomerName}}،  
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class='content-block' style='padding: 0 0 15px;vertical-align: top;'>
                                                        تحية من {{AgencyName}}!!
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class='content-block' style='padding: 0 0 15px;vertical-align: top;'>
                                                        يرجى استخدام كلمة المرور لمرة واحدة (OTP): {{Otp}} للتحقق من عنوان بريدك الإلكتروني.
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class='content-block' style='padding: 0 0 15px;vertical-align: top;'>
                                                        OTP هي كلمة المرور التي تستخدم لمرة واحدة للمتابعة على {{SiteName}} وهي صالحة لمدة {{Otp_expire_minute}} دقيقة.
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class='content-block' style='padding: 0 0 15px;vertical-align: top;'>
                                                        من فضلك لا تشارك OTP مع أي شخص.	
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style='vertical-align: top; padding:15px 0px 0px 0px'>
                                                        شكرا <br>
                                                        فريق {{AgencyName}}!!
                                                        </td>
                                                    </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </td>
                                <td></td>
                            </tr>
                        </table>
                        
                        </body>
                        </html>",
                        'language_code' => 'ar',
                    )
                ),
            ),

        );

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \App\Models\MailTemplateI18ns::truncate();
        MailTemplate::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        foreach ($mailData as $key => $mail) {
            MailTemplate::createSeederMailTemplates($mail);
        }
    }
}
