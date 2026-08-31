@extends('layouts.admin.skeleton')
@section('title', __('Save Lesson'))

@section('extra-styles')
    <!-- Summernote -->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/bower_components/summernote/summernote-bs4.min.css') }}">
@endsection

@section('extra-scripts')
    <!-- Summernote -->
    <script type="text/javascript" src="{{ asset('admin-assets/bower_components/summernote/summernote-bs4.min.js') }}"></script>
@endsection

@section('content')
    <form id="saveForm" class="autoSubmit" action="{{ route('admin.courses.lessons.save') }}" method="POST" enctype="multipart/form-data" data-parsley-validate>
        <input type="hidden" name="id" value="{{ $lesson->id }}">
        <input type="hidden" name="course_id" value="{{ $lesson->course_id }}">
        @csrf
        <div class="card">
            <div class="card-block">
                <div class="row">
                    <div class="col-md-6 offset-md-3">
                        <div class="form-group">
                            {{ lms_form_label('Lesson Type') }}
                            <select class="form-control select-two" id="lesson_type" data-placeholder="Choose Lesson Type"
                                name="lesson_type" @lmsparsley(lessons_add, lesson_type)>
                                <option value="">Lesson Type</option>
                                @foreach (lms_lesson_type() as $k => $item)
                                @php 
                                    if($lesson->lesson_type != $k) continue;
                                @endphp
                                    <option value="{{ $k }}" {{ old('lesson_type', $lesson->lesson_type) == $k ? 'selected' : '' }}>{{ $item }}</option>
                                @endforeach
                            </select>
                            @error('lesson_type')
                                <div class="parsley-errors-list">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            {{ lms_form_label('Section') }}
                            <select class="form-control select-two" id="section_id" data-placeholder="Choose Section"
                                name="section_id" @lmsparsley(lessons_add, section_id)>
                                <option value="">Lesson Type</option>
                                @foreach ($sections as $k => $item)
                                    <option value="{{ $item->id }}" {{ old('section_id', $lesson->section_id) == $item->id ? 'selected' : '' }}>
                                        {{ $item->title }}</option>
                                @endforeach
                            </select>
                            @error('section_id')
                                <div class="parsley-errors-list">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {{ lms_form_label('Title') }}
                            <input type="text" class="form-control" name="title" value="{{ old('title', $lesson->title)}}" placeholder="{{__('Enter Lesson Title')}}" @lmsparsley(lessons_add,title)>
                            @error('title')
                                <div class="parsley-errors-list">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 all-cont youtube-cont vimeo-cont dailymotion-cont mp4-cont google-cont iframe-cont">
                        <div class="form-group">
                            {{ lms_form_label('Url', className: 'text-capitalize', attributes: 'id="object_url"') }}
                            <input type="text" class="form-control" name="lesson_src" value="{{ old('lesson_src', $lesson->lesson_src)}}" placeholder="{{__('Enter Url')}}" @lmsparsley(lessons_add,lesson_src)>
                            @error('lesson_src')
                                <div class="parsley-errors-list">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6 all-cont document-cont">
                        <div class="form-group">
                            {{ lms_form_label('Document Type') }}
                            <select class="form-control select-two" id="document_type" data-placeholder="Choose Document Type" name="document_type" @lmsparsley(lessons_add, document_type)>
                                <option value="">Document Type</option>
                                @foreach (lms_document_type() as $k => $item)
                                    <option value="{{ $k }}" {{ old('document_type', $lesson->document_type) == $k ? 'selected' : '' }}>{{ $item }}</option>
                                @endforeach
                            </select>
                            @error('document_type')
                                <div class="parsley-errors-list">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6 all-cont upload-cont document-cont image-cont">
                        <div class="form-group">
                            {{lms_form_label('Upload MP4 Video', false, attributes: 'id="lesson_file"')}}
                            <input type="file" class="form-control-file" id="file" name="file">
                            @error('file')
                                <div class="parsley-errors-list">{{ $message }}</div>
                            @enderror
                            @if (in_array($lesson->lesson_type, ['upload', 'image', 'mp4', 'document']) && $lesson->lesson_src)
                                <a href="{{lms_storage($lesson->lesson_src)}}" target="_blank">View in new tab</a>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6 all-cont youtube-cont vimeo-cont dailymotion-cont mp4-cont google-cont upload-cont">
                        <div class="form-group">
                            {{ lms_form_label('Duration') }}
                            <input type="text" class="form-control" name="duration" minlength="8" maxlength="8" 
                            value="{{ old('duration', $lesson->duration)}}" placeholder="{{__('01:30:00 ==> One Hour & 30 Minutes')}}" @lmsparsley(lessons_add,duration)>
                            @error('duration')
                                <div class="parsley-errors-list">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6 all-cont upload-cont mp4-cont">
                        <div class="form-group">
                            {{lms_form_label('Thumbnail', false)}}
                            <input type="file" class="form-control-file" id="thumbnail" name="thumbnail" accept=".jpg,.jpeg,.png,.gif">
                            @error('thumbnail')
                                <div class="parsley-errors-list">{{ $message }}</div>
                            @enderror
                            @if ($lesson->thumbnail)
                                <img src="{{lms_storage($lesson->thumbnail)}}" class="img-thumbnail mt-3" style="height: 140px;" alt="">
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6 all-cont upload-cont mp4-cont">
                        <div class="form-group">
                            {{lms_form_label('Caption (.vtt)', false)}}
                            <input type="file" class="form-control-file" id="caption" name="caption" accept=".vtt">
                            @error('caption')
                                <div class="parsley-errors-list">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12 all-cont text-cont">
                        <div class="form-group">
                            {{lms_form_label('Text Description', false)}}
                            <textarea id="description" name="description" class="form-control" rows="4" @lmsparsley(courses_add,description)>{{ old('description', $lesson->description)}}</textarea>
                            @error('description')
                                <div class="parsley-errors-list">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            {{lms_form_label('Summary', false)}}
                            <textarea id="summary" name="summary" class="form-control" rows="4" @lmsparsley(courses_add,summary)>{{ old('summary', $lesson->summary)}}</textarea>
                            @error('summary')
                                <div class="parsley-errors-list">{{ $message }}</div>
                            @enderror
                        </div>
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
            $('#summary, #description').summernote({
                placeholder: 'Describe the lesson',
                tabsize: 2,
                height: 300
            });

            $('#lesson_type').change(function() {
                const cVal = $(this).val();
                $('.all-cont').addClass('d-none');
                $(`.${cVal}-cont`).removeClass('d-none');
                const newLabel = cVal == 'google' ? 'Google Drive Video' : cVal == 'mp4' ? 'MP4 Video' : cVal;
                $('#object_url').text(`${newLabel} Url`);
                $('#lesson_file').text(cVal == 'image' ? 'Upload Image' : cVal == 'upload' ? 'Upload Video' : cVal == 'document' ? 'Upload Document' : cVal);
            }).trigger('change');
        });
    </script>
@endsection
