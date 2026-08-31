@extends('layouts.admin.skeleton')
@section('title', __('Job Posts'))

@section('page-header')
    @include('layouts.admin.page-header', [
        'icon' => 'ph ph-briefcase text-muted',
        'title' => __('Job Posts'),
        'breadcrumbs' => [
            route('admin.job-posts.index') => __('Job Posts')
        ],
        'links' => [
            lms_is_admin() ? generate_link_element([
                'route' => 'admin.job-posts.create'
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
                                            <a href="{{ route('admin.job-posts.index') }}" class="btn btn-default btn-sm m-b-0 bs-tt sloc" title="Reset Filter"><i class="fa fa-undo"></i> Clear</a>
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
                        <th>Price</th>
                        <th>Ratings</th>
                        <th>Status</th>
                        <th>Created on</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($jobPostList as $item) 
                        @php
                            $thumb = lms_thumb_placeholder();                            
                            if($item->thumbnail) $thumb = lms_storage($item->thumbnail);
                        @endphp
                        <tr class="tr_{{$item->id}}">
                            <td>
                                <img src="{{$thumb}}" class="img-icon-xxxs rounded">
                            </td>
                            <td>{{$item->title}}</td>
                            <td>{{$item->category->title ?? '-'}}</td>
                            <td>{{lms_show_price($item)}}</td>
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
                                        <a href="{{ route('admin.job-posts.edit', $item) }}" class="btn btn-primary btn-mini sloc bs-tt mr-2" title="Edit Item" data-placement="left">
                                            <i class="ph ph-pencil-simple mr-0"></i>
                                        </a>
                                        <form action="{{ route('admin.job-posts.activate', ['jobPostId' => $item->id, 'status' => $item->status ? 0 : 1]) }}" id="activate-form-{{$item->id}}" 
                                            class="d-inline-block mb-0 confirm-action mr-1" data-action="submit" 
                                            data-element_id="activate-form-{{$item->id}}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <button type="button" class="btn btn-{{$item->status ? 'warning' : 'success'}} btn-mini bs-tt" title="{{$item->status ? 'Deactivate' : 'Activate'}} Item" data-placement="left">
                                                <i class="ph ph-{{$item->status ? 'stop-circle' : 'check-circle'}} mr-0"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.job-posts.destroy', $item) }}" id="delete-form-{{$item->id}}" 
                                            class="d-inline-block mb-0 confirm-action" data-action="submit" 
                                            data-element_id="delete-form-{{$item->id}}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger btn-mini bs-tt" title="Delete Item" data-placement="left">
                                                <i class="ph ph-trash-simple mr-0"></i>
                                            </button>
                                        </form>
                                    @else
                                        <a href="{{ route('admin.job-posts.enrolments', ['jobPostId' => $item->id]) }}" class="btn btn-primary btn-mini sloc bs-tt mr-2" title="Edit Item" data-placement="left">
                                            <i class="ph ph-user-plus mr-0"></i> Enrolments
                                        </a>
                                    @endif
                                    <a href="{{ route('admin.job-posts.ratings', $item) }}" class="btn btn-yellow btn-mini sloc bs-tt mr-2" title="View Ratings" data-placement="left">
                                        <i class="fa fa-star mr-0 text-white"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{$jobPostList->links()}}
    </div>
</div>
@endsection