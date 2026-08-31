@extends('layouts.admin.skeleton')
@section('title', __('Save Question'))

@section('extra-styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/bower_components/summernote/summernote-bs4.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/bower_components/bootstrap-tagsinput/css/bootstrap-tagsinput.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/assets/css/bootstrap-select.min.css') }}">
@endsection

@section('extra-scripts')
    <script type="text/javascript" src="{{ asset('admin-assets/bower_components/summernote/summernote-bs4.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('admin-assets/bower_components/bootstrap-tagsinput/js/bootstrap-tagsinput.js') }}"></script>
    <script type="text/javascript" src="{{ asset('admin-assets/assets/js/bootstrap-select.min.js') }}"></script>
@endsection

@section('content')
    <form id="saveForm" class="manualSubmission" action="{{ route('admin.courses.questions.save') }}" method="POST" enctype="multipart/form-data" data-parsley-validate>
        <input type="hidden" name="course_id" value="{{ $courseId }}">
        <input type="hidden" name="section_id" value="{{ $sectionId }}">
        <input type="hidden" name="lesson_id" value="{{ $lessonId }}">
        @csrf
        <div class="card">
            <div class="card-block">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ lms_form_label('Question Type') }}
                            <select class="form-control select-two" id="type" data-placeholder="Choose Question Type"
                                name="type" @lmsparsley(questions_add, type)>
                                <option value="">Lesson Type</option>
                                @foreach (lms_question_type() as $k => $item)
                                    <option value="{{ $k }}" {{ old('type') == $k ? 'selected' : '' }} data-type="{{$k}}">{{ $item }}</option>
                                @endforeach
                            </select>
                            @error('type')
                                <div class="parsley-errors-list">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group all-cont multiple-cont">
                            {{lms_form_label('Options')}}
                            <input type="text" class="form-control" id="multiple_options" name="multiple_options" placeholder="Add Options and hit ENTER"
                              value="{{ old('multiple_options')}}" placeholder="{{__('Enter Course Title')}}" @lmsparsley(questions_add,multiple_options)>
                            @error('multiple_options')
                                <div class="parsley-errors-list">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group all-cont multiple-cont">
                            {{lms_form_label('Answers')}}
                            <div></div>
                            <select class="selectpicker" id="multiple_answers" name="multiple_answers[]" title="Choose from Options" 
                                multiple data-selected-text-format="count > 3">
                            </select>
                            @error('multiple_answers')
                                <div class="parsley-errors-list">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group all-cont fill-cont">
                            {{ lms_form_label('Answer') }}
                            <input type="text" class="form-control" name="fill_answer" value="{{ old('fill_answer')}}" placeholder="{{__('Enter Answer')}}" @lmsparsley(questions_add,fill_answer)>
                            @error('fill_answer')
                                <div class="parsley-errors-list">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group all-cont yesno-cont">
                            {{ lms_form_label('Answer') }}
                            <div>
                                <label class="mr-2"><input type="radio" name="yesno" value="true"> True</label> 
                                <label><input type="radio" name="yesno" value="false"> False</label>
                            </div>
                            @error('yesno')
                                <div class="parsley-errors-list">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            {{lms_form_label('Write Question')}}
                            <textarea id="title" name="title" class="form-control" rows="4" @lmsparsley(questions_add,title)>{{ old('title')}}</textarea>
                            @error('title')
                                <div class="parsley-errors-list">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                    </div>
                </div>
                <div class="row fixed-submit-container">
                    <div class="col-md-12">
                        <div class="form-group">
                            <button type="submit" class="btn-submit btn btn-primary btn-sm m-b-0"><i
                                    class="fa fa-save"></i> Save</button>
                            <a href="javascript:;" id="" class="ml-2 closeModalFromIframe text-white" data-modal="ModalAddCourseLesson">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <style>
        .bootstrap-tagsinput{
          line-height: 32px;
          display: block;
        }
        .card .card-block .dropdown-menu>li>a:focus, .card .card-block .dropdown-menu>li>a:hover {
            background-color: #337ab7;
            color: #fff;
        }
    </style>
    <script>
        $(document).ready(function() {
            $('#title').summernote({
                placeholder: 'Describe the question',
                tabsize: 2,
                height: 300
            });

            $('#type').change(function() {
                const cVal = $(this).val();
                $('.all-cont').addClass('d-none');
                $(`.${cVal}-cont`).removeClass('d-none');
            }).trigger('change');

            var delimiter = '{{config('constants.ANSWER_DELIMITER')}}';
            $('#multiple_options').tagsinput({
                delimiter: delimiter,
                confirmKeys: [13]
                //maxTags: 3
            });

            $('#multiple_options').on('itemAdded itemRemoved', function (event) {
                updateAnswers();
            });

            function updateAnswers() {
                const options = $('#multiple_options').tagsinput('items'); // Get all options
                const answersSelect = $('#multiple_answers');

                // Clear current answers
                answersSelect.empty();

                // Add new options as answer choices
                options.forEach(option => {
                    answersSelect.append(new Option(option, option));
                });
                answersSelect.selectpicker('refresh');
            }

            // Initial load to sync answers with existing options
            updateAnswers();

            $('#saveForm').on('lms.form.validated', function (e){
                const options = $('#multiple_options').tagsinput('items'); 
                $('#multiple_options').val(options.join(delimiter));
                show_loader();
                this.submit();
            });
        });
    </script>
@endsection