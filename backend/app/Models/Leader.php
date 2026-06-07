<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Leader extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'role', 'bio', 'photo_url', 'display_order', 'category',
    ];
}