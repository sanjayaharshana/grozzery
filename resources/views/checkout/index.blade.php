@extends('layouts.app')

@section('title', 'Checkout - Grozzoery')

@section('content')
<div class="checkout-page">
    <div class="container">
        <div class="checkout-header">
            <h1>Checkout</h1>
            <p>Complete your order by selecting your delivery option and payment method</p>
        </div>

        <!-- Order Process Step Indicator -->
        <x-step-indicator :current-step="2" />

        <form action="{{ route('checkout.store') }}" method="POST" class="checkout-form">
            @csrf

            <div class="checkout-content">
                <!-- Checkout Form -->
                <div class="checkout-form-section">
                    <!-- Delivery Type Selection -->
                    <div class="form-card">
                        <h3>Delivery Option</h3>
                        <div class="delivery-options">
                            <div class="delivery-option">
                                <input type="radio" name="delivery_type" id="address_based" value="address_based" checked>
                                <label for="address_based" class="delivery-card">
                                    <i class="fas fa-home"></i>
                                    <div class="option-content">
                                        <h5>Address-Based Delivery</h5>
                                        <p>Deliver to your saved addresses</p>
                                        <span class="shipping-info">Standard shipping: $5.99</span>
                                    </div>
                                </label>
                            </div>

                            <div class="delivery-option">
                                <input type="radio" name="delivery_type" id="location_based" value="location_based">
                                <label for="location_based" class="delivery-card">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <div class="option-content">
                                        <h5>Location-Based Delivery</h5>
                                        <p>Deliver to any location on the map</p>
                                        <span class="shipping-info">Dynamic shipping based on distance</span>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Address Selection (for address-based delivery) -->
                    <div id="address-based-section" class="form-card">
                        <h3>Address Selection</h3>

                        <!-- Shipping Address -->
                        <div class="address-section">
                            <h4>Shipping Address</h4>
                            <div class="address-options">
                                @if($addresses->count() > 0)
                                    @foreach($addresses as $address)
                                        <div class="address-option">
                                            <input type="radio" name="shipping_address_id"
                                                   id="shipping_{{ $address->id }}"
                                                   value="{{ $address->id }}"
                                                   {{ $address->is_default ? 'checked' : '' }}>
                                            <label for="shipping_{{ $address->id }}" class="address-card">
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
                                                    @if($address->latitude && $address->longitude)
                                                        <p class="coordinates">
                                                            <i class="fas fa-map-marker-alt"></i>
                                                            GPS: {{ $address->latitude }}, {{ $address->longitude }}
                                                        </p>
                                                    @endif
                                                </div>
                                            </label>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="no-addresses">
                                        <p>No addresses found. Please add an address first.</p>
                                        <a href="{{ route('addresses.create') }}" class="btn btn-primary">Add Address</a>
                                    </div>
                                @endif
                            </div>

                            <div class="address-actions">
                                <button type="button" class="btn btn-outline" onclick="openAddressModal()">
                                    <i class="fas fa-plus"></i> Add New Address
                                </button>
                            </div>
                        </div>

                        <!-- Billing Address -->
                        <div class="address-section">
                            <h4>Billing Address</h4>
                            <div class="billing-options">
                                <div class="billing-toggle">
                                    <input type="checkbox" id="same_as_shipping" checked>
                                    <label for="same_as_shipping">Same as shipping address</label>
                                </div>

                                <div id="billing-addresses" class="address-options" style="display: none;">
                                    @if($addresses->count() > 0)
                                        @foreach($addresses as $address)
                                            <div class="address-option">
                                                <input type="radio" name="billing_address_id"
                                                       id="billing_{{ $address->id }}"
                                                       value="{{ $address->id }}"
                                                       {{ $address->is_default ? 'checked' : '' }}>
                                                <label for="billing_{{ $address->id }}" class="address-card">
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
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Location-Based Delivery Section -->
                    <div id="location-based-section" class="form-card" style="display: none;">
                        <h3>Delivery Location</h3>
                        <p class="section-description">Select your delivery location on the map or use your current location. All delivery details will be automatically populated.</p>

                        <div class="location-inputs">
                            <div class="location-info">
                                <p><strong>Location-based delivery selected</strong></p>
                                <p>Click "Select on Map" to choose your delivery location, or use "Use Current Location" to automatically detect your position.</p>
                            </div>

                            <!-- Hidden fields that will be populated automatically -->
                            <input type="hidden" id="delivery_recipient_name" name="delivery_recipient_name">
                            <input type="hidden" id="delivery_phone" name="delivery_phone">
                            <input type="hidden" id="delivery_address" name="delivery_address">
                            <input type="hidden" id="delivery_city" name="delivery_city">
                            <input type="hidden" id="delivery_state" name="delivery_state">
                            <input type="hidden" id="delivery_zip_code" name="delivery_zip_code">
                            <input type="hidden" id="delivery_country" name="delivery_country">
                            <input type="hidden" id="delivery_latitude" name="delivery_latitude">
                            <input type="hidden" id="delivery_longitude" name="delivery_longitude">

                            <div class="location-actions">
                                <button type="button" class="btn btn-outline" onclick="openLocationModal()">
                                    <i class="fas fa-map-marker-alt"></i> Select on Map
                                </button>
                                <button type="button" class="btn btn-outline" onclick="useCurrentLocation()">
                                    <i class="fas fa-location-arrow"></i> Use Current Location
                                </button>
                                <button type="button" class="btn btn-primary" onclick="openLocationModal()">
                                    <i class="fas fa-map-marker-alt"></i> Choose Location
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="payment-section">
                        <h4>Payment Method</h4>
                        <div class="payment-options">
                            <div class="payment-option">
                                <input type="radio" name="payment_method" id="credit_card" value="credit_card" checked>
                                <label for="credit_card" class="payment-card">
                                    <i class="fas fa-credit-card"></i>
                                    <span>Credit Card</span>
                                </label>
                            </div>
                            <div class="payment-option">
                                <input type="radio" name="payment_method" id="paypal" value="paypal">
                                <label for="paypal" class="payment-card">
                                    <i class="fab fa-paypal"></i>
                                    <span>PayPal</span>
                                </label>
                            </div>
                            <div class="payment-option">
                                <input type="radio" name="payment_method" id="cash_on_delivery" value="cash_on_delivery">
                                <label for="cash_on_delivery" class="payment-card">
                                    <i class="fas fa-money-bill-wave"></i>
                                    <span>Cash on Delivery</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="order-summary-section">
                    <div class="order-summary-card">
                        <h3>Order Summary</h3>

                        <div class="order-items">
                            @foreach($processedCartItems as $item)
                            <div class="order-item">
                                <div class="order-item-image">
                                    @if($item['product']->main_image)
                                        <img src="{{ asset('storage/' . $item['product']->main_image) }}"
                                             alt="{{ $item['product']->name }}"
                                             class="order-item-img"
                                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div class="image-placeholder order" style="display: none;">
                                            <i class="fas fa-image"></i>
                                        </div>
                                    @else
                                        <div class="image-placeholder order">
                                            <i class="fas fa-image"></i>
                                        </div>
                                    @endif
                                </div>

                                <div class="order-item-details">
                                    <div class="order-item-name">{{ $item['product']->name }}</div>
                                    <div class="order-item-quantity">Qty: {{ $item['quantity'] }}</div>
                                </div>

                                <div class="order-item-price">${{ number_format($item['price'], 2) }}</div>
                            </div>
                            @endforeach
                        </div>

                        <div class="order-divider"></div>

                        <div class="order-totals">
                            <div class="total-row">
                                <span>Subtotal:</span>
                                <span>${{ number_format($subtotal, 2) }}</span>
                            </div>
                            <div class="total-row">
                                <span>Shipping:</span>
                                <span>${{ number_format($shipping, 2) }}</span>
                            </div>
                            <div class="total-row">
                                <span>Tax:</span>
                                <span>${{ number_format($tax, 2) }}</span>
                            </div>
                            <div class="total-row total-final">
                                <span>Total:</span>
                                <span>${{ number_format($total, 2) }}</span>
                            </div>
                        </div>

                        <button type="submit" class="place-order-btn">
                            <i class="fas fa-lock"></i> Place Order Securely
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Address Modal Dialog -->
<div id="addressModal" class="address-modal">
    <div class="address-modal-content">
        <div class="address-modal-header">
            <h3>Add New Address</h3>
            <button type="button" class="address-close" onclick="closeAddressModal()">&times;</button>
        </div>
        <div class="address-modal-body">
            <!-- Initial Selection Screen -->
            <div id="addressSelectionScreen" class="address-selection-screen">
                <h4>Choose how you want to add your address:</h4>
                <div class="selection-options">
                    <button type="button" class="selection-option" onclick="showManualForm()">
                        <i class="fas fa-edit"></i>
                        <div class="option-content">
                            <h5>Enter Address Manually</h5>
                            <p>Type in your address details manually</p>
                        </div>
                    </button>
                    <button type="button" class="selection-option" onclick="showMapView()">
                        <i class="fas fa-map-marker-alt"></i>
                        <div class="option-content">
                            <h5>Get Address from Location</h5>
                            <p>Select your location on the map</p>
                        </div>
                    </button>
                </div>
            </div>

            <!-- Manual Form View -->
            <div id="manualFormView" class="manual-form-view" style="display: none;">
                <div class="view-header">
                    <button type="button" class="back-btn" onclick="showSelectionScreen()">
                        <i class="fas fa-arrow-left"></i> Back to Options
                    </button>
                    <h4>Enter Address Details</h4>
                </div>

                <form id="addressForm" class="address-form-inline">
                    @csrf
                    <input type="hidden" name="latitude" id="modal_latitude">
                    <input type="hidden" name="longitude" id="modal_longitude">

                    <div class="form-row">
                        <div class="form-group">
                            <label for="modal_type">Address Type *</label>
                            <select name="type" id="modal_type" required>
                                <option value="">Select Type</option>
                                <option value="shipping">Shipping Address</option>
                                <option value="billing">Billing Address</option>
                                <option value="both">Both</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="modal_label">Label (Optional)</label>
                            <input type="text" name="label" id="modal_label" placeholder="e.g., Home, Office">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="modal_first_name">First Name *</label>
                            <input type="text" name="first_name" id="modal_first_name" required>
                        </div>

                        <div class="form-group">
                            <label for="modal_last_name">Last Name *</label>
                            <input type="text" name="last_name" id="modal_last_name" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="modal_phone">Phone Number *</label>
                            <input type="tel" name="phone" id="modal_phone" required>
                        </div>

                        <div class="form-group full-width">
                            <label for="modal_address_line_1">Street Address *</label>
                            <input type="text" name="address_line_1" id="modal_address_line_1" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="modal_address_line_2">Apartment, suite, etc.</label>
                            <input type="text" name="address_line_2" id="modal_address_line_2">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="modal_city">City *</label>
                            <input type="text" name="city" id="modal_city" required>
                        </div>

                        <div class="form-group">
                            <label for="modal_state">State/Province *</label>
                            <input type="text" name="state" id="modal_state" required>
                        </div>

                        <div class="form-group">
                            <label for="modal_zip_code">ZIP/Postal Code *</label>
                            <input type="text" name="zip_code" id="modal_zip_code" required>
                        </div>

                        <div class="form-group">
                            <label for="modal_country">Country *</label>
                            <input type="text" name="country" id="modal_country" required>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn btn-outline" onclick="closeAddressModal()">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save Address
                        </button>
                    </div>
                </form>
            </div>

            <!-- Map View -->
            <div id="mapView" class="map-view" style="display: none;">
                <div class="view-header">
                    <button type="button" class="back-btn" onclick="showSelectionScreen()">
                        <i class="fas fa-arrow-left"></i> Back to Options
                    </button>
                    <h4>Select Your Location</h4>
                </div>

                <div class="map-section">
                    <div class="map-controls">
                        <div class="search-container">
                            <input type="text" id="modal_addressSearch" placeholder="Search for an address...">
                            <button type="button" class="btn btn-primary btn-sm" onclick="searchModalAddress()">
                                <i class="fas fa-search"></i> Search
                            </button>
                        </div>
                        <button type="button" class="btn btn-outline btn-sm" onclick="useModalCurrentLocation()">
                            <i class="fas fa-location-arrow"></i> Current Location
                        </button>
                    </div>

                    <div id="modal_map" class="modal-map"></div>
                    <div class="map-info">
                        <p id="modal_selectedLocation">Click on the map or search for an address</p>
                    </div>

                    <div class="map-form">
                        <h5>Address Details</h5>
                        <form id="mapAddressForm" class="address-form-inline">
                            @csrf
                            <input type="hidden" name="latitude" id="map_latitude">
                            <input type="hidden" name="longitude" id="map_longitude">

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="map_type">Address Type *</label>
                                    <select name="type" id="map_type" required>
                                        <option value="">Select Type</option>
                                        <option value="shipping">Shipping Address</option>
                                        <option value="billing">Billing Address</option>
                                        <option value="both">Both</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="map_label">Label (Optional)</label>
                                    <input type="text" name="label" id="map_label" placeholder="e.g., Home, Office">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="map_first_name">First Name *</label>
                                    <input type="text" name="first_name" id="map_first_name" required>
                                </div>

                                <div class="form-group">
                                    <label for="map_last_name">Last Name *</label>
                                    <input type="text" name="last_name" id="map_last_name" required>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="map_phone">Phone Number *</label>
                                    <input type="tel" name="phone" id="map_phone" required>
                                </div>

                                <div class="form-group full-width">
                                    <label for="map_address_line_1">Street Address *</label>
                                    <input type="text" name="address_line_1" id="map_address_line_1" required>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="map_address_line_2">Apartment, suite, etc.</label>
                                    <input type="text" name="address_line_2" id="map_address_line_2">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="map_city">City *</label>
                                    <input type="text" name="city" id="map_city" required>
                                </div>

                                <div class="form-group">
                                    <label for="map_state">State/Province *</label>
                                    <input type="text" name="state" id="map_state" required>
                                </div>

                                <div class="form-group">
                                    <label for="map_zip_code">ZIP/Postal Code *</label>
                                    <input type="text" name="zip_code" id="map_zip_code" required>
                                </div>

                                <div class="form-group">
                                    <label for="map_country">Country *</label>
                                    <input type="text" name="country" id="map_country" required>
                                </div>
                            </div>

                            <div class="form-actions">
                                <button type="button" class="btn btn-outline" onclick="closeAddressModal()">Cancel</button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Save Address
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Google Maps Modal for New Address -->
<div id="mapModal" class="map-modal">
    <div class="map-modal-content">
        <div class="map-modal-header">
            <h3>Select Location</h3>
            <button type="button" class="map-close" onclick="closeMapModal()">&times;</button>
        </div>
        <div class="map-modal-body">
            <div id="map" style="width: 100%; height: 400px;"></div>
            <div class="map-controls">
                <button type="button" class="btn btn-primary" onclick="confirmLocation()">
                    <i class="fas fa-check"></i> Confirm Location
                </button>
                <button type="button" class="btn btn-outline" onclick="closeMapModal()">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Location Selection Modal for Checkout -->
