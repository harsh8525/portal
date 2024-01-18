<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait SmsService {
    
    public $smsUser = 'ABC';
    public $smsPassword = '123456';
    public $smsSenderId = 'XYZ';
    public $smsPriority = 'ndnd';
    public $smsType = 'normal';
    
    public function sendSms($mobile_number,$message){
        $sms_user = $this->smsUser;
        $sms_password = $this->smsPassword;
        $sms_sender_id = $this->smsSenderId;
        $sms_priority = $this->smsPriority;
        $sms_type = $this->smsType;
        $url_encoded_message = rawurlencode($message);
        $API_ID = 'API90038012646';
        $API_PASSWORD = 'Gateway@123';
        $SENDER_ID = 'REHLTETOURS';
        

        $redirect_link = "?user=".$sms_user."&pass=".$sms_password."&sender=".$sms_sender_id."&phone=$mobile_number&text=$url_encoded_message&priority=".$sms_priority."&stype=".$sms_type;
      
        $url = 'http://bhashsms.com/api/sendmsg.php'.$redirect_link;
        try{
            $context = stream_context_create(array(
                'http' => array('ignore_errors' => true),
            ));
            // Set query data here with the URL
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://rest.gateway.sa/api/CheckBalance?api_id='.$API_ID.'&api_password='.$API_PASSWORD); 

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 3);
            $content = trim(curl_exec($ch));
            $content_sms = json_decode($content);
            curl_close($ch);
            // echo "<pre>";print_r($content_sms);die;
            if($content_sms->BalanceAmount >= 50){
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://rest.gateway.sa/api/SendSMS?api_id='. $API_ID .'&api_password='.$API_PASSWORD.'&sms_type=O&encoding=T&sender_id='.$SENDER_ID.'&phonenumber='.$mobile_number.'&textmessage='.$url_encoded_message); 

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 3);
            $content = trim(curl_exec($ch));
            curl_close($ch);
            }
            
        } catch (\Exception $e) {            
             print_r($e->getMessage());
        }

        return true;
    }
    
}
