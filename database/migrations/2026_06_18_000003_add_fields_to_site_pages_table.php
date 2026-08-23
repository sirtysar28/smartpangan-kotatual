<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_pages', function (Blueprint $table) {
            $table->string('image')->nullable()->after('icon');
            $table->string('color', 20)->default('#6366f1')->after('image');
            $table->integer('sort_order')->default(0)->after('color');
        });
    }

    public function down(): void
    {
        Schema::table('site_pages', function (Blueprint $table) {
            $table->dropColumn(['image', 'color', 'sort_order']);
        });
    }
};
