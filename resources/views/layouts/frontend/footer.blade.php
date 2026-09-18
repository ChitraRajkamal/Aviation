@php
    $footer = $footer ?? true;
    $appName = lms_setting('app_name');
    $email = lms_setting('email');
    $phone = lms_setting('phone');
@endphp

@if ($footer)
  <!-- footer call to action area start -->
  <div class="footer-callto-action-area">


    <div class="container">
        <div class="row ptb--80">
            <div class="col-lg-12">
                <!-- footer main wrapper -->
                <div class="footer-one-main-wrapper">
                    <!-- single sized  footer  -->
                    <div class="footer-singl-wized left-logo">
                        <div class="head">
                            <a href="{{ route('home') }}">
                                <img src="{{ asset('assets/images/logo-transparent.png') }}"alt="logo" style="height: 90px;">
                            </a>
                        </div>
                        <div class="body">
                            <p class="text-muted">
                                Wings to Future ✈️ Empowering students with aviation training, industry exposure, and career-focused learning.
                            </p>
                            <ul class="wrapper-list">
                                <li><i class="fa-regular fa-location-dot"></i> {{lms_setting('address')}}</li>
                                <li><i class="fa-regular fa-phone"></i><a href="tel:{{$phone}}">{{$phone}}</a></li>
                            </ul>
                        </div>
                    </div>
                    <!-- single sized  footer end -->
                    <!-- single sized  footer  -->
                    <div class="footer-singl-wized">
                        {{-- <div class="head">
                            <h6 class="title">Explore</h6>
                        </div>
                        <div class="body">
                            <ul class="menu">
                                <li><a href="{{route('about-us')}}">About us</a></li>
                                <li><a href="{{route('contact-us')}}">Contact us</a></li>
                                <li><a href="{{route('courses')}}">Course List</a></li>
                                <li><a href="{{route('faq.index')}}">FAQ</a></li>
                                <li><a href="{{route('gallery')}}">Gallery</a></li>
                            </ul>
                        </div> --}}
                    </div>
                    <!-- single sized  footer end -->
                    <!-- single sized  footer  -->
                    <div class="footer-singl-wized">
                        <div class="head">
                            <h6 class="title">Quick Links</h6>
                        </div>
                        <div class="body">
                            <ul class="menu">
                                <li><a href="{{route('about-us')}}">About us</a></li>
                                <li><a href="{{route('contact-us')}}">Contact us</a></li>
                                <li><a href="{{route('courses')}}">Course List</a></li>
                                <li><a href="{{route('faq.index')}}">FAQ</a></li>
                                <li><a href="{{route('gallery')}}">Gallery</a></li>
                                {{-- <li><a href="{{route('exams')}}">Exam List</a></li> --}}
                            </ul>
                        </div>
                    </div>
                    <!-- single sized  footer end -->
                    <!-- single sized  footer  -->
                   <!-- <div class="footer-singl-wized input-area">
                        <div class="head">
                            <h6 class="title">Newsletter</h6>
                        </div>
                        <div class="body">
                            <p class="disc">Subscribe Our newsletter get update our new course</p>
                            <form action="#">
                                <div class="input-area-fill">
                                    <input type="email" placeholder="Enter Your Email" required>
                                    <button> Subscribe</button>
                                </div>
                                <div class="d-flex align-items-center">
                                    <input type="checkbox" id="exampleCheck1">
                                    <label for="exampleCheck1">I agree to the terms of use and privacy policy.</label>
                                </div>
                            </form>
                        </div>
                    </div> -->
                    <!-- single sized  footer end -->
                </div>
                <!-- footer main wrapper end -->
            </div>
        </div>
    </div>
    <div class="copyright-area-one-border">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="copyright-area-one">
                        <p>Copyright &copy; {{date('Y')}} All Rights Reserved by {{$appName}}</p>
                        <div class="social-copyright">
                            <ul>
                                @if (lms_setting('social_facebook'))
                                    <li><a href="{{ lms_setting('social_facebook') }}"><i class="fa-brands fa-facebook-f"></i></a></li>
                                @endif
                                @if (lms_setting('social_twitter'))
                                    <li>
                                        <a href="{{ lms_setting('social_twitter') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="align-text-top" width="20" height="20" fill="#110c2d" viewBox="0 0 256 256"><path d="M215,219.85a8,8,0,0,1-7,4.15H160a8,8,0,0,1-6.75-3.71l-40.49-63.63L53.92,221.38a8,8,0,0,1-11.84-10.76l61.77-68L41.25,44.3A8,8,0,0,1,48,32H96a8,8,0,0,1,6.75,3.71l40.49,63.63,58.84-64.72a8,8,0,0,1,11.84,10.76l-61.77,67.95,62.6,98.38A8,8,0,0,1,215,219.85Z"></path></svg>
                                        </a>
                                    </li>
                                @endif
                                @if (lms_setting('social_instagram'))
                                    <li><a href="{{ lms_setting('social_instagram') }}"><i class="fa-brands fa-instagram"></i></a></li>
                                @endif
                                @if (lms_setting('social_linkedin'))
                                    <li><a href="{{ lms_setting('social_linkedin') }}"><i class="fa-brands fa-linkedin"></i></a></li>
                                @endif
                                @if (lms_setting('social_pinterest'))
                                    <li><a href="{{ lms_setting('social_pinterest') }}"><i class="fa-brands fa-pinterest"></i></a></li>
                                @endif
                                @if (lms_setting('social_youtube'))
                                    <li><a href="{{ lms_setting('social_youtube') }}"><i class="fa-brands fa-youtube"></i></a></li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>
  <!-- footer call to action area end -->
