<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DailyBread extends Model
{
    
    use HasFactory, SoftDeletes;
public $incrementing = false;
protected $keyType = 'string';
    protected $fillable = [
        'title',
        'body',
        'bible_reference',
        'image_url',
        'published_date',
        'scheduled_sent_at',
        'is_published',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'published_date' => 'date',
            'scheduled_sent_at' => 'datetime',
            'is_published' => 'boolean',
        ];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function scopeForDate(Builder $query, string $date): Builder
    {
        return $query->whereDate('published_date', $date);
    }
}