@php
    $lesson_add_url = route('admin.courses.lessons.create', ['courseId' => $course->id]);
    $quiz_add_url = route('admin.courses.quizs.create', ['courseId' => $course->id]);
@endphp
@if ($sections->isEmpty())
    <div class="alert alert-dark text-center">
        <i class="ph ph-info fa-4x"></i><br>No sections added so far<br>
        <a href="javascript:;" class="add-section btn btn-dark btn-sm mt-2">
            <i class="ph ph-plus"></i> Add Section
        </a>
    </div>
@else
    <div class="row course-sections">
        <!-- Left Column (Tab Links) -->
        <div class="col-md-4">
            <div class="dropdown d-block mb-3">
                <button class="btn btn-dark btn-sm w-100 dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="ph ph-plus"></i> Add Section / Lesson / Quiz
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    @if (lms_can_access('admin.courses.sections.create'))
                        <a class="dropdown-item add-section" href="javascript:;"><i class="ph ph-stack"></i> Add Section</a>
                    @else
                        <a class="dropdown-item opacity-5 pe-none" href="javascript:;"><i class="ph ph-stack"></i> Add Section</a>
                    @endif
                    @if (lms_can_access('admin.courses.lessons.create'))
                        <a class="dropdown-item add-lesson" href="javascript:;"><i class="ph ph-notepad"></i> Add Lesson</a>
                    @else
                        <a class="dropdown-item opacity-5 pe-none" href="javascript:;"><i class="ph ph-notepad"></i> Add Lesson</a>
                    @endif
                    @if (lms_can_access('admin.courses.quizs.create'))
                        <a class="dropdown-item add-quiz" href="javascript:;"><i class="ph ph-question"></i> Add Quiz</a>
                    @else
                        <a class="dropdown-item opacity-5 pe-none" href="javascript:;"><i class="ph ph-question"></i> Add Quiz</a>
                    @endif
                </div>
            </div>
            <div class="list-group mb-3" id="section-sort">
                @foreach ($sections as $key => $item)
                    <a href="#section_{{$item->id}}" data-id="{{$item->id}}" class="list-group-item p-2 list-group-item-action {{ $key == 0 ? 'active' : '' }}" data-toggle="tab">
                        <i class="ph ph-dots-six-vertical handle bs-tt" title="Drag & Drop to Change Position" data-placement="right"></i>
                        <span class="list-title">{{$item->title}}</span>
                        @if (lms_can_access('admin.courses.sections.virtual-edit'))
                            <i class="ph ph-pencil-simple edit-section btn btn-success p-1" data-id="{{$item->id}}" data-title="{{$item->title}}"></i>
                        @endif
                        @if (lms_can_access('admin.courses.sections.delete'))
                            <i class="ph ph-trash-simple confirm-action btn btn-danger p-1" data-action="link" 
                                data-url="{{ route('admin.courses.sections.delete', ['id' => $item->id, 'courseId' => $item->course_id]) }}"></i>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Right Column (Tab Content) -->
        <div class="col-md-8">
            <div class="tab-content">
                @foreach ($sections as $key => $item)
                    <div class="tab-pane fade {{ $key == 0 ? 'active show' : '' }}" id="section_{{$item->id}}">
                        @if ($item->lessons->isEmpty())
                            <div class="alert alert-dark text-center">
                                <i class="ph ph-info fa-4x"></i><br>No lessons / quizzes added in <b>{{$item->title}}</b> section so far<br>
                                @if (lms_can_access('admin.courses.lessons.create'))
                                    <a href="javascript:;" class="add-lesson btn btn-primary btn-sm mt-2 mr-2" data-section_id="{{$item->id}}">
                                        <i class="ph ph-plus"></i> Add Lesson
                                    </a>
                                @endif
                                @if (lms_can_access('admin.courses.quizs.create'))
                                    <a href="javascript:;" class="add-quiz btn btn-dark btn-sm mt-2" data-section_id="{{$item->id}}">
                                        <i class="ph ph-plus"></i> Add Quiz
                                    </a>
                                @endif
                            </div>
                        @else
                            <h4>
                                <span class="text-muted">Section:</span> {{$item->title}}
                                @if (lms_can_access('admin.courses.lessons.create'))
                                    <a href="javascript:;" class="add-lesson btn btn-primary px-2 py-1" data-section_id="{{$item->id}}">
                                        <i class="ph ph-notepad mr-0"></i> <span class="inner-text">Lesson</span>
                                    </a>
                                @endif
                                @if (lms_can_access('admin.courses.quizs.create'))
                                    <a href="javascript:;" class="add-quiz btn btn-dark px-2 py-1" data-section_id="{{$item->id}}">
                                        <i class="ph ph-question mr-0"></i> <span class="inner-text">Quiz</span>
                                    </a>
                                @endif
                            </h4>
                            <hr class="my-2">
                            @php
                                $lessonTypes = lms_lesson_type();
                            @endphp
                            <div class="lesson-sort" id="lessons-{{$key}}">
                                @foreach ($item->lessons as $iKey => $iItem)
                                    @php              
                                        if($iItem->is_quiz){
                                            $type = 'quiz';
                                        }else{
                                            $type = 'lesson';
                                        }
                                        $lesson_edit_url = route("admin.courses.{$type}s.edit", ['courseId' => $course->id, 'id' => $iItem->id]);
                                        $lesson_delete_url = route('admin.courses.lessons.delete', ['id' => $iItem->id, 'courseId' => $iItem->course_id, 'sectionId' => $iItem->section_id]);
                                        $lesson_view_questions_url = route("admin.courses.questions", ['courseId' => $iItem->course_id, 'lessonId' => $iItem->id]);
                                        $lesson_add_questions_url = route("admin.courses.questions.create", ['courseId' => $iItem->course_id, 'lessonId' => $iItem->id]);
                                    @endphp
                                    <div class="d-flex gap-2 align-items-center py-2" data-id="{{$iItem->id}}">
                                        <i class="ph ph-dots-six-vertical lesson-handle bs-tt" title="Drag & Drop to Change Position" data-placement="left"></i>
                                        <div class="d-flex gap-2 flex-1">
                                            {{$iItem->title}}
                                            @if (!$iItem->is_quiz && $iItem->lesson_type != 'text')
                                                <a href="{{lms_storage($iItem->lesson_src)}}" target="_blank"><i class="ph ph-arrow-square-out"></i></a>
                                            @endif
                                        </div>
                                        @if ($iItem->is_quiz)
                                            <div><span class="badge badge-dark">Quiz</span></div>
                                        @else
                                            <div><span class="badge badge-primary">{{$lessonTypes[$iItem->lesson_type]}}</span></div>
                                        @endif
                                        
                                        <div><small class="text-muted">{{lms_format_date($iItem->created_at)}}</small></div>
                                        <div class="dropdown d-block">
                                            <button class="btn btn-light p-0 dropdown-toggle no-caret border border-1 border-secondary" type="button" id="dropdownMenuButton_{{$iKey}}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="ph ph-dots-three-vertical mr-0"></i>
                                            </button>
                                            <div class="dropdown-menu shadow-lg" aria-labelledby="dropdownMenuButton_{{$iKey}}">
                                                @if ($iItem->is_quiz)
                                                    @if (lms_can_access('admin.courses.questions.create'))
                                                        <a class="dropdown-item add-question" data-url="{{$lesson_add_questions_url}}" data-name="{{$iItem->title}}" href="javascript:;"><i class="ph ph-plus"></i> Add Question</a>
                                                    @endif
                                                    @if (lms_can_access('admin.courses.questions'))
                                                        <a class="dropdown-item view-questions" data-url="{{$lesson_view_questions_url}}" data-name="{{$iItem->title}}" href="javascript:;"><i class="ph ph-question"></i> View Questions</a>
                                                    @endif
                                                    @if (lms_can_access('admin.courses.quizs.edit'))
                                                        <a class="dropdown-item edit-{{$type}}" data-url="{{$lesson_edit_url}}" href="javascript:;"><i class="ph ph-pencil-simple"></i> Edit</a>
                                                    @endif
                                                    @if (lms_can_access('admin.courses.quizs.delete'))
                                                        <a class="dropdown-item confirm-action" href="javascript:;" data-action="link"
                                                            data-url="{{ $lesson_delete_url }}">
                                                            <i class="ph ph-trash-simple"></i> Delete
                                                        </a>
                                                    @endif
                                                    @if (lms_can_access('admin.courses.quiz-results'))
                                                        <a class="dropdown-item" href="{{route('admin.courses.quiz-results', ['courseId' => $course->id, 'lessonId' => $iItem->id])}}"><i class="ph ph-file"></i> Quiz Results</a>
                                                    @endif
                                                @else
                                                    @if (lms_can_access('admin.courses.lessons.edit'))
                                                        <a class="dropdown-item edit-{{$type}}" data-url="{{$lesson_edit_url}}" href="javascript:;"><i class="ph ph-pencil-simple"></i> Edit</a>
                                                    @endif
                                                    @if (lms_can_access('admin.courses.lessons.delete'))
                                                        <a class="dropdown-item confirm-action" href="javascript:;" data-action="link"
                                                            data-url="{{ $lesson_delete_url }}">
                                                            <i class="ph ph-trash-simple"></i> Delete
                                                        </a>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif
