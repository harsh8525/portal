<?php

namespace B2BApp\Http\Controllers\B2B;

use B2BApp\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Setting;
use App\Models\GeoCountryLists;
use App\Models\UserLoginHistory;
use App\Models\Role;
use App\Traits\EmailService;
use Illuminate\Support\Str;
use Carbon\Carbon;
use DB;
use Auth;




class ProfileController extends Controller
{
    use EmailService;

    public function edit()
    {
     
        $filter = array(
            'id' => Auth::guard('b2b')->id(),
        );
        $response = User::getAdminUsers($filter);
        $userDetail = $response['data'];
        $getIsdCode = GeoCountryLists::get();
        if ($userDetail['app_name'] == 'managerapp') {
            $getRole = Role::where('role_type', 'manager')->get()->toArray();
      
        }  
        if ($userDetail['app_name'] == 'b2bapp') {
            $getRole = Role::where('role_type', 'b2b')->get();
         
        }
        if ($userDetail['app_name']== 'supplierapp') {
            $getRole = Role::where('role_type', 'supplier')->get();
          
        }
        
        if($response['status'] == 1 && !empty($response['data'])){
            //    echo "<pre>"; print_r($userDetail);die;
            return view('b2b/auth/profile-update')->with(['userDetail'=>$userDetail,'getIsdCode'=>$getIsdCode,'getRole'=>$getRole]);
        }else{
            return redirect()->back()->with('error', $response['message']);
        }
        // return view('admin/auth/profile-update');
    }

    public function update(Request $request, $id)
    {
        
        $requestData = $request->only(['admin_user_id','fname','password','email','profile_image','old_profile_image','role','status', 'isd_code', 'mobile']);
            //    echo "<pre>"; print_r($requestData);die;
        $rules = [
            ];

        $customMessages = [
                ];

            $niceNames = array();
            
            $this->validate($request, $rules, $customMessages, $niceNames);
        
    //    echo "<pre>";print_r($requestData);die;
        $response = User::updateUser($requestData);
        
        if(!empty($response['data'])){
            // echo "if";die;
            return redirect()->route('b2b.dashboard')->with('success',$response['message']);
        }else{
            return redirect()->back()->with('error', $response['message']);
        }

        // return view('admin/auth/profile-update');
    }

    public function addLoginHistory(Request $request){
        
        $requestData = $request->only(['token','deviceId']);
        $response = ['status'=>0,'message'=>'Something went wrong'];

        try{
            // echo "<pre>";
            // print_r($this->getBrowserInfo());
            // print_r($requestData);die;
            $user_id = Auth::id();
            $ip_address = $this->getUserIP();
            
            $requestData = $request->all();
            
            $userLoginHistoryData = UserLoginHistory::where('user_id',$user_id)
                                ->where('is_admin',1)
                                ->where('device_id',$requestData['deviceId'])
                                ->get()->toArray();
            
    //            $isMob = is_numeric(strpos(strtolower($_SERVER["HTTP_USER_AGENT"]), "mobile")); 
    //
    //            $device_type = 'desktop';
    //            if($isMob){ 
    //                $device_type = 'mobile'; 
    //            }
            $browserDetail = $this->getBrowserInfo();

            if(empty($userLoginHistoryData)){
                //create record for user login history
                $userLoginHistory = new UserLoginHistory;
                $userLoginHistory->is_admin = 1;
                $userLoginHistory->user_id = $user_id;
                $userLoginHistory->device_type = $browserDetail['device'];
                $userLoginHistory->device_os = $browserDetail['user_agent'];
                $userLoginHistory->device_version = $browserDetail['browser_version'];
                $userLoginHistory->device_token = $requestData['token'];
                $userLoginHistory->device_id = $requestData['deviceId'];
                $userLoginHistory->ip_address = $ip_address;                
                $userLoginHistory->last_login_date = date('Y-m-d');
                $userLoginHistory->app_version = '1.0';//$requestData['app_version'];
                $userLoginHistory->save();
            }else{
                //update record for user login history
                $userLoginHistoryId = $userLoginHistoryData[0]['id'];
                $userLoginHistory = UserLoginHistory::find($userLoginHistoryId);
                $userLoginHistory->is_admin = 1;
                $userLoginHistory->user_id = $user_id;
                $userLoginHistory->device_type = $browserDetail['device'];
                $userLoginHistory->device_os = $browserDetail['user_agent'];
                $userLoginHistory->device_version = $browserDetail['browser_version'];
                $userLoginHistory->device_token = $requestData['token'];
                $userLoginHistory->device_id = $requestData['deviceId'];
                $userLoginHistory->ip_address = $ip_address;                
                $userLoginHistory->last_login_date = date('Y-m-d');
                $userLoginHistory->app_version = '1.0';//$requestData['app_version'];
                $userLoginHistory->save();
            }

            $response = ['status'=>1,'message'=>'Login history save successfully'];
        }catch(\Exception $e){
            $response = ['status'=>0,'message'=>$e->getMessage()];
        }

        return $response;
    }

