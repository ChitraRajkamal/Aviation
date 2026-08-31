@extends('layouts.frontend.skeleton')
@section('title', $course->title . ' - ' . __('Course Stage'))
@php
    $delimiter = config('constants.ANSWER_DELIMITER');
    $question_count = $currentLesson->question_count(check_status: true);
    $sections = $course->sections;
    $lesson_type = $currentLesson->lesson_type;
    $settings = [
        'warnings' => 1,
        'skip' => true,
        'alt_tab' => true,
        'duration' => $currentLesson->duration
    ];
    $result_count = count($results);
    $completed_lesson_quiz_count = count($enroll->lesson_ids);
    if(!$enroll->is_completed && $completed_lesson_quiz_count > 0) $completed_lesson_quiz_count--;
    $total_lesson_quiz_count = $course->lesson_quiz_count();
    $accute_completed_percentage = ($completed_lesson_quiz_count * 100) / $total_lesson_quiz_count;
    $completed_percentage = sprintf("%.2f", round($accute_completed_percentage, 2));
@endphp

@section('extra-styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/plyr.css') }}">
@endsection

@section('extra-scripts')
    <script type="text/javascript" src="{{ asset('assets/js/plyr.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if($('.plyr__video-embed').length > 0){
                const players = Array.from(document.querySelectorAll('.plyr__video-embed')).map(player => 
                    new Plyr(player, {
                        autoplay: false,
                        controls: ['play', 'progress', 'current-time', 'mute', 'volume', 'captions', 'fullscreen'],
                        youtube: { noCookie: true, modestbranding: true },
                        vimeo: { dnt: true }
                    })
                );
            }
        });

        $(document).ready(function () {
            $('.header--sticky').removeClass('header--sticky');
            var header = $("#course-stage-header");
            var stickyOffset = header.offset().top;

            $(window).scroll(function () {
                if ($(window).scrollTop() > stickyOffset) {
                    if (!header.hasClass("sticky")) {
                        header.addClass("sticky fade-in-down").removeClass("fade-in-up");
                    }
                } else {
                    if (header.hasClass("sticky")) {
                        header.addClass("fade-in-up").removeClass("fade-in-down");
                        setTimeout(function () {
                            header.removeClass("sticky"); // Remove sticky after animation
                        }, 300);
                    }
                }
            });
        });
    </script>
@endsection

