<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleAuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'changed_by', 'old_role', 'new_role', 'changed_at',
    ];

    protected $casts = [
        'changed_at' => 'datetime',
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function changedBy() { return $this->belongsTo(User::class, 'changed_by'); }
}