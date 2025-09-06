@extends('layouts.app')

@section('title', 'Admin - Home Settings')
@section('meta_description', 'Manage dynamic home page content')

@section('content')
<div class="container py-6">
    <div class="mb-6">
        <h1 class="text-3xl font-bold">Home Page Settings</h1>
        <p class="text-gray-600">Manage dynamic content for your home page</p>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
        {{ session('success') }}
    </div>
    @endif

    <!-- Hero Settings -->
    <div class="card mb-6">
        <div class="card-header">
            <h2 class="text-xl font-semibold">Hero Section Settings</h2>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.home-settings.update') }}">
                @csrf
                <div class="grid grid-cols-1 grid-cols-2 gap-4">
                    <div>
                        <label for="hero_title" class="block text-sm font-medium text-gray-700 mb-2">Hero Title</label>
                        <input type="text" id="hero_title" name="hero_title" 
                               value="{{ HomeSetting::getValue('hero_title', 'Welcome to Grozzoery') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="hero_subtitle" class="block text-sm font-medium text-gray-700 mb-2">Hero Subtitle</label>
                        <input type="text" id="hero_subtitle" name="hero_subtitle" 
                               value="{{ HomeSetting::getValue('hero_subtitle', 'Discover amazing products from trusted vendors') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="hero_button_text" class="block text-sm font-medium text-gray-700 mb-2">Button Text</label>
                        <input type="text" id="hero_button_text" name="hero_button_text" 
                               value="{{ HomeSetting::getValue('hero_button_text', 'Browse Marketplace') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="hero_button_url" class="block text-sm font-medium text-gray-700 mb-2">Button URL</label>
                        <input type="text" id="hero_button_url" name="hero_button_url" 
                               value="{{ HomeSetting::getValue('hero_button_url', '/marketplace') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Update Hero Settings</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Section Titles -->
    <div class="card mb-6">
        <div class="card-header">
            <h2 class="text-xl font-semibold">Section Titles</h2>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.home-settings.update') }}">
                @csrf
                <div class="grid grid-cols-1 grid-cols-2 gap-4">
                    <div>
                        <label for="featured_title" class="block text-sm font-medium text-gray-700 mb-2">Featured Products Title</label>
                        <input type="text" id="featured_title" name="featured_title" 
                               value="{{ HomeSetting::getValue('featured_title', 'Featured Products') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="bestseller_title" class="block text-sm font-medium text-gray-700 mb-2">Bestseller Title</label>
                        <input type="text" id="bestseller_title" name="bestseller_title" 
                               value="{{ HomeSetting::getValue('bestseller_title', 'Bestsellers') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="categories_title" class="block text-sm font-medium text-gray-700 mb-2">Categories Title</label>
                        <input type="text" id="categories_title" name="categories_title" 
                               value="{{ HomeSetting::getValue('categories_title', 'Browse by Category') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="vendors_title" class="block text-sm font-medium text-gray-700 mb-2">Vendors Title</label>
                        <input type="text" id="vendors_title" name="vendors_title" 
                               value="{{ HomeSetting::getValue('vendors_title', 'Top Vendors') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Update Section Titles</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Banners Management -->
    <div class="card mb-6">
        <div class="card-header">
            <h2 class="text-xl font-semibold">Banners Management</h2>
        </div>
        <div class="card-body">
            <!-- Add New Banner Form -->
            <div class="mb-6">
                <h3 class="text-lg font-medium mb-4">Add New Banner</h3>
                <form method="POST" action="{{ route('admin.banners.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 grid-cols-2 gap-4">
                        <div>
                            <label for="banner_title" class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                            <input type="text" id="banner_title" name="title" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label for="banner_type" class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                            <select id="banner_type" name="type" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="flash_sale">Flash Sale</option>
                                <option value="promotion">Promotion</option>
                                <option value="announcement">Announcement</option>
                            </select>
                        </div>
                        <div>
                            <label for="banner_description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <input type="text" id="banner_description" name="description"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label for="banner_icon" class="block text-sm font-medium text-gray-700 mb-2">Icon Class</label>
                            <input type="text" id="banner_icon" name="icon" placeholder="fas fa-fire"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label for="banner_button_text" class="block text-sm font-medium text-gray-700 mb-2">Button Text</label>
                            <input type="text" id="banner_button_text" name="button_text"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label for="banner_button_url" class="block text-sm font-medium text-gray-700 mb-2">Button URL</label>
                            <input type="text" id="banner_button_url" name="button_url"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label for="banner_background_color" class="block text-sm font-medium text-gray-700 mb-2">Background Color</label>
                            <input type="color" id="banner_background_color" name="background_color"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label for="banner_text_color" class="block text-sm font-medium text-gray-700 mb-2">Text Color</label>
                            <input type="color" id="banner_text_color" name="text_color"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label for="banner_image" class="block text-sm font-medium text-gray-700 mb-2">Image</label>
                            <input type="file" id="banner_image" name="image" accept="image/*"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label for="banner_sort_order" class="block text-sm font-medium text-gray-700 mb-2">Sort Order</label>
                            <input type="number" id="banner_sort_order" name="sort_order" value="0"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Add Banner</button>
                    </div>
                </form>
            </div>

            <!-- Existing Banners -->
            <div class="mt-6">
                <h3 class="text-lg font-medium mb-4">Existing Banners</h3>
                <div class="grid grid-cols-1 gap-4">
                    @foreach($banners as $banner)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <h4 class="font-medium">{{ $banner->title }}</h4>
                                <p class="text-sm text-gray-600">{{ $banner->description }}</p>
                                <p class="text-sm text-gray-500">Type: {{ ucfirst(str_replace('_', ' ', $banner->type)) }}</p>
                                <p class="text-sm text-gray-500">Sort Order: {{ $banner->sort_order }}</p>
                                <p class="text-sm text-gray-500">Status: {{ $banner->is_active ? 'Active' : 'Inactive' }}</p>
                            </div>
                            <div class="flex space-x-2">
                                <button onclick="editBanner({{ $banner->id }})" class="btn btn-sm btn-secondary">Edit</button>
                                <form method="POST" action="{{ route('admin.banners.delete', $banner) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Promotions Management -->
    <div class="card mb-6">
        <div class="card-header">
            <h2 class="text-xl font-semibold">Promotions Management</h2>
        </div>
        <div class="card-body">
            <!-- Add New Promotion Form -->
            <div class="mb-6">
                <h3 class="text-lg font-medium mb-4">Add New Promotion</h3>
                <form method="POST" action="{{ route('admin.promotions.store') }}">
                    @csrf
                    <div class="grid grid-cols-1 grid-cols-2 gap-4">
                        <div>
                            <label for="promotion_title" class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                            <input type="text" id="promotion_title" name="title" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label for="promotion_type" class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                            <select id="promotion_type" name="type" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="shipping">Shipping</option>
                                <option value="discount">Discount</option>
                                <option value="offer">Offer</option>
                            </select>
                        </div>
                        <div>
                            <label for="promotion_description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <input type="text" id="promotion_description" name="description"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label for="promotion_icon" class="block text-sm font-medium text-gray-700 mb-2">Icon Class</label>
                            <input type="text" id="promotion_icon" name="icon" placeholder="fas fa-gift"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label for="promotion_button_text" class="block text-sm font-medium text-gray-700 mb-2">Button Text</label>
                            <input type="text" id="promotion_button_text" name="button_text"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label for="promotion_button_url" class="block text-sm font-medium text-gray-700 mb-2">Button URL</label>
                            <input type="text" id="promotion_button_url" name="button_url"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label for="promotion_discount_amount" class="block text-sm font-medium text-gray-700 mb-2">Discount Amount</label>
                            <input type="number" id="promotion_discount_amount" name="discount_amount" step="0.01"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label for="promotion_discount_type" class="block text-sm font-medium text-gray-700 mb-2">Discount Type</label>
                            <select id="promotion_discount_type" name="discount_type"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Select Type</option>
                                <option value="percentage">Percentage</option>
                                <option value="fixed">Fixed Amount</option>
                            </select>
                        </div>
                        <div>
                            <label for="promotion_minimum_order" class="block text-sm font-medium text-gray-700 mb-2">Minimum Order</label>
                            <input type="number" id="promotion_minimum_order" name="minimum_order" step="0.01"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label for="promotion_sort_order" class="block text-sm font-medium text-gray-700 mb-2">Sort Order</label>
                            <input type="number" id="promotion_sort_order" name="sort_order" value="0"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Add Promotion</button>
                    </div>
                </form>
            </div>

            <!-- Existing Promotions -->
            <div class="mt-6">
                <h3 class="text-lg font-medium mb-4">Existing Promotions</h3>
                <div class="grid grid-cols-1 gap-4">
                    @foreach($promotions as $promotion)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <h4 class="font-medium">{{ $promotion->title }}</h4>
                                <p class="text-sm text-gray-600">{{ $promotion->description }}</p>
                                <p class="text-sm text-gray-500">Type: {{ ucfirst($promotion->type) }}</p>
                                @if($promotion->discount_amount)
                                <p class="text-sm text-gray-500">Discount: {{ $promotion->formatted_discount }}</p>
                                @endif
                                @if($promotion->minimum_order)
                                <p class="text-sm text-gray-500">Min Order: ${{ number_format($promotion->minimum_order, 2) }}</p>
                                @endif
                                <p class="text-sm text-gray-500">Sort Order: {{ $promotion->sort_order }}</p>
                                <p class="text-sm text-gray-500">Status: {{ $promotion->is_active ? 'Active' : 'Inactive' }}</p>
                            </div>
                            <div class="flex space-x-2">
                                <button onclick="editPromotion({{ $promotion->id }})" class="btn btn-sm btn-secondary">Edit</button>
                                <form method="POST" action="{{ route('admin.promotions.delete', $promotion) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6">
        <a href="{{ route('home') }}" class="btn btn-secondary">View Home Page</a>
    </div>
</div>

<script>
function editBanner(id) {
    // Implement banner editing functionality
    alert('Banner editing functionality will be implemented');
}

function editPromotion(id) {
    // Implement promotion editing functionality
    alert('Promotion editing functionality will be implemented');
}
</script>
@endsection