@endif

<!-- Modal -->


<!-- header style two -->
<div id="side-bar" class="side-bar header-two">
  <button class="close-icon-menu"><i class="far fa-times"></i></button>
  <!-- inner menu area desktop start -->

  <!-- mobile menu area start -->
  <div class="mobile-menu-main">
      <nav class="nav-main mainmenu-nav mt--30">
          <ul class="mainmenu metismenu" id="mobile-menu-active">
              <li class="">
                  <a href="{{ url('/') }}" class="main">Home</a>
              </li>
              <li class="">
                  <a href="{{route('courses')}}" class="main">Courses</a>
              </li>
               <li>
        <a href="{{ route('gallery') }}" class="main">Gallery</a>
    </li>
    <li>
        <a href="{{ route('faq.index') }}" class="main">FAQ</a>
    </li>

              <li class="">
                  <a href="{{ route('about-us') }}" class="main">About us</a>
              </li>
              <li class="">
                  <a href="{{ route('contact-us') }}" class="main">Contact us</a>
              </li>
          </ul>
      </nav>

      <div class="buttons-area">
          <a href="{{route('login')}}" class="rts-btn btn-border">Log In</a>
          <a href="{{route('register')}}" class="rts-btn btn-primary">Sign Up</a>
      </div>

      <div class="rts-social-style-one pl--20 mt--50">
    <ul>
        @if (lms_setting('social_facebook'))
            <li>
                <a href="{{ lms_setting('social_facebook') }}">
                    <i class="fa-brands fa-facebook-f"></i>
                </a>
            </li>
        @endif

        @if (lms_setting('social_twitter'))
            <li>
                <a href="{{ lms_setting('social_twitter') }}">
                    <i class="fa-brands fa-twitter"></i>
                </a>
            </li>
        @endif

        @if (lms_setting('social_youtube'))
            <li>
                <a href="{{ lms_setting('social_youtube') }}">
                    <i class="fa-brands fa-youtube"></i>
                </a>
            </li>
        @endif

        @if (lms_setting('social_linkedin'))
            <li>
                <a href="{{ lms_setting('social_linkedin') }}">
                    <i class="fa-brands fa-linkedin-in"></i>
                </a>
            </li>
        @endif
    </ul>
</div>
  </div>
  <!-- mobile menu area end -->
</div>
<!-- header style two End -->

<!-- modal -->

<!-- rts backto top start -->
<div class="progress-wrap">
  <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
      <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" style="transition: stroke-dashoffset 10ms linear 0s; stroke-dasharray: 307.919, 307.919; stroke-dashoffset: 307.919;"></path>
  </svg>
