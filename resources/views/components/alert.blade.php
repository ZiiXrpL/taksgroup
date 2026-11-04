@props(['type' => 'info'])

@php
    $styles = [
            'success' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
            'danger' => 'bg-rose-50 text-rose-800 border-rose-200',
            'warning' => 'bg-amber-50 text-amber-800 border-amber-200',
        'info' => 'bg-cyan-50 text-cyan-800 border-cyan-200',
    ][$type] ?? 'bg-gray-50 text-gray-800 border-gray-200';
@endphp

<div
    x-data="{ show: true }"
    x-show="show"
    x-init="setTimeout(() => show = false, 5000)"
    x-transition
    {{ $attributes->merge(['class' => "flex items-start justify-between gap-3 rounded-xl border px-4 py-3 text-sm shadow-sm {$styles}"]) }}
>
    <div>{{ $slot }}</div>
    <button type="button" @click="show = false" class="shrink-0 text-current/60 hover:text-current" aria-label="Tutup">
        &times;
    </button>
</div>
