@extends('admin.layout.app')
@section('title',$header['title'])

@section('content')
<div class="bg-clr">
  @if (session('success'))
  <div class="alert alert-success" role="alert">
    <?php echo session('success'); ?>
  </div>
  @endif
  @if (session('error'))
  <div class="alert alert-danger" role="alert">
    {{ session('error') }}
  </div>
  @endif
  @if (isset($error))
  <div class="alert alert-danger" role="alert">
    {{ $error }}
  </div>
  @endif
  <div class="logo-login">
    @php
    @$logo = App\Models\Setting::where('config_key', 'general|basic|colorLogo')->get('value')[0]['value'];
    @$value = App\Models\Setting::where('config_key', 'general|basic|siteName')->get('value')[0]['value'];
    @endphp
    <a href="{{ route('admin.login') }}">
      @if($logo)
      <img src="{{@$logo}}" alt="">
      @elseif($value)
      <h1>{{ $value }}</h1>
      @else
      <img src="{{ URL::asset('assets/images/logo.png') }}" alt="">
      @endif
    </a>
  </div>
  <div>
    <p>@lang('otp.description')
    </p>
  </div>
  <form action="{{ route('admin.otp-verification') }}" class="form row mx-0 validate" method="post">
    @csrf
    <input type="hidden" name="mobile" id="mobile" value="{{$mobile}}">
    <div class="login-page p-0">
      <div class="otp-input">
        <input type="number" id="mobileno" name="digit1" class="otp__digit otp__field__1" autocomplete="off" maxlength="1" required="">
        <input type="number" id="mobileno" name="digit2" class="otp__digit otp__field__2" autocomplete="off" maxlength="1" required="">
        <input type="number" id="mobileno" name="digit3" class="otp__digit otp__field__3" autocomplete="off" maxlength="1" required="">
        <input type="number" id="mobileno" name="digit4" class="otp__digit otp__field__4" autocomplete="off" maxlength="1" required="">
        <input type="number" id="mobileno" name="digit5" class="otp__digit otp__field__5" autocomplete="off" maxlength="1" required="">
      </div>
    </div>
    <div class="login-bottom">
      <button type="submit" class="btn login-btn">@lang('otp.continue')</button>
    </div>
  </form>
</div>

<script>
  var otp_inputs = document.querySelectorAll(".otp__digit")
  var mykey = "0123456789".split("")
  otp_inputs.forEach((_) => {
    _.addEventListener("keyup", handle_next_input)
  })

  function handle_next_input(event) {
    let current = event.target
    let index = parseInt(current.classList[1].split("__")[2])
    current.value = event.key

    if (event.keyCode == 8 && index > 1) {
      current.previousElementSibling.focus()
    }
    if (index < 6 && mykey.indexOf("" + event.key + "") != -1) {
      var next = current.nextElementSibling;
      next.focus()
    }
    var _finalKey = ""
    for (let {
        value
      }
      of otp_inputs) {
      _finalKey += value
    }
  }
</script>
@endsection