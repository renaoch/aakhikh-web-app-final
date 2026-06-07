<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    use HasFactory;

    protected $fillable = [
        'email', 'name', 'status', 'confirmed_at', 'token',
    ];

    protected $casts = [
        'confirmed_at' => 'datetime',
    ];
}