@extends('layouts.admin.skeleton')
@section('title', __('Import Students'))

@section('page-header')
    @include('layouts.admin.page-header', [
        'icon' => 'ph ph-upload text-muted',
        'title' => __("Import Students"),
        'breadcrumbs' => [
            route('admin.users.student') => __('Students'),
            '#' => __('Import Students'),
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
<form id="saveForm" class="autoSubmit" action="{{ route('admin.users.student.upload') }}" method="POST" enctype="multipart/form-data" data-parsley-validate>
    @csrf
    <input type="hidden" name="save_and_new" id="hf-save-and-new">
    <div class="card">
        <div class="card-block mt-1">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group bg-light p-3 border">
                        {{lms_form_label('Upload File')}} <span class="file-resolution text-muted">[xlsx]</span>
                        <input type="file" class="form-control-file" id="file" name="file" accept=".xlsx" required data-parsley-required-message="Please choose question file">
                        @error('file')
                            <div class="parsley-errors-list">{{ $message }}</div>
                        @enderror
                        <div class="alert alert-danger mb-0 p-2 mt-2"><b>Note:</b> Please <b>DO NOT CHANGE</b> the excel headers</div>
                    </div>
                    <table class="table table-bordered">
                        <tr>
                            <td><b>Excel Format</b></td>
                            <td><a href="{{lms_storage('sample/sample-student-format.xlsx')}}" class="ml-2 text-underline"><i class="fa fa-download"></i> Download Sample</a></td>
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
@endsection