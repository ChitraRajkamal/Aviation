@extends('layouts.admin.skeleton')
@section('title', __('View Exam Category'))

@section('page-header')
    @include('layouts.admin.page-header', [
        'icon' => 'ph ph-tag text-muted',
        'title' => __('View Exam Category'),
        'breadcrumbs' => [
            route('admin.exam_categories.index') => __('Exam Categories'),
            '#' => __('View'),
        ],
        'links' => [
            generate_link_element([
                'action' => 'back',
                'route' => 'admin.exam_categories.index',
            ]),
            generate_link_element([
                'action' => 'edit',
                'route' => 'admin.exam_categories.edit',
                'route_data' => ['category' => $examCategory->id]
            ]),
        ]
    ])
@endsection

@section('content')
<div class="card">
    <div class="card-block mt-1">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="col-form-label">Name</label>
                    <div class="">{{$examCategory->title}}</div>
                </div>
                <div class="form-group d-none">
                    <label class="col-form-label">Parent Category</label>
                    <div class="">{{ optional($examCategory->parent)->title ?? "-" }}</div>
                </div>
                <div class="form-group">
                    <label class="col-form-label">Descricription</label>
                    <div class="">{{$examCategory->description}}</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="col-form-label">Thumb</label>
                    <div>
                        @if ($examCategory->thumbnail)
                            <img src="{{lms_storage($examCategory->thumbnail)}}" class="img-thumbnail" style="height: 200px;" alt="">
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

