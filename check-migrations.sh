#!/bin/bash

# Migration verification script for EALifecycle
# Usage: ./check-migrations.sh

echo "🔍 EALifecycle Migration Status Checker"
echo "========================================"

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    echo "❌ Error: This script must be run from the Laravel application root directory"
    exit 1
fi

# Determine PHP binary
if command -v php8.4 &> /dev/null; then
    PHP_BINARY=php8.4
elif command -v php8.3 &> /dev/null; then
    PHP_BINARY=php8.3
elif command -v php8.2 &> /dev/null; then
    PHP_BINARY=php8.2
else
    PHP_BINARY=php
fi

echo "Using PHP: $($PHP_BINARY -v | head -n1)"
echo ""

# Check migration status
echo "📋 Current Migration Status:"
echo "----------------------------"
$PHP_BINARY artisan migrate:status

echo ""

# Count pending migrations
PENDING_MIGRATIONS=$($PHP_BINARY artisan migrate:status | grep "Pending" | wc -l)

if [ $PENDING_MIGRATIONS -eq 0 ]; then
    echo "✅ All migrations are up to date!"
    exit 0
fi

echo "⚠️  Found $PENDING_MIGRATIONS pending migration(s)"
echo ""

# Ask user if they want to run pending migrations
read -p "Do you want to attempt to run pending migrations? (y/n): " -n 1 -r
echo ""

if [[ $REPLY =~ ^[Yy]$ ]]; then
    echo "🚀 Running pending migrations..."
    echo "--------------------------------"
    
    # Try to run all migrations first
    if $PHP_BINARY artisan migrate --force; then
        echo "✅ All migrations completed successfully!"
    else
        echo "⚠️  Some migrations failed. Attempting individual migration runs..."
        echo ""
        
        # Try to run each pending migration individually
        $PHP_BINARY artisan migrate:status | grep "Pending" | while read -r line; do
            MIGRATION_NAME=$(echo "$line" | awk '{print $1}')
            echo "🔄 Attempting: $MIGRATION_NAME"
            
            if $PHP_BINARY artisan migrate --path=database/migrations/${MIGRATION_NAME}.php --force 2>/dev/null; then
                echo "   ✅ Success: $MIGRATION_NAME"
            else
                echo "   ❌ Failed: $MIGRATION_NAME"
                echo "   📋 Error details:"
                $PHP_BINARY artisan migrate --path=database/migrations/${MIGRATION_NAME}.php --force 2>&1 | head -5 | sed 's/^/      /'
            fi
            echo ""
        done
    fi
    
    echo "📋 Final Migration Status:"
    echo "-------------------------"
    $PHP_BINARY artisan migrate:status
    
    FINAL_PENDING=$($PHP_BINARY artisan migrate:status | grep "Pending" | wc -l)
    if [ $FINAL_PENDING -eq 0 ]; then
        echo ""
        echo "🎉 All migrations completed successfully!"
    else
        echo ""
        echo "⚠️  $FINAL_PENDING migration(s) still pending. Manual intervention may be required."
    fi
else
    echo "❌ Migration check cancelled by user"
fi 