<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'type', 'subject', 'recipient_count',
        'sent_at', 'ses_message_id', 'open_count', 'bounce_count',
    ];

    protected $casts = [
        'sent_at'        => 'datetime',
        'recipient_count' => 'integer',
        'open_count'     => 'integer',
        'bounce_count'   => 'integer',
    ];
}