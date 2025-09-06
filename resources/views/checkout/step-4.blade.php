@extends('layouts.app')

@section('title', 'Checkout - Step 4: Confirmation')

@section('content')
<div class="checkout-page">
    <div class="container">
        <div class="checkout-header">
            <h1>Checkout</h1>
            <p>Review your order details before confirming</p>
        </div>

        <!-- Checkout Stepper -->
        <x-checkout-stepper :current-step="4" />

        <div class="checkout-content">
            <!-- Order Confirmation -->
            <div class="confirmation-section">
                <div class="confirmation-header">
                    <div class="success-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h2>Order Confirmation</h2>
                    <p>Please review your order details below before proceeding</p>
                </div>

                <!-- Order Items -->
                <div class="order-items-section">
                    <h3>Order Items</h3>
                    <div class="order-items">
                        @foreach($processedCartItems as $item)
                        <div class="order-item">
                            <div class="item-image">
                                @if($item['product']->main_image)
                                    <img src="{{ asset('storage/' . $item['product']->main_image) }}" 
                                         alt="{{ $item['product']->name }}"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <div class="image-placeholder" style="display: none;">
                                        <i class="fas fa-image"></i>
                                    </div>
                                @else
                                    <div class="image-placeholder">
                                        <i class="fas fa-image"></i>
                                    </div>
                                @endif
                            </div>

                            <div class="item-details">
                                <h4 class="item-name">{{ $item['product']->name }}</h4>
                                @if($item['variant'])
                                    <p class="item-variant">{{ $item['variant']->name }}</p>
                                @endif
                                <p class="item-price">${{ number_format($item['price'], 2) }} each</p>
                            </div>

                            <div class="item-quantity">
                                <span class="quantity-label">Quantity:</span>
                                <span class="quantity-value">{{ $item['quantity'] }}</span>
                            </div>

                            <div class="item-total">
                                <span class="total-label">Total:</span>
                                <span class="total-value">${{ number_format($item['total'], 2) }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Delivery Information -->
                <div class="delivery-info-section">
                    <h3>Delivery Information</h3>
                    <div class="info-card">
                        <div class="info-header">
                            <div class="info-icon">
                                <i class="fas fa-truck"></i>
                            </div>
                            <div class="info-title">
                                <h4>{{ ucfirst(str_replace('_', ' ', $checkoutData['delivery_type'] ?? 'address_delivery')) }}</h4>
                                <p>{{ $checkoutData['delivery_type'] === 'location_delivery' ? 'Location-based delivery' : 'Address-based delivery' }}</p>
                            </div>
                        </div>
                        
                        <div class="info-details">
                            <div class="detail-row">
                                <span class="detail-label">Recipient:</span>
                                <span class="detail-value">{{ $checkoutData['shipping_address']['first_name'] ?? 'N/A' }} {{ $checkoutData['shipping_address']['last_name'] ?? '' }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Address:</span>
                                <span class="detail-value">{{ $checkoutData['shipping_address']['address'] ?? 'N/A' }}</span>
                            </div>
                            @if($checkoutData['shipping_address']['address_line_2'] ?? '')
                            <div class="detail-row">
                                <span class="detail-label">Address Line 2:</span>
                                <span class="detail-value">{{ $checkoutData['shipping_address']['address_line_2'] }}</span>
                            </div>
                            @endif
                            <div class="detail-row">
                                <span class="detail-label">City:</span>
                                <span class="detail-value">{{ $checkoutData['shipping_address']['city'] ?? 'N/A' }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">State:</span>
                                <span class="detail-value">{{ $checkoutData['shipping_address']['state'] ?? 'N/A' }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">ZIP Code:</span>
                                <span class="detail-value">{{ $checkoutData['shipping_address']['zip_code'] ?? 'N/A' }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Country:</span>
                                <span class="detail-value">{{ $checkoutData['shipping_address']['country'] ?? 'N/A' }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Phone:</span>
                                <span class="detail-value">{{ $checkoutData['shipping_address']['phone'] ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Information -->
                <div class="payment-info-section">
                    <h3>Payment Information</h3>
                    <div class="info-card">
                        <div class="info-header">
                            <div class="info-icon">
                                <i class="fas fa-credit-card"></i>
                            </div>
                            <div class="info-title">
                                <h4>{{ ucfirst(str_replace('_', ' ', $checkoutData['payment_method'] ?? 'card_payment')) }}</h4>
                                <p>Payment method selected</p>
                            </div>
                        </div>
                        
                        <div class="info-details">
                            <div class="detail-row">
                                <span class="detail-label">Payment Method:</span>
                                <span class="detail-value">{{ ucfirst(str_replace('_', ' ', $checkoutData['payment_method'] ?? 'card_payment')) }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Status:</span>
                                <span class="detail-value status-pending">Pending</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="order-summary-section">
                    <h3>Order Summary</h3>
                    <div class="summary-card">
                        <div class="summary-row">
                            <span>Subtotal:</span>
                            <span>${{ number_format($subtotal, 2) }}</span>
                        </div>
                        
                        <div class="summary-row">
                            <span>Shipping:</span>
                            <span>${{ number_format($shipping, 2) }}</span>
                        </div>
                        
                        <div class="summary-row">
                            <span>Tax:</span>
                            <span>${{ number_format($tax, 2) }}</span>
                        </div>
                        
                        <div class="summary-divider"></div>
                        
                        <div class="summary-row total-row">
                            <span>Total:</span>
                            <span class="total-amount">${{ number_format($total, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Confirmation Actions -->
                <div class="confirmation-actions">
                    <form action="{{ route('checkout.process', 4) }}" method="POST" class="confirmation-form">
                        @csrf
                        <div class="action-buttons">
                            <a href="{{ route('checkout.step', 3) }}" class="back-btn">
                                <i class="fas fa-arrow-left"></i>
                                Back to Payment
                            </a>
                            <button type="submit" class="confirm-btn">
                                <i class="fas fa-check"></i>
                                Confirm Order
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.checkout-page {
    padding: 2rem 0;
    background-color: #f8fafc;
    min-height: 100vh;
}

.checkout-header {
    text-align: center;
    margin-bottom: 2rem;
}

.checkout-header h1 {
    font-size: 2.5rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 0.5rem;
}

.checkout-header p {
    font-size: 1.1rem;
    color: #64748b;
}

.checkout-content {
    max-width: 800px;
    margin: 0 auto;
}

.confirmation-section {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    border: 1px solid #e2e8f0;
}

.confirmation-header {
    text-align: center;
    margin-bottom: 3rem;
    padding-bottom: 2rem;
    border-bottom: 2px solid #f1f5f9;
}

.success-icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3);
}

.success-icon i {
    font-size: 2.5rem;
    color: white;
}

.confirmation-header h2 {
    font-size: 2rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 0.5rem;
}

.confirmation-header p {
    font-size: 1.1rem;
    color: #64748b;
    margin: 0;
}

.order-items-section, .delivery-info-section, .payment-info-section, .order-summary-section {
    margin-bottom: 2rem;
}

.order-items-section h3, .delivery-info-section h3, .payment-info-section h3, .order-summary-section h3 {
    font-size: 1.5rem;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 1.5rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #f1f5f9;
}

.order-items {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.order-item {
    display: grid;
    grid-template-columns: 80px 1fr auto auto;
    gap: 1rem;
    align-items: center;
    padding: 1.5rem;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    background: #fafafa;
}

.item-image {
    width: 80px;
    height: 80px;
    border-radius: 8px;
    overflow: hidden;
    background: #f1f5f9;
}

.item-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.image-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #94a3b8;
    font-size: 1.5rem;
}

.item-details {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.item-name {
    font-size: 1.1rem;
    font-weight: 600;
    color: #1e293b;
    margin: 0;
}

.item-variant {
    font-size: 0.9rem;
    color: #64748b;
    margin: 0;
}

.item-price {
    font-size: 1rem;
    font-weight: 500;
    color: #3b82f6;
    margin: 0;
}

.item-quantity {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.25rem;
}

.quantity-label {
    font-size: 0.875rem;
    color: #64748b;
}

.quantity-value {
    font-size: 1.1rem;
    font-weight: 600;
    color: #1e293b;
}

.item-total {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.25rem;
}

.total-label {
    font-size: 0.875rem;
    color: #64748b;
}

.total-value {
    font-size: 1.1rem;
    font-weight: 700;
    color: #1e293b;
}

.info-card {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 1.5rem;
}

.info-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
}

.info-icon {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: #3b82f6;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
}

.info-title h4 {
    font-size: 1.2rem;
    font-weight: 600;
    color: #1e293b;
    margin: 0 0 0.25rem 0;
}

.info-title p {
    color: #64748b;
    margin: 0;
}

.info-details {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.detail-label {
    font-weight: 500;
    color: #64748b;
}

.detail-value {
    font-weight: 500;
    color: #1e293b;
}

.status-pending {
    background: #fef3c7;
    color: #92400e;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 500;
}

.summary-card {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 1.5rem;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    color: #64748b;
}

.summary-row.total-row {
    font-size: 1.3rem;
    font-weight: 700;
    color: #1e293b;
}

.summary-divider {
    height: 1px;
    background: #e2e8f0;
    margin: 1rem 0;
}

.total-amount {
    font-size: 1.3rem;
    font-weight: 700;
    color: #1e293b;
}

.confirmation-actions {
    margin-top: 3rem;
    padding-top: 2rem;
    border-top: 2px solid #f1f5f9;
}

.action-buttons {
    display: flex;
    gap: 1rem;
}

.back-btn {
    flex: 1;
    background: #f8fafc;
    color: #64748b;
    border: 1px solid #e2e8f0;
    padding: 1rem 2rem;
    border-radius: 8px;
    font-weight: 500;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    transition: all 0.2s ease;
}

.back-btn:hover {
    background: #e2e8f0;
    color: #374151;
}

.confirm-btn {
    flex: 2;
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
    border: none;
    padding: 1rem 2rem;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
    font-size: 1.1rem;
}

.confirm-btn:hover {
    background: linear-gradient(135deg, #059669 0%, #047857 100%);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3);
}

@media (max-width: 768px) {
    .checkout-content {
        padding: 0 1rem;
    }
    
    .confirmation-section {
        padding: 1.5rem;
    }
    
    .order-item {
        grid-template-columns: 1fr;
        gap: 1rem;
        text-align: center;
    }
    
    .item-image {
        width: 100%;
        height: 200px;
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .detail-row {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.25rem;
    }
}
</style>
@endsection
