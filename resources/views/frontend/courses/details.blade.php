@extends('layouts.frontend.skeleton')
@section('title', $course->title)
@php
    $meta_data = $course->meta_data ?? [];
    $sections = $course->sections;
    $total_lessons = $course->lesson_count();
    $total_quizs = $course->quiz_count();
    $requirements = $course->requirements && $course->requirements != '[]' ? json_decode($course->requirements) : false;
    $outcomes = $course->outcomes && $course->outcomes != '[]' ? json_decode($course->outcomes) : false;
    $faqs = $course->faqs && $course->faqs != '[]' ? json_decode($course->faqs) : false;
@endphp

@section('meta-data')
    <meta name="description" content="{{ $meta_data['description'] ?? '' }}">
    <meta name="robots" content="{{ $meta_data['robot'] ?? 'index, follow' }}">

    <!-- Open Graph (OG) Tags for Social Media -->
    <meta property="og:title" content="{{ $meta_data['og_title'] ?? ($meta_data['title'] ?? $course->title) }}">
    <meta property="og:description" content="{{ $meta_data['og_description'] ?? ($meta_data['description'] ?? '') }}">
    <meta property="og:image" content="{{ lms_storage($meta_data['og_image'] ?? '') }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">

    <!-- Twitter Card (Optional for Twitter Sharing) -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $meta_data['og_title'] ?? ($meta_data['title'] ?? $course->title) }}">
    <meta name="twitter:description" content="{{ $meta_data['og_description'] ?? ($meta_data['description'] ?? '') }}">
    <meta name="twitter:image" content="{{ lms_storage($meta_data['og_image'] ?? '') }}">
@endsection

