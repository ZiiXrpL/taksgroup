@props(['title', 'subtitle' => null])

<div {{ $attributes->merge(['class' => 'flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between']) }}>
    <div class="min-w-0">
        <h3 class="text-xl font-bold tracking-tight text-slate-900 sm:text-2xl">{{ $title }}</h3>
        @if ($subtitle)
            <p class="mt-1 text-sm text-slate-500">{{ $subtitle }}</p>
        @endif
    </div>
    @isset($actions)
        <div class="flex shrink-0 flex-wrap items-center gap-2">{{ $actions }}</div>
    @endisset
</div>
