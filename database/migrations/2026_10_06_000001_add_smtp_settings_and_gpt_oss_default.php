<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        // ===== Pengaturan SMTP untuk notifikasi email =====
        $smtp = [
            ['key' => 'mail_mailer',       'value' => 'smtp',                                                  'group' => 'email', 'label' => 'Mail Mailer',              'type' => 'select'],
            ['key' => 'mail_host',         'value' => '',                                                      'group' => 'email', 'label' => 'SMTP Host',                'type' => 'text'],
            ['key' => 'mail_port',         'value' => '587',                                                   'group' => 'email', 'label' => 'SMTP Port',                'type' => 'number'],
            ['key' => 'mail_username',     'value' => '',                                                      'group' => 'email', 'label' => 'SMTP Username',            'type' => 'text'],
            ['key' => 'mail_password',     'value' => '',                                                      'group' => 'email', 'label' => 'SMTP Password',            'type' => 'password'],
            ['key' => 'mail_encryption',   'value' => 'tls',                                                   'group' => 'email', 'label' => 'Enkripsi SMTP',            'type' => 'select'],
            ['key' => 'mail_from_address', 'value' => 'no-reply@smartpangan-kotatual.bangunberkahabadi.com',  'group' => 'email', 'label' => 'Email Pengirim (From)',    'type' => 'text'],
            ['key' => 'mail_from_name',    'value' => 'PANCA Kota Tual',                                       'group' => 'email', 'label' => 'Nama Pengirim (From Name)','type' => 'text'],
        ];

        foreach ($smtp as $row) {
            DB::table('settings')->insertOrIgnore(
                array_merge($row, ['created_at' => $now, 'updated_at' => $now])
            );
        }

        // ===== Alihkan model Groq default ke GPT-OSS 120B =====
        DB::table('settings')
            ->where('key', 'groq_model')
            ->whereIn('value', ['', 'llama-3.3-70b-versatile', 'llama-3.1-8b-instant'])
            ->update(['value' => 'openai/gpt-oss-120b', 'updated_at' => $now]);
    }

    public function down(): void
    {
        DB::table('settings')->whereIn('key', [
            'mail_mailer', 'mail_host', 'mail_port', 'mail_username',
            'mail_password', 'mail_encryption', 'mail_from_address', 'mail_from_name',
        ])->delete();
    }
};
