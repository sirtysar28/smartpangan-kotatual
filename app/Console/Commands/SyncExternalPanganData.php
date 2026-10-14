<?php

namespace App\Console\Commands;

use App\Models\Setting;
use App\Services\ActivityLogService;
use Illuminate\Console\Command;

/**
 * Sinkronisasi Data Eksternal (Revisi 14 Okt 2026)
 *
 * Menarik (pull) data harga/stok pangan secara berkala dari sumber eksternal
 * (FPI/Bappenas) agar dashboard selalu up-to-date. Dijalankan otomatis oleh
 * scheduler (cron) bila diaktifkan di Pengaturan, atau manual:
 *
 *   php artisan pangan:sync-external
 *
 * Koordinasi format & mekanisme integrasi API dengan Bappenas/FPI masih
 * berjalan — command ini siap diaktifkan setelah endpoint final tersedia.
 */
class SyncExternalPanganData extends Command
{
    protected $signature = 'pangan:sync-external {--force : Jalankan walau sinkronisasi dinonaktifkan}';

    protected $description = 'Tarik data harga & stok pangan dari sumber eksternal (FPI/Bappenas) secara berkala';

    public function handle(): int
    {
        $enabled = filter_var(Setting::get('sync_external_enabled', false), FILTER_VALIDATE_BOOLEAN);
        $url = (string) Setting::get('sync_external_url');

        if (!$this->option('force')) {
            if (!$enabled) {
                $this->info('Sinkronisasi data eksternal dinonaktifkan. Aktifkan di Admin → Pengaturan → grup Sinkronisasi.');
                return self::SUCCESS;
            }
            if (!$url) {
                $this->warn('URL API sumber data (FPI/Bappenas) belum diatur di Pengaturan.');
                return self::SUCCESS;
            }
        }

        $this->info("Menarik data dari: {$url}");

        try {
            $response = \Http::timeout(60)->acceptJson()->get($url);

            if ($response->failed()) {
                $this->error('Gagal mengambil data (HTTP ' . $response->status() . ').');
                ActivityLogService::log('sync', 'sinkronisasi', 'Gagal sinkronisasi data eksternal: HTTP ' . $response->status());
                return self::FAILURE;
            }

            $payload = $response->json();

            // TODO (menunggu format final API dari Bappenas/FPI):
            // - mapping field harga per komoditas & tanggal berlaku → commodity_prices
            // - mapping field stok/pasokan → commodity_stocks
            // - updateOrCreate agar idempotent (aman dijalankan berkala)
            $count = is_array($payload) ? count($payload) : 0;
            $this->info("Data diterima ({$count} entri). Mapping menyusul sesuai format final API Bappenas/FPI.");

            \Illuminate\Support\Facades\Cache::put('pangan_sync_last_run', now(), now()->addDay());
            ActivityLogService::log('sync', 'sinkronisasi', "Sinkronisasi data eksternal dijalankan ({$count} entri diterima dari {$url})");
            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error('Error sinkronisasi: ' . $e->getMessage());
            \Log::error('Sync external pangan failed: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}
