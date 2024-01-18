<?php

use Illuminate\Support\Facades\Route;

/*
  |--------------------------------------------------------------------------
  | Manager Web Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */

Route::domain('admin.' . env('APP_URL'))->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.login');
    });


    Route::get('.well-known/apple-developer-merchantid-domain-association.txt', function () {
        // Replace this path with the actual path to your file
        $file = public_path('.well-known/apple-developer-merchantid-domain-association.txt');

        // Check if the file exists
        if (file_exists($file)) {
            // Read the file and return as response
            return Response::file($file);
        } else {
            // If file not found, return a 404 response
            abort(404);
        }
    });
    /*-----------------Task Vijay Prajapati-----------------------*/

    // All routes for admin
    Route::group(['namespace' => 'Admin', 'prefix' => ''], function () {

        /*     * ***************** global routes ********************* */

        Route::get('/login', function () {
            return view('admin/auth/login');
        })->name('admin.login');

        Route::get('/', function () {
            return redirect()->route('admin.login');
        });

        Route::post('login', 'Auth\LoginController@adminLogin')->name('adminLogin');
        Route::get('otp', 'Auth\LoginController@getOtp')->name('admin.otp');
        Route::get('forgot-password', 'Auth\LoginController@getForgotPassword')->name('admin.forgot-password');
        Route::post('forgot-password', 'Auth\LoginController@forgotPassword')->name('admin.forgot-password.save');
        Route::post('otp-verify', 'Auth\LoginController@otpVerify')->name('admin.otp-verification');
        Route::get('/reset-password/{mobile}', 'Auth\LoginController@getResetPassword')->name('admin.reset-password.create');
        Route::post('reset-password', 'Auth\LoginController@reset_password')->name('admin.reset-password.save');
        Route::post('reset-password/check', 'Auth\LoginController@resetPasswordvalidate')->name('admin.reset-password.validate');

        //customer reset password routes
        Route::get('/customer-reset-password/{token}', 'CustomerController@getCustomerResetPassword')->name('admin.customer-reset-password.create');
        Route::post('customer-reset-password', 'CustomerController@action_customer_reset_password')->name('admin.customer-reset-password.save');
        Route::get('/customer-updated-password', 'CustomerController@getCustomerUpdatedResetPassword')->name('admin.customer-updated-password');
        Route::get('/customer-active-account/{id}', 'CustomerController@customerActiveAccount')->name('admin.customer-active-account');

        Route::get('/api-send-otp/{token}', function ($token) {
            return view('Admin/Auth/api-send-otp', ['token' => $token]);
        })->name('reset-password');
        Route::post('api-send-otp', 'Auth\LoginController@api_reset_password')->name('admin.api-send-otp.save');


        /*     * ****************** authenticated routes ********************** */
        Route::group(['middleware' => ['auth']], function () {

            Route::resource('feature-flight', 'FeatureFlightController');
            Route::get('/deleteFeatureFlight', 'FeatureFlightController@deleteFeatureFlight')->name('feature-flight.delete');
            Route::get('/featured-flight/fetchAirlineCode', 'FeatureFlightController@fetchAirlineCode')->name('feature-flight.fetchAirlineCode');
            Route::get('/get-airport-name', 'FeatureFlightController@getAirportName');

            // social media link routes
            Route::resource('social-media-link', 'SocialMediaLinkController');
            Route::post('/social-media-link/checkNameIsExists', 'SocialMediaLinkController@checkNameIsExists')->name('social-media-link.checkNameIsExists');
            Route::get('/deleteSocialMediaLink', 'SocialMediaLinkController@deleteSocialMediaLink')->name('social-media-link.delete');

            // instagram feed routes
            Route::get('create-instagram-feed', 'InstagramFeedController@createInstagramFeed')->name('create-instagram-feed');
            Route::post('instagram-feed', 'InstagramFeedController@instagramFeed')->name('instagram-feed');

            // app download preference routes
            Route::get('create-app-download-preference', 'AppDownloadPreferenceController@createAppDownloadPreference')->name('create-app-download-preference');
            Route::post('app-download-preference', 'AppDownloadPreferenceController@AppDownloadPreference')->name('app-download-preference');

            Route::post('add-login-history', 'Auth\ProfileController@addLoginHistory')->name('admin.fcm_token_update');

            Route::get('/dashboard', 'DashboardController@index')->name('admin.dashboard');
            Route::get('/getRecord', 'DashboardController@getRecord')->name('getRecords');
            Route::get('/get-duration', 'DashboardController@getDuration')->name('admin.get_duration');
            //order-challan's routes

            Route::get('logout', 'Auth\LoginController@logout')->name('admin.logout');

            //profiile-update routes
            Route::get('profile/edit', 'Auth\ProfileController@edit')->name('admin.profile.edit');
            Route::put('profile/update/{id}', 'Auth\ProfileController@update')->name('admin.profile.update');


            //change password
            Route::get('change-password/', 'Auth\ProfileController@changePasswordUserEdit')->name('admin.change-password');
            Route::post('check-current-password/check', 'Auth\ProfileController@currentPasswordvalidate')->name('admin.current-password.validate');
            Route::post('change-password/check', 'Auth\ProfileController@changePasswordvalidate')->name('admin.change-password.validate');
            Route::post('change-password/{id}', 'Auth\ProfileController@changePasswordUserUpdate')->name('admin.change-password.update');

            //booking routes
            Route::resource('/booking', 'BookingController');
            Route::get('/designShow', 'BookingController@designShow');
            Route::get('/e-ticket/{id}', 'BookingController@eTicket')->name('booking.e-ticket');

            //user's routes // customers route(change name)
            Route::resource('/customers', 'CustomerController');
            Route::post('/customers/checkMobile', 'CustomerController@checkUserExist')->name('admin.customers.checkUser');
            Route::post('/customers/checkEmailUserExist', 'CustomerController@checkEmailUserExist')->name('admin.customers.checkEmailUserExist');
            Route::post('/customers/checkCustomerMobileExist', 'CustomerController@checkCustomerMobileExist')->name('admin.customers.checkCustomerMobileExist');
            Route::get('/deleteCustomers', 'CustomerController@deleteCustomers')->name('admin.customers.delete');
            Route::get('export/customers', 'CustomerController@export')->name('export.user');
            Route::post('cropped-image', 'CustomerController@cropImg')->name('cropped-image');

            //Travellers routes
            Route::resource('/travellers', 'TravellerController');
            Route::get('/deleteTravellers', 'TravellerController@deleteTravellers')->name('admin.travellers.delete');
            Route::get('/restoreTraveller', 'TravellerController@restoreTraveller')->name('admin.travellers.restore');


            //home banner's routes
            Route::resource('/home-banner', 'HomeBannerController');
            Route::get('/deleteBanner', 'HomeBannerController@deleteBanner')->name('admin.home-banner.delete');

            //cms pages routes
            Route::resource('/cms-pages', 'PagesController');
            Route::get('/deletepage', 'PagesController@deletePage')->name('admin.cms-pages.delete');
            Route::post('/admin-page/checkTitle', 'PagesController@checkPageTitleExist')->name('admin.cms-pages-title.checkExist');
            Route::post('/admin-page/checkSlugURL', 'PagesController@checkPageSlugURLExist')->name('admin.cms-pages-SlugURL.checkExist');
            Route::post('/uploadCmsFile', 'PagesController@uploadCmsFile');

            //reports routes
            Route::group(['namespace' => 'Reports'], function () {

                //agency report routs
                Route::get('/agencyReport/agency-report', 'AgencyReportController@index')->name('reports.agencyReport.agency-report');
                Route::get('/agencyReport/master-agency-report-pdf', 'AgencyReportController@generateMasterAgencyReportPdf')->name('admin.master-agency-report.pdf');
                Route::get('master-agency-report-export', 'AgencyReportController@generateMasterOrderReportExport')->name('admin.master-agency-report.export');

                //user report routes
                Route::get('/userReport/user-report', 'UserReportController@index')->name('reports.userReport.user-report');
                Route::get('/userReport/master-user-report-export', 'UserReportController@generateMasterUserReportExport')->name('admin.master-user-report.export');
                Route::get('master-user-report-export', 'UserReportController@generateMasterUserReportPdf')->name('admin.master-user-report.pdf');

                //customer report routes
                Route::get('/customerReport/customer-report', 'CustomerReportController@index')->name('reports.customerReport.customer-report');
                Route::get('/customerReport/master-customer-report-export', 'CustomerReportController@generateMasterCustomerReportExport')->name('admin.master-customer-report.export');
                Route::get('/customerReport/master-customer-report-pdf', 'CustomerReportController@generateMasterCustomerReportPdf')->name('admin.master-customer-report.pdf');

                //monthly customer report routes
                Route::get('/monthlyCustomerReport/monthly-customer-report', 'MonthlyCustomerReportController@index')->name('reports.monthlyCustomerReport.monthly-customer-report');
                Route::get('/monthlyCustomerReport/master-monthly-customer-report-export', 'MonthlyCustomerReportController@generateMasterMonthlyCustomerReportExport')->name('admin.master-monthly-customer-report.export');
                Route::get('/monthlyCustomerReport/master-monthly-customer-report-pdf', 'MonthlyCustomerReportController@generateMasterMonthlyCustomerReportPdf')->name('admin.master-monthly-customer-report.pdf');

                //log report routes
                Route::get('/logReport/log-report', 'LogReportController@index')->name('reports.logReport.log-report');
                Route::get('/logReport/log-report-export', 'LogReportController@generateLogReportExport')->name('admin.log-report.export');
                Route::get('/logReport/log-report-pdf', 'LogReportController@generateLogReportPdf')->name('admin.log-report.pdf');

                //log report routes
                Route::get('/backendLogReport/backend-log-report', 'LogReportController@backendlogReport')->name('reports.backendlogReport.log-report');
                Route::get('/backendLogReport/backend-log-report-export', 'LogReportController@generateBackendLogReportExport')->name('admin.backend-log-report.export');
                Route::get('/backendLogReport/backend-log-report-pdf', 'LogReportController@generateBackendLogReportPdf')->name('admin.backend-log-report.pdf');
            });
            //agencies routes
            Route::resource('/agency', 'AgencyController');
            Route::get('get-currency', 'AgencyController@getCurrency')->name('admin.get_currency');
            Route::post('/admin-agency/checkEmail', 'AgencyController@checkAgencyEmailExist')->name('admin.agency-email.checkExist');
            Route::post('/admin-agency/checkPhone', 'AgencyController@checkAgencyPhoneExist')->name('admin.agency-phone.checkExist');
            Route::post('/admin-agency/checkFax', 'AgencyController@checkAgencyFaxExist')->name('admin.agency-fax.checkExist');
            Route::post('/admin-agency/checkWebURL', 'AgencyController@checkAgencyWebURLExist')->name('admin.agency-webUrl.checkExist');
            Route::post('/admin-operator/checkEmail', 'AgencyController@checkUserEmailExist')->name('admin.user-email.checkExist');
            Route::post('/admin-operator/checkMobile', 'AgencyController@checkUserMobileExist')->name('admin.user-mobile.checkExist');
            Route::post('/admin-agency/checkIATANumber', 'AgencyController@checkIATANumberExist')->name('admin.agency.checkIATANumber');


            Route::get('/delete-agency', 'AgencyController@deleteAgency')->name('admin.agency.delete');

            //setting's routes
            Route::group(['namespace' => 'Setting'], function () {
                //admin user's routes
                Route::resource('/user', 'AdminUserController');
                Route::post('/user/checkEmailAgencyUserExist', 'AdminUserController@checkEmailAgencyUserExist')->name('admin.user.checkEmailAgencyUserExist');
                Route::post('/admin-user/checkMobile', 'AdminUserController@checkAdminUserExist')->name('admin.user.checkAdminUser');
                Route::get('/deleteUser', 'AdminUserController@deleteUser')->name('admin.user.delete');
                Route::get('/admin/user/sendActivationEmail/{id}', 'AdminUserController@sendActivationEmail')->name('admin.user.activationEmail');

                //roles and permission
                Route::resource('/role-permission', 'RolePermissionController');
                Route::get('/deleteRollPermission', 'RolePermissionController@deleteRollPermission')->name('admin.role-permission.delete');
                Route::post('/role-permission/checkExist', 'RolePermissionController@checkExist')->name('admin.role-permission.checkExist');
                Route::post('/role-permission/fetchModules', 'RolePermissionController@fetchModules')->name('admin.role-permission.fetchModules');

                //API Users route
                Route::get('/api-users', 'AdminUserController@apiUsers')->name('api-users');

                //general routes
                Route::resource('/general', 'GeneralController');
                Route::post('/general/basic', 'GeneralController@basic')->name('general.basic');
                Route::post('/general/maintenance', 'GeneralController@maintenance')->name('general.maintenance');
                Route::post('/general/additional-information', 'GeneralController@additionalInfo')->name('general.additionalInformation');
                Route::post('/general/bank-details', 'GeneralController@bankDetails')->name('general.bankDetails');
                Route::post('/general/mobile', 'GeneralController@mobile')->name('general.mobile');
                Route::post('/general/final-order-discont', 'GeneralController@orderDiscount')->name('general.orderDiscount');
                Route::post('/general/otp-verification', 'GeneralController@OtpVerification')->name('general.otp-phoneVerification');
                
                //smtp routes
                Route::resource('/smtp', 'SmtpController');
                
                //invoice routes
                Route::resource('/incoice', 'InvoiceController');
                Route::post('/incoice/general', 'InvoiceController@general')->name('invoice.general');
                Route::post('/incoice/sales', 'InvoiceController@sales')->name('invoice.sales');
                Route::post('/incoice/purchase', 'InvoiceController@purchase')->name('invoice.purchase');

                //sms routes
                Route::resource('/sms', 'SmsController');

                //Hotel Beds API routes
                Route::resource('/hotelbeds-api', 'HotelBedsApiController');

                //Amadeus API routes
                Route::resource('/amadeus-api', 'AmadeusApiController');

                //Language routes
                Route::resource('/language', 'LanguageController');
                Route::get('/deleteLanguage', 'LanguageController@deleteLanguage')->name('language.delete');
                Route::post('/language/checkCode', 'LanguageController@checkExistCode')->name('admin.language.checkExistCode');
                Route::post('/language/checkExistKey', 'LanguageController@checkExistKey')->name('admin.language.checkExistKey');
                Route::get('/language/translate/b2c/{id}', 'LanguageController@translate')->name('language.translate.b2c');
                Route::get('/languages/translate/create/{id}', 'LanguageController@createLangTranslator')->name('languages.translate.create');
                Route::post('/languages/translate/store', 'LanguageController@storeLangTranslator')->name('languages.translate.store');
                Route::put('/languages/translate/update', 'LanguageController@updateLangTranslator')->name('languages.updateLangTranslator');
                Route::get('/language/translate/b2b/{id}', 'LanguageController@translateB2B')->name('language.translate.b2b');
                Route::put('/languages/translate/update/b2b', 'LanguageController@updateLangTranslatorB2B')->name('languages.updateLangTranslatorB2B');
                Route::get('/language/translate-json/{id}', 'LanguageController@translateJson')->name('language.translate-json');
                Route::get('/getFileContents/{id}', 'LanguageController@getFileContents')->name('language.getFileContents');
                Route::post('/languages/translate/store/json', 'LanguageController@storeLangTranslatorJson')->name('languages.translate.store.json');

                //login attempts routes
                Route::resource('/login-attempt', 'LoginAttemptController');

                //password secirity routes
                Route::resource('/password-security', 'PasswordSecurityController');
                Route::post('/check-password-length', 'PasswordSecurityController@checkPasswordLength')->name('checkPasswordLength');

                //singin method routes
                Route::resource('/signin-method', 'SinginMethodController');


                //Currency method routes
                Route::resource('/currency', 'CurrencyController');
                Route::get('/agency-currency/checkCurrency', 'CurrencyController@checkAgencyCurrencyExist')->name('admin.agency-currency.checkExist');
                Route::get('/currencies/exchange-rate', 'CurrencyController@exchangeRate')->name('currency.exchange-rate');
                Route::get('/currency-data', 'CurrencyController@getAllowCurrency')->name('admin.get-allow_currency');
                Route::get('/check-currency-data', 'CurrencyController@checkAllowCurrency')->name('admin.check-allow_currency');
                Route::post('/get-default-currency', 'CurrencyController@getDefaultCurrency')->name('admin.get-default_currency');
                Route::post('/get_currencyexchangerate', 'CurrencyController@getCurrencyExchange')->name('admin.get-currency_exchange_rate');
                Route::post('/get_currencyapplymargin', 'CurrencyController@getCurrencyApplymargin')->name('admin.get-currency_apply_margin');
                Route::post('/get_currencysingleapplymargin', 'CurrencyController@getCurrencysingleApplymargin')->name('admin.get-currency_apply_single_margin');
            });
            //setting's routes
            Route::group(['namespace' => 'OperationalData'], function () {
                //admin operational_data Agency-Type Type routes
                Route::resource('agency-type', 'AgencyTypeController');
                Route::get('/deleteAgency', 'AgencyTypeController@deleteAgency')->name('agency-type.delete');
                Route::post('/agency-type/checkExist', 'AgencyTypeController@checkExist')->name('agency-type.checkExist');

                //admin operational_data Service Type routes
                Route::resource('service-type', 'ServiceTypeController');
                Route::get('/deleteService', 'ServiceTypeController@deleteService')->name('service-type.delete');
                Route::post('/service-type/checkExist', 'ServiceTypeController@checkExist')->name('service-type.checkExist');

                //admin operational_data Suppliers routes
                Route::resource('suppliers', 'SuppliersController');
                Route::get('/deletesupplier', 'SuppliersController@deletesupplier')->name('suppliers.delete');
                Route::post('suppliers/checkExit', 'SuppliersController@checkExist')->name('suppliers.checkExist');


                //admin operational_data Payment-Method routes
                Route::resource('paymentmethod', 'PaymentmethodController');
                Route::get('/deletepayment', 'PaymentmethodController@deletePaymentMethod')->name('paymentmethod.delete');
                Route::post('paymentmethod/checkExit', 'PaymentmethodController@checkExist')->name('paymentmethod.checkExist');

                //admin operational_data Banks routes
                Route::resource('banks', 'BankController');
                Route::get('/deletebank', 'BankController@deleteBank')->name('bank.delete');

                //admin operational_data payment-gateway routes
                Route::resource('payment-gateway', 'PaymentGatewayController');
                Route::get('/deletePayment', 'PaymentGatewayController@deletePayment')->name('payment-gateway.delete');
                Route::post('/payment-gateway/checkName', 'PaymentGatewayController@checkExistName')->name('admin.payment-gateway.checkExistName');

                //admin operational_data Banks routes
                Route::resource('/coupons', 'CouponController');
                Route::post('/checkCouponCodeExist', 'CouponController@checkCouponCodeExist')->name('checkCouponCodeExist');
                Route::get('/deleteCoupons', 'CouponController@deleteCoupons')->name('admin.coupons.delete');

                //test hyper pay
                Route::get('/checkout', 'PaymentGatewayController@checkout')->name('checkout');
                Route::any('checkout-payment', 'PaymentGatewayController@payment')->name('checkout-payment');
                Route::get('checkout-payment-status', 'PaymentGatewayController@checkPaymentStatus')->name('checkout-payment-status');
            });

             //Markups's routes
            Route::group(['namespace' => 'Markups'], function () {
            //markups(show service-type list) routes
            Route::resource('/flight-markups', 'FlightMarkupsController');
            Route::post('/updateMarkupData/{id}', 'FlightMarkupsController@updateMarkupData')->name('update-markups');
            Route::get('/deleteMarkups', 'FlightMarkupsController@deleteMarkups')->name('delete.markups');
            Route::get('/markups/manage', 'FlightMarkupsController@getListData')->name('markups.service_types');
            Route::post('/markups/storeDefaultMarkup', 'FlightMarkupsController@storeDefaultMarkup')->name('default-flight-markups.store');
            Route::get('/markups/default-flights-markups/{id}', 'FlightMarkupsController@showDefaultMarkup')->name('default-flight-markups-show.show');
            Route::get('/markups/default-flights-markups-edit/{id}', 'FlightMarkupsController@editDefaultMarkup')->name('default-flight-markups-edit.edit');
            Route::post('/markups/default-flights-markups-update/{id}', 'FlightMarkupsController@updateDefaultMarkup')->name('update-default-markups');
            Route::get('/deleteDefaultMarkups', 'FlightMarkupsController@deleteDefaultMarkups')->name('delete.default-markups');
            Route::get('/markups/created', 'FlightMarkupsController@addData')->name('markups.created');
            Route::get('/markups/addDefaultMarkup', 'FlightMarkupsController@addDefaultMarkupData')->name('markups.addDefaultMarkup');
            Route::get('/manage/fetch-origin', 'FlightMarkupsController@fetchOriginList')->name('markups.fetchOrigin');
            Route::get('/manage/fetch-airlines', 'FlightMarkupsController@fetchAirlines')->name('markups.fetchAirlines');
            Route::get('/manage/fetch-supplier', 'FlightMarkupsController@fetchSupplier')->name('markups.fetchSupplier');
            
            //hotel markups routes
            Route::resource('/hotel-markups', 'HotelMarkupsController');
        });
            Route::group(['namespace' => 'Templates'], function () {
                //admin user's routes
                Route::resource('mail-template', 'MailTemplateController');
                Route::post('/uploadMailFile', 'MailTemplateController@uploadMailFile');
                //admin operation_Data Service Type routes
                Route::resource('sms-template', 'SmsTemplateController');
            });

            Route::group(['namespace' => 'Geography'], function () {
                //admin user's routes
                Route::resource('airports', 'AirportsController');
                Route::get('/deleteAirport', 'AirportsController@deleteAirport')->name('admin.airports.delete');
                Route::get('/restoreAirport', 'AirportsController@restoreAirport')->name('admin.airports.restore');
                Route::post('/airports/checkAirportCodeExist', 'AirportsController@checkAirportCodeExist')->name('airports.checkAirportCodeExist');
                Route::post('/airports/checkAirportNameEnExist', 'AirportsController@checkAirportNameEnExist')->name('airports.checkAirportNameEnExist');
                Route::post('/airports/checkAirportNameArExist', 'AirportsController@checkAirportNameArExist')->name('airports.checkAirportNameArExist');
                Route::post('/airports/checkAirportLatitudeExist', 'AirportsController@checkAirportLatitudeExist')->name('airports.checkAirportLatitudeExist');
                Route::post('/airports/checkAirportLongitudeExist', 'AirportsController@checkAirportLongitudeExist')->name('airports.checkAirportLongitudeExist');
                Route::get('/airport/dataforselect2', 'AirportsController@fetchCities')->name('airports.dataforselect2');
                Route::get('/get-country-name', 'AirportsController@fetchCountryName');
                Route::get('/get-city-name/{country_code}', 'AirportsController@fetchCityName');
                Route::get('/get-state-name/{city_id}', 'AirportsController@fetchStateName');
                Route::get('/get-only-city-name', 'AirportsController@fetchOnlyCityName');

                //airport's import routes
                Route::post('importAirport', 'AirportsController@importAirport')->name('import-airport');

                //admin countries routes
                Route::resource('countries', 'CountryController');
                Route::get('/deleteCountry', 'CountryController@deleteCountry')->name('admin.countries.delete');
                Route::get('/restoreCountry', 'CountryController@restoreCountry')->name('admin.countries.restore');
                Route::post('/countries/checkCountryNameEnExist', 'CountryController@checkCountryNameEnExist')->name('countries.checkCountryNameEnExist');
                Route::post('/countries/checkCountryNameArExist', 'CountryController@checkCountryNameArExist')->name('countries.checkCountryNameArExist');
                Route::post('/countries/checkISOCodeExist', 'CountryController@checkISOCodeExist')->name('countries.checkISOCodeExist');
                Route::post('/countries/checkISDCodeExist', 'CountryController@checkISDCodeExist')->name('countries.checkISDCodeExist');

                //country's import routes
                Route::post('importCountry', 'CountryController@importCountryCsv')->name('import-country');

                //admin states routes
                Route::resource('states', 'StateController');
                Route::get('/deleteState', 'StateController@deleteState')->name('admin.states.delete');
                Route::get('/restoreState', 'StateController@restoreState')->name('admin.states.restore');
                Route::get('/get-states', 'StateController@fetchCountryCode');
                Route::get('/get-cities/{country_code}', 'StateController@getCities');
                Route::post('/states/checkStateNameEnExist', 'StateController@checkStateNameEnExist')->name('states.checkStateNameEnExist');
                Route::post('/states/checkStateNameArExist', 'StateController@checkStateNameArExist')->name('states.checkStateNameArExist');
                Route::post('/states/checkISOCodeExist', 'StateController@checkISOCodeExist')->name('states.checkISOCodeExist');
                Route::post('/states/checkStateLatitudeExist', 'StateController@checkStateLatitudeExist')->name('states.checkStateLatitudeExist');
                Route::post('/states/checkStateLongitudeExist', 'StateController@checkStateLongitudeExist')->name('states.checkStateLongitudeExist');

                //states's import routes
                Route::post('importState', 'StateController@importState')->name('import-state');

                //admin cities routes
                Route::resource('cities', 'CityController');
                Route::get('/deleteCity', 'CityController@deleteCity')->name('admin.cities.delete');
                Route::get('/restoreCity', 'CityController@restoreCity')->name('admin.cities.restore');
                Route::post('/cities/checkCityNameEnExist', 'CityController@checkCityNameEnExist')->name('cities.checkCityNameEnExist');
                Route::post('/cities/checkCityNameArExist', 'CityController@checkCityNameArExist')->name('cities.checkCityNameArExist');
                Route::post('/cities/checkISOCodeExist', 'CityController@checkISOCodeExist')->name('cities.checkISOCodeExist');
                Route::post('/cities/checkCityLatitudeExist', 'CityController@checkCityLatitudeExist')->name('cities.checkCityLatitudeExist');
                Route::post('/cities/checkCityLongitudeExist', 'CityController@checkCityLongitudeExist')->name('cities.checkCityLongitudeExist');
                Route::get('/fetchCountryCode', 'CityController@fetchCountryCode');

                //city's import routes
                Route::post('importCity', 'CityController@importCity')->name('import-city');

                //admin airlines routes
                Route::resource('airlines', 'AirlineController');
                Route::get('/deleteAirline', 'AirlineController@deleteAirline')->name('admin.airlines.delete');
                Route::get('/restoreAirline', 'AirlineController@restoreAirline')->name('admin.airlines.restore');
                Route::post('/airlines/checkAirlineNameEnExist', 'AirlineController@checkAirlineNameEnExist')->name('airlines.checkAirlineNameEnExist');
                Route::post('/airlines/checkAirlineNameArExist', 'AirlineController@checkAirlineNameArExist')->name('airlines.checkAirlineNameArExist');
                Route::post('/airlines/checkAirlineCodeExist', 'AirlineController@checkAirlineCodeExist')->name('airlines.checkAirlineCodeExist');

                //airline's import routes
                Route::post('importAirline', 'AirlineController@importAirline')->name('import-airline');
            });
        });
    });

    Route::get('/amadeus/refresh-token', 'Admin\Setting\AmadeusApiController@getRefreshToken')->name('amadeus.refresh-token');
    Route::get('/send-password-expiry', 'Admin\Auth\ProfileController@sendExpiryNotificaton')->name('send-password-expiry');

    Route::fallback(function () {
        return view('admin.404');
    });
});
