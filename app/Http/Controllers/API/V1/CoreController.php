<?php
/**
 * @package     Core
 * @subpackage  Core
 * @Author      Amar Technolabs Pvt. ltd(info@amarinfotech.com)
 * @Copyright(C) 2023 [NAME OF THE ORGANISATION THAT ON BEHALF OF THE CODE WE ARE WORKING].
 * @Version 1.0.0
 * module of the Core.
 */
namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\API\V1\BaseController as BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

use App\Models\Currency;
use App\Models\Page;
use App\Models\Setting;
use App\Models\User;
use App\Models\Country;
use App\Models\SocialMediaLink;
use App\Models\FeatureFlight;
 
use App\Models\Language;
use App\Models\HomeBanner;
use App\Models\ServiceType;
use DB, File;
use App, Lang;

class CoreController extends BaseController
{
    public function __construct()
    {

        $this->perPage = count(Setting::where('config_key', 'general|setting|pagePerAPIRecords')->get('value')) > 0 ? Setting::where('config_key', 'general|setting|pagePerAPIRecords')->get('value')[0]['value'] : "20";
    }
    /**
     * @OA\Get(
     ** path="/v1/core/getGeneral",
     *   tags={"Core"},
     *   summary="get general details information into application ",
     *   description="get general details information <br><br>",
     *   operationId="getGeneral",
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     **/
    public function getGeneralDetails(Request $request)
    {

        $minPassLength = count(Setting::where('config_key', '=', 'passwordSecurity|minimumPasswordLength')->get()) > 0 ? Setting::where('config_key', '=', 'passwordSecurity|minimumPasswordLength')->get('value')[0]['value'] : 0;
        $upperCharCount = count(Setting::where('config_key', '=', 'passwordSecurity|uppercaseCharacter')->get()) > 0 ? Setting::where('config_key', '=', 'passwordSecurity|uppercaseCharacter')->get('value')[0]['value'] : 0;
        $lowerCharCount = count(Setting::where('config_key', '=', 'passwordSecurity|lowercaseCharacter')->get()) > 0 ? Setting::where('config_key', '=', 'passwordSecurity|lowercaseCharacter')->get('value')[0]['value'] : 0;
        $numericCharCount = count(Setting::where('config_key', '=', 'passwordSecurity|numericCharacter')->get()) > 0 ? Setting::where('config_key', '=', 'passwordSecurity|numericCharacter')->get('value')[0]['value'] : 0;
        $specialCharCount = count(Setting::where('config_key', '=', 'passwordSecurity|specialCharacter')->get()) > 0 ? Setting::where('config_key', '=', 'passwordSecurity|specialCharacter')->get('value')[0]['value'] : 0;
        $alphaNumericCharCount = count(Setting::where('config_key', '=', 'passwordSecurity|alphanumericCharacter')->get()) > 0 ? Setting::where('config_key', '=', 'passwordSecurity|alphanumericCharacter')->get('value')[0]['value'] : 0;

        $PasswordValidationArr = [
            'minimum_length' => $minPassLength,
            'upper_case_characters' => $upperCharCount,
            'lower_case_characters' => $lowerCharCount,
            'numeric_characters' => $numericCharCount,
            'special_characters' => $specialCharCount,
            'alphanumeric_characters' => $alphaNumericCharCount
        ];
        $generalInfo['PasswordValidation'] = array($PasswordValidationArr);
        //check is signin method enable or not
        $isSignInMethod  = count(Setting::where('config_key', '=', 'signInMethod|email|enable')->get()) > 0 ? Setting::where('config_key', '=', 'signInMethod|email|enable')->get('value')[0]['value'] : "";
        $generalInfo['SigninMethod']['emailPassword']['enable'] = ($isSignInMethod == '1') ? true : false;

        //get google related information
        $isGoogleEnable = count(Setting::where('config_key', '=', 'signInMethod|google|enable')->get()) > 0 ? Setting::where('config_key', '=', 'signInMethod|google|enable')->get('value')[0]['value'] : "";
        $googleClientID = count(Setting::where('config_key', '=', 'signInMethod|google|clientId')->get()) > 0 ? Setting::where('config_key', '=', 'signInMethod|google|clientId')->get('value')[0]['value'] : "";
        $googleClientSecret = count(Setting::where('config_key', '=', 'signInMethod|google|clientSecret')->get()) > 0 ? Setting::where('config_key', '=', 'signInMethod|google|clientSecret')->get('value')[0]['value'] : "";
        $googleRedirectURI = count(Setting::where('config_key', '=', 'signInMethod|google|redirectUri')->get()) > 0 ? Setting::where('config_key', '=', 'signInMethod|google|redirectUri')->get('value')[0]['value'] : "";
        $googleDeveloperKey = count(Setting::where('config_key', '=', 'signInMethod|google|developerKey')->get()) > 0 ? Setting::where('config_key', '=', 'signInMethod|google|developerKey')->get('value')[0]['value'] : "";
        $generalInfo['google'] = array(
            "enable" => ($isGoogleEnable == '1') ? true : false,
            "clientId" => $googleClientID,
            "clientSecret" => $googleClientSecret,
            "redirectUri" => $googleRedirectURI,
            "developerKey" => $googleDeveloperKey,
            'loginUrl' =>  env('APP_URL_PROTOCOL') . 'admin.' . env('APP_URL') . '/login/google'
        );
        //get facebook related information
        $isFacebookEnable = count(Setting::where('config_key', '=', 'signInMethod|facebook|enable')->get()) > 0 ? Setting::where('config_key', '=', 'signInMethod|facebook|enable')->get('value')[0]['value'] : "";
        $facebookAppID = count(Setting::where('config_key', '=', 'signInMethod|facebook|appId')->get()) > 0 ? Setting::where('config_key', '=', 'signInMethod|facebook|appId')->get('value')[0]['value'] : "";
        $facebookAppSecret = count(Setting::where('config_key', '=', 'signInMethod|facebook|appSecret')->get()) > 0 ? Setting::where('config_key', '=', 'signInMethod|facebook|appSecret')->get('value')[0]['value'] : "";
        $facebookRedirectUri = count(Setting::where('config_key', '=', 'signInMethod|facebook|redirectUri')->get()) > 0 ? Setting::where('config_key', '=', 'signInMethod|facebook|redirectUri')->get('value')[0]['value'] : "";
        $facebookRedirectUriLogout = count(Setting::where('config_key', '=', 'signInMethod|facebook|redirectUriLogout')->get()) > 0 ? Setting::where('config_key', '=', 'signInMethod|facebook|redirectUriLogout')->get('value')[0]['value'] : "";
        $generalInfo['facebook'] = array(
            "enable" => ($isFacebookEnable == '1') ? true : false,
            "appId" => $facebookAppID,
            "appSecret" => $facebookAppSecret,
            "redirectUri" => $facebookRedirectUri,
            "redirectUriLogout" => $facebookRedirectUriLogout,
            'loginUrl' =>  env('APP_URL_PROTOCOL') . 'admin.' . env('APP_URL') . '/login/facebook'
        );
        //get instagram related information
        $isInstagramEnable = count(Setting::where('config_key', '=', 'signInMethod|instagram|enable')->get()) > 0 ? Setting::where('config_key', '=', 'signInMethod|instagram|enable')->get('value')[0]['value'] : "";
        $instagramAppID = count(Setting::where('config_key', '=', 'signInMethod|instagram|appId')->get()) > 0 ? Setting::where('config_key', '=', 'signInMethod|instagram|appId')->get('value')[0]['value'] : "";
        $instagramAppSecret = count(Setting::where('config_key', '=', 'signInMethod|instagram|appSecret')->get()) > 0 ? Setting::where('config_key', '=', 'signInMethod|instagram|appSecret')->get('value')[0]['value'] : "";
        $instagramRedirectUri = count(Setting::where('config_key', '=', 'signInMethod|instagram|redirectUri')->get()) > 0 ? Setting::where('config_key', '=', 'signInMethod|instagram|redirectUri')->get('value')[0]['value'] : "";
        $generalInfo['instagram'] = array(
            "enable" => ($isInstagramEnable == '1') ? true : false,
            "appId" => $instagramAppID,
            "appSecret" => $instagramAppSecret,
            "redirectUri" => $instagramRedirectUri,
            'loginUrl' =>  env('APP_URL_PROTOCOL') . 'admin.' . env('APP_URL') . '/login/instagram'
        );
        //get twitter related information
        $isTwitterEnable = count(Setting::where('config_key', '=', 'signInMethod|twitter|enable')->get()) > 0 ? Setting::where('config_key', '=', 'signInMethod|twitter|enable')->get('value')[0]['value'] : "";
        $twitterAppID = count(Setting::where('config_key', '=', 'signInMethod|twitter|clientId')->get()) > 0 ? Setting::where('config_key', '=', 'signInMethod|twitter|clientId')->get('value')[0]['value'] : "";
        $twitterAppSecret = count(Setting::where('config_key', '=', 'signInMethod|twitter|clientSecret')->get()) > 0 ? Setting::where('config_key', '=', 'signInMethod|twitter|clientSecret')->get('value')[0]['value'] : "";
        $twitterRedirectUri = count(Setting::where('config_key', '=', 'signInMethod|twitter|redirectUri')->get()) > 0 ? Setting::where('config_key', '=', 'signInMethod|twitter|redirectUri')->get('value')[0]['value'] : "";
        $generalInfo['twitter'] = array(
            "enable" => ($isTwitterEnable == '1') ? true : false,
            "clientId" => $twitterAppID,
            "clientSecret" => $twitterAppSecret,
            "redirectUri" => $twitterRedirectUri,
            'loginUrl' =>  env('APP_URL_PROTOCOL') . 'admin.' . env('APP_URL') . '/login/twitter'
        );
        //get apple related information
        $isAppleEnable = count(Setting::where('config_key', '=', 'signInMethod|apple|enable')->get()) > 0 ? Setting::where('config_key', '=', 'signInMethod|apple|enable')->get('value')[0]['value'] : "";
        $appleAppID = count(Setting::where('config_key', '=', 'signInMethod|apple|clientId')->get()) > 0 ? Setting::where('config_key', '=', 'signInMethod|apple|clientId')->get('value')[0]['value'] : "";
        $appleTeamID = count(Setting::where('config_key', '=', 'signInMethod|apple|team_id')->get()) > 0 ? Setting::where('config_key', '=', 'signInMethod|apple|team_id')->get('value')[0]['value'] : "";
        $appleKeyID = count(Setting::where('config_key', '=', 'signInMethod|apple|key_id')->get()) > 0 ? Setting::where('config_key', '=', 'signInMethod|apple|key_id')->get('value')[0]['value'] : "";
        $appleAppSecret = count(Setting::where('config_key', '=', 'signInMethod|apple|clientSecret')->get()) > 0 ? Setting::where('config_key', '=', 'signInMethod|apple|clientSecret')->get('value')[0]['value'] : "";
        $appleRedirectUri = count(Setting::where('config_key', '=', 'signInMethod|apple|redirectUrl')->get()) > 0 ? Setting::where('config_key', '=', 'signInMethod|apple|redirectUrl')->get('value')[0]['value'] : "";
        $generalInfo['apple'] = array(
            "enable" => ($isAppleEnable == '1') ? true : false,
            "clientId" => $appleAppID,
            "teamId" => $appleTeamID,
            "keyId" => $appleKeyID,
            "clientSecret" => $appleAppSecret,
            "redirectUri" => $appleRedirectUri,
            'loginUrl' =>  env('APP_URL_PROTOCOL') . 'admin.' . env('APP_URL') . '/login/apple'
        );
        //get basic information
        $generalInfo['colorLogo'] = count(Setting::where('config_key', '=', 'general|basic|colorLogo')->get()) > 0 ? Setting::where('config_key', '=', 'general|basic|colorLogo')->get('value')[0]['value'] : "";
        $generalInfo['whiteLogo'] = count(Setting::where('config_key', '=', 'general|basic|whiteLogo')->get()) > 0 ? Setting::where('config_key', '=', 'general|basic|whiteLogo')->get('value')[0]['value'] : "";
        $generalInfo['organizationName'] = count(Setting::where('config_key', '=', 'general|basic|siteName')->get()) > 0 ? Setting::where('config_key', '=', 'general|basic|siteName')->get('value')[0]['value'] : "";
        $generalInfo['siteURL'] = count(Setting::where('config_key', '=', 'general|basic|siteUrl')->get()) > 0 ? Setting::where('config_key', '=', 'general|basic|siteUrl')->get('value')[0]['value'] : "";
        $generalInfo['siteEmail'] = count(Setting::where('config_key', '=', 'general|basic|siteEmail')->get()) > 0 ? Setting::where('config_key', '=', 'general|basic|siteEmail')->get('value')[0]['value'] : "";
        $generalInfo['sitePhone'] = count(Setting::where('config_key', '=', 'general|basic|sitePhoneNo')->get()) > 0 ? Setting::where('config_key', '=', 'general|basic|sitePhoneNo')->get('value')[0]['value'] : "";
        $generalInfo['storeAddress'] = count(Setting::where('config_key', '=', 'general|basic|storeAddress')->get()) > 0 ? Setting::where('config_key', '=', 'general|basic|storeAddress')->get('value')[0]['value'] : "";
        $generalInfo['storeCountry'] = count(Setting::where('config_key', '=', 'general|basic|storeCountry')->get()) > 0 ? Setting::where('config_key', '=', 'general|basic|storeCountry')->get('value')[0]['value'] : "";
        $generalInfo['storeState'] = count(Setting::where('config_key', '=', 'general|basic|storeState')->get()) > 0 ? Setting::where('config_key', '=', 'general|basic|storeState')->get('value')[0]['value'] : "";
        $generalInfo['storeCity'] = count(Setting::where('config_key', '=', 'general|basic|storeCity')->get()) > 0 ? Setting::where('config_key', '=', 'general|basic|storeCity')->get('value')[0]['value'] : "";
        $generalInfo['storePinCode'] = count(Setting::where('config_key', '=', 'general|basic|storePincode')->get()) > 0 ? Setting::where('config_key', '=', 'general|basic|storePincode')->get('value')[0]['value'] : "";
        $generalInfo['storeGSTNo'] = count(Setting::where('config_key', '=', 'general|basic|storeGSTNo')->get()) > 0 ? Setting::where('config_key', '=', 'general|basic|storeGSTNo')->get('value')[0]['value'] : "";
        $generalInfo['timeFormat'] = count(Setting::where('config_key', '=', 'general|site|timeFormat')->get()) > 0 ? Setting::where('config_key', '=', 'general|site|timeFormat')->get('value')[0]['value'] : "";
        $generalInfo['dateFormat'] = count(Setting::where('config_key', '=', 'general|site|dateFormat')->get()) > 0 ? Setting::where('config_key', '=', 'general|site|dateFormat')->get('value')[0]['value'] : "";
        $generalInfo['timeZone'] = count(Setting::where('config_key', '=', 'general|site|timeZone')->get()) > 0 ? Setting::where('config_key', '=', 'general|site|timeZone')->get('value')[0]['value'] : "";
        $generalInfo['googleAPIKey'] = count(Setting::where('config_key', '=', 'general|site|googleApiKey')->get()) > 0 ? Setting::where('config_key', '=', 'general|site|googleApiKey')->get('value')[0]['value'] : "";
        $generalInfo['inquiryEmail'] = count(Setting::where('config_key', '=', 'general|site|inquiryEmail')->get()) > 0 ? Setting::where('config_key', '=', 'general|site|inquiryEmail')->get('value')[0]['value'] : "";
        $generalInfo['defaultISDCode'] = count(Setting::where('config_key', '=', 'general|site|defaultISDCode')->get()) > 0 ? Setting::where('config_key', '=', 'general|site|defaultISDCode')->get('value')[0]['value'] : "";
        $generalInfo['defaultLanguageCode'] = count(Setting::where('config_key', '=', 'general|site|defaultLanguageCode')->get()) > 0 ? Setting::where('config_key', '=', 'general|site|defaultLanguageCode')->get('value')[0]['value'] : "";
        $generalInfo['androidUpdate'] = count(Setting::where('config_key', '=', 'general|androidUpdate')->get()) > 0 ? Setting::where('config_key', '=', 'general|androidUpdate')->get('value')[0]['value'] : "";
        $generalInfo['iosUpdate'] = count(Setting::where('config_key', '=', 'general|iosUpdate')->get()) > 0 ? Setting::where('config_key', '=', 'general|iosUpdate')->get('value')[0]['value'] : "";
        // $generalInfo['shortAboutUs'] = count(Setting::where('config_key', '=', 'general|basic|shortAboutUs')->get()) > 0 ? Setting::where('config_key', '=', 'general|basic|shortAboutUs')->get('value')[0]['value'] : "";

        $success = 1;
        return $this->sendResponse($generalInfo, 'General preferences founded!', $success);
    }

