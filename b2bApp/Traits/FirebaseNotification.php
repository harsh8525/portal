<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


namespace B2BApp\Traits;

use App\Models\User;
use App\Models\AppUsers;
use App\Models\UserLoginHistory;
use App\Models\ManualNotificationApplicableUser;

trait FirebaseNotification {
    
    public function sendFirebaseNotification($dataNotification,$messages,$userData,$orderId)
    {
        
        $userId = array_values($userData)[0];
        $userType = array_keys($userData)[0];
        $url = 'https://fcm.googleapis.com/fcm/send';
       // $FcmToken = [];
        
        $appUsers = UserLoginHistory::where('user_id',$userId)->get()->toArray();
        //print_r($appUsers);//die;
        
        $moreData = ['order_id'=>$orderId];
        $userData = User::where('id',$userId)->first();
        
        $data = array(
            'title'=>$dataNotification->entity,
            'message'=>$messages['text']
        );

        foreach($appUsers AS $app_user){
            //$FcmToken[] = $app_user['device_token'];
            if($app_user['device_type'] == 'mobile'){
                $appServerKey = 'AAAARWiKUT4:APA91bGDBDZZf_y4efZuzTV884QhlAQQRmZDRRp4P7cMJUao0E1RWCTyMaY7G5S3NlcNbYsgKS2_5HnzWIOOGrUrbN7Q8mSzJHfdqfww9Wxm8nBJpHQOiY6n5xNKQEvEdZvE5x1UHt2H';
            }else if($app_user['device_type'] == 'web'){
                $appServerKey = env('FCM_SERVER_KEY');
            }else{
                $appServerKey = 'AAAARWiKUT4:APA91bGDBDZZf_y4efZuzTV884QhlAQQRmZDRRp4P7cMJUao0E1RWCTyMaY7G5S3NlcNbYsgKS2_5HnzWIOOGrUrbN7Q8mSzJHfdqfww9Wxm8nBJpHQOiY6n5xNKQEvEdZvE5x1UHt2H';
            }
            $FcmToken = $app_user['device_token'];
            //echo $FcmToken;die;
            self::sendAppVizFcmNotification($app_user['device_type'],$appServerKey, $FcmToken, $data,$moreData,$messages);
        }
        
        /*if($userType == 'app_user'){
            //need to send firabase mobile notification to vendor
            return self::sendAppVizFcmNotification($appServerKey, $FcmToken, $data,$moreData,$messages);
        }else{
            //desktop notification for admin
            $serverKey = env('FCM_SERVER_KEY');

            $data = [
                "registration_ids" => [$FcmToken->fcm_token],
                "notification" => [
                    "title" => $dataNotification->entity,
                    "body" => $messages['text'],  
                    "click_action"=>$messages['redirection_link'],
                    "icon"=>$messages['image'],
                    "sound"=> "default",
                    "vibrate"=> 1,
                ],
                "data" => [
                    "web_text"=>$messages['web_text']
                ]
            ];
            $encodedData = json_encode($data);

            $headers = [
                'Authorization:key=' . $serverKey,
                'Content-Type: application/json',
            ];

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            // Disabling SSL Certificate support temporarly
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);        
            curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);

            // Execute post
            $result = curl_exec($ch);

            if ($result === FALSE) {
                //die('Curl failed: ' . curl_error($ch));
            }        

            // Close connection
            curl_close($ch);

            // FCM response
    //        print_r($result);die;
            return $result;
        }*/
    }
    
    public function sendAppVizFcmNotification($deviceType='mobile', $serverKey, $token, $data,$moredata=[],$webData=[]){
        if($deviceType == 'mobile'){
            $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
            if(isset($data['image'])){
            $image= $data['image']; 
            }else{
                $image='';
            }
            $notification = [
                'title' => $data['title'],
                'body' => $data['message'],
                'sound' => true,
                'image' => $image,
                'android_channel_id' => 'high_importance_channel',
                'priority' => 'high',
            ];
            
            $extraNotificationData = ["message" => $notification,"moredata" =>$moredata];

            $fcmNotification = [
                // 'registration_ids' => $token, //multple token array
                'to'        => $token, //single token
                'notification' => $notification,
                'data' => $extraNotificationData
            ];
            
            //$serverKey = 'AAAA5EtJ1T4:APA91bFjr1UZOGqP1U_Naf90dAu0vFy_Yd5xIHxvpwkSxF9vGP9TQtQXouHvbROMZaoPREzNsV7cJp7tQsWPAOCeoM-u-bXklJCBLVJcWAutomClUBPtrHn9ubigo5TRX1MfVL4SzktY';
    //        $serverKey = env('FCM_SERVER_KEY');
            $headers = [
                'Authorization: key='.$serverKey,
                'Content-Type: application/json'
            ];


            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,$fcmUrl);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
            $result = curl_exec($ch);
            curl_close($ch);
            
           //echo $result;
            return $result;
        }
        else if($deviceType == 'web'){
            //desktop notification for admin
            $url = 'https://fcm.googleapis.com/fcm/send';
            $serverKey = env('FCM_SERVER_KEY');

            $data = [
                "registration_ids" => [$token],
                "notification" => [
                    "title" => $data['title'],
                    "body" => $webData['text'],  
                    "click_action"=>$webData['redirection_link'],
                    "icon"=>$webData['image'],
                    "sound"=> "default",
                    "vibrate"=> 1,
                ],
                "data" => [
                    "web_text"=>$webData['web_text']
                ]
            ];
            $encodedData = json_encode($data);

            $headers = [
                'Authorization:key=' . $serverKey,
                'Content-Type: application/json',
            ];

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            // Disabling SSL Certificate support temporarly
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);        
            curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);

            // Execute post
            $result = curl_exec($ch);

            if ($result === FALSE) {
                //die('Curl failed: ' . curl_error($ch));
            }        

            // Close connection
            curl_close($ch);

            // FCM response
        //    print_r($result);die;
            return $result;
        }
    }
    
    public function sendFirebaseFcmNotification($token, $data,$moredata=[],$userId){
    //    echo $token;
    //    echo"<pre>";print_r($data);
    //    echo"<pre>";print_r($moredata);
    //    echo"<pre>";print_r($userId);die;
        
        $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
        
        $FcmToken = AppUsers::select('fcm_token')->where('id',$userId)->first();
        
        if(!$FcmToken){
            return;
        }
        
        $userData = AppUsers::where('id',$userId)->first();
        
        $appServerKey = 'AAAARWiKUT4:APA91bGDBDZZf_y4efZuzTV884QhlAQQRmZDRRp4P7cMJUao0E1RWCTyMaY7G5S3NlcNbYsgKS2_5HnzWIOOGrUrbN7Q8mSzJHfdqfww9Wxm8nBJpHQOiY6n5xNKQEvEdZvE5x1UHt2H';
            //need to send firabase mobile notification to vendor
        return self::sendAppVizFcmNotification($appServerKey, $FcmToken, $data, $moredata);    
    }
    
}