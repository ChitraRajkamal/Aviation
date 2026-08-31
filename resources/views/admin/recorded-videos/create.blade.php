@extends('layouts.admin.skeleton')
@section('title', __('Add Recorded Video'))

@section('page-header')
    @include('layouts.admin.page-header', [
        'icon' => 'ph ph-plus-square text-muted',
        'title' => __('Add Recorded Video'),
        'breadcrumbs' => [
            route('admin.recorded-videos.index') => __('Recorded Videos'),
            '#' => __('New'),
        ],
        'links' => [
            generate_link_element([
                'action' => 'back',
                'route' => 'admin.recorded-videos.index',
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
<form id="saveForm" class="autoSubmit" action="{{ route('admin.recorded-videos.store') }}" method="POST" enctype="multipart/form-data" data-parsley-validate>
    @csrf
    <input type="hidden" name="save_and_new" id="hf-save-and-new">
    <div class="card">
        <div class="card-block mt-1">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        {{lms_form_label('Title')}}
                        <input type="text" class="form-control" name="title" value="{{ old('title')}}" placeholder="{{__('Enter Title')}}" @lmsparsley(recorded_videos_add,title)>
                        @error('title')
                            <div class="parsley-errors-list">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {{lms_form_label('Link')}}
                        <input type="text" class="form-control" name="link" value="{{ old('link')}}" placeholder="{{__('Enter Link')}}" @lmsparsley(recorded_videos_add,link)>
                        @error('link')
                            <div class="parsley-errors-list">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {{lms_form_label('Price', false)}} <span class="text-muted">[Leave 0.00 if free]</span>
                        <input type="text" class="form-control decimal_input" name="price" value="{{ old('price')}}" placeholder="{{__('Enter Price')}}" @lmsparsley(recorded_videos_add,price)>
                        @error('price')
                            <div class="parsley-errors-list">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row">  
                <div class="col-md-4">
                    <div class="form-group">
                        {{lms_form_label('Category')}}
                        <select class="form-control select-two" id="recorded_video_category_id" data-placeholder="Choose Category"  name="recorded_video_category_id" @lmsparsley(recorded_videos_add,recorded_video_category_id)>
                            <option value="">Choose</option>
                            @foreach ($categoriesHierarchy as $item)
                              <option value="{{$item->id}}" {{ old('recorded_video_category_id') == $item->id ? 'selected' : '' }}>{{$item->title}}</option>
                            @endforeach
                        </select>
                        @error('recorded_video_category_id')
                            <div class="parsley-errors-list">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        {{lms_form_label('Thumbnail', false)}} <span class="file-resolution text-muted">[400 x 280]</span>
                        <input type="file" class="form-control-file" id="thumbnail" name="thumbnail" accept=".jpg,.jpeg,.png,.fig">
                        @error('thumbnail')
                            <div class="parsley-errors-list">{{ $message }}</div>
                        @enderror
                        <div class="border-3 border-primary text-dark p-2 mt-3 rounded shadow"><i class="fa fa-info-circle text-info"></i> If thumbnail empty, youtube thumb will be used</div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                        {{lms_form_label('Description', false)}}
                        <textarea id="description" name="description" class="form-control" rows="4" placeholder="{{__('Enter Description')}}" @lmsparsley(recorded_videos_add,description)>{{ old('description')}}</textarea>
                        @error('description')
                            <div class="parsley-errors-list">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
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
<script>
    $(document).ready(function() {
        $('#description').summernote({
            placeholder: 'Describe the video',
            tabsize: 2,
            height: 300
        });
    });
</script>
@endsection