    /**
     * @OA\Get(
     ** path="/v1/core/currencies",
     *   tags={"Core"},
     *   summary="get currency details information into application ",
     *   description="get currency details information <br><br>",
     *   operationId="currencies",
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     **/
    public function getCurrenciesDetails(Request $request)
    {

        //get base currency details
        $getBaseCurrency = Currency::where('is_base_currency', '1')->get()->toArray();
        $getCurrencies['base_cur'] = array(
            'uuid' => $getBaseCurrency[0]['id'],
            "code" => $getBaseCurrency[0]['code'],
            "name" => $getBaseCurrency[0]['name'],
            "symbol" => $getBaseCurrency[0]['symbol'] ?? '',
        );
        
        //get default display currency details
        $getDefaultCurrency = Currency::where('is_default', '1')->get()->toArray();
        $getCurrencies['default_display_cur'] = array(
            'uuid' => $getDefaultCurrency[0]['id'],
            "code" => $getDefaultCurrency[0]['code'],
            "name" => $getDefaultCurrency[0]['name'],
            "symbol" => $getDefaultCurrency[0]['symbol'] ?? '',
        );
        //get top currency details
        $getTopCurrency = Currency::where('is_top_cur', '1')->get()->toArray();
        $getCurrencies['top_cur'] = [];
        foreach ($getTopCurrency as $currency) {
            $tempTopArray = array(
                'uuid' => $currency['id'],
                "code" => $currency['code'],
                "name" => $currency['name'],
                "symbol" => $currency['symbol'] ?? '',
            );
            array_push($getCurrencies['top_cur'], $tempTopArray);
        }
        
        //get allow currency details
        $getAllowCurrency = Currency::where('is_allowed', '1')->get()->toArray();
        $getCurrencies['allow_cur'] = [];
        foreach ($getAllowCurrency as $currency) {
            $tempAllowArray = array(
                'uuid' => $currency['id'],
                "code" => $currency['code'],
                "name" => $currency['name'],
                "symbol" => $currency['symbol'] ?? '',
            );
            array_push($getCurrencies['allow_cur'], $tempAllowArray);
        }
        $success = 1;
        return $this->sendResponse($getCurrencies, 'Currency details founded.', $success);
    }

