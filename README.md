# 🛒 Grozzery - Multi-Vendor Grocery Delivery Platform

[![Laravel](https://img.shields.io/badge/Laravel-12.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

## 🚀 **Project Overview**

**Grozzery** is a comprehensive, multi-vendor grocery delivery platform built with Laravel 12. The system enables customers to order groceries from multiple local vendors with flexible delivery options, while providing vendors with a complete management dashboard for their products and orders.

## ✨ **Key Features**

### 🏪 **Multi-Vendor Marketplace**
- **Vendor Management**: Complete vendor onboarding and profile management
- **Product Catalog**: Rich product management with variants, images, and categories
- **Order Processing**: Streamlined order management for vendors
- **Commission System**: Built-in vendor commission tracking

### 🛍️ **Customer Experience**
- **Smart Shopping**: Advanced product search, filtering, and sorting
- **Shopping Cart**: Persistent cart with real-time updates
- **Wishlist**: Save favorite products for later
- **Product Reviews**: Customer feedback and rating system
- **Vendor Profiles**: Detailed vendor information and contact forms

### 🚚 **Flexible Delivery System**
- **Address-Based Delivery**: Deliver to saved addresses with fixed shipping
- **Location-Based Delivery**: Interactive map selection with dynamic pricing
- **Real-Time Tracking**: GPS coordinates and distance-based shipping calculation
- **Multiple Addresses**: Manage multiple delivery addresses

### 💳 **Checkout & Payment**
- **Secure Checkout**: Multi-step checkout process
- **Payment Methods**: Credit card, PayPal, and cash on delivery
- **Order Management**: Complete order history and status tracking
- **Invoice Generation**: Detailed order receipts

## 🏗️ **Architecture & Technology Stack**

### **Backend Framework**
- **Laravel 12.x** - Modern PHP framework with robust features
- **PHP 8.2+** - Latest PHP version with enhanced performance
- **MySQL Database** - Reliable relational database system
- **Eloquent ORM** - Elegant database interactions

### **Frontend Technologies**
- **Blade Templates** - Laravel's powerful templating engine
- **Tailwind CSS** - Utility-first CSS framework
- **Alpine.js** - Lightweight JavaScript framework
- **Google Maps API** - Interactive maps and geolocation

### **Key Libraries & Dependencies**
- **Laravel Tinker** - Interactive REPL for Laravel
- **Laravel Sail** - Docker development environment
- **Laravel Pint** - Code style fixer
- **Faker** - Data generation for testing

## 📁 **Project Structure**

```
grozzery/
├── app/
│   ├── Http/Controllers/     # Application controllers
│   │   ├── AuthController.php
│   │   ├── CartController.php
│   │   ├── CheckoutController.php
│   │   ├── HomeController.php
│   │   ├── VendorController.php
│   │   └── AddressController.php
│   ├── Models/               # Eloquent models
│   │   ├── User.php
│   │   ├── Vendor.php
│   │   ├── Product.php
│   │   ├── Order.php
│   │   ├── OrderItem.php
│   │   ├── Category.php
│   │   ├── ProductVariant.php
│   │   ├── ProductImage.php
│   │   ├── ProductReview.php
│   │   ├── Address.php
│   │   └── Tag.php
│   └── Providers/            # Service providers
├── database/
│   ├── migrations/           # Database schema migrations
│   ├── seeders/             # Database seeders
│   └── factories/            # Model factories
├── resources/
│   ├── views/                # Blade templates
│   │   ├── layouts/          # Main layout files
│   │   ├── auth/             # Authentication views
│   │   ├── vendor/           # Vendor dashboard views
│   │   ├── checkout/         # Checkout process views
│   │   ├── cart/             # Shopping cart views
│   │   └── addresses/        # Address management views
│   ├── css/                  # Stylesheets
│   └── js/                   # JavaScript files
├── routes/
│   └── web.php               # Web route definitions
└── public/                   # Public assets
```

## 🚀 **Getting Started**

### **Prerequisites**
- PHP 8.2 or higher
- Composer
- MySQL 8.0 or higher
- Node.js & NPM (for frontend assets)

### **Installation**

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/grozzery.git
   cd grozzery
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Database configuration**
   ```bash
   # Update .env with your database credentials
   php artisan migrate
   php artisan db:seed
   ```

6. **Start the development server**
   ```bash
   php artisan serve
   npm run dev
   ```

### **Environment Variables**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=grozzery
DB_USERNAME=your_username
DB_PASSWORD=your_password

GOOGLE_MAPS_API_KEY=your_google_maps_api_key
```

## 🔧 **Core Features Implementation**

### **Multi-Vendor System**
- **Vendor Registration**: Complete vendor onboarding process
- **Product Management**: Add, edit, and manage products with variants
- **Order Management**: Process and fulfill customer orders
- **Dashboard Analytics**: Sales reports and performance metrics

### **Shopping Experience**
- **Product Discovery**: Advanced search with filters and categories
- **Shopping Cart**: Session-based cart with persistent storage
- **Checkout Process**: Multi-step checkout with delivery options
- **Order Tracking**: Real-time order status updates

### **Delivery System**
- **Flexible Delivery**: Choose between address-based or location-based delivery
- **Dynamic Pricing**: Distance-based shipping cost calculation
- **Map Integration**: Interactive location selection with Google Maps
- **Address Management**: Multiple delivery addresses support

## 📊 **Database Schema**

### **Core Tables**
- **users** - Customer and vendor user accounts
- **vendors** - Vendor information and settings
- **products** - Product catalog with details
- **orders** - Customer orders and delivery information
- **order_items** - Individual items within orders
- **categories** - Product categorization
- **addresses** - Customer delivery addresses

### **Relationships**
- Users can have multiple addresses
- Vendors can have multiple products
- Products can have multiple variants and images
- Orders contain multiple order items
- Products belong to categories and can have tags

## 🧪 **Testing**

```bash
# Run PHPUnit tests
php artisan test

# Run with coverage
php artisan test --coverage
```

## 📝 **API Documentation**

### **Public Endpoints**
- `GET /` - Homepage with featured products
- `GET /shop` - Product catalog with filters
- `GET /product/{slug}` - Individual product details
- `GET /vendor/{id}` - Vendor profile and products
- `POST /vendor/{id}/contact` - Contact vendor

### **Cart Endpoints**
- `GET /cart` - View shopping cart
- `POST /cart/add` - Add product to cart
- `PUT /cart/update` - Update cart quantities
- `DELETE /cart/remove/{key}` - Remove item from cart

### **Checkout Endpoints**
- `GET /checkout` - Checkout page (authenticated)
- `POST /checkout` - Process order (authenticated)
- `GET /checkout/success` - Order confirmation

### **Vendor Endpoints**
- `GET /vendor/dashboard` - Vendor dashboard (authenticated)
- `GET /vendor/products` - Manage products (authenticated)
- `GET /vendor/orders` - View orders (authenticated)

## 🤝 **Contributing**

We welcome contributions! Please see our [Contributing Guidelines](CONTRIBUTING.md) for details.

### **Development Workflow**
1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📄 **License**

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🙏 **Acknowledgments**

- **Laravel Team** - For the amazing framework
- **Tailwind CSS** - For the utility-first CSS framework
- **Google Maps API** - For location services
- **Open Source Community** - For inspiration and support

## 📞 **Support**

- **Issues**: [GitHub Issues](https://github.com/yourusername/grozzery/issues)
- **Discussions**: [GitHub Discussions](https://github.com/yourusername/grozzery/discussions)
- **Email**: support@grozzery.com

---

**Built with ❤️ using Laravel 12**

*Last updated: August 2025*
