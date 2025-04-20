# Ananas Product Seeders

This directory contains seeders for populating the database with products from the Ananas footwear brand. 
The product data is based on the Ananas website (https://ananas.vn) and includes the four main product categories:
- Basas
- Vintas
- Urbas 
- Pattas

## Available Seeders

### AnanasProductSeeder
A more generic version that creates products based on the Ananas categories with some preset data.

### AnanasScrapedProductSeeder
A detailed version that directly maps products from the Ananas website, including accurate:
- Product names
- Descriptions
- Prices
- Images
- Color options
- Style variants

### AnanasSeeder
A standalone seeder that:
1. Removes any existing Ananas categories and products
2. Seeds new Ananas product data using the AnanasScrapedProductSeeder
3. Can be run independently without affecting other data in the database

## How to Use

### To seed all data (including Ananas products):
```
php artisan db:seed
```

### To seed only Ananas products:
```
php artisan db:seed --class=AnanasSeeder
```

### To choose between the two Ananas seeders:
Edit the `DatabaseSeeder.php` file and uncomment/comment the relevant lines:

```php
// Choose one of these seeders:
// $this->call(AnanasProductSeeder::class); // Generic version
$this->call(AnanasScrapedProductSeeder::class); // Version with direct mapping from Ananas website
```

## Data Structure

The seeders create:
1. A main category for footwear products
2. Four sub-categories for each Ananas line
3. Multiple product models within each category
4. Variants for each product (color, style)
5. Size variants (35-46) for each product variant
6. Images for all variants

## Notes

- Size availability varies by product, with less stock for extreme sizes (≤37 or ≥44)
- Each product has its unique SKU based on category, color, and size
- All images point to actual product images from the Ananas website 
