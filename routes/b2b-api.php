<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['namespace' => 'API\V1', 'prefix' => 'v1'], function () {
    Route::post('login', 'AuthGeneralController@login');
    // Route::post('signup','AuthGeneralController@signup');
    // Route::post('forgot-password','AuthGeneralController@forgotPassword');
    // Route::post('mobile-verification-otp/send', 'AuthGeneralController@otpSend');
    // Route::post('check-user-exists','AuthGeneralController@checkUserExists');
    // Route::post('mobile-verification-otp/verify', 'AuthGeneralController@otpVerify');
    // Core API


    // Route::get('getGeneral','CoreController@getGeneralDetails');
    // Route::get('currencies','CoreController@getCurrenciesDetails');
    // Route::get('featured-flight','CoreController@getFeatureFlightDetails');
    // Route::get('languages','CoreController@getLanguageDetails');

    //fetch home banner data
    // Route::get('get-home-banner','CoreController@getHomeBanners');

});

Route::group(['prefix' => 'user'], function () {
    // Route::get('get','UserController@getUser');
    // Route::post('create','UserController@createUser');
    // Route::post('update','UserController@userUpdate');
    // Route::post('change-password','UserController@changePassword');
    // Route::get('currencies', 'CoreController@getCurrenciesDetails');
    // Route::get('featured-flight', 'CoreController@getFeatureFlightDetails');
    // Route::get('languages', 'CoreController@getLanguageDetails');

    //fetch home banner data
    // Route::get('get-home-banner', 'CoreController@getHomeBanners');
});
