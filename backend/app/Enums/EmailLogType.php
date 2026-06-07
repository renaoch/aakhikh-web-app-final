<?php

namespace App\Enums;

enum EmailLogType: string
{
    case DAILY_BREAD = 'daily_bread';
    case ANNOUNCEMENT = 'announcement';
    case WELCOME = 'welcome';
    case SUBSCRIPTION_CONFIRMATION = 'subscription_confirmation';

    public static function values(): array
    {
        return array_map(fn (self $type) => $type->value, self::cases());
    }
}