<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('cart_id')->constrained('carts')->references('id')->on('carts')->onDelete('cascade');
            $table->foreignUuid('product_id')->constrained('products')->references('id')->on('products')->onDelete('cascade');
            $table->foreignUuid('product_variant_id')->constrained('product_variants')->references('id')->on('product_variants')->onDelete('cascade');
            $table->integer('quantity');
            $table->timestamps();

            $table->unique(['cart_id', 'product_id', 'product_variant_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
