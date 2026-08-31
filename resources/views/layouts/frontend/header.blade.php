@php
    $email = lms_setting('email');
    $phone = lms_setting('phone');
@endphp
<!-- header style one -->
<header class="header-one header--sticky">
    <div class="header-top-one-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="header-top-one py-2">
                        <div class="left-information">
                            <a href="mailto:{{$email}}" class="email"><i class="fa-light fa-envelope"></i> {{$email}}</a>
                            <a href="tel:{{$phone}}" class="email text-white small"><i class="fa-light fa-phone"></i> {{$phone}}</a>
                        </div>
                        <div class="right-information">
							<ul class="header-social-links">

								@if (lms_setting('social_facebook'))
									<li>
										<a href="{{ lms_setting('social_facebook') }}" target="_blank" rel="noopener">
											<i class="fa-brands fa-facebook-f"></i>
										</a>
									</li>
								@endif

								@if (lms_setting('social_twitter'))
									<li>
										<a href="{{ lms_setting('social_twitter') }}" target="_blank" rel="noopener">
											<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 256 256">
												<path d="M215,219.85a8,8,0,0,1-7,4.15H160a8,8,0,0,1-6.75-3.71l-40.49-63.63L53.92,221.38a8,8,0,0,1-11.84-10.76l61.77-68L41.25,44.3A8,8,0,0,1,48,32H96a8,8,0,0,1,6.75,3.71l40.49,63.63,58.84-64.72a8,8,0,0,1,11.84,10.76l-61.77,67.95,62.6,98.38A8,8,0,0,1,215,219.85Z"></path>
											</svg>
										</a>
									</li>
								@endif

								@if (lms_setting('social_instagram'))
									<li>
										<a href="{{ lms_setting('social_instagram') }}" target="_blank" rel="noopener">
											<i class="fa-brands fa-instagram"></i>
										</a>
									</li>
								@endif

								@if (lms_setting('social_linkedin'))
									<li>
										<a href="{{ lms_setting('social_linkedin') }}" target="_blank" rel="noopener">
											<i class="fa-brands fa-linkedin-in"></i>
										</a>
									</li>
								@endif

								@if (lms_setting('social_pinterest'))
									<li>
										<a href="{{ lms_setting('social_pinterest') }}" target="_blank" rel="noopener">
											<i class="fa-brands fa-pinterest-p"></i>
										</a>
									</li>
								@endif

								@if (lms_setting('social_youtube'))
									<li>
										<a href="{{ lms_setting('social_youtube') }}" target="_blank" rel="noopener">
											<i class="fa-brands fa-youtube"></i>
										</a>
									</li>
								@endif

							</ul>
						</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="header-one-wrapper">
                    <div class="left-side-header">
                        <a href="{{url('')}}" class="logo-area">
                            <img src="{{ asset('assets/images/logo-new.png') }}"" alt="logo">
                        </a>
                        <div class="category-area d-flex gap-4">
                            <div class="category-btn category-course-btn">
                                @php /* <img src="{{ asset('assets/images/header/grid.png') }}"" alt="grid"> */ @endphp
                                <i class="fa-regular fa-notebook"></i>
                                <span>Course</span>
                                <i class="fa-sharp fa-regular fa-chevron-down"></i>
                                <ul class="category-sub-menu category-course-sub-menu">
                                    <li>
                                        <div class="row w-100">
                                            @if($global_course_categories->isEmpty())
                                                <div class="mb-0 text-center py-5"><i class="fa-regular fa-info-circle fa-3x text-primary mb-2"></i><br>No course categories to be displayed</div>
                                            @else
                                                @foreach ($global_course_categories as $item)
                                                    <div class="col-lg-12">
                                                        <a href="{{ route('courses') }}?search=true&query=&category[]={{$item->id}}" class="d-flex header-categories">
                                                            <img src="{{lms_image($item->thumbnail)}}" alt="">
                                                            <div class="d-flex flex-column">
                                                                <div class="text-muted mb-0 title">{{$item->title}}</div>
                                                                <div class="text-muted small">{{$item->courses_count}} {{lms_plural('course', $item->courses_count)}}</div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            {{-- <div class="category-btn category-exams-btn">
                                <i class="fa-regular fa-pencil-alt"></i>
                                <span>Exam</span>
                                <i class="fa-sharp fa-regular fa-chevron-down"></i>
                                <ul class="category-sub-menu category-exams-sub-menu">
                                    <li>
                                        <div class="row w-100">
                                            @if($global_exam_categories->isEmpty())
                                                <div class="mb-0 text-center py-5"><i class="fa-regular fa-info-circle fa-3x text-primary mb-2"></i><br>No exam categories to be displayed</div>
                                            @else
                                                @foreach ($global_exam_categories as $item)
                                                    <div class="col-lg-12">
                                                        <a href="{{ route('exams') }}?search=true&query=&category[]={{$item->id}}" class="d-flex header-categories">
                                                            <img src="{{lms_image($item->thumbnail)}}" alt="">
                                                            <div class="d-flex flex-column">
                                                                <div class="text-muted mb-0 title">{{$item->title}}</div>
                                                                <div class="text-muted small">{{$item->exams_count}} {{lms_plural('exam', $item->exams_count)}}</div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </li>
                                </ul>
                            </div> --}}
                        </div>
                    </div>

                    <div class="main-nav-one">
                        <nav>
                            <ul>
                                <li class="nav-menu-home">
                                    <a class="nav-link {{ Request::is('/') ? 'active' : '' }}" href="{{ url('') }}">Home</a>
                                </li>
                                <li class="nav-menu-courses">
                                    <a class="nav-link {{ Request::is('courses') ? 'active' : '' }}" href="{{ route('courses') }}">Courses</a>
                                </li>
                                {{-- <li class="nav-menu-exams">
                                    <a class="nav-link {{ Request::is('exams') ? 'active' : '' }}" href="{{ route('exams') }}">Exams</a>
                                </li>
                                <li class="nav-menu-recorded-videos">
                                    <a class="nav-link {{ Request::is('recorded-videos') ? 'active' : '' }}" href="{{ route('recorded-videos') }}">Videos</a>
                                </li>
                                <li class="nav-menu-job-posts">
                                    <a class="nav-link {{ Request::is('job-posts') ? 'active' : '' }}" href="{{ route('job-posts') }}">Jobs</a>
                                </li> --}}
                                <li class="nav-menu-about">
                                    <a class="nav-link {{ Request::is('gallery') ? 'active' : '' }}" href="{{ route('gallery') }}">Gallery</a>
                                </li>
                                <li class="nav-menu-faq">
                                    <a class="nav-link {{ request()->routeIs('faq.*') ? 'active' : '' }}"
                                    href="{{ route('faq.index') }}">
                                        FAQ
                                    </a>
                                </li>
                                <li class="nav-menu-about">
                                    <a class="nav-link {{ Request::is('about-us') ? 'active' : '' }}" href="{{ route('about-us') }}">About us</a>
                                </li>
                                <li class="nav-menu-contact">
                                    <a class="nav-link {{ Request::is('contact-us') ? 'active' : '' }}" href="{{ route('contact-us') }}">Contact us</a>
                                </li>
                            </ul>
                        </nav>
                    </div>

                    <div class="main-nav-one">
                    </div>
                    <div class="header-right-area-one">
                        {{-- <div class="actions-area">
                            <div class="search-btn" id="search">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22" fill="none">
                                    <path d="M19.9375 18.9652L14.7454 13.7732C15.993 12.2753 16.6152 10.3542 16.4824 8.40936C16.3497 6.46453 15.4722 4.64575 14.0326 3.33139C12.593 2.01702 10.7021 1.30826 8.75326 1.35254C6.8044 1.39683 4.94764 2.19075 3.56924 3.56916C2.19083 4.94756 1.39691 6.80432 1.35263 8.75317C1.30834 10.702 2.0171 12.5929 3.33147 14.0325C4.64584 15.4721 6.46461 16.3496 8.40944 16.4823C10.3543 16.6151 12.2754 15.993 13.7732 14.7453L18.9653 19.9374L19.9375 18.9652ZM2.75 8.93742C2.75 7.71365 3.11289 6.51736 3.79278 5.49983C4.47267 4.4823 5.43903 3.68923 6.56965 3.22091C7.70026 2.7526 8.94436 2.63006 10.1446 2.86881C11.3449 3.10756 12.4474 3.69686 13.3127 4.56219C14.1781 5.42753 14.7674 6.53004 15.0061 7.7303C15.2449 8.93055 15.1223 10.1747 14.654 11.3053C14.1857 12.4359 13.3926 13.4022 12.3751 14.0821C11.3576 14.762 10.1613 15.1249 8.9375 15.1249C7.29703 15.1231 5.72427 14.4706 4.56429 13.3106C3.4043 12.1506 2.75182 10.5779 2.75 8.93742Z" fill="#553CDF" />
                                </svg>
                            </div>
                            <div class="cart cart-icon">
                                <i data-href="{{ route('my-cart') }}" class="cursor-pointer fa-regular fa-cart-shopping" title="{{$global_cart_count}}"></i>
                            </div>
                        </div> --}}
                        <div class="buttons-area">
                            @auth
                                <div class="dropdown">
                                    <button class="dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                    Hi, {{auth()->user()->first_name}}
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        @if (auth()->user()->isAdmin() || auth()->user()->isOrganization())
                                            <li><a class="dropdown-item" href="{{route('admin.dashboard')}}">Dashboard</a></li>
                                        @else
                                            <li><a class="dropdown-item" href="{{route('dashboard')}}">Dashboard</a></li>
                                        @endif
                                        <li><a class="dropdown-item" href="{{route('my-profile')}}">Profile</a></li>
                                        <li><a class="dropdown-item" href="{{route('log.out')}}">Logout</a></li>
                                    </ul>
                                </div>
                            @else
                                <a href="{{route('login')}}" class="rts-btn btn-border">Log In</a>
                                <a href="{{route('register')}}" class="rts-btn btn-primary">Sign Up</a>
                            @endauth
                        </div>
                        <div class="menu-btn" id="menu-btn">
                            <svg width="20" height="16" viewBox="0 0 20 16" fill="none" xmlns="http://www.w3.org/2000/svg">
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
</header>
<!-- header style end -->