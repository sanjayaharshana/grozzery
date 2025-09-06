@extends('layouts.app')

@section('title', 'Checkout - Step 1: Review Cart')

@section('content')
<div class="checkout-page">
    <div class="container">
        <div class="checkout-header">
            <h1>Checkout</h1>
            <p>Review your cart items and proceed to delivery</p>
        </div>

        <!-- Checkout Stepper -->
        <x-checkout-stepper :current-step="1" />

        <div class="checkout-content">
            <!-- Cart Items -->
            <div class="cart-section">
                <div class="section-header">
                    <h2>Your Cart Items</h2>
                    <span class="item-count">{{ count($processedCartItems) }} {{ count($processedCartItems) === 1 ? 'item' : 'items' }}</span>
                </div>

                <div class="cart-items">
                    @foreach($processedCartItems as $item)
                    <div class="cart-item" data-cart-key="{{ $item['cart_key'] }}">
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
                            <h3 class="item-name">{{ $item['product']->name }}</h3>
                            @if($item['variant'])
                                <p class="item-variant">{{ $item['variant']->name }}</p>
                            @endif
                            <p class="item-price">${{ number_format($item['price'], 2) }}</p>
                        </div>

                        <div class="item-quantity">
                            <label>Quantity:</label>
                            <div class="quantity-controls">
                                <button type="button" class="quantity-btn" onclick="updateQuantity('{{ $item['cart_key'] }}', {{ $item['quantity'] - 1 }})">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="number" 
                                       class="quantity-input" 
                                       value="{{ $item['quantity'] }}" 
                                       min="1" 
                                       onchange="updateQuantity('{{ $item['cart_key'] }}', this.value)">
                                <button type="button" class="quantity-btn" onclick="updateQuantity('{{ $item['cart_key'] }}', {{ $item['quantity'] + 1 }})">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>

                        <div class="item-total">
                            <span class="total-label">Total:</span>
                            <span class="total-amount">${{ number_format($item['total'], 2) }}</span>
                        </div>

                        <div class="item-actions">
                            <button type="button" class="remove-btn" onclick="removeItem('{{ $item['cart_key'] }}')">
                                <i class="fas fa-trash"></i>
                                Remove
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Order Summary -->
            <div class="order-summary">
                <div class="summary-card">
                    <h3>Order Summary</h3>
                    
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

                    <form action="{{ route('checkout.process', 1) }}" method="POST" class="checkout-form">
                        @csrf
                        <button type="submit" class="proceed-btn">
                            <i class="fas fa-arrow-right"></i>
                            Proceed to Delivery
                        </button>
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
    display: grid;
    grid-template-columns: 1fr 400px;
    gap: 2rem;
    max-width: 1200px;
    margin: 0 auto;
}

.cart-section {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    border: 1px solid #e2e8f0;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #f1f5f9;
}

.section-header h2 {
    font-size: 1.5rem;
    font-weight: 600;
    color: #1e293b;
    margin: 0;
}

.item-count {
    background: #3b82f6;
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 500;
}

.cart-items {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.cart-item {
    display: grid;
    grid-template-columns: 80px 1fr auto auto auto;
    gap: 1rem;
    align-items: center;
    padding: 1.5rem;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    background: #fafafa;
    transition: all 0.3s ease;
}

.cart-item:hover {
    border-color: #3b82f6;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.1);
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
    font-weight: 600;
    color: #3b82f6;
    margin: 0;
}

.item-quantity {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
}

.item-quantity label {
    font-size: 0.875rem;
    font-weight: 500;
    color: #64748b;
}

.quantity-controls {
    display: flex;
    align-items: center;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    overflow: hidden;
}

.quantity-btn {
    width: 32px;
    height: 32px;
    border: none;
    background: #f8fafc;
    color: #64748b;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}

.quantity-btn:hover {
    background: #e2e8f0;
    color: #1e293b;
}

.quantity-input {
    width: 50px;
    height: 32px;
    border: none;
    text-align: center;
    font-weight: 500;
    background: white;
}

.item-total {
    text-align: center;
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.total-label {
    font-size: 0.875rem;
    color: #64748b;
}

.total-amount {
    font-size: 1.1rem;
    font-weight: 700;
    color: #1e293b;
}

.item-actions {
    display: flex;
    justify-content: center;
}

.remove-btn {
    background: #ef4444;
    color: white;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    font-size: 0.875rem;
    font-weight: 500;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.2s ease;
}

.remove-btn:hover {
    background: #dc2626;
    transform: translateY(-1px);
}

.order-summary {
    position: sticky;
    top: 2rem;
    height: fit-content;
}

.summary-card {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    border: 1px solid #e2e8f0;
}

.summary-card h3 {
    font-size: 1.5rem;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 1.5rem;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    color: #64748b;
}

.summary-row.total-row {
    font-size: 1.2rem;
    font-weight: 700;
    color: #1e293b;
}

.summary-divider {
    height: 1px;
    background: #e2e8f0;
    margin: 1rem 0;
}

.proceed-btn {
    width: 100%;
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    color: white;
    border: none;
    padding: 1rem 2rem;
    border-radius: 8px;
    font-size: 1.1rem;
    font-weight: 600;
    cursor: pointer;
    margin-top: 1.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
}

.proceed-btn:hover {
    background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);
}

@media (max-width: 768px) {
    .checkout-content {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .order-summary {
        position: static;
    }
    
    .cart-item {
        grid-template-columns: 1fr;
        gap: 1rem;
        text-align: center;
    }
    
    .item-image {
        width: 100%;
        height: 200px;
    }
}
</style>

<script>
function updateQuantity(cartKey, quantity) {
    if (quantity < 1) {
        removeItem(cartKey);
        return;
    }
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("checkout.process", 1) }}';
    
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = '{{ csrf_token() }}';
    
    const updateInput = document.createElement('input');
    updateInput.type = 'hidden';
    updateInput.name = 'update_quantity';
    updateInput.value = '1';
    
    const cartKeyInput = document.createElement('input');
    cartKeyInput.type = 'hidden';
    cartKeyInput.name = 'cart_key';
    cartKeyInput.value = cartKey;
    
    const quantityInput = document.createElement('input');
    quantityInput.type = 'hidden';
    quantityInput.name = 'quantity';
    quantityInput.value = quantity;
    
    form.appendChild(csrfToken);
    form.appendChild(updateInput);
    form.appendChild(cartKeyInput);
    form.appendChild(quantityInput);
    
    document.body.appendChild(form);
    form.submit();
}

function removeItem(cartKey) {
    if (confirm('Are you sure you want to remove this item from your cart?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("checkout.process", 1) }}';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        const removeInput = document.createElement('input');
        removeInput.type = 'hidden';
        removeInput.name = 'remove_item';
        removeInput.value = '1';
        
        const cartKeyInput = document.createElement('input');
        cartKeyInput.type = 'hidden';
        cartKeyInput.name = 'cart_key';
        cartKeyInput.value = cartKey;
        
        form.appendChild(csrfToken);
        form.appendChild(removeInput);
        form.appendChild(cartKeyInput);
        
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection
