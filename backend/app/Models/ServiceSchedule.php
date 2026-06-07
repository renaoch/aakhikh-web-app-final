<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'day_of_week', 'time', 'location', 'format', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}