<style>
    .course-sections .list-group-item.active {
        background-color: #e4e4e4;
        color: #000000;
        border-color: #0000001F;
    }
    .handle, .lesson-handle{
        cursor: move;
    }
    #section-sort a{
        display: flex;
        align-items: center;
        column-gap: 4px;
    }
    #section-sort a span.list-title{
        flex: 1;
    }
    /*.add-lesson span.inner-text{
        display: inline-block;
        width: 0px;
        opacity: 0;
        transition: 0.2s width linear, 0.5s opacity 0.1s;
    }
    .add-lesson:hover span.inner-text{
        width: 60px;
        opacity: 1;
    }
    .add-quiz span.inner-text{
        display: inline-block;
        width: 0px;
        opacity: 0;
        transition: 0.2s width linear, 0.5s opacity 0.1s;
    }
    .add-quiz:hover span.inner-text{
        width: 40px;
        opacity: 1;
    }*/
</style>
<script>
  $(document).ready(function() {
            
    var loading_code = `<style>body{margin: 0px;} @keyframes fa-spin{0%{-webkit-transform:rotate(0deg);transform:rotate(0deg)}100%{-webkit-transform:rotate(359deg);transform:rotate(359deg)}}</style><div style="text-align: center;height: 100vh;display: flex;align-items: center;justify-content: center;font-size: 18px;font-family: Arial, Helvetica, sans-serif;font-weight: 500;flex-direction: column;row-gap: 10px;"><svg style="width: 50px; height: 50px; fill: #01a9ac; animation: fa-spin 1.5s infinite linear;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M18.364 5.63604L16.9497 7.05025C15.683 5.7835 13.933 5 12 5C8.13401 5 5 8.13401 5 12C5 15.866 8.13401 19 12 19C15.866 19 19 15.866 19 12H21C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C14.4853 3 16.7353 4.00736 18.364 5.63604Z"></path></svg> Loading...</div>`;
    $('.add-section').click(function() {
        $('#ModalAddCourseSection .parsley-errors-list').text('');
        $('#section-id').val(0);
        $('#section-title').val('');
        $('#ModalAddCourseSection').modal('show');
    });
    $('.edit-section').click(function() {
        $('#section-id').val(this.dataset.id);
        $('#section-title').val(this.dataset.title);
        $('#ModalAddCourseSection').modal('show');
    });

    const loadIframeData = (title, url) =>{
        $('#ModalAddCourseLesson .modal-title').text(title);
        $('#ModalAddCourseLesson iframe').contents().find("body").html(loading_code);
        $('#ModalAddCourseLesson iframe').prop('src', url);
        $('#ModalAddCourseLesson').modal('show');
    }

    $('.add-lesson').click(function() {
        loadIframeData('Add Lesson', '{{$lesson_add_url}}?section_id=' + (this.dataset.section_id || 0));
    });
    $('.edit-lesson').click(function() {
        loadIframeData('Edit Lesson', this.dataset.url);
    });

    $('.add-quiz').click(function() {
        loadIframeData('Add Quiz', '{{$quiz_add_url}}?section_id=' + (this.dataset.section_id || 0));
    });
    $('.edit-quiz').click(function() {
        loadIframeData('Edit Quiz', this.dataset.url);
    });
    
    $('.view-questions').click(function() {
        loadIframeData(`${this.dataset.name} - Questions`, this.dataset.url);
    });    
    $('.add-question').click(function() {
        loadIframeData(`${this.dataset.name} - Add Question`, this.dataset.url);
    });

    var ids = [];
    async function processRow(row) {
        ids.push($(row).data('id'));
    }
    var section_sortable = new Sortable(document.getElementById('section-sort'), {
        handle: '.handle',
        ghostClass: 'bg-light',
        fallbackTolerance: 3, // So that we can select items on mobile
        animation: 500,
        onEnd: async function (evt) {
            ids = [];
            const rows = $('#section-sort > a').toArray(); // Convert jQuery object to array
            for (let i = 0; i < rows.length; i++) {
                await processRow(rows[i]); // Process each row sequentially
            }

            try {
                $.post('{{route('admin.courses.sections.sort')}}', { _token: $('meta[name="csrf-token"]').attr('content'), ids: ids }, function (result) {
                    if(result.status == 'error'){
                        notify({title: result.message});
                        return false;
                    }
                    notify({
                        title: result.message,
                        type: 'success'
                    });
                }, 'JSON');
            }
            catch(err) {

            }
        },
    });

    document.querySelectorAll('.lesson-sort').forEach((el) => {
        new Sortable(el, {
            handle: '.lesson-handle',
            ghostClass: 'bg-light',
            fallbackTolerance: 3, // So that we can select items on mobile
            animation: 500,
            onEnd: async function (evt) {
                ids = [];
                const rows = $(`#${el.id} > div`).toArray(); // Convert jQuery object to array
                for (let i = 0; i < rows.length; i++) {
                    await processRow(rows[i]); // Process each row sequentially
                }

                try {
                    $.post('{{route('admin.courses.lessons.sort')}}', { _token: $('meta[name="csrf-token"]').attr('content'), ids: ids }, function (result) {
                        if(result.status == 'error'){
                            notify({title: result.message});
                            return false;
                        }
                        notify({
                            title: result.message,
                            type: 'success'
                        });
                    }, 'JSON');
                }
                catch(err) {

                }
            },
        });
    });
  });
</script>