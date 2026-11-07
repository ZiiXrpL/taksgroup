@props(['href', 'active' => false])

@php
    $classes = $active
        ? 'bg-gradient-to-r from-indigo-500/25 to-violet-500/10 text-white font-semibold shadow-inner ring-1 ring-white/10'
        : 'text-slate-400 hover:bg-white/5 hover:text-white';
@endphp

<a href="{{ $href }}" @if ($active) aria-current="page" @endif {{ $attributes->merge([
    'class' => "group relative flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm transition-colors duration-150 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-400 {$classes}",
]) }}>
    @if ($active)
        <span class="absolute inset-y-2 left-0 w-1 rounded-r-full bg-indigo-400"></span>
    @endif
    @isset($icon)
        <span class="w-5 shrink-0 text-center {{ $active ? 'text-indigo-300' : 'text-slate-500 group-hover:text-indigo-300' }}">{!! $icon !!}</span>
    @endisset
    <span class="truncate">{{ $slot }}</span>
</a>
