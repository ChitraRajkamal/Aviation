@extends('layouts.frontend.skeleton')

@section('title', 'My Reviews')

@section('content')

@include('layouts.frontend.dashboard-header')

<!-- rts dahboard-area-main-wrapper -->
<div class="dashboard--area-main pt--100">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-3">
                @include('layouts.frontend.dashboard-sidebar', ['activeMenu' => 'my-ratings'])
            </div>
            <div class="col-lg-9">
                <div class="right-sidebar-dashboard no-border no-padding">
                    <div class="row">
                        <div class="col-lg-12">
                            <!-- in progress course area -->
                            <div class="in-progress-course-wrapper  title-between-dashboard mb--10">
                                <h5 class="title mb-2">My Reviews ({{count($ratings)}})</h5>
                            </div>
                            <!-- in progress course area end -->

                            <!-- my course enroll wrapper -->
                            <div class="my-course-enroll-wrapper-board">
                                <!-- single course inroll -->
                                <div class="single-course-inroll-board head">
                                    <div class="name">
                                        <p>Type</p>
                                    </div>
                                    <div class="name">
                                        <p>Course / Exam</p>
                                    </div>
                                    <div class="enroll">
                                        <p>Your Rating</p>
                                    </div>
                                    <div class="enroll">
                                        <p>Submitted On</p>
                                    </div>
                                </div>
                                @if ($ratings->isEmpty())
                                    <div class="alert alert-light text-center my-5">
                                        <i class="fa-regular fa-info-circle fa-3x text-success mb-3"></i><br>Your ratings is empty
                                    </div>
                                @endif
                                @foreach ($ratings as $e)
									@php
										if(!$e->item) continue;
									@endphp
                                    <div class="single-course-inroll-board">
                                        <div class="name">
                                            <p>{{ucfirst($e->type)}}</p>
                                        </div>
                                        <div class="name">
                                            <p>
                                                {{$e->item->title}} 
                                                @if ($e->type == 'course')
                                                    <a href="{{route('courses.details', ['slug' => $e->item->slug])}}" class="fw-medium" target="_blank"><i class="fa-regular fa-eye text-danger"></i></a>
                                                @else
                                                    <a href="{{route('exams.details', ['slug' => $e->item->slug])}}" class="fw-medium" target="_blank"><i class="fa-regular fa-eye text-danger"></i></a>
                                                @endif
                                            </p>
                                        </div>
                                        <div class="enroll">
                                            <div class="stars">
                                                <i class="fa-solid fa-star bg-star-rating bs-tt" title="1"></i>
                                                <i class="fa-{{$e->rating > 1 ? 'solid' : 'regular'}} fa-star bg-star-rating bs-tt" title="2"></i>
                                                <i class="fa-{{$e->rating > 2 ? 'solid' : 'regular'}} fa-star bg-star-rating bs-tt" title="3"></i>
                                                <i class="fa-{{$e->rating > 3 ? 'solid' : 'regular'}} fa-star bg-star-rating bs-tt" title="4"></i>
                                                <i class="fa-{{$e->rating > 4 ? 'solid' : 'regular'}} fa-star bg-star-rating bs-tt" title="5"></i>
                                            </div>
                                        </div>
                                        <div class="enroll">
                                            <p>{{lms_format_date($e->created_at)}}</p>
                                        </div>
                                    </div>
                                @endforeach

                                <div class="custom-pagination">
                                    {{$ratings->links()}}
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