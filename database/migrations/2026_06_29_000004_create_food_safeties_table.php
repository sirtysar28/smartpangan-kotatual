<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('food_safeties', function (Blueprint $table) {
            $table->id();
            $table->date('check_date');

            // Jenis pangan
            $table->enum('food_source_type', ['tumbuhan', 'ikan', 'hewan'])->comment('Asal pangan');
            $table->string('food_name')->comment('Nama pangan');
            $table->foreignId('commodity_id')->nullable()->constrained()->nullOnDelete();

            // Distributor & pengecer
            $table->string('distributor_name')->nullable();
            $table->string('retailer_name')->nullable();
            $table->string('retailer_address')->nullable();

            // Kondisi pangan
            $table->enum('food_condition', ['segar', 'segar_olahan_awal'])->default('segar')
                ->comment('Segar / Segar olahan awal (tempe, tahu, dll)');

            // Kemasan
            $table->string('packaging_type')->nullable()->comment('Jenis kemasan');
            $table->string('packaging_netto')->nullable()->comment('Netto (gram/ml)');
            $table->date('expiry_date')->nullable()->comment('Tanggal kadaluarsa');
            $table->string('bpom_number')->nullable()->comment('Nomor BP POM / MD');
            $table->boolean('is_halal')->default(false)->comment('Sertifikasi halal');
            $table->string('halal_number')->nullable()->comment('Nomor sertifikasi halal');
            $table->string('pduk_number')->nullable()->comment('Nomor PDUK (Produksi Dalam Uji Kelayakan)');
            $table->boolean('is_pduk')->default(false);

            // Hasil pemeriksaan
            $table->enum('inspection_result', ['layak', 'tidak_layak', 'perlu_tindak_lanjut'])->default('layak');
            $table->text('recommendation')->nullable()->comment('Rekomendasi (difoto dan upload)');
            $table->string('recommendation_photo')->nullable()->comment('Foto rekomendasi');

            // Umpulan
            $table->enum('inspection_type', ['rutin', 'insidentil', 'follow_up'])->default('rutin');
            $table->string('inspector_name')->nullable();
            $table->text('notes')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('food_safeties');
    }
};
