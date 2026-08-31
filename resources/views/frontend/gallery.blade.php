@extends('layouts.frontend.skeleton')
@section('title', 'Student Activities & Aviation Training Gallery | First Fly Aviation Academy')

@section('content')

    <div class="rts-bread-crumbarea-1 rts-section-gap bg_image">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-main-wrapper">
                        <h1 class="title">Gallery</h1>
                        <div class="pagination-wrapper">
                            <a href="{{ route('home') }}">Home</a>
                            <i class="fa-regular fa-chevron-right"></i>
                            <a class="active" href="javascript:;">Gallery</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="rts-section-gapTop pb--80">
        <div class="container-sm">

            <div class="text-center mb-5">
                <span class="text-danger fw-bold text-uppercase">Experience Aviation Learning</span>
                <h2 class="mt-3">Explore Student Activities</h2>
                <p>Cabin Crew Practical Training • Airport Visits • Industry Workshops • Interview Preparation Sessions •
                    Grooming Classes • Guest Lectures • Graduation Ceremonies • Student Success Stories</p>
            </div>

            <div class="gallery-filters mb-5">
                <ul class="gallery-tabs">
                    <li><button class="active filter-btn" data-filter="all">All</button></li>
                    <li><button class="filter-btn" data-filter="aviation-training">Aviation Training</button></li>
                    <li><button class="filter-btn" data-filter="airport-exposure">Airport Exposure</button></li>
                    <li><button class="filter-btn" data-filter="cabin-crew-activities">Cabin Crew Activities</button></li>
                    <li><button class="filter-btn" data-filter="student-achievements">Student Achievements</button></li>
                    <li><button class="filter-btn" data-filter="workshops-and-seminars">Workshops & Seminars</button></li>
                    <li><button class="filter-btn" data-filter="placement-events">Placement Events</button></li>
                </ul>
            </div>

            <div class="row g-4">

                {{-- Aviation Training --}}
                @php
                    $gallery = [
                        'aviation-training-classroom-session.jpg' => 'Aviation Training Classroom Session',
                        'aircraft-systems-training.jpg' => 'Aircraft Systems Training',
                        'aviation-safety-training.jpg' => 'Aviation Safety Training',
                        'flight-simulator-training.jpg' => 'Flight Simulator Training',
                    ]
                @endphp
                @foreach ($gallery as $path => $title)
                    <div class="col-lg-3 col-md-4 col-sm-6 gallery-item aviation-training">
                        <a href="{{ asset('assets/images/gallery/aviation-training/' . $path) }}" class="glightbox"
                            data-gallery="gallery" data-title="{{ $title }}">
                            <div class="gallery-card">
                                <img src="{{ asset('assets/images/gallery/aviation-training/' . $path) }}"
                                    alt="Aviation Training at First Fly Aviation Academy">
                                <div class="gallery-overlay">
                                    <div>
                                        <h6 class="text-white mb-1">
                                            {{ $title }}
                                        </h6>
                                        <small>Click to View</small>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach

                {{-- Airport Exposure --}}
                @php
                    $gallery = [
                        'students-visiting-airport-terminal.jpg' => 'Students Visiting Airport Terminal',
                        'students-near-aircraft-apron.jpg' => 'Students Near Aircraft Apron',
                        'airport-operations-briefing.jpg' => 'Airport Operations Briefing',
                        'airport-ground-handling-exposure.jpg' => 'Airport Ground Handling Exposure',
                    ]
                @endphp
                @foreach ($gallery as $path => $title)
                    <div class="col-lg-3 col-md-4 col-sm-6 gallery-item airport-exposure">
                        <a href="{{ asset('assets/images/gallery/airport-exposure/' . $path) }}" class="glightbox"
                            data-gallery="gallery" data-title="{{ $title }}">
                            <div class="gallery-card">
                                <img src="{{ asset('assets/images/gallery/airport-exposure/' . $path) }}"
                                    alt="Aviation Training at First Fly Aviation Academy">
                                <div class="gallery-overlay">
                                    <div>
                                        <h6 class="text-white mb-1">
                                            {{ $title }}
                                        </h6>
                                        <small>Click to View</small>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach

                {{-- Cabin Crew --}}
                @php
                    $gallery = [
                        'cabin-crew-safety-demonstration.jpg' => 'Cabin Crew Safety Demonstration',
                        'inflight-service-training.jpg' => 'Inflight Service Training',
                        'cabin-crew-team-activity.jpg' => 'Cabin Crew Team Activity',
                        'passenger-assistance-training.jpg' => 'Passenger Assistance Training',
                    ]
                @endphp
                @foreach ($gallery as $path => $title)
                    <div class="col-lg-3 col-md-4 col-sm-6 gallery-item cabin-crew-activities">
                        <a href="{{ asset('assets/images/gallery/cabin-crew-activities/' . $path) }}" class="glightbox"
                            data-gallery="gallery" data-title="{{ $title }}">
                            <div class="gallery-card">
                                <img src="{{ asset('assets/images/gallery/cabin-crew-activities/' . $path) }}"
                                    alt="Aviation Training at First Fly Aviation Academy">
                                <div class="gallery-overlay">
                                    <div>
                                        <h6 class="text-white mb-1">
                                            {{ $title }}
                                        </h6>
                                        <small>Click to View</small>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach

                {{-- Achievements --}}
                @php
                    $gallery = [
                        'student-receiving-award.jpg' => 'Student Receiving Award',
                        'graduation-ceremony.jpg' => 'Graduation Ceremony',
                        'certificate-distribution.jpg' => 'Certificate Distribution',
                        'student-success-celebration.jpg' => 'Student Success Celebration',
                    ]
                @endphp
                @foreach ($gallery as $path => $title)
                    <div class="col-lg-3 col-md-4 col-sm-6 gallery-item student-achievements">
                        <a href="{{ asset('assets/images/gallery/student-achievements/' . $path) }}" class="glightbox"
                            data-gallery="gallery" data-title="{{ $title }}">
                            <div class="gallery-card">
                                <img src="{{ asset('assets/images/gallery/student-achievements/' . $path) }}"
                                    alt="Aviation Training at First Fly Aviation Academy">
                                <div class="gallery-overlay">
                                    <div>
                                        <h6 class="text-white mb-1">
                                            {{ $title }}
                                        </h6>
                                        <small>Click to View</small>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach

                {{-- Workshops --}}
                @php
                    $gallery = [
                        'aviation-industry-workshop.jpg' => 'Aviation Industry Workshop',
                        'guest-lecture-session.jpg' => 'Guest Lecture Session',
                        'interactive-aviation-seminar.jpg' => 'Interactive Aviation Seminar',
                        'career-development-workshop.jpg' => 'Career Development Workshop',
                    ]
                @endphp
                @foreach ($gallery as $path => $title)
                    <div class="col-lg-3 col-md-4 col-sm-6 gallery-item workshops-and-seminars">
                        <a href="{{ asset('assets/images/gallery/workshops-and-seminars/' . $path) }}" class="glightbox"
                            data-gallery="gallery" data-title="{{ $title }}">
                            <div class="gallery-card">
                                <img src="{{ asset('assets/images/gallery/workshops-and-seminars/' . $path) }}"
                                    alt="Aviation Training at First Fly Aviation Academy">
                                <div class="gallery-overlay">
                                    <div>
                                        <h6 class="text-white mb-1">
                                            {{ $title }}
                                        </h6>
                                        <small>Click to View</small>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach

                {{-- Placements --}}
                @php
                    $gallery = [
                        'placement-interview-session.jpg' => 'Placement Interview Session',
                        'campus-recruitment-drive.jpg' => 'Campus Recruitment Drive',
                        'hr-interaction-session.jpg' => 'HR Interaction Session',
                        'selected-students-group-photo.jpg' => 'Selected Students Group Photo',
                    ]
                @endphp
                @foreach ($gallery as $path => $title)
                    <div class="col-lg-3 col-md-4 col-sm-6 gallery-item placement-events">
                        <a href="{{ asset('assets/images/gallery/placement-events/' . $path) }}" class="glightbox"
                            data-gallery="gallery" data-title="{{ $title }}">
                            <div class="gallery-card">
                                <img src="{{ asset('assets/images/gallery/placement-events/' . $path) }}"
                                    alt="Aviation Training at First Fly Aviation Academy">
                                <div class="gallery-overlay">
                                    <div>
                                        <h6 class="text-white mb-1">
                                            {{ $title }}
                                        </h6>
                                        <small>Click to View</small>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach

            </div>

        </div>
    </section>

