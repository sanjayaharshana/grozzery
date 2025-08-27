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
                        <span class="rating-value" data-stat="rating">{{ number_format($vendor->rating, 1) }}</span>
                    </div>
                    @endif

                    @if($vendor->total_orders)
                    <div class="vendor-orders">
                        <span class="orders-count" data-stat="orders">{{ $vendor->total_orders }} orders</span>
                    </div>
                    @endif

                    <div class="vendor-products-count">
                        <span class="products-count" data-stat="products">{{ $products->count() }} products</span>
                    </div>
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

            <!-- Product Filters and Search -->
            <div class="product-filters" style="margin-bottom: 2rem; padding: 1.5rem; background: white; border-radius: 12px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);">
                <div class="search-box" style="position: relative; margin-bottom: 1rem;">
                    <input type="text" id="productSearch" placeholder="Search products..." class="search-input" style="width: 100%; padding: 0.75rem 1rem 0.75rem 2.5rem; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 1rem; transition: border-color 0.2s ease;" onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.1)'" onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
                    <i class="fas fa-search search-icon" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #a0aec0;"></i>
                </div>

                <div class="filter-options" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                    <select id="categoryFilter" class="filter-select" style="padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 1rem; background: white; cursor: pointer; transition: border-color 0.2s ease;" onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.1)'" onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
                        <option value="">All Categories</option>
                        @if(isset($categories) && $categories->count() > 0)
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        @endif
                    </select>

                    <select id="priceFilter" class="filter-select" style="padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 1rem; background: white; cursor: pointer; transition: border-color 0.2s ease;" onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.1)'" onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
                        <option value="">All Prices</option>
                        <option value="0-25">$0 - $25</option>
                        <option value="25-50">$25 - $50</option>
                        <option value="50-100">$50 - $100</option>
                        <option value="100+">$100+</option>
                    </select>

                    <select id="sortFilter" class="filter-select" style="padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 1rem; background: white; cursor: pointer; transition: border-color 0.2s ease;" onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.1)'" onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
                        <option value="newest">Newest First</option>
                        <option value="oldest">Oldest First</option>
                        <option value="price_low">Price: Low to High</option>
                        <option value="price_high">Price: High to Low</option>
                        <option value="rating">Highest Rated</option>
                        <option value="name">Name A-Z</option>
                    </select>
                </div>
            </div>

            @if($products->count() > 0)
            <div class="products-grid">
                @foreach($products as $product)
                <div class="product-card"
                     data-category="{{ $product->category_id ?? '' }}"
                     data-price="{{ $product->price }}"
                     data-rating="{{ $product->rating ?? 0 }}"
                     data-name="{{ strtolower($product->name) }}"
                     data-date="{{ $product->created_at ? $product->created_at->timestamp : 0 }}">

                    <a href="{{ route('product', $product->slug) }}">
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

                            @if($product->is_featured ?? false)
                                <div class="featured-badge" style="position: absolute; top: 0.5rem; left: 0.5rem; background: #ffd700; color: #2d3748; padding: 0.15rem 0.5rem; border-radius: 12px; font-size: 0.6rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.3px; z-index: 2;">Featured</div>
                            @endif

                            @if(($product->discount_percentage ?? 0) > 0)
                                <div class="discount-badge" style="position: absolute; top: 0.5rem; right: 0.5rem; background: #38a169; color: white; padding: 0.15rem 0.5rem; border-radius: 12px; font-size: 0.6rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.3px; z-index: 2;">{{ $product->discount_percentage }}% OFF</div>
                            @endif
                        </div>
                    </a>


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

                        <div class="product-stock">
                            @if(($product->quantity ?? 0) > 0)
                                <span class="in-stock" style="background: #2da5dc;color: white;padding: 0px 10px 2px 10px;border-radius: 5px;">In Stock ({{ $product->quantity }})</span>
                            @else
                                <span class="out-of-stock" style="background: #9d0202;color: white;padding: 0px 10px 2px 10px;border-radius: 5px;">Out of Stock</span>
                            @endif
                        </div>

                        <div class="product-actions">
                            @if(($product->quantity ?? 0) > 0)
                            <form action="{{ route('cart.add') }}" method="POST" style="flex: 1;">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn btn-primary btn-icon" style="width: 100%;" title="Add to Cart">
                                    <i class="fas fa-cart-plus"></i>
                                </button>
                            </form>
                            @else
                            @endif
                            <button class="btn btn-outline btn-icon" title="Add to Wishlist">
                                <i class="fas fa-heart"></i>
                            </button>
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

                <!-- Dynamic Reviews -->
                @if(isset($reviews) && $reviews->count() > 0)
                <div class="reviews-list">
                    @foreach($reviews as $review)
                    <div class="review-item">
                        <div class="review-header">
                            <div class="reviewer-info">
                                <div class="reviewer-avatar">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="reviewer-details">
                                    <h4>{{ $review->customer_name ?? 'Anonymous' }}</h4>
                                    <div class="review-rating">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= ($review->rating ?? 0))
                                                <i class="fas fa-star filled"></i>
                                            @else
                                                <i class="fas fa-star"></i>
                                            @endif
                                        @endfor
                                        <span class="rating-value">{{ number_format($review->rating ?? 0, 1) }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="review-date">
                                {{ $review->created_at ? $review->created_at->diffForHumans() : 'Recently' }}
                            </div>
                        </div>
                        <div class="review-content">
                            <p>{{ $review->comment ?? 'No comment provided' }}</p>
                        </div>
                        @if($review->product_name)
                        <div class="reviewed-product">
                            <small>Reviewed: {{ $review->product_name }}</small>
                        </div>
                        @endif
                    </div>
                    @endforeach

                    <div class="reviews-pagination">
                        {{ $reviews->links() }}
                    </div>
                </div>
                @else
                <div class="reviews-placeholder">
                    <div class="placeholder-icon">
                        <i class="fas fa-comments"></i>
                    </div>
                    <h3>Customer Reviews</h3>
                    <p>Customer reviews will appear here once customers start leaving feedback.</p>
                    <div class="review-cta">
                        <button class="btn btn-primary" onclick="showReviewForm()">
                            <i class="fas fa-star"></i>
                            Write a Review
                        </button>
                    </div>
                </div>
                @endif

                <!-- Review Form Modal -->
                <div id="reviewModal" class="modal">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3>Write a Review</h3>
                            <span class="close" onclick="closeReviewModal()">&times;</span>
                        </div>
                        <form id="reviewForm" class="review-form">
                            @csrf
                            <input type="hidden" name="vendor_id" value="{{ $vendor->id }}">
                            <div class="form-group">
                                <label for="review_rating">Rating</label>
                                <div class="rating-input">
                                    @for($i = 5; $i >= 1; $i--)
                                    <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}">
                                    <label for="star{{ $i }}" class="star-label">
                                        <i class="fas fa-star"></i>
                                    </label>
                                    @endfor
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="review_comment">Your Review</label>
                                <textarea id="review_comment" name="comment" rows="4" required placeholder="Share your experience with this vendor..."></textarea>
                            </div>
                            <div class="form-group">
                                <label for="reviewer_name">Your Name (Optional)</label>
                                <input type="text" id="reviewer_name" name="customer_name" placeholder="Anonymous">
                            </div>
                            <div class="form-actions">
                                <button type="button" class="btn btn-secondary" onclick="closeReviewModal()">Cancel</button>
                                <button type="submit" class="btn btn-primary">Submit Review</button>
                            </div>
                        </form>
                    </div>
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

                    @if(session('success'))
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    <form class="message-form" action="{{ route('vendor.contact', $vendor->id) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="name">Your Name</label>
                            <input type="text" id="name" name="name" required value="{{ old('name') }}">
                            @error('name')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="email">Your Email</label>
                            <input type="email" id="email" name="email" required value="{{ old('email') }}">
                            @error('email')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="subject">Subject</label>
                            <input type="text" id="subject" name="subject" required value="{{ old('subject') }}">
                            @error('subject')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="message">Message</label>
                            <textarea id="message" name="message" rows="5" required placeholder="Tell us how we can help you...">{{ old('message') }}</textarea>
                            @error('message')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i>
                            Send Message
                        </button>
                    </form>
                </div>

                <!-- Business Hours -->
                @if(isset($businessHours) && count($businessHours) > 0)
                <div class="business-hours">
                    <h3>Business Hours</h3>
                    <div class="hours-grid">
                        @foreach($businessHours as $day => $hours)
                        <div class="day-hours">
                            <span class="day">{{ $day }}</span>
                            <span class="hours">
                                @if($hours['open'] && $hours['close'])
                                    {{ $hours['open'] }} - {{ $hours['close'] }}
                                @else
                                    Closed
                                @endif
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
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
                @if(isset($offers) && $offers->count() > 0)
                <div class="offers-grid">
                    @foreach($offers as $offer)
                    <div class="offer-card {{ $offer->type ?? 'featured' }}">
                        <div class="offer-badge {{ $offer->type ?? 'featured' }}">{{ ucfirst($offer->type ?? 'Featured') }}</div>
                        <div class="offer-content">
                            <h3>{{ $offer->title ?? 'Special Offer' }}</h3>
                            <p>{{ $offer->description ?? 'Get amazing deals on our products' }}</p>
                            <div class="offer-details">
                                <span class="discount">{{ $offer->discount_amount ?? '0' }}{{ $offer->discount_type == 'percentage' ? '%' : '$' }} OFF</span>
                                <span class="validity">
                                    @if($offer->valid_until)
                                        Valid until {{ \Carbon\Carbon::parse($offer->valid_until)->format('M d, Y') }}
                                    @else
                                        Always Available
                                    @endif
                                </span>
                            </div>
                            @if($offer->code)
                            <div class="offer-code">
                                <span>Use Code:</span>
                                <code>{{ $offer->code }}</code>
                            </div>
                            @endif
                            @if($offer->terms)
                            <div class="offer-terms">
                                <small>{{ $offer->terms }}</small>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="offers-grid">
                    <!-- Default Offers when none exist -->
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
                @endif

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

