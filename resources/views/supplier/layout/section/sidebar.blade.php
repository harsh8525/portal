<!-- !-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary sidebar-dark-v2 elevation-4">

  <!-- Brand Logo -->
  <!-- <a href="dashboard.html" class="brand-link" style="position: relative;">
  <img src="{{ URL::asset('assets/dist/img/logo.png') }}" alt="AdminLTE Logo">
</a> -->

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
  
  <div class="sidebar">

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
         with font-awesome or any other icon font library -->
        <!-- Start Div Dashboards -->
        <li class="nav-item menu-open">
          <a href="{{ route('admin.dashboard') }}" class="nav-link {{ (request()->segment(2) == 'dashboard') ? 'active' : '' }}">
            <i class="fas fa-home sid-icon light-icon"></i>
            <i class="fas fa-home sid-icon dark-icon"></i>
            <p>
              @lang('main.dashboard')
            </p>
          </a>
        </li>
        <!-- End Div Dashboards -->

        <!-- Start Div Booking -->
        <li class="nav-item menu-open">
          <a href="#" class="nav-link {{ (request()->segment(2) == 'booking') ? 'active' : '' }}">
            <i class="fa fa-hotel sid-icon light-icon"></i>
            <i class="fa fa-hotel sid-icon dark-icon"></i>
            <p>
              Booking
            </p>
          </a>
        </li>
        <!-- End Div Booking -->

        <!-- Start div Product List -->
        <li class="nav-item menu-open">
            <a href="#" class="nav-link {{ (request()->segment(2) == '') ? 'active' : '' }}">
            <i class="fa fa-store sid-icon light-icon"></i>
            <i class="fa fa-store sid-icon dark-icon"></i>
            <p>
              Product
            </p>
          </a>
        </li>
        <!-- End div Product List -->

        <!-- Start Div Availability -->
        <li class="nav-item menu-open">
          <a href="#" class="nav-link {{ (request()->segment(2) == '') ? 'active' : '' }}">
            <i class="fas fa-calendar-alt sid-icon light-icon"></i>
            <i class="fas fa-calendar-alt sid-icon dark-icon"></i>
            <p>
              Availability
            </p>
          </a>
        </li>
        <!-- End Div Availability -->

      <!-- Start div Property -->
       
        <li class="nav-item menu-open">
            <a href="#" class="nav-link {{ (request()->segment(2) == '') ? 'active' : '' }}">
            <i class="fa fa-city sid-icon light-icon"></i>
            <i class="fa fa-city sid-icon dark-icon"></i>
            <p>
              Property
            </p>
          </a>
        </li>
        <!-- End div Property -->

        <!-- Start Div Property Availability -->
        <li class="nav-item menu-open">
          <a href="#" class="nav-link {{ (request()->segment(2) == '') ? 'active' : '' }}">
            <i class="fa fa-building sid-icon light-icon"></i>
            <i class="fa fa-building sid-icon dark-icon"></i>
            <p>
              Property Availability
            </p>
          </a>
        </li>
        <!-- End Div Property Availability -->

        <!-- Start Div Reports -->
        <li class="nav-item menu-open">
          <a href="#" class="nav-link {{ (request()->segment(2) == '') ? 'active' : '' }}">
            <i class="fas fa-file-excel sid-icon light-icon"></i>
            <i class="fas fa-file-excel sid-icon dark-icon"></i>
            <p>
              Reports
            </p>
          </a>
        </li>
        <!-- End Div Reports -->

        <!-- Start Div Reviews -->
        <li class="nav-item menu-open">
          <a href="#" class="nav-link {{ (request()->segment(2) == '') ? 'active' : '' }}">
            <i class="fas fa-star sid-icon light-icon"></i>
            <i class="fas fa-star sid-icon dark-icon"></i>
            <p>
              Reviews
            </p>
          </a>
        </li>
        <!-- End Div Reviews -->

        <!-- Start Users -->
        <li class="nav-item menu-open">
            <a href="#" class="nav-link {{ (request()->segment(2) == '') ? 'active' : '' }}">
            <i class="fa fa-users sid-icon light-icon"></i>
            <i class="fa fa-users sid-icon dark-icon"></i>
            <p>
              Users
            </p>
          </a>
        </li>
        <!-- End Users -->

        <!-- Start Div Notification -->
        <li class="nav-item menu-open">
          <a href="#" class="nav-link {{ (request()->segment(2) == '') ? 'active' : '' }}">
            <!-- <i class="fa fa-dollar-sign sid-icon light-icon"></i>
            <i class="fa fa-dollar-sign sid-icon dark-icon"></i> -->
            <i class="fas fa-bell sid-icon light-icon"></i>
            <i class="fas fa-bell sid-icon dark-icon"></i>
            <p>
             Notification
            </p>
          </a>
        </li>
        <!-- End Div Notification -->

        <!-- Start div Finance -->
        <li class="nav-item menu-open">

          <a href="#" class="nav-link {{ (request()->segment(2) == '') ? 'active' : '' }}">
            <i class="fa fa-money-bill-alt sid-icon light-icon"></i>
            <i class="fa fa-money-bill-alt sid-icon dark-icon"></i>
            <p>
              Finance
            </p>
          </a>
        </li>
        <!-- End Div Finance -->

        <!-- Start div Account -->
        <li class="nav-item menu-open">
            <a href="#" class="nav-link {{ (request()->segment(2) == '') ? 'active' : '' }}">
            <i class="fas fa-chart-line sid-icon light-icon"></i>
            <i class="fas fa-chart-line sid-icon dark-icon"></i>
            <p>
              Account
            </p>
          </a>
        </li>
        <!-- End Div Account -->

        <!-- Start div Contact-us -->
        <li class="nav-item menu-open">

          <a href="#" class="nav-link {{ (request()->segment(2) == '') ? 'active' : '' }}">
            <i class="fas fa-address-card sid-icon light-icon"></i>
            <i class="fas fa-address-card sid-icon dark-icon"></i>
            <p>
              Contact Us
            </p>
          </a>
        </li>
        <!-- End Div Contact-us -->

      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>