@extends('layouts.admin.skeleton')
@section('title', __('Edit Organization'))

@section('page-header')
    @include('layouts.admin.page-header', [
        'icon' => 'ph ph-note-book text-muted',
        'title' => __('Edit Organization'),
        'breadcrumbs' => [
            route('admin.users.organization') => __('Organizations'),
            '#' => __('New'),
        ],
        'links' => [
            generate_link_element([
                'action' => 'back',
                'route' => 'admin.users.organization',
            ]),
        ]
    ])
@endsection

@section('content')
    <form id="saveForm" class="autoSubmission" action="{{ route('admin.users.organization.save') }}" method="POST" data-parsley-validate>
        @csrf
        <input type="hidden" name="id" value="{{$user->id}}">
        <div class="card">
            <div class="card-block">
                <h5 class="text-primary"><i class="ph ph-building"></i> Organization Details</h5>
                <hr>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            {{lms_form_label('Organization Name')}}
                            <input type="text" class="form-control" name="organization_name" value="{{ old('organization_name', $user->organization->name)}}" placeholder="{{__('Enter Organization Name')}}" @lmsparsley(organizations_add,organization_name)>
                            @error('organization_name')
                                <div class="parsley-errors-list">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{lms_form_label('Organization Email ID', false)}}
                            <input type="text" class="form-control" name="organization_email" value="{{ old('organization_email', $user->organization->email)}}" placeholder="{{__('Enter Email ID')}}" @lmsparsley(organizations_add,organization_email)>
                            @error('organization_email')
                                <div class="parsley-errors-list">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{lms_form_label('Organization Phone', false, false)}}
                            <input type="text" class="form-control" name="organization_phone" value="{{ old('organization_phone', $user->organization->phone)}}" placeholder="{{__('Enter Phone')}}" @lmsparsley(organizations_add,organization_phone)>
                            @error('organization_phone')
                                <div class="parsley-errors-list">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{lms_form_label('Organization Address', false)}}
                            <input type="text" class="form-control" name="organization_address" value="{{ old('organization_address', $user->organization->address)}}" placeholder="{{__('Enter Organization Address')}}" @lmsparsley(organizations_add,organization_address)>
                            @error('organization_address')
                                <div class="parsley-errors-list">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{lms_form_label('Organization City', false)}}
                            <input type="text" class="form-control" name="organization_city" value="{{ old('organization_city', $user->organization->city)}}" placeholder="{{__('Enter Organization City')}}" @lmsparsley(organizations_add,organization_city)>
                            @error('organization_city')
                                <div class="parsley-errors-list">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{lms_form_label('Organization State', false)}}
                            <input type="text" class="form-control" name="organization_state" value="{{ old('organization_state', $user->organization->state)}}" placeholder="{{__('Enter Organization State')}}" @lmsparsley(organizations_add,organization_state)>
                            @error('organization_state')
                                <div class="parsley-errors-list">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{lms_form_label('Organization Zip / Postal Code', false)}}
                            <input type="text" class="form-control" name="organization_postal_code" value="{{ old('organization_postal_code', $user->organization->postal_code)}}" placeholder="{{__('Enter Organization Zip / Postal Code')}}" @lmsparsley(organizations_add,organization_postal_code)>
                            @error('organization_postal_code')
                                <div class="parsley-errors-list">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{lms_form_label('Country', false)}}
                            <select class="form-control select-two" id="organization_country" data-placeholder="Choose Country" name="organization_country" @lmsparsley(organizations_add,organization_country)>
                                <option value="">Choose Country</option>
                                @foreach (lms_country_list() as $item)
                                  <option value="{{$item}}" {{ old('country', $user->organization->country) == $item ? 'selected' : '' }}>{{$item}}</option>
                                @endforeach
                            </select>
                            @error('organization_country')
                                <div class="parsley-errors-list">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <br>
                <h5 class="text-primary"><i class="ph ph-shield-check"></i> Admin Details</h5>
                <hr>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            {{lms_form_label('First Name')}}
                            <input type="text" class="form-control" name="first_name" value="{{ old('first_name', $user->first_name)}}" placeholder="{{__('Enter First Name')}}" @lmsparsley(organizations_add,first_name)>
                            @error('first_name')
                                <div class="parsley-errors-list">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{lms_form_label('Last Name')}}
                            <input type="text" class="form-control" name="last_name" value="{{ old('last_name', $user->last_name)}}" placeholder="{{__('Enter Last Name')}}" @lmsparsley(organizations_add,last_name)>
                            @error('last_name')
                                <div class="parsley-errors-list">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{lms_form_label('Email ID')}}
                            <input type="text" class="form-control" name="email" readonly value="{{ old('email', $user->email)}}" placeholder="{{__('Enter Email ID')}}" @lmsparsley(organizations_add,email)>
                            @error('email')
                                <div class="parsley-errors-list">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            {{lms_form_label('Date of Birth', false)}}
                            <input type="date" class="form-control" name="dob" value="{{ old('dob', $user->dob)}}" placeholder="{{__('Enter DOB')}}" 
                               max="{{today()->subYears(lms_setting('user_min_age'))->format('Y-m-d')}}" @lmsparsley(organizations_add,dob)>
                            @error('dob')
                                <div class="parsley-errors-list">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{lms_form_label('Mobile', false)}}
                            <input type="text" class="form-control" name="mobile" value="{{ old('mobile', $user->mobile)}}" placeholder="{{__('Enter Mobile')}}" @lmsparsley(organizations_add,mobile)>
                            @error('mobile')
                                <div class="parsley-errors-list">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{lms_form_label('Phone', false)}}
                            <input type="text" class="form-control" name="phone" value="{{ old('phone', $user->phone)}}" placeholder="{{__('Enter Phone')}}" @lmsparsley(organizations_add,phone)>
                            @error('phone')
                                <div class="parsley-errors-list">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            {{lms_form_label('Gender', false)}}<br>
                            <div class="">
                                <label class="mr-2"><input {{old('gender', $user->gender)=='Male'?'checked':''}} type="radio" name="gender" value="Male" data-parsley-errors-container="#genderError" @lmsparsley(organizations_add,gender)> Male</label>
                                <label class="mr-2"><input {{old('gender', $user->gender)=='Female'?'checked':''}} type="radio" name="gender" value="Female" @lmsparsley(organizations_add,gender)> Female</label>
                                <label><input {{old('gender', $user->gender)=='Others'?'checked':''}} type="radio" name="gender" value="Others" @lmsparsley(organizations_add,gender)> Others</label>
                            </div>
                            <div id="genderError"></div>
                            @error('gender')
                                <div class="parsley-errors-list">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row fixed-submit-container">
                    <div class="col-md-12">
                        <div class="form-group">
                            <button type="submit" class="btn-submit btn btn-primary btn-sm m-b-0">
                                <i class="fa fa-save"></i> Save
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <style>
        
    </style>
    <script>
        $(document).ready(function() {
            $('.menu_users').addClass('active');
        });
    </script>
@endsection