<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'ministry_team_id', 'name', 'role',
        'avatar', 'bio', 'order', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'order'     => 'integer',
        ];
    }

    /* ── Relationships ─────────────────────────────────────── */
    public function team()
    {
        return $this->belongsTo(MinistryTeam::class, 'ministry_team_id');
    }

    /* ── Scopes ─────────────────────────────────────────────── */
    public function scopeActive(Builder $q): Builder
    {
        return $q->where('is_active', true)->orderBy('order');
    }
}
