<?php

namespace App\Enums;

enum LeaderCategory: string
{
    case PASTOR = 'pastor';
    case ELDER = 'elder';
    case DEACON = 'deacon';
    case STAFF = 'staff';
    case MINISTRY_HEAD = 'ministry_head';

    public static function values(): array
    {
        return array_map(fn (self $category) => $category->value, self::cases());
    }
}