@extends('layouts.admin.skeleton')
@section('title', __('Enrolments'))

@section('page-header')
    @include('layouts.admin.page-header', [
        'icon' => 'ph ph-book-open text-muted',
        'title' => $course->title . ' - ' . __('Enrolments'),
        'breadcrumbs' => [
            route('admin.courses.index') => __(key: 'Courses'),
            '#' => __('Enrolments')
        ],
        'links' => [
            lms_can_access('admin.courses.index') ? generate_link_element([
                'action' => 'back',
                'route' => 'admin.courses.index'
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
                        <th>Student</th>
                        <th>Completed</th>
                        <th>Enrolled on</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($enrolments as $item) 
                        <tr>
                            <td>{{$item->user->full_name}}</td>
                            <td>
                                @if ($item->is_completed)
                                    <i class="ph ph-check-circle text-success"></i> Yes
                                @else
                                    <i class="ph ph-stop-circle text-warning"></i> No
                                @endif
                            </td>
                            <td>{{lms_format_date($item->created_at)}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{$enrolments->links()}}
    </div>
</div>
<script>
    $('.menu_courses').addClass('active');
</script>
@endsection