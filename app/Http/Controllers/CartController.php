<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function index()
    {
        $cart = Session::get('cart', []);
        $cartItems = [];
        $total = 0;

        foreach ($cart as $item) {
            $product = Product::with(['vendor', 'images'])->find($item['product_id']);
            if ($product) {
                $variant = null;
                if (isset($item['variant_id'])) {
                    $variant = ProductVariant::find($item['variant_id']);
                }

                $price = $variant ? $variant->price : $product->price;
                $itemTotal = $price * $item['quantity'];
                $total += $itemTotal;

                $cartItems[] = [
                    'product' => $product,
                    'variant' => $variant,
                    'quantity' => $item['quantity'],
                    'price' => $price,
                    'total' => $itemTotal,
                ];
            }
        }

        return view('cart.index', compact('cartItems', 'total'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'nullable|exists:product_variants,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        
        if (!$product->is_active) {
            return redirect()->back()->with('error', 'Product is not available.');
        }

        if ($product->quantity < $request->quantity) {
            return redirect()->back()->with('error', 'Insufficient stock.');
        }

        $cart = Session::get('cart', []);
        $cartKey = $request->product_id . '_' . ($request->variant_id ?? '0');

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $request->quantity;
        } else {
            $cart[$cartKey] = [
                'product_id' => $request->product_id,
                'variant_id' => $request->variant_id,
                'quantity' => $request->quantity,
            ];
        }

        Session::put('cart', $cart);

        // Return JSON response for AJAX requests
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Product added to cart successfully!',
                'cart_count' => array_sum(array_column($cart, 'quantity'))
            ]);
        }

        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }

    public function update(Request $request)
    {
        $request->validate([
            'cart_key' => 'required|string',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = Session::get('cart', []);
        
        if (isset($cart[$request->cart_key])) {
            $cart[$request->cart_key]['quantity'] = $request->quantity;
            Session::put('cart', $cart);
            
            return redirect()->back()->with('success', 'Cart updated successfully!');
        }

        return redirect()->back()->with('error', 'Item not found in cart.');
    }

    public function remove(Request $request, $cartKey)
    {
        $cart = Session::get('cart', []);
        
        if (isset($cart[$cartKey])) {
            unset($cart[$cartKey]);
            Session::put('cart', $cart);
            
            // Return JSON response for AJAX requests
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Item removed from cart successfully!',
                    'cart_count' => array_sum(array_column($cart, 'quantity'))
                ]);
            }
            
            return redirect()->back()->with('success', 'Item removed from cart successfully!');
        }

        // Return JSON response for AJAX requests
        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found in cart.'
            ], 404);
        }

        return redirect()->back()->with('error', 'Item not found in cart.');
    }

    public function clear()
    {
        Session::forget('cart');
        return redirect()->back()->with('success', 'Cart cleared successfully!');
    }

    public function getCartCount()
    {
        $cart = Session::get('cart', []);
        $count = 0;
        
        foreach ($cart as $item) {
            $count += $item['quantity'];
        }
        
        return response()->json(['count' => $count]);
    }

    public function preview()
    {
        $cart = Session::get('cart', []);
        $cartItems = [];
        $total = 0;

        foreach ($cart as $cartKey => $item) {
            $product = Product::with(['vendor', 'images'])->find($item['product_id']);
            if ($product) {
                $variant = null;
                if (isset($item['variant_id'])) {
                    $variant = ProductVariant::find($item['variant_id']);
                }

                $price = $variant ? $variant->price : $product->price;
                $itemTotal = $price * $item['quantity'];
                $total += $itemTotal;

                $cartItems[] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'image' => $product->main_image ? asset('storage/' . $product->main_image) : 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHZpZXdCb3g9IjAgMCA0MCA0MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjQwIiBoZWlnaHQ9IjQwIiBmaWxsPSIjRjNGNEY2Ii8+CjxwYXRoIGQ9Ik0yMCAyNUMyMi41IDI1IDI0IDIzLjUgMjQgMjFDMjQgMTguNSAyMi41IDE3IDIwIDE3QzE3LjUgMTcgMTYgMTguNSAxNiAyMUMxNiAyMy41IDE3LjUgMjUgMjAgMjVaIiBmaWxsPSIjOUNBM0FGIi8+CjxwYXRoIGQ9Ik0zMCAzM0gxMEM5LjI0IDMzIDggMzAuNzYgOCAyOFYyOEM4IDI1LjI0IDkuMjQgMjMgMTAgMjNIMzBDMzAuNzYgMjMgMzIgMjUuMjQgMzIgMjhWMjhDMzIgMzAuNzYgMzAuNzYgMzMgMzAgMzNaIiBmaWxsPSIjOUNBM0FGIi8+Cjwvc3ZnPg==',
                    'quantity' => $item['quantity'],
                    'price' => number_format($price, 2),
                    'total' => number_format($itemTotal, 2),
                    'cart_key' => $cartKey,
                ];
            }
        }

        return response()->json([
            'items' => $cartItems,
            'total' => number_format($total, 2),
            'count' => count($cartItems)
        ]);
    }
}
