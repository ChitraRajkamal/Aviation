@extends('layouts.admin.skeleton')
@section('title', __('Quiz Questions'))

@section('content')
    <div class="text-center">
        @if (lms_can_access('admin.courses.questions.create'))
            <a href="{{route('admin.courses.questions.create', ['courseId' => $lesson->course_id, 'lessonId' => $lesson->id])}}" class="btn btn-primary">
                <i class="fa fa-plus"></i> Add New Question
            </a>
        @endif
    </div>
    <div class="card">
        <div class="card-block">
            
            <div class="dt-responsive table-responsive dt-fixed-controls">
                <table id="listing-data-table" class="g-datatable table compact table-striped table-hover table-bordered table-header-dark nowrap">
                    <thead>
                        <tr>
                            <th>Question</th>
                            <th>Type</th>
                            <th>Created on</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($questions as $item) 
                        @php
                        $question_delete_url = route('admin.courses.questions.delete', ['courseId' => $item->course_id, 'sectionId' => $item->section_id, 'lessonId' => $item->lesson_id, 'id' => $item->id]);
                        @endphp
                            <tr class="tr_{{$item->id}}">
                                <td>{!! $item->title !!}</td>
                                <td>{{lms_question_type()[$item->type]}}</td>
                                <td>{{lms_format_date($item->created_at)}}</td>
                                <td class="dt-actions">
                                    <div class="btn-group" role="group">
                                        <a class="confirm-action btn btn-danger btn-mini bs-tt" title="Delete Question" href="javascript:;" 
                                            data-action="link" data-url="{{ $question_delete_url }}">
                                            <i class="ph ph-trash-simple mr-0"></i>
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
            
        });
    </script>
@endsection
