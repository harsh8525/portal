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

    // Auth Api User Login
    Route::post('api-login', 'AuthGeneralController@ApiUserLogin');
    Route::get('check-token-validity', 'AuthGeneralController@checkTokenValidity');
    Route::get('refresh-token', 'AuthGeneralController@refreshToken');

    Route::group(['middleware' => ['auth:api-login-b2cuser']], function () {
        Route::group(['prefix' => 'hotel'], function () {
            Route::post('hotel-auto-search', 'HotelController@hotelSearch');
            Route::post('hotel-details', 'HotelController@hotelDetail');
            Route::post('hotel-availability', 'HotelController@hotelAvailability');
            Route::post('check-rate', 'HotelController@hotelCheckRate');
            Route::post('booking-confirmation', 'HotelController@hotelBookingConfirmation');
            Route::get('booking-detail', 'HotelController@hotelBookingDetail');
            Route::delete('booking-cancellation', 'HotelController@hotelBookingCancellation');
        });
    });

    // Auth General API
    Route::post('login', 'AuthGeneralController@login');
    Route::post('signup', 'AuthGeneralController@signup');

    Route::post('check-user-exists', 'AuthGeneralController@checkUserExists');

    // Google Map API
    Route::post('google-place-search', 'GoogleMapController@placeSearch');
    Route::post('google-detail-place-search', 'GoogleMapController@placeDetailSearch');
    Route::post('geo-location-by-ip', 'GoogleMapController@geoLocationByIp');

    // Core API
    Route::group(['prefix' => 'core'], function () {

        Route::get('getGeneral', 'CoreController@getGeneralDetails');
        Route::get('currencies', 'CoreController@getCurrenciesDetails');
        Route::get('featured-flight', 'CoreController@getFeatureFlightDetails');
        Route::get('languages', 'CoreController@getLanguageDetails');
        Route::get('language-translate', 'CoreController@getLanguageTranslateDetails');
        Route::get('service-types', 'CoreController@getServiceTypeDetails');
        Route::get('maintenance-mode', 'CoreController@checkMaintenance');
        Route::get('social-media', 'CoreController@socialMedia');
        Route::get('instagram-feed', 'CoreController@instagramFeed');
        Route::get('app-download-preference', 'CoreController@appDownloadPreference');

        //fetch home banner data
        Route::get('get-home-banner', 'CoreController@getHomeBanners');
        Route::get('get-arabic-speak-countries', 'CoreController@getArabicSpeakCountries');
    });

    Route::group(['prefix' => 'customer'], function () {
        Route::post('customer-login', 'CustomerController@login');
        Route::post('customer-signup', 'CustomerController@signUpCustomer');
        Route::post('verification-otp/send', 'CustomerController@otpVerificationSend');
        Route::post('verification-otp/verify', 'CustomerController@OtpVerify');
        Route::post('forgot-password-otp/send', 'CustomerController@forgotPassword');
        Route::post('forgot-password-otp/verify', 'CustomerController@forgotPasswordOtpVerify');
        Route::group(['middleware' => ['auth:appuser-api']], function () {
            Route::post('resend-activation-mail', 'CustomerController@customerResendMail');
            Route::get('get-customer-profile', 'CustomerController@customerProfile');
            Route::post('update-customer', 'CustomerController@updateCustomer');
            Route::post('update-profilePicture', 'CustomerController@updateProfilePicture');
            Route::get('get-traveller-list', 'TravellerController@getTraveller');
            Route::get('get-traveller', 'TravellerController@getTravellerById');
            Route::post('create-traveller', 'TravellerController@createTraveller');
            Route::post('update-traveller', 'TravellerController@updateTraveller');
            Route::delete('delete-traveller/{traveller_id}', 'TravellerController@destroy');
            Route::post('reset-password', 'CustomerController@resetForgotPassword');
            Route::post('change-password', 'CustomerController@changePassword');
            Route::post('close-account', 'CustomerController@closeAccount');
            Route::post('logout-customer','CustomerController@logout');
        });

        Route::post('email-verify', 'CustomerController@emailVerify');
    });

    Route::group(['prefix' => 'flight'], function () {
        Route::get('search/airport', 'FlightController@airportSearch');
        Route::get('search', 'FlightController@flightSearch');
        Route::post('flight-offers', 'FlightController@flightSearch');
        Route::post('flight-offers-search', 'FlightController@flightOffersSearch');
        Route::post('flight-offers-price', 'FlightController@flightOfferPrice');
        Route::post('flight-offers-price-upselling', 'FlightController@flightOfferPriceUpselling');
        Route::post('flight-offers-seatmap-amenities', 'FlightController@flightOfferPriceAmenities');
        Route::get('get-airlines', 'FlightController@getAirlines');
        Route::post('flight-order-create', 'FlightController@flightOrderCreate');
        
    });

    Route::group(['prefix' => 'customer'], function () {
        Route::get('/booking-detail', 'CustomerOrderController@getFlightOrder');
        Route::group(['middleware' => ['auth:appuser-api']], function () {
            
            Route::get('/bookings', 'CustomerOrderController@getCustomerOrders');
            
        });
        
    });

    Route::group(['prefix' => 'page'], function () {
        Route::get('get-pages', 'PageController@getpages');
        Route::get('page-code', 'PageController@getPageCodeDetails');
    });

    Route::group(['prefix' => 'geography'], function () {
        Route::get('get-countries', 'GeographyController@getCountries');
        Route::get('get-country', 'GeographyController@getCountryDetails');
        Route::get('get-states', 'GeographyController@getStateDetails');
        Route::get('get-city', 'GeographyController@getCityDetails');
    });
});
