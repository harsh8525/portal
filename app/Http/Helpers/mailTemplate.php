<?php
    use App\Models\Setting;
    use Carbon\Carbon;
    use App\Models\Agency;
    use App\Models\User;
    /**
     * send mail with reset-password link
     * created date 16-08-2023
     */
    function processData($user)
    {
        if($user['agency_id'] == '0')
        {
            $agencyLogo = count(Setting::where('config_key', 'general|basic|colorLogo')->get('value')) > 0 ? Setting::where('config_key', 'general|basic|colorLogo')->get('value')[0]['value'] : Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'];
            $siteName = count(Setting::where('config_key', 'general|basic|siteName')->get('value')) > 0 ? Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'] : "Amar Infotech";
        }
        else
        {
            if($user['primary_user'] == '1')
            {
                $agencyLogo = count(Setting::where('config_key', 'general|basic|colorLogo')->get('value')) > 0 ? Setting::where('config_key', 'general|basic|colorLogo')->get('value')[0]['value'] : Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'];
                $siteName = count(Setting::where('config_key', 'general|basic|siteName')->get('value')) > 0 ? Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'] : "Amar Infotech";
            }
            else
            {
                $siteName = Agency::where('id', $user['agency_id'])->value('full_name');
                $agencyLogo = Agency::where('id', $user['agency_id'])->value('logo');
            }
        }
        //set User data in mailTemplate Function
        $token = Str::random(60);
        $updatePassword = DB::table('password_resets')
            ->where(['email' => $user['email']])
            ->first();
        if (!$updatePassword) {
            \DB::table('password_resets')->insert(
                ['email' => $user['email'], 'token' => $token, 'created_at' => Carbon::now()]
            );
        } else {
            DB::table('password_resets')->where(['email' => $user['email']])->update(
                ['token' => $updatePassword->token]
            );
            $token = $updatePassword->token;
        }
        $userName = ucwords($user['name']);
        if(Auth::guard('b2b')->check()){
            $link = 'http://b2b.' . env('APP_URL') . '/reset-password/' . $token;
        }else{
            $link = 'http://admin.' . env('APP_URL') . '/reset-password/' . $token;
        }
        
        $data = array(
            'customer_name' => $userName,
            'site_name' => $siteName,
            'agency_logo' => $agencyLogo,
            'activation_link' => $link
        );
        return $data;
    }
    /**
     * send mail without reset-password link
     * created date 16-08-2023
     */
    function processSimpleTemplate($user)
    {
        // Check if the user is from the user table
        if (array_key_exists('name', $user)) {
            $name = $user['name'];
        } else { // If not from the user table, assume it's from the customer table
            $firstName = $user['first_name'];
            $lastName = $user['last_name'];
            $name = $firstName . ' ' . $lastName;
        }

        if($user['agency_id'] == '0')
        {
            $agencyLogo = count(Setting::where('config_key', 'general|basic|colorLogo')->get('value')) > 0 ? Setting::where('config_key', 'general|basic|colorLogo')->get('value')[0]['value'] : Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'];
            $siteName = count(Setting::where('config_key', 'general|basic|siteName')->get('value')) > 0 ? Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'] : "Amar Infotech";
        }
        else
        {
            if($user['primary_user'] == '1')
            {
                $agencyLogo = count(Setting::where('config_key', 'general|basic|colorLogo')->get('value')) > 0 ? Setting::where('config_key', 'general|basic|colorLogo')->get('value')[0]['value'] : Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'];
                $siteName = count(Setting::where('config_key', 'general|basic|siteName')->get('value')) > 0 ? Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'] : "Amar Infotech";
            }
            else
            {
                $siteName = Agency::where('id', $user['agency_id'])->value('full_name');
                $agencyLogo = Agency::where('id', $user['agency_id'])->value('logo');
            }
        }
        
        $data = array( 
                    'agency_logo' => $agencyLogo,
                    'agency_name' => $siteName,
                    'user_name' => ucwords($name),
                );
        return $data;
    }