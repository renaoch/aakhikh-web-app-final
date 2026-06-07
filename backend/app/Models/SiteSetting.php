<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value', 'updated_by'];

    public function updatedBy() { return $this->belongsTo(User::class, 'updated_by'); }
}