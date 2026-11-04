@php
    $recentNotifications = auth()->user()->notifications()->latest()->limit(8)->get();
    $unreadCount = auth()->user()->unreadNotifications()->count();
@endphp

<div x-data="{ open: false }" class="relative z-50">
    <button
        @click="open = !open"
        @click.outside="open = false"
        type="button"
        class="relative rounded-xl p-2.5 text-gray-500 transition hover:bg-gray-100"
        aria-label="Notifikasi"
    >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM8.5 16a1.5 1.5 0 003 0h-3z" />
        </svg>

        @if ($unreadCount > 0)
            <span class="absolute right-1 top-1 flex h-4 min-w-[16px] items-center justify-center rounded-full bg-status-danger px-1 text-[10px] font-bold text-white">
                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
            </span>
        @endif
    </button>

    <div
        x-cloak
        x-show="open"
        x-transition
        class="absolute right-0 z-50 mt-2 w-80 max-w-[calc(100vw-2rem)] overflow-hidden rounded-xl border border-gray-200 bg-white shadow-xl sm:right-0"
    >
        <div class="flex items-center justify-between border-b border-gray-100 px-4 py-3">
            <p class="text-sm font-semibold text-gray-900">Notifikasi</p>
            @if ($unreadCount > 0)
                <form method="POST" action="{{ route('notifications.read-all') }}">
                    @csrf
                    <button type="submit" class="text-xs font-semibold text-status-primary hover:underline">
                        Tandai semua dibaca
                    </button>
                </form>
            @endif
        </div>

        <div class="max-h-96 overflow-y-auto">
            @if ($recentNotifications->isEmpty())
                <p class="px-4 py-8 text-center text-sm text-gray-400">Belum ada notifikasi.</p>
            @else
                <ul class="divide-y divide-gray-100">
                    @foreach ($recentNotifications as $notification)
                        <li>
                            <form method="POST" action="{{ route('notifications.read', $notification) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="flex w-full items-start gap-2.5 px-4 py-3 text-left transition hover:bg-gray-50 {{ $notification->read_at ? '' : 'bg-blue-50/60' }}">
                                    <span class="mt-1.5 h-2 w-2 shrink-0 rounded-full {{ $notification->read_at ? 'bg-transparent' : 'bg-status-primary' }}"></span>
                                    <span class="min-w-0 flex-1">
                                        <span class="block text-xs font-semibold text-gray-900">{{ $notification->data['title'] }}</span>
                                        <span class="mt-0.5 block truncate text-xs text-gray-500">{{ $notification->data['message'] }}</span>
                                        <span class="mt-1 block text-[11px] text-gray-400">{{ $notification->created_at->diffForHumans() }}</span>
                                    </span>
                                </button>
                            </form>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <a href="{{ route('notifications.index') }}" class="block border-t border-gray-100 px-4 py-2.5 text-center text-xs font-semibold text-status-primary hover:bg-gray-50">
            Lihat semua notifikasi
        </a>
    </div>
</div>
