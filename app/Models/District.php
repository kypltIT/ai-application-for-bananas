<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class District extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'name_en',
        'full_name',
        'full_name_en',
        'latitude',
        'longitude',
    ];

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function wards()
    {
        return $this->hasMany(Ward::class);
    }
}
