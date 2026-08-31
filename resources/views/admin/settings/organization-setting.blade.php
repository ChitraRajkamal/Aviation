@extends('layouts.admin.skeleton')
@section('title', __('Edit Organization Setting'))

@section('page-header')
    @include('layouts.admin.page-header', [
        'icon' => 'ph ph-pencil-simple text-muted',
        'title' => __('Edit Organization Setting'),
        'breadcrumbs' => [
            route('admin.settings') => __('Organization Settings'),
        ],
    ])
@endsection

@section('content')
<form id="saveForm" class="autoSubmit" action="{{ route('admin.settings.organization-update') }}" enctype="multipart/form-data" method="POST" data-parsley-validate>
    @csrf
    @method('PUT')
    <div class="card">
        <div class="card-block mt-1">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        {{lms_form_label('Certificate Logo')}} 
                        <input type="file" class="form-control-file" id="certificate_logo" name="certificate_logo" accept=".jpg,.jpeg,.png,.fig">
                        @if ($setting->certificate_logo)
                          <img src="{{lms_storage($setting->certificate_logo)}}" class="img-thumbnail mt-3" style="height: 140px;" alt="">
                        @endif
                        @error('certificate_logo')
                          <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {{lms_form_label('Certificate Signature')}} 
                        <input type="file" class="form-control-file" id="certificate_signature" name="certificate_signature" accept=".jpg,.jpeg,.png,.fig">
                        @if ($setting->certificate_signature)
                          <img src="{{lms_storage($setting->certificate_signature)}}" class="img-thumbnail mt-3" style="height: 140px;" alt="">
                        @endif
                        @error('certificate_signature')
                          <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-4">
                    <div class="form-group">
                        {{lms_form_label('Course Certificate Title')}}
                        <input type="text" class="form-control" name="course_certificate_title" placeholder="{{__('Enter Course Certificate Title')}}"
                            value="{{ old('course_certificate_title', $setting->course_certificate_title)}}" required >
                        @error('course_certificate_title')
                            <div class="parsley-errors-list">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {{lms_form_label('Course Certificate Template')}}
                        <select class="form-control" name="course_certificate_template" id="course_certificate_template" required>
                            @foreach (lms_certificate_templates() as $item)
                                <option {{old('course_certificate_template', $setting->course_certificate_template) == $item ? 'selected' : ''}} value="{{$item}}">Template {{$item}}</option>
                            @endforeach
                        </select>
                        @error('course_certificate_template')
                            <div class="parsley-errors-list">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-4">
                    <div class="form-group">
                        {{lms_form_label('Exam Certificate Title')}}
                        <input type="text" class="form-control" name="exam_certificate_title" placeholder="{{__('Enter Exam Certificate Title')}}"
                            value="{{ old('exam_certificate_title', $setting->exam_certificate_title)}}" required >
                        @error('exam_certificate_title')
                            <div class="parsley-errors-list">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {{lms_form_label('Exam Certificate Template')}}
                        <select class="form-control" name="exam_certificate_template" id="exam_certificate_template" required>
                            @foreach (lms_certificate_templates() as $item)
                                <option {{old('exam_certificate_template', $setting->exam_certificate_template) == $item ? 'selected' : ''}} value="{{$item}}">Template {{$item}}</option>
                            @endforeach
                        </select>
                        @error('exam_certificate_template')
                            <div class="parsley-errors-list">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-4">
                    <div class="form-group">
                        <button type="submit" class="btn-submit btn btn-primary btn-sm m-b-0 flex-1"><i class="fa fa-save"></i> Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection