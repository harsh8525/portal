<?php

namespace B2BApp\Http\Controllers;

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */


use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
/*
  |--------------------------------------------------------------------------
  | B2B Web Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */

Route::domain('b2b.' . env('APP_URL'))->group(function () {
    Route::get('/', function () {
        return redirect()->route('b2b.login');
    });

    Route::get('/login', function () {

        // if (Auth::guard('b2b')->check()) {
        //     return redirect()->route('b2b.login');
        // }
        return view('b2b/auth/login');
    })->name('b2b.login');
    Route::group(['namespace' => '\B2BApp\Http\Controllers', 'prefix' => ''], function () {
        Route::post('login', 'Auth\LoginController@b2bLogin')->name('b2bLogin');
        Route::get('otp', 'Auth\LoginController@getOtp')->name('b2b.otp');
        Route::get('forgot-password', 'Auth\LoginController@getForgotPassword')->name('b2b.forgot-password');
        Route::post('forgot-password', 'Auth\LoginController@forgotPassword')->name('b2b.forgot-password.save');
        Route::post('otp-verify', 'Auth\LoginController@otpVerify')->name('b2b.otp-verification');
        Route::get('/reset-password/{mobile}', 'Auth\LoginController@getResetPassword')->name('b2b.reset-password.create');
        Route::post('reset-password', 'Auth\LoginController@reset_password')->name('b2b.reset-password.save');
        Route::post('reset-password/check', 'Auth\LoginController@resetPasswordvalidate')->name('b2b.reset-password.validate');





        Route::group(['namespace' => 'B2B', 'middleware' => ['checkRole']], function () {


            //Authenticated routes
            //        Route::group(['middleware' => ['auth:b2b']], function () {
            Route::group(['middleware' => []], function () {

                Route::get('/dashboard', 'DashboardController@index')->name('b2b.dashboard')->middleware('checkpasswordexpiration');
                Route::resource('user', 'B2BuserController', [
                    'as' => 'b2b_user'
                ])->name('user', 'b2b_user');
                Route::get('/deleteUser', 'B2BuserController@deleteUser')->name('b2b.user.delete');
                Route::get('/b2b/user/sendActivationEmail/{id}', 'B2BuserController@sendActivationEmail')->name('b2b.user.activationEmail');
                Route::post('/b2b/user/checkMobile', 'B2BuserController@checkAdminUserExist')->name('b2b.user.checkAdminUser');
                Route::post('/user/checkEmailAgencyUserExist', 'B2BuserController@checkEmailAgencyUserExist')->name('user.checkEmailAgencyUserExist');


                //profiile-update routes
                Route::get('profile/edit', 'ProfileController@edit')->name('b2b.profile.edit');
                Route::put('profile/update/{id}', 'ProfileController@update')->name('b2b.profile.update');

                //change password
                Route::get('change-password/', 'ProfileController@changePasswordUserEdit')->name('b2b.change-password');
                Route::post('change-password/check', 'ProfileController@changePasswordvalidate')->name('b2b.change-password.validate');
                Route::post('change-password/{id}', 'ProfileController@changePasswordUserUpdate')->name('b2b.change-password.update');


                // Manage Agency
                // Route::resource('/agency', 'AgencyController');
                // Route::get('/agency', 'AgencyController@show')->name('b2b.agency.view');
                Route::resource('agency', 'AgencyController', [
                    'as' => 'b2b_agency'
                ])->name('agency', 'b2b_agency');
                Route::post('/b2b-agency/checkEmail', 'AgencyController@checkAgencyEmailExist')->name('b2b.agency-email.checkExist');
                Route::post('/b2b-agency/checkPhone', 'AgencyController@checkAgencyPhoneExist')->name('b2b.agency-phone.checkExist');
                Route::post('/b2b-agency/checkFax', 'AgencyController@checkAgencyFaxExist')->name('b2b.agency-fax.checkExist');
                Route::post('/b2b-agency/checkWebURL', 'AgencyController@checkAgencyWebURLExist')->name('b2b.agency-webUrl.checkExist');
                Route::post('/b2b-operator/checkEmail', 'AgencyController@checkUserEmailExist')->name('b2b.user-email.checkExist');
                Route::post('/b2b-operator/checkMobile', 'AgencyController@checkUserMobileExist')->name('b2b.user-mobile.checkExist');
                Route::get('get-currency', 'AgencyController@getCurrency')->name('b2b.get_currency');



                Route::get('logout', '\B2BApp\Http\Controllers\Auth\LoginController@logout')->name('b2b.logout');

                // Route:: Deposet Request 
                Route::resource('b2b-deposit-request', 'DepositRequestController');
            });
        });
    });
    Route::fallback(function () {
        return view('b2b.404');
    });
});