<div id="locationModal" class="location-modal">
    <div class="location-modal-content">
        <div class="location-modal-header">
            <h3>Select Delivery Location</h3>
            <button type="button" class="location-close" onclick="closeLocationModal()">&times;</button>
        </div>
        <div class="location-modal-body">
            <div class="location-search">
                <input type="text" id="locationSearch" placeholder="Search for an address...">
                <button type="button" class="btn btn-primary btn-sm" onclick="searchLocation()">
                    <i class="fas fa-search"></i> Search
                </button>
            </div>

            <div id="locationMap" class="location-map"></div>

            <div class="location-info">
                <p id="selectedLocationInfo">Click on the map or search for an address</p>
            </div>

            <div class="location-form">
                <h5>Location Details</h5>
                <p class="location-description">Click on the map or search for an address to select your delivery location. All address details will be automatically populated from the selected location.</p>
            </div>

            <div class="location-actions">
                <button type="button" class="btn btn-outline" onclick="closeLocationModal()">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="confirmLocationSelection()">
                    <i class="fas fa-check"></i> Confirm Location
                </button>
            </div>
        </div>
    </div>
</div>



@section('scripts')
<!-- Add Google Maps API -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAfaIXojUkRZePqWFdgvawI8MBWONn5Aw4&libraries=places"></script>

