@php
    $noLayout = $noLayout ?? false; 
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    @include('layouts.admin.style')
</head>
<body class="{{ $noLayout ? 'bg-white' : '' }}">
    @include('layouts.admin.script')

    @include('layouts.admin.loader')

    <!-- Toaster Message -->

    <div id="pcoded" class="pcoded {{ $noLayout ? 'skeleton-less' : '' }}">
        <div class="pcoded-overlay-box"></div>
        <div class="pcoded-container navbar-wrapper">
            @if (!$noLayout)
                @include('layouts.admin.navigation')
            @endif            

            <div class="pcoded-main-container {{ $noLayout ? 'bg-white' : '' }}">
                <div class="pcoded-wrapper">
                    @if (!$noLayout)
                        @include('layouts.admin.sidebar')
                    @endif  

                    <div class="pcoded-content {{ $noLayout ? 'ml-0' : '' }}">
                        <div class="pcoded-inner-content {{ $noLayout ? 'p-0' : '' }}">
                            <div class="main-body">
                                <div class="page-wrapper {{ $noLayout ? 'p-0' : '' }}">
                                    <!-- Optional page header -->
                                    @yield('page-header')

                                    <div class="page-body">
                                        <!-- Content Body -->
                                        @yield('content')
                                    </div>
                                </div>
                                <div id="styleSelector">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ConfirmModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
        role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Action Confirmation</h5>
                    <button type="button" class="close mt-0" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="fa fa-close"></span>
                    </button>
                </div>
                <div class="modal-body confirm-message">
                    <p class="mb-0">Are you sure you want to do this?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default no" data-dismiss="modal">Not now</button>
                    <button type="button" class="btn btn-primary yes ">Yes, i do</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal modal-drawer drawer-right" id="ConfirmModal111" data-backdrop="static"
        data-keyboard="false" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Action Confirmation</h5>
                    <button type="button" class="close mt-0" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="fa fa-close"></span>
                    </button>
                </div>
                <div class="modal-body confirm-message">
                    <p class="mb-0">Are you sure you want to do this?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default no" data-dismiss="modal">Not now</button>
                    <button type="button" class="btn btn-primary yes ">Yes, i do</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>