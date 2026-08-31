@extends('layouts.admin.skeleton')
@section('title', __('Course Categories'))

@section('page-header')
    @include('layouts.admin.page-header', [
        'icon' => 'ph ph-tag text-muted',
        'title' => __('Course Categories'),
        'breadcrumbs' => [
            route('admin.course_categories.index') => __('Course Categories')
        ],
        'links' => [
            generate_link_element([
                'route' => 'admin.course_categories.create'
            ]),
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
                                            <a href="{{ route('admin.course_categories.index') }}" class="btn btn-default btn-sm m-b-0 bs-tt sloc" title="Reset Filter"><i class="fa fa-undo"></i> Clear</a>
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
                        <th>Parent</th>
                        <th>Status</th>
                        <th>Created on</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categoryList as $item) 
                        @php
                            $thumb = lms_thumb_placeholder();                            
                            if($item->thumbnail) $thumb = lms_storage($item->thumbnail);
                        @endphp
                        <tr class="tr_{{$item->id}}">
                            <td>
                                <img src="{{$thumb}}" class="img-icon-xxxs rounded">
                            </td>
                            <td>{{$item->title}}</td>
                            <td>{{$item->parent->title ?? '-'}}</td>
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
                                    <a href="{{ route('admin.course_categories.edit', $item) }}" class="btn btn-primary btn-mini sloc bs-tt mr-2" title="Edit Item" data-placement="left">
                                        <i class="ph ph-pencil-simple mr-0"></i>
                                    </a>
                                    <form action="{{ route('admin.course_categories.activate', ['id' => $item->id, 'status' => $item->status ? 0 : 1]) }}" id="activate-form-{{$item->id}}" 
                                        class="d-inline-block mb-0 confirm-action mr-1" data-action="submit" 
                                        data-element_id="activate-form-{{$item->id}}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button type="button" class="btn btn-{{$item->status ? 'warning' : 'success'}} btn-mini bs-tt" title="{{$item->status ? 'Deactivate' : 'Activate'}} Item" data-placement="left">
                                            <i class="ph ph-{{$item->status ? 'stop-circle' : 'check-circle'}} mr-0"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.course_categories.destroy', $item) }}" id="delete-form-{{$item->id}}" 
                                        class="d-inline-block mb-0 confirm-action" data-action="submit" 
                                        data-element_id="delete-form-{{$item->id}}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-mini bs-tt" title="Delete Item" data-placement="left">
                                            <i class="ph ph-trash-simple mr-0"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{$categoryList->links()}}
    </div>
</div>
@endsection