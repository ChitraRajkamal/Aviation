<!DOCTYPE html>
<html lang="en">

<head>
    @include('layouts.frontend.style')
</head>

<body class="login-page">

    <div class="header-transparent">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="header-tranaparent-main-wrapper">
                        <a href="{{url('/')}}" class="logo-area">
                            <img src="{{ asset('assets/images/logo-new.png') }}"" alt="logo">
                        </a>
                        <div class="right-area pt-3">

                            <a href="{{ route('register') }}" class="rts-btn btn-primary-white">Sign Up</a>
                            <div class="menu-btn" id="menu-btn">
                                <svg width="20" height="16" viewBox="0 0 20 16" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <rect y="14" width="20" height="2" fill="#1F1F25"></rect>
                                    <rect y="7" width="20" height="2" fill="#1F1F25"></rect>
                                    <rect width="20" height="2" fill="#1F1F25"></rect>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="login-registration-wrapper">
        <div class="container mt-2">
            <div class="row g-0">
                <div class="col-lg-6">
                    <div class="login-page-form-area">
                        <h4 class="title">Login to Your Account</h4>
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="single-input-wrapper">
                                <label for="email">Your Email</label>
                                <input id="email" type="text" name="email" placeholder="Enter Your Email" value="{{old('email')}}" required>
                                @error('email')
                                    <div class="parsley-errors-list text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="single-input-wrapper">
                                <label for="password">Your Password</label>
                                <input id="password" type="password" name="password" placeholder="Password" required>
                                @error('password')
                                    <div class="parsley-errors-list">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="single-checkbox-filter d-none">
                                <div class="check-box">
                                    <input type="checkbox" id="type-1">
                                    <label for="type-1">Remember Me</label><br>
                                </div>
                            </div>
                            <button id="btn-login" class="rts-btn btn-primary">Login</button>

                            @session('error')
                                <div class="text-danger mt-2">
                                    {{ session('error') }}
                                </div>
                            @endsession                            

                            <div class="google-apple-wrapper flex-column">
                                @if (lms_setting('google_login_client_id'))
                                    <div id="btn-google-login" onClick="googleOAuth();" class="google d-flex align-items-center text-white w-md-50 p-1 pe-5 rounded" style="background: #DB4437;">
                                        <img src="{{ asset('assets/images/google-icon.png') }}" class="p-2 bg-white">
                                        <span class="ms-4">Login with Google</span>
                                    </div>
                                @endif
                                @if (lms_setting('facebook_login_app_id'))
                                    <div id="btn-facebook-login" onClick="checkFbLoginState();" class="google d-flex align-items-center text-white w-md-50 p-1 pe-3" style="background: #1877F2;">
                                        <img src="{{ asset('assets/images/facebook-icon.png') }}" class="p-2 bg-white">
                                        <span class="ms-4">Login with Facebook</span>
                                    </div>
                                @endif
                            </div>
                            <p>
                                Don't Have an account? <a href="{{ route('register') }}">Register One</a>
                                <br>
                                Forgot your password? <a href="{{ route('password.request') }}">Reset Password</a>
                            </p>
                        </form>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="contact-thumbnail-login-p mt--100">
                        <img src="{{ asset('assets/images/banner/login-bg.png') }}" width="600" height="495">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('login.by.token') }}" id="login-form" data-action="submit" method="POST">
        @csrf
        <input type="hidden" id="login_token" name="login_token" value="" />
    </form>

    @include('layouts.frontend.footer', ['footer' => false])

    <script type="text/javascript">
        var YOUR_CLIENT_ID = '{{lms_setting('google_login_client_id')}}';
        var YOUR_REDIRECT_URI = '{{route(name: 'login')}}?oauth=google';
        var fragmentString = location.hash.substring(1);
      
        // Parse query string to see if page request is coming from OAuth 2.0 server.
        var params = {};
        var regex = /([^&=]+)=([^&]*)/g, m;
        while (m = regex.exec(fragmentString)) {
          params[decodeURIComponent(m[1])] = decodeURIComponent(m[2]);
        }
        setTimeout(() => {
          //if($_GET['return'] != '') params['return'] = $_GET['return'];    
        }, 10);
        if (Object.keys(params).length > 0) {
          localStorage.setItem('lms-google-params', JSON.stringify(params) );
          if(params['error'] == 'access_denied'){
                notify({title: 'Permission Denied', animIn: 'shake'});
            }else{
                if (params['state'] && params['state'].toString().startsWith('googleOAuth')) {
                    googleOAuth();
                }
            }
        }
      
        // If there's an access token, try an API request.
        // Otherwise, start OAuth 2.0 flow.
        function googleOAuth() {
          var params = JSON.parse(localStorage.getItem('lms-google-params'));
          //alert(params['access_token']);
          if (params && params['access_token']) {
            $('#btn-login').prop('disabled', true).addClass('opacity-25');
            $('#btn-facebook-login').addClass('pe-none opacity-25');
            $('#btn-google-login').html('<i class="fa fa-spin py-2 fa-circle-notch me-3"></i> <span>Authenticating...</span>').removeClass('pe-3 pe-5').addClass('justify-content-center');
            var xhr = new XMLHttpRequest();
            xhr.open('GET',
                'https://www.googleapis.com/oauth2/v2/userinfo?fields=name,email,gender,id,picture,verified_email&' +
                'access_token=' + params['access_token']);
            xhr.onreadystatechange = function (e) {
              if (xhr.readyState === 4 && xhr.status === 200) {
                $.post('{{route('login.google.auth.verify')}}', { _token: '{{ csrf_token() }}', t: params['access_token'] }, function (result){
                    if(result.status == 'error'){
                        notify({title: result.message, animIn: 'shake'});
                        localStorage.removeItem('lms-google-params');
                        setTimeout(() => {
                          window.location.href = '{{route('login')}}';
                        }, 2000);
                        return false;
                    }
                    //notify({title: result.message, type: 'success'});
                    //redirect('{{route('home')}}');
                    $('#login_token').val(params['access_token']);
                    $('#login-form')[0].submit();
                }, 'JSON');
              } else if (xhr.readyState === 4 && xhr.status === 401) {
                oauth2SignIn();
              }
            };
            xhr.send(null);
          } else {
            oauth2SignIn();
          }
        }
      
        /*
         * Create form to request access token from Google's OAuth 2.0 server.
         */
        function oauth2SignIn() {
          // Google's OAuth 2.0 endpoint for requesting an access token
          var oauth2Endpoint = 'https://accounts.google.com/o/oauth2/v2/auth';
      
          // Create element to open OAuth 2.0 endpoint in new window.
          var form = document.createElement('form');
          form.setAttribute('method', 'GET'); // Send as a GET request.
          form.setAttribute('action', oauth2Endpoint);
      
          // Parameters to pass to OAuth 2.0 endpoint.
          var params = {'client_id': YOUR_CLIENT_ID,
                        'redirect_uri': YOUR_REDIRECT_URI,
                        'scope': 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile',
                        'state': 'googleOAuth,' + ($_GET['return'] ? $_GET['return'] : ''),
                        'include_granted_scopes': 'true',
                        'response_type': 'token'};
      
          // Add form parameters as hidden input values.
          for (var p in params) {
            var input = document.createElement('input');
            input.setAttribute('type', 'hidden');
            input.setAttribute('name', p);
            input.setAttribute('value', params[p]);
            form.appendChild(input);
          }
      
          // Add form to page and submit it to open the OAuth 2.0 endpoint.
          document.body.appendChild(form);
          form.submit();
        }
        $(document).ready(function(){
          
        });
      </script>

      <script>  
        window.fbAsyncInit = function() {
          FB.init({
            appId      : '{{lms_setting('facebook_login_app_id')}}',
            cookie     : true,
            xfbml      : true,
            version    : '{{lms_setting('facebook_graph_api_version')}}'
          });
        };
      
        function checkFbLoginState() {
            
          FB.getLoginStatus(function(response) {
            if (response.status === 'connected') {
              authenticateFacebook(response.authResponse.accessToken);  
            } else { 
              loginToFacebook();
            }
          });
        }
      
        function loginToFacebook(){
          FB.login(function(response) {
            if (response.status === 'connected') {
              authenticateFacebook(response.authResponse.accessToken);  
            }else{
              alert('Authentication is not completed. Please try again');
            }
          }, {scope: 'public_profile,email'});
        }
      
        function authenticateFacebook(t) {
            
          $.post('{{route('login.facebook.auth.verify')}}', { _token: '{{ csrf_token() }}', t: t }, function (result){
            if(result.status == 'error'){
              notify({title: result.message});
              return false;
            }
            $('#login_token').val(t);
            $('#login-form')[0].submit();
            if($_GET['return'] && $_GET['return'] != ''){
              //redirect($_GET['return'] + '#comments-section', true, false);
              //redirect('{{route('home')}}');
            }else{
                //redirect('{{route('home')}}');
            }
          }, 'JSON');
        }
      </script>
      <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js"></script>
</body>

</html>
