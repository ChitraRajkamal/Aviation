@extends('layouts.frontend.skeleton')
@section('title', 'Contact First Fly Aviation Academy | Admissions & Career Counseling')

@php
    $footer = $footer ?? true;
    $email = lms_setting('email');
    $phone = lms_setting('phone');
    $phone_1 = lms_setting('phone_1');
@endphp

@section('content')

    <div class="rts-bread-crumbarea-1 rts-section-gap bg_image">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-main-wrapper">
                        <h1 class="title">Contact Us</h1>
                        <!-- breadcrumb pagination area -->
                        <div class="pagination-wrapper">
                            <a href="{{ route('home') }}">Home</a>
                            <i class="fa-regular fa-chevron-right"></i>
                            <a class="active" href="javascript:;">Contact us</a>
                        </div>
                        <!-- breadcrumb pagination area end -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt--60">

    <div class="bg-site-blue-alt  p-5 rounded-3 overflow-hidden position-relative">

        <div class="row align-items-center">

            <div class="col-lg-7">

                <span class="text-danger text-uppercase fw-bold">
                    Why Contact Us?
                </span>

                <h3 class=" mt-3 mb-4">
                    Take the First Step Towards Your Aviation Career
                </h3>

                <p class="mb-4">
                    Speak with our admission counselors and get personalized guidance to choose the right aviation course and career path.
                </p>

                <div class="row g-3">

                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-route text-danger me-3"></i>
                            <span>Free Career Guidance</span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-book-open text-danger me-3"></i>
                            <span>Course Counseling</span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-user-graduate text-danger me-3"></i>
                            <span>Admission Assistance</span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-briefcase text-danger me-3"></i>
                            <span>Placement Information</span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-award text-danger me-3"></i>
                            <span>Scholarship Guidance</span>
                        </div>
                    </div>

                </div>

            </div>

            <div class="col-lg-5 text-center mt_md--30 mt_sm--30">

                <div class="bg-danger-subtle rounded-3 p-4 d-flex flex-column align-items-center">

                    <h4 class="text-dark mb-3">
                        Need Help Choosing a Course?
                    </h4>

                    <p class="text-muted mb-4">
                        Our team is ready to guide you.
                    </p>

                    <a href="#contact-form-section" class="rts-btn btn-primary">
                        Get Free Counseling
                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

    <div class="rts-contact-area rts-section-gapTop mb--80" id="contact-form-section">
        <div class="container">
            <div class="row g-5">
                <div class="col-xl-5">
                    <!-- contact left area start -->
                    <div class="contact-left-area-start">
                        <div class="title-area-left-style">
                            <div class="pre-title">
                                <img src="{{ asset('assets/images/banner/bulb.png') }}" alt="icon">
                                <span>Courses</span>
                            </div>
                            <h2 class="title mb-0">Love to hear from you</h2>
                            <p class="mt-0 mb-5 text-uppercase text-danger">Request a Free Career Counseling Session</p>
                        </div>
                        <form action="{{ route('send-contact-message') }}" method="post" class="contact-page-form"
                            id="contact-forms" data-parsley-validate>
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="single-input mb-4">
                                        <label for="name">Full Name <span class="text-danger">*</span></label>
                                        <input type="text" id="name" name="name" class="form-control"
                                            value="{{ old('name') }}" required maxlength="100"
                                            data-parsley-required-message="Please enter your full name"
                                            data-parsley-maxlength-message="Name cannot exceed 100 characters">
                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="single-input mb-4">
                                        <label for="phone">Phone Number <span class="text-danger">*</span></label>
                                        <input type="text" id="phone" name="phone" class="form-control"
                                            value="{{ old('phone') }}" required data-parsley-pattern="^[0-9+\-\s]{8,20}$"
                                            data-parsley-pattern-message="Please enter a valid phone number"
                                            data-parsley-required-message="Please enter your phone number">
                                        @error('phone')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="single-input mb-4">
                                        <label for="email">Email Address <span class="text-danger">*</span></label>
                                        <input type="email" id="email" name="email" class="form-control"
                                            value="{{ old('email') }}" required maxlength="100"
                                            data-parsley-type-message="Please enter a valid email address"
                                            data-parsley-required-message="Please enter your email address">
                                        @error('email')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="single-input mb-4">
                                        <label for="course">Course Interested In <span class="text-danger">*</span></label>
                                        <select id="course" name="course" class="form-control" required
                                            data-parsley-required-message="Please select a course">
                                            <option value="">Select Course</option>
                                            <option value="Cabin Crew Training"
                                                {{ old('course') == 'Cabin Crew Training' ? 'selected' : '' }}>Cabin Crew Training
                                            </option>
                                            <option value="Pilot Training"
                                                {{ old('course') == 'Pilot Training' ? 'selected' : '' }}>Pilot Training</option>
                                            <option value="Airport Operations"
                                                {{ old('course') == 'Airport Operations' ? 'selected' : '' }}>Airport Operations
                                            </option>
                                            <option value="Travel & Tourism Management"
                                                {{ old('course') == 'Travel & Tourism Management' ? 'selected' : '' }}>Travel &
                                                Tourism Management</option>
                                            <option value="Drone Pilot Training"
                                                {{ old('course') == 'Drone Pilot Training' ? 'selected' : '' }}>Drone Pilot
                                                Training</option>
                                            <option value="Air Cargo Operations"
                                                {{ old('course') == 'Air Cargo Operations' ? 'selected' : '' }}>Air Cargo
                                                Operations</option>
                                        </select>
                                        @error('course')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="single-input mt-3">
                                <label for="message">Message <span class="text-danger">*</span></label>
                                <textarea id="message" name="message" class="form-control" rows="5" required maxlength="1200"
                                    data-parsley-required-message="Please enter your message"
                                    data-parsley-maxlength-message="Message cannot exceed 1200 characters">{{ old('message') }}</textarea>
                                @error('message')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <button type="submit" id="btn-send" class="rts-btn btn-primary mt--30">
                                Submit
                            </button>
                        </form>
                        <div id="form-messages" class="mt--20"></div>
                    </div>
                    <!-- contact left area end -->
                </div>
                <div class="col-xl-7 pl--50 pl_lg--15 pl_md--15 pl_sm--15 pb_md--100 pb_sm--100">
                    <div class="contact-map-area-start">
                        <div class="single-maptop-info">
                            <p class="disc">
                                <i class="far fa-map-marker-alt text-danger"></i> {{ lms_setting('address') }}
                            </p>
                        </div>
                        <div class="single-maptop-info">
                            <i class="far fa-phone text-danger"></i> <a
                                href="tel:{{ $phone }}">{{ $phone }}</a>
                            <br>
                            <i class="far fa-envelope text-danger"></i> <a
                                href="tel:{{ $email }}">{{ $email }}</a>
                        </div>
                    </div>
                    <div class="map-bottom-area mt--30">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3888.6819154098444!2d80.1164951!3d12.928154000000001!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3a525f616d640b37%3A0x13ee90d6eb828f3!2sChandra%20Towers%2C%20No.23%202nd%20floor%2C%20Rajaji%20Rd%2C%20Tambaram%20West%2C%20Chennai%2C%20Tamil%20Nadu%20600045!5e0!3m2!1sen!2sin!4v1742485737092!5m2!1sen!2sin"
                            width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .contact-map-area-start>div {
            flex-basis: 50%;
        }
        #contact-form-section{
            scroll-margin-top: 150px;
        }
    </style>

@endsection

@section('extra-scripts')
    <script src="https://cdn.jsdelivr.net/npm/parsleyjs@2.9.2/dist/parsley.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#contact-forms').submit(function(e) {
                e.preventDefault();
                $('#btn-send').prop('disabled', true).html(
                    '<i class="far fa-spin fa-circle-notch me-2"></i> Submitting...');
                this.submit();
            });

        });
    </script>
@endsection
