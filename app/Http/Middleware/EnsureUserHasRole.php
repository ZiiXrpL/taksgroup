<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Membatasi akses route hanya untuk user dengan role tertentu.
 *
 * Middleware ini TIDAK menggantikan Policy - ia hanya menjaga level route
 * (mis. hanya "guru" yang boleh masuk ke prefix /guru/*). Otorisasi yang
 * lebih detail per-record (mis. guru hanya boleh mengubah tugas miliknya
 * sendiri) tetap dilakukan lewat Policy pada Tahap 7.
 *
 * Penggunaan pada route: middleware('role:admin') atau middleware('role:guru,admin')
 */
class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(401, 'Anda harus login terlebih dahulu.');
        }

        if (! in_array($user->role, $roles, true)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
