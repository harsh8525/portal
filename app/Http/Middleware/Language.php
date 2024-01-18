<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config;

class Language
{
    public function handle($request, Closure $next) {
        // Get the 'lang' query parameter from the request URL
        // Set the application's locale based on the 'lang' parameter
        
        $languageCode = $request->session()->get('languageCode');

        if($languageCode == NULL){
            $appURL = env('APP_URL') . "/api/v1/core/languages";

            // Initialize cURL
            $curl = curl_init();

            // Set the cURL options
            curl_setopt_array($curl, [
                CURLOPT_URL => $appURL,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json',
                ],
            ]);
            $response = curl_exec($curl);
            $responseData = json_decode($response, true);

            $languageCode = 'en';
            if(isset($responseData['data'])){
                foreach ($responseData['data'] as $langCode) {
                    if ($langCode['is_default'] == 1) {
                        $languageCode = $langCode['language_code'];
                    }
                }
            }
            $request->session()->put('languageCode', $languageCode);
        }else{
           $languageCode = session('languageCode');
                    
        }
        
        $request->query('lang', $languageCode);
        App::setLocale($languageCode);
        return $next($request);
    }
}
