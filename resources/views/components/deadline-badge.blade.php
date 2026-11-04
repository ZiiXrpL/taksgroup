@props(['deadline'])

@php
    // Aturan tampilan deadline & warnanya (spesifikasi bagian 17):
    // > 3 hari -> normal (abu-abu), 1-3 hari -> kuning, hari ini -> merah,
    // sudah lewat -> merah tua.
    $deadline = $deadline instanceof \Illuminate\Support\Carbon
        ? $deadline
        : \Illuminate\Support\Carbon::parse($deadline);

    $now = \Illuminate\Support\Carbon::now();

    if ($now->isSameDay($deadline) && $now->lessThanOrEqualTo($deadline)) {
        $label = 'Deadline hari ini';
        $styles = 'bg-red-50 text-status-danger';
    } elseif ($deadline->isPast()) {
        $lateDays = max(1, $now->diffInDays($deadline));
        $label = "Terlambat {$lateDays} hari";
        $styles = 'bg-red-100 text-red-800';
    } else {
        $daysLeft = (int) ceil($now->floatDiffInDays($deadline));

        $label = match (true) {
            $daysLeft <= 1 => 'Deadline besok',
            default => "Deadline {$daysLeft} hari lagi",
        };

        $styles = $daysLeft <= 3
            ? 'bg-yellow-50 text-status-warning'
            : 'bg-gray-100 text-gray-600';
    }
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center whitespace-nowrap rounded-full px-2.5 py-1 text-xs font-medium {$styles}"]) }}>
    {{ $label }}
</span>
