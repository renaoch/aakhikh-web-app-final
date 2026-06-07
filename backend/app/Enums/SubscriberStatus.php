<?php

namespace App\Enums;

enum SubscriberStatus: string
{
    case PENDING_CONFIRMATION = 'pending_confirmation';
    case ACTIVE = 'active';
    case UNSUBSCRIBED = 'unsubscribed';
    case BOUNCED = 'bounced';
    case COMPLAINED = 'complained';

    public static function values(): array
    {
        return array_map(fn (self $status) => $status->value, self::cases());
    }
}