@extends('layouts.admin.skeleton')
@section('title', 'Dashboard')

@section('content')
<style>
  .dashboard-leaf-like-icon .card {
      border-bottom: 3px solid;
  }
  .dashboard-leaf-like-icon .feather.icon-dashboard {
      padding: 10px;
      border-radius: 0px;
      border: 1px solid;
      box-shadow: 2px 3px;
  }
  .top-box .card-block{
    background: linear-gradient(360deg, #ffffff 19%, var(--bg-gradient));
  }
</style>

<div class="row top-box">
    <div class="col-xl-3 col-md-6 col-6">
        <div class="card">
            <div class="card-block" style="--bg-gradient: #fe936550;">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h4 class="text-c-yellow f-w-600 mb-0">{{$counter['courses']}}</h4>
                    </div>
                    <div class="col-4 text-right">
                        <i class="ph ph-book-open f-28"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-c-yellow">
                <div class="row align-items-center">
                    <div class="col-9">
                        <p class="text-white m-b-0">Courses</p>
                    </div>
                    <div class="col-3 text-right d-none d-md-inline-block">
                        <i class="feather icon-trending-up text-white f-16"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 col-6">
        <div class="card">
            <div class="card-block" style="--bg-gradient: #0ac28250;">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h4 class="text-c-green f-w-600 mb-0">{{$counter['exams']}}</h4>
                    </div>
                    <div class="col-4 text-right">
                        <i class="ph ph-notebook f-28"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-c-green">
                <div class="row align-items-center">
                    <div class="col-9">
                        <p class="text-white m-b-0">Exams</p>
                    </div>
                    <div class="col-3 text-right d-none d-md-inline-block">
                        <i class="feather icon-trending-up text-white f-16"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 col-6">
        <div class="card">
            <div class="card-block" style="--bg-gradient: #ec352350;">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h4 class="text-c-pink f-w-600 mb-0">{{$counter['students']}}</h4>
                    </div>
                    <div class="col-4 text-right">
                        <i class="ph ph-graduation-cap f-28"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-c-pink">
                <div class="row align-items-center">
                    <div class="col-9">
                        <p class="text-white m-b-0">Students</p>
                    </div>
                    <div class="col-3 text-right d-none d-md-inline-block">
                        <i class="feather icon-trending-up text-white f-16"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 col-6">
        <div class="card">
            <div class="card-block" style="--bg-gradient: #01aaad50;">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h4 class="text-c-blue f-w-600 mb-0">{{$counter['staffs']}}</h4>
                    </div>
                    <div class="col-4 text-right">
                        <i class="ph ph-users f-28"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-c-blue">
                <div class="row align-items-center">
                    <div class="col-9">
                        <p class="text-white m-b-0">Staffs</p>
                    </div>
                    <div class="col-3 text-right d-none d-md-inline-block">
                        <i class="feather icon-trending-up text-white f-16"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card bg-c-yellow text-white">
            <div class="card-block">
                <div class="row align-items-center">
                    <div class="col">
                        <p class="m-b-5">Course Enrollments</p>
                        <h4 class="m-b-0">{{$counter['course_enrolls']}}</h4>
                    </div>
                    <div class="col col-auto text-dark">
                        <i class="ph ph-book-open f-50 bg-white rounded p-2"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header text-uppercase">
                <h5>Last 5 Course Enrollments</h5>
            </div>
            <div class="card-block p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-borderless mb-0">
                        <thead class="bg-primary">
                            <tr>
                                <th>Student</th>
                                <th>Course</th>
                                <th>Enrolled On</th>
                            </th></tr>
                        </thead>
                        <tbody>
                            @foreach ($course_enrolls as  $item)
                            <tr>
                                <td>{{$item->user->full_name}}</td>
                                <td>{{$item->course->title}}</td>
                                <td>{{lms_format_date($item->created_at)}}</td>
                            </tr>
                            @endforeach
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card bg-c-green text-white">
            <div class="card-block">
                <div class="row align-items-center">
                    <div class="col">
                        <p class="m-b-5">Exam Enrollments</p>
                        <h4 class="m-b-0">{{$counter['exam_enrolls']}}</h4>
                    </div>
                    <div class="col col-auto text-dark">
                        <i class="ph ph-notebook f-50 bg-white rounded p-2"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header text-uppercase">
                <h5>Last 5 Exam Enrollments</h5>
            </div>
            <div class="card-block p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-borderless mb-0">
                        <thead class="bg-primary">
                            <tr>
                                <th>Student</th>
                                <th>Exam</th>
                                <th>Enrolled On</th>
                            </th></tr>
                        </thead>
                        <tbody>
                            @foreach ($exam_enrolls as  $item)
                            <tr>
                                <td>{{$item->user->full_name}}</td>
                                <td>{{$item->exam->title}}</td>
                                <td>{{lms_format_date($item->created_at)}}</td>
                            </tr>
                            @endforeach
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


@php
    /*

<div class="row dashboard-leaf-like-icon">
    <div class="col-xl-3 col-md-6 col-6">
        <div class="card text-c-yellow">
            <div class="card-block">
                <div class="row align-items-center">
                    <div class="col">
                        <i class="feather icon-bar-chart icon-dashboard f-30" style="background: #fe93663d;"></i>
                    </div>
                    <div class="col col-auto text-right">
                        <p class="m-b-5 text-muted">Purchases</p>
                        <h4 class="m-b-0 font-weight-bold">1</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 col-6">
        <div class="card text-c-green">
            <div class="card-block">
                <div class="row align-items-center">
                    <div class="col">
                        <i class="feather icon-shopping-cart icon-dashboard f-30" style="background: #0ac3833d;"></i>
                    </div>
                    <div class="col col-auto text-right">
                        <p class="m-b-5 text-muted">Sales</p>
                        <h4 class="m-b-0 font-weight-bold">0</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 col-6">
        <div class="card text-c-pink">
            <div class="card-block">
                <div class="row align-items-center">
                    <div class="col">
                        <i class="feather icon-users icon-dashboard f-30" style="background: #eb35243d;"></i>
                    </div>
                    <div class="col col-auto text-right">
                        <p class="m-b-5 text-muted">Suppliers</p>
                        <h4 class="m-b-0 font-weight-bold">1</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 col-6">
        <div class="card text-c-blue">
            <div class="card-block">
                <div class="row align-items-center">
                    <div class="col">
                        <i class="feather icon-user-check icon-dashboard f-30" style="background: #01abae3d;"></i>
                    </div>
                    <div class="col col-auto text-right">
                        <p class="m-b-5 text-muted">Customers</p>
                        <h4 class="m-b-0 font-weight-bold">2</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
*/ 
@endphp
@endsection