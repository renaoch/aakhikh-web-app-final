<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'avatar', 'phone',
        'is_active', 'last_login_at',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'role'          => UserRole::class,
            'is_active'     => 'boolean',
            'last_login_at' => 'datetime',
            'password'      => 'hashed',
        ];
    }

    /* ── Relationships ─────────────────────────────────────── */
    public function roleAuditLogs()
    {
        return $this->hasMany(RoleAuditLog::class);
    }

    /* ── Helpers ────────────────────────────────────────────── */
    public function isAdmin(): bool   { return $this->role === UserRole::Admin; }
    public function isEditor(): bool  { return $this->role === UserRole::Editor; }
    public function hasRole(UserRole $role): bool { return $this->role === $role; }
}
