<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('food_vulnerabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commodity_id')->nullable()->constrained()->nullOnDelete();
            $table->string('district_name')->comment('Nama kecamatan/kelurahan');
            $table->enum('period_type', ['q1', 'q2', 'q3', 'q4'])->comment('Triwulan');
            $table->year('period_year')->comment('Tahun periode');
            $table->string('period_label')->comment('Contoh: Jan-Mar 2026');
            $table->enum('vulnerability_status', ['rawan', 'tidak_rawan', 'rawan_sedang', 'rawan_berat'])->default('tidak_rawan');

            // Indikator FSVA
            $table->decimal('food_price_index', 5, 2)->nullable()->comment('Indeks harga pangan (%)');
            $table->decimal('poverty_rate', 5, 2)->nullable()->comment('Tingkat kemiskinan (%)');
            $table->decimal('malnutrition_rate', 5, 2)->nullable()->comment('Tingkat gizi buruk (%)');
            $table->decimal('food_access_score', 5, 2)->nullable()->comment('Skor akses pangan (0-100)');
            $table->decimal('food_availability_score', 5, 2)->nullable()->comment('Skor ketersediaan pangan (0-100)');
            $table->decimal('food_utilization_score', 5, 2)->nullable()->comment('Skor pemanfaatan pangan (0-100)');
            $table->decimal('food_stability_score', 5, 2)->nullable()->comment('Skor stabilitas pangan (0-100)');
            $table->decimal('overall_fsva_score', 5, 2)->nullable()->comment('Skor FSVA keseluruhan (0-100)');

            $table->text('risk_factors')->nullable()->comment('Faktor risiko (json/array)');
            $table->text('intervention_recommendation')->nullable()->comment('Rekomendasi intervensi');
            $table->text('notes')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();

            $table->timestamps();

            $table->unique(['district_name', 'period_type', 'period_year'], 'fv_district_period_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('food_vulnerabilities');
    }
};