<script>
let map, marker, selectedLocation;
let modalMap, modalMarker, modalAutocomplete, modalSelectedLocation;
let locationMap, locationMarker, locationSelectedLocation;

// Handle delivery type switching
document.addEventListener('DOMContentLoaded', function() {
    const addressBasedRadio = document.getElementById('address_based');
    const locationBasedRadio = document.getElementById('location_based');
    const addressSection = document.getElementById('address-based-section');
    const locationSection = document.getElementById('location-based-section');

    function toggleDeliverySections() {
        if (addressBasedRadio.checked) {
            addressSection.style.display = 'block';
            locationSection.style.display = 'none';
            // Make address fields required
            document.querySelectorAll('#address-based-section input[required]').forEach(input => {
                input.required = true;
            });
            // Location fields are hidden, so no need to set required
        } else {
            addressSection.style.display = 'none';
            locationSection.style.display = 'block';
            // Make address fields not required
            document.querySelectorAll('#address-based-section input[required]').forEach(input => {
                input.required = false;
            });
            // Location fields are hidden inputs, validation will be handled by the controller

            // Reset location info to default state
            resetLocationInfo();
        }
    }

    // Add event listeners
    addressBasedRadio.addEventListener('change', toggleDeliverySections);
    locationBasedRadio.addEventListener('change', toggleDeliverySections);

    // Initialize on page load
    toggleDeliverySections();
});

