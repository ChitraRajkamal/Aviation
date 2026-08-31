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
    ])
@endsection

@section('content')
<div class="card">
    <div class="card-block">
        <div class="dt-responsive table-responsive dt-fixed-controls">
            <table id="listing-data-table" class="g-datatable table compact table-striped table-hover table-bordered table-header-dark nowrap">
                <thead>
                    <tr>
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
                            <td class="dt-actions">
                                <a href="{{ route('admin.subscriptions.show', $item) }}" class="btn btn-dark btn-mini sloc bs-tt mr-2" title="View Details" data-placement="left">
                                    <i class="ph ph-tag mr-0"></i>
                                </a>
                                @if ($item->status == \App\Enums\SubscriptionStatus::Paid->value)
                                    <span class="badge badge-warning">
                                        Pending approval
                                    </span>
                                @elseif ($item->status != \App\Enums\SubscriptionStatus::Approved->value)
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.subscriptions.pay', ['id' => $item->id]) }}" class="btn btn-primary btn-mini sloc bs-tt mr-2" title="Make Payment" data-placement="left">
                                            <i class="ph ph-plus mr-0"></i> Pay
                                        </a>
                                    </div>
                                @endif
                                
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