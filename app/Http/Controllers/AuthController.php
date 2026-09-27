<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Show the login page.
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    /**
     * Handle login request.
     */
    public function login(Request $request)
    {
        // BYPASS LOGIN: Mengabaikan input dan langsung login sebagai admin
        $user = \App\Models\User::where('username', 'admin')->first();
        
        // Jika user admin belum ada (belum di-seed), gunakan user apa saja yang ada
        if (!$user) {
            $user = \App\Models\User::first();
        }

        if ($user) {
            Auth::login($user);
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        // Jika database benar-benar kosong
        return back()->withErrors([
            'username' => 'Tidak ada user di database. Harap jalankan: php artisan migrate --seed',
        ]);
    }

    /**
     * Handle logout request.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
