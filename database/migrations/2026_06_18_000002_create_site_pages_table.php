<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_pages', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->text('content')->nullable();
            $table->string('icon')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Seed default pages
        DB::table('site_pages')->insert([
            [
                'slug' => 'profil-informasi-umum',
                'title' => 'Informasi Umum Kota Tual',
                'icon' => 'bi-building',
                'content' => 'Kota Tual adalah sebuah kota yang terletak di Provinsi Maluku, Indonesia. Kota ini memiliki kekayaan sumber daya pangan laut dan pertanian yang melimpah. Dinas Ketahanan Pangan Kota Tual berperan strategis dalam memastikan ketersediaan, keamanan, dan kerawanan pangan bagi masyarakat. Melalui aplikasi Pangan Cerdas (Panca) Maryadat ini, pemerintah daerah berkomitmen untuk menyediakan data harga pangan yang akurat, realtime, dan mudah diakses oleh seluruh pemangku kepentingan.',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'profil-dinas-ketahanan-pangan',
                'title' => 'Dinas Ketahanan Pangan Kota Tual',
                'icon' => 'bi-people-fill',
                'content' => 'Dinas Ketahanan Pangan Kota Tual bertugas melaksanakan urusan pemerintahan di bidang ketahanan pangan, yang meliputi: ketersediaan pangan, akses terhadap pangan, pengendalian inflasi pangan, diversifikasi pangan, keamanan pangan, dan pemberdayaan masyarakat di sektor pangan lokal. Dengan dukungan teknologi informasi, dinas ini memberikan pelayanan yang lebih transparan dan akuntabel.',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('site_pages');
    }
};
