<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'description', 'price', 'stock_qty', 'images', 'is_active', 'category',
    ];

    protected $casts = [
        'price'     => 'decimal:2',
        'images'    => 'array',
        'is_active' => 'boolean',
        'stock_qty' => 'integer',
    ];

    public function orderItems() { return $this->hasMany(OrderItem::class); }
}