<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Scheduler Sinkronisasi Data Pangan (Revisi 14 Okt 2026)
|--------------------------------------------------------------------------
| Menyalin/menarik data harga & stok pangan secara berkala dari sumber
| eksternal (FPI/Bappenas) agar dashboard selalu up-to-date.
| Interval & URL diatur dari Admin → Pengaturan → grup "Sinkronisasi".
| Pastikan cron server aktif: * * * * * php artisan schedule:run
*/
Schedule::command('pangan:sync-external')->everyMinute()->when(function () {
    $enabled = filter_var(\App\Models\Setting::get('sync_external_enabled', false), FILTER_VALIDATE_BOOLEAN);
    $url = (string) \App\Models\Setting::get('sync_external_url');
    if (!$enabled || $url === '') {
        return false;
    }
    // Throttle sesuai interval (menit) dari pengaturan (min. 5 menit)
    $interval = max(5, (int) \App\Models\Setting::get('sync_external_interval', 60));
    $lastRun = \Illuminate\Support\Facades\Cache::get('pangan_sync_last_run');
    return !$lastRun || now()->diffInMinutes($lastRun) >= $interval;
})->withoutOverlapping();
