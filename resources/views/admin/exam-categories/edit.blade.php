@extends('layouts.admin.skeleton')
@section('title', __('Edit Exam Category'))

@section('page-header')
    @include('layouts.admin.page-header', [
        'icon' => 'ph ph-pencil-simple text-muted',
        'title' => __('Edit Exam Category'),
        'breadcrumbs' => [
            route('admin.exam_categories.index') => __('Exam Categories'),
            '#' => __('Edit'),
        ],
        'links' => [
            generate_link_element([
                'action' => 'back',
                'route' => 'admin.exam_categories.index',
            ]),
        ]
    ])
@endsection

@section('content')
<form id="saveForm" class="manualSubmission" action="{{ route('admin.exam_categories.update', $examCategory) }}" method="POST" enctype="multipart/form-data" data-parsley-validate>
    @csrf
    @method('PUT')
    <div class="card">
        <div class="card-block mt-1">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        {{lms_form_label('Title')}}
                        <input type="text" class="form-control" name="title" value="{{ old('title', $examCategory->title)}}" placeholder="{{__('Enter Category Title')}}" @lmsparsley(exam_categories,title)>
                        @error('title')
                            <div class="parsley-errors-list">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4 d-none">
                    <div class="form-group">
                        {{lms_form_label('Parent Category', false)}}
                        <select class="form-control select-two" id="parent_id" name="parent_id" data-placeholder="Choose Parent Category" @lmsparsley(exam_categories,parent_id)>
                            <option value="">Choose Category</option>
                            @foreach ($categoriesHierarchy as $item)
                              <option value="{{$item->id}}" {{ old('parent_id', $examCategory->parent_id) == $item->id ? 'selected' : '' }}>{{$item->title}}</option>
                            @endforeach
                        </select>
                        @error('parent_id')
                            <div class="parsley-errors-list">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {{lms_form_label('Slug')}}
                        <input type="text" class="form-control" name="slug" value="{{ old('slug', $examCategory->slug)}}" 
                            data-parsley-required="true" data-parsley-required-message="Category slug is required" placeholder="{{__('Enter Category Slug')}}">
                        @error('slug')
                            <div class="parsley-errors-list">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        {{lms_form_label('Description', false)}}
                        <textarea name="description" class="form-control" rows="4" placeholder="{{__('Enter Category Description')}}" @lmsparsley(exam_categories,description)>{{ old('description', $examCategory->description)}}</textarea>
                        @error('description')
                            <div class="parsley-errors-list">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {{lms_form_label('Thumbnail', false)}} <span class="file-resolution text-muted">[400 x 280]</span>
                        <input type="file" class="form-control-file" id="thumbnail" name="thumbnail" accept=".jpg,.jpeg,.png,.fig">
                        @if ($examCategory->thumbnail)
                          <img src="{{lms_storage($examCategory->thumbnail)}}" class="img-thumbnail mt-3" style="height: 140px;" alt="">
                        @endif
                        @error('thumbnail')
                          <div class="text-danger">{{ $message }}</div>
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
        $('#saveForm').on('lms.form.validated', function (e){
            show_loader();
            this.submit();
        });
    });
</script>
@endsection