// Debug: Check if modal exists when page loads
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Page loaded, checking modal elements...');
    console.log('Address Modal:', document.getElementById('addressModal'));
    console.log('Address Form:', document.getElementById('addressForm'));
    console.log('Modal Map Container:', document.getElementById('modal_map'));

    // Check if Google Maps API is loaded
    if (typeof google !== 'undefined' && google.maps) {
        console.log('✅ Google Maps API loaded successfully');
    } else {
        console.log('❌ Google Maps API not loaded yet');
    }

    // Check modal CSS properties
    const modal = document.getElementById('addressModal');
    if (modal) {
        const computedStyle = window.getComputedStyle(modal);
        console.log('Modal computed styles:', {
            display: computedStyle.display,
            position: computedStyle.position,
            zIndex: computedStyle.zIndex,
            visibility: computedStyle.visibility,
            opacity: computedStyle.opacity
        });

        // Check for potential CSS conflicts
        console.log('🔍 Checking for CSS conflicts...');
        const allElements = document.querySelectorAll('*');
        let highZIndexElements = [];

        allElements.forEach(el => {
            const zIndex = window.getComputedStyle(el).zIndex;
            if (zIndex !== 'auto' && parseInt(zIndex) > 1000) {
                highZIndexElements.push({
                    element: el.tagName + (el.id ? '#' + el.id : '') + (el.className ? '.' + el.className.split(' ').join('.') : ''),
                    zIndex: zIndex
                });
            }
        });

        if (highZIndexElements.length > 0) {
            console.log('⚠️ Found elements with high z-index that might interfere:', highZIndexElements);
        }
    }

    // Add global error handler
    window.addEventListener('error', function(e) {
        console.error('🚨 Global error caught:', e.error);
        console.error('Error details:', {
            message: e.message,
            filename: e.filename,
            lineno: e.lineno,
            colno: e.colno
        });
    });
});

// Initialize billing address toggle
document.getElementById('same_as_shipping').addEventListener('change', function() {
    const billingAddresses = document.getElementById('billing-addresses');
    if (this.checked) {
        billingAddresses.style.display = 'none';
        // Set billing address to same as shipping
        const shippingAddress = document.querySelector('input[name="shipping_address_id"]:checked');
        if (shippingAddress) {
            document.querySelector('input[name="billing_address_id"]').value = shippingAddress.value;
        }
    } else {
        billingAddresses.style.display = 'block';
    }
});

// Test function for debugging
function testModal() {
    console.log('🧪 Testing modal functionality...');
    const modal = document.getElementById('addressModal');

    if (!modal) {
        console.error('❌ Modal element not found!');
        return;
    }

    console.log('Modal element:', modal);
    console.log('Modal display style:', modal.style.display);
    console.log('Modal computed style:', window.getComputedStyle(modal).display);

    // Simple test - just show the modal
    console.log('🧪 Simple test - showing modal');
    modal.style.display = 'block';
    modal.style.visibility = 'visible';
    modal.style.opacity = '1';
    modal.style.zIndex = '9999';

    console.log('After setting styles:', {
        display: window.getComputedStyle(modal).display,
        visibility: window.getComputedStyle(modal).visibility,
        opacity: window.getComputedStyle(modal).opacity,
        zIndex: window.getComputedStyle(modal).zIndex
    });

    // Check if modal is visible
    setTimeout(() => {
        const rect = modal.getBoundingClientRect();
        console.log('Modal dimensions:', rect);
        console.log('Modal is visible:', rect.width > 0 && rect.height > 0);
    }, 100);
}

