@extends('layouts.admin.skeleton')
@section('title', __('Organizations'))

@section('page-header')
    @include('layouts.admin.page-header', [
        'icon' => 'ph ph-book-open text-muted',
        'title' => __(key: 'Organizations'),
        'breadcrumbs' => [
            route('admin.users.organization') => __('Organizations')
        ],
        'links' => [
            generate_link_element([
                'route' => 'admin.users.organization.create'
            ]),
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
                                            <a href="{{ route('admin.users.organizations') }}" class="btn btn-default btn-sm m-b-0 bs-tt sloc" title="Reset Filter"><i class="fa fa-undo"></i> Clear</a>
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
                        <th>Organization</th>
                        <th>Org Admin</th>
                        <th>Staffs</th>
                        <th>Email ID</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Created on</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($organizationList as $item) 
                        <tr class="tr_{{$item->id}}">
                            <td>{{$item->name}}</td>
                            <td>{{$item->user->full_name}}</td>
                            <td><a href="{{ route('admin.users.organization') }}?id={{$item->id}}"><span class="badge bg-primary">{{$item->user_count()}}</span></a></td>
                            <td>{{$item->email}}</td>
                            <td>{{$item->phone}}</td>
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
                                    <a href="{{ route('admin.users.organization.edit', ['id' => $item->user_id]) }}" class="btn btn-primary btn-mini sloc bs-tt" title="Edit Organization" data-placement="left">
                                        <i class="ph ph-pencil-simple mr-0"></i>
                                    </a>                                    
                                    @if (lms_is_organization_admin())
                                        <a href="{{ route('admin.users.organization.staff.create', ['organizationId' => $item->id]) }}" class="btn btn-dark btn-mini sloc bs-tt ml-1" title="Add Organization Staff" data-placement="left">
                                            <i class="ph ph-plus mr-0"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{$organizationList->links()}}
    </div>
</div>
@endsection