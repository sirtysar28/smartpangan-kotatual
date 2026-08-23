<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commodity_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commodity_id')->constrained()->onDelete('cascade');
            $table->date('monitoring_date');
            $table->decimal('stock_quantity', 15, 2)->comment('Jumlah stok pada hari pantauan');
            $table->string('stock_unit')->default('kg')->comment('Satuan stok');
            $table->integer('stock_duration_days')->nullable()->comment('Estimasi stok bertahan berapa hari');
            $table->decimal('weekly_need', 15, 2)->comment('Kebutuhan per minggu');
            $table->enum('stock_status', ['aman', 'rawan', 'kritis'])->default('aman')->comment('Status stok');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['commodity_id', 'monitoring_date'], 'cs_commodity_date_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commodity_stocks');
    }
};
