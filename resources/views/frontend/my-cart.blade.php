@extends('layouts.frontend.skeleton')
@php
    $cart_total = $discount = 0;
    $currency_symbol = lms_setting('currency_symbol');
    $is_paying = request()->input('pay');
@endphp

@section('title', 'My Cart')

@section('content')

<div class="rts-bread-crumbarea-1 rts-section-gap bg_image">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-main-wrapper">
                    <h1 class="title">{{__($is_paying ? 'Payment' : 'Cart')}}</h1>
                    <!-- breadcrumb pagination area -->
                    <div class="pagination-wrapper">
                        <a href="index.html">Home</a>
                        <i class="fa-regular fa-chevron-right"></i>
                        @if ($is_paying)
                            <a class="active" href="javascript:;">{{__('Payment')}}</a>
                        @else
                            <a class="active" href="javascript:;">{{__('Cart')}}</a>
                        @endif
                    </div>
                    <!-- breadcrumb pagination area end -->
                </div>
            </div>
        </div>
    </div>
</div>

<main class="ms-main py-5">
    <div class="ms-page-content">
        <article id="post-283" class="post-283 page type-page status-publish hentry">
            <div class="ms-default-page container">
                <div class="woocommerce">
                    <div class="woocommerce-notices-wrapper"></div>
                    <div class="ms-woocommerce-cart-form-wrapper">
                        <table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents">
                            <thead>
                                <tr>
                                    <th class="product-remove">&nbsp;</th>
                                    <th class="product-thumbnail">Type</th>
                                    <th class="product-name">Course / Exam</th>
                                    <th class="product-price">Category</th>
                                    <th class="product-subtotal text-end">Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($cart->isEmpty())
                                    <tr class="alert alert-light text-center">
                                        <td colspan="10">
                                            <div class="mt--50">
                                                <i class="fa-regular fa-info-circle fa-3x text-success mb-3"></i><br>Your cart is empty
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                                @foreach ($cart as $e)
                                    @php
										if(!$e->item) continue;
                                        $cart_total += $e->amount;
                                    @endphp
                                    <tr class="woocommerce-cart-form__cart-item cart_item">
                                        <td class="product-remove">
                                            @if (!$is_paying)
                                                <form action="{{ route('cart-remove', ['type' => $e->type, 'type_id' => $e->item->id]) }}" id="cart-form-{{$e->id}}" 
                                                    class="mb-0 ml-1 remove" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="">
                                                        <svg viewBox="0 0 200 200" width="18" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M114,100l49-49a9.9,9.9,0,0,0-14-14L100,86,51,37A9.9,9.9,0,0,0,37,51l49,49L37,149a9.9,9.9,0,0,0,14,14l49-49,49,49a9.9,9.9,0,0,0,14-14Z">
                                                            </path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                        <td class="product-thumbnail">
                                            {{ucfirst($e->type)}}
                                        </td>
                                        <td class="product-name" data-title="Product">
                                            {{$e->item->title}} 
                                            <a href="{{route($e->type . 's.details', ['slug' => $e->item->slug])}}" class="fw-medium" target="_blank"><i class="fa-regular fa-eye text-danger"></i></a>
                                        </td>
                                        <td class="product-price">
                                            {{$e->item->category->title}}
                                        </td>
                                        <td class="product-subtotal text-end">
                                            <span class="woocommerce-Price-amount amount">
                                                {!!lms_show_price($e->item)!!}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @if($cart_total > 0)
                            <div class="row">
                                <div class="col-md-5 offset-md-7">
                                    <div class="ms-cart-collaterals cart-collaterals">
                                        <div class="ms-cart-totals cart_totals mt-5 border shadow">
                                            @if ($is_paying)
                                                <h3 class="animated fadeIn">{{__('Make Payment')}}</h3>
                                            @else
                                                <h3 class="animated fadeIn">{{__('Cart totals')}}</h3>
                                            @endif
                                            <table class="shop_table shop_table_responsive">
                                                <tbody>
                                                    <tr class="cart-subtotal">
                                                        <th>{{__('Subtotal')}}</th>
                                                        <td data-title="Subtotal">
                                                            <span class="woocommerce-Price-amount amount">
                                                                <bdi><span class="woocommerce-Price-currencySymbol">{{$currency_symbol}}</span>{{$cart_total}}</bdi>
                                                            </span>
                                                        </td>
                                                    </tr>
                                                    <tr class="cart-subtotal">
                                                        <th>{{__('Discount')}}</th>
                                                        <td data-title="Subtotal">
                                                            <span class="woocommerce-Price-amount amount">
                                                                <bdi><span class="woocommerce-Price-currencySymbol">{{$currency_symbol}}</span>{{$discount}}</bdi>
                                                            </span>
                                                        </td>
                                                    </tr>
                                                    <tr class="order-total">
                                                        <th>{{__(key: 'Total')}}</th>
                                                        <td data-title="Total">
                                                            <strong><span class="woocommerce-Price-amount amount">
                                                                <bdi><span class="woocommerce-Price-currencySymbol">{{$currency_symbol}}</span>{{$cart_total}}</bdi></span>
                                                            </strong>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            @if ($is_paying)
                                                <div class="ms-proceed-to-checkout wc-proceed-to-checkout">
                                                    <button id="make-payment" type="button" class="rts-btn btn-primary"> {{__(key: 'PAY NOW')}}</button>
                                                </div>
                                            @else
                                                <form action="{{ route('cart-create-order') }}" id="cart-order-form" class="mb-0 ml-1" method="POST">
                                                    @csrf                                                
                                                    <div class="ms-proceed-to-checkout wc-proceed-to-checkout">
                                                        <button type="submit" class="rts-btn btn-primary">
                                                            {{__(key: 'Proceed to checkout')}} <i class="ms-3 fa-regular fa-chevron-right"></i>
                                                        </button>
                                                    </div>
                                                </form>
                                            @endif
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        </article>
    </div>
</main>


@if ($is_paying)
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    var options = {
        "key": "{{lms_setting('razorpay_api_key')}}",
        "amount": "{{$payment->amount}}",
        "currency": "{{lms_setting('currency')}}",
        "order_id": "{{$payment->order_id}}",
        "name": "{{lms_setting('app_name')}}",
        "description": "{{lms_setting('app_name')}} - Payment",
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
                            $('#make-payment').prop('disabled', true).html('<i class="fa fa-spin fa-circle-notch"></i> Redirecting to courses page...');
                            notify({ title: data.message, type: 'success' });
                            setTimeout(() => {
                                show_loader();
                                window.location.href = "{{route('courses')}}";
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
                $('#make-payment').prop('disabled', false).html('{{__('PAY NOW')}}');
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
            $('#make-payment').prop('disabled', false).html('{{__('PAY NOW')}}');
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
            "razorpay_payment_id": "pay_Q2cKIkyFi9CGZu",
            "razorpay_order_id": "order_Q2cJgBgOaMtsMt",
            "razorpay_signature": "75c9f3ee56f5c88f5f068f44781604aab1865532abbea475106b57306da497a7"
        };*/
    });
</script>
@endif

@endsection