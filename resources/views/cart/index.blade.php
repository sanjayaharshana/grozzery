@extends('layouts.app')

@section('title', 'Shopping Cart - Grozzoery')

@section('content')
<div class="cart-page">
    <div class="container">
        <!-- Cart Header -->
        <div class="cart-header">
            <h1>Shopping Cart</h1>
            <p>Review your items and proceed to checkout</p>
        </div>
        
        <!-- Order Process Step Indicator -->
        <x-step-indicator :current-step="1" />
        
        @if(count($cartItems) > 0)
        <div class="cart-content">
            <!-- Cart Items -->
            <div class="cart-items">
                @foreach($cartItems as $item)
                <div class="cart-item">
                    <div class="cart-item-image">
                        <img src="{{ asset('storage/' . $item['product']->main_image) }}" 
                             alt="{{ $item['product']->name }}"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="image-placeholder product" style="display: none;">
                            <i class="fas fa-image"></i>
                        </div>
                    </div>
                    
                    <div class="cart-item-details">
                        <h3>{{ $item['product']->name }}</h3>
                        <div class="cart-item-vendor">{{ $item['product']->vendor->business_name }}</div>
                        <div class="cart-item-price">${{ number_format($item['price'], 2) }}</div>
                    </div>
                    
                    <div class="cart-item-quantity">
                        <div class="quantity-controls">
                            <button type="button" class="quantity-btn" onclick="updateQuantity(this, -1)">-</button>
                            <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" class="quantity-input" readonly>
                            <button type="button" class="quantity-btn" onclick="updateQuantity(this, 1)">+</button>
                        </div>
                    </div>
                    
                    <div class="cart-item-actions">
                        <form action="{{ route('cart.update') }}" method="POST" class="update-form" style="display: none;">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="cart_key" value="{{ $item['product']->id . '_' . ($item['variant'] ? $item['variant']->id : '0') }}">
                            <input type="hidden" name="quantity" class="update-quantity-input">
                        </form>
                        <button type="button" class="cart-item-remove" onclick="removeItem('{{ $item['product']->id . '_' . ($item['variant'] ? $item['variant']->id : '0') }}')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- Cart Summary -->
            <div class="cart-summary">
                <h2>Order Summary</h2>
                
                <div class="summary-item">
                    <span class="summary-label">Subtotal:</span>
                    <span class="summary-value">${{ number_format($total, 2) }}</span>
                </div>
                
                <div class="summary-item">
                    <span class="summary-label">Shipping:</span>
                    <span class="summary-value">$10.00</span>
                </div>
                
                <div class="summary-item">
                    <span class="summary-label">Tax:</span>
                    <span class="summary-value">${{ number_format($total * 0.085, 2) }}</span>
                </div>
                
                <div class="summary-item">
                    <span class="summary-label">Total:</span>
                    <span class="summary-total">${{ number_format($total + 10 + ($total * 0.085), 2) }}</span>
                </div>
                
                <div class="cart-actions">
                    <a href="{{ route('checkout.index') }}" class="checkout-btn">
                        Proceed to Checkout
                    </a>
                    <a href="{{ route('marketplace') }}" class="continue-browsing">
                        Continue Browsing
                    </a>
                </div>
            </div>
        </div>
        @else
        <!-- Empty Cart -->
        <div class="empty-cart">
            <div class="empty-cart-icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <h2>Your cart is empty</h2>
            <p>Looks like you haven't added any products to your cart yet.</p>
            <a href="{{ route('marketplace') }}" class="btn btn-primary">
                Start Browsing
            </a>
        </div>
        @endif
    </div>
</div>

<!-- Image Zoom Modal -->
<div class="image-zoom-modal" id="imageZoomModal">
    <span class="image-zoom-close" onclick="closeImageZoom()">&times;</span>
    <img id="zoomedImage" src="" alt="Zoomed Image">
</div>

<script>
function updateQuantity(button, change) {
    const input = button.parentElement.querySelector('.quantity-input');
    const newQuantity = Math.max(1, parseInt(input.value) + change);
    input.value = newQuantity;
    
    // Update the hidden input for form submission
    const updateForm = button.closest('.cart-item').querySelector('.update-form');
    const updateQuantityInput = updateForm.querySelector('.update-quantity-input');
    updateQuantityInput.value = newQuantity;
    
    // Submit the form
    updateForm.submit();
}

function removeItem(cartKey) {
    if (confirm('Are you sure you want to remove this item from your cart?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("cart.remove", ":cartKey") }}'.replace(':cartKey', cartKey);
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        
        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();
    }
}

function openImageZoom(imageSrc) {
    const modal = document.getElementById('imageZoomModal');
    const zoomedImage = document.getElementById('zoomedImage');
    zoomedImage.src = imageSrc;
    modal.style.display = 'block';
}

function closeImageZoom() {
    const modal = document.getElementById('imageZoomModal');
    modal.style.display = 'none';
}

// Close modal when clicking outside the image
document.getElementById('imageZoomModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeImageZoom();
    }
});
</script>
@endsection
