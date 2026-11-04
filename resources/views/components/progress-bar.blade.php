@props(['value' => 0, 'showLabel' => true])

@php
    $value = max(0, min(100, (int) $value));

    // Aturan warna progress bar (spesifikasi bagian 10 & 24):
    // 0-39% merah, 40-69% kuning, 70-99% biru, 100% hijau.
    $barColor = match (true) {
        $value >= 100 => 'bg-status-success',
        $value >= 70 => 'bg-status-primary',
        $value >= 40 => 'bg-status-warning',
        default => 'bg-status-danger',
    };
@endphp

<div {{ $attributes->merge(['class' => 'flex items-center gap-3']) }}>
    <div class="h-2.5 w-full flex-1 overflow-hidden rounded-full bg-slate-100">
        <div class="h-full rounded-full {{ $barColor }} transition-all" style="width: {{ $value }}%"></div>
    </div>

    @if ($showLabel)
        <span class="w-10 shrink-0 text-right text-xs font-semibold text-gray-600">{{ $value }}%</span>
    @endif
</div>
