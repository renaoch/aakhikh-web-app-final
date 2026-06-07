<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasUuids, SoftDeletes;

    protected $fillable = [
        'supabase_uid',
        'name',
        'email',
        'phone',
        'avatar_url',
        'bio',
        'role',
        'is_active',
        'last_login_at',
    ];

    protected $hidden = [
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'role'              => UserRole::class,
            'is_active'         => 'boolean',
            'email_verified_at' => 'datetime',
            'last_login_at'     => 'datetime',
        ];
    }

    public function hasRole(UserRole ...$roles): bool
    {
        return in_array($this->role, $roles, true);
    }

    public function isAdmin(): bool
    {
        return $this->role?->canAccessAdmin() ?? false;
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === UserRole::SUPER_ADMIN;
    }

    // Relationships
    public function orders() { return $this->hasMany(Order::class); }
    public function roleAuditLogs() { return $this->hasMany(RoleAuditLog::class); }
}