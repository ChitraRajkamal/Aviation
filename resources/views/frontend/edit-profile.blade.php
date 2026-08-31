@extends('layouts.frontend.skeleton')
@php
    $maxDob = now()->subYear(10)->format('Y-m-d');
@endphp

@section('title', 'Change Password')

@section('content')

@include('layouts.frontend.dashboard-header')

<!-- rts dahboard-area-main-wrapper -->
<div class="dashboard--area-main pt--100">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-3">
                @include('layouts.frontend.dashboard-sidebar', ['activeMenu' => 'my-profile'])
            </div>
            <div class="col-lg-9">
                <div class="right-sidebar-my-profile-dash theiaStickySidebar pt--30">
                    <h5 class="title"><i class="fa-regular fa-edit"></i> Edit Profile</h5>
                    
                    <form action="{{ route('profile.update') }}" method="POST" class="submitForm" enctype="multipart/form-data" data-parsley-validate>
                        @csrf
                        @method('PATCH')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" value="{{old('first_name', $user->first_name)}}"
                                        required data-parsley-required-message="Please enter your first name.">
                                    @error('first_name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" value="{{old('last_name', $user->last_name)}}" 
                                        required data-parsley-required-message="Please enter your last name.">
                                    @error('last_name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Email ID</label>
                                    <div class="form-control cursor-disabled bg-light">
                                        {{$user->email}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="dob" class="form-label">Date of Birth <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="dob" name="dob"
                                        value="{{ old('dob', optional($user->dob)->format('Y-m-d')) }}"
                                        required
                                        max="{{ $maxDob }}"
                                        data-parsley-required-message="Please enter your date of birth."
                                        data-parsley-max="{{ $maxDob }}"
                                        data-parsley-max-message="You must be at least 10 years old.">
                                    @error('dob')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="mobile" class="form-label">Mobile <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="mobile" name="mobile" value="{{old('mobile', $user->mobile)}}" 
                                        required data-parsley-required-message="Please enter your mobile.">
                                    @error('mobile')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Gender <span class="text-danger">*</span></label>
                                    <div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="gender" id="gender_male" value="Male"
                                                {{ old('gender', $user->gender) == 'Male' ? 'checked' : '' }}
                                                required
                                                data-parsley-errors-container="#gender-error"
                                                data-parsley-required-message="Please select your gender.">
                                            <label class="form-check-label" for="gender_male">Male</label>
                                        </div>

                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="gender" id="gender_female" value="Female"
                                                {{ old('gender', $user->gender) == 'Female' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="gender_female">Female</label>
                                        </div>

                                        <div id="gender-error" class="text-danger"></div>
                                    </div>

                                    @error('gender')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="name">
                                        Profile Picture
                                    </label>
                                    <small class="text-muted">[100 x 100 - less than <b>1 MB</b> size]</small>
                                    <input type="file" class="form-control" id="image" name="image" accept=".jpg,.jpeg,.png,.fig">
                                    @if ($user->image)
                                        <img src="{{lms_storage($user->image)}}" class="bs-view-image mt-4" style="max-height: 140px;">
                                    @endif
                                    @error('image')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                
                            </div>
                            <div class="col-md-6">
                                
                            </div>
                        </div>
                        
                        
                        

                        <button type="submit" class="btn btn-primary btn-lg w-auto">Update Profile</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- rts dahboard-area-main-wrapper end -->

@endsection