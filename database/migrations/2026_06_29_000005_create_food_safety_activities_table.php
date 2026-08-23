<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('food_safety_activities', function (Blueprint $table) {
            $table->id();
            $table->date('activity_date');
            $table->string('title')->comment('Judul kegiatan');
            $table->enum('activity_type', [
                'pengawasan', 'sosialisasi', 'pelatihan', 'pengambilan_sampel',
                'penyuluhan', 'sidak', 'rapat_koordinasi', 'lainnya'
            ])->default('pengawasan');
            $table->text('description')->nullable();
            $table->string('location')->nullable()->comment('Lokasi kegiatan');
            $table->integer('participants_count')->default(0)->comment('Jumlah peserta');
            $table->string('evidence_photo')->nullable()->comment('Foto bukti kegiatan (upload)');
            $table->string('evidence_photo_2')->nullable();
            $table->string('evidence_photo_3')->nullable();
            $table->string('document_file')->nullable()->comment('File dokumen pendukung');
            $table->text('outcome')->nullable()->comment('Hasil/tindak lanjut');
            $table->enum('status', ['draft', 'published'])->default('published');

            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('food_safety_activities');
    }
};
