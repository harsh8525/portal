<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;




// All routes for admin
Route::group(['namespace' => 'Front'], function () {



    //Google URL
    Route::get('/login/google', 'SocialiteController@redirectToGoogle');
    Route::get('/login/google/callback', 'SocialiteController@handleGoogleCallback');

    //Facebook URL
    Route::get('/login/facebook', 'SocialiteController@redirectToFacebook');
    Route::get('/login/facebook/callback', 'SocialiteController@handleFacebookCallback');
    Route::get('/logout/facebook', 'SocialiteController@facebookLogout');

    //Instagram URL
    Route::get('/login/instagram', 'SocialiteController@redirectToInstagram');
    Route::get('/login/instagram/callback', 'SocialiteController@handleInstagramCallback');

    //Twitter URL
    Route::get('/login/twitter', 'SocialiteController@redirectToTwitter');
    Route::get('/login/twitter/callback', 'SocialiteController@handleTwitterCallback');

    //Apple URL
    Route::get('/login/apple', 'SocialiteController@redirectToApple');
    Route::post('/auth/apple/callback', 'SocialiteController@handleAppleCallback');
});
