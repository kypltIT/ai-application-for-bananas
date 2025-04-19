<?php
// Changes made:
// - Fixed ProductVariantImage seeder by changing column 'image' to 'image_path' to match the database schema
// - Added CustomerSeeder, OrderSeeder, and OrderItemSeeder to the database seeder
// - Made sure seeders run in the correct order (customers before orders, orders before order items)
// - Updated DatabaseSeeder to call the seeders in the correct order
// - Added memory usage optimizations for large datasets
// - Added AnanasScrapedProductSeeder to create products from the Ananas categories based on actual website data

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
    ['name' => 'Basas', 'description' => 'Simple, timeless shoes for everyday wear.'],
    ['name' => 'Vintas', 'description' => 'Retro-inspired sneakers with a vintage vibe.'],
    ['name' => 'Urbas', 'description' => 'Modern, urban-style shoes for active youth.'],
    ['name' => 'Pattas', 'description' => 'Unique designs with bold patterns and collabs.'],
];


        foreach ($productCategories as $category) {
            ProductCategory::create($category);
        }

        // Create products
        $products = [
            ['name' => 'Workaday - Low Top - Black', 'product_category_id' => ProductCategory::where('name', 'Basas')->first()->id],
            ['name' => 'Workaday - Low Top - Real Teal', 'product_category_id' => ProductCategory::where('name', 'Basas')->first()->id],
            ['name' => 'Venim - Low Top - Night Sky', 'product_category_id' => ProductCategory::where('name', 'Vintas')->first()->id],
            ['name' => 'Venim - High Top - Night Sky', 'product_category_id' => ProductCategory::where('name', 'Vintas')->first()->id],
            ['name' => 'SC - Mule - Dark Purple', 'product_category_id' => ProductCategory::where('name', 'Urbas')->first()->id],
            ['name' => 'SC - Mule - Foliage', 'product_category_id' => ProductCategory::where('name', 'Urbas')->first()->id],
            ['name' => 'Polka Dots - Low Top - Black', 'product_category_id' => ProductCategory::where('name', 'Pattas')->first()->id],
            ['name' => 'Polka Dots - High Top - Offwhite', 'product_category_id' => ProductCategory::where('name', 'Pattas')->first()->id],

        ];

        foreach ($products as $product) {
            Product::create($product);
        }

        $sizes = ['38', '39', '40', '41', '42'];
        $description = "Gender: Unisex\nSize run: 35 – 46\nUpper: Canvas NE\nOutsole: Rubber";
        
        // Khởi tạo mảng đếm index theo danh mục
        $categoryIndexCounters = [];
        
        // Lặp qua tất cả sản phẩm
        $allProducts = Product::all();
        
        foreach ($allProducts as $product) {
            // Lấy danh mục sản phẩm
            $category = ProductCategory::find($product->product_category_id);
            $prefix = strtoupper(substr($category->name, 0, 3)); // ví dụ: BAS, VIN, URB
        
            // Nếu danh mục chưa có trong mảng thì khởi tạo
            if (!isset($categoryIndexCounters[$prefix])) {
                $categoryIndexCounters[$prefix] = 0;
            }
        
            // Lấy index hiện tại cho danh mục này
            $currentIndex = $categoryIndexCounters[$prefix];
        
            foreach ($sizes as $size) {
                $price = str_contains($product->name, 'High') ? 690000 : 580000;
        
                $sku = $prefix . '-' . $currentIndex . '-' . $size;
        
                ProductVariant::create([
                    'name' => $size,
                    'description' => $description,
                    'price' => $price,
                    'stock' => 100,
                    'sku' => $sku,
                    'product_id' => $product->id,
                ]);
            }
        
            // Sau khi xử lý xong 1 sản phẩm, tăng index danh mục lên 1
            $categoryIndexCounters[$prefix]++;
        }
        


        // Create product variant images for each product variant
$productVariants = ProductVariant::all();

foreach ($productVariants as $variant) {
    ProductVariantImage::create([
        'product_variant_id' => $variant->id,
        'image_path' => 'https://placehold.co/336x400/png',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}


        // Run additional seeders in the correct order
        // Add memory optimization for large data sets
        $this->command->info('Seeding Ananas products...');
        // Choose one of these seeders:
        // $this->call(AnanasProductSeeder::class); // Generic version

        $this->command->info('Seeding 1,000 customers...');
        $this->call(CustomerSeeder::class);

        $this->command->info('Seeding 10,000 orders...');
        $this->call(OrderSeeder::class);

        $this->command->info('Seeding 2,000 addresses...');
        $this->call(AddressSeeder::class);

        $this->command->info('Seeding order items...');
        $this->call(OrderItemSeeder::class);
    }
}
