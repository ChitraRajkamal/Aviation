@extends('layouts.admin.skeleton')
@section('title', __('Add Student'))

@section('page-header')
    @include('layouts.admin.page-header', [
        'icon' => 'ph ph-note-book text-muted',
        'title' => __('Add Student'),
        'breadcrumbs' => [
            route('admin.users.student') => __('Students'),
            '#' => __('New'),
        ],
        'links' => [
            generate_link_element([
                'action' => 'back',
                'route' => 'admin.users.student',
            ]),
        ]
    ])
@endsection

@section('content')
    <form id="saveForm" class="autoSubmission" action="{{ route('admin.users.student.save') }}" method="POST" enctype="multipart/form-data" data-parsley-validate>
        @csrf
        <div class="card">
            <div class="card-block">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            {{lms_form_label('First Name')}}
                            <input type="text" class="form-control" name="first_name" value="{{ old('first_name')}}" placeholder="{{__('Enter First Name')}}" @lmsparsley(students_add,first_name)>
                            @error('first_name')
                                <div class="parsley-errors-list">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{lms_form_label('Last Name')}}
                            <input type="text" class="form-control" name="last_name" value="{{ old('last_name')}}" placeholder="{{__('Enter Last Name')}}" @lmsparsley(students_add,last_name)>
                            @error('last_name')
                                <div class="parsley-errors-list">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{lms_form_label('Email ID')}}
                            <input type="text" class="form-control" name="email" value="{{ old('email')}}" placeholder="{{__('Enter Email ID')}}" @lmsparsley(students_add,email)>
                            @error('email')
                                <div class="parsley-errors-list">{{ $message }}</div>
                            @enderror
                            <div class="text-primary"><i class="fa fa-info-circle"></i> Email will be sent to this EMail ID</div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            {{lms_form_label('Date of Birth')}}
                            <input type="date" class="form-control" name="dob" value="{{ old('dob')}}" placeholder="{{__('Enter DOB')}}" 
                               max="{{today()->subYears(lms_setting('user_min_age'))->format('Y-m-d')}}" @lmsparsley(students_add,dob)>
                            @error('dob')
                                <div class="parsley-errors-list">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{lms_form_label('Mobile')}}
                            <input type="text" class="form-control" name="mobile" value="{{ old('mobile')}}" placeholder="{{__('Enter Mobile')}}" @lmsparsley(students_add,mobile)>
                            @error('mobile')
                                <div class="parsley-errors-list">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{lms_form_label('Phone', false)}}
                            <input type="text" class="form-control" name="phone" value="{{ old('phone')}}" placeholder="{{__('Enter Phone')}}" @lmsparsley(students_add,phone)>
                            @error('phone')
                                <div class="parsley-errors-list">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            {{lms_form_label('Gender')}}<br>
                            <div class="">
                                <label class="mr-2"><input {{old('gender')=='Male'?'checked':''}} type="radio" name="gender" value="Male" data-parsley-errors-container="#genderError" @lmsparsley(students_add,gender)> Male</label>
                                <label class="mr-2"><input {{old('gender')=='Female'?'checked':''}} type="radio" name="gender" value="Female" @lmsparsley(students_add,gender)> Female</label>
                                <label><input {{old('gender')=='Others'?'checked':''}} type="radio" name="gender" value="Others" @lmsparsley(students_add,gender)> Others</label>
                            </div>
                            <div id="genderError"></div>
                            @error('gender')
                                <div class="parsley-errors-list">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row fixed-submit-container">
                    <div class="col-md-12">
                        <div class="form-group">
                            <button type="submit" class="btn-submit btn btn-primary btn-sm m-b-0">
                                <i class="fa fa-save"></i> Save
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <style>
        
    </style>
    <script>
        $(document).ready(function() {
            $('.menu_users').addClass('active');
        });
    </script>
@endsection