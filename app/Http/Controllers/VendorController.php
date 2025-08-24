<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VendorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('vendor');
    }

    public function dashboard()
    {
        $vendor = Auth::user()->vendor;
        $totalProducts = $vendor->products()->count();
        $totalOrders = $vendor->orders()->count();
        $totalSales = $vendor->orders()->where('payment_status', 'paid')->sum('total_amount');
        $pendingOrders = $vendor->orders()->where('status', 'pending')->count();

        $recentOrders = $vendor->orders()
            ->with(['user', 'items.product'])
            ->latest()
            ->take(5)
            ->get();

        $topProducts = $vendor->products()
            ->with('category')
            ->orderBy('total_sales', 'desc')
            ->take(5)
            ->get();

        return view('vendor.dashboard', compact(
            'vendor',
            'totalProducts',
            'totalOrders',
            'totalSales',
            'pendingOrders',
            'recentOrders',
            'topProducts'
        ));
    }

    public function profile()
    {
        $vendor = Auth::user()->vendor;
        return view('vendor.profile', compact('vendor'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'business_name' => 'required|string|max:255',
            'business_description' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $vendor = Auth::user()->vendor;
        $data = $request->only([
            'business_name', 'business_description', 'phone', 'address',
            'city', 'state', 'zip_code', 'country'
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            if ($vendor->logo) {
                Storage::delete('public/' . $vendor->logo);
            }
            $data['logo'] = $request->file('logo')->store('vendors/logos', 'public');
        }

        // Handle banner upload
        if ($request->hasFile('banner')) {
            if ($vendor->banner) {
                Storage::delete('public/' . $vendor->banner);
            }
            $data['banner'] = $request->file('banner')->store('vendors/banners', 'public');
        }

        $vendor->update($data);

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

    public function products()
    {
        $vendor = Auth::user()->vendor;
        $products = $vendor->products()
            ->with(['category', 'images'])
            ->latest()
            ->paginate(15);

        return view('vendor.products.index', compact('products'));
    }

    public function createProduct()
    {
        $categories = Category::active()->root()->ordered()->get();
        return view('vendor.products.create', compact('categories'));
    }

    public function storeProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string',
            'sku' => 'required|string|unique:products,sku',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'min_quantity' => 'required|integer|min:1',
            'max_quantity' => 'nullable|integer|min:1',
            'weight' => 'nullable|numeric|min:0',
            'dimensions' => 'nullable|string',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'is_bestseller' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $vendor = Auth::user()->vendor;

        $product = $vendor->products()->create([
            'name' => $request->name,
            'slug' => \Str::slug($request->name),
            'description' => $request->description,
            'short_description' => $request->short_description,
            'sku' => $request->sku,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'compare_price' => $request->compare_price,
            'cost_price' => $request->cost_price,
            'quantity' => $request->quantity,
            'min_quantity' => $request->min_quantity,
            'max_quantity' => $request->max_quantity,
            'weight' => $request->weight,
            'dimensions' => $request->dimensions,
            'is_active' => $request->boolean('is_active', true),
            'is_featured' => $request->boolean('is_featured', false),
            'is_bestseller' => $request->boolean('is_bestseller', false),
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
        ]);

        // Handle product images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $imagePath = $image->store('products', 'public');
                $product->images()->create([
                    'image_path' => $imagePath,
                    'alt_text' => $request->name,
                    'sort_order' => $index,
                    'is_primary' => $index === 0,
                ]);
            }
        }

        return redirect()->route('vendor.products.index')
            ->with('success', 'Product created successfully!');
    }

    public function editProduct($id)
    {
        $vendor = Auth::user()->vendor;
        $product = $vendor->products()->with('images')->findOrFail($id);
        $categories = Category::active()->root()->ordered()->get();

        return view('vendor.products.edit', compact('product', 'categories'));
    }

    public function updateProduct(Request $request, $id)
    {
        $vendor = Auth::user()->vendor;
        $product = $vendor->products()->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string',
            'sku' => 'required|string|unique:products,sku,' . $id,
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'min_quantity' => 'required|integer|min:1',
            'max_quantity' => 'nullable|integer|min:1',
            'weight' => 'nullable|numeric|min:0',
            'dimensions' => 'nullable|string',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'is_bestseller' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
        ]);

        $product->update([
            'name' => $request->name,
            'slug' => \Str::slug($request->name),
            'description' => $request->description,
            'short_description' => $request->short_description,
            'sku' => $request->sku,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'compare_price' => $request->compare_price,
            'cost_price' => $request->cost_price,
            'quantity' => $request->quantity,
            'min_quantity' => $request->min_quantity,
            'max_quantity' => $request->max_quantity,
            'weight' => $request->weight,
            'dimensions' => $request->dimensions,
            'is_active' => $request->boolean('is_active', true),
            'is_featured' => $request->boolean('is_featured', false),
            'is_bestseller' => $request->boolean('is_bestseller', false),
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
        ]);

        return redirect()->route('vendor.products.index')
            ->with('success', 'Product updated successfully!');
    }

    public function orders()
    {
        $vendor = Auth::user()->vendor;
        $orders = $vendor->orders()
            ->with(['user', 'items.product'])
            ->latest()
            ->paginate(15);

        return view('vendor.orders.index', compact('orders'));
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
            'tracking_number' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $vendor = Auth::user()->vendor;
        $order = $vendor->orders()->findOrFail($id);

        $order->update([
            'status' => $request->status,
            'tracking_number' => $request->tracking_number,
            'notes' => $request->notes,
        ]);

        if ($request->status === 'delivered') {
            $order->update(['delivered_at' => now()]);
        }

        return redirect()->back()->with('success', 'Order status updated successfully!');
    }
}
