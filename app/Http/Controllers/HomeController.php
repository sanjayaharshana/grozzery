<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Vendor;
use App\Models\HomeSetting;
use App\Models\Banner;
use App\Models\Promotion;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Dynamic home settings
        $heroSettings = [
            'title' => HomeSetting::getValue('hero_title', 'Welcome to Grozzoery'),
            'subtitle' => HomeSetting::getValue('hero_subtitle', 'Discover amazing products from trusted vendors'),
            'button_text' => HomeSetting::getValue('hero_button_text', 'Browse Marketplace'),
            'button_url' => HomeSetting::getValue('hero_button_url', route('marketplace')),
        ];

        // Dynamic banners
        $banners = Banner::active()
            ->orderBy('sort_order')
            ->get();

        // Dynamic promotions
        $promotions = Promotion::active()
            ->orderBy('sort_order')
            ->get();

        // Section titles
        $sectionTitles = [
            'featured_title' => HomeSetting::getValue('featured_title', 'Featured Products'),
            'bestseller_title' => HomeSetting::getValue('bestseller_title', 'Bestsellers'),
            'categories_title' => HomeSetting::getValue('categories_title', 'Browse by Category'),
            'vendors_title' => HomeSetting::getValue('vendors_title', 'Top Vendors'),
        ];

        // Products data
        $featuredProducts = Product::with(['vendor', 'category', 'images'])
            ->active()
            ->featured()
            ->latest()
            ->take(8)
            ->get();

        $bestsellerProducts = Product::with(['vendor', 'category', 'images'])
            ->active()
            ->bestseller()
            ->latest()
            ->take(8)
            ->get();

        $categories = Category::with('children')
            ->active()
            ->root()
            ->ordered()
            ->take(6)
            ->get();

        $vendors = Vendor::where('status', 'approved')
            ->whereNotNull('verified_at')
            ->orderBy('rating', 'desc')
            ->take(6)
            ->get();

        return view('home', compact(
            'heroSettings',
            'banners',
            'promotions',
            'sectionTitles',
            'featuredProducts',
            'bestsellerProducts',
            'categories',
            'vendors'
        ));
    }

    public function marketplace(Request $request)
    {
        $query = Product::with(['vendor', 'category', 'images'])
            ->active();

        // Filter by category
        if ($request->has('category') && !empty($request->category)) {
            $query->byCategory($request->category);
        }

        // Filter by vendor
        if ($request->has('vendor') && !empty($request->vendor)) {
            $query->byVendor($request->vendor);
        }

        // Filter by price range
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Search by name or description
        if ($request->has('search') && !empty($request->search)) {
            $search = strtolower($request->search);
            $query->where(function($q) use ($search) {
                $q->whereRaw('LOWER(name) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(description) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(short_description) LIKE ?', ["%{$search}%"]);
            });
        }

        // Sort products
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            case 'rating':
                $query->orderBy('rating', 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        $products = $query->paginate(12);
        $categories = Category::active()->root()->ordered()->get();
        $vendors = Vendor::where('status', 'approved')->get();

        return view('marketplace', compact('products', 'categories', 'vendors'));
    }

    public function product($slug)
    {
        $product = Product::with(['vendor', 'category', 'images', 'variants', 'reviews.user', 'tags'])
            ->where('slug', $slug)
            ->active()
            ->firstOrFail();

        $relatedProducts = Product::with(['vendor', 'category', 'images'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->active()
            ->take(4)
            ->get();

        return view('product', compact('product', 'relatedProducts'));
    }

    public function category($slug)
    {
        $category = Category::where('slug', $slug)
            ->active()
            ->with(['children', 'products.vendor', 'products.images'])
            ->firstOrFail();

        $products = $category->products()
            ->with(['vendor', 'images'])
            ->active()
            ->paginate(12);

        return view('category', compact('category', 'products'));
    }

    public function vendor($id)
    {
        $vendor = Vendor::with(['user', 'products.images', 'products.category'])
            ->where('id', $id)
            ->where('status', 'approved')
            ->firstOrFail();

        $products = $vendor->products()
            ->with(['images', 'category'])
            ->active()
            ->paginate(12);

        return view('vendor', compact('vendor', 'products'));
    }

    public function vendorContact(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
        ]);

        $vendor = Vendor::findOrFail($id);

        // Here you would typically:
        // 1. Send an email to the vendor
        // 2. Store the message in a database
        // 3. Send a confirmation email to the customer
        
        // For now, we'll just redirect back with a success message
        return redirect()->back()->with('success', 'Thank you for your message! We will get back to you soon.');
    }
}
