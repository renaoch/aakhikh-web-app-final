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

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'supabase_uid',
        'name',
        'email',
        'phone',
        'avatar_url',
        'bio',
        'role',
        'is_active',
        'email_verified_at',
        'last_login_at',
        'remember_token',
    ];

    protected $hidden = [
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'role' => UserRole::class,
            'is_active' => 'boolean',
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
        ];
    }

    public function roleAuditLogs()
    {
        return $this->hasMany(RoleAuditLog::class);
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, [UserRole::SUPER_ADMIN, UserRole::ADMIN], true);
    }

    public function isEditor(): bool
    {
        return $this->role === UserRole::EDITOR;
    }

    public function hasRole(UserRole $role): bool
    {
        return $this->role === $role;
    }
}