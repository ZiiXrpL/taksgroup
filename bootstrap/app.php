<?php

use App\Http\Middleware\EnsureUserHasRole;
use App\Http\Middleware\EnsureStudentHasClass;
use App\Models\User;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            // Global web middleware stack uses Laravel defaults.
        ]);

        $middleware->alias([
            'role' => EnsureUserHasRole::class,
            'student.class' => EnsureStudentHasClass::class,
        ]);

        // User yang belum login akan diarahkan ke halaman login saat
        // mencoba mengakses route yang dilindungi middleware 'auth'.
        $middleware->redirectGuestsTo(fn() => route('login'));

        // User yang sudah login tidak boleh kembali ke halaman login.
        // Arahkan langsung ke dashboard sesuai role agar route home
        // yang mengarah ke login tidak membentuk redirect loop.
        $middleware->redirectUsersTo(function (Request $request) {
            if ($request->user()?->isSiswa() && $request->user()->class_id === null) {
                return route('profile.edit');
            }

            return match ($request->user()?->role) {
                User::ROLE_ADMIN => route('admin.dashboard'),
                User::ROLE_GURU => route('guru.dashboard'),
                User::ROLE_SISWA => route('siswa.dashboard'),
                default => route('login'),
            };
        });
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
