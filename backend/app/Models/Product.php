<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
public $incrementing = false;
protected $keyType = 'string';
    protected $fillable = [
        'name', 'description', 'price', 'stock',
        'image', 'category', 'sku', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price'     => 'decimal:2',
            'stock'     => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /* ── Relationships ─────────────────────────────────────── */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /* ── Scopes ─────────────────────────────────────────────── */
    public function scopeActive(Builder $q): Builder
    {
        return $q->where('is_active', true);
    }

    public function scopeInStock(Builder $q): Builder
    {
        return $q->where('stock', '>', 0);
    }

    public function scopeByCategory(Builder $q, string $category): Builder
    {
        return $q->where('category', $category);
    }

    /* ── Helpers ─────────────────────────────────────────────── */
    public function isInStock(): bool { return $this->stock > 0; }
}
