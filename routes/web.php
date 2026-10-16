<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| PANCA - Pangan Cerdas Maryadat Kota Tual
| Layanan Pangan Cerdas: Sistem Monitoring Stabilisasi Harga, Pasokan, dan Keamanan Pangan Kota Tual
|--------------------------------------------------------------------------
*/

// ==================== PUBLIC SITE PAGES (PROFIL, etc) ====================
Route::get('/halaman/{slug}', [App\Http\Controllers\Frontend\HomeController::class, 'sitePage'])->name('site.page');

// ==================== FRONTEND (PUBLIC) ====================
Route::get('/', [App\Http\Controllers\Frontend\HomeController::class, 'index'])->name('home');
Route::get('/kebutuhan-pokok', [App\Http\Controllers\Frontend\HomeController::class, 'commodities'])->name('frontend.commodities');
Route::get('/ketersediaan-pangan', [App\Http\Controllers\Frontend\HomeController::class, 'availability'])->name('frontend.availability');
Route::get('/kerawanan-pangan', [App\Http\Controllers\Frontend\HomeController::class, 'foodVulnerability'])->name('frontend.food-vulnerability');
Route::get('/keamanan-pangan', [App\Http\Controllers\Frontend\HomeController::class, 'foodSafety'])->name('frontend.food-safety');
Route::get('/perizinan', [App\Http\Controllers\Frontend\HomeController::class, 'permits'])->name('frontend.permits');

