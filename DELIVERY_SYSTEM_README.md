# Grozzoery Delivery System

## Overview
The Grozzoery delivery system now supports two delivery options:
1. **Address-Based Delivery** - Deliver to saved addresses
2. **Location-Based Delivery** - Deliver to any location on the map

## Features

### Address-Based Delivery
- Uses existing saved addresses from user's address book
- Standard shipping cost: $5.99
- Requires selection of shipping and billing addresses
- Traditional checkout flow

### Location-Based Delivery
- Interactive map selection for delivery location
- Dynamic shipping costs based on distance from city center
- Real-time address autocomplete and geocoding
- GPS coordinates capture for precise delivery

## Technical Implementation

### Database Changes
- Added `delivery_type` field to `orders` table
- Values: `location_based` or `address_based`

### Backend Changes
- **CheckoutController**: Enhanced to handle both delivery types
- **Order Model**: Added `delivery_type` to fillable fields
- **Shipping Calculation**: Dynamic pricing for location-based delivery

### Frontend Changes
- **Delivery Type Selection**: Radio buttons for choosing delivery method
- **Dynamic Form Sections**: Shows/hides relevant fields based on selection
- **Location Modal**: Interactive map for location selection
- **Google Maps Integration**: Geocoding, reverse geocoding, and autocomplete

### Shipping Cost Calculation
Location-based delivery uses a tiered pricing system:
- Base cost: $5.99
- 25-50km from city center: +$5.00
- 50+km from city center: +$10.00

## Usage

### For Customers
1. **Choose Delivery Type**: Select between address-based or location-based
2. **Address-Based**: Select from saved addresses
3. **Location-Based**: 
   - Click "Select on Map" to open location modal
   - Search for address or click on map
   - Fill in recipient details
   - Confirm location selection

### For Developers
The system automatically:
- Validates required fields based on delivery type
- Calculates appropriate shipping costs
- Stores delivery information in the order
- Handles both delivery methods seamlessly

## API Endpoints

### Checkout
- `POST /checkout` - Enhanced to accept `delivery_type` parameter
- Additional validation for location-based delivery coordinates

## Dependencies
- Google Maps JavaScript API
- Places library for autocomplete
- Geocoding service for address resolution

## Future Enhancements
- Vendor-specific delivery zones
- Real-time delivery tracking
- Delivery time estimation
- Multiple delivery time slots
- Delivery preferences and instructions
