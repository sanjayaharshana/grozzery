@extends('layouts.app')

@section('title', 'Edit Address - Grozzoery')

@section('content')
<div class="address-edit-page">
    <div class="container">
        <div class="page-header">
            <h1>Edit Address</h1>
            <p>Update your address information and location</p>
        </div>
        
        <div class="address-form-container">
            <form action="{{ route('addresses.update', $address) }}" method="POST" class="address-form" id="addressForm">
                @csrf
                @method('PUT')
                
                <div class="form-grid">
                    <!-- Address Type -->
                    <div class="form-group">
                        <label for="type">Address Type *</label>
                        <select name="type" id="type" required>
                            <option value="">Select Type</option>
                            <option value="shipping" {{ $address->type == 'shipping' ? 'selected' : '' }}>Shipping Address</option>
                            <option value="billing" {{ $address->type == 'billing' ? 'selected' : '' }}>Billing Address</option>
                            <option value="both" {{ $address->type == 'both' ? 'selected' : '' }}>Both</option>
                        </select>
                    </div>
                    
                    <!-- Label -->
                    <div class="form-group">
                        <label for="label">Label (Optional)</label>
                        <input type="text" name="label" id="label" placeholder="e.g., Home, Office, etc." value="{{ $address->label }}">
                    </div>
                    
                    <!-- First Name -->
                    <div class="form-group">
                        <label for="first_name">First Name *</label>
                        <input type="text" name="first_name" id="first_name" required value="{{ $address->first_name }}">
                    </div>
                    
                    <!-- Last Name -->
                    <div class="form-group">
                        <label for="last_name">Last Name *</label>
                        <input type="text" name="last_name" id="last_name" required value="{{ $address->last_name }}">
                    </div>
                    
                    <!-- Phone -->
                    <div class="form-group">
                        <label for="phone">Phone Number *</label>
                        <input type="tel" name="phone" id="phone" required value="{{ $address->phone }}">
                    </div>
                    
                    <!-- Address Line 1 -->
                    <div class="form-group full-width">
                        <label for="address_line_1">Street Address *</label>
                        <input type="text" name="address_line_1" id="address_line_1" required value="{{ $address->address_line_1 }}">
                    </div>
                    
                    <!-- Address Line 2 -->
                    <div class="form-group full-width">
                        <label for="address_line_2">Apartment, suite, etc. (Optional)</label>
                        <input type="text" name="address_line_2" id="address_line_2" value="{{ $address->address_line_2 }}">
                    </div>
                    
                    <!-- City -->
                    <div class="form-group">
                        <label for="city">City *</label>
                        <input type="text" name="city" id="city" required value="{{ $address->city }}">
                    </div>
                    
                    <!-- State -->
                    <div class="form-group">
                        <label for="state">State/Province *</label>
                        <input type="text" name="state" id="state" required value="{{ $address->state }}">
                    </div>
                    
                    <!-- Zip Code -->
                    <div class="form-group">
                        <label for="zip_code">ZIP/Postal Code *</label>
                        <input type="text" name="zip_code" id="zip_code" required value="{{ $address->zip_code }}">
                    </div>
                    
                    <!-- Country -->
                    <div class="form-group">
                        <label for="country">Country *</label>
                        <input type="text" name="country" id="country" required value="{{ $address->country }}">
                    </div>
                    
                    <!-- Hidden coordinates -->
                    <input type="hidden" name="latitude" id="latitude" value="{{ $address->latitude }}">
                    <input type="hidden" name="longitude" id="longitude" value="{{ $address->longitude }}">
                </div>
                
                <!-- Map Section -->
                <div class="map-section">
                    <h3>Location Selection</h3>
                    <p>Use the map below to adjust your location or search for a new address</p>
                    
                    <div class="map-controls">
                        <div class="search-container">
                            <input type="text" id="addressSearch" placeholder="Search for an address...">
                            <button type="button" class="btn btn-primary" onclick="searchAddress()">
                                <i class="fas fa-search"></i> Search
                            </button>
                        </div>
                        <button type="button" class="btn btn-outline" onclick="useCurrentLocation()">
                            <i class="fas fa-location-arrow"></i> Use Current Location
                        </button>
                    </div>
                    
                    <div id="map" class="address-map"></div>
                    <div class="map-info">
                        <p><i class="fas fa-info-circle"></i> Click on the map or search for an address to update your location</p>
                        <p id="selectedLocation">
                            @if($address->latitude && $address->longitude)
                                Current: {{ $address->latitude }}, {{ $address->longitude }}
                            @else
                                No location set
                            @endif
                        </p>
                    </div>
                </div>
                
                <!-- Form Actions -->
                <div class="form-actions">
                    <a href="{{ route('addresses.index') }}" class="btn btn-outline">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Address
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Google Maps API -->
<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_MAPS_API_KEY&libraries=places"></script>
@endsection