// Address Modal Functions
function openAddressModal() {
    console.log('🔓 Opening address modal...');
    const modal = document.getElementById('addressModal');

    if (!modal) {
        console.error('❌ Address modal not found!');
        return;
    }

    // Simple approach - just show the modal
    modal.style.display = 'block';
    modal.style.visibility = 'visible';
    modal.style.opacity = '1';
    modal.style.zIndex = '9999';

    console.log('✅ Modal display set to block');
    console.log('✅ Modal visibility set to visible');
    console.log('✅ Modal opacity set to 1');
    console.log('✅ Modal z-index set to 9999');

    // Log current state
    const computedStyle = window.getComputedStyle(modal);
    console.log('Current computed styles:', {
        display: computedStyle.display,
        position: computedStyle.position,
        zIndex: computedStyle.zIndex,
        visibility: computedStyle.visibility,
        opacity: computedStyle.opacity
    });

    // Show selection screen by default
    showSelectionScreen();
}

function closeAddressModal() {
    console.log('🔒 Closing address modal...');
    const modal = document.getElementById('addressModal');

    if (!modal) {
        console.error('❌ Address modal not found for closing!');
        return;
    }

    // Simple approach - just hide the modal
    modal.style.display = 'none';
    modal.style.visibility = 'hidden';
    modal.style.opacity = '0';

    console.log('✅ Modal display set to none');
    console.log('✅ Modal visibility set to hidden');
    console.log('✅ Modal opacity set to 0');

    // Reset forms and return to selection screen
    const manualForm = document.getElementById('addressForm');
    const mapForm = document.getElementById('mapAddressForm');
    if (manualForm) manualForm.reset();
    if (mapForm) mapForm.reset();

    const locationText = document.getElementById('modal_selectedLocation');
    if (locationText) locationText.textContent = 'Click on the map or search for an address';

    // Return to selection screen
    showSelectionScreen();
}

// View switching functions
function showSelectionScreen() {
    document.getElementById('addressSelectionScreen').style.display = 'block';
    document.getElementById('manualFormView').style.display = 'none';
    document.getElementById('mapView').style.display = 'none';
}

function showManualForm() {
    document.getElementById('addressSelectionScreen').style.display = 'none';
    document.getElementById('manualFormView').style.display = 'block';
    document.getElementById('mapView').style.display = 'none';
}

function showMapView() {
    document.getElementById('addressSelectionScreen').style.display = 'none';
    document.getElementById('manualFormView').style.display = 'none';
    document.getElementById('mapView').style.display = 'block';

    // Initialize map if not already done
    setTimeout(() => {
        if (typeof google !== 'undefined' && google.maps && !modalMap) {
            console.log('🗺️ Initializing modal map...');
            try {
                initModalMap();
                initModalAutocomplete();
                console.log('✅ Modal map initialized successfully');
            } catch (error) {
                console.error('❌ Error initializing modal map:', error);
            }
        } else if (modalMap) {
            console.log('🗺️ Modal map already initialized');
        } else {
            console.log('🗺️ Google Maps not loaded yet, skipping map initialization');
        }
    }, 100);
}

// Initialize modal map
function initModalMap() {
    const defaultLocation = { lat: 0, lng: 0 };

    modalMap = new google.maps.Map(document.getElementById('modal_map'), {
        zoom: 15,
        center: defaultLocation,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        styles: [
            {
                featureType: 'poi',
                elementType: 'labels',
                stylers: [{ visibility: 'off' }]
            }
        ]
    });

    modalMarker = new google.maps.Marker({
        position: defaultLocation,
        map: modalMap,
        draggable: true,
        title: 'Drag to adjust location'
    });

    // Add click listener to map
    modalMap.addListener('click', function(event) {
        setModalMapLocation(event.latLng);
        reverseGeocodeModal(event.latLng);
    });

    // Add drag listener to marker
    modalMarker.addListener('dragend', function(event) {
        reverseGeocodeModal(event.latLng);
    });
}

// Initialize modal autocomplete
function initModalAutocomplete() {
    modalAutocomplete = new google.maps.places.Autocomplete(
        document.getElementById('modal_addressSearch'),
        { types: ['address'] }
    );

    modalAutocomplete.addListener('place_changed', function() {
        const place = modalAutocomplete.getPlace();

        if (place.geometry) {
            setModalMapLocation(place.geometry.location);
            fillModalAddressFields(place);
        }
    });
}

// Set modal map location
function setModalMapLocation(latLng) {
    modalMap.setCenter(latLng);
    modalMarker.setPosition(latLng);
    modalSelectedLocation = latLng;

    // Update hidden fields for both forms
    const modalLat = document.getElementById('modal_latitude');
    const modalLng = document.getElementById('modal_longitude');
    const mapLat = document.getElementById('map_latitude');
    const mapLng = document.getElementById('map_longitude');

    if (modalLat) modalLat.value = latLng.lat();
    if (modalLng) modalLng.value = latLng.lng();
    if (mapLat) mapLat.value = latLng.lat();
    if (mapLng) mapLng.value = latLng.lng();

    // Update info display
    const locationText = document.getElementById('modal_selectedLocation');
    if (locationText) {
        locationText.textContent = `Selected: ${latLng.lat().toFixed(6)}, ${latLng.lng().toFixed(6)}`;
    }
}

