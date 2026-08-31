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

                            <a href="{{ route('login') }}" class="rts-btn btn-primary-white">Login</a>
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
        <div class="container">
            <div class="row g-0">
                <div class="col-lg-6">
                    <div class="login-page-form-area">
                        <h4 class="title">Register an Account</h4>
                        <form method="POST" action="{{ route('register') }}">
                          @csrf
                            <div class="half-input-wrapper">
                                <div class="single-input-wrapper">
                                    <label for="first_name">Your First Name*</label>
                                    <input id="first_name" type="text" name="first_name" placeholder="Enter First Name" value="{{old('first_name') }}" requireds>
                                    @error('first_name')
                                      <div class="parsley-errors-list text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="single-input-wrapper">
                                    <label for="last_name">Your Last Name*</label>
                                    <input id="last_name" type="text" name="last_name" placeholder="Enter Last Name" value="{{old('last_name') }}" requireds>
                                    @error('last_name')
                                      <div class="parsley-errors-list text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="single-input-wrapper">
                                <label for="email">Email</label>
                                <input id="email" name="email" type="text" placeholder="Enter Email ID" value="{{old('email') }}" requireds>
                                @error('email')
                                  <div class="parsley-errors-list text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="half-input-wrapper">
                                <div class="single-input-wrapper">
                                    <label for="password">Your Password</label>
                                    <input id="password" name="password" type="password" placeholder="Password" requireds>
                                    @error('password')
                                      <div class="parsley-errors-list text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="single-input-wrapper">
                                    <label for="password_confirmation">Re Password</label>
                                    <input id="password_confirmation" name="password_confirmation" type="password" placeholder="Re Password" requireds>
                                    @error('password_confirmation')
                                      <div class="parsley-errors-list text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="single-checkbox-filter">
                                <div class="check-box">
                                    <input type="checkbox" id="type-1">
                                    <label for="type-1">Accept the Terms and Privacy Policy</label><br>
                                </div>
                            </div>
                            <button class="rts-btn btn-primary">Register</button>
                            <p>Already have an account? <a href="{{route('login')}}">Login </a></p>
                        </form>
                    
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="contact-thumbnail-login-p mt--100">
                        <img src="{{ asset('assets/images/banner/login-bg.png') }}"" width="600" height="495"
                            alt="login-form">
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('layouts.frontend.footer', ['footer' => false])

</body>

</html>
