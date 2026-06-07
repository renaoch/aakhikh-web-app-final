<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DailyBread extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title', 'body', 'bible_reference', 'image_url',
        'published_date', 'scheduled_sent_at', 'created_by',
    ];

    protected $casts = [
        'published_date'   => 'date',
        'scheduled_sent_at' => 'datetime',
    ];

    public function author() { return $this->belongsTo(User::class, 'created_by'); }
}