Route::get('/products', [App\Http\Controllers\Frontend\ProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [App\Http\Controllers\Frontend\ProductController::class, 'show'])->name('products.show');

Route::get('/umkm/register', [App\Http\Controllers\UmkmRegistrationController::class, 'create'])->name('umkm.register');
Route::post('/umkm/register', [App\Http\Controllers\UmkmRegistrationController::class, 'store'])->name('umkm.register.store');

// ==================== PROFILE ====================
Route::middleware('auth')->group(function () {
    Route::get('/profil', [App\Http\Controllers\Frontend\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profil', [App\Http\Controllers\Frontend\ProfileController::class, 'update'])->name('profile.update');
});

// ==================== AI CHAT (PUBLIC) ====================
Route::post('/api/chat', [App\Http\Controllers\ChatController::class, 'chat'])->name('chat.send');

// ==================== AUTHENTICATED BUYER ====================
Route::middleware('auth')->group(function () {
    // Cart
    Route::get('/cart', [App\Http\Controllers\Frontend\CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [App\Http\Controllers\Frontend\CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update', [App\Http\Controllers\Frontend\CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove/{id}', [App\Http\Controllers\Frontend\CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/clear', [App\Http\Controllers\Frontend\CartController::class, 'clear'])->name('cart.clear');

    // Checkout
    Route::get('/checkout', [App\Http\Controllers\Frontend\CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/process', [App\Http\Controllers\Frontend\CheckoutController::class, 'process'])->name('checkout.process');

    // Orders (Buyer)
    Route::get('/orders', [App\Http\Controllers\Frontend\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [App\Http\Controllers\Frontend\OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/receive', [App\Http\Controllers\Frontend\OrderController::class, 'receive'])->name('orders.receive');
    Route::post('/orders/{order}/cancel', [App\Http\Controllers\Frontend\OrderController::class, 'cancel'])->name('orders.cancel');
    Route::post('/orders/{order}/review', [App\Http\Controllers\Frontend\OrderController::class, 'review'])->name('orders.review');

    // Payment
    Route::get('/payment/{order}/select', [App\Http\Controllers\Payment\MidtransController::class, 'selectMethod'])->name('payment.select');
    Route::get('/payment/{order}/pay', [App\Http\Controllers\Payment\MidtransController::class, 'pay'])->name('payment.pay');
    Route::get('/payment/{order}/transfer', [App\Http\Controllers\Payment\MidtransController::class, 'transfer'])->name('payment.transfer');
    Route::get('/payment/{order}/success', [App\Http\Controllers\Payment\MidtransController::class, 'success'])->name('payment.success');
    Route::get('/payment/{order}/pending', [App\Http\Controllers\Payment\MidtransController::class, 'pending'])->name('payment.pending');
    Route::get('/payment/{order}/error', [App\Http\Controllers\Payment\MidtransController::class, 'error'])->name('payment.error');
});

// ==================== MIDTRANS WEBHOOK ====================
Route::post('/api/midtrans/webhook', [App\Http\Controllers\Api\MidtransWebhookController::class, 'handler']);

// ==================== ADMIN PANEL ====================
Route::middleware(['auth','admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // === PANEL HARGA (Komoditas & Harga Pangan) ===
    Route::get('/commodities', [App\Http\Controllers\Admin\CommodityController::class, 'index'])->name('commodities.index');
    Route::get('/commodities/create', [App\Http\Controllers\Admin\CommodityController::class, 'create'])->name('commodities.create');
    Route::post('/commodities', [App\Http\Controllers\Admin\CommodityController::class, 'store'])->name('commodities.store');
    Route::get('/commodities/{commodity}/edit', [App\Http\Controllers\Admin\CommodityController::class, 'edit'])->name('commodities.edit');
    Route::put('/commodities/{commodity}', [App\Http\Controllers\Admin\CommodityController::class, 'update'])->name('commodities.update');
    Route::post('/commodities/{commodity}/update-price', [App\Http\Controllers\Admin\CommodityController::class, 'updatePrice'])->name('commodities.update-price');
    Route::delete('/commodities/{commodity}', [App\Http\Controllers\Admin\CommodityController::class, 'destroy'])->name('commodities.destroy');

    // === KETERSEDIAAN PANGAN (Stok & Pasokan) ===
    Route::get('/availability', [App\Http\Controllers\Admin\AvailabilityController::class, 'index'])->name('availability.index');
    Route::get('/availability/create', [App\Http\Controllers\Admin\AvailabilityController::class, 'create'])->name('availability.create');
    Route::post('/availability', [App\Http\Controllers\Admin\AvailabilityController::class, 'store'])->name('availability.store');
    Route::get('/availability/{stock}/edit', [App\Http\Controllers\Admin\AvailabilityController::class, 'edit'])->name('availability.edit');
    Route::put('/availability/{stock}', [App\Http\Controllers\Admin\AvailabilityController::class, 'update'])->name('availability.update');
    Route::delete('/availability/{stock}', [App\Http\Controllers\Admin\AvailabilityController::class, 'destroy'])->name('availability.destroy');

    // === KERAWANAN PANGAN (FSVA per Triwulan) ===
    Route::get('/food-vulnerability', [App\Http\Controllers\Admin\FoodVulnerabilityController::class, 'index'])->name('food-vulnerability.index');
    Route::post('/food-vulnerability', [App\Http\Controllers\Admin\FoodVulnerabilityController::class, 'store'])->name('food-vulnerability.store');
    Route::put('/food-vulnerability/{foodVulnerability}', [App\Http\Controllers\Admin\FoodVulnerabilityController::class, 'update'])->name('food-vulnerability.update');
    Route::delete('/food-vulnerability/{foodVulnerability}', [App\Http\Controllers\Admin\FoodVulnerabilityController::class, 'destroy'])->name('food-vulnerability.destroy');

    // === KEAMANAN PANGAN ===
    Route::get('/food-safety', [App\Http\Controllers\Admin\FoodSafetyController::class, 'index'])->name('food-safety.index');
    Route::get('/food-safety/check/create', [App\Http\Controllers\Admin\FoodSafetyController::class, 'createCheck'])->name('food-safety.check.create');
    Route::post('/food-safety/check', [App\Http\Controllers\Admin\FoodSafetyController::class, 'storeCheck'])->name('food-safety.check.store');
    Route::get('/food-safety/check/{foodSafety}/edit', [App\Http\Controllers\Admin\FoodSafetyController::class, 'editCheck'])->name('food-safety.check.edit');
    Route::put('/food-safety/check/{foodSafety}', [App\Http\Controllers\Admin\FoodSafetyController::class, 'updateCheck'])->name('food-safety.check.update');
    Route::delete('/food-safety/check/{foodSafety}', [App\Http\Controllers\Admin\FoodSafetyController::class, 'destroyCheck'])->name('food-safety.check.destroy');

    // === KEGIATAN KEAMANAN PANGAN ===
    Route::get('/food-safety/activity/create', [App\Http\Controllers\Admin\FoodSafetyController::class, 'createActivity'])->name('food-safety.activity.create');
    Route::post('/food-safety/activity', [App\Http\Controllers\Admin\FoodSafetyController::class, 'storeActivity'])->name('food-safety.activity.store');
    Route::get('/food-safety/activity/{activity}/edit', [App\Http\Controllers\Admin\FoodSafetyController::class, 'editActivity'])->name('food-safety.activity.edit');
    Route::put('/food-safety/activity/{activity}', [App\Http\Controllers\Admin\FoodSafetyController::class, 'updateActivity'])->name('food-safety.activity.update');
    Route::delete('/food-safety/activity/{activity}', [App\Http\Controllers\Admin\FoodSafetyController::class, 'destroyActivity'])->name('food-safety.activity.destroy');

    // === UJI RAPID TEST (KEAMANAN PANGAN) ===
    Route::get('/food-safety/rapid-test/create', [App\Http\Controllers\Admin\RapidTestController::class, 'create'])->name('food-safety.rapid.create');
    Route::post('/food-safety/rapid-test', [App\Http\Controllers\Admin\RapidTestController::class, 'store'])->name('food-safety.rapid.store');
    Route::get('/food-safety/rapid-test/{rapidTest}/edit', [App\Http\Controllers\Admin\RapidTestController::class, 'edit'])->name('food-safety.rapid.edit');
    Route::put('/food-safety/rapid-test/{rapidTest}', [App\Http\Controllers\Admin\RapidTestController::class, 'update'])->name('food-safety.rapid.update');
    Route::delete('/food-safety/rapid-test/{rapidTest}', [App\Http\Controllers\Admin\RapidTestController::class, 'destroy'])->name('food-safety.rapid.destroy');

    // === MASTER PARAMETER UJI (Revisi 14 Okt 2026) ===
    Route::get('/master-parameters', [\App\Http\Controllers\Admin\MasterParameterController::class, 'index'])->name('master-parameters.index');
    Route::post('/master-parameters', [\App\Http\Controllers\Admin\MasterParameterController::class, 'store'])->name('master-parameters.store');
    Route::put('/master-parameters/{masterParameter}', [\App\Http\Controllers\Admin\MasterParameterController::class, 'update'])->name('master-parameters.update');
    Route::post('/master-parameters/{masterParameter}/toggle', [\App\Http\Controllers\Admin\MasterParameterController::class, 'toggle'])->name('master-parameters.toggle');
    Route::delete('/master-parameters/{masterParameter}', [\App\Http\Controllers\Admin\MasterParameterController::class, 'destroy'])->name('master-parameters.destroy');

    // === UJI LABORATORIUM (KEAMANAN PANGAN) ===
    Route::get('/food-safety/lab-test/create', [App\Http\Controllers\Admin\LabTestController::class, 'create'])->name('food-safety.lab.create');
    Route::post('/food-safety/lab-test', [App\Http\Controllers\Admin\LabTestController::class, 'store'])->name('food-safety.lab.store');
    Route::get('/food-safety/lab-test/{labTest}/edit', [App\Http\Controllers\Admin\LabTestController::class, 'edit'])->name('food-safety.lab.edit');
    Route::put('/food-safety/lab-test/{labTest}', [App\Http\Controllers\Admin\LabTestController::class, 'update'])->name('food-safety.lab.update');
    Route::delete('/food-safety/lab-test/{labTest}', [App\Http\Controllers\Admin\LabTestController::class, 'destroy'])->name('food-safety.lab.destroy');

    // === PERIZINAN PANGAN (bersifat laporan/daftar — revisi 14 Okt 2026) ===
    Route::get('/permits', [App\Http\Controllers\Admin\PermitController::class, 'index'])->name('permits.index');

    // === FAQ PERIZINAN ===
    Route::resource('faqs', App\Http\Controllers\Admin\FaqController::class);
    Route::get('/permits/create', [App\Http\Controllers\Admin\PermitController::class, 'create'])->name('permits.create');
    Route::post('/permits', [App\Http\Controllers\Admin\PermitController::class, 'store'])->name('permits.store');
    Route::get('/permits/{permit}/edit', [App\Http\Controllers\Admin\PermitController::class, 'edit'])->name('permits.edit');
    Route::put('/permits/{permit}', [App\Http\Controllers\Admin\PermitController::class, 'update'])->name('permits.update');
    Route::post('/permits/{permit}/upload-nib', [App\Http\Controllers\Admin\PermitController::class, 'uploadNib'])->name('permits.uploadNib');
    Route::delete('/permits/{permit}', [App\Http\Controllers\Admin\PermitController::class, 'destroy'])->name('permits.destroy');
    Route::post('/permits/{permit}/approve', [App\Http\Controllers\Admin\PermitController::class, 'approve'])->name('permits.approve');
    Route::post('/permits/{permit}/reject', [App\Http\Controllers\Admin\PermitController::class, 'reject'])->name('permits.reject');

    // UMKM Management
    Route::get('/umkms', [App\Http\Controllers\Admin\UmkmController::class, 'index'])->name('umkms.index');
    Route::get('/umkms/{umkm}', [App\Http\Controllers\Admin\UmkmController::class, 'show'])->name('umkms.show');
    Route::post('/umkms/{umkm}/verify', [App\Http\Controllers\Admin\UmkmController::class, 'verify'])->name('umkms.verify');
    Route::post('/umkms/{umkm}/reject', [App\Http\Controllers\Admin\UmkmController::class, 'reject'])->name('umkms.reject');
    Route::post('/umkms/{umkm}/suspend', [App\Http\Controllers\Admin\UmkmController::class, 'suspend'])->name('umkms.suspend');
    Route::delete('/umkms/{umkm}', [App\Http\Controllers\Admin\UmkmController::class, 'destroy'])->name('umkms.destroy');

    // Categories
    Route::resource('categories', App\Http\Controllers\Admin\CategoryController::class);

    // Orders
    Route::get('/orders', [App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');

    // AI Dashboard
    Route::get('/ai-dashboard', [App\Http\Controllers\Admin\AiDashboardController::class, 'index'])->name('ai-dashboard.index');
    Route::get('/ai-dashboard/inflation', [App\Http\Controllers\Admin\AiDashboardController::class, 'inflation'])->name('ai-dashboard.inflation');
    // Dashboard Pertumbuhan Ekonomi dihapus (revisi 15 Okt 2026)
    // Route::get('/ai-dashboard/economy', [App\Http\Controllers\Admin\AiDashboardController::class, 'economy'])->name('ai-dashboard.economy');
    Route::post('/ai-dashboard/generate', [App\Http\Controllers\Admin\AiDashboardController::class, 'generateInsights'])->name('ai-dashboard.generate');

    // Settings
    Route::get('/settings', [App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
    Route::post('/settings/test-email', [App\Http\Controllers\Admin\SettingController::class, 'testEmail'])->name('settings.test-email');

    // Reports (Revisi 14 Okt 2026: hanya panel harga, ketersediaan & keamanan pangan yang aktif di menu)
    Route::get('/reports', [App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
    Route::post('/reports/availability', [App\Http\Controllers\Admin\ReportController::class, 'availabilityReport'])->name('reports.availability');
    Route::post('/reports/commodity', [App\Http\Controllers\Admin\ReportController::class, 'commodityReport'])->name('reports.commodity');
    Route::post('/reports/food-safety', [App\Http\Controllers\Admin\ReportController::class, 'foodSafetyReport'])->name('reports.food-safety');
    // Laporan berikut disembunyikan dari menu (revisi 14 Okt 2026) — endpoint dipertahankan
    // Route::post('/reports/sales', [App\Http\Controllers\Admin\ReportController::class, 'salesReport'])->name('reports.sales');
    // Route::post('/reports/umkm', [App\Http\Controllers\Admin\ReportController::class, 'umkmReport'])->name('reports.umkm');
    // Route::post('/reports/top-products', [App\Http\Controllers\Admin\ReportController::class, 'topProductsReport'])->name('reports.top-products');

    // Site Pages (Kelola Halaman Profil)
    Route::get('/site-pages', [App\Http\Controllers\Admin\SitePageController::class, 'index'])->name('site-pages.index');
    Route::get('/site-pages/create', [App\Http\Controllers\Admin\SitePageController::class, 'create'])->name('site-pages.create');
    Route::post('/site-pages', [App\Http\Controllers\Admin\SitePageController::class, 'store'])->name('site-pages.store');
    Route::get('/site-pages/{sitePage}/edit', [App\Http\Controllers\Admin\SitePageController::class, 'edit'])->name('site-pages.edit');
    Route::put('/site-pages/{sitePage}', [App\Http\Controllers\Admin\SitePageController::class, 'update'])->name('site-pages.update');

    // Tampilan Beranda (struktur organisasi + galeri kegiatan — revisi 14 Okt 2026)
    Route::get('/homepage', [App\Http\Controllers\Admin\HomepageController::class, 'index'])->name('homepage.index');
    Route::post('/homepage/struktur', [App\Http\Controllers\Admin\HomepageController::class, 'updateStruktur'])->name('homepage.struktur.update');
    Route::delete('/homepage/struktur', [App\Http\Controllers\Admin\HomepageController::class, 'destroyStruktur'])->name('homepage.struktur.destroy');
    Route::post('/homepage/gallery', [App\Http\Controllers\Admin\HomepageController::class, 'storeGallery'])->name('homepage.gallery.store');
    Route::post('/homepage/gallery/{gallery}', [App\Http\Controllers\Admin\HomepageController::class, 'toggleGallery'])->name('homepage.gallery.toggle');
    Route::delete('/homepage/gallery/{gallery}', [App\Http\Controllers\Admin\HomepageController::class, 'destroyGallery'])->name('homepage.gallery.destroy');
    Route::delete('/site-pages/{sitePage}', [App\Http\Controllers\Admin\SitePageController::class, 'destroy'])->name('site-pages.destroy');

    // ==================== SUPER ADMIN ONLY ====================
    Route::middleware('super_admin')->group(function () {
        // User Management
        Route::resource('users', App\Http\Controllers\Admin\UserController::class);

        // Activity Logs
        Route::get('/activity-logs', [App\Http\Controllers\Admin\ActivityLogController::class, 'index'])->name('activity-logs.index');
        Route::delete('/activity-logs/{activityLog}', [App\Http\Controllers\Admin\ActivityLogController::class, 'destroy'])->name('activity-logs.destroy');
        Route::post('/activity-logs/clear', [App\Http\Controllers\Admin\ActivityLogController::class, 'clearAll'])->name('activity-logs.clear');

        // Backup & Restore
        Route::get('/backup', [App\Http\Controllers\Admin\BackupController::class, 'index'])->name('backup.index');
        Route::post('/backup/create', [App\Http\Controllers\Admin\BackupController::class, 'backup'])->name('backup.create');
        Route::get('/backup/download/{filename}', [App\Http\Controllers\Admin\BackupController::class, 'download'])->name('backup.download');
        Route::post('/backup/restore', [App\Http\Controllers\Admin\BackupController::class, 'restore'])->name('backup.restore');
        Route::delete('/backup/{filename}', [App\Http\Controllers\Admin\BackupController::class, 'destroy'])->name('backup.destroy');
    });
});

// ==================== UMKM PANEL ====================
Route::middleware(['auth','umkm'])->prefix('umkm-panel')->name('umkm.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\UmkmPanel\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('products', App\Http\Controllers\UmkmPanel\ProductController::class);
    Route::get('/orders', [App\Http\Controllers\UmkmPanel\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [App\Http\Controllers\UmkmPanel\OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/process', [App\Http\Controllers\UmkmPanel\OrderController::class, 'process'])->name('orders.process');
    Route::post('/orders/{order}/ship', [App\Http\Controllers\UmkmPanel\OrderController::class, 'ship'])->name('orders.ship');
});

// ==================== AUTH ROUTES (Breeze) ====================
require __DIR__.'/auth.php';
