@props(['title' => 'Dashboard', 'role' => auth()->user()?->role])

@php
    $roleLabel = match ($role) {
        'admin' => 'Administrator',
        'guru' => 'Guru',
        'siswa' => 'Siswa',
        default => 'Pengguna',
    };
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} - {{ config('app.name', 'TaskGroup') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
</head>
<body class="h-full bg-slate-50 font-sans antialiased" x-data="{ sidebarOpen: false }" @keydown.escape.window="sidebarOpen = false">
    <div class="min-h-screen">

        {{-- Overlay drawer (khusus layar kecil) --}}
        <div x-cloak x-show="sidebarOpen" x-transition.opacity @click="sidebarOpen = false" class="fixed inset-0 z-30 bg-slate-950/60 backdrop-blur-sm lg:hidden" aria-hidden="true"></div>

        {{-- Sidebar: permanen di desktop (lg+), drawer di tablet/HP --}}
        <aside
            x-cloak
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
            id="app-sidebar"
            class="sidebar-surface fixed inset-y-0 left-0 z-40 flex w-[min(85vw,18rem)] flex-col border-r border-white/5 text-slate-300 shadow-2xl shadow-slate-950/40 transition-transform duration-200 ease-out lg:w-72"
        >
            <div class="flex h-16 shrink-0 items-center justify-between gap-3 px-5 sm:h-20">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600 text-sm font-extrabold text-white shadow-glow">
                        TG
                    </div>
                    <div>
                        <p class="text-sm font-bold leading-tight text-white">TaskGroup</p>
                        <p class="text-[10px] font-semibold uppercase leading-tight tracking-[0.16em] text-slate-400">Education Platform</p>
                    </div>
                </div>
                <button type="button" @click="sidebarOpen = false" class="rounded-lg p-2 text-slate-400 transition hover:bg-white/10 hover:text-white lg:hidden" aria-label="Tutup menu">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12M18 6L6 18"/></svg>
                </button>
            </div>

            <nav class="flex-1 space-y-1 overflow-y-auto px-3 pb-4" aria-label="Menu utama" @click="if ($event.target.closest('a')) sidebarOpen = false">
                <p class="nav-section-title">Menu {{ $roleLabel }}</p>
                <x-nav-link :href="route($role.'.dashboard')" :active="request()->routeIs($role.'.dashboard')">
                    <x-slot:icon>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4h6v6H4V4zm10 0h6v6h-6V4zM4 14h6v6H4v-6zm10 0h6v6h-6v-6z"/></svg>
                    </x-slot:icon>
                    Dashboard
                </x-nav-link>

                @if (Route::has($role.'.teachers.index'))
                    <x-nav-link :href="route($role.'.teachers.index')" :active="request()->routeIs($role.'.teachers.*')">
                        <x-slot:icon>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 21V8a1 1 0 011-1h4V4a1 1 0 011-1h6a1 1 0 011 1v3h4a1 1 0 011 1v13H3zm7-13h4M10 12h4m-4 4h4"/></svg>
                        </x-slot:icon>
                        Guru
                    </x-nav-link>
                @endif

                @if (Route::has($role.'.students.index'))
                    <x-nav-link :href="route($role.'.students.index')" :active="request()->routeIs($role.'.students.*')">
                        <x-slot:icon>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM4 21v-1a6 6 0 0112 0v1"/></svg>
                        </x-slot:icon>
                        Siswa
                    </x-nav-link>
                @endif

                @if (Route::has($role.'.classes.index'))
                    <x-nav-link :href="route($role.'.classes.index')" :active="request()->routeIs($role.'.classes.*')">
                        <x-slot:icon>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7a2 2 0 012-2h4l2 2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V7z"/></svg>
                        </x-slot:icon>
                        Kelas
                    </x-nav-link>
                @endif

                @if (Route::has($role.'.groups.index'))
                    <x-nav-link :href="route($role.'.groups.index')" :active="request()->routeIs($role.'.groups.*')">
                        <x-slot:icon>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-1a4 4 0 00-4-4h-1m-6-3a3 3 0 100-6 3 3 0 000 6zm0 0c-3.31 0-6 1.79-6 4v1h12v-1c0-2.21-2.69-4-6-4zm8-6a3 3 0 100-6 3 3 0 000 6z"/></svg>
                        </x-slot:icon>
                        Kelompok
                    </x-nav-link>
                @endif

                @if (Route::has($role.'.tasks.index'))
                    <x-nav-link :href="route($role.'.tasks.index')" :active="request()->routeIs($role.'.tasks.*')">
                        <x-slot:icon>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6M9 8h1M7 4h10a1 1 0 011 1v14a1 1 0 01-1 1H7a1 1 0 01-1-1V5a1 1 0 011-1z"/></svg>
                        </x-slot:icon>
                        Tugas
                    </x-nav-link>
                @endif

                @if (Route::has($role.'.leaderboard.index'))
                    <x-nav-link :href="route($role.'.leaderboard.index')" :active="request()->routeIs($role.'.leaderboard.*')">
                        <x-slot:icon>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M8 21h8m-4-4v4m-5-18h10v5a5 5 0 01-10 0V3zM7 5H4v1a3 3 0 003 3m10-4h3v1a3 3 0 01-3 3"/></svg>
                        </x-slot:icon>
                        Leaderboard
                    </x-nav-link>
                @endif

                @if (Route::has($role.'.monitoring.index'))
                    <x-nav-link :href="route($role.'.monitoring.index')" :active="request()->routeIs($role.'.monitoring.*')">
                        <x-slot:icon>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 3H5a2 2 0 00-2 2v4m0 6v4a2 2 0 002 2h4m6 0h4a2 2 0 002-2v-4m0-6V5a2 2 0 00-2-2h-4"/></svg>
                        </x-slot:icon>
                        Monitoring
                    </x-nav-link>
                @endif

                @if (Route::has($role.'.assessments.index'))
                    <x-nav-link :href="route($role.'.assessments.index')" :active="request()->routeIs($role.'.assessments.*')">
                        <x-slot:icon>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </x-slot:icon>
                        Penilaian
                    </x-nav-link>
                @endif

                @if (Route::has($role.'.reports.index'))
                    <x-nav-link :href="route($role.'.reports.index')" :active="request()->routeIs($role.'.reports.*')">
                        <x-slot:icon>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h10M4 18h16"/></svg>
                        </x-slot:icon>
                        Laporan
                    </x-nav-link>
                @endif

                <p class="nav-section-title">Akun</p>

                <x-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.edit')">
                    <x-slot:icon>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM4 21v-1a8 8 0 0116 0v1"/></svg>
                    </x-slot:icon>
                    Profil Saya
                </x-nav-link>
            </nav>

            <div class="shrink-0 border-t border-white/10 p-3">
                <div class="mb-2 flex items-center gap-3 rounded-xl bg-white/5 px-3 py-2.5">
                    @if (auth()->user()->profile_photo_path)
                        <img src="{{ route('profile.photo') }}" alt="" class="h-9 w-9 shrink-0 rounded-full object-cover ring-2 ring-white/10">
                    @else
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-indigo-500/80 text-xs font-semibold text-white">
                            {{ collect(explode(' ', auth()->user()->name))->map(fn($w) => strtoupper(substr($w, 0, 1)))->take(2)->implode('') }}
                        </div>
                    @endif
                    <div class="min-w-0">
                        <p class="truncate text-sm font-semibold text-white">{{ auth()->user()->name }}</p>
                        <p class="text-[11px] text-slate-400">{{ $roleLabel }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-rose-300 transition hover:bg-rose-500/10 hover:text-rose-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m0-9H5a2 2 0 00-2 2v14a2 2 0 002 2h2"/></svg>
                        Keluar
                    </button>
                </form>
            </div>
        </aside>

        {{-- Konten utama (digeser selebar sidebar permanen di desktop) --}}
        <div class="flex min-h-screen min-w-0 flex-col lg:pl-72">
            {{-- Topbar --}}
            <header class="sticky top-0 z-20 flex h-16 items-center justify-between gap-3 border-b border-slate-200/70 bg-white/80 px-3 backdrop-blur-xl sm:h-20 sm:px-4 lg:px-8">
                <div class="flex min-w-0 items-center gap-2 sm:gap-3">
                    <button @click="sidebarOpen = ! sidebarOpen" :aria-expanded="sidebarOpen" aria-controls="app-sidebar" class="shrink-0 rounded-xl p-2.5 text-slate-500 transition hover:bg-slate-100 hover:text-slate-900 lg:hidden" aria-label="Buka menu">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 5h14a1 1 0 010 2H3a1 1 0 010-2zm0 4h14a1 1 0 010 2H3a1 1 0 010-2zm0 4h14a1 1 0 010 2H3a1 1 0 010-2z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div class="min-w-0">
                        <h2 class="truncate text-lg font-bold tracking-tight text-slate-900 sm:text-xl">{{ $title }}</h2>
                        <p class="hidden text-xs text-slate-400 sm:block">{{ $roleLabel }} &middot; {{ now()->translatedFormat('l, d F Y') }}</p>
                    </div>
                </div>

                <div class="flex shrink-0 items-center gap-1 sm:gap-2">
                    <x-notification-dropdown />

                    {{-- Menu pengguna --}}
                    <div x-data="{ open: false }" class="relative">
                        <button type="button" @click="open = ! open" @click.outside="open = false" class="flex items-center gap-2 rounded-xl p-1.5 transition hover:bg-slate-100 sm:pr-3" aria-label="Menu pengguna" :aria-expanded="open">
                            @if (auth()->user()->profile_photo_path)
                                <img src="{{ route('profile.photo') }}" alt="Foto profil {{ auth()->user()->name }}" class="h-9 w-9 shrink-0 rounded-full object-cover ring-2 ring-indigo-100 sm:h-10 sm:w-10">
                            @else
                                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-indigo-500 to-violet-600 text-xs font-semibold text-white shadow-md shadow-indigo-200 sm:h-10 sm:w-10 sm:text-sm">
                                    {{ collect(explode(' ', auth()->user()->name))->map(fn($w) => strtoupper(substr($w, 0, 1)))->take(2)->implode('') }}
                                </div>
                            @endif
                            <div class="hidden text-left sm:block">
                                <p class="max-w-[10rem] truncate text-sm font-semibold leading-tight text-slate-900">{{ auth()->user()->name }}</p>
                                <p class="text-xs capitalize leading-tight text-slate-400">{{ auth()->user()->role }}</p>
                            </div>
                        </button>

                        <div x-cloak x-show="open" x-transition class="absolute right-0 z-50 mt-2 w-56 overflow-hidden rounded-2xl border border-slate-200 bg-white p-1.5 shadow-xl">
                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">Profil Saya</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex w-full items-center gap-2 rounded-xl px-3 py-2.5 text-sm font-medium text-rose-600 transition hover:bg-rose-50">Keluar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <main class="fade-up flex-1 overflow-x-hidden px-3 py-4 sm:px-4 sm:py-6 lg:px-8">
                <div class="mx-auto w-full max-w-screen-2xl">
                    @if (session('status'))
                        <x-alert type="success" class="mb-4">{{ session('status') }}</x-alert>
                    @endif

                    @if ($errors->any())
                        <x-alert type="danger" class="mb-4">
                            <ul class="list-inside list-disc space-y-0.5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </x-alert>
                    @endif

                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>

    {{--
        Tempat halaman menaruh script inisialisasi Chart.js (Tahap 18).
        Diletakkan setelah @vite di <head> supaya window.Chart (didaftarkan
        di resources/js/app.js) sudah tersedia saat script ini berjalan.
    --}}
    @stack('scripts')
</body>
</html>
