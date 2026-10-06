<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SettingController extends Controller
{
    public function index()
    {
        $groups = Setting::orderBy('group')->orderBy('label')->get()->groupBy('group');

        return view('admin.settings.index', compact('groups'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'settings' => 'required|array',
            'settings.*' => 'nullable|string',
        ]);

        $changes = [];
        $failures = [];

        foreach ($request->settings as $key => $value) {
            try {
                $existing = Setting::where('key', $key)->first();

                if ($existing) {
                    // Password kosong = jangan ubah
                    if ($existing->type === 'password' && ($value === '' || $value === '••••••••••••••••')) {
                        continue;
                    }
                    if ((string) $existing->value !== (string) $value) {
                        $changes[] = "{$existing->label}: diubah";
                        $existing->value = $value;
                        $existing->save();
                    }
                } else {
                    // Row baru (mis. setting SMTP yang baru ditambahkan)
                    Setting::set($key, $value);
                    $changes[] = "{$key}: ditambahkan";
                }
            } catch (\Throwable $e) {
                $failures[] = $key;
                \Log::error("Gagal simpan setting [{$key}]: " . $e->getMessage());
            }
        }

        $desc = count($changes) > 0 ? implode('; ', $changes) : 'Tidak ada perubahan';
        ActivityLogService::log('update', 'settings', "Memperbarui pengaturan sistem. {$desc}");

        if (!empty($failures)) {
            return back()->with('error', 'Beberapa pengaturan gagal disimpan: ' . implode(', ', $failures));
        }

        return back()->with('success', 'Pengaturan berhasil disimpan. ' . (count($changes) ? '(' . count($changes) . ' perubahan)' : ''));
    }

    /**
     * Kirim email tes menggunakan konfigurasi SMTP dari pengaturan.
     */
    public function testEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $host = Setting::get('mail_host');
        if (!$host) {
            $msg = 'SMTP Host belum diatur. Isi dulu pengaturan SMTP lalu simpan.';
            return $request->expectsJson()
                ? response()->json(['success' => false, 'message' => $msg], 422)
                : back()->with('error', $msg);
        }

        // Override konfigurasi mail runtime dari settings
        self::applyMailConfig();

        try {
            Mail::raw('Ini adalah email tes dari PANCA — Pangan Cerdas Maryadat Kota Tual. Jika Anda menerima email ini, konfigurasi SMTP sudah benar. ✅', function ($message) use ($request) {
                $message->to($request->email)
                    ->subject('[PANCA] Email Tes Konfigurasi SMTP');
            });

            ActivityLogService::log('update', 'settings', "Kirim email tes SMTP ke {$request->email}");

            $msg = "Email tes berhasil dikirim ke {$request->email}. Cek inbox (dan folder spam).";
            return $request->expectsJson()
                ? response()->json(['success' => true, 'message' => $msg])
                : back()->with('success', $msg);
        } catch (\Throwable $e) {
            \Log::error('SMTP test failed: ' . $e->getMessage());
            $msg = 'Gagal mengirim email: ' . $e->getMessage();
            return $request->expectsJson()
                ? response()->json(['success' => false, 'message' => $msg], 500)
                : back()->with('error', $msg);
        }
    }

    /**
     * Terapkan konfigurasi SMTP dari settings ke config runtime.
     */
    public static function applyMailConfig(): void
    {
        if (!Setting::get('mail_host')) {
            return;
        }

        config([
            'mail.default' => Setting::get('mail_mailer', 'smtp'),
            'mail.mailers.smtp.host' => (string) Setting::get('mail_host'),
            'mail.mailers.smtp.port' => (int) Setting::get('mail_port', 587),
            'mail.mailers.smtp.username' => (string) Setting::get('mail_username'),
            'mail.mailers.smtp.password' => (string) Setting::get('mail_password'),
            'mail.mailers.smtp.encryption' => in_array(Setting::get('mail_encryption'), ['tls', 'ssl', null]) ? Setting::get('mail_encryption', 'tls') : null,
            'mail.from.address' => Setting::get('mail_from_address', config('mail.from.address')),
            'mail.from.name' => Setting::get('mail_from_name', config('mail.from.name')),
        ]);
    }
}