@endsection

@section('extra-styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css">
    <style>
        .gallery-filters .btn {
            margin: 4px
        }

        .gallery-card {
            position: relative;
            overflow: hidden;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, .08);
            background: #fff;
        }

        .gallery-card img {
            width: 100%;
            height: 280px;
            object-fit: cover;
            display: block;
            transition: .5s;
        }

        .gallery-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top,
                    rgba(0, 0, 0, .8),
                    rgba(0, 0, 0, .1));
            display: flex;
            align-items: flex-end;
            padding: 20px;
            opacity: 0;
            transition: .4s;
            color: #fff;
        }

        .gallery-card:hover img {
            transform: scale(1.08);
        }

        .gallery-card:hover .gallery-overlay {
            opacity: 1;
        }

        .gallery-tabs {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 12px;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .gallery-tabs li {
            margin: 0;
        }

        .gallery-tabs button {
            border: none;
            background: #fff;
            color: var(--color-primary);
            padding: 12px 22px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 13px;
            transition: .3s;
            box-shadow: 0 4px 15px rgba(0, 0, 0, .08);
        }

        .gallery-tabs button:hover,
        .gallery-tabs button.active {
            background: var(--color-primary);
            color: #fff;
        }
        .gslide-title{
            margin-bottom: 0px !important;
        }

        @media(max-width:991px) {
            .gallery-card img {
                height: 240px
            }
        }

        @media(max-width:767px) {
            .gallery-card img {
                height: 220px
            }
        }
    </style>
@endsection

@section('extra-scripts')
    <script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>
    <script>
        GLightbox();
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.filter-btn').forEach(x => {
                    x.classList.remove('btn-danger', 'active');
                    x.classList.add('btn-outline-danger');
                });
                this.classList.remove('btn-outline-danger');
                this.classList.add('btn-danger', 'active');
                let filter = this.dataset.filter;
                document.querySelectorAll('.gallery-item').forEach(item => {
                    item.style.display = (filter === 'all' || item.classList.contains(filter)) ?
                        'block' : 'none';
                });
            });
        });
    </script>
@endsection
