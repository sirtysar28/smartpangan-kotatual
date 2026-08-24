<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lab_tests', function (Blueprint $table) {
            $table->id();
            $table->date('test_date')->comment('Tanggal pengujian');
            $table->string('sample_location')->comment('Lokasi sampel');
            $table->string('lab_name')->comment('Lab uji');
            $table->string('commodity_name')->comment('Nama komoditas');
            $table->string('psat_type')->comment('Jenis PSAT');
            $table->string('method')->nullable()->comment('Metode uji (umum)');
            $table->string('certificate_file')->nullable()->comment('File sertifikat CoA (PDF)');
            $table->text('notes')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();

            $table->timestamps();
        });

        Schema::create('lab_test_parameters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lab_test_id')->constrained('lab_tests')->cascadeOnDelete();
            $table->enum('test_category', ['mikotoksin', 'logam_berat', 'mikrobiologi', 'residu_pestisida'])->comment('Kelompok uji');
            $table->string('parameter');
            $table->enum('result', ['terdeteksi', 'tidak_terdeteksi'])->default('tidak_terdeteksi')->comment('Hasil uji');
            $table->string('value')->nullable()->comment('Nilai (diisi via popup)');
            $table->string('unit')->nullable()->comment('Satuan');
            $table->string('standard')->nullable()->comment('Baku mutu / standar');
            $table->string('lod')->nullable()->comment('LOD (Limit of Detection)');
            $table->string('loq')->nullable()->comment('LOQ (Limit of Quantification)');
            $table->enum('status', ['MS', 'TMS'])->nullable()->comment('MS = Memenuhi Syarat, TMS = Tidak Memenuhi Syarat');
            $table->string('method')->nullable()->comment('Metode uji per parameter');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lab_test_parameters');
        Schema::dropIfExists('lab_tests');
    }
};
