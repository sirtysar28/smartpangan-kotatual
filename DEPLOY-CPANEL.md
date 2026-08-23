# 🚀 PANDUAN DEPLOYMENT TUALMART AI KE CPANEL

## Langkah-langkah Setup di cPanel

---

### STEP 1: Buat Database MySQL

1. Login ke **cPanel** → **MySQL Databases**
2. Buat database baru: `tualmart_ai`
3. Buat user baru: `tualmart_user` dengan password strong
4. **Add user to database** → Beri privilege **ALL PRIVILEGES**
5. Catat: nama database, username, password

---

### STEP 2: Upload Files ke cPanel

**Struktur folder di cPanel (shared hosting):**

```
/home/username/
├── tualmart-ai/              ← Seluruh project Laravel di SINI (di luar public_html)
│   ├── app/
│   ├── bootstrap/
│   ├── config/
│   ├── database/
│   ├── resources/
│   ├── routes/
│   ├── storage/
│   ├── vendor/
│   ├── .env
│   ├── artisan
│   ├── composer.json
│   └── ...
│
└── public_html/              ← Hanya isi folder public/ Laravel
    ├── build/
    │   └── assets/
    ├── .htaccess
    ├── index.php
    ├── favicon.ico
    ├── robots.txt
    └── storage → symlink ke /home/username/tualmart-ai/storage/app/public
```

#### Cara Upload:

**Option A: Via File Manager**
1. Zip semua file project `tualmart-ai/` kecuali `vendor/` dan `node_modules/`
2. Upload zip ke `/home/username/` → Extract
3. Pindahkan isi `tualmart-ai/public/` ke `/home/username/public_html/`

**Option B: Via Terminal/SSH (RECOMMENDED)**
```bash
# SSH ke server
ssh username@server.com

# Upload project via git atau scp, lalu:
cd /home/username/

# Clone atau extract project
# Pastikan struktur: /home/username/tualmart-ai/
```

---

### STEP 3: Edit index.php di public_html

Edit file `/home/username/public_html/index.php`:

```php
<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Maintenance mode
if (file_exists($maintenance = __DIR__.'/../tualmart-ai/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Composer autoloader
require __DIR__.'/../tualmart-ai/vendor/autoload.php';

// Bootstrap Laravel
(require_once __DIR__.'/../tualmart-ai/bootstrap/app.php')
    ->handleRequest(Request::capture());
```

**PENTING:** Ganti path `__DIR__.'/../tualmart-ai/` sesuai lokasi folder project Anda.

---

### STEP 4: Konfigurasi .env

Edit file `/home/username/tualmart-ai/.env`:

```env
APP_NAME="Smart Pangan Kota Tual"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://domainanda.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=tualmart_ai        ← Sesuaikan
DB_USERNAME=tualmart_user       ← Sesuaikan
DB_PASSWORD=password_anda       ← Sesuaikan

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
LOG_CHANNEL=daily

MIDTRANS_SERVER_KEY=SB-Mid-server-xxxxxxxxx  ← Ganti dengan key asli
MIDTRANS_IS_PRODUCTION=false    ← true kalau sudah production Midtrans
```

---

### STEP 5: Setup via Terminal SSH

```bash
cd /home/username/tualmart-ai

# 1. Install composer dependencies
composer install --no-dev --optimize-autoloader

# 2. Clear cache
php artisan view:clear
php artisan config:clear
php artisan cache:clear

# 3. Run migrations
php artisan migrate --force

# 4. Run seeder (hanya pertama kali, untuk data demo)
php artisan db:seed --force

# 5. Create storage symlink
# HAPUS symlink lama dulu kalau ada
rm -f /home/username/public_html/storage
php artisan storage:link

# 6. Optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

**Kalau storage:link gagal di shared hosting, buat manual:**
```bash
ln -s /home/username/tualmart-ai/storage/app/public /home/username/public_html/storage
```

---

### STEP 6: Set Permission Folder

```bash
cd /home/username/tualmart-ai

chmod -R 755 .
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/
chmod -R 755 public_html/

# Pastikan ownership benar
chown -R username:username .
```

---

### STEP 7: Setup .htaccess di public_html

Pastikan `/home/username/public_html/.htaccess` berisi:

```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    RewriteCond %{HTTP:x-xsrf-token} .
    RewriteRule .* - [E=HTTP_X_XSRF_TOKEN:%{HTTP:X-XSRF-Token}]

    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

