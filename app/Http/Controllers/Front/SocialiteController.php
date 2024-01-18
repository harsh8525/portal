<?php
/**
 * @package     Fornt
 * @subpackage  Social Login
 * @Author      Amar Technolabs Pvt. ltd(info@amarinfotech.com)
 * @Copyright(C) 2023 [NAME OF THE ORGANISATION THAT ON BEHALF OF THE CODE WE ARE WORKING].
 * @Version 1.0.0
 * module of the Social Login.
 */
namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;
use App\Traits\EmailService;
use GeneaLabs\LaravelSocialiter\Facades\Socialiter;
use Laravel\Socialite\Facades\Socialite;
use App\Models\Customer;
use App\Models\Setting;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Auth, DB;
use \Firebase\JWT\JWT;

class SocialiteController extends Controller
{
    use EmailService;
    /**
     * Redirect to google login page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToGoogle(Request $request)
    {
        session()->forget('language_code');
        $isGoogle = Setting::select('value')->where('config_key', 'signInMethod|google|enable')->first();

        if (isset($isGoogle) && $isGoogle->value == '1') {

            $clientId  = Setting::select('value')->where('config_key', 'signInMethod|google|clientId')->first();
            $clientSecret = Setting::select('value')->where('config_key', 'signInMethod|google|clientSecret')->first();
            $redirectUri  = Setting::select('value')->where('config_key', 'signInMethod|google|redirectUri')->first();
            $developerKey  = Setting::select('value')->where('config_key', 'signInMethod|google|developerKey')->first();
            $language_code = $request->language_code;

            $config = array(
                'client_id'     => $clientId->value,
                'client_secret' => $clientSecret->value,
                'redirect'      => str_replace('{{SITE_URL}}', URL('/'), $redirectUri->value)
            );
            Config::set('services.google', $config);
            session(['language_code' => $language_code]);

            return Socialite::driver('google')->redirect();
        };
    }

    /**
     * Handle google callback url.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleGoogleCallback(Request $request)
    {
        $isGoogle = Setting::select('value')->where('config_key', 'signInMethod|google|enable')->first();

        if (isset($isGoogle) && $isGoogle->value == '1') {

            try {
                $clientId  = Setting::select('value')->where('config_key', 'signInMethod|google|clientId')->first();
                $clientSecret = Setting::select('value')->where('config_key', 'signInMethod|google|clientSecret')->first();
                $redirectUri  = Setting::select('value')->where('config_key', 'signInMethod|google|redirectUri')->first();
                $developerKey  = Setting::select('value')->where('config_key', 'signInMethod|google|developerKey')->first();
                $b2cUrl  = Setting::select('value')->where('config_key', 'general|b2cUrl')->first();

                $config = array(
                    'client_id'     => $clientId->value,
                    'client_secret' => $clientSecret->value,
                    'redirect'      => str_replace('{{SITE_URL}}', URL('/'), $redirectUri->value)
                );
                
                Config::set('services.google', $config);
                $language_code = session('language_code');

                $user = Socialite::driver('google')->user();
                
                $existingUser = Customer::where('email', $user['email'])->first();
               
                if ($existingUser) {
                    $newUser = Customer::where('email', $user['email'])
                    ->update([
                        'google_id' => $user['id']
                     ]);
                    Auth::login($existingUser);
                    $accessToken = $existingUser->createToken('authToken')->accessToken;
                    $userDetail = Customer::where('email', $user['email'])->first();
                } else {
                    $newUser = Customer::create([
                        'first_name' => $user['given_name'] ?? '',
                        'last_name' => $user['family_name'] ?? '',
                        'email' => $user['email'] ?? '',
                        'profile_photo' => $user['picture'] ?? '',
                        'google_id' => $user['id']
                    ]);

                    Auth::login($newUser);
                    $accessToken = $newUser->createToken('authToken')->accessToken;
                    $userDetail = Customer::where('email', $user['email'])->first();
                   

                    $code = 'CUSTOMER_SIGN_UP';
                    $token = Str::random(60);

                    $updateCustomerToken = DB::table('customer_activation_tokens')
                        ->where(['email' => $userDetail['email']])
                        ->first();
                    if (!$updateCustomerToken) {
                        \DB::table('customer_activation_tokens')->insert(
                            ['email' => $userDetail['email'], 'token' => $token, 'created_at' => Carbon::now()]
                        );
                    } else {
                        DB::table('customer_activation_tokens')->where(['email' => $userDetail['email']])->update(
                            ['token' => $updateCustomerToken->token]
                        );
                        $token = $updateCustomerToken->token;
                    }
                    $customerName = ucwords($userDetail['first_name']);
                    $customerEmail = $userDetail['email'];
                    $siteName = count(Setting::where('config_key', 'general|basic|siteName')->get('value')) > 0 ? Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'] : "Amar Infotech";
                    $agencyName = Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'];
                    $agencyLogo = Setting::where('config_key', 'general|basic|colorLogo')->get('value')[0]['value'] ?? Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'];
                    $link = $b2cUrl->value . 'email-verification/' . $token;

                    $data = array(
                        'first_name' => $customerName,
                        'site_name' => $siteName,
                        'agency_name' => $agencyName,
                        'agency_logo' => $agencyLogo,
                        'email' => $customerEmail,
                        'activation_link' => $link
                    );
                    $getCustomerSignUp = $this->customerSignUp($code, $data, $language_code);
                
                    $mailData = $getCustomerSignUp['data']['mailData'];
                    $subject = $getCustomerSignUp['data']['subject'];
                    $mailData = $getCustomerSignUp['data']['mailData'];
                    $toEmail = $customerEmail;
                    $files = [];

                    // set data in sendEmail function
                    $data = $this->sendEmail($toEmail, $subject, $mailData, $files, $siteName, $code);
                }

                return response()->json([
                    'user' => $userDetail,
                    'access_token' => $accessToken
                ]);
            } catch (\Exception $e) {
                return response()->json(['error' => 'Error retrieving user data'], 500);
            }
        }
    }

    /**
     * Redirect to facebook login page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToFacebook(Request $request)
    {
        session()->forget('language_code');
        $isFacebook = Setting::select('value')->where('config_key', 'signInMethod|facebook|enable')->first();
      
        if (isset($isFacebook) && $isFacebook->value == '1') {
  
            $appId  = Setting::select('value')->where('config_key', 'signInMethod|facebook|appId')->first();
            $appSecret = Setting::select('value')->where('config_key', 'signInMethod|facebook|appSecret')->first();
            $redirectUri  = Setting::select('value')->where('config_key', 'signInMethod|facebook|redirectUri')->first();
            $redirectUriLogout  = Setting::select('value')->where('config_key', 'signInMethod|facebook|redirectUriLogout')->first();
            $language_code = $request->language_code;
            
            $config = array(
                'client_id'     => $appId->value,
                'client_secret' => $appSecret->value,
                'redirect'      => str_replace('{{SITE_URL}}', URL('/'), $redirectUri->value)
            );
            Config::set('services.facebook', $config);
            session(['language_code' => $language_code]);

            return Socialite::driver('facebook')->redirect();
        };
    }

    /**
     * Handle facebook callback url.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleFacebookCallback(Request $request)
    {
        $isFacebook = Setting::select('value')->where('config_key', 'signInMethod|facebook|enable')->first();

        if (isset($isFacebook) && $isFacebook->value == '1') {

            try {
                $appId  = Setting::select('value')->where('config_key', 'signInMethod|facebook|appId')->first();
                $appSecret = Setting::select('value')->where('config_key', 'signInMethod|facebook|appSecret')->first();
                $redirectUri  = Setting::select('value')->where('config_key', 'signInMethod|facebook|redirectUri')->first();
                $redirectUriLogout  = Setting::select('value')->where('config_key', 'signInMethod|facebook|redirectUriLogout')->first();
                $b2cUrl  = Setting::select('value')->where('config_key', 'general|b2cUrl')->first();

                $config = array(
                    'client_id'     => $appId->value,
                    'client_secret' => $appSecret->value,
                    'redirect'      => str_replace('{{SITE_URL}}', URL('/'), $redirectUri->value)
                );
                
                Config::set('services.facebook', $config);
                $language_code = session('language_code');

                $user = Socialite::driver('facebook')->user();
                $existingUser = Customer::where('email', $user['email'])->first();

                if ($existingUser) {
                    $newUser = Customer::where('email', $user['email'])
                            ->update([
                                'facebook_id' => $user['id']
                            ]);

                    Auth::login($existingUser);
                    $accessToken = $existingUser->createToken('authToken')->accessToken;
                    $userDetail = Customer::where('email', $user['email'])->first();
                } else {
                    $newUser = Customer::create([
                        'first_name' => $user['name'] ?? '',
                        'email' => $user['email'] ?? '',
                        'profile_photo' => $user->avatar ?? '',
                        'facebook_id' => $user['id']
                    ]);

                    Auth::login($newUser);
                    $accessToken = $newUser->createToken('authToken')->accessToken;

                    $userDetail = Customer::where('email', $user['email'])->first();

                    $code = 'CUSTOMER_SIGN_UP';
                    $token = Str::random(60);

                    $updateCustomerToken = DB::table('customer_activation_tokens')
                        ->where(['email' => $userDetail['email']])
                        ->first();
                    if (!$updateCustomerToken) {
                        \DB::table('customer_activation_tokens')->insert(
                            ['email' => $userDetail['email'], 'token' => $token, 'created_at' => Carbon::now()]
                        );
                    } else {
                        DB::table('customer_activation_tokens')->where(['email' => $userDetail['email']])->update(
                            ['token' => $updateCustomerToken->token]
                        );
                        $token = $updateCustomerToken->token;
                    }
                    $customerName = ucwords($userDetail['first_name']);
                    $customerEmail = $userDetail['email'];
                    $siteName = count(Setting::where('config_key', 'general|basic|siteName')->get('value')) > 0 ? Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'] : "Amar Infotech";
                    $agencyName = Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'];
                    $agencyLogo = Setting::where('config_key', 'general|basic|colorLogo')->get('value')[0]['value'] ?? Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'];
                    $link = $b2cUrl->value . 'email-verification/' . $token;

                    $data = array(
                        'first_name' => $customerName,
                        'site_name' => $siteName,
                        'agency_name' => $agencyName,
                        'agency_logo' => $agencyLogo,
                        'email' => $customerEmail,
                        'activation_link' => $link
                    );

                    $getCustomerSignUp = $this->customerSignUp($code, $data, $language_code);

                    $mailData = $getCustomerSignUp['data']['mailData'];
                    $subject = $getCustomerSignUp['data']['subject'];
                    $mailData = $getCustomerSignUp['data']['mailData'];
                    $toEmail = $customerEmail;
                    $files = [];

                    // set data in sendEmail function
                    $data = $this->sendEmail($toEmail, $subject, $mailData, $files, $siteName, $code);
                }

                return response()->json([
                    'user' => $userDetail,
                    'access_token' => $accessToken
                ]);
            } catch (\Exception $e) {
                return response()->json(['error' => 'Error retrieving user data'], 500);
            }
        }
    }

    /**
     * Logout with facebook url.
     *
     * @return \Illuminate\Http\Response
     */
    public function facebookLogout()
    {
        $isFacebook = Setting::select('value')->where('config_key', 'signInMethod|facebook|enable')->first();

        if (isset($isFacebook) && $isFacebook->value == '1') {

            try {
                $appId  = Setting::select('value')->where('config_key', 'signInMethod|facebook|appId')->first();
                $appSecret = Setting::select('value')->where('config_key', 'signInMethod|facebook|appSecret')->first();
                $redirectUri  = Setting::select('value')->where('config_key', 'signInMethod|facebook|redirectUri')->first();
                $redirectUriLogout  = Setting::select('value')->where('config_key', 'signInMethod|facebook|redirectUriLogout')->first();
                $b2cUrl  = Setting::select('value')->where('config_key', 'general|b2cUrl')->first();

                $config = array(
                    'client_id'     => $appId->value,
                    'client_secret' => $appSecret->value,
                    'redirect'      => str_replace('{{SITE_URL}}', URL('/'), $redirectUri->value)
                );
                Config::set('services.facebook', $config);
                $accessToken = Socialite::driver('facebook')->user()->token;
                $response = Socialite::driver('facebook')->getHttpClient()->get('https://graph.facebook.com/v12.0/me/permissions', [
                    'query' => [
                        'access_token' => $accessToken,
                    ],
                ]);

                $permissions = json_decode($response->getBody(), true);

                if (isset($permissions['data'])) {
                    foreach ($permissions['data'] as $permission) {
                        Socialite::driver('facebook')->getHttpClient()->get('https://graph.facebook.com/v12.0/me/permissions', [
                            'query' => [
                                'access_token' => $accessToken,
                                'method' => 'delete',
                                'permission' => $permission['permission'],
                            ],
                        ]);
                    }
                }

                Auth::logout(); // Laravel's built-in logout method

                return redirect('/login/facebook'); // Redirect to the desired page after logout
            } catch (\Exception $e) {
                return response()->json(['error' => 'Error retrieving user data'], 500);
            }
        }
    }

