<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\HomeSettingsController;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/marketplace', [HomeController::class, 'marketplace'])->name('marketplace');
Route::get('/product/{slug}', [HomeController::class, 'product'])->name('product');
Route::get('/category/{slug}', [HomeController::class, 'category'])->name('category');
Route::get('/vendor/{id}', [HomeController::class, 'vendor'])->name('vendor');
Route::post('/vendor/{id}/contact', [HomeController::class, 'vendorContact'])->name('vendor.contact');

// Cart routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::put('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{cartKey}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::get('/cart/count', [CartController::class, 'getCartCount'])->name('cart.count');
Route::get('/cart/preview', [CartController::class, 'preview'])->name('cart.preview');

// Checkout routes (require authentication)
Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');

// Address Management Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/addresses', [AddressController::class, 'index'])->name('addresses.index');
    Route::get('/addresses/create', [AddressController::class, 'create'])->name('addresses.create');
    Route::post('/addresses', [AddressController::class, 'store'])->name('addresses.store');
    Route::get('/addresses/{address}/edit', [AddressController::class, 'edit'])->name('addresses.edit');
    Route::put('/addresses/{address}', [AddressController::class, 'update'])->name('addresses.update');
    Route::delete('/addresses/{address}', [AddressController::class, 'destroy'])->name('addresses.destroy');
    Route::post('/addresses/{address}/default', [AddressController::class, 'setDefault'])->name('addresses.default');
});
});

// Vendor routes (require authentication and vendor role)
Route::middleware(['auth', 'vendor'])->prefix('vendor')->name('vendor.')->group(function () {
    Route::get('/dashboard', [VendorController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [VendorController::class, 'profile'])->name('profile');
    Route::put('/profile', [VendorController::class, 'updateProfile'])->name('profile.update');
    
    // Product management
    Route::get('/products', [VendorController::class, 'products'])->name('products.index');
    Route::get('/products/create', [VendorController::class, 'createProduct'])->name('products.create');
    Route::post('/products', [VendorController::class, 'storeProduct'])->name('products.store');
    Route::get('/products/{id}/edit', [VendorController::class, 'editProduct'])->name('products.edit');
    Route::put('/products/{id}', [VendorController::class, 'updateProduct'])->name('products.update');
    
    // Order management
    Route::get('/orders', [VendorController::class, 'orders'])->name('orders.index');
    Route::put('/orders/{id}/status', [VendorController::class, 'updateOrderStatus'])->name('orders.status.update');
});

// Authentication routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin routes for dynamic content management
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/home-settings', [HomeSettingsController::class, 'index'])->name('home-settings');
    Route::post('/home-settings', [HomeSettingsController::class, 'updateSettings'])->name('home-settings.update');
    
    // Banner management
    Route::post('/banners', [HomeSettingsController::class, 'storeBanner'])->name('banners.store');
    Route::put('/banners/{banner}', [HomeSettingsController::class, 'updateBanner'])->name('banners.update');
    Route::delete('/banners/{banner}', [HomeSettingsController::class, 'deleteBanner'])->name('banners.delete');
    
    // Promotion management
    Route::post('/promotions', [HomeSettingsController::class, 'storePromotion'])->name('promotions.store');
    Route::put('/promotions/{promotion}', [HomeSettingsController::class, 'updatePromotion'])->name('promotions.update');
    Route::delete('/promotions/{promotion}', [HomeSettingsController::class, 'deletePromotion'])->name('promotions.delete');
});
