<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        $cartItems = session('cart', []);
        
        if (empty($cartItems)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
        }

        // Process cart items to ensure they have all required fields
        $processedCartItems = [];
        $subtotal = 0;
        
        foreach ($cartItems as $cartKey => $item) {
            // Get fresh product data
            $product = Product::find($item['product_id']);
            if (!$product) continue;
            
            // Get variant if exists
            $variant = null;
            if (isset($item['variant_id']) && $item['variant_id']) {
                $variant = ProductVariant::find($item['variant_id']);
            }
            
            // Calculate price
            $price = $variant ? $variant->price : $product->price;
            
            // Create processed item
            $processedItem = [
                'cart_key' => $cartKey,
                'product_id' => $product->id,
                'variant_id' => $variant ? $variant->id : null,
                'product' => $product,
                'variant' => $variant,
                'quantity' => $item['quantity'],
                'price' => $price,
                'total' => $price * $item['quantity']
            ];
            
            $processedCartItems[] = $processedItem;
            $subtotal += $processedItem['total'];
        }
        
        if (empty($processedCartItems)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
        }

        // Get user's addresses
        $addresses = $user->addresses()->orderBy('is_default', 'desc')->get();
        
        // Calculate totals
        $shipping = 5.99; // Fixed shipping cost
        $tax = $subtotal * 0.08; // 8% tax
        $total = $subtotal + $shipping + $tax;

        return view('checkout.index', compact('processedCartItems', 'subtotal', 'shipping', 'tax', 'total', 'addresses'));
    }

    public function store(Request $request)
    {
        // Validate delivery type and related fields
        $request->validate([
            'delivery_type' => 'required|in:location_based,address_based',
            'payment_method' => 'required|in:credit_card,paypal,cash_on_delivery',
        ]);

        // Additional validation based on delivery type
        if ($request->delivery_type === 'address_based') {
            $request->validate([
                'shipping_address_id' => 'required|exists:addresses,id',
                'billing_address_id' => 'required|exists:addresses,id',
            ]);
        } else {
            $request->validate([
                'delivery_latitude' => 'required|numeric|between:-90,90',
                'delivery_longitude' => 'required|numeric|between:-180,180',
                'delivery_address' => 'required|string|max:500',
                'delivery_city' => 'required|string|max:100',
                'delivery_state' => 'required|string|max:100',
                'delivery_zip_code' => 'required|string|max:20',
                'delivery_country' => 'required|string|max:100',
                'delivery_phone' => 'required|string|max:20',
                'delivery_recipient_name' => 'required|string|max:255',
            ]);
        }

        $user = Auth::user();
        $cartItems = session('cart', []);
        
        if (empty($cartItems)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
        }

        // Process cart items to ensure they have all required fields
        $processedCartItems = [];
        foreach ($cartItems as $cartKey => $item) {
            // Get fresh product data
            $product = Product::find($item['product_id']);
            if (!$product) continue;
            
            // Get variant if exists
            $variant = null;
            if (isset($item['variant_id']) && $item['variant_id']) {
                $variant = ProductVariant::find($item['variant_id']);
            }
            
            // Calculate price
            $price = $variant ? $variant->price : $product->price;
            
            // Create processed item
            $processedItem = [
                'cart_key' => $cartKey,
                'product_id' => $product->id,
                'variant_id' => $variant ? $variant->id : null,
                'product' => $product,
                'variant' => $variant,
                'quantity' => $item['quantity'],
                'price' => $price,
                'total' => $price * $item['quantity']
            ];
            
            $processedCartItems[] = $processedItem;
        }
        
        if (empty($processedCartItems)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
        }

        // Prepare shipping and billing addresses based on delivery type
        if ($request->delivery_type === 'address_based') {
            // Verify address ownership
            $shippingAddress = $user->addresses()->findOrFail($request->shipping_address_id);
            $billingAddress = $user->addresses()->findOrFail($request->billing_address_id);
            
            $shippingAddressData = [
                'first_name' => $shippingAddress->first_name,
                'last_name' => $shippingAddress->last_name,
                'address' => $shippingAddress->address_line_1,
                'address_line_2' => $shippingAddress->address_line_2,
                'city' => $shippingAddress->city,
                'state' => $shippingAddress->state,
                'zip_code' => $shippingAddress->zip_code,
                'country' => $shippingAddress->country,
                'phone' => $shippingAddress->phone,
                'latitude' => $shippingAddress->latitude,
                'longitude' => $shippingAddress->longitude,
            ];
            
            $billingAddressData = [
                'first_name' => $billingAddress->first_name,
                'last_name' => $billingAddress->last_name,
                'address' => $billingAddress->address_line_1,
                'address_line_2' => $billingAddress->address_line_2,
                'city' => $billingAddress->city,
                'state' => $billingAddress->state,
                'zip_code' => $billingAddress->zip_code,
                'country' => $billingAddress->country,
                'phone' => $billingAddress->phone,
                'latitude' => $billingAddress->latitude,
                'longitude' => $billingAddress->longitude,
            ];
        } else {
            // Location-based delivery - use provided coordinates and address
            $shippingAddressData = [
                'first_name' => $request->delivery_recipient_name,
                'last_name' => '',
                'address' => $request->delivery_address,
                'address_line_2' => '',
                'city' => $request->delivery_city,
                'state' => $request->delivery_state,
                'zip_code' => $request->delivery_zip_code,
                'country' => $request->delivery_country,
                'phone' => $request->delivery_phone,
                'latitude' => $request->delivery_latitude,
                'longitude' => $request->delivery_longitude,
            ];
            
            // For location-based delivery, billing address is same as shipping
            $billingAddressData = $shippingAddressData;
        }

        // Group items by vendor
        $vendorGroups = [];
        foreach ($processedCartItems as $item) {
            $vendorId = $item['product']->vendor_id;
            if (!isset($vendorGroups[$vendorId])) {
                $vendorGroups[$vendorId] = [];
            }
            $vendorGroups[$vendorId][] = $item;
        }

        $orders = [];

        DB::beginTransaction();
        try {
            foreach ($vendorGroups as $vendorId => $items) {
                // Calculate totals for this vendor
                $subtotal = 0;
                $shipping = 0;
                $tax = 0;
                
                foreach ($items as $item) {
                    $subtotal += $item['price'] * $item['quantity'];
                }
                
                // Calculate shipping based on delivery type and distance
                if ($request->delivery_type === 'location_based') {
                    $shipping = $this->calculateLocationBasedShipping($request->delivery_latitude, $request->delivery_longitude, $vendorId);
                } else {
                    $shipping = 5.99; // Fixed shipping cost for address-based delivery
                }
                
                $tax = $subtotal * 0.08; // 8% tax
                $total = $subtotal + $shipping + $tax;

                // Create order
                $order = Order::create([
                    'user_id' => $user->id,
                    'vendor_id' => $vendorId,
                    'order_number' => 'ORD-' . strtoupper(Str::random(8)),
                    'status' => 'pending',
                    'payment_status' => 'pending',
                    'payment_method' => $request->payment_method,
                    'delivery_type' => $request->delivery_type,
                    'subtotal' => $subtotal,
                    'shipping_amount' => $shipping,
                    'tax_amount' => $tax,
                    'total_amount' => $total,
                    'shipping_address' => $shippingAddressData,
                    'billing_address' => $billingAddressData,
                ]);

                // Create order items
                foreach ($items as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item['product']->id,
                        'product_name' => $item['product']->name,
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['price'],
                        'total_price' => $item['price'] * $item['quantity'],
                        'variant_id' => $item['variant'] ? $item['variant']->id : null,
                    ]);
                }

                $orders[] = $order;
            }

            // Clear cart
            session()->forget('cart');

            DB::commit();

            return redirect()->route('checkout.success')->with('orders', $orders);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred while processing your order. Please try again.');
        }
    }

    /**
     * Calculate shipping cost based on location and vendor
     */
    private function calculateLocationBasedShipping($latitude, $longitude, $vendorId)
    {
        // Get vendor location (you may need to add vendor coordinates to your vendor model)
        // For now, using a simple calculation
        $baseShipping = 5.99;
        
        // You can implement more complex distance-based calculations here
        // For example, using Haversine formula to calculate distance between coordinates
        // and then apply different rates based on distance zones
        
        // Simple example: if coordinates are far from city center, add extra cost
        $cityCenterLat = 40.7128; // Example: New York City
        $cityCenterLng = -74.0060;
        
        $distance = $this->calculateDistance($latitude, $longitude, $cityCenterLat, $cityCenterLng);
        
        if ($distance > 50) { // More than 50 km from city center
            $baseShipping += 10.00; // Add extra shipping cost
        } elseif ($distance > 25) { // More than 25 km from city center
            $baseShipping += 5.00; // Add moderate shipping cost
        }
        
        return $baseShipping;
    }

    /**
     * Calculate distance between two coordinates using Haversine formula
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // Earth's radius in kilometers
        
        $latDelta = deg2rad($lat2 - $lat1);
        $lonDelta = deg2rad($lon2 - $lon1);
        
        $a = sin($latDelta / 2) * sin($latDelta / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($lonDelta / 2) * sin($lonDelta / 2);
        
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        
        return $earthRadius * $c;
    }

    public function success()
    {
        $orders = session('orders', []);
        
        if (empty($orders)) {
            return redirect()->route('home');
        }

        return view('checkout.success', compact('orders'));
    }
}