@section('content')
<style>
    .course-progress{
        width: 100%;
        height: 2px;
        background: #ccc;
        position: relative;
    }
    .course-progress::before{
        content: '';
        position: absolute;
        width: 0%;
        height: 6px;
        background: #1f075d;
        top: -2px;
        border-radius: 10px;
        transition: width 0.5s;
        animation: progressAnimation 1s linear forwards;
    }
    /* Keyframes for progress animation */
    @keyframes progressAnimation {
        from {
            width: 0%;
        }
        to {
            width: {{$accute_completed_percentage}}%;
        }
    }
    .play-vedio-wrapper{
        align-items: start !important;
    }
    .play-vedio-wrapper .left span{
        text-decoration-line: none !important;
    }
    .play-vedio-wrapper.active{
        background: #1f075d17;
        background: linear-gradient(270deg, #ff1ef047 0, #3c7dd100 100%) !important;
        padding: 7px 10px !important;
        border-radius: 5px;
        transform: scale(1.06);
        border: 2px solid #000;
        margin-bottom: 5px;
    }
    .play-vedio-wrapper .left{
        flex-grow: 1;
    }
    .play-vedio-wrapper .right{
        background: #110C2D;
        color: #fff;
        padding: 0px 8px;
        border-radius: 50px;
        flex-grow: 0;
        flex-shrink: 0;
    }
    .course-content-wrapper-main .accordion .accordion-header button{
        background: #eeeeee;
    }
    .plyr__video-embed{
        max-height: 560px;
    }
    .plyr--fullscreen-enabled .plyr__video-embed {
        max-height: none !important;
    }
    .video-container{
        box-shadow: 1px 1px 10px #ccc;
    }
    .current-lesson-title{
        line-height: 30px;
    }

    #course-stage-header {
        transition: all 0.3s ease-in-out;
    }
    #course-stage-header.sticky {
        position: fixed;
        width: 100%;
        z-index: 1000;
        top: 0;
    }
    #course-stage-header.sticky + .container{
        margin-top: 120px;
    }
    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .fade-in-down {
        animation: fadeInDown 0.3s ease-in-out;
    }
    @keyframes fadeInUp {
        from {
            opacity: 1;
            transform: translateY(0);
        }
        to {
            opacity: 0;
            transform: translateY(-20px);
        }
    }
    .fade-in-up {
        animation: fadeInUp 0.3s ease-in-out;
    }


    .widget-card {
        border-radius: 10px;
        color: #fff;
        padding: 14px;
        transition: transform 0.3s ease-in-out;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .widget-card h2, .widget-card h5{
        margin-bottom: 0px;
    }
    .widget-card h5{
        text-transform: uppercase;
        font-size: 15px;
        font-weight: 500;
    }
    .widget-card:hover {
        transform: translateY(-5px);
    }
    .widget-icon {
        font-size: 2.5rem;
        opacity: 0.8;
    }
    .widget-cards > div:nth-child(1) > div { background: linear-gradient(135deg, #badfff, #42A5F5); } 
    .widget-cards > div:nth-child(2) > div { background: linear-gradient(135deg, #9ffda3, #66BB6A); }
    .widget-cards > div:nth-child(3) > div { background: linear-gradient(135deg, #f99390, #EF5350); }
    .widget-cards > div:nth-child(4) > div { background: linear-gradient(135deg, #f9ea90, #efa750); }
    .exam-prepare-info{
        text-align: center;
        margin-top: 20px;
    }
    .exam-prepare-info .btn{
        font-size: 30px;
        padding: 20px 30px;
        text-transform: uppercase;
        background: var(--color-primary);
        border-radius: 0px 30px 0px 30px;
    }
    #count-down-timer{
        font-size: 30px;
    }
    .each-quesiton{
        background: #553cdf1a;
        margin-bottom: 20px;
        border-radius: 10px;
        padding: 20px;
    }
    .answer-fill{
        background: white !important;
        border-radius: 10px !important;
        padding: 6px 10px !important;
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
    .passed{
        background: #228b22;
        color: #e6f9e6 !important;
        border: 1px solid;
        padding: 2px 10px 0px 10px;
        border-radius: 50px;
    }
    .failed{
        background: #cc0000;
        color: #fde6e6 !important;
        border: 1px solid;
        padding: 2px 10px 0px 10px;
        border-radius: 50px;
    }
</style>
<!-- course details breadcrumb -->
<div id="course-stage-header" class="course-details-breadcrumb-1 bg_image pt-3 pb-2 text-center text-uppercase">
    <h3 class="text-light mb-0 fw-medium">{{$course->title}}</h3>
</div>
<!-- course details breadcrumb end -->

<!-- course details area start -->
<form id="formAttendExam" action="{{route('courses.stage.save_answers', ['slug' => $course->slug, 'lessonId' => $currentLesson->id])}}" method="POST">
    @csrf
    <input type="hidden" id="time_taken" name="time_taken">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                @if ($currentLesson->is_quiz)
                    <div class="mt-5 mb-4 d-flex flex-column">
                        <h3 class="mb-0 fw-medium current-lesson-title">{{$currentLesson->title}}</h3>
                        <h6 class="mb-0 text-muted bs-tt fw-normal" title="Quiz Duration">{{ $currentLesson->duration_text }}</h6>
                    </div>
                    @if (request()->input('start'))
                        <div class="d-flex gap-3 justify-content-center flex-column align-items-center">
                            <div>Answers will be submitted in </div>
                            <div id="count-down-timer">00:00:00</div>
                        </div>
                        <div class="questions mt-5">
                            @foreach ($questions as $qk => &$q)
                                @php
                                    $answers = explode($delimiter, $q->answer);
                                    $answers = array_values(array_unique(lms_array_filter($answers)));
                                    unset($q->answer); // This is to remove the answers from javascript array
                                @endphp
                                <input type="hidden" name="answer[{{$q->id}}][type]" value="{{$q->type}}">
                                <div class="each-quesiton">
                                    <h6 class="mb-1 d-flex justify-content-between">
                                        <span>
                                            Question #{{$qk+1}} 
                                            @if ($q->type == 'multiple' && count($answers) > 1)
                                                <small class="text-muted">
                                                    [Choose {{count($answers)}} {{ lms_plural('answer', count($answers)) }}]
                                                </small>
                                            @endif
                                        </span>
                                        <span class="text-success fw-normal">{{$q->marks}} {{lms_plural('mark', $q->marks)}}</span>
                                    </h6>
                                    <hr class="mb-3">
                                    <div class="quesiton-text">{!! $q->title !!}</div>
                                    <div class="answers mt-2">
                                        @if ($q->type == 'multiple')
                                            <div class="row">
                                                @foreach (explode($delimiter, $q->options) as $opk => $op)
                                                    @php
                                                        if(!trim($op)) continue;
                                                    @endphp
                                                    <div class="col-lg-12">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" id="checkbox_{{$q->id}}_{{$opk}}" name="answer[{{$q->id}}][answer][]" value="{{$op}}">
                                                            <label class="form-check-label" for="checkbox_{{$q->id}}_{{$opk}}">
                                                                {{$op}}
                                                            </label>
                                                        </div>                                                   
                                                    </div>
                                                @endforeach
                                            </div>
                                        @elseif ($q->type == 'fill')
                                            <input type="text" placeholder="Type answer" class="answer-fill" name="answer[{{$q->id}}][answer]" />
                                        @else
                                            <div class="d-flex gap-5">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="answer[{{$q->id}}][answer]" id="yesno_true_{{$q->id}}" value="true">
                                                    <label class="form-check-label" for="yesno_true_{{$q->id}}">
                                                        true
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="answer[{{$q->id}}][answer]" id="yesno_false_{{$q->id}}" value="false">
                                                    <label class="form-check-label" for="yesno_false_{{$q->id}}">
                                                        false
                                                    </label>
                                                </div>
                                            </div>                                    
                                        @endif
                                    </div>
                                </div>
                            @endforeach                        
                        </div>
                        <button class="btn btn-primary w-auto btn-normal" id="submitAnswersBtn"><i class="fa-regular fa-save"></i> Submit Answers</button>
                    @else
                        <div class="row widget-cards g-4">
                            <!-- Users Widget -->
                            <div class="col-md-3">
                                <div class="widget-card">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <i class="bi bi-people widget-icon"></i>
                                        </div>
                                        <div>
                                            <h2>{{$currentLesson->total_mark}}</h2>
                                            <h5>Total Marks</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                
                            <!-- Revenue Widget -->
                            <div class="col-md-3">
                                <div class="widget-card">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <i class="bi bi-currency-dollar widget-icon"></i>
                                        </div>
                                        <div>
                                            <h2>{{$currentLesson->pass_mark}}</h2>
                                            <h5>Pass Marks</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                
                            <!-- Orders Widget -->
                            <div class="col-md-3">
                                <div class="widget-card">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <i class="bi bi-cart widget-icon"></i>
                                        </div>
                                        <div>
                                            <h2>{!!$currentLesson->retake == 0 ? '<i class="fas fa-infinity"></i></h2>' : $currentLesson->retake !!} <small class="fw-normal" style="font-size: 40%;">/ {{$result_count}} attempted</small></h2>
                                            <h5>Reattempt</h5> 
                                        </div>
                                    </div>
                                </div>
                            </div>
                
                            <!-- Orders Widget -->
                            <div class="col-md-3">
                                <div class="widget-card">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <i class="bi bi-cart widget-icon"></i>
                                        </div>
                                        <div>
                                            <h2>{{$currentLesson->question_count(check_status: true)}}</h2>
                                            <h5>Questions</h5> 
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center">                            
                            @if ($result_count == 0)
                                <div class="d-flex justify-content-center gap-3 mt-5 mb-1">
                                    <div>{{ __('Challenge your mind, test your skills') }}</div>
                                </div>
                                <h2>{{ __('Are you ready to begin?') }}</h2>
                                <div class="exam-prepare-info">
                                    <a href="{{route('courses.stage', ['slug' => $course->slug, 'lessonId' => $currentLesson->id])}}?start=true" class="btn btn-primary btn-lg"><i class="fa-regular fa-play"></i> Take Quiz</a>
                                </div>
                            @elseif (!$currentLesson->retake || $result_count < $currentLesson->retake)
                                <div class="d-flex justify-content-center gap-3 mt-5 mb-1">
                                    <div>{{ __('Retry, rethink, and rule the quiz') }}</div>
                                </div>
                                <h2>{{ __('Go for a perfect score!') }}</h2>
                                <div class="exam-prepare-info">
                                    <a href="{{route('courses.stage', ['slug' => $course->slug, 'lessonId' => $currentLesson->id])}}?start=true" class="btn btn-primary btn-lg"><i class="fa-regular fa-play"></i> Reattempt Quiz</a>
                                </div>
                            @else
                                <div class="d-flex justify-content-center gap-3 mt-5 mb-1">
                                    <div>{{ __('You have used all allowed retakes') }}</div>
                                </div>
                            @endif
                        </div>
                        @if ($result_count > 0)
                            <h5 class="mt-5 mb-3">Quiz Results ({{$result_count}})</h5>
                            <div class="course-content-wrapper-main">
                                <div class="accordion" id="accordionResults">
                                    @foreach ($results as $k => $res)
                                    @php
                                    @endphp
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="resultsSec{{$res->id}}">
                                                <button class="accordion-button d-flex flex-column flex-md-row justify-content-between" type="button" data-bs-toggle="collapse" data-bs-target="#collapseResults{{$res->id}}" aria-expanded="true" aria-controls="collapseResults{{$res->id}}">
                                                    <span class="text-center text-md-start w-50">
                                                        <span class="text-muted"><i class="fa-regular fa-calendar"></i> {{lms_format_date($res->created_at)}}</span>
                                                    </span>
                                                    <div class="d-flex mt-3 mt-md-0 justify-content-between align-items-center gap-3">
                                                        <span class="text-muted">Attempt #{{$k + 1}}</span>
                                                        <span class="{{$res->is_pass ? 'passed' : 'failed'}}">{{$res->is_pass ? 'PASS' : 'FAIL'}}</span>
                                                    </div>
                                                </button>
                                            </h2>
                                            <div id="collapseResults{{$res->id}}" class="accordion-collapse collapse {{$k == 0 ? 'shows' : ''}}" aria-labelledby="resultsSec{{$res->id}}" data-bs-parent="#accordionResults">
                                                <div class="accordion-body">
                                                    <div class="course-content-wrapper-main">
                                                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                                                            <h5>Total Marks: <span class="badge bg-primary">{{$res->total_mark}}</span></h5>
                                                            <h5>Pass Marks: <span class="badge bg-dark">{{$res->pass_mark}}</span></h5>
                                                            <h5>Obtained Marks: <span class="badge bg-success">{{$res->obtained_mark}}</span></h5>
                                                        </div>
                                                        <div class="questions">
                                                            @foreach ($res->answers_all as $qk => $ans)
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
                                                                        <span class="text-success fw-normal">{{$q->marks}} {{lms_plural('mark', $q->marks)}}</span>
                                                                    </h6>
                                                                    <hr class="mb-3">
                                                                    <div class="quesiton-text">{!! $q->title !!}</div>
                                                                    <div class="answers mt-2">
                                                                        <div>Your Answer: <span class="fw-bold">{{$ans->answer !== '' ? lms_get_answer_text($ans->answer) : '-'}}</span></div>
                                                                    </div>
                                                                    <div class="correct-answer mt-3 d-flex justify-content-between">
                                                                        <div>Correct Answer: <span class="text-success fw-bold">{{lms_get_answer_text($q->answer)}}</span></div>
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
                                                                        <div class="question-explanation">{!! $q->description !!}</div>
                                                                    @endif
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>                            
                        @endif
                    @endif                    
                @else
                    <h3 class="my-3 mt-5 fw-medium current-lesson-title">{{$currentLesson->title}}</h3>
                    @if ($lesson_type == 'youtube')
                        <div class="video-container">
                            <div class="plyr__video-embed">
                                <iframe src="https://www.youtube.com/embed/{{lms_get_youtube_id($currentLesson->lesson_src)}}" allowfullscreen allowtransparency allow="autoplay"></iframe>
                            </div>
                        </div>
                    @elseif ($lesson_type == 'vimeo')
                        <div class="video-container">
                            <div class="plyr__video-embed">
                                <iframe src="https://player.vimeo.com/video/{{lms_get_vimeo_id($currentLesson->lesson_src)}}" allowfullscreen allowtransparency allow="autoplay"></iframe>
                            </div>
                        </div>                        
                    @elseif ($lesson_type == 'image')
                        <img src="{{lms_storage($currentLesson->lesson_src)}}" class="w-100">
                    @elseif (in_array($lesson_type, ['upload', 'mp4']) == 'upload')
                        <div class="video-container">
                            <video id="mp4-player" controls class="plyr__video-embed" poster="{{lms_storage($currentLesson->thumbnail)}}">
                                <source src="{{lms_storage($currentLesson->lesson_src)}}" type="video/mp4">
                            </video>
                        </div>
                    @elseif ($lesson_type == 'document')
                        @if ($currentLesson->document_type == 'text')
                            {{file_get_contents(storage_path('app/public/' . $currentLesson->lesson_src))}}</p>
                        @elseif ($currentLesson->document_type == 'pdf')
                            <iframe src="{{ lms_storage($currentLesson->lesson_src) }}?#toolbar=0" width="100%" height="700px"></iframe>
                        @endif
                    @elseif ($lesson_type == 'iframe')
                        <div class="shadow">
                            <iframe src="{{ $currentLesson->lesson_src }}?" width="100%" height="700px"></iframe>
                        </div>
                    @endif
                @endif

                @if ($result_count == 0)
                    <hr class="mt-5">
                @endif
                
                <div class="mt-4">
                    <div class="course-details-btn-wrapper border-bottom-0 pb-3">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="summary-tab" data-bs-toggle="tab" data-bs-target="#summary" type="button" role="tab" aria-controls="summary" aria-selected="true"><i class="fa-regular fa-notebook"></i> Summary</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab" aria-controls="description" aria-selected="true"><i class="fa-regular fa-tags"></i> Description</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="certificate-tab" data-bs-toggle="tab" data-bs-target="#certificate" type="button" role="tab" aria-controls="certificate" aria-selected="false"><i class="fa-regular fa-award"></i> Certificate</button>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content mb-5" id="myTabContent">
                        <div class="tab-pane fade show active" id="summary" role="tabpanel" aria-labelledby="summary-tab">
                            {!!$currentLesson->summary!!}
                        </div>
                        <div class="tab-pane fade" id="description" role="tabpanel" aria-labelledby="description-tab">
                            {!!$currentLesson->description!!}
                        </div>
                        <div class="tab-pane fade" id="certificate" role="tabpanel" aria-labelledby="home-tab">
                            @if ($enroll->is_completed)
                                <p class="mb-4">Congratulations on successfully completing the course! Your dedication and effort have paid off, and you are now equipped with valuable knowledge. Keep learning and growing as you take the next step in your journey. Click below to download your certificate and showcase your achievement!</p>
                                <a href="{{route('courses.certificate', ['slug' => $course->slug, 'action' => 'download'])}}" target="_blank" class="btn btn-primary btn-lg me-2"><i class="fa-regular fa-download"></i> Download Certificate</a>
                                <a href="{{route('courses.certificate', ['slug' => $course->slug, 'action' => 'send'])}}" class="btn btn-warning btn-lg" id="send-certificate"><i class="fa-regular fa-paper-plane"></i> Send Certificate to Me</a>
                            @else
                                <div class="alert alert-info"><i class="fa-regular fa-info-circle"></i> Please complete the course in order to download the certificate</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center mt-4">
                    <h3 class="fw-medium mb-0">{{__('Course Outline')}}</h3>
                    <h6 class="fw-normal mt-0 text-muted">
                        <b>{{$completed_percentage}}%</b> {{__('Completed')}} ({{$completed_lesson_quiz_count}}/{{$total_lesson_quiz_count}})
                    </h6>
                </div>
                <div class="course-progress"></div>
                @if ($enroll->is_completed)
                    <h5 class="text-center fw-medium text-success mt-4">Voila! Course Completed</h5>
                @else
                    <div class="text-center mt-4">
                        <button type="button" class="btn btn-success w-auto btn-lg go-to-next-lesson"><i class="fa fa-check"></i> Complete & Go To Next</button>
                    </div>
                @endif                
        
                <div class="course-content-wrapper-main">
                    <!-- course content accordion area -->
                    <div class="accordion mt--30" id="accordionSection">
                        @foreach ($sections as $k => $section)
                        @php
                            $section_lesson_count = $section->lesson_count();
                            $section_quiz_count = $section->quiz_count();
                            $summary = '';
                            if($section_lesson_count > 0) $summary .= "$section_lesson_count " . lms_plural('Lesson', $section_lesson_count) . ', ';
                            if($section_quiz_count > 0) $summary .= "$section_quiz_count " . lms_plural('Quiz', $section_quiz_count) . ', ';
                        @endphp
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingSec{{$section->id}}">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSec{{$section->id}}" aria-expanded="true" aria-controls="collapseSec{{$section->id}}">
                                        <span>{{$section->title}}</span>
                                        <span>{{rtrim($summary, ', ')}}</span>
                                    </button>
                                </h2>
                                <div id="collapseSec{{$section->id}}" class="accordion-collapse collapse {{$currentLesson->section->id == $section->id ? 'show' : ''}}" aria-labelledby="headingSec{{$section->id}}" data-bs-parent="#accordionSection">
                                    <div class="accordion-body">
                                        @foreach ($section->lessons as $lk => $lesson)
                                            <a href="{{$lesson->is_completed() ? (route('courses.stage', ['slug' => $course->slug, 'lessonId' => $lesson->id])) : 'javascript:;' }}" 
                                                class="play-vedio-wrapper {{$lesson->is_completed() ? '' : 'cursor-disabled opacity-75'}} {{$currentLesson->id == $lesson->id ? 'active' : ''}}">
                                                <div class="left">
                                                    <span class="cursor-help">{!! lms_lesson_type_icon($lesson->lesson_type, $lesson->document_type) !!}</span>
                                                    <span>{{$lesson->title}}</span>
                                                </div>
                                                <div class="right">
                                                    <span class="play d-none">Preview</span>
                                                    <span>{{ $lesson->getDurationTextAttribute('short') }}</span>
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
        </div>
    </div>
</form>
<!-- course details area end -->
@if (request()->input('start'))
    <script>
        var exam_settings = {!! json_encode($settings) !!};
        var questions = {!! json_encode($questions) !!};
        $(document).ready(function() {
            
            const [hours, minutes, seconds] = exam_settings.duration.split(":").map(Number);
            let totalSeconds = hours * 3600 + minutes * 60 + seconds;
            let _totalSeconds = totalSeconds;

            // Display the timer
            function updateTimerDisplay(totalSeconds) {
                const hrs = String(Math.floor(totalSeconds / 3600)).padStart(2, "0");
                const mins = String(Math.floor((totalSeconds % 3600) / 60)).padStart(2, "0");
                const secs = String(totalSeconds % 60).padStart(2, "0");

                $("#count-down-timer").text(`${hrs}:${mins}:${secs}`);
                $('#time_taken').val(_totalSeconds - totalSeconds);
            }

            var interval;
            function startCountdown() {
                interval = setInterval(() => {
                    if (totalSeconds < 0) {
                        clearInterval(interval);
                        $('#formAttendExam').trigger('submit');
                        return;
                    }
                    updateTimerDisplay(totalSeconds);
                    totalSeconds--;
                }, 1000);
            }

            updateTimerDisplay(totalSeconds);
            startCountdown();

            let warnings = 0;
            window.addEventListener('blur', () => {
                if($('#warningAltTabModal').hasClass('show')) return;
                if(warnings >= exam_settings.warnings){
                    clearInterval(interval);
                    $('#formAttendExam').trigger('submit');
                    return false;
                }
                warnings++;
                $('#warningAltTabModal').modal('show');
            });

            $('#formAttendExam').submit(function(e) {
                e.preventDefault();
                $('#submitAnswersBtn').prop('disabled', true).html('<i class="fa fa-spin fa-circle-notch"></i> Processing...');
                this.submit();
            });
        });
    </script>
    <div class="modal fade" id="warningAltTabModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title" id="exampleModalLabel">Warning..!</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-4 px-5 text-center text-muted h">
                    Switching windows or using Alt + Tab during the quiz is <b class="text-danger h4">strictly prohibited</b>. 
                    This is your first and last warning. Further violations will result in the automatic 
                    submission of your answers and <b class="text-danger h4">termination</b> of the exam.
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-warning mb-4" data-bs-dismiss="modal">Okay, i understand</button>
                </div>
            </div>
        </div>
    </div>
@endif
<script>
    $(document).ready(function() {
        var currentUrl = window.location.href;
        var urlWithoutLessonId = (currentUrl.split('stage')[0] + 'stage');
        $('.go-to-next-lesson').click(function() {
            $(this).html('<i class="fa fa-spin fa-circle-notch"></i> Processing...').prop('disabled', true).addClass('opacity-50');
            $.post('{{route('courses.stage.next_lesson', ['slug' => $course->slug, 'lessonId' => $currentLesson->id])}}', {
                _token: $('meta[name="csrf-token"]').attr('content'), 
                id: {{$currentLesson->id}}
            }, function (result) {
                if(result.status == 'error'){
                    alert('Error occurred')
                    return false;
                }
                show_loader();
                window.location.href = `${urlWithoutLessonId}/${result.id}`;
            }, 'JSON');
        });

        $('#send-certificate').click(function() {
            $('#send-certificate').html('<i class="fa fa-spin fa-circle-notch"></i> Sending...').prop('disabled', true).addClass('opacity-50');
        });
    });
</script>
@endsection