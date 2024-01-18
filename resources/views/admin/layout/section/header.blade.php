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
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
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
  <link rel="stylesheet" href="{{ URL::asset('assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">

  <!-- Select2 Bootstrap 4 -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">

  <style>
    .dark-mode .alert-success {
      text-transform: capitalize;
    }

    .dark-mode .alert-danger {
      text-transform: capitalize;
    }

    .table .softdelete {
      background-color: rgba(249, 62, 62, .1) !important;
    }

    #pol {
      display: none;
    }

    .see_noti {
      background-color: #fff !important;
      color: #000 !important
    }

    .see_noti:hover {
      color: #ee3137 !important;
    }
  </style>
</head>

<body class="hold-transition dark-mode sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
  <div class="wrapper">

    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center">
      @php
      $logo = "";
      @$logo = App\Models\Setting::where('config_key', 'general|basic|favicon')->get('value')[0]['value'];
      @endphp
      @if($logo)
      <img class="animation__wobble" src="{{ $logo }}" alt="" height="60" width="60">

      @else
      <!-- <img class="animation__wobble" src="{{ URL::asset('assets/images/favicon.png') }}" alt="" height="60" width="60"> -->
      <h1>{{App\Models\Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value']}}</h1>
      @endif
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
        <li class="nav-item">
          <a class="nav-link menu-icon dropdown-toggle" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false"><img src="{{ Auth::user()->profile_image ?? URL::asset('assets/dist/img/avatar.png') }}" style="display: inline;" width="30" alt=""></a>
          <ul class="user-drop dropdown-menu dropdown-menu-lg-end">
            <li><a class="dropdown-item" href="{{ route('admin.profile.edit') }}">Edit Profile</a></li>
            <li><a class="dropdown-item" href="{{ route('admin.change-password') }}">Change Password</a></li>
            <li><a class="dropdown-item" href="{{ route('admin.logout') }}">Logout</a></li>
          </ul>
        </li>


      </ul>
    </nav>

    <script>
      window.addEventListener('mouseup', function(event) {
        var pol = document.getElementById('pol');
        if (event.target != pol && event.target.parentNode != pol) {

        }
      });
    </script>
    <!-- /.navbar -->