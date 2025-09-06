<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\HomeSetting;
use App\Models\Banner;
use App\Models\Promotion;
use Carbon\Carbon;

class DynamicContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Home Settings
        $homeSettings = [
            // Hero Section
            ['key' => 'hero_title', 'value' => 'Welcome to Grozzoery', 'type' => 'text', 'group' => 'hero', 'description' => 'Main hero title'],
            ['key' => 'hero_subtitle', 'value' => 'Discover amazing products from trusted vendors', 'type' => 'text', 'group' => 'hero', 'description' => 'Hero subtitle'],
            ['key' => 'hero_button_text', 'value' => 'Shop Now', 'type' => 'text', 'group' => 'hero', 'description' => 'Hero button text'],
            ['key' => 'hero_button_url', 'value' => '/shop', 'type' => 'text', 'group' => 'hero', 'description' => 'Hero button URL'],
            
            // Section Titles
            ['key' => 'featured_title', 'value' => 'Featured Products', 'type' => 'text', 'group' => 'sections', 'description' => 'Featured products section title'],
            ['key' => 'bestseller_title', 'value' => 'Bestsellers', 'type' => 'text', 'group' => 'sections', 'description' => 'Bestseller products section title'],
            ['key' => 'categories_title', 'value' => 'Shop by Category', 'type' => 'text', 'group' => 'sections', 'description' => 'Categories section title'],
            ['key' => 'vendors_title', 'value' => 'Top Vendors', 'type' => 'text', 'group' => 'sections', 'description' => 'Vendors section title'],
        ];

        foreach ($homeSettings as $setting) {
            HomeSetting::create($setting);
        }

        // Banners
        $banners = [
            [
                'title' => 'Flash Sale',
                'description' => 'Up to 70% Off',
                'type' => 'flash_sale',
                'icon' => 'fas fa-fire',
                'button_text' => 'Shop Now',
                'button_url' => '/shop?sale=flash',
                'background_color' => '#ff6b6b',
                'text_color' => '#ffffff',
                'sort_order' => 1,
                'is_active' => true,
                'starts_at' => now(),
                'ends_at' => now()->addDays(3),
            ],
            [
                'title' => 'New Arrivals',
                'description' => 'Fresh Products Daily',
                'type' => 'promotion',
                'icon' => 'fas fa-star',
                'button_text' => 'Explore',
                'button_url' => '/shop?sort=newest',
                'background_color' => '#4ecdc4',
                'text_color' => '#ffffff',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Free Shipping',
                'description' => 'On Orders Over $50',
                'type' => 'promotion',
                'icon' => 'fas fa-truck',
                'button_text' => 'Shop Now',
                'button_url' => '/shop',
                'background_color' => '#45b7d1',
                'text_color' => '#ffffff',
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($banners as $banner) {
            Banner::create($banner);
        }

        // Promotions
        $promotions = [
            [
                'title' => 'Free Shipping',
                'description' => 'On orders over $50',
                'type' => 'shipping',
                'icon' => 'fas fa-shipping-fast',
                'button_text' => 'Learn More',
                'button_url' => '/shop',
                'minimum_order' => 50.00,
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'New Customer Discount',
                'description' => 'Get 10% off your first order',
                'type' => 'discount',
                'icon' => 'fas fa-percentage',
                'button_text' => 'Sign Up',
                'button_url' => '/register',
                'discount_amount' => 10.00,
                'discount_type' => 'percentage',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Same Day Delivery',
                'description' => 'Available in select areas',
                'type' => 'offer',
                'icon' => 'fas fa-clock',
                'button_text' => 'Check Availability',
                'button_url' => '/shop',
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($promotions as $promotion) {
            Promotion::create($promotion);
        }
    }
}
