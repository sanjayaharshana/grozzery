@extends('layouts.app')

@section('title', 'Add New Address - Grozzoery')

@section('content')
<div class="address-create-page">
    <div class="container">
        <div class="page-header">
            <h1>Add New Address</h1>
            <p>Add a new delivery address with location selection</p>
        </div>
        
        <div class="address-form-container">
            <form action="{{ route('addresses.store') }}" method="POST" class="address-form" id="addressForm">
                @csrf
                
                <div class="form-grid">
                    <!-- Address Type -->
                    <div class="form-group">
                        <label for="type">Address Type *</label>
                        <select name="type" id="type" required>
                            <option value="">Select Type</option>
                            <option value="shipping">Shipping Address</option>
                            <option value="billing">Billing Address</option>
                            <option value="both">Both</option>
                        </select>
                    </div>
                    
                    <!-- Label -->
                    <div class="form-group">
                        <label for="label">Label (Optional)</label>
                        <input type="text" name="label" id="label" placeholder="e.g., Home, Office, etc.">
                    </div>
                    
                    <!-- First Name -->
                    <div class="form-group">
                        <label for="first_name">First Name *</label>
                        <input type="text" name="first_name" id="first_name" required>
                    </div>
                    
                    <!-- Last Name -->
                    <div class="form-group">
                        <label for="last_name">Last Name *</label>
                        <input type="text" name="last_name" id="last_name" required>
                    </div>
                    
                    <!-- Phone -->
                    <div class="form-group">
                        <label for="phone">Phone Number *</label>
                        <input type="tel" name="phone" id="phone" required>
                    </div>
                    
                    <!-- Address Line 1 -->
                    <div class="form-group full-width">
                        <label for="address_line_1">Street Address *</label>
                        <input type="text" name="address_line_1" id="address_line_1" required>
                    </div>
                    
                    <!-- Address Line 2 -->
                    <div class="form-group full-width">
                        <label for="address_line_2">Apartment, suite, etc. (Optional)</label>
                        <input type="text" name="address_line_2" id="address_line_2">
                    </div>
                    
                    <!-- City -->
                    <div class="form-group">
                        <label for="city">City *</label>
                        <input type="text" name="city" id="city" required>
                    </div>
                    
                    <!-- State -->
                    <div class="form-group">
                        <label for="state">State/Province *</label>
                        <input type="text" name="state" id="state" required>
                    </div>
                    
                    <!-- Zip Code -->
                    <div class="form-group">
                        <label for="zip_code">ZIP/Postal Code *</label>
                        <input type="text" name="zip_code" id="zip_code" required>
                    </div>
                    
                    <!-- Country -->
                    <div class="form-group">
                        <label for="country">Country *</label>
                        <input type="text" name="country" id="country" required>
                    </div>
                    
                    <!-- Hidden coordinates -->
                    <input type="hidden" name="latitude" id="latitude">
                    <input type="hidden" name="longitude" id="longitude">
                </div>
                
                <!-- Map Section -->
                <div class="map-section">
                    <h3>Location Selection</h3>
                    <p>Use the map below to select your exact location or search for an address</p>
                    
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
                        <p><i class="fas fa-info-circle"></i> Click on the map or search for an address to set your location</p>
                        <p id="selectedLocation">No location selected</p>
                    </div>
                </div>
                
                <!-- Form Actions -->
                <div class="form-actions">
                    <button type="button" class="btn btn-outline" onclick="history.back()">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Address
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
    
    // Check if coordinates were passed via URL
    const urlParams = new URLSearchParams(window.location.search);
    const lat = urlParams.get('lat');
    const lng = urlParams.get('lng');
    
    if (lat && lng) {
        const position = { lat: parseFloat(lat), lng: parseFloat(lng) };
        setMapLocation(position);
        reverseGeocode(position);
    }
});

// Initialize map
function initMap() {
    const defaultLocation = { lat: 0, lng: 0 };
    
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
        alert('Please select a location on the map before saving the address.');
        return false;
    }
});
</script>
@endsection
