@extends('layouts.admin.skeleton')
@section('title', __('Quiz Result Details'))

@section('page-header')
    @include('layouts.admin.page-header', [
        'icon' => 'ph ph-book-open text-muted',
        'title' => __('Quiz Result Details') . " - $user->full_name - $lesson->title",
        'breadcrumbs' => [
            route('admin.courses.index') => __('Courses'),
            '#' => __('Quiz Result Details')
        ],
        'links' => [
            lms_can_access('admin.courses.quiz-results') ? generate_link_element([
                'action' => 'back',
                'route' => 'admin.courses.quiz-results',
                'route_data' => [
                    'courseId' => $course->id,
                    'lessonId' => $lesson->id
                ]
            ]) : false,
        ]
    ])
@endsection

@section('content')
<div class="card">
    <div class="card-block p-0">
        <div id="accordion">
            @php
                $index = 0;
            @endphp
            @foreach ($quizResultList as $res) 
                <div class="s">
                    <div class="card-header note-toolbar px-4 pt-4 pb-0" id="heading-{{$res->id}}">
                        <button class="btn btn-default w-100 text-left d-flex justify-content-between" data-toggle="collapse" data-target="#collapse-{{$res->id}}" aria-expanded="true" aria-controls="collapse-{{$res->id}}">
                            <div class="h4">Attempt #{{$index+1}}</div>
                            <div class="{{$res->is_pass ? 'passed' : 'failed'}}">{{$res->is_pass ? 'PASS' : 'FAIL'}}</d>
                        </button>
                    </div>
        
                    <div id="collapse-{{$res->id}}" class="collapse {{ $index++ == 0 ? 'show' : '' }}" aria-labelledby="heading-{{$res->id}}" data-parent="#accordion">
                        <div class="card-body pt-0">
                            <div class="course-content-wrapper-main">
                                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center my-4">
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
        {{$quizResultList->links()}}
    </div>
</div>
<style>
    .course-content-wrapper-main .accordion .accordion-header button{
        background: #eeeeee;
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
    .passed, .failed{
        border: 1px solid;
        padding: 2px 10px 0px 10px;
        border-radius: 50px;
        display: flex;
        align-items: center;
        font-weight: 600;
    }
    .passed{
        background: #228b22;
        color: #e6f9e6 !important;
    }
    .failed{
        background: #cc0000;
        color: #fde6e6 !important;
    }
</style>
<script>
    $('.menu_courses').addClass('active');
</script>
@endsection