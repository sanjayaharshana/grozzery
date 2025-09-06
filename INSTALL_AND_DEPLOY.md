# 🚀 Grozzery - Install and Deploy Guide

This comprehensive guide will walk you through the complete installation and deployment process for the Grozzery multi-vendor grocery delivery platform.

## 📋 **Table of Contents**

1. [System Requirements](#system-requirements)
2. [Local Development Setup](#local-development-setup)
3. [Production Deployment](#production-deployment)
4. [Environment Configuration](#environment-configuration)
5. [Database Setup](#database-setup)
6. [Google Maps API Setup](#google-maps-api-setup)
7. [SSL and Security](#ssl-and-security)
8. [Performance Optimization](#performance-optimization)
9. [Monitoring and Maintenance](#monitoring-and-maintenance)
10. [Troubleshooting](#troubleshooting)

---

## 🖥️ **System Requirements**

### **Minimum Requirements**
- **PHP**: 8.2 or higher
- **Composer**: 2.0 or higher
- **Node.js**: 18.0 or higher
- **NPM**: 8.0 or higher
- **MySQL**: 8.0 or higher
- **Web Server**: Apache 2.4+ or Nginx 1.18+
- **Memory**: 2GB RAM minimum
- **Storage**: 10GB available space

### **Recommended Requirements**
- **PHP**: 8.3 or higher
- **Composer**: 2.6 or higher
- **Node.js**: 20.0 LTS or higher
- **NPM**: 10.0 or higher
- **MySQL**: 8.0 or higher
- **Web Server**: Nginx 1.24+ (recommended)
- **Memory**: 4GB RAM or higher
- **Storage**: 50GB SSD
- **CPU**: 2+ cores

### **PHP Extensions Required**
```bash
# Required PHP extensions
php-bcmath
php-curl
php-dom
php-fileinfo
php-gd
php-json
php-mbstring
php-mysql
php-opcache
php-pdo
php-tokenizer
php-xml
php-zip
```

---

## 🛠️ **Local Development Setup**

### **Step 1: Clone the Repository**
```bash
# Clone the repository
git clone https://github.com/yourusername/grozzery.git
cd grozzery

# Checkout the main branch
git checkout main
```

### **Step 2: Install PHP Dependencies**
```bash
# Install Composer dependencies
composer install

# For development, you might want to install dev dependencies
composer install --dev
```

### **Step 3: Install Node.js Dependencies**
```bash
# Install NPM packages
npm install

# For development
npm install --save-dev
```

### **Step 4: Environment Configuration**
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Generate application encryption key
php artisan key:generate --force
```

### **Step 5: Database Setup**
```bash
# Create database (MySQL)
mysql -u root -p
CREATE DATABASE grozzery CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'grozzery_user'@'localhost' IDENTIFIED BY 'your_secure_password';
GRANT ALL PRIVILEGES ON grozzery.* TO 'grozzery_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;

# Update .env file with database credentials
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=grozzery
DB_USERNAME=grozzery_user
DB_PASSWORD=your_secure_password
```

### **Step 6: Run Migrations and Seeders**
```bash
# Run database migrations
php artisan migrate

# Seed the database with sample data
php artisan db:seed

# For development, you can use factories
php artisan db:seed --class=DevelopmentSeeder
```

### **Step 7: Storage and Permissions**
```bash
# Create storage links
php artisan storage:link

# Set proper permissions (Linux/Mac)
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# For Windows, ensure proper write permissions
```

### **Step 8: Start Development Servers**
```bash
# Start Laravel development server
php artisan serve

# In another terminal, start Vite for frontend assets
npm run dev

# Or use the combined command
composer run dev
```

---

## 🌐 **Production Deployment**

### **Option 1: Traditional VPS/Server Deployment**

#### **Step 1: Server Preparation**
```bash
# Update system packages
sudo apt update && sudo apt upgrade -y

# Install required packages
sudo apt install -y nginx mysql-server php8.2-fpm php8.2-mysql php8.2-xml php8.2-mbstring php8.2-curl php8.2-gd php8.2-zip php8.2-bcmath php8.2-opcache composer git unzip
```

#### **Step 2: PHP Configuration**
```bash
# Edit PHP configuration
sudo nano /etc/php/8.2/fpm/php.ini

# Recommended settings for production
upload_max_filesize = 64M
post_max_size = 64M
memory_limit = 512M
max_execution_time = 300
opcache.enable = 1
opcache.memory_consumption = 128
opcache.max_accelerated_files = 4000
opcache.revalidate_freq = 2

# Restart PHP-FPM
sudo systemctl restart php8.2-fpm
```

#### **Step 3: Nginx Configuration**
```bash
# Create Nginx configuration
sudo nano /etc/nginx/sites-available/grozzery

# Basic Nginx configuration
server {
    listen 80;
    server_name yourdomain.com www.yourdomain.com;
    root /var/www/grozzery/public;
    index index.php index.html index.htm;

    # Security headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header Referrer-Policy "no-referrer-when-downgrade" always;
    add_header Content-Security-Policy "default-src 'self' http: https: data: blob: 'unsafe-inline'" always;

    # Gzip compression
    gzip on;
    gzip_vary on;
    gzip_min_length 1024;
    gzip_proxied expired no-cache no-store private must-revalidate auth;
    gzip_types text/plain text/css text/xml text/javascript application/x-javascript application/xml+rss;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Cache static assets
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|pdf|txt)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}

# Enable the site
sudo ln -s /etc/nginx/sites-available/grozzery /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

#### **Step 4: Application Deployment**
```bash
# Create application directory
sudo mkdir -p /var/www/grozzery
sudo chown -R $USER:$USER /var/www/grozzery

# Clone repository
cd /var/www/grozzery
git clone https://github.com/yourusername/grozzery.git .

# Install dependencies
composer install --optimize-autoloader --no-dev
npm install --production
npm run build

# Set permissions
sudo chown -R www-data:www-data /var/www/grozzery
sudo chmod -R 755 /var/www/grozzery
sudo chmod -R 775 storage bootstrap/cache
```

### **Option 2: Docker Deployment**

#### **Step 1: Create Docker Compose File**
```yaml
# docker-compose.yml
version: '3.8'

services:
  app:
    build:
      context: .
      dockerfile: Dockerfile
    container_name: grozzery_app
    restart: unless-stopped
    working_dir: /var/www/
    volumes:
      - ./:/var/www
      - ./docker/php/local.ini:/usr/local/etc/php/conf.d/local.ini
    networks:
      - grozzery

  webserver:
    image: nginx:alpine
    container_name: grozzery_nginx
    restart: unless-stopped
    ports:
      - "8000:80"
    volumes:
      - ./:/var/www
      - ./docker/nginx/conf.d/:/etc/nginx/conf.d/
    networks:
      - grozzery

  db:
    image: mysql:8.0
    container_name: grozzery_db
    restart: unless-stopped
    environment:
      MYSQL_DATABASE: grozzery
      MYSQL_ROOT_PASSWORD: your_root_password
      MYSQL_PASSWORD: your_password
      MYSQL_USER: grozzery_user
    volumes:
      - dbdata:/var/lib/mysql
      - ./docker/mysql/my.cnf:/etc/mysql/my.cnf
    networks:
      - grozzery

  redis:
    image: redis:alpine
    container_name: grozzery_redis
    restart: unless-stopped
    networks:
      - grozzery

networks:
  grozzery:
    driver: bridge

volumes:
  dbdata:
    driver: local
```

#### **Step 2: Create Dockerfile**
```dockerfile
# Dockerfile
FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy existing application directory contents
COPY . /var/www

# Copy existing application directory permissions
COPY --chown=www-data:www-data . /var/www

# Change current user to www
USER www-data

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
```

#### **Step 3: Deploy with Docker**
```bash
# Build and start containers
docker-compose up -d

# Run migrations
docker-compose exec app php artisan migrate

# Seed database
docker-compose exec app php artisan db:seed

# Create storage link
docker-compose exec app php artisan storage:link
```

### **Option 3: Cloud Platform Deployment**

#### **Heroku Deployment**
```bash
# Install Heroku CLI
# Create Procfile
echo "web: vendor/bin/heroku-php-apache2 public/" > Procfile

# Create app.json
{
  "name": "Grozzery",
  "description": "Multi-vendor grocery delivery platform",
  "repository": "https://github.com/yourusername/grozzery",
  "addons": [
    "cleardb:ignite",
    "rediscloud:30"
  ],
  "buildpacks": [
    {
      "url": "heroku/php"
    }
  ]
}

# Deploy
heroku create your-grozzery-app
git push heroku main
heroku run php artisan migrate
```

#### **AWS Elastic Beanstalk**
```bash
# Install EB CLI
pip install awsebcli

# Initialize EB application
eb init grozzery --platform php --region us-east-1

# Create environment
eb create grozzery-prod

# Deploy
eb deploy
```

---

## ⚙️ **Environment Configuration**

### **Production Environment Variables**
```env
APP_NAME="Grozzery"
APP_ENV=production
APP_KEY=base64:your_generated_key
APP_DEBUG=false
APP_URL=https://yourdomain.com

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=grozzery
DB_USERNAME=grozzery_user
DB_PASSWORD=your_secure_password

BROADCAST_DRIVER=log
CACHE_DRIVER=redis
FILESYSTEM_DISK=local
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis
SESSION_LIFETIME=120

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@grozzery.com"
MAIL_FROM_NAME="${APP_NAME}"

GOOGLE_MAPS_API_KEY=your_google_maps_api_key
STRIPE_KEY=your_stripe_public_key
STRIPE_SECRET=your_stripe_secret_key
STRIPE_WEBHOOK_SECRET=your_stripe_webhook_secret

AWS_ACCESS_KEY_ID=your_aws_access_key
AWS_SECRET_ACCESS_KEY=your_aws_secret_key
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=your_s3_bucket
AWS_USE_PATH_STYLE_ENDPOINT=false
```

---

## 🗄️ **Database Setup**

### **Production Database Optimization**
```sql
-- MySQL optimization for production
SET GLOBAL innodb_buffer_pool_size = 1073741824; -- 1GB
SET GLOBAL innodb_log_file_size = 268435456; -- 256MB
SET GLOBAL innodb_flush_log_at_trx_commit = 2;
SET GLOBAL innodb_flush_method = 'O_DIRECT';

-- Create optimized indexes
CREATE INDEX idx_products_vendor_status ON products(vendor_id, status);
CREATE INDEX idx_orders_user_status ON orders(user_id, status);
CREATE INDEX idx_order_items_order ON order_items(order_id);
CREATE INDEX idx_products_category ON products(category_id);
```

### **Database Backup Strategy**
```bash
#!/bin/bash
# backup.sh
DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/backups/grozzery"
DB_NAME="grozzery"
DB_USER="grozzery_user"
DB_PASS="your_password"

# Create backup directory
mkdir -p $BACKUP_DIR

# Create database backup
mysqldump -u $DB_USER -p$DB_PASS $DB_NAME > $BACKUP_DIR/grozzery_$DATE.sql

# Compress backup
gzip $BACKUP_DIR/grozzery_$DATE.sql

# Keep only last 7 days of backups
find $BACKUP_DIR -name "*.sql.gz" -mtime +7 -delete

# Add to crontab for daily backups
# 0 2 * * * /path/to/backup.sh
```

---

## 🗺️ **Google Maps API Setup**

### **Step 1: Create Google Cloud Project**
1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project or select existing one
3. Enable billing for the project

### **Step 2: Enable Required APIs**
```bash
# Enable these APIs:
# - Maps JavaScript API
# - Places API
# - Geocoding API
# - Distance Matrix API
```

### **Step 3: Create API Key**
1. Go to Credentials → Create Credentials → API Key
2. Restrict the API key to your domain
3. Add to your .env file:
```env
GOOGLE_MAPS_API_KEY=your_api_key_here
```

### **Step 4: API Key Restrictions**
```json
{
  "application_restrictions": {
    "http_referrers": {
      "allowed_referrers": [
        "https://yourdomain.com/*",
        "https://www.yourdomain.com/*"
      ]
    }
  },
  "api_targets": {
    "targets": [
      {
        "target": "Maps JavaScript API",
        "methods": ["GET"]
      },
      {
        "target": "Places API",
        "methods": ["GET"]
      },
      {
        "target": "Geocoding API",
        "methods": ["GET"]
      }
    ]
  }
}
```

---

## 🔒 **SSL and Security**

### **Let's Encrypt SSL Setup**
```bash
# Install Certbot
sudo apt install certbot python3-certbot-nginx

# Obtain SSL certificate
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com

# Auto-renewal setup
sudo crontab -e
# Add this line:
0 12 * * * /usr/bin/certbot renew --quiet
```

### **Security Headers**
```nginx
# Add to Nginx configuration
add_header X-Frame-Options "SAMEORIGIN" always;
add_header X-XSS-Protection "1; mode=block" always;
add_header X-Content-Type-Options "nosniff" always;
add_header Referrer-Policy "no-referrer-when-downgrade" always;
add_header Content-Security-Policy "default-src 'self' http: https: data: blob: 'unsafe-inline'" always;
add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;
```

### **Firewall Configuration**
```bash
# UFW Firewall setup
sudo ufw allow 22/tcp
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw enable

# Or with iptables
sudo iptables -A INPUT -p tcp --dport 22 -j ACCEPT
sudo iptables -A INPUT -p tcp --dport 80 -j ACCEPT
sudo iptables -A INPUT -p tcp --dport 443 -j ACCEPT
sudo iptables -A INPUT -j DROP
```

---

## ⚡ **Performance Optimization**

### **Laravel Optimization**
```bash
# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimize autoloader
composer install --optimize-autoloader --no-dev

# Queue optimization
php artisan queue:work --daemon
```

### **Redis Configuration**
```bash
# Install Redis
sudo apt install redis-server

# Configure Redis
sudo nano /etc/redis/redis.conf

# Production settings
maxmemory 256mb
maxmemory-policy allkeys-lru
save 900 1
save 300 10
save 60 10000

# Restart Redis
sudo systemctl restart redis
```

### **Nginx Optimization**
```nginx
# Add to Nginx configuration
# Gzip compression
gzip on;
gzip_vary on;
gzip_min_length 1024;
gzip_proxied expired no-cache no-store private must-revalidate auth;
gzip_types text/plain text/css text/xml text/javascript application/x-javascript application/xml+rss;

# Browser caching
location ~* \.(jpg|jpeg|png|gif|ico|css|js|pdf|txt)$ {
    expires 1y;
    add_header Cache-Control "public, immutable";
}
```

---

## 📊 **Monitoring and Maintenance**

### **Application Monitoring**
```bash
# Install monitoring tools
sudo apt install htop iotop nethogs

# Laravel Telescope (for debugging)
composer require laravel/telescope --dev
php artisan telescope:install
php artisan migrate
```

### **Log Management**
```bash
# Log rotation
sudo nano /etc/logrotate.d/grozzery

/var/www/grozzery/storage/logs/*.log {
    daily
    missingok
    rotate 52
    compress
    delaycompress
    notifempty
    create 644 www-data www-data
}
```

### **Performance Monitoring**
```bash
# Install New Relic (optional)
# Add to composer.json
"require": {
    "newrelic/newrelic-php-agent": "^10.0"
}

# Or use Laravel Horizon for queue monitoring
composer require laravel/horizon
php artisan horizon:install
```

---

## 🔧 **Troubleshooting**

### **Common Issues and Solutions**

#### **1. Permission Issues**
```bash
# Fix storage permissions
sudo chown -R www-data:www-data /var/www/grozzery
sudo chmod -R 755 /var/www/grozzery
sudo chmod -R 775 storage bootstrap/cache
```

#### **2. Database Connection Issues**
```bash
# Check database connection
php artisan tinker
DB::connection()->getPdo();

# Test database credentials
mysql -u grozzery_user -p grozzery
```

#### **3. Cache Issues**
```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

#### **4. Queue Issues**
```bash
# Check queue status
php artisan queue:work --verbose

# Restart queue workers
php artisan queue:restart
```

#### **5. Storage Issues**
```bash
# Recreate storage link
php artisan storage:link

# Check storage permissions
ls -la storage/
```

### **Debug Mode**
```bash
# Enable debug mode temporarily
php artisan config:set app.debug true

# Check logs
tail -f storage/logs/laravel.log

# Disable debug mode for production
php artisan config:set app.debug false
```

---

## 📚 **Additional Resources**

### **Useful Commands**
```bash
# Artisan commands
php artisan list
php artisan route:list
php artisan migrate:status
php artisan queue:work
php artisan schedule:run

# Composer commands
composer dump-autoload
composer update
composer install --optimize-autoloader --no-dev

# NPM commands
npm run dev
npm run build
npm run production
```

### **Maintenance Mode**
```bash
# Enable maintenance mode
php artisan down --message="Upgrading Database" --retry=60

# Disable maintenance mode
php artisan up
```

### **Backup and Restore**
```bash
# Database backup
php artisan db:backup

# Restore from backup
php artisan db:restore backup_file.sql
```

---

## 🎯 **Next Steps After Deployment**

1. **Test all functionality** - Ensure all features work correctly
2. **Set up monitoring** - Implement logging and performance monitoring
3. **Configure backups** - Set up automated database and file backups
4. **Security audit** - Review and harden security settings
5. **Performance testing** - Load test your application
6. **Documentation** - Document your deployment process
7. **Team training** - Train your team on the new system

---

## 📞 **Support and Help**

- **GitHub Issues**: [Create an issue](https://github.com/yourusername/grozzery/issues)
- **Documentation**: [Read the docs](https://docs.grozzery.com)
- **Community**: [Join our Discord](https://discord.gg/grozzery)
- **Email Support**: support@grozzery.com

---

**Happy Deploying! 🚀**

*Last updated: August 2025*
