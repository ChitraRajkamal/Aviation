@extends('layouts.frontend.skeleton')

@section('title', 'Change Password')

@section('content')

@include('layouts.frontend.dashboard-header')

<!-- rts dahboard-area-main-wrapper -->
<div class="dashboard--area-main pt--100">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-3">
                @include('layouts.frontend.dashboard-sidebar', ['activeMenu' => 'change-password'])
            </div>
            <div class="col-lg-9">
                <div class="right-sidebar-my-profile-dash theiaStickySidebar pt--30">
                    <h5 class="title"><i class="fa-regular fa-key"></i> Change Password</h5>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <form action="{{ route('password.update') }}" method="POST" class="submitForm" data-parsley-validate>
                                @csrf
                                @method('PUT')
                                {{-- Current Password --}}
                                <div class="mb-3">
                                    {{lms_form_label('Current Password', true, '', 'for="current_password"')}}
                                    <input type="password" class="form-control"
                                        id="current_password" name="current_password" required data-parsley-required-message="Please enter your current password.">

                                    @error('current_password', 'updatePassword')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- New Password --}}
                                <div class="mb-3">
                                    {{lms_form_label('New Password', true, '', 'for="password"')}}
                                    <input type="password" class="form-control"
                                        id="password" name="password" required data-parsley-minlength="8"
                                        data-parsley-required-message="Please enter a new password."
                                        data-parsley-minlength-message="Password must be at least 8 characters.">

                                    @error('password', 'updatePassword')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Confirm New Password --}}
                                <div class="mb-3">
                                    {{lms_form_label('Confirm New Password', true, '', 'for="password_confirmation"')}}
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" 
                                        data-parsley-equalto="#password"
                                        data-parsley-required-message="Please confirm your new password"
                                        data-parsley-equalto-message="Passwords do not match" required>
                                </div>

                                <button type="submit" class="btn btn-primary btn-lg w-auto">Update Password</button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- rts dahboard-area-main-wrapper end -->

@endsection