.products-count {
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
        gap: 2rem;
        margin-bottom: 2rem;
        margin-top: 1rem;
    }

    .offer-card {
        position: relative;
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        border-radius: 12px;
        padding: 1.5rem;
        border: 2px solid transparent;
        transition: all 0.3s ease;
        overflow: visible;
        margin-top: 2rem;
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
        z-index: 3;
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

    /* Ensure proper spacing for offer content */
    .offer-content {
        position: relative;
        z-index: 1;
    }

    .offer-content p {
        margin-bottom: 1rem;
        line-height: 1.6;
    }

    .offer-details {
        margin-top: 1rem;
    }

    .offer-content h3 {
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 0.75rem;
        margin-top: 2rem;
        padding-right: 80px;
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
    grid-template-columns: repeat(5, 1fr);
    gap: 1rem;
    margin-bottom: 2rem;
}

.product-card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    min-width: 0;
}

.product-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.product-image-container {
    position: relative;
    height: 100px;
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
    padding: 0.75rem;
}

.product-title {
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
    line-height: 1.3;
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
    font-size: 0.7rem;
    margin-bottom: 0.25rem;
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
    font-size: 1rem;
    font-weight: 700;
    color: #38a169;
}

.price-old {
    font-size: 0.8rem;
    color: #a0aec0;
    text-decoration: line-through;
}

