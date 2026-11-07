<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureStudentHasClass
{
    public function handle(Request $request, Closure $next): Response
    {
        // Siswa wajib punya kelas DAN nomor absen. Siswa lama yang sudah
        // punya class_id tetapi belum punya nomor absen diarahkan ke profil
        // untuk melengkapinya (rute profil berada di luar grup siswa,
        // sehingga tidak terjadi redirect berulang).
        if ($request->user()?->needsClassProfile()) {
            return redirect()->route('profile.edit');
        }

        return $next($request);
    }
}
