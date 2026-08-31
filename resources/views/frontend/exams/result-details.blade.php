@extends('layouts.frontend.skeleton')
@section('title', $exam->title)
@php
@endphp

@section('content')
<style>
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
    .exam-prepare-info-each.result{
        text-transform: uppercase;
        transform: scale(1.04);
        font-weight: 600;
    }
    .exam-prepare-info-each.result.pass{
        background: #e6f9e6;
        color: #228b22;
        border: 1px solid;
        padding: 10px 10px 10px 16px;
        display: flex;
        align-items: center;
    }
    .exam-prepare-info-each.result.pass span{
        padding: 8px 16px;
        background: #228b22;
        color: #fff;
        border-radius: 8px;
    }
    .exam-prepare-info-each.result.fail{
        background: #fde6e6;
        color: #cc0000;
        border: 1px solid;
        padding: 10px 10px 10px 16px;
        display: flex;
        align-items: center;
    }
    .exam-prepare-info-each.result.fail span{
        padding: 8px 16px;
        background: #cc0000;
        color: #fff;
        border-radius: 8px;
    }
    .each-question{
        background: #553cdf1a;
        margin-bottom: 20px;
        border-radius: 10px;
        padding: 20px;
    }
    .each-question.question-correct{
        background: #e6f9e6;
        border: 1px solid #228b22;
    }
    .each-question.question-wrong{
        background: #fde6e6;
        border: 1px solid #cc0000;
    }
    .answer-fill{
        background: white !important;
        border-radius: 10px !important;
        padding: 6px 10px !important;
    }
    .correct-answer span{
        height: 30px;
    }
    .question-explanation{
        word-break: break-word;
        overflow-wrap: break-word;
    }
    .question-explanation mark{
        color: initial;
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

    .confetti-wrapper {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        overflow: hidden;
        z-index: 9999;
    }

    .confetti {
        position: absolute;
        width: 10px;
        height: 10px;
        background-color: red;
        opacity: 0.8;
        animation: 10s fall linear forwards;
    }

    @keyframes fall {
        0% {
            transform: translateY(-100px) rotate(0deg);
        }
        60% {
            transform: translateY(60vh) rotate(360deg);
            opacity: 0.6;
        }
        100% {
            transform: translateY(100vh) rotate(360deg);
            opacity: 0;
        }
    }
</style>
@if ($result->is_pass)    
    <script>
        function launchConfetti() {
        const wrapper = document.getElementById('confetti');
        for (let i = 0; i < 100; i++) {
            const confetti = document.createElement('div');
            confetti.classList.add('confetti');
            confetti.style.left = `${Math.random() * 100}%`;
            confetti.style.backgroundColor = `hsl(${Math.random() * 360}, 70%, 60%)`;
            confetti.style.animationDuration = `${Math.random() * 10 + 3}s`;
            wrapper.appendChild(confetti);
            setTimeout(() => confetti.remove(), 15000);
        }
        }
        setTimeout(() => {
            launchConfetti();
        }, 500);
    </script>
    <div class="confetti-wrapper" id="confetti"></div>
@endif
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
                        <a href="{{lms_exam_slug($exam, 'result')}}">Results</a>
                        <i class="fa-solid fa-chevron-right"></i>
                        <a class="active" href="#">Details</a>
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
                        <a href="{{ lms_exam_slug($exam, 'result') }}" class="btn btn-primary btn-lg"><i class="fa-regular fa-arrow-left"></i> Back to Results</a>
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
                <div class="course-details-btn-wrapper pb--20">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#sumaary" type="button" role="tab" aria-controls="home" aria-selected="true">Summary</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="contacts-tab" data-bs-toggle="tab" data-bs-target="#details" type="button" role="tab" aria-controls="contacts" aria-selected="false">Review Answers</button>
                        </li>
                    </ul>
                </div>
                <div class="tab-content mt--20" id="myTabContent">
                    <div class="tab-pane fade  show active" id="sumaary" role="tabpanel" aria-labelledby="home-tab">
                        <div class="course-content-wrapper">
                            <div class="exam-prepare-info">
                                <div class="exam-prepare-info-each">
                                    <label>Date/Time</label>
                                    <span>{{lms_format_date($result->created_at)}}</span>
                                </div>
                                <div class="exam-prepare-info-each result {{$result->is_pass ? 'pass' : 'fail'}}">
                                    <label>Result</label>
                                    <span>{{$result->is_pass ? 'PASS' : 'FAIL'}}</span>
                                </div>
                                <div class="d-lg-flex gap-4">
                                    <div class="exam-prepare-info-each">
                                        <label>Duration</label>
                                        <span>{{$exam->duration_text}}</span>
                                    </div>
                                    <div class="exam-prepare-info-each">
                                        <label>Time Taken</label>
                                        <span>{{lms_duration_to_string($result->time_taken)}}</span>
                                    </div>
                                </div>
                                <div class="d-lg-flex gap-4">
                                    <div class="exam-prepare-info-each">
                                        <label>Total Questions</label>
                                        <span>{{$result->total_questions}}</span>
                                    </div>
                                    <div class="exam-prepare-info-each">
                                        <label>Questions Answered</label>
                                        <span>{{$result->attempted}}</span>
                                    </div>
                                </div>
                                <div class="d-lg-flex gap-4">
                                    <div class="exam-prepare-info-each">
                                        <label>Total Marks</label>
                                        <span>{{$result->total_mark}}</span>
                                    </div>
                                    <div class="exam-prepare-info-each">
                                        <label>Pass Marks</label>
                                        <span>{{$result->pass_mark}}</span>
                                    </div>
                                </div>
                                <div class="d-lg-flex gap-4">
                                    <div class="exam-prepare-info-each">
                                        <label>Obtained Marks</label>
                                        <span>{{$result->obtained_mark}}</span>
                                    </div>
                                    <div class="exam-prepare-info-each">
                                        <label>Percentage</label>
                                        <span>{{$result->percentage}}%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="details" role="tabpanel" aria-labelledby="profile-tab">
                        <div class="course-content-wrapper-main">
                            <div class="questions">
                                @foreach ($answers as $qk => $ans)
                                    @php
                                        $q = json_decode($ans->question);
                                        $answers = explode(config('constants.ANSWER_DELIMITER'), $q->answer);
                                        $answers = array_values(array_unique(lms_array_filter($answers)));
                                    @endphp
                                    <div class="each-question question-{{$ans->is_correct ? 'correct' : 'wrong'}}">
                                        <h6 class="mb-1 d-flex justify-content-between">
                                            <span>
                                                Question #{{$qk+1}} 
                                            </span>
                                            @if ($q->marks != 0)
                                                <span class="text-{{$ans->is_correct ? 'success' : 'danger'}} fw-normal">{{$q->marks}} {{lms_plural('mark', $q->marks)}}</span>
                                            @endif
                                        </h6>
                                        <hr class="mb-3">
                                        <div class="quesiton-text">{!! $q->title !!}</div>
                                        <div class="answers mt-2">
                                            <div>Your Answer: <span class="fw-bold">{!!$ans->answer !== '' ? lms_get_answer_text($ans->answer) : '-'!!}</span></div>
                                        </div>
                                        <div class="correct-answer mt-3 d-flex justify-content-between align-items-center">
                                            <div>Correct Answer: <span class="text-success fw-bold">{!!lms_get_answer_text($q->answer)!!}</span></div>
                                            @if ($ans->is_correct)
                                                <span class="badge bg-success d-flex align-items-center gap-2"><i class="fa fa-check-circle"></i> CORRECT</span>
                                            @elseif($ans->answer)
                                                <span class="badge bg-danger d-flex align-items-center">WRONG</span>
                                            @else
                                                <span class="badge bg-dark d-flex align-items-center shadow">NOT ANSWERED</span>
                                            @endif
                                        </div>
                                        @if (isset($q->description) && $q->description)
                                            <div class="mt-3">Explanation:</div>
                                            <div class="question-explanation small">{!! $q->description !!}</div>
                                        @endif
                                    </div>
                                @endforeach                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 order-cl-2 order-lg-2 order-md-1 order-sm-1 order-1  rts-sticky-column-item">
                <!-- right- sticky bar area -->
                <div class="right-course-details">
                    <!-- single course-sidebar -->
                    <div class="course-side-bar">
                        <div class="thumbnail shadow">
                            <img src="{{asset('assets/images/' . ($result->is_pass ? 'passed-badge.png' : 'failed-badge.png'))}}" alt="">
                        </div>
                        <div class="price-area">                            
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