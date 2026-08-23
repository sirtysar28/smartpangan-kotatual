#!/bin/bash
# ================================================
# Smart Pangan Kota Tual - cPanel Deployment Script
# ================================================
# Jalankan via SSH setelah upload project
# Usage: bash deploy-cpanel.sh
# ================================================

set -e

# --- CONFIGURATION (SESUAIKAN!) ---
PROJECT_DIR="$HOME/smart-pangan-kota-tual"
PUBLIC_DIR="$HOME/public_html"
DB_NAME="tualmart_ai"
DB_USER="tualmart_user"
DB_PASS="CHANGE_ME"
APP_URL="https://smartpangan-kotatual.bangunberkahabadi.com"
# -----------------------------------

echo "🚀 Smart Pangan Kota Tual - cPanel Deployment"
echo "===================================="
echo ""

# Step 1: Cek lokasi project
if [ ! -d "$PROJECT_DIR" ]; then
    echo "❌ Folder $PROJECT_DIR tidak ditemukan!"
    echo "   Upload project dulu ke $PROJECT_DIR"
    exit 1
fi

cd "$PROJECT_DIR"
echo "✅ Project ditemukan: $PROJECT_DIR"

# Step 2: Install composer dependencies
echo ""
echo "📦 Installing composer dependencies..."
composer install --no-dev --optimize-autoloader 2>&1 || {
    echo "⚠️  Composer install gagal, coba dengan memory limit..."
    php -d memory_limit=512M $(which composer) install --no-dev --optimize-autoloader
}
echo "✅ Composer dependencies installed"

# Step 3: Setup .env
if [ ! -f .env ]; then
    echo ""
    echo "⚙️  Creating .env file..."
    cp .env.example .env
    php artisan key:generate --force
fi

# Step 4: Pindahkan public files
echo ""
echo "📁 Setting up public directory..."
mkdir -p "$PUBLIC_DIR/build/assets"
cp -r public/build/assets/* "$PUBLIC_DIR/build/assets/" 2>/dev/null || true
cp public/build/manifest.json "$PUBLIC_DIR/build/" 2>/dev/null || true
cp public/favicon.png "$PUBLIC_DIR/" 2>/dev/null || true
cp public/logo.png "$PUBLIC_DIR/" 2>/dev/null || true
cp public/robots.txt "$PUBLIC_DIR/" 2>/dev/null || true
cp public/.htaccess "$PUBLIC_DIR/" 2>/dev/null || true

# Buat index.php untuk public_html
cat > "$PUBLIC_DIR/index.php" << 'PHPEOF'
<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

if (file_exists($maintenance = __DIR__.'/../smart-pangan-kota-tual/storage/framework/maintenance.php')) {
    require $maintenance;
}

require __DIR__.'/../smart-pangan-kota-tual/vendor/autoload.php';

(require_once __DIR__.'/../smart-pangan-kota-tual/bootstrap/app.php')
    ->handleRequest(Request::capture());
PHPEOF

echo "✅ Public files configured"

# Step 5: Storage symlink
echo ""
echo "🔗 Creating storage symlink..."
rm -f "$PUBLIC_DIR/storage"
ln -s "$PROJECT_DIR/storage/app/public" "$PUBLIC_DIR/storage"
echo "✅ Storage symlink created"

# Step 6: Set permissions
echo ""
echo "🔒 Setting permissions..."
chmod -R 755 "$PROJECT_DIR"
chmod -R 775 "$PROJECT_DIR/storage"
chmod -R 775 "$PROJECT_DIR/bootstrap/cache"
chmod 755 "$PUBLIC_DIR"
echo "✅ Permissions set"

# Step 7: Migrate database
echo ""
echo "🗄️  Running database migrations..."
php artisan migrate --force
echo "✅ Migrations complete"

# Step 8: Seed database (hanya pertama kali)
echo ""
read -p "🌱 Run database seeder? (first time only) [y/N]: " SEED
if [ "$SEED" = "y" ] || [ "$SEED" = "Y" ]; then
    php artisan db:seed --force
    echo "✅ Database seeded with demo data"
fi

# Step 9: Clear & cache
echo ""
echo "🧹 Clearing & caching..."
php artisan view:clear
php artisan config:clear
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
echo "✅ Cache optimized"

echo ""
echo "===================================="
echo "🎉 DEPLOYMENT COMPLETE!"
echo "===================================="
echo ""
echo "📋 Next steps:"
echo "   1. Edit .env: nano $PROJECT_DIR/.env"
echo "      - Set APP_URL=$APP_URL"
echo "      - Set DB_DATABASE=$DB_NAME"
echo "      - Set DB_USERNAME=$DB_USER"
echo "      - Set DB_PASSWORD=<your_password>"
echo "      - Set APP_DEBUG=false"
echo "      - Set MIDTRANS_SERVER_KEY=<your_key>"
echo ""
echo "   2. Re-cache config:"
echo "      php artisan config:cache"
echo ""
echo "   3. Setup Cron Job di cPanel:"
echo "      * * * * * cd $PROJECT_DIR && php artisan schedule:run >> /dev/null 2>&1"
echo ""
echo "   4. Setup Midtrans Webhook URL:"
echo "      $APP_URL/api/midtrans/webhook"
echo ""
echo "   5. Setup SSL di cPanel"
echo ""
echo "   6. Buka browser: $APP_URL"
echo ""
