@php
    $footer = $footer ?? true;
    $appName = lms_setting('app_name');
    $email = lms_setting('email');
    $phone = lms_setting('phone');
@endphp
@php
/*
<!-- cart area start -->
<div class="cart-bar">
  <div class="cart-header">
      <h3 class="cart-heading">MY CART (3 ITEMS)</h3>
      <div class="close-cart"><i class="fal fa-times"></i></div>
  </div>
  <div class="product-area">
      <div class="product-item">
          <div class="product-detail">
              <div class="product-thumb"><img src="{{ asset('assets/images/course/cart/01.jpg') }}"alt="product-thumb"></div>
              <div class="item-wrapper">
                  <span class="product-name">Construct Map</span>
                  <div class="item-wrapper">
                      <span class="product-variation"><span class="color">Green /</span>
                      <span class="size">XL</span></span>
                  </div>
                  <div class="item-wrapper">
                      <span class="product-qnty">3 ×</span>
                      <span class="product-price">$198.00</span>
                  </div>
              </div>
          </div>
          <div class="cart-edit">
              <div class="quantity-edit">
                  <button class="button"><i class="fal fa-minus minus"></i></button>
                  <input type="text" class="input" value="3">
                  <button class="button plus">+<i class="fal fa-plus plus"></i></button>
              </div>
              <div class="item-wrapper d-flex mr--5 align-items-center">
                  <a href="#" class="product-edit"><i class="fal fa-edit"></i></a>
                  <a href="#" class="delete-cart"><i class="fal fa-times"></i></a>
              </div>
          </div>
      </div>
      <div class="product-item">
          <div class="product-detail">
              <div class="product-thumb"><img src="{{ asset('assets/images/course/cart/02.jpg') }}"alt="product-thumb"></div>
              <div class="item-wrapper">
                  <span class="product-name"> Bridge product</span>
                  <div class="item-wrapper">
                      <span class="product-variation"><span class="color">Green /</span>
                      <span class="size">XL</span></span>
                  </div>
                  <div class="item-wrapper">
                      <span class="product-qnty">2 ×</span>
                      <span class="product-price">$88.00</span>
                  </div>
              </div>
          </div>
          <div class="cart-edit">
              <div class="quantity-edit">
                  <button class="button"><i class="fal fa-minus minus"></i></button>
                  <input type="text" class="input" value="2">
                  <button class="button plus">+<i class="fal fa-plus plus"></i></button>
              </div>
              <div class="item-wrapper d-flex mr--5 align-items-center">
                  <a href="#" class="product-edit"><i class="fal fa-edit"></i></a>
                  <a href="#" class="delete-cart"><i class="fal fa-times"></i></a>
              </div>
          </div>
      </div>
      <div class="product-item last-child">
          <div class="product-detail">
              <div class="product-thumb"><img src="{{ asset('assets/images/course/cart/03.jpg') }}"alt="product-thumb"></div>
              <div class="item-wrapper">
                  <span class="product-name">Labour helmet</span>
                  <div class="item-wrapper">
                      <span class="product-variation"><span class="color">Green /</span>
                      <span class="size">XL</span></span>
                  </div>
                  <div class="item-wrapper">
                      <span class="product-qnty">1 ×</span>
                      <span class="product-price">$289.00</span>
                  </div>
              </div>
          </div>
          <div class="cart-edit">
              <div class="quantity-edit">
                  <button class="button"><i class="fal fa-minus minus"></i></button>
                  <input type="text" class="input" value="2">
                  <button class="button plus">+<i class="fal fa-plus plus"></i></button>
              </div>
              <div class="item-wrapper d-flex mr--5 align-items-center">
                  <a href="#" class="product-edit"><i class="fal fa-edit"></i></a>
                  <a href="#" class="delete-cart"><i class="fal fa-times"></i></a>
              </div>
          </div>
      </div>
  </div>
  <div class="cart-bottom-area">
      <span class="spend-shipping"><i class="fal fa-truck"></i> SPENT <span class="amount">$199.00</span> MORE
      FOR FREE SHIPPING</span>
      <span class="total-price">TOTAL: <span class="price">$556</span></span>
      <a href="###" class="checkout-btn cart-btn">PROCEED TO CHECKOUT</a>
      <a href="###" class="view-btn cart-btn">VIEW CART</a>
  </div>
</div>
<!-- cart area edn -->
*/
@endphp
@if ($footer)
  <!-- footer call to action area start -->
  <div class="footer-callto-action-area">
    {{-- @if (request()->path() == '/')
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="call-to-sction bg_image shape-move">
                        <h2 class="title">Skills Certificate From <br> the {{$appName}}</h2>
                        <a href="###" class="rts-btn btn-primary-white with-arrow">View All Course <i class="fa-regular fa-arrow-right"></i></a>
                        <div class="cta-image">
                            <img src="{{ asset('assets/images/cta/women.png') }}" alt="">
                        </div>
                        <div class="shape-image">
                            <div class="shape one" data-speed="0.04"><img src="{{ asset('assets/images/cta/03.svg') }}"alt="" style="filter: brightness(0);"></div>
                            <div class="shape two" data-speed="0.04"><img src="{{ asset('assets/images/cta/04.svg') }}"alt="" style="filter: brightness(0);"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif --}}
    
    <div class="container">
        <div class="row ptb--80">
            <div class="col-lg-12">
                <!-- footer main wrapper -->
                <div class="footer-one-main-wrapper">
                    <!-- single sized  footer  -->
                    <div class="footer-singl-wized left-logo">
                        <div class="head">
                            <a href="#">
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
                    <div class="footer-singl-wized input-area">
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
                    </div>
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
<div class="modal login-pupup-modal fade" id="exampleModal-login" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Hi, Welcome back!</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
              <form action="{{route('login')}}" method="POST" class="login-form">
                @csrf
                  <input type="text" name="email" placeholder="Username of Email Address" required>
                  <input type="password" name="password" placeholder="Password" required>
                  <div class="d-flex mb--20 align-items-center">
                      <input type="checkbox" id="examplecheck-modal">
                      <label for="examplecheck-modal" class="d-none">I agree to the terms of use and privacy policy.</label>
                  </div>
                  <button type="submit" class="rts-btn btn-primary">Sign In</button>

                  <p class="dont-acc mt--20">Dont Have an Account? <a href="{{route('register')}}">Sign-up</a> </p>
              </form>
          </div>
      </div>
  </div>
