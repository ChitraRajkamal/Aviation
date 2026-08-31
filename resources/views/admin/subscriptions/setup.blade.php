@extends('layouts.admin.skeleton')
@section('title', __('Setup'))

@php
    $currency_symbol = lms_setting('currency_symbol');
    $category_types = [
        'course' => 'Course',
        'exam' => 'Exam',
        'recordedVideo' => 'Recorded Video',
        'jobPost' => 'Job Post',
    ]
@endphp

@section('page-header')
    @include('layouts.admin.page-header', [
        'icon' => 'ph ph-credit-card text-muted',
        'title' => __('Setup - ' . $organization->name),
        'breadcrumbs' => [
            route('admin.subscriptions.index') => __('Subscriptions'),
            '#' => __('Payment'),
        ],
    ])
@endsection

@section('extra-scripts')
    <script type="text/javascript" src="{{ asset('admin-assets/assets/pages/j-pro/js/autoNumeric.js') }}"></script>
@endsection

@section('content')
<form id="saveForm" class="autoSubmit" action="{{ route('admin.subscriptions.save-setup', ['organizationId' => $organization->id]) }}" method="POST" enctype="multipart/form-data" data-parsley-validate>
    @csrf
    <div class="dt-responsive auto-height bg-white shadow mb-3">
        <table id="listing-data-table" class="mb-0 table compact table-striped table-hover table-bordered table-header-dark nowrap">
            <thead>
                <tr>
                    <th>No of Students</th>
                    <th>Courses</th>
                    <th>Exams</th>
                    <th>Jobs</th>
                    <th>Rec. Videos</th>
                    <th>Status</th>
                    <th class="text-right">Price</th>
                    <th>Created on</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($subscriptions as $item) 
                    <tr class="tr_{{$item->id}}">
                        <td>{{$item->no_of_students}}</td>
                        <td>
                            @if ($item->allow_courses)
                                <i class="fa fa-check-circle text-success"></i> Needed
                            @else
                                <i class="fa fa-stop-circle text-warning"></i> Not Needed
                            @endif
                        </td>
                        <td>
                            @if ($item->allow_exams)
                                <i class="fa fa-check-circle text-success"></i> Needed
                            @else
                                <i class="fa fa-stop-circle text-warning"></i> Not Needed
                            @endif
                        </td>
                        <td>
                            @if ($item->allow_job_posts)
                                <i class="fa fa-check-circle text-success"></i> Needed
                            @else
                                <i class="fa fa-stop-circle text-warning"></i> Not Needed
                            @endif
                        </td>
                        <td>
                            @if ($item->allow_recorded_videos)
                                <i class="fa fa-check-circle text-success"></i> Needed
                            @else
                                <i class="fa fa-stop-circle text-warning"></i> Not Needed
                            @endif
                        </td>
                        <td>
                            <span class="text-uppercase badge badge-{{$item->status == \App\Enums\SubscriptionStatus::Unpaid->value ? 'danger' : ($item->status == \App\Enums\SubscriptionStatus::Paid->value ? 'primary' : 'success')}}">
                                {{$item->status}}
                            </span>
                        </td>
                        <td class="text-right">{{$currency_symbol . $item->price}}</td>
                        <td>{{lms_format_date($item->created_at)}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="card">
        <div class="card-block mt-1">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        {{lms_form_label('No of Students')}}</span>
                        <input type="number" min="0" class="form-control" name="no_of_students" value="{{ old('no_of_students', $organization->no_of_students)}}" placeholder="{{__('Enter No of Students')}}" required>
                        @error('no_of_students')
                            <div class="parsley-errors-list">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            @php
                /*
            @foreach ($category_types as $categoryIndex => $categoryName )
                <div class="row">
                    <div class="col-md-6">
                        {{lms_form_label($categoryName . ' Categories', false)}}</span>
                        <div class="list-group mb-3 category-container">                        
                            @foreach ($categories[$categoryIndex] ?? [] as $item)
                                <div class="list-group-item p-1">
                                    <label class="mb-0 cursor-pointer"><input type="checkbox" name="categories[{{$categoryIndex}}][]" value="{{$item->id}}"> {{$item->title}}</label>
                                </div>
                            @endforeach                        
                        </div>
                    </div>
                </div>
            @endforeach
            */
            @endphp

            @error('organization_permissions')
                <div class="parsley-errors-list">{{ $message }}</div>
            @enderror

            <hr>

            {{-- ==== Courses ==== --}}
            <h5 class="mb-3 fw-bold text-primary text-underline">Courses</h5>
            @foreach($courseCategories as $category)
                <div class="form-group">
                    <div class="form-check bg-light p-2 category-name">
                        <input type="checkbox"
                            class="form-check-input category-checkbox"
                            id="course_cat_{{ $category->id }}"
                            data-group="courses"
                            data-target="course_cat_{{ $category->id }}"
                            name="organization_permissions[courses][categories][]"
                            value="{{ $category->id }}"
                            @if(in_array(
                                $category->id,
                                old('organization_permissions.courses.categories', $organization->organization_permissions['courses']['categories'] ?? [])
                            )) checked @endif
                        >
                        <label class="form-check-label" for="course_cat_{{ $category->id }}">
                            {{ $category->title }}
                        </label>
                    </div>

                    {{-- Courses inside this category --}}
                    <div class="ml-4 category-container">
                        @foreach($category->courses as $course)
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input type="checkbox"
                                        class="form-check-input course-checkbox"
                                        data-parent="course_cat_{{ $category->id }}"
                                        name="organization_permissions[courses][items][]"
                                        value="{{ $course->id }}"
                                        @if(in_array($course->id, $organization->organization_permissions['courses']['items'] ?? [])) checked @endif>
                                    {{ $course->title }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <hr>

            {{-- ==== Exams ==== --}}
            <h5 class="mb-3 fw-bold text-primary text-underline">Exams</h5>
            @foreach($examCategories as $category)
                <div class="form-group">
                    <div class="form-check bg-light p-2 category-name">
                        <input type="checkbox"
                            class="form-check-input category-checkbox"
                            id="exam_cat_{{ $category->id }}"
                            data-group="exams"
                            name="organization_permissions[exams][categories][]"
                            value="{{ $category->id }}"
                            @if(in_array($category->id, $organization->organization_permissions['exams']['categories'] ?? [])) checked @endif>
                        <label class="form-check-label" for="exam_cat_{{ $category->id }}">
                            {{ $category->title }}
                        </label>
                    </div>

                    {{-- Exams inside this category --}}
                    <div class="ml-4 category-container">
                        @foreach($category->exams as $exam)
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input type="checkbox"
                                        class="form-check-input exam-checkbox"
                                        data-parent="exam_cat_{{ $category->id }}"
                                        name="organization_permissions[exams][items][]"
                                        value="{{ $exam->id }}"
                                        @if(in_array($exam->id, $organization->organization_permissions['exams']['items'] ?? [])) checked @endif>
                                    {{ $exam->title }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <hr>

            {{-- ==== Job Posts ==== --}}
            <h5 class="mb-3 fw-bold text-primary text-underline">Job Posts</h5>
            @foreach($jobCategories as $category)
                <div class="form-group">
                    <div class="form-check bg-light p-2 category-name">
                        <input type="checkbox"
                            class="form-check-input category-checkbox"
                            id="job_cat_{{ $category->id }}"
                            data-group="job_posts"
                            name="organization_permissions[job_posts][categories][]"
                            value="{{ $category->id }}"
                            @if(in_array($category->id, $organization->organization_permissions['job_posts']['categories'] ?? [])) checked @endif>
                        <label class="form-check-label" for="job_cat_{{ $category->id }}">
                            {{ $category->title }}
                        </label>
                    </div>

                    <div class="ml-4 category-container">
                        @foreach($category->job_posts as $job)
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input type="checkbox"
                                        class="form-check-input job-checkbox"
                                        data-parent="job_cat_{{ $category->id }}"
                                        name="organization_permissions[job_posts][items][]"
                                        value="{{ $job->id }}"
                                        @if(in_array($job->id, $organization->organization_permissions['job_posts']['items'] ?? [])) checked @endif>
                                    {{ $job->title }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <hr>

            {{-- ==== Recorded Videos ==== --}}
            <h5 class="mb-3 fw-bold text-primary text-underline">Recorded Videos</h5>
            @foreach($videoCategories as $category)
                <div class="form-group">
                    <div class="form-check bg-light p-2 category-name">
                        <input type="checkbox"
                            class="form-check-input category-checkbox"
                            id="video_cat_{{ $category->id }}"
                            data-group="recorded_videos"
                            name="organization_permissions[recorded_videos][categories][]"
                            value="{{ $category->id }}"
                            @if(in_array($category->id, $organization->organization_permissions['recorded_videos']['categories'] ?? [])) checked @endif>
                        <label class="form-check-label" for="video_cat_{{ $category->id }}">
                            {{ $category->title }}
                        </label>
                    </div>

                    <div class="ml-4 category-container">
                        @foreach($category->recorded_videos as $video)
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input type="checkbox"
                                        class="form-check-input video-checkbox"
                                        data-parent="video_cat_{{ $category->id }}"
                                        name="organization_permissions[recorded_videos][items][]"
                                        value="{{ $video->id }}"
                                        @if(in_array($video->id, $organization->organization_permissions['recorded_videos']['items'] ?? [])) checked @endif>
                                    {{ $video->title }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <hr>
                
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <button type="submit" class="btn-submit btn btn-primary btn-sm m-b-0 flex-1"><i class="fa fa-save"></i> Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<style>
    .category-container{
        max-height: 200px;
        overflow: auto;
    }
    .form-check-input{
        margin-left: 0px;
    }
    .category-name{
        box-shadow: 1px 1px 5px #cccccc;
    }
</style>
<script>
    $(document).ready(function() {
        $('.menu_subscriptions').addClass('active');

        // When a category is checked, disable its children
        document.querySelectorAll('.category-checkbox').forEach(cat => {            
            $(cat).parent().next().toggleClass('d-none', cat.checked);
            cat.addEventListener('change', function () {
                const parentId = this.id;
                const children = document.querySelectorAll('[data-parent="' + parentId + '"]');
                $(this).parent().next().toggleClass('d-none', this.checked);
                children.forEach(chk => {
                    $(chk).toggleClass('readonly-checkbox', this.checked);
                    chk.checked = this.checked;
                });
            });
        });

        // When a child is checked, uncheck the parent category
        document.querySelectorAll('[data-parent]').forEach(child => {
            child.addEventListener('change', function () {
                //if (this.checked) {
                    const parent = document.getElementById(this.dataset.parent);
                    if (parent) {
                        parent.checked = false;
                    }
                //}
            });
        });
    });
</script>
@endsection

