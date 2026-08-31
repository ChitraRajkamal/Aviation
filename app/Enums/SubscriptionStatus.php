<?php

namespace App\Enums;

enum SubscriptionStatus: string {
    case Unpaid = 'unpaid';
    case Paid = 'paid';
    case Approved = 'approved';
    case Inactive = 'inactive';
}
