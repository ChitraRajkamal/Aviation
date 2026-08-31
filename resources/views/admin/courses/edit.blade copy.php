@extends('layouts.admin.skeleton')
@section('title', __('Edit Course'))

@section('extra-styles')
<!-- Summernote -->
<link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/bower_components/summernote/summernote-bs4.min.css') }}">
@endsection

@section('extra-scripts')
<!-- Summernote -->
<script type="text/javascript" src="{{ asset('admin-assets/bower_components/summernote/summernote-bs4.min.js') }}"></script>
@endsection

@section('page-header')
    @include('layouts.admin.page-header', [
        'icon' => 'ph ph-pencil-simple text-muted',
        'title' => __('Edit Course'),
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
<form id="saveForm" class="autoSubmit" action="{{ route('admin.courses.update') }}" method="POST" enctype="multipart/form-data" data-parsley-validate>
    @csrf
    <input type="hidden" name="save_and_new" id="hf-save-and-new">
    <div class="card">
        <div class="card-block mt-1">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        {{lms_form_label('Title')}}
                        <input type="text" class="form-control" name="title" value="{{ old('title', $course->title)}}" placeholder="{{__('Enter Course Title')}}" @lmsparsley(courses,title)>
                        @error('title')
                            <div class="parsley-errors-list">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {{lms_form_label('Category')}}
                        <select class="form-control select-two" id="course_category_id" data-placeholder="Choose Category" name="course_category_id" @lmsparsley(courses,course_category_id)>
                            <option value="">Choose Category</option>
                            @foreach ($categoriesHierarchy as $item)
                              <option value="{{$item->id}}" {{ old('course_category_id', $course->course_category_id) == $item->id ? 'selected' : '' }}>{{$item->title}}</option>
                            @endforeach
                        </select>
                        @error('course_category_id')
                            <div class="parsley-errors-list">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {{lms_form_label('Course Level')}}
                        <select class="form-control select-two" id="level" data-placeholder="Choose Course Level" name="level" @lmsparsley(courses,level)>
                            <option value="">Course Level</option>
                            @foreach (lms_course_levels() as $item)
                              <option value="{{$item}}" {{ old('level', $course->level) == $item ? 'selected' : '' }}>{{$item}}</option>
                            @endforeach
                        </select>
                        @error('level')
                            <div class="parsley-errors-list">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        {{lms_form_label('Price Type')}}
                        <select class="form-control select-two" id="is_paid" data-placeholder="Choose Price Type" name="is_paid" @lmsparsley(courses,is_paid)>
                            <option value="">Price Type</option>
                            @foreach (lms_course_pricing() as $k => $item)
                              <option value="{{$k}}" {{ old('is_paid', $course->is_paid) == $k ? 'selected' : '' }}>{{$item}}</option>
                            @endforeach
                        </select>
                        @error('is_paid')
                            <div class="parsley-errors-list">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {{lms_form_label('Status')}}
                        <select class="form-control select-two" id="status" data-placeholder="Choose Course Status" name="status" @lmsparsley(courses,status)>
                            <option value="">Course Status</option>
                            @foreach (lms_course_status() as $k => $item)
                              <option value="{{$item}}" {{ old('status', $course->status) == $item ? 'selected' : '' }}>{{$item}}</option>
                            @endforeach
                        </select>
                        @error('status')
                            <div class="parsley-errors-list">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {{lms_form_label('Thumbnail', false)}}
                        <input type="file" class="form-control-file" id="thumbnail" name="thumbnail">
                        @error('thumbnail')
                            <div class="parsley-errors-list">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row pricing-cont">
                <div class="col-md-4">
                    <div class="form-group">
                        {{lms_form_label('Price')}}
                        <input type="number" class="form-control" name="price" value="{{ old('price', $course->price)}}" placeholder="{{__('Enter Course Price')}}" @lmsparsley(courses,price)>
                        @error('price')
                            <div class="parsley-errors-list">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {{lms_form_label('Any Discount?')}}<br>
                        <div class="">
                            <label class="mr-2"><input {{old('discount_flag', $course->discount_flag)=='1'?'checked':''}} type="radio" name="discount_flag" value="1" data-parsley-errors-container="#discountError" @lmsparsley(courses,discount_flag)> Yes</label>
                            <label><input {{old('discount_flag', $course->discount_flag)=='0'?'checked':''}} type="radio" name="discount_flag" value="0" @lmsparsley(courses,discount_flag)> No</label>
                        </div>
                        <div id="discountError"></div>
                        @error('discount_flag')
                            <div class="parsley-errors-list">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4 discounted-price-cont">
                    <div class="form-group">
                        {{lms_form_label('Discounted Price', false)}}
                        <input type="number" class="form-control" name="discounted_price" value="{{ old('discounted_price', $course->discounted_price)}}" placeholder="{{__('Enter Discounted Price')}}" @lmsparsley(courses,discounted_price)>
                        @error('discounted_price')
                            <div class="parsley-errors-list">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        {{lms_form_label('Description', false)}}
                        <textarea id="description" name="description" class="form-control" rows="4" @lmsparsley(courses,description)>{{ old('description', $course->description)}}</textarea>
                        @error('description')
                            <div class="parsley-errors-list">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {{lms_form_label('Short Description', false)}}
                        <textarea name="short_description" class="form-control" rows="4" @lmsparsley(courses,short_description)>{{ old('short_description', $course->short_description)}}</textarea>
                        @error('short_description')
                            <div class="parsley-errors-list">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <button type="submit" class="btn-submit btn btn-primary btn-sm m-b-0 flex-1"><i class="fa fa-save"></i> Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<script>
    $(document).ready(function() {
        $('#description').summernote({
            placeholder: 'Hello Bootstrap 4',
            tabsize: 2,
            height: 100
        });

        $('#is_paid').change(function() {
            if(this.value == '0'){
                $('.pricing-cont').addClass('d-none');
            }else{
                $('.pricing-cont').removeClass('d-none');
            }
        }).trigger('change');

        $('[name="discount_flag"]').change(function() {
            if(this.value == '0'){
                $('.discounted-price-cont input').val('').prop('disabled', true);
            }else{
                $('.discounted-price-cont input').val('').prop('disabled', false).focus();
            }
        }).trigger('change');
    });
</script>
@endsection