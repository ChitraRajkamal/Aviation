@extends('layouts.admin.skeleton')
@section('title', __('Edit Course ' . $course->title))

@section('extra-styles')
    <!-- Summernote -->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/bower_components/summernote/summernote-bs4.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/bower_components/bootstrap-tagsinput/css/bootstrap-tagsinput.css') }}">
@endsection

@section('extra-scripts')
    <!-- Summernote -->
    <script type="text/javascript" src="{{ asset('admin-assets/bower_components/summernote/summernote-bs4.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('admin-assets/bower_components/bootstrap-tagsinput/js/bootstrap-tagsinput.js') }}"></script>
    <script type="text/javascript" src="{{ asset('admin-assets/bower_components/Sortable/js/Sortable.js') }}"></script>
    <script type="text/javascript" src="{{ asset('admin-assets/assets/pages/j-pro/js/autoNumeric.js') }}"></script>
@endsection

@section('page-header')
    @include('layouts.admin.page-header', [
        'icon' => 'ph ph-pencil-simple text-muted',
        'title' => __('Edit Course - ' . $course->title),
        'breadcrumbs' => [
            route('admin.courses.index') => __('Courses'),
            '#' => __('Edit'),
        ],
        'links' => [
            generate_link_element([
                'action' => 'back',
                'route' => 'admin.courses.index',
            ]),
        ]
    ])
@endsection

@section('content')
@include('admin.courses.actions', ['courseId' => $course->id, 'action' => $action])
<form id="saveForm" class="autoSubmit" action="{{ route('admin.courses.update', ['course' => $course]) }}" 
    method="POST" enctype="multipart/form-data" data-parsley-validate>
    @csrf
    @method('PUT')
    <input type="hidden" name="save_and_new" id="hf-save-and-new">
    <input type="hidden" name="action" id="hf-action" value="{{$action}}">
    <div class="card">
        <div class="card-block mt-1">
            
            <div style="min-height: 40vh;">
                @if ($action === 'basic')
                    @include('admin.courses.edit.basic', ['course' => $course, 'categoriesHierarchy' => $categoriesHierarchy])
                @elseif ($action === 'curriculum')
                    @include('admin.courses.edit.curriculum', ['course' => $course])
                @elseif ($action === 'pricing')
                    @include('admin.courses.edit.pricing', ['course' => $course])
                @elseif ($action === 'info')
                    @include('admin.courses.edit.info', ['course' => $course])
                @elseif ($action === 'media')
                    @include('admin.courses.edit.media', ['course' => $course])
                @elseif ($action === 'seo')
                    @include('admin.courses.edit.seo', ['course' => $course])
                @endif
            </div>
            @if ($action !== 'curriculum')
                <div class="row fixed-submit-container">
                    <div class="col-md-4">
                        <div class="form-group">
                            <button type="submit" class="btn-submit btn btn-primary btn-sm m-b-0 flex-1"><i class="fa fa-save"></i> Save</button>
                        </div>
                    </div>
                </div>
            @endif            
        </div>
    </div>
</form>
@if ($action === 'curriculum')
    @include('admin.courses.edit.curriculum.create-section', ['course' => $course])
    @include('admin.courses.edit.curriculum.lesson-popup', ['course' => $course])
@endif 
<script>
    $(document).ready(function() {
        $('.menu_courses').addClass('active');
    });
</script>
@endsection