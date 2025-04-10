<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Session;
use App\Models\CartItem;

class Cart extends Model
{
    use HasUuids, HasFactory;
    protected $fillable = ['session_id'];

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function session()
    {
        return $this->belongsTo(Session::class);
    }
}
