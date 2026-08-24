<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rapid_tests', function (Blueprint $table) {
            $table->id();
            $table->date('test_date')->comment('Tanggal hasil pengujian');
            $table->string('sample_location')->comment('Lokasi sampel');
            $table->string('commodity_name')->comment('Komoditas');
            $table->string('commodity_type')->nullable()->comment('Jenis komoditas');
            $table->string('rapid_test_type')->comment('Jenis rapid test (boraks, formalin, dll)');
            $table->enum('result', ['positif', 'negatif'])->default('negatif')->comment('Hasil uji');
            $table->text('notes')->nullable()->comment('Catatan');

            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rapid_tests');
    }
};