    public function getUserIP()
    {
        // Get real visitor IP behind CloudFlare network
        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
                $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
                $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        }
        $client  = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote  = $_SERVER['REMOTE_ADDR'];

        if(filter_var($client, FILTER_VALIDATE_IP))
        {
            $ip = $client;
        }
        elseif(filter_var($forward, FILTER_VALIDATE_IP))
        {
            $ip = $forward;
        }
        else
        {
            $ip = $remote;
        }

        return $ip;
    }

    function getBrowserInfo(){
        $browserInfo = array('user_agent'=>'','browser'=>'','browser_version'=>'','os_platform'=>'','pattern'=>'', 'device'=>'');
    
        $u_agent = $_SERVER['HTTP_USER_AGENT']; 
        $bname = 'Unknown';
        $ub = 'Unknown';
        $version = "";
        $platform = 'Unknown';
    
        $deviceType='web';
    
        if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$u_agent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($u_agent,0,4))){
    
            $deviceType='mobile';
    
        }
    
        if($_SERVER['HTTP_USER_AGENT'] == 'Mozilla/5.0(iPad; U; CPU iPhone OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B314 Safari/531.21.10') {
            $deviceType='Tablet';
        }
    
        if(stristr($_SERVER['HTTP_USER_AGENT'], 'Mozilla/5.0(iPad;')) {
            $deviceType='Tablet';
        }
    
        //$detect = new Mobile_Detect();
        
        //First get the platform?
        if (preg_match('/linux/i', $u_agent)) {
            $platform = 'linux';
    
        } elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
            $platform = 'mac';
    
        } elseif (preg_match('/windows|win32/i', $u_agent)) {
            $platform = 'windows';
        }
    
        // Next get the name of the user agent yes seperately and for good reason
        if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) 
        { 
            $bname = 'IE'; 
            $ub = "MSIE";
    
        } else if(preg_match('/Firefox/i',$u_agent))
        { 
            $bname = 'Mozilla Firefox'; 
            $ub = "Firefox"; 
    
        } else if(preg_match('/Chrome/i',$u_agent) && (!preg_match('/Opera/i',$u_agent) && !preg_match('/OPR/i',$u_agent))) 
        { 
            $bname = 'Chrome'; 
            $ub = "Chrome"; 
    
        } else if(preg_match('/Safari/i',$u_agent) && (!preg_match('/Opera/i',$u_agent) && !preg_match('/OPR/i',$u_agent))) 
        { 
            $bname = 'Safari'; 
            $ub = "Safari"; 
    
        } else if(preg_match('/Opera/i',$u_agent) || preg_match('/OPR/i',$u_agent)) 
        { 
            $bname = 'Opera'; 
            $ub = "Opera"; 
    
        } else if(preg_match('/Netscape/i',$u_agent)) 
        { 
            $bname = 'Netscape'; 
            $ub = "Netscape"; 
    
        } else if((isset($u_agent) && (strpos($u_agent, 'Trident') !== false || strpos($u_agent, 'MSIE') !== false)))
        {
            $bname = 'Internet Explorer'; 
            $ub = 'Internet Explorer'; 
        } 
        
    
        // finally get the correct version number
        $known = array('Version', $ub, 'other');
        $pattern = '#(?<browser>' . join('|', $known) . ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    
        if (!preg_match_all($pattern, $u_agent, $matches)) {
            // we have no matching number just continue
        }
    
        // see how many we have
        $i = count($matches['browser']);
        if ($i != 1) {
            //we will have two since we are not using 'other' argument yet
            //see if version is before or after the name
            if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
                $version= $matches['version'][0];
    
            } else {
                $version= @$matches['version'][1];
            }
    
        } else {
            $version= $matches['version'][0];
        }
    
        // check if we have a number
        if ($version==null || $version=="") {$version="?";}
    
        return array(
            'user_agent' => $u_agent,
            'browser'      => $bname,
            'browser_version'   => $version,
            'os_platform'  => $platform,
            'pattern'   => $pattern,
            'device'    => $deviceType
        );
    }
    public function changePasswordUserEdit(Request $request)
    {
        // echo "123";die;
        $header['title'] = 'Change Password';
        $header['heading'] = 'Change Password';
        // echo Auth::guard('b2b')->id();die;
        $filter = array(
            'id' => Auth::guard('b2b')->id(),
        );
        $response = User::getAdminUsers($filter);
        $userDetail = $response['data'];
        // echo "<pre>";print_r($userDetail);die;
        if($response['status'] == 1 && !empty($response['data'])){
            //    echo "<pre>"; print_r($userDetail);die;
            return view('b2b/auth/change-password')->with(['userDetail'=>$userDetail,'header'=>$header]);
        }else{
            return redirect()->back()->with('error', $response['message']);
        }
    }
    public function changePasswordUserUpdate(Request $request, $id)
    {
        
        $requestData = $request->only(['user_id','confirm_password','old_password']);
        
        $rules = [

            ];

            $customMessages = [
                ];

            $niceNames = array();
            
            $this->validate($request, $rules, $customMessages, $niceNames);
        
    
        $response = User::updateNewPassAdmin($requestData);
    //    echo "<pre>";print_r($response);die;
        
        if(!empty($response['data'])){
            $checkNotifyEnable = Setting::where('config_key','passwordSecurity|changePasswordNotify')->get('value')[0]['value'];        
            if($checkNotifyEnable == '1')
                    {
                        
                        $userDetail = User::where('id',$response['data']['id'])->get()->toArray();
                        $agencyName = Setting::where('config_key','general|basic|siteName')->get('value')[0]['value'];
                        $agencyLogo = Setting::where('config_key','general|basic|colorLogo')->get('value')[0]['value'] ?? Setting::where('config_key','general|basic|colorLogo')->get('value')[0]['value'];

                        // echo "<pre>";print_r($agencyLogo);die;
                        //send mail to user when password changed
                        $code = 'CHANGE_PASSWORD';
                        $siteName = count(Setting::where('config_key', 'general|basic|siteName')->get('value')) > 0 ? Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'] : "Amar Infotech";
                        $customerName = ucwords($userDetail[0]['name']);
                        // $data = $siteName.','.$customerName;
                        $data = array(
                            'customer_name'=>$customerName,
                            'site_name'=>$siteName,
                            'agency_name' => $agencyName,
                            'agency_logo'=>$agencyLogo
                        );
                        // echo "<pre>";print_r($data);die;

                        $getTemplateData = $this->changePasswordMailTemplate($code,$data);
                        // echo "<pre>";print_r($getTemplateData);die;
                        if($getTemplateData['status'] == 'false'){
                            return back()->with('error', $getTemplateData['error']);
                        }else{
                            $subject = $getTemplateData['data']['subject'];
                            $mailData = $getTemplateData['data']['mailData'];
                            $toEmail = $userDetail[0]['email'];
                            $files = [];

                            // set data in sendEmail function
                            $data = $this->sendEmail($toEmail,$subject,$mailData,$files,$siteName,$code);
                        }
                        
                        
                        
                    }
            //Auth::logout();
            return redirect()->route('b2b.dashboard');
            // return redirect()->route('adminLogin')->with('success',$response['message']);
        }else{
            return redirect()->back()->with('error', $response['message']);
        }

        // return view('admin/auth/profile-update');
    }
    public function changePasswordvalidate(Request $request)
    {
        //fetch password security configuration values form setting table
        $minPassLength = Setting::where('config_key','passwordSecurity|minimumPasswordLength')->get('value')[0]['value'];
        $minDigitsLength = Setting::where('config_key','passwordSecurity|numericCharacter')->get('value')[0]['value'];
        $minSpecialcharLength = Setting::where('config_key','passwordSecurity|specialCharacter')->get('value')[0]['value'];
        $minUppercharLength = Setting::where('config_key','passwordSecurity|uppercaseCharacter')->get('value')[0]['value'];
        $minLowercharLength = Setting::where('config_key','passwordSecurity|lowercaseCharacter')->get('value')[0]['value'];
        $minAlphanumericcharLength = Setting::where('config_key','passwordSecurity|alphanumericCharacter')->get('value')[0]['value'];
        $response = [
            'valid' => false,
        ];
        $matches = [];
        //validate pass length based on setting value
        if($minPassLength > strlen($request->newPassword))
        {
            $response['valid'] = false;
            $response['message'] = "Password should contain atleast ".$minPassLength." charcters";
            
        }
        //validate pass that should contain digits
        else if(preg_match_all("/\d/", $request->newPassword,$matches) < $minDigitsLength)
        {
            $response['valid'] = false;
            $response['message'] = "Password should contain at least ".$minDigitsLength." digit";
            
        }
        //validate pass that should contain special character
        else if(preg_match_all("/\W/", $request->newPassword,$matches) < $minSpecialcharLength) 
        {
            $response['valid'] = false;
            $response['message'] ="Password should contain at least ".$minSpecialcharLength." special character";
            
        }
        //validate pass that should contain capital letter
        else if(!preg_match('/^(.*?[A-Z]){'.$minUppercharLength.'}/', $request->newPassword)) 
        {
            $response['valid'] = false;
            $response['message'] = "Password should contain at least ".$minUppercharLength." Capital Letter";
            
        }
        //validate pass that should contain small letter
        else if(!preg_match('/^(.*?[a-z]){'.$minLowercharLength.'}/', $request->newPassword)) 
        {
            $response['valid'] = false;
            $response['message'] = "Password should contain at least ".$minLowercharLength." small Letter";
            
        }
        //validate pass that shoult contain alphanumeric
        else if(preg_match_all("/[a-zA-Z0-9]/", $request->newPassword,$matches) < $minAlphanumericcharLength) 
        {
            $response['valid'] = false;
            $response['message'] = "Password should contain at least ".$minAlphanumericcharLength." alphanumeric character";
            
        }
        else
        {
            $response['valid'] = true;
        }
    
        return response()->json($response);

    }

    //send expiry notify functionality
    public static function sendExpiryNotificaton()
    {
        $checkNotifyEnable = Setting::where('config_key','passwordSecurity|changePasswordNotify')->get('value')[0]['value'];
        if($checkNotifyEnable == '1')
        {
            
            $checkExpiryDays = Setting::where('config_key','passwordSecurity|expiryDays')->get('value')[0]['value'];
            $checkExpiryNotifyDays = Setting::where('config_key','passwordSecurity|expireNotifyDays')->get('value')[0]['value'];
            $notifyDaysArray = explode(",",$checkExpiryNotifyDays);
            foreach($notifyDaysArray as $day)
            {
                $userDetail = User::where('id',$user->id)->value('password_updated_at');
                $expiryDate = Carbon::parse($userDetail)->addDays($checkExpiryDays);
                $beforeExpiryDays = $expiryDate->subDays($day)->format('Y-m-d h:i:s');
                if(Carbon::now() == $beforeExpiryDays)
                {
                    //set pasword expiry notification mailTemplate Function
                    $agencyName = Setting::where('config_key','general|basic|siteName')->get('value')[0]['value'];
                    $agencyLogo = Setting::where('config_key','general|basic|colorLogo')->get('value')[0]['value'] ?? Setting::where('config_key','general|basic|siteName')->get('value')[0]['value'];
                    $code = 'PASSWORD_EXPIRY';
                    $siteName = count(Setting::where('config_key', 'general|basic|siteName')->get('value')) > 0 ? Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'] : "Amar Infotech";
                    $customerName = ucwords($userData[0]['name']);
                    // $link = env('APP_URL').'/admin/reset-password/' . $token;
                    // $data = $siteName.','.$customerName;
                    $data = array(
                            'customer_name'=>$customerName,
                            'site_name'=>$siteName,
                            'agency_name' => $agencyName,
                            'agency_logo'=>$agencyLogo,
                            'password_expiry_day'=>$checkExpiryDays
                        );
                    $getTemplateData = $this->mailTemplate($code,$data);
                    if($getTemplateData['status'] == 'false'){
                        return back()->with('error', $getTemplateData['error']);
                    }else{
                        $subject = $getTemplateData['data']['subject'];
                        $mailData = $getTemplateData['data']['mailData'];
                        $toEmail = $userData[0]['email'];
                        $files = [];

                        // set data in sendEmail function
                        $data = $this->sendEmail($toEmail,$subject,$mailData,$files,$siteName,$code);
                        
                    }
                }
            }
            return ['success'=>1];
        }
    }
   
}
