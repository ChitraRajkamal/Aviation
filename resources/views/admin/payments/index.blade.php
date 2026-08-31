@extends('layouts.admin.skeleton')
@section('title', __('Payments'))

@section('page-header')
    @include('layouts.admin.page-header', [
        'icon' => 'ph ph-book-open text-muted',
        'title' => __('Payments'),
        'breadcrumbs' => [
            '#' => __('Payments')
        ],
        'links' => [
            /*generate_link_element([
                'route' => 'admin.exams.create'
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
                                        <input type="text" id="filter_search" name="search" class="form-control" placeholder="Search by Payment ID, Order ID" value="{{request('search')}}" />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="col-form-label d-md-block d-none">&nbsp;</label>
                                        <button type="submit" class="btn-submit btn btn-primary btn-sm m-b-0"><i class="fa fa-filter"></i> Filter</button>
                                        @if (request('search'))
                                            <a href="{{ route('admin.payments') }}" class="btn btn-default btn-sm m-b-0 bs-tt sloc" title="Reset Filter"><i class="fa fa-undo"></i> Clear</a>
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
                        <th>User</th>
                        <th>Order ID</th>
                        <th>Payment ID</th>
                        <th class="text-right">Amount</th>
                        <th>Created on</th>
                        <th class="d-none">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($paymentList as $item) 
                        <tr class="tr_{{$item->id}}">
                            <td>{{$item->user->full_name}}</td>
                            <td>{{$item->order_id}}</td>
                            <td>{{$item->payment_id}}</td>
                            <td class="text-right fw-bold">{{$item->amount}}</td>
                            <td>{{lms_format_date($item->created_at)}}</td>
                            <td class="dt-actions d-none">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.exams.show', $item) }}" class="btn btn-dark btn-mini sloc bs-tt d-none" title="View Details" data-placement="left">
                                        <i class="ph ph-tag mr-0"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{$paymentList->links()}}
    </div>
</div>
@endsection