.product-rating {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    margin-bottom: 0.5rem;
}

.product-actions {
    display: flex;
    gap: 0.25rem;
    justify-content: space-between;
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

.btn-icon {
    padding: 0.5rem;
    min-width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    font-size: 0.9rem;
}

.btn-outline {
    background: transparent;
    color: #667eea;
    border: 1px solid #667eea;
}

.btn-outline:hover {
    background: #667eea;
    color: white;
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
        grid-template-columns: repeat(2, 1fr);
        gap: 0.75rem;
    }

    .product-image-container {
        height: 80px;
    }

    .product-info {
        padding: 0.5rem;
    }

    .product-title {
        font-size: 0.8rem;
    }

    .btn-icon {
        min-width: 32px;
        height: 32px;
        padding: 0.4rem;
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

    .product-filters {
        margin-bottom: 2rem;
        padding: 1.5rem;
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .search-box {
        position: relative;
        margin-bottom: 1rem;
    }

    .search-input {
        width: 100%;
        padding: 0.75rem 1rem 0.75rem 2.5rem;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 1rem;
        transition: border-color 0.2s ease;
    }

    .search-input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #a0aec0;
    }

    .filter-options {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    .filter-select {
        padding: 0.75rem;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 1rem;
        background: white;
        cursor: pointer;
        transition: border-color 0.2s ease;
    }

    .filter-select:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    /* Product Badges */
    .featured-badge, .discount-badge {
        position: absolute;
        top: 1rem;
        left: 1rem;
        background: #ff6b6b;
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        z-index: 2;
    }

    .featured-badge {
        background: #ffd700;
        color: #2d3748;
    }

    .discount-badge {
        background: #38a169;
        color: white;
    }

    .product-stock {
        margin-bottom: 1rem;
    }

    .in-stock {
        color: #38a169;
        font-size: 0.9rem;
        font-weight: 600;
    }

    .out-of-stock {
        color: #e53e3e;
        font-size: 0.9rem;
        font-weight: 600;
    }

    /* Review Styles */
    .reviews-list {
        margin-top: 2rem;
    }

    .review-item {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        border: 1px solid #e2e8f0;
    }

    .review-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }

    .reviewer-info {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .reviewer-avatar {
        width: 50px;
        height: 50px;
        background: #667eea;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
    }

    .reviewer-details h4 {
        margin: 0 0 0.25rem 0;
        color: #2d3748;
        font-size: 1.1rem;
    }

    .review-rating {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .review-rating .rating-value {
        font-weight: 600;
        color: #667eea;
    }

    .review-date {
        color: #718096;
        font-size: 0.9rem;
    }

    .review-content p {
        color: #4a5568;
        line-height: 1.6;
        margin: 0 0 1rem 0;
    }

    .reviewed-product {
        color: #718096;
        font-size: 0.9rem;
        font-style: italic;
    }

    .review-cta {
        text-align: center;
        margin-top: 1rem;
    }

    .reviews-pagination {
        margin-top: 2rem;
        display: flex;
        justify-content: center;
    }

    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
    }

    .modal-content {
        background-color: white;
        margin: 5% auto;
        padding: 0;
        border-radius: 12px;
        width: 90%;
        max-width: 500px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        animation: modalSlideIn 0.3s ease;
    }

    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: translateY(-50px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .modal-header {
        padding: 1.5rem;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header h3 {
        margin: 0;
        color: #2d3748;
    }

    .close {
        color: #a0aec0;
        font-size: 2rem;
        font-weight: bold;
        cursor: pointer;
        transition: color 0.2s ease;
    }

    .close:hover {
        color: #2d3748;
    }

    .review-form {
        padding: 1.5rem;
    }

    .rating-input {
        display: flex;
        flex-direction: row-reverse;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }

    .rating-input input[type="radio"] {
        display: none;
    }

    .star-label {
        font-size: 2rem;
        color: #e2e8f0;
        cursor: pointer;
        transition: color 0.2s ease;
    }

    .star-label:hover,
    .star-label:hover ~ .star-label,
    .rating-input input[type="radio"]:checked ~ .star-label {
        color: #ffd700;
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        margin-top: 1.5rem;
    }

    /* Business Hours */
    .business-hours {
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid #e2e8f0;
    }

    .business-hours h3 {
        color: #2d3748;
        margin-bottom: 1.5rem;
        font-size: 1.3rem;
    }

    .hours-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    .day-hours {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem;
        background: #f7fafc;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
    }

    .day {
        font-weight: 600;
        color: #2d3748;
    }

    .hours {
        color: #4a5568;
        font-size: 0.9rem;
    }

    /* Error Messages */
    .error-message {
        color: #e53e3e;
        font-size: 0.9rem;
        margin-top: 0.25rem;
        display: block;
    }

    /* Success Alert */
    .alert {
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .alert-success {
        background: #f0fff4;
        border: 1px solid #9ae6b4;
        color: #22543d;
    }

    .alert i {
        font-size: 1.2rem;
    }

    /* Product Filters and Search */
    .product-filters {
        margin-bottom: 2rem;
        padding: 1.5rem;
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .search-box {
        position: relative;
        margin-bottom: 1rem;
    }

    .search-input {
        width: 100%;
        padding: 0.75rem 1rem 0.75rem 2.5rem;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 1rem;
        transition: border-color 0.2s ease;
    }

    .search-input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #a0aec0;
    }

    .filter-options {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    .filter-select {
        padding: 0.75rem;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 1rem;
        background: white;
        cursor: pointer;
        transition: border-color 0.2s ease;
    }

    .filter-select:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    /* Product Badges */
    .featured-badge, .discount-badge {
        position: absolute;
        top: 1rem;
        left: 1rem;
        background: #ff6b6b;
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        z-index: 2;
    }

    .featured-badge {
        background: #ffd700;
        color: #2d3748;
    }

    .discount-badge {
        background: #38a169;
        color: white;
    }

    .product-stock {
        margin-bottom: 1rem;
    }

    .in-stock {
        color: #38a169;
        font-size: 0.9rem;
        font-weight: 600;
    }

    .out-of-stock {
        color: #e53e3e;
        font-size: 0.9rem;
        font-weight: 600;
    }

         /* Mobile responsive for product filters */
     @media (max-width: 768px) {
         .product-filters {
             padding: 1rem;
         }

         .filter-options {
             grid-template-columns: 1fr;
         }

         .search-input {
             padding: 0.75rem 1rem 0.75rem 2.5rem;
         }

         .filter-select {
             padding: 0.75rem;
              }

     /* Tablet responsive */
     @media (max-width: 1024px) and (min-width: 769px) {
         .products-grid {
             grid-template-columns: repeat(3, 1fr);
             gap: 0.75rem;
         }
     }

     /* Small desktop */
     @media (max-width: 1200px) and (min-width: 1025px) {
         .products-grid {
             grid-template-columns: repeat(4, 1fr);
             gap: 0.75rem;
         }
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

    // Product Search and Filtering
    const productSearch = document.getElementById('productSearch');
    const categoryFilter = document.getElementById('categoryFilter');
    const priceFilter = document.getElementById('priceFilter');
    const sortFilter = document.getElementById('sortFilter');
    const productsGrid = document.querySelector('.products-grid');

    if (productSearch && productsGrid) {
        // Search functionality
        productSearch.addEventListener('input', filterProducts);

        // Filter functionality
        categoryFilter.addEventListener('change', filterProducts);
        priceFilter.addEventListener('change', filterProducts);
        sortFilter.addEventListener('change', filterProducts);

        function filterProducts() {
            const searchTerm = productSearch.value.toLowerCase();
            const selectedCategory = categoryFilter.value;
            const selectedPrice = priceFilter.value;
            const selectedSort = sortFilter.value;

            const productCards = productsGrid.querySelectorAll('.product-card');
            let visibleProducts = [];

            productCards.forEach(card => {
                let show = true;

                // Search filter
                const productName = card.dataset.name;
                if (searchTerm && !productName.includes(searchTerm)) {
                    show = false;
                }

                // Category filter
                if (selectedCategory && card.dataset.category !== selectedCategory) {
                    show = false;
                }

                // Price filter
                if (selectedPrice) {
                    const price = parseFloat(card.dataset.price);
                    const [min, max] = selectedPrice.split('-').map(p => p === '+' ? Infinity : parseFloat(p));
                    if (price < min || price > max) {
                        show = false;
                    }
                }

                if (show) {
                    card.style.display = 'block';
                    visibleProducts.push(card);
                } else {
                    card.style.display = 'none';
                }
            });

            // Sort products
            sortProducts(visibleProducts, selectedSort);

            // Update grid
            visibleProducts.forEach(card => {
                productsGrid.appendChild(card);
            });

            // Show/hide no products message
            const noProducts = document.querySelector('.no-products');
            if (visibleProducts.length === 0) {
                if (!noProducts) {
                    showNoProductsMessage();
                }
            } else {
                if (noProducts) {
                    noProducts.remove();
                }
            }
        }

        function sortProducts(products, sortBy) {
            products.sort((a, b) => {
                switch (sortBy) {
                    case 'newest':
                        return b.dataset.date - a.dataset.date;
                    case 'oldest':
                        return a.dataset.date - b.dataset.date;
                    case 'price_low':
                        return parseFloat(a.dataset.price) - parseFloat(b.dataset.price);
                    case 'price_high':
                        return parseFloat(b.dataset.price) - parseFloat(a.dataset.price);
                    case 'rating':
                        return parseFloat(b.dataset.rating) - parseFloat(a.dataset.rating);
                    case 'name':
                        return a.dataset.name.localeCompare(b.dataset.name);
                    default:
                        return 0;
                }
            });
        }

        function showNoProductsMessage() {
            const message = document.createElement('div');
            message.className = 'no-products';
            message.innerHTML = `
                <div class="no-products-icon">
                    <i class="fas fa-search"></i>
                </div>
                <h3>No Products Found</h3>
                <p>Try adjusting your search criteria or filters.</p>
            `;
            productsGrid.parentNode.insertBefore(message, productsGrid);
        }
    }

    // Review Modal Functionality
    function showReviewForm() {
        const modal = document.getElementById('reviewModal');
        if (modal) {
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden';
        }
    }

    function closeReviewModal() {
        const modal = document.getElementById('reviewModal');
        if (modal) {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('reviewModal');
        if (event.target === modal) {
            closeReviewModal();
        }
    }

    // Handle review form submission
    const reviewForm = document.getElementById('reviewForm');
    if (reviewForm) {
        reviewForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;

            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
            submitBtn.disabled = true;

            // Simulate form submission (replace with actual AJAX call)
            setTimeout(() => {
                alert('Thank you for your review! It will be published after moderation.');
                closeReviewModal();
                this.reset();
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;

                // Reload page to show new review
                location.reload();
            }, 1500);
        });
    }

    // Dynamic content loading
    function loadDynamicContent() {
        // Load vendor statistics
        loadVendorStats();

        // Load recent activity
        loadRecentActivity();
    }

    function loadVendorStats() {
        // This would make an AJAX call to get updated stats
        // For now, we'll simulate it
        const statsElements = document.querySelectorAll('[data-stat]');
        statsElements.forEach(element => {
            const statType = element.dataset.stat;
            // Simulate loading animation
            element.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

            setTimeout(() => {
                // Simulate updated data
                switch (statType) {
                    case 'rating':
                        element.innerHTML = '{{ number_format($vendor->rating ?? 0, 1) }}';
                        break;
                    case 'orders':
                        element.innerHTML = '{{ $vendor->total_orders ?? 0 }}';
                        break;
                    case 'products':
                        element.innerHTML = '{{ $products->count() }}';
                        break;
                }
            }, 1000);
        });
    }

    function loadRecentActivity() {
        // This would load recent orders, reviews, etc.
        // Implementation depends on your backend structure
    }

    // Initialize dynamic features
    loadDynamicContent();
});
</script>
@endsection
