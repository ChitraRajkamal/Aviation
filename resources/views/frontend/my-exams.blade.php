@extends('layouts.frontend.skeleton')

@section('title', 'My Exams')

@section('content')

@include('layouts.frontend.dashboard-header')

<!-- rts dahboard-area-main-wrapper -->
<div class="dashboard--area-main pt--100">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-3">
                @include('layouts.frontend.dashboard-sidebar', ['activeMenu' => 'my-exams'])
            </div>
            <div class="col-lg-9">
                <div class="right-sidebar-dashboard no-border no-padding">
                    <div class="row">
                        <div class="col-lg-12">
                            <!-- in progress course area -->
                            <div class="in-progress-course-wrapper  title-between-dashboard mb--10">
                                <h5 class="title">My Exams</h5>
                            </div>
                            <!-- in progress course area end -->

                            <!-- my course enroll wrapper -->
                            <div class="my-course-enroll-wrapper-board">
                                <!-- single course inroll -->
                                <div class="single-course-inroll-board head">
                                    <div class="name">
                                        <p>Exam</p>
                                    </div>
                                    <div class="enroll">
                                        <p>Enrolled On</p>
                                    </div>
                                    <div class="enroll">
                                        <p>Status</p>
                                    </div>
                                    <div class="enroll">
                                        <p>Attempts</p>
                                    </div>
                                    <div class="enroll">
                                        <p>Action</p>
                                    </div>

                                </div>
                                @if ($exams->isEmpty())
                                    <div class="alert alert-light text-center my-5">
                                        <i class="fa-regular fa-info-circle fa-3x text-success mb-3"></i><br>No exams to be displayed
                                    </div>
                                @endif
                                @foreach ($exams as $e)
                                    <div class="single-course-inroll-board">
                                        <div class="name">
                                            <p>{{$e->exam->title}}</p>
                                        </div>
                                        <div class="enroll">
                                            <p>{{lms_format_date($e->created_at)}}</p>
                                        </div>
                                        <div class="enroll">
                                            @if ($e->is_attended)
                                                <p class="badge bg-success">Completed</p>
                                            @else
                                                <p class="badge bg-dark">Active</p>
                                            @endif
                                        </div>
                                        <div class="enroll">
                                            <p>{{$e->attempt_count()}}</p>
                                        </div>
                                        <div class="enroll">
                                            @if ($e->exam->status == 1)
                                                @if ($e->is_attended)
                                                    <a href="{{route('exams.result', ['slug' => $e->exam->slug])}}" class="fw-medium"><i class="fa-regular fa-tags"></i> View Result</a>
                                                @else
                                                    <a href="{{route('exams.attend', ['slug' => $e->exam->slug])}}" class="fw-medium"><i class="fa-regular fa-check"></i> Attend Exam</a>
                                                @endif
                                            @else
                                                <s><i class="fa fa-exclamation-triangle text-danger"></i> Exam Disabled</s>
                                            @endif
                                        </div>

                                    </div>
                                @endforeach

                                <div class="custom-pagination">
                                    {{$exams->links()}}
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
