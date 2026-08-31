@extends('layouts.frontend.skeleton')
@section('title', $exam->title)

@php
    $timeTakenInSeconds = $results->map(function ($r) {
        list($h, $m, $s) = explode(':', $r->time_taken);
        return round(($h * 60 * 60) + ($m * 60) + ($s), 2);
    })->toArray();

    $formattedDates = $results->map(function ($item) {
        return \Carbon\Carbon::parse($item->created_at)->format('d-M-Y h:i:s a');
    })->toArray();
@endphp

@section('content')
<!-- course details breadcrumb -->
<div class="course-details-breadcrumb-1 bg_image rts-section-gap">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="single-course-left-align-wrapper">
                    <div class="meta-area">
                        <a href="{{url('')}}">Home</a>
                        <i class="fa-solid fa-chevron-right"></i>
                        <a href="{{route('exams')}}">Exams</a>
                        <i class="fa-solid fa-chevron-right"></i>
                        <a href="{{lms_exam_slug($exam)}}">{{$exam->title}}</a>
                        <i class="fa-solid fa-chevron-right"></i>
                        <a class="active" href="#">Result</a>
                    </div>
                    <h1 class="title my-2">
                        {{$exam->title}}
                    </h1>
                    <div class="author-area mt-1">
                        <div class="author">
                            <img src="{{asset('admin-assets/assets/images/profile-40.png')}}" alt="Instructor">
                            <div class="name fw-medium"><span>By</span> {{$exam->user->first_name}}.</div>
                        </div>
                        <p class="mb-0"> <span>Category: </span> {{$exam->category->title}}</p>
                        @if (!$exam->retake || count($results) < $exam->retake)
                            <a href="{{ lms_exam_slug($exam, 'attend') }}" class="btn btn-primary btn-lg"><i class="fa-regular fa-play"></i> Reattempt</a>
                        @endif
                        @if (count($results) > 0)
                            {{-- check this function lms_calculate_grade and also change there if you change value here --}}
                            <a href="{{ route('exams.certificate', ['slug' => $exam->slug, 'action' => 'download']) }}" target="_blank" class="btn btn-success btn-lg"><i class="fa-regular fa-certificate"></i> Downoad Certificate</a>
                            <i class="fas fa-info-circle text-light cursor-pointer ms-2"
                                data-bs-toggle="tooltip"
                                data-bs-html="true"
                                title="
                                    <b>Grade Calculation:</b><br>
                                    90% – 100% → A+<br>
                                    80% – 89% → A<br>
                                    70% – 79% → B+<br>
                                    60% – 69% → B<br>
                                    50% – 59% → C<br>
                                    40% – 49% → D<br>
                                    Below 40% → F
                                "
                            >
                            </i>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- course details breadcrumb end -->
<!-- course details area start -->
<form action="{{lms_exam_slug($exam, 'save_answers')}}" method="POST" class="rts-course-area rts-section-gap">
    @csrf
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div id="examStatusChart" style="height: 300px; margin-bottom: 2rem;"></div>
            </div>
            <div class="col-md-8">
                <div id="examMarksChart" style="height: 300px; margin-bottom: 2rem;"></div>
            </div>
        </div>
        
        <div id="examTimeChart" style="height: 300px;"></div>

        <div class="row g-5">
            <div class="col-lg-12 order-cl-1 order-lg-1 order-md-2 order-sm-2 order-2">
                <div class="rts-reviewd-area-dashed border-0 p-0 table-responsive" style="white-space: nowrap;">
                    <h5 class="title">Exam Results</h5>
                    <table class="table-reviews quiz">
                        <thead>
                            <tr>
                                <th>Date/Time</th>
                                <th>Total Questions</th>
                                <th>Total Marks</th>
                                <th>Pass Marks</th>
                                <th>Obtained Marks</th>
                                <th>Status</th>
                                <th>Grade</th>
                                <th>Time Taken</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($results as $d)
                                @php
                                    $grade = lms_calculate_grade($d->percentage);
                                @endphp
                                <tr>
                                    <td>
                                        <div class="information-quiz">
                                            <span>{{lms_format_date($d->created_at)}}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="questions">{{$d->total_questions}}</span>
                                    </td>
                                    <td>
                                        <span class="marks">{{$d->total_mark}}</span>
                                    </td>
                                    <td>
                                        <span class="marks">{{$d->pass_mark}}</span>
                                    </td>
                                    <td>
                                        <span class="text-{{$d->obtained_mark > 0 ? 'success' : 'danger'}} fw-bold">{{$d->obtained_mark}}</span>
                                    </td>
                                    <td>
                                        @if ($d->is_pass)
                                            <span class="pass"><i class="fa fa-check-circle"></i> PASS</span>
                                        @else
                                            <span class="fail"><i class="fa fa-exclamation-circle"></i> FAIL</span>
                                        @endif                                        
                                    </td>
                                    <td>
                                        <span class="p-2 rounded border text-center d-inline-block" style="width: 40px; background: {{lms_calculate_grade_bg_color($grade)}}; color: {{lms_calculate_grade_text_color($grade)}};">{{$grade}}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{$d->time_taken}}</span>
                                    </td>
                                    <td>
                                        <a href="{{route('exams.result_details', ['id' => $d->id, 'slug' => $exam->slug])}}" class="rts-btn btn-border">Details</a>
                                    </td>
                                </tr>
                            @endforeach
                            
                        </tbody>
                    </table>
                </div>         
            </div>
        </div>
    </div>
