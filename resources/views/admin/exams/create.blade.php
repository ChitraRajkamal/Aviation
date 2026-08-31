@extends('layouts.admin.skeleton')
@section('title', __('Add Exam'))

@php
    $max_marks = 100;
@endphp

@section('extra-styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/bower_components/summernote/summernote-bs4.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/bower_components/bootstrap-tagsinput/css/bootstrap-tagsinput.css') }}">
@endsection

@section('extra-scripts')
    <script type="text/javascript" src="{{ asset('admin-assets/bower_components/summernote/summernote-bs4.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('admin-assets/bower_components/bootstrap-tagsinput/js/bootstrap-tagsinput.js') }}"></script>
    <script type="text/javascript" src="{{ asset('admin-assets/assets/pages/j-pro/js/autoNumeric.js') }}"></script>
@endsection

@section('page-header')
    @include('layouts.admin.page-header', [
        'icon' => 'ph ph-plus-square text-muted',
        'title' => __('Add Exam'),
        'breadcrumbs' => [
            route('admin.exams.index') => __('Exams'),
            '#' => __('New'),
        ],
        'links' => [
            generate_link_element([
                'action' => 'back',
                'route' => 'admin.exams.index',
            ]),
        ]
    ])
@endsection

@section('content')
<form id="saveForm" class="autoSubmit" action="{{ route('admin.exams.store') }}" method="POST" enctype="multipart/form-data" data-parsley-validate>
    @csrf
    <input type="hidden" name="save_and_new" id="hf-save-and-new">
    <div class="card">
        <div class="card-block mt-1">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        {{lms_form_label('Title')}}
                        <input type="text" class="form-control" name="title" value="{{ old('title')}}" placeholder="{{__('Enter Exam Title')}}" @lmsparsley(exams_add,title)>
                        @error('title')
                            <div class="parsley-errors-list">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {{lms_form_label('Category')}}
                        <select class="form-control select-two" id="exam_category_id" data-placeholder="Choose Category" name="exam_category_id" @lmsparsley(exams_add,exam_category_id)>
                            <option value="">Choose Category</option>
                            @foreach ($categoriesHierarchy as $item)
                              <option value="{{$item->id}}" {{ old('exam_category_id') == $item->id ? 'selected' : '' }}>{{$item->title}}</option>
                            @endforeach
                        </select>
                        @error('exam_category_id')
                            <div class="parsley-errors-list">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {{ lms_form_label('Duration') }}
                        <input type="text" class="form-control" name="duration" 
                        value="{{ old('duration')}}" placeholder="{{__('01:30:00 ==> One Hour & 30 Minutes')}}" @lmsparsley(exams_add,duration)>
                        @error('duration')
                            <div class="parsley-errors-list">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="bg-light border border-dark my-2 pt-3">                
                <div class="row">
                    <div class="col-md-6 offset-md-3">
                        <div class="form-group">
                            <h4 class="text-center">{{__('Choose Question Bank')}}</h4>
                            <select class="form-control select-two" id="qbank_id" data-placeholder="Choose Question Bank">
                                <option value="">Choose an Option</option>
                                @foreach ($qbanks as $item)
                                    <option value="{{$item->id}}" data-title="{{$item->title}}" data-questions="{{$item->question_count()}}">
                                        {{$item->title . ' - (' . $item->question_count() . ' questions)'}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table id="selected-questions" class="table mb-0">
                            <thead class="bg-dark">
                                <tr>
                                    <th>Name</th>
                                    <th width="10%">Questions</th>
                                    <th>Take Randomly</th>
                                    <th>Marks</th>
                                    <th width="10%">Remove</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (old('qbank_ids'))
                                    @foreach (old('qbank_ids') as $q)
                                        @php
                                            $qb = lms_get_qbank($q['id']);
                                            $question_count = $qb->question_count();
                                        @endphp
                                        <tr id="tr_qb_{{$qb->id}}">
                                            <td>{{$qb->title}}</td>
                                            <td><span class="badge badge-dark">{{$question_count}}</span></td>
                                            <td>
                                                <input type="hidden" name="qbank_ids[{{$qb->id}}][id]" value="{{$qb->id}}" />
                                                <input type="number" min="1" max="{{$question_count}}" class="form-control p-1 qb_questions" placeholder="Enter how many question to take?" value="{{$q['questions']}}" 
                                                    name="qbank_ids[{{$qb->id}}][questions]" required data-parsley-type="number" data-parsley-min="1" data-parsley-max="{{$question_count}}"
                                                    data-parsley-min-message="Minimum is 1" data-parsley-max-message="Maximum is {{$question_count}}" data-parsley-range-message="" />
                                                @error("qb_$q[id]")
                                                    <div class="parsley-errors-list">{!! $message !!}</div>
                                                @enderror
                                            </td>
                                            <td>
                                                <input type="number" min="1" max="{{$max_marks}}" class="form-control p-1 qb_marks" placeholder="Marks per question" value="{{$q['marks']}}" 
                                                    name="qbank_ids[{{$qb->id}}][marks]" required data-parsley-type="number" data-parsley-min="1" data-parsley-max="{{$max_marks}}"
                                                    data-parsley-min-message="Minimum is 1" data-parsley-max-message="Maximum is {{$max_marks}}" data-parsley-range-message="" />
                                            </td>
                                            <td><span class="remove_qb cursor-pointer bg-white shadow p-1 rounded" data-id="{{$qb->id}}"><i class="fa fa-trash text-danger"></i> Remove</span></td>
                                        </tr>
                                    @endforeach
                                @endif
                                <tr id="empty_qb_details">
                                    <td colspan="5" class="bg-light text-center py-5"><i class="fa fa-exclamation-triangle fa-3x text-danger mb-2"></i><br>No question bank added</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        {{ lms_form_label('Total Marks') }}
                        <input type="number" min="0" class="form-control" id="total_mark" name="total_mark" value="0" readonly
                        value="{{ old('total_mark')}}" placeholder="{{__('Total available marks')}}" @lmsparsley(exams_add,total_mark)>
                        @error('total_mark')
                            <div class="parsley-errors-list">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {{ lms_form_label('Pass Marks') }}
                        <input type="number" min="0" class="form-control" id="pass_mark" name="pass_mark" data-parsley-range-message="This must be less than or equal to Total Marks"
                        value="{{ old('pass_mark')}}" placeholder="{{__('Totals marks needed to pass')}}" @lmsparsley(exams_add,pass_mark)>
                        @error('pass_mark')
                            <div class="parsley-errors-list">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {{ lms_form_label('Negative Marks') }}
                        <input type="number" min="0" max="100" class="form-control" name="negative_mark" value="{{ old('negative_mark')}}" 
                            placeholder="{{__('Deduct this marks if wrong answer')}}" @lmsparsley(exams_add,negative_mark)>
                        @error('negative_mark')
                            <div class="parsley-errors-list">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        {{ lms_form_label('Reattempts') }} <span class="text-muted">[0 - Unlimited]</span>
                        <input type="number" min="0" max="100" class="form-control" name="retake" value="{{ old('retake')}}" 
                            placeholder="{{__('How many reattempts are allowed. Set 0 if unlimited')}}" @lmsparsley(exams_add,retake)>
                        @error('retake')
                            <div class="parsley-errors-list">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {{lms_form_label('Price', false)}} <span class="text-muted">[Leave 0.00 if free]</span>
                        <input type="text" class="form-control decimal_input" name="price" value="{{ old('price')}}" placeholder="{{__('Enter Price')}}" @lmsparsley(exams_add,price)>
                        @error('price')
                            <div class="parsley-errors-list">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {{lms_form_label('Thumbnail', false)}} <span class="file-resolution text-muted">[400 x 280]</span>
                        <input type="file" class="form-control-file" id="thumbnail" name="thumbnail" accept=".jpg,.jpeg,.png,.fig">
                        @error('thumbnail')
                            <div class="parsley-errors-list">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        {{lms_form_label('Description', false)}}
                        <textarea id="description" name="description" class="form-control h-180" rows="4"placeholder="{{__('Enter Description')}}" @lmsparsley(exams_add,description)>{{ old('description')}}</textarea>
                        @error('description')
                            <div class="parsley-errors-list">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        {{lms_form_label('Meta Title', false)}}
                        <input type="text" class="form-control" name="meta_data[title]" value="{{ old('meta_data.title') }}" placeholder="{{__('Enter Meta Title')}}" >
                        @error('meta_data.title')
                            <div class="parsley-errors-list">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        {{lms_form_label('Meta Robot', false)}}
                        <input type="text" class="form-control" name="meta_data[robot]" value="{{ old('meta_data.robot') }}" placeholder="{{__('Enter Meta Robot')}}" >
                        @error('meta_data.robot')
                            <div class="parsley-errors-list">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        {{lms_form_label('Meta Description', false)}}
                        <textarea name="meta_data[description]" class="form-control" rows="4" placeholder="{{__('Enter Meta Description')}}">{{ old('meta_data.description') }}</textarea>
                        @error('meta_data.description')
                            <div class="parsley-errors-list">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {{lms_form_label('Og Title', false)}}
                        <input type="text" class="form-control" name="meta_data[og_title]" value="{{ old('meta_data.og_title') }}" placeholder="{{__('Enter Og Title')}}" >
                        @error('meta_data.og_title')
                            <div class="parsley-errors-list">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        {{lms_form_label('Og Description', false)}}
                        <input type="text" class="form-control" name="meta_data[og_description]" value="{{ old('meta_data.og_description') }}" placeholder="{{__('Enter Og Description')}}" >
                        @error('meta_data.og_description')
                            <div class="parsley-errors-list">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        {{lms_form_label('Og Image', false)}}
                        <input type="file" class="form-control-file" id="meta_data[og_image]" name="meta_data[og_image]" accept=".jpg,.jpeg,.png,.fig">
                        @error('meta_data.og_image')
                            <div class="parsley-errors-list">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row fixed-submit-container">
                <div class="col-md-4">
                    <div class="form-group d-flex justify-content-between gap-3">
                        <button type="submit" class="btn-submit btn btn-primary btn-sm m-b-0 flex-1"><i class="fa fa-save"></i> Save</button>
                        <button id="save-and-new" type="submit" class="btn-submit btn btn-dark btn-sm m-b-0 flex-1"><i class="fa fa-plus"></i> Save and New</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<a id="hidden-qb-confirm-action" data-element_id="hidden-qb-confirm-action" class="confirm-action" data-title="Confirmation" data-text="Are you sure you want to submit?" ></a>
<script>
    $(document).ready(function() {
        $('#description').summernote({
            placeholder: 'Describe the exam',
            tabsize: 2,
            height: 300
        });

        function calculate_marks(){
            const total_mark = $('.qb_questions').get().reduce((sum, el) => {
                return sum + ((+el.value || 0) * (+($(el).parent().next().children().first().val()) || 0));
            }, 0);
            $('#pass_mark').prop('max', total_mark);
            $('#total_mark').val(total_mark);
        }

        var empty_qb_details = $('#empty_qb_details')[0].outerHTML;
        $('#qbank_id').change(function (){
            if(!this.value) return false;
            var dataset = $(this).find('option:selected')[0].dataset;
            var qbankId = this.value
            $('#qbank_id').val('').trigger('change');
            if($('#tr_qb_' + qbankId).length){
                return false;
            }
            if($('#empty_qb_details').length) $('#empty_qb_details').remove();
            var max_marks = {{$max_marks}};
            var code = `
            <tr id="tr_qb_${qbankId}">
                <td>${dataset.title}</td>
                <td><span class="badge badge-dark">${dataset.questions}</span></td>
                <td>
                    <input type="hidden" name="qbank_ids[${qbankId}][id]" value="${qbankId}" />
                    <input type="number" min="1" max="${dataset.questions}" class="form-control p-1 qb_questions" placeholder="Enter how many question to take?" value="" 
                        name="qbank_ids[${qbankId}][questions]" required data-parsley-type="number" data-parsley-min="1" data-parsley-max="${dataset.questions}"
                        data-parsley-min-message="Minimum is 1" data-parsley-max-message="Maximum is ${dataset.questions}" data-parsley-range-message="" />
                </td>
                <td>
                    <input type="number" min="1" max="${max_marks}" class="form-control p-1 qb_marks" placeholder="Marks per question" value="" 
                        name="qbank_ids[${qbankId}][marks]" required data-parsley-type="number" data-parsley-min="1" data-parsley-max="${max_marks}"
                        data-parsley-min-message="Minimum is 1" data-parsley-max-message="Maximum is ${max_marks}" data-parsley-range-message="" />
                </td>
                <td><span class="remove_qb cursor-pointer bg-white shadow p-1 rounded" data-id="${qbankId}"><i class="fa fa-trash text-danger"></i> Remove</span></td>
            </tr>
            `;
            $('#selected-questions tbody').append(code);
            calculate_marks();
        });

        var currentItem = null;
        $(document).on('click', '.remove_qb', function (){
            currentItem = this;
            $('#hidden-qb-confirm-action').trigger('click');
        });

        $('#hidden-qb-confirm-action').on('lms.action.confirmed', function() {
            if($('#tr_qb_' + currentItem.dataset.id).length){
                $('#tr_qb_' + currentItem.dataset.id).remove();
            }
            if($('#selected-questions tbody tr').length == 0){
                $('#selected-questions tbody').append(empty_qb_details);
            }
            calculate_marks();
        });

        $(document).on('input paste', '.qb_questions, .qb_marks', function (){
            calculate_marks();
        });

        @if (old('qbank_ids'))
            if($('#empty_qb_details').length) $('#empty_qb_details').remove();
            calculate_marks();
        @endif
    });
</script>
@endsection