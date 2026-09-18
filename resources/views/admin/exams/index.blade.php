@extends('layouts.admin.skeleton')
@section('title', __('Exams'))

@section('page-header')
    @include('layouts.admin.page-header', [
        'icon' => 'ph ph-notebook text-muted',
        'title' => __('Exams'),
        'breadcrumbs' => [
            route('admin.exams.index') => __('Exams')
        ],
        'links' => [
            /*lms_can_access('admin.exams.create')*/lms_is_admin() ? generate_link_element([
                'route' => 'admin.exams.create'
            ]) : false,
        ]
    ])
@endsection

@section('content')
<form action="">
    <input type="hidden" name="filter" value="true">
    <div class="card mb-0 search-filter">
        <div class="card-block accordion-block">
            <div id="accordion" role="tablist" aria-multiselectable="true">
                <div class="accordion-panel">
                    <div class="accordion-heading" role="tab" id="headingOne">
                        <h3 class="card-title accordion-title bg-inverse">
                            <a class="accordion-msg text-light" data-toggle="collapse" data-parent="#accordion" href="#collapseFilter" aria-expanded="true" aria-controls="collapseFilter">
                                <i class="fa fa-filter mr-2"></i> SEARCH FILTER
                                <span class="float-right bs-tt" title="Collapse / Expand Filter"><i class="fa fa-sort"></i></span>
                            </a>
                        </h3>
                    </div>
                    <div id="collapseFilter" class="panel-collapse collapse in {{ request('filter') ? 'show' : ''}}" role="tabpanel" aria-labelledby="headingOne">
                        <div class="accordion-content accordion-desc p-3">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-form-label">Search</label>
                                        <input type="text" id="filter_search" name="search" class="form-control" placeholder="Search by Name" value="{{request('search')}}" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-form-label">Category</label>
                                        <select class="form-control select-two" data-placeholder="Choose Category" name="category_id">
                                            <option value="">Choose Category</option>
                                            @foreach ($categoriesHierarchy as $item)
                                              <option value="{{$item->id}}" {{ request('category_id') == $item->id ? 'selected' : '' }}>{{$item->title}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="col-form-label d-md-block d-none">&nbsp;</label>
                                        <button type="submit" class="btn-submit btn btn-primary btn-sm m-b-0"><i class="fa fa-filter"></i> Filter</button>
                                        @if (request('filter'))
                                            <a href="{{ route('admin.exams.index') }}" class="btn btn-default btn-sm m-b-0 bs-tt sloc" title="Reset Filter"><i class="fa fa-undo"></i> Clear</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<div class="card">
    <div class="card-block">
        <div class="dt-responsive table-responsive dt-fixed-controls">
            <table id="listing-data-table" class="g-datatable table compact table-striped table-hover table-bordered table-header-dark nowrap">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Questions</th>
                        <th>Ratings</th>
                        <th>Status</th>
                        <th>Created on</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($examList as $item)
                        @php
                            $thumb = lms_thumb_placeholder();
                            if($item->thumbnail) $thumb = lms_storage($item->thumbnail);
                        @endphp
                        <tr class="tr_{{$item->id}}">
                            <td>
                                <img src="{{$thumb}}" class="img-icon-xxxs rounded">
                            </td>
                            <td>
                                {{$item->title}}
                            </td>
                            <td>{{$item->category->title ?? '-'}}</td>
                            <td>{{$item->question_count()}}</td>
                            <td>
                                <div class="stars-area">
                                    <span class="bs-tt fw-bold" title="Average ratings">{{lms_decimal_points($item->rating_average($global_is_organization), 1)}}</span>
                                    <div class="stars-area">
                                        <div class="stars-background">
                                            {!!str_repeat('<i class="fa fa-star-o"></i>', 5)!!}
                                        </div>
                                        <div class="stars-overlay" style="width: {{lms_rating_to_percentage($item->rating_average($global_is_organization))}}%;">
                                            {!!str_repeat('<i class="fa fa-star"></i>', 5)!!}
                                        </div>
                                    </div>
                                    <span class="bs-tt text-muted" title="Number of ratings">({{$item->rating_count($global_is_organization)}})</span>
                                </div>
                            </td>
                            <td>
                                @if ($item->status)
                                    <i class="ph ph-check-circle text-success"></i> Active
                                @else
                                    <i class="ph ph-stop-circle text-warning"></i> Inactive
                                @endif
                            </td>
                            <td>{{lms_format_date($item->created_at)}}</td>
                            <td class="dt-actions">
                                <div class="btn-group" role="group">
                                    @if ($item->organization_id == $global_organization_id)
                                        @if (lms_can_access('admin.exams.results'))
                                            <div class="dropdown border-3 border-primary d-inline-block mr-2">
                                                <button class="btn btn-light btn-mini dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    More
                                                </button>
                                                <div class="dropdown-menu">
                                                    @if (lms_can_access('admin.exams.edit'))
                                                        <a class="dropdown-item" href="{{ route('admin.exams.edit', $item) }}"><i class="ph ph-pencil-simple"></i> Edit Exam</a>
                                                    @endif
                                                    @if (lms_can_access('admin.exams.results'))
                                                        <a class="dropdown-item" href="{{ route('admin.exams.results', ['examId' => $item->id]) }}"><i class="ph ph-file"></i> Exam Results</a>
                                                    @endif
                                                    @if (lms_can_access('admin.exams.ratings'))
                                                        <a class="dropdown-item" href="{{ route('admin.exams.ratings', $item) }}"><i class="ph ph-star"></i> View Ratings</a>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                        @if (lms_can_access('admin.exams.activate'))
                                            <form action="{{ route('admin.exams.activate', ['examId' => $item->id, 'status' => $item->status ? 0 : 1]) }}" id="activate-form-{{$item->id}}"
                                                class="d-inline-block mb-0 confirm-action mr-1" data-action="submit"
                                                data-element_id="activate-form-{{$item->id}}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <button type="button" class="btn btn-{{$item->status ? 'warning' : 'success'}} btn-mini bs-tt" title="{{$item->status ? 'Deactivate' : 'Activate'}} Item" data-placement="left">
                                                    <i class="ph ph-{{$item->status ? 'stop-circle' : 'check-circle'}} mr-0"></i>
                                                </button>
                                            </form>
                                        @endif
                                        @if (lms_can_access('admin.exams.destroy'))
                                            <form action="{{ route('admin.exams.destroy', $item) }}" id="delete-form-{{$item->id}}"
                                                class="d-inline-block mb-0 mr-1 confirm-action" data-action="submit"
                                                data-element_id="delete-form-{{$item->id}}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-danger btn-mini bs-tt" title="Delete Item" data-placement="left">
                                                    <i class="ph ph-trash-simple mr-0"></i>
                                                </button>
                                            </form>
                                        @endif
                                    @else
                                        @if (lms_can_access('admin.exams.results'))
                                            <a href="{{ route('admin.exams.results', ['examId' => $item->id]) }}" class="btn btn-primary btn-mini sloc bs-tt mr-2" title="Edit Item" data-placement="left">
                                                <i class="ph ph-file mr-0"></i> Result
                                            </a>
                                        @endif
                                        @if (lms_can_access('admin.exams.ratings'))
                                            <a href="{{ route('admin.exams.ratings', $item) }}" class="btn btn-yellow btn-mini sloc bs-tt mr-2" title="View Ratings" data-placement="left">
                                                <i class="fa fa-star mr-0 text-white"></i>
                                            </a>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="d-flex align-items-center flex-column flex-md-row justify-content-between mt-3">
            <div>Total Exams: <b>{{ $examList->total() }}</b></div>
            <div>{{$examList->links()}}</div>
        </div>

    </div>
</div>
@endsection