</form>
<!-- course details area end -->

<div class="modal fade" id="warningAltTabModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="exampleModalLabel">Warning..!</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4 px-5 text-center text-muted h">
                Switching windows or using Alt + Tab during the exam is <b class="text-danger h4">strictly prohibited</b>. 
                This is your first and final warning. Further violations will result in the automatic 
                submission of your answers and <b class="text-danger h4">termination</b> of the exam.
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Okay, i will honor</button>
            </div>
        </div>
    </div>
</div>
<style>
    .tooltip-inner {
        background-color: var(--color-primary) !important; /* Bootstrap primary blue */
        color: #fff !important;
        padding: 10px 15px !important;
        font-size: 14px;
        border-radius: 8px;
        text-align: left;
    }

    /* Optional: change tooltip arrow color */
    .tooltip.bs-tooltip-top .tooltip-arrow::before {
            border-top-color: var(--color-primary) !important;
    }
    .tooltip.bs-tooltip-bottom .tooltip-arrow::before {
            border-bottom-color: var(--color-primary) !important;
    }
    .tooltip.bs-tooltip-start .tooltip-arrow::before {
            border-left-color: var(--color-primary) !important;
    }
    .tooltip.bs-tooltip-end .tooltip-arrow::before {
        border-right-color: var(--color-primary) !important;
    }
</style>
@endsection

@section('extra-scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        const examMarksChart = {
            chart: {
                type: 'bar', // Apex uses 'bar' with horizontal: false for vertical columns
                height: 400,
                toolbar: {
                    show: false
                },
                fontFamily: 'inherit'
            },
            title: {
                text: 'Exam Marks',
                align: 'center',
                style: { 
                    fontSize: '16px',
                    fontWeight: 'bold',
                }
            },
            xaxis: {
                categories: @json($formattedDates),
                title: {
                    text: 'Date/Time'
                }
            },
            yaxis: {
                min: -5,
                title: {
                    text: 'Obtained Marks'
                }
            },
            tooltip: {
                y: {
                    formatter: (val) => `Marks: ${val}`
                }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '40%',
                    colors: {
                        ranges: [
                            {
                                from: -100,
                                to: -1,
                                color: '#e74c3c' // red for negative
                            },
                            {
                                from: 0,
                                to: 100,
                                color: '#006400' // green for positive
                            }
                        ]
                    }
                }
            },
            dataLabels: {
                enabled: true
            },
            series: [{
                name: 'Obtained Marks',
                data: @json($results->pluck('obtained_mark')->toArray())
            }],
            //colors: ['#e74c3c', '#2ecc71', '#e74c3c', '#2ecc71']
        };
        new ApexCharts(document.querySelector("#examMarksChart"), examMarksChart).render();

        const examStatusChart = {
            chart: {
                type: 'donut',
                height: 300,
                toolbar: {
                    show: false
                },
                fontFamily: 'inherit'
            },
            title: {
                text: 'Attempted vs Pass vs Fail',
                align: 'center',
                style: {
                    fontSize: '16px',
                    fontWeight: 'bold',
                }
            },
            labels: ['Pass', 'Fail'],
            series: [{{$results->where('is_pass', 1)->count()}}, {{$results->where('is_pass', 0)->count()}}],
            colors: ['#006400', '#e74c3c'],
            legend: {
                position: 'bottom'
            },
            dataLabels: {
                formatter: (val, opts) => {
                    return `${opts.w.globals.labels[opts.seriesIndex]}: ${opts.w.config.series[opts.seriesIndex]}`;
                },
                style: {
                    fontSize: '14px'
                }
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '50%'
                    }
                }
            }
        };
        new ApexCharts(document.querySelector("#examStatusChart"), examStatusChart).render();

        const examTimeChart = {
            chart: {
                type: 'bar',
                height: 400,
                toolbar: {
                    show: false
                },
                fontFamily: 'inherit'
            },
            title: {
                text: 'Time Taken per Exam',
                align: 'center',
                style: {
                    fontSize: '16px',
                    fontWeight: 'bold',
                }
            },
            xaxis: {
                categories: @json($formattedDates),
                title: {
                    text: 'Exam Time'
                },
            },
            yaxis: {
                title: {
                    text: 'Date'
                },
                labels: {
                    formatter: function (val) {
                        return val;
                    },
                    style: {
                        fontSize: '12px'
                    }
                }
            },
            plotOptions: {
                bar: {
                    horizontal: true,
                    barHeight: '50%'
                }
            },
            tooltip: {
                y: {
                    formatter: function (value) {
                        return to_time(value);
                    }
                }
            },
            dataLabels: {
                enabled: true,
                formatter: function (value) {
                        return to_time(value);
                },
            },
            series: [{
                name: 'Time Taken',
                data: @json($timeTakenInSeconds)
            }],
            colors: ['#3498db']
        };
        new ApexCharts(document.querySelector("#examTimeChart"), examTimeChart).render();
    </script>
    <script>
        // Initialize all tooltips on the page
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    </script>
@endsection