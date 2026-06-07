<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sermon extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'youtube_video_id', 'title', 'speaker', 'topic',
        'description', 'thumbnail_url', 'published_at',
        'is_featured', 'view_count',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_featured'  => 'boolean',
        'view_count'   => 'integer',
    ];
}