@extends('layouts.admin.skeleton')
@section('title', __('Save Quiz'))

@section('extra-styles')
    <!-- Summernote -->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/bower_components/summernote/summernote-bs4.min.css') }}">
@endsection

@section('extra-scripts')
    <!-- Summernote -->
    <script type="text/javascript" src="{{ asset('admin-assets/bower_components/summernote/summernote-bs4.min.js') }}"></script>
@endsection

@section('content')
    <form id="saveForm" class="autoSubmit" action="{{ route('admin.courses.quizs.save') }}" method="POST" enctype="multipart/form-data" data-parsley-validate>
        <input type="hidden" name="course_id" value="{{ $courseId }}">
        @csrf
        <div class="card">
            <div class="card-block">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ lms_form_label('Section') }}
                            <select class="form-control select-two" id="section_id" data-placeholder="Choose Section"
                                name="section_id" @lmsparsley(quizs_add, section_id)>
                                <option value="">Lesson Type</option>
                                @foreach ($sections as $k => $item)
                                    <option value="{{ $item->id }}" {{ old('section_id', $sectionId) == $item->id ? 'selected' : '' }}>
                                        {{ $item->title }}</option>
                                @endforeach
                            </select>
                            @error('section_id')
                                <div class="parsley-errors-list">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            {{ lms_form_label('Title') }}
                            <input type="text" class="form-control" name="title" value="{{ old('title')}}" placeholder="{{__('Enter Lesson Title')}}" @lmsparsley(quizs_add,title)>
                            @error('title')
                                <div class="parsley-errors-list">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            {{ lms_form_label('Duration') }}
                            <input type="text" class="form-control" name="duration" 
                            value="{{ old('duration')}}" placeholder="{{__('01:30:00 ==> One Hour & 30 Minutes')}}" @lmsparsley(quizs_add,duration)>
                            @error('duration')
                                <div class="parsley-errors-list">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            {{ lms_form_label('Total Marks') }}
                            <input type="number" min="0" class="form-control" name="total_mark" 
                            value="{{ old('total_mark')}}" placeholder="{{__('Total available marks')}}" @lmsparsley(quizs_add,total_mark)>
                            @error('total_mark')
                                <div class="parsley-errors-list">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            {{ lms_form_label('Pass Marks') }}
                            <input type="number" min="0" class="form-control" name="pass_mark" 
                            value="{{ old('pass_mark')}}" placeholder="{{__('Totals marks needed to pass')}}" @lmsparsley(quizs_add,pass_mark)>
                            @error('pass_mark')
                                <div class="parsley-errors-list">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            {{ lms_form_label('Reattempts') }}
                            <input type="number" min="0" class="form-control" name="retake" 
                            value="{{ old('retake')}}" placeholder="{{__('How many reattempts are allowed. Set 0 if unlimited reattempts')}}" @lmsparsley(quizs_add,retake)>
                            <div class="text-muted">0 - Unlimited Reattempts</div>
                            @error('retake')
                                <div class="parsley-errors-list">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            {{lms_form_label('Description', false)}}
                            <textarea id="description" name="description" class="form-control" rows="4" @lmsparsley(courses_add,description)>{{ old('description')}}</textarea>
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
    <script>
        $(document).ready(function() {
            $('#description').summernote({
                placeholder: 'Describe the quiz',
                tabsize: 2,
                height: 300
            });
        });
    </script>
@endsection
