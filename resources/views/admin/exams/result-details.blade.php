@extends('layouts.admin.skeleton')
@section('title', __('Attempts'))

@section('page-header')
    @include('layouts.admin.page-header', [
        'icon' => 'ph ph-note-book text-muted',
        'title' => $exam->title .  ' | ' . $enroll->user->full_name . ' | ' . __('Attempts'),
        'breadcrumbs' => [
            route('admin.exams.index') => __('Exams'),
            route('admin.exams.edit', ['exam' => $exam]) => $exam->title,
            '#' => __('Attempts'),
        ],
        'links' => [
            generate_link_element([
                'action' => 'back',
                'route' => 'admin.exams.results',
                'route_data' => ['examId' => $exam->id]
            ]),
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
                        <th>Date/Time</th>
                        <th>Total Questions</th>
                        <th>Total Marks</th>
                        <th>Pass Marks</th>
                        <th>Obtained Marks</th>
                        <th>Result</th>
                        <th>Time Taken</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($results as $item) 
                        <tr class="tr_{{$item->id}}">
                            <td>{{lms_format_date($item->created_at)}}</td>
                            <td>{{$item->total_questions}}</td>
                            <td>{{$item->total_mark}}</td>
                            <td>{{$item->pass_mark}}</td>
                            <td class="fw-bold">{{$item->obtained_mark}}</td>
                            <td>
                                @if ($item->is_pass)
                                    <span class="badge bg-success">PASS</span>
                                @else
                                    <span class="badge bg-danger">FAIL</span>
                                @endif
                            </td>
                            <td>{{$item->time_taken}}</td>
                            <td class="dt-actions">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.exams.results.answers', ['examId' => $item->exam_id, 'userId' => $item->user_id, 'resultId' => $item->id]) }}" class="btn btn-primary btn-mini sloc bs-tt" title="Edit Item" data-placement="left">
                                        <i class="ph ph-check mr-0"></i> Review Answers
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