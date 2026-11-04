<x-layouts.app title="Notifikasi">
    <div class="space-y-4">

        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <h3 class="text-xl font-bold text-slate-900">Semua Notifikasi</h3>
                <p class="mt-1 text-sm text-slate-500">Daftar aktivitas terbaru sistem akademik dan kolaborasi tugas.</p>
            </div>

            @if (auth()->user()->unreadNotifications->isNotEmpty())
                <form method="POST" action="{{ route('notifications.read-all') }}">
                    @csrf
                    <button type="submit" class="shrink-0 text-sm font-semibold text-status-primary hover:underline">
                        Tandai semua dibaca
                    </button>
                </form>
            @endif
        </div>

        @if ($notifications->isEmpty())
            <div class="rounded-2xl border border-slate-100 bg-white px-6 py-10 text-center text-sm text-slate-400 shadow-sm">
                Belum ada notifikasi.
            </div>
        @else
            <div class="space-y-3">
                @foreach ($notifications as $notification)
                    @php
                        $iconStyle = match ($notification->data['type'] ?? null) {
                            'new_task' => ['bg' => 'bg-blue-100 text-blue-600', 'icon' => 'M3 21V8a1 1 0 011-1h4V4a1 1 0 011-1h6a1 1 0 011 1v3h4a1 1 0 011 1v13H3z'],
                            'task_submitted' => ['bg' => 'bg-emerald-100 text-emerald-600', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                            'task_comment' => ['bg' => 'bg-cyan-100 text-cyan-600', 'icon' => 'M8 10h.01M12 10h.01M16 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                            'task_feedback' => ['bg' => 'bg-indigo-100 text-indigo-600', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM4 21v-1a8 8 0 0116 0v1'],
                            'deadline_reminder' => ['bg' => 'bg-red-100 text-red-500', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                            default => ['bg' => 'bg-slate-100 text-slate-500', 'icon' => 'M4 6h16M4 12h10M4 18h16'],
                        };
                    @endphp
                    <div class="overflow-hidden rounded-2xl border {{ $notification->read_at ? 'border-slate-100 bg-white' : 'border-blue-100 bg-white' }} shadow-sm">
                        <form method="POST" action="{{ route('notifications.read', $notification) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="flex w-full items-start gap-3 px-5 py-4 text-left transition hover:bg-slate-50">
                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full {{ $iconStyle['bg'] }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $iconStyle['icon'] }}"/>
                                    </svg>
                                </span>
                                <span class="min-w-0 flex-1 pt-1">
                                    <span class="block text-sm font-semibold text-slate-900">{{ $notification->data['message'] ?? $notification->data['title'] }}</span>
                                    <span class="mt-1 block text-xs text-slate-400">{{ $notification->created_at->diffForHumans() }}</span>
                                </span>
                                @unless ($notification->read_at)
                                    <span class="mt-2 h-2 w-2 shrink-0 rounded-full bg-status-primary"></span>
                                @endunless
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        @endif

        {{ $notifications->links() }}
    </div>
</x-layouts.app>
