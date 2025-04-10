<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Transaction extends Model
{
    use HasUuids, HasFactory;
    protected $fillable = ['customer_id', 'order_id', 'total_price', 'status', 'transaction_code', 'payment_method'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public static function generateTransactionCode()
    {
        return 'BIHA' . Str::random(10);
    }
}
