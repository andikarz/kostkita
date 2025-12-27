<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UnifiedLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|string',
            'password' => 'required|string',
        ]);

        $loginInput = $request->input('email');
        $password   = $request->input('password');

        // 1) Coba login sebagai ADMIN
        $adminField = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'nama';

        if (Auth::guard('admin')->attempt([
            $adminField => $loginInput,
            'password'  => $password,
        ])) {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        // 2) Kalau bukan admin, coba login sebagai USER
        $userField = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

        if (Auth::guard('web')->attempt([
            $userField => $loginInput,
            'password' => $password,
        ])) {
            $request->session()->regenerate();
            return redirect()->intended(route('home'));
        }

        // 3) Gagal dua-duanya
        return back()
            ->withErrors(['email' => 'Email/username atau password salah.'])
            ->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
        }

        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
