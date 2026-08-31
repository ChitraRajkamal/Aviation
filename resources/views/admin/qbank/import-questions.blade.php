@extends('layouts.admin.skeleton')
@section('title', __('Import Questions'))

@section('page-header')
    @include('layouts.admin.page-header', [
        'icon' => 'ph ph-upload text-muted',
        'title' => $qbank->title . ' | ' . __("Import Questions"),
        'breadcrumbs' => [
            route('admin.qbank.index') => __('Question Banks'),
            route('admin.qbank.edit', ['qbank' => $qbank->id]) => __($qbank->title),
            '#' => __('Import Questions'),
        ],
        'links' => [
            generate_link_element([
                'action' => 'back',
                'route' => 'admin.qbank.questions',
                'route_data' => ['qbankId' => $qbank->id]
            ]),
        ]
    ])
@endsection

@section('content')
<form id="saveForm" class="autoSubmit" action="{{ route('admin.qbank.upload_questions', ['qbankId' => $qbank->id]) }}" method="POST" enctype="multipart/form-data" data-parsley-validate>
    @csrf
    <input type="hidden" name="save_and_new" id="hf-save-and-new">
    <div class="card">
        <div class="card-block mt-1">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group bg-light p-3 border">
                        {{lms_form_label('Upload File')}} <span class="file-resolution text-muted">[docx, xlsx]</span>
                        <input type="file" class="form-control-file" id="file" name="file" accept=".docx,.xlsx" required data-parsley-required-message="Please choose question file">
                        @error('file')
                            <div class="parsley-errors-list">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <table class="table table-bordered">
                        <tr>
                            <td><b>Excel Format</b></td>
                            <td><a href="{{lms_storage('sample/sample-question-format.xlsx')}}" class="ml-2 text-underline"><i class="fa fa-download"></i> Download Sample</a></td>
                        </tr>
                        <tr>
                            <td><b>Word Format</b></td>
                            <td><a href="{{lms_storage('sample/sample-question-format.docx')}}" class="ml-2 text-underline"><i class="fa fa-download"></i> Download Sample</a></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <button type="submit" class="btn-submit btn btn-primary btn-sm m-b-0 flex-1"><i class="fa fa-save"></i> Submit</button>                
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@if(session('custom_alert'))
    <div class="text-danger">
        <strong>Warning: EMF image(s) detected and not supported. Unsupported (EMF) images found near</strong>
        <ul>
            @foreach(session('custom_alert') as $k => $error)
                <li class="mt-2 p-3 shadow rounded border">
                    <div class="fw-bold mb-2">EMF Image {{$k + 1}}:</div>
                    {{ $error['context'] }}
                </li>
            @endforeach
        </ul>
    </div>
@endif
<script>
    $(document).ready(function() {
        $('.menu_qbank').addClass('active');
    });
</script>
<style>    
    .custom_import_error{
        background: #fff;
        color: darkred;
        margin-top: 10px;
        padding: 6px 6px 0px 6px;
        max-width: 800px;
        white-space: pre-wrap;
    }
</style>
@endsection