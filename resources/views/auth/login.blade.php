<x-layouts.guest title="Login">
    <div class="mx-auto grid w-full max-w-5xl overflow-hidden rounded-[2rem] border border-white/80 bg-white/90 shadow-2xl shadow-slate-300/50 backdrop-blur md:grid-cols-[0.9fr_1.1fr]">
        <section class="relative hidden overflow-hidden bg-gradient-to-br from-blue-700 via-indigo-700 to-violet-800 p-10 text-white md:flex md:flex-col md:justify-between lg:p-14">
            <div class="absolute -right-20 -top-20 h-64 w-64 rounded-full border-[30px] border-white/10"></div>
            <div class="absolute -bottom-24 -left-24 h-72 w-72 rounded-full border-[40px] border-white/10"></div>

            <div class="relative">
                <div class="mb-12 flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-white/15 text-sm font-extrabold tracking-wide ring-1 ring-white/20">
                        TG
                    </div>
                    <span class="text-lg font-bold tracking-tight">TaskGroup</span>
                </div>

                <p class="mb-4 text-sm font-semibold uppercase tracking-[0.2em] text-blue-200">
                    TaskGroup
                </p>
                <h1 class="max-w-sm text-4xl font-bold leading-tight lg:text-5xl">
                    Kelola Tugas Kelompok Lebih Praktis
                </h1>
                <p class="mt-6 max-w-sm text-sm leading-6 text-blue-100">
                    TaskGroup membantu siswa berkolaborasi dan guru memantau progres tugas secara real-time.
                </p>
            </div>

            <div class="relative mt-12 flex items-center gap-3 text-sm text-blue-100">
                <div class="flex -space-x-2">
                    <span class="flex h-8 w-8 items-center justify-center rounded-full border-2 border-indigo-700 bg-amber-300 text-xs font-bold text-amber-900">A</span>
                    <span class="flex h-8 w-8 items-center justify-center rounded-full border-2 border-indigo-700 bg-emerald-300 text-xs font-bold text-emerald-900">G</span>
                    <span class="flex h-8 w-8 items-center justify-center rounded-full border-2 border-indigo-700 bg-pink-300 text-xs font-bold text-pink-900">S</span>
                </div>
                <span>Untuk tim sekolah yang aktif</span>
            </div>
        </section>

        <section class="p-6 sm:p-10 lg:p-14">
            <div class="mb-8 md:hidden">
                <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-600 text-sm font-extrabold tracking-wide text-white shadow-lg shadow-blue-200">
                    TG
                </div>
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-blue-600">TaskGroup</p>
            </div>

            <div class="mb-8">
                <h2 class="text-3xl font-bold tracking-tight text-slate-900">Masuk ke akun Anda</h2>
                <p class="mt-2 text-sm leading-6 text-slate-500">Silakan masuk untuk melanjutkan</p>
            </div>

            @if (session('status'))
                <x-alert type="success" class="mb-5">
                    {{ session('status') }}
                </x-alert>
            @endif

            @if (session('oauth_error'))
                <x-alert type="danger" class="mb-5">
                    {{ session('oauth_error') }}
                </x-alert>
            @endif

            <form method="POST" action="{{ route('login.store') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="mb-2 block text-sm font-semibold text-slate-700">Email Sekolah</label>
                    <div class="group relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400 transition-colors group-focus-within:text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l9 6 9-6M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="nama@sekolah.id"
                            class="h-14 w-full rounded-2xl border border-slate-200 bg-slate-50 pl-12 pr-4 text-sm text-slate-800 shadow-sm outline-none transition-all placeholder:text-slate-400 hover:border-blue-300 hover:bg-white focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100">
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-1"/>
                </div>

                <div>
                    <label for="password" class="mb-2 block text-sm font-semibold text-slate-700">Kata Sandi</label>
                    <div class="group relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400 transition-colors group-focus-within:text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 10-8 0v2h8z"/>
                            </svg>
                        </div>
                        <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="Masukkan kata sandi"
                            class="h-14 w-full rounded-2xl border border-slate-200 bg-slate-50 pl-12 pr-4 text-sm text-slate-800 shadow-sm outline-none transition-all placeholder:text-slate-400 hover:border-indigo-300 hover:bg-white focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-100">
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-1"/>
                </div>

                <label class="flex cursor-pointer items-center gap-3 text-sm text-slate-600">
                    <input type="checkbox" name="remember" class="h-4 w-4 rounded border-slate-300 text-blue-600 shadow-sm focus:ring-2 focus:ring-blue-500">
                    <span>Ingat saya</span>
                </label>

                <button type="submit" class="group flex h-14 w-full items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 px-4 font-semibold text-white shadow-lg shadow-blue-200 transition-all hover:-translate-y-0.5 hover:from-blue-700 hover:to-indigo-700 hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-blue-200 active:translate-y-0">
                    <span>Masuk</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </button>
            </form>

            <div class="my-6 flex items-center gap-3" aria-hidden="true">
                <span class="h-px flex-1 bg-slate-200"></span>
                <span class="text-xs font-medium text-slate-400">atau</span>
                <span class="h-px flex-1 bg-slate-200"></span>
            </div>

            <a href="{{ route('auth.google.redirect') }}" class="flex h-14 w-full items-center justify-center gap-3 rounded-2xl border border-slate-200 bg-white px-4 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:bg-slate-50 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                <svg class="h-5 w-5" viewBox="0 0 48 48" aria-hidden="true">
                    <path fill="#FFC107" d="M43.6 24.5c0-1.4-.1-2.8-.4-4.1H24v7.8h11c-.5 2.5-2 4.7-4.2 6.1v5h6.8c4-3.7 6-8.8 6-14.8z"/>
                    <path fill="#FF3D00" d="M24 44c5.5 0 10.1-1.8 13.5-4.8l-6.8-5c-1.9 1.3-4.1 2-6.7 2-5.2 0-9.6-3.5-11.2-8.2H5.8v5.1C9.2 39.6 16.1 44 24 44z"/>
                    <path fill="#4CAF50" d="M12.8 28c-.4-1.2-.7-2.6-.7-4s.3-2.8.7-4v-5.1H5.8C4.3 17.8 3.5 20.8 3.5 24s.8 6.2 2.3 9.1l7-5.1z"/>
                    <path fill="#1976D2" d="M24 11.8c3 0 5.7 1 7.8 3.1l5.8-5.8C34.1 5.8 29.5 4 24 4 16.1 4 9.2 8.4 5.8 14.9l7 5.1c1.6-4.7 6-8.2 11.2-8.2z"/>
                </svg>
                Masuk dengan Google
            </a>

            <p class="mt-7 text-center text-xs text-slate-400">&copy; {{ date('Y') }} TaskGroup. Hak Cipta Dilindungi.</p>
        </section>
    </div>
</x-layouts.guest>
