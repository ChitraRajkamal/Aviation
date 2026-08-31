@extends('layouts.frontend.skeleton')
@section('title', $exam->title)
@php
    $question_count = $exam->question_count();
@endphp

@section('content')
<style>
    .module-wrapper:empty{
        display: none;
    }
    .accordion-button{
        font-size: 21px;
    }
    .exam-prepare-info{
        font-size: 18px;
    }
    .exam-prepare-info-each{
        display: flex;
        justify-content: space-between;
        width: 100%;
        margin-bottom: 20px;
        background: #553cdf1f;
        background: linear-gradient(91deg, #e1e1e1 0, #553cdf4a 100%);
        padding: 16px 20px;
        border-radius: 10px;
        box-shadow: 2px 2px 1px #ccc;
    }
    .exam-prepare-info-each > span:last-child{
        color: black;
    }
    @media screen and (min-width: 1200px) {
        .right-course-details{
            margin-top: -250px;
        }
    }
    @media screen and (max-width: 768px) {
        .exam-prepare-info{
            font-size: 14px;
        }
        .exam-prepare-info-each{
            margin-bottom: 10px;
            padding: 10px 16px;
        }
    }
</style>
<!-- course details breadcrumb -->
<div class="course-details-breadcrumb-1 bg_image rts-section-gap">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="single-course-left-align-wrapper">
                    <div class="meta-area">
                        <a href="{{url('')}}">Home</a>
                        <i class="fa-solid fa-chevron-right"></i>
                        <a href="{{route('exams')}}">Exams</a>
                        <i class="fa-solid fa-chevron-right"></i>
                        <a href="{{lms_exam_slug($exam)}}">{{$exam->title}}</a>
                        <i class="fa-solid fa-chevron-right"></i>
                        <a class="active" href="#">Prepare</a>
                    </div>
                    <h1 class="title my-2">
                        {{$exam->title}}
                    </h1>
                    <div class="author-area mt-1">
                        <div class="author">
                            <img src="{{asset('admin-assets/assets/images/profile-40.png')}}" alt="Instructor">
                            <div class="name fw-medium"><span>By</span> {{$exam->user->first_name}}.</div>
                        </div>
                        <p class="mb-0"> <span>Category: </span> {{$exam->category->title}}</p>
                        <a href="{{ lms_exam_slug($exam, 'attend') }}" class="btn btn-warning text-dark btn-lg"><i class="fa-regular fa-play text-dark"></i> Attend Exam</a>
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
            <div class="col-lg-8">
                <div class="exam-prepare-info">
                    <div class="d-lg-flex gap-4">
                        <div class="exam-prepare-info-each">
                            <label>Category</label>
                            <span>{{$exam->category->title}}</span>
                        </div>
                        <div class="exam-prepare-info-each">
                            <label>Duration</label>
                            <span>{{$exam->duration_text}}</span>
                        </div>
                    </div>                    
                    <div class="d-lg-flex gap-4">
                        <div class="exam-prepare-info-each">
                            <label>Total Marks</label>
                            <span>{{$exam->total_mark}}</span>
                        </div>
                        <div class="exam-prepare-info-each">
                            <label>Pass Marks</label>
                            <span>{{$exam->pass_mark}}</span>
                        </div>
                    </div>                    
                    <div class="d-lg-flex gap-4">
                        <div class="exam-prepare-info-each">
                            <label>Reattempt</label>
                            <span>{{$exam->retake ? $exam->retake : 'Unlimited'}}</span>
                        </div>
                        <div class="exam-prepare-info-each">
                            <label>Total Questions</label>
                            <span>{{$exam->question_count()}}</span>
                        </div>
                    </div>                    
                </div>
            </div>
            <div class="col-lg-4 order-cl-2 order-lg-2 order-md-1 order-sm-1 order-1  rts-sticky-column-item">
                <!-- right- sticky bar area -->
                <div class="right-course-details">
                    <!-- single course-sidebar -->
                    <div class="course-side-bar">
                        @if ($exam->thumbnail)
                            <div class="thumbnail">
                                <img src="{{lms_storage($exam->thumbnail)}}" alt="">
                            </div>
                        @endif
                        <div class="price-area {{$exam->thumbnail ? 'mt-5' : 'mt-0'}}">
                            
                            @if ($exam->discount_flag)
                                <h3 class="title">{!!lms_show_price($exam, 'discounted_price')!!}</h3>
                                <h4 class="none">{!!lms_show_price($exam)!!}</h4>
                                <span class="discount">-{{(100 - round(($exam->discounted_price/$exam->price) * 100, 2))}}%</span>
                            @else
                                <h3 class="title">{!!lms_show_price($exam)!!}</h3>
                            @endif
                        </div>
                        @php
                            /*
                            <div class="clock-area">
                                <i class="fa-light fa-clock"></i>
                                <span>2 Day left at this price!</span>
                            </div>*/
                        @endphp

                        <a href="{{lms_exam_slug($exam, 'attend')}}" class="rts-btn btn-primary">ATTEND EXAM</a>

                        <div class="what-includes">
                            <h5 class="title">This exam includes: </h5>
                            <div class="single-include">
                                <div class="left">
                                    <i class="fa-light fa-timer"></i>
                                    <span>Duration</span>
                                </div>
                                <div class="right">
                                    <span>{{$exam->duration_text}}</span>
                                </div>
                            </div>
                            <div class="single-include">
                                <div class="left">
                                    <i class="fa-regular fa-tag"></i>
                                    <span>Category</span>
                                </div>
                                <div class="right">
                                    <span>{{$exam->category->title}}</span>
                                </div>
                            </div>
                            <div class="single-include">
                                <div class="left">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                    <span>Update</span>
                                </div>
                                <div class="right">
                                    <span>{{lms_format_date($exam->updated_at)}}</span>
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
            </div>
        </div>
    </div>
</div>
<!-- course details area end -->

@endsection