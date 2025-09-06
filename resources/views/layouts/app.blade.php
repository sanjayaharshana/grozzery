<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Grozzoery')</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    
    <!-- Immediate Preloader (shows before JavaScript loads) -->
    <div id="immediate-preloader" class="page-preloader">
        <div class="preloader-content">
            <div class="preloader-logo">
                <h2>Grozzoery</h2>
            </div>
            <div class="preloader-spinner">
                <div class="spinner"></div>
            </div>
            <div class="preloader-text">
                <p>Loading amazing products...</p>
            </div>
        </div>
    </div>
</head>
<body>
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="container">
            <div class="top-bar-content">
                <div class="top-bar-left">
                    <span><i class="fas fa-phone"></i> +1 (555) 123-4567</span>
                    <span><i class="fas fa-envelope"></i> support@grozzoery.com</span>
                </div>
                <div class="top-bar-right">
                    <a href="#"><i class="fas fa-truck"></i> Free Shipping</a>
                    <a href="#"><i class="fas fa-shield-alt"></i> Secure Payment</a>
                    <a href="#"><i class="fas fa-headset"></i> 24/7 Support</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Navigation -->
    <nav class="navbar">
        <div class="container">
            <div class="nav-left">
                <a href="{{ route('home') }}" class="logo">Grozzoery</a>
                
                <!-- Categories Dropdown -->
                <div class="categories-dropdown">
                    <button class="categories-toggle">
                        <i class="fas fa-bars"></i> All Categories
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="categories-menu">
                        @foreach($categories ?? [] as $category)
                            <a href="{{ route('category', $category->slug) }}" class="category-item">
                                <i class="fas fa-tag"></i>
                                {{ $category->name }}
                            </a>
                        @endforeach
                        <a href="{{ route('marketplace') }}" class="category-item view-all">
                            <i class="fas fa-th-large"></i>
                            View All Categories
                        </a>
                    </div>
                </div>
            </div>

            <!-- Search Bar -->
            <div class="search-container">
                <form class="search-form" action="{{ route('marketplace') }}" method="GET">
                    <div class="search-input-group">
                        <select name="category" class="search-category">
                            <option value="">All Categories</option>
                            @foreach($categories ?? [] as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <input type="text" name="search" placeholder="What are you looking for today?" class="search-input">
                        <button type="submit" class="search-btn">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>

            <div class="nav-right">
                <div class="nav-actions">
                    <a href="#" class="nav-action">
                        <i class="fas fa-heart"></i>
                        <span class="action-label">Wishlist</span>
                    </a>
                    
                    <div class="cart-dropdown">
                        <a href="{{ route('cart.index') }}" class="nav-action cart-toggle">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="action-label">Cart</span>
                            <span class="cart-badge" id="cart-count">0</span>
                        </a>
                        <div class="cart-preview">
                            <div class="cart-items" id="cart-preview-items">
                                <p class="empty-cart">Your cart is empty</p>
                            </div>
                            <div class="cart-footer">
                                <div class="cart-total">
                                    <span>Total:</span>
                                    <span class="total-amount">$0.00</span>
                                </div>
                                <a href="{{ route('cart.index') }}" class="btn btn-primary">View Cart</a>
                            </div>
                        </div>
                    </div>

                    @auth
                        <div class="user-dropdown">
                            <a href="#" class="nav-action user-toggle">
                                <i class="fas fa-user"></i>
                                <span class="action-label">{{ auth()->user()->name }}</span>
                                <i class="fas fa-chevron-down"></i>
                            </a>
                            <div class="user-menu">
                                <a href="#"><i class="fas fa-user-circle"></i> My Profile</a>
                                <a href="#"><i class="fas fa-shopping-bag"></i> My Orders</a>
                                <a href="#"><i class="fas fa-heart"></i> My Wishlist</a>
                                @if(auth()->user()->isVendor())
                                    <a href="{{ route('vendor.dashboard') }}"><i class="fas fa-store"></i> Vendor Dashboard</a>
                                @endif
                                <div class="user-menu-divider"></div>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="user-menu-item">
                                        <i class="fas fa-sign-out-alt"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="auth-buttons">
                            <a href="{{ route('login') }}" class="btn btn-outline">Login</a>
                            <a href="{{ route('register') }}" class="btn btn-primary">Register</a>
                        </div>
                    @endauth
                </div>
                
                <!-- Mobile Menu Toggle -->
                <button class="mobile-menu-toggle" style="display: none;">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </nav>

    <!-- Category Navigation Bar -->
    <div class="category-nav">
        <div class="container">
            <ul class="category-list">
                <li><a href="{{ route('marketplace') }}?category=electronics"><i class="fas fa-mobile-alt"></i> Electronics</a></li>
                <li><a href="{{ route('marketplace') }}?category=fashion"><i class="fas fa-tshirt"></i> Fashion</a></li>
                <li><a href="{{ route('marketplace') }}?category=home"><i class="fas fa-home"></i> Home & Garden</a></li>
                <li><a href="{{ route('marketplace') }}?category=sports"><i class="fas fa-futbol"></i> Sports</a></li>
                <li><a href="{{ route('marketplace') }}?category=beauty"><i class="fas fa-spa"></i> Beauty</a></li>
                <li><a href="{{ route('marketplace') }}?category=toys"><i class="fas fa-gamepad"></i> Toys & Games</a></li>
                <li><a href="{{ route('marketplace') }}?category=automotive"><i class="fas fa-car"></i> Automotive</a></li>
                <li><a href="{{ route('marketplace') }}?category=health"><i class="fas fa-heartbeat"></i> Health</a></li>
            </ul>
        </div>
    </div>

    <main>
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-error">{{ session('error') }}</div>
            @endif
        </div>

        @yield('content')
    </main>

    <footer class="footer">
        <div class="container">
            <p>&copy; {{ date('Y') }} Grozzoery. All rights reserved.</p>
        </div>
    </footer>
    
    <script src="{{ asset('js/app.js') }}"></script>
    @yield('scripts')
</body>
</html>
