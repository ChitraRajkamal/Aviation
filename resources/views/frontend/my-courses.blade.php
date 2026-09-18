@extends('layouts.frontend.skeleton')

@section('title', content: 'My Courses')

@section('content')

@include('layouts.frontend.dashboard-header')

<!-- rts dahboard-area-main-wrapper -->
<div class="dashboard--area-main pt--100">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-3">
                @include('layouts.frontend.dashboard-sidebar', ['activeMenu' => 'my-courses'])
            </div>
            <div class="col-lg-9">
                <div class="right-sidebar-dashboard no-border no-padding">
                    <div class="row">
                        <div class="col-lg-12">
                            <!-- in progress course area -->
                            <div class="in-progress-course-wrapper  title-between-dashboard mb--10">
                                <h5 class="title">My Courses</h5>
                            </div>
                            <!-- in progress course area end -->

                            <!-- my course enroll wrapper -->
                            <div class="my-course-enroll-wrapper-board">
                                <!-- single course inroll -->
                                <div class="single-course-inroll-board head">
                                    <div class="name">
                                        <p>Course</p>
                                    </div>
                                    <div class="enroll">
                                        <p>Enrolled On</p>
                                    </div>
                                    <div class="enroll">
                                        <p>Status</p>
                                    </div>
                                    <div class="enroll">
                                        <p>Action</p>
                                    </div>

                                </div>
                                @if ($courses->isEmpty())
                                    <div class="alert alert-light text-center my-5">
                                        <i class="fa-regular fa-info-circle fa-3x text-success mb-3"></i><br>No courses to be displayed
                                    </div>
                                @endif
                                @foreach ($courses as $e)
                                    <div class="single-course-inroll-board">
                                        <div class="name">
                                            <p>{{$e->course->title}}</p>
                                        </div>
                                        <div class="enroll">
                                            <p>{{lms_format_date($e->created_at)}}</p>
                                        </div>
                                        <div class="enroll">
                                            @if ($e->is_completed)
                                                <p class="badge bg-success">Completed</p>
                                            @else
                                                <p class="badge bg-dark">Active</p>
                                            @endif
                                        </div>
                                        <div class="enroll">
                                            @if ($e->course->status == 'Active')
                                                @if ($e->is_completed)
                                                    <a href="{{route('courses.details', ['slug' => $e->course->slug])}}" class="fw-medium"><i class="fa-regular fa-tags"></i> View Course</a>
                                                @else
                                                    <a href="{{route('courses.stage', ['slug' => $e->course->slug])}}" class="fw-medium"><i class="fa-regular fa-check"></i> Start Now</a>
                                                @endif
                                            @else
                                                <s><i class="fa fa-exclamation-triangle text-danger"></i> Course Disabled</s>
                                            @endif
                                        </div>

                                    </div>
                                @endforeach

                                <div class="custom-pagination">
                                    {{$courses->links()}}
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
