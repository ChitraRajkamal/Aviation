@extends('layouts.admin.skeleton')
@section('title', __('Edit Question'))

@section('page-header')
    @include('layouts.admin.page-header', [
        'icon' => 'ph ph-note-book text-muted',
        'title' => $qbank->title .  ' | ' . __('Questions'),
        'breadcrumbs' => [
            route('admin.qbank.index') => __('Question Banks'),
            route('admin.qbank.edit', ['qbank' => $qbank]) => $qbank->title,
            route('admin.qbank.questions', ['qbankId' => $qbank->id]) => __('Questions'),
            '#' => __('Edit'),
        ],
        'links' => [
            generate_link_element([
                'action' => 'back',
                'route' => 'admin.qbank.questions',
                'route_data' => [
                    'qbankId' => $qbank->id
                ],
            ]),
        ]
    ])
@endsection

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
    <form id="saveForm" class="manualSubmission" action="{{ route('admin.qbank.questions.save', ['qbankId' => $qbank->id]) }}" method="POST" enctype="multipart/form-data" data-parsley-validate>
        @csrf
        <input type="hidden" name="id" value="{{$question->id}}" />
        <div class="card">
            <div class="card-block">
                @if (strpos($question->options, '<img') !== false)
                    <div class="alert alert-danger mb-2">
                        <i class="fa fa-info-circle"></i> {{__('This question cannot be edited as it has images in options')}}
                    </div>
                @endif
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            {{lms_form_label('Write Question')}}
                            <textarea id="title" name="title" class="form-control" rows="4" @lmsparsley(qbank_questions_add,title)>{{ old('title', $question->title)}}</textarea>
                            @error('title')
                                <div class="parsley-errors-list">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        @php
                            /*<div class="form-group">
                            {{ lms_form_label('Marks') }}
                            <input type="number" min="0" class="form-control" name="marks" 
                            value="{{ old('marks', $question->marks)}}" placeholder="{{__('Marks Allocated')}}" @lmsparsley(qbank_questions_add,marks)>
                            @error('marks')
                                <div class="parsley-errors-list">{{ $message }}</div>
                            @enderror
                        </div>*/
                        @endphp
                        <div class="form-group">
                            {{ lms_form_label('Question Type') }}
                            <select class="form-control select-two" id="type" data-placeholder="Choose Question Type"
                                name="type" @lmsparsley(qbank_questions_add, type)>
                                <option value="">Lesson Type</option>
                                @foreach (lms_question_type() as $k => $item)
                                    <option value="{{ $k }}" {{ old('type', $question->type) == $k ? 'selected' : '' }} data-type="{{$k}}">{{ $item }}</option>
                                @endforeach
                            </select>
                            @error('type')
                                <div class="parsley-errors-list">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group all-cont multiple-cont">
                            {{lms_form_label('Options')}}
                            <input type="text" class="form-control" id="multiple_options" name="multiple_options" placeholder="Add Options and hit ENTER"
                              value="{{ old('multiple_options', $question->multiple_options)}}" placeholder="{{__('Enter Course Title')}}" @lmsparsley(qbank_questions_add,multiple_options)>
                            @error('multiple_options')
                                <div class="parsley-errors-list">{{ $message }}</div>
                            @enderror
                        </div>
                        @php
                            $options = explode(config('constants.ANSWER_DELIMITER'), $question->options);
                            $options = array_values(lms_array_filter($options));
                            $answers = explode(config('constants.ANSWER_DELIMITER'), $question->answer);
                            $answers = array_values(lms_array_filter($answers));
                        @endphp
                        <div class="form-group all-cont multiple-cont">
                            {{lms_form_label('Answers')}}
                            <select class="selectpicker" data-style="btn-primary" data-width="100%" id="multiple_answers" name="multiple_answers[]" 
                                title="Choose from Options" multiple data-selected-text-format="count > 3">
                                @foreach ($options as $kop => $op)
                                    <option {{in_array($op, $answers) ? 'selected' : ''}} value="{{$op}}">{{$op}}</option>
                                @endforeach
                            </select>
                            @error('multiple_answers')
                                <div class="parsley-errors-list">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group all-cont fill-cont">
                            {{ lms_form_label('Answer') }}
                            <input type="text" class="form-control" name="fill_answer" value="{{ old('fill_answer', $question->answer)}}" placeholder="{{__('Enter Answer')}}" @lmsparsley(qbank_questions_add,fill_answer)>
                            @error('fill_answer')
                                <div class="parsley-errors-list">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group all-cont yesno-cont">
                            {{ lms_form_label('Answer') }}
                            <div>
                                <label class="mr-2"><input type="radio" {{$question->type == 'yesno' &&  $question->answer == 'true' ? 'checked' : ''}} name="yesno" value="true"> True</label> 
                                <label><input type="radio" {{$question->type == 'yesno' &&  $question->answer == 'false' ? 'checked' : ''}} name="yesno" value="false"> False</label>
                            </div>
                            @error('yesno')
                                <div class="parsley-errors-list">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-8">                        
                        <div class="form-group">
                            {{lms_form_label('Explanation', false)}} <span class="text-muted">[{{__('Optional')}}]</span>
                            <textarea id="description" name="description" class="form-control">{{ old('description', $question->description)}}</textarea>
                            @error('description')
                                <div class="parsley-errors-list">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                    </div>
                </div>
                @if (strpos($question->options, '<img') === false)
                    <div class="row fixed-submit-container">
                        <div class="col-md-12">
                            <div class="form-group">
                                <button type="submit" class="btn-submit btn btn-primary btn-sm m-b-0">
                                    <i class="fa fa-save"></i> Save
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
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
                height: 200
            });
            $('#description').summernote({
                placeholder: 'Write Explanation',
                tabsize: 2,
                height: 160
            });

            $('#type').change(function() {
                const cVal = $(this).val();
                $('.all-cont').addClass('d-none');
                $(`.${cVal}-cont`).removeClass('d-none');
            }).trigger('change');

            var delimiter = '{{config('constants.ANSWER_DELIMITER')}}';
            var multiple_options = $('#multiple_options').tagsinput({
                delimiter: delimiter,
                confirmKeys: [13]
                //maxTags: 3
            });
            var values = `{{$question->options}}`;
            values.split(delimiter).forEach(function (item) {
                $('#multiple_options').tagsinput('add', item.trim());
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

            $('#saveForm').on('lms.form.validated', function (e){
                const options = $('#multiple_options').tagsinput('items'); 
                $('#multiple_options').val(options.join(delimiter));
                show_loader();
                this.submit();
            });
            
            $('.menu_qbank').addClass('active');
        });
    </script>
@endsection