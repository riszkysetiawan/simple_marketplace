<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Electronics',
                'description' => 'Electronic devices, gadgets, and accessories',
                'is_active' => true,
            ],
            [
                'name' => 'Fashion',
                'description' => 'Clothing, shoes, and fashion accessories',
                'is_active' => true,
            ],
            [
                'name' => 'Home & Living',
                'description' => 'Furniture, home decor, and kitchen appliances',
                'is_active' => true,
            ],
            [
                'name' => 'Beauty & Health',
                'description' => 'Cosmetics, skincare, and health products',
                'is_active' => true,
            ],
            [
                'name' => 'Sports & Outdoor',
                'description' => 'Sports equipment and outdoor gear',
                'is_active' => true,
            ],
            [
                'name' => 'Books & Stationery',
                'description' => 'Books, magazines, and office supplies',
                'is_active' => true,
            ],
            [
                'name' => 'Toys & Games',
                'description' => 'Toys, games, and hobby items',
                'is_active' => true,
            ],
            [
                'name' => 'Automotive',
                'description' => 'Car parts, accessories, and tools',
                'is_active' => true,
            ],
            [
                'name' => 'Food & Beverages',
                'description' => 'Groceries, snacks, and drinks',
                'is_active' => true,
            ],
            [
                'name' => 'Pet Supplies',
                'description' => 'Pet food, toys, and accessories',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
                'is_active' => $category['is_active'],
            ]);
        }

        $this->command->info('✅ Categories created successfully!');
    }
}
