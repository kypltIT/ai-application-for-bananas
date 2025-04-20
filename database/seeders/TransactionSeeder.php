<?php

namespace Database\Seeders;

use App\Models\Order;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransactionSeeder extends Seeder
{
    /**
     * Changes made:
     * - Created new TransactionSeeder
     * - Added transaction creation for each completed order
     * - Fixed payment_method to match ENUM values in database ('COD', 'Bank Transfer')
     * - Added status based on order status
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Process orders in batches to reduce memory usage
        Order::chunk(200, function ($orders) use ($faker) {
            $transactions = [];

            foreach ($orders as $order) {
                // Only create transactions for orders that are not cancelled
                if ($order->status !== 'cancelled') {
                    // Use only valid payment methods from the ENUM
                    $paymentMethod = $faker->randomElement(['COD', 'Bank Transfer']);

                    // Set transaction status based on order status
                    $status = match ($order->status) {
                        'completed' => 'paid',
                        'processing' => 'pending',
                        'ordered' => 'pending',
                        default => 'pending'
                    };

                    $transactions[] = [
                        'id' => Str::uuid()->toString(),
                        'customer_id' => $order->customer_id,
                        'order_id' => $order->id,
                        'total_price' => $order->total_price,
                        'status' => $status,
                        'transaction_code' => 'TRANS' . Str::random(10),
                        'payment_method' => $paymentMethod,
                        'created_at' => $order->created_at,
                        'updated_at' => $order->updated_at,
                    ];

                    // Insert in batches of 100
                    if (count($transactions) >= 100) {
                        DB::table('transactions')->insert($transactions);
                        $transactions = [];
                    }
                }
            }

            // Insert any remaining transactions
            if (count($transactions) > 0) {
                DB::table('transactions')->insert($transactions);
            }
        });
    }
}
