<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Get all product variants
        $productVariants = ProductVariant::all();

        // Process orders in batches to reduce memory usage
        Order::chunk(200, function ($orders) use ($faker, $productVariants) {
            foreach ($orders as $order) {
                $orderItems = [];
                $totalPrice = 0;
                $selectedVariants = $faker->randomElements($productVariants->toArray(), $faker->numberBetween(1, 5));

                foreach ($selectedVariants as $variant) {
                    $quantity = $faker->numberBetween(1, 5);
                    $totalPrice += $variant['price'] * $quantity;

                    $orderItems[] = [
                        'id' => Str::uuid()->toString(),
                        'order_id' => $order->id,
                        'product_id' => $variant['product_id'],
                        'product_variant_id' => $variant['id'],
                        'quantity' => $quantity,
                        'created_at' => $order->created_at,
                        'updated_at' => $order->updated_at,
                    ];
                }

                // Insert order items
                DB::table('order_items')->insert($orderItems);

                // Update order's total_price
                DB::table('orders')
                    ->where('id', $order->id)
                    ->update(['total_price' => $totalPrice]);
            }
        });
    }
}
