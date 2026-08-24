# Smart Pangan Kota Tual - Marketplace Digital UMKM Kota Tual

<div align="center">
    <h3>🛒 Marketplace Digital UMKM & Produk Lokal</h3>
    <p>Kota Tual, Provinsi Maluku</p>
    <p><strong>Berbasis AI untuk Smart Market Analysis</strong></p>
</div>

---

## 🚀 Cara Menjalankan

### Prasyarat
- PHP >= 8.2
- MySQL/MariaDB
- Composer
- Node.js & NPM

### Instalasi

```bash
# 1. Clone/download project
cd tualmart-ai

# 2. Install PHP dependencies
composer install

# 3. Install Node dependencies
npm install

# 4. Copy .env
cp .env.example .env
php artisan key:generate

# 5. Setup database MySQL
# Buat database: tualmart_ai
# Update .env: DB_DATABASE=tualmart_ai

# 6. Migrate & Seed
php artisan migrate:fresh --seed

# 7. Storage link
php artisan storage:link

# 8. Jalankan aplikasi
php artisan serve
```

Buka browser: **http://localhost:8000**

---

## 👤 Akun Demo

| Role | Email | Password |
|------|-------|----------|
| **Super Admin** | admin@tualmart.id | admin123 |
| **OPD** | diskop@tual.go.id | admin123 |
| **UMKM** | ikanasap@gmail.com | umkm123 |
| **UMKM** | rumputlaut@gmail.com | umkm123 |
| **UMKM** | kerajinan@gmail.com | umkm123 |
| **Buyer** | buyer@gmail.com | buyer123 |

---

## 📋 Fitur Utama

### 🏪 Frontend (Marketplace)
- ✅ Homepage dengan hero section, kategori, produk unggulan & terlaris
- ✅ Katalog produk dengan filter, search, dan sorting
- ✅ Detail produk dengan gambar, deskripsi, review, produk serupa
- ✅ Keranjang belanja (session-based)
- ✅ Checkout dengan pilihan kurir
- ✅ Pembayaran via Midtrans (QRIS, GoPay, VA, CC)
- ✅ Tracking pesanan (status flow)
- ✅ Review & rating produk
- ✅ Pendaftaran UMKM

### 🔧 Admin Panel (Pemerintah Daerah)
- ✅ Dashboard statistik (UMKM, produk, pendapatan, pesanan)
- ✅ Chart pendapatan bulanan (Chart.js)
- ✅ Chart status pesanan (doughnut)
- ✅ Manajemen UMKM (verifikasi, tolak, suspend)
- ✅ Manajemen kategori produk
- ✅ Monitoring pesanan
- ✅ Harga komoditas & bahan pangan
- ✅ Uji Rapid Test (boraks, formalin, rhodamin B, dll) — input & tampil di frontend
- ✅ Uji Laboratorium (mikotoksin, logam berat, mikrobiologi, residu pestisida) dengan nilai, satuan, standar, LOD/LOQ, status MS/TMS, sertifikat CoA PDF
- ✅ AI Dashboard (trending, stock alert, prediksi inflasi)
- ✅ Laporan PDF (penjualan, UMKM, komoditas, produk terlaris)

### 🏠 UMKM Panel (Pelaku UMKM)
- ✅ Dashboard (pendapatan, pesanan, stok menipis)
- ✅ CRUD produk dengan upload gambar
- ✅ Manajemen pesanan masuk
- ✅ Input nomor resi pengiriman
- ✅ Notifikasi pesanan baru

### 🤖 Fitur AI
- ✅ Auto-detect produk trending
- ✅ Stock alert dengan prediksi hari habis
- ✅ Prediksi inflasi pangan
- ✅ Analisis ekonomi UMKM
- ✅ Confidence score untuk setiap insight

---

## 🛠️ Tech Stack

| Teknologi | Keterangan |
|-----------|------------|
| **Laravel 11** | PHP Framework |
| **MySQL** | Database |
| **Bootstrap 5.3** | UI Framework |
| **Tailwind CSS** | Utility CSS |
| **Chart.js** | Grafik & Chart |
| **Midtrans** | Payment Gateway |
| **Alpine.js** | JavaScript Interactivity |
| **DomPDF** | Generate Laporan PDF |
| **Blade** | Template Engine |

---

## 📁 Struktur Project

```
tualmart-ai/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/          # Admin Panel Controllers
│   │   ├── Api/            # Webhook Controllers
│   │   ├── Auth/           # Authentication Controllers
│   │   ├── Frontend/       # Public Marketplace Controllers
│   │   ├── Payment/        # Payment Controllers
│   │   └── UmkmPanel/      # UMKM Dashboard Controllers
│   ├── Http/Middleware/     # Admin & UMKM Middleware
│   ├── Models/             # Eloquent Models (12 models)
│   └── Services/           # MidtransService
├── database/
│   ├── migrations/         # 15 migrations
│   └── seeders/           # Demo data seeder
├── resources/
│   └── views/
│       ├── admin/          # Admin Panel Views
│       ├── auth/           # Login/Register Views
│       ├── frontend/       # Marketplace Views
│       ├── layouts/        # Master Layouts
│       └── umkm/           # UMKM Panel Views
└── routes/
    ├── web.php             # Main routes (82 routes)
    └── auth.php            # Auth routes
```

---

## 🔄 Flow Aplikasi

### Alur Pembeli
1. **Browse** produk di marketplace → Filter & search
2. **Lihat detail** produk → Info lengkap + UMKM
3. **Tambah ke keranjang** → Atur jumlah
4. **Checkout** → Isi alamat, pilih kurir
5. **Bayar** via Midtrans → QRIS/GoPay/VA/CC
6. **Tracking** pesanan → Sampai → Review

### Alur UMKM
1. **Daftar UMKM** → Upload KTP & foto usaha
2. **Verifikasi** oleh Admin Pemda
3. **Kelola produk** → Tambah/edit/hapus
4. **Terima pesanan** → Proses → Kirim (input resi)
5. **Monitor** pendapatan & stok

### Alur Admin (Pemda)
1. **Dashboard** → Statistik overview
2. **Verifikasi UMKM** → Approve/reject
3. **Monitor** harga komoditas pangan
4. **Keamanan Pangan** → Input pemeriksaan, uji rapid test, uji laboratorium (hasil tampil di frontend menu Keamanan Pangan)
5. **AI Dashboard** → Insights & prediksi
6. **Generate laporan** → PDF export

---

## 📄 Lisensi

Hak Cipta © 2024 CV Cipta Karya Digital Indonesia
Untuk Pemerintah Daerah Kota Tual, Provinsi Maluku

---

<div align="center">
    <p>Dibuat dengan ❤️ untuk UMKM Kota Tual</p>
</div>
# smartpangan-kotatual
