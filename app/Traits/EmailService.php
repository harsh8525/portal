<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


namespace App\Traits;

use App\Models\Setting;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Config;
use App\Models\MailTemplate;
use DateTime;

trait EmailService
{

    public function passwordExpiryMailTemplate($code, $data = [])
    {

        // Get code and check that code exists or not.
        $mailTemplate = DB::table('core_mail_templates')->where('code', $code)->first();
        if (empty($mailTemplate)) {
            return (['status' => 'false', 'error' => 'Service Email Not Started!!']);
        } else {
            $contentData = $mailTemplate->content;
            $subjectData = $mailTemplate->subject;
            // find value and replace with particular data
            $contentData = (str_replace('{{AgencyLogo}}', $data['agency_logo'], $contentData));
            $contentData = (str_replace('{{CustomerName}}', $data['customer_name'], $contentData));
            $contentData = (str_replace('{{AgencyName}}', ucwords($data['agency_name']), $contentData));
            $contentData = (str_replace('{{DateTime}}', date('Y-m-d h:i:s'), $contentData));
            $contentData = (str_replace('{{PasswordExpiryDay}}', $data['password_expiry_day'] ?? "", $contentData));
            $subject = (str_replace('{{AgencyName}}', $data['agency_logo'], $subjectData));
            $data = ['mailData' => $contentData, 'subject' => $subject];
            return (['status' => 'true', 'data' => $data]);
        }
    }
    /**
     * replace contect with dynamic values in change password mail template
     * created date 11-07-2023
     */
    public function changePasswordMailTemplate($code, $data = [], $language_code)
    {

        // Get code and check that code exists or not.
        $query = MailTemplate::select('id', 'code')
            ->with(['mailCodeNameSingle' => function ($query) use ($language_code) {
                $query->select(['mail_id', 'content', 'subject', 'language_code'])
                    ->where('language_code', $language_code);
            }])
            ->where('code', $code)
            ->whereHas('mailCodeNameSingle', function ($q) use ($language_code) {
                $q->where('language_code', $language_code);
            })
            ->first();

        if (empty($query)) {
            return (['status' => 'false', 'error' => 'Service Email Not Started!!']);
        } else {
            $contentData = $query->mailCodeNameSingle->content;

            $subjectData = $query->mailCodeNameSingle->subject;
            $contentData = (str_replace('{{AgencyLogo}}', $data['agency_logo'], $contentData));
            $contentData = (str_replace('{{CustomerName}}', $data['customer_name'], $contentData));
            $contentData = (str_replace('{{AgencyName}}', ucwords($data['agency_name']), $contentData));
            $contentData = (str_replace('{{DateTime}}', date('Y-m-d h:i:s'), $contentData));
            $subject = (str_replace('{{AgencyName}}', $data['agency_name'], $subjectData));
            $data = ['mailData' => $contentData, 'subject' => $subject];
            return (['status' => 'true', 'data' => $data]);
        }
    }

