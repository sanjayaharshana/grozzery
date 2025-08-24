@extends('layouts.app')

@section('title', 'Grozzoery - Multivendor Ecommerce')
@section('meta_description', 'Your trusted multivendor ecommerce platform for quality products')

@section('content')
<div class="container py-6">
    <!-- Hero Section -->
    <div class="hero">
        <div class="text-center">
            <h1>Welcome to Grozzoery</h1>
            <p>Discover amazing products from trusted vendors</p>
            <a href="{{ route('shop') }}" class="btn btn-primary">
                Shop Now
            </a>
        </div>
    </div>

    <!-- Banner Section -->
    <div class="banner-section">
        <div class="container">
            <div class="banner-grid">
                <div class="banner-item banner-large">
                    <div class="banner-content">
                        <h3>Flash Sale</h3>
                        <p>Up to 70% Off</p>
                        <span class="banner-timer" id="flash-sale-timer">23:59:59</span>
                        <a href="{{ route('shop') }}?sale=flash" class="btn btn-light">Shop Now</a>
                    </div>
                    <div class="banner-image">
                        <i class="fas fa-fire"></i>
                    </div>
                </div>
                
                <div class="banner-item">
                    <div class="banner-content">
                        <h4>New Arrivals</h4>
                        <p>Fresh Products Daily</p>
                        <a href="{{ route('shop') }}?sort=newest" class="btn btn-sm">Explore</a>
                    </div>
                    <div class="banner-icon">
                        <i class="fas fa-star"></i>
                    </div>
                </div>
                
                <div class="banner-item">
                    <div class="banner-content">
                        <h4>Free Shipping</h4>
                        <p>On Orders Over $50</p>
                        <a href="{{ route('shop') }}" class="btn btn-sm">Shop Now</a>
                    </div>
                    <div class="banner-icon">
                        <i class="fas fa-truck"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Featured Products -->
    @if($featuredProducts->count() > 0)
    <section class="mb-6">
        <h2>Featured Products</h2>
        <div class="grid grid-cols-1 grid-cols-2 grid-cols-4">
            @foreach($featuredProducts as $product)
            <div class="product-card" onclick="window.location.href='{{ route('product', $product->slug) }}'" style="cursor: pointer;">
                <img src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->name }}" class="product-image" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <div class="image-placeholder product" style="display: none;">
                    <i class="fas fa-image"></i>
                </div>
                <div class="product-info">
                    <h3 class="product-title">{{ $product->name }}</h3>
                    <p class="product-vendor">{{ $product->vendor->business_name }}</p>
                    <div class="product-price">
                        <span class="price-new">{{ number_format($product->price, 2) }}</span>
                        @if($product->hasDiscount())
                        <span class="price-old">{{ number_format($product->compare_price, 2) }}</span>
                        @endif
                    </div>
                    <div class="product-actions" onclick="event.stopPropagation()">
                        <button class="btn btn-secondary add-to-cart-btn" data-product-id="{{ $product->id }}" data-product-name="{{ $product->name }}" title="Add to Cart">
                            <i class="fas fa-shopping-cart"></i>
                        </button>
                        <button class="btn btn-outline wishlist-btn" data-product-id="{{ $product->id }}" data-product-name="{{ $product->name }}" title="Add to Wishlist">
                            <i class="fas fa-heart"></i>
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    <!-- Bestseller Products -->
    @if($bestsellerProducts->count() > 0)
    <section class="mb-6">
        <h2>Bestsellers</h2>
        <div class="grid grid-cols-1 grid-cols-2 grid-cols-4">
            @foreach($bestsellerProducts as $product)
            <div class="product-card" onclick="window.location.href='{{ route('product', $product->slug) }}'" style="cursor: pointer;">
                <img src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->name }}" class="product-image" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <div class="image-placeholder product" style="display: none;">
                    <i class="fas fa-image"></i>
                </div>
                <div class="product-info">
                    <h3 class="product-title">{{ $product->name }}</h3>
                    <p class="product-vendor">{{ $product->vendor->business_name }}</p>
                    <div class="product-price">
                        <span class="price-new">{{ number_format($product->price, 2) }}</span>
                        @if($product->hasDiscount())
                        <span class="price-old">{{ number_format($product->compare_price, 2) }}</span>
                        @endif
                    </div>
                    <div class="product-actions" onclick="event.stopPropagation()">
                        <button class="btn btn-secondary add-to-cart-btn" data-product-id="{{ $product->id }}" data-product-name="{{ $product->name }}" title="Add to Cart">
                            <i class="fas fa-shopping-cart"></i>
                        </button>
                        <button class="btn btn-outline wishlist-btn" data-product-id="{{ $product->id }}" data-product-name="{{ $product->name }}" title="Add to Wishlist">
                            <i class="fas fa-heart"></i>
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    <!-- Categories -->
    @if($categories->count() > 0)
    <section class="mb-6">
        <h2>Shop by Category</h2>
        <div class="grid grid-cols-1 grid-cols-2 grid-cols-3">
            @foreach($categories as $category)
            <div class="card">
                @if($category->image)
                <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" style="width: 100%; height: 128px; object-fit: cover;" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <div class="image-placeholder category" style="display: none;">
                    <i class="fas fa-tags"></i>
                </div>
                @else
                <div class="image-placeholder category">
                    <i class="fas fa-tags"></i>
                </div>
                @endif
                <div class="card-body">
                    <h3 class="product-title">{{ $category->name }}</h3>
                    <p class="product-vendor">{{ $category->description }}</p>
                    <a href="{{ route('category', $category->slug) }}" class="btn btn-primary">
                        Browse Category →
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    <!-- Top Vendors -->
    @if($vendors->count() > 0)
    <section class="mb-6">
        <h2>Top Vendors</h2>
        <div class="grid grid-cols-1 grid-cols-2 grid-cols-3">
            @foreach($vendors as $vendor)
            <div class="card">
                <div class="card-body">
                    <div style="display: flex; align-items: center; margin-bottom: 1rem;">
                        @if($vendor->logo)
                        <img src="{{ asset('storage/' . $vendor->logo) }}" alt="{{ $vendor->business_name }}" style="width: 64px; height: 64px; border-radius: 50%; object-fit: cover; margin-right: 1rem;" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="image-placeholder vendor" style="display: none; margin-right: 1rem;">
                            <i class="fas fa-store"></i>
                        </div>
                        @else
                        <div class="image-placeholder vendor" style="margin-right: 1rem;">
                            <i class="fas fa-store"></i>
                        </div>
                        @endif
                        <div>
                            <h3 class="product-title">{{ $vendor->business_name }}</h3>
                            <div style="display: flex; align-items: center;">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $vendor->rating)
                                        <i class="fas fa-star" style="color: #fbbf24; font-size: 0.875rem;"></i>
                                    @else
                                        <i class="far fa-star" style="color: #fbbf24; font-size: 0.875rem;"></i>
                                    @endif
                                @endfor
                                <span style="font-size: 0.875rem; color: #6b7280; margin-left: 0.5rem;">({{ $vendor->rating }})</span>
                            </div>
                        </div>
                    </div>
                    <p class="product-vendor">{{ Str::limit($vendor->business_description, 100) }}</p>
                    <a href="{{ route('vendor', $vendor->id) }}" class="btn btn-primary">
                        Visit Store →
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif
</div>
@endsection
