@extends('layouts.frontend.skeleton')
@section('title', 'Buy - ' . $exam->title)

@section('content')
<style>
    .module-wrapper:empty{
        display: none;
    }
    .accordion-button{
        font-size: 21px;
    }
    .exam-prepare-info{
        font-size: 18px;
    }
    .exam-prepare-info-each{
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
        background: #553cdf1f;
        background: linear-gradient(91deg, #e1e1e1 0, #553cdf4a 100%);
        padding: 16px 20px;
        border-radius: 10px;
        box-shadow: 2px 2px 1px #ccc;
    }
    .exam-prepare-info-each > span:last-child{
        color: black;
    }
    #make-payment{
        font-size: 30px;
        padding: 10px 20px;
    }
    @media screen and (min-width: 1200px) {
        .right-course-details{
            margin-top: -250px;
        }
    }
    @media screen and (max-width: 768px) {
        .exam-prepare-info{
            font-size: 14px;
        }
        .exam-prepare-info-each{
            margin-bottom: 10px;
            padding: 10px 16px;
        }
    }
</style>
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
                        <a class="active" href="#">Buy</a>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- course details breadcrumb end -->
<!-- course details area start -->
<div class="rts-course-area rts-section-gap">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-8">
                <div class="text-center">
                    <div class="exam-prepare-info-each">
                        <label>Amount</label>
                        <span class="fa-2x">{{$exam->price}}</span>
                    </div>
                    <button class="btn btn-success w-auto mt-4 rounded-md" id="make-payment"><i class="fa-regular fa-credit-card"></i> MAKE PAYMENT</button>
                </div>
            </div>
            <div class="col-lg-4 order-cl-2 order-lg-2 order-md-1 order-sm-1 order-1  rts-sticky-column-item">
                <!-- right- sticky bar area -->
                <div class="right-course-details">
                    <!-- single course-sidebar -->
                    <div class="course-side-bar">
                        <div class="thumbnail">
                            <img src="{{lms_storage($exam->thumbnail)}}" alt="">
                        </div>
                        <div class="price-area">
                            
                            @if ($exam->discount_flag)
                                <h3 class="title">{!!lms_show_price($exam, 'discounted_price')!!}</h3>
                                <h4 class="none">{!!lms_show_price($exam)!!}</h4>
                                <span class="discount">-{{(100 - round(($exam->discounted_price/$exam->price) * 100, 2))}}%</span>
                            @else
                                <h3 class="title">{!!lms_show_price($exam)!!}</h3>
                            @endif
                        </div>
                        @php
                            /*
                            <div class="clock-area">
                                <i class="fa-light fa-clock"></i>
                                <span>2 Day left at this price!</span>
                            </div>*/
                        @endphp
                        
                        <div class="what-includes">
                            <h5 class="title">This exam includes: </h5>
                            <div class="single-include">
                                <div class="left">
                                    <i class="fa-light fa-timer"></i>
                                    <span>Duration</span>
                                </div>
                                <div class="right">
                                    <span>{{$exam->duration_text}}</span>
                                </div>
                            </div>
                            <div class="single-include">
                                <div class="left">
                                    <i class="fa-regular fa-tag"></i>
                                    <span>Category</span>
                                </div>
                                <div class="right">
                                    <span>{{$exam->category->title}}</span>
                                </div>
                            </div>
                            <div class="single-include">
                                <div class="left">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                    <span>Update</span>
                                </div>
                                <div class="right">
                                    <span>{{lms_format_date($exam->updated_at)}}</span>
                                </div>
                            </div>
                            <div class="single-include">
                                <div class="left">
                                    <i class="fa-sharp fa-light fa-file-certificate"></i>
                                    <span>Certificate</span>
                                </div>
                                <div class="right">
                                    <span>Certificate of completion </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- single course-sidebar end -->
                </div>
                <!-- right- sticky bar area end -->
            </div>
        </div>
    </div>
</div>
<!-- course details area end -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    var options = {
        "key": "{{lms_setting('razorpay_api_key')}}",
        "amount": "{{$payment->amount}}",
        "currency": "{{lms_setting('currency')}}",
        "order_id": "{{$payment->order_id}}",
        "name": "{{lms_setting('app_name')}}",
        "description": "{{lms_setting('app_name')}} - Exam Payment - {{$exam->title}}",
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
                    $.post('{{route('ajax.verify_razorpay_payment')}}', {
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
                            $('#make-payment').prop('disabled', true).html('<i class="fa fa-spin fa-circle-notch"></i> Redirecting to exam page...');
                            notify({ title: data.message, type: 'success' });
                            setTimeout(() => {
                                show_loader();
                                window.location.href = "{{lms_exam_slug($exam)}}";
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

@endsection