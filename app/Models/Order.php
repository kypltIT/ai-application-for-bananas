<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasUuids, HasFactory;
    protected $fillable = ['customer_id', 'total_price', 'status', 'order_code', 'order_note'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public static function generateOrderCode()
    {
        return 'BIHA' . Str::random(10);
    }

    public function address()
    {
        return $this->hasOne(Address::class);
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }
}
