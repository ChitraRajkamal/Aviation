@extends('layouts.frontend.skeleton')

@section('title', 'My Jobs')

@section('content')

@include('layouts.frontend.dashboard-header')

<!-- rts dahboard-area-main-wrapper -->
<div class="dashboard--area-main pt--100">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-3">
                @include('layouts.frontend.dashboard-sidebar', ['activeMenu' => 'my-job-posts'])
            </div>
            <div class="col-lg-9">
                <div class="right-sidebar-dashboard no-border no-padding">
                    <div class="row">
                        <div class="col-lg-12">
                            <!-- in progress course area -->
                            <div class="in-progress-course-wrapper  title-between-dashboard mb--10">
                                <h5 class="title">My Jobs</h5>
                            </div>
                            <!-- in progress course area end -->

                            <!-- my course enroll wrapper -->
                            <div class="my-course-enroll-wrapper-board">
                                <!-- single course inroll -->
                                <div class="single-course-inroll-board head">
                                    <div class="enroll">
                                        <p>Thumb</p>
                                    </div>
                                    <div class="name">
                                        <p>Title</p>
                                    </div>
                                    <div class="enroll">
                                        <p>Price</p>
                                    </div>
                                    <div class="enroll">
                                        <p>Enrolled On</p>
                                    </div>
                                    <div class="enroll">
                                        <p>Link</p>
                                    </div>
                                </div>
                                @if ($enrolls->isEmpty())
                                    <div class="alert alert-light text-center my-5">
                                        <i class="fa-regular fa-info-circle fa-3x text-success mb-3"></i><br>No jobs to be displayed
                                    </div>
                                @endif
                                @foreach ($enrolls as $e)
                                    @php
                                        $jobPost = $e->job_post;
                                    @endphp
                                    <div class="single-course-inroll-board">
                                        <div class="enroll">
                                            <img style="height: 50px;" src="{{lms_image($jobPost->thumbnail)}}" alt="{{ $jobPost->title }}">
                                        </div>
                                        <div class="name">
                                            <p>{{$jobPost->title}}</p>
                                        </div>
                                        <div class="enroll">
                                            <p>{!!lms_show_price($jobPost)!!}</p>
                                        </div>
                                        <div class="enroll">
                                            <p>{{lms_format_date($e->created_at)}}</p>
                                        </div>
                                        <div class="enroll">
                                            <a href="{{lms_storage($jobPost->link)}}" target="_blank">
                                                <i class="fa-light fa-external-link"></i> View
                                            </a>
                                        </div>
                                    </div>
                                @endforeach

                                <div class="custom-pagination">
                                    {{$enrolls->links()}}
                                </div>
                            </div>
                            <!-- my course enroll wrapper end -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- rts dahboard-area-main-wrapper end -->

@endsection