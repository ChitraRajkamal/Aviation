@extends('layouts.admin.skeleton')
@section('title', __('Razorpay Payment'))

@section('page-header')
    @include('layouts.admin.page-header', [
        'icon' => 'ph ph-credit-card text-muted',
        'title' => __('Razorpay Payment'),
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
                        <button class="btn btn-success btn-lg w-auto mt-4 rounded-md" id="make-payment"><i class="ph ph-credit-card"></i> MAKE PAYMENT</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    var options = {
        "key": "{{lms_setting('razorpay_api_key')}}",
        "amount": "{{$payment->amount}}",
        "currency": "{{lms_setting('currency')}}",
        "order_id": "{{$payment->order_id}}",
        "name": "{{lms_setting('app_name')}}",
        "description": "{{lms_setting('app_name')}} - Subscription Payment - {{$subscription->organization->name}}",
        "image": "{{asset('admin-assets/assets/images/favicon.png')}}",
        "handler": function (response) {
            console.log('handler', response);
            try {
                $('#razorpay-payment-verification').removeClass('d-none');
                $('#razorpay-button-container').addClass('d-none');
                setTimeout(() => {
                    show_loader();
                    unnotify();
                    $('#make-payment').prop('disabled', true).html('<i class="fa fa-spin fa-circle-notch"></i> Processing payment...');
                    $.post('{{route('admin.ajax.verify_razorpay_payment')}}', {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        id: {{$payment->id}},
                        razorpay_payment_id: response.razorpay_payment_id,
                        razorpay_order_id: response.razorpay_order_id,
                        razorpay_signature: response.razorpay_signature
                    }, function (data) {
                        console.log(data);
                        hide_loader();
                        if (data.status == 'error') {
                            notify({ title: data.message });
                        } else {
                            $('#make-payment').prop('disabled', true).html('<i class="fa fa-spin fa-circle-notch"></i> Redirecting to subscription page...');
                            notify({ title: data.message, type: 'success' });
                            setTimeout(() => {
                                show_loader();
                                window.location.href = "{{route('admin.subscriptions.index')}}";
                            }, 3000);
                        }
                    }, 'JSON');
                }, 100);
            }
            catch (err) {

            }
            finally {
                //hide_loader(true);
            }
        },
        "modal": {
            "ondismiss": function () {
                $('#make-payment').prop('disabled', false).html('MAKE PAYMENT');
                notify({ title: "Payment is cancelled and not completed. Please pay in order to complete the payment" });
            }
        },
        "theme": {
            "color": "#553CDF"
        }
    };
    var rpInstance = new Razorpay(options);
    if (!rpInstance.eventListeners || !rpInstance.eventListeners['payment.failed']) {
        rpInstance.on('payment.failed', function (response) {
            $('#make-payment').prop('disabled', false).html('MAKE PAYMENT');
            notify({ title: "Error: " + response.error.description });
        });
    }
    document.addEventListener("DOMContentLoaded", (event) => {
        document.getElementById('make-payment').onclick = function (e) {
            $('#make-payment').prop('disabled', true).html('<i class="fa fa-spin fa-circle-notch"></i> Please wait...');
            rpInstance.open();
            e.preventDefault();
        }
    });
    $(document).ready(function() {
        /*var response = {
            "razorpay_payment_id": "pay_Pq6jeiuuTBMX8E",
            "razorpay_order_id": "order_Pq6jKOjrQ9tkyf",
            "razorpay_signature": "39d549d8379e838893351b2d25087e6fb09188dacb120785e47902eb80ee9150"
        };*/
    });
</script>
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

