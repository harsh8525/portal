<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


namespace B2BApp\Traits;

use Illuminate\Support\Facades\Storage;

trait SmsService {
    
    public $smsUser = 'PODIGIT';
    public $smsPassword = '123456';
    public $smsSenderId = 'SAFDAR';
    public $smsPriority = 'ndnd';
    public $smsType = 'normal';
    
    public function sendSms($mobile_number,$message){
        // print_r($message);die;
        $sms_user = $this->smsUser;
        $sms_password = $this->smsPassword;
        $sms_sender_id = $this->smsSenderId;
        $sms_priority = $this->smsPriority;
        $sms_type = $this->smsType;
        $url_encoded_message = rawurlencode($message);
        
        $redirect_link = "?user=".$sms_user."&pass=".$sms_password."&sender=".$sms_sender_id."&phone=$mobile_number&text=$url_encoded_message&priority=".$sms_priority."&stype=".$sms_type;
        //echo 'http://bhashsms.com/api/sendmsg.php'.$redirect_link;die;
        $url = 'http://bhashsms.com/api/sendmsg.php'.$redirect_link;
        try{
            $context = stream_context_create(array(
                'http' => array('ignore_errors' => true),
            ));
            //echo file_get_contents($url,true,$context);
        
            /*$curl = curl_init('http://bhashsms.com/api/sendmsg.php?' . $redirect_link);
             *  http://bhashsms.com/api/sendmsg.php?user=PODIGIT&pass=123456&sender=SAFDAR&phone=+91 9460944244&text=4178%20is%20your%20one%20time%20password%20to%20proceed%20on%20Safaidaar.%20It%20is%20valid%20for%205%20minutes&priority=ndnd&stype=normal
            */
    //        $curl = curl_init('http://bhashsms.com/api/sendmsg.php?' . urldecode($redirect_link));
            // Set query data here with the URL
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'http://bhashsms.com/api/sendmsg.php' . $redirect_link); 

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 3);
            $content = trim(curl_exec($ch));
            curl_close($ch);
            //print $content;
        } catch (\Exception $e) {            
             print_r($e->getMessage());
        }
        /*if ($httpcode == '200') {
            if ($response) {
                return true;
            }
        }*/

        return true;
    }
    
}