    /**
     * Redirect to instagram login page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToInstagram(Request $request)
    {   
        session()->forget('language_code');
        $isInstagram = Setting::select('value')->where('config_key', 'signInMethod|instagram|enable')->first();
      
        if (isset($isInstagram) && $isInstagram->value == '1') {

            $appId  = Setting::select('value')->where('config_key', 'signInMethod|instagram|appId')->first();
            $appSecret = Setting::select('value')->where('config_key', 'signInMethod|instagram|appSecret')->first();
            $redirectUri  = Setting::select('value')->where('config_key', 'signInMethod|instagram|redirectUri')->first();
            $language_code = $request->language_code;
            session(['language_code' => $language_code]);
            $client_id = $appId->value;
            $redirect_uri = str_replace('{{SITE_URL}}', URL('/'), $redirectUri->value);
            $url = "https://api.instagram.com/oauth/authorize?client_id={$client_id}&redirect_uri={$redirect_uri}&scope=user_profile&response_type=code";

            return redirect()->away($url);
        }
    }

    /**
     * Handle instagram callback url.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleInstagramCallback(Request $request)
    {  
        $isInstagram = Setting::select('value')->where('config_key', 'signInMethod|instagram|enable')->first();
      
        if (isset($isInstagram) && $isInstagram->value == '1') {

            try {
                $appId  = Setting::select('value')->where('config_key', 'signInMethod|instagram|appId')->first();
                $appSecret = Setting::select('value')->where('config_key', 'signInMethod|instagram|appSecret')->first();
                $redirectUri  = Setting::select('value')->where('config_key', 'signInMethod|instagram|redirectUri')->first();
                
                $code = $request->query('code');
                $client_id = $appId->value;
                $client_secret = $appSecret->value;
                $redirect_uri = str_replace('{{SITE_URL}}', URL('/'), $redirectUri->value);
                $b2cUrl  = Setting::select('value')->where('config_key', 'general|b2cUrl')->first();
                $language_code = session('language_code');
                $httpClient = new Client();
            
                $response = $httpClient->post('https://api.instagram.com/oauth/access_token', [
                    'form_params' => [
                        'client_id' => $client_id,
                        'client_secret' => $client_secret,
                        'redirect_uri' => $redirect_uri,
                        'code' => $code,
                        'grant_type' => 'authorization_code',
                    ],
                ]);
    
                $data = json_decode($response->getBody(), true);
                $access_token = $data['access_token'];
                $user_id = $data['user_id'];
                $user_response = $httpClient->get("https://graph.instagram.com/{$user_id}?fields=id,username&access_token={$access_token}");
                $user_data = json_decode($user_response->getBody(), true);
    
                $email = 'ai.developer16@gmail.com';
                $existingUser = Customer::where('email', $email)->first();
                if ($existingUser) {
                    $newUser = Customer::where('email', $email)
                        ->update([
                            'instagram_id' => $user_data['id']
                         ]);
                    Auth::login($existingUser);
                    $accessToken = $existingUser->createToken('authToken')->accessToken;
                    $userDetail = Customer::where('email', $email)->first();
                } else {
                  
                    $newUser = Customer::create([
                        'first_name' => $user_data['username'] ?? '',
                        'email' => $email ?? '',
                        'instagram_id' => $user_data['id']
                    ]);
    
                    Auth::login($newUser);
                    $accessToken = $newUser->createToken('authToken')->accessToken;
                    $userDetail = Customer::where('email', $email)->first();
    
                    //SEND MAIL FOR CUSTOMER SIGN UP
                    $code = 'CUSTOMER_SIGN_UP';
                    $token = Str::random(60);
    
                    $updateCustomerToken = DB::table('customer_activation_tokens')
                        ->where(['email' => $email])
                        ->first();
                    if (!$updateCustomerToken) {
                        \DB::table('customer_activation_tokens')->insert(
                            ['email' => $email, 'token' => $token, 'created_at' => Carbon::now()]
                        );
                    } else {
                        DB::table('customer_activation_tokens')->where(['email' => $email])->update(
                            ['token' => $updateCustomerToken->token]
                        );
                        $token = $updateCustomerToken->token;
                    }
                    $customerName = ucwords($user_data['username']);
                    $customerEmail = $email;
                    $siteName = count(Setting::where('config_key', 'general|basic|siteName')->get('value')) > 0 ? Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'] : "Amar Infotech";
                    $agencyName = Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'];
                    $agencyLogo = Setting::where('config_key', 'general|basic|colorLogo')->get('value')[0]['value'] ?? Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'];
                    $link = $b2cUrl->value . 'email-verification/' . $token;
    
                    $data = array(
                        'first_name' => $customerName,
                        'site_name' => $siteName,
                        'agency_name' => $agencyName,
                        'agency_logo' => $agencyLogo,
                        'email' => $customerEmail,
                        'activation_link' => $link
                    );
                    $getCustomerSignUp = $this->customerSignUp($code, $data, $language_code);
    
                    $mailData = $getCustomerSignUp['data']['mailData'];
                    $subject = $getCustomerSignUp['data']['subject'];
                    $mailData = $getCustomerSignUp['data']['mailData'];
                    $toEmail = $customerEmail;
                    $files = [];
    
                    // set data in sendEmail function
                    $data = $this->sendEmail($toEmail, $subject, $mailData, $files, $siteName, $code);
                }
    
                return response()->json([
                    'user' => $userDetail,
                    'access_token' => $accessToken
                ]);
            } catch (\Exception $e) {
                return response()->json(['error' => 'Error retrieving user data'], 500);
            }

        }
    }

    /**
     * Redirect to twitter login page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToTwitter(Request $request)
    {
        session()->forget('language_code');
        $isTwitter = Setting::select('value')->where('config_key', 'signInMethod|twitter|enable')->first();
      
        if (isset($isTwitter) && $isTwitter->value == '1') {
  
            $clientId  = Setting::select('value')->where('config_key', 'signInMethod|twitter|clientId')->first();
            $clientSecret = Setting::select('value')->where('config_key', 'signInMethod|twitter|clientSecret')->first();
            $redirectUri  = Setting::select('value')->where('config_key', 'signInMethod|twitter|redirectUri')->first();
            $language_code = $request->language_code;
            $config = array(
                'client_id'     => $clientId->value,
                'client_secret' => $clientSecret->value,
                'redirect'      => str_replace('{{SITE_URL}}', URL('/'), $redirectUri->value)
            );
            Config::set('services.twitter', $config);
            session(['language_code' => $language_code]);

            return Socialite::driver('twitter')->redirect();
        };
    }

    /**
     * Handle twitter callback url.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleTwitterCallback(Request $request)
    {
        $isTwitter = Setting::select('value')->where('config_key', 'signInMethod|twitter|enable')->first();

        if (isset($isTwitter) && $isTwitter->value == '1') {

            try {
                //config process
                $clientId  = Setting::select('value')->where('config_key', 'signInMethod|twitter|clientId')->first();
                $clientSecret = Setting::select('value')->where('config_key', 'signInMethod|twitter|clientSecret')->first();
                $redirectUri  = Setting::select('value')->where('config_key', 'signInMethod|twitter|redirectUri')->first();
                $b2cUrl  = Setting::select('value')->where('config_key', 'general|b2cUrl')->first();
                $language_code = session('language_code');
                $config = array(
                    'client_id'     => $clientId->value,
                    'client_secret' => $clientSecret->value,
                    'redirect'      => str_replace('{{SITE_URL}}', URL('/'), $redirectUri->value)
                );
                Config::set('services.twitter', $config);

                $user = Socialite::driver('twitter')->user();
                $existingUser = Customer::where('email', $user->email)->first();
                if ($existingUser) {
                    $newUser = Customer::where('email', $user->email)
                            ->update([
                                'twitter_id' => $user->id
                            ]);

                    Auth::login($existingUser);
                    $accessToken = $existingUser->createToken('authToken')->accessToken;
                    $userDetail = Customer::where('email', $user->email)->first();
                } else {
                    $newUser = Customer::create([
                        'first_name' => $user->name ?? '',
                        'email' => $user->email ?? '',
                        'profile_photo' => $user->avatar ?? '',
                        'twitter_id' => $user->id
                    ]);

                    Auth::login($newUser);
                    $accessToken = $newUser->createToken('authToken')->accessToken;

                    $userDetail = Customer::where('email', $user->email)->first();

                    $code = 'CUSTOMER_SIGN_UP';
                    $token = Str::random(60);

                    $updateCustomerToken = DB::table('customer_activation_tokens')
                        ->where(['email' => $userDetail['email']])
                        ->first();
                    if (!$updateCustomerToken) {
                        \DB::table('customer_activation_tokens')->insert(
                            ['email' => $userDetail['email'], 'token' => $token, 'created_at' => Carbon::now()]
                        );
                    } else {
                        DB::table('customer_activation_tokens')->where(['email' => $userDetail['email']])->update(
                            ['token' => $updateCustomerToken->token]
                        );
                        $token = $updateCustomerToken->token;
                    }
                    $customerName = ucwords($userDetail['first_name']);
                    $customerEmail = $userDetail['email'];
                    $siteName = count(Setting::where('config_key', 'general|basic|siteName')->get('value')) > 0 ? Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'] : "Amar Infotech";
                    $agencyName = Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'];
                    $agencyLogo = Setting::where('config_key', 'general|basic|colorLogo')->get('value')[0]['value'] ?? Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'];
                    $link = $b2cUrl->value . 'email-verification/' . $token;

                    $data = array(
                        'first_name' => $customerName,
                        'site_name' => $siteName,
                        'agency_name' => $agencyName,
                        'agency_logo' => $agencyLogo,
                        'email' => $customerEmail,
                        'activation_link' => $link
                    );
                    $getCustomerSignUp = $this->customerSignUp($code, $data, $language_code);

                    $mailData = $getCustomerSignUp['data']['mailData'];
                    $subject = $getCustomerSignUp['data']['subject'];
                    $mailData = $getCustomerSignUp['data']['mailData'];
                    $toEmail = $customerEmail;
                    $files = [];

                    // set data in sendEmail function
                    $data = $this->sendEmail($toEmail, $subject, $mailData, $files, $siteName, $code);
                }

                return response()->json([
                    'user' => $userDetail,
                    'access_token' => $accessToken
                ]);
            } catch (\Exception $e) {
                return response()->json(['error' => 'Error retrieving user data'], 500);
            }
        }
    }

    /**
     * Redirect to apple login page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToApple(Request $request)
    {
        session()->forget('language_code');
        $isApple = Setting::select('value')->where('config_key', 'signInMethod|apple|enable')->first();
      
        if (isset($isApple) && $isApple->value == '1') {
  
            $clientId  = Setting::select('value')->where('config_key', 'signInMethod|apple|clientId')->first();
            $clientSecret = Setting::select('value')->where('config_key', 'signInMethod|apple|clientSecret')->first();
            $redirectUri  = Setting::select('value')->where('config_key', 'signInMethod|apple|redirectUrl')->first();
            $language_code = $request->language_code;
            // Retrieve values from the database or any other logic
            $customLoginValue = "/login/apple";
            $customRedirectValue = str_replace('{{SITE_URL}}', '', $redirectUri->value);
            $customClientIdValue = $clientId->value;
            $customClientSecretValue = $clientSecret->value;

            config([
                'services.sign_in_with_apple.redirect' => $customRedirectValue,
                'services.sign_in_with_apple.client_id' => $customClientIdValue,
                'services.sign_in_with_apple.client_secret' => $customClientSecretValue,
            ]);
   
            return Socialite::driver("sign-in-with-apple")
            ->scopes(["name", "email"])
            ->with([
                'state' => json_encode(['language_code' => $language_code])
            ])
            ->redirect();
        };
    }

    /**
     * Handle apple callback url.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleAppleCallback(Request $request)
    {    
        $state = $request->input('state');
        $stateData = json_decode($state, true);
        $language_code = $stateData['language_code'] ?? null;
        $isApple = Setting::select('value')->where('config_key', 'signInMethod|apple|enable')->first();

        if (isset($isApple) && $isApple->value == '1') {

            try {
            
                //config process
                $clientId  = Setting::select('value')->where('config_key', 'signInMethod|apple|clientId')->first();
                $clientSecret = Setting::select('value')->where('config_key', 'signInMethod|apple|clientSecret')->first();
                $redirectUri  = Setting::select('value')->where('config_key', 'signInMethod|apple|redirectUrl')->first();
                $b2cUrl  = Setting::select('value')->where('config_key', 'general|b2cUrl')->first();

                // Retrieve values from the database or any other logic
                $customLoginValue = "/login/apple";
                $customRedirectValue = str_replace('{{SITE_URL}}', '', $redirectUri->value);
                $customClientIdValue = $clientId->value;
                $customClientSecretValue = $clientSecret->value;

                config([
                    'services.sign_in_with_apple.redirect' => $customRedirectValue,
                    'services.sign_in_with_apple.client_id' => $customClientIdValue,
                    'services.sign_in_with_apple.client_secret' => $customClientSecretValue,
                ]);

                // Now you have access to user information
                $user = Socialite::driver("sign-in-with-apple")->user();
          
                $existingUser = Customer::where('email', $user->email)->first();
                if ($existingUser) {
                    $newUser = Customer::where('email', $user->email)
                            ->update([
                                'apple_id' => $user->id
                            ]);

                    Auth::login($existingUser);
                    $accessToken = $existingUser->createToken('authToken')->accessToken;
                    $userDetail = Customer::where('email', $user->email)->first();
                } else {
                    $makeName = explode('@', $user->email);
                    $firstName = $makeName[0];

                    $newUser = Customer::create([
                        'first_name' => $firstName ?? '',
                        'email' => $user->email ?? '',
                        'apple_id' => $user->id
                    ]);

                    Auth::login($newUser);
                    $accessToken = $newUser->createToken('authToken')->accessToken;

                    $userDetail = Customer::where('email', $user->email)->first();
                    
                    $code = 'CUSTOMER_SIGN_UP';
                    $token = Str::random(60);

                    $updateCustomerToken = DB::table('customer_activation_tokens')
                        ->where(['email' => $userDetail['email']])
                        ->first();
                    if (!$updateCustomerToken) {
                        \DB::table('customer_activation_tokens')->insert(
                            ['email' => $userDetail['email'], 'token' => $token, 'created_at' => Carbon::now()]
                        );
                    } else {
                        DB::table('customer_activation_tokens')->where(['email' => $userDetail['email']])->update(
                            ['token' => $updateCustomerToken->token]
                        );
                        $token = $updateCustomerToken->token;
                    }
                    
                    $customerName = ucwords($userDetail['first_name']);
                    $customerEmail = $userDetail['email'];
                    $siteName = count(Setting::where('config_key', 'general|basic|siteName')->get('value')) > 0 ? Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'] : "Amar Infotech";
                    $agencyName = Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'];
                    $agencyLogo = Setting::where('config_key', 'general|basic|colorLogo')->get('value')[0]['value'] ?? Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'];
                    $link = $b2cUrl->value . 'email-verification/' . $token;

                    $data = array(
                        'first_name' => $customerName,
                        'site_name' => $siteName,
                        'agency_name' => $agencyName,
                        'agency_logo' => $agencyLogo,
                        'email' => $customerEmail,
                        'activation_link' => $link
                    );
                    $getCustomerSignUp = $this->customerSignUp($code, $data, $language_code);

                    $mailData = $getCustomerSignUp['data']['mailData'];
                    $subject = $getCustomerSignUp['data']['subject'];
                    $mailData = $getCustomerSignUp['data']['mailData'];
                    $toEmail = $customerEmail;
                    $files = [];

                    // set data in sendEmail function
                    $data = $this->sendEmail($toEmail, $subject, $mailData, $files, $siteName, $code);
                }

                return response()->json([
                    'user' => $userDetail,
                    'access_token' => $accessToken
                ]);
            } catch (\Exception $e) {
                return response()->json(['error' => 'Error retrieving user data'], 500);
            }
        }
    }
}
