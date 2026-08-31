@extends('layouts.admin.skeleton')
@section('title', __('Edit Question Bank'))

@section('page-header')
    @include('layouts.admin.page-header', [
        'icon' => 'ph ph-pencil-simple text-muted',
        'title' => __('Edit Question Bank'),
        'breadcrumbs' => [
            route('admin.qbank.index') => __('Question Banks'),
            '#' => __('Edit'),
        ],
        'links' => [
            generate_link_element([
                'action' => 'back',
                'route' => 'admin.qbank.index',
            ]),
        ]
    ])
@endsection

@section('content')
<form id="saveForm" class="autoSubmit" action="{{ route('admin.qbank.update', ['qbank' => $qbank]) }}" method="POST" enctype="multipart/form-data" data-parsley-validate>
    @csrf
    @method('PUT')
    <input type="hidden" name="save_and_new" id="hf-save-and-new">
    <div class="card">
        <div class="card-block mt-1">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        {{lms_form_label('Title')}}
                        <input type="text" class="form-control" name="title" value="{{ old('title', $qbank->title)}}" placeholder="{{__('Enter Question Bank Title')}}" @lmsparsley(qbanks_add,title)>
                        @error('title')
                            <div class="parsley-errors-list">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        {{lms_form_label('Description', false)}}
                        <textarea id="description" name="description" class="form-control h-180" rows="4"placeholder="{{__('Enter Description')}}" @lmsparsley(qbanks_add,description)>{{ old('description', $qbank->description)}}</textarea>
                        @error('description')
                            <div class="parsley-errors-list">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="row fixed-submit-container">
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