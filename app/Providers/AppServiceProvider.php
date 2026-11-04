<?php

namespace App\Providers;

use App\Models\Task;
use App\Models\TaskComment;
use App\Models\TaskFile;
use App\Policies\CommentPolicy;
use App\Policies\TaskFilePolicy;
use App\Policies\TaskPolicy;
use Illuminate\Support\Facades\Gate;
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
    }
}
