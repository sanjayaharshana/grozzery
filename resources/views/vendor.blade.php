@extends('layouts.app')

@section('title', $vendor->business_name . ' - Grozzoery')

@section('content')
<div class="container py-8">
    <!-- Vendor Header -->
    <div class="vendor-header mb-8">
        <div class="vendor-info">
            @if($vendor->logo)
            <div class="vendor-logo">
                <img src="{{ asset('storage/' . $vendor->logo) }}" alt="{{ $vendor->business_name }}" class="logo-image">
            </div>
            @endif
            
            <div class="vendor-details">
                <h1 class="vendor-name">{{ $vendor->business_name }}</h1>
                @if($vendor->business_description)
                <p class="vendor-description">{{ $vendor->business_description }}</p>
                @endif
                
                <div class="vendor-meta">
                    @if($vendor->rating)
                    <div class="vendor-rating">
                        <span class="rating-stars">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $vendor->rating)
                                    <i class="fas fa-star filled"></i>
                                @else
                                    <i class="fas fa-star"></i>
                                @endif
                            @endfor
                        </span>
                        <span class="rating-value">{{ number_format($vendor->rating, 1) }}</span>
                    </div>
                    @endif
                    
                    @if($vendor->total_orders)
                    <div class="vendor-orders">
                        <span class="orders-count">{{ $vendor->total_orders }} orders</span>
                    </div>
                    @endif
                </div>
                
                @if($vendor->address || $vendor->city || $vendor->state)
                <div class="vendor-location">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>
                        @if($vendor->address){{ $vendor->address }}, @endif
                        @if($vendor->city){{ $vendor->city }}, @endif
                        @if($vendor->state){{ $vendor->state }}@endif
                        @if($vendor->zip_code) {{ $vendor->zip_code }}@endif
                    </span>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Vendor Navigation Menu -->
    <div class="vendor-nav mb-8">
        <nav class="nav-menu">
            <a href="#products" class="nav-item active" data-section="products">
                <i class="fas fa-box"></i>
                <span>Products</span>
            </a>
            <a href="#about" class="nav-item" data-section="about">
                <i class="fas fa-info-circle"></i>
                <span>About Us</span>
            </a>
            <a href="#reviews" class="nav-item" data-section="reviews">
                <i class="fas fa-star"></i>
                <span>Reviews</span>
            </a>
            <a href="#contact" class="nav-item" data-section="contact">
                <i class="fas fa-envelope"></i>
                <span>Contact</span>
            </a>
            <a href="#offers" class="nav-item" data-section="offers">
                <i class="fas fa-tags"></i>
                <span>Offers</span>
            </a>
        </nav>
    </div>

    <!-- Products Section -->
    <div id="products" class="vendor-section active">
        <div class="vendor-products">
            <h2 class="section-title">Products from {{ $vendor->business_name }}</h2>
            
            @if($products->count() > 0)
            <div class="products-grid">
                @foreach($products as $product)
                <div class="product-card">
                    <div class="product-image-container">
                        @if($product->main_image)
                        <img src="{{ asset('storage/' . $product->main_image) }}" 
                             alt="{{ $product->name }}" 
                             class="product-image"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="image-placeholder product" style="display: none;">
                            <i class="fas fa-image"></i>
                        </div>
                        @else
                        <div class="image-placeholder product">
                            <i class="fas fa-image"></i>
                        </div>
                        @endif
                    </div>
                    
                    <div class="product-info">
                        <h3 class="product-title">
                            <a href="{{ route('product', $product->slug) }}" class="product-link">
                                {{ $product->name }}
                            </a>
                        </h3>
                        
                        @if($product->category)
                        <p class="product-category">{{ $product->category->name }}</p>
                        @endif
                        
                        <div class="product-price">
                            <span class="price-new">${{ number_format($product->price, 2) }}</span>
                            @if($product->compare_price && $product->compare_price > $product->price)
                            <span class="price-old">${{ number_format($product->compare_price, 2) }}</span>
                            @endif
                        </div>
                        
                        @if($product->rating)
                        <div class="product-rating">
                            <span class="rating-stars">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $product->rating)
                                        <i class="fas fa-star filled"></i>
                                    @else
                                        <i class="fas fa-star"></i>
                                    @endif
                                @endfor
                            </span>
                            <span class="rating-value">{{ number_format($product->rating, 1) }}</span>
                        </div>
                        @endif
                        
                        <div class="product-actions">
                            <a href="{{ route('product', $product->slug) }}" class="btn btn-primary">
                                View Details
                            </a>
                            <form action="{{ route('cart.add') }}" method="POST" style="flex: 1;">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn btn-secondary" style="width: 100%;">
                                    Add to Cart
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="pagination-container mt-8">
                {{ $products->links() }}
            </div>
            @else
            <div class="no-products">
                <div class="no-products-icon">
                    <i class="fas fa-box-open"></i>
                </div>
                <h3>No Products Available</h3>
                <p>This vendor doesn't have any products available at the moment.</p>
            </div>
            @endif
        </div>
    </div>

    <!-- About Us Section -->
    <div id="about" class="vendor-section">
        <div class="about-section">
            <h2 class="section-title">About {{ $vendor->business_name }}</h2>
            <div class="about-content">
                @if($vendor->business_description)
                <div class="about-description">
                    <h3>Our Story</h3>
                    <p>{{ $vendor->business_description }}</p>
                </div>
                @endif
                
                <div class="about-details">
                    <div class="detail-item">
                        <i class="fas fa-calendar-alt"></i>
                        <div class="detail-content">
                            <h4>Established</h4>
                            <p>{{ $vendor->created_at ? $vendor->created_at->format('F Y') : 'Recently' }}</p>
                        </div>
                    </div>
                    
                    @if($vendor->total_orders)
                    <div class="detail-item">
                        <i class="fas fa-shopping-bag"></i>
                        <div class="detail-content">
                            <h4>Total Orders</h4>
                            <p>{{ $vendor->total_orders }} orders completed</p>
                        </div>
                    </div>
                    @endif
                    
                    @if($vendor->rating)
                    <div class="detail-item">
                        <i class="fas fa-star"></i>
                        <div class="detail-content">
                            <h4>Customer Rating</h4>
                            <p>{{ number_format($vendor->rating, 1) }} out of 5 stars</p>
                        </div>
                    </div>
                    @endif
                    
                    @if($vendor->address || $vendor->city || $vendor->state)
                    <div class="detail-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <div class="detail-content">
                            <h4>Location</h4>
                            <p>
                                @if($vendor->address){{ $vendor->address }}, @endif
                                @if($vendor->city){{ $vendor->city }}, @endif
                                @if($vendor->state){{ $vendor->state }}@endif
                                @if($vendor->zip_code) {{ $vendor->zip_code }}@endif
                            </p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Reviews Section -->
    <div id="reviews" class="vendor-section">
        <div class="reviews-section">
            <h2 class="section-title">Customer Reviews</h2>
            <div class="reviews-content">
                @if($vendor->rating)
                <div class="overall-rating">
                    <div class="rating-summary">
                        <div class="rating-number">{{ number_format($vendor->rating, 1) }}</div>
                        <div class="rating-stars-large">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $vendor->rating)
                                    <i class="fas fa-star filled"></i>
                                @else
                                    <i class="fas fa-star"></i>
                                @endif
                            @endfor
                        </div>
                        <div class="rating-text">Based on customer feedback</div>
                    </div>
                </div>
                @endif
                
                <div class="reviews-placeholder">
                    <div class="placeholder-icon">
                        <i class="fas fa-comments"></i>
                    </div>
                    <h3>Customer Reviews</h3>
                    <p>Customer reviews will appear here once customers start leaving feedback.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Section -->
    <div id="contact" class="vendor-section">
        <div class="contact-section">
            <h2 class="section-title">Contact {{ $vendor->business_name }}</h2>
            <div class="contact-content">
                <div class="contact-info">
                    @if($vendor->phone)
                    <div class="contact-item">
                        <i class="fas fa-phone"></i>
                        <div class="contact-details">
                            <h4>Phone</h4>
                            <p><a href="tel:{{ $vendor->phone }}">{{ $vendor->phone }}</a></p>
                        </div>
                    </div>
                    @endif
                    
                    @if($vendor->address || $vendor->city || $vendor->state)
                    <div class="contact-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <div class="contact-details">
                            <h4>Address</h4>
                            <p>
                                @if($vendor->address){{ $vendor->address }}, @endif
                                @if($vendor->city){{ $vendor->city }}, @endif
                                @if($vendor->state){{ $vendor->state }}@endif
                                @if($vendor->zip_code) {{ $vendor->zip_code }}@endif
                            </p>
                        </div>
                    </div>
                    @endif
                    
                    <div class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <div class="contact-details">
                            <h4>Email</h4>
                            <p><a href="mailto:{{ $vendor->user->email ?? 'contact@example.com' }}">{{ $vendor->user->email ?? 'contact@example.com' }}</a></p>
                        </div>
                    </div>
                </div>
                
                <div class="contact-form">
                    <h3>Send us a message</h3>
                    <form class="message-form">
                        <div class="form-group">
                            <label for="name">Your Name</label>
                            <input type="text" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Your Email</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="subject">Subject</label>
                            <input type="text" id="subject" name="subject" required>
                        </div>
                        <div class="form-group">
                            <label for="message">Message</label>
                            <textarea id="message" name="message" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Send Message</button>
                    </form>
                </div>
            </div>

            <!-- Map Location Section -->
            @if($vendor->address || $vendor->city || $vendor->state)
            <div class="map-section">
                <h3>Find Us on the Map</h3>
                <div class="map-container">
                    <div id="map" class="map-display"></div>
                    <div class="map-info">
                        <div class="map-address">
                            <i class="fas fa-map-marker-alt"></i>
                            <div>
                                <h4>Our Location</h4>
                                <p>
                                    @if($vendor->address){{ $vendor->address }}, @endif
                                    @if($vendor->city){{ $vendor->city }}, @endif
                                    @if($vendor->state){{ $vendor->state }}@endif
                                    @if($vendor->zip_code) {{ $vendor->zip_code }}@endif
                                </p>
                            </div>
                        </div>
                        <div class="map-actions">
                            <a href="#" class="btn btn-secondary" onclick="getDirections()">
                                <i class="fas fa-directions"></i>
                                Get Directions
                            </a>
                            <a href="#" class="btn btn-primary" onclick="openInMaps()">
                                <i class="fas fa-external-link-alt"></i>
                                Open in Maps
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            </div>
        </div>
    </div>

    <!-- Offers Section -->
    <div id="offers" class="vendor-section">
        <div class="offers-section">
            <h2 class="section-title">Special Offers & Deals</h2>
            <div class="offers-content">
                <div class="offers-grid">
                    <!-- Featured Offers -->
                    <div class="offer-card featured">
                        <div class="offer-badge">Featured</div>
                        <div class="offer-content">
                            <h3>Welcome Discount</h3>
                            <p>Get 15% off on your first order from {{ $vendor->business_name }}</p>
                            <div class="offer-details">
                                <span class="discount">15% OFF</span>
                                <span class="validity">Valid until Dec 31, 2024</span>
                            </div>
                            <div class="offer-code">
                                <span>Use Code:</span>
                                <code>WELCOME15</code>
                            </div>
                        </div>
                    </div>

                    <!-- Seasonal Offers -->
                    <div class="offer-card seasonal">
                        <div class="offer-badge seasonal">Seasonal</div>
                        <div class="offer-content">
                            <h3>Holiday Special</h3>
                            <p>Enjoy amazing deals on selected products this holiday season</p>
                            <div class="offer-details">
                                <span class="discount">Up to 25% OFF</span>
                                <span class="validity">Limited Time</span>
                            </div>
                            <div class="offer-code">
                                <span>Use Code:</span>
                                <code>HOLIDAY25</code>
                            </div>
                        </div>
                    </div>

                    <!-- Bulk Purchase Offers -->
                    <div class="offer-card bulk">
                        <div class="offer-badge bulk">Bulk</div>
                        <div class="offer-content">
                            <h3>Bulk Purchase Bonus</h3>
                            <p>Buy 5 or more items and get additional 10% discount</p>
                            <div class="offer-details">
                                <span class="discount">+10% OFF</span>
                                <span class="validity">Always Available</span>
                            </div>
                            <div class="offer-code">
                                <span>Use Code:</span>
                                <code>BULK10</code>
                            </div>
                        </div>
                    </div>

                    <!-- Loyalty Offers -->
                    <div class="offer-card loyalty">
                        <div class="offer-badge loyalty">Loyalty</div>
                        <div class="offer-content">
                            <h3>Loyalty Rewards</h3>
                            <p>Earn points on every purchase and redeem for discounts</p>
                            <div class="offer-details">
                                <span class="discount">Earn Points</span>
                                <span class="validity">Ongoing Program</span>
                            </div>
                            <div class="offer-code">
                                <span>Join our</span>
                                <code>Loyalty Program</code>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- How to Use Offers -->
                <div class="offers-info">
                    <h3>How to Use Our Offers</h3>
                    <div class="usage-steps">
                        <div class="step">
                            <div class="step-number">1</div>
                            <div class="step-content">
                                <h4>Choose Your Offer</h4>
                                <p>Browse through our available offers and select the one that suits you best.</p>
                            </div>
                        </div>
                        <div class="step">
                            <div class="step-number">2</div>
                            <div class="step-content">
                                <h4>Add Products to Cart</h4>
                                <p>Select the products you want to purchase and add them to your shopping cart.</p>
                            </div>
                        </div>
                        <div class="step">
                            <div class="step-number">3</div>
                            <div class="step-content">
                                <h4>Apply Offer Code</h4>
                                <p>Enter the offer code during checkout to get your discount applied.</p>
                            </div>
                        </div>
                        <div class="step">
                            <div class="step-number">4</div>
                            <div class="step-content">
                                <h4>Enjoy Your Savings</h4>
                                <p>Complete your purchase and enjoy the savings from your selected offer!</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Terms & Conditions -->
                <div class="offers-terms">
                    <h3>Terms & Conditions</h3>
                    <div class="terms-content">
                        <ul>
                            <li>Offers cannot be combined with other promotions</li>
                            <li>Discount codes are case-sensitive and must be entered exactly as shown</li>
                            <li>Some products may be excluded from certain offers</li>
                            <li>Offers are subject to availability and may be modified or discontinued</li>
                            <li>Bulk discounts apply to qualifying items only</li>
                            <li>Loyalty points are earned based on purchase amount and can be redeemed for future discounts</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.vendor-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 2rem;
    border-radius: 12px;
    margin-bottom: 2rem;
    overflow: hidden;
}