@section('content')
    <style>
        .module-wrapper:empty {
            display: none;
        }

        .accordion-button {
            font-size: 21px;
        }

        .course-content-wrapper-main .accordion .accordion-item .accordion-body .play-vedio-wrapper .left span {
            text-decoration-line: none;
        }

        .faq-container .accordion-button {
            font-size: 18px;
            font-weight: 500;
        }

        .faq-container .accordion-button:not(.collapsed) {
            color: white;
            background-color: var(--color-primary);
        }

        .faq-container .faq-answer ul li {
            font-size: 15px;
            margin: 0px;
            font-weight: 500;
        }
    </style>
    <!-- course details breadcrumb -->
    <div class="course-details-breadcrumb-1 bg_image rts-section-gap">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="single-course-left-align-wrapper">
                        <div class="meta-area">
                            <a href="{{ url('') }}">Home</a>
                            <i class="fa-solid fa-chevron-right"></i>
                            <a href="{{ route('courses') }}">Courses</a>
                            <i class="fa-solid fa-chevron-right"></i>
                            <a class="active" href="#">{{ $course->title }}</a>
                        </div>
                        <h1 class="title">
                            {{ $course->title }}
                        </h1>
                        <div class="rating-area">
                            <div class="stars-area">
                                <span>{{ lms_decimal_points($averageRating) }}</span>
                                <div class="stars-area">
                                    <div class="stars-background">
                                        {!! str_repeat('<i class="fa-regular fa-star"></i>', 5) !!}
                                    </div>
                                    <div class="stars-overlay"
                                        style="width: {{ lms_rating_to_percentage($averageRating) }}%;">
                                        {!! str_repeat('<i class="fa fa-star"></i>', 5) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="students">
                                <i class="fa-thin fa-book"></i>
                                <span>{{ $total_lessons }} {{ lms_plural('Lesson', $total_lessons) }}</span>
                            </div>
                            <div class="students">
                                <i class="fa-thin fa-pencil-alt"></i>
                                <span>{{ $total_quizs }} {{ lms_plural('Quiz', $total_quizs) }}</span>
                            </div>
                            <div class="calender-area-stars">
                                <i class="fa-light fa-calendar-lines-pen"></i>
                                <span>Last updated {{ lms_format_date($course->updated_at) }}</span>
                            </div>
                        </div>
                        <div class="author-area">
                            <div class="author">
                                <img src="{{ asset('admin-assets/assets/images/profile-40.png') }}" alt="Instructor" width="40" height="40">
                                <div class="name fw-medium"><span>By</span> {{ $course->user->first_name }}.</div>
                            </div>
                            <p> <span>Category: </span> {{ $course->category->title }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- course details breadcrumb end -->

    <!-- course details area start -->
    <div class="rts-course-area rts-section-gap">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-8 order-cl-1 order-lg-1 order-md-2 order-sm-2 order-2">
                    <div class="course-details-btn-wrapper pb--50">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home"
                                    type="button" role="tab" aria-controls="home" aria-selected="true">Course
                                    Information</button>
                            </li>
                            @if (!$sections->isEmpty())
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link " id="profile-tab" data-bs-toggle="tab"
                                        data-bs-target="#profile" type="button" role="tab" aria-controls="profile"
                                        aria-selected="false">Course Content</button>
                                </li>
                            @endif
                            @if ($course->galleryImages->count())
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="gallery-tab" data-bs-toggle="tab" data-bs-target="#gallery"
                                        type="button" role="tab" aria-controls="gallery"
                                        aria-selected="false">Gallery</button>
                                </li>
                            @endif
                            @if ($faqs && $faqs->name[0])
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="faqs-tab" data-bs-toggle="tab" data-bs-target="#faqs"
                                        type="button" role="tab" aria-controls="faqs"
                                        aria-selected="false">FAQs</button>
                                </li>
                            @endif
                            <li class="nav-item d-none" role="presentation">
                                <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact"
                                    type="button" role="tab" aria-controls="contact"
                                    aria-selected="false">Instructor</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews"
                                    type="button" role="tab" aria-controls="reviews"
                                    aria-selected="false">Reviews</button>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content mt--20" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel"
                            aria-labelledby="home-tab">
                            <div class="course-content-wrapper">
                                <h2 class="title text-danger text-decoration-underline">About Course</h2>
                                <p class="disc">
                                    {{ $course->short_description }}
                                </p>

                                @if ($course->description)
                                    {{-- <h5 class="title">Description</h5> --}}
                                    <p class="disc">
                                        {!! $course->description !!}
                                    </p>
                                @endif

                                @if ($outcomes && $outcomes?->name[0])
                                    <div class="module-wrapper">
                                        <h2 class="title">What Will You Learn?</h2>
                                        <div class="inner-content">
                                            <div class="single-wrapper w-100">
                                                @foreach ($outcomes->name as $d)
                                                    <div class="single-codule">
                                                        <i class="fa-regular fa-check"></i>
                                                        <p>{{ $d }}</p>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            <div class="course-content-wrapper-main">
                                <h2 class="title">Course Content</h2>

                                <!-- course content accordion area -->
                                <div class="accordion mt--30" id="accordionSection">
                                    @foreach ($sections as $k => $section)
                                        @php
                                            $section_lesson_count = $section->lesson_count();
                                            $section_quiz_count = $section->quiz_count();
                                            $summary = '';
                                            if ($section_lesson_count > 0) {
                                                $summary .=
                                                    "$section_lesson_count " .
                                                    lms_plural('Lesson', $section_lesson_count) .
                                                    ', ';
                                            }
                                            if ($section_quiz_count > 0) {
                                                $summary .=
                                                    "$section_quiz_count " .
                                                    lms_plural('Quiz', $section_quiz_count) .
                                                    ', ';
                                            }
                                        @endphp
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingSec{{ $section->id }}">
                                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                                    data-bs-target="#collapseSec{{ $section->id }}" aria-expanded="true"
                                                    aria-controls="collapseSec{{ $section->id }}">
                                                    <span>{{ $section->title }}</span>
                                                    <span>{{ rtrim($summary, ', ') }}</span>
                                                </button>
                                            </h2>
                                            <div id="collapseSec{{ $section->id }}"
                                                class="accordion-collapse collapse {{ $k == 0 ? 'show' : '' }}"
                                                aria-labelledby="headingSec{{ $section->id }}"
                                                data-bs-parent="#accordionSection">
                                                <div class="accordion-body">
                                                    @foreach ($section->lessons as $lk => $lesson)
                                                        <a class="play-vedio-wrapper text-dark">
                                                            <div class="left">
                                                                <span class="cursor-help">{!! lms_lesson_type_icon($lesson->lesson_type, $lesson->document_type) !!}</span>
                                                                <span>{{ $lesson->title }}</span>
                                                            </div>
                                                            <div class="right">
                                                                <span class="play d-none">Preview</span>
                                                                <span>{{ $lesson->duration_text }}</span>
                                                            </div>
                                                        </a>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <!-- course content accordion area end -->
                            </div>
                        </div>

                        {{-- <div class="tab-pane fade " id="contact" role="tabpanel" aria-labelledby="contact-tab">
                        <!-- single instructor area staret -->
                        <div class="single-instructor-area-details">
                            <a href="#" class="thumbnail">
                                <img src="{{asset('assets/images/instructor/10.jpg')}}" alt="instructor" width="205" height="205">
                            </a>
                            <div class="inner-instrustor-area">
                                <h5 class="title">William U.</h5>
                                <span class="deg">Advanced Educator</span>
                                <div class="stars-area-wrapper">
                                    <div class="stars-area">
                                        <span>4.5</span>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-regular fa-star"></i>
                                    </div>
                                    <div class="users-area">
                                        <i class="fa-light fa-users"></i>
                                        <span>1350 Students</span>
                                    </div>
                                    <div class="users-area">
                                        <i class="fa-light fa-video"></i>
                                        <span>26 Courses</span>
                                    </div>
                                </div>
                                <p class="disc">
                                    William U. Peña, MBA, CISSP No. 349867, is a former college professor and the lead instructor at Dion Training Solutions.
                                </p>
                                <div class="follow-us">
                                    <span>Follow</span>
                                    <ul>
                                        <li><a href="#"><i class="fa-brands fa-facebook"></i></a></li>
                                        <li><a href="#"><i class="fa-brands fa-instagram"></i></a></li>
                                        <li><a href="#"><i class="fa-brands fa-linkedin"></i></a></li>
                                        <li><a href="#"><i class="fa-brands fa-pinterest"></i></a></li>
                                        <li><a href="#"><i class="fa-brands fa-youtube"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- single instructor area end -->
                        <!-- single instructor area staret -->
                        <div class="single-instructor-area-details">
                            <a href="#" class="thumbnail">
                                <img src="{{asset('assets/images/instructor/11.jpg')}}" alt="instructor" width="205" height="205">
                            </a>
                            <div class="inner-instrustor-area">
                                <h5 class="title">William U.</h5>
                                <span class="deg">Advanced Educator</span>
                                <div class="stars-area-wrapper">
                                    <div class="stars-area">
                                        <span>4.5</span>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-regular fa-star"></i>
                                    </div>
                                    <div class="users-area">
                                        <i class="fa-light fa-users"></i>
                                        <span>1350 Students</span>
                                    </div>
                                    <div class="users-area">
                                        <i class="fa-light fa-video"></i>
                                        <span>26 Courses</span>
                                    </div>
                                </div>
                                <p class="disc">
                                    William U. Peña, MBA, CISSP No. 349867, is a former college professor and the lead instructor at Dion Training Solutions.
                                </p>
                                <div class="follow-us">
                                    <span>Follow</span>
                                    <ul>
                                        <li><a href="#"><i class="fa-brands fa-facebook"></i></a></li>
                                        <li><a href="#"><i class="fa-brands fa-instagram"></i></a></li>
                                        <li><a href="#"><i class="fa-brands fa-linkedin"></i></a></li>
                                        <li><a href="#"><i class="fa-brands fa-pinterest"></i></a></li>
                                        <li><a href="#"><i class="fa-brands fa-youtube"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- single instructor area end -->
                    </div> --}}

                        <div class="tab-pane fade faq-container" id="gallery" role="tabpanel"
                            aria-labelledby="gallery-tab">
                            <div class="course-content-wrapper">
                                @if ($course->galleryImages->count())
                                    <div class="module-wrapper mt-4 p-0 border-0">
                                        <h2 class="title">Gallery</h2>

                                        <div class="row g-4">
                                            @foreach ($course->galleryImages as $image)
                                                <div class="col-lg-3 col-md-4 col-sm-6">
                                                    <a href="{{ lms_storage($image->image) }}" class="glightbox"
                                                        data-gallery="course-gallery"
                                                        data-title="{{ $image->alt_text ?: $course->title }}">

                                                        <div class="gallery-card">
                                                            <div class="gallery-card">
                                                                <img src="{{ lms_storage($image->image) }}"
                                                                    alt="{{ $image->alt_text ?: $course->title }}"
                                                                    loading="lazy">

                                                                <div class="gallery-overlay">
                                                                    <div>
                                                                        <small>Click to View</small>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="tab-pane fade faq-container" id="faqs" role="tabpanel"
                            aria-labelledby="faqs-tab">
                            <div class="course-content-wrapper">
                                @if ($faqs && !empty($faqs->name[0]))
                                    <div class="module-wrapper mt-4 p-0 border-0">
                                        <h2 class="title">FAQs</h2>

                                        <div class="accordion" id="faqAccordion">

                                            @foreach ($faqs->name as $k => $question)
                                                <div class="accordion-item mb-3 border rounded">

                                                    <h2 class="accordion-header" id="heading{{ $k }}">
                                                        <button class="accordion-button {{ $k != 0 ? 'collapsed' : '' }}"
                                                            type="button" data-bs-toggle="collapse"
                                                            data-bs-target="#collapse{{ $k }}"
                                                            aria-expanded="{{ $k == 0 ? 'true' : 'false' }}"
                                                            aria-controls="collapse{{ $k }}">

                                                            {{ $question }}

                                                        </button>
                                                    </h2>

                                                    <div id="collapse{{ $k }}"
                                                        class="accordion-collapse collapse {{ $k == 0 ? 'show' : '' }}"
                                                        aria-labelledby="heading{{ $k }}"
                                                        data-bs-parent="#faqAccordion">

                                                        <div class="accordion-body faq-answer p-4">

                                                            @php
                                                                $answer = trim($faqs->value[$k]);

                                                                if (str_contains($answer, '•')) {
                                                                    $parts = array_filter(
                                                                        array_map('trim', explode('•', $answer)),
                                                                    );
                                                                    $firstLine = array_shift($parts);
                                                                    $answer = '<p>' . $firstLine . '</p><ul>';

                                                                    foreach ($parts as $part) {
                                                                        $answer .= '<li>' . e($part) . '</li>';
                                                                    }

                                                                    $answer .= '</ul>';
                                                                } else {
                                                                    $answer = nl2br(e($answer));
                                                                }
                                                            @endphp

                                                            {!! $answer !!}

                                                        </div>

                                                    </div>

                                                </div>
                                            @endforeach

                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="tab-pane fade " id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                            @if (lms_user_id() && $courseEnrolled)
                                @if ($rated)
                                    <div class="alert alert-info mb-4"><i class="fa fa-check-circle"></i>
                                        {{ __('You already rated this course') }}</div>
                                @else
                                    @error('type')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                    <form id="ratingForm" method="POST" class="submitForm"
                                        action="{{ route('rating-save', ['type' => 'course', 'type_id' => $course->id]) }}">
                                        @csrf
                                        <h4>Leave a Rating</h4>

                                        <!-- Star Rating Input -->
                                        <div class="mb-3 d-flex align-items-center gap-4">
                                            <label class="form-label">Your Rating:</label>
                                            <div id="star-rating">
                                                @foreach (array_reverse(lms_rating_numbers()) as $r)
                                                    <input type="radio" id="star-rating-{{ $r['rating'] }}"
                                                        name="rating" value="{{ $r['rating'] }}" required />
                                                    <label for="star-rating-{{ $r['rating'] }}" class="bs-tt"
                                                        title="{{ $r['name'] }}">★</label>
                                                @endforeach
                                            </div>
                                        </div>
                                        @error('rating')
                                            <div class="text-danger mb-3">{{ $message }}</div>
                                        @enderror

                                        <!-- Optional Message -->
                                        <div class="mb-4 d-flex align-items-center justify-content-between">
                                            <label for="message" class="form-label">Leave a Comment:</label>
                                            <textarea id="message" name="message" class="form-control" rows="3" required
                                                placeholder="Share your thoughts..."></textarea>
                                        </div>
                                        @error('message')
                                            <div class="text-danger mb-3">{{ $message }}</div>
                                        @enderror

                                        <!-- Submit Button -->
                                        <button type="submit" class="btn btn-primary btn-lg mb-5 w-auto">Submit
                                            Rating</button>
                                    </form>

                                    <!-- Star Rating Styling -->
                                    <style>
                                        #star-rating {
                                            display: flex;
                                            flex-direction: row-reverse;
                                            justify-content: flex-start;
                                        }

                                        #star-rating input {
                                            opacity: 0;
                                            position: absolute;
                                            z-index: -1;
                                        }

                                        #star-rating label {
                                            font-size: 30px;
                                            color: #a2a2a2;
                                            cursor: pointer;
                                            padding-left: 10px;
                                        }

                                        #star-rating label::before {
                                            display: none;
                                        }

                                        #star-rating input:checked~label {
                                            color: #f39c12;
                                        }
                                    </style>
                                    <script>
                                        @if ($errors->has('message') || $errors->has('type'))
                                            setTimeout(() => {
                                                $('#reviews-tab').trigger('click');
                                            }, 500);
                                        @endif
                                    </script>
                                @endif
                            @endif

                            <div class="rating-main-wrapper">
                                <!-- single-top-rating -->
                                <div class="rating-top-main-wrapper">
                                    <!-- rating area start -->
                                    <div class="rating-area-main-wrapper">
                                        <h2 class="title">{{ lms_decimal_points($averageRating) }}</h2>

                                        <div class="stars-area">
                                            <div class="stars-background">
                                                {!! str_repeat('<i class="fa-regular fa-star"></i>', 5) !!}
                                            </div>
                                            <div class="stars-overlay"
                                                style="width: {{ lms_rating_to_percentage($averageRating) }}%;">
                                                {!! str_repeat('<i class="fa fa-star"></i>', 5) !!}
                                            </div>
                                        </div>
                                        <br>
                                        <span>Total {{ $ratings->count() }} Ratings</span>
                                    </div>
                                    <!-- rating area end -->
                                    <div class="progress-wrapper-main">
                                        @foreach ($ratingPercentages as $rating => $percentage)
                                            <div class="single-progress-area-h" data-sal-delay="150" data-sal="slide-up"
                                                data-sal-duration="800">
                                                <div class="progress-top">
                                                    <i class="fa-regular fa-star"></i>
                                                    <span class="parcent">
                                                        {{ $rating }}
                                                    </span>
                                                </div>
                                                <div class="progress">
                                                    <div class="progress-bar wow fadeInLeft bg--primary"
                                                        role="progressbar" style="width: {{ $percentage }}%"
                                                        aria-valuenow="{{ $percentage }}" aria-valuemin="0"
                                                        aria-valuemax="100">
                                                    </div>
                                                </div>
                                                <div class="end">
                                                    <span>{{ $ratingCounts[$rating] }} Rating</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <!-- single-top-rating end-->

                                @foreach ($ratings as $item)
                                    <div class="indevidual-rating-area mt--25">
                                        <!-- author-area -->
                                        <div class="author-area">
                                            <img src="{{ $global_user_image }}" alt="instructor">
                                            <div class="information">
                                                <span>
                                                    {{ $item->user->full_name }}
                                                    @if (lms_user_id() == $item->user->id)
                                                        <small class="text-muted">[Your rating]</small>
                                                    @endif
                                                </span>
                                                <div class="stars">
                                                    <i class="fa-solid fa-star"></i>
                                                    <i
                                                        class="fa-{{ $item->rating > 1 ? 'solid' : 'regular' }} fa-star"></i>
                                                    <i
                                                        class="fa-{{ $item->rating > 2 ? 'solid' : 'regular' }} fa-star"></i>
                                                    <i
                                                        class="fa-{{ $item->rating > 3 ? 'solid' : 'regular' }} fa-star"></i>
                                                    <i
                                                        class="fa-{{ $item->rating > 4 ? 'solid' : 'regular' }} fa-star"></i>
                                                    <span
                                                        class="ml--10 mt-2 text-muted fw-normal">{{ $item->created_at->diffForHumans() }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- author-area end -->
                                        <p class="disc">
                                            {{ $item->message }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    {{-- <div class="wrapper-bottom-course-details-page g-5 row mt--50 pr--60 pr_sm--0 pl_sm--0 d-none">
                        <div class="title-between-area pr--150">
                            <h5 class="title mb-0">More Courses by William U.</h5>
                            <a href="#" class="rts-btn with-arrow p-0">View All Course <i
                                    class="fa-light fa-arrow-right"></i></a>
                        </div>
                        <div class="col-lg-5 col-md-6 col-sm-12">
                            <div class="rts-single-course">
                                <a href="single-course.html" class="thumbnail">
                                    <img src="{{ asset('assets/images/course/02.jpg') }}" alt="course">
                                </a>
                                <div class="save-icon" data-bs-toggle="modal" data-bs-target="#exampleModal-login">
                                    <i class="fa-sharp fa-light fa-bookmark"></i>
                                </div>
                                <div class="tags-area-wrapper">
                                    <div class="single-tag">
                                        <span>Marketing</span>
                                    </div>
                                    <div class="single-tag">
                                        <span>Finance</span>
                                    </div>
                                </div>
                                <div class="lesson-studente">
                                    <div class="lesson">
                                        <i class="fa-light fa-calendar-lines-pen"></i>
                                        <span>22 Lessons</span>
                                    </div>
                                    <div class="lesson">
                                        <i class="fa-light fa-user-group"></i>
                                        <span>60 Students</span>
                                    </div>
                                </div>
                                <a href="single-course.html">
                                    <h5 class="title">How to Write the Ultimate 1 Page
                                        Strategic Business Plan</h5>
                                </a>
                                <p class="teacher">William U. Peña, MBA</p>
                                <div class="rating-and-price">
                                    <div class="rating-area">
                                        <span>4.5</span>
                                        <div class="stars">
                                            <ul>
                                                <li><i class="fa-sharp fa-solid fa-star"></i></li>
                                                <li><i class="fa-sharp fa-solid fa-star"></i></li>
                                                <li><i class="fa-sharp fa-solid fa-star"></i></li>
                                                <li><i class="fa-sharp fa-solid fa-star"></i></li>
                                                <li><i class="fa-sharp fa-regular fa-star"></i></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="price-area">
                                        <div class="price">
                                            $79.99
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5 col-md-6 col-sm-12">
                            <div class="rts-single-course">
                                <a href="single-course.html" class="thumbnail">
                                    <img src="{{ asset('assets/images/course/03.jpg') }}" alt="course">
                                </a>
                                <div class="save-icon" data-bs-toggle="modal" data-bs-target="#exampleModal-login">
                                    <i class="fa-sharp fa-light fa-bookmark"></i>
                                </div>
                                <div class="tags-area-wrapper">
                                    <div class="single-tag">
                                        <span>Marketing</span>
                                    </div>
                                    <div class="single-tag">
                                        <span>Finance</span>
                                    </div>
                                </div>
                                <div class="lesson-studente">
                                    <div class="lesson">
                                        <i class="fa-light fa-calendar-lines-pen"></i>
                                        <span>22 Lessons</span>
                                    </div>
                                    <div class="lesson">
                                        <i class="fa-light fa-user-group"></i>
                                        <span>60 Students</span>
                                    </div>
                                </div>
                                <a href="single-course.html">
                                    <h5 class="title">How to Write the Ultimate 1 Page
                                        Strategic Business Plan</h5>
                                </a>
                                <p class="teacher">William U. Peña, MBA</p>
                                <div class="rating-and-price">
                                    <div class="rating-area">
                                        <span>4.5</span>
                                        <div class="stars">
                                            <ul>
                                                <li><i class="fa-sharp fa-solid fa-star"></i></li>
                                                <li><i class="fa-sharp fa-solid fa-star"></i></li>
                                                <li><i class="fa-sharp fa-solid fa-star"></i></li>
                                                <li><i class="fa-sharp fa-solid fa-star"></i></li>
                                                <li><i class="fa-sharp fa-regular fa-star"></i></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="price-area">
                                        <div class="price">
                                            $79.99
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                </div>
                <div class="col-lg-4 order-cl-2 order-lg-2 order-md-1 order-sm-1 order-1  rts-sticky-column-item">
                    <!-- right- sticky bar area -->
                    <div class="right-course-details">
                        <!-- single course-sidebar -->
                        <div class="course-side-bar">
                            @if ($course->uploaded_video_url || $course->online_video_url)
                                <div class="thumbnail">
                                    <img src="{{ lms_storage($course->thumbnail) }}" alt="">
                                    <div class="vedio-icone">
                                        <a class="video-play-button play-video popup-video"
                                            href="{{ $course->is_online_video ? $course->online_video_url : lms_storage($course->uploaded_video_url) }}">
                                            <span></span>
                                        </a>
                                        <div class="video-overlay">
                                            <a class="video-overlay-close">×</a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            {{-- <div class="price-area {{ $course->thumbnail ? 'mt-5' : 'mt-0' }}">
                                @if ($course->discount_flag)
                                    <h3 class="title">{!! lms_show_price($course, 'discounted_price') !!}</h3>
                                    <h4 class="none text-decoration-line-through">{!! lms_show_price($course) !!}</h4>
                                    <span
                                        class="discount">-{{ 100 - round(($course->discounted_price / $course->price) * 100, 2) }}%</span>
                                @else
                                    <h3 class="title">{!! lms_show_price($course) !!}</h3>
                                @endif
                            </div> --}}
                            @php
                                /*
                            <div class="clock-area">
                                <i class="fa-light fa-clock"></i>
                                <span>2 Day left at this price!</span>
                            </div>*/
                            @endphp

                            {{-- @if ($courseEnrolled)
                                @if ($courseCompleted)
                                    <a href="{{ route('courses.stage', ['slug' => $course->slug]) }}"
                                        class="rts-btn btn-primary"><i class="fa fa-download"></i> DOWNLOAD
                                        CERTIFICATE</a>
                                @else
                                    <a href="{{ route('courses.stage', ['slug' => $course->slug]) }}"
                                        class="rts-btn btn-primary">START NOW</a>
                                @endif
                            @else
                                @if ($course->is_paid)
                                    @if (auth()->check())
                                        @if ($course->cart())
                                            <form
                                                action="{{ route('cart-save', ['type' => 'course', 'type_id' => $course->id]) }}"
                                                id="cart-form" class="mb-0 ml-1" method="POST">
                                                @csrf
                                                <button type="submit" class="rts-btn btn-border">
                                                    <i class="fa-regular fa-shopping-cart"></i> Remove From Cart
                                                </button>
                                            </form>
                                        @else
                                            <form
                                                action="{{ route('cart-save', ['type' => 'course', 'type_id' => $course->id]) }}"
                                                id="cart-form" class="mb-0 ml-1" method="POST">
                                                @csrf
                                                <button type="submit" class="rts-btn btn-border">
                                                    <i class="fa-solid fa-shopping-cart"></i> Add To Cart
                                                </button>
                                            </form>
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}" class="rts-btn btn-border"><i
                                                class="fa-solid fa-shopping-cart"></i> Add To Cart</a>
                                    @endif

                                    <form action="{{ route('courses.create_order', ['slug' => $course->slug]) }}"
                                        id="pay-form" class="mb-0 ml-1" method="POST">
                                        @csrf
                                        <button type="submit" class="rts-btn btn-primary">
                                            Register for Demo
                                        </button>
                                    </form>
                                    <script>
                                        $(document).ready(function() {
                                            $('#pay-form').submit(function(e) {
                                                e.preventDefault();
                                                $('#pay-form [type="submit"]').prop('disabled', true).html(
                                                    '<i class="fa fa-spin fa-circle-notch"></i> Processing...');
                                                this.submit();
                                            });
                                        });
                                    </script>
                                @else
                                    <form action="{{ route('courses.enroll', ['slug' => $course->slug]) }}"
                                        id="enroll-form" class="mb-0 ml-1" method="POST">
                                        @csrf
                                        <button type="submit" class="rts-btn btn-primary">
                                            ENROLL
                                        </button>
                                    </form>
                                    <script>
                                        $(document).ready(function() {
                                            $('#enroll-form').submit(function(e) {
                                                e.preventDefault();
                                                $('#enroll-form [type="submit"]').prop('disabled', true).html(
                                                    '<i class="fa fa-spin fa-circle-notch"></i> Processing...');
                                                this.submit();
                                            });
                                        });
                                    </script>
                                @endif
                            @endif --}}

                            <div class="what-includes">
                                <h5 class="title">This course includes: </h5>
                                <div class="single-include">
                                    <div class="left">
                                        <i class="fa-light fa-chart-bar"></i>
                                        <span>Level</span>
                                    </div>
                                    <div class="right">
                                        <span>{{ $course->level }}</span>
                                    </div>
                                </div>
                                @if ($lessonDurations > 0)
                                    <div class="single-include">
                                        <div class="left">
                                            <i class="fa-light fa-timer"></i>
                                            <span>Duration</span>
                                        </div>
                                        <div class="right">
                                            <span>{{ $lessonDurations }}</span>
                                        </div>
                                    </div>
                                @endif
                                <div class="single-include">
                                    <div class="left">
                                        <i class="fa-regular fa-tag"></i>
                                        <span>Category</span>
                                    </div>
                                    <div class="right">
                                        <span>{{ $course->category->title }}</span>
                                    </div>
                                </div>
                                <div class="single-include">
                                    <div class="left">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                        <span>Update</span>
                                    </div>
                                    <div class="right">
                                        <span>{{ lms_format_date($course->updated_at) }}</span>
                                    </div>
                                </div>
                                <div class="single-include">
                                    <div class="left">
                                        <i class="fa-sharp fa-light fa-file-certificate"></i>
                                        <span>Certificate</span>
                                    </div>
                                    <div class="right">
                                        <span>Certificate of completion </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- single course-sidebar end -->
                    </div>
                    <!-- right- sticky bar area end -->
                    <!-- right- sticky bar area -->
                    <div class="right-course-details mt--30">
                        <!-- single course-sidebar -->
                        <div class="course-side-bar">
                            <!-- course single sidebar -->
                            <div class="course-single-information">
                                <h5 class="title">A course by</h5>
                                <div class="body">
                                    <div class="author">
                                        <img src="{{ asset('admin-assets/assets/images/profile-40.png') }}"
                                            alt="Instructor" width="40" height="40">
                                        <span>{{ $course->user->first_name }}</span>
                                    </div>
                                </div>
                            </div>
                            <!-- course single sidebar end-->
                            <!-- course single sidebar -->
                            <div class="course-single-information d-none">
                                <h5 class="title">Material Includes</h5>
                                <div class="body">
                                    <!-- ingle check -->
                                    <div class="single-check">
                                        <i class="fa-light fa-circle-check"></i>
                                        Flexible Deadlines
                                    </div>
                                    <!-- ingle check end -->
                                    <!-- ingle check -->
                                    <div class="single-check">
                                        <i class="fa-light fa-circle-check"></i>
                                        Hours of live- demo
                                    </div>
                                    <!-- ingle check end -->
                                    <!-- ingle check -->
                                    <div class="single-check">
                                        <i class="fa-light fa-circle-check"></i>
                                        Hours of live- demo
                                    </div>
                                    <!-- ingle check end -->
                                    <!-- ingle check -->
                                    <div class="single-check">
                                        <i class="fa-light fa-circle-check"></i>
                                        200+ downloadable resoursces
                                    </div>
                                    <!-- ingle check end -->
                                </div>
                            </div>
                            <!-- course single sidebar end-->

                            @if ($requirements && $requirements->name[0])
                                <!-- course single sidebar -->
                                <div class="course-single-information">
                                    <h5 class="title">Requirements</h5>
                                    <div class="body">
                                        @foreach ($requirements->name as $d)
                                            <!-- ingle check -->
                                            <div class="single-check">
                                                <i class="fa-light fa-circle-check"></i>
                                                {{ $d }}
                                            </div>
                                            <!-- ingle check end -->
                                        @endforeach
                                    </div>
                                </div>
                                <!-- course single sidebar end-->
                            @endif

                            <!-- course single sidebar -->
                            {{-- <div class="course-single-information">
                            <h5 class="title">Tags</h5>
                            <div class="body">
                                <div class="tags-wrapper">
                                    <!-- single tags -->
                                    <span>Course</span>
                                    <span>Design</span>
                                    <span>Web development</span>
                                    <span>Business</span>
                                    <span>UI/UX</span>
                                    <span>Financial</span>
                                    <!-- single tags end -->
                                </div>
                            </div>
                        </div> --}}
                            <!-- course single sidebar end-->
                            <!-- course single sidebar -->
                            <div class="course-single-information">
                                <h5 class="title">Share</h5>
                                <div class="body">
                                    <!-- social-share-course-sidebar -->
                                    <div class="social-share-course-side-bar">
                                        <ul>
                                            <li><a href="#" target="_blank" id="share-facebook" class="bs-tt"
                                                    title="Share on Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                                            </li>
                                            <li><a href="#" target="_blank" id="share-linkedin" class="bs-tt"
                                                    title="Share on Linkedin"><i class="fa-brands fa-linkedin"></i></a>
                                            </li>
                                            <li><a href="#" target="_blank" id="share-pinterest" class="bs-tt"
                                                    title="Share on Pinterest"><i class="fa-brands fa-pinterest"></i></a>
                                            </li>
                                            <li>
                                                <a href="#" target="_blank" id="share-twitter" class="bs-tt"
                                                    title="Share on X">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="align-baseline"
                                                        width="24" height="24" fill="#110c2d"
                                                        viewBox="0 0 256 256">
                                                        <path
                                                            d="M215,219.85a8,8,0,0,1-7,4.15H160a8,8,0,0,1-6.75-3.71l-40.49-63.63L53.92,221.38a8,8,0,0,1-11.84-10.76l61.77-68L41.25,44.3A8,8,0,0,1,48,32H96a8,8,0,0,1,6.75,3.71l40.49,63.63,58.84-64.72a8,8,0,0,1,11.84,10.76l-61.77,67.95,62.6,98.38A8,8,0,0,1,215,219.85Z">
                                                        </path>
                                                    </svg>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    <!-- social-share-course-sidebar end -->
                                </div>
                            </div>
                            <!-- course single sidebar end-->

                            <!-- course single sidebar -->
                            <div class="course-single-information last">
                                <h5 class="title">Audience</h5>
                                <div class="body">
                                    <!-- ingle check -->
                                    <div class="single-check">
                                        <i class="fa-light fa-circle-check"></i>
                                        Suitable for {{ $course->level }} students
                                    </div>
                                    <!-- ingle check end -->
                                </div>
                            </div>
                            <!-- course single sidebar end-->
                        </div>
                        <!-- single course-sidebar end -->
                    </div>
                    <!-- right- sticky bar area end -->
                </div>
            </div>
        </div>
    </div>
    <!-- course details area end -->
    <script>
        const currentURL = encodeURIComponent(window.location.href);
        const courseTitle = encodeURIComponent("{{ $course->title }}");

        document.getElementById('share-facebook').href = `https://www.facebook.com/sharer/sharer.php?u=${currentURL}`;
        document.getElementById('share-linkedin').href =
            `https://www.linkedin.com/sharing/share-offsite/?url=${currentURL}`;
        document.getElementById('share-pinterest').href =
            `https://pinterest.com/pin/create/button/?url=${currentURL}&description=${courseTitle}`;
        document.getElementById('share-twitter').href =
            `https://twitter.com/intent/tweet?text=${courseTitle}&url=${currentURL}`;
    </script>

@endsection

@section('extra-styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css">
    <style>
        .gallery-card {
            position: relative;
            overflow: hidden;
            border-radius: 12px;
            aspect-ratio: 4/3;
            box-shadow: 1px 1px 9px #d9d9d9;
        }

        .gallery-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: .3s ease;
        }

        .gallery-overlay {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(0, 0, 0, .4);
            opacity: 0;
            transition: .3s ease;
            color: #fff;
        }

        .gallery-card:hover img {
            transform: scale(1.05);
        }

        .gallery-card:hover .gallery-overlay {
            opacity: 1;
        }

        .gslide-title {
            margin-bottom: 0px !important;
        }
    </style>
@endsection

@section('extra-scripts')

    <script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>
    <script>
        GLightbox();
    </script>

    <script type="application/ld+json">
    {
        "@context":"https://schema.org",
        "@type":"Course",
        "name":"{{ $course->title }}",
        "description":"{{ strip_tags($course->short_description) }}",
        "provider":{
            "@type":"Organization",
            "name":"{{ config('app.name') }}",
            "url":"{{ url('/') }}"
        },
        "image":"{{ lms_storage($course->thumbnail) }}",
        "url":"{{ url()->current() }}",
        "educationalLevel":"{{ $course->level }}",
        "courseCode":"{{ $course->id }}"
    }
    </script>

    @if ($faqs && !empty($faqs->name[0]))
        <script type="application/ld+json">
        {
            "@context":"https://schema.org",
            "@type":"FAQPage",
            "mainEntity":[
                @foreach($faqs->name as $k => $question)
                {
                    "@type":"Question",
                    "name":{!! json_encode(strip_tags($question), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!},
                    "acceptedAnswer":{
                        "@type":"Answer",
                        "text":{!! json_encode(strip_tags($faqs->value[$k]), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
                    }
                }@if(!$loop->last),@endif
                @endforeach
            ]
        }
        </script>
    @endif

    <script type="application/ld+json">
        {
            "@context":"https://schema.org",
            "@type":"BreadcrumbList",
            "itemListElement":[
                {
                "@type":"ListItem",
                "position":1,
                "name":"Home",
                "item":"{{ url('/') }}"
                },
                {
                "@type":"ListItem",
                "position":2,
                "name":"Courses",
                "item":"{{ route('courses') }}"
                },
                {
                "@type":"ListItem",
                "position":3,
                "name":"{{ $course->title }}",
                "item":"{{ url()->current() }}"
                }
            ]
        }
    </script>
@endsection
