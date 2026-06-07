<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids;

    protected $table = 'users';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'name',
        'email',
        'role',
        'phone',
        'avatar_url',
        'bio',
        'email_verified_at',
        'last_login_at',
        'is_active',
        'deleted_at',
    ];

    protected $hidden = [
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'role' => UserRole::class,
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'is_active' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === UserRole::SUPER_ADMIN;
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRole::ADMIN;
    }

    public function isEditor(): bool
    {
        return $this->role === UserRole::EDITOR;
    }

    public function isMedia(): bool
    {
        return $this->role === UserRole::MEDIA;
    }

    public function isMember(): bool
    {
        return $this->role === UserRole::MEMBER;
    }

    public function canAccessAdmin(): bool
    {
        return $this->role?->canAccessAdmin() ?? false;
    }

    public function sermons(): HasMany
    {
        return $this->hasMany(Sermon::class, 'created_by');
    }

    public function dailyBreads(): HasMany
    {
        return $this->hasMany(DailyBread::class, 'created_by');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class);
    }

    public function submittedTestimonials(): HasMany
    {
        return $this->hasMany(Testimonial::class, 'submitted_by');
    }

    public function reviewedTestimonials(): HasMany
    {
        return $this->hasMany(Testimonial::class, 'reviewed_by');
    }
}