    /**
     * @OA\Get(
     *   path="/v1/core/featured-flight",
     *   tags={"Core"},
     *   summary="Get featured flight details",
     *   description="Represents the currency code used for transactions. Use ISO 4217 standard codes (e.g., SAR, USD, EUR).<br>",
     *   operationId="featured-flight",
     *   @OA\Parameter(
            name="body",
            in="query",
            required=false,
            explode=true,
            @OA\Schema(
                 collectionFormat="multi",
                 @OA\Property(property="currencyCode", type="string", default="SAR" ),
            ),
         ), 
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     * )
     *
     * @return \Illuminate\Http\Response
     */
    public function getFeatureFlightDetails(Request $request)
    {
        try {

            $currencyCode = $request->currencyCode ?? 'SAR';
            $defaultCurrency = "SAR";

            $checkAllowedCurrency = convertCurrencyExchangeRate('1', $defaultCurrency, $currencyCode, []);
            if ($checkAllowedCurrency['status'] == false) {
                $success = [];
                return $this->sendError($success, 'Currency not allowed.'); 
            }

            $getFeatureFlightList = FeatureFlight::with(['getAirline', 'getFromAirport.getCountry', 'getFromAirport.getCity', 'getToAirport.getCountry', 'getToAirport.getCity'])
                ->where('status', 1)
                ->paginate($this->perPage);
                
            $featuredFlightData = collect($getFeatureFlightList->items())->map(function ($featureflight) use ($currencyCode, $defaultCurrency){

                $airline_en = '';
                $airline_ar = '';
                $from_air_en = '';
                $from_air_ar = '';
                $to_air_en = '';
                $to_air_ar = '';
                $from_country_en = '';
                $from_country_ar = '';
                $to_country_en = '';
                $to_country_ar = '';
                $from_city_en = '';
                $from_city_ar = '';
                $to_city_en = '';
                $to_city_ar = '';

                if (!empty($featureflight['getAirline']['airlineCodeName'])) {
                    foreach ($featureflight['getAirline']['airlineCodeName'] as $airlineName) {
                        switch ($airlineName['language_code']) {
                            case 'en':
                                $airline_en = $airlineName['airline_name'];
                                break;
                            case 'ar':
                                $airline_ar = $airlineName['airline_name'];
                                break;
                        }
                    }
                }

                if (!empty($featureflight['getFromAirport']['airportName'])) {
                    foreach ($featureflight['getFromAirport']['airportName'] as $fromairport) {
                        switch ($fromairport['language_code']) {
                            case 'en':
                                $from_air_en = $fromairport['airport_name'];
                                break;
                            case 'ar':
                                $from_air_ar = $fromairport['airport_name'];
                                break;
                        }
                    }
                }

                if (!empty($featureflight['getToAirport']['airportName'])) {
                    foreach ($featureflight['getToAirport']['airportName'] as $toairport) {
                        switch ($toairport['language_code']) {
                            case 'en':
                                $to_air_en = $toairport['airport_name'];
                                break;
                            case 'ar':
                                $to_air_ar = $toairport['airport_name'];
                                break;
                        }
                    }
                }
                if (!empty($featureflight['getFromAirport']['getCountry']['countryCode'])) {
                    foreach ($featureflight['getFromAirport']['getCountry']['countryCode'] as $fromcountry) {
                        switch ($fromcountry['language_code']) {
                            case 'en':
                                $from_country_en = $fromcountry['country_name'];
                                break;
                            case 'ar':
                                $from_country_ar = $fromcountry['country_name'];
                                break;
                        }
                    }
                }

                if (!empty($featureflight['getToAirport']['getCountry']['countryCode'])) {
                    foreach ($featureflight['getToAirport']['getCountry']['countryCode'] as $tocountry) {
                        switch ($tocountry['language_code']) {
                            case 'en':
                                $to_country_en = $tocountry['country_name'];
                                break;
                            case 'ar':
                                $to_country_ar = $tocountry['country_name'];
                                break;
                        }
                    }
                }
                if (!empty($featureflight['getFromAirport']['getCity']['cityCode'])) {
                    foreach ($featureflight['getFromAirport']['getCity']['cityCode'] as $fromcity) {
                        switch ($fromcity['language_code']) {
                            case 'en':
                                $from_city_en = $fromcity['city_name'];
                                break;
                            case 'ar':
                                $from_city_ar = $fromcity['city_name'];
                                break;
                        }
                    }
                }

                if (!empty($featureflight['getToAirport']['getCity']['cityCode'])) {
                    foreach ($featureflight['getToAirport']['getCity']['cityCode'] as $tocity) {
                        switch ($tocity['language_code']) {
                            case 'en':
                                $to_city_en = $tocity['city_name'];
                                break;
                            case 'ar':
                                $to_city_ar = $tocity['city_name'];
                                break;
                        }
                    }
                }

                $convertPriceExchangeRate = convertCurrencyExchangeRate($featureflight['price'], $defaultCurrency, $currencyCode, []);
                            
                return [
                    'id' => $featureflight['id'],
                    'airline_code' => $featureflight['airline_code'],
                    'from_airport_code' => $featureflight['from_airport_code'],
                    'to_airport_code' => $featureflight['to_airport_code'],
                    'location_image' => $featureflight['location_image'],
                    'price' => $convertPriceExchangeRate['data']['convertedRate'],
                    'displayPrice' => number_format($convertPriceExchangeRate['data']['convertedRate']),
                    'currencySymbol' => $convertPriceExchangeRate['data']['symbol'],
                    'status' => $featureflight['status'],
                    'created_at'    => $featureflight['created_at'],
                    'updated_at' => $featureflight['updated_at'],
                    'airline_en' => $airline_en,
                    'airline_ar' => $airline_ar,
                    'from_airport_name_en' => $from_air_en,
                    'from_airport_name_ar' => $from_air_ar,
                    'to_airport_name_en' => $to_air_en,
                    'to_airport_name_ar' => $to_air_ar,
                    'from_country_name_en' => $from_country_en,
                    'from_country_name_ar' => $from_country_ar,
                    'to_country_name_en' => $to_country_en,
                    'to_country_name_ar' => $to_country_ar,
                    'from_city_name_en' => $from_city_en,
                    'from_city_name_ar' => $from_city_ar,
                    'to_city_name_en' => $to_city_en,
                    'to_city_name_ar' => $to_city_ar,
                    'airline_logo' => $featureflight['getAirline']['airline_logo'] ? $featureflight['getAirline']['airline_logo'] : URL::asset('assets/images/airlineLogo/' . $featureflight['getAirline']['airline_code'] . '.png')
                ];
            });

            $output = [
                'current_page' => $getFeatureFlightList->currentPage(),
                'data' => $featuredFlightData->values()->toArray(),
                'first_page_url' => $getFeatureFlightList->url(1),
                'from' => $getFeatureFlightList->firstItem(),
                'last_page' => $getFeatureFlightList->lastPage(),
                'last_page_url' => $getFeatureFlightList->url($getFeatureFlightList->lastPage()),
                'links' => [
                    [
                        'url' => $getFeatureFlightList->previousPageUrl(),
                        'label' => '&laquo; Previous',
                        'active' => $getFeatureFlightList->onFirstPage(),
                    ],
                    [
                        'url' => $getFeatureFlightList->url(1),
                        'label' => '1',
                        'active' => $getFeatureFlightList->currentPage() === 1,
                    ],
                    [
                        'url' => $getFeatureFlightList->nextPageUrl(),
                        'label' => 'Next &raquo;',
                        'active' => $getFeatureFlightList->hasMorePages(),
                    ],
                ],
                'next_page_url' => $getFeatureFlightList->nextPageUrl(),
                'path' => $getFeatureFlightList->path(),
                'per_page' => $getFeatureFlightList->perPage(),
                'prev_page_url' => $getFeatureFlightList->previousPageUrl(),
                'to' => $getFeatureFlightList->lastItem(),
                'total' => $getFeatureFlightList->total(),
            ];

            return $this->sendResponse($output, 'Featured Flight Data Fetch Successfully.');
        } catch (Exception $e) {
            $success = [];
            return $this->sendError($success, 'Something went wrong', ['error' => $e], 500);
        }
    }

