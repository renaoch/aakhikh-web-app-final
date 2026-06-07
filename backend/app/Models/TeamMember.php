<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TeamMember extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'team_id', 'name', 'role', 'photo_url', 'email', 'display_order',
    ];

    public function team() { return $this->belongsTo(MinistryTeam::class, 'team_id'); }
}