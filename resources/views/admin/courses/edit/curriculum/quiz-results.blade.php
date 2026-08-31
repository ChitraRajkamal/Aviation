@extends('layouts.admin.skeleton')
@section('title', __('Quiz Results'))

@section('page-header')
    @include('layouts.admin.page-header', [
        'icon' => 'ph ph-book-open text-muted',
        'title' => __('Quiz Results') . " - $lesson->title",
        'breadcrumbs' => [
            route('admin.courses.index') => __('Courses'),
            '#' => __('Quiz Results')
        ],
        'links' => [
            lms_can_access('admin.courses.create') ? generate_link_element([
                'action' => 'back',
                'route' => 'admin.courses.custom-edit',
                'route_data' => [
                    'course' => $course->id,
                    'action' => 'curriculum'
                ]
            ]) : false,
        ]
    ])
@endsection

@section('content')
<div class="card">
    <div class="card-block">
        <div class="dt-responsive table-responsive dt-fixed-controls">
            <table id="listing-data-table" class="g-datatable table compact table-striped table-hover table-bordered table-header-dark">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Attempts</th>
                        <th>Last Attempt Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($quizResultList as $item) 
                        <tr>
                            <td>
                                <a href="">{{$item->user->full_name}}</a>
                            </td>
                            <td>{{$item->total_attempts}}</td>
                            <td>{{lms_format_date($item->last_attempt_at)}}</td>
                            <td>
                                <a href="{{route('admin.courses.quiz-result-details', ['courseId' => $course->id, 'lessonId' => $lesson->id, 'userId' => $item->user_id])}}"><i class="ph ph-tag"></i> View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{$quizResultList->links()}}
    </div>
</div>
<script>
    $('.menu_courses').addClass('active');
</script>
@endsection