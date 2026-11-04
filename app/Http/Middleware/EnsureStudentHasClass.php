<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureStudentHasClass
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()?->isSiswa() && $request->user()->class_id === null) {
            return redirect()->route('profile.edit');
        }

        return $next($request);
    }
}
