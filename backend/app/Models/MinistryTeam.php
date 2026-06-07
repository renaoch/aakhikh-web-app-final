<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MinistryTeam extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'description', 'icon', 'display_order'];

    public function members() { return $this->hasMany(TeamMember::class, 'team_id'); }
}