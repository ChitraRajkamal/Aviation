<input type="hidden" id="HiddenAppBaseURL" value="{{ url('') }}" />
<input type="hidden" id="PreviewImagePlaceholder" value="" />
<!-- Required Jquery -->
<script type="text/javascript" src="{{ asset('admin-assets/bower_components/jquery/js/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('admin-assets/bower_components/jquery-ui/js/jquery-ui.min.js') }}">
</script>
<script type="text/javascript" src="{{ asset('admin-assets/bower_components/popper.js/js/popper.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('admin-assets/bower_components/bootstrap/js/bootstrap.min.js') }}">
</script>
<!-- C# inbuilt validation -->
<script type="text/javascript" src="{{ asset('admin-assets/assets/js/jquery.validate.js?2') }}"></script>
<script type="text/javascript" src="{{ asset('admin-assets/assets/js/jquery.validate.unobtrusive.js') }}"></script>
<!-- jquery slimscroll js -->
<script type="text/javascript"
    src="{{ asset('admin-assets/bower_components/jquery-slimscroll/js/jquery.slimscroll.js') }}"></script>
<!-- modernizr js -->
<script type="text/javascript" src="{{ asset('admin-assets/bower_components/modernizr/js/modernizr.js') }}"></script>

<script type="text/javascript" src="{{ asset('admin-assets/assets/js/jquery.mCustomScrollbar.concat.min.js') }}">
</script>
@php
    /*
    <script type="text/javascript" src="{{ asset('admin-assets/assets/js/SmoothScroll.js') }}"></script>
    */
@endphp
<script type="text/javascript" src="{{ asset('admin-assets/assets/js/pcoded.min.js') }}"></script>
<!-- notification js -->
<script type="text/javascript" src="{{ asset('admin-assets/assets/js/bootstrap-growl.min.js') }}"></script>
<!-- Bootstrap Validation js -->
<script type="text/javascript"
    src="{{ asset('admin-assets/bower_components/bootstrap-validator/bootstrap-validator.js') }}"></script>

<!-- sweet alert js -->
<script type="text/javascript" src="{{ asset('admin-assets/bower_components/sweetalert/js/sweetalert.min.js') }}"></script>

<!-- Select 2 js -->
<script type="text/javascript" src="{{ asset('admin-assets/assets/js/select2.min.js') }}"></script>

<!-- Datatable js -->
<script src="{{ asset('admin-assets/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>

<script src="{{ asset('admin-assets/bower_components/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('admin-assets/bower_components/datatables.net-responsive/js/dataTables.responsive.min.js') }}">
</script>
<script
    src="{{ asset('admin-assets/bower_components/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}">
</script>

<!-- ScrollToFixed js -->
<script type="text/javascript" src="{{ asset('admin-assets/assets/js/jquery-scrolltofixed.js') }}"></script>
<!-- custom js -->
<script type="text/javascript" src="{{ asset('admin-assets/assets/js/vartical-layout.min.js?') }}"></script>
<script type="text/javascript" src="{{ asset('admin-assets/assets/js/script.min.js') }}"></script>
<!-- Sweet Alert 2 -->
<script type="text/javascript" src="{{ asset('admin-assets/assets/js/sweetalert2.js') }}"></script>
<!-- Extra Scripts -->
@yield('extra-scripts')
<!-- Helper.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.min.js"></script>
<!-- Phosphor Icon 
<script src="https://unpkg.com/@phosphor-icons/web"></script>
<script src="https://cdn.jsdelivr.net/npm/phosphor-icons@1.4.2/src/index.min.js"></script>-->
<script type="text/javascript" src="{{ asset('admin-assets/assets/js/helper.js?' . rand()) }}"></script>
@session('alert')
    <script>
        var notifyMessageJson = decode_html_entities('{{ session('alert') }}').trimLeft('"').trimRight('"');
        var notifyMessage = JSON.parse(notifyMessageJson);
        notify(notifyMessage);
    </script>
@endsession
