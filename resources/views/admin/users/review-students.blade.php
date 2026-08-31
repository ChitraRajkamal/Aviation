@extends('layouts.admin.skeleton')
@section('title', __('Review & Import Students'))

@php
    $all_errors = [];
    if($errors->any()){
        $all_errors = $errors->getBag('default')->getMessages();
    }
@endphp

@section('page-header')
    @include('layouts.admin.page-header', [
        'icon' => 'ph ph-check text-muted',
        'title' => __("Review & Import Students"),
        'breadcrumbs' => [
            route('admin.users.student') => __('Students'),
            '#' => __('Review Students'),
        ],
        'links' => [
            generate_link_element([
                'action' => 'back',
                'route' => 'admin.users.student',
            ]),
        ]
    ])
@endsection

@section('content')
<a id="hidden-import-form-confirm-action" data-element_id="hidden-import-form-confirm-action" class="confirm-action" data-title="Confirmation" data-text="Are you sure you want to submit?" ></a>
<form id="saveForm" class="manualSubmission" action="{{ route('admin.users.student.save_bulk', ['grouping' => $grouping]) }}" method="POST" enctype="multipart/form-data" data-parsley-validate>
    @csrf
    <input type="hidden" name="save_and_new" id="hf-save-and-new">
    <div class="card">
        <div class="card-block mt-1">
            <div class="alert bg-light p-3 mb-2 pb-4 text-center shadow">
                <h3><i class="fa fa-info-circle mr-1"></i> Want to upload a new document?</h3>
                <a href="javascript:;" data-url="{{ route('admin.users.student.import') }}" type="button" class="btn btn-danger bs-tt confirm-action" title="Upload New Document" 
                    data-action="link" data-title="Confirmation" data-text="Are you sure you want to cancel the current import?" data-placement="left">
                    <i class="ph ph-upload mr-0"></i> Cancel & Upload New Document
                </a>
            </div>
            <div class="dt-responsive table-responsive dt-fixed-controls">
                <table id="listing-data-table" class="table compact table-striped table-hover table-bordered table-header-dark">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email ID</th>
                            <th>Gender</th>
                            <th>DOB</th>
                            <th>Mobile</th>
                            <th>Phone</th>
                            <th>Errors</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($students as $k => $item) 
                            <tr>
                                <td>{{$k + 1}}</td>
                                <td>{{$item->first_name}}</td>
                                <td>{{$item->last_name}}</td>
                                <td><b>{{$item->email}}</b></td>
                                <td>{{$item->gender}}</td>
                                <td>{{lms_format_date($item->dob, 'd-M-Y')}}</td>
                                <td>{{$item->mobile}}</td>
                                <td>{{$item->phone}}</td>
                                <td>
                                    @if (isset($all_errors["error_$k"]))
                                        @foreach ($all_errors["error_$k"] as $error) 
                                            <div class="text-danger">{{ $error }}</div> 
                                        @endforeach
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="row fixed-submit-container">
                <div class="col-md-4">
                    <div class="form-group">
                        <button type="submit" class="btn-submit btn btn-primary btn-sm m-b-0 flex-1"><i class="fa fa-save"></i> Import Students</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<a id="hidden-import-form-confirm-action" data-element_id="hidden-import-form-confirm-action" class="confirm-action" data-title="Confirmation" data-text="Are you sure you want to submit?" ></a>
<script>
    $(document).ready(function() {
        $('#saveForm').on('lms.form.validated', function() {
            $('#hidden-import-form-confirm-action').trigger('click');
        });

        $('#hidden-import-form-confirm-action').on('lms.action.confirmed', function() {
            show_loader();
            $('#saveForm')[0].submit();
        });
    });
</script>
@endsection