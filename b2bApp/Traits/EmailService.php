<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


namespace B2BApp\Traits;

use App\Models\Setting;
use Illuminate\Support\Facades\Mail;
use DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Config;
use DateTime;

trait EmailService {
    
    // public $fromEmail = 'riddhi@amarinfotech.com';
    
    public function passwordExpiryMailTemplate($code,$data = []) {
        
        // Get code and check that code exists or not.
        $mailTemplate = DB::table('core_mail_templates')->where('code',$code)->first();
        if(empty($mailTemplate)){
            return (['status'=>'false','error'=>'Service Email Not Started!!']);
        } else{
            $contentData = $mailTemplate->content;
            // echo "<pre>";print_r($contentData);die;
            $subjectData = $mailTemplate->subject;
                // find value and replace with particular data
            $contentData = (str_replace('{{AgencyLogo}}', $data['agency_logo'], $contentData));
            $contentData = (str_replace('{{CustomerName}}', $data['customer_name'], $contentData));
            $contentData = (str_replace('{{AgencyName}}', ucwords($data['agency_name']), $contentData));
            $contentData = (str_replace('{{DateTime}}', date('Y-m-d h:i:s'), $contentData));
            $contentData = (str_replace('{{PasswordExpiryDay}}', $data['password_expiry_day'] ?? "", $contentData));
            $subject = (str_replace('{{AgencyName}}', $data['agency_logo'], $subjectData));
            $data = ['mailData'=>$contentData,'subject'=>$subject];
            return (['status'=>'true','data'=>$data]);
        }
    }
    /**
     * replace contect with dynamic values in change password mail template
     * created by Hardik Kansakar
     * created date 11-07-2023
     */
    public function changePasswordMailTemplate($code,$data = []) {
        
        // Get code and check that code exists or not.
        $mailTemplate = DB::table('core_mail_templates')->where('code',$code)->first();
        if(empty($mailTemplate)){
            return (['status'=>'false','error'=>'Service Email Not Started!!']);
        } else{
            $contentData = $mailTemplate->content;
            
            $subjectData = $mailTemplate->subject;
            $contentData = (str_replace('{{AgencyLogo}}', $data['agency_logo'], $contentData));
            $contentData = (str_replace('{{CustomerName}}', $data['customer_name'], $contentData));
            $contentData = (str_replace('{{AgencyName}}', ucwords($data['agency_name']), $contentData));
            $contentData = (str_replace('{{DateTime}}', date('Y-m-d h:i:s'), $contentData));
            $subject = (str_replace('{{AgencyName}}', $data['agency_name'], $subjectData));
            $data = ['mailData'=>$contentData,'subject'=>$subject];
            return (['status'=>'true','data'=>$data]);
        }
    }

