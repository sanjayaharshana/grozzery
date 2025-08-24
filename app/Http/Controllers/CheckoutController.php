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
        $request->validate([
            'shipping_address_id' => 'required|exists:addresses,id',
            'billing_address_id' => 'required|exists:addresses,id',
            'payment_method' => 'required|in:credit_card,paypal,cash_on_delivery',
        ]);

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

        // Verify address ownership
        $shippingAddress = $user->addresses()->findOrFail($request->shipping_address_id);
        $billingAddress = $user->addresses()->findOrFail($request->billing_address_id);

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
                
                $shipping = 5.99; // Fixed shipping cost per vendor
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
                    'subtotal' => $subtotal,
                    'shipping_amount' => $shipping,
                    'tax_amount' => $tax,
                    'total_amount' => $total,
                    'shipping_address' => [
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
                    ],
                    'billing_address' => [
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
                    ],
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

    public function success()
    {
        $orders = session('orders', []);
        
        if (empty($orders)) {
            return redirect()->route('home');
        }

        return view('checkout.success', compact('orders'));
    }
}
