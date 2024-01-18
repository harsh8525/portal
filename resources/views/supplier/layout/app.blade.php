<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title')</title>
  
  
    <link rel="shortcut icon" href="{{ URL::asset('assets/images/favicon.png') }}" />
  
  
  
<!-- Google Font: Source Sans Pro -->
<link rel="stylesheet"href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
<!-- Font Awesome Icons -->
<link rel="stylesheet" href="{{ URL::asset('assets/plugins/fontawesome-free/css/all.min.css') }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

<!-- <link rel="stylesheet" href="{{ URL::asset('assets/dist/css/adminlte.css') }}"> -->

<link rel="stylesheet" href="{{ URL::asset('assets/dist/css/custome.css') }}">
  
  






<!-- <link rel="stylesheet" href="{{ URL::asset('assets/dist/css/custome.css') }}"> -->
  
</head>

<body class="hold-transition login-page login-v2 login">
  <!-- Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>    

<script src="{{ URL::asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/jquery-ui/jquery-ui.min.js') }}"></script>


  @yield('content')
<!-- /.login-box -->

  
</body>
  <!-- jQuery -->
  <script src="{{ URL::asset('assets/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/jquery-validation/additional-methods.min.js') }}"></script>

<script>
//allow to enter only digits
function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}
    //script for autofocus text and placeholders
    const inputs = document.querySelectorAll("input");

    inputs.forEach((input) => {
      input.addEventListener("blur", (event) => {
        if (event.target.value) {
          input.classList.add("is-valid");
        } else {
          input.classList.remove("is-valid");
        }
      });
    });

    const textareas = document.querySelectorAll("textarea");

    textareas.forEach((textarea) => {
      textarea.addEventListener("blur", (event) => {
        if (event.target.value) {
          textarea.classList.add("is-valid");
        } else {
          textarea.classList.remove("is-valid");
        }
      });
    });
</script>

</html>


  
  
  
  



