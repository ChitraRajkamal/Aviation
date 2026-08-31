@extends('layouts.admin.skeleton')
@section('title', __('View Course Category'))

@section('page-header')
    @include('layouts.admin.page-header', [
        'icon' => 'ph ph-tag text-muted',
        'title' => __('View Course Category'),
        'breadcrumbs' => [
            route('admin.course_categories.index') => __('Course Categories'),
            '#' => __('View'),
        ],
        'links' => [
            generate_link_element([
                'action' => 'back',
                'route' => 'admin.course_categories.index',
            ]),
            generate_link_element([
                'action' => 'edit',
                'route' => 'admin.course_categories.edit',
                'route_data' => ['category' => $courseCategory->id]
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
                    <div class="">{{$courseCategory->title}}</div>
                </div>
                <div class="form-group">
                    <label class="col-form-label">Parent Category</label>
                    <div class="">{{ optional($courseCategory->parent)->title ?? "-" }}</div>
                </div>
                <div class="form-group">
                    <label class="col-form-label">Descricription</label>
                    <div class="">{{$courseCategory->description}}</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="col-form-label">Thumb</label>
                    <div>
                        @if ($courseCategory->thumbnail)
                            <img src="{{lms_storage($courseCategory->thumbnail)}}" class="img-thumbnail" style="height: 200px;" alt="">
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

