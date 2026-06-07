<?php

namespace App\Enums;

enum UserRole: string
{
    case SUPER_ADMIN = 'super_admin';
    case ADMIN = 'admin';
    case EDITOR = 'editor';
    case MEDIA = 'media';
    case MEMBER = 'member';

    public function label(): string
    {
        return match ($this) {
            self::SUPER_ADMIN => 'Super Admin',
            self::ADMIN => 'Admin',
            self::EDITOR => 'Editor',
            self::MEDIA => 'Media Staff',
            self::MEMBER => 'Member',
        };
    }

    public function level(): int
    {
        return match ($this) {
            self::SUPER_ADMIN => 100,
            self::ADMIN => 80,
            self::EDITOR => 60,
            self::MEDIA => 40,
            self::MEMBER => 20,
        };
    }

    public function canAccessAdmin(): bool
    {
        return in_array($this, [
            self::SUPER_ADMIN,
            self::ADMIN,
            self::EDITOR,
            self::MEDIA,
        ], true);
    }

    public static function values(): array
    {
        return array_map(fn (self $role) => $role->value, self::cases());
    }
}