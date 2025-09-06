@extends('layouts.app')

@section('title', 'Checkout - Step 2: Delivery')

@section('content')
<div class="checkout-page">
    <div class="container">
        <div class="checkout-header">
            <h1>Checkout</h1>
            <p>Choose your delivery method and location</p>
        </div>

        <!-- Checkout Stepper -->
        <x-checkout-stepper :current-step="2" />

        <form action="{{ route('checkout.process', 2) }}" method="POST" class="checkout-form">
            @csrf
            
            <div class="checkout-content">
                <!-- Delivery Options -->
                <div class="delivery-section">
                    <div class="section-header">
                        <h2>Delivery Method</h2>
                        <p>Choose how you want your order delivered</p>
                    </div>

                    <div class="delivery-options">
                        <!-- Address Delivery Option -->
                        <div class="delivery-option">
                            <input type="radio" name="delivery_type" id="address_delivery" value="address_delivery" 
                                   {{ (old('delivery_type', $checkoutData['delivery_type'] ?? '') === 'address_delivery') ? 'checked' : '' }}>
                            <label for="address_delivery" class="delivery-card">
                                <div class="option-icon">
                                    <i class="fas fa-home"></i>
                                </div>
                                <div class="option-content">
                                    <h3>Address Delivery</h3>
                                    <p>Deliver to your saved addresses</p>
                                    <span class="shipping-info">Standard shipping: $5.99</span>
                                </div>
                            </label>
                        </div>

                        <!-- Location Delivery Option -->
                        <div class="delivery-option">
                            <input type="radio" name="delivery_type" id="location_delivery" value="location_delivery"
                                   {{ (old('delivery_type', $checkoutData['delivery_type'] ?? '') === 'location_delivery') ? 'checked' : '' }}>
                            <label for="location_delivery" class="delivery-card">
                                <div class="option-icon">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div class="option-content">
                                    <h3>Location Delivery</h3>
                                    <p>Deliver to any location on the map</p>
                                    <span class="shipping-info">Dynamic shipping based on distance</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Address Delivery Section -->
                    <div id="address-delivery-section" class="delivery-details" style="display: none;">
                        <h3>Select Address</h3>
                        
                        @if($addresses->count() > 0)
                            <div class="address-options">
                                @foreach($addresses as $address)
                                    <div class="address-option">
                                        <input type="radio" name="shipping_address_id" 
                                               id="address_{{ $address->id }}" 
                                               value="{{ $address->id }}"
                                               {{ (old('shipping_address_id', $checkoutData['shipping_address']['id'] ?? '') == $address->id) ? 'checked' : '' }}>
                                        <label for="address_{{ $address->id }}" class="address-card">
                                            <div class="address-header">
                                                <span class="address-label">{{ $address->label ?: 'Address' }}</span>
                                                @if($address->is_default)
                                                    <span class="default-badge">Default</span>
                                                @endif
                                            </div>
                                            <div class="address-details">
                                                <p><strong>{{ $address->full_name }}</strong></p>
                                                <p>{{ $address->address_line_1 }}</p>
                                                @if($address->address_line_2)
                                                    <p>{{ $address->address_line_2 }}</p>
                                                @endif
                                                <p>{{ $address->city }}, {{ $address->state }} {{ $address->zip_code }}</p>
                                                <p>{{ $address->country }}</p>
                                                <p>Phone: {{ $address->phone }}</p>
                                            </div>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="no-addresses">
                                <p>No addresses found. Please add an address first.</p>
                                <a href="{{ route('addresses.create') }}" class="btn btn-primary">Add Address</a>
                            </div>
                        @endif
                    </div>

                    <!-- Location Delivery Section -->
                    <div id="location-delivery-section" class="delivery-details" style="display: none;">
                        <h3>Select Delivery Location</h3>
                        <p class="map-description">Click on the map to set your delivery location. The distance from our vendor location will be calculated automatically.</p>
                        
                        <!-- Map Container -->
                        <div class="map-container">
                            <div id="delivery-map" class="delivery-map"></div>
                            <div class="map-info">
                                <div class="distance-info">
                                    <span class="distance-label">Distance:</span>
                                    <span id="distance-value" class="distance-value">Click on map to calculate</span>
                                </div>
                                <div class="shipping-cost">
                                    <span class="cost-label">Shipping Cost:</span>
                                    <span id="shipping-cost" class="cost-value">$5.99</span>
                                </div>
                            </div>
                        </div>

                        <!-- Hidden fields for location data -->
                        <input type="hidden" name="delivery_latitude" id="delivery_latitude" value="{{ old('delivery_latitude', $checkoutData['shipping_address']['latitude'] ?? '') }}">
                        <input type="hidden" name="delivery_longitude" id="delivery_longitude" value="{{ old('delivery_longitude', $checkoutData['shipping_address']['longitude'] ?? '') }}">
                        
                        <!-- Location form fields -->
                        <div class="location-form">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="delivery_recipient_name">Recipient Name *</label>
                                    <input type="text" name="delivery_recipient_name" id="delivery_recipient_name" 
                                           value="{{ old('delivery_recipient_name', $checkoutData['shipping_address']['first_name'] ?? '') }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="delivery_phone">Phone Number *</label>
                                    <input type="tel" name="delivery_phone" id="delivery_phone" 
                                           value="{{ old('delivery_phone', $checkoutData['shipping_address']['phone'] ?? '') }}" required>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="delivery_address">Address *</label>
                                <input type="text" name="delivery_address" id="delivery_address" 
                                       value="{{ old('delivery_address', $checkoutData['shipping_address']['address'] ?? '') }}" required>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="delivery_city">City *</label>
                                    <input type="text" name="delivery_city" id="delivery_city" 
                                           value="{{ old('delivery_city', $checkoutData['shipping_address']['city'] ?? '') }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="delivery_state">State *</label>
                                    <input type="text" name="delivery_state" id="delivery_state" 
                                           value="{{ old('delivery_state', $checkoutData['shipping_address']['state'] ?? '') }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="delivery_zip_code">ZIP Code *</label>
                                    <input type="text" name="delivery_zip_code" id="delivery_zip_code" 
                                           value="{{ old('delivery_zip_code', $checkoutData['shipping_address']['zip_code'] ?? '') }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="delivery_country">Country *</label>
                                    <input type="text" name="delivery_country" id="delivery_country" 
                                           value="{{ old('delivery_country', $checkoutData['shipping_address']['country'] ?? '') }}" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="order-summary">
                    <div class="summary-card">
                        <h3>Order Summary</h3>
                        
                        <div class="summary-items">
                            @foreach($processedCartItems as $item)
                            <div class="summary-item">
                                <div class="item-info">
                                    <span class="item-name">{{ $item['product']->name }}</span>
                                    <span class="item-quantity">x{{ $item['quantity'] }}</span>
                                </div>
                                <span class="item-price">${{ number_format($item['total'], 2) }}</span>
                            </div>
                            @endforeach
                        </div>
                        
                        <div class="summary-row">
                            <span>Subtotal:</span>
                            <span>${{ number_format($subtotal, 2) }}</span>
                        </div>
                        
                        <div class="summary-row">
                            <span>Shipping:</span>
                            <span id="summary-shipping">${{ number_format($shipping, 2) }}</span>
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

                        <div class="form-actions">
                            <a href="{{ route('checkout.step', 1) }}" class="back-btn">
                                <i class="fas fa-arrow-left"></i>
                                Back to Cart
                            </a>
                            <button type="submit" class="proceed-btn">
                                <i class="fas fa-arrow-right"></i>
                                Proceed to Payment
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

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

