@extends('layouts.admin.skeleton')
@section('title', __('Staffs'))

@section('page-header')
    @include('layouts.admin.page-header', [
        'icon' => 'ph ph-shield-check text-muted',
        'title' => __('Users') . ' | ' . __('Staffs'),
        'breadcrumbs' => [
            route('admin.users.admin') => __('Users')
        ],
        'links' => [
            /*generate_link_element([
                'route' => 'admin.users.admin.create'
            ]),*/
        ]
    ])
@endsection

@section('content')
<form action="">
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
                    <div id="collapseFilter" class="panel-collapse collapse in {{ request('search') ? 'show' : ''}}" role="tabpanel" aria-labelledby="headingOne">
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
                                        @if (request('search'))
                                            <a href="{{ route('admin.users.admin') }}" class="btn btn-default btn-sm m-b-0 bs-tt sloc" title="Reset Filter"><i class="fa fa-undo"></i> Clear</a>
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
                        <th>Email ID</th>
                        <th>Mobile</th>
                        <th>Status</th>
                        <th>Created on</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($userList as $item) 
                        <tr class="tr_{{$item->id}}">
                            <td>{{$item->full_name}}</td>
                            <td>{{$item->email}}</td>
                            <td>{{$item->mobile}}</td>
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
                                    <a href="{{ route('admin.exams.edit', $item) }}" class="btn btn-primary btn-mini sloc bs-tt d-none" title="Edit Staff" data-placement="left">
                                        <i class="ph ph-pencil-simple mr-0"></i>
                                    </a>
                                    <form action="{{ route('admin.users.staff.activate', ['userId' => $item->id, 'status' => $item->status ? 0 : 1]) }}" id="activate-form-{{$item->id}}" 
                                        class="d-inline-block mb-0 confirm-action ml-1" data-action="submit" 
                                        data-element_id="activate-form-{{$item->id}}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button type="button" class="btn btn-{{$item->status ? 'warning' : 'success'}} btn-mini bs-tt" title="{{$item->status ? 'Deactivate' : 'Activate'}} Staff" data-placement="left">
                                            <i class="ph ph-{{$item->status ? 'stop-circle' : 'check-circle'}} mr-0"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{$userList->links()}}
    </div>
</div>
@endsection