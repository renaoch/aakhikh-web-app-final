<?php

namespace App\Enums;

enum TestimonialStatus: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';

    public static function values(): array
    {
        return array_map(fn (self $status) => $status->value, self::cases());
    }
}