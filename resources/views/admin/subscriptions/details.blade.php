@extends('layouts.admin.skeleton')
@section('title', __('Subscription Details'))

@section('page-header')
    @include('layouts.admin.page-header', [
        'icon' => 'ph ph-tag text-muted',
        'title' => __('Subscription Details'),
        'breadcrumbs' => [
            route('admin.subscriptions.index') => __('Subscriptions'),
            '#' => __('Details'),
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
                @if (lms_is_admin())
                    <div class="col-md-4">
                        <div class="form-group">
                            {{lms_form_label('Organization', false)}}</span>
                            <div>{{$subscription->organization->name}}</div>
                        </div>
                    </div>
                @endif
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
                <div class="col-md-4">
                    <div class="form-group">
                        {{lms_form_label('Status', false)}}</span>
                        <br>
                        <span class="badge badge-dark">{{$subscription->status}}</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {{lms_form_label('Expiry Date', false)}}</span>
                        <div>{{lms_format_date($subscription->expiry, 'd-M-Y', '-')}}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {{lms_form_label('Payment Type', false)}}</span>
                        <div>{{$subscription->type ? $subscription->type : '-'}}</div>
                    </div>
                </div>
                @if ($subscription->type == \App\Enums\SubscriptionPaymentType::Razorpay->value)
                    <div class="col-md-4">
                        <div class="form-group">
                            {{lms_form_label('Payment ID', false)}}</span>
                            <div>{{$subscription->payment->payment_id ?? '-'}}</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{lms_form_label('Order ID', false)}}</span>
                            <div>{{$subscription->payment->order_id ?? '-'}}</div>
                        </div>
                    </div>
                @else
                    <div class="col-md-4">
                        <div class="form-group">
                            {{lms_form_label('Details', false)}}</span>
                            <div>{{$subscription->remarks ? $subscription->remarks : '-'}}</div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</form>
<script>
    $(document).ready(function() {
        $('.menu_subscriptions').addClass('active');
    });
</script>
@endsection

