<?php

namespace App\Models;

use App\Enums\DonationStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'phone', 'amount', 'purpose',
        'message', 'status', 'razorpay_order_id',
        'payment_id', 'payment_signature',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'status' => DonationStatus::class,
        ];
    }

    public function scopePaid(Builder $q): Builder
    {
        return $q->where('status', DonationStatus::Paid);
    }

    public function scopeByPurpose(Builder $q, string $purpose): Builder
    {
        return $q->where('purpose', $purpose);
    }
}
