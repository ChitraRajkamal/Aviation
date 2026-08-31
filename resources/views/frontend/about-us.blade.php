@extends('layouts.frontend.skeleton')
@section('title', 'About First Fly Aviation Academy | Aviation Training in Chennai')
@section('content')

<div class="rts-bread-crumbarea-1 rts-section-gap bg_image">
  <div class="container">
      <div class="row">
          <div class="col-lg-12">
              <div class="breadcrumb-main-wrapper">
                  <h1 class="title">About Us</h1>
                  <!-- breadcrumb pagination area -->
                  <div class="pagination-wrapper">
                    <a href="{{ route('home') }}">Home</a>
                      <i class="fa-regular fa-chevron-right"></i>
                      <a class="active" href="javascript:;">About Us</a>
                  </div>
                  <!-- breadcrumb pagination area end -->
              </div>
          </div>
      </div>
  </div>
</div>

<div class="container py-5">
    {{-- ABOUT --}}
    <div class="row align-items-center gy-5 mb-5">
        <div class="col-lg-6">
            <span class="text-danger fw-bold text-uppercase">
                About First Fly Aviation Academy
            </span>
            <h2 class="mt-3 mb-4">
                Wings to Future
            </h2>
            <p>
                First Fly Aviation Academy is a leading aviation training institute committed to preparing students for successful careers in aviation, airports, airlines, travel, tourism, and emerging aviation technologies.
            </p>
            <p>
                Our mission is to provide practical aviation education that develops industry-ready professionals.
            </p>
        </div>
        <div class="col-lg-6">
            <img src="{{ asset('assets/images/about-us-banner.jpg') }}"
                 class="img-fluid rounded shadow"
                 alt="First Fly Aviation Academy">
        </div>
    </div>

    {{-- VISION & MISSION --}}
    <div class="row g-4 mb-5">
        <div class="col-lg-6">
            <div class="shadow p-5 h-100 bg-white">
                <h3 class="mb-4">
                    Our Vision
                </h3>
                <p>
                    To become India's most trusted aviation training institution by empowering students with knowledge, skills, and confidence.
                </p>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="shadow p-5 h-100 bg-white">
                <h3 class="mb-4">
                    Our Mission
                </h3>
                <ul class="mb-0">
                    <li>Deliver quality aviation education</li>
                    <li>Develop employability skills</li>
                    <li>Promote aviation professionalism</li>
                    <li>Create successful aviation careers</li>
                </ul>
            </div>
        </div>
    </div>
</div>

{{-- WHY STUDENTS CHOOSE US --}}
<section class="py-5 bg-light">
    <div class="container">

        <div class="text-center mb-5">
            <span class="text-danger fw-bold text-uppercase">
                Why Students Choose Us
            </span>
            <h2 class="mt-3">
                Why Students Choose First Fly Aviation Academy
            </h2>
        </div>

        <div class="row align-items-center g-5 g-md-4">

            <div class="col-lg-4">

                <div class="d-flex mb-5">
                    <div class="me-3">
                        <i class="fa-solid fa-book-open fs-1 text-danger"></i>
                    </div>
                    <div>
                        <h5>Industry-Relevant Curriculum</h5>
                        <p class="mb-0">
                            Courses aligned with aviation industry requirements.
                        </p>
                    </div>
                </div>

                <div class="d-flex">
                    <div class="me-3">
                        <i class="fa-solid fa-user-tie fs-1 text-danger"></i>
                    </div>
                    <div>
                        <h5>Experienced Faculty</h5>
                        <p class="mb-0">
                            Learn from professionals with real aviation experience.
                        </p>
                    </div>
                </div>

            </div>

            <div class="col-lg-4 text-center">
                <img src="{{ asset('assets/images/about-us-why-choose.jpg') }}"
                     class="img-fluid rounded shadow"
                     alt="Aviation Academy">
            </div>

            <div class="col-lg-4">

                <div class="d-flex mb-5">
                    <div class="me-3">
                        <i class="fa-solid fa-plane-departure fs-1 text-danger"></i>
                    </div>
                    <div>
                        <h5>Practical Learning</h5>
                        <p class="mb-0">
                            Hands-on training and simulations.
                        </p>
                    </div>
                </div>

                <div class="d-flex">
                    <div class="me-3">
                        <i class="fa-solid fa-briefcase fs-1 text-danger"></i>
                    </div>
                    <div>
                        <h5>Career Support</h5>
                        <p class="mb-0">
                            Placement guidance and interview preparation.
                        </p>
                    </div>
                </div>

            </div>

        </div>

    </div>
</section>

@endsection