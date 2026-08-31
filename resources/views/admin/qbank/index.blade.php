@extends('layouts.admin.skeleton')
@section('title', __('Question Banks'))

@section('page-header')
    @include('layouts.admin.page-header', [
        'icon' => 'ph ph-book-open text-muted',
        'title' => __('Question Banks'),
        'breadcrumbs' => [
            route('admin.qbank.index') => __('Question Banks')
        ],
        'links' => [
            lms_can_access('admin.qbank.create') ? generate_link_element([
                'route' => 'admin.qbank.create'
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
                                            <a href="{{ route('admin.qbank.index') }}" class="btn btn-default btn-sm m-b-0 bs-tt sloc" title="Reset Filter"><i class="fa fa-undo"></i> Clear</a>
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
                        <th>Name</th>
                        <th>Description</th>
                        <th>Active Questions</th>
                        <th>Status</th>
                        <th>Created on</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($qbankList as $item) 
                        @php
                            $thumb = lms_thumb_placeholder();                            
                            if($item->thumbnail) $thumb = lms_storage($item->thumbnail);
                        @endphp
                        <tr>
                            <td>{{$item->title}}</td>
                            <td>{{$item->description}}</td>
                            <td><span class="badge badge-dark">{{$item->question_count()}}</span></td>
                            <td>
                                @if ($item->status)
                                    <i class="ph ph-check-circle text-success"></i> Active
                                @else
                                    <i class="ph ph-stop-circle text-warning"></i> Inactive
                                @endif
                            </td>
                            <td>{{lms_format_date($item->created_at)}}</td>
                            <td class="dt-action">
                                <div class="btn-group" role="group">
                                    @if (lms_can_access('admin.qbank.questions.create') || lms_can_access('admin.qbank.import-questions') ||
                                                lms_can_access('admin.qbank.questions'))
                                        <div class="dropdown border-3 border-primary d-inline-block mr-2">
                                            <button class="btn btn-light btn-mini dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                More
                                            </button>                                        
                                            <div class="dropdown-menu">
                                                @if (lms_can_access('admin.qbank.questions.create'))
                                                    <a class="dropdown-item" href="{{ route('admin.qbank.questions.create', ['qbankId' => $item->id]) }}">
                                                        <i class="ph ph-plus"></i> Add Questions
                                                    </a>
                                                @endif
                                                @if (lms_can_access('admin.qbank.import-questions'))
                                                <a class="dropdown-item" href="{{ route('admin.qbank.import-questions', ['qbankId' => $item->id]) }}">
                                                    <i class="ph ph-upload"></i> Import Questions
                                                </a>
                                                @endif
                                                @if (lms_can_access('admin.qbank.questions'))
                                                    <a class="dropdown-item" href="{{ route('admin.qbank.questions', ['qbankId' => $item->id]) }}">
                                                        <i class="ph ph-question"></i> Manage Questions
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                    @if (lms_can_access('admin.qbank.edit'))
                                        <a href="{{ route('admin.qbank.edit', $item) }}" class="btn btn-primary btn-mini sloc bs-tt mr-1" title="Edit Item" data-placement="left">
                                            <i class="ph ph-pencil-simple mr-0"></i>
                                        </a>
                                    @endif
                                    @if (lms_can_access('admin.qbank.activate'))
                                        <form action="{{ route('admin.qbank.activate', ['qbankId' => $item->id, 'status' => $item->status ? 0 : 1]) }}" id="activate-form-{{$item->id}}" 
                                            class="d-inline-block mb-0 confirm-action mr-1" data-action="submit" 
                                            data-element_id="activate-form-{{$item->id}}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <button type="button" class="btn btn-{{$item->status ? 'warning' : 'success'}} btn-mini bs-tt" title="{{$item->status ? 'Deactivate' : 'Activate'}} Item" data-placement="left">
                                                <i class="ph ph-{{$item->status ? 'stop-circle' : 'check-circle'}} mr-0"></i>
                                            </button>
                                        </form>
                                    @endif
                                    @if (lms_can_access('admin.qbank.destroy'))
                                        <form action="{{ route('admin.qbank.destroy', $item) }}" id="delete-form-{{$item->id}}" 
                                            class="d-inline-block mb-0 mr-1 confirm-action" data-action="submit" 
                                            data-element_id="delete-form-{{$item->id}}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger btn-mini bs-tt" title="Delete Item" data-placement="left">
                                                <i class="ph ph-trash-simple mr-0"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="d-flex align-items-center flex-column flex-md-row justify-content-between mt-3">
            <div>Total Question Banks: <b>{{ $qbankList->total() }}</b></div>
            <div>{{$qbankList->links()}}</div>
        </div>
        
    </div>
</div>
@endsection