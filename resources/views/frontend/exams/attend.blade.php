@extends('layouts.frontend.skeleton')
@section('title', $exam->title)
@php
    $delimiter = config('constants.ANSWER_DELIMITER');
    $settings = [
        'warnings' => 1,
        'negative_mark' => $exam->negative_mark,
        'alt_tab' => true,
        'duration' => $exam->duration
    ]
@endphp

@section('content')
<style>
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
    input[type=checkbox] ~ label::after{
        top: 2px;
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
    #time-lapse.scroll-to-fixed-fixed{
        justify-content: center;
        background: rgb(255, 255, 255);
        box-shadow: 1px 1px 10px #ccc;
        padding: 6px;
    }
    @media screen and (min-width: 1200px) {
        .right-course-details{
            margin-top: -250px;
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
                        <a class="active" href="#">Attend</a>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- course details breadcrumb end -->
<!-- course details area start -->
<a href="#" id="btn-submit-confirmtion" data-element_id="btn-submit-confirmtion" class="confirm-action"></a>
<form id="formAttendExam" action="{{lms_exam_slug($exam, 'save_answers')}}" method="POST" class="rts-course-area rts-section-gap">
    @csrf
    <input type="hidden" id="time_taken" name="time_taken">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-8 order-cl-1 order-lg-1 order-md-2 order-sm-2 order-2">
                @if (request()->input('start'))
                    <div class="d-flex gap-3" id="time-lapse">
                        <div>Answers will be submitted in </div>
                        <div id="count-down-timer">00:00:00</div>
                    </div>
                    @if ($exam->negative_mark)
                        <div class="alert alert-warning d-flex align-items-center gap-3 px-5 py-4 mt-2">
                            <i class="fa fa-exclamation-triangle text-danger fa-2x"></i> 
                            <div>
                                Negative marking applies to this exam.<br>
                                <b>{{$exam->negative_mark}}</b> marks will be deducted for each incorrect answer.
                            </div>
                        </div>
                    @endif
                    <div class="questions mt-5">
                        @php
                            $question_serial_number = 0;
                        @endphp
                        @foreach ($qbank_questions as $each_qb)
                            <h4 class="text-muted mb-2">{{$each_qb['qbank']->title}}</h4>
                            @foreach ($each_qb['questions'] as &$q)
								@php
									$answers = explode($delimiter, $q->answer);
									$answers = array_values(array_unique(lms_array_filter($answers)));
									$c_answer = $q->answer;
									unset($q->answer); // This is to remove the answers from javascript array
								@endphp
								<input type="hidden" name="answer[{{$q->id}}][type]" value="{{$q->type}}">
								<div class="each-quesiton">
									<h6 class="mb-1 d-flex justify-content-between">
										<span>
											Question #{{++$question_serial_number}} 
											@if ($q->type == 'multiple' && count($answers) > 1)
												<small class="text-muted">
													[Choose {{count($answers)}} {{ lms_plural('answer', count($answers)) }}]
												</small>
											@endif
										</span>
										<span class="text-success fw-normal">{{$each_qb['marks']}} {{lms_plural('mark', $each_qb['marks'])}}</span>
									</h6>
									<hr class="mb-3">
									<div class="quesiton-text">{!! $q->title !!}</div>
									<div class="answers mt-2">
										@if ($q->type == 'multiple')
											<div class="row">
												@foreach (explode($delimiter, $q->options) as $opk => $op)
													@php
														$op = trim($op);
														if($op === '') continue;
														//$op = '<img src="http://127.0.0.1:8000/storage/exam_images/67bdf56d80037.png" style="max-height: 160px;" />';
														/*preg_match('/src="([^"]+)"/', $op, $matches);
														$image_link = isset($matches[1]) && is_array($matches[1]) ? '' : ($matches[1] ?? '');*/
													@endphp
													<div class="col-lg-12">
														<div class="form-check">
															<input class="form-check-input" type="{{strpos($c_answer, $delimiter) !== false ? 'checkbox' : 'radio'}}" id="checkbox_{{$q->id}}_{{$opk}}" name="answer[{{$q->id}}][answer][]" value="{{$op}}">
															<label class="form-check-label" for="checkbox_{{$q->id}}_{{$opk}}">
																{!!$op!!}
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
                        @endforeach
                    </div>
                    <button class="btn btn-primary w-auto btn-normal" id="submitAnswersBtn"><i class="fa-regular fa-save"></i> Submit Answers</button>
                @else
                    <div class="exam-prepare-info">
                        <a href="{{ lms_exam_slug($exam, 'attend') }}?start=true" class="btn btn-primary btn-lg"><i class="fa-regular fa-play"></i> Start Exam</a>
                    </div>
                @endif
            </div>
            <div class="col-lg-4 order-cl-2 order-lg-2 order-md-1 order-sm-1 order-1  rts-sticky-column-item">
                <!-- right- sticky bar area -->
                <div class="right-course-details">
                    <!-- single course-sidebar -->
                    <div class="course-side-bar">
                        @if ($exam->thumbnail)
                            <div class="thumbnail">
                                <img src="{{lms_storage($exam->thumbnail)}}" alt="">
                                @if ($exam->uploaded_video_url || $exam->online_video_url)
                                    <div class="vedio-icone">
                                        <a class="video-play-button play-video popup-video" href="{{$exam->is_online_video ? $exam->online_video_url : lms_storage($exam->uploaded_video_url)}}">
                                            <span></span>
                                        </a>
                                        <div class="video-overlay">
                                            <a class="video-overlay-close">×</a>
                                        </div>
                                    </div>
                                @endif
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
                                    <i class="fa-regular fa-question-circle"></i>
                                    <span>Reattempt</span>
                                </div>
                                <div class="right">
                                    <span>{{$exam->retake ? $exam->retake : 'Unlimited'}}</span>
                                </div>
                            </div>
                            <div class="single-include">
                                <div class="left">
                                    <i class="fa-regular fa-question-circle"></i>
                                    <span>Total Questions</span>
                                </div>
                                <div class="right">
                                    <span>{{$exam->question_count()}}</span>
                                </div>
                            </div>
                            <div class="single-include">
                                <div class="left">
                                    <i class="fa-sharp fa-light fa-list"></i>
                                    <span>Total Marks</span>
                                </div>
                                <div class="right">
                                    <span>{{$exam->total_mark}}</span>
                                </div>
                            </div>
                            <div class="single-include">
                                <div class="left">
                                    <i class="fa-sharp fa-light fa-check-circle"></i>
                                    <span>Pass Marks</span>
                                </div>
                                <div class="right">
                                    <span>{{$exam->pass_mark}}</span>
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
</form>
<!-- course details area end -->
@if (request()->input('start'))
    <script type=" text/javascript" src="{{asset('admin-assets/assets/js/jquery-scrolltofixed.js')}}"></script>
    <script>
        var exam_settings = {!! json_encode($settings) !!};
        $(document).ready(function() {

            $('#time-lapse').scrollToFixed({
                marginTop: 120,
                minWidth: 1100
            });
            
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
                    $('#formAttendExam')[0].submit();
                    return false;
                }
                warnings++;
                $('#warningAltTabModal').modal('show');
            });

            $('#formAttendExam').submit(function(e) {
                e.preventDefault();
                //$('#submitAnswersBtn').prop('disabled', true).html('<i class="fa fa-spin fa-circle-notch"></i> Processing...');
                //this.submit();
                $('#btn-submit-confirmtion').trigger('click');
            });

            $('#btn-submit-confirmtion').on('lms.action.confirmed', function(e) {
                $('#submitAnswersBtn').prop('disabled', true).html('<i class="fa fa-spin fa-circle-notch"></i> Processing...');
                $('#formAttendExam')[0].submit();
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
                    Switching windows or using Alt + Tab during the exam is <b class="text-danger h4">strictly prohibited</b>. 
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
@endsection