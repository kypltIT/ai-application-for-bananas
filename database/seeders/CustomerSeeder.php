<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('vi_VN');
        $customers = [];

        // Create 1,000 customers in batches for better performance
        for ($i = 0; $i < 1000; $i++) {
            $customers[] = [
                'id' => \Illuminate\Support\Str::uuid()->toString(),
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'phone' => $faker->unique()->numerify('0#########'),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Insert in batches of 100
            if (count($customers) === 100) {
                DB::table('customers')->insert($customers);
                $customers = [];
            }
        }

        // Insert any remaining customers
        if (count($customers) > 0) {
            DB::table('customers')->insert($customers);
        }
    }
}
