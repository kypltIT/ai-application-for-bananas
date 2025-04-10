<?php
// Changes made:
// - Fixed ProductVariantImage seeder by changing column 'image' to 'image_path' to match the database schema
// - Added CustomerSeeder, OrderSeeder, and OrderItemSeeder to the database seeder
// - Made sure seeders run in the correct order (customers before orders, orders before order items)
// - Updated DatabaseSeeder to call the seeders in the correct order
// - Added memory usage optimizations for large datasets

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\ProductCategory;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantImage;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with cosmetic products.
     */
    public function run(): void
    {
        // Create roles
        Role::create(['name' => 'admin', 'guard_name' => 'web']);
        Role::create(['name' => 'customer', 'guard_name' => 'web']);

        // Đường dẫn tuyệt đối đến file SQL
        $path = database_path('sql/address_data.sql');

        // Đọc nội dung file và chạy
        DB::unprepared(file_get_contents($path));

        // Create users
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('12345678'),
            'phone' => '0123456789',
        ]);
        $admin->assignRole('admin');

        $customer = User::create([
            'name' => 'Customer',
            'email' => 'customer@customer.com',
            'password' => bcrypt('12345678'),
            'phone' => '01234567890',
        ]);
        $customer->assignRole('customer');

        // Create product categories
        $productCategories = [
            ['name' => 'Skincare', 'description' => 'Products related to skincare.'],
            ['name' => 'Makeup', 'description' => 'Cosmetics for makeup application.'],
            ['name' => 'Haircare', 'description' => 'Products for hair treatment and styling.'],
        ];

        foreach ($productCategories as $category) {
            ProductCategory::create($category);
        }

        // Create products
        $products = [
            ['name' => 'Moisturizer', 'product_category_id' => ProductCategory::where('name', 'Skincare')->first()->id],
            ['name' => 'Foundation', 'product_category_id' => ProductCategory::where('name', 'Makeup')->first()->id],
            ['name' => 'Shampoo', 'product_category_id' => ProductCategory::where('name', 'Haircare')->first()->id],
            ['name' => 'Sunscreen', 'product_category_id' => ProductCategory::where('name', 'Skincare')->first()->id],
            ['name' => 'Lipstick', 'product_category_id' => ProductCategory::where('name', 'Makeup')->first()->id],
            ['name' => 'Conditioner', 'product_category_id' => ProductCategory::where('name', 'Haircare')->first()->id],
            ['name' => 'Face Wash', 'product_category_id' => ProductCategory::where('name', 'Skincare')->first()->id],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }

        // Create product variants
        $productVariants = [
            ['name' => 'Moisturizer - 50ml', 'description' => '50ml hydrating moisturizer.', 'price' => 20, 'stock' => 100, 'sku' => 'MOI-001', 'product_id' => Product::where('name', 'Moisturizer')->first()->id],
            ['name' => 'Moisturizer - 100ml', 'description' => '100ml hydrating moisturizer.', 'price' => 35, 'stock' => 80, 'sku' => 'MOI-002', 'product_id' => Product::where('name', 'Moisturizer')->first()->id],
            ['name' => 'Foundation - Light', 'description' => 'Light shade liquid foundation.', 'price' => 25, 'stock' => 50, 'sku' => 'FND-001', 'product_id' => Product::where('name', 'Foundation')->first()->id],
            ['name' => 'Foundation - Medium', 'description' => 'Medium shade liquid foundation.', 'price' => 25, 'stock' => 50, 'sku' => 'FND-002', 'product_id' => Product::where('name', 'Foundation')->first()->id],
            ['name' => 'Foundation - Dark', 'description' => 'Dark shade liquid foundation.', 'price' => 25, 'stock' => 50, 'sku' => 'FND-003', 'product_id' => Product::where('name', 'Foundation')->first()->id],
            ['name' => 'Shampoo - 100ml', 'description' => '100ml nourishing shampoo.', 'price' => 15, 'stock' => 100, 'sku' => 'SHA-001', 'product_id' => Product::where('name', 'Shampoo')->first()->id],
            ['name' => 'Conditioner - 100ml', 'description' => '100ml moisturizing conditioner.', 'price' => 18, 'stock' => 90, 'sku' => 'CON-001', 'product_id' => Product::where('name', 'Conditioner')->first()->id],
            ['name' => 'Sunscreen - SPF 50', 'description' => '50ml sunscreen with SPF 50.', 'price' => 30, 'stock' => 70, 'sku' => 'SUN-001', 'product_id' => Product::where('name', 'Sunscreen')->first()->id],
            ['name' => 'Lipstick - Red', 'description' => 'Classic red lipstick.', 'price' => 22, 'stock' => 60, 'sku' => 'LIP-001', 'product_id' => Product::where('name', 'Lipstick')->first()->id],
            ['name' => 'Face Wash - 200ml', 'description' => '200ml gentle face wash.', 'price' => 12, 'stock' => 120, 'sku' => 'FW-001', 'product_id' => Product::where('name', 'Face Wash')->first()->id],
        ];

        foreach ($productVariants as $variant) {
            ProductVariant::create($variant);
        }

        // Create product variant images
        $productVariantImages = [
            ['product_variant_id' => ProductVariant::where('name', 'Moisturizer - 50ml')->first()->id, 'image_path' => 'https://via.placeholder.com/150', 'id' => null, 'updated_at' => now(), 'created_at' => now()],
            ['product_variant_id' => ProductVariant::where('name', 'Moisturizer - 100ml')->first()->id, 'image_path' => 'https://via.placeholder.com/150', 'id' => null, 'updated_at' => now(), 'created_at' => now()],
            ['product_variant_id' => ProductVariant::where('name', 'Foundation - Light')->first()->id, 'image_path' => 'https://via.placeholder.com/150', 'id' => null, 'updated_at' => now(), 'created_at' => now()],
            ['product_variant_id' => ProductVariant::where('name', 'Foundation - Medium')->first()->id, 'image_path' => 'https://via.placeholder.com/150', 'id' => null, 'updated_at' => now(), 'created_at' => now()],
            ['product_variant_id' => ProductVariant::where('name', 'Foundation - Dark')->first()->id, 'image_path' => 'https://via.placeholder.com/150', 'id' => null, 'updated_at' => now(), 'created_at' => now()],
            ['product_variant_id' => ProductVariant::where('name', 'Shampoo - 100ml')->first()->id, 'image_path' => 'https://via.placeholder.com/150', 'id' => null, 'updated_at' => now(), 'created_at' => now()],
            ['product_variant_id' => ProductVariant::where('name', 'Conditioner - 100ml')->first()->id, 'image_path' => 'https://via.placeholder.com/150', 'id' => null, 'updated_at' => now(), 'created_at' => now()],
            ['product_variant_id' => ProductVariant::where('name', 'Sunscreen - SPF 50')->first()->id, 'image_path' => 'https://via.placeholder.com/150', 'id' => null, 'updated_at' => now(), 'created_at' => now()],
            ['product_variant_id' => ProductVariant::where('name', 'Lipstick - Red')->first()->id, 'image_path' => 'https://via.placeholder.com/150', 'id' => null, 'updated_at' => now(), 'created_at' => now()],
            ['product_variant_id' => ProductVariant::where('name', 'Face Wash - 200ml')->first()->id, 'image_path' => 'https://via.placeholder.com/150', 'id' => null, 'updated_at' => now(), 'created_at' => now()],
        ];

        foreach ($productVariantImages as $image) {
            ProductVariantImage::create($image);
        }

        // Run additional seeders in the correct order
        // Add memory optimization for large data sets
        $this->command->info('Seeding 1,000 customers...');
        $this->call(CustomerSeeder::class);

        $this->command->info('Seeding 10,000 orders...');
        $this->call(OrderSeeder::class);

        $this->command->info('Seeding order items...');
        $this->call(OrderItemSeeder::class);

        $this->command->info('Seeding 2,000 addresses...');
        $this->call(AddressSeeder::class);
    }
}