</div>
<!-- rts backto top end -->

<!-- offcanvase search -->
<div class="search-input-area">
  <div class="container">
      <div class="search-input-inner">
          <form action="{{ route('courses') }}" class="input-div">
            <input type="hidden" name="search" value="true" >
            <input class="search-input autocompletes" name="query" type="text" value="{{request('query')}}" placeholder="Search courses...">
            <button><i class="far fa-search"></i></button>
          </form>
      </div>
  </div>
  <div id="close" class="search-close-icon"><i class="far fa-times"></i></div>
</div>
<!-- offcanvase search -->
<div id="anywhere-home" class="">
</div>

<!-- all scripts -->
<!-- jquery ui js -->
<script src="{{ asset('assets/js/vendor/jquery-ui.js') }}"></script>
<!-- metismenu js -->
<script src="{{ asset('assets/js/vendor/metismenu.js') }}"></script>
<!-- magnific popup js-->
<script src="{{ asset('assets/js/vendor/magnifying-popup.js') }}"></script>
<!-- swiper JS 10.2.0 -->
<script src="{{ asset('assets/js/plugins/swiper.js') }}"></script>
<!-- counterup js -->
<script src="{{ asset('assets/js/plugins/counterup.js') }}"></script>
<!-- waypoint js -->
<script src="{{ asset('assets/js/vendor/waypoint.js') }}"></script>
<!-- wow js -->
<script src="{{ asset('assets/js/vendor/waw.js') }}"></script>
<!-- isotop mesonary -->
<script src="{{ asset('assets/js/plugins/isotop.js') }}"></script>
<!-- jquery imageloaded -->
<script src="{{ asset('assets/js/plugins/imagesloaded.pkgd.min.js') }}"></script>
<!-- resize sensor js -->
<script src="{{ asset('assets/js/plugins/resizer-sensor.js') }}"></script>
<!-- sticky sidebar -->
<script src="{{ asset('assets/js/plugins/sticky-sidebar.js') }}"></script>
<!-- gsap twinmax js -->
<script src="{{ asset('assets/js/plugins/twinmax.js') }}"></script>
<!-- chroma js -->
<script src="{{ asset('assets/js/vendor/chroma.min.js') }}"></script>
<!-- bootstrap 5.0.2 -->
<script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
<!-- dymanic Contact Form -->
<script src="{{ asset('assets/js/plugins/contact.form.js') }}"></script>
<!-- calender js -->
<script src="{{ asset('assets/js/plugins/calender.js') }}"></script>
<!-- sweet alert js -->
<script src="{{ asset('assets/js/sweetalert2.js') }}"></script>
<!-- notification js -->
<script type="text/javascript" src="{{ asset('admin-assets/assets/js/bootstrap-growl.min.js') }}"></script>
<!-- main Js -->
<script src="{{ asset('assets/js/main.js') }}"></script><!-- Helper.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.min.js"></script>
<script src="{{ asset('assets/js/helper.js') }}"></script>

@yield('extra-scripts')

<script type="application/ld+json">
{
  "@context":"https://schema.org",
  "@type":"EducationalOrganization",
  "name":"{{ config('app.name') }}",
    "url":"{{ url('/') }}",
    "logo":"{{ asset('assets/images/logo-new.png') }}",
  "description":"First Fly Aviation Academy provides aviation training, cabin crew training, airport operations training, travel and tourism courses, drone training, and aviation English training.",
  "telephone":"{{ lms_setting('phone') }}",
  "address":{
    "@type":"PostalAddress",
    "streetAddress":"23, 3rd floor, Chandra Tower, Rajaji Road, West Tambaram",
    "addressLocality":"Chennai",
    "addressRegion":"Tamil Nadu",
    "postalCode":"600045",
    "addressCountry":"IN"
  }
}
</script>

@session('alert')
    <script>
        var notifyMessageJson = decode_html_entities("{{ session('alert') }}").trimLeft('"').trimRight('"');
        var notifyMessage = JSON.parse(notifyMessageJson);
        notify(notifyMessage);
    </script>
@endsession
