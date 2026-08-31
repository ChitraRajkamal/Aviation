<?php

namespace App\Enums;

enum SubscriptionPaymentType: string {
    case Razorpay = 'razorpay';
    case NetBanking = 'netbanking';
    case Cheque = 'cheque';
    case Others = 'others';
}
