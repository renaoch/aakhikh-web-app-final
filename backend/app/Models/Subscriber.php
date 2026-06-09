<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    use HasFactory;
public $incrementing = false;
protected $keyType = 'string';
    protected $fillable = ['email', 'name', 'status', 'token'];

    protected function casts(): array
    {
        return ['status' => 'string'];
    }

    public function scopeActive(Builder $q): Builder
    {
        return $q->where('status', 'subscribed');
    }

    public function scopeBounced(Builder $q): Builder
    {
        return $q->where('status', 'bounced');
    }
}
