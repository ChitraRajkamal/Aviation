<nav class="navbar header-navbar pcoded-header sdfsdfd">
    <div class="navbar-wrapper">

        <div class="navbar-logo">
            <a class="mobile-menu" id="mobile-collapse" href="javascript:;">
                <i class="feather icon-menu"></i>
            </a>
            <a href="{{ route('admin.dashboard') }}">
                <img class="img-fluid" src="{{ asset('admin-assets/assets/images/logo-150x50.png') }}" alt="Theme-Logo">
            </a>
            <a class="mobile-options">
                <i class="feather icon-more-horizontal"></i>
            </a>
        </div>

        <div class="navbar-container container-fluid">
            <ul class="nav-left">
                <li class="header-search d-none">
                    <div class="main-search morphsearch-search">
                        <div class="input-group">
                            <span class="input-group-addon search-close"><i class="feather icon-x"></i></span>
                            <input type="text" class="form-control" placeholder="Search...">
                            <span class="input-group-addon search-btn"><i class="feather icon-search"></i></span>
                        </div>
                    </div>
                </li>
                <li class="p-0 pt-1 d-none">
                    <a href="#" class="px-2 py-2 label label-primary mt-2 sloc">
                        <i class="feather icon-user"></i> Profile
                    </a>
                </li>
                <li class="pt-1">
                    <a href="{{ route('home') }}" class="px-2 py-2 label label-primary mt-2" target="parent">
                        <i class="feather icon-globe"></i> View Site
                    </a>
                </li>
            </ul>
            <ul class="nav-right" style="margin-top: 2px;">
                @php
                    /*<li>
                    <a class="">
                        {{request()->route()->getName()}}
                    </a>
                </li>*/
                @endphp
                <li class="user-profile header-notification">
                    <div class="dropdown-primary dropdown">
                        <div class="dropdown-toggle dropdown-toggle d-flex align-items-center" data-toggle="dropdown">
                            <img src="{{asset('admin-assets/assets/images/profile-64.png')}}" class="img-radius" alt="User-Profile-Image">
                            <span class="d-flex flex-column" style="line-height: 16px;">
                                @auth
                                    <span>{{auth()->user()->first_name .' ' . auth()->user()->last_name}}</span>
                                    <span class="text-muted">{{auth()->user()->role == 'organization' ? auth()->user()->organization->name : auth()->user()->role}}</span>
                                @endauth
                            </span>
                            <i class="feather icon-chevron-down"></i>
                        </div>
                        <ul class="show-notification profile-notification dropdown-menu" data-dropdown-in="fadeIn"
                            data-dropdown-out="fadeOut">
                            <li>
                              <a href="~/Home/Logout" class="sloc">
                                  <i class="feather icon-log-out"></i> Logout
                              </a>
                            </li>
                        </ul>

                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>
