@extends('layouts.app')

@section('title', 'Order Confirmation - Grozzoery')

@section('content')
<div class="checkout-success-page">
    <div class="container">
        <!-- Success Header -->
        <div class="success-header">
            <div class="success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h1>Order Placed Successfully!</h1>
            <p>Thank you for your purchase. Your order has been confirmed.</p>
        </div>

        <!-- Order Summary -->
        <div class="order-summary-card">
            <h2>Order Summary</h2>
            
            @foreach($orders as $order)
            <div class="order-item">
                <div class="order-header">
                    <div>
                        <h3>Order #{{ $order->order_number }}</h3>
                        <p class="vendor-name">Vendor: {{ $order->vendor->business_name ?? 'Unknown Vendor' }}</p>
                    </div>
                    <div class="order-total">
                        <p class="total-amount">${{ number_format($order->total_amount, 2) }}</p>
                        <p class="order-date">{{ $order->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
                
                <div class="order-status-grid">
                    <div class="status-item">
                        <span class="status-label">Status:</span>
                        <span class="status-badge status-pending">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                    <div class="status-item">
                        <span class="status-label">Payment:</span>
                        <span class="status-badge status-payment">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </div>
                    <div class="status-item">
                        <span class="status-label">Method:</span>
                        <span class="payment-method">{{ ucfirst($order->payment_method) }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Order Details -->
        <div class="order-details-card">
            <h2>Order Details</h2>
            
            @foreach($orders as $order)
            <div class="order-detail-section">
                <h3>Order #{{ $order->order_number }}</h3>
                
                <!-- Order Items -->
                <div class="order-items-list">
                    @foreach($order->items as $item)
                    <div class="order-item-row">
                        <div class="item-info">
                            <div class="item-icon">
                                <i class="fas fa-box"></i>
                            </div>
                            <div>
                                <p class="item-name">{{ $item->product_name }}</p>
                                <p class="item-quantity">Qty: {{ $item->quantity }}</p>
                            </div>
                        </div>
                        <div class="item-pricing">
                            <p class="item-price">${{ number_format($item->unit_price, 2) }}</p>
                            <p class="item-total">Total: ${{ number_format($item->total_price, 2) }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <!-- Order Totals -->
                <div class="order-totals">
                    <div class="total-row">
                        <span class="total-label">Subtotal:</span>
                        <span class="total-value">${{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    <div class="total-row">
                        <span class="total-label">Shipping:</span>
                        <span class="total-value">${{ number_format($order->shipping_amount, 2) }}</span>
                    </div>
                    <div class="total-row">
                        <span class="total-label">Tax:</span>
                        <span class="total-value">${{ number_format($order->tax_amount, 2) }}</span>
                    </div>
                    <div class="total-row total-final">
                        <span class="total-label">Total:</span>
                        <span class="total-amount-final">${{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Shipping Information -->
        @if(isset($orders[0]->shipping_address))
        <div class="shipping-info-card">
            <h2>Shipping Information</h2>
            <div class="addresses-grid">
                <div class="address-section">
                    <h3>Shipping Address</h3>
                    <div class="address-details">
                        <p>{{ $orders[0]->shipping_address['first_name'] }} {{ $orders[0]->shipping_address['last_name'] }}</p>
                        <p>{{ $orders[0]->shipping_address['address'] }}</p>
                        <p>{{ $orders[0]->shipping_address['city'] }}, {{ $orders[0]->shipping_address['state'] }} {{ $orders[0]->shipping_address['zip_code'] }}</p>
                        <p>{{ $orders[0]->shipping_address['country'] }}</p>
                        <p class="phone-number">Phone: {{ $orders[0]->shipping_address['phone'] }}</p>
                    </div>
                </div>
                <div class="address-section">
                    <h3>Billing Address</h3>
                    <div class="address-details">
                        <p>{{ $orders[0]->billing_address['first_name'] }} {{ $orders[0]->billing_address['last_name'] }}</p>
                        <p>{{ $orders[0]->billing_address['address'] }}</p>
                        <p>{{ $orders[0]->billing_address['city'] }}, {{ $orders[0]->billing_address['state'] }} {{ $orders[0]->billing_address['zip_code'] }}</p>
                        <p>{{ $orders[0]->billing_address['country'] }}</p>
                        <p class="phone-number">Phone: {{ $orders[0]->billing_address['phone'] }}</p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Action Buttons -->
        <div class="action-buttons">
            <a href="{{ route('home') }}" class="continue-browsing-btn">
                Continue Browsing
            </a>
            <p class="confirmation-note">
                You will receive an email confirmation shortly. 
                You can also track your order status in your account dashboard.
            </p>
        </div>
    </div>
</div>
@endsection
