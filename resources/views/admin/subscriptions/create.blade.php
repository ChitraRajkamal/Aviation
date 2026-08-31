@extends('layouts.admin.skeleton')
@section('title', __('Add Subscription'))

@section('page-header')
    @include('layouts.admin.page-header', [
        'icon' => 'ph ph-plus-square text-muted',
        'title' => __('Add Subscription'),
        'breadcrumbs' => [
            route('admin.subscriptions.index') => __('Subscriptions'),
            '#' => __('New'),
        ],
        'links' => [
            generate_link_element([
                'action' => 'back',
                'route' => 'admin.subscriptions.index',
            ]),
        ]
    ])
@endsection

@section('extra-scripts')
    <script type="text/javascript" src="{{ asset('admin-assets/assets/pages/j-pro/js/autoNumeric.js') }}"></script>
@endsection

@section('content')
<form id="saveForm" class="autoSubmit" action="{{ route('admin.subscriptions.store') }}" method="POST" enctype="multipart/form-data" data-parsley-validate>
    @csrf
    <input type="hidden" name="save_and_new" id="hf-save-and-new">
    <div class="card">
        <div class="card-block mt-1">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        {{lms_form_label('Organization')}}
                        <select class="form-control select-two" id="organization_id" data-placeholder="Choose Organization" required name="organization_id" @lmsparsley(subscriptions_add,organization_id)>
                            <option value="">Choose</option>
                            @foreach ($organizations as $item)
                              <option value="{{$item->id}}" {{ old('organization_id') == $item->id ? 'selected' : '' }}>{{$item->name}}</option>
                            @endforeach
                        </select>
                        @error('organization_id')
                            <div class="parsley-errors-list">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {{lms_form_label('No of students')}}</span>
                        <input type="number" min="0" class="form-control" name="no_of_students" value="{{ old('no_of_students')}}" placeholder="{{__('Enter No of Students')}}" @lmsparsley(subscriptions_add,no_of_students)>
                        @error('no_of_students')
                            <div class="parsley-errors-list">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        {{lms_form_label('Need Courses?')}}<br>
                        <div class="">
                            <label class="mr-2"><input {{old('allow_courses')=='1'?'checked':''}} type="radio" name="allow_courses" value="1" data-parsley-errors-container="#courseError" @lmsparsley(subscriptions_add,allow_courses)> Yes</label>
                            <label><input {{old('allow_courses')=='0'?'checked':''}} type="radio" name="allow_courses" value="0" @lmsparsley(subscriptions_add,allow_courses)> No</label>
                        </div>
                        <div id="courseError"></div>
                        @error('allow_courses')
                            <div class="parsley-errors-list">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {{lms_form_label('Need Exams?')}}<br>
                        <div class="">
                            <label class="mr-2"><input {{old('allow_exams')=='1'?'checked':''}} type="radio" name="allow_exams" value="1" data-parsley-errors-container="#examError" @lmsparsley(subscriptions_add,allow_exams)> Yes</label>
                            <label><input {{old('allow_exams')=='0'?'checked':''}} type="radio" name="allow_exams" value="0" @lmsparsley(subscriptions_add,allow_exams)> No</label>
                        </div>
                        <div id="examError"></div>
                        @error('allow_exams')
                            <div class="parsley-errors-list">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        {{lms_form_label('Need Job Posts?')}}<br>
                        <div class="">
                            <label class="mr-2"><input {{old('allow_job_posts')=='1'?'checked':''}} type="radio" name="allow_job_posts" value="1" data-parsley-errors-container="#jobPostError" @lmsparsley(subscriptions_add,allow_job_posts)> Yes</label>
                            <label><input {{old('allow_job_posts')=='0'?'checked':''}} type="radio" name="allow_job_posts" value="0" @lmsparsley(subscriptions_add,allow_job_posts)> No</label>
                        </div>
                        <div id="jobPostError"></div>
                        @error('allow_job_posts')
                            <div class="parsley-errors-list">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {{lms_form_label('Need Recorded Videos?')}}<br>
                        <div class="">
                            <label class="mr-2"><input {{old('allow_recorded_videos')=='1'?'checked':''}} type="radio" name="allow_recorded_videos" value="1" data-parsley-errors-container="#recordedVideoError" @lmsparsley(subscriptions_add,allow_recorded_videos)> Yes</label>
                            <label><input {{old('allow_recorded_videos')=='0'?'checked':''}} type="radio" name="allow_recorded_videos" value="0" @lmsparsley(subscriptions_add,allow_recorded_videos)> No</label>
                        </div>
                        <div id="recordedVideoError"></div>
                        @error('allow_recorded_videos')
                            <div class="parsley-errors-list">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        {{lms_form_label('Price')}}</span>
                        <input type="text" class="form-control decimal_input" name="price" value="{{ old('price')}}" placeholder="{{__('Enter Price')}}" @lmsparsley(subscriptions_add,price)>
                        @error('price')
                            <div class="parsley-errors-list">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
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
@endsection