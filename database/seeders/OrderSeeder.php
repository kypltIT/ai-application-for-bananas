<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Get all customer IDs
        $customerIds = Customer::pluck('id')->toArray();

        // Define date range for 2024-2025
        $startDate = Carbon::createFromDate(2023, 1, 1)->startOfDay();
        $endDate = Carbon::createFromDate(2025, 4, 21)->endOfDay();

        // Create 10,000 orders in batches for better performance
        $batchSize = 500;
        for ($b = 0; $b < 3; $b++) {
            $orders = [];

            for ($i = 0; $i < $batchSize; $i++) {
                // Generate a random date within 2024-2025
                $createdAt = $faker->dateTimeBetween($startDate, $endDate);
                $formattedDate = $createdAt->format('Y-m-d H:i:s');

                $orders[] = [
                    'id' => Str::uuid()->toString(),
                    'customer_id' => $faker->randomElement($customerIds),
                    'total_price' => $faker->numberBetween(50000, 5000000),
                    'status' => $faker->randomElement(['ordered', 'processing', 'completed', 'cancelled']),
                    'order_code' => 'BANANAS' . Str::random(10),
                    'order_note' => $faker->optional(0.7)->sentence,
                    'created_at' => $formattedDate,
                    'updated_at' => $formattedDate,
                ];
            }

            DB::table('orders')->insert($orders);
        }
    }
}