    /**
     * replace data into user signup mail template
     * created date 08-07-2023
     */
    public static function userSignUpMailTemplate($code, $data = [], $language_code)
    {
        // Get code and check that code exists or not.
        $query = MailTemplate::select('id', 'code')
            ->with(['mailCodeNameSingle' => function ($query) use ($language_code) {
                $query->select(['mail_id', 'content', 'subject', 'language_code'])
                    ->where('language_code', $language_code);
            }])
            ->where('code', $code)
            ->whereHas('mailCodeNameSingle', function ($q) use ($language_code) {
                $q->where('language_code', $language_code);
            })
            ->first();
        if (empty($query)) {

            return (['status' => 'false', 'error' => 'Service Email Not Started!!']);
        } else {
            $click_txt = '';
            if ($language_code == 'en') {
                $click_txt = 'Click Here';
            }

            if ($language_code == 'ar') {
                $click_txt = 'انقر هنا';
            }
            $contentData = $query->mailCodeNameSingle->content;

            $subjectData = $query->mailCodeNameSingle->subject;
            
            $contentData = (str_replace('{{AgencyLogo}}', $data['agency_logo'], $contentData));
            $contentData = (str_replace('{{UserName}}', $data['user_name'], $contentData));
            $contentData = (str_replace('{{AgencyName}}', ucwords($data['site_name']), $contentData));
            $contentData = (str_replace('{{click here}}', '<a href="' . $data['activation_link'] . '" target="_blank">' . $click_txt . '</a>', $contentData));
            $contentData = (str_replace('{{ActivationLink}}', $data['activation_link'], $contentData));
            $subject = (str_replace('{{AgencyName}}', $data['site_name'], $subjectData));
            $data = ['mailData' => $contentData, 'subject' => $subject];
            return (['status' => 'true', 'data' => $data]);
        }
    }
    /**
     * replace data into user account activation mail template
     * created date 11-07-2023
     */
    public static function userAccountActivationMailTemplate($code, $data = [])
    {

        // Get code and check that code exists or not.
        $mailTemplate = DB::table('core_mail_templates')->where('code', $code)->first();
        $userData = processSimpleTemplate($data);
        if (empty($mailTemplate)) {
            return (['status' => 'false', 'error' => 'Service Email Not Started!!']);
        } else {
            $contentData = $mailTemplate->content;
            $subjectData = $mailTemplate->subject;
            $contentData = (str_replace('{{AgencyLogo}}', $userData['agency_logo'], $contentData));
            $contentData = (str_replace('{{UserName}}', $userData['user_name'], $contentData));
            $contentData = (str_replace('{{AgencyName}}', $userData['agency_name'], $contentData));
            $subject = (str_replace('{{AgencyName}}', $userData['agency_name'], $subjectData));
            $data = ['mailData' => $contentData, 'subject' => $subject];
            return (['status' => 'true', 'data' => $data, 'agencyName' => $userData['agency_name']]);
        }
    }
    public static function customerSignUp($code, $data = [], $language_code = 'en')
    {
        // Get code and check that code exists or not.
        $query = MailTemplate::select('id', 'code')
            ->with(['mailCodeNameSingle' => function ($query) use ($language_code) {
                $query->select(['mail_id', 'content', 'subject', 'language_code'])
                    ->where('language_code', $language_code);
            }])
            ->where('code', $code)
            ->whereHas('mailCodeNameSingle', function ($q) use ($language_code) {
                $q->where('language_code', $language_code);
            })
            ->first();
        if (empty($query)) {

            return (['status' => 'false', 'error' => 'Service Email Not Started!!']);
        } else {
            $click_txt = '';
            if ($language_code == 'en') {
                $click_txt = 'Click Here';
            }

            if ($language_code == 'ar') {
                $click_txt = 'انقر هنا';
            }
            $contentData = $query->mailCodeNameSingle->content;

            $subjectData = $query->mailCodeNameSingle->subject;

            $contentData = (str_replace('{{CustomerName}}', $data['first_name'], $contentData));
            $contentData = (str_replace('{{AgencyName}}', ucwords($data['agency_name']), $contentData));
            $contentData = (str_replace('{{CustomerEmail}}', strtolower($data['email']), $contentData));
            $contentData = (str_replace('{{click here}}', '<a href="' . $data['activation_link'] . '" target="_blank">' . $click_txt . '</a>', $contentData));
            $contentData = (str_replace('{{AgencyLogo}}', $data['agency_logo'], $contentData));
            $contentData = (str_replace('{{ActivationLink}}', $data['activation_link'], $contentData));
            $subject = (str_replace('{{AgencyName}}', $data['agency_name'], $subjectData));

            $data = ['mailData' => $contentData, 'subject' => $subject];
            return (['status' => 'true', 'data' => $data]);
        }
    }
    /**
     * replace data into user welcome agency mail template
     * created date 04-08-2023
     */
    public static function welcomeAgencyMailTemplate($code, $data = [], $language_code = 'en')
    {

        // Get code and check that code exists or not.
        $query = MailTemplate::select('id', 'code')
            ->with(['mailCodeNameSingle' => function ($query) use ($language_code) {
                $query->select(['mail_id', 'content', 'subject', 'language_code'])
                    ->where('language_code', $language_code);
            }])
            ->where('code', $code)
            ->whereHas('mailCodeNameSingle', function ($q) use ($language_code) {
                $q->where('language_code', $language_code);
            })
            ->first();
        $userData = processSimpleTemplate($data);
        if (empty($query)) {

            return (['status' => 'false', 'error' => 'Service Email Not Started!!']);
        } else {

            $contentData = $query->mailCodeNameSingle->content;
            $subjectData = $query->mailCodeNameSingle->subject;

            $contentData = (str_replace('{{AgencyLogo}}', $userData['agency_logo'], $contentData));
            $contentData = (str_replace('{{UserName}}', $userData['user_name'], $contentData));
            $contentData = (str_replace('{{AgencyName}}', $userData['agency_name'], $contentData));
            $subject = (str_replace('{{AgencyName}}', $userData['agency_name'], $subjectData));
            $data = ['mailData' => $contentData, 'subject' => $subject];
            return (['status' => 'true', 'data' => $data, 'agencyName' => $userData['agency_name']]);
        }
    }
    /**
     * replace data into customer account welcome mail template
     * created date 27-10-2023
     */
    public static function customerWelcomeMailTemplete($code, $data = [], $language_code = 'en')
    {
        // Get code and check that code exists or not.
        $query = MailTemplate::select('id', 'code')
            ->with(['mailCodeNameSingle' => function ($query) use ($language_code) {
                $query->select(['mail_id', 'content', 'subject', 'language_code'])
                    ->where('language_code', $language_code);
            }])
            ->where('code', $code)
            ->whereHas('mailCodeNameSingle', function ($q) use ($language_code) {
                $q->where('language_code', $language_code);
            })
            ->first();
        if (empty($query)) {

            return (['status' => 'false', 'error' => 'Service Email Not Started!!']);
        } else {

            $contentData = $query->mailCodeNameSingle->content;
            $subjectData = $query->mailCodeNameSingle->subject;

            $contentData = (str_replace('{{AgencyLogo}}', $data['agency_logo'], $contentData));
            $contentData = (str_replace('{{UserName}}', $data['customer_name'], $contentData));
            $contentData = (str_replace('{{AgencyName}}', ucwords($data['agency_name']), $contentData));
            $subject = (str_replace('{{AgencyName}}', $data['agency_name'], $subjectData));
            $data = ['mailData' => $contentData, 'subject' => $subject];
            return (['status' => 'true', 'data' => $data]);
        }
    }
    /**
     * replace data into customer OTP send mail template
     * created date 07-12-2023
     */
    public static function customerSendOTPTemplete($code, $data = [], $language_code = 'en')
    {
        // Get code and check that code exists or not.
        $query = MailTemplate::select('id', 'code')
            ->with(['mailCodeNameSingle' => function ($query) use ($language_code) {
                $query->select(['mail_id', 'content', 'subject', 'language_code'])
                    ->where('language_code', $language_code);
            }])
            ->where('code', $code)
            ->whereHas('mailCodeNameSingle', function ($q) use ($language_code) {
                $q->where('language_code', $language_code);
            })
            ->first();
        if (empty($query)) {

            return (['status' => 'false', 'error' => 'Service Email Not Started!!']);
        } else {
            $contentData = $query->mailCodeNameSingle->content;

            $subjectData = $query->mailCodeNameSingle->subject;
            $contentData = (str_replace('{{AgencyLogo}}', $data['agencyLogo'], $contentData));
            $contentData = (str_replace('{{AgencyName}}', $data['agencyName'], $contentData));
            $contentData = (str_replace('{{CustomerName}}', $data['customer_name'], $contentData));
            $contentData = (str_replace('{{Otp}}', $data['otp'], $contentData));
            $contentData = (str_replace('{{Otp_expire_minute}}', $data['otp_expire_minute'], $contentData));
            // $contentData = (str_replace('{{AgencyName}}', $data['site_name'], $contentData));
            $contentData = (str_replace('{{SiteName}}', $data['site_name'], $contentData));
            $subject = $subjectData;
            $data = ['mailData' => $contentData, 'subject' => $subject];
            return (['status' => 'true', 'data' => $data]);
        }
    }
    //notify user when account has been bloked
    public function mailTemplateBlockAccount($code, $data = [], $language_code = 'en')
    {

        // Get code and check that code exists or not.
        $query = MailTemplate::select('id', 'code')
            ->with(['mailCodeNameSingle' => function ($query) use ($language_code) {
                $query->select(['mail_id', 'content', 'subject', 'language_code'])
                    ->where('language_code', $language_code);
            }])
            ->where('code', $code)
            ->whereHas('mailCodeNameSingle', function ($q) use ($language_code) {
                $q->where('language_code', $language_code);
            })
            ->first();
        if (empty($query)) {

            return (['status' => 'false', 'error' => 'Service Email Not Started!!']);
        } else {

            $contentData = $query->mailCodeNameSingle->content;
            $subjectData = $query->mailCodeNameSingle->subject;
            $contentData = (str_replace('{{CustomerName}}', $data['customer_name'], $contentData));
            $contentData = (str_replace('{{AgencyName}}', ucwords($data['agency_name']), $contentData));
            $contentData = (str_replace('{{Hours}}', $data['hours'] . " " . $data['duration'], $contentData));
            $contentData = (str_replace('{{AgencyLogo}}', $data['agency_logo'], $contentData));

            $subject = (str_replace('{{AgencyName}}', $data['agency_name'], $subjectData));
            $data = ['mailData' => $contentData, 'subject' => $subject];
            return (['status' => 'true', 'data' => $data]);
        }
    }
    /**
     * replace data into user agency block mail template
     * created date 07-08-2023
     */
    public static function agencyBlockMailTemplate($code, $data = [])
    {

        // Get code and check that code exists or not.
        $mailTemplate = DB::table('core_mail_templates')->where('code', $code)->first();

        if (empty($mailTemplate)) {
            return (['status' => 'false', 'error' => 'Service Email Not Started!!']);
        } else {
            $contentData = $mailTemplate->content;
            $subjectData = $mailTemplate->subject;
            $contentData = (str_replace('{{AgencyLogo}}', $data['agency_logo'], $contentData));
            $contentData = (str_replace('{{AgencyName}}', ucwords($data['agency_name']), $contentData));
            $subject = (str_replace('{{AgencyName}}', $data['agency_name'], $subjectData));
            $data = ['mailData' => $contentData, 'subject' => $subject];
            return (['status' => 'true', 'data' => $data]);
        }
    }
    /**
     * replace data into user forgot password mail template
     * created date 16-09-2023
     */
    public function forgotPasswordMailTemplate($code, $data = [], $language_code = 'en')
    {

        // Get code and check that code exists or not.
        $query = MailTemplate::select('id', 'code')
            ->with(['mailCodeNameSingle' => function ($query) use ($language_code) {
                $query->select(['mail_id', 'content', 'subject', 'language_code'])
                    ->where('language_code', $language_code);
            }])
            ->where('code', $code)
            ->whereHas('mailCodeNameSingle', function ($q) use ($language_code) {
                $q->where('language_code', $language_code);
            })
            ->first();
        if (empty($query)) {

            return (['status' => 'false', 'error' => 'Service Email Not Started!!']);
        } else {
            $click_txt = '';
            if ($language_code == 'en') {
                $click_txt = 'Click Here';
            }

            if ($language_code == 'ar') {
                $click_txt = 'انقر هنا';
            }
            $contentData = $query->mailCodeNameSingle->content;
            $subjectData = $query->mailCodeNameSingle->subject;
            $contentData = (str_replace('{{AgencyLogo}}', $data['agency_logo'], $contentData));
            $contentData = (str_replace('{{AgencyName}}', ucwords($data['agency_name']), $contentData));
            $contentData = (str_replace('{{CustomerName}}', ucwords($data['customer_name']), $contentData));
            $contentData = (str_replace('Click Here', '<a href="' . $data['activation_link'] . '" target="_blank">' . $click_txt . '</a>', $contentData));
            $subject = (str_replace('{{AgencyName}}', $data['agency_name'], $subjectData));
            $data = ['mailData' => $contentData, 'subject' => $subject];
            return (['status' => 'true', 'data' => $data]);
        }
    }

