<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Services\ActivityLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

/**
 * Terminal Aplikasi (khusus role admin — super_admin & opd).
 *
 * Keamanan:
 * - TIDAK menjalankan shell sembarangan. Perintah artisan dijalankan in-process
 *   via Artisan::call() dan hanya untuk WHITELIST perintah aman.
 * - Setiap eksekusi dicatat ke Activity Log.
 * - Route endpoint dibatasi throttle.
 */
class TerminalController extends Controller
{
    /**
     * Whitelist perintah artisan yang boleh dijalankan.
     * Hanya perintah baca / maintenance ringan. Jangan tambahkan
     * perintah destruktif (migrate, db:wipe, tinker, dsb).
     */
    private const ARTISAN_WHITELIST = [
        'about'           => 'Informasi aplikasi (framework, PHP, environment)',
        'cache:clear'     => 'Bersihkan cache aplikasi',
        'config:clear'    => 'Bersihkan cache konfigurasi',
        'route:list'      => 'Daftar seluruh route aplikasi (opsi: --path=...)',
        'migrate:status'  => 'Status migrasi database (read-only)',
        'optimize'        => 'Optimalkan aplikasi (cache config, route, view)',
        'optimize:clear'  => 'Bersihkan semua file cache optimasi',
        'view:clear'      => 'Bersihkan cache view compiled',
        'event:list'      => 'Daftar event & listener terdaftar',
        'schedule:list'   => 'Daftar jadwal scheduled tasks',
        'queue:failed'    => 'Daftar job queue yang gagal',
        'queue:restart'   => 'Restart signal worker queue',
        'storage:link'    => 'Buat symbolic link public/storage',
    ];

    /** Perintah artisan yang boleh menerima argumen tambahan. */
    private const ARGS_ALLOWED = ['route:list'];

    /** Prefix yang otomatis diabaikan agar terasa seperti terminal sungguhan. */
    private const IGNORED_PREFIXES = ['sudo', 'php', 'artisan'];

    /**
     * Halaman terminal.
     */
    public function index()
    {
        return view('admin.terminal.index', [
            'commands' => $this->commandList(),
        ]);
    }

    /**
     * Eksekusi perintah (AJAX).
     */
    public function run(Request $request): JsonResponse
    {
        $request->validate(['command' => 'required|string|max:255']);

        $raw = trim($request->input('command'));
        $tokens = preg_split('/\s+/', strtolower($raw));

        // Normalisasi: buang prefix "sudo", "php", "artisan"
        while (!empty($tokens) && in_array($tokens[0], self::IGNORED_PREFIXES, true)) {
            array_shift($tokens);
        }

        $command = $tokens[0] ?? '';
        $args = array_slice($tokens, 1);
        $started = microtime(true);

        if ($command === '') {
            return $this->respond(false, 'Perintah kosong. Ketik "help" untuk bantuan.', $started);
        }

        try {
            switch ($command) {
                case 'help':
                    $output = $this->renderHelp();
                    break;

                case 'clear':
                    return response()->json(['ok' => true, 'output' => '', 'cleared' => true]);

                case 'sysinfo':
                    $output = $this->renderSysinfo();
                    break;

                case 'whoami':
                    $output = $this->renderWhoami();
                    break;

                case 'logs':
                    $output = $this->renderLogs();
                    break;

                default:
                    if (!isset(self::ARTISAN_WHITELIST[$command])) {
                        $output = "❌ Perintah \"{$command}\" tidak dikenal atau TIDAK diizinkan.\n"
                            . "    Ketik \"help\" untuk daftar perintah yang tersedia.\n"
                            . "    ⚠️ Terminal ini hanya menjalankan perintah whitelist (aman).";
                        return $this->respond(false, $output, $started, $raw);
                    }

                    // Validasi argumen: hanya command tertentu & format aman
                    if (!empty($args)) {
                        if (!in_array($command, self::ARGS_ALLOWED, true)) {
                            $output = "❌ Perintah \"{$command}\" tidak menerima argumen.";
                            return $this->respond(false, $output, $started, $raw);
                        }
                        foreach ($args as $arg) {
                            if (!preg_match('/^[a-z0-9:._\-\/=]+$/', $arg)) {
                                $output = "❌ Argumen tidak valid: \"{$arg}\"";
                                return $this->respond(false, $output, $started, $raw);
                            }
                        }
                    }

                    Artisan::call($command, $this->parseArgs($args));
                    $output = trim(Artisan::output());
                    if ($output === '') {
                        $output = "✅ Perintah \"{$command}\" berhasil dijalankan.";
                    }
                    break;
            }

            return $this->respond(true, $output, $started, $raw);
        } catch (\Throwable $e) {
            $output = "❌ Gagal menjalankan perintah: " . $e->getMessage();
            return $this->respond(false, $output, $started, $raw);
        }
    }

