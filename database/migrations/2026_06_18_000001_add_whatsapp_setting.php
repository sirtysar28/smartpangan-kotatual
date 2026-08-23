<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Add whatsapp_number setting if not exists
        $exists = DB::table('settings')->where('key', 'whatsapp_number')->exists();
        if (!$exists) {
            DB::table('settings')->insert([
                'key' => 'whatsapp_number',
                'value' => '',
                'group' => 'general',
                'label' => 'Nomor WhatsApp (Admin)',
                'type' => 'text',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        DB::table('settings')->where('key', 'whatsapp_number')->delete();
    }
};
