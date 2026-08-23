<?php

namespace App\Http\Controllers;

use App\Models\Umkm;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UmkmRegistrationController extends Controller
{
    public function create()
    {
        if (Auth::check() && Auth::user()->umkm) {
            return redirect()->route('umkm.dashboard');
        }
        return view('frontend.umkm.register');
    }

    public function store(Request $request)
    {
        // Jika belum login → buat akun user dulu
        if (!Auth::check()) {
            $request->validate([
                'name'     => 'required|string|max:255',
                'email'    => 'required|email|max:255|unique:users,email',
                'password' => 'required|string|min:8|confirmed',
            ]);

            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => 'umkm',
                'phone'    => $request->phone,
                'status'   => 'active',
            ]);

            Auth::login($user);
        }

        $data = $request->validate([
            'business_name'=>'required|string|max:255',
            'description'=>'nullable|string',
            'nib'=>'nullable|string|max:50',
            'ktp_image'=>'required|image|max:2048',
            'business_image'=>'required|image|max:2048',
            'address'=>'required|string',
            'phone'=>'required|string|max:20',
            'whatsapp'=>'nullable|string|max:20',
        ]);

        $data['user_id'] = Auth::id();
        $data['slug'] = \Str::slug($data['business_name']) . '-' . \Str::random(4);
        $data['status'] = 'pending';

        if ($request->hasFile('ktp_image')) {
            $data['ktp_image'] = $request->file('ktp_image')->store('umkm/ktp', 'public');
        }
        if ($request->hasFile('business_image')) {
            $data['business_image'] = $request->file('business_image')->store('umkm/business', 'public');
        }

        $umkm = Umkm::create($data);
        Auth::user()->update(['role' => 'umkm']);

        ActivityLogService::log('register', 'umkm', "Pendaftaran UMKM baru: {$umkm->business_name} oleh " . Auth::user()->name, ['umkm_id' => $umkm->id]);

        return redirect()->route('umkm.dashboard')->with('success', 'Pendaftaran berhasil! Menunggu verifikasi.');
    }
}
