<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Revisi 14 Oktober 2026:
     * 1. Parameter uji lab bersifat opsional (bisa kosong, mis. uji boraks tanpa parameter)
     *    → kolom test_category dibuat nullable.
     * 2. Master data parameter uji (terhubung form uji lab, ±8 parameter utama).
     * 3. Galeri kegiatan + struktur organisasi untuk tampilan beranda.
     * 4. Setting sinkronisasi data eksternal (FPI/Bappenas) via scheduler.
     */
    public function up(): void
    {
        // 1. Parameter uji opsional (test_category bisa kosong)
        Schema::table('lab_test_parameters', function (Blueprint $table) {
            $table->string('test_category')->nullable()->change();
        });

        // 2. Master parameter uji
        if (!Schema::hasTable('test_parameter_masters')) {
            Schema::create('test_parameter_masters', function (Blueprint $table) {
                $table->id();
                $table->string('name')->comment('Nama parameter, cth: Residu Pestisida');
                $table->string('category')->nullable()->comment('Kelompok uji: mikotoksin/logam_berat/mikrobiologi/residu_pestisida/lainnya');
                $table->string('unit')->nullable()->comment('Satuan, cth: ppm');
                $table->string('standard')->nullable()->comment('Baku mutu / standar');
                $table->string('method')->nullable()->comment('Metode uji');
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 3. Galeri kegiatan beranda (foto kegiatan / foto pejabat / dokumentasi)
        if (!Schema::hasTable('homepage_galleries')) {
            Schema::create('homepage_galleries', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('image')->comment('Path foto (storage)');
                $table->text('description')->nullable();
                $table->date('activity_date')->nullable()->comment('Tanggal kegiatan');
                $table->integer('sort_order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 4. Setting beranda & sinkronisasi data eksternal
        $settings = [
            ['key' => 'homepage_struktur_organisasi', 'value' => null, 'group' => 'beranda', 'label' => 'Struktur Organisasi (Gambar)', 'type' => 'image'],
            ['key' => 'sync_external_enabled', 'value' => '0', 'group' => 'sinkronisasi', 'label' => 'Aktifkan Sinkronisasi Data Eksternal (FPI/Bappenas)', 'type' => 'boolean'],
            ['key' => 'sync_external_url', 'value' => null, 'group' => 'sinkronisasi', 'label' => 'URL API Sumber Data (FPI/Bappenas)', 'type' => 'text'],
            ['key' => 'sync_external_interval', 'value' => '60', 'group' => 'sinkronisasi', 'label' => 'Interval Sinkronisasi (menit)', 'type' => 'number'],
        ];
        foreach ($settings as $s) {
            DB::table('settings')->updateOrInsert(
                ['key' => $s['key']],
                array_merge($s, ['created_at' => now(), 'updated_at' => now()])
            );
        }
    }

    public function down(): void
    {
        Schema::table('lab_test_parameters', function (Blueprint $table) {
            $table->enum('test_category', ['mikotoksin', 'logam_berat', 'mikrobiologi', 'residu_pestisida'])->nullable()->change();
        });
        Schema::dropIfExists('test_parameter_masters');
        Schema::dropIfExists('homepage_galleries');
        DB::table('settings')->whereIn('key', [
            'homepage_struktur_organisasi', 'sync_external_enabled', 'sync_external_url', 'sync_external_interval',
        ])->delete();
    }
};