.delivery-section {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    border: 1px solid #e2e8f0;
}

.section-header {
    margin-bottom: 2rem;
}

.section-header h2 {
    font-size: 1.5rem;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 0.5rem;
}

.section-header p {
    color: #64748b;
    margin: 0;
}

.delivery-options {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-bottom: 2rem;
}

.delivery-option {
    position: relative;
}

.delivery-option input[type="radio"] {
    position: absolute;
    opacity: 0;
    pointer-events: none;
}

.delivery-card {
    display: flex;
    align-items: center;
    padding: 1.5rem;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.3s ease;
    background: white;
}

.delivery-card:hover {
    border-color: #3b82f6;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.1);
}

.delivery-option input[type="radio"]:checked + .delivery-card {
    border-color: #3b82f6;
    background: #f0f9ff;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
}

.option-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    transition: all 0.3s ease;
}

.delivery-option input[type="radio"]:checked + .delivery-card .option-icon {
    background: #3b82f6;
    color: white;
}

.option-icon i {
    font-size: 1.5rem;
    color: #64748b;
}

.delivery-option input[type="radio"]:checked + .delivery-card .option-icon i {
    color: white;
}

.option-content h3 {
    font-size: 1.2rem;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 0.25rem;
}

.option-content p {
    color: #64748b;
    margin-bottom: 0.25rem;
}