.vendor-info {
    display: flex;
    align-items: center;
    gap: 2rem;
    background: transparent;
}

.vendor-logo {
    flex-shrink: 0;
}

.logo-image {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid rgba(255, 255, 255, 0.3);
}

.vendor-details {
    flex: 1;
    background: transparent;
}

.vendor-name {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    color: #ffffff !important;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    background: transparent;
    padding: 0;
    margin: 0 0 0.5rem 0;
    display: block;
}

.vendor-description {
    font-size: 1.1rem;
    margin-bottom: 1rem;
    opacity: 0.95;
    line-height: 1.6;
    color: #ffffff;
}

.vendor-meta {
    display: flex;
    gap: 2rem;
    margin-bottom: 1rem;
}

.vendor-rating, .vendor-orders {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.rating-stars {
    color: #ffd700;
}

.rating-stars .filled {
    color: #ffd700;
}

.rating-stars .fas {
    font-size: 1.1rem;
}

.rating-value {
    font-weight: 600;
    font-size: 1.1rem;
    color: #ffffff;
}

.orders-count {
    font-weight: 600;
    font-size: 1.1rem;
    color: #ffffff;
}

.vendor-location {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 1rem;
    opacity: 0.95;
    color: #ffffff;
}

.vendor-location i {
    color: #ff6b6b;
}

/* Navigation Menu */
.vendor-nav {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    padding: 0;
    overflow: hidden;
}

.nav-menu {
    display: flex;
    border-bottom: 1px solid #e2e8f0;
}

.nav-item {
    flex: 1;
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: center;
    padding: 1rem 1.5rem;
    text-decoration: none;
    color: #718096;
    transition: all 0.3s ease;
    border-bottom: 3px solid transparent;
    gap: 0.5rem;
}

.nav-item:hover {
    color: #667eea;
    background: #f7fafc;
}

.nav-item.active {
    color: #667eea;
    border-bottom-color: #667eea;
    background: #f0f4ff;
}

.nav-item i {
    font-size: 1.2rem;
    margin: 0;
}

.nav-item span {
    font-weight: 600;
    font-size: 1rem;
}

/* Section Management */
.vendor-section {
    display: none;
}

.vendor-section.active {
    display: block;
}

.section-title {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 2rem;
    text-align: center;
    color: #2d3748;
}

/* About Section */
.about-content {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.about-description {
    margin-bottom: 2rem;
}

.about-description h3 {
    color: #2d3748;
    margin-bottom: 1rem;
    font-size: 1.3rem;
}

.about-description p {
    color: #4a5568;
    line-height: 1.7;
    font-size: 1.1rem;
}

.about-details {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
}

.detail-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: #f7fafc;
    border-radius: 8px;
}

.detail-item i {
    font-size: 1.5rem;
    color: #667eea;
    width: 40px;
    text-align: center;
}

.detail-content h4 {
    color: #2d3748;
    margin-bottom: 0.25rem;
    font-size: 1rem;
}

.detail-content p {
    color: #4a5568;
    margin: 0;
}

/* Reviews Section */
.reviews-content {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.overall-rating {
    text-align: center;
    margin-bottom: 2rem;
    padding: 2rem;
    background: #f7fafc;
    border-radius: 8px;
}

.rating-summary {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
}

.rating-number {
    font-size: 3rem;
    font-weight: 700;
    color: #667eea;
}

.rating-stars-large {
    font-size: 2rem;
    color: #ffd700;
}

.rating-text {
    color: #718096;
    font-size: 1.1rem;
}

.reviews-placeholder {
    text-align: center;
    padding: 3rem 2rem;
}

.placeholder-icon {
    font-size: 4rem;
    color: #a0aec0;
    margin-bottom: 1rem;
}

.reviews-placeholder h3 {
    color: #2d3748;
    margin-bottom: 0.5rem;
}

.reviews-placeholder p {
    color: #718096;
}

/* Contact Section */
.contact-content {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.contact-info {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.contact-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem;
    background: #f7fafc;
    border-radius: 8px;
}

.contact-item i {
    font-size: 1.5rem;
    color: #667eea;
    width: 40px;
    text-align: center;
}

.contact-details h4 {
    color: #2d3748;
    margin-bottom: 0.25rem;
    font-size: 1rem;
}

.contact-details p {
    color: #4a5568;
    margin: 0;
}

.contact-details a {
    color: #667eea;
    text-decoration: none;
}

    .contact-details a:hover {
        text-decoration: underline;
    }

    /* Offers Section */
    .offers-content {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .offers-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .offer-card {
        position: relative;
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        border-radius: 12px;
        padding: 1.5rem;
        border: 2px solid transparent;
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .offer-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        border-color: #667eea;
    }

    .offer-card.featured {
        background: linear-gradient(135deg, #ffd700 0%, #ffed4e 100%);
        color: #2d3748;
    }

    .offer-card.seasonal {
        background: linear-gradient(135deg, #ff6b6b 0%, #ff8e8e 100%);
        color: white;
    }

    .offer-card.bulk {
        background: linear-gradient(135deg, #38a169 0%, #48bb78 100%);
        color: white;
    }

    .offer-card.loyalty {
        background: linear-gradient(135deg, #667eea 0%, #7c3aed 100%);
        color: white;
    }

    .offer-badge {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: rgba(255, 255, 255, 0.9);
        color: #2d3748;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .offer-card.featured .offer-badge {
        background: #2d3748;
        color: #ffd700;
    }

    .offer-card.seasonal .offer-badge {
        background: #2d3748;
        color: #ff6b6b;
    }

    .offer-card.bulk .offer-badge {
        background: #2d3748;
        color: #38a169;
    }

    .offer-card.loyalty .offer-badge {
        background: #2d3748;
        color: #667eea;
    }

    .offer-content h3 {
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 0.75rem;
        margin-top: 0.5rem;
    }

    .offer-content p {
        font-size: 1rem;
        line-height: 1.6;
        margin-bottom: 1rem;
        opacity: 0.9;
    }

    .offer-details {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        padding: 0.75rem;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 8px;
    }

    .discount {
        font-size: 1.1rem;
        font-weight: 700;
        color: #2d3748;
    }

    .validity {
        font-size: 0.9rem;
        opacity: 0.8;
    }

    .offer-code {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9rem;
    }

    .offer-code code {
        background: rgba(255, 255, 255, 0.2);
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-family: monospace;
        font-weight: 600;
        border: 1px dashed rgba(255, 255, 255, 0.4);
    }

    .offers-info {
        margin-bottom: 2rem;
        padding: 2rem;
        background: #f7fafc;
        border-radius: 12px;
    }

    .offers-info h3 {
        color: #2d3748;
        margin-bottom: 1.5rem;
        font-size: 1.3rem;
        text-align: center;
    }

    .usage-steps {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }

    .step {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
    }

    .step-number {
        background: #667eea;
        color: white;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    .step-content h4 {
        color: #2d3748;
        margin-bottom: 0.5rem;
        font-size: 1.1rem;
    }

    .step-content p {
        color: #4a5568;
        line-height: 1.6;
        margin: 0;
    }

    .offers-terms {
        padding: 2rem;
        background: #f7fafc;
        border-radius: 12px;
    }

    .offers-terms h3 {
        color: #2d3748;
        margin-bottom: 1.5rem;
        font-size: 1.3rem;
        text-align: center;
    }

    .terms-content ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .terms-content li {
        color: #4a5568;
        padding: 0.5rem 0;
        border-bottom: 1px solid #e2e8f0;
        position: relative;
        padding-left: 1.5rem;
    }

    .terms-content li:before {
        content: "•";
        color: #667eea;
        font-weight: bold;
        position: absolute;
        left: 0;
    }

        .terms-content li:last-child {
        border-bottom: none;
    }

    /* Map Section */
    .map-section {
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid #e2e8f0;
    }

    .map-section h3 {
        color: #2d3748;
        margin-bottom: 1.5rem;
        font-size: 1.3rem;
        text-align: center;
    }

    .map-container {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
        align-items: start;
    }

    .map-display {
        height: 400px;
        background: #f7fafc;
        border-radius: 12px;
        border: 2px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }

    .map-display::before {
        content: "🗺️";
        font-size: 4rem;
        color: #a0aec0;
    }

    .map-display::after {
        content: "Interactive Map Loading...";
        position: absolute;
        bottom: 1rem;
        left: 50%;
        transform: translateX(-50%);
        background: rgba(0, 0, 0, 0.7);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.9rem;
    }

    .map-info {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .map-address {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        padding: 1.5rem;
        background: #f7fafc;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
    }

    .map-address i {
        font-size: 1.5rem;
        color: #667eea;
        margin-top: 0.25rem;
    }

    .map-address h4 {
        color: #2d3748;
        margin-bottom: 0.5rem;
        font-size: 1.1rem;
    }

    .map-address p {
        color: #4a5568;
        line-height: 1.6;
        margin: 0;
    }

    .map-actions {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .map-actions .btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        width: 100%;
        padding: 0.75rem 1rem;
    }

    .map-actions .btn i {
        font-size: 1rem;
    }

    .contact-form {
    border-top: 1px solid #e2e8f0;
    padding-top: 2rem;
}

.contact-form h3 {
    color: #2d3748;
    margin-bottom: 1.5rem;
    font-size: 1.3rem;
}

.message-form {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.message-form .form-group:nth-child(3),
.message-form .form-group:nth-child(4) {
    grid-column: 1 / -1;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group label {
    color: #2d3748;
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.form-group input,
.form-group textarea {
    padding: 0.75rem;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    font-size: 1rem;
    transition: border-color 0.2s ease;
}

.form-group input:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

/* Products Section Styles */
.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 2rem;
    margin-bottom: 2rem;
}

.product-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.product-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.product-image-container {
    position: relative;
    height: 200px;
    overflow: hidden;
}

.product-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.product-card:hover .product-image {
    transform: scale(1.05);
}

.image-placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f7fafc;
    color: #a0aec0;
    font-size: 3rem;
    width: 100%;
    height: 100%;
}

.product-info {
    padding: 1.5rem;
}

.product-title {
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    line-height: 1.4;
}

.product-link {
    color: #2d3748;
    text-decoration: none;
    transition: color 0.2s ease;
}

.product-link:hover {
    color: #667eea;
}

.product-category {
    color: #718096;
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.product-price {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.5rem;
}

.price-new {
    font-size: 1.3rem;
    font-weight: 700;
    color: #38a169;
}

.price-old {
    font-size: 1rem;
    color: #a0aec0;
    text-decoration: line-through;
}

.product-rating {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.product-actions {
    display: flex;
    gap: 0.5rem;
}

.btn {
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    text-align: center;
    cursor: pointer;
    transition: all 0.2s ease;
    display: inline-block;
}

.btn-primary {
    background: #667eea;
    color: white;
}

.btn-primary:hover {
    background: #5a67d8;
    transform: translateY(-1px);
}

.btn-secondary {
    background: #38a169;
    color: white;
}

.btn-secondary:hover {
    background: #2f855a;
    transform: translateY(-1px);
}

.no-products {
    text-align: center;
    padding: 4rem 2rem;
}

.no-products-icon {
    font-size: 4rem;
    color: #a0aec0;
    margin-bottom: 1rem;
}

.no-products h3 {
    font-size: 1.5rem;
    color: #2d3748;
    margin-bottom: 0.5rem;
}

.no-products p {
    color: #718096;
    font-size: 1.1rem;
}

.pagination-container {
    display: flex;
    justify-content: center;
}

@media (max-width: 768px) {
    .vendor-info {
        flex-direction: column;
        text-align: center;
    }
    
    .vendor-name {
        font-size: 2rem;
    }
    
    .vendor-meta {
        justify-content: center;
        flex-wrap: wrap;
    }
    
    .nav-menu {
        flex-direction: row;
        flex-wrap: wrap;
    }
    
    .nav-item {
        flex: 1 1 auto;
        min-width: 120px;
        padding: 0.75rem 1rem;
    }
    
    .nav-item.active {
        border-bottom-color: #667eea;
    }
    
    .message-form {
        grid-template-columns: 1fr;
    }
    
    .products-grid {
        grid-template-columns: 1fr;
    }
    
    .product-actions {
        flex-direction: column;
    }
    
    .about-details,
    .contact-info {
        grid-template-columns: 1fr;
    }
    
    .offers-grid {
        grid-template-columns: 1fr;
    }
    
    .usage-steps {
        grid-template-columns: 1fr;
    }
    
    .map-container {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .map-display {
        height: 300px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const navItems = document.querySelectorAll('.nav-item');
    const sections = document.querySelectorAll('.vendor-section');
    
    navItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Remove active class from all nav items and sections
            navItems.forEach(nav => nav.classList.remove('active'));
            sections.forEach(section => section.classList.remove('active'));
            
            // Add active class to clicked nav item
            this.classList.add('active');
            
            // Show corresponding section
            const targetSection = this.getAttribute('data-section');
            document.getElementById(targetSection).classList.add('active');
        });
    });
    
    // Handle form submission
    const messageForm = document.querySelector('.message-form');
    if (messageForm) {
        messageForm.addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Thank you for your message! We will get back to you soon.');
            this.reset();
        });
    }

    // Map functionality
    function getDirections() {
        const address = '{{ $vendor->address ?? "" }}, {{ $vendor->city ?? "" }}, {{ $vendor->state ?? "" }} {{ $vendor->zip_code ?? "" }}'.trim();
        if (address && address !== ', , ') {
            const encodedAddress = encodeURIComponent(address);
            window.open(`https://www.google.com/maps/dir/?api=1&destination=${encodedAddress}`, '_blank');
        } else {
            alert('Address information is not available for directions.');
        }
    }

    function openInMaps() {
        const address = '{{ $vendor->address ?? "" }}, {{ $vendor->city ?? "" }}, {{ $vendor->state ?? "" }} {{ $vendor->zip_code ?? "" }}'.trim();
        if (address && address !== ', , ') {
            const encodedAddress = encodeURIComponent(address);
            window.open(`https://www.google.com/maps/search/?api=1&query=${encodedAddress}`, '_blank');
        } else {
            alert('Address information is not available to open in maps.');
        }
    }

    // Initialize map if Google Maps API is available
    function initMap() {
        const address = '{{ $vendor->address ?? "" }}, {{ $vendor->city ?? "" }}, {{ $vendor->state ?? "" }} {{ $vendor->zip_code ?? "" }}'.trim();
        if (address && address !== ', , ') {
            // This would be replaced with actual Google Maps API integration
            const mapElement = document.getElementById('map');
            if (mapElement) {
                mapElement.innerHTML = `
                    <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; flex-direction: column; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; text-align: center;">
                        <div style="font-size: 3rem; margin-bottom: 1rem;">📍</div>
                        <h3 style="margin: 0 0 0.5rem 0;">{{ $vendor->business_name }}</h3>
                        <p style="margin: 0; opacity: 0.9; padding: 0 1rem;">${address}</p>
                        <div style="margin-top: 1rem;">
                            <button onclick="getDirections()" style="background: white; color: #667eea; border: none; padding: 0.5rem 1rem; border-radius: 6px; margin: 0 0.25rem; cursor: pointer; font-weight: 600;">Get Directions</button>
                            <button onclick="openInMaps()" style="background: rgba(255,255,255,0.2); color: white; border: 1px solid white; padding: 0.5rem 1rem; border-radius: 6px; margin: 0 0.25rem; cursor: pointer; font-weight: 600;">Open in Maps</button>
                        </div>
                    </div>
                `;
            }
        }
    }

    // Initialize map when page loads
    if (document.getElementById('map')) {
        initMap();
    }
});
</script>
@endsection
