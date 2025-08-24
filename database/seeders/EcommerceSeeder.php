<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EcommerceSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@grozzoery.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create vendor user
        $vendorUser = User::create([
            'name' => 'John Vendor',
            'email' => 'vendor@grozzoery.com',
            'password' => Hash::make('password'),
            'role' => 'vendor',
            'phone' => '+1234567890',
            'address' => '123 Vendor Street',
            'city' => 'Vendor City',
            'state' => 'VS',
            'zip_code' => '12345',
            'country' => 'USA',
        ]);

        // Create vendor profile
        $vendor = Vendor::create([
            'user_id' => $vendorUser->id,
            'business_name' => 'Quality Products Store',
            'business_description' => 'We offer high-quality products at competitive prices.',
            'phone' => '+1234567890',
            'address' => '123 Vendor Street',
            'city' => 'Vendor City',
            'state' => 'VS',
            'zip_code' => '12345',
            'country' => 'USA',
            'status' => 'approved',
            'commission_rate' => 10.00,
            'minimum_payout' => 50.00,
            'verified_at' => now(),
        ]);

        // Create customer user
        $customer = User::create([
            'name' => 'Jane Customer',
            'email' => 'customer@grozzoery.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'phone' => '+1987654321',
            'address' => '456 Customer Avenue',
            'city' => 'Customer City',
            'state' => 'CC',
            'zip_code' => '54321',
            'country' => 'USA',
        ]);

        // Create categories
        $electronics = Category::create([
            'name' => 'Electronics',
            'slug' => 'electronics',
            'description' => 'Latest electronic gadgets and devices',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $clothing = Category::create([
            'name' => 'Clothing',
            'slug' => 'clothing',
            'description' => 'Fashionable clothing for all ages',
            'is_active' => true,
            'sort_order' => 2,
        ]);

        $home = Category::create([
            'name' => 'Home & Garden',
            'slug' => 'home-garden',
            'description' => 'Everything for your home and garden',
            'is_active' => true,
            'sort_order' => 3,
        ]);

        // Create products
        $product1 = Product::create([
            'name' => 'Wireless Bluetooth Headphones',
            'slug' => 'wireless-bluetooth-headphones',
            'description' => 'High-quality wireless headphones with noise cancellation',
            'short_description' => 'Premium wireless headphones',
            'sku' => 'WH-001',
            'vendor_id' => $vendor->id,
            'category_id' => $electronics->id,
            'price' => 99.99,
            'compare_price' => 129.99,
            'cost_price' => 60.00,
            'quantity' => 50,
            'min_quantity' => 1,
            'weight' => 0.5,
            'is_active' => true,
            'is_featured' => true,
            'is_bestseller' => true,
        ]);

        $product2 = Product::create([
            'name' => 'Cotton T-Shirt',
            'slug' => 'cotton-t-shirt',
            'description' => 'Comfortable cotton t-shirt in various colors',
            'short_description' => 'Soft cotton t-shirt',
            'sku' => 'TS-001',
            'vendor_id' => $vendor->id,
            'category_id' => $clothing->id,
            'price' => 24.99,
            'compare_price' => 29.99,
            'cost_price' => 15.00,
            'quantity' => 100,
            'min_quantity' => 1,
            'weight' => 0.2,
            'is_active' => true,
            'is_featured' => false,
            'is_bestseller' => true,
        ]);

        $product3 = Product::create([
            'name' => 'Garden Plant Pot',
            'slug' => 'garden-plant-pot',
            'description' => 'Beautiful ceramic plant pot for indoor and outdoor use',
            'short_description' => 'Elegant plant pot',
            'sku' => 'GP-001',
            'vendor_id' => $vendor->id,
            'category_id' => $home->id,
            'price' => 39.99,
            'compare_price' => 49.99,
            'cost_price' => 25.00,
            'quantity' => 75,
            'min_quantity' => 1,
            'weight' => 2.0,
            'is_active' => true,
            'is_featured' => true,
            'is_bestseller' => false,
        ]);

        // Create product images (using placeholder URLs for now)
        ProductImage::create([
            'product_id' => $product1->id,
            'image_path' => 'products/headphones.jpg',
            'alt_text' => 'Wireless Bluetooth Headphones',
            'sort_order' => 1,
            'is_primary' => true,
        ]);

        ProductImage::create([
            'product_id' => $product2->id,
            'image_path' => 'products/tshirt.jpg',
            'alt_text' => 'Cotton T-Shirt',
            'sort_order' => 1,
            'is_primary' => true,
        ]);

        ProductImage::create([
            'product_id' => $product3->id,
            'image_path' => 'products/plantpot.jpg',
            'alt_text' => 'Garden Plant Pot',
            'sort_order' => 1,
            'is_primary' => true,
        ]);

        $this->command->info('Ecommerce sample data seeded successfully!');
        $this->command->info('Admin: admin@grozzoery.com / password');
        $this->command->info('Vendor: vendor@grozzoery.com / password');
        $this->command->info('Customer: customer@grozzoery.com / password');
    }
}
