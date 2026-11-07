<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Tampilkan halaman login.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Proses percobaan login lalu arahkan user sesuai role-nya.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        if (Auth::user()->needsClassProfile()) {
            return redirect()->route('profile.edit');
        }

        return redirect()->intended($this->redirectPathForRole(Auth::user()));
    }

    /**
     * Logout user yang sedang aktif.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Tentukan halaman dashboard tujuan berdasarkan role user.
     */
    private function redirectPathForRole(User $user): string
    {
        if ($user->needsClassProfile()) {
            return route('profile.edit');
        }

        return match ($user->role) {
            User::ROLE_ADMIN => route('admin.dashboard'),
            User::ROLE_GURU => route('guru.dashboard'),
            User::ROLE_SISWA => route('siswa.dashboard'),
            default => route('login'),
        };
    }
}
