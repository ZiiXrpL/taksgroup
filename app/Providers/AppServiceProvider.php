<?php

namespace App\Providers;

use App\Models\Task;
use App\Models\TaskComment;
use App\Models\TaskFile;
use App\Policies\CommentPolicy;
use App\Policies\TaskFilePolicy;
use App\Policies\TaskPolicy;
use App\Support\UploadRules;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Didaftarkan eksplisit (bukan mengandalkan auto-discovery Laravel)
        // agar jelas terbaca di satu tempat.
        Gate::policy(Task::class, TaskPolicy::class);
        Gate::policy(TaskFile::class, TaskFilePolicy::class);
        Gate::policy(TaskComment::class, CommentPolicy::class);

        // Aturan validasi `extensions:` untuk upload file tugas. Aturan
        // bawaan Laravel hanya mengenali `mimes`/`mimetypes`; pengecekan
        // ekstensi eksplisit tetap diperlukan agar daftar format yang
        // diizinkan (dan penolakan executable) konsisten di backend.
        Validator::extend('extensions', function ($attribute, $value, $parameters) {
            if (! $value instanceof UploadedFile) {
                return false;
            }

            $extension = strtolower($value->getClientOriginalExtension());
            $allowed = UploadRules::normalizeExtensions($parameters);

            return in_array($extension, $allowed, true);
        });
    }
}
