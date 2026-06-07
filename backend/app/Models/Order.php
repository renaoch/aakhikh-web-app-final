<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number', 'name', 'email', 'phone',
        'address', 'city', 'state', 'pincode',
        'total_amount', 'status',
        'razorpay_order_id', 'payment_id', 'payment_signature',
    ];

    protected function casts(): array
    {
        return [
            'total_amount' => 'decimal:2',
            'status'       => OrderStatus::class,
        ];
    }

    /* ── Relationships ─────────────────────────────────────── */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /* ── Scopes ─────────────────────────────────────────────── */
    public function scopeByStatus(Builder $q, OrderStatus $status): Builder
    {
        return $q->where('status', $status);
    }

    public function scopePaid(Builder $q): Builder
    {
        return $q->where('status', OrderStatus::Paid);
    }

    /* ── Helpers ─────────────────────────────────────────────── */
    public static function generateOrderNumber(): string
    {
        return 'ORD-' . strtoupper(uniqid());
    }
}
