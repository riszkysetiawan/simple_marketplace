<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        // Get all categories
        $categories = Category::all();

        if ($categories->isEmpty()) {
            $this->command->error('❌ No categories found!  Run CategorySeeder first.');
            return;
        }

        $this->command->info('🚀 Generating 200 products per category...');

        $totalCreated = 0;

        foreach ($categories as $category) {
            $this->command->info("📦 Creating products for: {$category->name}");

            // Generate 200 products per category
            for ($i = 1; $i <= 200; $i++) {
                $productName = $this->generateProductName($category->name, $i, $faker);

                Product::create([
                    'category_id' => $category->id,
                    'name' => $productName,
                    'slug' => Str::slug($productName) . '-' . Str::random(4),
                    'description' => $this->generateDescription($productName, $faker),
                    'price' => $this->generatePrice($category->name, $faker),
                    'stock' => $faker->numberBetween(0, 500),
                    'sku' => 'PRD-' . strtoupper(Str::random(8)),
                    'is_active' => $faker->boolean(95), // 95% active
                    'is_featured' => $i <= 10 ? true : $faker->boolean(5), // First 10 featured
                ]);

                $totalCreated++;

                // Progress indicator
                if ($i % 50 == 0) {
                    $this->command->info("  ✓ Created {$i}/200 products");
                }
            }
        }

        $this->command->info("✅ Total {$totalCreated} products created successfully!");
    }

    /**
     * Generate realistic product names based on category
     */
    private function generateProductName(string $categoryName, int $index, $faker): string
    {
        $productNames = [
            'Electronics' => [
                'iPhone 15 Pro Max',
                'Samsung Galaxy S24 Ultra',
                'MacBook Pro M3',
                'iPad Air',
                'Sony WH-1000XM5 Headphones',
                'Apple Watch Series 9',
                'Dell XPS 13 Laptop',
                'LG OLED TV 55"',
                'Canon EOS R6 Camera',
                'DJI Mini 3 Pro Drone',
                'Bose SoundLink Speaker',
                'Logitech MX Master Mouse',
                'Razer BlackWidow Keyboard',
                'Samsung Galaxy Buds Pro',
                'GoPro Hero 12',
                'Ring Video Doorbell',
                'Nest Thermostat',
                'Philips Hue Smart Bulb',
                'Anker PowerCore Battery',
                'SanDisk USB Flash Drive',
                'Western Digital HDD',
                'Asus ROG Gaming Monitor',
            ],
            'Fashion' => [
                'Nike Air Max Sneakers',
                'Adidas Ultraboost',
                'Levi\'s 501 Jeans',
                'Zara Leather Jacket',
                'H&M Cotton T-Shirt',
                'Ralph Lauren Polo Shirt',
                'Gucci Belt',
                'Rolex Submariner Watch',
                'Ray-Ban Aviator Sunglasses',
                'North Face Winter Jacket',
                'Timberland Boots',
                'Converse Chuck Taylor',
                'Vans Old Skool',
                'Puma Running Shoes',
                'Under Armour Sports Bra',
                'Tommy Hilfiger Dress',
                'Calvin Klein Underwear',
                'Lacoste Hoodie',
            ],
            'Home & Living' => [
                'IKEA Sofa Bed',
                'Coffee Table Oak Wood',
                'LED Table Lamp',
                'Kitchen Blender',
                'Dyson Vacuum Cleaner',
                'Nespresso Coffee Machine',
                'Air Purifier HEPA',
                'Memory Foam Mattress',
                'Dining Table Set',
                'Bookshelf Cabinet',
                'Wall Clock Modern',
                'Throw Pillow Set',
                'Area Rug 6x9',
                'Curtains Blackout',
                'Storage Ottoman',
            ],
            'Beauty & Health' => [
                'Cetaphil Face Wash',
                'The Ordinary Serum',
                'Dyson Hair Dryer',
                'Fitbit Charge 6',
                'L\'Oreal Lipstick',
                'Maybelline Mascara',
                'Neutrogena Sunscreen',
                'Colgate Toothpaste',
                'Gillette Razor',
                'Dove Body Wash',
                'Nivea Moisturizer',
                'Olay Anti-Aging Cream',
            ],
            'Sports & Outdoor' => [
                'Yoga Mat Premium',
                'Dumbbells Set 20kg',
                'Camping Tent 4 Person',
                'Mountain Bike 27.5"',
                'Treadmill Electric',
                'Exercise Ball',
                'Resistance Bands',
                'Hiking Backpack 50L',
                'Sleeping Bag Winter',
                'Fishing Rod Carbon',
                'Golf Club Set',
                'Basketball Spalding',
            ],
            'Books & Stationery' => [
                'Atomic Habits Book',
                'Moleskine Notebook',
                'Pilot G2 Pen Set',
                'Harry Potter Collection',
                'The Lean Startup',
                'Post-it Notes',
                'Stapler Heavy Duty',
                'Calculator Scientific',
                'File Organizer',
            ],
            'Toys & Games' => [
                'LEGO Star Wars Set',
                'PlayStation 5 Console',
                'Nintendo Switch OLED',
                'Xbox Series X',
                'Barbie Doll House',
                'Hot Wheels Track Set',
                'Monopoly Board Game',
                'Rubik\'s Cube',
                'Nerf Blaster',
            ],
            'Automotive' => [
                'Car Vacuum Cleaner',
                'Dash Cam 4K',
                'Motor Oil 5W-30',
                'Car Phone Mount',
                'Jump Starter Battery',
                'Tire Pressure Gauge',
                'Car Cover Waterproof',
                'Floor Mats All Weather',
                'Wiper Blades',
            ],
            'Food & Beverages' => [
                'Organic Coffee Beans 1kg',
                'Green Tea Box 100pcs',
                'Olive Oil Extra Virgin',
                'Pasta Spaghetti',
                'Rice Basmati 5kg',
                'Honey Pure 500g',
                'Protein Powder Whey',
                'Energy Drink',
                'Mineral Water 24 Pack',
            ],
            'Pet Supplies' => [
                'Royal Canin Dog Food 10kg',
                'Cat Litter Box Auto',
                'Pet Carrier Backpack',
                'Dog Leash Retractable',
                'Cat Scratching Post',
                'Fish Tank 20 Gallon',
                'Bird Cage Large',
                'Hamster Wheel',
                'Pet Grooming Kit',
            ],
        ];

        $baseNames = $productNames[$categoryName] ?? ['Generic Product'];
        $baseName = $baseNames[array_rand($baseNames)];

        // Add variations
        $variations = [
            'Pro',
            'Plus',
            'Ultra',
            'Premium',
            'Deluxe',
            'Elite',
            'Advanced',
            'Classic',
            'Modern',
            'Vintage',
            'Sport',
            'Luxury',
            'Essential',
            'Max',
            'Mini',
            'Lite',
            'XL',
            'Compact',
            'Portable',
        ];

        $colors = [
            'Black',
            'White',
            'Blue',
            'Red',
            'Green',
            'Silver',
            'Gold',
            'Gray',
            'Pink',
            'Purple',
            'Orange',
            'Brown',
            'Navy',
        ];

        $sizes = ['Small', 'Medium', 'Large', 'XL', 'XXL'];

        // 70% chance to add variation
        if ($faker->boolean(70)) {
            $variation = $faker->randomElement($variations);
            $baseName .= ' ' . $variation;
        }

        // 50% chance to add color
        if ($faker->boolean(50)) {
            $color = $faker->randomElement($colors);
            $baseName .= ' ' . $color;
        }

        // 30% chance to add size (fashion items)
        if (in_array($categoryName, ['Fashion', 'Sports & Outdoor']) && $faker->boolean(30)) {
            $size = $faker->randomElement($sizes);
            $baseName .= ' Size ' . $size;
        }

        // Add unique identifier
        $baseName .= ' #' . $index;

        return $baseName;
    }

    /**
     * Generate product description
     */
    private function generateDescription(string $productName, $faker): string
    {
        $templates = [
            "High quality {product}.{sentence1} {sentence2} Perfect for daily use.",
            "Premium {product} with excellent features.{sentence1} {sentence2} Limited stock available! ",
            "Top-rated {product}.{sentence1} {sentence2} Buy now and get free shipping!",
            "Best-selling {product} in the market.{sentence1} {sentence2} 100% satisfaction guaranteed.",
            "Professional-grade {product}.{sentence1} {sentence2} Trusted by millions worldwide.",
        ];

        $template = $faker->randomElement($templates);

        $sentences = [
            "Made from premium materials with advanced technology.",
            "Features cutting-edge design and superior performance.",
            "Durable construction ensures long-lasting quality.",
            "Ergonomic design for maximum comfort and convenience.",
            "Industry-leading warranty and customer support included.",
            "Eco-friendly and sustainable manufacturing process.",
            "Award-winning design recognized globally.",
            "Trusted by professionals and enthusiasts alike.",
        ];

        return str_replace(
            ['{product}', '{sentence1}', '{sentence2}'],
            [$productName, $faker->randomElement($sentences), $faker->randomElement($sentences)],
            $template
        );
    }

    /**
     * Generate realistic prices based on category
     */
    private function generatePrice(string $categoryName, $faker): float
    {
        $priceRanges = [
            'Electronics' => [500000, 50000000],
            'Fashion' => [100000, 5000000],
            'Home & Living' => [200000, 10000000],
            'Beauty & Health' => [50000, 2000000],
            'Sports & Outdoor' => [150000, 5000000],
            'Books & Stationery' => [20000, 500000],
            'Toys & Games' => [100000, 10000000],
            'Automotive' => [200000, 3000000],
            'Food & Beverages' => [10000, 500000],
            'Pet Supplies' => [50000, 2000000],
        ];

        [$min, $max] = $priceRanges[$categoryName] ?? [100000, 1000000];

        // Generate price with some randomness
        $price = $faker->numberBetween($min, $max);

        // Round to nearest 1000
        $price = round($price / 1000) * 1000;

        return $price;
    }
}
