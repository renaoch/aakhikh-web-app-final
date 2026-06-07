<?php

namespace App\Enums;

enum DonationCategory: string
{
    case GENERAL = 'general';
    case TITHE = 'tithe';
    case MISSION = 'mission';
    case BUILDING = 'building';
    case YOUTH = 'youth';
    case OTHER = 'other';

    public static function values(): array
    {
        return array_map(fn (self $category) => $category->value, self::cases());
    }
}