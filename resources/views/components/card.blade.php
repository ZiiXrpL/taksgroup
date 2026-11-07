@props(['title' => null, 'subtitle' => null, 'padding' => true])

<section {{ $attributes->merge(['class' => 'panel-card']) }}>
    @if ($title || isset($actions))
        <header class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 px-4 py-4 sm:px-6">
            <div class="min-w-0">
                @if ($title)
                    <h3 class="text-base font-bold tracking-tight text-slate-900">{{ $title }}</h3>
                @endif
                @if ($subtitle)
                    <p class="mt-0.5 text-sm text-slate-500">{{ $subtitle }}</p>
                @endif
            </div>
            @isset($actions)
                <div class="flex shrink-0 items-center gap-2">{{ $actions }}</div>
            @endisset
        </header>
    @endif

    <div @class(['p-4 sm:p-6' => $padding])>
        {{ $slot }}
    </div>
</section>
