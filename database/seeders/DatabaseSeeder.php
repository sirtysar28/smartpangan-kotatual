<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Umkm;
use App\Models\Product;
use App\Models\Commodity;
use App\Models\CommodityPrice;
use App\Models\AiInsight;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Users — firstOrCreate agar aman di-run berulang
        $admin = User::firstOrCreate(
            ['email' => 'admin@tualmart.id'],
            ['name' => 'Admin Pangan Cerdas (Panca) Maryadat Kota Tual', 'password' => Hash::make('admin123'), 'role' => 'super_admin', 'phone' => '081234567890', 'status' => 'active'],
        );
        User::firstOrCreate(
            ['email' => 'diskop@tual.go.id'],
            ['name' => 'Dinas Koperasi', 'password' => Hash::make('admin123'), 'role' => 'opd', 'phone' => '081234567891', 'status' => 'active'],
        );
        User::firstOrCreate(
            ['email' => 'bulog@tual.go.id'],
            ['name' => 'Perum BULOG Kota Tual', 'password' => Hash::make('bulog123'), 'role' => 'opd', 'phone' => '0910123456', 'address' => 'Kantor BULOG Kota Tual, Maluku Tenggara', 'status' => 'active'],
        );

        // UMKM Users
        $umkmUsers = [];
        $umkmData = [
            ['name'=>'UMKM Ikan Asap Tual','email'=>'ikanasap@gmail.com','phone'=>'081111111111'],
            ['name'=>'UMKM Rumput Laut Maluku','email'=>'rumputlaut@gmail.com','phone'=>'081222222222'],
            ['name'=>'UMKM Kerajinan Laut','email'=>'kerajinan@gmail.com','phone'=>'081333333333'],
            ['name'=>'UMKM Kue Kering Tual','email'=>'kuekering@gmail.com','phone'=>'081444444444'],
            ['name'=>'UMKM Abon Ikan Jaya','email'=>'abonikan@gmail.com','phone'=>'081555555555'],
        ];
        foreach ($umkmData as $d) {
            $umkmUsers[] = User::firstOrCreate(
                ['email' => $d['email']],
                ['name' => $d['name'], 'password' => Hash::make('umkm123'), 'role' => 'umkm', 'phone' => $d['phone'], 'status' => 'active'],
            );
        }

        // Demo Buyer
        User::firstOrCreate(
            ['email' => 'buyer@gmail.com'],
            ['name' => 'Pembeli Demo', 'password' => Hash::make('buyer123'), 'role' => 'buyer', 'phone' => '081666666666', 'status' => 'active'],
        );

        // Categories — firstOrCreate
        $cats = ['Perikanan','Kerajinan','Kuliner','Fashion','Pertanian','Olahan','Minuman','Bumbu & Rempah'];
        foreach ($cats as $c) {
            Category::firstOrCreate(['name'=>$c], ['slug'=>\Str::slug($c), 'is_active'=>true]);
        }

        // UMKM Records — firstOrCreate per business_name
        $umkms = [];
        $umkmNames = ['Ikan Asap Tual','Rumput Laut Maluku','Kerajinan Laut Nusantara','Kue Kering Tual','Abon Ikan Jaya'];
        foreach ($umkmUsers as $i => $user) {
            $umkms[] = Umkm::firstOrCreate(
                ['user_id' => $user->id],
                ['business_name' => $umkmNames[$i], 'slug' => \Str::slug($umkmNames[$i]),
                'description' => 'UMKM lokal Kota Tual yang menghasilkan produk berkualitas.',
                'address' => 'Jl. Merdeka No. '.($i+1).', Kota Tual', 'phone' => $user->phone,
                'status' => 'verified', 'verified_at' => now(), 'rating' => rand(40,50)/10],
            );
        }

        // Products — firstOrCreate per name, update image jika belum ada
        $products = [
            ['Ikan Asap Premium','Perikanan',75000,100,'250g ikan asap kualitas terbaik',200,'https://images.unsplash.com/photo-1510130387422-82bed34b37e9?w=400&h=300&fit=crop'],
            ['Rumput Laut Kering','Perikanan',35000,150,'Rumput laut kering organik',100,'https://images.unsplash.com/photo-1615485290382-441e4d049cb5?w=400&h=300&fit=crop'],
            ['Kerajinan Gantungan Kunci','Kerajinan',25000,200,'Gantungan kunci dari kerang laut',50,'https://images.unsplash.com/photo-1523293182086-7651a899d37f?w=400&h=300&fit=crop'],
            ['Kerupuk Ikan Tual','Kuliner',30000,80,'Kerupuk ikan renyah khas Tual',150,'https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?w=400&h=300&fit=crop'],
            ['Kue Kering Nastar','Kuliner',65000,50,'Kue kering nastar premium',250,'https://images.unsplash.com/photo-1558961363-fa8fdf82db35?w=400&h=300&fit=crop'],
            ['Abon Ikan','Olahan',45000,120,'Abon ikan lezat tanpa pengawet',180,'https://images.unsplash.com/photo-1534422298391-e4f8c172dddb?w=400&h=300&fit=crop'],
            ['Sirup Markisa','Minuman',28000,90,'Sirup markisa asli Maluku Tenggara',300,'https://images.unsplash.com/photo-1544145945-f90425340c7e?w=400&h=300&fit=crop'],
            ['Sambal Roe','Bumbu & Rempah',40000,70,'Sambal roe khas Maluku',120,'https://images.unsplash.com/photo-1563379926898-05f4575a45d8?w=400&h=300&fit=crop'],
            ['Tas Anyaman Pandan','Kerajinan',85000,30,'Tas anyaman pandan handmade',200,'https://images.unsplash.com/photo-1590874103328-eac38a683ce7?w=400&h=300&fit=crop'],
            ['Madu Hutan Maluku','Pertanian',120000,40,'Madu murni dari hutan Maluku',250,'https://images.unsplash.com/photo-1587049352846-4a222e784d38?w=400&h=300&fit=crop'],
            ['Gantungan Kerang','Kerajinan',15000,300,'Gantungan kerang hiasan dinding',30,'https://images.unsplash.com/photo-1545580492-8859ba8323f0?w=400&h=300&fit=crop'],
            ['Dodol Rumput Laut','Olahan',20000,100,'Dodol rumput laut rasa original',80,'https://images.unsplash.com/photo-1559329007-40df8a9345d8?w=400&h=300&fit=crop'],
        ];
        foreach ($products as $i => $p) {
            $category = Category::where('name',$p[1])->first();
            Product::firstOrCreate(
                ['name' => $p[0]],
                ['umkm_id' => $umkms[$i % 5]->id, 'category_id' => $category->id,
                'slug' => \Str::slug($p[0]).'-'.\Str::random(4),
                'description' => $p[4], 'price' => $p[2], 'stock' => $p[3],
                'weight' => $p[5], 'unit' => 'pcs', 'image' => $p[6],
                'is_active' => true, 'is_featured' => $i < 6,
                'total_sold' => rand(10,250), 'total_views' => rand(100,2000),
                'rating' => rand(35,50)/10],
            );
        }

        // Commodities — firstOrCreate per name, update image jika belum ada
        $commData = [
            ['Beras','kg','pangan',14000,'https://images.unsplash.com/photo-1586201375761-83865001e31c?w=300&h=200&fit=crop'],
            ['Gula Pasir','kg','pangan',16000,'https://images.unsplash.com/photo-1558642452-9d2a7deb7f62?w=300&h=200&fit=crop'],
            ['Minyak Goreng','liter','pangan',18000,'https://images.unsplash.com/photo-1474979266404-7eaacbcd87c5?w=300&h=200&fit=crop'],
            ['Cabai Rawit','kg','pangan',45000,'https://images.unsplash.com/photo-1583119022894-919a68a3d0e3?w=300&h=200&fit=crop'],
            ['Bawang Merah','kg','pangan',35000,'https://images.unsplash.com/photo-1618512496248-a07fe83aa8cb?w=300&h=200&fit=crop'],
            ['Bawang Putih','kg','pangan',32000,'https://images.unsplash.com/photo-1618512496248-a07fe83aa8cb?w=300&h=200&fit=crop'],
            ['Telur Ayam','kg','pangan',28000,'https://images.unsplash.com/photo-1582722872445-44dc5f7e3c8f?w=300&h=200&fit=crop'],
            ['Daging Ayam','kg','pangan',36000,'https://images.unsplash.com/photo-1604503468506-a8da13d82791?w=300&h=200&fit=crop'],
            ['Ikan Segar','kg','pangan',42000,'https://images.unsplash.com/photo-1510130387422-82bed34b37e9?w=300&h=200&fit=crop'],
            ['Jagung','kg','pangan',12000,'https://images.unsplash.com/photo-1551754655-cd27e38d2076?w=300&h=200&fit=crop'],
            ['Kentang','kg','pangan',15000,'https://images.unsplash.com/photo-1574323347407-f5e1ad6d020b?w=300&h=200&fit=crop'],
            ['Tepung Terigu','kg','pangan',11000,'https://images.unsplash.com/photo-1574323347407-f5e1ad6d020b?w=300&h=200&fit=crop'],
            ['Kopi Bubuk','kg','pangan',85000,'https://images.unsplash.com/photo-1559056199-641a0ac8b55e?w=300&h=200&fit=crop'],
            ['Sagu','kg','pangan',8000,'https://images.unsplash.com/photo-1551754655-cd27e38d2076?w=300&h=200&fit=crop'],
            ['Pala','kg','pangan',95000,'https://images.unsplash.com/photo-1532336414038-cf19250c5757?w=300&h=200&fit=crop'],
            ['Cengkeh','kg','pangan',120000,'https://images.unsplash.com/photo-1596040033229-a9821ebd058d?w=300&h=200&fit=crop'],
        ];
        foreach ($commData as $c) {
            $comm = Commodity::firstOrCreate(
                ['name' => $c[0]],
                ['unit'=>$c[1],'category'=>$c[2],'image'=>$c[4],'is_active'=>true],
            );
            CommodityPrice::firstOrCreate(
                ['commodity_id' => $comm->id, 'price_date' => today()],
                ['price'=>$c[3],'price_previous'=>$c[3],'change_percent'=>0,'source'=>'admin'],
            );
        }

        // AI Insights — firstOrCreate per title
        $insights = [
            ['trending_product','Produk Terlaris #1','Ikan Asap Premium terjual 250 unit bulan ini.',['product'=>'Ikan Asap Premium','sold'=>250],92.5,'info',7],
            ['viral_prediction','Prediksi Viral: Abon Ikan Tual','Trend naik +250%. Potensi penjualan meningkat 30 hari ke depan.',['keyword'=>'Abon Ikan Tual','trend'=>250],78.0,'info',14],
            ['stock_alert','Stok Menipis!','Kerupuk Ikan Tual tersisa 15 pcs. Prediksi habis: 3 hari.',['product'=>'Kerupuk Ikan Tual','stock'=>15],85.0,'critical',3],
            ['inflation_prediction','Prediksi Inflasi Pangan','Potensi inflasi pangan bulan depan sebesar 0,45%.',['prediction'=>0.45],72.0,'warning',30],
        ];
        foreach ($insights as $ins) {
            AiInsight::firstOrCreate(
                ['title' => $ins[1]],
                ['type'=>$ins[0],'description'=>$ins[2],'data'=>$ins[3],'confidence_score'=>$ins[4],'severity'=>$ins[5],'expires_at'=>now()->addDays($ins[6])],
            );
        }

        // Update produk lama yang belum punya image (jika ada)
        Product::whereNull('image')->orWhere('image','')->get()->each(function ($product) {
            $urlMap = [
                'Ikan Asap Premium' => 'https://images.unsplash.com/photo-1510130387422-82bed34b37e9?w=400&h=300&fit=crop',
                'Rumput Laut Kering' => 'https://images.unsplash.com/photo-1615485290382-441e4d049cb5?w=400&h=300&fit=crop',
                'Kerajinan Gantungan Kunci' => 'https://images.unsplash.com/photo-1523293182086-7651a899d37f?w=400&h=300&fit=crop',
                'Kerupuk Ikan Tual' => 'https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?w=400&h=300&fit=crop',
                'Kue Kering Nastar' => 'https://images.unsplash.com/photo-1558961363-fa8fdf82db35?w=400&h=300&fit=crop',
                'Abon Ikan' => 'https://images.unsplash.com/photo-1534422298391-e4f8c172dddb?w=400&h=300&fit=crop',
                'Sirup Markisa' => 'https://images.unsplash.com/photo-1544145945-f90425340c7e?w=400&h=300&fit=crop',
                'Sambal Roe' => 'https://images.unsplash.com/photo-1563379926898-05f4575a45d8?w=400&h=300&fit=crop',
                'Tas Anyaman Pandan' => 'https://images.unsplash.com/photo-1590874103328-eac38a683ce7?w=400&h=300&fit=crop',
                'Madu Hutan Maluku' => 'https://images.unsplash.com/photo-1587049352846-4a222e784d38?w=400&h=300&fit=crop',
                'Gantungan Kerang' => 'https://images.unsplash.com/photo-1545580492-8859ba8323f0?w=400&h=300&fit=crop',
                'Dodol Rumput Laut' => 'https://images.unsplash.com/photo-1559329007-40df8a9345d8?w=400&h=300&fit=crop',
            ];
            if (isset($urlMap[$product->name])) {
                $product->update(['image' => $urlMap[$product->name]]);
            }
        });

        // Update komoditas lama yang belum punya image (jika ada)
        Commodity::whereNull('image')->orWhere('image','')->get()->each(function ($comm) {
            $urlMap = [
                'Beras' => 'https://images.unsplash.com/photo-1586201375761-83865001e31c?w=300&h=200&fit=crop',
                'Gula Pasir' => 'https://images.unsplash.com/photo-1558642452-9d2a7deb7f62?w=300&h=200&fit=crop',
                'Minyak Goreng' => 'https://images.unsplash.com/photo-1474979266404-7eaacbcd87c5?w=300&h=200&fit=crop',
                'Cabai Rawit' => 'https://images.unsplash.com/photo-1583119022894-919a68a3d0e3?w=300&h=200&fit=crop',
                'Bawang Merah' => 'https://images.unsplash.com/photo-1618512496248-a07fe83aa8cb?w=300&h=200&fit=crop',
                'Bawang Putih' => 'https://images.unsplash.com/photo-1618512496248-a07fe83aa8cb?w=300&h=200&fit=crop',
                'Telur Ayam' => 'https://images.unsplash.com/photo-1582722872445-44dc5f7e3c8f?w=300&h=200&fit=crop',
                'Daging Ayam' => 'https://images.unsplash.com/photo-1604503468506-a8da13d82791?w=300&h=200&fit=crop',
                'Ikan Segar' => 'https://images.unsplash.com/photo-1510130387422-82bed34b37e9?w=300&h=200&fit=crop',
                'Jagung' => 'https://images.unsplash.com/photo-1551754655-cd27e38d2076?w=300&h=200&fit=crop',
                'Kentang' => 'https://images.unsplash.com/photo-1574323347407-f5e1ad6d020b?w=300&h=200&fit=crop',
                'Tepung Terigu' => 'https://images.unsplash.com/photo-1574323347407-f5e1ad6d020b?w=300&h=200&fit=crop',
                'Kopi Bubuk' => 'https://images.unsplash.com/photo-1559056199-641a0ac8b55e?w=300&h=200&fit=crop',
                'Sagu' => 'https://images.unsplash.com/photo-1551754655-cd27e38d2076?w=300&h=200&fit=crop',
                'Pala' => 'https://images.unsplash.com/photo-1532336414038-cf19250c5757?w=300&h=200&fit=crop',
                'Cengkeh' => 'https://images.unsplash.com/photo-1596040033229-a9821ebd058d?w=300&h=200&fit=crop',
            ];
            if (isset($urlMap[$comm->name])) {
                $comm->update(['image' => $urlMap[$comm->name]]);
            }
        });

        $this->command->info('✅ Seeding complete! Data siap.');
    }
}
