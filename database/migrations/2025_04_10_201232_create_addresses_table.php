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


        Schema::create('addresses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('order_id');
            $table->uuid('customer_id');
            $table->string('name');
            $table->string('phone');
            $table->uuid('city')->nullable();
            $table->uuid('district')->nullable();
            $table->uuid('ward')->nullable();
            $table->string('address');
            $table->boolean('is_default')->default(false);
            $table->timestamps();
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('city')->references('id')->on('cities')->onDelete('set null');
            $table->foreign('district')->references('id')->on('districts')->onDelete('set null');
            $table->foreign('ward')->references('id')->on('wards')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
