<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Seeder;

class TotalPriceOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Process orders in chunks to reduce memory usage
        Order::chunk(200, function ($orders) {
            foreach ($orders as $order) {
                // Calculate total price based on order items
                $totalPrice = OrderItem::where('order_id', $order->id)
                    ->join('product_variants', 'order_items.product_variant_id', '=', 'product_variants.id')
                    ->selectRaw('SUM(order_items.quantity * product_variants.price) as total')
                    ->value('total');

                // Update the order's total price
                $order->update(['total_price' => $totalPrice]);
            }
        });
    }
}