    /**
     * replace data into user signup mail template
     * created by hardik kansakar
     * created date 08-07-2023
     */
    public function userSignUpMailTemplate($code,$data = []) {
        // Get code and check that code exists or not.
        $mailTemplate = DB::table('core_mail_templates')->where('code',$code)->first();
        
        $userData = processData($data);
        
        if(empty($mailTemplate)){
            return (['status'=>'false','error'=>'Service Email Not Started!!']);
        } else{
            $contentData = $mailTemplate->content;
            $subjectData = $mailTemplate->subject;
            $contentData = (str_replace('{{AgencyLogo}}', $userData['agency_logo'], $contentData));
            $contentData = (str_replace('{{UserName}}', $userData['customer_name'], $contentData));
            $contentData = (str_replace('{{AgencyName}}', ucwords($userData['site_name']), $contentData));
            $contentData = (str_replace('{{click here}}', '<a href="'.$userData['activation_link']. '">Click Here</a>', $contentData));
            $contentData = (str_replace('{{ActivationLink}}', $userData['activation_link'], $contentData));
            $subject = (str_replace('{{AgencyName}}', $userData['site_name'], $subjectData));
            $data = ['mailData'=>$contentData,'subject'=>$subject];
            return (['status'=>'true','data'=>$data,'agencyName'=>$userData['site_name']]);
        }
    }
    /**
     * replace data into user account activation mail template
     * created by hardik kansakar
     * created date 11-07-2023
     */
    public function userAccountActivationMailTemplate($code,$data = []) 
    {
        
        // Get code and check that code exists or not.
        $mailTemplate = DB::table('core_mail_templates')->where('code',$code)->first();
        
        if(empty($mailTemplate)){
            return (['status'=>'false','error'=>'Service Email Not Started!!']);
        } else{
            $contentData = $mailTemplate->content;
            $subjectData = $mailTemplate->subject;
            $contentData = (str_replace('{{AgencyLogo}}', $data['agency_logo'], $contentData));
            $contentData = (str_replace('{{UserName}}', $data['user_name'], $contentData));
            $contentData = (str_replace('{{AgencyName}}', ucwords($data['agency_name']), $contentData));
            $subject = (str_replace('{{AgencyName}}', $data['agency_name'], $subjectData));
            $data = ['mailData'=>$contentData,'subject'=>$subject];
            return (['status'=>'true','data'=>$data]);
        }
    }
    public function customerSignUp($code, $data = [])
    {
       
        // Get code and check that code exists or not.
        $mailTemplate = DB::table('core_mail_templates')->where('code', $code)->first();
        if (empty($mailTemplate)) {
         
            return (['status' => 'false', 'error' => 'Service Email Not Started!!']);
        } else {
           
            $contentData = $mailTemplate->content;
         
            $subjectData = $mailTemplate->subject;
            // find value and replace with particular data
            // $search = explode(',',$mailTemplate->suggested_variable);
            // $replace =  explode(',',$data);
            // $mailData = (str_replace($search, $replace, $contentData));
            // $subject = (str_replace($search, $replace, $subjectData));
            // $data = ['mailData'=>$mailData,'subject'=>$subject];
            // return (['status'=>'true','data'=>$data]);
   
            $contentData = (str_replace('{{CustomerName}}', $data['first_name'], $contentData));
            $contentData = (str_replace('{{AgencyName}}', ucwords($data['agency_name']), $contentData));
            $contentData = (str_replace('{{CustomerEmail}}', $data['email']   , $contentData));
            $contentData = (str_replace('{{click here}}', '<a href="' . $data['activation_link'] . '">Click here</a>', $contentData));
            $contentData = (str_replace('{{AgencyLogo}}', $data['agency_logo'], $contentData));    
            $contentData = (str_replace('{{ActivationLink}}', $data['activation_link'], $contentData));
            $subject = (str_replace('{{AgencyName}}', $data['agency_name'], $subjectData));
          
            $data = ['mailData' => $contentData, 'subject' => $subject];
        
            return (['status' => 'true', 'data' => $data]);

        }
    }
    /**
     * replace data into user welcome agency mail template
     * created by hardik kansakar
     * created date 04-08-2023
     */
    public function welcomeAgencyMailTemplate($code,$data = []) 
    {
        
        // Get code and check that code exists or not.
        $mailTemplate = DB::table('core_mail_templates')->where('code',$code)->first();
        
        if(empty($mailTemplate)){
            return (['status'=>'false','error'=>'Service Email Not Started!!']);
        } else{
            $contentData = $mailTemplate->content;
            $subjectData = $mailTemplate->subject;
            $contentData = (str_replace('{{AgencyLogo}}', $data['agency_logo'], $contentData));
            $contentData = (str_replace('{{UserName}}', $data['user_name'], $contentData));
            $contentData = (str_replace('{{AgencyName}}', ucwords($data['agency_name']), $contentData));
            $subject = (str_replace('{{AgencyName}}', $data['agency_name'], $subjectData));
            $data = ['mailData'=>$contentData,'subject'=>$subject];
            return (['status'=>'true','data'=>$data]);
        }
    }
    //notify user when account has been bloked
    public function mailTemplateBlockAccount($code,$data = []) {
        
        // Get code and check that code exists or not.
        $mailTemplate = DB::table('core_mail_templates')->where('code',$code)->first();
        if(empty($mailTemplate)){
            return (['status'=>'false','error'=>'Service Email Not Started!!']);
        } else{
            $contentData = $mailTemplate->content;
            // echo "<pre>";print_r($contentData);die; 
            $subjectData = $mailTemplate->subject;
            $contentData = (str_replace('{{CustomerName}}', $data['customer_name'], $contentData));
            $contentData = (str_replace('{{AgencyName}}', ucwords($data['agency_name']), $contentData));
            $contentData = (str_replace('{{Hours}}', $data['hours']." ".$data['duration'], $contentData));    
            $contentData = (str_replace('{{AgencyLogo}}', $data['agency_logo'], $contentData));    
            // echo $contentData;die;
            $subject = (str_replace('{{AgencyName}}', $data['agency_name'], $subjectData));
            $data = ['mailData'=>$contentData,'subject'=>$subject];
            return (['status'=>'true','data'=>$data]);
        }
    }
    /**
     * replace data into user agency block mail template
     * created by hardik kansakar
     * created date 07-08-2023
     */
    public function agencyBlockMailTemplate($code,$data = []) 
    {
        
        // Get code and check that code exists or not.
        $mailTemplate = DB::table('core_mail_templates')->where('code',$code)->first();
        
        if(empty($mailTemplate)){
            return (['status'=>'false','error'=>'Service Email Not Started!!']);
        } else{
            $contentData = $mailTemplate->content;
            $subjectData = $mailTemplate->subject;
            $contentData = (str_replace('{{AgencyLogo}}', $data['agency_logo'], $contentData));
            $contentData = (str_replace('{{AgencyName}}', ucwords($data['agency_name']), $contentData));
            $subject = (str_replace('{{AgencyName}}', $data['agency_name'], $subjectData));
            $data = ['mailData'=>$contentData,'subject'=>$subject];
            return (['status'=>'true','data'=>$data]);
        }
    }
    public function sendEmail($toEmail,$subject,$mailData,$files=[],$fromName="Travel Portal",$templateCode = null){
            
            $isMail = Setting ::select('value')->where('config_key','=','mail|smtp|server')->first();
            $siteEmail = count(Setting::where('config_key', 'general|basic|siteEmail')->get('value')) > 0 ? Setting::where('config_key', 'general|basic|siteEmail')->get('value')[0]['value'] : "";
            if(empty($siteEmail)){
                return (['status'=>'false','error'=>'Service Email Not Started!!']);
            }
            if(isset($isMail) && $isMail->value == '1'){

                $hostName  = Setting ::select('value')->where('config_key','=','mail|smtp|host')->first();
                $fromEmail = Setting ::select('value')->where('config_key','=','mail|smtp|fromEmail')->first();
                $userName  = Setting ::select('value')->where('config_key','=','mail|smtp|userName')->first();
                $password  = Setting ::select('value')->where('config_key','=','mail|smtp|password')->first();
                $security  = Setting ::select('value')->where('config_key','=','mail|smtp|security')->first();
                $port = Setting ::select('value')->where('config_key','=','mail|smtp|port')->first();

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
                try{
                    if(Mail::send('mail.blankEmailTemplate',$mailDataArr, function($message) use ($toEmail,$mailData,$subject,$fromaddress,$files,$fromName){    
                        
                        $message->from($fromaddress,$fromName);
                        $message->to($toEmail)->subject($subject);
                        
                        foreach ($files as $file){
                            $message->attach($file);
                        }
                    })){
                        return (['status'=>'true']);
                    }
                }catch (\Exception $ex) {
                    print_r($ex->getMessage());die;
                    return (['status'=>'true']);
                }


            }else{

                // Header for sender info 
                $headers = "From:".$fromName." <".$fromEmail.">"; 

                // Boundary  
                $semi_rand = md5(time());  
                $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";  

                // Headers for attachment  
                $headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\""; 

                // Multipart boundary  
                $message = "--{$mime_boundary}\n" . "Content-Type: text/html; charset=\"UTF-8\"\n" . 
                "Content-Transfer-Encoding: 7bit\n\n" . $mailData . "\n\n";  

                // Preparing attachment 
                if(!empty($files)){ 
                    foreach ($files as $file){
                        $message .= "--{$mime_boundary}\n"; 
                        $fp =    @fopen($file,"rb"); 
                        $data =  @fread($fp,filesize($file)); 

                        @fclose($fp); 
                        $data = chunk_split(base64_encode($data)); 
                        $message .= "Content-Type: application/octet-stream; name=\"".basename($file)."\"\n" .  
                        "Content-Description: ".basename($file)."\n" . 
                        "Content-Disposition: attachment;\n" . " filename=\"".basename($file)."\"; size=".filesize($file).";\n" .  
                        "Content-Transfer-Encoding: base64\n\n" . $data . "\n\n"; 
                    }
                } 

                $message .= "--{$mime_boundary}--"; 
                $returnpath = "-f" . $fromEmail; 
                
                try{
                    if(mail($toEmail, $subject, $mailData, $headers, $returnpath)){
                        return (['status'=>'true']);
                    } 
                }catch (\Exception $ex) {
                    return (['status'=>'true']);
                }
            }

            return (['status'=>'true']);
        
    }
    
}