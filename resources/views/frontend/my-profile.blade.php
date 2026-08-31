@extends('layouts.frontend.skeleton')

@section('title', 'My Profile')

@section('content')

@include('layouts.frontend.dashboard-header')

<!-- rts dahboard-area-main-wrapper -->
<div class="dashboard--area-main pt--100">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-3">
                @include('layouts.frontend.dashboard-sidebar', ['activeMenu' => 'my-profile'])
            </div>
            <div class="col-lg-9  rts-sticky-column-item">
                <div class="right-sidebar-my-profile-dash theiaStickySidebar pt--30">
                    <div class="d-flex justify-content-between align-items-center mb-5">
                        <h5 class="title mb-0">My Profile</h5>
                        <a href="{{route('edit-profile')}}" class="btn btn-success"><i class="fa fa-edit"></i> Edit</a>
                    </div>
                    <div class="my-single-portfolio-dashed">
                        <div class="name">Registration Date</div>
                        <div class="value">{{lms_format_date($user->created_at)}}</div>
                    </div>
                    <div class="my-single-portfolio-dashed">
                        <div class="name">First Name:</div>
                        <div class="value">{{$user->first_name}}</div>
                    </div>
                    <div class="my-single-portfolio-dashed">
                        <div class="name">Last Name:</div>
                        <div class="value">{{$user->last_name}}</div>
                    </div>
                    <div class="my-single-portfolio-dashed">
                        <div class="name">Email ID:</div>
                        <div class="value">{{$user->email}}</div>
                    </div>
                    <div class="my-single-portfolio-dashed">
                        <div class="name">Mobile Number:</div>
                        <div class="value">{{$user->mobile}}</div>
                    </div>
                    <div class="my-single-portfolio-dashed">
                        <div class="name">Date of Birth:</div>
                        <div class="value">{{optional($user->dob)->format('d-M-Y')}}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- rts dahboard-area-main-wrapper end -->

@endsection