@extends('layouts.app')

@section('title', 'Shop - Grozzoery')

@section('content')
<div class="container py-8">
    <h1 class="text-center mb-6">Shop</h1>
    
    @if($products->count() > 0)
    <div class="grid grid-cols-1 grid-cols-2 grid-cols-3">
        @foreach($products as $product)
        <div class="product-card">
            <img src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->name }}" class="product-image" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
            <div class="image-placeholder product" style="display: none;">
                <i class="fas fa-image"></i>
            </div>
            <div class="product-info">
                <h3 class="product-title">{{ $product->name }}</h3>
                <p class="product-vendor">{{ $product->vendor->business_name }}</p>
                <div class="product-price">
                    <span class="price-new">{{ number_format($product->price, 2) }}</span>
                </div>
                <div class="product-actions">
                    <a href="{{ route('product', $product->slug) }}" class="btn btn-primary">
                        View
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
    
    <div class="mt-8">
        {{ $products->links() }}
    </div>
    @else
    <div class="text-center py-8">
        <p class="text-gray-600">No products found.</p>
    </div>
    @endif
</div>
@endsection