.shipping-info {
    font-size: 0.9rem;
    color: #10b981;
    font-weight: 500;
}

.delivery-details {
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 2px solid #f1f5f9;
}

.delivery-details h3 {
    font-size: 1.3rem;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 1rem;
}

.map-description {
    color: #64748b;
    margin-bottom: 1.5rem;
}

.map-container {
    margin-bottom: 2rem;
}

.delivery-map {
    width: 100%;
    height: 400px;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
}

.map-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 1rem;
    padding: 1rem;
    background: #f8fafc;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
}

.distance-info, .shipping-cost {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.25rem;
}

.distance-label, .cost-label {
    font-size: 0.875rem;
    color: #64748b;
    font-weight: 500;
}

.distance-value, .cost-value {
    font-size: 1.1rem;
    font-weight: 700;
    color: #1e293b;
}

.location-form {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.form-group label {
    font-weight: 500;
    color: #374151;
}

.form-group input {
    padding: 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 1rem;
    transition: border-color 0.2s ease;
}

.form-group input:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.address-options {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.address-option {
    position: relative;
}

.address-option input[type="radio"] {
    position: absolute;
    opacity: 0;
    pointer-events: none;
}

.address-card {
    display: block;
    padding: 1.5rem;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    background: white;
}

.address-card:hover {
    border-color: #3b82f6;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.1);
}

.address-option input[type="radio"]:checked + .address-card {
    border-color: #3b82f6;
    background: #f0f9ff;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
}

.address-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
}

.address-label {
    font-weight: 600;
    color: #1e293b;
}

.default-badge {
    background: #10b981;
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 500;
}

.address-details p {
    margin: 0.25rem 0;
    color: #64748b;
}

.no-addresses {
    text-align: center;
    padding: 2rem;
    background: #f8fafc;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
}

.no-addresses p {
    color: #64748b;
    margin-bottom: 1rem;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border-radius: 6px;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s ease;
}

.btn-primary {
    background: #3b82f6;
    color: white;
}

.btn-primary:hover {
    background: #2563eb;
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

.summary-items {
    margin-bottom: 1rem;
}

.summary-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
    border-bottom: 1px solid #f1f5f9;
}

.item-info {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.item-name {
    font-weight: 500;
    color: #1e293b;
}

.item-quantity {
    font-size: 0.875rem;
    color: #64748b;
}

.item-price {
    font-weight: 600;
    color: #1e293b;
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

.form-actions {
    display: flex;
    gap: 1rem;
    margin-top: 1.5rem;
}

.back-btn {
    flex: 1;
    background: #f8fafc;
    color: #64748b;
    border: 1px solid #e2e8f0;
    padding: 0.75rem 1.5rem;
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

.proceed-btn {
    flex: 2;
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
}

.proceed-btn:hover {
    background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

@media (max-width: 768px) {
    .checkout-content {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .order-summary {
        position: static;
    }
    
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .form-actions {
        flex-direction: column;
    }
}
</style>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
let map, vendorMarker, deliveryMarker, polyline;
let vendorLocation = { lat: 6.9271, lng: 79.8612 }; // Colombo, Sri Lanka
let deliveryLocation = null;

document.addEventListener('DOMContentLoaded', function() {
    // Handle delivery type changes
    const addressDelivery = document.getElementById('address_delivery');
    const locationDelivery = document.getElementById('location_delivery');
    const addressSection = document.getElementById('address-delivery-section');
    const locationSection = document.getElementById('location-delivery-section');

    function toggleDeliverySections() {
        if (addressDelivery.checked) {
            addressSection.style.display = 'block';
            locationSection.style.display = 'none';
        } else if (locationDelivery.checked) {
            addressSection.style.display = 'none';
            locationSection.style.display = 'block';
            initMap();
        } else {
            addressSection.style.display = 'none';
            locationSection.style.display = 'none';
        }
    }

    addressDelivery.addEventListener('change', toggleDeliverySections);
    locationDelivery.addEventListener('change', toggleDeliverySections);

    // Initialize based on current selection
    toggleDeliverySections();
});

function initMap() {
    if (map) return; // Already initialized

    // Initialize map
    map = L.map('delivery-map').setView(vendorLocation, 12);

    // Add tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Add vendor marker
    vendorMarker = L.marker(vendorLocation, {
        icon: L.divIcon({
            className: 'vendor-marker',
            html: '<div class="marker-icon vendor"><i class="fas fa-store"></i></div>',
            iconSize: [30, 30],
            iconAnchor: [15, 30]
        })
    }).addTo(map);

    // Add vendor popup
    vendorMarker.bindPopup('<strong>Vendor Location</strong><br>Colombo, Sri Lanka').openPopup();

    // Add click listener to map
    map.on('click', function(e) {
        setDeliveryLocation(e.latlng);
    });

    // Load existing delivery location if available
    const existingLat = document.getElementById('delivery_latitude').value;
    const existingLng = document.getElementById('delivery_longitude').value;
    
    if (existingLat && existingLng) {
        const existingLocation = { lat: parseFloat(existingLat), lng: parseFloat(existingLng) };
        setDeliveryLocation(existingLocation);
    }
}

function setDeliveryLocation(latlng) {
    deliveryLocation = latlng;

    // Remove existing delivery marker
    if (deliveryMarker) {
        map.removeLayer(deliveryMarker);
    }

    // Add new delivery marker
    deliveryMarker = L.marker(latlng, {
        icon: L.divIcon({
            className: 'delivery-marker',
            html: '<div class="marker-icon delivery"><i class="fas fa-map-marker-alt"></i></div>',
            iconSize: [30, 30],
            iconAnchor: [15, 30]
        })
    }).addTo(map);

    // Add delivery popup
    deliveryMarker.bindPopup('<strong>Delivery Location</strong><br>Click to change location').openPopup();

    // Update hidden fields
    document.getElementById('delivery_latitude').value = latlng.lat;
    document.getElementById('delivery_longitude').value = latlng.lng;

    // Calculate distance and update UI
    calculateDistance();

    // Reverse geocode to fill address fields
    reverseGeocode(latlng);
}

function calculateDistance() {
    if (!deliveryLocation) return;

    const distance = calculateDistanceBetweenPoints(vendorLocation, deliveryLocation);
    const shippingCost = calculateShippingCost(distance);

    // Update UI
    document.getElementById('distance-value').textContent = distance.toFixed(2) + ' km';
    document.getElementById('shipping-cost').textContent = '$' + shippingCost.toFixed(2);
    document.getElementById('summary-shipping').textContent = '$' + shippingCost.toFixed(2);
}

function calculateDistanceBetweenPoints(point1, point2) {
    const R = 6371; // Earth's radius in kilometers
    const dLat = (point2.lat - point1.lat) * Math.PI / 180;
    const dLng = (point2.lng - point1.lng) * Math.PI / 180;
    const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
              Math.cos(point1.lat * Math.PI / 180) * Math.cos(point2.lat * Math.PI / 180) *
              Math.sin(dLng/2) * Math.sin(dLng/2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    return R * c;
}

function calculateShippingCost(distance) {
    let baseCost = 5.99;
    
    if (distance > 50) {
        baseCost += 15.00;
    } else if (distance > 25) {
        baseCost += 10.00;
    } else if (distance > 10) {
        baseCost += 5.00;
    }
    
    return baseCost;
}

function reverseGeocode(latlng) {
    // Simple reverse geocoding - in production, use a proper geocoding service
    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${latlng.lat}&lon=${latlng.lng}`)
        .then(response => response.json())
        .then(data => {
            if (data.address) {
                // Fill address fields
                document.getElementById('delivery_address').value = data.display_name.split(',')[0] || '';
                document.getElementById('delivery_city').value = data.address.city || data.address.town || data.address.village || '';
                document.getElementById('delivery_state').value = data.address.state || '';
                document.getElementById('delivery_zip_code').value = data.address.postcode || '';
                document.getElementById('delivery_country').value = data.address.country || '';
            }
        })
        .catch(error => {
            console.log('Geocoding failed:', error);
        });
}
</script>

<style>
.marker-icon {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 14px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.3);
}

.marker-icon.vendor {
    background: #10b981;
}

.marker-icon.delivery {
    background: #ef4444;
}

.leaflet-popup-content {
    font-family: inherit;
}

.leaflet-popup-content strong {
    color: #1e293b;
}
</style>
@endsection
