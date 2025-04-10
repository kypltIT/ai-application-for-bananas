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

        // Get all product variants with their product IDs
        $productVariants = ProductVariant::with('product')->get();

        // Process orders in batches to reduce memory usage
        Order::chunk(200, function ($orders) use ($faker, $productVariants) {
            $orderItems = [];

            foreach ($orders as $order) {
                $itemCount = $faker->numberBetween(1, 5);
                $selectedVariants = $faker->randomElements($productVariants->toArray(), min($itemCount, count($productVariants)));

                foreach ($selectedVariants as $variant) {
                    $orderItems[] = [
                        'id' => Str::uuid()->toString(),
                        'order_id' => $order->id,
                        'product_id' => $variant['product_id'],
                        'product_variant_id' => $variant['id'],
                        'quantity' => $faker->numberBetween(1, 5),
                        'created_at' => $order->created_at,
                        'updated_at' => $order->updated_at,
                    ];

                    // Insert in batches of 100
                    if (count($orderItems) >= 100) {
                        DB::table('order_items')->insert($orderItems);
                        $orderItems = [];
                    }
                }
            }

            // Insert any remaining order items
            if (count($orderItems) > 0) {
                DB::table('order_items')->insert($orderItems);
            }
        });
    }
}
