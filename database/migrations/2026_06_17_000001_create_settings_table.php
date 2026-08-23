<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->text('value')->nullable();
            $table->string('group')->default('general')->index();
            $table->string('label')->nullable();
            $table->string('type')->default('text'); // text, textarea, number, boolean, password
            $table->timestamps();
        });

        // Seed default settings
        DB::table('settings')->insert([
            ['key' => 'groq_api_key', 'value' => '', 'group' => 'ai', 'label' => 'Groq API Key', 'type' => 'password', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'groq_model', 'value' => 'llama-3.3-70b-versatile', 'group' => 'ai', 'label' => 'Groq Model', 'type' => 'text', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'ai_max_tokens', 'value' => '1000', 'group' => 'ai', 'label' => 'AI Max Tokens', 'type' => 'number', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'ai_temperature', 'value' => '0.7', 'group' => 'ai', 'label' => 'AI Temperature', 'type' => 'number', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'site_name', 'value' => 'Pangan Cerdas (Panca) Maryadat Kota Tual', 'group' => 'general', 'label' => 'Nama Aplikasi', 'type' => 'text', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'whatsapp_number', 'value' => '', 'group' => 'general', 'label' => 'Nomor WhatsApp (Admin)', 'type' => 'text', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
