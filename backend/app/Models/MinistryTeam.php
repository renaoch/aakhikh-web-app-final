<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MinistryTeam extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'image', 'order', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'order'     => 'integer',
        ];
    }

    /* ── Relationships ─────────────────────────────────────── */
    public function members()
    {
        return $this->hasMany(TeamMember::class)->orderBy('order');
    }

    public function activeMembers()
    {
        return $this->hasMany(TeamMember::class)->where('is_active', true)->orderBy('order');
    }

    /* ── Scopes ─────────────────────────────────────────────── */
    public function scopeActive(Builder $q): Builder
    {
        return $q->where('is_active', true)->orderBy('order');
    }
}
