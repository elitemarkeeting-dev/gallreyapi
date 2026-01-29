#!/bin/bash

# Gallrey API - Manual Deployment Script
# This script can be run directly on the server or via SSH

set -e

PROJECT_PATH="${1:-.}"
cd "$PROJECT_PATH"

echo "🚀 Starting Gallrey API Deployment..."
echo "📁 Project Path: $PROJECT_PATH"

# Colors for output
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Step 1: Fetch latest code
echo -e "${BLUE}Step 1: Fetching latest code from GitHub...${NC}"
git fetch origin
git reset --hard origin/main
echo -e "${GREEN}✓ Code updated${NC}\n"

# Step 2: Install dependencies
echo -e "${BLUE}Step 2: Installing Composer dependencies...${NC}"
composer install --no-interaction --prefer-dist --optimize-autoloader
echo -e "${GREEN}✓ Dependencies installed${NC}\n"

# Step 3: Run migrations
echo -e "${BLUE}Step 3: Running database migrations...${NC}"
php artisan migrate --force
echo -e "${GREEN}✓ Migrations completed${NC}\n"

# Step 4: Clear caches
echo -e "${BLUE}Step 4: Clearing caches...${NC}"
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
echo -e "${GREEN}✓ Caches cleared${NC}\n"

# Step 5: Optimize application
echo -e "${BLUE}Step 5: Optimizing application...${NC}"
php artisan optimize
php artisan queue:restart
echo -e "${GREEN}✓ Application optimized${NC}\n"

# Step 6: Set permissions
echo -e "${BLUE}Step 6: Setting directory permissions...${NC}"
chmod -R 775 storage bootstrap/cache
chmod -R 755 public
echo -e "${GREEN}✓ Permissions set${NC}\n"

# Step 7: Verify installation
echo -e "${BLUE}Step 7: Verifying deployment...${NC}"
php artisan about
echo -e "${GREEN}✓ Verification complete${NC}\n"

echo -e "${GREEN}═══════════════════════════════════════════════════${NC}"
echo -e "${GREEN}🎉 Deployment completed successfully!${NC}"
echo -e "${GREEN}═══════════════════════════════════════════════════${NC}"
echo ""
echo "📊 Deployment Summary:"
echo "  - Code: Latest from main branch"
echo "  - Dependencies: Installed & optimized"
echo "  - Database: Migrations executed"
echo "  - Cache: Cleared & optimized"
echo "  - Permissions: Set correctly"
echo ""
echo "👉 Next steps:"
echo "  1. Verify the application is running"
echo "  2. Check logs: tail -f storage/logs/laravel.log"
echo "  3. Test the application"
echo ""
