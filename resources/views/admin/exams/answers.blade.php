@extends('layouts.admin.skeleton')
@section('title', __('Answers'))

@section('page-header')
    @include('layouts.admin.page-header', [
        'icon' => 'ph ph-note-book text-muted',
        'title' => $exam->title .  ' | ' . $enroll->user->full_name .  ' | ' . __('Answers'),
        'breadcrumbs' => [
            route('admin.exams.index') => __('Exams'),
            route('admin.exams.edit', ['exam' => $exam]) => $exam->title,
            '#' => __('Answers'),
        ],
        'links' => [
            generate_link_element([
                'action' => 'back',
                'route' => 'admin.exams.results.details',
                'route_data' => ['examId' => $exam->id, 'userId' => $enroll->user_id]
            ]),
        ]
    ])
@endsection

@section('content')
<style>
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
        line-height: initial;
    }
</style>
<div class="card">
    <div class="card-block">
        <h4 class="text-center" style="line-height: 30px;">
            Time Taken: <b>{{lms_duration_to_string($result->time_taken)}}</b><br>
            Total Marks: <b>{{$exam->total_mark}}</b><br>
            Pass Marks: <b>{{$exam->pass_mark}}</b><br>
            Obtained Marks: <b>{{$result->obtained_mark}}</b><br>
            Percentage: <b>{{$result->percentage}}%</b>
        </h4>
        <hr>
        <h1 class="text-center mb-4">
            Result : <b class="{{$result->is_pass ? 'text-success' : 'text-danger'}}">{{$result->is_pass ? 'PASS' : 'FAIL'}}</b>
        </h1>
        <div class="course-content-wrapper-main">
            <div class="questions">
                @foreach ($answers as $qk => $ans)
                    @php
                        $q = json_decode($ans->question);
                        $answers = explode(config('constants.ANSWER_DELIMITER'), $q->answer);
                        $answers = array_values(array_unique(lms_array_filter($answers)));
                    @endphp
                    <input type="hidden" name="answer[{{$q->id}}][type]" value="{{$q->type}}">
                    <div class="each-question question-{{$ans->is_correct ? 'correct' : 'wrong'}}">
                        <h6 class="mb-1 d-flex justify-content-between">
                            <span>
                                Question #{{$qk+1}} 
                            </span>
                            <div class="d-flex gap-1">
                                <span>ID: <b>{{$q->id}}</b></span>
                                @if ($q->marks != 0)
                                    <span>|</span>
                                    <span class="text-{{$ans->is_correct ? 'success' : 'danger'}} fw-normal">{{$q->marks}} {{lms_plural('mark', $q->marks)}}</span>
                                @endif
                            </div>
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
                            <div class="mt-3 mb-2">Explanation:</div>
                            <div class="question-explanation">{!! $q->description !!}</div>
                        @endif
                    </div>
                @endforeach                                
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('.menu_exams').addClass('active');
    });
</script>
@endsection