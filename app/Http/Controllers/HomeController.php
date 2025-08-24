<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Vendor;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
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

        return view('home', compact('featuredProducts', 'bestsellerProducts', 'categories', 'vendors'));
    }

    public function shop(Request $request)
    {
        $query = Product::with(['vendor', 'category', 'images'])
            ->active();

        // Filter by category
        if ($request->has('category')) {
            $query->byCategory($request->category);
        }

        // Filter by vendor
        if ($request->has('vendor')) {
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
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%");
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

        return view('shop', compact('products', 'categories', 'vendors'));
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
}
