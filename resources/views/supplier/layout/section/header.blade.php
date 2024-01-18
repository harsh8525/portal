<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>@yield('title')</title>
  @php 
      $logo = "";
      @$logo = App\Models\Setting::where('config_key', 'general|basic|favicon')->get('value')[0]['value'];
  @endphp
  @if($logo)
    <link rel="shortcut icon" href="{{ $logo }}" />  
  @else
    <link rel="shortcut icon" href="{{ URL::asset('assets/images/favicon.png') }}" />
  @endif
  

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="{{ URL::asset('assets/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="{{ URL::asset('assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ URL::asset('assets/dist/css/adminlte.min.css') }}">
  <link rel="stylesheet" href="{{ URL::asset('assets/dist/css/custome.css') }}">
  
  <!-- bt css -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

  <!--Sweet alert -->
  <link rel="stylesheet"
    href="{{ URL::asset('assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">

<!-- Select2 Bootstrap 4 -->
<link rel="stylesheet" href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}">
  <link rel="stylesheet" href="{{ URL::asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">

  <style>
      .dark-mode .alert-success{
          text-transform: capitalize;
      }
      .dark-mode .alert-danger{
          text-transform: capitalize;
      }
      .table .softdelete{
          background-color: rgba(249,62,62,.1) !important;
      }
      #pol{
          display:none;
      }

      .see_noti{
        background-color: #fff !important;
        color: #000 !important
      }

      .see_noti:hover{
        color: #ee3137 !important;
      }
      
  </style>
</head>

<body class="hold-transition dark-mode sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
  <div class="wrapper">

    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center">
      <img class="animation__wobble" src="{{ URL::asset('assets/dist/img/AdminLTELogo.png') }}" alt="AdminLTELogo" height="60" width="60">
    </div>

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-dark">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link menu-icon" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
      </ul>

      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">
        <!-- Navbar Search -->


        <li class="nav-item dropdown brows_btn">
          <a class="nav-link browse-web" data-toggle="dropdown" href="#">
            Browse Website
          </a>
        </li>
        <!-- Notifications Dropdown Menu -->
        <!-- <li class="nav-item">
          <a class="nav-link" href="{{route('admin.notificationEntity.getNotificationAll')}}">
            <svg version="1.1" height="20" class="nav-icon" width="20" fill="#ee3137" id="Layer_1" x="0px" y="0px"
              viewBox="0 0 116.11 122.88" style="enable-background:new 0 0 116.11 122.88" xml:space="preserve">
              <g>
                <path
                  d="M74.82,109.04c-0.37,1.94-1.02,3.72-1.96,5.35c-0.97,1.67-2.24,3.18-3.8,4.5c-1.57,1.32-3.27,2.32-5.12,2.99 c-1.85,0.67-3.81,1-5.88,1c-2.08,0-4.04-0.34-5.88-1c-1.84-0.67-3.55-1.66-5.11-2.99c-1.57-1.33-2.83-2.83-3.8-4.5 c-0.97-1.67-1.63-3.51-1.99-5.53c-0.18-0.98,0.48-1.92,1.46-2.1c0.03,0,0.32-0.03,0.32-0.03h30.02c1,0,1.82,0.81,1.82,1.82 C74.89,108.72,74.86,108.88,74.82,109.04L74.82,109.04L74.82,109.04z M20.21,0.25c1.83-0.73,3.9,0.17,4.63,2 c0.73,1.83-0.17,3.9-2,4.63c-3.96,1.58-7.28,3.77-9.93,6.61c-2.64,2.84-4.63,6.36-5.93,10.59c-0.58,1.88-2.58,2.94-4.46,2.36 c-1.88-0.58-2.94-2.58-2.36-4.46c1.63-5.3,4.15-9.74,7.52-13.36C11.05,5.01,15.24,2.23,20.21,0.25L20.21,0.25z M93.27,6.88 c-1.83-0.73-2.73-2.8-2-4.63c0.73-1.83,2.8-2.73,4.63-2c4.97,1.98,9.16,4.76,12.53,8.38c3.37,3.63,5.9,8.07,7.52,13.36 c0.58,1.88-0.48,3.88-2.36,4.46c-1.88,0.58-3.88-0.48-4.46-2.36c-1.3-4.24-3.29-7.76-5.93-10.59 C100.55,10.65,97.23,8.46,93.27,6.88L93.27,6.88z M67.62,10.54c1.47,0.38,2.9,0.85,4.29,1.4c2.04,0.81,4,1.78,5.88,2.91 c0.07,0.05,0.15,0.09,0.22,0.14c1.8,1.11,3.48,2.33,5.02,3.65c1.62,1.39,3.12,2.92,4.52,4.6l0.01,0.01h0 c1.37,1.65,2.59,3.42,3.67,5.29c1.08,1.88,2.01,3.84,2.78,5.86l0,0c0.79,2.09,1.38,4.22,1.78,6.41c0.39,2.2,0.59,4.45,0.59,6.76 c0,4.56,0,7.03,0,7.33c0.01,2.34,0.02,4.63,0.04,6.86v0.02l0,0c0.01,2.02,0.14,4.05,0.39,6.08c0.25,2.01,0.61,3.95,1.08,5.82l0,0 c0.47,1.84,1.11,3.62,1.9,5.32c0.82,1.75,1.82,3.47,2.99,5.14l0.01,0c1.16,1.64,2.61,3.27,4.35,4.87c1.8,1.65,3.88,3.28,6.26,4.86 c1.36,0.91,1.73,2.76,0.81,4.12c-0.57,0.85-1.51,1.32-2.47,1.32v0.01l-26.85,0H58.06H31.21H4.37c-1.65,0-2.98-1.33-2.98-2.98 c0-1.08,0.58-2.03,1.44-2.55c2.41-1.63,4.48-3.25,6.21-4.85c1.72-1.59,3.16-3.22,4.32-4.9c0.03-0.05,0.07-0.1,0.11-0.14 c1.12-1.64,2.08-3.31,2.87-5.01c0.81-1.73,1.46-3.51,1.94-5.34c0.01-0.04,0.02-0.08,0.03-0.11c0.46-1.78,0.81-3.66,1.05-5.63 c0.24-1.98,0.37-4.03,0.37-6.14v-14.1c0-2.27,0.2-4.52,0.61-6.77c0.41-2.24,1-4.39,1.79-6.44c0.78-2.05,1.72-4.02,2.81-5.9 c1.08-1.87,2.32-3.64,3.71-5.32l0.02-0.02l0,0c1.38-1.65,2.9-3.19,4.55-4.6c1.63-1.39,3.39-2.66,5.28-3.79 c1.91-1.14,3.89-2.1,5.93-2.88c1.42-0.54,2.89-0.99,4.39-1.36c0.51-1.79,1.39-3.24,2.64-4.35c1.72-1.53,3.98-2.29,6.79-2.26 c2.78,0.02,5.03,0.79,6.73,2.32C66.22,7.32,67.11,8.76,67.62,10.54L67.62,10.54L67.62,10.54z M69.75,17.47 c-1.65-0.65-3.33-1.16-5.04-1.53c-1.32-0.17-2.4-1.21-2.57-2.59c-0.16-1.3-0.53-2.21-1.12-2.73c-0.59-0.52-1.53-0.79-2.82-0.8 c-1.29-0.01-2.22,0.24-2.79,0.75c-0.58,0.52-0.95,1.44-1.1,2.76h0c-0.14,1.26-1.09,2.34-2.41,2.58c-1.85,0.35-3.64,0.85-5.37,1.51 c-1.73,0.65-3.38,1.46-4.98,2.41c-1.59,0.95-3.08,2.02-4.46,3.21c-1.38,1.18-2.67,2.48-3.85,3.9l0,0c-1.16,1.4-2.2,2.91-3.13,4.51 c-0.91,1.58-1.71,3.26-2.39,5.04c-0.68,1.77-1.18,3.57-1.51,5.37c-0.33,1.81-0.49,3.72-0.49,5.72v14.1c0,2.34-0.14,4.62-0.41,6.86 c-0.27,2.15-0.67,4.29-1.22,6.4c-0.01,0.05-0.02,0.09-0.03,0.14c-0.57,2.15-1.34,4.26-2.31,6.34c-0.94,2.01-2.06,3.96-3.35,5.85 c-0.04,0.06-0.08,0.12-0.12,0.18c-1.36,1.96-3.09,3.91-5.18,5.85l-0.08,0.07h18.22h26.85H84.9h18.19c-2.04-1.88-3.76-3.82-5.16-5.8 l0,0l0-0.01c-1.37-1.96-2.54-3.97-3.51-6.03c-0.99-2.1-1.75-4.23-2.3-6.37l0,0l0-0.01c-0.54-2.13-0.95-4.32-1.22-6.56 c-0.26-2.14-0.4-4.4-0.41-6.77v-0.01c-0.02-2.21-0.03-4.51-0.04-6.91c-0.02-4.44-0.02-6.86-0.02-7.33c0-1.96-0.16-3.87-0.5-5.72 c-0.33-1.84-0.82-3.62-1.47-5.34l0,0l0-0.01c-0.67-1.77-1.46-3.44-2.36-5.01c-0.9-1.57-1.94-3.06-3.11-4.48l0,0 c-1.16-1.39-2.43-2.68-3.81-3.87c-1.34-1.15-2.76-2.18-4.25-3.11c-0.07-0.03-0.13-0.07-0.2-0.11 C73.11,18.97,71.45,18.15,69.75,17.47L69.75,17.47L69.75,17.47z">
                </path>
              </g>
            </svg>
            <span class="badge badge-warning navbar-badge notificaionCount">15</span>
          </a>
          <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <span class="dropdown-item dropdown-header">15 Notifications</span>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item">
              <i class="fas fa-envelope mr-2"></i> 4 new messages
              <span class="float-right text-muted text-sm">3 mins</span>
            </a>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item">
              <i class="fas fa-users mr-2"></i> 8 friend requests
              <span class="float-right text-muted text-sm">12 hours</span>
            </a>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item">
              <i class="fas fa-file mr-2"></i> 3 new reports
              <span class="float-right text-muted text-sm">2 days</span>
            </a>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
          </div>
        </li> -->
        <li class="nav-item dropdown header_nav_icon">
            <a class="nav-link notify-bell-icon" data-bs-toggle="dropdown" href="javascript:void(0)"  onclick="getNotificationList()">
            <svg version="1.1" height="20" class="nav-icon" width="20" fill="#ee3137" id="Layer_1" x="0px" y="0px"
              viewBox="0 0 116.11 122.88" style="enable-background:new 0 0 116.11 122.88" xml:space="preserve">
              <g>
                <path
                  d="M74.82,109.04c-0.37,1.94-1.02,3.72-1.96,5.35c-0.97,1.67-2.24,3.18-3.8,4.5c-1.57,1.32-3.27,2.32-5.12,2.99 c-1.85,0.67-3.81,1-5.88,1c-2.08,0-4.04-0.34-5.88-1c-1.84-0.67-3.55-1.66-5.11-2.99c-1.57-1.33-2.83-2.83-3.8-4.5 c-0.97-1.67-1.63-3.51-1.99-5.53c-0.18-0.98,0.48-1.92,1.46-2.1c0.03,0,0.32-0.03,0.32-0.03h30.02c1,0,1.82,0.81,1.82,1.82 C74.89,108.72,74.86,108.88,74.82,109.04L74.82,109.04L74.82,109.04z M20.21,0.25c1.83-0.73,3.9,0.17,4.63,2 c0.73,1.83-0.17,3.9-2,4.63c-3.96,1.58-7.28,3.77-9.93,6.61c-2.64,2.84-4.63,6.36-5.93,10.59c-0.58,1.88-2.58,2.94-4.46,2.36 c-1.88-0.58-2.94-2.58-2.36-4.46c1.63-5.3,4.15-9.74,7.52-13.36C11.05,5.01,15.24,2.23,20.21,0.25L20.21,0.25z M93.27,6.88 c-1.83-0.73-2.73-2.8-2-4.63c0.73-1.83,2.8-2.73,4.63-2c4.97,1.98,9.16,4.76,12.53,8.38c3.37,3.63,5.9,8.07,7.52,13.36 c0.58,1.88-0.48,3.88-2.36,4.46c-1.88,0.58-3.88-0.48-4.46-2.36c-1.3-4.24-3.29-7.76-5.93-10.59 C100.55,10.65,97.23,8.46,93.27,6.88L93.27,6.88z M67.62,10.54c1.47,0.38,2.9,0.85,4.29,1.4c2.04,0.81,4,1.78,5.88,2.91 c0.07,0.05,0.15,0.09,0.22,0.14c1.8,1.11,3.48,2.33,5.02,3.65c1.62,1.39,3.12,2.92,4.52,4.6l0.01,0.01h0 c1.37,1.65,2.59,3.42,3.67,5.29c1.08,1.88,2.01,3.84,2.78,5.86l0,0c0.79,2.09,1.38,4.22,1.78,6.41c0.39,2.2,0.59,4.45,0.59,6.76 c0,4.56,0,7.03,0,7.33c0.01,2.34,0.02,4.63,0.04,6.86v0.02l0,0c0.01,2.02,0.14,4.05,0.39,6.08c0.25,2.01,0.61,3.95,1.08,5.82l0,0 c0.47,1.84,1.11,3.62,1.9,5.32c0.82,1.75,1.82,3.47,2.99,5.14l0.01,0c1.16,1.64,2.61,3.27,4.35,4.87c1.8,1.65,3.88,3.28,6.26,4.86 c1.36,0.91,1.73,2.76,0.81,4.12c-0.57,0.85-1.51,1.32-2.47,1.32v0.01l-26.85,0H58.06H31.21H4.37c-1.65,0-2.98-1.33-2.98-2.98 c0-1.08,0.58-2.03,1.44-2.55c2.41-1.63,4.48-3.25,6.21-4.85c1.72-1.59,3.16-3.22,4.32-4.9c0.03-0.05,0.07-0.1,0.11-0.14 c1.12-1.64,2.08-3.31,2.87-5.01c0.81-1.73,1.46-3.51,1.94-5.34c0.01-0.04,0.02-0.08,0.03-0.11c0.46-1.78,0.81-3.66,1.05-5.63 c0.24-1.98,0.37-4.03,0.37-6.14v-14.1c0-2.27,0.2-4.52,0.61-6.77c0.41-2.24,1-4.39,1.79-6.44c0.78-2.05,1.72-4.02,2.81-5.9 c1.08-1.87,2.32-3.64,3.71-5.32l0.02-0.02l0,0c1.38-1.65,2.9-3.19,4.55-4.6c1.63-1.39,3.39-2.66,5.28-3.79 c1.91-1.14,3.89-2.1,5.93-2.88c1.42-0.54,2.89-0.99,4.39-1.36c0.51-1.79,1.39-3.24,2.64-4.35c1.72-1.53,3.98-2.29,6.79-2.26 c2.78,0.02,5.03,0.79,6.73,2.32C66.22,7.32,67.11,8.76,67.62,10.54L67.62,10.54L67.62,10.54z M69.75,17.47 c-1.65-0.65-3.33-1.16-5.04-1.53c-1.32-0.17-2.4-1.21-2.57-2.59c-0.16-1.3-0.53-2.21-1.12-2.73c-0.59-0.52-1.53-0.79-2.82-0.8 c-1.29-0.01-2.22,0.24-2.79,0.75c-0.58,0.52-0.95,1.44-1.1,2.76h0c-0.14,1.26-1.09,2.34-2.41,2.58c-1.85,0.35-3.64,0.85-5.37,1.51 c-1.73,0.65-3.38,1.46-4.98,2.41c-1.59,0.95-3.08,2.02-4.46,3.21c-1.38,1.18-2.67,2.48-3.85,3.9l0,0c-1.16,1.4-2.2,2.91-3.13,4.51 c-0.91,1.58-1.71,3.26-2.39,5.04c-0.68,1.77-1.18,3.57-1.51,5.37c-0.33,1.81-0.49,3.72-0.49,5.72v14.1c0,2.34-0.14,4.62-0.41,6.86 c-0.27,2.15-0.67,4.29-1.22,6.4c-0.01,0.05-0.02,0.09-0.03,0.14c-0.57,2.15-1.34,4.26-2.31,6.34c-0.94,2.01-2.06,3.96-3.35,5.85 c-0.04,0.06-0.08,0.12-0.12,0.18c-1.36,1.96-3.09,3.91-5.18,5.85l-0.08,0.07h18.22h26.85H84.9h18.19c-2.04-1.88-3.76-3.82-5.16-5.8 l0,0l0-0.01c-1.37-1.96-2.54-3.97-3.51-6.03c-0.99-2.1-1.75-4.23-2.3-6.37l0,0l0-0.01c-0.54-2.13-0.95-4.32-1.22-6.56 c-0.26-2.14-0.4-4.4-0.41-6.77v-0.01c-0.02-2.21-0.03-4.51-0.04-6.91c-0.02-4.44-0.02-6.86-0.02-7.33c0-1.96-0.16-3.87-0.5-5.72 c-0.33-1.84-0.82-3.62-1.47-5.34l0,0l0-0.01c-0.67-1.77-1.46-3.44-2.36-5.01c-0.9-1.57-1.94-3.06-3.11-4.48l0,0 c-1.16-1.39-2.43-2.68-3.81-3.87c-1.34-1.15-2.76-2.18-4.25-3.11c-0.07-0.03-0.13-0.07-0.2-0.11 C73.11,18.97,71.45,18.15,69.75,17.47L69.75,17.47L69.75,17.47z">
                </path>
              </g>
            </svg>
            <span class="badge badge-warning navbar-badge notificaionCount">15</span>
          </a>
          <div class="dropdown-menu dropdown-menu-lg pb-0 drop-menu-width dropdown-menu-right" style="top: 2.8rem;">
            <!-- <span class="dropdown-header dropdown-notify"><el class="notificaionCount"></el> Notifications</span> -->
            <div class="notificationHeaderList">
            <div class="dropdown-divider"></div>
              
            </div>
            <!--<div class="dropdown-divider"></div>-->
            <a href="{{route('admin.notificationEntity.getNotificationAll')}}" class="dropdown-notify dropdown-footer see_noti">See All Notifications</a>
          </div>
        </li>
        <li class="nav-item">
          
          <a class="nav-link menu-icon dropdown-toggle" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false"><img src="{{ Auth::user()->profile_image ?? URL::asset('assets/dist/img/avatar.png') }}" width="30" alt=""></a>
          <ul class="user-drop dropdown-menu dropdown-menu-lg-end">
            <li><a class="dropdown-item" href="{{ route('supplier.profile.edit') }}">Edit Profile</a></li>
            <li><a class="dropdown-item" href="{{ route('supplier.change-password') }}">Change Password</a></li>
            <li><a class="dropdown-item" href="{{ route('admin.logout') }}">Logout</a></li>
          </ul>
        </li>
        <!-- <li class="nav-item">
          <a class="nav-link" data-widget="fullscreen" href="#" role="button">
            <i class="fas fa-expand-arrows-alt"></i>
          </a>
        </li> -->
       
      </ul>
    </nav>

    <script>
      window.addEventListener('mouseup',function(event){
      var pol = document.getElementById('pol');
      if(event.target != pol && event.target.parentNode != pol){
          pol.style.display = 'none';
      }
     });  
    </script>
    <!-- /.navbar -->