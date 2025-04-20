<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Address extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'order_id',
        'name',
        'phone',
        'city',
        'district',
        'ward',
        'address',
        'is_default',
        'customer_id',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'district');
    }

    public function ward()
    {
        return $this->belongsTo(Ward::class);
    }
    // Getter để lấy tên thay vì ID
    public function getCityNameAttribute()
    {
        return City::where('id', $this->city)->value('full_name_en');
    }

    public function getDistrictNameAttribute()
    {
        return District::where('id', $this->district)->value('full_name_en');
    }

    public function getWardNameAttribute()
    {
        return Ward::where('id', $this->ward)->value('full_name_en');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
