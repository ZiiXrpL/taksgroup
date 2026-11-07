@props(['value' => 0, 'showLabel' => true])

@php
    $value = max(0, min(100, (int) $value));

    // Aturan warna progress bar (spesifikasi bagian 10 & 24):
    // 0-39% merah, 40-69% kuning, 70-99% biru, 100% hijau.
    $barColor = match (true) {
        $value >= 100 => 'bg-gradient-to-r from-emerald-500 to-teal-400',
        $value >= 70 => 'bg-gradient-to-r from-indigo-500 to-violet-500',
        $value >= 40 => 'bg-gradient-to-r from-amber-500 to-yellow-400',
        default => 'bg-gradient-to-r from-rose-500 to-orange-400',
    };
@endphp

<div {{ $attributes->merge(['class' => 'flex items-center gap-3']) }}>
    <div class="h-2.5 w-full flex-1 overflow-hidden rounded-full bg-slate-100">
        <div class="h-full rounded-full {{ $barColor }} transition-all" style="width: {{ $value }}%"></div>
    </div>

    @if ($showLabel)
        <span class="w-10 shrink-0 text-right text-xs font-semibold text-slate-600">{{ $value }}%</span>
    @endif
</div>
