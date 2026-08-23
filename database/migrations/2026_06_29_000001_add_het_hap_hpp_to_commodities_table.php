<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('commodities', function (Blueprint $table) {
            $table->decimal('het', 15, 2)->nullable()->after('unit')->comment('Harga Eceran Tertinggi');
            $table->decimal('hap', 15, 2)->nullable()->after('het')->comment('Harga Acuan Pemerintah');
            $table->decimal('hpp', 15, 2)->nullable()->after('hap')->comment('Harga Pokok Penjualan');
        });
    }

    public function down(): void
    {
        Schema::table('commodities', function (Blueprint $table) {
            $table->dropColumn(['het', 'hap', 'hpp']);
        });
    }
};
