<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Leader extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'role_title',
        'bio',
        'photo_url',
        'email',
        'display_order',
        'is_active',
        'category',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'display_order' => 'integer',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)
            ->orderBy('display_order');
    }
}