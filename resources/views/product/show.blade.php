@extends('layouts.app')

@section('title', $product->name . ' - Grozzoery')
@section('meta_description', $product->description)

@section('content')
<div class="container py-8">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Product Images -->
        <div class="product-images">
            <div class="main-image-container">
                <img src="{{ $product->main_image ? asset('storage/' . $product->main_image) : asset('images/placeholder.png') }}" 
                     alt="{{ $product->name }}" 
                     class="main-image"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <div class="image-placeholder product" style="display: none;">
                    <i class="fas fa-image"></i>
                </div>
            </div>
            
            @if($product->images && count($product->images) > 0)
            <div class="thumbnail-images">
                @foreach($product->images as $image)
                <div class="thumbnail-item">
                    <img src="{{ asset('storage/' . $image->image_path) }}" 
                         alt="{{ $product->name }}" 
                         class="thumbnail-image"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="image-placeholder thumbnail" style="display: none;">
                        <i class="fas fa-image"></i>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        <!-- Product Info -->
        <div class="product-info">
            <h1 class="product-title">{{ $product->name }}</h1>
            
            <div class="product-vendor">
                <span>Sold by:</span>
                <a href="{{ route('vendor', $product->vendor->id) }}" class="vendor-link">
                    {{ $product->vendor->business_name }}
                </a>
            </div>

            <div class="product-rating">
                <div class="rating">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star star {{ $i <= ($product->average_rating ?? 0) ? '' : 'empty' }}"></i>
                    @endfor
                    <span class="rating-text">({{ $product->reviews_count ?? 0 }} reviews)</span>
                </div>
            </div>

            <div class="product-price">
                <span class="price-new">${{ number_format($product->price, 2) }}</span>
                @if($product->original_price && $product->original_price > $product->price)
                    <span class="price-old">${{ number_format($product->original_price, 2) }}</span>
                @endif
            </div>

            <div class="product-description">
                <h3>Description</h3>
                <p>{{ $product->description }}</p>
            </div>

            @if($product->specifications)
            <div class="product-specifications">
                <h3>Specifications</h3>
                <div class="specs-list">
                    @foreach(json_decode($product->specifications, true) ?? [] as $key => $value)
                    <div class="spec-item">
                        <span class="spec-key">{{ $key }}:</span>
                        <span class="spec-value">{{ $value }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="product-availability">
                <span class="availability-label">Availability:</span>
                @if($product->quantity > 0)
                    <span class="availability-in-stock">In Stock ({{ $product->quantity }} available)</span>
                @else
                    <span class="availability-out-of-stock">Out of Stock</span>
                @endif
            </div>

            @if($product->quantity > 0)
            <form class="add-to-cart-form" action="{{ route('cart.add') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                
                <div class="quantity-selector">
                    <label for="quantity">Quantity:</label>
                    <div class="quantity-controls">
                        <button type="button" class="quantity-btn quantity-minus">-</button>
                        <input type="number" id="quantity" name="quantity" value="1" min="1" max="{{ $product->quantity }}" class="quantity-input">
                        <button type="button" class="quantity-btn quantity-plus">+</button>
                    </div>
                </div>

                <div class="product-actions">
                    <button type="submit" class="btn btn-primary add-to-cart-btn">
                        <i class="fas fa-shopping-cart"></i>
                        Add to Cart
                    </button>
                    
                    <button type="button" class="btn btn-outline add-to-wishlist-btn" data-product-id="{{ $product->id }}">
                        <i class="fas fa-heart"></i>
                        Add to Wishlist
                    </button>
                </div>
            </form>
            @else
            <div class="out-of-stock-notice">
                <p>This product is currently out of stock.</p>
                <button class="btn btn-outline notify-me-btn">
                    <i class="fas fa-bell"></i>
                    Notify When Available
                </button>
            </div>
            @endif

            <div class="product-meta">
                <div class="meta-item">
                    <span class="meta-label">SKU:</span>
                    <span class="meta-value">{{ $product->sku }}</span>
                </div>
                <div class="meta-item">
                    <span class="meta-label">Category:</span>
                    <a href="{{ route('category', $product->category->slug) }}" class="meta-link">
                        {{ $product->category->name }}
                    </a>
                </div>
                <div class="meta-item">
                    <span class="meta-label">Tags:</span>
                    <div class="product-tags">
                        @foreach($product->tags ?? [] as $tag)
                            <span class="tag">{{ $tag->name }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Reviews -->
    @if($product->reviews && count($product->reviews) > 0)
    <div class="product-reviews">
        <h2>Customer Reviews</h2>
        <div class="reviews-list">
            @foreach($product->reviews as $review)
            <div class="review-item">
                <div class="review-header">
                    <div class="reviewer-info">
                        <span class="reviewer-name">{{ $review->user->name }}</span>
                        <div class="review-rating">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star star {{ $i <= $review->rating ? '' : 'empty' }}"></i>
                            @endfor
                        </div>
                    </div>
                    <span class="review-date">{{ $review->created_at->format('M d, Y') }}</span>
                </div>
                <div class="review-content">
                    <p>{{ $review->comment }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Related Products -->
    @if($relatedProducts && count($relatedProducts) > 0)
    <div class="related-products">
        <h2>Related Products</h2>
        <div class="products-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($relatedProducts as $relatedProduct)
            <div class="product-card">
                <div class="product-image-container">
                    <img src="{{ $relatedProduct->main_image ? asset('storage/' . $relatedProduct->main_image) : asset('images/placeholder.png') }}" 
                         alt="{{ $relatedProduct->name }}" 
                         class="product-image"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="image-placeholder product" style="display: none;">
                        <i class="fas fa-image"></i>
                    </div>
                </div>
                <div class="product-info">
                    <h3 class="product-title">{{ $relatedProduct->name }}</h3>
                    <p class="product-vendor">{{ $relatedProduct->vendor->business_name }}</p>
                    <div class="product-price">
                        <span class="price-new">${{ number_format($relatedProduct->price, 2) }}</span>
                    </div>
                    <div class="product-actions">
                        <a href="{{ route('product', $relatedProduct->slug) }}" class="btn btn-primary">View Details</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
