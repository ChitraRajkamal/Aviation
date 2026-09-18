<meta charset="UTF-8">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', lms_setting('app_name'))</title>
@yield('meta-data')

<meta name="theme-color" content="#b56edc" />
<link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/images/favicon.png') }}">
<!-- fontawesome 6.4.2 -->
<link rel="stylesheet" href="{{ asset('assets/css/plugins/fontawesome-6.css') }}">
<!-- swiper Css 10.2.0 -->
<link rel="stylesheet" href="{{ asset('assets/css/plugins/swiper.min.css') }}">
<!-- magnific popup css -->
<link rel="stylesheet" href="{{ asset('assets/css/vendor/magnific-popup.css') }}">
<!-- Bootstrap 5.0.2 -->
<link rel="stylesheet" href="{{ asset('assets/css/vendor/bootstrap.min.css') }}">
<!-- jquery ui css -->
<link rel="stylesheet" href="{{ asset('assets/css/vendor/jquery-ui.css') }}">
<!-- metismenu scss -->
<link rel="stylesheet" href="{{ asset('assets/css/vendor/metismenu.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/parsleyjs/src/parsley.css">
<!-- custom style css -->
<link rel="stylesheet" href="{{ asset('assets/css/style.css?') }}">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<link href="https://fonts.googleapis.com/css2?family=Hind:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Teko:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<link href="https://fonts.googleapis.com/css2?family=Alegreya:wght@400;500;600&display=swap" rel="stylesheet">
@yield('extra-styles')
<style>
.gfont{
    font-family: 'Alegreya', sans-serif;
    /*font-size: 20px !important;
    font-weight: 100 !important;
    color: #553cdf !important;*/
}
</style>

<!-- jquery min js -->
<script src="{{ asset('assets/js/vendor/jquery.min.js') }}"></script>
