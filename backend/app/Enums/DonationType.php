<?php

namespace App\Enums;

enum DonationType: string
{
    case ONE_TIME = 'one_time';
    case RECURRING = 'recurring';

    public static function values(): array
    {
        return array_map(fn (self $type) => $type->value, self::cases());
    }
}