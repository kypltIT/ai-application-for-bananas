<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ProductVariant extends Model
{
    use HasFactory, HasUuids;
    protected $fillable = ['product_id', 'name', 'description', 'price', 'stock', 'sku'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function images()
    {
        return $this->hasMany(ProductVariantImage::class);
    }

    public function generateSku()
    {
        $this->sku = strtoupper(bin2hex(random_bytes(6)));
        $this->save();
    }

    public function productVariantImages()
    {
        return $this->hasMany(ProductVariantImage::class, 'product_variant_id', 'id');
    }
}
