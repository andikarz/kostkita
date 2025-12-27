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
     * Tampilkan halaman login (view custom-mu)
     */
    public function create(): View
    {
        return view('auth.login'); // sudah pakai view buatanmu
    }

    /**
     * Proses autentikasi
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        // Arahkan ke 'home' (atau ke URL terakhir yang diminta)
        return redirect()->intended(
            \Route::has('home') ? route('home', absolute: false) : '/'
        );
    }

    /**
     * Logout
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Setelah logout, balik ke 'home'
        return \Route::has('home') ? redirect()->route('home') : redirect('/');
    }
}