    /**
     * @OA\Get(
     ** path="/v1/core/languages",
     *   tags={"Core"},
     *   summary="get language details information into application ",
     *   description="get language details information <br><br>",
     *   operationId="languages",
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     **/

    public function getLanguageDetails(Request $request)
    {
        $getLanguageData = Language::select('id', 'language_code', 'language_name', 'language_type', 'status', 'sort_order', 'is_default')->where('status', '1')->orderBy('sort_order', 'ASC')->get()->toArray();

        $success = 1;
        return $this->sendResponse($getLanguageData, 'Language details founded.', $success);
    }

    /**
     * @OA\Get(
     ** path="/v1/core/language-translate",
     *   tags={"Core"},
     *   summary="get language translate information into application ",
     *   description="need to pass language code from get language api due to get language translate information list<br><br>",
     *   operationId="language-translate",
     *   @OA\Parameter(
            name="body",
            in="query",
            required=false,
            explode=true,
            @OA\Schema(
                 @OA\Property(property="lang_code", type="string",  ),
            ),
        ), 
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     **/

    public function getLanguageTranslateDetails(Request $request)
    {
        $lang_code = request()->input('lang_code');

        $filePath = resource_path("lang/{$lang_code}/B2CTranslate.php");

        if (File::exists($filePath)) {
            App::setLocale($lang_code);
            $fileNames = [basename($filePath, '.php')];

            foreach ($fileNames as $fileName) {
                $files[$fileName] = Lang::get($fileName);
            }
        } else {
            $filePath = resource_path("lang/ar/B2CTranslate.php");

            App::setLocale('ar');
            $fileNames = [basename($filePath, '.php')];

            foreach ($fileNames as $fileName) {
                $files[$fileName] = Lang::get($fileName);
            }
        }
        $success = 1;
        return $this->sendResponse($files, 'Language translate details founded.', $success);
    }

