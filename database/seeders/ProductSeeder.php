<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            // Electronics
            ['category' => 'Electronics', 'name' => 'iPhone 15 Pro', 'price' => 15999000, 'stock' => 50],
            ['category' => 'Electronics', 'name' => 'Samsung Galaxy S24', 'price' => 12999000, 'stock' => 45],
            ['category' => 'Electronics', 'name' => 'MacBook Pro M3', 'price' => 35999000, 'stock' => 20],
            ['category' => 'Electronics', 'name' => 'iPad Air', 'price' => 8999000, 'stock' => 30],
            ['category' => 'Electronics', 'name' => 'Sony WH-1000XM5', 'price' => 4999000, 'stock' => 60],
            ['category' => 'Electronics', 'name' => 'Apple Watch Series 9', 'price' => 6999000, 'stock' => 40],

            // Fashion
            ['category' => 'Fashion', 'name' => 'Nike Air Max 270', 'price' => 1899000, 'stock' => 100],
            ['category' => 'Fashion', 'name' => 'Adidas Ultraboost', 'price' => 2299000, 'stock' => 80],
            ['category' => 'Fashion', 'name' => 'Levi\'s 501 Jeans', 'price' => 899000, 'stock' => 120],
            ['category' => 'Fashion', 'name' => 'Zara Leather Jacket', 'price' => 1599000, 'stock' => 50],
            ['category' => 'Fashion', 'name' => 'H&M Cotton T-Shirt', 'price' => 199000, 'stock' => 200],

            // Home & Living
            ['category' => 'Home & Living', 'name' => 'IKEA Sofa Bed', 'price' => 4999000, 'stock' => 15],
            ['category' => 'Home & Living', 'name' => 'Coffee Table Oak', 'price' => 1299000, 'stock' => 25],
            ['category' => 'Home & Living', 'name' => 'LED Table Lamp', 'price' => 299000, 'stock' => 75],
            ['category' => 'Home & Living', 'name' => 'Kitchen Blender', 'price' => 599000, 'stock' => 50],

            // Beauty & Health
            ['category' => 'Beauty & Health', 'name' => 'Cetaphil Face Wash', 'price' => 189000, 'stock' => 150],
            ['category' => 'Beauty & Health', 'name' => 'The Ordinary Serum', 'price' => 299000, 'stock' => 100],
            ['category' => 'Beauty & Health', 'name' => 'Dyson Hair Dryer', 'price' => 5999000, 'stock' => 20],
            ['category' => 'Beauty & Health', 'name' => 'Fitbit Charge 6', 'price' => 2499000, 'stock' => 35],

            // Sports & Outdoor
            ['category' => 'Sports & Outdoor', 'name' => 'Yoga Mat Premium', 'price' => 299000, 'stock' => 100],
            ['category' => 'Sports & Outdoor', 'name' => 'Dumbbells Set 20kg', 'price' => 899000, 'stock' => 40],
            ['category' => 'Sports & Outdoor', 'name' => 'Camping Tent 4 Person', 'price' => 1599000, 'stock' => 25],
            ['category' => 'Sports & Outdoor', 'name' => 'Mountain Bike 27.5"', 'price' => 4999000, 'stock' => 15],

            // Books & Stationery
            ['category' => 'Books & Stationery', 'name' => 'Atomic Habits Book', 'price' => 149000, 'stock' => 200],
            ['category' => 'Books & Stationery', 'name' => 'Moleskine Notebook', 'price' => 199000, 'stock' => 150],
            ['category' => 'Books & Stationery', 'name' => 'Pilot G2 Pen Set', 'price' => 89000, 'stock' => 300],

            // Toys & Games
            ['category' => 'Toys & Games', 'name' => 'LEGO Star Wars Set', 'price' => 1299000, 'stock' => 50],
            ['category' => 'Toys & Games', 'name' => 'PlayStation 5', 'price' => 7999000, 'stock' => 30],
            ['category' => 'Toys & Games', 'name' => 'Nintendo Switch OLED', 'price' => 4999000, 'stock' => 40],

            // Automotive
            ['category' => 'Automotive', 'name' => 'Car Vacuum Cleaner', 'price' => 399000, 'stock' => 60],
            ['category' => 'Automotive', 'name' => 'Dash Cam 4K', 'price' => 1299000, 'stock' => 35],

            // Food & Beverages
            ['category' => 'Food & Beverages', 'name' => 'Organic Coffee Beans 1kg', 'price' => 189000, 'stock' => 100],
            ['category' => 'Food & Beverages', 'name' => 'Green Tea Box 100pcs', 'price' => 99000, 'stock' => 150],

            // Pet Supplies
            ['category' => 'Pet Supplies', 'name' => 'Royal Canin Dog Food 10kg', 'price' => 899000, 'stock' => 50],
            ['category' => 'Pet Supplies', 'name' => 'Cat Litter Box Auto', 'price' => 1599000, 'stock' => 25],
        ];

        foreach ($products as $productData) {
            $category = Category::where('name', $productData['category'])->first();

            if ($category) {
                Product::create([
                    'category_id' => $category->id,
                    'name' => $productData['name'],
                    'slug' => Str::slug($productData['name']),
                    'description' => 'High quality ' . $productData['name'] . '.Lorem ipsum dolor sit amet, consectetur adipiscing elit.Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                    'price' => $productData['price'],
                    'stock' => $productData['stock'],
                    'sku' => 'PRD-' . strtoupper(Str::random(8)),
                    'is_active' => true,
                ]);
            }
        }

        $this->command->info('✅ Products created successfully! ');
    }
}
