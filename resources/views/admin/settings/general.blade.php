@extends('layouts.admin.skeleton')
@section('title', __('Settings'))

@section('page-header')
    @include('layouts.admin.page-header', [
        'icon' => 'ph ph-note-book text-muted',
        'title' => __('Settings'),
        'breadcrumbs' => [
            route('admin.settings') => __('Settings')
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
                        <th>Key</th>
                        <th>Value</th>
                        <th>Description</th>
                        <th>Last Updated</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($settingList as $item) 
                        <tr>
                            <td title="{{$item->type}}">{{$item->key}} {!!$item->required ? '<span class="text-danger">*</span>' : ''!!}</td>
                            <td>
                                @if ($item->type == 'file:image')
                                    @if ($item->value)
                                        <a href="{{runnwic_storage($item->value)}}" target="_blank" class="btn-link">View <i class="fa fa-external-link"></i></a>
                                    @endif
                                @else
                                    {{$item->value}}
                                @endif
                            </td>
                            <td>{{str($item->description)->words(10)}}</td>
                            <td>{{lms_format_date($item->updated_at)}}</td>
                            <td class="dt-actions">
                                <div class="btn-group" role="group">
                                    @if (lms_can_access('admin.settings.edit'))
                                        <a href="{{ route('admin.settings.edit', ['id' => $item->id]) }}" class="btn btn-primary btn-mini sloc bs-tt mr-1" title="Edit Setting" data-placement="left">
                                            <i class="ph ph-pencil-simple mr-0"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{$settingList->links()}}
    </div>
</div>
@endsection