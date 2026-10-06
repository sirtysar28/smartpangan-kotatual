<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        // ===== CAPTCHA 5 KARAKTER ALPHANUMERIC =====
        if (!session()->has('login_captcha_code')) {
            // Karakter tanpa ambigu (tanpa O/0/I/1/L)
            $chars = 'ABCDEFGHJKMNPQRSTUVWXYZ23456789';
            $code = substr(str_shuffle(str_repeat($chars, 5)), 0, 5);
            session(['login_captcha_code' => $code]);
        }

        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Hapus captcha setelah login sukses
        session()->forget('login_captcha_code');

        $user = Auth::user();

        if ($user->isAdminLevel()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isUmkm()) {
            return redirect()->route('umkm.dashboard');
        }

        return redirect()->route('home');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
