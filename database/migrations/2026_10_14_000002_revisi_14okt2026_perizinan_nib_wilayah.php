<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Revisi 14 Oktober 2026 — Laporan Perizinan & Dashboard Admin (Perizinan Pangan):
     * 1. Kolom NIB (Nomor Izin Berusaha) pada data perizinan.
     * 2. Kolom wilayah (Kecamatan & Desa/Kelurahan) untuk filter, grafik per kecamatan,
     *    dan dropdown alamat usaha di Dashboard Admin.
     * 3. Kolom dokumen NIB (upload dokumen NIB dari Dashboard Admin).
     * 4. Backfill NIB dari data UMKM terkait (jika tersedia).
     */
    public function up(): void
    {
        Schema::table('permits', function (Blueprint $table) {
            $table->string('nib')->nullable()->after('permit_number')->comment('Nomor Izin Berusaha (NIB)');
            $table->string('kecamatan')->nullable()->after('business_address');
            $table->string('desa')->nullable()->after('kecamatan');
            $table->string('nib_document')->nullable()->after('document_file')->comment('Dokumen NIB (file)');
        });

        // Backfill: ambil NIB dari data UMKM terkait bila perizinan belum memiliki NIB
        DB::statement(
            "UPDATE permits p
             LEFT JOIN umkms u ON u.id = p.umkm_id
             SET p.nib = u.nib
             WHERE (p.nib IS NULL OR p.nib = '')
               AND u.nib IS NOT NULL AND u.nib <> ''"
        );
    }

    public function down(): void
    {
        Schema::table('permits', function (Blueprint $table) {
            $table->dropColumn(['nib', 'kecamatan', 'desa', 'nib_document']);
        });
    }
};
