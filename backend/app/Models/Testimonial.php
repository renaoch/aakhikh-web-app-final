<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Testimonial extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'author_name', 'content', 'status', 'submitted_at', 'reviewed_by',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    public function reviewer() { return $this->belongsTo(User::class, 'reviewed_by'); }
}