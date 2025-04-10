<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Session extends Model
{
    use HasUuids, HasFactory;
    protected $fillable = ['id', 'user_id', 'ip_address', 'user_agent', 'payload', 'last_activity'];

    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
