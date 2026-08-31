@extends('layouts.admin.skeleton')
@section('title', __('Courses'))

@section('page-header')
    @include('layouts.admin.page-header', [
        'icon' => 'ph ph-book-open text-muted',
        'title' => __('Courses'),
        'breadcrumbs' => [
            route('admin.courses.index') => __('Courses')
        ],
        'links' => [
            /*lms_can_access('admin.courses.create')*/lms_is_admin() ? generate_link_element([
                'route' => 'admin.courses.create'
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
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="col-form-label d-md-block d-none">&nbsp;</label>
                                        <button type="submit" class="btn-submit btn btn-primary btn-sm m-b-0"><i class="fa fa-filter"></i> Filter</button>
                                        @if (request('filter'))
                                            <a href="{{ route('admin.courses.index') }}" class="btn btn-default btn-sm m-b-0 bs-tt sloc" title="Reset Filter"><i class="fa fa-undo"></i> Clear</a>
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
                        <th>Ratings</th>
                        <th>Status</th>
                        <th>Created on</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($courseList as $item) 
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
                                <a href="{{ route('courses.details', ['slug'=>$item->slug]) }}" target="_blank"><i class="ph ph-eye"></i></a>
                            </td>
                            <td>{{$item->category->title ?? '-'}}</td>                            
                            <td>
                                <div class="stars-area">
                                    <span class="bs-tt fw-bold" title="Average ratings">{{lms_decimal_points($item->rating_average($global_is_organization))}}</span>
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
                                @if ($item->status == 'Active')
                                    <i class="ph ph-check-circle text-success"></i> Active
                                @else
                                    <i class="ph ph-stop-circle text-warning"></i> Inactive
                                @endif
                            </td>
                            <td>{{lms_format_date($item->created_at)}}</td>
                            <td class="dt-action">
                                <div class="btn-group" role="group">
                                    @if ($item->organization_id == $global_organization_id)
                                        @if (lms_can_access('admin.courses.enrolments'))
                                            <a href="{{ route('admin.courses.enrolments', $item) }}" class="btn btn-dark btn-mini sloc bs-tt mr-2" title="View Enrolments" data-placement="left">
                                                <i class="fa fa-user-plus mr-0 text-white"></i>
                                            </a>
                                        @endif
                                        @if (lms_can_access('admin.courses.ratings'))
                                            <a href="{{ route('admin.courses.ratings', $item) }}" class="btn btn-yellow btn-mini sloc bs-tt mr-2" title="View Ratings" data-placement="left">
                                                <i class="fa fa-star mr-0 text-white"></i>
                                            </a>
                                        @endif
                                        @if (lms_can_access('admin.courses.edit'))
                                            <a href="{{ route('admin.courses.edit', $item) }}" class="btn btn-primary btn-mini sloc bs-tt mr-2" title="Edit Item" data-placement="left">
                                                <i class="ph ph-pencil-simple mr-0"></i>
                                            </a>
                                        @endif
                                        @if (lms_can_access('admin.courses.activate'))
                                            <form action="{{ route('admin.courses.activate', ['courseId' => $item->id, 'status' => $item->status == 'Active' ? 0 : 1]) }}" id="activate-form-{{$item->id}}" 
                                                class="d-inline-block mb-0 confirm-action mr-1" data-action="submit" 
                                                data-element_id="activate-form-{{$item->id}}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <button type="button" class="btn btn-{{$item->status == 'Active' ? 'warning' : 'success'}} btn-mini bs-tt" title="{{$item->status == 'Active' ? 'Deactivate' : 'Activate'}} Item" data-placement="left">
                                                    <i class="ph ph-{{$item->status == 'Active' ? 'stop-circle' : 'check-circle'}} mr-0"></i>
                                                </button>
                                            </form>
                                        @endif
                                        @if (lms_can_access('admin.courses.destroy'))
                                            <form action="{{ route('admin.courses.destroy', $item) }}" id="delete-form-{{$item->id}}" 
                                                class="d-inline-block mb-0 confirm-action" data-action="submit" 
                                                data-element_id="delete-form-{{$item->id}}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-danger btn-mini bs-tt" title="Delete Item" data-placement="left">
                                                    <i class="ph ph-trash-simple mr-0"></i>
                                                </button>
                                            </form>
                                        @endif
                                    @else
                                        @if (lms_can_access('admin.courses.enrolments'))
                                            <a href="{{ route('admin.courses.enrolments', $item) }}" class="btn btn-dark btn-mini sloc bs-tt mr-2" title="View Enrolments" data-placement="left">
                                                <i class="fa fa-user-plus mr-0 text-white"></i>
                                            </a>
                                        @endif
                                        @if (lms_can_access('admin.courses.ratings'))
                                            <a href="{{ route('admin.courses.ratings', $item) }}" class="btn btn-yellow btn-mini sloc bs-tt mr-2" title="View Ratings" data-placement="left">
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
        {{$courseList->links()}}
    </div>
</div>
@endsection