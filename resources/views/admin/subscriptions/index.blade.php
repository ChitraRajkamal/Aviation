@extends('layouts.admin.skeleton')
@section('title', __('Subscriptions'))

@php
    $currency_symbol = lms_setting('currency_symbol');
@endphp

@section('page-header')
    @include('layouts.admin.page-header', [
        'icon' => 'ph ph-bookmark text-muted',
        'title' => __('Subscriptions'),
        'breadcrumbs' => [
            route('admin.subscriptions.index') => __('Subscriptions')
        ],
        'links' => [
            generate_link_element([
                'route' => 'admin.subscriptions.create'
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
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-form-label">Organization</label>
                                        <select class="form-control select-two" data-placeholder="Choose Organization" name="organization_id">
                                            <option value="">Choose Organization</option>
                                            @foreach ($organizations as $item)
                                              <option value="{{$item->id}}" {{ request('organization_id') == $item->id ? 'selected' : '' }}>{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="col-form-label d-md-block d-none">&nbsp;</label>
                                        <button type="submit" class="btn-submit btn btn-primary btn-sm m-b-0"><i class="fa fa-filter"></i> Filter</button>
                                        @if (request('filter'))
                                            <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-default btn-sm m-b-0 bs-tt sloc" title="Reset Filter"><i class="fa fa-undo"></i> Clear</a>
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
                        <th>No of Students</th>
                        <th>Courses</th>
                        <th>Exams</th>
                        <th>Jobs</th>
                        <th>Rec. Videos</th>
                        <th>Status</th>
                        <th class="text-right">Price</th>
                        <th>Created on</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($subscriptionList as $item) 
                        <tr class="tr_{{$item->id}}">
                            <td>{{$item->organization->name}}</td>
                            <td>{{$item->no_of_students}}</td>
                            <td>
                                @if ($item->allow_courses)
                                    <i class="fa fa-check-circle text-success"></i> Needed
                                @else
                                    <i class="fa fa-stop-circle text-warning"></i> Not Needed
                                @endif
                            </td>
                            <td>
                                @if ($item->allow_exams)
                                    <i class="fa fa-check-circle text-success"></i> Needed
                                @else
                                    <i class="fa fa-stop-circle text-warning"></i> Not Needed
                                @endif
                            </td>
                            <td>
                                @if ($item->allow_job_posts)
                                    <i class="fa fa-check-circle text-success"></i> Needed
                                @else
                                    <i class="fa fa-stop-circle text-warning"></i> Not Needed
                                @endif
                            </td>
                            <td>
                                @if ($item->allow_recorded_videos)
                                    <i class="fa fa-check-circle text-success"></i> Needed
                                @else
                                    <i class="fa fa-stop-circle text-warning"></i> Not Needed
                                @endif
                            </td>
                            <td>
                                <span class="text-uppercase badge badge-{{$item->status == \App\Enums\SubscriptionStatus::Unpaid->value ? 'danger' : ($item->status == \App\Enums\SubscriptionStatus::Paid->value ? 'primary' : 'success')}}">
                                    {{$item->status}}
                                </span>
                            </td>
                            <td class="text-right">{{$currency_symbol . $item->price}}</td>
                            <td>{{lms_format_date($item->created_at)}}</td>
                            <td class="dt-actions dt-actions-lg">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.subscriptions.edit', $item) }}" class="btn btn-primary btn-mini sloc bs-tt mr-2" title="Edit Item" data-placement="left">
                                        <i class="ph ph-pencil-simple mr-0"></i>
                                    </a>
                                    <a href="{{ route('admin.subscriptions.show', $item) }}" class="btn btn-dark btn-mini sloc bs-tt mr-2" title="View Details" data-placement="left">
                                        <i class="ph ph-tag mr-0"></i>
                                    </a>
                                    @if ($item->status == \App\Enums\SubscriptionStatus::Approved->value)
                                        <a href="{{ route('admin.subscriptions.setup', ['organizationId' => $item->organization_id]) }}" class="btn btn-info btn-mini sloc bs-tt mr-2" title="Setup" data-placement="left">
                                            <i class="ph ph-gear mr-0"></i>
                                        </a>
                                    @endif
                                    @if ($item->status != \App\Enums\SubscriptionStatus::Approved->value)
                                        <form action="{{ route('admin.subscriptions.destroy', $item) }}" id="delete-form-{{$item->id}}" 
                                            class="d-inline-block mb-0 confirm-action" data-action="submit" 
                                            data-element_id="delete-form-{{$item->id}}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger btn-mini bs-tt" title="Delete Item" data-placement="left">
                                                <i class="ph ph-trash-simple mr-0"></i>
                                            </button>
                                        </form>
                                    @endif
                                    @if ($item->status == \App\Enums\SubscriptionStatus::Paid->value)
                                        <form action="{{ route('admin.subscriptions.approve', ['id' => $item->id]) }}" id="approve-form-{{$item->id}}" 
                                            class="d-inline-block mb-0 confirm-action ml-1" data-action="submit" 
                                            data-element_id="approve-form-{{$item->id}}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <button type="button" class="btn btn-success btn-mini bs-tt" title="Approve" data-placement="left">
                                                <i class="ph ph-check-circle mr-0"></i>
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
        {{$subscriptionList->links()}}
    </div>
</div>
@endsection