</div>

<!-- header style two -->
<div id="side-bar" class="side-bar header-two">
  <button class="close-icon-menu"><i class="far fa-times"></i></button>
  <!-- inner menu area desktop start -->
  <div class="inner-main-wrapper-desk">
      <div class="thumbnail">
          <img src="{{ asset('assets/images/banner/04.jpg') }}"alt="elevate">
      </div>
      <div class="inner-content">
          <h4 class="title">We Build Building and Great Constructive Homes.</h4>
          <p class="disc">
              We successfully cope with tasks of varying complexity, provide long-term guarantees and regularly master new technologies.
          </p>
          <div class="footer">
              <h4 class="title">Got a project in mind?</h4>
              <a href="###" class="rts-btn btn-primary">Let's talk</a>
          </div>
      </div>
  </div>
  <!-- mobile menu area start -->
  <div class="mobile-menu-main">
      <nav class="nav-main mainmenu-nav mt--30">
          <ul class="mainmenu metismenu" id="mobile-menu-active">
              <li class="">
                  <a href="#" class="main">Home</a>
              </li>
              <li class="">
                  <a href="{{route('courses')}}" class="main">Courses</a>
              </li>
              <li class="">
                  <a href="{{route('exams')}}" class="main">Exams</a>
              </li>
              <li class="">
                  <a href="#" class="main">About us</a>
              </li>
              <li class="">
                  <a href="#" class="main">Contact us</a>
              </li>
          </ul>
      </nav>

      <div class="buttons-area">
          <a href="{{route('login')}}" class="rts-btn btn-border">Log In</a>
          <a href="{{route('register')}}" class="rts-btn btn-primary">Sign Up</a>
      </div>

      <div class="rts-social-style-one pl--20 mt--50">
          <ul>
              <li>
                  <a href="#">
                      <i class="fa-brands fa-facebook-f"></i>
                  </a>
              </li>
              <li>
                  <a href="#">
                      <i class="fa-brands fa-twitter"></i>
                  </a>
              </li>
              <li>
                  <a href="#">
                      <i class="fa-brands fa-youtube"></i>
                  </a>
              </li>
              <li>
                  <a href="#">
                      <i class="fa-brands fa-linkedin-in"></i>
                  </a>
              </li>
          </ul>
      </div>
  </div>
  <!-- mobile menu area end -->
</div>
<!-- header style two End -->

<!-- modal -->
<div id="myModal-1" class="modal fade" role="dialog">
  <div class="modal-dialog bg_image">
      <div class="modal-content">
          <div class="modal-header">
              <button type="button" class="close" data-bs-dismiss="modal"><i class="fa-light fa-x"></i></button>
          </div>
          <div class="modal-body text-center">
              <div class="inner-content">
                  <div class="title-area">
                      <span class="pre">Get Our Courses Free</span>
                      <h4 class="title">Wonderful for Learning</h4>
                  </div>
                  <form action="#">
                      <input type="text" placeholder="Your Mail.." required>
                      <button>Download Now</button>
                      <span>Your information will never be shared with any third party</span>
                  </form>
              </div>
          </div>
      </div>
  </div>
</div>
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
<script src="{{ asset('assets/js/main.js?') }}"></script><!-- Helper.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.min.js"></script>
<script src="{{ asset('assets/js/helper.js?r=') . rand() }}"></script>

@yield('extra-scripts')

<script type="application/ld+json">
{
  "@context":"https://schema.org",
  "@type":"EducationalOrganization",
  "name":"{{ config('app.name') }}",
    "url":"{{ url('/') }}",
    "logo":"{{ asset('assets/images/logo-new.png') }}"
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
        var notifyMessageJson = decode_html_entities('{{ session('alert') }}').trimLeft('"').trimRight('"');
        var notifyMessage = JSON.parse(notifyMessageJson);
        notify(notifyMessage);
    </script>
@endsession