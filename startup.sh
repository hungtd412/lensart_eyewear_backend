#!/bin/bash

# ==================================================
# Azure Web App Startup Script for Laravel
# LensArt Eyewear Backend
# ==================================================

echo "=========================================="
echo "🚀 Starting LensArt Laravel Application"
echo "=========================================="

# Navigate to application directory
cd /home/site/wwwroot

# ==================================================
# STEP 1: Environment Setup
# ==================================================
echo "📦 Step 1: Setting up environment..."

# Ensure .env file exists
if [ ! -f .env ]; then
    echo "⚠️  .env file not found, copying from .env.production"
    if [ -f .env.production ]; then
        cp .env.production .env
    else
        echo "❌ ERROR: No .env file found!"
        exit 1
    fi
fi

# ==================================================
# STEP 2: Install/Update Dependencies
# ==================================================
echo "📦 Step 2: Installing Composer dependencies..."

# Check if vendor directory exists and is not empty
if [ ! -d "vendor" ] || [ -z "$(ls -A vendor)" ]; then
    echo "Installing dependencies from scratch..."
    composer install --no-dev --optimize-autoloader --no-interaction
else
    echo "Vendor directory exists, skipping composer install"
fi

# ==================================================
# STEP 3: Storage Permissions
# ==================================================
echo "🔐 Step 3: Setting storage permissions..."

# Create necessary directories if they don't exist
mkdir -p storage/framework/cache/data
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs
mkdir -p bootstrap/cache

# Set proper permissions
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Ensure www-data (Azure PHP) can write
chown -R www-data:www-data storage
chown -R www-data:www-data bootstrap/cache

echo "✅ Storage permissions set"

# ==================================================
# STEP 4: Laravel Optimizations
# ==================================================
echo "⚡ Step 4: Running Laravel optimizations..."

# Clear all caches first
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Cache for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run optimizations
php artisan optimize

echo "✅ Laravel optimized"

# ==================================================
# STEP 5: Database Migrations (Optional)
# ==================================================
echo "🗄️  Step 5: Checking database migrations..."

# Uncomment to run migrations automatically on deploy
# ⚠️  WARNING: Use with caution in production!
# php artisan migrate --force

echo "ℹ️  Skipping auto-migrations (run manually if needed)"

# ==================================================
# STEP 6: Queue Worker Setup
# ==================================================
echo "📨 Step 6: Queue worker configuration..."

# Check if queue connection is azure-queue
QUEUE_CONNECTION=$(php artisan tinker --execute="echo config('queue.default');")
echo "Queue connection: $QUEUE_CONNECTION"

# Note: Azure Web App will handle queue workers separately
# via Webjobs or Azure Functions
echo "ℹ️  Queue workers should be configured as Webjobs"

# ==================================================
# STEP 7: Application Key Check
# ==================================================
echo "🔑 Step 7: Checking application key..."

if grep -q "APP_KEY=$" .env || grep -q "APP_KEY=\"\"" .env; then
    echo "⚠️  Generating application key..."
    php artisan key:generate --force
else
    echo "✅ Application key exists"
fi

# ==================================================
# STEP 8: Health Check
# ==================================================
echo "🏥 Step 8: Running health checks..."

# Check if app can boot
php artisan about --only=environment,cache 2>&1 | head -20

echo "✅ Health check completed"

# ==================================================
# STEP 9: Start PHP-FPM
# ==================================================
echo "🚀 Step 9: Starting PHP-FPM..."

# Azure will handle PHP-FPM, but we can ensure it's configured
echo "✅ PHP-FPM will be started by Azure platform"

# ==================================================
# Final Status
# ==================================================
echo ""
echo "=========================================="
echo "✅ LensArt Laravel Application Ready!"
echo "=========================================="
echo "📍 App URL: $APP_URL"
echo "📦 Environment: $APP_ENV"
echo "🗄️  Database: $DB_CONNECTION"
echo "📨 Queue: $QUEUE_CONNECTION"
echo "=========================================="

# Keep the script running for logging
# Azure expects script to exit for container to continue
exit 0