---

### STEP 8: Setup Cron Job (Opsional - untuk queue & scheduler)

Di **cPanel** → **Cron Jobs**, tambah:

```
* * * * * cd /home/username/tualmart-ai && php artisan schedule:run >> /dev/null 2>&1
```

---

### STEP 9: Midtrans Configuration

1. Login ke **Midtrans Dashboard** (sandbox.midtrans.com)
2. Ke **Settings** → **Payment URL**
3. Tambahkan **Payment Notification URL:**
   ```
   https://domainanda.com/api/midtrans/webhook
   ```
4. Copy **Server Key** → Paste ke `.env` MIDTRANS_SERVER_KEY
5. Untuk production, ganti `MIDTRANS_IS_PRODUCTION=true` dan gunakan key production

---

### STEP 10: SSL Certificate

Di **cPanel** → **SSL/TLS** atau **Let's Encrypt**:
1. Aktifkan SSL untuk domain
2. Force HTTPS (tambahkan di .htaccess):

```apache
RewriteCond %{HTTPS} off
RewriteRule ^ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

---

## 🔧 Troubleshooting

### Error 500 (Internal Server Error)
```bash
# Cek error log
tail -50 /home/username/tualmart-ai/storage/logs/laravel.log

# Atau cek server log
tail -50 /home/username/logs/error_log

# Cek permission
chmod -R 775 storage/ bootstrap/cache/
```

### Blank Page / White Screen
```bash
# Set APP_DEBUG=true sementara untuk debug
# Jangan lupa kembalikan ke false setelah fix!

# Clear semua cache
php artisan view:clear
php artisan config:clear
php artisan cache:clear
```

### Storage Link Tidak Bekerja
```bash
# Manual symlink
rm -f /home/username/public_html/storage
ln -s /home/username/tualmart-ai/storage/app/public /home/username/public_html/storage
```

### Database Connection Error
```bash
# Cek koneksi
php artisan tinker
>>> DB::connection()->getPdo();
>>> exit
```

### Composer Install Gagal (Memory Limit)
```bash
php -d memory_limit=512M $(which composer) install --no-dev --optimize-autoloader
```

### Permission Denied
```bash
chmod -R 755 /home/username/tualmart-ai
chmod -R 775 /home/username/tualmart-ai/storage
chmod -R 775 /home/username/tualmart-ai/bootstrap/cache
```

---

## 📋 Checklist Deployment

- [ ] Database MySQL dibuat di cPanel
- [ ] Files diupload ke `/home/username/tualmart-ai/`
- [ ] `public/` contents dipindah ke `public_html/`
- [ ] `index.php` di-update path-nya
- [ ] `.env` dikonfigurasi (APP_URL, DB, MIDTRANS)
- [ ] `composer install --no-dev` berhasil
- [ ] `php artisan migrate --force` berhasil
- [ ] `php artisan db:seed --force` berhasil (data demo)
- [ ] `storage/` symlink dibuat
- [ ] Permission 755/775 sudah benar
- [ ] `.htaccess` di public_html sudah benar
- [ ] SSL/HTTPS aktif
- [ ] Midtrans webhook URL dikonfigurasi
- [ ] Cron job untuk scheduler
- [ ] `APP_DEBUG=false` di production
- [ ] `php artisan config:cache` dijalankan
- [ ] Test akses website → OK
- [ ] Test login admin → OK
- [ ] Test login UMKM → OK
- [ ] Test login buyer → OK

---

## 🌐 Info Domain & DNS

Kalau pakai subdomain (misal: tualmart.kotatual.go.id):
1. cPanel → **Subdomains** → Buat `tualmart.kotatual.go.id`
2. Document Root arahkan ke `/home/username/public_html`
3. Tunggu DNS propagate (1-24 jam)

---

## 📞 Akun Demo (Setelah Deploy)

| Role | Email | Password |
|------|-------|----------|
| Super Admin | admin@tualmart.id | admin123 |
| OPD | diskop@tual.go.id | admin123 |
| UMKM | ikanasap@gmail.com | umkm123 |
| UMKM | rumputlaut@gmail.com | umkm123 |
| Buyer | buyer@gmail.com | buyer123 |

**⚠️ GANTI password akun admin setelah deploy ke production!**
