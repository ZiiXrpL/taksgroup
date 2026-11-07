@props(['type' => 'info'])

@php
    $styles = [
        'success' => 'bg-emerald-50/90 text-emerald-800 border-emerald-200',
        'danger' => 'bg-rose-50/90 text-rose-800 border-rose-200',
        'warning' => 'bg-amber-50/90 text-amber-800 border-amber-200',
        'info' => 'bg-indigo-50/90 text-indigo-800 border-indigo-200',
    ][$type] ?? 'bg-slate-50 text-slate-800 border-slate-200';
@endphp

<div
    x-data="{ show: true }"
    x-show="show"
    x-init="setTimeout(() => show = false, 6000)"
    x-transition
    role="alert"
    {{ $attributes->merge(['class' => "flex items-start justify-between gap-3 rounded-2xl border px-4 py-3 text-sm shadow-sm {$styles}"]) }}
>
    <div class="min-w-0">{{ $slot }}</div>
    <button type="button" @click="show = false" class="shrink-0 text-lg leading-none text-current/60 hover:text-current" aria-label="Tutup">
        &times;
    </button>
</div>
