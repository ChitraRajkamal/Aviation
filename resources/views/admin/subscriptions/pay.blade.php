@extends('layouts.admin.skeleton')
@section('title', __('Make Payment'))

@section('page-header')
    @include('layouts.admin.page-header', [
        'icon' => 'ph ph-credit-card text-muted',
        'title' => __('Make Payment'),
        'breadcrumbs' => [
            route('admin.subscriptions.index') => __('Subscriptions'),
            '#' => __('Payment'),
        ],
        'links' => [
            generate_link_element([
                'action' => 'back',
                'route' => 'admin.subscriptions.index',
            ]),
        ]
    ])
@endsection

@section('extra-scripts')
    <script type="text/javascript" src="{{ asset('admin-assets/assets/pages/j-pro/js/autoNumeric.js') }}"></script>
@endsection

@section('content')
<form id="saveForm" class="autoSubmit" action="{{ route('admin.subscriptions.save-payment', $subscription->id) }}" method="POST" enctype="multipart/form-data" data-parsley-validate>
    @csrf
    @method('PUT')
    <div class="card">
        <div class="card-block mt-1">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        {{lms_form_label('No of students', false)}}</span>
                        <div>{{$subscription->no_of_students}}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {{lms_form_label('Need Courses?', false)}}</span>
                        <div>{!!$subscription->allow_courses ? '<i class="fa fa-check-circle text-success"></i> <b>Yes</b>' : 'No'!!}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {{lms_form_label('Need Exams?', false)}}</span>
                        <div>{!!$subscription->allow_exams ? '<i class="fa fa-check-circle text-success"></i> <b>Yes</b>' : 'No'!!}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {{lms_form_label('Need Job Posts?', false)}}</span>
                        <div>{!!$subscription->allow_job_posts ? '<i class="fa fa-check-circle text-success"></i> <b>Yes</b>' : 'No'!!}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {{lms_form_label('Need Recorded Videos?', false)}}</span>
                        <div>{!!$subscription->allow_recorded_videos ? '<i class="fa fa-check-circle text-success"></i> <b>Yes</b>' : 'No'!!}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {{lms_form_label('Price', false)}}</span>
                        <div class="font-weight-bold">{!! lms_setting('currency_symbol') . $subscription->price!!}</div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        {{lms_form_label('Payment Type')}}
                        <select class="form-control select-two" id="type" data-placeholder="Choose payment type" required name="type">
                            <option value="">Choose</option>
                            @foreach (lms_enum_to_array(\App\Enums\SubscriptionPaymentType::class, true) as $item)
                              <option value="{{$item['value']}}" {{ old('type') == $item['value'] ? 'selected' : '' }}>{{$item['name']}}</option>
                            @endforeach
                        </select>
                        @error('type')
                            <div class="parsley-errors-list">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group details-cont d-none">
                        {{lms_form_label('Details')}}</span>
                        <textarea class="form-control h-120" name="remarks" placeholder="{{__('Enter Details')}}">{{ old('remarks')}}</textarea>
                        @error('remarks')
                            <div class="parsley-errors-list">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
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
<script>
    $(document).ready(function() {
        $('.menu_subscriptions').addClass('active');
        $('#type').change(function() {
            if(!this.value) return;
            $('.details-cont').addClass('d-none');
            $('.details-cont textarea').prop('required', false);
            if('{{\App\Enums\SubscriptionPaymentType::Razorpay->value}}' != this.value){
                $('.details-cont').removeClass('d-none');
                $('.details-cont textarea').prop('required', true);
            }
        }).trigger('change');
    });
</script>
@endsection

