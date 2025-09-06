@extends('layouts.app')

@section('title', 'Grozzoery - Multivendor Ecommerce')
@section('meta_description', 'Your trusted multivendor ecommerce platform for quality products')

@section('content')
<div class="container py-6">
    <!-- Hero Section -->
    <div class="hero">
        <div class="text-center">
            <h1>{{ $heroSettings['title'] }}</h1>
            <p>{{ $heroSettings['subtitle'] }}</p>
            <a href="{{ $heroSettings['button_url'] }}" class="btn btn-primary">
                {{ $heroSettings['button_text'] }}
            </a>
        </div>
    </div>

    <!-- Banner Section -->
    @if($banners->count() > 0)
    <div class="banner-section">
        <div class="container">
            <div class="banner-grid">
                @foreach($banners as $banner)
                <div class="banner-item {{ $banner->type === 'flash_sale' ? 'banner-large' : '' }}" 
                     style="{{ $banner->background_color ? 'background-color: ' . $banner->background_color . ';' : '' }} {{ $banner->text_color ? 'color: ' . $banner->text_color . ';' : '' }}">
                    <div class="banner-content">
                        <h3>{{ $banner->title }}</h3>
                        @if($banner->description)
                        <p>{{ $banner->description }}</p>
                        @endif
                        @if($banner->type === 'flash_sale' && $banner->ends_at)
                        <span class="banner-timer" data-end-time="{{ $banner->ends_at->timestamp }}">00:00:00</span>
                        @endif
                        @if($banner->button_text && $banner->button_url)
                        <a href="{{ $banner->button_url }}" class="btn btn-light">{{ $banner->button_text }}</a>
                        @endif
                    </div>
                    <div class="banner-image">
                        @if($banner->image)
                        <img src="{{ asset('storage/' . $banner->image) }}" alt="{{ $banner->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                        @elseif($banner->icon)
                        <i class="{{ $banner->icon }}"></i>
                        @else
                        <i class="fas fa-fire"></i>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Promotions Section -->
    @if($promotions->count() > 0)
    <div class="promotions-section mb-6">
        <div class="container">
            <div class="grid grid-cols-1 grid-cols-2 grid-cols-3">
                @foreach($promotions as $promotion)
                <div class="promotion-card">
                    <div class="promotion-icon">
                        @if($promotion->icon)
                        <i class="{{ $promotion->icon }}"></i>
                        @else
                        <i class="fas fa-gift"></i>
                        @endif
                    </div>
                    <div class="promotion-content">
                        <h4>{{ $promotion->title }}</h4>
                        @if($promotion->description)
                        <p>{{ $promotion->description }}</p>
                        @endif
                        @if($promotion->minimum_order)
                        <small>Min. order: ${{ number_format($promotion->minimum_order, 2) }}</small>
                        @endif
                        @if($promotion->button_text && $promotion->button_url)
                        <a href="{{ $promotion->button_url }}" class="btn btn-sm">{{ $promotion->button_text }}</a>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Featured Products -->
    @if($featuredProducts->count() > 0)
    <section class="mb-6">
        <h2>{{ $sectionTitles['featured_title'] }}</h2>
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
        <h2>{{ $sectionTitles['bestseller_title'] }}</h2>
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
        <h2>{{ $sectionTitles['categories_title'] }}</h2>
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
        <h2>{{ $sectionTitles['vendors_title'] }}</h2>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Countdown timer for flash sale banners
    const timers = document.querySelectorAll('.banner-timer[data-end-time]');
    
    timers.forEach(function(timer) {
        const endTime = parseInt(timer.getAttribute('data-end-time'));
        
        function updateTimer() {
            const now = Math.floor(Date.now() / 1000);
            const timeLeft = endTime - now;
            
            if (timeLeft <= 0) {
                timer.textContent = '00:00:00';
                return;
            }
            
            const hours = Math.floor(timeLeft / 3600);
            const minutes = Math.floor((timeLeft % 3600) / 60);
            const seconds = timeLeft % 60;
            
            timer.textContent = 
                String(hours).padStart(2, '0') + ':' +
                String(minutes).padStart(2, '0') + ':' +
                String(seconds).padStart(2, '0');
        }
        
        updateTimer();
        setInterval(updateTimer, 1000);
    });
});
</script>
@endsection
