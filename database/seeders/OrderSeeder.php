<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Order;
use App\Models\City;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    /**
     * Changes made:
     * - Added address creation for each order
     * - Added city, district, ward data for addresses
     * - Integrated address creation with order creation
     */
    public function run(): void
    {
        $faker = Faker::create('vi_VN');

        // Get all customer IDs
        $customerIds = Customer::pluck('id')->toArray();

        // Get city data and preload districts/wards relationships
        $cities = City::with(['districts.wards'])->get();

        // Define date range for 2024-2025
        $startDate = Carbon::createFromDate(2023, 1, 1)->startOfDay();
        $endDate = Carbon::createFromDate(2025, 4, 21)->endOfDay();

        // Create orders in batches for better performance
        $batchSize = 500;
        for ($b = 0; $b < 3; $b++) {
            $orders = [];
            $addresses = [];

            for ($i = 0; $i < $batchSize; $i++) {
                // Generate a random date within 2024-2025
                $createdAt = $faker->dateTimeBetween($startDate, $endDate);
                $formattedDate = $createdAt->format('Y-m-d H:i:s');

                $customerId = $faker->randomElement($customerIds);
                $customer = Customer::find($customerId);

                // Create order
                $orderId = Str::uuid()->toString();
                $orders[] = [
                    'id' => $orderId,
                    'customer_id' => $customerId,
                    'total_price' => 0, // Will be updated in OrderItemSeeder
                    'status' => $faker->randomElement(['ordered', 'processing', 'completed', 'cancelled']),
                    'order_code' => 'BANANAS' . Str::random(10),
                    'order_note' => $faker->optional(0.7)->sentence,
                    'created_at' => $formattedDate,
                    'updated_at' => $formattedDate,
                ];

                // Get random location data
                $city = $faker->randomElement($cities);
                if (!$city->districts->isEmpty()) {
                    $district = $faker->randomElement($city->districts->all());
                    if (!$district->wards->isEmpty()) {
                        $ward = $faker->randomElement($district->wards->all());

                        // Create address for this order
                        $addresses[] = [
                            'id' => Str::uuid()->toString(),
                            'order_id' => $orderId,
                            'customer_id' => $customerId,
                            'name' => $customer->name,
                            'phone' => $customer->phone,
                            'city' => $city->id,
                            'district' => $district->id,
                            'ward' => $ward->id,
                            'address' => $faker->streetAddress,
                            'is_default' => $faker->boolean(20), // 20% chance to be default
                            'created_at' => $formattedDate,
                            'updated_at' => $formattedDate,
                        ];
                    }
                }
            }

            // Insert orders and addresses in batches
            DB::table('orders')->insert($orders);
            if (!empty($addresses)) {
                DB::table('addresses')->insert($addresses);
            }
        }
    }
}
