@props(['title' => 'Login'])

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} - {{ config('app.name', 'TaskGroup') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 font-sans antialiased">
    <div class="relative flex min-h-screen items-center justify-center overflow-hidden px-4 py-8 sm:px-6 lg:px-8"
        style="background: radial-gradient(60rem 36rem at 15% 0%, rgba(99,102,241,.35), transparent 60%), radial-gradient(50rem 30rem at 100% 100%, rgba(139,92,246,.28), transparent 60%), linear-gradient(180deg, #0b1120, #0f172a);">
        <div class="pointer-events-none absolute inset-0 opacity-[0.07]" style="background-image: linear-gradient(#fff 1px, transparent 1px), linear-gradient(90deg, #fff 1px, transparent 1px); background-size: 44px 44px;"></div>

        <main class="fade-up relative z-10 w-full">
            {{ $slot }}
        </main>
    </div>
</body>
</html>
