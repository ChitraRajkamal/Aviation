@extends('layouts.frontend.skeleton')

@section('title', 'Dashboard')

@section('content')

@include('layouts.frontend.dashboard-header')

<!-- rts dahboard-area-main-wrapper -->
<style>
    .single-dashboard-card{
        background: linear-gradient(0deg, #ffffff 0, #3c7dd126 100%) !important;
    }
    .single-dashboard-card .icon{
        box-shadow: 0 0 0 5px #e5effb, 0 0 0 10px #ffffff, 0 0 0 20px #e4ecf6 !important;
    }
</style>
<div class="dashboard--area-main pt--100">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-3">
                @include('layouts.frontend.dashboard-sidebar', ['activeMenu' => 'dashboard'])
            </div>
            <div class="col-lg-9">
                <div class="right-sidebar-dashboard no-border no-padding">
                    <div class="row">
                        <div class="col-md-8">
                            <div id="courseExamChart"></div>
                        </div>
                        <div class="col-md-4">
                            <div id="courseCompletionPie"></div>
                        </div>
                    </div>

                    <div class="row g-5">
                        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                            <!-- single dashboard-card -->
                            <div class="single-dashboard-card">
                                <div class="icon shadow-sm">
                                    <i class="fa-light fa-book-open-cover"></i>
                                </div>
                                <h5 class="title"><span class="counter">{{$course_enrolled}}</span></h5>
                                <p>Enrolled Courses</p>
                            </div>
                            <!-- single dashboard-card end -->
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                            <!-- single dashboard-card -->
                            <div class="single-dashboard-card">
                                <div class="icon shadow-sm">
                                    <i class="fa-regular fa-graduation-cap"></i>
                                </div>
                                <h5 class="title"><span class="counter">{{$course_enrolled - $course_completed}}</span></h5>
                                <p>In Progress Courses</p>
                            </div>
                            <!-- single dashboard-card end -->
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                            <!-- single dashboard-card -->
                            <div class="single-dashboard-card">
                                <div class="icon shadow-sm">
                                    <i class="fa-light fa-trophy"></i>
                                </div>
                                <h5 class="title"><span class="counter">{{$course_completed}}</span></h5>
                                <p>Completed Courses</p>
                            </div>
                            <!-- single dashboard-card end -->
                        </div>

                    </div>
                    <div class="row mt--40">
                        <div class="col-lg-12">
                            <!-- in progress course area -->
                            <div class="in-progress-course-wrapper  title-between-dashboard mb--10">
                                <h5 class="title">Last 5 Courses</h5>
                                <a href="{{route('my-courses')}}" class="more"><i class="fa-regular fa-list"></i> View All</a>
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
                                    <div class="rating d-none">
                                        <p>Rating</p>
                                    </div>
                                </div>
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
                                                <span class="badge bg-success rounded-lg">Completed</span>
                                            @else
                                                <span class="badge bg-dark">Active</span>
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
                                        <div class="rating d-none">
                                            <i class="fa-light fa-star"></i>
                                            <i class="fa-light fa-star"></i>
                                            <i class="fa-light fa-star"></i>
                                            <i class="fa-light fa-star"></i>
                                            <i class="fa-light fa-star"></i>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <!-- my course enroll wrapper end -->
                        </div>
                    </div>
                    <div class="row mt-0 g-5">
                        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                            <!-- single dashboard-card -->
                            <div class="single-dashboard-card">
                                <div class="icon shadow-sm">
                                    <i class="fa-light fa-book-open-cover"></i>
                                </div>
                                <h5 class="title"><span class="counter">{{$exam_enrolled}}</span></h5>
                                <p>Enrolled Exams</p>
                            </div>
                            <!-- single dashboard-card end -->
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                            <!-- single dashboard-card -->
                            <div class="single-dashboard-card">
                                <div class="icon shadow-sm">
                                    <i class="fa-regular fa-graduation-cap"></i>
                                </div>
                                <h5 class="title"><span class="counter">{{$exam_enrolled - $exam_attended}}</span></h5>
                                <p>In Progress Exams</p>
                            </div>
                            <!-- single dashboard-card end -->
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                            <!-- single dashboard-card -->
                            <div class="single-dashboard-card">
                                <div class="icon shadow-sm">
                                    <i class="fa-light fa-trophy"></i>
                                </div>
                                <h5 class="title"><span class="counter">{{$exam_attended}}</span></h5>
                                <p>Completed Exams</p>
                            </div>
                            <!-- single dashboard-card end -->
                        </div>

                    </div>
                    <div class="row mt--40 mb-5">
                        <div class="col-lg-12">
                            <!-- in progress course area -->
                            <div class="in-progress-course-wrapper  title-between-dashboard mb--10">
                                <h5 class="title">Last 5 Exams</h5>
                                <a href="{{route('my-exams')}}" class="more"><i class="fa-regular fa-list"></i> View All</a>
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
                                        <p>Action</p>
                                    </div>
                                    <div class="rating d-none">
                                        <p>Rating</p>
                                    </div>
                                </div>
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
                                                <span class="badge bg-success rounded-lg">Completed</span>
                                            @else
                                                <span class="badge bg-dark">Active</span>
                                            @endif                                            
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
                                        <div class="rating d-none">
                                            <i class="fa-light fa-star"></i>
                                            <i class="fa-light fa-star"></i>
                                            <i class="fa-light fa-star"></i>
                                            <i class="fa-light fa-star"></i>
                                            <i class="fa-light fa-star"></i>
                                        </div>
                                    </div>
                                @endforeach
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

@section('extra-scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        const courseExamChart = {
            chart: {
                type: 'bar',
                height: 400,
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 1000
                },
                toolbar: {
                    show: false
                },
                fontFamily: 'inherit',
            },
            title: {
                text: 'Student Progress Overview',
                align: 'center',
                style: {
                    fontSize: '16px',
                    fontWeight: 'bold',
                }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '40%',
                    endingShape: 'rounded'
                }
            },
            dataLabels: {
                enabled: true
            },
            xaxis: {
                categories: ['Courses', 'Exams'],
            },
            yaxis: {
                title: {
                    text: 'Count'
                }
            },
            tooltip: {
                shared: true,
                intersect: false
            },
            series: [
                {
                    name: 'Enrolled',
                    data: [{{ $course_enrolled }}, {{ $exam_enrolled }}]
                },
                {
                    name: 'In Progress',
                    data: [{{ ($course_enrolled - $course_completed) }}, {{ ($exam_enrolled - $exam_attended) }}]
                },
                {
                    name: 'Completed',
                    data: [{{ $course_completed }}, {{ $exam_attended }}]
                }
            ],
            colors: ['#7cb5ec', '#f7a35c', '#8085e9'],
            legend: {
                position: 'top'
            }
        };

        new ApexCharts(document.querySelector("#courseExamChart"), courseExamChart).render();

        const courseCompletionPie = {
            chart: {
                type: 'donut',
                height: 320,
                animations: {
                    enabled: true,
                    easing: 'easeout',
                    speed: 900
                },
                fontFamily: 'inherit',
            },
            title: {
                text: 'Courses vs Exams',
                align: 'center',
                margin: 30,
                style: {
                    fontSize: '16px',
                    fontWeight: 'bold',
                }
            },
            series: [{{ $course_enrolled }}, {{ $exam_enrolled }}],
            labels: ['Courses', 'Exams'],
            colors: ['#00b894', '#0984e3'],
            legend: {
                position: 'bottom'
            },
            dataLabels: {
                style: {
                    fontSize: '14px'
                }
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '50%'
                    }
                }
            }
        };

        new ApexCharts(document.querySelector("#courseCompletionPie"), courseCompletionPie).render();

    </script>
@endsection