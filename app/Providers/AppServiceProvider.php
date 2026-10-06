<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use App\Services\ActivityLogService;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        \Illuminate\Database\Eloquent\Model::preventLazyLoading(!$this->app->isProduction());

        // ===== SMTP dari Pengaturan (override config mail runtime) =====
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                \App\Http\Controllers\Admin\SettingController::applyMailConfig();
            }
        } catch (\Throwable $e) {
            // DB belum siap (mis. saat migrate pertama) — abaikan
        }

        // Redirect default /dashboard based on role
        \Illuminate\Support\Facades\Route::get('/dashboard', function () {
            $user = auth()->user();
            if ($user->isAdminLevel()) return redirect()->route('admin.dashboard');
            if ($user->isUmkm()) return redirect()->route('umkm.dashboard');
            return redirect()->route('home');
        })->middleware('auth')->name('dashboard');

        // Activity log for login
        Event::listen(Login::class, function (Login $event) {
            ActivityLogService::log('login', 'auth', "User login: {$event->user->name} ({$event->user->email})", [
                'user_id' => $event->user->id,
                'role' => $event->user->role,
            ]);
        });

        // Activity log for logout
        Event::listen(Logout::class, function (Logout $event) {
            if ($event->user) {
                ActivityLogService::log('logout', 'auth', "User logout: {$event->user->name} ({$event->user->email})", [
                    'user_id' => $event->user->id,
                    'role' => $event->user->role,
                ]);
            }
        });
    }
}
