<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commodity_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commodity_id')->constrained()->onDelete('cascade');
            $table->decimal('price', 15, 2);
            $table->decimal('price_previous', 15, 2)->nullable();
            $table->decimal('change_percent', 5, 2)->nullable();
            $table->string('source')->default('market');
            $table->date('price_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commodity_prices');
    }
};
