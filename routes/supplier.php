<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

use Illuminate\Support\Facades\Route;

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

Route::get('/supplier', function () {
    return redirect()->route('supplier.login');
});

Route::get('supplier/login', function () {
    if (Auth::check()) {
        return redirect()->route('supplier.dashboard');
    }
    return view('admin/auth/login');
})->name('supplier.login');

Route::group(['namespace' => 'Supplier', 'prefix' => 'supplier'], function () {

    //Authenticated routes
    Route::group(['middleware' => ['auth', 'checkRole']], function () {
        Route::get('/dashboard', 'DashboardController@index')->name('supplier.dashboard')->middleware('checkpasswordexpiration');

        //profiile-update routes
        Route::get('profile/edit', 'ProfileController@edit')->name('supplier.profile.edit');
        Route::put('profile/update/{id}', 'ProfileController@update')->name('supplier.profile.update');

        //change password
        Route::get('change-password/', 'ProfileController@changePasswordUserEdit')->name('supplier.change-password');
        Route::post('change-password/check', 'ProfileController@changePasswordvalidate')->name('supplier.change-password.validate');
        Route::post('change-password/{id}', 'ProfileController@changePasswordUserUpdate')->name('supplier.change-password.update');
    });
});