@section('scripts')
<script>
let map, marker, autocomplete, selectedLocation;

// Initialize the page
document.addEventListener('DOMContentLoaded', function() {
    initMap();
    initAutocomplete();
    
    // Set initial location if coordinates exist
    @if($address->latitude && $address->longitude)
        const initialLocation = { 
            lat: {{ $address->latitude }}, 
            lng: {{ $address->longitude }} 
        };
        setMapLocation(initialLocation);
    @endif
});

// Initialize map
function initMap() {
    const defaultLocation = @if($address->latitude && $address->longitude)
        { lat: {{ $address->latitude }}, lng: {{ $address->longitude }} }
    @else
        { lat: 0, lng: 0 }
    @endif;
    
    map = new google.maps.Map(document.getElementById('map'), {
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

    marker = new google.maps.Marker({
        position: defaultLocation,
        map: map,
        draggable: true,
        title: 'Drag to adjust location'
    });

    // Add click listener to map
    map.addListener('click', function(event) {
        setMapLocation(event.latLng);
        reverseGeocode(event.latLng);
    });

    // Add drag listener to marker
    marker.addListener('dragend', function(event) {
        reverseGeocode(event.latLng);
    });
}

// Initialize autocomplete
function initAutocomplete() {
    autocomplete = new google.maps.places.Autocomplete(
        document.getElementById('addressSearch'),
        { types: ['address'] }
    );
    
    autocomplete.addListener('place_changed', function() {
        const place = autocomplete.getPlace();
        
        if (place.geometry) {
            setMapLocation(place.geometry.location);
            fillAddressFields(place);
        }
    });
}

// Set map location
function setMapLocation(latLng) {
    map.setCenter(latLng);
    marker.setPosition(latLng);
    selectedLocation = latLng;
    
    // Update hidden fields
    document.getElementById('latitude').value = latLng.lat();
    document.getElementById('longitude').value = latLng.lng();
    
    // Update info display
    document.getElementById('selectedLocation').textContent = 
        `Selected: ${latLng.lat().toFixed(6)}, ${latLng.lng().toFixed(6)}`;
}

// Reverse geocode coordinates to address
function reverseGeocode(latLng) {
    const geocoder = new google.maps.Geocoder();
    
    geocoder.geocode({ location: latLng }, function(results, status) {
        if (status === 'OK' && results[0]) {
            fillAddressFields(results[0]);
        }
    });
}

// Fill address fields from Google Places result
function fillAddressFields(place) {
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
    
    // Fill the form fields
    if (streetNumber && route) {
        document.getElementById('address_line_1').value = `${streetNumber} ${route}`;
    }
    if (city) document.getElementById('city').value = city;
    if (state) document.getElementById('state').value = state;
    if (zipCode) document.getElementById('zip_code').value = zipCode;
    if (country) document.getElementById('country').value = country;
}

// Search for address
function searchAddress() {
    const searchInput = document.getElementById('addressSearch');
    const query = searchInput.value.trim();
    
    if (query) {
        const geocoder = new google.maps.Geocoder();
        
        geocoder.geocode({ address: query }, function(results, status) {
            if (status === 'OK' && results[0]) {
                setMapLocation(results[0].geometry.location);
                fillAddressFields(results[0]);
            } else {
                alert('Address not found. Please try a different search term.');
            }
        });
    }
}

// Use current location
function useCurrentLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                const positionObj = { lat: lat, lng: lng };
                
                setMapLocation(positionObj);
                reverseGeocode(positionObj);
            },
            function(error) {
                alert('Error getting location: ' + error.message);
            }
        );
    } else {
        alert('Geolocation is not supported by this browser.');
    }
}

// Form validation
document.getElementById('addressForm').addEventListener('submit', function(e) {
    if (!selectedLocation) {
        e.preventDefault();
        alert('Please select a location on the map before updating the address.');
        return false;
    }
});
</script>
@endsection
