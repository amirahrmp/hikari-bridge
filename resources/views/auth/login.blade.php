<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login | Hikari Bridge</title>
  <link rel="shortcut icon" type="image/png" href="{{asset('AdminLTE')}}/dist/img/hikari_logo.png"/>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{asset('AdminLTE')}}/plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="{{asset('AdminLTE')}}/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('AdminLTE')}}/dist/css/adminlte.min.css">

  <style>
    body {
      background-image: url('{{ asset('AdminLTE/dist/img/background.jpg') }}'); /* Ganti dengan path ke gambar gedung */
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
    }
    .login-box {
      background: rgba(255, 255, 255, 0.7); /* Agar background lebih transparan */
    }
</style>
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <!-- /.login-logo -->
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
        <img src="{{asset('AdminLTE')}}/dist/img/hikari_logo2.png" alt="Hikari Bridge Logo" class="brand-image" style="width: auto; height: 70px;">
    </div>
    <div class="card-body">
      <p class="login-box-msg">SIGN IN</p>

      <!-- Session Status -->
      <x-auth-session-status class="mb-4" :status="session('status')" />

      <!-- Validation Errors -->
      <x-auth-validation-errors class="mb-4 alert alert-danger" :errors="$errors" />

      <form method="POST" action="{{ route('login') }}">
          @csrf

          <!-- Email Address -->
          <div class="input-group mb-3">
              <input id="email" class="form-control" type="email" name="email" :value="old('email')" required autofocus placeholder="Email" />
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-envelope"></span>
                </div>
              </div>
          </div>

          <!-- Password -->
          <div class="input-group mb-3">
              <input id="password" class="form-control"
                              type="password"
                              name="password"
                              required autocomplete="current-password" placeholder="Password"/>
                              <div class="input-group-append">
                                <div class="input-group-text">
                                  <span class="fas fa-lock"></span>
                                </div>
                              </div>
          </div>

          <!-- Remember Me -->
          <div class="block mt-4">
              <label for="remember_me" class="inline-flex items-center">
                  <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" name="remember">
                  <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
              </label>
          </div>

          <div class="flex items-center justify-end mt-1">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-blue hover:text-blue-900" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif
          
              <button class="mt-4 mb-4 btn btn-primary btn-block">
                  {{ __('Sign in') }}
              </button>
             
            &nbsp;<a class="px-6 py-3 text-gray no-underline bg-green-700 rounded hover:bg-green-800 hover:underline hover:text-green-200" href="{{ route('register') }}"><b>Don't have an account? Register</b></a>
        
          </div>
      </form>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="{{asset('AdminLTE')}}/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('AdminLTE')}}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="{{asset('AdminLTE')}}/dist/js/adminlte.min.js"></script>
</body>
</html>