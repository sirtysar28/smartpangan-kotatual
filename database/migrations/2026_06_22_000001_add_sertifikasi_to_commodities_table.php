<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('commodities', function (Blueprint $table) {
            $table->string('sertifikasi_type')->nullable()->after('description')->comment('Jenis sertifikasi, misal: Organik, BPOM, Halal, dll');
            $table->string('sertifikasi_issuer')->nullable()->after('sertifikasi_type')->comment('Lembaga penerbit, misal: LSO, BPOM, MUI, dll');
            $table->string('sertifikasi_number')->nullable()->after('sertifikasi_issuer')->comment('Nomor sertifikat');
            $table->date('sertifikasi_date')->nullable()->after('sertifikasi_number')->comment('Tanggal terbit sertifikat');
            $table->text('sertifikasi_note')->nullable()->after('sertifikasi_date')->comment('Keterangan tambahan sertifikasi');
        });
    }

    public function down(): void
    {
        Schema::table('commodities', function (Blueprint $table) {
            $table->dropColumn(['sertifikasi_type', 'sertifikasi_issuer', 'sertifikasi_number', 'sertifikasi_date', 'sertifikasi_note']);
        });
    }
};
