<title>@yield('title', 'Neo Matrix')</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="#">
<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- Favicon icon -->
<link rel="icon" href="{{ asset('admin-assets/assets/images/favicon.png') }}" type="image/png">
<!-- Google font-->
<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600" rel="stylesheet">
<!-- Required Framework -->
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/bower_components/bootstrap/css/bootstrap.min.css') }}">
<!-- sweet alert framework -->
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/bower_components/sweetalert/css/sweetalert.css') }}">
<!-- Font Awesome -->
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/assets/icon/font-awesome/css/font-awesome.min.css') }}">
<!-- Simple Line Icons -->
<link rel="stylesheet" type="text/css"
    href="{{ asset('admin-assets/assets/icon/simple-line-icons/css/simple-line-icons.css') }}">
<!-- feather Awesome -->
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/assets/icon/feather/css/feather.css') }}">
<!-- Animate.css -->
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/bower_components/animate.css/css/animate.css') }}">
<!-- Select 2 css -->
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/assets/css/select2.min.css') }}">
<!-- DataTable Css -->
<link rel="stylesheet" type="text/css"
    href="{{ asset('admin-assets/bower_components/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" type="text/css"
    href="{{ asset('admin-assets/assets/pages/data-table/css/buttons.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css"
    href="{{ asset('admin-assets/bower_components/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}">
<!-- Style.css -->
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/assets/css/style.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/assets/css/jquery.mCustomScrollbar.css') }}">
<!-- Extra Styles -->
@yield('extra-styles')
<!-- Phosphor Font -->
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/assets/fonts/ph-regular/ph.css') }}">
<!-- Helper.css -->
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/assets/css/helper.css?' . rand()) }}">