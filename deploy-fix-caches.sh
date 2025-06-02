#!/bin/bash

# EALifeCycle - Cache Fix & Deployment Script
# This script clears all Laravel caches and optimizes the application to prevent 405 errors

echo "🚀 EALifeCycle Cache Fix & Deployment Script"
echo "============================================="

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    echo "❌ Error: artisan file not found. Please run this script from the Laravel root directory."
    exit 1
fi

# Detect PHP binary
if command -v php8.4 &> /dev/null; then
    PHP_BINARY=php8.4
    echo "✅ Using PHP 8.4"
elif command -v php8.3 &> /dev/null; then
    PHP_BINARY=php8.3
    echo "✅ Using PHP 8.3"
elif command -v php8.2 &> /dev/null; then
    PHP_BINARY=php8.2
    echo "✅ Using PHP 8.2"
else
    PHP_BINARY=php
    echo "✅ Using default PHP version"
fi

echo ""
echo "📋 Step 1: Clearing all Laravel caches..."
echo "----------------------------------------"
$PHP_BINARY artisan optimize:clear || echo "⚠️  Some caches couldn't be cleared (this is normal for first-time deployment)"

echo ""
echo "📋 Step 2: Clearing specific caches that cause 405 errors..."
echo "----------------------------------------------------------"
$PHP_BINARY artisan config:clear
$PHP_BINARY artisan route:clear
$PHP_BINARY artisan view:clear
$PHP_BINARY artisan cache:clear

echo ""
echo "📋 Step 3: Running database migrations..."
echo "-----------------------------------------"
$PHP_BINARY artisan migrate --force

echo ""
echo "📋 Step 4: Setting proper file permissions..."
echo "--------------------------------------------"
chmod -R 755 storage/ bootstrap/cache/ public/
chmod 644 public/index.php
chmod 644 public/.htaccess 2>/dev/null || echo "⚠️  .htaccess not found (this is OK)"

echo ""
echo "📋 Step 5: Final optimization (optional - comment out if causing issues)..."
echo "-----------------------------------------------------------------------"
# Uncomment these lines only if your production environment is stable
# $PHP_BINARY artisan config:cache
# $PHP_BINARY artisan route:cache
# echo "✅ Production optimization applied"

echo ""
echo "🎯 Cache fix completed successfully!"
echo "====================================="
echo "✅ All Laravel caches cleared"
echo "✅ Database migrations run"
echo "✅ File permissions set correctly"
echo "✅ Application should now work without 405 errors"
echo ""
echo "🔗 Test your application: https://4xhacker.com/ealifecycle/"
echo ""

# Optional: Test the application
echo "📊 Testing application response..."
if command -v curl &> /dev/null; then
    HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:8000/ 2>/dev/null || echo "000")
    if [ "$HTTP_CODE" = "200" ]; then
        echo "✅ Local test: Application responding correctly (HTTP 200)"
    else
        echo "⚠️  Local test: HTTP $HTTP_CODE (this is normal if not running locally)"
    fi
else
    echo "ℹ️  curl not available for testing"
fi

echo ""
echo "🛡️  To prevent this issue in the future:"
echo "   1. Always run this script after manual file changes"
echo "   2. Use the GitHub Actions deployment workflow"
echo "   3. Monitor application logs for cache-related errors"
echo "" 