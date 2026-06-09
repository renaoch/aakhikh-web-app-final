<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sermon extends Model
{
    use HasFactory, HasUuids;
public $incrementing = false;
protected $keyType = 'string';
    protected $fillable = [
        'title', 'description', 'speaker', 'series',
        'preached_at', 'youtube_url', 'thumbnail',
        'audio_url', 'is_published',
    ];

    protected function casts(): array
    {
        return [
            'preached_at'  => 'date',
            'is_published' => 'boolean',
        ];
    }

    /* ── Scopes ─────────────────────────────────────────────── */
    public function scopePublished(Builder $q): Builder
    {
        return $q->where('is_published', true);
    }

    public function scopeLatest(Builder $q): Builder
    {
        return $q->orderByDesc('preached_at');
    }

    public function scopeBySeries(Builder $q, string $series): Builder
    {
        return $q->where('series', $series);
    }
}
