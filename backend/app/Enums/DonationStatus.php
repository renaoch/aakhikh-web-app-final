<?php

namespace App\Enums;

enum DonationStatus: string
{
    case Pending = 'pending';
    case Paid    = 'paid';
    case Failed  = 'failed';
    case Refunded = 'refunded';
}
