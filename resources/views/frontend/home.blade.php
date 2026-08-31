@extends('layouts.frontend.skeleton')

@php
    $title = 'First Fly Aviation Academy | Best Aviation Institute in Chennai';
    $description = 'First Fly Aviation Academy | Best Aviation Institute in Chennai';
    $appName = lms_setting('app_name');
@endphp
@section('title', $title)

@section('meta-data')
    <meta name="title" content="{{ $title }}">
    <meta name="description" content="{{ $description }}">
    <meta name="keywords"
        content="Aviation Academy Chennai,Air Hostess Course Chennai,Cabin Crew Training Chennai,Airport Ground Staff Course,Airport Operations Training,Pilot Ground School Chennai,Aviation Institute Tamil Nadu,Aviation Courses Chennai,Travel and Tourism Course Chennai,Drone Training Chennai">

    <!-- Open Graph (OG) Tags for Social Media -->
    <meta property="og:title" content="{{ $title }}">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:image" content="{{ asset('assets/images/home-og-image.jpg') }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">

    <!-- Twitter Card (Optional for Twitter Sharing) -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $title }}">
    <meta name="twitter:description" content="{{ $description }}">
    <meta name="twitter:image" content="{{ asset('assets/images/home-og-image.jpg') }}">
@endsection

@section('content')
    <!-- banner area start -->
    <!-- banner area start -->
    <div class="banner-area-one shape-move">
        <div class="container">
            <div class="row align-items-center">

                <div class="col-lg-6 order-xl-1 order-lg-1 order-sm-2 order-2">

                    <div class="banner-content-one">

                        <div class="inner">

                            <div class="pre-title-banner text-center text-md-start">
                                <img src="{{ asset('assets/images/banner/bulb.png') }}" width="22" alt="icon">
                                <span>Admissions Open for 2026 Batch</span>
                            </div>

                            <h1 class="title-banner text-center text-md-start">
                                Launch Your Aviation Career with
                                <span>First Fly Aviation Academy</span>
                            </h1>

                            <h4 class="mb-4 text-site-primary text-center text-md-start">
                                Wings to Future ✈️
                            </h4>

                            <p class="disc mb-4">
                                Become a Cabin Crew, Air Hostess, Pilot, Airport Operations Executive, Ground Staff
                                Professional, Drone Pilot, Travel Consultant, or Aviation Specialist through
                                industry-focused training and placement support.
                            </p>

                            <div class="row g-3 mb-4">

                                <div class="col-md-6">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-check-circle text-site-primary me-2"></i>
                                        <span>Industry Expert Trainers</span>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-check-circle text-site-primary me-2"></i>
                                        <span>Airport Exposure Visits</span>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-check-circle text-site-primary me-2"></i>
                                        <span>Interview Preparation</span>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-check-circle text-site-primary me-2"></i>
                                        <span>Certification Programs</span>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-check-circle text-site-primary me-2"></i>
                                        <span>Placement Assistance</span>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-check-circle text-site-primary me-2"></i>
                                        <span>Practical Aviation Training</span>
                                    </div>
                                </div>

                            </div>

                            <div class="banner-btn-author-wrapper align-items-center align-items-md-start">

                                <a href="{{ route('contact-us') }}" class="rts-btn btn-primary with-arrow">
                                    Apply Now
                                    <i class="fa-regular fa-arrow-right"></i>
                                </a>

                                <a href="javascript:;" class="rts-btn btn-border" data-bs-toggle="modal"
                                    data-bs-target="#brochureModal">
                                    Download Brochure
                                </a>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="col-lg-6 order-xl-2 order-lg-2 order-sm-1 order-1">

                    <div class="banner-right-img text-center">

                        <img src="{{ asset('assets/images/home-banner-3.png') }}" alt="First Fly Aviation Academy Chennai">

                    </div>

                </div>

            </div>
        </div>

        <div class="shape-image">
            <div class="shape one aviation-shape" data-speed="0.04" data-revert="true">
                <i class="fa-light fa-plane fa-3x text-site-primary opacity-25"></i>
            </div>

            <div class="shape two aviation-shape" data-speed="0.04">
                <i class="fa-light fa-plane-departure fa-3x text-site-primary opacity-25"></i>
            </div>

            <div class="shape three aviation-shape" data-speed="0.04">
                <i class="fa-light fa-tower-control fa-3x text-site-primary opacity-25"></i>
            </div>
        </div>

        <div class="hero-plane">
            <i class="fa-light fa-plane"></i>
        </div>

        <div class="hero-plane-2">
            <i class="fa-light fa-plane"></i>
        </div>

    </div>
    <!-- banner area end -->

    <!-- fun facts area start -->
    <div class="aviation-route-section ptb--100 position-relative overflow-hidden">

        <div class="container">

            <div class="text-center mb-5">

                <div class="pre-title-banner justify-content-center mb-3">
                    <i class="fa-light fa-plane-departure text-site-primary me-2"></i>
                    <span>First Fly Impact</span>
                </div>

                <h2 class="mb-3">
                    Your Journey to the Aviation Industry Starts Here
                </h2>

                <p class="mb-0">
                    Industry-focused training, practical exposure, and placement support to help students build successful
                    aviation careers.
                </p>

            </div>

            <div class="route-wrapper p-4 p-md-5">

                <div class="route-line"></div>

                <div class="route-plane">
                    <i class="fa-light fa-plane"></i>
                </div>

                <div class="row g-4">

                    <div class="col-lg-3 col-md-6 col-6">

                        <div class="route-stop">

                            <div class="airport-code">MAA</div>

                            <div class="route-dot"></div>

                            <h3><span class="counter">5000</span>+</h3>

                            <p>Students Trained</p>

                        </div>

                    </div>

                    <div class="col-lg-3 col-md-6 col-6">

                        <div class="route-stop">

                            <div class="airport-code">DXB</div>

                            <div class="route-dot"></div>

                            <h3><span class="counter">50</span>+</h3>

                            <p>Hiring Partners</p>

                        </div>

                    </div>

                    <div class="col-lg-3 col-md-6 col-6">

                        <div class="route-stop">

                            <div class="airport-code">SIN</div>

                            <div class="route-dot"></div>

                            <h3>100%</h3>

                            <p>Placement Assistance</p>

                        </div>

                    </div>

                    <div class="col-lg-3 col-md-6 col-6">

                        <div class="route-stop">

                            <div class="airport-code">LHR</div>

                            <div class="route-dot"></div>

                            <h3><span class="counter">15</span>+</h3>

                            <p>Aviation Programs</p>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>
    <!-- fun facts area end -->

    <!-- about area start -->
    <div class="about-area-start rts-section-gap">
        <div class="container">

            <div class="row align-items-center">

                <div class="col-lg-6">

                    <div class="about-one-left-image">

                        <div class="second-order">
                            <img src="{{ asset('assets/images/about-us-why-choose.jpg') }}"
                                alt="First Fly Aviation Academy Chennai">
                        </div>

                    </div>

                </div>

                <div class="col-lg-6 ps-md-5">

                    <div class="title-area-left-style mt-md-0 mt-5">

                        <div class="pre-title">
                            <img src="{{ asset('assets/images/banner/bulb.png') }}" alt="icon">
                            <span>Why First Fly Aviation Academy?</span>
                        </div>

                        <h2 class="title">
                            Building Industry-Ready Aviation Professionals
                        </h2>

                        <p class="post-title mb-4">
                            At First Fly Aviation Academy, we prepare students for successful careers in the aviation
                            industry through practical learning, industry exposure, and professional development.
                        </p>
                        <p>
                            Our programs are designed to bridge the gap between education and employment, helping students
                            become industry-ready professionals.
                        </p>

                    </div>

                    <div class="row mt--30">

                        <div class="col-md-6 mb-4">
                            <div class="single-facilityes">
                                <div class="information">
                                    <h6 class="title mb-0 fw-medium">
                                        <i class="fas fa-check-circle text-site-primary me-2"></i>
                                        Aviation Industry Experts
                                    </h6>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="single-facilityes">
                                <div class="information">
                                    <h6 class="title mb-0 fw-medium">
                                        <i class="fas fa-check-circle text-site-primary me-2"></i>
                                        Practical Skill Development
                                    </h6>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="single-facilityes">
                                <div class="information">
                                    <h6 class="title mb-0 fw-medium">
                                        <i class="fas fa-check-circle text-site-primary me-2"></i>
                                        Grooming & Personality Training
                                    </h6>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="single-facilityes">
                                <div class="information">
                                    <h6 class="title mb-0 fw-medium">
                                        <i class="fas fa-check-circle text-site-primary me-2"></i>
                                        Communication & Aviation English
                                    </h6>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="single-facilityes">
                                <div class="information">
                                    <h6 class="title mb-0 fw-medium">
                                        <i class="fas fa-check-circle text-site-primary me-2"></i>
                                        Airport Familiarization Programs
                                    </h6>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="single-facilityes">
                                <div class="information">
                                    <h6 class="title mb-0 fw-medium">
                                        <i class="fas fa-check-circle text-site-primary me-2"></i>
                                        Career Guidance & Counseling
                                    </h6>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 mb-4">
                            <div class="single-facilityes">
                                <div class="information">
                                    <h6 class="title mb-0 fw-medium">
                                        <i class="fas fa-check-circle text-site-primary me-2"></i>
                                        Mock Interviews & Placement Support
                                    </h6>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="mt--20">
                        <a href="{{ route('about-us') }}" class="rts-btn btn-primary">
                            Learn More About Us
                        </a>
                    </div>

                </div>

            </div>

        </div>
    </div>
    <!-- about area end -->

    <!-- why choose us section area start -->
    <div class="why-choose-us bg-blue bg-choose-us-one bg_image rts-section-gap shape-move">

        <div class="container">

            <div class="row align-items-center">

                <div class="col-lg-6">

                    <div class="why-choose-us-area-image">

                        <img class="rounded-5"
                            src="{{ asset('assets/images/gallery/airport-exposure/airport-exposure.png') }}"
                            alt="Airport Exposure Program">

                    </div>

                </div>

                <div class="col-lg-6 ps-md-5 pl_md--15 pl_sm--15 pt_md--50 pt_sm--50">

                    <div class="title-area-left-style">

                        <div class="pre-title">
                            <img src="{{ asset('assets/images/banner/bulb-2.png') }}" alt="icon">
                            <span>Why Students Choose Us</span>
                        </div>

                        <h2 class="title text-white">
                            Practical Aviation Training That Creates Career Opportunities
                        </h2>

                        <p class="post-title text-white">
                            First Fly Aviation Academy combines industry-focused training, airport exposure, professional
                            development, and placement support to prepare students for successful aviation careers.
                        </p>

                    </div>

                    <div class="row mt--30">

                        <div class="col-md-6 mb-4">
                            <div class="single-choose-reason-1">
                                <div class="icon">
                                    <i class="fas fa-user-tie text-site-primary4"></i>
                                </div>
                                <h6 class="title text-white">
                                    Industry Expert Trainers
                                </h6>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="single-choose-reason-1">
                                <div class="icon">
                                    <i class="fas fa-plane-departure text-site-primary4"></i>
                                </div>
                                <h6 class="title text-white">
                                    Airport Exposure Visits
                                </h6>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="single-choose-reason-1">
                                <div class="icon">
                                    <i class="fas fa-comments text-site-primary4"></i>
                                </div>
                                <h6 class="title text-white">
                                    Aviation English Training
                                </h6>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="single-choose-reason-1">
                                <div class="icon">
                                    <i class="fas fa-user-check text-site-primary4"></i>
                                </div>
                                <h6 class="title text-white">
                                    Interview Preparation
                                </h6>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="single-choose-reason-1">
                                <div class="icon">
                                    <i class="fas fa-certificate text-site-primary4"></i>
                                </div>
                                <h6 class="title text-white">
                                    Certification Programs
                                </h6>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="single-choose-reason-1">
                                <div class="icon">
                                    <i class="fas fa-briefcase text-site-primary4"></i>
                                </div>
                                <h6 class="title text-white">
                                    Placement Assistance
                                </h6>
                            </div>
                        </div>

                    </div>

                    <a href="{{ route('contact-us') }}" class="rts-btn btn-primary-white with-arrow mt-3">
                        Get Free Career Guidance
                        <i class="fa-regular fa-arrow-right"></i>
                    </a>

                </div>

            </div>

        </div>

    </div>
    <!-- why choose us section area end -->

    <!-- course area start -->
    <div class="course-area-start rts-section-gap">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="title-between-area">
                        <div class="title-area-left-style">
                            <div class="pre-title">
                                <img src="{{ asset('assets/images/banner/bulb.png') }}" alt="icon">
                                <span>Courses</span>
                            </div>
                            <h2 class="title">Aviation Courses We Offer</h2>
                            <p class="post-title">Build industry-ready skills through professional aviation, airport,
                                airline, travel, tourism, and drone training programs.</p>
                        </div>
                        {{-- <div class="button-group filters-button-group">
                            <button class="button is-checked" data-filter="*">All Categories</button>
                            @foreach ($categories_n_courses as $item)
                                <button class="button"
                                    data-filter=".category_{{ $item->id }}">{{ $item->title }}</button>
                            @endforeach
                        </div> --}}
                    </div>
                </div>
            </div>
            <div class="ms-portfolio-filter-area main-isotop">
                <div class="portfolio_wrap">
                    <div class="filter row g-5 mt--20 portfolio-feed personal">

                        @foreach ($categories_n_courses as $category_item)
                            @foreach ($category_item->courses as $course_item)
                                <div class="flash grid-item-p element-item transition category_{{ $category_item->id }} col-xl-3 col-lg-4 col-md-6 col-sm-6"
                                    data-category="transition">
                                    <!-- rts single course -->
                                    <div class="rts-single-course">
                                        <a href="{{ lms_course_slug($course_item) }}" class="thumbnail">
                                            <img src="{{ lms_image($course_item->thumbnail) }}" alt="course">
                                        </a>
                                        <div class="save-icon d-none" data-bs-toggle="modal"
                                            data-bs-target="#exampleModal-login">
                                            <i class="fa-sharp fa-light fa-bookmark"></i>
                                        </div>
                                        <div class="tags-area-wrapper mb-3">
                                            <div class="single-tag">
                                                <span>{{ $category_item->title }}</span>
                                            </div>
                                        </div>
                                        {{-- <div class="lesson-studente">
                                            <div class="lesson">
                                                <i class="fa-light fa-calendar-lines-pen"></i>
                                                <span>{{ $course_item->lesson_count }}
                                                    {{ lms_plural('Lesson', $course_item->lesson_count) }}</span>
                                            </div>
                                            <div class="lesson">
                                                <i class="fa-light fa-users"></i>
                                                <span>{{ $course_item->enroll_count }} Enrolled</span>
                                            </div>
                                        </div> --}}
                                        <a href="{{ lms_course_slug($course_item) }}">
                                            <h5 class="title">{{ $course_item->title }}</h5>
                                        </a>

                                        <div class="rating-and-price mt-2">
                                            {{-- <div class="price-area">
                                                <div class="price">
                                                    @if ($course_item->discount_flag)
                                                        <span class="title">{!! lms_show_price($course_item, 'discounted_price') !!}</span>
                                                        <span
                                                            class="text-muted small text-decoration-line-through">{!! lms_show_price($course_item) !!}</span>
                                                    @else
                                                        <span class="title">{!! lms_show_price($course_item) !!}</span>
                                                    @endif
                                                </div>
                                            </div> --}}
                                            <div class="rating-area">
                                                <div class="stars">
                                                    <span
                                                        class="text-dark fw-bold">{{ lms_decimal_points($course_item->ratings) }}</span>

                                                    <div class="stars-area">
                                                        <div class="stars-background">
                                                            {!! str_repeat('<i class="fa-regular fa-star"></i>', 5) !!}
                                                        </div>
                                                        <div class="stars-overlay"
                                                            style="width: {{ lms_rating_to_percentage($course_item->ratings) }}%;">
                                                            {!! str_repeat('<i class="fa fa-star"></i>', 5) !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- rts single course end -->
                                </div>
                            @endforeach
                        @endforeach



                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- course area end -->



    <!-- career pathway area start -->
    <div class="rts-section-gap bg-light-1">

        <div class="container">

            <div class="row justify-content-center">

                <div class="col-lg-8">

                    <div class="title-area-center-style">

                        <div class="pre-title">
                            <img src="{{ asset('assets/images/banner/bulb.png') }}" alt="icon">
                            <span>Your Aviation Journey</span>
                        </div>

                        <h2 class="title">
                            Your Aviation Journey Starts Here
                        </h2>

                        <p class="post-title">
                            From classroom learning to industry readiness, our structured training pathway helps students
                            build confidence, gain practical exposure, and prepare for successful aviation careers.
                        </p>

                    </div>

                </div>

            </div>

            <div class="row g-4 mt--20 justify-content-center">

                <div class="col-lg col-md-4 col-6">
                    <div class="journey-card text-center">
                        <i class="fa-light fa-user-graduate journey-bg-icon" style="animation-delay:0s"></i>
                        <div class="journey-number">01</div>
                        <h5>Enroll</h5>
                        <p>Choose your aviation career path.</p>
                    </div>
                </div>

                <div class="col-lg col-md-4 col-6">
                    <div class="journey-card text-center">
                        <i class="fa-light fa-book-open-reader journey-bg-icon" style="animation-delay:1s"></i>
                        <div class="journey-number">02</div>
                        <h5>Learn</h5>
                        <p>Industry-focused classroom training.</p>
                    </div>
                </div>

                <div class="col-lg col-md-4 col-6">
                    <div class="journey-card text-center">
                        <i class="fa-light fa-plane-engines journey-bg-icon" style="animation-delay:2s"></i>
                        <div class="journey-number">03</div>
                        <h5>Practice</h5>
                        <p>Hands-on practical aviation training.</p>
                    </div>
                </div>

                <div class="col-lg col-md-4 col-6">
                    <div class="journey-card text-center">
                        <i class="fa-light fa-plane-departure journey-bg-icon" style="animation-delay:3s"></i>
                        <div class="journey-number">04</div>
                        <h5>Exposure</h5>
                        <p>Airport visits and industry interaction.</p>
                    </div>
                </div>

                <div class="col-lg col-md-4 col-6">
                    <div class="journey-card text-center">
                        <i class="fa-light fa-user-tie journey-bg-icon" style="animation-delay:4s"></i>
                        <div class="journey-number">05</div>
                        <h5>Prepare</h5>
                        <p>Interview coaching and career guidance.</p>
                    </div>
                </div>

                <div class="col-lg col-md-4 col-6">
                    <div class="journey-card text-center">
                        <i class="fa-light fa-rocket-launch journey-bg-icon" style="animation-delay:5s"></i>
                        <div class="journey-number">06</div>
                        <h5>Launch</h5>
                        <p>Start your aviation career journey.</p>
                    </div>
                </div>

            </div>

        </div>

    </div>
    <!-- career pathway area end -->

    <div class="rts-section-gap">
        <div class="container">

            <div class="row align-items-center">

                <div class="col-lg-6">

                    <img src="{{ asset('assets/images/career-opportunities.jpg') }}" class="img-fluid rounded-3 shadow"
                        alt="Aviation Career Opportunities">

                </div>

                <div class="col-lg-6 ps-lg-5 mt_md--50 mt_sm--50">

                    <div class="title-area-left-style">

                        <div class="pre-title">
                            <img src="{{ asset('assets/images/banner/bulb.png') }}" alt="icon">
                            <span>Career Opportunities</span>
                        </div>

                        <h2 class="title">
                            Turn Your Aviation Training Into a Successful Career
                        </h2>

                        <p class="post-title">
                            After completing our aviation courses, students can pursue rewarding careers in airlines,
                            airports, aviation services, cargo operations, travel companies, and emerging aviation sectors.
                        </p>

                    </div>

                    <div class="row mt--30">

                        <div class="col-md-6 mb-3">
                            <div class="career-item"><span class="text-site-primary">✈</span> Cabin Crew</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="career-item"><span class="text-site-primary">✈</span> Air Hostess</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="career-item"><span class="text-site-primary">✈</span> Flight Steward</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="career-item"><span class="text-site-primary">✈</span> Ground Staff</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="career-item"><span class="text-site-primary">✈</span> Airport Operations Executive
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="career-item"><span class="text-site-primary">✈</span> Passenger Service Agent
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="career-item"><span class="text-site-primary">✈</span> Cargo Executive</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="career-item"><span class="text-site-primary">✈</span> Travel Consultant</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="career-item"><span class="text-site-primary">✈</span> Drone Pilot</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="career-item"><span class="text-site-primary">✈</span> Airline Customer Service
                                Executive</div>
                        </div>

                    </div>

                    <a href="{{ route('contact-us') }}" class="rts-btn btn-primary mt-4">
                        Get Free Career Guidance
                    </a>

                </div>

            </div>

        </div>
    </div>

    <!-- feedback area start -->
    <div class="rts-feedback-area rts-section-gap bg-site-primary4 shape-move">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="title-area-center-style">
                        <div class="pre-title">
                            <img src="{{ asset('assets/images/banner/bulb.png') }}" alt="icon">
                            <span>Student Success Stories</span>
                        </div>
                        <h2 class="title">Hear From Our Successful Students</h2>
                    </div>
                </div>
            </div>
            <div class="row mt--50">
                <div class="col-lg-12">
                    <div class="students-feedback-wrapper-1 bg_image">
                        <div class="swiper mySwiper-testimonials-1">
                            <div class="swiper-wrapper">

                                <div class="swiper-slide">
                                    <div class="single-students-feedback">
                                        <div class="right-content">
                                            <p class="mb-2 text-site-primary">Customer Service AgentProgram</p>
                                            <p class="disc">
                                                {{ $appName }} taught me how to handle customers professionally and
                                                confidently. The training environment was friendly and motivating. The
                                                practical sessions and expert guidance helped me improve my communication
                                                skills and prepared me for real-world customer interactions in the aviation
                                                industry.
                                            </p>
                                            <div class="author-area">
                                                <div class="d-flex gap-5 align-items-center">
                                                    <img src="{{ asset('assets/images/students/aishwarya.png') }}"
                                                        class="rounded-circle" alt="Aishwarya G">
                                                    <div>
                                                        <ul class="stars">
                                                            <li><i class="fa-sharp fa-solid fa-star"></i></li>
                                                            <li><i class="fa-sharp fa-solid fa-star"></i></li>
                                                            <li><i class="fa-sharp fa-solid fa-star"></i></li>
                                                            <li><i class="fa-sharp fa-solid fa-star"></i></li>
                                                            <li><i class="fa-sharp fa-solid fa-star"></i></li>
                                                        </ul>
                                                        <h5 class="title">Aishwarya G.</h5>
                                                        <h6 class="text-white fw-normal">Chennai | Class of 2025.</h6>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="swiper-slide">
                                    <div class="single-students-feedback">
                                        <div class="right-content">
                                            <p class="mb-2 text-site-primary">Drone Training</p>
                                            <p class="disc">
                                                The drone training program provided excellent hands-on experience. Learning
                                                about DGCA regulations and practical flying operations was very exciting.
                                                The instructors ensured that every concept was explained clearly, making the
                                                training highly engaging and valuable for aspiring drone professionals.
                                            </p>
                                            <div class="author-area">
                                                <div class="d-flex gap-5 align-items-center">
                                                    <img src="{{ asset('assets/images/students/vishal.png') }}"
                                                        class="rounded-circle" alt="Vishal S">
                                                    <div>
                                                        <ul class="stars">
                                                            <li><i class="fa-sharp fa-solid fa-star"></i></li>
                                                            <li><i class="fa-sharp fa-solid fa-star"></i></li>
                                                            <li><i class="fa-sharp fa-solid fa-star"></i></li>
                                                            <li><i class="fa-sharp fa-solid fa-star"></i></li>
                                                            <li><i class="fa-sharp fa-solid fa-star"></i></li>
                                                        </ul>
                                                        <h5 class="title">Vishal S.</h5>
                                                        <h6 class="text-white fw-normal">Bengaluru | Class of 2025.</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="swiper-slide">
                                    <div class="single-students-feedback">
                                        <div class="right-content">
                                            <p class="mb-2 text-site-primary">Travel & Tourism</p>
                                            <p class="disc">
                                                The trainers shared valuable industry knowledge and practical tips. The
                                                course prepared me well for a career in tourism and travel management. I
                                                gained confidence in handling travel operations, reservations, and customer
                                                interactions effectively.
                                            </p>
                                            <div class="author-area">
                                                <div class="d-flex gap-5 align-items-center">
                                                    <img src="{{ asset('assets/images/students/monika.png') }}"
                                                        class="rounded-circle" alt="Monika R">
                                                    <div>
                                                        <ul class="stars">
                                                            <li><i class="fa-sharp fa-solid fa-star"></i></li>
                                                            <li><i class="fa-sharp fa-solid fa-star"></i></li>
                                                            <li><i class="fa-sharp fa-solid fa-star"></i></li>
                                                            <li><i class="fa-sharp fa-solid fa-star"></i></li>
                                                            <li><i class="fa-sharp fa-solid fa-star"></i></li>
                                                        </ul>
                                                        <h5 class="title">Monika R.</h5>
                                                        <h6 class="text-white fw-normal">Coimbatore | Class of 2024.</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="swiper-slide">
                                    <div class="single-students-feedback">
                                        <div class="right-content">
                                            <p class="mb-2 text-site-primary">RTR (Aero) Program</p>
                                            <p class="disc">
                                                The RTR preparation classes helped me improve my aviation communication
                                                skills and understand standard ATC phraseology. The structured approach and
                                                experienced faculty played a significant role in improving my confidence and
                                                technical knowledge.
                                            </p>
                                            <div class="author-area">
                                                <div class="d-flex gap-5 align-items-center">
                                                    <img src="{{ asset('assets/images/students/suresh.png') }}"
                                                        class="rounded-circle" alt="Suresh K">
                                                    <div>
                                                        <ul class="stars">
                                                            <li><i class="fa-sharp fa-solid fa-star"></i></li>
                                                            <li><i class="fa-sharp fa-solid fa-star"></i></li>
                                                            <li><i class="fa-sharp fa-solid fa-star"></i></li>
                                                            <li><i class="fa-sharp fa-solid fa-star"></i></li>
                                                            <li><i class="fa-sharp fa-solid fa-star"></i></li>
                                                        </ul>
                                                        <h5 class="title">Suresh K.</h5>
                                                        <h6 class="text-white fw-normal">Hyderabad | Class of 2025.</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="swiper-slide">
                                    <div class="single-students-feedback">
                                        <div class="right-content">
                                            <p class="mb-2 text-site-primary">Cabin Crew / Air Hostess</p>
                                            <p class="disc">
                                                I appreciated the academy’s focus on discipline, grooming, and
                                                communication.
                                                These skills are essential for success in the aviation industry. The
                                                trainers
                                                provided continuous support and practical guidance that helped me become
                                                more
                                                confident and professional.
                                            </p>
                                            <div class="author-area">
                                                <div class="d-flex gap-5 align-items-center">
                                                    <img src="{{ asset('assets/images/students/janani.png') }}"
                                                        class="rounded-circle" alt="Janani P">
                                                    <div>
                                                        <ul class="stars">
                                                            <li><i class="fa-sharp fa-solid fa-star"></i></li>
                                                            <li><i class="fa-sharp fa-solid fa-star"></i></li>
                                                            <li><i class="fa-sharp fa-solid fa-star"></i></li>
                                                            <li><i class="fa-sharp fa-solid fa-star"></i></li>
                                                            <li><i class="fa-sharp fa-solid fa-star"></i></li>
                                                        </ul>
                                                        <h5 class="title">Janani P.</h5>
                                                        <h6 class="text-white fw-normal">Kochi | Class of 2025.</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="swiper-slide">
                                    <div class="single-students-feedback">
                                        <div class="right-content">
                                            <p class="mb-2 text-site-primary">Airport Operations</p>
                                            <p class="disc">
                                                The airport familiarization visit gave me practical exposure and helped me
                                                connect classroom learning with real airport operations. The program offered
                                                valuable insights into airport management, passenger handling, and
                                                operational procedures.
                                            </p>
                                            <div class="author-area">
                                                <div class="d-flex gap-5 align-items-center">
                                                    <img src="{{ asset('assets/images/students/harish.png') }}"
                                                        class="rounded-circle" alt="Harish M">
                                                    <div>
                                                        <ul class="stars">
                                                            <li><i class="fa-sharp fa-solid fa-star"></i></li>
                                                            <li><i class="fa-sharp fa-solid fa-star"></i></li>
                                                            <li><i class="fa-sharp fa-solid fa-star"></i></li>
                                                            <li><i class="fa-sharp fa-solid fa-star"></i></li>
                                                            <li><i class="fa-sharp fa-solid fa-star"></i></li>
                                                        </ul>
                                                        <h5 class="title">Harish M.</h5>
                                                        <h6 class="text-white fw-normal">Chennai | Class of 2024.</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="swiper-slide">
                                    <div class="single-students-feedback">
                                        <div class="right-content">
                                            <p class="mb-2 text-site-primary">Aviation English</p>
                                            <p class="disc">
                                                The interactive speaking sessions boosted my confidence. I can now
                                                communicate effectively in professional aviation environments. The training
                                                improved my fluency, pronunciation, and ability to communicate clearly in
                                                aviation-related situations.
                                            </p>
                                            <div class="author-area">
                                                <div class="d-flex gap-5 align-items-center">
                                                    <img src="{{ asset('assets/images/students/sneha.png') }}"
                                                        class="rounded-circle" alt="Sneha V">
                                                    <div>
                                                        <ul class="stars">
                                                            <li><i class="fa-sharp fa-solid fa-star"></i></li>
                                                            <li><i class="fa-sharp fa-solid fa-star"></i></li>
                                                            <li><i class="fa-sharp fa-solid fa-star"></i></li>
                                                            <li><i class="fa-sharp fa-solid fa-star"></i></li>
                                                            <li><i class="fa-sharp fa-solid fa-star"></i></li>
                                                        </ul>
                                                        <h5 class="title">Sneha V.</h5>
                                                        <h6 class="text-white fw-normal">Madurai | Class of 2025.</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="swiper-slide">
                                    <div class="single-students-feedback">
                                        <div class="right-content">
                                            <p class="mb-2 text-site-primary">Interview Preparation Program</p>
                                            <p class="disc">
                                                The mock interviews and resume-building sessions helped me understand
                                                employer expectations and improve my interview performance. The personalized
                                                feedback and guidance significantly improved my confidence during
                                                recruitment processes.
                                            </p>
                                            <div class="author-area">
                                                <div class="d-flex gap-5 align-items-center">
                                                    <img src="{{ asset('assets/images/students/akash.png') }}"
                                                        class="rounded-circle" alt="Akash D">
                                                    <div>
                                                        <ul class="stars">
                                                            <li><i class="fa-sharp fa-solid fa-star"></i></li>
                                                            <li><i class="fa-sharp fa-solid fa-star"></i></li>
                                                            <li><i class="fa-sharp fa-solid fa-star"></i></li>
                                                            <li><i class="fa-sharp fa-solid fa-star"></i></li>
                                                            <li><i class="fa-sharp fa-solid fa-star"></i></li>
                                                        </ul>
                                                        <h5 class="title">Akash D.</h5>
                                                        <h6 class="text-white fw-normal">Tiruchirappalli | Class of 2025.
                                                        </h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="swiper-slide">
                                    <div class="single-students-feedback">
                                        <div class="right-content">
                                            <p class="mb-2 text-site-primary">Airport Operations</p>
                                            <p class="disc">
                                                The mock interviews and resume-building sessions helped me understand
                                                employer expectations and improve my interview performance. The personalized
                                                feedback and guidance significantly improved my confidence during
                                                recruitment processes.
                                            </p>
                                            <div class="author-area">
                                                <div class="d-flex gap-5 align-items-center">
                                                    <img src="{{ asset('assets/images/students/lavanya.png') }}"
                                                        class="rounded-circle" alt="Lavanya S">
                                                    <div>
                                                        <ul class="stars">
                                                            <li><i class="fa-sharp fa-solid fa-star"></i></li>
                                                            <li><i class="fa-sharp fa-solid fa-star"></i></li>
                                                            <li><i class="fa-sharp fa-solid fa-star"></i></li>
                                                            <li><i class="fa-sharp fa-solid fa-star"></i></li>
                                                            <li><i class="fa-sharp fa-solid fa-star"></i></li>
                                                        </ul>
                                                        <h5 class="title">Lavanya S.</h5>
                                                        <h6 class="text-white fw-normal">Bengaluru | Class of 2024.
                                                        </h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="swiper-button-next"><i class="fa-solid fa-chevron-right"></i></div>
                            <div class="swiper-button-prev"><i class="fa-solid fa-chevron-left"></i></div>
                            <div class="swiper-pagination"></div>
                        </div>
                        <div class="shape-image">
                            <div class="shape one" data-speed="0.04" data-revert="true"><img
                                    src="{{ asset('assets/images/banner/18.png') }}" alt=""></div>
                            <div class="shape three" data-speed="0.04"><img
                                    src="{{ asset('assets/images/banner/17.png') }}" alt=""></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- feedback area end -->

    <!-- faq area start -->
    <div class="rts-section-gap">

        <div class="container-sm">

            <div class="row justify-content-center">

                <div class="col-lg-8">

                    <div class="title-area-center-style mb--50">

                        <div class="pre-title">
                            <img src="{{ asset('assets/images/banner/bulb.png') }}" alt="icon">
                            <span>Frequently Asked Questions</span>
                        </div>

                        <h2 class="title">
                            Aviation Training FAQs
                        </h2>

                        <p class="post-title">
                            Find answers to common questions about aviation courses, admissions, career opportunities, and
                            placement support at First Fly Aviation Academy.
                        </p>

                    </div>

                </div>

            </div>

            <div class="accordion faq-container" id="homeFaq">

                <div class="accordion-item">
                    <h2 class="accordion-header" id="faq1">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapse1">
                            Which is the best aviation academy in Chennai?
                        </button>
                    </h2>
                    <div id="collapse1" class="accordion-collapse collapse show" data-bs-parent="#homeFaq">
                        <div class="accordion-body">
                            First Fly Aviation Academy provides industry-focused aviation training with practical exposure,
                            interview preparation, airport visits, and placement assistance to help students build
                            successful aviation careers.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header" id="faq2">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapse2">
                            What is the eligibility for Cabin Crew courses?
                        </button>
                    </h2>
                    <div id="collapse2" class="accordion-collapse collapse" data-bs-parent="#homeFaq">
                        <div class="accordion-body">
                            Students who have completed 10+2 can apply for Cabin Crew and Air Hostess training programs.
                            Additional eligibility requirements may vary depending on the course.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header" id="faq3">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapse3">
                            Does First Fly Aviation Academy provide placement assistance?
                        </button>
                    </h2>
                    <div id="collapse3" class="accordion-collapse collapse" data-bs-parent="#homeFaq">
                        <div class="accordion-body">
                            Yes. Placement support, interview preparation, resume building, communication training, and
                            career guidance are included to help students prepare for aviation industry opportunities.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header" id="faq4">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapse4">
                            What career opportunities are available after completing aviation courses?
                        </button>
                    </h2>
                    <div id="collapse4" class="accordion-collapse collapse" data-bs-parent="#homeFaq">
                        <div class="accordion-body">
                            Students can pursue careers as Cabin Crew, Air Hostess, Flight Steward, Ground Staff, Passenger
                            Service Agent, Airport Operations Executive, Cargo Executive, Travel Consultant, Drone Pilot,
                            and Airline Customer Service Executive.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header" id="faq5">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapse5">
                            Do students receive practical aviation training?
                        </button>
                    </h2>
                    <div id="collapse5" class="accordion-collapse collapse" data-bs-parent="#homeFaq">
                        <div class="accordion-body">
                            Yes. Our programs include practical training, airport exposure visits, grooming sessions,
                            interview preparation, communication training, and industry-focused learning activities.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header" id="faq6">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapse6">
                            Does the academy offer Aviation English training?
                        </button>
                    </h2>
                    <div id="collapse6" class="accordion-collapse collapse" data-bs-parent="#homeFaq">
                        <div class="accordion-body">
                            Yes. Aviation English training is available to help students improve communication skills
                            required for professional aviation careers and customer-facing roles.
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
    <!-- faq area end -->

    <!-- gallery preview start -->
    <div class="rts-section-gap bg-site-primary4">

        <div class="container">

            <div class="row align-items-center mb--50">

                <div class="col-lg-8">

                    <div class="title-area-left-style mb-0">

                        <div class="pre-title">
                            <img src="{{ asset('assets/images/banner/bulb.png') }}" alt="icon">
                            <span>Student Activities</span>
                        </div>

                        <h2 class="title">
                            Experience Aviation Learning Beyond the Classroom
                        </h2>

                        <p class="post-title">
                            Explore cabin crew practical training, airport exposure visits, aviation workshops, placement
                            activities, student achievements, and industry interaction programs.
                        </p>

                    </div>

                </div>

                <div class="col-lg-4 text-lg-end mt_md--20 mt_sm--20">

                    <a href="{{ route('gallery') }}" class="rts-btn btn-primary">
                        View Full Gallery
                    </a>

                </div>

            </div>

            <div class="row g-4">

                <div class="col-lg-3 col-md-6">
                    <a href="{{ route('gallery') }}" class="text-decoration-none">
                        <div class="gallery-preview-card">

                            <img src="{{ asset('assets/images/gallery/airport-exposure/students-visiting-airport-terminal.jpg') }}"
                                alt="Airport Exposure Program">

                            <div class="gallery-preview-content">
                                <span class="badge-category">Airport Exposure</span>
                                <h5>Airport Visits</h5>
                            </div>

                        </div>
                    </a>
                </div>

                <div class="col-lg-3 col-md-6">
                    <a href="{{ route('gallery') }}" class="text-decoration-none">
                        <div class="gallery-preview-card">

                            <img src="{{ asset('assets/images/gallery/cabin-crew-activities/cabin-crew-safety-demonstration.jpg') }}"
                                alt="Cabin Crew Training">

                            <div class="gallery-preview-content">
                                <span class="badge-category">Cabin Crew</span>
                                <h5>Practical Training</h5>
                            </div>

                        </div>
                    </a>
                </div>

                <div class="col-lg-3 col-md-6">
                    <a href="{{ route('gallery') }}" class="text-decoration-none">
                        <div class="gallery-preview-card">

                            <img src="{{ asset('assets/images/gallery/workshops-and-seminars/aviation-industry-workshop.jpg') }}"
                                alt="Industry Workshop">

                            <div class="gallery-preview-content">
                                <span class="badge-category">Workshops</span>
                                <h5>Industry Interaction</h5>
                            </div>

                        </div>
                    </a>
                </div>

                <div class="col-lg-3 col-md-6">
                    <a href="{{ route('gallery') }}" class="text-decoration-none">
                        <div class="gallery-preview-card">

                            <img src="{{ asset('assets/images/gallery/placement-events/placement-interview-session.jpg') }}"
                                alt="Placement Support">

                            <div class="gallery-preview-content">
                                <span class="badge-category">Placements</span>
                                <h5>Career Preparation</h5>
                            </div>

                        </div>
                    </a>
                </div>

            </div>

        </div>

    </div>
    <!-- gallery preview end -->

    <!-- admissions cta area start -->
    <div class="rts-section-gap bg_image position-relative overflow-hidden"
        style="background: linear-gradient(135deg,var(--color-primary),var(--color-primary1));">

        <div class="container">

            <div class="row align-items-center">

                <div class="col-lg-8">

                    <span class="badge bg-white text-dark px-4 py-2 mb-4">
                        Admissions Open for 2026 Batch
                    </span>

                    <h2 class="text-white mb-4">
                        Start Your Journey Towards a Successful Aviation Career
                    </h2>

                    <p class="text-white mb-4">
                        Join First Fly Aviation Academy and gain the skills, confidence, industry exposure, and placement
                        support needed to build a rewarding career in aviation, airports, airlines, travel, tourism, and
                        emerging aviation sectors.
                    </p>

                    <div class="d-flex flex-wrap gap-3">

                        <div class="d-flex align-items-center text-white">
                            <i class="fas fa-check-circle me-2"></i>
                            Industry Expert Trainers
                        </div>

                        <div class="d-flex align-items-center text-white">
                            <i class="fas fa-check-circle me-2"></i>
                            Airport Exposure Visits
                        </div>

                        <div class="d-flex align-items-center text-white">
                            <i class="fas fa-check-circle me-2"></i>
                            Placement Assistance
                        </div>

                    </div>

                </div>

                <div class="col-lg-4 text-lg-end mt_md--40 mt_sm--40">

                    <a href="{{ route('contact-us') }}" class="rts-btn btn-primary-white mb-3">
                        Apply Now
                    </a>

                    <br>

                    <a href="javascript:;" class="rts-btn btn-border text-white border-white" data-bs-toggle="modal"
                        data-bs-target="#brochureModal">
                        Download Brochure
                    </a>

                </div>

            </div>

        </div>

        <div class="position-absolute top-0 end-0 opacity-25 d-none d-lg-block">
            <i class="fas fa-plane-departure" style="font-size: 250px;color:#fff;transform:rotate(-15deg);"></i>
        </div>

    </div>
    <!-- admissions cta area end -->
    <div class="modal fade" id="brochureModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header px-5 py-4 bg-site-primary">
                    <h5 class="modal-title">Download Brochure</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body p-5">

                    <form id="brochureForm">

                        @csrf

                        <div class="mb-3">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Phone</label>
                            <input type="text" name="phone" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <button type="submit" class="rts-btn btn-primary w-100" id="btnBrochure"><i
                                class="fa fa-download"></i>
                            Download Brochure
                        </button>

                    </form>

                </div>

            </div>
        </div>
    </div>

    <style>
        .career-item {
            background: #fff;
            padding: 14px 18px;
            border-radius: 10px;
            font-weight: 600;
            box-shadow: 0 5px 20px rgba(0, 0, 0, .08);
            height: 100%;
            transition: .3s;
        }

        .career-item:hover {
            transform: translateY(-3px);
        }

        .faq-container .accordion-item {
            border: none;
            margin-bottom: 15px;
        }

        .faq-container .accordion-button {
            font-size: 18px;
            font-weight: 500;
            background: var(--color-primary4);
            border-radius: 10px !important;
        }

        .faq-container .accordion-button:not(.collapsed) {
            color: white;
            background-color: var(--color-primary);
        }

        .faq-container .accordion-button:not(.collapsed)::after {
            filter: brightness(0);
        }

        .gallery-preview-card {
            position: relative;
            overflow: hidden;
            border-radius: 20px;
            height: 320px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, .08);
        }

        .gallery-preview-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: .5s;
        }

        .gallery-preview-card:hover img {
            transform: scale(1.08);
        }

        .gallery-preview-content {
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            padding: 20px;
            background: linear-gradient(to top, rgba(0, 0, 0, .85), transparent);
        }

        .gallery-preview-content h5 {
            color: #fff;
            margin: 8px 0 0;
        }

        .badge-category {
            display: inline-block;
            background: var(--color-primary);
            color: #fff;
            padding: 5px 12px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 600;
        }

        .journey-card {
            position: relative;
            overflow: hidden;
            background: #fff;
            padding: 35px 20px;
            border-radius: 20px;
            height: 100%;
            box-shadow: 0 10px 30px rgba(0, 0, 0, .08);
            transition: .35s;
        }

        .journey-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, .12);
        }

        .journey-card:hover p,
        .journey-card:hover h5 {
            color: var(--color-primary);
        }

        .journey-number {
            width: 75px;
            height: 75px;
            margin: 0 auto 20px;
            border-radius: 50%;
            background: var(--color-primary4);
            color: var(--color-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            font-weight: 700;
            position: relative;
            z-index: 2;
        }

        .journey-card h5 {
            position: relative;
            z-index: 2;
            margin-bottom: 0px;
        }

        .journey-card p {
            position: relative;
            z-index: 2;
            margin-bottom: 0;
            font-size: 15px;
            line-height: 1.6;
        }

        .journey-bg-icon {
            position: absolute;
            right: -15px;
            bottom: -15px;
            font-size: 110px;
            color: var(--color-primary);
            opacity: .15;
            z-index: 1;
            animation: journeyFloat 6s ease-in-out infinite;
        }

        @keyframes journeyFloat {
            0% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-18px);
            }

            100% {
                transform: translateY(0);
            }
        }

        @media(max-width:991px) {
            .journey-card {
                padding: 30px 15px;
            }

            .journey-bg-icon {
                font-size: 90px;
            }
        }

        .hero-plane {
            position: absolute;
            left: -120px;
            bottom: 150px;
            font-size: 42px;
            color: var(--color-primary);
            opacity: .5;
            z-index: 1;
            animation: flyPlane 18s linear infinite;
        }

        @keyframes flyPlane {
            0% {
                left: -120px;
                bottom: 150px;
                transform: rotate(-25deg);
            }

            100% {
                left: calc(100% + 120px);
                bottom: 90%;
                transform: rotate(-25deg);
            }
        }

        .hero-plane-2 {
            position: absolute;
            right: -100px;
            top: 65%;
            font-size: 28px;
            color: var(--color-primary);
            opacity: .12;
            z-index: 1;
            animation: flyPlaneReverse 24s linear infinite;
        }

        .hero-plane-2 i {
            animation: planeBlink 1.5s ease-in-out infinite;
        }

        @keyframes flyPlaneReverse {

            0% {
                transform: translate(0, 0) rotate(-150deg);
            }

            25% {
                transform: translate(-250px, -60px) rotate(-150deg);
            }

            50% {
                transform: translate(-500px, -130px) rotate(-150deg);
            }

            75% {
                transform: translate(-800px, -220px) rotate(-150deg);
            }

            100% {
                transform: translate(-1200px, -350px) rotate(-150deg);
            }

        }

        @keyframes planeBlink {

            0%,
            100% {
                opacity: 15;
                filter: drop-shadow(0 0 0 transparent);
            }

            50% {
                opacity: .8;
                filter: drop-shadow(0 0 8px var(--color-primary));
            }

        }

        .hero-plane-2::before {
            content: '';
            position: absolute;
            top: 50%;
            right: -4px;
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #ff0000;
            animation: navLight 1s infinite;
        }

        @keyframes navLight {

            0%,
            100% {
                opacity: 0;
            }

            50% {
                opacity: 1;
                box-shadow: 0 0 10px #ff0000;
            }
        }

        .aviation-route-section {
            background: #fff;
        }

        .route-wrapper {
            position: relative;
            padding: 80px 0 40px;
            background: var(--color-primary4);
            border-radius: 10px;
        }

        .route-line {
            position: absolute;
            top: 105px;
            left: 10%;
            width: 80%;
            border-top: 3px dashed rgba(181, 110, 220, .25);
        }

        .route-plane {
            position: absolute;
            top: 92px;
            left: 10%;
            color: var(--color-primary);
            font-size: 30px;
            animation: flyRoute 18s linear infinite;
            opacity: 0.3;
        }

        .route-stop {
            position: relative;
            text-align: center;
            z-index: 2;
        }

        .airport-code {
            display: inline-block;
            background: #fff;
            color: var(--color-primary);
            padding: 10px 18px;
            border-radius: 50px;
            font-weight: 700;
            letter-spacing: 2px;
            margin-bottom: 25px;
        }

        .route-dot {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: var(--color-primary);
            margin: 0 auto 25px;
            box-shadow: 0 0 0 10px rgba(181, 110, 220, .15);
        }

        .route-stop h3 {
            font-size: 48px;
            font-weight: 800;
            color: #091230;
            margin-bottom: 10px;
        }

        .route-stop p {
            margin: 0;
            color: #6b7280;
            font-weight: 600;
        }

        @keyframes flyRoute {
            from {
                left: 10%;
            }

            to {
                left: 85%;
            }
        }

        @media(max-width:991px) {

            .route-line,
            .route-plane {
                display: none;
            }

            .route-stop {
                background: #fff;
                border-radius: 20px;
                padding: 30px 20px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, .08);
            }

        }
    </style>
@endsection


@section('extra-scripts')
    <script>
        $(document).on('submit', '#brochureForm', function(e) {

            e.preventDefault();

            let btn = $('#btnBrochure');

            btn.prop('disabled', true)
                .html('Please Wait...');

            $.ajax({
                url: '{{ route('download-brochure') }}',
                type: 'POST',
                data: $(this).serialize(),

                success: function(response) {

                    if (response.success) {

                        $('#brochureModal').modal('hide');

                        window.open(response.download_url, '_blank');

                        $('#brochureForm')[0].reset();
                    }

                },

                complete: function() {

                    btn.prop('disabled', false)
                        .html('Download Brochure');
                }
            });

        });
    </script>
@endsection
