@props(['title' => 'Belum ada data', 'message' => null])

<div {{ $attributes->merge(['class' => 'flex flex-col items-center justify-center px-4 py-10 text-center']) }}>
    <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-500">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-4l-2 3h-4l-2-3H4"/></svg>
    </div>
    <p class="text-sm font-semibold text-slate-700">{{ $title }}</p>
    @if ($message)
        <p class="mt-1 max-w-sm text-sm text-slate-400">{{ $message }}</p>
    @endif
    {{ $slot }}
</div>
