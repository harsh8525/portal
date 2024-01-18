<!-- !-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary sidebar-dark-v2 elevation-4">

  @php
  $logo = "";$value = "";$fav = "";
  @$logo = App\Models\Setting::where('config_key', 'general|basic|whiteLogo')->get('value')[0]['value'];
  @$value = App\Models\Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'];
  @$fav = App\Models\Setting::where('config_key', 'general|basic|favicon')->get('value')[0]['value'];
  @endphp

  <div>
    <a href="{{ route('admin.dashboard') }}" class="brand-link logo-switch" style="position: relative; display: flex; justify-content: center; text-align: center;">
      @if(@$fav)
      <img src="{{$fav}}" alt="" class="brand-image-xl logo-xs">
      @else
      <img src="{{ URL::asset('assets/images/favicon.png') }}" alt="" class="brand-image-xl logo-xs">
      @endif
      @if($logo)
      <img class="brand-image-xs logo-xl" src="{{ $logo }}" style="position: relative; left: 0; top: 0;">
      @elseif($value)
      <h1 style="font-size: 24px; text-align: center; width: 100%; left: 0; margin: 0; top: 1rem; color: white !important;" class="brand-image-xs logo-xl text-primary">{{ $value }}</h1>
      @else
      <img class="brand-image-xs logo-xl" src="{{ URL::asset('assets/images/logo.png') }}">
      @endif

    </a>
  </div>
  <!-- Sidebar -->
  @php
  $request = request()->segment(1);
  $routeName = array("");
  @endphp
  <div class="sidebar">

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- SidebarSearch Form -->
        <div class="form-inline user-panel1 mt-3 pb-3 mb-3 d-flex" style="border-bottom: 1px solid #4f5962;">
          <div class="input-group" data-widget="sidebar-search">
            <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
              <button class="btn btn-sidebar">
                <i class="fas fa-search fa-fw"></i>
              </button>
            </div>
          </div>
        </div>
        <!-- Add icons to the links using the .nav-icon class
         with font-awesome or any other icon font library -->
        <!-- Start Div Dashboards -->
        <li class="nav-item menu-open">
          <a href="{{ route('admin.dashboard') }}" class="nav-link {{ (request()->segment(1) == 'dashboard') ? 'active' : '' }}">
            <i class="fas fa-home sid-icon light-icon"></i>
            <i class="fas fa-home sid-icon dark-icon"></i>
            <p>
              @lang('main.dashboard')
            </p>
          </a>
        </li>
        <!-- End Div Dashboards -->

        <!-- Start div booking -->

        @if(hasPermission('BOOKING','read'))
        <li class="nav-item menu-open">
          <a href="{{ route('booking.index') }}" class="nav-link {{ (request()->segment(1) == 'booking') ? 'active' : '' }}">
            <i class="fa fa-hotel sid-icon light-icon"></i>
            <i class="fa fa-hotel sid-icon dark-icon"></i>
            <p>
              Booking
            </p>
          </a>
        </li>
        @endif
        <!-- End div booking -->


        <!-- Start div Customer -->
        @php
        $request = request()->segment(1);
        $routeNameCustomer = array("customers","customerReview");
        @endphp
        <li class="nav-item @if(in_array($request,$routeNameCustomer)) menu-is-opening menu-open  @endif">
          @if((hasPermission('CUSTOMERS','read')) || (hasPermission('CUSTOMERS_REVIEW','read')))
          <a href="#" class="nav-link @if(in_array($request,$routeNameCustomer)) active  @endif">
            <i class="fa fa-users sid-icon light-icon"></i>
            <i class="fa fa-users sid-icon dark-icon"></i>
            <p>
              Customers
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          @endif
          <ul class="nav nav-treeview">
            @if(hasPermission('CUSTOMERS_LIST','read'))
            <li class="nav-item">
              <a href="{{ route('customers.index') }}" class="nav-link {{ (request()->segment(1) == 'customers') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Customers</p>
              </a>
            </li>
            @endif


          </ul>
        </li>
        <!-- End div Customer -->

        <!-- Start Reports -->

        <!-- ------------------------------------------------ -->
        @php
        $request = request()->segment(1);
        $reportsData = array("agencyReport","userReport","customerReport","monthlyCustomerReport","logReport","backendLogReport");
        @endphp
        <li class="nav-item @if(in_array($request,$reportsData)) menu-is-opening menu-open  @endif">
          @if(hasPermission('REPORTS','read'))
          <a href="#" class="nav-link @if(in_array($request,$reportsData)) active  @endif">
            <i class="fa fa-scroll sid-icon light-icon"></i>
            <i class="fa fa-scroll sid-icon dark-icon"></i>
            <p>
              Reports
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          @endif
          <ul class="nav nav-treeview">
            @if(hasPermission('REPORTS','read'))
            <li class="nav-item">
              <a href="{{ route('reports.agencyReport.agency-report') }}" class="nav-link {{ (request()->segment(1) == 'agencyReport') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Agency List Report</p>
              </a>
            </li>
            @endif
            @if(hasPermission('REPORTS','read'))
            <li class="nav-item">
              <a href="{{ route('reports.userReport.user-report') }}" class="nav-link {{ (request()->segment(1) == 'userReport') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>User List Report</p>
              </a>
            </li>
            @endif
            @if(hasPermission('REPORTS','read'))
            <li class="nav-item">
              <a href="{{ route('reports.customerReport.customer-report') }}" class="nav-link {{ (request()->segment(1) == 'customerReport') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>New customer signup Report</p>
              </a>
            </li>
            @endif
            @if(hasPermission('REPORTS','read'))
            <li class="nav-item">
              <a href="{{ route('reports.monthlyCustomerReport.monthly-customer-report') }}" class="nav-link {{ (request()->segment(1) == 'monthlyCustomerReport') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p> Monthly list of customer signup Report</p>
              </a>
            </li>
            @endif
            @if(hasPermission('REPORTS','read'))
            <li class="nav-item">
              <a href="{{ route('reports.logReport.log-report') }}" class="nav-link {{ (request()->segment(1) == 'logReport') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p> Frontend Log Report</p>
              </a>
            </li>
            @endif
            @if(hasPermission('REPORTS','read'))
            <li class="nav-item">
              <a href="{{ route('reports.backendlogReport.log-report') }}" class="nav-link {{ (request()->segment(1) == 'backendLogReport') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p> Beckend Log Report</p>
              </a>
            </li>
            @endif
          </ul>
        </li>
        <!-- End Reports -->

        <!-- Start Agencies -->
        @if(hasPermission('AGENCY','read'))
        <li class="nav-item">
          <a href="{{ route('agency.index') }}" class="nav-link {{ (request()->segment(1) == 'agency') ? 'active' : '' }}">
            <i class="fa fa-building sid-icon light-icon"></i>
            <i class="fa fa-building sid-icon dark-icon"></i>
            <p>
              Agencies
            </p>
          </a>
        </li>
        @endif
        <!-- End Agencies -->

        <!-- Start Users -->
        @php
        $request = request()->segment(1);
        $routeNameUser = array("user","role-permission", "api-users");
        @endphp
        <li class="nav-item @if(in_array($request,$routeNameUser)) menu-is-opening menu-open  @endif">
          @if((hasPermission('USERS_LIST','read')) || (hasPermission('ROLES_PERMISSION','read')) || (hasPermission('API_USERS','read')))
          <a href="#" class="nav-link @if(in_array($request,$routeNameUser)) active  @endif">
            <i class="fa fa-users sid-icon light-icon"></i>
            <i class="fa fa-users sid-icon dark-icon"></i>
            <p>
              Users
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          @endif

          <ul class="nav nav-treeview">
            @if(hasPermission('USERS_LIST','read'))
            <li class="nav-item">
              <a href="{{ route('user.index') }}" class="nav-link {{ (request()->segment(1) == 'user') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Users</p>
              </a>
            </li>
            @endif

            @if(hasPermission('ROLES_PERMISSION','read'))
            <li class="nav-item">
              <a href="{{ route('role-permission.index') }}" class="nav-link {{ (request()->segment(1) == 'role-permission') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Roles & Permission</p>
              </a>
            </li>
            @endif

            @if(hasPermission('API_USERS','read'))
            <li class="nav-item">
              <a href="{{ route('api-users') }}" class="nav-link {{ (request()->segment(1) == 'api-users') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>API Users</p>
              </a>
            </li>
            @endif
          </ul>
        </li>
        <!-- End Users -->


        <!-- Start Prefrences -->
        @php
        $request = request()->segment(1);
        $routeNamePreferences = array("general","login-attempt","password-security","smtp","currency","currencies","exchange-rate","signin-method","mailchimp","notification-entity","sms","amadeus-api","hotelbeds-api","language","incoice");
        @endphp
        <li class="nav-item @if(in_array($request,$routeNamePreferences)) menu-is-opening menu-open  @endif">
          @if((hasPermission('PREFERENCES','read')) || (hasPermission('GENERAL','read')) || (hasPermission('LOGIN_ATTEMPTS','read')) ||
          (hasPermission('PASSWORD_SECURITY','read')) || (hasPermission('SMTP_SETTINGS','read')) || (hasPermission('SIGN_IN_METHOD','read')) ||
          (hasPermission('notification-entity','read')) || (hasPermission('CURRENCIES','read')) || (hasPermission('SMS_SETTINGS','read')) || (hasPermission('Invoice','read')))
          <a href="#" class="nav-link @if(in_array($request,$routeNamePreferences)) active  @endif">
            <i class="fa fa-cogs sid-icon light-icon"></i>
            <i class="fa fa-cogs sid-icon dark-icon"></i>
            <p>
              Preferences
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          @endif
          <ul class="nav nav-treeview">
            @if(hasPermission('GENERAL','read'))
            <li class="nav-item">
              <a href="{{ route('general.index') }}" class="nav-link {{ (request()->segment(1) == 'general') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>General</p>
              </a>
            </li>
            @endif
            @if(hasPermission('INVOICE','read'))
            <li class="nav-item">
              <a href="{{ route('incoice.index') }}" class="nav-link {{ (request()->segment(1) == 'incoice') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Invoice</p>
              </a>
            </li>
            @endif

            @if(hasPermission('LOGIN_ATTEMPTS','read'))
            <li class="nav-item">
              <a href="{{ route('login-attempt.index') }}" class="nav-link {{ (request()->segment(1) == 'login-attempt') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Login Attempts</p>
              </a>
            </li>
            @endif
            @if(hasPermission('PASSWORD_SECURITY','read'))
            <li class="nav-item">
              <a href="{{ route('password-security.index') }}" class="nav-link {{ (request()->segment(1) == 'password-security') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Password Security</p>
              </a>
            </li>
            @endif

            @if(hasPermission('SMTP_SETTINGS','read'))
            <li class="nav-item">
              <a href="{{ route('smtp.index') }}" class="nav-link {{ (request()->segment(1) == 'smtp') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>SMTP Settings</p>
              </a>
            </li>
            @endif
            @if(hasPermission('SIGN_IN_METHOD','read'))
            <li class="nav-item">
              <a href="{{ route('signin-method.index') }}" class="nav-link {{ (request()->segment(1) == 'signin-method') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Sign In Method</p>
              </a>
            </li>
            @endif



            @if(hasPermission('CURRENCIES','read'))
            <li class="nav-item">
              <a href="{{ route('currency.index') }}" class="nav-link {{ ((request()->segment(1) == 'currency') || (request()->segment(2) == 'exchange-rate')) ? 'active' : '' }} ">
                <i class="far fa-circle nav-icon"></i>
                <p>Currencies</p>
              </a>
            </li>
            @endif

            @if(hasPermission('SMS_SETTINGS','read'))
            <li class="nav-item">
              <a href="{{ route('sms.index') }}" class="nav-link {{ (request()->segment(1) == 'sms') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>SMS Settings</p>
              </a>
            </li>
            @endif
            @if(hasPermission('LANGUAGE','read'))
            <li class="nav-item">
              <a href="{{ route('language.index') }}" class="nav-link {{ (request()->segment(1) == 'language') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Language</p>
              </a>
            </li>
            @endif
          </ul>
        </li>
        <!-- End Prefrences -->



        <!-- Start Operational Data -->
        @php
        $request = request()->segment(1);
        $routeNameOperational = array("agency-type","service-type","suppliers","paymentmethod","Payment Methods","banks","payment-gateway","checkout-payment","coupons");
        @endphp
        <li class="nav-item @if(in_array($request,$routeNameOperational)) menu-is-opening menu-open  @endif">
          @if((hasPermission('AGENCY_TYPE','read')) || (hasPermission('SERVICE_TYPE','read')) || (hasPermission('SUPPLIERS','read'))||
          (hasPermission('PAYMENT_METHOD','read')) || (hasPermission('PAYMENT_GATEWAY','read')) || (hasPermission('BANKS','read')))
          <a href="#" class="nav-link @if(in_array($request,$routeNameOperational)) active  @endif">
            <i class="fa fa-table sid-icon light-icon"></i>
            <i class="fa fa-table sid-icon dark-icon"></i>
            <p>
              Operational Data
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          @endif
          <ul class="nav nav-treeview">
            @if(hasPermission('AGENCY_TYPE','read'))
            <li class="nav-item">
              <a href="{{ route('agency-type.index') }}" class="nav-link {{ (request()->segment(1) == 'agency-type') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Agency Type</p>
              </a>
            </li>
            @endif
            @if(hasPermission('SERVICE_TYPE','read'))
            <li class="nav-item">
              <a href="{{ route('service-type.index') }}" class="nav-link {{ (request()->segment(1) == 'service-type') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Service Type</p>
              </a>
            </li>
            @endif
            @if(hasPermission('SUPPLIERS','read'))
            <li class="nav-item">
              <a href="{{ route('suppliers.index') }}" class="nav-link {{ (request()->segment(1) == 'suppliers') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Suppliers</p>
              </a>
            </li>
            @endif
            @if(hasPermission('PAYMENT_METHOD','read'))
            <li class="nav-item">
              <a href="{{route('paymentmethod.index')}}" class="nav-link {{ (request()->segment(1) == 'paymentmethod') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Payment Methods</p>
              </a>
            </li>
            @endif
            @if(hasPermission('PAYMENT_GATEWAY','read'))
            <li class="nav-item">
              <a href="{{ route('payment-gateway.index') }}" class="nav-link {{ (request()->segment(1) == 'payment-gateway') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Payment Gateway</p>
              </a>
            </li>
            @endif
            @if(hasPermission('BANKS','read'))
            <li class="nav-item">
              <a href="{{route('banks.index')}}" class="nav-link {{ (request()->segment(1) == 'banks') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Banks</p>
              </a>
            </li>
            @endif
            @if(hasPermission('COUPONS','read'))
            <li class="nav-item">
              <a href="{{route('coupons.index')}}" class="nav-link {{ (request()->segment(1) == 'coupons') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Coupons</p>
              </a>
            </li>
            @endif
            <li class="nav-item">
              <a href="{{ route('checkout-payment') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Hyperpay Payment Gateway</p>
              </a>
            </li>
          </ul>
        </li>
        <!-- End Operational Data -->

        <!-- Start Markups -->
        @php
        $request = request()->segment(1);
        $routeNameMarkups = array("markups","markup","mark-ups","flight-markups","hotel-markups");
        @endphp
        <li class="nav-item @if(in_array($request,$routeNameMarkups)) menu-is-opening menu-open  @endif">
          @if((hasPermission('MARKUPS','read')))
          <a href="#" class="nav-link @if(in_array($request,$routeNameMarkups)) active  @endif">
            <i class="fab fa-servicestack sid-icon light-icon"></i>
            <i class="fab fa-servicestack sid-icon dark-icon"></i>
            <p>
              Markup Rules
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          @endif
          <?php
          if (isset($_GET['service_type'])) {
            $serviceTypes = str_replace(' ', '_', $_GET['service_type']);
          } else {
            $serviceTypes = "";
          }
          ?>
          <ul class="nav nav-treeview">
            @if((hasPermission('MARKUPS','read')))
            <?php
           
            ?>
            <li class="nav-item">
              <a href="{{ route('flight-markups.index') }}" class="nav-link {{ (in_array(request()->segment(1) , ['markups','flight-markups'])) ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Flight Markup</p>
              </a>
            </li>
            @endif
            @if((hasPermission('MARKUPS','read')))
            <li class="nav-item">
              <a href="{{ route('hotel-markups.index')}}" class="nav-link {{ (request()->segment(1) == 'hotel-markups') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Hotel Markup</p>
              </a>
            </li>
            @endif
          </ul>
        </li>
        <!-- End Markups -->

        <!-- Start Templates -->
        @php
        $request = request()->segment(1);
        $routeNameTemplates = array("mail-template","sms-template");
        @endphp
        <li class="nav-item @if(in_array($request,$routeNameTemplates)) menu-is-opening menu-open  @endif">
          @if((hasPermission('MAIL_TEMPLATES','read')) || (hasPermission('SMS_TEMPLATES','read')))
          <a href="#" class="nav-link @if(in_array($request,$routeNameTemplates)) active  @endif">
            <i class="fa fa-file-code sid-icon light-icon"></i>
            <i class="fa fa-file-code sid-icon dark-icon"></i>
            <p>
              Templates
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          @endif
          <ul class="nav nav-treeview">
            @if(hasPermission('MAIL_TEMPLATES','read'))
            <li class="nav-item">
              <a href="{{ route('mail-template.index') }}" class="nav-link {{ (request()->segment(1) == 'mail-template') ? 'active' : '' }}">

                <i class="far fa-circle nav-icon"></i>
                <p>Mail Templates</p>
              </a>
            </li>
            @endif
            @if(hasPermission('SMS_TEMPLATES','read'))
            <li class="nav-item">
              <a href="{{ route('sms-template.index') }}" class="nav-link {{ (request()->segment(1) == 'sms-template') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>SMS Templates</p>
              </a>
            </li>
            @endif
          </ul>
        </li>

        <!-- End Templates -->

        <!-- Start geography -->
        @php
        $request = request()->segment(1);
        $routeNameGeography = array("airports","regions","countries","cities","states","airlines");
        @endphp
        <li class="nav-item @if(in_array($request,$routeNameGeography)) menu-is-opening menu-open  @endif">
          @if((hasPermission('AIRPORTS','read')) || (hasPermission('COUNTRIES','read')) || (hasPermission('STATES','read')))
          <a href="#" class="nav-link @if(in_array($request,$routeNameGeography)) active  @endif">
            <i class="fas fa-globe sid-icon light-icon"></i>
            <i class="fa fa-file-code sid-icon dark-icon"></i>

            <p>
              Geography
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          @endif
          <ul class="nav nav-treeview">
            @if(hasPermission('AIRLINE','read'))
            <li class="nav-item">
              <a href="{{ route('airlines.index') }}" class="nav-link {{ (request()->segment(1) == 'airlines') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Airlines</p>
              </a>
            </li>
            @endif
            @if(hasPermission('AIRPORTS','read'))
            <li class="nav-item">
              <a href="{{ route('airports.index') }}" class="nav-link {{ (request()->segment(1) == 'airports') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Airports</p>
              </a>
            </li>
            @endif

            @if(hasPermission('COUNTRIES','read'))
            <li class="nav-item">
              <a href="{{ route('countries.index') }}" class="nav-link {{ (request()->segment(1) == 'countries') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Countries</p>
              </a>
            </li>
            @endif
            @if(hasPermission('CITIES','read'))
            <li class="nav-item">
              <a href="{{ route('cities.index') }}" class="nav-link {{ (request()->segment(1) == 'cities') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Cities</p>
              </a>
            </li>
            @endif
            @if(hasPermission('STATES','read'))
            <li class="nav-item">
              <a href="{{ route('states.index') }}" class="nav-link {{ (request()->segment(1) == 'states') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>States</p>
              </a>
            </li>
            @endif
          </ul>
        </li>

        <!-- End geography -->



        <!-- Start B2C -->
        @php
        $request = request()->segment(1);
        $routeNameB2C = array("cms-pages","home-banner",'feature-flight','social-media-link','create-instagram-feed','create-app-download-preference');
        @endphp
        <li class="nav-item @if(in_array($request,$routeNameB2C)) menu-is-opening menu-open  @endif">
          @if((hasPermission('CMS_PAGES','read')) || (hasPermission('HOME_BANNERS','read')) || (hasPermission('feature-flight','read')) || (hasPermission('Social_Media_Link','read')) || (hasPermission('AppDownloadPreference','read')) || (hasPermission('INSTAGRAM_FEED','read')))
          <a href="#" class="nav-link @if(in_array($request,$routeNameB2C)) active  @endif">
            <i class="fa fa-toolbox sid-icon light-icon"></i>
            <i class="fa fa-toolbox sid-icon dark-icon"></i>
            <p>
              B2C
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          @endif
          <ul class="nav nav-treeview">

            @if(hasPermission('CMS_PAGES','read'))
            <li class="nav-item">
              <a href="{{ route('cms-pages.index') }}" class="nav-link {{ (request()->segment(1) == 'cms-pages') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Pages</p>
              </a>
            </li>
            @endif

            @if(hasPermission('HOME_BANNERS','read'))
            <li class="nav-item">
              <a href="{{ route('home-banner.index') }}" class="nav-link {{ (request()->segment(1) == 'home-banner') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Home Banners</p>
              </a>
            </li>
            @endif

            @if(hasPermission('FEATURED_FLIGHT','read'))
            <li class="nav-item">
              <a href="{{ route('feature-flight.index') }}" class="nav-link {{ (request()->segment(1) == 'feature-flight') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Featured Flights</p>
              </a>
            </li>
            @endif

            @if(hasPermission('Social_Media_Link','read'))
            <li class="nav-item">
              <a href="{{ route('social-media-link.index') }}" class="nav-link {{ (request()->segment(1) == 'social-media-link') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Social Media Links</p>
              </a>
            </li>
            @endif

            @if(hasPermission('AppDownloadPreference','read'))
            <li class="nav-item">
              <a href="{{ route('create-app-download-preference') }}" class="nav-link {{ (request()->segment(1) == 'create-app-download-preference') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>App Download Preference</p>
              </a>
            </li>
            @endif

            @if(hasPermission('INSTAGRAM_FEED','read'))
            <li class="nav-item">
              <a href="{{ route('create-instagram-feed') }}" class="nav-link {{ (request()->segment(1) == 'create-instagram-feed') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Instagram Feed</p>
              </a>
            </li>
            @endif
          </ul>
        </li>
        <!-- End B2C -->
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>