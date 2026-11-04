@props(['title' => 'Login'])

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} - {{ config('app.name', 'TaskGroup') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-white antialiased">
    <div class="relative flex min-h-screen items-center justify-center overflow-hidden bg-white px-4 py-8 sm:px-6 lg:px-8">
        <div class="pointer-events-none absolute -left-32 -top-32 h-80 w-80 rounded-full bg-blue-100/40 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-40 -right-32 h-96 w-96 rounded-full bg-indigo-100/40 blur-3xl"></div>

        <main class="relative z-10 w-full">
            {{ $slot }}
        </main>
    </div>
</body>
</html>
