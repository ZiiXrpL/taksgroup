<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;

class GoogleAuthController extends Controller
{
    public function redirect(): RedirectResponse
    {
        if (! config('services.google.client_id') || ! config('services.google.client_secret') || ! config('services.google.redirect')) {
            return redirect()->route('login')->with('oauth_error', 'Login Google belum dikonfigurasi. Hubungi administrator aplikasi.');
        }

        $redirect = parse_url(config('services.google.redirect'));
        $configuredOrigin = ($redirect['scheme'] ?? 'http') . '://' . ($redirect['host'] ?? 'localhost');

        if (isset($redirect['port'])) {
            $configuredOrigin .= ':' . $redirect['port'];
        }

        if (request()->getSchemeAndHttpHost() !== $configuredOrigin) {
            return redirect()->away($configuredOrigin . '/auth/google');
        }

        return Socialite::driver('google')->redirect();
    }

    public function callback(): RedirectResponse
    {
        /** @var \Laravel\Socialite\Two\User $googleUser */
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (InvalidStateException) {
            return redirect()->route('login')->with('oauth_error', 'Sesi login Google tidak cocok atau sudah kedaluwarsa. Silakan mulai login Google lagi dari halaman ini.');
        }

        $googleData = $googleUser->user;
        $email = strtolower(trim((string) $googleUser->getEmail()));
        $googleId = (string) $googleUser->getId();
        $emailIsVerified = in_array($googleData['email_verified'] ?? false, [true, 1, '1', 'true'], true);

        if ($email === '' || $googleId === '' || ! $emailIsVerified) {
            return redirect()->route('login')->with('oauth_error', 'Google harus memberikan email terverifikasi untuk masuk.');
        }

        $user = User::query()->where('google_id', $googleId)->first();

        if (! $user) {
            $user = User::query()->where('email', $email)->first();

            if ($user && $user->google_id && $user->google_id !== $googleId) {
                return redirect()->route('login')->with('oauth_error', 'Email tersebut sudah terhubung ke akun Google lain.');
            }
        }

        if ($user) {
            $user->forceFill([
                'google_id' => $googleId,
                'email_verified_at' => $user->email_verified_at ?? now(),
            ])->save();
        } else {
            $user = User::create([
                'name' => $googleUser->getName() ?: Str::before($email, '@'),
                'email' => $email,
                'google_id' => $googleId,
                'password' => Hash::make(Str::random(64)),
                'role' => User::ROLE_SISWA,
                'email_verified_at' => now(),
            ]);
        }

        Auth::login($user, true);
        request()->session()->regenerate();

        if ($user->needsClassProfile()) {
            return redirect()->route('profile.edit');
        }

        return redirect()->route(match ($user->role) {
            User::ROLE_ADMIN => 'admin.dashboard',
            User::ROLE_GURU => 'guru.dashboard',
            default => 'siswa.dashboard',
        });
    }
}
