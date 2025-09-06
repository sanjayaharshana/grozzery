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

    public function show($step)
    {
        $user = Auth::user();
        $cartItems = session('cart', []);

        if (empty($cartItems)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
        }

        // Process cart items
        $processedCartItems = [];
        $subtotal = 0;

        foreach ($cartItems as $cartKey => $item) {
            $product = Product::find($item['product_id']);
            if (!$product) continue;

            $variant = null;
            if (isset($item['variant_id']) && $item['variant_id']) {
                $variant = ProductVariant::find($item['variant_id']);
            }

            $price = $variant ? $variant->price : $product->price;

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

        // Calculate totals
        $shipping = 5.99;
        $tax = $subtotal * 0.08;
        $total = $subtotal + $shipping + $tax;

        // Get checkout data from session
        $checkoutData = session('checkout_data', []);

        // Get user's addresses for delivery step
        $addresses = $user->addresses()->orderBy('is_default', 'desc')->get();

        return view('checkout.step-' . $step, compact(
            'step', 
            'processedCartItems', 
            'subtotal', 
            'shipping', 
            'tax', 
            'total', 
            'checkoutData',
            'addresses'
        ));
    }

    public function process(Request $request, $step)
    {
        $user = Auth::user();
        $cartItems = session('cart', []);

        if (empty($cartItems)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
        }

        // Get existing checkout data
        $checkoutData = session('checkout_data', []);

        switch ($step) {
            case 1:
                // Step 1: Review Cart - handle quantity updates
                if ($request->has('update_quantity')) {
                    $cartKey = $request->cart_key;
                    $quantity = $request->quantity;
                    
                    if ($quantity <= 0) {
                        unset($cartItems[$cartKey]);
                    } else {
                        $cartItems[$cartKey]['quantity'] = $quantity;
                    }
                    
                    session(['cart' => $cartItems]);
                    return redirect()->route('checkout.step', 1);
                }
                
                if ($request->has('remove_item')) {
                    $cartKey = $request->cart_key;
                    unset($cartItems[$cartKey]);
                    session(['cart' => $cartItems]);
                    return redirect()->route('checkout.step', 1);
                }
                
                // Proceed to step 2
                return redirect()->route('checkout.step', 2);

            case 2:
                // Step 2: Delivery
                $request->validate([
                    'delivery_type' => 'required|in:address_delivery,location_delivery',
                ]);

                if ($request->delivery_type === 'address_delivery') {
                    $request->validate([
                        'shipping_address_id' => 'required|exists:addresses,id',
                    ]);
                    
                    $address = $user->addresses()->findOrFail($request->shipping_address_id);
                    $checkoutData['delivery_type'] = 'address_delivery';
                    $checkoutData['shipping_address'] = [
                        'first_name' => $address->first_name,
                        'last_name' => $address->last_name,
                        'address' => $address->address_line_1,
                        'address_line_2' => $address->address_line_2,
                        'city' => $address->city,
                        'state' => $address->state,
                        'zip_code' => $address->zip_code,
                        'country' => $address->country,
                        'phone' => $address->phone,
                        'latitude' => $address->latitude,
                        'longitude' => $address->longitude,
                    ];
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
                    
                    $checkoutData['delivery_type'] = 'location_delivery';
                    $checkoutData['shipping_address'] = [
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
                }
                
                session(['checkout_data' => $checkoutData]);
                return redirect()->route('checkout.step', 3);

            case 3:
                // Step 3: Payment
                $request->validate([
                    'payment_method' => 'required|in:card_payment,credit_card,employee_id',
                ]);
                
                $checkoutData['payment_method'] = $request->payment_method;
                session(['checkout_data' => $checkoutData]);
                return redirect()->route('checkout.step', 4);

            case 4:
                // Step 4: Confirmation - Create order
                return $this->createOrder($checkoutData);

            default:
                return redirect()->route('checkout.step', 1);
        }
    }

    private function createOrder($checkoutData)
    {
        $user = Auth::user();
        $cartItems = session('cart', []);

        // Process cart items
        $processedCartItems = [];
        foreach ($cartItems as $cartKey => $item) {
            $product = Product::find($item['product_id']);
            if (!$product) continue;

            $variant = null;
            if (isset($item['variant_id']) && $item['variant_id']) {
                $variant = ProductVariant::find($item['variant_id']);
            }

            $price = $variant ? $variant->price : $product->price;

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

                // Calculate shipping
                if ($checkoutData['delivery_type'] === 'location_delivery') {
                    $shipping = $this->calculateLocationBasedShipping(
                        $checkoutData['shipping_address']['latitude'], 
                        $checkoutData['shipping_address']['longitude'], 
                        $vendorId
                    );
                } else {
                    $shipping = 5.99;
                }

                $tax = $subtotal * 0.08;
                $total = $subtotal + $shipping + $tax;

                // Create order
                $order = Order::create([
                    'user_id' => $user->id,
                    'vendor_id' => $vendorId,
                    'order_number' => 'ORD-' . strtoupper(Str::random(8)),
                    'status' => 'pending',
                    'payment_status' => 'pending',
                    'payment_method' => $checkoutData['payment_method'],
                    'delivery_type' => $checkoutData['delivery_type'],
                    'subtotal' => $subtotal,
                    'shipping_amount' => $shipping,
                    'tax_amount' => $tax,
                    'total_amount' => $total,
                    'shipping_address' => $checkoutData['shipping_address'],
                    'billing_address' => $checkoutData['shipping_address'], // Same as shipping for now
                ]);

                // Create order items
                foreach ($items as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item['product']->id,
                        'product_variant_id' => $item['variant'] ? $item['variant']->id : null,
                        'product_name' => $item['product']->name,
                        'product_sku' => $item['product']->sku ?? 'SKU-' . $item['product']->id,
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['price'],
                        'total_price' => $item['price'] * $item['quantity'],
                        'options' => null,
                    ]);
                }

                $orders[] = $order;
            }

            // Clear cart and checkout data
            session()->forget('cart');
            session()->forget('checkout_data');

            DB::commit();

            return redirect()->route('checkout.success')->with('orders', $orders);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
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
