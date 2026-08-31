@extends('layouts.admin.skeleton')
@section('title', __('Add Course'))

@section('extra-styles')
    <!-- Summernote -->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/bower_components/summernote/summernote-bs4.min.css') }}">
@endsection

@section('extra-scripts')
    <!-- Summernote -->
    <script type="text/javascript" src="{{ asset('admin-assets/bower_components/summernote/summernote-bs4.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('admin-assets/assets/pages/j-pro/js/autoNumeric.js') }}"></script>
@endsection

@section('page-header')
    @include('layouts.admin.page-header', [
        'icon' => 'ph ph-plus-square text-muted',
        'title' => __('Add Course'),
        'breadcrumbs' => [
            route('admin.courses.index') => __('Courses'),
            '#' => __('New'),
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
<form id="saveForm" class="autoSubmit" action="{{ route('admin.courses.store') }}" method="POST" enctype="multipart/form-data" data-parsley-validate>
    @csrf
    <input type="hidden" name="save_and_new" id="hf-save-and-new">
    <div class="card">
        <div class="card-block mt-1">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        {{lms_form_label('Title')}}
                        <input type="text" class="form-control" name="title" value="{{ old('title')}}" placeholder="{{__('Enter Course Title')}}" @lmsparsley(courses_add,title)>
                        @error('title')
                            <div class="parsley-errors-list">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {{lms_form_label('Category')}}
                        <select class="form-control select-two" id="course_category_id" data-placeholder="Choose Category" name="course_category_id" @lmsparsley(courses_add,course_category_id)>
                            <option value="">Choose Category</option>
                            @foreach ($categoriesHierarchy as $item)
                              <option value="{{$item->id}}" {{ old('course_category_id') == $item->id ? 'selected' : '' }}>{{$item->title}}</option>
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
                        <select class="form-control select-two" id="level" data-placeholder="Choose Course Level" name="level" @lmsparsley(courses_add,level)>
                            <option value="">Course Level</option>
                            @foreach (lms_course_levels() as $item)
                              <option value="{{$item}}" {{ old('level') == $item ? 'selected' : '' }}>{{$item}}</option>
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
                        <select class="form-control select-two" id="is_paid" data-placeholder="Choose Price Type" name="is_paid" @lmsparsley(courses_add,is_paid)>
                            <option value="">Price Type</option>
                            @foreach (lms_course_pricing() as $k => $item)
                              <option value="{{$k}}" {{ old('is_paid', 1) == $k ? 'selected' : '' }}>{{$item}}</option>
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
                        <select class="form-control select-two" id="status" data-placeholder="Choose Course Status" name="status" @lmsparsley(courses_add,status)>
                            <option value="">Course Status</option>
                            @foreach (lms_course_status() as $k => $item)
                              <option value="{{$item}}" {{ old('status') == $item ? 'selected' : '' }}>{{$item}}</option>
                            @endforeach
                        </select>
                        @error('status')
                            <div class="parsley-errors-list">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {{lms_form_label('Thumbnail', false)}} <span class="file-resolution text-muted">[400 x 280]</span>
                        <input type="file" class="form-control-file" id="thumbnail" name="thumbnail" accept=".jpg,.jpeg,.png,.fig">
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
                        <input type="text" class="form-control decimal_input" name="price" value="{{ old('price')}}" placeholder="{{__('Enter Course Price')}}" @lmsparsley(courses_add,price)>
                        @error('price')
                            <div class="parsley-errors-list">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {{lms_form_label('Any Discount?')}}<br>
                        <div class="">
                            <label class="mr-2"><input {{old('discount_flag')=='1'?'checked':''}} type="radio" name="discount_flag" value="1" data-parsley-errors-container="#discountError" @lmsparsley(courses_add,discount_flag)> Yes</label>
                            <label><input {{old('discount_flag')=='0'?'checked':''}} type="radio" name="discount_flag" value="0" @lmsparsley(courses_add,discount_flag)> No</label>
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
                        <input type="text" class="form-control decimal_input" name="discounted_price" value="{{ old('discounted_price')}}" placeholder="{{__('Enter Discounted Price')}}" @lmsparsley(courses_add,discounted_price)>
                        @error('discounted_price')
                            <div class="parsley-errors-list">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        {{lms_form_label('Description', false)}}
                        <textarea id="description" name="description" class="form-control" rows="4"placeholder="{{__('Enter Description')}}" @lmsparsley(courses_add,description)>{{ old('description')}}</textarea>
                        @error('description')
                            <div class="parsley-errors-list">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {{lms_form_label('Short Description', false)}}
                        <textarea name="short_description" class="form-control" rows="4" @lmsparsley(courses_add,short_description)>{{ old('short_description')}}</textarea>
                        @error('short_description')
                            <div class="parsley-errors-list">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row fixed-submit-container">
                <div class="col-md-4">
                    <div class="form-group d-flex justify-content-between gap-3">
                        <button type="submit" class="btn-submit btn btn-primary btn-sm m-b-0 flex-1"><i class="fa fa-save"></i> Save</button>
                        <button id="save-and-new" type="submit" class="btn-submit btn btn-dark btn-sm m-b-0 flex-1"><i class="fa fa-plus"></i> Save and New</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<script>
    $(document).ready(function() {
        $('#description').summernote({
            placeholder: 'Describe the course',
            tabsize: 2,
            height: 300
        });

        $('#is_paid').change(function() {
            if(this.value == '0'){
                $('.pricing-cont').addClass('d-none');
            }else{
                $('.pricing-cont').removeClass('d-none');
            }
        }).trigger('change');

        $('[name="discount_flag"]').change(function() {
            var discountPriceElement = $('.discounted-price-cont input');
            if(this.value == '0'){
                discountPriceElement.val('0.00').prop('readonly', true);
            }else{
                discountPriceElement.prop('readonly', false);
                if(discountPriceElement.val() == '0.00'){
                    discountPriceElement.focus();
                }
            }
        });
        $('[name="discount_flag"]:checked').trigger('change');
    });
</script>
@endsection