// Reverse geocode for modal
function reverseGeocodeModal(latLng) {
    const geocoder = new google.maps.Geocoder();

    geocoder.geocode({ location: latLng }, function(results, status) {
        if (status === 'OK' && results[0]) {
            fillModalAddressFields(results[0]);
        }
    });
}

// Fill modal address fields
function fillModalAddressFields(place) {
    let streetNumber = '', route = '', city = '', state = '', zipCode = '', country = '';

    for (const component of place.address_components) {
        const type = component.types[0];

        switch (type) {
            case 'street_number':
                streetNumber = component.long_name;
                break;
            case 'route':
                route = component.long_name;
                break;
            case 'locality':
                city = component.long_name;
                break;
            case 'administrative_area_level_1':
                state = component.long_name;
                break;
            case 'postal_code':
                zipCode = component.long_name;
                break;
            case 'country':
                country = component.long_name;
                break;
        }
    }

    // Fill both forms if they exist
    const addressLine1 = `${streetNumber} ${route}`.trim();

    // Manual form fields
    const modalAddressLine1 = document.getElementById('modal_address_line_1');
    const modalCity = document.getElementById('modal_city');
    const modalState = document.getElementById('modal_state');
    const modalZipCode = document.getElementById('modal_zip_code');
    const modalCountry = document.getElementById('modal_country');

    // Map form fields
    const mapAddressLine1 = document.getElementById('map_address_line_1');
    const mapCity = document.getElementById('map_city');
    const mapState = document.getElementById('map_state');
    const mapZipCode = document.getElementById('map_zip_code');
    const mapCountry = document.getElementById('map_country');

    // Fill manual form
    if (modalAddressLine1 && addressLine1) modalAddressLine1.value = addressLine1;
    if (modalCity && city) modalCity.value = city;
    if (modalState && state) modalState.value = state;
    if (modalZipCode && zipCode) modalZipCode.value = zipCode;
    if (modalCountry && country) modalCountry.value = country;

    // Fill map form
    if (mapAddressLine1 && addressLine1) mapAddressLine1.value = addressLine1;
    if (mapCity && city) mapCity.value = city;
    if (mapState && state) mapState.value = state;
    if (mapZipCode && zipCode) mapZipCode.value = zipCode;
    if (mapCountry && country) mapCountry.value = country;
}

// Search modal address
function searchModalAddress() {
    const searchInput = document.getElementById('modal_addressSearch');
    const query = searchInput.value.trim();

    if (query) {
        const geocoder = new google.maps.Geocoder();

        geocoder.geocode({ address: query }, function(results, status) {
            if (status === 'OK' && results[0]) {
                setModalMapLocation(results[0].geometry.location);
                fillModalAddressFields(results[0]);
            } else {
                alert('Address not found. Please try a different search term.');
            }
        });
    }
}

// Use current location for modal
function useModalCurrentLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                const positionObj = { lat: lat, lng: lng };

                setModalMapLocation(positionObj);
                reverseGeocodeModal(positionObj);
            },
            function(error) {
                alert('Error getting location: ' + error.message);
            }
        );
    } else {
        alert('Geolocation is not supported by this browser.');
    }
}

// Handle manual address form submission
document.getElementById('addressForm').addEventListener('submit', function(e) {
    e.preventDefault();

    // Collect form data
    const formData = new FormData(this);

    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
    submitBtn.disabled = true;

    // Submit via AJAX
    fetch('{{ route("addresses.store") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Close modal and refresh page to show new address
            closeAddressModal();
            window.location.reload();
        } else {
            alert(data.message || 'Error saving address');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error saving address. Please try again.');
    })
    .finally(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});

// Handle map address form submission
document.getElementById('mapAddressForm').addEventListener('submit', function(e) {
    e.preventDefault();

    if (!modalSelectedLocation) {
        alert('Please select a location on the map before saving the address.');
        return false;
    }

    // Collect form data
    const formData = new FormData(this);

    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
    submitBtn.disabled = true;

    // Submit via AJAX
    fetch('{{ route("addresses.store") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Close modal and refresh page to show new address
            closeAddressModal();
            window.location.reload();
        } else {
            alert(data.message || 'Error saving address');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error saving address. Please try again.');
    })
    .finally(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});

// Sync form data between manual and map forms
function syncFormData() {
    const manualForm = document.getElementById('addressForm');
    const mapForm = document.getElementById('mapAddressForm');

    if (!manualForm || !mapForm) return;

    // Sync from manual form to map form
    const manualInputs = manualForm.querySelectorAll('input, select');
    manualInputs.forEach(input => {
        if (input.name && input.name !== 'latitude' && input.name !== 'longitude') {
            const mapInput = mapForm.querySelector(`[name="${input.name}"]`);
            if (mapInput) {
                mapInput.value = input.value;
            }
        }
    });

    // Sync from map form to manual form
    const mapInputs = mapForm.querySelectorAll('input, select');
    mapInputs.forEach(input => {
        if (input.name && input.name !== 'latitude' && input.name !== 'longitude') {
            const manualInput = manualForm.querySelector(`[name="${input.name}"]`);
            if (manualInput) {
                manualInput.value = input.value;
            }
        }
    });
}