    // ==================== PRIVATE HELPERS ====================

    private function respond(bool $ok, string $output, float $started, ?string $command = null): JsonResponse
    {
        if ($command !== null) {
            ActivityLogService::log('terminal', 'terminal', "Eksekusi perintah terminal: {$command}", [
                'ok' => $ok,
            ]);
        }

        return response()->json([
            'ok' => $ok,
            'output' => $output,
            'duration' => round((microtime(true) - $started) * 1000),
        ]);
    }

    /**
     * Ubah token ["--path=admin"] menjadi ["path" => "admin"].
     */
    private function parseArgs(array $args): array
    {
        $params = [];
        foreach ($args as $arg) {
            if (str_starts_with($arg, '--') && str_contains($arg, '=')) {
                [$key, $value] = explode('=', substr($arg, 2), 2);
                $params[$key] = $value;
            }
        }
        return $params;
    }

    /**
     * Daftar perintah (untuk help & tombol cepat di UI).
     */
    private function commandList(): array
    {
        $builtins = [
            'help'    => 'Daftar semua perintah yang tersedia',
            'sysinfo' => 'Informasi sistem & server aplikasi',
            'whoami'  => 'Identitas user yang sedang login',
            'logs'    => '10 aktivitas terakhir pada aplikasi',
            'clear'   => 'Bersihkan layar terminal',
        ];

        return [
            'builtins' => $builtins,
            'artisan'  => self::ARTISAN_WHITELIST,
        ];
    }

    private function renderHelp(): string
    {
        $list = $this->commandList();
        $out = "┌─ PANGAN CERDAS (PANCA) TERMINAL ────────┐\n";

        $out .= "\n  PERINTAH BAWAAN:\n";
        foreach ($list['builtins'] as $cmd => $desc) {
            $out .= sprintf("   %-16s %s\n", $cmd, $desc);
        }

        $out .= "\n  ARTISAN (whitelist — aman):\n";
        foreach ($list['artisan'] as $cmd => $desc) {
            $out .= sprintf("   %-16s %s\n", $cmd, $desc);
        }

        $out .= "\n  Catatan:\n"
            . "   • Prefix \"php artisan\" boleh dipakai (otomatis diabaikan).\n"
            . "   • Terminal ini dibatasi whitelist demi keamanan aplikasi.\n"
            . "   • Semua eksekusi tercatat di Activity Log.";

        return $out;
    }

    private function renderSysinfo(): string
    {
        $rows = [
            'Aplikasi'    => 'Pangan Cerdas (PANCA) Maryadat Kota Tual',
            'Laravel'     => app()->version(),
            'PHP'         => PHP_VERSION . ' (' . PHP_SAPI . ')',
            'OS Server'   => PHP_OS_FAMILY,
            'Environment' => config('app.env'),
            'Debug Mode'  => config('app.debug') ? 'ON ⚠️' : 'OFF',
            'Database'    => strtoupper(config('database.default')),
            'Timezone'    => config('app.timezone'),
            'Waktu'       => now()->format('d M Y H:i:s'),
            'Memory Limit'=> ini_get('memory_limit') ?: '-',
        ];

        if (function_exists('disk_free_space')) {
            try {
                $free = round(disk_free_space(base_path()) / 1024 / 1024);
                $total = round(disk_total_space(base_path()) / 1024 / 1024);
                $rows['Disk'] = number_format($free, 0, ',', '.') . ' MB bebas dari ' . number_format($total, 0, ',', '.') . ' MB';
            } catch (\Throwable $e) {
                $rows['Disk'] = '-';
            }
        }

        $out = "── SYSTEM INFO ─────────────────────────────\n";
        foreach ($rows as $label => $value) {
            $out .= sprintf("  %-13s : %s\n", $label, $value);
        }
        return trim($out);
    }

    private function renderWhoami(): string
    {
        $user = auth()->user();
        return "── USER AKTIF ──────────────────────────────\n"
            . "  Nama     : {$user->name}\n"
            . "  Email    : {$user->email}\n"
            . "  Role     : {$user->role}\n"
            . "  Login    : " . now()->format('d M Y H:i:s');
    }

    private function renderLogs(): string
    {
        $logs = ActivityLog::with('user')->latestFirst()->limit(10)->get();

        if ($logs->isEmpty()) {
            return "Belum ada aktivitas tercatat.";
        }

        $out = "── 10 AKTIVITAS TERAKHIR ───────────────────\n";
        foreach ($logs as $log) {
            $time = $log->created_at->format('d/m H:i');
            $user = $log->user?->name ?? 'sistem';
            $out .= sprintf("  [%s] %-12s %-12s %s\n", $time, "#{$log->action}", $user, \Str::limit($log->description, 60));
        }
        $out .= "\n  Lihat lengkap: menu Super Admin → Aktivitas Log";
        return $out;
    }
}
