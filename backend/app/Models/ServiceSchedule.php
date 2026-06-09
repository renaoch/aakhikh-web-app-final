<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceSchedule extends Model
{
    use HasFactory, SoftDeletes;
public $incrementing = false;
protected $keyType = 'string';
    protected $fillable = [
        'title',
        'description',
        'day_of_week',
        'start_time',
        'end_time',
        'location',
        'livestream_url',
        'is_active',
        'format',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'day_of_week' => 'integer',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)
            ->orderBy('day_of_week')
            ->orderBy('start_time');
    }
}