    /**
     * Sends an email using specified parameters.
     *
     * @param string $toEmail The recipient's email address.
     * @param string $subject The subject line of the email.
     * @param array $mailData An array containing email content data.
     * @param array $files An optional array of file paths to attach.
     * @param string $fromName The sender's name (default: "Travel Portal").
     * @param string|null $templateCode An optional template code for the email.
     * @return void
     */
    public static function sendEmail($toEmail, $subject, $mailData, $files = [], $fromName = "Travel Portal", $templateCode = null)
    {

        $isMail = Setting::select('value')->where('config_key', '=', 'mail|smtp|server')->first();
        $siteEmail = count(Setting::where('config_key', 'general|basic|siteEmail')->get('value')) > 0 ? Setting::where('config_key', 'general|basic|siteEmail')->get('value')[0]['value'] : "";
        if (empty($siteEmail)) {
            return (['status' => 'false', 'error' => 'Service Email Not Started!!']);
        }
        if (isset($isMail) && $isMail->value == '1') {

            $hostName  = Setting::select('value')->where('config_key', '=', 'mail|smtp|host')->first();
            $fromEmail = Setting::select('value')->where('config_key', '=', 'mail|smtp|fromEmail')->first();
            $userName  = Setting::select('value')->where('config_key', '=', 'mail|smtp|userName')->first();
            $password  = Setting::select('value')->where('config_key', '=', 'mail|smtp|password')->first();
            $security  = Setting::select('value')->where('config_key', '=', 'mail|smtp|security')->first();
            $port = Setting::select('value')->where('config_key', '=', 'mail|smtp|port')->first();

            $config = array(
                'driver'    => 'smtp',
                'host'       => $hostName->value,
                'port'       => $port->value,
                'encryption' => 'tls',
                'from'       => array('address' => $fromEmail->value, 'name' => $fromName),
                'username'   => $userName->value,
                'password'   => $password->value,
            );

            Config::set('mail', $config);

            $fromaddress = $fromEmail->value;
            $mailDataArr = ['mailData' => $mailData];
            try {
                if (Mail::send('mail.blankEmailTemplate', $mailDataArr, function ($message) use ($toEmail, $mailData, $subject, $fromaddress, $files, $fromName) {

                    $message->from($fromaddress, $fromName);
                    $message->to($toEmail)->subject($subject);

                    foreach ($files as $file) {
                        $message->attach($file);
                    }
                })) {
                    return (['status' => 'true']);
                }
            } catch (\Exception $ex) {
                $ex->getMessage();
                // die;
                return (['status' => 'true']);
            }
        } else {
            $fromEmail = Setting::select('value')->where('config_key', '=', 'mail|smtp|fromEmail')->first();
            // Header for sender info 
            $headers = "From:" . $fromName . " <" . $fromEmail . ">";

            // Boundary  
            $semi_rand = md5(time());
            $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";

            // Headers for attachment  
            $headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\"";

            // Multipart boundary  
            $message = "--{$mime_boundary}\n" . "Content-Type: text/html; charset=\"UTF-8\"\n" .
                "Content-Transfer-Encoding: 7bit\n\n" . $mailData . "\n\n";

            // Preparing attachment 
            if (!empty($files)) {
                foreach ($files as $file) {
                    $message .= "--{$mime_boundary}\n";
                    $fp =    @fopen($file, "rb");
                    $data =  @fread($fp, filesize($file));

                    @fclose($fp);
                    $data = chunk_split(base64_encode($data));
                    $message .= "Content-Type: application/octet-stream; name=\"" . basename($file) . "\"\n" .
                        "Content-Description: " . basename($file) . "\n" .
                        "Content-Disposition: attachment;\n" . " filename=\"" . basename($file) . "\"; size=" . filesize($file) . ";\n" .
                        "Content-Transfer-Encoding: base64\n\n" . $data . "\n\n";
                }
            }

            $message .= "--{$mime_boundary}--";
            $returnpath = "-f" . $fromEmail;

            try {
                if (mail($toEmail, $subject, $mailData, $headers, $returnpath)) {
                    return (['status' => 'true']);
                }
            } catch (\Exception $ex) {
                // echo "<pre>";print_r($ex->getMessage());die;
                return (['status' => 'true']);
            }
        }

        return (['status' => 'true']);
    }
}