// Add input event listeners to sync forms
document.addEventListener('DOMContentLoaded', function() {
    const manualForm = document.getElementById('addressForm');
    const mapForm = document.getElementById('mapAddressForm');

    if (manualForm) {
        manualForm.addEventListener('input', syncFormData);
    }

    if (mapForm) {
        mapForm.addEventListener('input', syncFormData);
    }
});

// Use current location for location-based delivery
function useCurrentLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;

                // Open location modal and set current position
                openLocationModal();

                // Set the location on the map after modal opens
                setTimeout(() => {
                    if (locationMap && locationMarker) {
                        const latLng = new google.maps.LatLng(lat, lng);
                        setLocationMapPosition(latLng);
                        reverseGeocodeLocation(latLng);
                    }
                }, 200);
            },
            function(error) {
                alert('Error getting location: ' + error.message);
            }
        );
    } else {
        alert('Geolocation is not supported by this browser.');
    }
}

// Location Modal Functions
function openLocationModal() {
    const modal = document.getElementById('locationModal');
    if (!modal) return;

    modal.style.display = 'block';
    modal.style.visibility = 'visible';
    modal.style.opacity = '1';
    modal.style.zIndex = '9999';

    // Initialize location map if not already done
    setTimeout(() => {
        if (typeof google !== 'undefined' && google.maps && !locationMap) {
            initLocationMap();
        }
    }, 100);
}

function closeLocationModal() {
    const modal = document.getElementById('locationModal');
    if (!modal) return;

    modal.style.display = 'none';
    modal.style.visibility = 'hidden';
    modal.style.opacity = '0';
}

function populateUserInfoInModal() {
    // This function is no longer needed since we removed modal fields
    // User info will be populated directly from saved addresses when confirming location
}

function initLocationMap() {
    const defaultLocation = { lat: 0, lng: 0 };

    locationMap = new google.maps.Map(document.getElementById('locationMap'), {
        zoom: 15,
        center: defaultLocation,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        styles: [
            {
                featureType: 'poi',
                elementType: 'labels',
                stylers: [{ visibility: 'off' }]
            }
        ]
    });

    locationMarker = new google.maps.Marker({
        position: defaultLocation,
        map: locationMap,
        draggable: true,
        title: 'Drag to adjust location'
    });

    // Add click listener to map
    locationMap.addListener('click', function(event) {
        setLocationMapPosition(event.latLng);
        reverseGeocodeLocation(event.latLng);
    });

    // Add drag listener to marker
    locationMarker.addListener('dragend', function(event) {
        reverseGeocodeLocation(event.latLng);
    });

    // Initialize autocomplete for search
    const searchInput = document.getElementById('locationSearch');
    if (searchInput) {
        const autocomplete = new google.maps.places.Autocomplete(searchInput, { types: ['address'] });
        autocomplete.addListener('place_changed', function() {
            const place = autocomplete.getPlace();
            if (place.geometry) {
                setLocationMapPosition(place.geometry.location);
                fillLocationAddressFields(place);
            }
        });
    }
}

function setLocationMapPosition(latLng) {
    locationMap.setCenter(latLng);
    locationMarker.setPosition(latLng);
    locationSelectedLocation = latLng;

    // Update info display
    const locationInfo = document.getElementById('selectedLocationInfo');
    if (locationInfo) {
        locationInfo.textContent = `Selected: ${latLng.lat().toFixed(6)}, ${latLng.lng().toFixed(6)}`;
    }
}

function reverseGeocodeLocation(latLng) {
    const geocoder = new google.maps.Geocoder();

    geocoder.geocode({ location: latLng }, function(results, status) {
        if (status === 'OK' && results[0]) {
            fillLocationAddressFields(results[0]);
        }
    });
}

function fillLocationAddressFields(place) {
    let streetNumber = '', route = '', city = '', state = '', zipCode = '', country = '';

    for (const component of place.address_components) {
        const type = component.types[0];

        switch (type) {
            case 'street_number':
                streetNumber = component.long_name;
                break;
            case 'route':
                route = component.long_name;
                break;
            case 'locality':
                city = component.long_name;
                break;
            case 'administrative_area_level_1':
                state = component.long_name;
                break;
            case 'postal_code':
                zipCode = component.long_name;
                break;
            case 'country':
                country = component.long_name;
                break;
        }
    }

    const addressLine1 = `${streetNumber} ${route}`.trim();

    // Store the address data for later use when confirming location
    locationSelectedLocation.addressData = {
        address: addressLine1,
        city: city,
        state: state,
        zipCode: zipCode,
        country: country
    };

    // Update info display
    const locationInfo = document.getElementById('selectedLocationInfo');
    if (locationInfo) {
        locationInfo.innerHTML = `
            <p><strong>Location Selected:</strong></p>
            <p>${addressLine1}, ${city}, ${state} ${zipCode}</p>
            <p>${country}</p>
        `;
    }
}

function searchLocation() {
    const searchInput = document.getElementById('locationSearch');
    const query = searchInput.value.trim();

    if (query) {
        const geocoder = new google.maps.Geocoder();

        geocoder.geocode({ address: query }, function(results, status) {
            if (status === 'OK' && results[0]) {
                setLocationMapPosition(results[0].geometry.location);
                fillLocationAddressFields(results[0]);
            } else {
                alert('Address not found. Please try a different search term.');
            }
        });
    }
}

