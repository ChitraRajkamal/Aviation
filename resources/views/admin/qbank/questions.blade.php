@extends('layouts.admin.skeleton')
@section('title', __('Questions'))

@section('page-header')
    @include('layouts.admin.page-header', [
        'icon' => 'ph ph-note-book text-muted',
        'title' => $qbank->title .  ' | ' . __('Questions'),
        'breadcrumbs' => [
            route('admin.qbank.index') => __('Question Banks'),
            route('admin.qbank.edit', ['qbank' => $qbank]) => $qbank->title,
            '#' => __('Questions'),
        ],
        'links' => [
            lms_can_access('admin.qbank.questions.create') ? generate_link_element([
                'route' => 'admin.qbank.questions.create',
                'route_data' => [
                    'qbankId' => $qbank->id
                ],
            ]) : false,
            lms_can_access('admin.qbank.import-questions') ? generate_link_element([
                'label' => '<i class="ph ph-upload mb-0 mr-0"></i> ' . __('Import'),
                'action' => __('Import'),
                'title' => __('Import Bulk Questions'),
                'class' => 'btn py-1 px-2 btn-primary bs-tt',
                'route' => 'admin.qbank.import-questions',
                'route_data' => [
                    'qbankId' => $qbank->id
                ],
            ]) : false,
        ]
    ])
@endsection

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-block">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h4 class="text-c-yellow f-w-600">{{$counts['all_questions']}}</h4>
                        <h6 class="text-muted m-b-0">Questions</h6>
                    </div>
                    <div class="col-4 text-right">
                        <i class="feather icon-menu f-28"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-c-yellow">
                <div class="row align-items-center">
                    <div class="col-9">
                        <p class="text-white m-b-0">Total questions added</p>
                    </div>
                    <div class="col-3 text-right">
                        <i class="feather icon-trending-up text-white f-16"></i>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-block">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h4 class="text-c-blue f-w-600">{{$counts['active_questions']}}</h4>
                        <h6 class="text-muted m-b-0">Active</h6>
                    </div>
                    <div class="col-4 text-right">
                        <i class="feather icon-check-circle f-28"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-c-blue">
                <div class="row align-items-center">
                    <div class="col-9">
                        <p class="text-white m-b-0">Total marks added</p>
                    </div>
                    <div class="col-3 text-right">
                        <i class="feather icon-trending-up text-white f-16"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-block">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h4 class="text-c-pink f-w-600">{{$counts['inactive_questions']}}</h4>
                        <h6 class="text-muted m-b-0">Inactive</h6>
                    </div>
                    <div class="col-4 text-right">
                        <i class="feather icon-alert-triangle f-28"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-c-pink">
                <div class="row align-items-center">
                    <div class="col-9">
                        <p class="text-white m-b-0">Total marks added</p>
                    </div>
                    <div class="col-3 text-right">
                        <i class="feather icon-trending-up text-white f-16"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<form action="" class="d-none">
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
                        <th>Type</th>
                        <th>Status</th>
                        <th>Created on</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($questions as $item) 
                        @php
                            $question_title = str()->limit(strip_tags($item->title), 30, '...');
                            if(empty(($question_title))){
                                $question_title = $item->title;
                            }
                        @endphp
                        <tr>
                            <td>{!!$question_title!!}</td>
                            <td>{{lms_exam_question_type($item->type)}}</td>
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
                                    @if (lms_can_access('admin.qbank.questions.edit'))
                                        <a href="{{ route('admin.qbank.questions.edit', ['qbankId' => $item->qbank_id, 'id' => $item->id]) }}" class="btn btn-primary btn-mini sloc bs-tt mr-1" title="Edit Item" data-placement="left">
                                            <i class="ph ph-pencil-simple mr-0"></i>
                                        </a>
                                    @endif
                                    @if (lms_can_access('admin.qbank.questions.activate'))
                                        <form action="{{ route('admin.qbank.questions.activate', ['qbankId' => $item->qbank_id, 'questionId' => $item->id, 'status' => $item->status ? 0 : 1]) }}" id="activate-form-{{$item->id}}" 
                                            class="d-inline-block mb-0 confirm-action mr-1" data-action="submit" 
                                            data-element_id="activate-form-{{$item->id}}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <button type="button" class="btn btn-{{$item->status ? 'warning' : 'success'}} btn-mini bs-tt" title="{{$item->status ? 'Deactivate' : 'Activate'}} Item" data-placement="left">
                                                <i class="ph ph-{{$item->status ? 'stop-circle' : 'check-circle'}} mr-0"></i>
                                            </button>
                                        </form>
                                    @endif
                                    @if (lms_can_access('admin.qbank.questions.delete'))
                                        <form action="{{ route('admin.qbank.questions.delete', ['qbankId' => $item->qbank_id, 'questionId' => $item->id]) }}" id="delete-form-{{$item->id}}" 
                                            class="d-inline-block mb-0 confirm-action" data-action="submit" 
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
            <div>Total Questions: <b>{{ $questions->total() }}</b></div>
            <div>{{$questions->links()}}</div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('.menu_qbank').addClass('active');
    });
</script>
@endsection