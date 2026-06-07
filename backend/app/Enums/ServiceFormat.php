<?php

namespace App\Enums;

enum ServiceFormat: string
{
    case IN_PERSON = 'in_person';
    case ONLINE = 'online';
    case HYBRID = 'hybrid';

    public static function values(): array
    {
        return array_map(fn (self $format) => $format->value, self::cases());
    }
}