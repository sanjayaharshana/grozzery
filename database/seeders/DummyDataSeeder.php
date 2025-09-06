<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Creating comprehensive dummy data...');

        // Create categories first
        $categories = $this->createCategories();
        
        // Create vendors
        $vendors = $this->createVendors();
        
        // Create products for each vendor
        $this->createProducts($vendors, $categories);

        $this->command->info('Dummy data created successfully!');
        $this->command->info('Created ' . count($categories) . ' categories');
        $this->command->info('Created ' . count($vendors) . ' vendors');
        $this->command->info('Created products for each vendor');
    }

    private function createCategories()
    {
        $categories = [
            [
                'name' => 'Electronics',
                'slug' => 'electronics',
                'description' => 'Latest electronic gadgets, smartphones, laptops, and accessories',
                'sort_order' => 1,
            ],
            [
                'name' => 'Fashion & Clothing',
                'slug' => 'fashion-clothing',
                'description' => 'Trendy clothing, shoes, and accessories for men, women, and kids',
                'sort_order' => 2,
            ],
            [
                'name' => 'Home & Garden',
                'slug' => 'home-garden',
                'description' => 'Furniture, decor, gardening tools, and home improvement items',
                'sort_order' => 3,
            ],
            [
                'name' => 'Sports & Fitness',
                'slug' => 'sports-fitness',
                'description' => 'Sports equipment, fitness gear, and outdoor activities',
                'sort_order' => 4,
            ],
            [
                'name' => 'Beauty & Health',
                'slug' => 'beauty-health',
                'description' => 'Cosmetics, skincare, health supplements, and personal care',
                'sort_order' => 5,
            ],
            [
                'name' => 'Books & Media',
                'slug' => 'books-media',
                'description' => 'Books, movies, music, and educational materials',
                'sort_order' => 6,
            ],
            [
                'name' => 'Toys & Games',
                'slug' => 'toys-games',
                'description' => 'Children\'s toys, board games, and entertainment',
                'sort_order' => 7,
            ],
            [
                'name' => 'Automotive',
                'slug' => 'automotive',
                'description' => 'Car parts, accessories, and maintenance products',
                'sort_order' => 8,
            ],
            [
                'name' => 'Food & Beverages',
                'slug' => 'food-beverages',
                'description' => 'Gourmet foods, beverages, and specialty items',
                'sort_order' => 9,
            ],
            [
                'name' => 'Pet Supplies',
                'slug' => 'pet-supplies',
                'description' => 'Pet food, toys, accessories, and care products',
                'sort_order' => 10,
            ],
        ];

        $createdCategories = [];
        foreach ($categories as $categoryData) {
            // Check if category already exists
            $existingCategory = Category::where('slug', $categoryData['slug'])->first();
            if ($existingCategory) {
                $createdCategories[] = $existingCategory;
                continue;
            }

            $category = Category::create([
                'name' => $categoryData['name'],
                'slug' => $categoryData['slug'],
                'description' => $categoryData['description'],
                'is_active' => true,
                'sort_order' => $categoryData['sort_order'],
            ]);
            $createdCategories[] = $category;
        }

        return $createdCategories;
    }

    private function createVendors()
    {
        $vendorData = [
            [
                'name' => 'TechGear Pro',
                'email' => 'techgear@example.com',
                'business_name' => 'TechGear Pro',
                'business_description' => 'Leading provider of cutting-edge electronics and tech accessories. We bring you the latest innovations in technology.',
                'phone' => '+1-555-0101',
                'address' => '123 Tech Street',
                'city' => 'San Francisco',
                'state' => 'CA',
                'zip_code' => '94105',
                'rating' => 4.8,
                'total_orders' => 1250,
            ],
            [
                'name' => 'Fashion Forward',
                'email' => 'fashion@example.com',
                'business_name' => 'Fashion Forward',
                'business_description' => 'Trendy and affordable fashion for the modern lifestyle. Quality clothing that makes you look and feel great.',
                'phone' => '+1-555-0102',
                'address' => '456 Fashion Ave',
                'city' => 'New York',
                'state' => 'NY',
                'zip_code' => '10001',
                'rating' => 4.6,
                'total_orders' => 890,
            ],
            [
                'name' => 'Home Sweet Home',
                'email' => 'home@example.com',
                'business_name' => 'Home Sweet Home',
                'business_description' => 'Transform your living space with our curated collection of home decor and furniture. Quality meets style.',
                'phone' => '+1-555-0103',
                'address' => '789 Home Lane',
                'city' => 'Austin',
                'state' => 'TX',
                'zip_code' => '73301',
                'rating' => 4.7,
                'total_orders' => 650,
            ],
            [
                'name' => 'FitLife Sports',
                'email' => 'sports@example.com',
                'business_name' => 'FitLife Sports',
                'business_description' => 'Your one-stop shop for sports equipment and fitness gear. Helping you achieve your fitness goals.',
                'phone' => '+1-555-0104',
                'address' => '321 Sports Blvd',
                'city' => 'Denver',
                'state' => 'CO',
                'zip_code' => '80202',
                'rating' => 4.5,
                'total_orders' => 420,
            ],
            [
                'name' => 'Beauty Bliss',
                'email' => 'beauty@example.com',
                'business_name' => 'Beauty Bliss',
                'business_description' => 'Premium beauty and skincare products. Discover your natural beauty with our carefully selected products.',
                'phone' => '+1-555-0105',
                'address' => '654 Beauty Way',
                'city' => 'Los Angeles',
                'state' => 'CA',
                'zip_code' => '90028',
                'rating' => 4.9,
                'total_orders' => 780,
            ],
            [
                'name' => 'Bookworm Central',
                'email' => 'books@example.com',
                'business_name' => 'Bookworm Central',
                'business_description' => 'Your literary haven with books for every reader. From bestsellers to rare finds, we have it all.',
                'phone' => '+1-555-0106',
                'address' => '987 Library St',
                'city' => 'Boston',
                'state' => 'MA',
                'zip_code' => '02108',
                'rating' => 4.4,
                'total_orders' => 320,
            ],
            [
                'name' => 'Toy Kingdom',
                'email' => 'toys@example.com',
                'business_name' => 'Toy Kingdom',
                'business_description' => 'Magical toys and games that spark imagination and creativity. Making childhood memories that last forever.',
                'phone' => '+1-555-0107',
                'address' => '147 Playground Rd',
                'city' => 'Orlando',
                'state' => 'FL',
                'zip_code' => '32801',
                'rating' => 4.6,
                'total_orders' => 560,
            ],
            [
                'name' => 'Auto Parts Plus',
                'email' => 'auto@example.com',
                'business_name' => 'Auto Parts Plus',
                'business_description' => 'Quality automotive parts and accessories. Keeping your vehicle running smoothly with genuine parts.',
                'phone' => '+1-555-0108',
                'address' => '258 Garage Ave',
                'city' => 'Detroit',
                'state' => 'MI',
                'zip_code' => '48201',
                'rating' => 4.3,
                'total_orders' => 290,
            ],
            [
                'name' => 'Gourmet Delights',
                'email' => 'food@example.com',
                'business_name' => 'Gourmet Delights',
                'business_description' => 'Exquisite gourmet foods and beverages from around the world. Elevate your culinary experience.',
                'phone' => '+1-555-0109',
                'address' => '369 Culinary Ct',
                'city' => 'Seattle',
                'state' => 'WA',
                'zip_code' => '98101',
                'rating' => 4.7,
                'total_orders' => 180,
            ],
            [
                'name' => 'Pet Paradise',
                'email' => 'pets@example.com',
                'business_name' => 'Pet Paradise',
                'business_description' => 'Everything your furry friends need for a happy and healthy life. Premium pet supplies and care products.',
                'phone' => '+1-555-0110',
                'address' => '741 Pet Park Dr',
                'city' => 'Portland',
                'state' => 'OR',
                'zip_code' => '97201',
                'rating' => 4.8,
                'total_orders' => 340,
            ],
        ];

        $vendors = [];
        foreach ($vendorData as $data) {
            // Check if vendor already exists
            $existingUser = User::where('email', $data['email'])->first();
            if ($existingUser) {
                $vendor = Vendor::where('user_id', $existingUser->id)->first();
                if ($vendor) {
                    $vendors[] = $vendor;
                    continue;
                }
            }

            // Create user
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make('password'),
                'role' => 'vendor',
                'phone' => $data['phone'],
                'address' => $data['address'],
                'city' => $data['city'],
                'state' => $data['state'],
                'zip_code' => $data['zip_code'],
                'country' => 'USA',
            ]);

            // Create vendor
            $vendor = Vendor::create([
                'user_id' => $user->id,
                'business_name' => $data['business_name'],
                'business_description' => $data['business_description'],
                'phone' => $data['phone'],
                'address' => $data['address'],
                'city' => $data['city'],
                'state' => $data['state'],
                'zip_code' => $data['zip_code'],
                'country' => 'USA',
                'status' => 'approved',
                'commission_rate' => rand(8, 15),
                'minimum_payout' => rand(25, 100),
                'total_sales' => rand(10000, 100000),
                'total_orders' => $data['total_orders'],
                'rating' => $data['rating'],
                'verified_at' => now(),
            ]);

            $vendors[] = $vendor;
        }

        return $vendors;
    }

    private function createProducts($vendors, $categories)
    {
        $productData = [
            // Electronics products
            [
                'name' => 'iPhone 15 Pro Max',
                'description' => 'Latest iPhone with advanced camera system, A17 Pro chip, and titanium design.',
                'price' => 1199.99,
                'compare_price' => 1299.99,
                'category' => 'Electronics',
                'vendor_index' => 0,
            ],
            [
                'name' => 'Samsung Galaxy S24 Ultra',
                'description' => 'Premium Android smartphone with S Pen, 200MP camera, and AI features.',
                'price' => 1299.99,
                'compare_price' => 1399.99,
                'category' => 'Electronics',
                'vendor_index' => 0,
            ],
            [
                'name' => 'MacBook Pro 16-inch',
                'description' => 'Powerful laptop with M3 Pro chip, Liquid Retina XDR display, and all-day battery.',
                'price' => 2499.99,
                'compare_price' => 2699.99,
                'category' => 'Electronics',
                'vendor_index' => 0,
            ],
            [
                'name' => 'Sony WH-1000XM5 Headphones',
                'description' => 'Industry-leading noise canceling wireless headphones with 30-hour battery.',
                'price' => 399.99,
                'compare_price' => 449.99,
                'category' => 'Electronics',
                'vendor_index' => 0,
            ],
            [
                'name' => 'Apple Watch Series 9',
                'description' => 'Advanced smartwatch with health monitoring, GPS, and cellular connectivity.',
                'price' => 429.99,
                'compare_price' => 479.99,
                'category' => 'Electronics',
                'vendor_index' => 0,
            ],

            // Fashion products
            [
                'name' => 'Designer Leather Jacket',
                'description' => 'Premium genuine leather jacket with modern cut and timeless style.',
                'price' => 299.99,
                'compare_price' => 399.99,
                'category' => 'Fashion & Clothing',
                'vendor_index' => 1,
            ],
            [
                'name' => 'Cashmere Sweater',
                'description' => 'Luxurious 100% cashmere sweater in classic colors. Soft and warm.',
                'price' => 199.99,
                'compare_price' => 249.99,
                'category' => 'Fashion & Clothing',
                'vendor_index' => 1,
            ],
            [
                'name' => 'Designer Jeans',
                'description' => 'Premium denim jeans with perfect fit and modern styling.',
                'price' => 149.99,
                'compare_price' => 179.99,
                'category' => 'Fashion & Clothing',
                'vendor_index' => 1,
            ],
            [
                'name' => 'Running Shoes',
                'description' => 'High-performance running shoes with advanced cushioning technology.',
                'price' => 129.99,
                'compare_price' => 159.99,
                'category' => 'Fashion & Clothing',
                'vendor_index' => 1,
            ],
            [
                'name' => 'Silk Scarf',
                'description' => 'Elegant silk scarf with beautiful patterns. Perfect accessory for any outfit.',
                'price' => 79.99,
                'compare_price' => 99.99,
                'category' => 'Fashion & Clothing',
                'vendor_index' => 1,
            ],

            // Home & Garden products
            [
                'name' => 'Modern Sofa Set',
                'description' => 'Contemporary 3-piece sofa set with premium fabric upholstery.',
                'price' => 1299.99,
                'compare_price' => 1599.99,
                'category' => 'Home & Garden',
                'vendor_index' => 2,
            ],
            [
                'name' => 'Smart LED Light Strip',
                'description' => 'WiFi-enabled LED light strip with millions of colors and smart controls.',
                'price' => 49.99,
                'compare_price' => 69.99,
                'category' => 'Home & Garden',
                'vendor_index' => 2,
            ],
            [
                'name' => 'Indoor Plant Collection',
                'description' => 'Set of 5 low-maintenance indoor plants perfect for home decoration.',
                'price' => 89.99,
                'compare_price' => 119.99,
                'category' => 'Home & Garden',
                'vendor_index' => 2,
            ],
            [
                'name' => 'Ceramic Dinnerware Set',
                'description' => 'Complete 16-piece ceramic dinnerware set for 4 people.',
                'price' => 149.99,
                'compare_price' => 199.99,
                'category' => 'Home & Garden',
                'vendor_index' => 2,
            ],
            [
                'name' => 'Garden Tool Set',
                'description' => 'Professional garden tool set with ergonomic handles and rust-resistant coating.',
                'price' => 79.99,
                'compare_price' => 99.99,
                'category' => 'Home & Garden',
                'vendor_index' => 2,
            ],

            // Sports & Fitness products
            [
                'name' => 'Adjustable Dumbbells',
                'description' => 'Space-saving adjustable dumbbells with weight range from 5-50 lbs.',
                'price' => 299.99,
                'compare_price' => 399.99,
                'category' => 'Sports & Fitness',
                'vendor_index' => 3,
            ],
            [
                'name' => 'Yoga Mat Premium',
                'description' => 'Non-slip yoga mat with superior cushioning and eco-friendly materials.',
                'price' => 59.99,
                'compare_price' => 79.99,
                'category' => 'Sports & Fitness',
                'vendor_index' => 3,
            ],
            [
                'name' => 'Resistance Bands Set',
                'description' => 'Complete resistance bands set with door anchor and exercise guide.',
                'price' => 39.99,
                'compare_price' => 49.99,
                'category' => 'Sports & Fitness',
                'vendor_index' => 3,
            ],
            [
                'name' => 'Basketball',
                'description' => 'Official size basketball with premium leather construction.',
                'price' => 29.99,
                'compare_price' => 39.99,
                'category' => 'Sports & Fitness',
                'vendor_index' => 3,
            ],
            [
                'name' => 'Fitness Tracker',
                'description' => 'Advanced fitness tracker with heart rate monitoring and GPS.',
                'price' => 199.99,
                'compare_price' => 249.99,
                'category' => 'Sports & Fitness',
                'vendor_index' => 3,
            ],

            // Beauty & Health products
            [
                'name' => 'Anti-Aging Serum',
                'description' => 'Premium anti-aging serum with retinol and hyaluronic acid.',
                'price' => 89.99,
                'compare_price' => 119.99,
                'category' => 'Beauty & Health',
                'vendor_index' => 4,
            ],
            [
                'name' => 'Vitamin C Face Mask',
                'description' => 'Brightening face mask with vitamin C and natural antioxidants.',
                'price' => 24.99,
                'compare_price' => 34.99,
                'category' => 'Beauty & Health',
                'vendor_index' => 4,
            ],
            [
                'name' => 'Organic Shampoo Set',
                'description' => 'Sulfate-free organic shampoo and conditioner set for all hair types.',
                'price' => 39.99,
                'compare_price' => 49.99,
                'category' => 'Beauty & Health',
                'vendor_index' => 4,
            ],
            [
                'name' => 'Multivitamin Supplements',
                'description' => 'Complete multivitamin supplement with 25 essential vitamins and minerals.',
                'price' => 29.99,
                'compare_price' => 39.99,
                'category' => 'Beauty & Health',
                'vendor_index' => 4,
            ],
            [
                'name' => 'Essential Oil Diffuser',
                'description' => 'Ultrasonic essential oil diffuser with LED lights and timer.',
                'price' => 49.99,
                'compare_price' => 69.99,
                'category' => 'Beauty & Health',
                'vendor_index' => 4,
            ],

            // Books & Media products
            [
                'name' => 'Bestseller Novel Collection',
                'description' => 'Set of 5 bestselling novels from top authors.',
                'price' => 79.99,
                'compare_price' => 99.99,
                'category' => 'Books & Media',
                'vendor_index' => 5,
            ],
            [
                'name' => 'Programming Book Bundle',
                'description' => 'Complete programming book bundle covering multiple languages.',
                'price' => 149.99,
                'compare_price' => 199.99,
                'category' => 'Books & Media',
                'vendor_index' => 5,
            ],
            [
                'name' => 'Bluetooth Speaker',
                'description' => 'Portable Bluetooth speaker with 360-degree sound and waterproof design.',
                'price' => 79.99,
                'compare_price' => 99.99,
                'category' => 'Books & Media',
                'vendor_index' => 5,
            ],
            [
                'name' => 'Educational DVD Set',
                'description' => 'Complete educational DVD set for children with interactive learning.',
                'price' => 59.99,
                'compare_price' => 79.99,
                'category' => 'Books & Media',
                'vendor_index' => 5,
            ],
            [
                'name' => 'Art Supplies Kit',
                'description' => 'Professional art supplies kit with paints, brushes, and canvas.',
                'price' => 99.99,
                'compare_price' => 129.99,
                'category' => 'Books & Media',
                'vendor_index' => 5,
            ],

            // Toys & Games products
            [
                'name' => 'LEGO Creator Set',
                'description' => 'Advanced LEGO Creator set with 2000+ pieces for creative building.',
                'price' => 199.99,
                'compare_price' => 249.99,
                'category' => 'Toys & Games',
                'vendor_index' => 6,
            ],
            [
                'name' => 'Board Game Collection',
                'description' => 'Family board game collection with 6 popular games.',
                'price' => 89.99,
                'compare_price' => 119.99,
                'category' => 'Toys & Games',
                'vendor_index' => 6,
            ],
            [
                'name' => 'Remote Control Car',
                'description' => 'High-speed remote control car with LED lights and rechargeable battery.',
                'price' => 79.99,
                'compare_price' => 99.99,
                'category' => 'Toys & Games',
                'vendor_index' => 6,
            ],
            [
                'name' => 'Educational Puzzle Set',
                'description' => 'Set of 5 educational puzzles for different age groups.',
                'price' => 49.99,
                'compare_price' => 69.99,
                'category' => 'Toys & Games',
                'vendor_index' => 6,
            ],
            [
                'name' => 'Building Blocks Set',
                'description' => 'Colorful building blocks set with storage container.',
                'price' => 39.99,
                'compare_price' => 49.99,
                'category' => 'Toys & Games',
                'vendor_index' => 6,
            ],

            // Automotive products
            [
                'name' => 'Car Phone Mount',
                'description' => 'Magnetic car phone mount with wireless charging capability.',
                'price' => 34.99,
                'compare_price' => 44.99,
                'category' => 'Automotive',
                'vendor_index' => 7,
            ],
            [
                'name' => 'Car Air Freshener Set',
                'description' => 'Premium car air freshener set with long-lasting scents.',
                'price' => 19.99,
                'compare_price' => 24.99,
                'category' => 'Automotive',
                'vendor_index' => 7,
            ],
            [
                'name' => 'Dash Cam',
                'description' => 'HD dash cam with night vision and loop recording.',
                'price' => 129.99,
                'compare_price' => 159.99,
                'category' => 'Automotive',
                'vendor_index' => 7,
            ],
            [
                'name' => 'Car Cleaning Kit',
                'description' => 'Complete car cleaning kit with microfiber towels and cleaning solutions.',
                'price' => 49.99,
                'compare_price' => 69.99,
                'category' => 'Automotive',
                'vendor_index' => 7,
            ],
            [
                'name' => 'Tire Pressure Gauge',
                'description' => 'Digital tire pressure gauge with LCD display and backlight.',
                'price' => 24.99,
                'compare_price' => 34.99,
                'category' => 'Automotive',
                'vendor_index' => 7,
            ],

            // Food & Beverages products
            [
                'name' => 'Gourmet Coffee Beans',
                'description' => 'Premium single-origin coffee beans roasted to perfection.',
                'price' => 29.99,
                'compare_price' => 39.99,
                'category' => 'Food & Beverages',
                'vendor_index' => 8,
            ],
            [
                'name' => 'Artisan Chocolate Box',
                'description' => 'Handcrafted artisan chocolate box with exotic flavors.',
                'price' => 49.99,
                'compare_price' => 69.99,
                'category' => 'Food & Beverages',
                'vendor_index' => 8,
            ],
            [
                'name' => 'Organic Tea Collection',
                'description' => 'Premium organic tea collection with 12 different varieties.',
                'price' => 39.99,
                'compare_price' => 49.99,
                'category' => 'Food & Beverages',
                'vendor_index' => 8,
            ],
            [
                'name' => 'Gourmet Spice Set',
                'description' => 'Complete gourmet spice set with exotic spices from around the world.',
                'price' => 59.99,
                'compare_price' => 79.99,
                'category' => 'Food & Beverages',
                'vendor_index' => 8,
            ],
            [
                'name' => 'Wine Gift Set',
                'description' => 'Premium wine gift set with 3 bottles and tasting notes.',
                'price' => 89.99,
                'compare_price' => 119.99,
                'category' => 'Food & Beverages',
                'vendor_index' => 8,
            ],

            // Pet Supplies products
            [
                'name' => 'Premium Dog Food',
                'description' => 'High-quality premium dog food with natural ingredients.',
                'price' => 69.99,
                'compare_price' => 89.99,
                'category' => 'Pet Supplies',
                'vendor_index' => 9,
            ],
            [
                'name' => 'Cat Scratching Post',
                'description' => 'Multi-level cat scratching post with toys and hiding spots.',
                'price' => 89.99,
                'compare_price' => 119.99,
                'category' => 'Pet Supplies',
                'vendor_index' => 9,
            ],
            [
                'name' => 'Pet Grooming Kit',
                'description' => 'Complete pet grooming kit with brushes, nail clippers, and shampoo.',
                'price' => 39.99,
                'compare_price' => 49.99,
                'category' => 'Pet Supplies',
                'vendor_index' => 9,
            ],
            [
                'name' => 'Dog Training Treats',
                'description' => 'Healthy dog training treats made with natural ingredients.',
                'price' => 19.99,
                'compare_price' => 24.99,
                'category' => 'Pet Supplies',
                'vendor_index' => 9,
            ],
            [
                'name' => 'Pet Carrier Backpack',
                'description' => 'Comfortable pet carrier backpack for small dogs and cats.',
                'price' => 79.99,
                'compare_price' => 99.99,
                'category' => 'Pet Supplies',
                'vendor_index' => 9,
            ],
        ];

        $categoryMap = [];
        foreach ($categories as $category) {
            $categoryMap[$category->name] = $category;
        }

        foreach ($productData as $data) {
            $category = $categoryMap[$data['category']];
            $vendor = $vendors[$data['vendor_index']];
            
            $product = Product::create([
                'name' => $data['name'],
                'slug' => Str::slug($data['name']),
                'description' => $data['description'],
                'short_description' => substr($data['description'], 0, 100) . '...',
                'sku' => 'SKU-' . strtoupper(substr(md5($data['name']), 0, 8)),
                'vendor_id' => $vendor->id,
                'category_id' => $category->id,
                'price' => $data['price'],
                'compare_price' => $data['compare_price'],
                'cost_price' => $data['price'] * 0.6, // 60% of selling price
                'quantity' => rand(10, 100),
                'min_quantity' => 1,
                'weight' => rand(1, 50) / 10, // 0.1 to 5.0 kg
                'is_active' => true,
                'is_featured' => rand(0, 1) == 1,
                'is_bestseller' => rand(0, 1) == 1,
            ]);

            // Create product image
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => 'products/' . Str::slug($data['name']) . '.jpg',
                'alt_text' => $data['name'],
                'sort_order' => 1,
                'is_primary' => true,
            ]);

            // Create some product variants for certain products
            if (rand(0, 2) == 0) { // 33% chance
                $variantNames = ['Small', 'Medium', 'Large', 'Red', 'Blue', 'Black', 'White'];
                $variantName = $variantNames[array_rand($variantNames)];
                
                ProductVariant::create([
                    'product_id' => $product->id,
                    'name' => $variantName,
                    'sku' => $product->sku . '-' . substr($variantName, 0, 3),
                    'price' => $product->price + rand(-20, 20),
                    'quantity' => rand(5, 50),
                    'is_active' => true,
                ]);
            }
        }
    }
}
