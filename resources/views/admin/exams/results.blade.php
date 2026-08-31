@extends('layouts.admin.skeleton')
@section('title', __('Results'))

@section('page-header')
    @include('layouts.admin.page-header', [
        'icon' => 'ph ph-note-book text-muted',
        'title' => $exam->title .  ' | ' . __('Results'),
        'breadcrumbs' => [
            route('admin.exams.index') => __('Exams'),
            route('admin.exams.edit', ['exam' => $exam]) => $exam->title,
            '#' => __('Results'),
        ],
        'links' => [
            generate_link_element([
                'action' => 'back',
                'route' => 'admin.exams.index'
            ]),
        ]
    ])
@endsection

@section('content')
<form action="">
    <input type="hidden" name="search" value="1">
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
                                        <label class="col-form-label">Student</label>
                                        <select class="form-control select-two-ajax" id="student_id" name="f[student_id]" data-placeholder="Search Student" data-placeholders="Search Sales"
                                            data-object="students" data-ajax="{{route('ajax.search_students')}}">
                                            @foreach ($students as $item)
                                              <option value="{{$item->id}}" selected>{{$item->full_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="col-form-label d-md-block d-none">&nbsp;</label>
                                        <button type="submit" class="btn-submit btn btn-primary btn-sm m-b-0"><i class="fa fa-filter"></i> Filter</button>
                                        @if (request('search'))
                                            <a href="{{ route('admin.exams.index') }}" class="btn btn-default btn-sm m-b-0 bs-tt sloc" title="Reset Filter"><i class="fa fa-undo"></i> Clear</a>
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
                        <th>Student</th>
                        <th>Enrolled On</th>
                        <th>Status</th>
                        <th>Attempts</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($enrolls as $item) 
                        <tr class="tr_{{$item->id}}">
                            <td>{{$item->user->full_name}}</td>
                            <td>{{lms_format_date($item->created_at)}}</td>
                            <td>
                                @if ($item->is_attended)
                                    <span class="badge bg-success">Completed</span>
                                @else
                                    <span class="badge bg-dark">Active</span>
                                @endif
                            </td>
                            <td>{{$item->attempt_count()}}</td>
                            <td class="dt-actions">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.exams.results.details', ['examId' => $item->exam_id, 'userId' => $item->user_id]) }}" class="btn btn-primary btn-mini sloc bs-tt" title="Edit Item" data-placement="left">
                                        <i class="ph ph-tag mr-0"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('.menu_exams').addClass('active');
    });
</script>
@endsection