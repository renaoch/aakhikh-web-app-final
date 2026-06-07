<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Announcement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title', 'body', 'is_active', 'published_at', 'created_by',
    ];

    protected $casts = [
        'is_active'    => 'boolean',
        'published_at' => 'datetime',
    ];

    public function author() { return $this->belongsTo(User::class, 'created_by'); }
}