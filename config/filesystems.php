<?php

use Illuminate\Support\Facades\Storage;

return [

    'default' => env('FILESYSTEM_DISK', 'local'),

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app/private'),
            'serve' => true,
            'throw' => false,
            'report' => false,
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
            'throw' => false,
            'report' => false,
        ],

        // Dedicated disk for task submission files. Kept separate from the
        // generic public disk so access can be gated through TaskFilePolicy
        // instead of being served as a raw public URL.
        'task_files' => [
            'driver' => 'local',
            'root' => storage_path('app/task_files'),
            'visibility' => 'private',
            'throw' => false,
            'report' => false,
        ],

    ],

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
