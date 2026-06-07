<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Donation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'donor_name', 'donor_email', 'amount', 'type',
        'category', 'razorpay_payment_id', 'razorpay_order_id', 'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];
}