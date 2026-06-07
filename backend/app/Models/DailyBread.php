<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyBread extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'verse', 'verse_reference',
        'content', 'author', 'published_at', 'is_published',
    ];

    protected function casts(): array
    {
        return [
            'published_at'  => 'date',
            'is_published'  => 'boolean',
        ];
    }

    public function scopePublished(Builder $q): Builder
    {
        return $q->where('is_published', true);
    }

    public function scopeForDate(Builder $q, string $date): Builder
    {
        return $q->whereDate('published_at', $date);
    }
}
