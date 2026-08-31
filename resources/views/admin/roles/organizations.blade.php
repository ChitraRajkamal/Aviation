@extends('layouts.admin.skeleton')
@section('title', __('Roles'))

@section('page-header')
    @include('layouts.admin.page-header', [
        'icon' => 'ph ph-key text-muted',
        'title' => __(key: 'Roles'),
        'breadcrumbs' => [
            route('admin.roles.organization') => __('Roles')
        ],
        'links' => [
            lms_can_access('admin.roles.organization.create') ? generate_link_element([
                'route' => 'admin.roles.organization.create'
            ]) : false,
        ]
    ])
@endsection

@section('content')
<div class="card">
    <div class="card-block">
        <div class="dt-responsive table-responsive dt-fixed-controls">
            <table id="listing-data-table" class="g-datatable table compact table-striped table-hover table-bordered table-header-dark nowrap">
                <thead>
                    <tr>
                        <th>Role</th>
                        <th>Staffs</th>
                        <th>Created on</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rolesList as $item) 
                        <tr class="tr_{{$item->id}}">
                            <td>{{$item->name}}</td>
                            <td>{{$item->staff_count()}}</td>
                            <td>{{lms_format_date($item->created_at)}}</td>
                            <td class="dt-actions">
                                <div class="btn-group" role="group">
                                    @if (lms_can_access('admin.roles.organization.edit'))
                                        <a href="{{ route('admin.roles.organization.edit', ['id' => $item->id]) }}" class="btn btn-primary btn-mini sloc bs-tt" title="Edit Role" data-placement="left">
                                            <i class="ph ph-pencil-simple mr-0"></i>
                                        </a>
                                    @endif
                                    @if (lms_can_access('admin.roles.organization.delete'))
                                        <form action="{{ route('admin.roles.organization.delete', ['id' => $item->id]) }}" id="delete-form-{{$item->id}}" 
                                            class="d-inline-block mb-0 confirm-action ml-1" data-action="submit" 
                                            data-element_id="delete-form-{{$item->id}}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger btn-mini bs-tt" title="Delete User" data-placement="left">
                                                <i class="ph ph-trash mr-0"></i>
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
        {{$rolesList->links()}}
    </div>
</div>
@endsection