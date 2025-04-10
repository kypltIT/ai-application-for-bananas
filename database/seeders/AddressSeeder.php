<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\City;
use App\Models\District;
use App\Models\Order;
use App\Models\Ward;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('vi_VN');

        // Get city data and preload districts/wards relationships to reduce database queries
        $cities = City::with(['districts.wards'])->get();

        // Get a random subset of 2,000 orders
        $orderIds = Order::inRandomOrder()->take(2000)->pluck('id', 'customer_id');

        // Prepare the addresses
        $addresses = [];
        $count = 0;

        foreach ($orderIds as $customerId => $orderId) {
            // Get a random city
            $city = $faker->randomElement($cities);

            if ($city->districts->isEmpty()) {
                continue; // Skip if city has no districts
            }

            // Get a random district from this city
            $district = $faker->randomElement($city->districts->all());

            if ($district->wards->isEmpty()) {
                continue; // Skip if district has no wards
            }

            // Get a random ward from this district
            $ward = $faker->randomElement($district->wards->all());

            // Get the order to use its timestamps and customer info
            $order = Order::with('customer')->find($orderId);

            // Create the address
            $addresses[] = [
                'id' => Str::uuid()->toString(),
                'order_id' => $orderId,
                'customer_id' => $customerId,
                'name' => $order->customer->name,
                'phone' => $order->customer->phone,
                'city' => $city->id,
                'district' => $district->id,
                'ward' => $ward->id,
                'address' => $faker->streetAddress,
                'is_default' => $faker->boolean(20), // 20% chance to be default
                'created_at' => $order->created_at,
                'updated_at' => $order->updated_at,
            ];

            $count++;

            // Insert in batches of 100
            if (count($addresses) === 100) {
                DB::table('addresses')->insert($addresses);
                $addresses = [];
            }

            // Stop after creating 2,000 addresses
            if ($count >= 2000) {
                break;
            }
        }

        // Insert any remaining addresses
        if (count($addresses) > 0) {
            DB::table('addresses')->insert($addresses);
        }
    }
}
