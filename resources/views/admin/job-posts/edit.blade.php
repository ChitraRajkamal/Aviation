@extends('layouts.admin.skeleton')
@section('title', __('Edit Job Post'))

@section('page-header')
    @include('layouts.admin.page-header', [
        'icon' => 'ph ph-pencil-simple text-muted',
        'title' => __('Edit Job Post'),
        'breadcrumbs' => [
            route('admin.job-posts.index') => __('Job Posts'),
            '#' => __('Edit'),
        ],
        'links' => [
            generate_link_element([
                'action' => 'back',
                'route' => 'admin.job-posts.index',
            ]),
        ]
    ])
@endsection

@section('extra-styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/bower_components/summernote/summernote-bs4.min.css') }}">
@endsection

@section('extra-scripts')
    <script type="text/javascript" src="{{ asset('admin-assets/bower_components/summernote/summernote-bs4.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('admin-assets/assets/pages/j-pro/js/autoNumeric.js') }}"></script>
@endsection

@section('content')
<form id="saveForm" class="manualSubmission" action="{{ route('admin.job-posts.update', $jobPost) }}" method="POST" enctype="multipart/form-data" data-parsley-validate>
    @csrf
    @method('PUT')
    <div class="card">
        <div class="card-block mt-1">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        {{lms_form_label('Title')}}
                        <input type="text" class="form-control" name="title" value="{{ old('title', $jobPost->title)}}" placeholder="{{__('Enter Title')}}" @lmsparsley(job_posts_add,title)>
                        @error('title')
                            <div class="parsley-errors-list">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {{lms_form_label('Link')}}
                        <input type="text" class="form-control" name="link" value="{{ old('link', $jobPost->link)}}" placeholder="{{__('Enter Link')}}" @lmsparsley(job_posts_add,link)>
                        @error('link')
                            <div class="parsley-errors-list">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {{lms_form_label('Price', false)}} <span class="text-muted">[Leave 0.00 if free]</span>
                        <input type="text" class="form-control decimal_input" name="price" value="{{ old('price', $jobPost->price)}}" placeholder="{{__('Enter Price')}}" @lmsparsley(job_posts_add,price)>
                        @error('price')
                            <div class="parsley-errors-list">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row">  
                <div class="col-md-4">
                    <div class="form-group">
                        {{lms_form_label('Slug')}}
                        <input type="text" class="form-control" name="slug" value="{{ old('slug', $jobPost->slug)}}" 
                            data-parsley-required="true" data-parsley-required-message="Category slug is required" placeholder="{{__('Enter Slug')}}">
                        @error('slug')
                            <div class="parsley-errors-list">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        {{lms_form_label('Category')}}
                        <select class="form-control select-two" id="job_post_category_id" data-placeholder="Choose Category"  name="job_post_category_id" @lmsparsley(job_posts_add,job_post_category_id)>
                            <option value="">Choose</option>
                            @foreach ($categoriesHierarchy as $item)
                              <option value="{{$item->id}}" {{ old('job_post_category_id', $jobPost->job_post_category_id) == $item->id ? 'selected' : '' }}>{{$item->title}}</option>
                            @endforeach
                        </select>
                        @error('job_post_category_id')
                            <div class="parsley-errors-list">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        {{lms_form_label('Thumbnail', false)}} <span class="file-resolution text-muted">[400 x 280]</span>
                        <input type="file" class="form-control-file" id="thumbnail" name="thumbnail" accept=".jpg,.jpeg,.png,.fig">
                        @if ($jobPost->thumbnail)
                          <img src="{{lms_storage($jobPost->thumbnail)}}" class="img-thumbnail mt-3" style="height: 140px;" alt="">
                        @endif
                        @error('thumbnail')
                          <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                        {{lms_form_label('Description', false)}}
                        <textarea id="description" name="description" class="form-control" rows="4" placeholder="{{__('Enter Description')}}" @lmsparsley(job_posts_add,description)>{{ old('description', $jobPost->description)}}</textarea>
                        @error('description')
                            <div class="parsley-errors-list">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <button type="submit" class="btn-submit btn btn-primary btn-sm m-b-0"><i class="fa fa-save"></i> Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<script>
    $(document).ready(function (){
        $('#description').summernote({
            placeholder: 'Describe the job',
            tabsize: 2,
            height: 300
        });

        $('#saveForm').on('lms.form.validated', function (e){
            show_loader();
            this.submit();
        });
    });
</script>
@endsection