function confirmLocationSelection() {
    if (!locationSelectedLocation) {
        alert('Please select a location on the map before confirming.');
        return;
    }

    // Get user info from the first available saved address
    let recipientName = '';
    let phone = '';
    const firstAddress = document.querySelector('#address-based-section .address-option input[type="radio"]:checked');
    if (firstAddress) {
        const addressCard = firstAddress.closest('.address-option').querySelector('.address-card');
        if (addressCard) {
            const fullName = addressCard.querySelector('strong');
            const phoneElement = addressCard.querySelector('p:last-child');

            if (fullName) {
                recipientName = fullName.textContent;
            }

            if (phoneElement && phoneElement.textContent.includes('Phone:')) {
                phone = phoneElement.textContent.replace('Phone:', '').trim();
            }
        }
    }

    // Fill the main form fields
    const deliveryLat = document.getElementById('delivery_latitude');
    const deliveryLng = document.getElementById('delivery_longitude');
    const deliveryRecipientName = document.getElementById('delivery_recipient_name');
    const deliveryPhone = document.getElementById('delivery_phone');
    const deliveryAddress = document.getElementById('delivery_address');
    const deliveryCity = document.getElementById('delivery_city');
    const deliveryState = document.getElementById('delivery_state');
    const deliveryZipCode = document.getElementById('delivery_zip_code');
    const deliveryCountry = document.getElementById('delivery_country');

    if (deliveryLat) deliveryLat.value = locationSelectedLocation.lat();
    if (deliveryLng) deliveryLng.value = locationSelectedLocation.lng();

    // Fill recipient name and phone from saved address
    if (deliveryRecipientName) deliveryRecipientName.value = recipientName;
    if (deliveryPhone) deliveryPhone.value = phone;

    // Fill address fields from the stored location data
    if (locationSelectedLocation.addressData) {
        if (deliveryAddress) deliveryAddress.value = locationSelectedLocation.addressData.address;
        if (deliveryCity) deliveryCity.value = locationSelectedLocation.addressData.city;
        if (deliveryState) deliveryState.value = locationSelectedLocation.addressData.state;
        if (deliveryZipCode) deliveryZipCode.value = locationSelectedLocation.addressData.zipCode;
        if (deliveryCountry) deliveryCountry.value = locationSelectedLocation.addressData.country;
    }

    // Show success message
    showLocationSuccessMessage();

    // Close modal
    closeLocationModal();
}

function showLocationSuccessMessage() {
    const locationInfo = document.querySelector('#location-based-section .location-info');
    if (locationInfo) {
        locationInfo.innerHTML = `
            <p><strong>✅ Location selected successfully!</strong></p>
            <p>Your delivery location has been set. You can now proceed with checkout.</p>
        `;
        locationInfo.classList.add('success');
    }
}

function resetLocationInfo() {
    const locationInfo = document.querySelector('#location-based-section .location-info');
    if (locationInfo) {
        locationInfo.innerHTML = `
            <p><strong>Location-based delivery selected</strong></p>
            <p>Click "Select on Map" to choose your delivery location, or use "Use Current Location" to automatically detect your position.</p>
        `;
        locationInfo.classList.remove('success');
    }

    // Clear hidden fields
    const hiddenFields = ['delivery_recipient_name', 'delivery_phone', 'delivery_address', 'delivery_city', 'delivery_state', 'delivery_zip_code', 'delivery_country', 'delivery_latitude', 'delivery_longitude'];
    hiddenFields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field) field.value = '';
    });
}

// Open map modal
function openMapModal(lat = null, lng = null) {
    document.getElementById('mapModal').style.display = 'block';

    setTimeout(() => {
        if (!map) {
            initMap(lat, lng);
        } else {
            if (lat && lng) {
                map.setCenter({ lat: lat, lng: lng });
                if (marker) {
                    marker.setPosition({ lat: lat, lng: lng });
                }
            }
        }
    }, 100);
}

// Close map modal
function closeMapModal() {
    document.getElementById('mapModal').style.display = 'none';
}

// Initialize map
function initMap(lat = null, lng = null) {
    const defaultLocation = lat && lng ? { lat: lat, lng: lng } : { lat: 0, lng: 0 };

    map = new google.maps.Map(document.getElementById('map'), {
        zoom: 15,
        center: defaultLocation,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    marker = new google.maps.Marker({
        position: defaultLocation,
        map: map,
        draggable: true
    });

    // Add click listener to map
    map.addListener('click', function(event) {
        marker.setPosition(event.latLng);
        selectedLocation = event.latLng;
    });

    // Add drag listener to marker
    marker.addListener('dragend', function(event) {
        selectedLocation = event.latLng;
    });
}

// Confirm location and redirect to add address
function confirmLocation() {
    if (selectedLocation) {
        const lat = selectedLocation.lat();
        const lng = selectedLocation.lng();

        // Redirect to add address page with coordinates
        window.location.href = `{{ route('addresses.create') }}?lat=${lat}&lng=${lng}`;
    } else {
        alert('Please select a location on the map.');
    }
}

// Close modals when clicking outside
window.onclick = function(event) {
    const addressModal = document.getElementById('addressModal');
    const mapModal = document.getElementById('mapModal');

    if (event.target === addressModal) {
        closeAddressModal();
    }
    if (event.target === mapModal) {
        closeMapModal();
    }
}
</script>
@endsection

@endsection
