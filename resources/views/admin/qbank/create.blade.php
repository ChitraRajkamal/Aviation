@extends('layouts.admin.skeleton')
@section('title', __('Add Question Bank'))

@section('page-header')
    @include('layouts.admin.page-header', [
        'icon' => 'ph ph-plus-square text-muted',
        'title' => __('Add Question Bank'),
        'breadcrumbs' => [
            route('admin.qbank.index') => __('Question Banks'),
            '#' => __('New'),
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
<form id="saveForm" class="autoSubmit" action="{{ route('admin.qbank.store') }}" method="POST" enctype="multipart/form-data" data-parsley-validate>
    @csrf
    <input type="hidden" name="save_and_new" id="hf-save-and-new">
    <div class="card">
        <div class="card-block mt-1">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        {{lms_form_label('Title')}}
                        <input type="text" class="form-control" name="title" value="{{ old('title')}}" placeholder="{{__('Enter Question Bank Title')}}" @lmsparsley(qbank_add,title)>
                        @error('title')
                            <div class="parsley-errors-list">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        {{lms_form_label('Description', false)}}
                        <textarea id="description" name="description" class="form-control h-180" rows="4"placeholder="{{__('Enter Description')}}" @lmsparsley(qbank_add,description)>{{ old('description')}}</textarea>
                        @error('description')
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
@endsection