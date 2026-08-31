@extends('layouts.admin.skeleton')
@section('title', __('Review & Import Questions'))

@section('page-header')
    @include('layouts.admin.page-header', [
        'icon' => 'ph ph-check text-muted',
        'title' => $qbank->title . ' | ' . __("Review & Import Questions"),
        'breadcrumbs' => [
            route('admin.qbank.index') => __('Question Banks'),
            route('admin.qbank.edit', ['qbank' => $qbank->id]) => __($qbank->title),
            '#' => __('Review Questions'),
        ],
        'links' => [
            generate_link_element([
                'action' => 'back',
                'route' => 'admin.qbank.index',
            ]),
        ]
    ])
@endsection

@section('content')
<a id="hidden-import-form-confirm-action" data-element_id="hidden-import-form-confirm-action" class="confirm-action" data-title="Confirmation" data-text="Are you sure you want to submit?" ></a>
<button type="button" class="btn btn-light bs-tt d-none" title="Upload New Document" data-placement="left">
    <i class="ph ph-upload mr-0"></i> Upload New Document
</button>
<form action="{{ route('admin.qbank.disable_bulk_questions', ['qbankId' => $qbank->id]) }}" id="upload-new-document-form" 
    class="d-inline-block mb-0 confirm-action" data-action="submit" data-title="Upload New Document?" data-no_text="Cancel"
    data-element_id="upload-new-document-form" method="POST">
    @csrf
    <button type="button" class="btn btn-light bs-tt d-none" title="Upload New Document" data-placement="left">
        <i class="ph ph-upload mr-0"></i> Upload New Document
    </button>
</form>
<form id="saveForm" class="manualSubmission" action="{{ route('admin.qbank.save_bulk_questions', ['qbankId' => $qbank->id]) }}" method="POST" enctype="multipart/form-data" data-parsley-validate>
    @csrf
    <input type="hidden" name="save_and_new" id="hf-save-and-new">
    <div class="card">
        <div class="card-block mt-1">
            <div class="alert bg-light p-3 mb-2 pb-4 text-center shadow">
                <h3><i class="fa fa-info-circle mr-1"></i> Want to upload a new document?</h3>
                <button id="btn-upload-new-document" type="button" class="btn btn-danger bs-tt" title="Upload New Document" data-placement="left">
                    <i class="ph ph-upload mr-0"></i> Cancel & Upload New Document
                </button>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        {{ lms_form_label('Already added questions', false) }}
                        <div><div class="badge badge-primary">{{$qbank->question_count()}}</div></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        {{ lms_form_label('Already added marks', false) }}
                        <div><div class="badge badge-primary">{{$qbank->added_marks()}}</div></div>
                    </div>
                </div>
            </div>
            <h2>{{count($questions)}} questions to be added now</h2>
            
            <div class="dt-responsive table-responsive dt-fixed-controls">
                <table id="listing-data-table" class="table table-question compact table-striped table-hover table-bordered table-header-dark">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Question</th>
                            <th>Options</th>
                            <th>Answer</th>
                            <th>Explanation</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($questions as $k => $item) 
                            <tr>
                                <td style="width: 3%;">{{++$k}}</td>
                                <td>
                                    <div class="custom-td-content">
                                        {!!$item->question!!}
                                        <br>
                                        <a id="delete-confirm-action-{{$item->id}}" class="confirm-action btn btn-danger btn-mini bs-tt mt-2" href="javascript:;"
                                            title="Delete Item" data-placement="left" data-title="Confirmation" data-text="Are you sure you want to delete?" 
                                            data-action="link" data-url="{{ route('admin.qbank.delete-import-questions', ['qbankId' => $item->qbank_id, 'id' => $item->id]) }}" >
                                            <i class="ph ph-trash-simple mr-0"></i> Delete
                                        </a>
                                    </div>
                                </td>
                                <td>
                                    <div class="custom-td-content">
                                        <b>a.</b> {!!$item->option_a!!}<hr class="my-1">
                                        <b>b.</b> {!!$item->option_b!!}

                                        @if ($item->option_c !== null && $item->option_c !== '')
                                            <hr class="my-1">
                                            <b>c.</b> {!!$item->option_c!!}
                                        @endif
                                        @if ($item->option_d !== null && $item->option_d !== '')
                                            <hr class="my-1">
                                            <b>d.</b> {!!$item->option_d!!}
                                        @endif
                                        @if ($item->option_e !== null && $item->option_e !== '')
                                            <hr class="my-1">
                                            <b>e.</b> {!!$item->option_e!!}
                                        @endif
                                    </div>
                                </td>
                                <td class="font-weight-bold">
                                    <div style="width: 300px; white-space: wrap;">
                                        <i class="fa fa-check-circle text-success"></i> {!!$item->correct_answer!!}
                                    </div>
                                </td>
                                <td>
                                    <div style="width: 300px; white-space: wrap;">
                                        {!!$item->explanation!!}
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="row fixed-submit-container">
                <div class="col-md-4">
                    <div class="form-group">
                        <button type="submit" class="btn-submit btn btn-primary btn-sm m-b-0 flex-1"><i class="fa fa-save"></i> Import Questions</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<script>
    $(document).ready(function() {
        var old_total_mark = {{$qbank->added_marks()}};
        var total_questions = {{count($questions)}};
        $('#marks_per_question').on('input keyup', function() {
            var marks = parseInt(this.value) || 0;
            $('#total_mark').val((marks * total_questions) + old_total_mark).addClass('marks_updated');
            setTimeout(()=> $('#total_mark').removeClass('marks_updated'), 1000);
        });

        $('#btn-upload-new-document').on('click', function() {
            $('#upload-new-document-form button').trigger('click');
        });

        $('#saveForm').on('lms.form.validated', function() {
            $('#hidden-import-form-confirm-action').trigger('click');
        });

        $('#hidden-import-form-confirm-action').on('lms.action.confirmed', function() {
            show_loader();
            $('#saveForm')[0].submit();
        });

        $('.menu_qbank').addClass('active');

        $('.table-question img').click(function() {
            window.open(this.src);
        });
        $('.table-question img').tooltip({
            title: 'Click to view full image',
            placement: 'top',
        });
    });
</script>
<style>
    #total_mark {
        transition: all 0.5s;
    }
    #total_mark.marks_updated {
        transform: scale(1.2);
        background: #ccc;
        z-index: 1;
        border: 3px solid darkgreen;
        color: #fff;
        background: darkgreen;
    }
    .custom-td-content{
        width: 300px; white-space: wrap; overflow: hidden;
    }
</style>
@endsection