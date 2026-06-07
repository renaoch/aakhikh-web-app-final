<?php

namespace App\Enums;

enum OrderStatus: string
{
    case Pending  = 'pending';
    case Paid     = 'paid';
    case Failed   = 'failed';
    case Refunded = 'refunded';
    case Shipped  = 'shipped';
    case Delivered = 'delivered';
    case Cancelled = 'cancelled';
}
