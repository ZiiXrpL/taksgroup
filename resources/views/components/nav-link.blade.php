@props(['href', 'active' => false])

@php
    $classes = $active
        ? 'bg-blue-50 text-blue-700 font-semibold ring-1 ring-blue-100'
        : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900';
@endphp

<a href="{{ $href }}" {{ $attributes->merge([
    'class' => "flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm transition-colors duration-150 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-status-primary {$classes}",
]) }}>
    @isset($icon)
        <span class="w-5 shrink-0 text-center">{!! $icon !!}</span>
    @endisset
    <span>{{ $slot }}</span>
</a>
