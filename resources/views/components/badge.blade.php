@props(['color' => 'gray'])

@php
    // Palet warna status yang konsisten di seluruh aplikasi (spesifikasi
    // bagian 24): Primary=Blue, Success=Green, Warning=Yellow, Danger=Red,
    // Info=Cyan, Neutral=Gray.
    $styles = [
        'blue' => 'border border-blue-200 bg-blue-50 text-blue-700 ring-1 ring-blue-100',
        'green' => 'border border-emerald-200 bg-emerald-50 text-emerald-700 ring-1 ring-emerald-100',
        'yellow' => 'border border-amber-200 bg-amber-50 text-amber-700 ring-1 ring-amber-100',
        'red' => 'border border-red-200 bg-red-50 text-red-700 ring-1 ring-red-100',
        'cyan' => 'border border-cyan-200 bg-cyan-50 text-cyan-700 ring-1 ring-cyan-100',
        'gray' => 'border border-slate-200 bg-slate-100 text-slate-600 ring-1 ring-slate-100',
    ][$color] ?? 'border border-slate-200 bg-slate-100 text-slate-600 ring-1 ring-slate-100';
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center whitespace-nowrap rounded-full px-2.5 py-1 text-[11px] font-semibold tracking-wide {$styles}"]) }}>
    {{ $slot }}
</span>