    /**
     * @OA\Get(
     ** path="/v1/core/service-types",
     *   tags={"Core"},
     *   summary="get service types information into application ",
     *   description="get service types information list<br><br>",
     *   operationId="service-types",
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     **/

    public function getServiceTypeDetails(Request $request)
    {
        $getServiceTypeData = ServiceType::select('id', 'name', 'code', 'description', 'guideline', 'image', 'sort_order', 'is_active')->where('is_active', '1')->orderBy('sort_order', 'ASC')->get()->toArray();

        $success = 1;
        return $this->sendResponse($getServiceTypeData, 'Service types details founded.', $success);
    }

    /**
     * @OA\Get(
     *   path="/v1/core/get-home-banner",
     *   tags={"Core"},
     *   summary="Get Home Banner",
     *   description="get home banners details",
     *   operationId="getHomeBanners",
     *   @OA\Parameter(
     *      name="body",
     *      in="query",
     *      @OA\Schema(
     *           collectionFormat="multi",
     *           @OA\Property(property="language_code", type="string",default="ar"),
     *      )
     *   ),
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,   
     *          description="Forbidden"
     *      )
     * )
     * )
     *
     * @return \Illuminate\Http\Response
     */
    public function getHomeBanners(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'language_code' => 'required|exists:core_languages,language_code',


            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => 'Invalid request', 'data' => [$validator->errors()]], 422);
            }
            $languageCode = 'ar';
            // get all home banners
            $todayDate = date("Y-m-d");
            $homeBanner = HomeBanner::select(
                'home_banners.*',
                'banner_title as banne_name',
                DB::raw('(CASE WHEN home_banners.status = "0" THEN "In-Active" '
                    . 'WHEN home_banners.status = "1" THEN "Active" '
                    . 'END) AS home_banners_status_text')
            )
                ->join('home_banner_i18ns', 'home_banner_i18ns.banner_id', 'home_banners.id')
                ->where('home_banner_i18ns.language_code', $request->language_code)
                ->whereDate('home_banners.from_date', '<=', $todayDate)
                ->whereDate('home_banners.to_date', '>=', $todayDate)
                ->where('home_banners.panel', 'b2c')
                ->where('home_banners.status', 1)
                ->orderBy('home_banners.sort_order', 'ASC')
                ->get()
                ->makeHidden(['home_banners.banner_type']);
            return $this->sendResponse($homeBanner, 'Home Banner Fetch Successfully.');
        } catch (Exception $e) {
            $success = [];
            return $this->sendError($success, 'Something went wrong', ['error' => $e], 500);
        }
    }
    /**
     * @OA\Get(
     *   path="/v1/core/get-arabic-speak-countries",
     *   tags={"Core"},
     *   summary="Get Arabic Speak Countries",
     *   description="get Arabic Speak Countries",
     *   operationId="getArabicSpeakCountries",
     *   @OA\Parameter(
     *      name="body",
     *      in="query",
     *      @OA\Schema(
     *           collectionFormat="multi",
     *           @OA\Property(property="language_code", type="string",default="ar"),
     *      )
     *   ),
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,   
     *          description="Forbidden"
     *      )
     * )
     * )
     *
     * @return \Illuminate\Http\Response
     */
    public function getArabicSpeakCountries(Request $request)
    {
        
        try {
            $validator = Validator::make($request->all(), [
                'language_code' => 'required|exists:core_languages,language_code',


            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => 'Invalid request', 'data' => [$validator->errors()]], 422);
            }
            $languageCode = 'ar';
            // get all home banners
            
            $query = Setting::where('config_key', 'general|site|arabic_speak_country')->get('value')[0]['value'];
            $countryCode['ArabicSpeakCountries'] = explode(',', $query);
               
            return $this->sendResponse($countryCode, 'Arabic Speak Countries Fetch Successfully.');
        } catch (Exception $e) {
            $success = [];
            return $this->sendError($success, 'Something went wrong', ['error' => $e], 500);
        }
    }
     /**
     * @OA\Get(
     ** path="/v1/core/maintenance-mode",
     *   tags={"Core"},
     *   summary="Check site is in maintenance mode or not",
     *   operationId="maintenance-mode",
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     **/
    public function checkMaintenance() {
        try{
            $maintenance_mode = Setting::where('config_key', 'general|maintenanceMode')->get('value')[0]['value']; 
            
            $maintenance["mode"]=$maintenance_mode;
            
            if($maintenance){
                if($maintenance_mode=="on"){
                    $maintenance_message = Setting::where('config_key', 'general|maintenanceMode|message')->get('value')[0]['value']; 
                    $maintenance["message"]=$maintenance_message;
                    return $this->sendResponse($maintenance, 'Maintenance Mode.');
                }else{
                    return $this->sendResponse($maintenance, 'Maintenance Mode.');
                }
            }else{
                return $this->sendResponse($maintenance, 'Maintenance Mode.');
            }
            
        }catch(Exception $ex){
            $success = [];
            return $this->sendError('Error during Get Maintenance Mode',$success);
        }
    }
     /**
     * @OA\Get(
     ** path="/v1/core/social-media",
     *   tags={"Core"},
     *   summary="get social media",
     *   operationId="social-media",
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     **/
    public function socialMedia() {
        try{
            $social_media = SocialMediaLink::where('status', 'active')->get(); 
          
            return $this->sendResponse($social_media, 'Social Media Fetch Successfully.');
        } catch (Exception $e) {
            $success = [];
            return $this->sendError($success, 'Something went wrong', ['error' => $e], 500);
        }
    }
     /**
     * @OA\Get(
     ** path="/v1/core/instagram-feed",
     *   tags={"Core"},
     *   summary="get instagram feed",
     *   operationId="instagram-feed",
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     **/
    public function instagramFeed() {
        try{
            $generalInfo['instagramFeed'] = count(Setting::where('config_key', '=', 'instagramFeed')->get()) > 0 ? Setting::where('config_key', '=', 'instagramFeed')->get('value')[0]['value'] : "";
          
            return $this->sendResponse($generalInfo, 'Instagram Feed Fetch Successfully.');
        } catch (Exception $e) {
            $success = [];
            return $this->sendError($success, 'Something went wrong', ['error' => $e], 500);
        }
    }

    /**
     * @OA\Get(
     ** path="/v1/core/app-download-preference",
     *   tags={"Core"},
     *   summary="get app download preference",
     *   operationId="app-download-preference",
     *   @OA\Parameter(
            name="body",
            in="query",
            required=false,
            explode=true,
            @OA\Schema(
                 collectionFormat="multi",
                 @OA\Property(property="languageCode", type="string", default="en" ),
            ),
         ), 
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     **/
    public function appDownloadPreference(Request $request) {
        try{   
            $titleEn = Setting::where('config_key','appDownloadPreference|titleEn')->first();
            $titleAr = Setting::where('config_key','appDownloadPreference|titleAr')->first();
            $qrCodeImage = Setting::where('config_key','appDownloadPreference|qrCodeImage')->first();
            $bannerImageEn = Setting::where('config_key','appDownloadPreference|bannerImageEn')->first();
            $bannerImageAr = Setting::where('config_key','appDownloadPreference|bannerImageAr')->first();
            $googlePlaystoreURL = Setting::where('config_key','appDownloadPreference|googlePlaystoreURL')->first();
            $appStoreURL = Setting::where('config_key','appDownloadPreference|appStoreURL')->first();
            $HUAWEIStoreURL = Setting::where('config_key','appDownloadPreference|HUAWEIStoreURL')->first();
            $shortAboutUsEn = Setting::where('config_key','general|basic|shortAboutUsEn')->first();
            $shortAboutUsAr = Setting::where('config_key','general|basic|shortAboutUsAr')->first();

            if ($request->languageCode == 'ar') {
                $data = [
                    'title' => $titleAr->value ?? "",
                    'qr_code_image' => $qrCodeImage->value ?? "",
                    'banner_image' => $bannerImageAr->value ?? "",
                    'google_playstore_url' => $googlePlaystoreURL->value ?? "",
                    'app_store_url' => $appStoreURL->value ?? "",
                    'HUAWEI_store_url' => $HUAWEIStoreURL->value ?? "",
                    'short_about_us' => $shortAboutUsAr->value ?? ""
                ];
            }else{
                $data = [
                    'title' => $titleEn->value ?? "",
                    'qr_code_image' => $qrCodeImage->value ?? "",
                    'banner_image' => $bannerImageEn->value ?? "",
                    'google_playstore_url' => $googlePlaystoreURL->value ?? "",
                    'app_store_url' => $appStoreURL->value ?? "",
                    'HUAWEI_store_url' => $HUAWEIStoreURL->value ?? "",
                    'short_about_us' => $shortAboutUsEn->value ?? ""
                ];
            }

            return $this->sendResponse($data, 'App Download Preference Fetch Successfully.');
        } catch (Exception $e) {
            $success = [];
            return $this->sendError($success, 'Something went wrong', ['error' => $e], 500);
        }
    }
}
